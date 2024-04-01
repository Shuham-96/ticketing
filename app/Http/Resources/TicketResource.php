<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'subject' => $this->subject,
            'app_name' => $this->app_name,
            'app_agent_id' => $this->app_agent_id,
            'agency_id' => $this->agency_id,
            'content' => $this->content,
            'html' => $this->html,
            'cc' => $this->cc,
            'status' => $this->when($this->status, function () {
                return [
                    'id' => $this->status->id,
                    'name' => $this->status->name
                ];
                
            }),
            'priority' => $this->when($this->priority, function () {
                return [
                    'id' => $this->priority->id,
                    'name' => $this->priority->name
                ];
            }),
            'category' => $this->when($this->category, function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name
                ];
            }),
            'comments' => $this->comments()->exists() ? $this->comments : [],
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString(),
            'completed_at' => $this->completed_at ? Carbon::parse($this->completed_at)->toDateTimeString() : null
        ];
    }
    
    
}
