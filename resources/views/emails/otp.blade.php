<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your OTP Code</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">

    <h2 style="color: #2563eb;">Vaccine Management System</h2>
    <p>Hello <strong>{{ $userName }}</strong>,</p>
    <p>Your login verification code is:</p>

    <div style="background: #f3f4f6; padding: 20px; text-align: center;
                font-size: 36px; font-weight: bold; letter-spacing: 8px;
                color: #1d4ed8; border-radius: 8px; margin: 20px 0;">
        {{ $otp }}
    </div>

    <p>This code expires in <strong>10 minutes</strong>.</p>
    <p style="color: #6b7280; font-size: 12px;">
        If you did not request this, please ignore this email.
    </p>

</body>
</html>