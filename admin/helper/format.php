<?php
function currency_format($number, $suffix = 'đ'){
    return number_format($number).$suffix;
}

function show_status_page($status) {
    $list_status = array(
        'draft' => 'draft',
        'published' => 'published',
        'pending' => 'pending',
        'archived' => 'archived'
    );  
    
    if(array_key_exists($status, $list_status)) {
        return "<p class='$list_status[$status]'>$list_status[$status]</p>";
    }
}

function show_is_featured($featured) {
    $list_featured = array(
        '0' => 'Nổi bật',
        '1' => 'Bình thường'
    );  
    
    if(array_key_exists($featured, $list_featured)) {
        return $list_featured[$featured];
    }
}

function format_time($time) {
    $formattedTime = date("d:m:Y H:i:s", strtotime($time));
    return $formattedTime;
}