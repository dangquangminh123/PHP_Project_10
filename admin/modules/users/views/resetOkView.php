<html>

<head>
    <title>Thiết lập mật khẩu mới</title>
    <link rel="stylesheet" href="./public/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="./public/css/login.css" type="text/css" />
</head>

<body>
    <div id="wp-form-login">
        <h1 class="page-title">Khôi Phục Mật Khẩu</h1>
        <p>Bạn đã thay đổi mật khẩu thành công, vui lòng click vào link sau để đăng nhập<a
                href="<?php echo base_url("?mod=users&action=login") ?>" id="lost-pass">Đăng nhập ?</a></p>
        <a href="<?php echo base_url("?mod=users&action=reg") ?>" id="lost-pass">Đăng ký</a>
    </div>
</body>

</html>