<form method="POST" action="{{ route('tickets-comment.store') }}" class="">
    @csrf
    
    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

    <textarea name="content" class="form-control summernote-editor" rows="3"></textarea>

    <button type="submit" class="btn btn-outline-primary pull-right mt-3 mb-3">{{ trans('lang.reply') }}</button>
</form>