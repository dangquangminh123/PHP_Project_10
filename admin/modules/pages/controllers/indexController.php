<?php

function construct() {
    load_model('index');
    load('lib','validation');
    load('lib','slug');
    load('lib','pagging');
}

function indexAction() {
    load('helper','format', 'users', 'data');
    $status_page = isset($_GET['pageStatus'])?(string)$_GET['pageStatus']:'';
     // Số lượng bản ghi trên trang
     $num_per_pages = 5;
        
    $page = isset($_GET['page'])?(int)$_GET['page']:1;
    $start = ($page-1)*$num_per_pages;

    if($status_page !== '') {
        $num_row_pages = get_row_pages_status($status_page);
        $list_pages = get_paginate_pages($start, $num_per_pages, "`page_status` = '$status_page'");
    } else {
        $num_row_pages = get_row_pages();
        $list_pages = get_paginate_pages($start, $num_per_pages, '');
    }
    
   
    $user_post =get_list_pages_user();

    // Tổng số bản ghi
    $total_pages = $num_row_pages;
    // Tổng số trang
    $num_pages = ceil($total_pages/$num_per_pages);
    
   
    foreach($list_pages as &$pages) {
        $pages['url_update'] = "?mod=pages&action=UpdatePage&id={$pages['page_id']}";
        $pages['url_delete'] = "?mod=pages&action=DeletePage&id={$pages['page_id']}";
    }
    unset($pages);
    
    $user_pages = get_list_pages_user();
    // show_array($data['username']);
    // $username = $data['username'];    
    
    // $data = get_user_by_id($info_user);
    // $username = $data['username'];   
    $all_pages =get_list_pages();
    $list_pages_published = get_list_pages_status('published');
    $list_pages_draft = get_list_pages_status('draft');
    $list_pages_pending = get_list_pages_status('pending');
    $list_pages_archived = get_list_pages_status('archived');
   
    
    $data = array(
        'list_pages' => $list_pages,
        'num_pages' => $num_pages,
        'user_post' => $user_post,
        'page' => $page,
        'num_row_pages' => $num_row_pages,
        'all_pages' => $all_pages,
        'list_pages_draft' => $list_pages_draft,
        'user_pages' => $user_pages,
        'list_pages_published' => $list_pages_published,
        'list_pages_pending' => $list_pages_pending,
        'list_pages_archived' => $list_pages_archived,
    );
    load_view('index', $data);
}


function AddPageAction() {
    $time = date("d/m/Y h:m:s");
    load('helper','format', 'users', 'data');
    global $error, $page_title, $page_slug, $page_content, $page_status, $user_id, $upload_file, $created_at;
    $info_user = user_login();
    $data = get_user_by_username($info_user);
    $user_id = $data['user_id'];  
    
    if($info_user) {
        if(isset($_POST['btn-add'])) {
            $error= array(); 
            
            if(empty($_POST['title'])) {
                $error['title'] = "Không được để trống tiêu đề trang";
            } else {
                $page_title = $_POST['title'];
            }
            
            // Kiểm tra slug
            if(empty($_POST['slug'])) {
                $error['slug'] = "Slug website không được để trống";
            } else {
         
                    $page_slug = create_slug($_POST['slug']);
    
            }
    
            //Kiểm tra mô tả
            if(empty($_POST['desc'])) {
                $error['desc'] = "Không được để trống mô tả pages";
            } else {
                if(is_description($_POST['desc'])) {
                    $error['desc'] = 'mô tả không đúng định dạng';
                } else {
                    $page_content = $_POST['desc'];
                }
            }
    
            if(empty($_POST['status'])) {
                $page_status = "draft";
            } else {
                $page_status = $_POST['status'];
            }
    
            if(isset($_FILES['file'])) {
                // show_array($_FILES);
                global $config;
                $error_images = array(); 
                $upload_dir = 'public/uploads/pages/';
                // Đường dẫn file sau khi upload
                $upload_file = $upload_dir.$_FILES['file']['name'];
                // Xử lý upload đúng file ảnh
                $type_allow = array('png', 'jpg', 'gift', 'jpeg');
                // pathinfo lấy đuôi file ảnh và có tham số PATHINFO_EXTENSION phía sau để lấy đuôi file
                $type = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if(!in_array(strtolower($type), $type_allow)) {
                    $error_images['file'] = "Chỉ được upload file có đuôi png, jpg, gif, jpeg";
                }else {
                    // Upload file có kích thước cho phép (<20MB ~ 29.000.000 Byte)
                    $file_size = $_FILES['file']['size'];
                    if($file_size > 29000000) {
                        $error_images['file'] = "chỉ được upload file bé hơn 20 MB";
                    } 
                    //Kiểm tra xem file đó trùng 1 file đã tồn tại trên hệ thống hay không
                    if(file_exists($upload_file)) {
                        $file_name = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                        $new_file_name = $file_name . ' - Copy.';
                        $new_upload_file = $upload_dir . $new_file_name . $type;
                        $k = 1;
                        while(file_exists($new_upload_file)) {
                            $new_file_name = $file_name . " - Copy({$k}).";
                            $k++;
                            $new_upload_file = $upload_dir . $new_file_name . $type; 
                        }
                        $upload_file = $new_upload_file;
                    }
                }
                if(empty($error_images)){
                    if($upload_file) {
                        move_uploaded_file($_FILES['file']['tmp_name'], $upload_file);
                        echo "<a href='$upload_file'>Download: {$_FILES['file']['name']}</a><br/>";
                        echo "<img src='{$upload_file}' style='height: 50px; width: 50px'/>";
                        // echo "Uploads files thành công";
                    }else {
                        echo "Upload file thất bại";
                    }
                }else {
                    $error['file'] = $error_images;
                }
            }else {
                $error['file'] = 'Phải có hình cho pages';
            }
           
    
            if(empty($error)) {
         
                if(!pages_exists($page_title, $page_slug)) {
                    $data = array(
                        'page_title' => $page_title,
                        'page_slug' => $page_slug,
                        'page_content' => $page_content,
                        'page_status' => $page_status,
                        'user_id' => $user_id,
                        'images_pages' => $upload_file,
                    );
                add_pages($data);
                // show_array($data);
                
                }else {
                    $error['pages'] = "Đã có lỗi trong quá trình add";
                }
            } 
        }
        load_view('add');
    }else {
        redirect("?mod=users&action=login");
    }
    
   
}

