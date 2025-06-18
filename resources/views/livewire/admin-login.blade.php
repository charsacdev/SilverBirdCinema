<div>
    <div class="main-content">
        <div class="auth-card">
            <div class="auth-icon">
                <img src="{{asset('vendor/images/user-icon.png')}}">
            </div>

            <h1 class="auth-title">Sign In</h1>
            <p class="auth-subtitle">Fill in the details below to gain access to your account.</p>

            <div class="form-container">
                <form wire:submit.prevent="submit">
                    @csrf {{-- Add CSRF token for security --}}
                    <div class="form-group">
                        <label class="form-label" for="email">Email address</label>
                        <input wire:model.defer="email" type="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email address" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                   <button type="submit" wire:loading.target="submit" wire:loading.attr="disabled" class="btn-signin">
                        <span wire:loading.remove wire:target="submit">Sign In</span>
                        <span wire:loading wire:target="submit">Signing In...</span>
                    </button>

                </form>
            </div>
        </div>
    </div>

    <style>
        /* Add some basic styling for error messages */
        .is-invalid {
            border-color: #e3342f; /* Red border */
        }
        .invalid-feedback {
            color: #e3342f;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        .alert {
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
    </style>
</div>

