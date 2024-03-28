@extends('bootstrap4.layouts.master')
@section('page', trans('admin.config-create-subtitle'))
@section('ticket_header')
    <a href="{{ route('admin-tickets.configuration.index') }}" class="btn btn-secondary">{{ trans('admin.btn-back') }}</a>
@endsection
@section('ticket_content')
    <form method="POST" action="{{ route('admin-tickets.configuration.store') }}">
        @csrf
        <!-- Slug Field -->
        <div class="form-group row">
            <label for="slug" class="col-sm-3 col-form-label">{{ trans('admin.config-edit-slug') . trans('admin.colon') }}</label>
            <div class="col-sm-9">
                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
            </div>
        </div>
        <!-- Default Field -->
        <div class="form-group row">
            <label for="default" class="col-sm-3 col-form-label">{{ trans('admin.config-edit-default') . trans('admin.colon') }}</label>
            <div class="col-sm-9">
                <input type="text" name="default" class="form-control" value="{{ old('default') }}">
            </div>
        </div>
        <!-- Value Field -->
        <div class="form-group row">
            <label for="value" class="col-sm-3 col-form-label">{{ trans('admin.config-edit-value') . trans('admin.colon') }}</label>
            <div class="col-sm-9">
                <input type="text" name="value" class="form-control" value="{{ old('value') }}">
            </div>
        </div>
        <!-- Lang Field -->
        <div class="form-group row">
            <label for="lang" class="col-sm-3 col-form-label">{{ trans('admin.config-edit-language') . trans('admin.colon') }}</label>
            <div class="col-sm-9">
                <input type="text" name="lang" class="form-control" value="{{ old('lang') }}">
            </div>
        </div>
        <!-- Submit Field -->
        <div class="form-group row">
            <div class="col-sm-10 offset-sm-3">
                <button type="submit" class="btn btn-primary">{{ trans('admin.btn-submit') }}</button>
            </div>
        </div>
    </form>
@endsection
@section('footer')
    <script>
        $(document).ready(function() {
            $("#slug").bind('change', function() {
                var slugger = $('#slug').val();
                slugger = slugger
                    .replace(/\W/g, '.')
                    .toLowerCase();
                $("#slug").val(slugger);
            });
            $("#default").bind('keyup blur keypress change', function() {
                var duplicate = $('#default').val();
                $("#value").val(duplicate);
            });
        });
    </script>
@endsection
