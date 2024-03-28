@extends('bootstrap4.layouts.master')
@section('page', trans('admin.priority-edit-title', ['name' => ucwords($priority->name)]))

@section('ticket_content')
    <form method="POST" action="{{ route('admin-tickets.priority.update', ['priority' => $priority->id]) }}">
        @csrf
        @method('PATCH')
        @include('bootstrap4.admin.priority.form', ['update' => true])
    </form>
@endsection
