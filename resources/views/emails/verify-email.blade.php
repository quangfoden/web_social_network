<!DOCTYPE html>
<html>

<head>
    <title>Xác thực Email</title>
</head>

<body>
    <p>Xin chào {{ $userName }},</p>
    <p>Vui lòng nhấp vào liên kết dưới đây để xác thực email của bạn:</p>
    <p><a href="{{ $verificationUrl }}">Xác thực email của tôi</a></p>
</body>

</html>