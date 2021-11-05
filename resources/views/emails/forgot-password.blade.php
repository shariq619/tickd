<h3> Hello! {{ $user }}</h3>
<p>System has got password Reset Request for your account.</p>
<p>if request was not initiated by you please ignore this email and change your password immediately, please find out your password verification code right here : <strong>{{ $code }}</strong></p>
<br>
<strong>Email was Generated at {{ date("F jS Y h:i:s A") }}</strong>

<h4>Best Regards:</h4>
<h4>{{ config('app.name') }}</h4>
