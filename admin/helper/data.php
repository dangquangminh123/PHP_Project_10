<?php

function show_array($data) {
    if (is_array($data)) {
        echo "<pre>";
        print_r($data);
        echo "<pre>";
    }
}

function has_child($data, $id) {
    foreach($data as $v) {
        if($v['parent_id'] == $id) return true;
    }
    return false;
}

function data_tree($data, $parent_id = 0, $level = 0) {
    $result = array();
    foreach($data as $v) {
        if($v['parent_id'] == $parent_id) {
            $v['level'] = $level;
            $result[] = $v;
            // $i=0;
            if(has_child($data, $v['category_id'])) {
                $result_child = data_tree($data, $v['category_id'], $level + 1);
                $result = array_merge($result, $result_child);
            }
            // $i++;
        }
    }
    return $result;
}