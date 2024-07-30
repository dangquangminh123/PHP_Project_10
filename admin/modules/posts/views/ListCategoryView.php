<?php get_header(); ?>

<div id="main-content-wp" class="list-post-page">

    <div class="wrap clearfix">
        <?php 
            get_sidebar();
        ?>


        <div id="content" class="fl-right">

            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Danh sách danh mục bài viết</h3>
                    <a href="?mod=posts&action=AddCategories" title="" id="add-new" class="fl-left">Thêm mới</a>

                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <div class="filter-wp clearfix">
                        <!-- <ul class="post-status fl-left">
                            <li class="all"><a href="">Tất cả <span class="count">(10)</span></a> |</li>
                            <li class="publish"><a href="">Đã đăng <span class="count">(5)</span></a> |</li>
                            <li class="pending"><a href="">Chờ xét duyệt <span class="count">(5)</span></a>
                            </li>
                            <li class="trash"><a href="">Thùng rác <span class="count">(0)</span></a></li>
                        </ul>
                        <form method="GET" class="form-s fl-right">
                            <input type="text" name="s" id="s">
                            <input type="submit" name="sm_s" value="Tìm kiếm">
                        </form> -->
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
                            if(!empty($list_category_post)) {
                        ?>
                        <table class="table list-table-wp">
                            <thead>
                                <tr>
                                    <td><span class="thead-text">STT</span></td>
                                    <td><span class="thead-text">Tiêu đề</span></td>
                                    <td><span class="thead-text">Slug</span></td>
                                    <td><span class="thead-text">Mô tả</span></td>
                                    <td><span class="thead-text">Danh mục</span></td>
                                    <td><span class="thead-text">Người tạo</span></td>
                                    <td><span class="thead-text">Thời gian</span></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // $temp=$start; 
                                    foreach($list_category_post as $item) { 
                                    // $temp++;
                                ?>
                                <tr>
                                    <td><span class="tbody-text"><?php echo $item['category_id']; ?></span>
                                    <td class="clearfix">
                                        <div class="tb-title fl-left">
                                            <span class="category_name"><?php echo $item['category_name']; ?></span>
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
                                        <span class="categoryslug">
                                            <?php echo $item['category_slug']; ?>
                                        </span>
                                    </td>
                                    <td><span class="tbody-text"><?php echo $item['category_desc']; ?></span></td>
                                    <td>
                                        <span class="tbody-text">
                                            <?php
                                                if($item['parent_id'] == 0 )  {
                                                    echo '<p class="level_parent">cha</p>';
                                                }else {
                                                    echo '<p class="level_children">con</p>';
                                                }
                                              
                                            ?>
                                        </span>
                                    </td>
                                    <?php
                                        foreach($user_category_post as $user) { 
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
                                    <td><span class="tbody-text"><?php echo format_time($item['created_at']); ?></span>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                ?>
                            </tbody>
                        </table>
                        <?php } else { ?>

                        <span>Hiện tại chưa có danh mục bài viết nào!</span>
                        <?php } ?>
                    </div>

                </div>
            </div>
            <div class="section" id="paging-wp">
                <div class="section-detail clearfix">
                    <?php 
                         echo get_pagging($num_category_post, $page,'?mod=posts&action=indexCategory');
                    ?>
                    <p class="num_rows">Có <?php echo count($total_category_post); ?> Trang</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>