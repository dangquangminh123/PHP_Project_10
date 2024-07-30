<?php get_header(); ?>

<div id="main-content-wp" class="list-post-page">
    <div class="wrap clearfix">
        <?php 
            get_sidebar();
        ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Danh sách sản phẩm</h3>
                    <a href="?mod=products&action=AddProduct" title="" id="add-new" class="fl-left">Thêm mới</a>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <div class="filter-wp clearfix">
                        <ul class="post-status fl-left">
                            <li class="all"><a href="?mod=products&action=index" class="active">All
                                    <span class="count">(<?php echo count($all_product);?>)</span></a> |
                            </li>
                            <li class="all"><a href="?mod=products&action=index&products_status=active">Active
                                    <span class="count">(<?php echo count($list_product_active);?>)</span></a> |
                            </li>
                            <li class="all"><a href="?mod=products&action=index&products_status=inactive">Inactive
                                    <span class="count">(<?php echo count($list_product_inactive);?>)</span></a> |
                            </li>
                            <li class="all"><a href="?mod=products&action=index&products_status=out_of_stock">Out of stock
                                    <span class="count">(<?php echo count($list_product_outofstock);?>)</span></a> |
                            </li>
                        </ul>
                        <form method="GET" class="form-s fl-right">
                            <input type="text" name="s" id="s">
                            <input type="submit" name="sm_s" value="Tìm kiếm">
                        </form>
                    </div>
                  
                    <div class="table-responsive">
                        <?php
                            if(!empty($list_product)) {
                        ?>
                        <table class="table list-table-wp">
                            <thead>
                                <tr>
                                    <td><span class="thead-text">STT</span></td>
                                    <td><span class="thead-text">Tên</span></td>
                                    <td><span class="thead-text">Slug</span></td>
                                    <td class="w-10"><span class="thead-text">Mô tả ngắn</span></td>
                                    <td class="w-20"><span class="thead-text">Chi tiết</span></td>
                                    <td><span class="thead-text">Giá</span></td>
                                    <td><span class="thead-text">Số Lượng</span></td>
                                    <td><span class="thead-text">Đặc trưng</span></td>
                                    <td><span class="thead-text">Trạng thái</span></td>
                                    <td><span class="thead-text">Người tạo</span></td>
                                    <td><span class="thead-text">Danh mục</span></td>
                                    <td><span class="thead-text">Hình ảnh</span></td>
                                    <td><span class="thead-text">Thời gian</span></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // $temp=$start; 
                                    foreach($list_product as $item) { 
                                    // $temp++;
                                ?>
                                <tr>
                                    <td><span class="tbody-text"><?php echo $item['product_id']; ?></span>
                                    <td class="clearfix">
                                        <div class="tb-title fl-left">
                                            <span class="post_title"><?php echo $item['product_name']; ?></span>
                                        </div>
                                        <ul class="list-operation fl-right">
                                            <li><a href="<?php echo $item['url_update']; ?>" title="Sửa"
                                                    class="edit edit_page"><i class="fa fa-pencil"
                                                        aria-hidden="true"></i></a></li>
                                            <li><a href="<?php echo $item['url_delete']; ?>" title="Xóa"
                                                    class="delete delete_page"><i class="fa fa-trash"
                                                        aria-hidden="true"></i></a>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <span class="postslug">
                                            <?php echo $item['product_slug']; ?>
                                        </span>
                                    </td>
                                    <td class="w-10">
                                        <span class="tbody-text tb-excerpt"><?php echo $item['product_desc']; ?></span>
                                    </td>
                                    <td class="w-20">
                                        <span class="tbody-text tb-content"><?php echo $item['product_details']; ?></span>
                                    </td>
                                    <td>
                                        <span class="tbody-text"><?php echo currency_format($item['product_price']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="tbody-text"><?php echo $item['stock_quantity']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="tbody-text"><?php echo show_is_featured($item['is_featured']); ?>
                                        </span>
                                    </td>
                                    <td><span class="tbody-text"><?php echo $item['product_status']; ?></span></td>
                                    <?php
                                        foreach($user_product as $user) { 
                                        if($user['user_id'] === $item['user_id']) {
                                    ?>
                                    <td>
                                        <span class="tbody-text"><?php echo pages_author($user['username']); ?>
                                        </span>
                                    </td>
                                    <?php       
                                        break;        
                                            }
                                        } 
                                    ?>
                                    <?php
                                        foreach($category_product as $category) { 
                                        if($category['category_id'] === $item['category_id']) {
                                    ?>
                                    <td>
                                        <span class="tbody-text"><?php echo $category['category_name']; ?>
                                        </span>
                                    </td>
                                    <?php       
                                        break;        
                                            }
                                        } 
                                    ?>
                                    <?php
                                        foreach($product_images as $images) { 
                                        if($images['product_id'] === $item['product_id']) {
                                    ?>
                                    <td>
                                        <span class="tbody-text tb-images">
                                            <a href="<?php $images['image_url'] ?>" title="" class="thumb">
                                                <img src="<?php echo $images['image_url'] ?>" alt=""/>
                                            </a>
                                        </span>
                                    </td>
                                    <?php       
                                        break;        
                                            }
                                        } 
                                    ?>
                                   
                                   <td>
                                        <span class="tbody-text"><?php echo format_time($item['created_at']); ?></span>
                                   </td>
                                </tr>
                                <?php 
                                    }
                                ?>
                            </tbody>
                        </table>
                        <?php } else { ?>

                        <span>Hiện tại chưa có sản phẩm nào!</span>
                        <?php } ?>
                    </div>

                </div>
            </div>
            <div class="section" id="paging-wp">
                <div class="section-detail clearfix">
                    <?php 
                         echo get_pagging($num_product, $page,'?mod=products&action=index');
                    ?>
                    <p class="num_rows">Có <?php echo $num_row_product; ?> sản phẩm</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>