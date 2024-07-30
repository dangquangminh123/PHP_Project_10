<?php
function post_exists($post_title, $post_slug) {
    $check_posts = db_num_rows("SELECT * FROM `tbl_posts` WHERE `post_title` = '{$post_title}' OR `post_slug` = '{$post_slug}'");
    if($check_posts > 0) {
        return true;
    }
    return false;
}

function get_list_post() {
    $result = db_fetch_array("SELECT * FROM `tbl_posts` ORDER BY `post_id` ASC");
    return $result;
}

function get_list_post_status($status) {
    $result = db_fetch_array("SELECT * FROM `tbl_posts` WHERE `post_status` = '$status'");
    return $result;
}

function get_row_post_status($status) {
    $result = db_num_rows("SELECT * FROM `tbl_posts` WHERE `post_status` = '$status'");
    return $result;
}

function get_row_posts() {
    $result = db_num_rows("SELECT * FROM `tbl_posts`");
    return $result;
}

function get_paginate_post($start, $num_per_page, $where = "") {
    if(!empty($where)) {
        $where = "WHERE {$where}";
    }
    $result = db_fetch_array("SELECT * FROM `tbl_posts` {$where} LIMIT {$start}, {$num_per_page}");
    return $result;
}

function get_list_post_user() {
    $item = db_fetch_array("SELECT `tbl_posts`.`user_id`,`tbl_users`.`username` 
    FROM `tbl_posts` JOIN `tbl_users` ON `tbl_posts`.`user_id` = `tbl_users`.`user_id`");
    return $item;
}

function get_list_category_post() {
    $item = db_fetch_array("SELECT `tbl_posts`.`category_id`,`tbl_post_categories`.`category_name` 
    FROM `tbl_posts` JOIN `tbl_post_categories` ON `tbl_posts`.`category_id` = `tbl_post_categories`.`category_id`");
    return $item;
}

function get_list_post_images() {
    $item = db_fetch_array("SELECT `tbl_posts`.`image_id`,`tbl_images`.`image_url` 
    FROM `tbl_posts` JOIN `tbl_images` ON `tbl_posts`.`image_id` = `tbl_images`.`image_id`");
    return $item;
}

function get_image_by_id($id) {
    $item = db_fetch_row("SELECT * FROM `tbl_images` WHERE `image_id` = {$id}");
    return $item;
}

function get_post_by_id($id) {
    $item = db_fetch_row("SELECT * FROM `tbl_posts` WHERE `post_id` = {$id}");
    return $item;
}
function get_images_post($id) {
    $item = db_fetch_row("SELECT * FROM `tbl_images` WHERE `image_id` = {$id}");
    return $item;
}

function add_posts($data) {
    return db_insert('tbl_posts', $data);
}

function update_post($data, $post_id) {
    db_update('tbl_posts', $data, "`post_id` = '{$post_id}'");
}

function delete_posts($post_id) {
    db_delete("tbl_posts", "`post_id` = {$post_id}");
}

