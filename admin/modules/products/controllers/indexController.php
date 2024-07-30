<?php

function construct()
{
    load_model("index");
    load_model("product");
    load("lib", "validation");
    load("lib", "slug");
    load("lib", "pagging");
}

function logoutAction()
{
    unset($_SESSION['is_login']);
    unset($_SESSION['user_login']);
    redirect("?mod=users&action=login");
}

function resetAction()
{
    global $error, $pass_old, $pass_new, $confirm_pass;
    if (isset($_POST['btn-resetPass'])) {
        $error = array();

        // Kiểm tra mật khẩu cũ
        if (empty($_POST['pass-old'])) {
            $error['pass-old'] = "Không được để trống mật khẩu cũ";
        } else {
            if (!is_password($_POST['pass-old'])) {
                $error['pass-old'] = 'Mật khẩu cũ không đúng định dạng';
            } else {
                $pass_old = md5($_POST['pass-old']);
            }
        }

        // Kiểm tra mật khẩu mới
        if (empty($_POST['pass-new'])) {
            $error['pass-new'] = "Không được để trống mật khẩu mới";
        } else {
            if (!is_password($_POST['pass-new'])) {
                $error['pass-new'] = 'mật khẩu mới không đúng định dạng';
            } else {
                $pass_new = md5($_POST['pass-new']);
            }
        }

        // Kiểm tra xác thực mật khẩu mới
        if (empty($_POST['confirm-pass'])) {
            $error['confirm-pass'] = "Bạn cần nhập xác thực mật khẩu mới";
        } else {
            if (!is_password($_POST['confirm-pass'])) {
                $error['confirm-pass'] = 'Xác thực mật khẩu mới không đúng định dạng';
            } else {
                $confirm_pass = md5($_POST['confirm-pass']);
            }
        }

        // Kết luận
        if (empty($error)) {
            if (old_password_exists($pass_old)) {
                // $_SESSION['is_login'] = true;
                // $_SESSION['user_login'] = $username;
                if ($pass_new === $confirm_pass) {
                    $data = array(
                        'password' => $confirm_pass,
                    );
                    update_password_new(user_login(), $data);

                    // Chuyển trang điều hướng
                    redirect("?mod=users&controller=team");
                } else {
                    $error['account'] = "Mật khẩu mới không khớp với xác thực mật khẩu! Vui lòng nhập lại cho đúng";
                }
            } else {
                $error['account'] = "Mật khẩu cũ không chính xác! vui lòng nhập lại";
            }
        }
    }
    load_view('reset');
}

