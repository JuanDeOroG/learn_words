<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn Words</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('head')
    {{-- SweetAlert2 CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <div class="wrapper">
        @if (empty($hideHeader))
            <header>
                <h1>Learn Words</h1>
                <p>Welcome to your vocabulary learning hub!</p>
            </header>
        @endif

        {{-- botón para volver atrás, se oculta si $hideBackButton está definido y es true --}}
        @if (empty($hideBackButton))
            <div style="position:fixed; left:32px; top:40%; z-index:1050;">
                <button class="btn btn-outline-secondary" onclick="window.history.back();">
                    ← Back
                </button>
            </div>
        @endif

        <main>
            @yield('content')
        </main>
        <footer>
            <p>&copy; {{ date('Y') }} Learn Words. All rights reserved.</p>
        </footer>
    </div>
    <script defer src="{{ asset('js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('components.sweet-alert')
    @yield('scripts')

</body>

</html>
