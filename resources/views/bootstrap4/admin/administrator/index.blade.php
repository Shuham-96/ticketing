@extends('bootstrap4.layouts.master')
@section('page', trans('admin.administrator-index-title'))
@section('ticket_header')
    <a href="{{ route('admin-tickets.administrator.create') }}" class="btn btn-primary">{{ trans('admin.btn-create-new-administrator') }}</a>
@endsection
@section('ticket_content_parent_class', 'p-0')
@section('ticket_content')
    @if ($administrators->isEmpty())
        <h3 class="text-center">{{ trans('admin.administrator-index-no-administrators') }}
            <a href="{{ route('admin-tickets.administrator.create') }}">{{ trans('admin.btn-create-new-administrator') }}</a>
        </h3>
    @else
        <div id="message"></div>
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{{ trans('admin.table-id') }}</th>
                    <th>{{ trans('admin.table-name') }}</th>
                    <th>{{ trans('admin.table-remove-administrator') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($administrators as $administrator)
                    <tr>
                        <td>
                            {{ $administrator->id }}
                        </td>
                        <td>
                            {{ $administrator->name }}
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin-tickets.administrator.destroy', ['administrator' => $administrator->id]) }}" id="delete-{{ $administrator->id }}">
                                @csrf
                                @method('DELETE')
                            
                                <button type="submit" class="btn btn-danger">{{ trans('admin.btn-remove') }}</button>
                            </form>                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