function AddProductAction()
{
    load("helper", "format", "users", "data");

    $time = date("d/m/Y h:m:s");
    global $error,
    $product_name,
    $product_slug,
    $product_desc,
    $product_details,
    $product_price,
    $stock_quantity,
    $featured,

    $product_status,
    $category_product,
    $images_product,
        $created_at;
    $info_user = user_login();
    $data_user = get_user_by_username($info_user);
    $user_id = $data_user["user_id"];
    $all_category_product = get_list_category();
    $category_product_format = data_tree($all_category_product);
    $data = [
        "category_product_format" => $category_product_format,
    ];

    if ($info_user) {
        if (isset($_POST["addProduct"])) {
            $error = [];
            $show_featured = array(
                '0' => 'Nổi bật',
                '1' => 'Bình thường',
            );
            if (empty($_POST["product_name"])) {
                $error["product_name"] = "Không được để trống tên sản phẩm";
            } else {
                if (is_title($_POST["product_name"])) {
                    $error["product_name"] = "Tên sản phẩm không đúng định dạng";
                } else {
                    $product_name = $_POST["product_name"];
                    $product_slug = create_slug($_POST["product_name"]);
                }
            }

            //Kiểm tra mô tả
            if (empty($_POST["product_desc"])) {
                $error["product_desc"] = "Không được để trống mô tả ngắn sản phẩm";
            } else {
                if (is_description($_POST["product_desc"])) {
                    $error["product_desc"] = "mô tả ngắn không đúng định dạng";
                } else {
                    $product_desc = $_POST["product_desc"];
                }
            }

            if (empty($_POST["product_details"])) {
                $error["product_details"] = "Không được để trống nội dung sản phẩm";
            } else {
                if (is_description($_POST["product_details"])) {
                    $error["product_details"] =
                        "nội dung sản phẩm không đúng định dạng";
                } else {
                    $product_details = $_POST["product_details"];
                }
            }

            if (empty($_POST['product_price'])) {
                $error['product_price'] = "Giá sản phẩm không được trống";
            } else {
                $product_price = $_POST['product_price'];
            }

            if (empty($_POST['stock_quantity'])) {
                $error['stock_quantity'] = "Số lượng sản phẩm không được trống";
            } else {
                $stock_quantity = $_POST['stock_quantity'];
            }

            if (empty($_POST["featured"])) {
                $featured = $show_featured[0];
            } else {

                $featured = $show_featured[$_POST["featured"]];
            }

            if (empty($_POST["product_status"])) {
                $product_status = "active";
            } else {
                $product_status = $_POST["product_status"];
            }

            // Kiểm tra có thuộc danh mục cha không
            if (empty($_POST["category_product"])) {
                $error["category_product"] = "Phải chọn danh mục sản phẩm của sản phẩm này";
            } else {
                $category_product = $_POST["category_product"];
            }

            if (empty($error)) {
                if (!products_exists($product_name, $product_slug)) {
                    $data = [
                        "product_name" => $product_name,
                        "product_slug" => $product_slug,
                        "product_desc" => $product_desc,
                        "product_details" => $product_details,
                        "product_price" => $product_price,
                        "stock_quantity" => $stock_quantity,
                        "is_featured" => $featured,
                        "product_status" => $product_status,
                        "user_id" => $user_id,
                        "category_id" => $category_product,
                    ];
                    // show_array($data);
                    add_product($data);
                    $imageIds = explode(',', $_POST['image_ids']);
                    $thumbId = $_POST['thumb_id'];
                    $data_product = get_products($product_name, $user_id);
                    $product_id = $data_product['product_id'];
                    $product_images = [];
                    for ($i = 0; $i < count($imageIds); $i++) {
                        if ($imageIds[$i] === $thumbId) {
                            $product_images = [
                                "product_id" => $product_id,
                                "image_id" => $imageIds[$i],
                                "pin" => 1,
                            ];
                            add_product_imgages($product_images);
                        } else {
                            $product_images = [
                                "product_id" => $product_id,
                                "image_id" => $imageIds[$i],
                                "pin" => 0,
                            ];
                            add_product_imgages($product_images);
                        }
                    }
                    // show_array($product_images);
                } else {
                    $error["products"] = "Đã có lỗi trong quá trình add";
                }
            } else {
                show_array($error);
            }

            redirect("?mod=products");
        } else {
            show_array($error);
        }
        load_view("addProduct", $data);
    } else {
        redirect("?mod=users&action=login");
    }
}

