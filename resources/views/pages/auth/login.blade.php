<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Project Management System</title>
    <link rel="stylesheet" href="{{ asset('css/authentication.css') }}">
</head>

<body>
    <div class="container">
        <div id="login" class="page">
            <div class="page-header">
                <h1>Welcome Back</h1>
                <p>Login to manage your projects</p>
            </div>

            <div class="form-card">
                @if ($errors->any())
                    <div class="alert-error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="submit-btn">Login</button>

                    {{-- <p class="form-note">
                        Don't have an account?
                        <a href="{{ route('register') }}">Register here</a>
                    </p> --}}
                </form>
            </div>
        </div>
    </div>
</body>

</html>
