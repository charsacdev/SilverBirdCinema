<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - SilverBird Cinema</title>
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

          <!--Admin Login-->
          @livewire('admin-login')
    </div>
</body>
  @livewireScripts
</html>