function UpdateProductAction()
{
    load("helper", "format", "users", "data");

    if ($_GET["product_id"]) {
        $product_id = (int) $_GET["product_id"];
    } else {
        $product_id = null;
    }

    $info_product = get_product_by_id($product_id);
    $list_category_product = get_list_category();
    $data_id_images_product = all_id_images_products($product_id);
    $all_id_product_images = get_all_id_images_product($product_id);
    $image_ids = array_column($all_id_product_images, 'image_id');
    $list_id_images = implode(",", $image_ids);

    // show_array($all_id_product_images);
    $images_info = [];
    foreach ($data_id_images_product as $key => $info_images) {
        $detail_images = get_id_images_product($info_images['image_id']);
        $images_info[] = array(
            'image_id' => $detail_images['image_id'],
            'image_url' => $detail_images['image_url'],
            'pin' => $info_images['pin'],
            'product_id' => $product_id,
        );
    }

    $category_product_format = data_tree($list_category_product);
    // show_array($category_product_format);
    $info_user = get_user_by_username(user_login());
    $user_id = $info_user['user_id'];
    $data = [
        "info_product" => $info_product,
        "category_product_format" => $category_product_format,
        "images_info" => $images_info,
        "list_id_images" => $list_id_images,
    ];
    $error = [];

    if (isset($_POST['updateProduct'])) {
        $show_featured = array(
            '0' => 'Nổi bật',
            '1' => 'Bình thường',
        );

        // Kiểm tra fullname
        if (empty($_POST['product_name'])) {
            $error['product_name'] = "Không được để trống họ và tên";
        } else {
            if (is_title($_POST['product_name'])) {
                $error['product_name'] = 'tên sản phẩm không đúng định dạng';
            } else {
                $product_name = $_POST['product_name'];
                $product_slug = create_slug($_POST["product_name"]);
            }
        }

        //Kiểm tra mô tả
        if (empty($_POST["product_desc"])) {
            $error["product_desc"] = "Không được để trống mô tả ngắn sản phẩm";
        } else {
            if (is_description($_POST["product_desc"])) {
                $error["product_desc"] = "mô tả ngắn không đúng định dạng";
            } else {
                $product_desc = $_POST["product_desc"];
            }
        }

        if (empty($_POST["product_details"])) {
            $error["product_details"] = "Không được để trống nội dung sản phẩm";
        } else {
            if (is_description($_POST["product_details"])) {
                $error["product_details"] =
                    "nội dung sản phẩm không đúng định dạng";
            } else {
                $product_details = $_POST["product_details"];
            }
        }

        if (empty($_POST['product_price'])) {
            $error['product_price'] = "Giá sản phẩm không được trống";
        } else {
            $product_price = $_POST['product_price'];
        }

        if (empty($_POST['stock_quantity'])) {
            $error['stock_quantity'] = "Số lượng sản phẩm không được trống";
        } else {
            $stock_quantity = $_POST['stock_quantity'];
        }

        if (empty($_POST["featured"])) {
            $featured = $show_featured[0];
        } else {

            $featured = $show_featured[$_POST["featured"]];
        }

        if (empty($_POST["product_status"])) {
            $product_status = "active";
        } else {
            $product_status = $_POST["product_status"];
        }

        // Kiểm tra có thuộc danh mục cha không
        if (empty($_POST["category_product"])) {
            $error["category_product"] = "Phải chọn danh mục sản phẩm của sản phẩm này";
        } else {
            $category_product = $_POST["category_product"];
        }

        if (empty($error)) {
            if (products_exists($product_name, $product_slug)) {
                $data = [
                    "product_name" => $product_name,
                    "product_slug" => $product_slug,
                    "product_desc" => $product_desc,
                    "product_details" => $product_details,
                    "product_price" => $product_price,
                    "stock_quantity" => $stock_quantity,
                    "is_featured" => $featured,
                    "product_status" => $product_status,
                    "user_id" => $user_id,
                    "category_id" => $category_product,
                ];
                update_product($data, $product_id);

                $imageIds = [];

                if (isset($_POST['image_ids'])) {
                    $imageIds = explode(',', $_POST['image_ids']);
                }

                $thumbId = $_POST['thumb_id'];
                // echo $thumbId;
                $data_product = get_products($product_name, $user_id);
                // echo show_array($imageIds);
                $length_imageId = count($imageIds);
                $product_images = [];
                $product_id = $data_product['product_id'];

                for ($i = 0; $i < $length_imageId; $i++) {
                    $product_images[] = [
                        "image_id" => $imageIds[$i],
                        "pin" => $imageIds[$i] === $thumbId ? 1 : 0,
                    ];
                }

                update_product_images($product_images, $product_id);
            } else {
                $error["products"] = "Đã có lỗi trong quá trình add";
            }

        } else {
            show_array($error);
        }
        // load_view('update', $data);
        redirect("?mod=products");
    }
    $info_user = get_user_by_username(user_login());
    // show_array($info_user);
    if (empty($info_user)) {
        redirect("?mod=users&action=login");
    } else {
        $data['info_user'] = $info_user;
        load_view('update', $data);
    }
}

