<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Mail</title>
</head>
<body>

<h2 style="font-size: 18px; font-weight: 600;">Dealer Genius Welcomes You<h2>
<div style="font-size: 16px; font-weight: 500;">
<p>Hello {{ $name }}, <br> You have been registered with an employee account under the Company "{{ $company_name }}".<br>
Following are your login details. <br>
Login Email : "{{ $email }}"<br> Password : "{{ $random_pass }}"<br>
<a href="{{ url('/verification_process') }}?vc={{ $verification_code }}"><strong style="font-size: 18px; font-weight: 600;">CLICK HERE TO LOGIN</strong></a>
</p>
</div>
<p style="font-size: 18px; font-weight: 600;">Thank You</p>


</body>
</html>