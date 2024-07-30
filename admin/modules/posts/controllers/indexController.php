<?php

function construct()
{
    //    echo "DÙng chung, load đầu tiên";
    load_model("index");
    load_model("post");
    load("lib", "validation");
    load("lib", "slug");
    load("lib", "pagging");
}

function indexAction()
{
    load("helper", "format", "users", "data");
    $time = date("d/m/Y h:m:s");

    $status_post = isset($_GET['postStatus'])?(string)$_GET['postStatus']:'';
     // Số lượng bản ghi trên trang
    $num_per_post = 12;
    $page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
    $start = ($page - 1) * $num_per_post;
    if($status_post !== '') {
        $num_row_post = get_row_post_status($status_post);
        $list_post = get_paginate_post($start, $num_per_post, "`post_status` = '$status_post'");
    } else {
        $num_row_post = get_row_posts();
        $list_post = get_paginate_post($start, $num_per_post, '');
    }
   
   

    // Tổng số bản ghi
    $total_post = $num_row_post;
    // Tổng số trang
    $num_post = ceil($total_post / $num_per_post);

    foreach ($list_post as &$posts) {
        $posts[
            "url_update"
        ] = "?mod=posts&action=UpdatePost&post_id={$posts["post_id"]}";
        $posts[
            "url_delete"
        ] = "?mod=posts&action=DeletePost&post_id={$posts["post_id"]}";
    }

    $info_user = user_login();
    $user_post = get_list_post_user();
    $post_images = get_list_post_images();
    $category_post = get_list_category_post();
    $data_user = get_user_by_username($info_user);
    $user_id = $data_user["user_id"];
    $all_post = get_list_post();
    $list_post_published = get_list_post_status('published');
    $list_post_draft = get_list_post_status('draft');
    $list_post_pending = get_list_post_status('pending');
    $list_post_archived = get_list_post_status('archived');
    // show_array($list_post);
    $data = [
        "all_post" => $all_post,
        "list_post" => $list_post,
        "page" => $page,
        "num_row_post" => $num_row_post,
        "user_post" => $user_post,
        "post_images" => $post_images,
        "category_post" => $category_post,
        "num_post" => $num_post,
        "total_post" => $total_post,
        "list_post_published" => $list_post_published,
        "list_post_draft" => $list_post_draft,
        "list_post_pending" => $list_post_pending,
        "list_post_archived" => $list_post_archived
    ];
    load_view("index", $data);
}

function AddImagesPostAction() {
    load("helper", "format", "users", "data");
    $all_cagory_post = get_list_category();

    $data = [
        "all_cagory_post" => $all_cagory_post,
    ];
    $time = date("d/m/Y h:m:s");
    global $error, $upload_file;
    $info_user = user_login();
    $data_user = get_user_by_username($info_user);
    $user_id = $data_user["user_id"];

    if ($info_user) {
        if (isset($_FILES['files'])) {
            // show_array($_FILES);
            global $config, $imgae_url, $new_file_name, $file_size, $error_images;
            $upload_dir = "public/uploads/post/";
            // Đường dẫn file sau khi upload
            $upload_file = $upload_dir . $_FILES["files"]["name"];
            $new_file_name= $_FILES["files"]["name"];
            // Xử lý upload đúng file ảnh
            $type_allow = ["png", "jpg", "gift", "jpeg"];
            // pathinfo lấy đuôi file ảnh và có tham số PATHINFO_EXTENSION phía sau để lấy đuôi file
            $type = pathinfo($_FILES["files"]["name"], PATHINFO_EXTENSION);
            if (!in_array(strtolower($type), $type_allow)) {
                $error_images = "Chỉ được upload file có đuôi png, jpg, gif, jpeg";
            } else {
                // Upload file có kích thước cho phép (<20MB ~ 29.000.000 Byte)
                $file_size = $_FILES["files"]["size"];
                if ($file_size > 29000000) {
                    $error_images = "chỉ được upload file bé hơn 20 MB";
                }
                //Kiểm tra xem file đó trùng 1 file đã tồn tại trên hệ thống hay không
                if (file_exists($upload_file)) {
                    $file_name = pathinfo(
                        $_FILES["files"]["name"],
                        PATHINFO_EXTENSION
                    );
                    $new_file_name = $file_name . " - Copy.";
                    $new_upload_file = $upload_dir . $new_file_name . $type;
                    $k = 1;
                    while (file_exists($new_upload_file)) {
                        $new_file_name = $file_name . " - Copy({$k}).";
                        $k++;
                        $new_upload_file = $upload_dir . $new_file_name . $type;
                    }
                    $upload_file = $new_upload_file;
                  
                }
            }
            if (empty($error_images)) {
                if ($upload_file) {
                   
                    move_uploaded_file($_FILES["files"]["tmp_name"], $upload_file);
                    $info_images = [
                        "image_url" => $upload_file,
                        "file_name" => $new_file_name,
                        "file_size" => $file_size,
                        "user_id" => $user_id,
                    ];
                    add_images($info_images);
                    $data_image = get_images($new_file_name, $user_id);
                    echo '<img src="'.$data_image['image_url'].'" style="height: 150px; width: 150px; overflow: hidden; border: 1px solid #ddd"/> <br>
                    <input type="hidden" name="images_id" value="'.$data_image['image_id'].'"/>';
                    return $data_image;         
                } else {
                    echo "Upload file thất bại";
                }
            } else {
                $error["images"] = $error_images;
                echo '<p class="error">'.$error["images"].'</p>';
                
            }
        } else {
            $error["images"] = "Phải có hình cho bài viết";
            echo '<p class="error">'.$error["images"].'</p>';
        }
        // load_view("add", $data);
    } else {
        redirect("?mod=users&action=login");
    }
}