function addImagesProductAction()
{
    $productId = $_GET['product_id'] ?? null;

    if (!empty($productId)) {
        // Remove all image by product and img id
        //Step 1: select all image by product id
        $imgListByProductId = all_id_images_products($productId);
        $imgListIds = [];

        if (!empty($imgListByProductId)) {
            foreach ($imgListByProductId as $productImg) {
                $imgListIds[] = $productImg['image_id'];
            }

            // Get all link img - để tý unlink
            $imgLinks = getUrlImages($imgListIds);
        }

        if (!empty($imgListIds)) {
            //Step 2: delete img and product_img
            $delete = deleteAllImgByProductId($productId, $imgListIds);

            //nếu nhận về false thì trả lỗi và kết thúc
            if (!$delete) {
                $results = [
                    'success' => false,
                    'data' => [],
                    'messages' => ['Không thể xóa ảnh cũ vui lòng thử lại sau!'],
                    'code' => 400,
                ];

                echo json_encode($results);exit();
            }

            //nếu k lỗi thì unlink
            if (!empty($imgLinks)) {
                foreach ($imgLinks as $imgLink) {
                    unlink("{$imgLink['image_url']}");
                }
            }
        }
    }

    load("helper", "format", "users", "data");
    global $error, $upload_file;

    $allPostCategories = get_list_category();

    $data = [
        "all_cagory_post" => $allPostCategories,
    ];

    $time = date("d/m/Y h:m:s");
    $userInfo = user_login();
    $user = get_user_by_username($userInfo);
    $user_id = $user["user_id"];
    $messages = [];
    $dataRs = [];
    $code = 400;

    //Đây là thông tin mình muốn trả về cho view bằng json array gồm trạng thái, mảng data, messages, http_code
    $results = [
        'success' => false,
        'data' => $dataRs,
        'messages' => $messages,
        'code' => $code,
    ];

    if ($userInfo) {
        if (!empty($_FILES['files'])) {
            // File upload configuration
            $targetDir = "public/uploads/product/";
            $allowTypes = ["png", "jpg", "gift", "jpeg"];
            $images_arr = [];
            $data_images = [];
            $info_images = [];
            $key = 0;
            foreach ($_FILES['files']['name'] as $val) {
                $file_name = $_FILES['files']['name'][$key];
                $tmp_name = $_FILES['files']['tmp_name'][$key];
                $file_size = $_FILES['files']['size'][$key];
                $file_type = $_FILES['files']['type'][$key];
                $error = $_FILES['files']['error'][$key];
                $upload_file = $targetDir . $_FILES["files"]["name"][$key];
                $type = pathinfo($file_name, PATHINFO_EXTENSION);
                $isValid = true;

                // File upload path
                if (!in_array(strtolower($type), $allowTypes)) {
                    //Khi 1 lỗi xảy ra thì đưa message lỗi và mảng, đánh cờ lỗi $isValid = false; và mã lỗi 422 là lỗi validation
                    $messages[] = "Chỉ được upload file có đuôi png, jpg, gif, jpeg";
                    $isValid = false;
                    $code = 422;
                } else {
                    // Upload file có kích thước cho phép (<20MB ~ 29.000.000 Byte)
                    if ($file_size > 29000000) {
                        $messages[] = "chỉ được upload file bé hơn 20 MB";
                        $isValid = false;
                        $code = 422;
                    }
                    //Kiểm tra xem file đó trùng 1 file đã tồn tại trên hệ thống hay không
                    if (file_exists($upload_file)) {
                        $fileName = pathinfo(
                            $file_name,
                            PATHINFO_EXTENSION
                        );

                        $new_file_name = basename($upload_file) . " - Copy";
                        $new_upload_file = "{$targetDir}{$new_file_name}.{$type}";
                        $k = 1;
                        while (file_exists($new_upload_file)) {
                            $new_file_name = $file_name . " - Copy({$k})";
                            $k++;
                            $new_upload_file = "{$targetDir}{$new_file_name}.{$type}";
                        }
                        $upload_file = $new_upload_file;
                    }
                }

                if ($isValid) {
                    if ($upload_file) {
                        move_uploaded_file($tmp_name, $upload_file);

                        $info_images[] = [
                            "image_url" => $upload_file,
                            "file_name" => basename($upload_file),
                            "file_size" => $file_size,
                            "user_id" => $user_id,
                        ];
                    } else {
                        $messages[] = "Upload file {$file_name} thất bại";
                        $code = 422;
                    }
                }

                $key++;
            }
            $imagesIds = [];
            $countInfoImg = count($info_images);

            if ($countInfoImg) {
                for ($i = 0; $i < $countInfoImg; $i++) {
                    add_images($info_images[$i]);
                    $data = get_images($info_images[$i]['file_name'], $user_id);

                    if ($i == 0) {
                        // phần này nếu img đầu tiên thì chọn nó làm thump bằng cách thêm cờ is_thump vào mảng data
                        $data = array_merge($data, ['is_thump' => true]);
                    }

                    $dataRs[] = $data;
                }
                $code = 200;
            }
        }
    } else {
        $messages[] = 'Please login!.';
        $code = 401;
    }

    $results = [
        'success' => false,
        'data' => $dataRs,
        'message' => $messages,
        'code' => $code,
    ];

    //Trả về view 1 json data
    echo json_encode($results);exit();
}

