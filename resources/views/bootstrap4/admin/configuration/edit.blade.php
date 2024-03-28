@extends('bootstrap4.layouts.master')
@section('page', trans('admin.config-edit-subtitle'))
@section('ticket_header')
    <a href="{{ route('admin-tickets.configuration.index') }}" class="btn btn-secondary">{{ trans('admin.btn-back') }}</a>
@endsection
@section('ticket_content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/theme/monokai.min.css">

    <form method="POST" action="{{ route('admin-tickets.configuration.update', ['configuration' => $configuration->id]) }}">
        @csrf
        @method('PATCH')

        <div class="card bg-light mb-3">
            <div class="card-body">
                <b>{{ trans('admin.config-edit-tools') }}</b>
                <br>
                <a href="https://www.functions-online.com/unserialize.html" target="_blank">
                    {{ trans('admin.config-edit-unserialize') }}
                </a>
                <br>
                <a href="https://www.functions-online.com/serialize.html" target="_blank">
                    {{ trans('admin.config-edit-serialize') }}
                </a>
            </div>
        </div>

        @if (trans('ticket::settings.' . $configuration->slug) != 'ticket::settings.' . $configuration->slug &&
                trans('ticket::settings.' . $configuration->slug))
            <div class="card border-info mb-3">
                <div class="card-body">{!! trans('ticket::settings.' . $configuration->slug) !!}</div>
            </div>
        @endif

        <!-- ID Field -->
        <div class="form-group row">
            <label for="id"
                class="col-sm-2 col-form-label">{{ trans('admin.config-edit-id') . trans('admin.colon') }}</label>
            <div class="col-sm-9">
                <input type="text" name="id" class="form-control" value="{{ $configuration->id }}" disabled>
            </div>
        </div>

        <!-- Slug Field -->
        <div class="form-group row">
            <label for="slug"
                class="col-sm-2 col-form-label">{{ trans('admin.config-edit-slug') . trans('admin.colon') }}</label>
            <div class="col-sm-9">
                <input type="text" name="slug" class="form-control" value="{{ $configuration->slug }}" disabled>
            </div>
        </div>

        <!-- Default Field -->
        <div class="form-group row">
            <label for="default"
                class="col-sm-2 col-form-label">{{ trans('admin.config-edit-default') . trans('admin.colon') }}</label>
            <div class="col-sm-9">
                @if (!$default_serialized)
                    <input type="text" name="default" class="form-control" value="{{ $configuration->default }}"
                        disabled>
                @else
                    <pre>{{ var_export(unserialize($configuration->default), true) }}</pre>
                @endif
            </div>
        </div>

        <!-- Value Field -->
        <div class="form-group row">
            <label for="value"
                class="col-sm-2 col-form-label">{{ trans('admin.config-edit-value') . trans('admin.colon') }}</label>
            <div class="col-sm-9">
                @if (!$should_serialize)
                    <input type="text" name="value" class="form-control" value="{{ $configuration->value }}">
                @else
                    <textarea name="value" class="form-control">{{ var_export(unserialize($configuration->value), true) }}</textarea>
                @endif
            </div>
        </div>

        <!-- Serialize Field -->
        <div class="form-group row">
            <label for="serialize"
                class="col-sm-2 col-form-label">{{ trans('admin.config-edit-should-serialize') . trans('admin.colon') }}</label>
            <div class="col-sm-9">
                <input type="checkbox" name="serialize" value="1" class="form-control" onchange="changeSerialize(this)"
                    {{ $should_serialize ? 'checked' : '' }}>
                <span class="form-text" style="color: red;">@lang('ticket::admin.config-edit-eval-warning') <code>eval('$value = serialize(' . $value .
                        ');')</code></span>
            </div>
        </div>

        <!-- Password Field -->
        <div id="serialize-password" class="form-group row">
            <label for="password"
                class="col-sm-2 col-form-label">{{ trans('admin.config-edit-reenter-password') . trans('admin.colon') }}</label>
            <div class="col-sm-9">
                <input type="password" name="password" class="form-control">
            </div>
        </div>

        <!-- Lang Field -->
        <div class="form-group row">
            <label for="lang"
                class="col-sm-2 col-form-label">{{ trans('admin.config-edit-language') . trans('admin.colon') }}</label>
            <div class="col-sm-9">
                <input type="text" name="lang" class="form-control" value="{{ $configuration->lang }}">
            </div>
        </div>

        <!-- Submit Field -->
        <div class="form-group row">
            <div class="col-sm-10 col-sm-offset-2">
                <button type="submit" class="btn btn-primary">{{ trans('admin.btn-submit') }}</button>
            </div>
        </div>

    </form>

    <script>
        function changeSerialize(e) {
            document.querySelector("#serialize-password").style.display = e.checked ? 'flex' : 'none';
            document.querySelector(".form-text").style.display = e.checked ? 'block' : 'none';
        }
        changeSerialize(document.querySelector("input[name='serialize']"));
    </script>
    @if ($should_serialize)
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/codemirror.min.js">
        </script>
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/mode/clike/clike.min.js">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/mode/php/php.min.js">
        </script>
        <script>
            window.addEventListener('load', function() {
                CodeMirror.fromTextArea(document.querySelector("textarea[name='value']"), {
                    lineNumbers: true,
                    mode: 'text/x-php',
                    theme: 'monokai',
                    indentUnit: 2,
                    lineWrapping: true
                });
            });
        </script>
    @endif
@endsection
