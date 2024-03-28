@extends('bootstrap4.layouts.master')
@section('page', trans('admin.category-index-title'))
@section('ticket_header')
    <a href="{{ route('admin-tickets.category.create') }}" class="btn btn-primary">{{ trans('admin.btn-create-new-category') }}</a>
@endsection
@section('ticket_content_parent_class', 'p-0')
@section('ticket_content')
    @if ($categories->isEmpty())
        <h3 class="text-center">{{ trans('admin.category-index-no-categories') }}
            <a href="{{ route('admin-tickets.category.create') }}">{{ trans('admin.btn-create-new-category') }}</a>
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
                @foreach ($categories as $category)
                    <tr>
                        <td style="vertical-align: middle">
                            {{ $category->id }}
                        </td>
                        <td style="color: {{ $category->color }}; vertical-align: middle">
                            {{ $category->name }}
                        </td>
                        <td>
                            <a href="{{ route('admin-tickets.category.edit', ['category' => $category->id]) }}" class="btn btn-info">{{ trans('admin.btn-edit') }}</a>
                            <form method="POST" action="{{ route('admin-tickets.category.destroy', ['category' => $category->id]) }}" id="delete-{{ $category->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger deleteit" data-node="{{ $category->name }}">{{ trans('admin.btn-delete') }}</button>
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
            if (confirm("{!! trans('admin.category-index-js-delete') !!}" + $(this).attr("node") + " ?")) {
                var form = $(this).attr("form");
                $("#" + form).submit();
            }
        });
    </script>
@endsection
