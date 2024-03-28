@extends('bootstrap4.layouts.master')
@section('page', trans('admin.agent-index-title'))
@section('ticket_header')
    <a href="{{ route('admin-tickets.agent.create') }}" class="btn btn-primary">{{ trans('admin.btn-create-new-agent') }}</a>
@endsection
@section('ticket_content_parent_class', 'p-0')
@section('ticket_content')
    @if ($agents->isEmpty())
        <h3 class="text-center">{{ trans('admin.agent-index-no-agents') }}
            <a href="{{ route('admin-tickets.agent.create') }}">{{ trans('admin.agent-index-create-new') }}</a>
        </h3>
    @else
        <div id="message"></div>
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{{ trans('admin.table-id') }}</th>
                    <th>{{ trans('admin.table-name') }}</th>
                    <th>{{ trans('admin.table-categories') }}</th>
                    <th>{{ trans('admin.table-join-category') }}</th>
                    <th>{{ trans('admin.table-remove-agent') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($agents as $agent)
                    <tr>
                        <td>
                            {{ $agent->id }}
                        </td>
                        <td>
                            {{ $agent->name }}
                        </td>
                        <td>
                            @foreach ($agent->categories as $category)
                                <span style="color: {{ $category->color }}">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin-tickets.agent.update', ['agent' => $agent->id]) }}">
                                @csrf
                                @method('PATCH')
                                @foreach (\App\Models\Category::all() as $agent_cat)
                                    <input name="agent_cats[]" type="checkbox" class="form-check-input" value="{{ $agent_cat->id }}" {!! $agent_cat->agents()->where('id', $agent->id)->count() > 0 ? 'checked' : '' !!}> {{ $agent_cat->name }}
                                @endforeach
                                <button type="submit" class="btn btn-info btn-sm">{{ trans('admin.btn-join') }}</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin-tickets.agent.destroy', ['agent' => $agent->id]) }}" id="delete-{{ $agent->id }}">
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