function UpdateImagesPostAction() {
    load("helper", "format", "users", "data");
    $post_id = (int)$_POST["post_id"];
    $all_cagory_post = get_list_category();
    $info_post = get_post_by_id($post_id);
    $images_post = get_images_post($info_post['image_id']);
    $image_id_old = $images_post['image_id'];
    $images_old = $images_post['image_url'];
    $data = [
        "all_cagory_post" => $all_cagory_post,
    ];
    $time = date("d/m/Y h:m:s");
    global $error,
        $post_title,
        $post_slug,
        $desc_short,
        $content_post,
        $post_status,
        $category_post,
        $upload_file,
        $created_at;
    $info_user = user_login();
    $data_user = get_user_by_username($info_user);
    $user_id = $data_user["user_id"];

    if ($info_user) {
        if (isset($_FILES['files'])) {
            // show_array($_FILES);
            global $config, $imgae_url, $new_file_name, $file_size, $error_images;
            $upload_dir = "public/uploads/post/";
            // Đường dẫn file sau khi upload
            $upload_file = $upload_dir . $_FILES["files"]["name"];
            $new_file_name= $_FILES["files"]["name"];
            // Xử lý upload đúng file ảnh
            $type_allow = ["png", "jpg", "gift", "jpeg"];
            // pathinfo lấy đuôi file ảnh và có tham số PATHINFO_EXTENSION phía sau để lấy đuôi file
            $type = pathinfo($_FILES["files"]["name"], PATHINFO_EXTENSION);
            if (!in_array(strtolower($type), $type_allow)) {
                $error_images = "Chỉ được upload file có đuôi png, jpg, gif, jpeg";
            } else {
                // Upload file có kích thước cho phép (<20MB ~ 29.000.000 Byte)
                $file_size = $_FILES["files"]["size"];
                if ($file_size > 29000000) {
                    $error_images = "chỉ được upload file bé hơn 20 MB";
                }
                //Kiểm tra xem file đó trùng 1 file đã tồn tại trên hệ thống hay không
                if (file_exists($upload_file)) {
                   
                    $file_name = pathinfo(
                        $_FILES["files"]["name"],
                        PATHINFO_EXTENSION
                    );
                    $new_file_name = $file_name . " - Copy.";
                    $new_upload_file = $upload_dir . $new_file_name . $type;
                    $k = 1;
                    while (file_exists($new_upload_file)) {
                        $new_file_name = $file_name . " - Copy({$k}).";
                        $k++;
                        $new_upload_file = $upload_dir . $new_file_name . $type;
                    }
                    $upload_file = $new_upload_file;
                  
                }
            }
            if (empty($error_images)) {
                if ($upload_file) {
                    
                    move_uploaded_file($_FILES["files"]["tmp_name"], $upload_file);
                    $info_images = [
                        "image_url" => $upload_file,
                        "file_name" => $new_file_name,
                        "file_size" => $file_size,
                        "user_id" => $user_id,
                    ];
                    update_images($info_images, $image_id_old);
                    if($images_old != $upload_file AND $images_old != '') {
                        unlink($images_old);
                    }
                    $data_image = get_images($new_file_name, $user_id);
                    echo '<img src="'.$data_image['image_url'].'" style="height: 150px; width: 150px; overflow: hidden; border: 1px solid #ddd"/> <br>
                    <input type="hidden" name="images_id" value="'.$data_image['image_id'].'"/>';
                    return $data_image;         
                } else {
                    echo "Upload file thất bại";
                }
            } else {
                $error["images"] = $error_images;
                echo '<p class="error">'.$error["images"].'</p>';
                
            }
        } else {
            echo '<img src="'.$images_post['image_url'].'" style="height: 150px; width: 150px; overflow: hidden; border: 1px solid #ddd"/> <br>
            <input type="hidden" name="images_id" value="'.$image_id_old.'"/>';
            return $images_post;
        }
        // load_view("add", $data);
    } else {
        redirect("?mod=users&action=login");
    }
}

