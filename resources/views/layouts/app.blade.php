<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <!-- Add any items for the left side of the navbar here -->
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item dropdown" id="auth-links" style="display: none;">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <span id="user-name">Loading...</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#" id="logout-link">
                                    {{ __('Logout') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('profile')}}" >
                                    {{ __('Profile') }}
                                </a>
                            </div>


                        </li>

                        <li class="nav-item" id="register-link" style="display: none;">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
   
    @yield('scripts')

    <script>
        
        let routeName= "{{ Route::currentRouteName() }}";
        $(document).ready(function() {
            const authToken = localStorage.getItem('auth_token');
            
            if (authToken && routeName != 'login') {
                $.ajax({
                    url: '/api/user/profile',
                    type: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + authToken,
                    },
                    success: function(response) {
                        $('#auth-links').show();
                        $('#register-link').hide();
                        $('#user-name').text(response.data.name);
                        $('#userName').text(response.data.name);
                        if(routeName != 'register') {

                            showUserProfileDetails(response);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#auth-links').hide();
                        $('#register-link').show();
                        localStorage.removeItem('auth_token');
                    }
                });
            } else {
                $('#auth-links').hide();
                $('#register-link').show();
            }

            $('#logout-link').on('click', function(e) {
                e.preventDefault();
                
                const authToken = localStorage.getItem('auth_token');
                
                if (authToken) {
                    $.ajax({
                        url: '/api/logout',
                        type: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + authToken
                        },
                        success: function(response) {
                            localStorage.removeItem('auth_token');
                            $('body').prepend(`<div class="alert alert-success">${response.message}</div>`);
                            localStorage.removeItem('success_message');
                            $('#auth-links').hide();
                            $('#register-link').show();
                            
                            setTimeout(() => {
                                
                                window.location.href = '/login';
                            }, 1000);
                        },
                        error: function(xhr, status, error) {
                            console.error('Logout failed:', error);
                        }
                    });
                } else {
                    $('#auth-links').hide();
                    $('#register-link').show();
                }
            });

            if ($('.select2').length) {
                $('.select2').each(function () {
                    $(this).select2({
                        dropdownParent: $(this).parent(),
                    });
                });
            }
        });

        function showUserProfileDetails(response) {
            $('#square_icon_image').attr('src', response.data.square_icon);
            $('#background-image').attr('src', response.data.background_image);
            $('#user-id').val(response.data.id); 
            $('#primary_category').val(response.data.primary_category); 
            $('#name').val(response.data.name); 
            $('#business_place').val(response.data.business_place);
            $('#business_website').val(response.data.business_website);
            $('#phone_number').val(response.data.phone_number); 
            $('#email').val(response.data.email); 
            $('#business_time').val(response.data.business_time); 
            $('#description').val(response.data.description); 
        }
    </script>
</body>
</html>
