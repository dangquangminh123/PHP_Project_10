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
                    <h3 id="index" class="fl-left">Cập nhập trang</h3>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <form method="POST" enctype="multipart/form-data">
                        <label for="title">Tiêu đề</label>
                        <input type="text" name="title" id="title" value="<?php echo $info_page['page_title'] ?>">
                        <?php echo form_error('title'); ?>
                        <label for="slug">Slug ( Friendly_url )</label>
                        <input type="text" name="slug" id="slug" value="<?php echo $info_page['page_slug'] ?>">
                        <?php echo form_error('slug'); ?>

                        <label for="desc">Mô tả</label>
                        <textarea name="desc" id="desc" class="ckeditor">
                            <?php echo $info_page['page_content'] ?>
                        </textarea>
                        <?php echo form_error('desc'); ?>

                        <label for="status">Trạng thái pages</label>
                        <select name="status">
                            <option value="">--Chọn--</option>
                            <option value="draft"
                                <?php if(isset($info_page['page_status']) && $info_page['page_status'] == 'draft') echo "selected='selected'" ?>>
                                Nháp</option>
                            <option value="published"
                                <?php if(isset($info_page['page_status']) && $info_page['page_status'] == 'published') echo "selected='selected'" ?>>
                                Công khai</option>
                            <option value="pending"
                                <?php if(isset($info_page['page_status']) && $info_page['page_status'] == 'pending') echo "selected='selected'" ?>>
                                Chờ duyệt</option>
                            <option value="archived"
                                <?php if(isset($info_page['page_status']) && $info_page['page_status'] == 'archived') echo "selected='selected'" ?>>
                                Lưu trữ</option>
                        </select>
                        <?php echo form_error('status'); ?>
                        <label>Hình ảnh</label>
                        <div id="uploadFile">

                            <input type="file" name="file" id="upload-thumb" />
                            <?php if($info_page['images_pages'] != ''){ ?>
                            <img src="<?= $info_page['images_pages']; ?>" class="image" />

                            <?php } ?>
                        </div>
                        <?php echo form_error('file'); ?>

                        <button type="submit" name="btn-update" id="btn-submit">Cập nhập</button>
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