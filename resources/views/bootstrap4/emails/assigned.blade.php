<?php $notification_owner = unserialize($notification_owner);?>
<?php $ticket = unserialize($ticket);?>

@extends($email)

@section('subject')
	{{ trans('email/globals.assigned') }}
@endsection

@section('link')
	<a style="color:#ffffff" href="{{ route('tickets.show', $ticket->id) }}">
		{{ trans('email/globals.view-ticket') }}
	</a>
@endsection

@section('content')
	{!! trans('email/assigned.data', [
		'name'      =>  $notification_owner->name,
		'subject'   =>  $ticket->subject,
		'status'    =>  $ticket->status->name,
		'category'  =>  $ticket->category->name
	]) !!}
@endsection
