@extends('bootstrap4.layouts.master')

@section('page', trans('lang.index-title'))
@section('page_title', trans('lang.index-my-tickets'))


@section('ticket_header')
<a href="{{ route('tickets.create')}}" class="btn btn-primary">{{ trans('lang.btn-create-new-ticket') }}</a>
@endsection

@section('ticket_content_parent_class', 'pl-0 pr-0')

@section('ticket_content')
    <div id="message"></div>
	@include('bootstrap4.tickets.partials.datatable')
@endsection

@section('footer')
	{{-- <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script> --}}
	{{-- <script src="https://cdn.datatables.net/v/bs4/dt-1.10.18/r-2.2.2/datatables.min.js"></script> --}}
	<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
	<script>
	    $('.table').DataTable({
	        processing: false,
	        serverSide: true,
	        responsive: true,
            pageLength: {{ paginate_items() }},
        	lengthMenu: {{ json_encode(length_menu()) }},
	        ajax: '{!! route('tickets.data', $complete) !!}',
	        language: {
				decimal:        "{{ trans('lang.table-decimal') }}",
				emptyTable:     "{{ trans('lang.table-empty') }}",
				info:           "{{ trans('lang.table-info') }}",
				infoEmpty:      "{{ trans('lang.table-info-empty') }}",
				infoFiltered:   "{{ trans('lang.table-info-filtered') }}",
				infoPostFix:    "{{ trans('lang.table-info-postfix') }}",
				thousands:      "{{ trans('lang.table-thousands') }}",
				lengthMenu:     "{{ trans('lang.table-length-menu') }}",
				loadingRecords: "{{ trans('lang.table-loading-results') }}",
				processing:     "{{ trans('lang.table-processing') }}",
				search:         "{{ trans('lang.table-search') }}",
				zeroRecords:    "{{ trans('lang.table-zero-records') }}",
				paginate: {
					first:      "{{ trans('lang.table-paginate-first') }}",
					last:       "{{ trans('lang.table-paginate-last') }}",
					next:       "{{ trans('lang.table-paginate-next') }}",
					previous:   "{{ trans('lang.table-paginate-prev') }}"
				},
				aria: {
					sortAscending:  "{{ trans('lang.table-aria-sort-asc') }}",
					sortDescending: "{{ trans('lang.table-aria-sort-desc') }}"
				},
			},
	        columns: [
	            { data: 'id', name: 'ticket.id' },
	            { data: 'ticket_number', name: 'ticket_number'},
	            { data: 'app_name', name: 'app_name' },
	            { data: 'subject', name: 'subject' },
	            { data: 'status', name: 'ticket_statuses.name' },
	            { data: 'updated_at', name: 'ticket.updated_at' },
            	// { data: 'agent', name: 'users.name' },
	            @if( isAgent() || isAdmin() )
		            { data: 'priority', name: 'ticket_priorities.name' },
	            	{ data: 'owner', name: 'users.name' },
		            { data: 'category', name: 'ticket_categories.name' }
	            @endif
	        ]
	    });
	</script>
@endsection
