<div class="form-group">
    <label for="name">{{ trans('admin.status-create-name') . trans('admin.colon') }}</label>
    <input type="text" name="name" class="form-control" value="{{ isset($status->name) ? $status->name : '' }}">
</div>
<div class="form-group">
    <label for="color">{{ trans('admin.status-create-color') . trans('admin.colon') }}</label>
    <input type="color" name="color" class="form-control" value="{{ isset($status->color) ? $status->color : '#000000' }}">
</div>

<a href="{{ route('admin-tickets.status.index') }}" class="btn btn-link">{{ trans('admin.btn-back') }}</a>
@if(isset($status))
    <button type="submit" class="btn btn-primary">{{ trans('admin.btn-update') }}</button>
@else
    <button type="submit" class="btn btn-primary">{{ trans('admin.btn-submit') }}</button>
@endif
