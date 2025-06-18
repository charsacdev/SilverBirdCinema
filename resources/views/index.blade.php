<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - SilverBird Cinema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('vendor/css/login2.css')}}">
    @livewireStyles
</head>
<body>
    <div class="login-container">
        <!-- Header -->
        <header class="login-header">
            <div class="logo-section">
                <div class="logo-text">
                    <img src="{{asset('vendor/images/logo.png')}}">
                </div>
            </div>
            <div class="support-link">
                <span class="support-text">Need help?</span>
                <a href="#" class="contact-support">Contact Support</a>
            </div>
        </header>
          <!--User Login-->
          @livewire('user-login')
    </div>

    <script src="{{asset('vendor/js/login2.js')}}"></script>
</body>
 @livewireScripts
</html>