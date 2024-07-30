<?php

function construct() {
//    echo "DÙng chung, load đầu tiên";

    load_model('index');
    load('lib','validation');
    load('lib','email');
}

function indexAction() {
    load('helper','format');
    load_view('teamIndex'); 
}


// function detailAction() {
//     load_view('index');
// }

function regAction() {
    global $error, $username, $password, $email, $fullname, $address, $phone_number;
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

        // Kiểm tra địa chỉ
        if(empty($_POST['address'])) {
            $error['address'] = "Không được để trống địa chỉ";
        } else {
            if(!is_address($_POST['address'])) {
                $error['address'] = 'address không đúng định dạng';
            } else {
                $address = $_POST['address'];
            }
        }

        //Kiểm tra phone
        if(empty($_POST['phone_number'])) {
            $error['phone_number'] = "Không được để trống số điện thoại";
        } else {
            if(!is_phonenumber($_POST['phone_number'])) {
                $error['phone_number'] = 'Số điện thoại không đúng định dạng';
            } else {
                $phone_number = $_POST['phone_number'];
            }
        }
        
        if(empty($error)) {
            if(!user_exists($username, $email)) {
                // $active_token = md5($username.time());
                $data = array(
                    'fullname' => $fullname,
                    'username' => $username,
                    'password' => $password,
                    'email' => $email,
                    'phone_number' => $phone_number,
                    'address' => $address,
                    // 'active_token' => $active_token,
                    // 'reg_date' => time(),
                );
                add_user($data);
                // $link_active = base_url("?mod=users&action=active&active_token={$active_token}");
                // $content = "<p>Chào bạn {$fullname}</p>
                //   <p>Bạn vui lòng click vào đường link này để kích hoạt tài khoản: {$link_active}</p>
                //   <p>Nếu không phải bạn đăng ký tài khoản thì hãy bỏ qua email này!</p>
                //   <p>Team Support Unitop.vn</p>";
                //   send_mail('dangquangminhdn76@gmail.com',"Đặng Quang Minh", "Kích hoạt tài khoản PHP MASTER", $content);
                redirect("?mod=users&action=login");
            }else {
                $error['account'] = "Email hoặc username đã tồn tại trên hệ thống!";
            }
        }
    }
   
    load_view('reg');
}

function loginAction() {
    // echo date("d/m/Y h:m:s");
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
                redirect("?mod=users&controller=team");
            } else {
                $error['account'] = "Tên đăng nhập hoặc mật khẩu không tồn tại";
            }
        }
    }
    load_view('login');
}

// function activeAction() {
//     $link_login = base_url("?mod=users&action=login");
//     $active_token = $_GET['active_token'];
//     if(check_active_token($active_token)) {
//         active_user($active_token);
//         echo "Bạn đã kích hoạt thành công, vui lòng click vào đây để đăng nhập: <a href='{$link_login}'>{$link_login}</a>";
//     }else {
//         echo "Yêu cầu kích hoạt không hợp lệ hoặc tài khoản đã được kích hoạt trước đó, vui lòng click vào đây để đăng nhập: <a href='{$link_login}'>{$link_login}</a>";
//     }
// }

function logoutAction() {
    unset($_SESSION['is_login']);
    unset($_SESSION['user_login']);
    redirect("?mod=users&action=login");
}

function resetAction() {
    global $error, $pass_old, $pass_new, $confirm_pass;
    if(isset($_POST['btn-resetPass'])) {
        $error = array();

        // Kiểm tra mật khẩu cũ
        if(empty($_POST['pass-old'])) {
            $error['pass-old'] = "Không được để trống mật khẩu cũ";
        } else {
            if(!is_password($_POST['pass-old'])) {
                $error['pass-old'] = 'Mật khẩu cũ không đúng định dạng';
            } else {
                $pass_old = md5($_POST['pass-old']);
            }
        }

        // Kiểm tra mật khẩu mới 
        if(empty($_POST['pass-new'])) {
            $error['pass-new'] = "Không được để trống mật khẩu mới";
        } else {
            if(!is_password($_POST['pass-new'])) {
                $error['pass-new'] = 'mật khẩu mới không đúng định dạng';
            } else {
                $pass_new = md5($_POST['pass-new']);
            }
        }

        // Kiểm tra xác thực mật khẩu mới
        if(empty($_POST['confirm-pass'])) {
            $error['confirm-pass'] = "Bạn cần nhập xác thực mật khẩu mới";
        } else {
            if(!is_password($_POST['confirm-pass'])) {
                $error['confirm-pass'] = 'Xác thực mật khẩu mới không đúng định dạng';
            } else {
                $confirm_pass = md5($_POST['confirm-pass']);
            }
        }

         // Kết luận
         if(empty($error)) {
            if(old_password_exists($pass_old)) {
                // $_SESSION['is_login'] = true;
                // $_SESSION['user_login'] = $username;
                if($pass_new === $confirm_pass) {
                    $data = array(
                        'password' => $confirm_pass,
                    );
                    update_password_new(user_login(), $data);
                    
                    // Chuyển trang điều hướng
                    redirect("?mod=users&controller=team");
                }else {
                    $error['account'] = "Mật khẩu mới không khớp với xác thực mật khẩu! Vui lòng nhập lại cho đúng";
                }       
            } else {
                $error['account'] = "Mật khẩu cũ không chính xác! vui lòng nhập lại";
            }
        }
    }
    load_view('reset');
} 



function updateAction() {
    if(isset($_POST['btn-update'])) {
        $error= array(); 
        
        // Kiểm tra fullname
        if(empty($_POST['fullname'])) {
            $error['fullname'] = "Không được để trống họ và tên";
        } else {
            if(!is_username($_POST['fullname'])) {
                $error['fullname'] = 'fullname không đúng định dạng';
            } else {
                $fullname = $_POST['fullname'];
            }
        }
        
        // Kiểm tra username
        if(empty($_POST['username'])) {
            $error['username'] = "Không được để trống tên đăng nhập";
        } else {
            if(!is_username($_POST['username'])) {
                $error['username'] = 'username không đúng định dạng';
            } else {
                $username = $_POST['username'];
            }
        }

        //Kiểm tra địa chỉ
        if(empty($_POST['address'])) {
            $error['address'] = "Không được để trống địa chỉ";
        } else {
            if(!is_address($_POST['address'])) {
                $error['address'] = 'address không đúng định dạng';
            } else {
                $address = $_POST['address'];
            }
        }

        //Kiểm tra phone
        if(empty($_POST['phone_number'])) {
            $error['phone_number'] = "Không được để trống số điện thoại";
        } else {
            if(!is_phonenumber($_POST['phone_number'])) {
                $error['phone_number'] = 'Số điện thoại không đúng định dạng';
            } else {
                $phone_number = $_POST['phone_number'];
            }
        }
        
        if(empty($error)) {
            $data = array(
                'fullname' => $fullname,
                'address' => $address,
                'phone_number' => $phone_number
            );
            update_user_login(user_login(), $data);
        }
    }
    $info_user = get_user_by_username(user_login());
    // show_array($info_user);
    
    if(empty($info_user)) {
        redirect("?mod=users&action=login");
    }else {
        $data['info_user'] =$info_user; 
        load_view('update', $data);
    }
}


// function resetOkAction() {
//     load_view('resetOk');
// }


// function addAction() {
//     echo "Thêm dữ liệu";
// }

// function editAction() {
//     $id = (int)$_GET['id'];
//     $item = get_user_by_id($id);
//     show_array($item);
// }