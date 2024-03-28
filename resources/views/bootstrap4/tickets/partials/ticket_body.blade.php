<div class="card mb-3">
    <div class="card-body row">
        <div class="col-md-6">
            <p>
                <strong>{{ trans('lang.status') }}</strong>{{ trans('lang.colon') }}
                @if( $ticket->isComplete() )
                    <span style="color: blue">Complete</span>
                @else
                    <span style="color: {{ $ticket->status->color }}">{{ $ticket->status->name }}</span>
                @endif
            </p>
            <p><strong>{{ trans('lang.owner') }}</strong>{{ trans('lang.colon') }}{{ $ticket->user_id == auth()->user()->id ? auth()->user()->name : $ticket->user->name }}</p>
            <p>
                <strong>{{ trans('lang.app_name') }}</strong>{{ trans('lang.colon') }}
                {{ $ticket->app_name }}</p>
            <p>
                <strong>{{ trans('lang.priority') }}</strong>{{ trans('lang.colon') }}
                <span style="color: {{ $ticket->priority->color }}">
                    {{ $ticket->priority->name }}
                </span>
            </p>
        </div>
        <div class="col-md-6">
            <p> <strong>{{ trans('lang.responsible') }}</strong>{{ trans('lang.colon') }}{{ $ticket->agent_id == auth()->user()->id ? auth()->user()->name : $ticket->agent->name }}</p>
            <p>
                <strong>{{ trans('lang.category') }}</strong>{{ trans('lang.colon') }}
                <span style="color: {{ $ticket->category->color }}">
                    {{ $ticket->category->name }}
                </span>
            </p>
            <p> <strong>{{ trans('lang.created') }}</strong>{{ trans('lang.colon') }}{{ $ticket->created_at->diffForHumans() }}</p>
            <p> <strong>{{ trans('lang.last-update') }}</strong>{{ trans('lang.colon') }}{{ $ticket->updated_at->diffForHumans() }}</p>
        </div>
    </div>
</div>

{!! $ticket->html !!}

<form method="POST" action="{{ route('tickets.destroy', $ticket->id) }}" id="delete-ticket-{{ $ticket->id }}">
    @csrf
    @method('DELETE')
</form>


@if(isAgent() || isAdmin())
   @include('bootstrap4.tickets.edit')
@endif

{{-- // OR; Modal Window: 2/2 --}}
@if(isAdmin())
   @include('bootstrap4.tickets.partials.modal-delete-confirm')
@endif
{{-- // END Modal Window: 2/2 --}}
