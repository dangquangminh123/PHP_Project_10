<?php

function construct() {
//    echo "DÙng chung, load đầu tiên";
    load_model('index');
}

function indexAction() {
  load_view('index');
}

// function addAction() {
//     echo "Thêm dữ liệu";
// }


function detailAction() {
  load_view('index');
}

function updateAction() {
    $id = $_POST['id'];
    echo $id;
}

// function editAction() {
//     // $id = (int)$_GET['id'];
//     // $item = get_user_by_id($id);
//     // show_array($item);
// }