<?php $notification_owner = unserialize($notification_owner);?>
<?php $ticket = unserialize($ticket);?>
<?php $original_ticket = unserialize($original_ticket);?>

@extends($email)

@section('subject')
	{{ trans('email/globals.transfer') }}
@endsection

@section('link')
	<a style="color:#ffffff" href="{{ route('tickets.show', $ticket->id) }}">
		{{ trans('email/globals.view-ticket') }}
	</a>
@endsection

@section('content')
	{!! trans('email/transfer.data', [
	    'name'          =>  $notification_owner->name,
	    'subject'       =>  $ticket->subject,
	    'status'        =>  $ticket->status->name,
	    'agent'         =>  $original_ticket->agent->name,
	    'old_category'  =>  $original_ticket->category->name,
	    'new_category'  =>  $ticket->category->name
	]) !!}
@endsection
