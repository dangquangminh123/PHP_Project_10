<?php
get_header();
// show_array($list_users);
?>

<head>
    <link rel="stylesheet" href="./public/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="./public/css/login.css" type="text/css" />

    <style>

    </style>
</head>

<script type="text/javascript">
    $(document).ready(function() {
        // File upload via Ajax
        $("#btn-upload-thumb").on('click', function(e){

            e.preventDefault();
            var files = $('#upload-thum')[0].files; // Get selected files
            var form_data = new FormData();

            // Append each file to FormData object
            for (var i = 0; i < files.length; i++) {
                form_data.append('files[]', files[i]);
            }
            // alert(form_data);
            $.ajax({
                type: 'POST',
                url: '?mod=products&action=addImagesProduct',
                data: form_data,
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(){
                    // $('#uploadStatus').html('<img src="images/uploading.gif"/>');
                    $('#image_preview').html('Uploading........')
                },
                error:function(err, dt){
                    $('#image_preview').html('<span style="color:#EA4335;">Images upload failed, please try again.<span>');
                },
                success: function(data) {
                    data = JSON.parse(data);
                    let html = '';
                    let selected = '';
                    let imgIds = '';


                    if (data.code === 401) {
                        window.location.href = "?mod=users&action=login";
                    } else if (data.code != 200) {
                        $.each(data.messages, function(key, value) {
                            html += `<p>${value}</p>`;
                        })
                    } else {
                        let default_id = '';

                        $.each(data.data, function(key, value) {
                            if (value.is_thump) {
                                selected = 'selected';
                                default_id = value.image_id
                            }
                            else selected = '';

                            if (imgIds) imgIds += `,`;
                            imgIds += value.image_id;

                            html += `<img src="${value.image_url}" class="product-image ${selected}" data-image-id="${value.image_id}";/> <br>`;
                        })
                        html += `<input id="thumb-image" type="hidden" name="thumb_id" value="${default_id}">`;
                        html += `<input type="hidden" name="image_ids" value="${imgIds}">`
                    }

                    $("#preview").hide();
                    $('#image_preview').html(html);
                }
            });
        });

        // File type validation
        $("#upload-thum").change(function(){
            var fileLength = this.files.length;
            var match= ["image/jpeg","image/png","image/jpg","image/gif"];
            var i;
            for(i = 0; i < fileLength; i++){
                var file = this.files[i];
                var imagefile = file.type;
                if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]) || (imagefile==match[3]))){
                    alert('Please select a valid image file (JPEG/JPG/PNG/GIF).');
                    $("#upload-thum").val('');
                    return false;
                }
            }
        });

        // Ajax set images thumb
        $(document).on('click', '.product-image', function(e) {
            e.preventDefault();

            const el = e.target

            $('.product-image').each(function(index, image) {
                $(image).removeClass('selected');
            });

            $(el).addClass('selected');

            const thumb_id = $(el).data('image-id');

            $('#thumb-image').val(thumb_id);
        })
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
                    <h3 id="index" class="fl-left">Thêm sản phẩm</h3>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <form method="POST" enctype="multipart/form-data">
                        <label for="product_name">Tên sản phẩm</label>
                        <input type="text" name="product_name" id="product_name">
                        <?php echo form_error('product_name'); ?>

                        <label for="product_slug">Slug ( Friendly_url )</label>
                        <input type="text" name="product_slug" id="product_slug" disabled />

                        <label for="product_desc">Sơ lược sản phẩm</label>
                        <textarea name="product_desc" id="product_desc" class="ckeditor"></textarea>
                        <?php echo form_error('product_desc'); ?>

                        <label for="product_details">Mô tả chi tiết sản phẩm</label>
                        <textarea name="product_details" id="product_details" class="ckeditor"></textarea>
                        <?php echo form_error('product_details'); ?>

                        <label for="product_price">Giá sản phẩm</label>
                        <input type="number" name="product_price" id="product_price" />
                        <?php echo form_error('product_price'); ?>

                        <label for="stock_quantity">Số lượng kho</label>
                        <input type="number" name="stock_quantity" id="stock_quantity" />
                        <?php echo form_error('stock_quantity'); ?>

                        <label for="featured">Đặc tính sản phẩm</label>

                                <div class="feature_product">
                                    <div class="featured_item">
                                        <input type="radio" name="featured" value="0" id="0" checked="checked"/>
                                        <label for="0">Nổi bật</label>
                                    </div>

                                    <div class="featured_item">
                                        <input type="radio" name="featured" value="1" id="1" />
                                        <label for="1">Bình thường</label>
                                    </div>
                                </div>

                            <?php echo form_error('featured'); ?>


                        <label for="product_status">Trạng thái sản phẩm</label>
                        <select name="product_status">
                            <option value="">--Chọn--</option>
                            <option value="active" selected="selected">Hoạt động</option>
                            <option value="inactive">Không hoạt động</option>
                            <option value="out_of_stock">Hết hàng</option>
                        </select>

                        <label for="category_product">Thuộc danh mục</label>
                        <select name="category_product">
                            <option value="">--Chọn--</option>
                            <?php
                            if (!empty($category_product_format)) {
                                foreach ($category_product_format as $key => $list_category) {
                            ?>
                            <option value="<?php echo $list_category['category_id'] ?>">
                                <?php echo str_repeat('--', $list_category['level']) . $list_category['category_name']; ?>
                            </option>
                            <?php
                                }
                            } else {
                                ?>
                                    <option value="">--Chọn--</option>
                                <?php
                                }
                                ?>
                        </select>
                        <?php echo form_error('category_product'); ?>
                        <label>Hình ảnh</label>
                        <div id="uploadFile">

                                <label>Choose Images</label>
                                <input type="file" name="files[]" id="upload-thum" multiple />
                                <input type="button" name="btn-upload-thumb" value="Upload" id="btn-upload-thumb" />
                                <span id="preview"><img src="public/images/img-thumb.png"></span>

                        </div>
                        <div id="image_preview"></div>

                        <button type="submit" name="addProduct" id="btn-submit">Thêm</button>
                        <?php echo form_error('products'); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>