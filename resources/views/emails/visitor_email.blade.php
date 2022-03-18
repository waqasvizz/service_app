<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Mail</title>
</head>
<body>

<h2 style="font-size: 18px; font-weight: 600;">Dealer Genius Email Verification:<h2>
<div style="font-size: 16px; font-weight: 500;">
<p>Please verify your email to register your account.<br>
  <a href="{{ url('/verification_process') }}?vc={{ $verification_code }}"><strong style="font-size: 18px; font-weight: 600;">CLICK HERE TO VERIFY</strong></a>
</p>
</div>
<p style="font-size: 18px; font-weight: 600;">Thank You</p>


</body>
</html>