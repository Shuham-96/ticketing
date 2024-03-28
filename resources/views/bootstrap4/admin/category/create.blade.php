@extends('bootstrap4.layouts.master')
@section('page', trans('admin.category-create-title'))

@section('ticket_content')
    <form method="POST" action="{{ route('admin-tickets.category.store') }}" class="">
        @csrf
        @include('bootstrap4.admin.category.form')
    </form>
    
@endsection
