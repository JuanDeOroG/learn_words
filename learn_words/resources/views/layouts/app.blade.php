<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn Words</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script defer src="{{ asset('js/script.js') }}"></script>
    @yield('head')
</head>

<body>
    <div class="wrapper">
        <header>
            <h1>Learn Words</h1>
            <p>Welcome to your vocabulary learning hub!</p>
        </header>
        <main>
            @yield('content')
        </main>
    </div>
    <footer>
        <p>&copy; {{ date('Y') }} Learn Words. All rights reserved.</p>
    </footer>
    @yield('scripts')
</body>

</html>