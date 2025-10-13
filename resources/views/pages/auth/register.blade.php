@extends('layouts')

@section('content')
    <!-- REGISTER PAGE -->
    <div id="register" class="page">
        <div class="page-header">
            <h1>Add New User</h1>
        </div>

        <div class="form-card">
            <form id="registerForm" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-grid">

                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>

                </div>

                <button type="submit" class="submit-btn">Register</button>

                <p class="form-note mt-3">
                    Already have an account?
                    <a href="{{ route('login') }}">Login here</a>
                </p>
            </form>
        </div>
    </div>
@endsection
