@extends('bootstrap4.layouts.master')
@section('page', trans('lang.show-ticket-title') . trans('lang.colon') . $ticket->subject)
@section('page_title', $ticket->subject)

@section('ticket_header')
    <div class="d-flex">
        @if (!$ticket->completed_at && $close_perm == 'yes')
            <a href="{!! route('tickets.complete', $ticket->id) !!}" class="btn btn-success">{!! trans('lang.btn-mark-complete') !!}</a>
        @elseif($ticket->completed_at && $reopen_perm == 'yes')
            <a href="{!! route('tickets.reopen', $ticket->id) !!}" class="btn btn-success">{!! trans('lang.reopen-ticket') !!}</a>
        @endif
        @if (isAgent() || isAdmin())
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#ticket-edit-modal">
                {{ trans('lang.btn-edit') }}
            </button>
        @endif
        @if (isAdmin())
            @if (delete_modal_type() == 'builtin')
                <form method="POST" action="{{ route('tickets.destroy', $ticket->id) }}" id="delete-ticket-{{ $ticket->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger deleteit" data-node="{{ $ticket->subject }}">
                        {{ trans('lang.btn-delete') }}
                    </button>
                </form>
            @elseif(delete_modal_type() == 'modal')
                <form method="POST" action="{{ route('tickets.destroy', $ticket->id) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('lang.show-ticket-modal-delete-title', ['id' => $ticket->id]) }}" data-message="{{ trans('lang.show-ticket-modal-delete-message', ['subject' => $ticket->subject]) }}">
                        {{ trans('lang.btn-delete') }}
                    </button>
                </form>
            @endif
        @endif

    </div>
@endsection

@section('ticket_content')
    @include('bootstrap4.tickets.partials.ticket_body')
@endsection

@section('ticket_extra_content')
    <h2 class="mt-5">{{ trans('lang.comments') }}</h2>
    @include('bootstrap4.tickets.partials.comments')
    {{-- pagination --}}
    {!! $comments->render('pagination::bootstrap-4') !!}
    @include('bootstrap4.tickets.partials.comment_form')
@endsection

@section('footer')
    <script>
        $(document).ready(function() {
            $(".deleteit").click(function(event) {
                event.preventDefault();
                if (confirm("{!! trans('lang.show-ticket-js-delete') !!}" + $(this).attr("node") + " ?")) {
                    var form = $(this).attr("form");
                    $("#" + form).submit();
                }

            });
            $('#category_id').change(function() {
                var loadpage = "{!! route('ticketsagentselectlist') !!}/" + $(this).val() + "/{{ $ticket->id }}";
                $('#agent_id').load(loadpage);
            });
            $('#confirmDelete').on('show.bs.modal', function(e) {
                $message = $(e.relatedTarget).attr('data-message');
                $(this).find('.modal-body p').text($message);
                $title = $(e.relatedTarget).attr('data-title');
                $(this).find('.modal-title').text($title);

                // Pass form reference to modal for submission on yes/ok
                var form = $(e.relatedTarget).closest('form');
                $(this).find('.modal-footer #confirm').data('form', form);
            });

            // <!--Form confirm(yes / ok) handler, submits form-- >
                $('#confirmDelete').find('.modal-footer #confirm').on('click', function() {
                    $(this).data('form').submit();
                });
        });
    </script>
    @include('bootstrap4.tickets.partials.summernote')
@endsection
