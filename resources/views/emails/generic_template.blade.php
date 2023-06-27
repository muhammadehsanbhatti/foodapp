<section style="width: 100%;">
    <div style="
        min-width:600px;
        width:700px;
        min-height: 700px;
        max-height: 100vh;
        background-image: url('http://localhost:8000/storage/default-images/email-background.png');
        background-repeat: no-repeat;       
        background-size: cover;
        background-position: bottom center;
        margin: 10px auto;
        box-sizing: border-box;
        overflow: hidden;
        padding: 10px 20px;
        position:relative;
        ">

        <figure style="display:flex;justify-content:center">
            <img src="http://localhost:8000/storage/default-images/app-logo-email.png" alt="{{ config('app.name') }} Logo" height="100" style="margin: 40px 0;">
        </figure>

        @if ($email_data['email_mode'] == 'welcome_mail')
            <div style="padding: 20px 40px; position:absolute; top: 30%;">
                <p style="font-size:18px;font-family: Arial, Helvetica, sans-serif;color: #FFF;">Hi <b>{{ $email_data['name'] }},</b></p>
                <p style="font-size:18px;font-family: Arial, Helvetica, sans-serif;color: #FFF;">We are happy that you signed up for {{ config('app.name') }}.<br><br>
                <p style="font-size:18px;font-family: Arial, Helvetica, sans-serif;color: #FFF;">Welcome to {{ config('app.name') }}.</p>
                <p style="font-size:16px;font-family: Arial, Helvetica, sans-serif;color: #FFF;">{{ config('app.name') }} Team</p>
                <br><br>
            </div>
        @elseif ($email_data['email_mode'] == 'send_otp')
            <div style="padding: 20px 40px; position:absolute; top: 30%;">
                <p style="font-size:18px;font-family: Arial, Helvetica, sans-serif;color: #FFF;">Hi <b>{{ $email_data['name'] }},</b></p>
                <p style="font-size:18px;font-family: Arial, Helvetica, sans-serif;color: #FFF;">We are very thankful for your interest in {{ config('app.name') }}.<br><br>
                <p style="font-size:18px;font-family: Arial, Helvetica, sans-serif;color: #FFF;">Use this OTP to complete the verification.</p><br>
                <p style="background: #FFF;color: #000000;padding: 4px;border-radius: 15px;width: 110px;text-align: center;">
                    <strong style="font-size: 20px;font-weight: 600;">{{ $email_data['otp_token'] }}</strong>
                </p><br><br>
                <p style="font-size:16px;font-family: Arial, Helvetica, sans-serif;color: #FFF;">{{ config('app.name') }} Team</p>
                <br><br>
            </div>
        @endif

    </div>
</section>