<!-- Modal Dialog -->
<div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ trans('lang.show-ticket-modal-delete-title') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">{{ trans('lang.flash-x') }}</button>
      </div>
      <div class="modal-body">
        <p>{{ trans('lang.show-ticket-modal-delete-message') }}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('lang.btn-cancel') }}</button>
        <button type="button" class="btn btn-danger" id="confirm">{{ trans('lang.btn-delete') }}</button>
      </div>
    </div>
  </div>
</div>
