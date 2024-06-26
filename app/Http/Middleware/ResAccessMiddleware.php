<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Agent;
use App\Models\Setting;

class ResAccessMiddleware
{
    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Agent::isAdmin()) {
            return $next($request);
        }

        // All Agents have access in none restricted mode
        if (agent_restrict() == 'no') {
            if (Agent::isAgent()) {
                return $next($request);
            }
        }

        // if this is a ticket show page
        if ($request->route()->getName() == 'tickets.show') {
            $ticket_id = $request->route('tickets');
        }

        // if this is a new comment on a ticket
        if ($request->route()->getName() == 'tickets-comment.store') {
            $ticket_id = $request->get('ticket_id');
        }

        // Assigned Agent has access in the restricted mode enabled
        if (Agent::isAgent() && Agent::isAssignedAgent($ticket_id)) {
            return $next($request);
        }

        // Ticket Owner has access
        if (Agent::isTicketOwner($ticket_id)) {
            return $next($request);
        }

        return redirect()->route('tickets.index')
            ->with('warning', trans('lang.you-are-not-permitted-to-access'));
    }
}
