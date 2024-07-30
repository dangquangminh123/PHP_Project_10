<?php
    get_header();
// show_array($list_users);
?>

<head>
    <link rel="stylesheet" href="./public/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="./public/css/login.css" type="text/css" />
</head>
<div id="main-content-wp" class="add-cat-page">
    <div class="wrap clearfix">
        <?php 
            get_sidebar();  
        ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Thêm trang</h3>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <form method="POST" enctype="multipart/form-data">
                        <label for="title">Tiêu đề</label>
                        <input type="text" name="title" id="title">
                        <?php echo form_error('title'); ?>
                        <label for="slug">Slug ( Friendly_url )</label>
                        <input type="text" name="slug" id="slug">
                        <?php echo form_error('slug'); ?>

                        <label for="desc">Mô tả</label>
                        <textarea name="desc" id="desc" class="ckeditor"></textarea>
                        <?php echo form_error('desc'); ?>
                        <label for="status">Trạng thái pages</label>
                        <select name="status">
                            <option value="">--Chọn--</option>
                            <option value="draft" selected="selected">Nháp</option>
                            <option value="published">Công khai</option>
                            <option value="pending">Chờ duyệt</option>
                            <option value="archived">Lưu trữ</option>
                        </select>
                        <label>Hình ảnh</label>
                        <div id="uploadFile">
                            <input type="file" name="file" id="upload-thumb">
                            <!-- <input type="submit" name="btn-upload-thumb" value="Upload" id="btn-upload-thumb"> -->
                            <img src="public/images/img-thumb.png">
                        </div>
                        <?php echo form_error('file'); ?>

                        <button type="submit" name="btn-add" id="btn-submit">Thêm</button>
                        <?php echo form_error('pages'); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    get_footer();
?>