function AddPostAction()
{
    load("helper", "format", "users", "data");
    
    $time = date("d/m/Y h:m:s");
    global $error,
        $post_title,
        $post_slug,
        $desc_short,
        $content_post,
        $post_status,
        $category_post,
        $upload_file,
        $created_at;
    $info_user = user_login();
    $data_user = get_user_by_username($info_user);
    $user_id = $data_user["user_id"];
    $list_category_post = get_list_category();
    $category_post_format= data_tree($list_category_post);
    $data = [
        "category_post_format" => $category_post_format,
    ];
    if ($info_user) {
        if (isset($_POST["btn-addPost"])) {
            $error = [];
           
            if (empty($_POST["post_title"])) {
                $error["post_title"] = "Không được để trống tiêu đề bài viết";
            } else {
                if (is_title($_POST["post_title"])) {
                    $error["post_title"] = "tiêu đề không đúng định dạng";
                } else {
                    $post_title = $_POST["post_title"];
                    $post_slug = create_slug($_POST["post_title"]);
                }
            }

            // // Kiểm tra slug
            // if (empty($_POST["post_slug"])) {
            //     $error["post_slug"] = "Slug bài viết không được để trống";
            // } else {
            //     $post_slug = create_slug($_POST["post_slug"]);
            // }

            //Kiểm tra mô tả
            if (empty($_POST["desc_short"])) {
                $error["desc_short"] =
                    "Không được để trống mô tả ngắn bài viết";
            } else {
                if (is_description($_POST["desc_short"])) {
                    $error["desc_short"] = "mô tả ngắn không đúng định dạng";
                } else {
                    $desc_short = $_POST["desc_short"];
                }
            }

            if (empty($_POST["content_post"])) {
                $error["content_post"] =
                    "Không được để trống nội dung bài viết";
            } else {
                if (is_description($_POST["content_post"])) {
                    $error["content_post"] =
                        "nội dung bài viết không đúng định dạng";
                } else {
                    $content_post = $_POST["content_post"];
                }
            }

            if (empty($_POST["post_status"])) {
                $post_status = "draft";
            } else {
                $post_status = $_POST["post_status"];
            }

            // Kiểm tra có thuộc danh mục cha không
            if (empty($_POST["category_post"])) {
                $category_post = 0;
            } else {
                $category_post = $_POST["category_post"];
            }

            if(empty($_POST["images_id"])) {
                $error["images"] = "Vui lòng uploads hình ảnh bài viết";
            }else {
                $image_id = $_POST["images_id"];
            }

            if (empty($error)) {
                if (!post_exists($post_title, $post_slug)) {
                    $data = [
                        "post_title" => $post_title,
                        "post_slug" => $post_slug,
                        "post_excerpt" => $desc_short,
                        "post_content" => $content_post,
                        "post_status" => $post_status,
                        "user_id" => $user_id,
                        "category_id" => $category_post,
                        "image_id" => $image_id,
                    ];
                    add_posts($data);
                    // show_array($data);
                } else {
                    $error["posts"] = "Đã có lỗi trong quá trình add";
                }
            }
            redirect("?mod=posts&action=AddPost");
        }       
        load_view("add", $data);
    } else {
        redirect("?mod=users&action=login");
    }
}

