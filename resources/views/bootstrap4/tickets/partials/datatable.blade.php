<table class="ticket-table table table-striped  dt-responsive nowrap" style="width:100%">
    <thead>
        <tr>
            <td>{{ trans('lang.table-id') }}</td>
            <td>{{trans('lang.ticket-number') }}</td>
            <td>{{ trans('lang.table-app_name') }}</td>
            <td>{{ trans('lang.table-subject') }}</td>
            <td>{{ trans('lang.table-status') }}</td>
            <td>{{ trans('lang.table-last-updated') }}</td>
            {{-- <td>{{ trans('lang.table-agent') }}</td> --}}
            @if( isAgent() || isAdmin() )
              <td>{{ trans('lang.table-priority') }}</td>
              <td>{{ trans('lang.table-owner') }}</td>
              <td>{{ trans('lang.table-category') }}</td>
            @endif
        </tr>
    </thead>
</table>