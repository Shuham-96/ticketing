<?php $notification_owner = unserialize($notification_owner);?>
<?php $original_ticket = unserialize($original_ticket);?>
<?php $ticket = unserialize($ticket);?>

@extends($email)

@section('subject')
	{{ trans('email/globals.status') }}
@endsection

@section('link')
	<a style="color:#ffffff" href="{{ route('tickets.show', $ticket->id) }}">
		{{ trans('email/globals.view-ticket') }}
	</a>
@endsection

@section('content')
	{!! trans('email/status.data', [
	    'name'        =>  $notification_owner->name,
	    'subject'     =>  $ticket->subject,
	    'old_status'  =>  $original_ticket->status->name,
	    'new_status'  =>  $ticket->status->name
	]) !!}
@endsection