function UpdatePostAction()
{
    load("helper", "format", "users", "data");

    $post_id = (int)$_GET["post_id"];
    $info_post = get_post_by_id($post_id);
    $list_category_post = get_list_category();
    $images_post = get_images_post($info_post['image_id']);
    $category_post_format= data_tree($list_category_post);
    // show_array($category_post_format);
    $info_user = get_user_by_username(user_login());
    $user_id = $info_user['user_id'];
    $data = [
        "info_post" => $info_post,
        "category_post_format" => $category_post_format,
        "images_post" => $images_post,
    ];

    if (empty($info_user)) {
        redirect("?mod=users&action=login");
    } else {
        if (isset($_POST["btn-update"])) {
            
            // print_r($images_old);
            $error = [];
           
            if (empty($_POST["post_title"])) {
                $error["post_title"] = "Không được để trống tiêu đề bài viết";
            } else {
                if (is_title($_POST["post_title"])) {
                    $error["post_title"] = "tiêu đề không đúng định dạng";
                } else {
                    $post_title = $_POST["post_title"];
                    $post_slug = create_slug($_POST["post_title"]);
                }
            }

            //Kiểm tra mô tả
            if (empty($_POST["desc_short"])) {
                $error["desc_short"] =
                    "Không được để trống mô tả ngắn bài viết";
            } else {
                if (is_description($_POST["desc_short"])) {
                    $error["desc_short"] = "mô tả ngắn không đúng định dạng";
                } else {
                    $desc_short = $_POST["desc_short"];
                }
            }

            if (empty($_POST["content_post"])) {
                $error["content_post"] =
                    "Không được để trống nội dung bài viết";
            } else {
                if (is_description($_POST["content_post"])) {
                    $error["content_post"] =
                        "nội dung bài viết không đúng định dạng";
                } else {
                    $content_post = $_POST["content_post"];
                }
            }

            if (empty($_POST["post_status"])) {
                $post_status = "draft";
            } else {
                $post_status = $_POST["post_status"];
            }

            // Kiểm tra có thuộc danh mục cha không
            if (empty($_POST["category_post"])) {
                $category_post = 0;
            } else {
                $category_post = $_POST["category_post"];
            }

            if(empty($_POST["images_id"])) {
                $error["images"] = "Vui lòng uploads hình ảnh bài viết";
            }else {
                $image_id = $_POST["images_id"];
            }
           
            if (empty($error)) {
                
                    $data = array(
                        "post_title" => $post_title,
                        "post_slug" => $post_slug,
                        "post_excerpt" => $desc_short,
                        "post_content" => $content_post,
                        "post_status" => $post_status,
                        "user_id" => $user_id,
                        "category_id" => $category_post,
                        "image_id" => $image_id,
                    );
                    update_post($data, $post_id);
                    // show_array($data); 
            }else {
               show_array($error);
            }
      
            redirect("?mod=posts");
        }
       
        load_view("update", $data);
    }
}

function indexCategoryAction()
{
    load("helper", "format", "users", "data");
    $time = date("d/m/Y h:m:s");
    // Số lượng bản ghi trên trang
    $num_per_category_post = 8;

    // Tổng số bản ghi
    $total_category_post = get_list_category();

    // Tổng số trang
    $num_category_post = ceil(
        count($total_category_post) / $num_per_category_post
    );

    $page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
    $start = ($page - 1) * $num_per_category_post;

    $list_category_post = get_paginate_category_post(
        $start,
        $num_per_category_post,
        ""
    );

    $list_parent_cagory_post = get_list_category_parent_post();
    $user_category_post = get_list_category_user();
    // show_array($category_parent);
    $result_category = data_tree($total_category_post);
    // show_array($result_category);
    foreach ($list_category_post as &$category_post) {
        $category_post[
            "url_update"
        ] = "?mod=posts&action=UpdateCategoryPost&id={$category_post["category_id"]}";
        $category_post[
            "url_delete"
        ] = "?mod=posts&action=DeleteCategoryPost&id={$category_post["category_id"]}";
    }

    $info_user = user_login();
    $data_user = get_user_by_username($info_user);
    $user_id = $data_user["user_id"];
    $data = [
        "list_category_post" => $list_category_post,
        "list_parent_cagory_post" => $list_parent_cagory_post,
        "page" => $page,
        "num_category_post" => $num_category_post,
        "total_category_post" => $total_category_post,
        "result_category" => $result_category,
        "user_category_post" => $user_category_post,
    ];
    load_view("ListCategory", $data);
}

