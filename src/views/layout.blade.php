<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ ENV('APP_NAME') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    @stack('head')
    <style>
        .loader {
            position: fixed;
            inset: 0px;
            z-index: 1050;
            background: rgb(255 255 255 / 71%);
        }
        .loader-img {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>

<body>
    <div class="loader">
        <div class="loader-img">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="ixora-container">
        <div class="container page-body-wrapper">
            <div class="main-panel ixora-head-office">
                <div class="content-wrapper mt-5">
                    {{-- content --}}
                    @yield('content')
                    {{-- /content --}}
                </div>
            </div>
        </div>
    </div>

    {{-- <x-modal />

        <x-theme.script />
        <x-toast /> --}}
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
        <script>
            function loader(show = "show") {
                if (show == "show") {
                    $(".loader").removeClass("d-none");
                } else {
                    $(".loader").addClass("d-none");
                }
            }
            jQuery(document).ready(function () {
                $("body").removeClass("no-scroll");
                $(".loader").addClass("d-none");
            });
        </script>
        @stack('scripts')
</body>

</html>
