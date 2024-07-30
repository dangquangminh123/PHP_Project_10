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
                    <h3 id="index" class="fl-left">Thêm danh mục bài viết</h3>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <form method="POST" enctype="multipart/form-data">
                        <label for="category_name">Tiêu đề danh mục</label>
                        <input type="text" name="category_name" id="category_name" />
                        <?php echo form_error('category_name'); ?>
                        <label for="category_slug">Slug ( Friendly_url ) danh mục</label>
                        <input type="text" name="category_slug" id="category_slug" disabled/>
                        <label for="category_desc">Mô tả danh mục</label>
                        <textarea name="category_desc" id="category_desc" class="ckeditor"></textarea>
                        <?php echo form_error('category_desc'); ?>
                        <label for="parent">Thuộc danh mục cha</label>
                        <select name="parent_category">
                            <option value="">--Chọn--</option>
                            <?php
                                if(!empty($list_parent_category_post)) {
                                    foreach($list_parent_category_post as $key => $list_parent){ 
                            ?>

                            <option value="<?php echo $list_parent['category_id'] ?>">
                                <?php echo $list_parent['category_name'] ?></option>
                            <?php
                                    } 
                                }else { 
                            ?>
                            <option value="">--Chọn--</option>
                            <?php } ?>
                        </select>
                        <button type="submit" name="category-add" id="btn-submit">Thêm</button>
                        <?php echo form_error('category_post'); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    get_footer();
?>