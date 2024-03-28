@extends('bootstrap4.layouts.master')
@section('page', trans('admin.status-create-title'))

@section('ticket_content')
    <form method="POST" action="{{ route('admin-tickets.status.store') }}" class="">
        @csrf
        @include('bootstrap4.admin.status.form')
    </form>
@endsection
