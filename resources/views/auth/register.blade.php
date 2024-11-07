@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif

            @if (Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">{{ __('Sign Up Your Business') }}</div>
                <div class="card-body">
                    <form method="POST" id="registerForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                                <h1>Company Logo</h1>
                                <p>Size should be less than 2 mb. </p>
                            <!-- Square Icon Image -->
                            <div class="col-md-4">
                                <label for="square_icon" class="col-form-label">{{ __('Square Icon Image') }}</label>
                                <input id="square_icon" type="file" class="form-control" name="square_icon" >
                                <div class="invalid-feedback print-error-msg print-msg-square_icon" style="display:none"></div>
                            </div>

                            <!-- Primary Background Image -->
                            <div class="col-md-4">
                                <label for="background_image" class="col-form-label">{{ __('Primary Background Image') }}</label>
                                <input id="background_image" type="file" class="form-control" name="background_image" >
                                <div class="invalid-feedback print-error-msg print-msg-background_image" style="display:none"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                                <h1>Basic Details </h1>
                                <p>Foundational information about your business </p>
                            <div class="col-md-4">
                                <label for="name" class="col-form-label">{{ __('Business Name') }} <span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" >
                                <div class="invalid-feedback print-error-msg print-msg-name" style="display:none"></div>
                            </div>

                            <div class="col-md-4">
                                <label for="business_place" class="col-form-label">{{ __('Business Place') }} <span class="text-danger">*</span></label>
                                <input id="business_place" type="text" class="form-control" name="business_place" value="{{ old('business_place') }}" >
                                <div class="invalid-feedback print-error-msg print-msg-business_place" style="display:none"></div>
                            </div>

                            <div class="col-md-4">
                                <label for="primary_category" class="col-form-label">{{ __('Primary Category') }} <span class="text-danger">*</span></label>
                                <select id="primary_category" class="form-control select2" name="primary_category">
                                    <option value="">-- Select Primary Category --</option>
                                    <option value="Retail">Retail</option>
                                    <option value="Service">Service</option>
                                    <option value="Technology">Technology</option>
                                    <option value="Healthcare">Healthcare</option>
                                </select>
                                <div class="invalid-feedback print-error-msg print-msg-primary_category" style="display:none"></div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="business_website" class="col-form-label">{{ __('Business Website') }} <span class="text-danger">*</span></label>
                                <input id="business_website" type="text" class="form-control" name="business_website" value="{{ old('business_website') }}" >
                                <div class="invalid-feedback print-error-msg print-msg-business_website" style="display:none"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="phone_number" class="col-form-label">{{ __('Business Phone Number') }} <span class="text-danger">*</span></label>
                                <input id="phone_number" type="number" class="form-control" name="phone_number" value="{{ old('phone_number') }}" >
                                <div class="invalid-feedback print-error-msg print-msg-phone_number" style="display:none"></div>
                            </div>

                            <div class="col-md-4">
                                <label for="email" class="col-form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" >
                                <div class="invalid-feedback print-error-msg print-msg-email" style="display:none"></div>

                            </div>
                        </div>

                         <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="business_time" class="col-form-label">{{ __('Business Place Time Zone') }} <span class="text-danger">*</span></label>
                                <input id="business_time" type="time" class="form-control" name="business_time" >
                                <div class="invalid-feedback print-error-msg print-msg-business_time" style="display:none"></div>
                            </div>
                        </div>

                        <div class="row mb-3">

                            <div class="col-md-12">
                                <label for="description" class="col-form-label">{{ __('Business Description') }} <span class="text-danger">*</span></label>
                                <input id="description" type="text" class="form-control" name="description" >
                                <div class="invalid-feedback print-error-msg print-msg-description" style="display:none"></div>

                            </div>
                        </div>
                        
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        const authToken = localStorage.getItem('auth_token');
        
        if (!authToken) {
            window.location.href = "{{ route('login') }}";
        }
        $("#registerForm")[0].reset();
        $(document).ready(function() {
            $('#registerForm').submit(function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr("action");
                let formData = new FormData(this);
                $.ajax({
                    url: '/api/register',
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false, 
                    success: function(response) {
                        $("#registerForm")[0].reset();
                        $('body').prepend(`<div class="alert alert-success">${response.message}</div>`);
                        localStorage.removeItem('success_message');
                        localStorage.setItem('auth_token', response.token);
                        setTimeout(() => {
                            window.location.href = '/login';
                        }, 1000);
                    },
                    error: function(data) {
                        $.each(data.responseJSON.errors, function (key, value) {
                            $(".print-msg-" + key + "").css("display", "block");
                            $(".print-msg-" + key + "").html(value[0]);
                        });
                    }
                });
            });
        });
    </script>
@endsection