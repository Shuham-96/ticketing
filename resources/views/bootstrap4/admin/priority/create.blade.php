@extends('bootstrap4.layouts.master')
@section('page', trans('admin.priority-create-title'))

@section('ticket_content')
    <form method="POST" action="{{ route('admin-tickets.priority.store') }}" class="">
        @csrf
        @include('bootstrap4.admin.priority.form')
    </form>
    
@endsection
