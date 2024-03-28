<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Agent;
use App\Models\Setting;

class AgentsController extends Controller
{
    public function index()
    {
        $agents = Agent::agents()->get();

        return view('bootstrap4.admin.agent.index', compact('agents'));
    }

    public function create()
    {
        $users = Agent::paginate(paginate_items());

        return view('bootstrap4.admin.agent.create', compact('users'));
    }

    public function store(Request $request)
    {
    	$rules = [
            'agents' => 'required|array|min:1',
        ];

        $rules['agents.*'] = 'integer|exists:users,id';

    	$this->validate($request, $rules);

        $agents_list = $this->addAgents($request->input('agents'));
        $agents_names = implode(',', $agents_list);

        Session::flash('status', trans('lang.agents-are-added-to-agents', ['names' => $agents_names]));

        return redirect()->action('\App\Http\Controllers\AgentsController@index');
    }

    public function update($id, Request $request)
    {
        $this->syncAgentCategories($id, $request);

        Session::flash('status', trans('lang.agents-joined-categories-ok'));

        return redirect()->action('\App\Http\Controllers\AgentsController@index');
    }

    public function destroy($id)
    {
        $agent = $this->removeAgent($id);

        Session::flash('status', trans('lang.agents-is-removed-from-team', ['name' => $agent->name]));

        return redirect()->action('\App\Http\Controllers\AgentsController@index');
    }

    /**
     * Assign users as agents.
     *
     * @param $user_ids
     *
     * @return array
     */
    public function addAgents($user_ids)
    {
        $users = Agent::find($user_ids);
        foreach ($users as $user) {
            $user->ticket_agent = true;
            $user->save();
            $users_list[] = $user->name;
        }

        return $users_list;
    }

    /**
     * Remove user from the agents.
     *
     * @param $id
     *
     * @return mixed
     */
    public function removeAgent($id)
    {
        $agent = Agent::find($id);
        $agent->ticket_agent = false;
        $agent->save();

        // Remove him from tickets categories as well
        if (version_compare(app()->version(), '5.2.0', '>=')) {
            $agent_cats = $agent->categories->pluck('id')->toArray();
        } else { // if Laravel 5.1
            $agent_cats = $agent->categories->lists('id')->toArray();
        }

        $agent->categories()->detach($agent_cats);

        return $agent;
    }

    /**
     * Sync Agent categories with the selected categories got from update form.
     *
     * @param $id
     * @param Request $request
     */
    public function syncAgentCategories($id, Request $request)
    {
        $form_cats = ($request->input('agent_cats') == null) ? [] : $request->input('agent_cats');
        $agent = Agent::find($id);
        $agent->categories()->sync($form_cats);
    }
}