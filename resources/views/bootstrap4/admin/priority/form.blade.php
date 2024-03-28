<div class="form-group">
    <label for="name">{{ trans('admin.priority-create-name') . trans('admin.colon') }}</label>
    <input type="text" name="name" class="form-control" value="{{ isset($priority->name) ? $priority->name : '' }}">
</div>
<div class="form-group">
    <label for="color">{{ trans('admin.priority-create-color') . trans('admin.colon') }}</label>
    <input type="color" name="color" class="form-control" value="{{ isset($priority->color) ? $priority->color : '#000000' }}">
</div>

<a href="{{ route('admin-tickets.priority.index') }}" class="btn btn-link">{{ trans('admin.btn-back') }}</a>

@if(isset($priority))
    <button type="submit" class="btn btn-primary">{{ trans('admin.btn-update') }}</button>
@else
    <button type="submit" class="btn btn-primary">{{ trans('admin.btn-submit') }}</button>
@endif