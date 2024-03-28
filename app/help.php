<?php

use App\Models\Ticket;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

function isAdmin()
{
    // return false;
    return auth()->check() && auth()->user()->ticket_admin;
}
function isAgent()
{
    // return false;
    return auth()->check() && auth()->user()->ticket_agent;
}

function paginate_items()
{
    return 10;
}

function length_menu()
{
    return [[10, 50, 100], [10, 50, 100]];
}

function editor_enabled()
{
    return 'yes';
}

function include_font_awesome()
{
    return 'yes';
}

function codemirror_enabled()
{
    return 'yes';
}
function codemirror_theme()
{
    return 'monokai';
}

function fullUrlIs($match)
{
    $url = Request::fullUrl();

    if (Str::is($match, $url)) {
        return true;
    }

    return false;
}

function close_ticket_perm()
{
    return ['owner' => 'yes', 'agent' => 'yes', 'admin' => 'yes'];
}

function reopen_ticket_perm()
{
    return ['owner' => 'yes', 'agent' => 'yes', 'admin' => 'yes'];
}

function delete_modal_type()
{
    return 'builtin';
    // return 'modal';
}

function sortArray($data, $field, $type = 'desc')
{
    uasort($data, function ($a, $b) use ($field, $type) {
        if ($a[$field] == $b[$field]) {
            return 0;
        }
        if ($type == 'desc') {
            return $a[$field] < $b[$field] ? 1 : -1;
        }
        if ($type == 'asc') {
            return $a[$field] > $b[$field] ? 1 : -1;
        }
    });

    return $data;
}
function master_template()
{
    return 'master';
}

function default_close_status_id()
{
    return false;
}

function default_reopen_status_id()
{
    return false;
}

function agent_restrict()
{
    return 'no';
}

function comment_notification()
{
    return true;
}

function status_notification()
{
    return true;
}

function assigned_notification()
{
    return true;
}

function ticketNumberExists($ticket_number)
{
    try {
        return Ticket::where('ticket_number', $ticket_number)->exists();
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return false;
    }
}
function getTicketNumber($app_name , $user_id)
{

    $date = date("Ymd");
    $app_name = strtoupper($app_name);
    do {
        $somerandomNumber = rand(999, 9999);
        $ticket_number = $app_name .'-'.$user_id.'-' .  $date . '-' . $somerandomNumber;
    } while (ticketNumberExists($ticket_number));
    return $ticket_number;
}
