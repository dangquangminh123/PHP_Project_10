<?php
    get_header();
// show_array($list_users);
?>

<head>
    <link rel="stylesheet" href="./public/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="./public/css/login.css" type="text/css" />
</head>

<script type="text/javascript">
    $(document).ready(function() {
        $('#btn-upload-thumb').on("click", function(e){

            e.preventDefault();
            var files = $('#upload-thumb')[0].files[0];
            var formData = new FormData();
            formData.append('files', files)
            $.ajax({
                url: "?mod=posts&action=AddImagesPost",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    // console.log(res);
                    $("#preview").hide();
                    $("#image_preview").html(res);
                    $("#upload-thumb").val('');
                },
                error: function(error) {
                    $("#image_preview").html(error);
                }
            });
        });

        //Delete Image
        // $(document).on("click", "#delete_btn", function() {
        //     if (confirm("Are you sure you want to remove this image?")) {
        //         var path = $("#delete_btn").data("path");

        //         $.ajax({
        //             url: 'delete.php',
        //             type: "POST",
        //             data: {
        //                 path: path
        //             },
        //             success: function(data) {
        //                 if (data != "") {
        //                     $("#preview").hide();
        //                     $("#image_preview").html('');
        //                 }
        //             }
        //         });
        //     }
        // });
    });
    </script>
<div id="main-content-wp" class="add-cat-page">
    <div class="wrap clearfix">
        <?php 
            get_sidebar();  
        ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Thêm bài viết</h3>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <form method="POST" enctype="multipart/form-data">
                        <label for="post_title">Tiêu đề bài viết</label>
                        <input type="text" name="post_title" id="post_title">
                        <?php echo form_error('post_title'); ?>

                        <label for="post_slug">Slug ( Friendly_url )</label>
                        <input type="text" name="post_slug" id="post_slug">
                        <?php echo form_error('post_slug'); ?>

                        <label for="desc_short">Mô tả ngắn</label>
                        <textarea name="desc_short" id="desc_short" class="ckeditor"></textarea>
                        <?php echo form_error('desc_short'); ?>

                        <label for="content_post">Nội dung bài viết</label>
                        <textarea name="content_post" id="content_post" class="ckeditor"></textarea>
                        <?php echo form_error('content_post'); ?>

                        <label for="post_status">Trạng thái bài viết</label>
                        <select name="post_status">
                            <option value="">--Chọn--</option>
                            <option value="draft" selected="selected">Nháp</option>
                            <option value="published">Công khai</option>
                            <option value="pending">Chờ duyệt</option>
                            <option value="archived">Lưu trữ</option>
                        </select>

                        <label for="category_post">Thuộc danh mục</label>
                        <select name="category_post">
                            <option value="">--Chọn--</option>
                            <?php
                                if(!empty($category_post_format)) {
                                    foreach($category_post_format as $key => $list_category){ 
                            ?>

                            <option value="<?php echo $list_category['category_id'] ?>">
                        <?php echo str_repeat('--', $list_category['level']).$list_category['category_name']; ?>
                            <?php
                                    } 
                                }else { 
                            ?>
                            <option value="">--Chọn--</option>
                            <?php } ?>
                        </select>

                        <label>Hình ảnh</label>
                        <div id="uploadFile">
                                <input type="file" name="files" id="upload-thumb">
                                <input type="button" name="btn-upload-thumb" value="Upload" id="btn-upload-thumb">
                            <span id="preview"><img src="public/images/img-thumb.png"></span>
                            <div id="image_preview"></div>
                            <?php echo form_error('images'); ?>
                        </div>
                       

                        <button type="submit" name="btn-addPost" id="btn-submit">Thêm</button>
                        <?php echo form_error('posts'); ?>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    get_footer();
?>