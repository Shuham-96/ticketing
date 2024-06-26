@extends('bootstrap4.layouts.master')
@section('page', trans('lang.create-ticket-title'))
@section('page_title', trans('lang.create-new-ticket'))


@section('ticket_header')
    <a href="{{ route('tickets.index') }}" class="btn btn-link">{{ trans('lang.btn-back') }}</a>
@endsection
@section('ticket_content')
<style>
    .loader{

    }
</style>
    <div id="loader" class="loader"></div>

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="row">
                <div class="col-lg-6">
                    <label for="subject" class="col-form-label">{{ trans('lang.subject') }}</label>
                    <input type="text" name="subject" class="form-control" required autofocus>
                    <small class="form-text text-info">{!! trans('lang.create-ticket-brief-issue') !!}</small>
                </div>
                <div class="col-lg-6">
                    <label for="content"
                        class="col-form-label">{{ trans('lang.description')  }}</label>
                    <textarea name="content" class="form-control summernote-editor" rows="5" required></textarea>
                    <small class="form-text text-info">{!! trans('lang.create-ticket-describe-issue') !!}</small>
                </div>
                <div class="col-lg-6">
                    <label for="priority" class="col-form-label">{{ trans('lang.priority')  }}</label>
                    <select name="priority_id" class="form-control" required>
                        @foreach ($priorities as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6">
                    <label for="category" class="col-form-label">{{ trans('lang.category') }}</label>
                    <select name="category_id" class="form-control" required>
                        @foreach ($categories as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="app_name" class="col-form-label text-md-end">{{ __('App Name') }}</label>
                    <select name="app_name" id="app_name" class="form-control @error('app_name') is-invalid @enderror"
                        required>
                        <option value="">Select App Name</option>
                        <option value="sotc">SOTC</option>
                        <option value="tcil">TCIL</option>
                        <option value="maruti">Maruti</option>
                    </select>
                    @error('app_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="app_agent_id" class="col-form-label text-md-end">{{ __('App Agent') }}</label>
                    <select name="app_agent_id" id="app_agent_id"
                        class="form-control @error('app_agent_id') is-invalid @enderror" required></select>
                    @error('app_agent_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="agency_id" class="col-form-label text-md-end">{{ __('App  Agency') }}</label>
                    <select name="agency_id" id="agency_id" class="form-control @error('agency_id') is-invalid @enderror"
                        required></select>
                    @error('agency_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <input type="hidden" name="agent_id" value="auto">
                </div>
                <div class="col-lg-6">
                    <label for="cc" class="col-form-label">{{ trans('lang.cc') }}</label>
                    <div id="cc-input-container">
                        <div class="input-group mb-3">
                            <input type="text" id="cc" name="cc[]" class="form-control" placeholder="Enter CC email">
                            <div class="input-group-append">
                                <button class="btn btn-success add-field d-block" type="button">Add</button>
                                
                            </div>
                        </div>
                    </div>
                    <small class="form-text text-muted">Enter email addresses separated by spaces for CC.</small>
                </div>
                
                
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-primary float-right mt-4">{{ trans('lang.btn-submit') }}</button>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('footer')
    @include('bootstrap4.tickets.partials.summernote')
    <script>
        $("#app_name").on('change', function() {
            var app_name = $(this).val();
            var agentsSelect = $('#app_agent_id');
            var agenciesSelect = $('#agency_id');
            agenciesSelect.empty();
            agentsSelect.empty();

            $('#loader').show();

            $.ajax({
                url: "{{ route('tickets.getAgentAgencies') }}",
                method: 'GET',
                data: {
                    app_name: app_name
                },
                success: function(response) {
                    if (response.success) {
                        var agents = response.data.agents;
                        $.each(agents, function(index, agent) {
                            agentsSelect.append($('<option>', {
                                value: agent.id,
                                text: agent.name
                            }));
                        });

                        var agencies = response.data.agencies;
                        $.each(agencies, function(index, agency) {
                            agenciesSelect.append($('<option>', {
                                value: agency.id,
                                text: agency.name
                            }));
                        });
                    } else {

                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                },
                complete: function() {
                    $('#loader').hide();
                }
            });
        });
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const container = document.getElementById("cc-input-container");

        // Function to add new input field
        function addInputField() {
            const newInput = document.createElement("div");
            newInput.className = "input-group mb-3";
            newInput.innerHTML = `
                <input type="text" class="form-control" name="cc[]" placeholder="Enter CC email">
                <div class="input-group-append">
                    <button class="btn btn-danger delete-field" type="button">Delete</button>
                </div>
            `;
            container.appendChild(newInput);
            // Hide the Add button for all fields after the first one
            const addButtons = container.querySelectorAll(".add-field");
            addButtons.forEach(function(button) {
                button.style.display = "none";
            });
        }

        // Function to delete input field
        function deleteInputField(inputGroup) {
            container.removeChild(inputGroup);
            // Show the Add button for the previous field when a field is deleted
            const addButtons = container.querySelectorAll(".add-field");
            addButtons.forEach(function(button) {
                button.style.display = "inline-block";
            });
        }

        // Event listener for add button
        container.addEventListener("click", function(event) {
            if (event.target.classList.contains("add-field")) {
                addInputField();
            }
        });

        // Event listener for delete button
        container.addEventListener("click", function(event) {
            if (event.target.classList.contains("delete-field")) {
                const inputGroup = event.target.closest(".input-group");
                deleteInputField(inputGroup);
            }
        });
    });
</script>




    

@endsection
