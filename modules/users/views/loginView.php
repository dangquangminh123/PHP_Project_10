<html>

<head>
    <title>Trang đăng nhập</title>
    <link rel="stylesheet" href="./public/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="./public/css/login.css" type="text/css" />
</head>

<body>
    <div id="wp-form-login">
        <h1 class="page-title">Đăng nhập</h1>
        <form id="form-login" action="" method="POST">
            <input type="text" name="username" value="" id="username" placeholder="Username" />
            <?php echo form_error('username'); ?>
            <input type="password" name="password" value="" id="password" placeholder="Password" />
            <?php echo form_error('password'); ?>
            <input type="checkbox" name="remember_me" id="" />Ghi nhớ đăng nhập <br />
            <input type="submit" id="btn-login" name="btn_login" value="Đăng nhập" />
            <?php echo form_error('account'); ?>
        </form>
        <a href="<?php echo base_url("?mod=users&action=reset") ?>" id="lost-pass">Quên mật khẩu ?</a>
        <a href="<?php echo base_url("?mod=users&action=reg") ?>" id="lost-pass">Đăng ký</a>
    </div>
</body>

</html>