<?php

function add_pages($data) {
    return db_insert('tbl_pages', $data);    
}

function get_list_users() {
    $result = db_fetch_array("SELECT * FROM `tbl_users`");
    return $result;
}

function get_user_by_id($id) {
    $item = db_fetch_row("SELECT * FROM `tbl_users` WHERE `user_id` = {$id}");
    return $item;
}

function get_list_pages_user() {
    $item = db_fetch_array("SELECT `tbl_pages`.`user_id`,`tbl_users`.`username` 
    FROM `tbl_pages` JOIN `tbl_users` ON `tbl_pages`.`user_id` = `tbl_users`.`user_id`");
    return $item;
}

function get_pages_by_id($id) {
    $item = db_fetch_row("SELECT * FROM `tbl_pages` WHERE `page_id` = {$id}");
    return $item;
}
function get_list_pages() {
    $result = db_fetch_array("SELECT * FROM `tbl_pages`");
    return $result;
}

function get_list_pages_status($status) {
    $result = db_fetch_array("SELECT * FROM `tbl_pages` WHERE `page_status` = '$status'");
    return $result;
}

function get_row_pages() {
    $result = db_num_rows("SELECT * FROM `tbl_pages`");
    return $result;
}

function get_row_pages_status($status) {
    $result = db_num_rows("SELECT * FROM `tbl_pages` WHERE `page_status` = '$status'");
    return $result;
}

function get_paginate_pages($start, $num_per_page, $where = "") {
    if(!empty($where)) {
        $where = "WHERE {$where}";
    }
    $result = db_fetch_array("SELECT * FROM `tbl_pages` {$where} LIMIT {$start}, {$num_per_page}");
    return $result;
}

function search_value_pages($value_search) {
    $item = db_fetch_row("SELECT * FROM `tbl_pages` WHERE `page_title` LIKE %$value_search% OR `page_slug` LIKE %$value_search%");
    if(!empty($item)) {
        return $item;
    }
}

function pages_exists($page_title, $page_slug) {
    $check_pages = db_num_rows("SELECT * FROM `tbl_pages` WHERE `page_title` = '{$page_title}' OR `page_slug` = '{$page_slug}'");
    if($check_pages > 0) {
        return true;
    }
    return false;
}

function update_pages($data, $page_id) {
    db_update('tbl_pages', $data, "`page_id` = '{$page_id}'");
}

function delete_pages($page_id) {
    db_delete("tbl_pages", "`page_id` = {$page_id}");
}

function get_user_by_username($username) {
    $item = db_fetch_row("SELECT * FROM `tbl_users` WHERE `username` = '{$username}'");
    if(!empty($item)) {
        return $item;
    }
}