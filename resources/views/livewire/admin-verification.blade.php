<div>
    <div class="main-content">
        <div class="auth-card">
            <div class="auth-icon">
                <img src="{{ asset('vendor/images/verify-login.png') }}">
            </div>

            <h1 class="auth-title">Enter Verification Code</h1>
            <p class="auth-subtitle">We've sent a 6-digit code to <strong>{{ $email ?? 'your email' }}</strong>.</p>

            <div class="verification-container">
                <form wire:submit.prevent="verifyCode">
                    @csrf

                    <div class="verification-inputs">
                        {{-- Individual visible inputs --}}
                        @for ($i = 0; $i < 6; $i++)
                            <input type="text" class="verification-input" maxlength="1" data-index="{{ $i }}" inputmode="numeric" pattern="[0-9]*">
                        @endfor

                        {{-- Hidden input bound to Livewire --}}
                        <input type="text" wire:model.defer="authCodeInput" id="combinedAuthCode" style="display: none;">
                    </div>

                    @error('authCodeInput')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

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
                    
                    <button type="submit" class="btn-continue" disabled>Continue</button>

                    <p class="resend-text">
                        Didn't get a code?
                        <a href="#" wire:click.prevent="resendCode" class="resend-link" wire:loading.attr="disabled" wire:target="resendCode">
                            Click to resend
                            <span wire:loading wire:target="resendCode"> (Sending...)</span>
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <style>
        .invalid-feedback {
            color: #e3342f;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        .d-block {
            display: block !important;
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
        .verification-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
        }
        .btn-continue:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</div>
