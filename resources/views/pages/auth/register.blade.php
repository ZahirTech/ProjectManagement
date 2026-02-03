<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Project Management System</title>
    <link rel="stylesheet" href="{{ asset('css/authentication.css') }}">
</head>

<body>
    <div class="split-container">
        <!-- Left Side - Branding -->
        <div class="left-side">
            <div class="branding-content">
                <div class="brand-logo">PM</div>
                <h1>Start Managing Your Projects Today</h1>
                <p>Join thousands of teams who trust our platform to deliver projects on time and within budget. Simple,
                    powerful, and designed for success.</p>

                <div class="feature-list">
                    <div class="feature-item">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Free to Get Started</span>
                    </div>
                    <div class="feature-item">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>No Credit Card Required</span>
                    </div>
                    <div class="feature-item">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Setup in Minutes</span>
                    </div>
                    <div class="feature-item">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>24/7 Support Available</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="right-side">
            <div class="form-container">
                <div class="form-header">
                    <h2>Create Account</h2>
                    <p>Fill in your details to get started</p>
                </div>

                @if ($errors->any())
                    <div class="alert-error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form id="registerForm" method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter your full name"
                                value="{{ old('name') }}" required autocomplete="name">
                        </div>

                        <div class="form-group full-width">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email"
                                value="{{ old('email') }}" required autocomplete="email">
                        </div>

                        <div class="form-group full-width">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Create a strong password"
                                required autocomplete="new-password" minlength="6">
                            <small style="color: #718096; font-size: 13px; display: block; margin-top: 5px;">
                                Must be at least 6 characters
                            </small>
                        </div>

                        <div class="form-group full-width">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Re-enter your password" required autocomplete="new-password"
                                minlength="6">
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">Create Account</button>

                    <p class="form-note">
                        Already have an account?
                        <a href="{{ route('login') }}">Sign in here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Add loading state to button on form submit
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('.submit-btn');
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            // Check if passwords match
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }

            btn.classList.add('loading');
            btn.disabled = true;
            btn.textContent = 'Creating Account...';
        });

        // Real-time password match validation
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');

        confirmPasswordInput.addEventListener('input', function() {
            if (this.value && passwordInput.value !== this.value) {
                this.style.borderColor = '#fc8181';
            } else {
                this.style.borderColor = '#e2e8f0';
            }
        });

        // Password strength indicator (optional)
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            if (password.length < 6) {
                this.style.borderColor = '#fc8181';
            } else if (password.length < 10) {
                this.style.borderColor = '#f6ad55';
            } else {
                this.style.borderColor = '#48bb78';
            }
        });
    </script>
</body>

</html>
