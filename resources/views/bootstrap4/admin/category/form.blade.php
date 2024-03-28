<div class="form-group">
    <label for="name">{{ trans('admin.category-create-name') . trans('admin.colon') }}</label>
    <input type="text" name="name" class="form-control" value="{{ isset($category->name) ? $category->name : '' }}">
</div>

<div class="form-group">
    <label for="color">{{ trans('admin.category-create-color') . trans('admin.colon') }}</label>
    <input type="color" name="color" class="form-control" value="{{ isset($category->color) ? $category->color : '#000000' }}">
</div>

<a href="{{ route('admin-tickets.category.index') }}" class="btn btn-link">{{ trans('admin.btn-back') }}</a>
@if(isset($category))
    <button type="submit" class="btn btn-primary">{{ trans('admin.btn-update') }}</button>
@else
    <button type="submit" class="btn btn-primary">{{ trans('admin.btn-submit') }}</button>
@endif