function UpdateCategoryPostAction()
{
    load("helper", "format", "users", "data");

    $id = (int) $_GET["id"];
    $info_category_post = get_category_post_by_id($id);
    $info_user = get_user_by_username(user_login());
    $list_parent_cagory_post = get_all_category_parent_post($id);

    $data = [
        "info_category_post" => $info_category_post,
        "list_parent_cagory_post" => $list_parent_cagory_post,
    ];

    if (empty($info_user)) {
        redirect("?mod=users&action=login");
    } else {
        if (isset($_POST["category-update"])) {
            // print_r($images_old);
            $error = [];
            if (empty($_POST["category_name"])) {
                $error["category_name"] =
                    "Không được để trống tiêu đề danh mục";
            } else {
                if (is_title($_POST["category_name"])) {
                    $error["category_name"] =
                        "Tiêu đề danh mục chứa ký tự không hợp lệ";
                } else {
                    $category_name = $_POST["category_name"];
                    $category_slug = create_slug($_POST["category_name"]);
                }
            }

            //Kiểm tra mô tả
            if (empty($_POST["category_desc"])) {
                $error["category_desc"] = "Không được để trống mô tả danh mục";
            } else {
                if (is_description($_POST["category_desc"])) {
                    $error["category_desc"] =
                        "mô tả danh mục không đúng định dạng";
                } else {
                    $category_desc = $_POST["category_desc"];
                }
            }

            // Kiểm tra có thuộc danh mục cha không
            if (empty($_POST["parent_category"])) {
                $parent_id = $info_category_post["parent_id"];
            } else {
                $parent_id = $_POST["parent_category"];
            }

            if (empty($error)) {
                if (category_post_exists($category_name, $category_slug)) {
                    $info_category = [
                        "category_name" => $category_name,
                        "category_slug" => $category_slug,
                        "category_desc" => $category_desc,
                        "user_id" => $info_category_post["user_id"],
                        "parent_id" => $parent_id,
                    ];
                    // show_array($info_category);
                    update_category_post($info_category, $id);
                    redirect("?mod=posts&action=indexCategory");
                } else {
                    $error["category_post"] =
                        "Đã có lỗi trong quá trình Update";
                }
            } else {
                show_array($error);
            }
        }
        load_view("updateCategories", $data);
    }
}

function AddCategoriesAction()
{
    load("helper", "format", "users", "data");
    $list_parent_category_post = get_list_category_parent_post();

    $data = [
        "list_parent_category_post" => $list_parent_category_post,
    ];
    $time = date("d/m/Y h:m:s");
    global $error,
        $category_name,
        $category_slug,
        $category_desc,
        $user_id,
        $created_at;
    $info_user = user_login();
    $data_user = get_user_by_username($info_user);
    $user_id = $data_user["user_id"];
    if ($info_user) {
        if (isset($_POST["category-add"])) {
            $error = [];

            if (empty($_POST["category_name"])) {
                $error["category_name"] =
                    "Không được để trống tiêu đề danh mục";
            } else {
                if (is_title($_POST["category_name"])) {
                    $error["category_name"] =
                        "Tiêu đề danh mục chứa ký tự không hợp lệ";
                } else {
                    $category_name = $_POST["category_name"];
                    $category_slug = create_slug($_POST["category_name"]);
                }
            }

            //Kiểm tra mô tả
            if (empty($_POST["category_desc"])) {
                $error["category_desc"] = "Không được để trống mô tả danh mục";
            } else {
                if (is_description($_POST["category_desc"])) {
                    $error["category_desc"] =
                        "mô tả danh mục không đúng định dạng";
                } else {
                    $category_desc = $_POST["category_desc"];
                }
            }

            // Kiểm tra có thuộc danh mục cha không
            if (empty($_POST["parent_category"])) {
                $parent_id = 0;
            } else {
                $parent_id = $_POST["parent_category"];
            }

            if (empty($error)) {
                if (!category_post_exists($category_name, $category_slug)) {
                    $info_category = [
                        "category_name" => $category_name,
                        "category_slug" => $category_slug,
                        "category_desc" => $category_desc,
                        "user_id" => $user_id,
                        "parent_id" => $parent_id,
                    ];
                    // show_array($info_category);
                    add_category_post($info_category);
                } else {
                    $error["category_post"] = "Đã có lỗi trong quá trình add";
                }
            }
        }

        load_view("addCategories", $data);
    } else {
        redirect("?mod=users&action=login");
    }
}

function DeleteCategoryPostAction()
{
    $id = (int) $_GET["id"];
    $info_category_post = get_category_post_by_id($id);
    show_array($info_category_post);
    delete_category_posts($id);
    redirect("?mod=posts&action=indexCategory");
}

function DeletePostAction() {
    $post_id = (int)$_GET['post_id'];
    $info_post = get_post_by_id($post_id);
    $list_category_post = get_list_category();
    $images_post = get_images_post($info_post['image_id']);
    // show_array($info_page);
    if($images_post['image_url']) {
        unlink($images_post['image_url']);
        delete_images($info_post['image_id']);
    }
    delete_posts($post_id);
    redirect("?mod=posts&action=index");
}