function DeleteProductAction()
{
    $product_id = (int) $_GET['product_id'];
    $info_product = get_product_by_id($product_id);
    //  show_array($info_product);
    if (!empty($product_id)) {
        // Remove all image by product and img id
        //Step 1: select all image by product id
        $allImgByProductId = all_id_images_products($product_id);
        // show_array($allImgByProductId);
        $imgListIds = [];
        if (!empty($allImgByProductId)) {

            foreach ($allImgByProductId as $productImg) {
                $imgListIds[] = $productImg['image_id'];
                // echo $productImg['image_id'];
            }
            foreach ($imgListIds as $image_id) {
                delete_product_images($product_id, $image_id);
            }

            // Get all link img - để tý unlink
            $imgLinks = getUrlImages($imgListIds);
            // show_array($imgLinks);
            //nếu k lỗi thì unlink
            if (!empty($imgLinks)) {
                foreach ($imgLinks as $imgLink) {
                    // show_array($imgLink);
                    if (file_exists($imgLink['image_url'])) {
                        unlink($imgLink['image_url']);
                    }
                }
            }

            foreach ($imgListIds as $image_id) {
                delete_images($image_id);
            }

        }
        delete_product($product_id);
    }
    redirect("?mod=products&action=index");
}

function indexCategoryAction()
{
    load("helper", "format", "users", "data");
    $time = date("d/m/Y h:m:s");
    // Số lượng bản ghi trên trang
    $num_per_category_product = 8;

    // Tổng số bản ghi
    $total_category_product = get_list_category();

    // Tổng số trang
    $num_category_product = ceil(
        count($total_category_product) / $num_per_category_product
    );

    $page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
    $start = ($page - 1) * $num_per_category_product;

    $list_category_product = get_paginate_category_product(
        $start,
        $num_per_category_product,
        ""
    );

    $list_parent_cagory_product = get_list_category_parent_product();
    $user_category_product = get_list_category_user();
    // show_array($category_parent);
    $result_category = data_tree($total_category_product);
    // show_array($result_category);
    foreach ($list_category_product as &$category_product) {
        $category_product[
            "url_update"
        ] = "?mod=products&action=UpdateCategoryProduct&category_id={$category_product["category_id"]}";
        $category_product[
            "url_delete"
        ] = "?mod=products&action=DeleteCategoryProduct&category_id={$category_product["category_id"]}";
    }

    $info_user = user_login();
    $data_user = get_user_by_username($info_user);
    $user_id = $data_user["user_id"];
    $data = [
        "list_category_product" => $list_category_product,
        "list_parent_cagory_product" => $list_parent_cagory_product,
        "page" => $page,
        "num_category_product" => $num_category_product,
        "total_category_product" => $total_category_product,
        "result_category" => $result_category,
        "user_category_product" => $user_category_product,
    ];
    load_view("ListCategory", $data);
}

