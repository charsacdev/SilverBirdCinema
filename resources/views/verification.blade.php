<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SilverBird-Cinema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('vendor/css/login.css')}}">
    @livewireStyles
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div class="logo-container">
                <div class="logo-text">
                    <img src="{{asset('vendor/images/logo.png')}}">
                </div>
            </div>
            <a href="#" class="support-link">Need help? Contact Support</a>
        </div>

        <!--Verification-->
        @livewire('admin-verification')
    </div>

    <script>
        // Verification code input handling
        const inputs = document.querySelectorAll('.verification-input');
        const continueBtn = document.querySelector('.btn-continue');

        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const value = e.target.value;
                
                // Only allow numbers
                if (!/^\d*$/.test(value)) {
                    e.target.value = '';
                    return;
                }
                
                if (value.length === 1) {
                    input.classList.add('filled');
                    // Move to next input
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                } else {
                    input.classList.remove('filled');
                }
                
                // Check if all inputs are filled
                checkAllFilled();
            });
            
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '' && index > 0) {
                    inputs[index - 1].focus();
                    inputs[index - 1].classList.remove('filled');
                }
            });
            
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = e.clipboardData.getData('text');
                const digits = paste.replace(/\D/g, '').slice(0, 6);
                
                digits.split('').forEach((digit, i) => {
                    if (inputs[i]) {
                        inputs[i].value = digit;
                        inputs[i].classList.add('filled');
                    }
                });
                
                checkAllFilled();
            });
        });

        function checkAllFilled() {
            const allFilled = Array.from(inputs).every(input => input.value.length === 1);
            continueBtn.disabled = !allFilled;

             if (allFilled) {
                    const code = Array.from(inputs).map(input => input.value).join('');
                    
                    const hiddenInput = document.querySelector("#combinedAuthCode");
                    hiddenInput.value = code;

                    // Required for Livewire to detect the change
                    hiddenInput.dispatchEvent(new Event('input'));
                }

        }
    </script>
  </body>
  @livewireScripts
</html>