<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Interfaces\TicketRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
class TicketController extends Controller
{
    
    private TicketRepositoryInterface $ticketRepositoryInterface;
    
    public function __construct(TicketRepositoryInterface $ticketRepositoryInterface)
    {
        $this->ticketRepositoryInterface = $ticketRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->ticketRepositoryInterface->index();
        return ApiResponseClass::sendResponse(TicketResource::collection($data),'',200);
    }
    /**
     * Display a listing of the resource.
     */
    public function active()
    {
        $data = $this->ticketRepositoryInterface->active();
        return ApiResponseClass::sendResponse(TicketResource::collection($data),'',200);
    }
    /**
     * Display a listing of the resource.
     */
    public function completed()
    {
        $data = $this->ticketRepositoryInterface->completed();
        return ApiResponseClass::sendResponse(TicketResource::collection($data),'',200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $details =[
            'subject' => $request->subject,
            'content' => $request->content,
            'html' => $request->html,
            'status_id' => $request->status_id,
            'priority_id' => $request->priority_id,
            'category_id' => $request->category_id,
            'agent_id' => $request->agent_id,
            'app_agent_id' => $request->app_agent_id,
            'agency_id' => $request->agency_id,
            'app_name' => $request->app_name,
            'user_id' => $request->user_id,
        ];
        DB::beginTransaction();
        try{
            if (Category::where('id', $request->category_id)->exists() && Priority::where('id', $request->priority_id)->exists() && Status::where('id', $request->status_id)->exists()) {
                $ticket = $this->ticketRepositoryInterface->store($details);
                DB::commit();
                return ApiResponseClass::sendResponse(new TicketResource($ticket),'Ticket Create Successful',201);
            }else{
                return ApiResponseClass::rollback('Error: Unable to find master data.');
            }
        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ticket = $this->ticketRepositoryInterface->getById($id);
        return ApiResponseClass::sendResponse(new TicketResource($ticket),'',200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $id)
    {
        DB::beginTransaction();
        try{
            $ticket = $this->ticketRepositoryInterface->update($request->all(), $id);
            DB::commit();
            return ApiResponseClass::sendResponse('Ticket Update Successful','',201);
        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
         $this->ticketRepositoryInterface->delete($id);
        return ApiResponseClass::sendResponse('Ticket Delete Successful','',204);
    }

    protected function PCS()
    {
        $time = 60*60;

        $priorities = Cache::remember('ticket::priorities', $time, function () {
            return $this->ticketRepositoryInterface->priorities();
        });

        $categories = Cache::remember('ticket::categories', $time, function () {
            return $this->ticketRepositoryInterface->categories();
        });

        $statuses = Cache::remember('ticket::statuses', $time, function () {
            return $this->ticketRepositoryInterface->statuses();
        });
        
        return ApiResponseClass::sendResponse(
            [
                'priorities' => $priorities->pluck('name', 'id'),
                'categories' => $categories->pluck('name', 'id'),
                'statuses' => $statuses->pluck('name', 'id')
            ], '', 200);
    }
}