<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>

<h2 style="font-size: 18px; font-weight: 600;">{{ config('app.name') }} Password Reset<h2>
<div style="font-size: 16px; font-weight: 500;">
<p>Your password has been changed successfully.<br>
   Your new password is "{{ $new_password }}" <br>
  <a href="{{ route('login') }}"><strong style="font-size: 18px; font-weight: 600;">CLICK HERE TO LOGIN</strong></a>
</p>
</div>


</body>
</html>