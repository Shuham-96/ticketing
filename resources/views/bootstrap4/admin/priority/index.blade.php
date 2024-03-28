@extends('bootstrap4.layouts.master')
@section('page', trans('admin.priority-index-title'))
@section('ticket_header')
    <a href="{{ route('admin-tickets.priority.create') }}" class="btn btn-primary">{{ trans('admin.btn-create-new-priority') }}</a>
@endsection
@section('ticket_content_parent_class', 'p-0')
@section('ticket_content')
    @if ($priorities->isEmpty())
        <h3 class="text-center">{{ trans('admin.priority-index-no-priorities') }}
            <a href="{{ route('admin-tickets.priority.create') }}">{{ trans('admin.priority-index-create-new') }}</a>
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
                @foreach ($priorities as $priority)
                    <tr>
                        <td style="vertical-align: middle">
                            {{ $priority->id }}
                        </td>
                        <td style="color: {{ $priority->color }}; vertical-align: middle">
                            {{ $priority->name }}
                        </td>
                        <td>
                            <a href="{{ route('admin-tickets.priority.edit', ['priority' => $priority->id]) }}" class="btn btn-info">{{ trans('admin.btn-edit') }}</a>

                            <form method="POST" action="{{ route('admin-tickets.priority.destroy', ['priority' => $priority->id]) }}" id="delete-{{ $priority->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger deleteit" data-node="{{ $priority->name }}">{{ trans('admin.btn-delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
@section('footer')
    <script>
        $(".deleteit").click(function(event) {
            event.preventDefault();
            if (confirm("{!! trans('admin.priority-index-js-delete') !!}" + $(this).attr("node") + " ?")) {
                $form = $(this).attr("form");
                $("#" + $form).submit();
            }
        });
    </script>
@endsection
