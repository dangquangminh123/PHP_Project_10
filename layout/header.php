<html>

<head>
    <title>Project mvc</title>
    <base href="<?php echo base_url(); ?>" />
    <link rel="stylesheet" href="./public/css/reset.css">
    <link rel="stylesheet" href="./public/css/style.css">
    <script src="./public/js/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="./public/js/app.js" type="text/javascript"></script>
</head>

<body>
    <div id="wrapper">
        <div id="header">

            <?php if(is_login()) {?>
            <div id="user-login">
                <p>Xin chào <strong><?php echo $_SESSION['user_login'] ?></strong>
                    (<a href="?mod=users&action=login">Thoát</a>)
                </p>
            </div>
            <?php } ?>
            <ul id="main-menu">
                <li><a href="?">Trang chủ</a></li>
                <li><a href="??mod=about&controller=index">Giới thiệu</a></li>
                <li><a href="?mod=users&controller=index">Thành viên</a></li>
                <li><a href="?mod=order&controller=index">Đơn hàng</a></li>
            </ul>
        </div>