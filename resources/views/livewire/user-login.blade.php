<div>
    <div class="login-form-container">
        <div class="login-form-wrapper">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="user-icon">
                <img src="{{asset('vendor/images/user-icon.png')}}">
            </div>

            <h1 class="login-title">Sign In</h1>
            <p class="login-description">Fill in the details below to gain access to your account.</p>

            <form wire:submit.prevent="login" class="login-form">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-input @error('username') is-invalid @enderror"
                        placeholder="Enter your username"
                        wire:model.defer="username"
                        required
                    >
                    @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    {{-- Use wire:ignore on the wrapper of the password input and toggle --}}
                    <div class="password-input-wrapper" wire:ignore>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input password-input" {{-- Remove @error('password') is-invalid @enderror here for wire:ignore, will handle below --}}
                            placeholder="Enter your password"
                            wire:model.defer="password"
                            required
                        >
                        <button type="button" class="password-toggle" id="passwordToggle">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                    {{-- Display password errors outside wire:ignore, as they are Livewire reactive --}}
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="sign-in-btn" wire:loading.attr="disabled" wire:target="login">
                    <span class="btn-text" wire:loading.remove wire:target="login">Sign in</span>
                    <div class="btn-loader" wire:loading wire:target="login">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
            </form>
        </div>
    </div>

    {{-- The script can remain here, as wire:ignore protects the elements --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordInput = document.getElementById('password');

            if (passwordToggle && passwordInput) {
                passwordToggle.addEventListener('click', function () {
                    
                    const type = passwordInput.type;
                    if(type== 'password'){
                        type="text";
                        this.querySelector('i').classList.toggle('fa-eye');
                    }
                    else{
                        type="password";
                        this.querySelector('i').classList.toggle('fa-eye-slash');
                    }
    
                });
            }
        });
    </script>
</div>