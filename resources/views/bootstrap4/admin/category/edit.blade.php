@extends('bootstrap4.layouts.master')
@section('page', trans('admin.category-edit-title', ['name' => ucwords($category->name)]))
@section('ticket_content')
    <form method="POST" action="{{ route('admin-tickets.category.update', ['category' => $category->id]) }}" class="">
        @csrf
        @method('PATCH')
        @include('bootstrap4.admin.category.form', ['update' => true])
    </form>
@endsection
