<?php get_header(); ?>

<div id="main-content-wp" class="list-post-page">

    <div class="wrap clearfix">
        <?php 
            get_sidebar();
        ?>


        <div id="content" class="fl-right">

            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Danh sách bài viết</h3>
                    <a href="?mod=posts&action=AddPost" title="" id="add-new" class="fl-left">Thêm mới</a>

                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <div class="filter-wp clearfix">
                        <ul class="post-status fl-left">
                            <li class="all"><a href="?mod=posts&action=index" class="active">Tất cả
                                    <span class="count">(<?php echo count($all_post);?>)</span></a> |
                            </li>
                            <li class="all"><a href="?mod=posts&action=index&postStatus=draft">Nháp
                                    <span class="count">(<?php echo count($list_post_draft);?>)</span></a> |
                            </li>
                            <li class="all"><a href="?mod=posts&action=index&postStatus=published">Công khai
                                    <span class="count">(<?php echo count($list_post_published);?>)</span></a> |
                            </li>
                            <li class="all"><a href="?mod=posts&action=index&postStatus=pending">Chờ xét duyệt
                                    <span class="count">(<?php echo count($list_post_pending);?>)</span></a> |
                            </li>
                            <li class="all"><a href="?mod=posts&action=index&postStatus=archived">Lưu trữ
                                    <span class="count">(<?php echo count($list_post_archived);?>)</span></a>
                            </li>
                        </ul>
                        <form method="GET" class="form-s fl-right">
                            <input type="text" name="s" id="s">
                            <input type="submit" name="sm_s" value="Tìm kiếm">
                        </form>
                    </div>
                    <!-- <div class="actions">
                        <form method="GET" action="" class="form-actions">
                            <select name="actions">
                                <option value="0">Tác vụ</option>
                                <option value="1">Chỉnh sửa</option>
                                <option value="2">Bỏ vào thủng rác</option>
                            </select>
                            <input type="submit" name="sm_action" value="Áp dụng">
                        </form>
                    </div> -->
                    <div class="table-responsive">
                        <?php
                            if(!empty($list_post)) {
                        ?>
                        <table class="table list-table-wp">
                            <thead>
                                <tr>
                                    <td><span class="thead-text">STT</span></td>
                                    <td><span class="thead-text">Tiêu đề</span></td>
                                    <td><span class="thead-text">Slug</span></td>
                                    <td class="w-10"><span class="thead-text">Mô tả ngắn</span></td>
                                    <td class="w-20"><span class="thead-text">Nội dung</span></td>
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
                                    foreach($list_post as $item) { 
                                    // $temp++;
                                ?>
                                <tr>
                                    <td><span class="tbody-text"><?php echo $item['post_id']; ?></span>
                                    <td class="clearfix">
                                        <div class="tb-title fl-left">
                                            <span class="post_title"><?php echo $item['post_title']; ?></span>
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
                                            <?php echo $item['post_slug']; ?>
                                        </span>
                                    </td>
                                    <td class="w-10">
                                        <span class="tbody-text tb-excerpt"><?php echo $item['post_excerpt']; ?></span>
                                    </td>
                                    <td class="w-20">
                                        <span class="tbody-text tb-content"><?php echo $item['post_content']; ?></span>
                                    </td>
                                    <td><span class="tbody-text"><?php echo $item['post_status']; ?></span></td>
                                    <?php
                                        foreach($user_post as $user) { 
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
                                        foreach($category_post as $category) { 
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
                                        foreach($post_images as $images) { 
                                        if($images['image_id'] === $item['image_id']) {
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

                        <span>Hiện tại chưa có bài viết nào!</span>
                        <?php } ?>
                    </div>

                </div>
            </div>
            <div class="section" id="paging-wp">
                <div class="section-detail clearfix">
                    <?php 
                         echo get_pagging($num_post, $page,'?mod=posts&action=index');
                    ?>
                    <p class="num_rows">Có <?php echo $num_row_post; ?> bài viết</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>