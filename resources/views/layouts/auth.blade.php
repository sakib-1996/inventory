<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        if (document.cookie.indexOf('token') != null) {
            window.location.href = "/dashboard";
        }
    </script>

    <link rel="stylesheet" href="{{ asset('assets/css/toastify.min.css') }}">
    <script src="{{ asset('assets/js/toastify-js.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    {{-- axios --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>

</head>

<body class="hold-transition login-page">

    @yield('auth-content')

</body>

</html>