function UpdatePageAction() {
    load('helper','format', 'users', 'data');
 
    $id = (int)$_GET['id'];
    $info_page = get_pages_by_id($id); 
    $data['info_page'] = $info_page; 
    $info_user = get_user_by_username(user_login());

    $images_old = $info_page['images_pages'];
    
    if(empty($info_user)) {
        redirect("?mod=users&action=login");
    }else {
        if(isset($_POST['btn-update'])) {
            // print_r($images_old);
            $error= array(); 
            // 
            if(empty($_POST['title'])) {
                $error['title'] = 'Tiêu đề Page không được trống';
            } else {
                $page_title = $_POST['title'];
            }
            
            // Kiểm tra slug
            if(empty($_POST['slug'])) { 
               $error['slug'] = 'Slug Page không được trống';
            } else {
                $page_slug = create_slug($_POST['slug']);
            }
    
            // //Kiểm tra mô tả
            if(empty($_POST['desc'])) {
                $error['desc'] = 'mô tả không đúng định dạng';
            } else {
                $page_content =$_POST['desc'];
            }
    
            if(empty($_POST['status'])) {
                $page_status = "draft";
            } else {
                $page_status = $_POST['status'];
            }
           
            if(empty($_FILES['file']['name'])){
                $upload_file = $images_old;
            }else {
                global $config;
                $error_images = array(); 
                $upload_dir = 'public/uploads/pages/';
                // Đường dẫn file sau khi upload
                $upload_file = $upload_dir.$_FILES['file']['name'];
                // Xử lý upload đúng file ảnh
                $type_allow = array('png', 'jpg', 'gift', 'jpeg');
                // pathinfo lấy đuôi file ảnh và có tham số PATHINFO_EXTENSION phía sau để lấy đuôi file
                $type = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if(!in_array(strtolower($type), $type_allow)) {
                    $error_images['file'] = "Chỉ được upload file có đuôi png, jpg, gif, jpeg";
                }else {
                    // Upload file có kích thước cho phép (<20MB ~ 29.000.000 Byte)
                    $file_size = $_FILES['file']['size'];
                    if($file_size > 29000000) {
                        $error_images['file'] = "chỉ được upload file bé hơn 20 MB";
                    } 
                    //Kiểm tra xem file đó trùng 1 file đã tồn tại trên hệ thống hay không
                    if(file_exists($upload_file)) {
                        $file_name = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                        $new_file_name = $file_name . ' - Copy.';
                        $new_upload_file = $upload_dir . $new_file_name . $type;
                        $k = 1;
                        while(file_exists($new_upload_file)) {
                            $new_file_name = $file_name . " - Copy({$k}).";
                            $k++;
                            $new_upload_file = $upload_dir . $new_file_name . $type; 
                        }
                        $upload_file = $new_upload_file;
                    }
                }
                if(empty($error_images)){
                    if($upload_file) {
                        // move_uploaded_file($image_tmp_name, $image_folder);
                        if($images_old != $upload_file AND $images_old != '') {
                            unlink($images_old);
                        }
                        move_uploaded_file($_FILES['file']['tmp_name'], $upload_file);
                        echo "<a href='$upload_file'>Download: {$_FILES['file']['name']}</a><br/>";
                        echo "<img src='{$upload_file}' style='height: 50px; width: 50px'/>";
                        // echo "Uploads files thành công";
                    }else {
                        echo "Upload file thất bại";
                    }
                }else {
                    $error['file'] = $error_images;
                }
               
            }
            
    
            if(empty($error)) {
                $data = array(
                    'page_title' => $page_title,
                    'page_slug' => $page_slug,
                    'page_content' => $page_content,
                    'page_status' => $page_status,
                    'user_id' => $info_page['user_id'],
                    'images_pages' => $upload_file,
                );
                update_pages($data, $id);
                // show_array($data);
                redirect("?mod=pages");
            }else {
                show_array($error);
            }
          
        }
        // $info_page = get_pages_by_id($id); 
        // $data['info_page'] = $info_page; 
        load_view('update', $data);
    }
   
}

function DeletePageAction() {
    $id = (int)$_GET['id'];
    $info_page = get_pages_by_id($id); 
    // show_array($info_page);
    if($info_page['images_pages']) {
        echo "đã vào trong";
        unlink($info_page['images_pages']);
    }
    delete_pages($id);
    redirect("?mod=pages&action=index");
}