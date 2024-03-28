@extends('bootstrap4.layouts.master')
@section('page', trans('admin.status-edit-title', ['name' => ucwords($status->name)]))

@section('ticket_content')
    <form method="POST" action="{{ route('admin-tickets.status.update', ['status' => $status->id]) }}">
        @csrf
        @method('PATCH')
        @include('bootstrap4.admin.status.form', ['update' => true])
    </form>    
@endsection
