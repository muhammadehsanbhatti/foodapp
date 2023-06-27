
<div style="text-align: center">
  <h2 style="font-size: 18px; font-weight: 600; text-transform: uppercase;">{{ config('app.name') }} Login Credentials<h2>
  <div style="font-size: 16px; font-weight: 500;">
    <p style="margin: 25px 0px; line-height: 1.7;">
      <b>Email: </b>{{ $email }}<br>
      <b>Password: </b>{{ $password }}
    </p>
      <a target="_blank" style="background: #007bff; color: white; padding: 15px; border-radius: 25px;" href="{{ route('sp-login') }}"><strong style="font-size: 18px; font-weight: 600;">CLICK HERE TO LOGIN</strong></a>
    
  </div>
</div>