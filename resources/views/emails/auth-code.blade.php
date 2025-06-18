<x-mail::message>
# Your Login Verification Code

Hello!

Your one-time verification code for login is:

**{{ $authCode }}**

This code is valid for this login only. Please do not share this code with anyone.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>