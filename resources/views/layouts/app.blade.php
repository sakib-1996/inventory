<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        if (document.cookie.indexOf('token') === null) {
            window.location.href = "/login";
        }
    </script>
    <title>Inventory Management</title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/toastify.min.css') }}">
    <script src="{{ asset('assets/js/toastify-js.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>


    {{-- axios --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>

    @yield('custom_css')

</head>

<body>
    <div id="loader" class="LoadingOverlay d-none">
        <div class="Line-Progress">
            <div class="indeterminate"></div>
        </div>
    </div>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        @include('layouts.sidenav-layout')
        <div id="page-content-wrapper">
            @include('layouts.navbar')

            <!--==== page-content =====-->
            @yield('content')

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");

        toggleButton.onclick = function() {
            el.classList.toggle("toggled");
        };
    </script>
</body>

</html>
