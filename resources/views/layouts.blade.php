<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <title>Project Management System</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard_pin.css') }}">
</head>

<body>

    @include('design.includes.sidebar')

    <!-- Main Content -->

    <div class="main-content">
        {{-- @include('design.includes.alert') --}}
        @yield('content')
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/dashboard_pin.js') }}"></script>
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>

</html>