function UpdateCategoryProductAction()
{
    load("helper", "format", "users", "data");

    $category_id = (int) $_GET["category_id"];
    $info_category_product = get_category_product_by_id($category_id);
    $info_user = get_user_by_username(user_login());
    $list_parent_category_product = get_all_category_parent_product($category_id);

    $data = [
        "info_category_product" => $info_category_product,
        "list_parent_category_product" => $list_parent_category_product,
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
                $parent_id = 0;
            } else {
                $parent_id = $_POST["parent_category"];
            }

            if (empty($error)) {
                if (category_product_exists($category_name, $category_slug)) {
                    $info_category = [
                        "category_name" => $category_name,
                        "category_slug" => $category_slug,
                        "category_desc" => $category_desc,
                        "user_id" => $info_category_post["user_id"],
                        "parent_id" => $parent_id,
                    ];
                    // show_array($info_category);
                    update_category_product($info_category, $category_id);
                    redirect("?mod=products&action=indexCategory");
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
    $list_parent_category_product = get_list_category_parent_product();

    $data = [
        "list_parent_category_product" => $list_parent_category_product,
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
                if (!category_product_exists($category_name, $category_slug)) {
                    $info_category = [
                        "category_name" => $category_name,
                        "category_slug" => $category_slug,
                        "category_desc" => $category_desc,
                        "user_id" => $user_id,
                        "parent_id" => $parent_id,
                    ];
                    // show_array($info_category);
                    add_category_product($info_category);
                    redirect("?mod=products&action=indexCategory");
                } else {
                    $error["category_product"] = "Đã có lỗi trong quá trình add";
                }
            }
        }

        load_view("addCategories", $data);
    } else {
        redirect("?mod=users&action=login");
    }
}

function DeleteCategoryProductAction()
{
    $category_id = (int) $_GET["category_id"];
    $info_category_product = get_category_product_by_id($category_id);
    delete_category_product($category_id);
    redirect("?mod=products&action=indexCategory");
}

function indexAction()
{
    load("helper", "format", "users", "data");
    $time = date("d/m/Y h:m:s");
    $info_user = user_login();
    $data_user = get_user_by_username($info_user);
    $user_id = $data_user["user_id"];
    if ($info_user) {
        $productProduct = isset($_GET['products_status']) ? (string) $_GET['products_status'] : '';
        // Số lượng bản ghi trên trang
        $num_per_products = 11;
        $page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
        $start = ($page - 1) * $num_per_products;
        if ($productProduct !== '') {
            $num_row_product = get_row_product_status($productProduct);
            $list_product = get_paginate_product($start, $num_per_products, "`product_status` = '$productProduct'");
        } else {
            $num_row_product = get_row_product();
            $list_product = get_paginate_product($start, $num_per_products, '');
        }
    
        // Tổng số bản ghi sản phẩm
        $total_product = $num_row_product;
    
        // Tổng số sản phẩm
        $num_product = ceil($total_product / $num_per_products);
    
        foreach ($list_product as &$products) {
            $products[
                "url_update"
            ] = "?mod=products&action=UpdateProduct&product_id={$products["product_id"]}";
            $products[
                "url_delete"
            ] = "?mod=products&action=DeleteProduct&product_id={$products["product_id"]}";
        }
        $product_images = get_thumb_product_images();
        $info_user = user_login();
        $user_product = get_list_product_user();
    
        $category_product = get_list_category_products();
        $data_user = get_user_by_username($info_user);
        $user_id = $data_user["user_id"];
        $all_product = get_list_product();
        $list_product_active = get_list_product_status('active');
        $list_product_inactive = get_list_product_status('inactive');
        $list_product_outofstock = get_list_product_status('out_of_stock');
    
        // show_array($list_product);
        $data = [
            "all_product" => $all_product,
            "list_product" => $list_product,
            "page" => $page,
            "num_row_product" => $num_row_product,
            "user_product" => $user_product,
            "product_images" => $product_images,
            "category_product" => $category_product,
            "num_product" => $num_product,
            "total_product" => $total_product,
            "list_product_active" => $list_product_active,
            "list_product_inactive" => $list_product_inactive,
            "list_product_outofstock" => $list_product_outofstock,
        ];
        load_view("index", $data);
     } else {
        redirect("?mod=users&action=login");
    }
    
}

// function AddImagesProductAction() {
//     load("helper", "format", "users", "data");
//     $all_cagory_post = get_list_category();

