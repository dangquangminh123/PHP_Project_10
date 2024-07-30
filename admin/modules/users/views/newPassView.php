<html>

<head>
    <title>Thiết lập mật khẩu mới</title>
    <link rel="stylesheet" href="./public/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="./public/css/login.css" type="text/css" />
</head>

<body>
    <div id="wp-form-login">
        <h1 class="page-title">Mật Khẩu Mới</h1>
        <form id="form-login" action="" method="POST">
            <input type="password" name="password" value="" id="password" placeholder="Password" />
            <?php echo form_error('password'); ?>

            <input type="submit" id="btn-login" name="btn-new-pass" value="LƯU MẬT KHẨU" />
            <?php echo form_error('account'); ?>
        </form>
        <a href="<?php echo base_url("?mod=users&action=login") ?>" id="lost-pass">Đăng nhập ?</a>
        <a href="<?php echo base_url("?mod=users&action=reg") ?>" id="lost-pass">Đăng ký</a>
    </div>
</body>

</html>