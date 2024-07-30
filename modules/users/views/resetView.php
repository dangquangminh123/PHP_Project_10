<html>

<head>
    <title>Trang đăng nhập</title>
    <link rel="stylesheet" href="./public/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="./public/css/login.css" type="text/css" />
</head>

<body>
    <div id="wp-form-login">
        <h1 class="page-title">Khôi phục mật khẩu cho Email</h1>
        <form id="form-login" action="" method="POST">
            <input type="text" name="email" value="" id="email" placeholder="Email" />
            <?php echo form_error('email'); ?>

            <input type="submit" id="btn-login" name="btn-reset" value="Gửi Yêu Cầu" />
            <?php echo form_error('account'); ?>
        </form>
    </div>
</body>

</html>