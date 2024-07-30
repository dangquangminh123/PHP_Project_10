<?php

function get_user_by_username($username) {
    $item = db_fetch_row("SELECT * FROM `tbl_users` WHERE `username` = '{$username}'");
    if(!empty($item)) {
        return $item;
    }
}

function get_user_by_id($id) {
    $item = db_fetch_row("SELECT * FROM `tbl_users` WHERE `user_id` = {$id}");
    return $item;
}

function get_list_users() {
    $result = db_fetch_array("SELECT * FROM `tbl_users`");
    return $result;
}

function get_list_category_user() {
    $item = db_fetch_array("SELECT `tbl_post_categories`.`user_id`,`tbl_users`.`username` 
    FROM `tbl_post_categories` JOIN `tbl_users` ON `tbl_post_categories`.`user_id` = `tbl_users`.`user_id`");
    return $item;
}

function get_list_category() {
    $result = db_fetch_array("SELECT * FROM `tbl_post_categories` ORDER BY `category_id` ASC");
    return $result;
}

function get_category_post_by_id($id) {
    $item = db_fetch_row("SELECT * FROM `tbl_post_categories` WHERE `category_id` = {$id}");
    return $item;
}

function delete_category_posts($category_id) {
    db_delete("tbl_post_categories", "`category_id` = {$category_id}");
}

function get_paginate_category_post($start, $num_per_page, $where = "") {
    if(!empty($where)) {
        $where = "WHERE {$where}";
    }
    $result = db_fetch_array("SELECT * FROM `tbl_post_categories` {$where} LIMIT {$start}, {$num_per_page}");
    return $result;
}

function get_list_category_parent_post() {
    $result = db_fetch_array("SELECT * FROM `tbl_post_categories` WHERE `parent_id` = 0");
    return $result;
}


function get_all_category_parent_post($id) {
    $result = db_fetch_array("SELECT * FROM `tbl_post_categories` WHERE `parent_id` = 0 AND NOT `category_id` = {$id}");
    return $result;
}

function add_images($data) {
    return db_insert('tbl_images', $data);
}

function get_images($name_file, $user_id) {
    $result = db_fetch_row("SELECT * FROM `tbl_images` WHERE `file_name` = '{$name_file}' AND `user_id` = {$user_id}");
    return $result;
}

function update_category_post($data, $category_id) {
    db_update('tbl_post_categories', $data, "`category_id` = '{$category_id}'");
}


function add_category_post($data) {
    return db_insert('tbl_post_categories', $data);
}

function update_images($data, $image_id) {
    db_update('tbl_images', $data, "`image_id` = '{$image_id}'");
}

function delete_images($images_id) {
    db_delete("tbl_images", "`image_id` = {$images_id}");
}


function category_post_exists($category_name, $category_slug) {
    $check_pages = db_num_rows("SELECT * FROM `tbl_post_categories` WHERE `category_name` = '{$category_name}' OR `category_slug` = '{$category_slug}'");
    if($check_pages > 0) {
        return true;
    }
    return false;
}