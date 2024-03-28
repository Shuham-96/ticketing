@extends('bootstrap4.layouts.master')

@section('page', trans('admin.status-index-title'))

@section('ticket_header')
    <a href="{{ route('admin-tickets.status.create') }}" class="btn btn-primary">{{ trans('admin.btn-create-new-status') }}</a>
@endsection

@section('ticket_content_parent_class', 'p-0')

@section('ticket_content')
    @if ($statuses->isEmpty())
        <h3 class="text-center">{{ trans('admin.status-index-no-statuses') }}
            <a href="{{ route('admin-tickets.status.create') }}">{{ trans('admin.status-index-create-new') }}</a>
        </h3>
    @else
        <div id="message"></div>
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{{ trans('admin.table-id') }}</th>
                    <th>{{ trans('admin.table-name') }}</th>
                    <th>{{ trans('admin.table-action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($statuses as $status)
                    <tr>
                        <td style="vertical-align: middle">
                            {{ $status->id }}
                        </td>
                        <td style="color: {{ $status->color }}; vertical-align: middle">
                            {{ $status->name }}
                        </td>
                        <td>
                            <a href="{{ route('admin-tickets.status.edit', ['status' => $status->id]) }}" class="btn btn-info">{{ trans('admin.btn-edit') }}</a>
                            <form method="POST" action="{{ route('admin-tickets.status.destroy', ['status' => $status->id]) }}"
                                id="delete-{{ $status->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger deleteit" data-node="{{ $status->name }}">{{ trans('admin.btn-delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <script>
        $(".deleteit").click(function(event) {
            event.preventDefault();
            if (confirm("{!! trans('admin.status-index-js-delete') !!}" + $(this).attr("node") + " ?")) {
                $form = $(this).attr("form");
                $("#" + $form).submit();
            }
        });
    </script>
@endsection
