<div class="modal fade" id="ticket-edit-modal" tabindex="-1" role="dialog" aria-labelledby="ticket-edit-modal-Label">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('tickets.update', $ticket->id) }}" class="form-horizontal">
                @csrf
                @method('PATCH')
            
                <div class="modal-header">
                    <h5 class="modal-title" id="ticket-edit-modal-Label">{{ $ticket->subject }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">{{ trans('lang.flash-x') }}</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="subject" value="{{ $ticket->subject }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control summernote-editor" rows="5" required name="content"
                            cols="50">{!! htmlspecialchars($ticket->html) !!}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="priority_id">{{ trans('lang.priority') . trans('lang.colon') }}</label>
                        <select name="priority_id" class="form-control">
                            @foreach($priority_lists as $key => $value)
                                <option value="{{ $key }}" {{ $ticket->priority_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="agent_id">{{ trans('lang.agent') . trans('lang.colon') }}</label>
                        <select name="agent_id" class="form-control">
                            @foreach($agent_lists as $key => $value)
                                <option value="{{ $key }}" {{ $ticket->agent_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category_id">{{ trans('lang.category') . trans('lang.colon') }}</label>
                        <select name="category_id" class="form-control">
                            @foreach($category_lists as $key => $value)
                                <option value="{{ $key }}" {{ $ticket->category_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status_id">{{ trans('lang.status') . trans('lang.colon') }}</label>
                        <select name="status_id" class="form-control">
                            @foreach($status_lists as $key => $value)
                                <option value="{{ $key }}" {{ $ticket->status_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label for="cc" class="col-form-label">{{ trans('lang.cc') }}</label>
                        <input type="text" id="cc" name="cc" class="form-control" placeholder="Enter CC email" autofocus value="{{ old('cc', is_array($ticket->cc) ? implode(', ', $ticket->cc) : $ticket->cc) }}">
                        <small class="form-text text-muted">Enter email addresses separated by commas for CC.</small>
                    </div>
                    
                    
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('lang.btn-close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ trans('lang.btn-submit') }}</button>
                </div>
            </form>
            
        </div>
    </div>
</div>
