<!DOCTYPE html>
<html>

<head>
    <title>Reset Your Password</title>
</head>

<body>
    <p>Xin chào {{ $userName }},</p>
    <p>Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
    <p><a href="{{ $resetLink }}">Nhấp vào đây để đặt lại mật khẩu của bạn</a></p>
    <p>Nếu bạn không yêu cầu đặt lại mật khẩu, bạn không cần thực hiện thêm hành động nào nữa. Hãy liên hệ ngay với chúng tôi để bảo mật tài khoản của bạn.</p>
</body>

</html>