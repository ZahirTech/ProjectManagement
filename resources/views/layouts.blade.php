<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management System</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>

<body>

    @include('design.includes.sidebar')
    
    <!-- Main Content -->

    <div class="main-content">
        @include('design.includes.alert')
        @yield('content')
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
