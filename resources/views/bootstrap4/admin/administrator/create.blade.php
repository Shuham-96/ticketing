@extends('bootstrap4.layouts.master')
@section('page', trans('admin.administrator-create-title'))

@section('ticket_content')
    @if ($users->isEmpty())
        <h3 class="text-center">{{ trans('admin.administrator-create-no-users') }}</h3>
    @else
        <form method="POST" action="{{ route('admin-tickets.administrator.store') }}" class="">
            @csrf
            <p>{{ trans('admin.administrator-create-select-user') }}</p>
            <table class="table table-hover">
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <div class="form-check form-check-inline">
                                    <input name="administrators[]" type="checkbox" class="form-check-input" value="{{ $user->id }}" {!! $user->ticket_admin ? 'checked' : '' !!}>
                                    <label class="form-check-label">{{ $user->name }}</label>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('admin-tickets.administrator.index') }}" class="btn btn-link">{{ trans('admin.btn-back') }}</a>
            <button type="submit" class="btn btn-primary">{{ trans('admin.btn-submit') }}</button>
        </form>
    @endif
    {!! $users->render('pagination::bootstrap-4') !!}
@endsection
