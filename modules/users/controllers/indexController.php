<?php

function construct() {
//    echo "DÙng chung, load đầu tiên";

    load_model('index');
    load('lib','validation');
    load('lib','email');
}

function indexAction() {
    load('helper','format');
    $list_users = get_list_users();
//    show_array($list_users);
    $data['list_users'] = $list_users;
    load_view('index', $data);
}


function detailAction() {
    load_view('index');
}

function regAction() {
    global $error, $username, $password, $email, $fullname;
    // echo sendmail('phancuong.qt@gmail.com',"Đặng Quang Minh", "Kích hoạt tài khoản", "<a href='http://unitop.vn'>Kích hoạt</a>");
    if(isset($_POST['btn-reg'])) {
        $error = array();

        // Fullname
        if(empty($_POST['fullname'])) {
            $error['fullname'] = "Không được để trống fullname";
        } else {
            // if(!is_fullname($_POST['fullname'])) {
            //     $error['fullname'] = 'fullname không đúng định dạng';
            // } else {
                $fullname = $_POST['fullname'];
            // }
        }

        // Kiểm tra username
        if(empty($_POST['username'])) {
            $error['username'] = "Không được để trống username";
        } else {
            if(!is_username($_POST['username'])) {
                $error['username'] = 'username không đúng định dạng';
            } else {
                $username = $_POST['username'];
            }
        }

         // Email
         if(empty($_POST['email'])) {
            $error['email'] = "Không được để trống email";
        } else {
            if(!is_email($_POST['email'])) {
                $error['email'] = 'Email không đúng định dạng';
            } else {
                $email = $_POST['email'];
            }
        }

        // Password
        if(empty($_POST['password'])) {
            $error['password'] = "Không được để trống password";
        } else {
            if(!is_password($_POST['password'])) {
                $error['username'] = 'password không đúng định dạng';
            } else {
                $password = md5($_POST['password']);
            }
        }
        if(empty($error)) {
            if(!user_exists($username, $email)) {
                $active_token = md5($username.time());
                $data = array(
                    'fullname' => $fullname,
                    'username' => $username,
                    'password' => $password,
                    'email' => $email,
                    'active_token' => $active_token,
                    'reg_date' => time(),
                );
                add_user($data);
                $link_active = base_url("?mod=users&action=active&active_token={$active_token}");
                $content = "<p>Chào bạn {$fullname}</p>
                  <p>Bạn vui lòng click vào đường link này để kích hoạt tài khoản: {$link_active}</p>
                  <p>Nếu không phải bạn đăng ký tài khoản thì hãy bỏ qua email này!</p>
                  <p>Team Support Unitop.vn</p>";
                  send_mail('dangquangminhdn76@gmail.com',"Đặng Quang Minh", "Kích hoạt tài khoản PHP MASTER", $content);
                // redirect("?mod=users&action=login");
            }else {
                $error['account'] = "Email hoặc username đã tồn tại trên hệ thống!";
            }
        }
    }
   
    load_view('reg');
}

function loginAction() {
    global $error, $username, $password;
    if(isset($_POST['btn_login'])) {
        $error = array();
        if(empty($_POST['username'])) {
            $error['username'] = "Không được để trống tên đăng nhập";
        } else {
            if(!is_username($_POST['username'])) {
                $error['username'] = 'username không đúng định dạng';
            } else {
                $username = $_POST['username'];
            }
        }

        // Kiểm tra Password
        if(empty($_POST['password'])) {
            $error['password'] = "Không được để trống mật khẩu";
        } else {
            if(!is_password($_POST['password'])) {
                $error['password'] = 'password không đúng định dạng';
            } else {
                $password = md5($_POST['password']);
            }
        }

        // Kết luận
        if(empty($error)) {
            if(check_login($username, $password)) {
                $_SESSION['is_login'] = true;
                $_SESSION['user_login'] = $username;
                // Chuyển trang điều hướng
                redirect();
            } else {
                $error['account'] = "Tên đăng nhập hoặc mật khẩu không tồn tại";
            }
        }
    }
    load_view('login');
}

function activeAction() {
    $link_login = base_url("?mod=users&action=login");
    $active_token = $_GET['active_token'];
    if(check_active_token($active_token)) {
        active_user($active_token);
        echo "Bạn đã kích hoạt thành công, vui lòng click vào đây để đăng nhập: <a href='{$link_login}'>{$link_login}</a>";
    }else {
        echo "Yêu cầu kích hoạt không hợp lệ hoặc tài khoản đã được kích hoạt trước đó, vui lòng click vào đây để đăng nhập: <a href='{$link_login}'>{$link_login}</a>";
    }
}

function logoutAction() {
    unset($_SESSION['is_login']);
    unset($_SESSION['user_login']);
    redirect("?mod=users&action=login");
}

function resetAction() {
    global $error, $email, $password;
    $reset_token = $_GET['reset_token'];
    if(!empty($reset_token)) {
        if(check_reset_token($reset_token)) {
            if(isset($_POST['btn-new-pass'])) {
                if(empty($_POST['password'])) {
                    $error['password'] = "Không được để trống mật khẩu";
                } else {
                    if(!is_password($_POST['password'])) {
                        $error['password'] = 'password không đúng định dạng';
                    } else {
                        $password = md5($_POST['password']);
                    }
                }
                if(empty($error)){
                    $data = array(
                        'password' => $password
                    );
                    update_pass($data, $reset_token);
                    redirect("?mod=users&action=resetOk");
                }
            }
            load_view('newPass');
        } else {
            echo "Yêu cầu lấy lại mật khẩu không hợp lệ";
        }
       
    } else {
        if(isset($_POST['btn-reset'])) {
            $error = array();
        
            // Email
            if(empty($_POST['email'])) {
                $error['email'] = "Không được để trống email";
            } else {
                if(!is_email($_POST['email'])) {
                    $error['email'] = 'Email không đúng định dạng';
                } else {
                    $email = $_POST['email'];
                }
            }
            // Kết luận
            if(empty($error)) {
                if(check_email($email)) {
                $reset_token = md5($email.time());
                $data = array(
                    'reset_token' => $reset_token
                );
                    //Cập nhập mã reset password
                update_reset_token($data, $email);
                    //Gửi link khôi phục email 
                    $link = base_url("?mod=users&action=reset&reset_token={$reset_token}");
                    $content = "
                    <p>Bạn vui lòng click vào đường link này để khôi phục lại mật khẩu: {$link}</p>
                    <p>Nếu không phải bạn tài khoản này thì hãy bỏ qua email này!</p>
                    <p>Team Support Unitop.vn</p>";
                    // Vì đây là đăng ký toàn gmail giả, nên khi gửi xác thật thì để ngầm định gmail chính chủ thớt
                send_mail('dangquangminhdn76@gmail.com', 'Test', 'Khôi phục mật khẩu', $content);
                } else {
                    $error['account'] = "Tên đăng nhập hoặc mật khẩu không tồn tại";
                }
            }
        }
        load_view('reset');
    }
   
} 


function resetOkAction() {
    load_view('resetOk');
}

function addAction() {
    echo "Thêm dữ liệu";
}

function editAction() {
    $id = (int)$_GET['id'];
    $item = get_user_by_id($id);
    show_array($item);
}