<?php
    get_header();
// show_array($list_users);
?>


<div id="main-content-wp" class="list-post-page">
    <div class="wrap clearfix">
        <?php 
            get_sidebar();  
        ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Danh sách pages</h3>
                    <a href="?mod=pages&action=AddPage" title="" id="add-new" class="fl-left">Thêm mới</a>
                </div>
            </div>

            <div class="section" id="detail-page">
                <div class="section-detail">
                    <div class="filter-wp clearfix">
                        <ul class="post-status fl-left">
                            <li class="all"><a href="?mod=pages&action=index" class="active">Tất cả
                                    <span class="count">(<?php echo count($all_pages);?>)</span></a> |
                            </li>
                            <li class="all"><a href="?mod=pages&action=index&pageStatus=draft">Nháp
                                    <span class="count">(<?php echo count($list_pages_draft);?>)</span></a> |
                            </li>
                            <li class="all"><a href="?mod=pages&action=index&pageStatus=published">Công khai
                                    <span class="count">(<?php echo count($list_pages_published);?>)</span></a> |
                            </li>
                            <li class="all"><a href="?mod=pages&action=index&pageStatus=pending">Chờ xét duyệt
                                    <span class="count">(<?php echo count($list_pages_pending);?>)</span></a> |
                            </li>
                            <li class="all"><a href="?mod=pages&action=index&pageStatus=archived">Lưu trữ
                                    <span class="count">(<?php echo count($list_pages_archived);?>)</span></a>
                            </li>
                        </ul>
                        <form method="GET" class="form-s fl-right">
                            <input type="text" name="search_page" id="s">
                            <input type="submit" name="sm_s" value="Tìm kiếm">
                        </form>
                    </div>
                    <div class="table-responsive">
                        <?php
                            if(!empty($list_pages)) {
                        ?>
                        <table class="table list-table-wp">
                            <thead>
                                <tr>
                                    <td><input type="checkbox" name="checkAll" id="checkAll"></td>
                                    <td><span class="thead-text">STT</span></td>
                                    <td><span class="thead-text">Tiêu đề</span></td>
                                    <td><span class="thead-text">Slug Page</span></td>
                                    <td><span class="thead-text">Mô tả</span></td>
                                    <td><span class="thead-text">Hình ảnh</span></td>
                                    <td><span class="thead-text">Trạng thái</span></td>
                                    <td><span class="thead-text">Người tạo</span></td>
                                    <td><span class="thead-text">Thời gian</span></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // $temp=$start; 
                                    foreach($list_pages as $item) { 
                                    // $temp++;
                                ?>
                                <tr>
                                    <td><input type="checkbox" name="checkItem" class="checkItem"></td>
                                    <td><span class="tbody-text"><?php echo $item['page_id']; ?></span>
                                    <td class="clearfix">
                                        <div class="tb-title fl-left">
                                            <span class="page_title"><?php echo $item['page_title']; ?></span>
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
                                    <td><span class="tbody-text"><?php echo $item['page_slug']; ?></span></td>
                                    <td><span class="tbody-text"><?php echo $item['page_content']; ?></span></td>
                                    <?php
                                        foreach($user_post as $user) { 
                                        if($user['user_id'] === $item['user_id']) {
                                    ?>
                                    <td>
                                        <span class="tbody-text">
                                            <a href="<?php $item['images_pages'] ?>" title="" class="thumb">
                                                <img src="<?php echo $item['images_pages'] ?>" alt="">
                                            </a>
                                        </span>
                                    </td>
                                    <?php       
                                        break;        
                                            }
                                        } 
                                    ?>
                                    <td><span
                                            class="tbody-text"><?php echo show_status_page($item['page_status']); ?></span>
                                    </td>
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
                                    <td><span class="tbody-text"><?php echo format_time($item['created_at']); ?></span>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                ?>
                            </tbody>
                        </table>

                        <?php } else { ?>

                        <span>Hiện tại chưa có pages nào!</span>
                        <?php } ?>
                    </div>

                </div>
            </div>
            <div class="section" id="paging-wp">
                <div class="section-detail clearfix">
                    <?php 
                            echo get_pagging($num_pages, $page,'?mod=pages&action=index');
                        ?>
                    <p class="num_rows">Có <?php echo $num_row_pages; ?> Trang</p>

                </div>
            </div>


        </div>
    </div>
</div>

<?php 
    get_footer();
?>