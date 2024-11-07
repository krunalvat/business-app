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
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" id="loginForm">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                                <div class="invalid-feedback print-error-msg print-msg-email" style="display:none"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">
                                <div class="invalid-feedback print-error-msg print-msg-password" style="display:none"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                <div class="invalid-feedback print-error-msg print-msg-password_confirmation" style="display:none"></div>
                            </div>
                        </div>

                    
                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary login-submit">
                                    {{ __('Login') }}
                                    <div class="spinner-border d-none" role="status">
                                <span class="visually-hidden">Loading...</span>
                                </div></button>
                            </div>
                        </div>
                        <div class="response-message d-none"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#loginForm').submit(function(e) {
                e.preventDefault();

                $(".login-submit").prop("disabled", true);
                $('#loginForm').find(".spinner-border").removeClass("d-none");

                let form = $(this);
                let url = form.attr("action");
                let formData = new FormData(this);
                

                $.ajax({
                    url: '/api/login',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false, 
                    success: function(response) {
                        if(response.success == true){
                            localStorage.setItem('auth_token', response.token);
                            $('body').prepend(`<div class="alert alert-success">${response.message}</div>`);
                            localStorage.removeItem('success_message');
                            $(".login-submit").prop("disabled", false);
                            $('#loginForm').find(".spinner-border").removeClass("d-none");
                            setTimeout(() => {
                                window.location.href = '/home';
                            }, 500);
                            $('.response-message').addClass('d-none');
                        } else {
                            $(".print-error-msg").css("display", "none");
                            $('.response-message').removeClass('d-none').addClass('text-danger text-center');
                            $('.response-message').text(response.message);
                            $(".login-submit").prop("disabled", false);
				            $('#loginForm').find(".spinner-border").addClass("d-none");
                        }
                        
                    },
                    error: function(data) {
                        if(data.responseJSON && data.responseJSON.errors){

                            $.each(data.responseJSON.errors, function (key, value) {
                                $(".print-msg-" + key + "").css("display", "block");
                                $(".print-msg-" + key + "").html(value[0]);
                            });
                            $('.response-message').addClass('d-none');
                            $(".login-submit").prop("disabled", false);
				            $('#loginForm').find(".spinner-border").addClass("d-none");

                        } else {
                            $(".print-error-msg").css("display", "none");
                            $('.response-message').removeClass('d-none').addClass('text-danger text-center');
                            $('.response-message').text(data.responseJSON.message);
                            $(".login-submit").prop("disabled", false);
				            $('#loginForm').find(".spinner-border").addClass("d-none");
                        }
                    }
                });
            });
        });
    </script>
@endsection

