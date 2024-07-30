<?php
    get_header();
// show_array($list_users);
?>

<head>
    <link rel="stylesheet" href="./public/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="./public/css/login.css" type="text/css" />
</head>
<script type="text/javascript">
    function getUrlParameter(name) {
            name = name.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }
    $(document).ready(function() {
        $('#btn-upload-thumb').on("click", function(e){

            e.preventDefault();
            var id_post = getUrlParameter('post_id');
            var files = $('#upload-thumb')[0].files[0];
            var formData = new FormData();
            formData.append('files', files)
            formData.append('post_id', id_post)
            $.ajax({
                url: "?mod=posts&action=UpdateImagesPost",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    // console.log(res);
                    $("#image_preview .image").remove();
                    $("#image_preview").html(res);
                    $("#upload-thumb").val('');
                },
                error: function(error) {
                    $("#image_preview").html(error);
                }
            });
        });

      
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
                    <h3 id="index" class="fl-left">Chỉnh sửa bài viết</h3>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <form method="POST" enctype="multipart/form-data">
                        <label for="post_title">Tiêu đề bài viết</label>
                        <input type="text" name="post_title" id="post_title" value="<?php echo $info_post['post_title']; ?>">
                        <?php echo form_error('post_title'); ?>

                        <label for="post_slug">Slug ( Friendly_url )</label>
                        <input type="text" name="post_slug" id="post_slug" value="<?php echo $info_post['post_slug']; ?>">
                        <?php echo form_error('post_slug'); ?>

                        <label for="desc_short">Mô tả ngắn</label>
                        <textarea name="desc_short" id="desc_short" class="ckeditor">
                        <?php echo $info_post['post_excerpt'] ?>
                        </textarea>
                        <?php echo form_error('desc_short'); ?>

                        <label for="content_post">Nội dung bài viết</label>
                        <textarea name="content_post" id="content_post" class="ckeditor">
                        <?php echo $info_post['post_content'] ?>
                        </textarea>
                        <?php echo form_error('content_post'); ?>

                        <label for="post_status">Trạng thái bài viết</label>
                        <select name="post_status">
                            <option value="">--Chọn--</option>
                            <option value="draft"
                                <?php if(isset($info_post['post_status']) && $info_post['post_status'] == 'draft') echo "selected='selected'" ?>>
                                Nháp</option>
                            <option value="published"
                                <?php if(isset($info_post['post_status']) && $info_post['post_status'] == 'published') echo "selected='selected'" ?>>
                                Công khai</option>
                            <option value="pending"
                                <?php if(isset($info_post['post_status']) && $info_post['post_status'] == 'pending') echo "selected='selected'" ?>>
                                Chờ duyệt</option>
                            <option value="archived"
                                <?php if(isset($info_post['post_status']) && $info_post['post_status'] == 'archived') echo "selected='selected'" ?>>
                                Lưu trữ</option>
                        </select>
                        <label for="category_post">Thuộc danh mục</label>
                        <select name="category_post">
                            <option value="">--Chọn--</option>
                            <?php
                            // unset($v); 
                            $i =0;
                                foreach($category_post_format as $v) {
                                    $i++;
                            ?>
                             <option value="<?php echo $v['category_id'] ?>"
                             <?php if($info_post['category_id'] == $v['category_id']) echo "selected='selected'"?>>
                             <?php echo str_repeat('--', $v['level']).$v['category_name']; ?>
                            </option>
                            <tr>
                                <td></td>
                            </tr>
                            <?php
                                }
                            ?>
                        </select>

                        <label>Hình ảnh</label>
                        <div id="uploadFile">
                                <input type="file" name="files" id="upload-thumb">
                                <input type="button" name="btn-upload-thumb" value="Upload" id="btn-upload-thumb">
                            
                            <div id="image_preview">
                            <?php if(isset($info_post['image_id'])){ ?>
                                <input type="hidden" name="images_id" value="<?php echo $info_post['image_id'] ?>'"/>
                                <img src="<?= $images_post['image_url']; ?>" class="image" />
                            <?php } ?>
                            </div>
                        </div>
                        <?php echo form_error('images'); ?>

                        <button type="submit" name="btn-update" id="btn-submit">Cập nhập</button>
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