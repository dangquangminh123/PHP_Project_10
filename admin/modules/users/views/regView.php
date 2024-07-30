<html>

<head>
    <title>Trang đăng nhập</title>
    <link rel="stylesheet" href="./public/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="./public/css/login.css" type="text/css" />
</head>

<body>
    <div id="wp-form-login">
        <h1 class="page-title">ĐĂNG KÝ TÀI KHOẢN</h1>
        <form id="form-login" action="" method="POST">
            <input type="text" name="fullname" id="fullname" value="<?php echo set_value('fullname') ?>"
                placeholder="Nhập họ và tên" />
            <?php echo form_error('fullname'); ?>

            <input type="text" name="email" id="email" value="<?php echo set_value('email') ?>"
                placeholder="Email..." />
            <?php echo form_error('email'); ?>

            <input type="text" name="username" id="username" value="<?php echo set_value('username') ?>"
                placeholder="Username..." />
            <?php echo form_error('username'); ?>

            <input type="password" name="password" id="password" value="<?php echo set_value('password') ?>"
                placeholder="Password..." />
            <?php echo form_error('password'); ?>

            <input type="phone" name="phone_number" id="phone_number" value="<?php echo set_value('phone_number') ?>"
                placeholder="Số điện thoại...">
            <?php echo form_error('phone_number'); ?>

            <textarea name="address" id="address" placeholder="Địa chỉ..."><?php echo set_value('address') ?></textarea>
            <?php echo form_error('address'); ?>

            <input type="submit" id="btn-reg" name="btn-reg" value="ĐĂNG KÝ" />
            <?php echo form_error('account'); ?>
        </form>
        <a href="<?php echo base_url("?mod=users&action=login") ?>" id="lost-pass">Đăng nhập</a>
    </div>
</body>

</html>