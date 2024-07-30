<?php
function products_exists($post_title, $post_slug)
{
    $check_posts = db_num_rows("SELECT * FROM `tbl_products` WHERE `product_name` = '{$post_title}' OR `product_slug` = '{$post_slug}'");
    if ($check_posts > 0) {
        return true;
    }
    return false;
}

function get_list_product()
{
    $result = db_fetch_array("SELECT * FROM `tbl_products` ORDER BY `product_id` ASC");
    return $result;
}

function get_list_product_status($status)
{
    $result = db_fetch_array("SELECT * FROM `tbl_products` WHERE `product_status` = '$status'");
    return $result;
}

function get_row_product_status($status)
{
    $result = db_num_rows("SELECT * FROM `tbl_products` WHERE `product_status` = '$status'");
    return $result;
}

function get_row_product()
{
    $result = db_num_rows("SELECT * FROM `tbl_products`");
    return $result;
}

function get_paginate_product($start, $num_per_page, $where = "")
{
    if (!empty($where)) {
        $where = "WHERE {$where}";
    }
    $result = db_fetch_array("SELECT * FROM `tbl_products` {$where} LIMIT {$start}, {$num_per_page}");
    return $result;
}

function get_list_product_user()
{
    $item = db_fetch_array("SELECT `tbl_products`.`user_id`,`tbl_users`.`username`
    FROM `tbl_products` JOIN `tbl_users` ON `tbl_products`.`user_id` = `tbl_users`.`user_id`");
    return $item;
}

function get_list_category_products()
{
    $item = db_fetch_array("SELECT `tbl_products`.`category_id`,`tbl_product_categories`.`category_name`
    FROM `tbl_products` JOIN `tbl_product_categories` ON `tbl_products`.`category_id` = `tbl_product_categories`.`category_id`");
    return $item;
}

function get_id_image_thumb($id)
{
    $item = db_fetch_row("SELECT * FROM `tbl_product_images` WHERE `product_id` = {$id} AND `pin` = 1");
    return $item;
}

function get_image_product_by_id($id)
{
    $item = db_fetch_row("SELECT * FROM `tbl_images` WHERE `image_id` = {$id}");
    return $item;
}

function get_thumb_product_images()
{
    $item = db_fetch_array("SELECT `tbl_product_images`.`product_id`, `tbl_product_images`.`image_id`,`tbl_images`.`image_url`
    FROM `tbl_product_images` JOIN `tbl_images` ON `tbl_product_images`.`image_id` = `tbl_images`.`image_id`
    WHERE `pin` = 1");
    return $item;
}

function add_images($data)
{
    return db_insert('tbl_images', $data);
}

function get_images($name_file, $user_id)
{
    $result = db_fetch_row("SELECT * FROM `tbl_images` WHERE `file_name` = '{$name_file}' AND `user_id` = {$user_id}");
    return $result;
}

function get_product_by_id($id)
{
    $item = db_fetch_row("SELECT * FROM `tbl_products` WHERE `product_id` = {$id}");
    return $item;
}

function add_product($data)
{
    return db_insert('tbl_products', $data);
}

function get_products($name_products, $user_id)
{
    $result = db_fetch_row("SELECT * FROM `tbl_products` WHERE `product_name` = '{$name_products}' AND `user_id` = {$user_id}");
    return $result;
}

function add_product_imgages($data)
{
    return db_insert('tbl_product_images', $data);
}

function update_product($data, $product_id)
{
    db_update('tbl_products', $data, "`product_id` = '{$product_id}'");
}

function all_id_images_products($product_id)
{
    $item = db_fetch_array("SELECT * FROM `tbl_product_images` WHERE `product_id` = '{$product_id}'");
    return $item;
}

function get_all_id_images_product($product_id)
{
    $item = db_fetch_array("SELECT image_id FROM `tbl_product_images` WHERE `product_id` = '{$product_id}'");
    return $item;
}

function get_id_images_product($id)
{
    $item = db_fetch_row("SELECT * FROM `tbl_images` WHERE `image_id` = {$id}");
    return $item;
}

function getUrlImages($img_ids = [])
{
    $img = implode(',', $img_ids);
    $item = db_fetch_array("SELECT image_url FROM `tbl_images` WHERE image_id in ({$img})");
    return $item;
}

function delete_images($picture_id)
{
    db_delete("tbl_images", "`image_id` = {$picture_id}");
}

function delete_product_images($product_id, $picture_id)
{
    db_delete("tbl_product_images", "`product_id` = {$product_id} AND `image_id` = {$picture_id}");
}
function deleteAllImgByProductId($product_id, $image_ids = [])
{
    //begin transaction để mở 1 kết nối và chưa thực thi db liền
    db_query('START TRANSACTION');

    //Khi lm 1 công việc ở đây - trên thực tế nó đã gọi vào xóa trong db rồi,
    // nhưng mở transaction thì phải commit nó mới thực thi
    $isDelete = db_delete('tbl_product_images', "product_id = '{$product_id}'");

    if (!empty($image_ids)) {
        $img = implode(',', $image_ids);
        $isDelete = db_delete('tbl_images', "image_id in ({$img})");
    }

    //Nếu có lỗi xảy ra thì rollback -> dữ liệu còn nguyên như chưa làm gì cả.
    if (!$isDelete) {
        db_query('ROLLBACK');

        return false;
    }

    //Nếu k có lỗi thì thực hiện commit -> thực thi cả 2 câu query delete ở trên
    db_query('COMMIT');

    // trả data về dạng bool
    return true;
}

function delete_product($product_id)
{
    db_delete("tbl_products", "`product_id` = {$product_id}");
}

function update_product_images($data, $product_id)
{
    db_query('START TRANSACTION');

    db_delete('tbl_product_images', "product_id = '{$product_id}'");

    $dataInsert = [];

    foreach ($data as $items) {
        $row = [];
        $row['product_id'] = $product_id;

        foreach ($items as $column => $item) {
            $row[$column] = escape_string($item);
        }

        if (!empty($row['product_id'])
            && !empty($row['image_id'])
        ) {
            $dataInsert[] = $row;
        }
    }

    $newProductImgs = db_insert_multiple('tbl_product_images', $dataInsert);

    if (!$newProductImgs) {
        db_query('ROLLBACK');

        return false;
    }

    db_query('COMMIT');

    return true;
}
