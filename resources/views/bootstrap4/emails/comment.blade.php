<?php $comment = unserialize($comment);?>
<?php $ticket = unserialize($ticket);?>

@extends($email)

@section('subject')
	{{ trans('email/globals.comment') }}
@endsection

@section('link')
	<a style="color:#ffffff" href="{{ route('tickets.show', $ticket->id) }}">
		{{ trans('email/globals.view-ticket') }}
	</a>
@endsection

@section('content')
	{!! trans('email/comment.data', [
	    'name'      =>  $comment->user->name,
	    'subject'   =>  $ticket->subject,
	    'status'    =>  $ticket->status->name,
	    'category'  =>  $ticket->category->name,
	    'comment'   =>  $comment->getShortContent()
	]) !!}
@endsection
