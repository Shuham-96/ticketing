<?php
namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Middleware\IsAgentMiddleware;
use App\Http\Middleware\ResAccessMiddleware;
use App\Models;
use App\Models\Agent;
use App\Models\Category;
use App\Models\Status;
use App\Models\Ticket;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TicketsController extends Controller
{
    protected $tickets;
    protected $agent;
    public function __construct(Ticket $tickets, Agent $agent)
    {
        $this->middleware(ResAccessMiddleware::class)->only('show');
        $this->middleware(IsAgentMiddleware::class)->only(['edit', 'update']);
        $this->middleware(IsAdminMiddleware::class)->only('destroy');

        $this->tickets = $tickets;
        $this->agent = $agent;
    }
    public function data($complete = false)
    {
        $datatables = app(\Yajra\DataTables\DataTables::class);

        $user = $this->agent->find(auth()->user()->id);

        if (isAdmin()) {
            if ($complete) {
                $collection = Ticket::complete();
            } else {
                $collection = Ticket::active();
            }
        } elseif ($user->isAgent()) {
            if ($complete) {
                $collection = Ticket::complete()->agentUserTickets($user->id);
            } else {
                $collection = Ticket::active()->agentUserTickets($user->id);
            }
        } else {
            if ($complete) {
                $collection = Ticket::userTickets($user->id)->complete();
            } else {
                $collection = Ticket::userTickets($user->id)->active();
            }
        }

        $collection
            ->join('users', 'users.id', '=', 'ticket.user_id')
            ->join('ticket_statuses', 'ticket_statuses.id', '=', 'ticket.status_id')
            ->join('ticket_priorities', 'ticket_priorities.id', '=', 'ticket.priority_id')
            ->join('ticket_categories', 'ticket_categories.id', '=', 'ticket.category_id')
            ->select(['ticket.id','ticket.ticket_number', 'ticket.app_name AS app_name', 'ticket.subject AS subject', 'ticket_statuses.name AS status', 'ticket_statuses.color AS color_status', 'ticket_priorities.color AS color_priority', 'ticket_categories.color AS color_category', 'ticket.id AS agent', 'ticket.updated_at AS updated_at', 'ticket_priorities.name AS priority', 'users.name AS owner', 'ticket.agent_id', 'ticket_categories.name AS category']);
        $collection = $datatables->of($collection);

        $this->renderTicketTable($collection);

        $collection->editColumn('updated_at', function ($data) {
            return date('d-m-Y h:i A', strtotime($data->updated_at));
        });

        $collection->rawColumns(['subject', 'app_name', 'status', 'priority', 'category', 'agent']);
        return $collection->make(true);
    }

    public function renderTicketTable($collection)
    {
        /* $collection->editColumn('subject', function ($ticket) {
            return (string) route(
                'tickets.show',
                $ticket->subject,
                $ticket->id
            );
        }); */
        $collection->editColumn('subject', function ($ticket) {
            return '<a href="' . route('tickets.show', $ticket->id) . '">' . $ticket->subject . '</a>';
        });
        $collection->editColumn('app_name', function ($ticket) {
            return $ticket->app_name;
        });

        $collection->editColumn('status', function ($ticket) {
            $color = $ticket->color_status;
            $status = e($ticket->status);

            return "<div style='color: $color'>$status</div>";
        });

        $collection->editColumn('priority', function ($ticket) {
            $color = $ticket->color_priority;
            $priority = e($ticket->priority);

            return "<div style='color: $color'>$priority</div>";
        });

        $collection->editColumn('category', function ($ticket) {
            $color = $ticket->color_category;
            $category = e($ticket->category);

            return "<div style='color: $color'>$category</div>";
        });

        /* $collection->editColumn('agent', function ($ticket) {
            $ticket = $this->tickets->find($ticket->id);

            return e($ticket->agent->name);
        }); */

        return $collection;
    }

    /**
     * Display a listing of active tickets related to user.
     *
     * @return Response
     */
    public function index()
    {
        $complete = false;

        return view('bootstrap4.index', compact('complete'));
    }

    /**
     * Display a listing of completed tickets related to user.
     *
     * @return Response
     */
    public function indexComplete()
    {
        $complete = true;

        return view('bootstrap4.index', compact('complete'));
    }

    /**
     * Returns priorities, categories and statuses lists in this order
     * Decouple it with list().
     *
     * @return array
     */
    protected function PCS()
    {
        // seconds expected for L5.8<=, minutes before that
        $time = 60 * 60;

        $priorities = Cache::remember('ticket::priorities', $time, function () {
            return Models\Priority::all();
        });

        $categories = Cache::remember('ticket::categories', $time, function () {
            return Models\Category::all();
        });

        $statuses = Cache::remember('ticket::statuses', $time, function () {
            return Models\Status::all();
        });

        return [$priorities->pluck('name', 'id'), $categories->pluck('name', 'id'), $statuses->pluck('name', 'id')];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        [$priorities, $categories] = $this->PCS();

        return view('bootstrap4.tickets.create', compact('priorities', 'categories'));
    }

    /**
     * Store a newly created ticket and auto assign an agent for it.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */

    
    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|min:3',
            'content' => 'required|min:6',
            'priority_id' => 'required|exists:ticket_priorities,id',
            'category_id' => 'required|exists:ticket_categories,id',
        ]);

        $ticket = new Ticket();
        $ticket->ticket_number = getTicketNumber($request->app_name, $request->app_agent_id);


        $ticket->subject = $request->subject;
        $ticket->setPurifiedContent($request->get('content'));
        $ticket->priority_id = $request->priority_id;
        $ticket->category_id = $request->category_id;
        $ticket->status_id = Status::first()->id;
        $ticket->user_id = auth()->user()->id;
        $ticket->autoSelectAgent();
        $ticket->app_agent_id = $request->app_agent_id;
        $ticket->app_name = $request->app_name;
        $ticket->agency_id = $request->agency_id;
        $ticket->save();

        session()->flash('status', trans('lang.the-ticket-has-been-created'));

        return redirect()->route('tickets.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $ticket = $this->tickets->findOrFail($id);

        [$priority_lists, $category_lists, $status_lists] = $this->PCS();

        $close_perm = $this->permToClose($id);
        $reopen_perm = $this->permToReopen($id);

        $cat_agents = Models\Category::find($ticket->category_id)->agents()->agentsLists();
        if (is_array($cat_agents)) {
            $agent_lists = ['auto' => 'Auto Select'] + $cat_agents;
        } else {
            $agent_lists = ['auto' => 'Auto Select'];
        }

        $comments = $ticket->comments()->paginate(paginate_items());

        return view('bootstrap4.tickets.show', compact('ticket', 'status_lists', 'priority_lists', 'category_lists', 'agent_lists', 'comments', 'close_perm', 'reopen_perm'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'subject' => 'required|min:3',
            'content' => 'required|min:6',
            'priority_id' => 'required|exists:ticket_priorities,id',
            'category_id' => 'required|exists:ticket_categories,id',
            'status_id' => 'required|exists:ticket_statuses,id',
            'agent_id' => 'required',
        ]);

        $ticket = $this->tickets->findOrFail($id);

        $ticket->subject = $request->subject;

        $ticket->setPurifiedContent($request->get('content'));

        $ticket->status_id = $request->status_id;
        $ticket->category_id = $request->category_id;
        $ticket->priority_id = $request->priority_id;
        $ticket->app_agent_id = $request->app_agent_id;
        $ticket->app_name = $request->app_name;
        $ticket->agency_id = $request->agency_id;

        if ($request->input('agent_id') == 'auto') {
            $ticket->autoSelectAgent();
        } else {
            $ticket->agent_id = $request->input('agent_id');
        }

        $ticket->save();

        session()->flash('status', trans('lang.the-ticket-has-been-modified'));

        return redirect()->route('tickets.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ticket = $this->tickets->findOrFail($id);
        $subject = $ticket->subject;
        $ticket->delete();

        session()->flash('status', trans('lang.the-ticket-has-been-deleted', ['name' => $subject]));

        return redirect()->route('tickets.index');
    }

    /**
     * Mark ticket as complete.
     *
     * @param int $id
     *
     * @return Response
     */
    public function complete($id)
    {
        if ($this->permToClose($id) == 'yes') {
            $ticket = $this->tickets->findOrFail($id);
            $ticket->completed_at = Carbon::now();

            if (default_close_status_id()) {
                $ticket->status_id = default_close_status_id();
            }

            $subject = $ticket->subject;
            $ticket->save();

            session()->flash('status', trans('lang.the-ticket-has-been-completed', ['name' => $subject]));

            return redirect()->route('tickets.index');
        }

        return redirect()->route('tickets.index')->with('warning', trans('lang.you-are-not-permitted-to-do-this'));
    }

    /**
     * Reopen ticket from complete status.
     *
     * @param int $id
     *
     * @return Response
     */
    public function reopen($id)
    {
        if ($this->permToReopen($id) == 'yes') {
            $ticket = $this->tickets->findOrFail($id);
            $ticket->completed_at = null;

            if (default_reopen_status_id()) {
                $ticket->status_id = default_reopen_status_id();
            }

            $subject = $ticket->subject;
            $ticket->save();

            session()->flash('status', trans('lang.the-ticket-has-been-reopened', ['name' => $subject]));

            return redirect()->route('tickets.index');
        }

        return redirect()->route('tickets.index')->with('warning', trans('lang.you-are-not-permitted-to-do-this'));
    }

    public function agentSelectList($category_id, $ticket_id)
    {
        $cat_agents = Models\Category::find($category_id)->agents()->agentsLists();
        if (is_array($cat_agents)) {
            $agents = ['auto' => 'Auto Select'] + $cat_agents;
        } else {
            $agents = ['auto' => 'Auto Select'];
        }

        $selected_Agent = $this->tickets->find($ticket_id)->agent->id;
        $select = '<select class="form-control" id="agent_id" name="agent_id">';
        foreach ($agents as $id => $name) {
            $selected = $id == $selected_Agent ? 'selected' : '';
            $select .= '<option value="' . $id . '" ' . $selected . '>' . $name . '</option>';
        }
        $select .= '</select>';

        return $select;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function permToClose($id)
    {
        $closePerm = close_ticket_perm();

        if (isAdmin() && $closePerm['admin'] == 'yes') {
            return 'yes';
        }
        if (isAgent() && $closePerm['agent'] == 'yes') {
            return 'yes';
        }
        if ($this->agent->isTicketOwner($id) && $closePerm['owner'] == 'yes') {
            return 'yes';
        }

        return 'no';
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function permToReopen($id)
    {
        $reopenPerm = reopen_ticket_perm();
        if ($this->agent->isAdmin() && $reopenPerm['admin'] == 'yes') {
            return 'yes';
        } elseif ($this->agent->isAgent() && $reopenPerm['agent'] == 'yes') {
            return 'yes';
        } elseif ($this->agent->isTicketOwner($id) && $reopenPerm['owner'] == 'yes') {
            return 'yes';
        }
        return 'no';
    }

    /**
     * Calculate average closing period of days per category for number of months.
     *
     * @param int $period
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function monthlyPerfomance($period = 2)
    {
        $categories = Category::all();
        foreach ($categories as $cat) {
            $records['categories'][] = $cat->name;
        }

        for ($m = $period; $m >= 0; $m--) {
            $from = Carbon::now();
            $from->day = 1;
            $from->subMonth($m);
            $to = Carbon::now();
            $to->day = 1;
            $to->subMonth($m);
            $to->endOfMonth();
            $records['interval'][$from->format('F Y')] = [];
            foreach ($categories as $cat) {
                $records['interval'][$from->format('F Y')][] = round($this->intervalPerformance($from, $to, $cat->id), 1);
            }
        }

        return $records;
    }

    /**
     * Calculate the date length it took to solve a ticket.
     *
     * @param Ticket $ticket
     *
     * @return int|false
     */
    public function ticketPerformance($ticket)
    {
        if ($ticket->completed_at == null) {
            return false;
        }

        $created = new Carbon($ticket->created_at);
        $completed = new Carbon($ticket->completed_at);
        $length = $created->diff($completed)->days;

        return $length;
    }

    /**
     * Calculate the average date length it took to solve tickets within date period.
     *
     * @param $from
     * @param $to
     *
     * @return int
     */
    public function intervalPerformance($from, $to, $cat_id = false)
    {
        if ($cat_id) {
            $tickets = Ticket::where('category_id', $cat_id)
                ->whereBetween('completed_at', [$from, $to])
                ->get();
        } else {
            $tickets = Ticket::whereBetween('completed_at', [$from, $to])->get();
        }

        if (empty($tickets->first())) {
            return false;
        }

        $performance_count = 0;
        $counter = 0;
        foreach ($tickets as $ticket) {
            $performance_count += $this->ticketPerformance($ticket);
            $counter++;
        }
        $performance_average = $performance_count / $counter;

        return $performance_average;
    }

    function getAgentAgencies(Request $request)
    {
        $response = null;
        try {
            $client = new Client();
            if ($request->app_name == 'tcil') {
                $response = $client->request('GET', 'https://traveller.fluede.online/agents-and-agencies');
            }elseif ($request->app_name == 'sotc') {
                $response = $client->request('GET', 'https://traveller.fluede.online/agents-and-agencies');
            }elseif ($request->app_name == 'maruti') {
                $response = $client->request('GET', 'https://traveller.fluede.online/agents-and-agencies');
            }
            
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Error decoding JSON response');
            }
            return ApiResponseClass::sendResponse($data, 'Agents and agencies fetched successfully', 200);
        } catch (RequestException $e) {
            return ApiResponseClass::throw($e, 'Failed to fetch data: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return ApiResponseClass::throw($e, 'Failed to fetch data: ' . $e->getMessage(), 500);
        }
    }
}
