<?php

namespace App\Repositories;
use App\Models\Ticket;
use App\Interfaces\TicketRepositoryInterface;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use Carbon\Carbon;

class TicketRepository implements TicketRepositoryInterface
{
   public function index($agencyId, $appAgentId, $appName)
    {
        // Fetch data based on conditions
        $data = Ticket::where('agency_id', $agencyId)
                      ->where('app_agent_id', $appAgentId)
                      ->where('app_name', $appName)
                      ->get();

        return $data;
    }

   public function active(){
      return Ticket::active()->get();
   }
   public function completed(){
      return Ticket::complete()->get();
   }

   public function getById($id){
      return Ticket::findOrFail($id);
   }

   public function store(array $data){
      return Ticket::create($data);
   }

   public function update(array $data,$id){
      return Ticket::whereId($id)->update($data);
   }
   
   public function delete($id){
      Ticket::destroy($id);
   }

   public function priorities(){
      return Priority::all();
   }
   public function complete($id){
      return Ticket::whereId($id)->update(['completed_at' => Carbon::now()]);
   }
   public function reopen($id){
      return Ticket::whereId($id)->update(['completed_at' => null]);
   }


   public function categories(){
      return Category::all();
   }

   public function statuses(){
      return Status::all();
   }
}