//     $data = [
//         "all_cagory_post" => $all_cagory_post,
//     ];
//     $time = date("d/m/Y h:m:s");
//     global $error, $upload_file;
//     $info_user = user_login();
//     $data_user = get_user_by_username($info_user);
//     $user_id = $data_user["user_id"];

//     if ($info_user) {
//         if(!empty($_FILES['files'])){
//             // File upload configuration
//             $targetDir = "public/uploads/product/";
//             $allowTypes = array("png", "jpg", "gift", "jpeg");
//             $images_arr = array();
//             $data_images = [];
//             $info_images = [];
//             $key = 0;
//             foreach($_FILES['files']['name'] as $val){
//                 $file_name  = $_FILES['files']['name'][$key];
//                 $tmp_name   = $_FILES['files']['tmp_name'][$key];
//                 $file_size  = $_FILES['files']['size'][$key];
//                 $file_type  = $_FILES['files']['type'][$key];
//                 $error      = $_FILES['files']['error'][$key];
//                 $upload_file = $targetDir . $_FILES["files"]["name"][$key];
//                 $new_file_name  = $_FILES['files']['name'][$key];
//                 $type = pathinfo($file_name, PATHINFO_EXTENSION);

//                 // File upload path
//                 if (!in_array(strtolower($type), $allowTypes)) {
//                     $error_images = "Chỉ được upload file có đuôi png, jpg, gif, jpeg";
//                 } else {
//                     // Upload file có kích thước cho phép (<20MB ~ 29.000.000 Byte)
//                     if ($file_size > 29000000) {
//                         $error_images = "chỉ được upload file bé hơn 20 MB";
//                     }
//                     //Kiểm tra xem file đó trùng 1 file đã tồn tại trên hệ thống hay không
//                     if (file_exists($upload_file)) {
//                         $fileName = pathinfo(
//                             $file_name,
//                             PATHINFO_EXTENSION
//                         );
//                         $new_file_name = $file_name . " - Copy.";
//                         $new_upload_file = $targetDir . $new_file_name . $type;
//                         $k = 1;
//                         while (file_exists($new_upload_file)) {
//                             $new_file_name = $file_name . " - Copy({$k}).";
//                             $k++;
//                             $new_upload_file = $targetDir . $new_file_name . $type;
//                         }
//                         $upload_file = $new_upload_file;
//                     }
//                 }

//                 if (empty($error_images)) {
//                     if ($upload_file) {
//                         move_uploaded_file($tmp_name, $upload_file);
//                         $info_images[] = [
//                             "image_url" => $upload_file,
//                             "file_name" => $new_file_name,
//                             "file_size" => $file_size,
//                             "user_id" => $user_id,
//                         ];

//                     } else {
//                         echo "Upload file ". $file_name ." thất bại";
//                         return;
//                     }
//                 } else {
//                     echo '<p class="error">'.$error_images.'</p>';
//                     return;
//                 }

//                 $key++;
//             }
//             // show_array($info_images);

//             $imagesIds = [];

//             if (count($info_images) == count($_FILES['files']['name'])) {
//                 for ($i=0; $i < count($info_images); $i++) {
//                     add_images($info_images[$i]);
//                     $data = get_images($info_images[$i]['file_name'], $user_id);
//                     if($i == 0 ) {
//                         echo '<img src="'.$data['image_url'].'" class="product-image selected" data-image-id="'.$data['image_id'].'";/> <br>
//                         <input id="thumb-image" type="hidden" name="thumb_id" value="'.$data['image_id'].'">'; // sửa mỗi chỗ này thôi
//                     }else {
//                         echo '<img src="'.$data['image_url'].'" class="product-image" data-image-id="'.$data['image_id'].'";/> <br>';
//                     }

//                     $data_image[] = $data;
//                     $imageIds[] = $data['image_id'];
//                 }
//             }

//             echo '<input type="hidden" name="image_ids" value="' . implode(",", $imageIds) . '">';

//             // return $data_images;
//         };
//         // load_view("add", $data);
//     } else {
//         redirect("?mod=users&action=login");
//     }
// }
