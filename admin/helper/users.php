<?php 
//  function check_login($username, $password) {
//     global $list_users;
//     // show_array($list_users);
//     foreach($list_users as $user) {
//         if($username == $user['username'] && md5($password) == $user['password']) {
//             return true;
//         }
//     }
//     return false;
// }

// hàm lưu trữ  phiên đăng nhập
function is_login() {
    if(isset($_SESSION['is_login'])) {
        return true;
    }
    return false;
}

// hàm lưu trữ thông tin user phiên đăng nhập
function user_login() {
    if(!empty($_SESSION['user_login'])) {
        return $_SESSION['user_login'];
    }
    return false;
}

function info_user($field = 'id') {
    global $list_users;
    if(isset($_SESSION['is_login'])) {
        foreach($list_users as $user) {
            if($_SESSION['user_login']) {
                if(array_key_exists($field, $user)) {
                    return $user[$field];
                }
            }
        }
    }
    return false;
}   
?>