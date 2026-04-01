<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Batatais Serviços'))</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Page-specific styles -->
    @stack('styles')
</head>

<body>
    @if(!in_array(request()->route()->getName(), ['login', 'verification.verify', 'verification.notice', 'verification.send', 'register', 'password.confirm', 'password.request', 'password.reset']))
        @include('layouts.navigation')
    @endif

    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @if(!in_array(request()->route()->getName(), ['login', 'verification.verify', 'verification.notice', 'verification.send', 'register', 'password.confirm', 'password.request', 'password.reset']))
        <!-- Footer -->
        @include('layouts.footer')
    @endif

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- Page-specific scripts -->
    @stack('scripts')
</body>

</html>
