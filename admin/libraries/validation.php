<?php 
    function is_username($username) {
        $partten = "/^[A-Za-z0-9_\.]{6,32}$/";
        if(!preg_match($partten, $username, $matchs) ) {
            return false;
        }
        return true;
    }

    function is_title($title) {
        $partten = '/^[A-Za-z0-9_\.]{6,32}$/';
        if(!preg_match($partten, $title, $matchs)) {
            return false;
        }
        return true;
    }

    
    function is_password($password) {
        $partten = "/^([A-Z]){1}([\w_\.!@#$%^&*()]+){5,31}$/";
        if(!preg_match($partten, $password, $matchs) ) {
            return false;
        }
        return true;
    }

    function is_fullname($fullname) {
        $partten = "/^[a-zA-Z]{4,}(?: [a-zA-Z]+){0,2}$/";
        if(!preg_match($partten, $fullname, $matchs) ) {
            return false;
        }
        return true;
    }

    function is_email($email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    function is_phonenumber($phone_number) {
        $partten = "/^[0-9\-\+]{9,15}$/";
        if(!preg_match($partten, $phone_number, $matchs) ) {
            return false;
        }
        return true;
    }

    function is_address($address) {
        $partten = "/\d+ [0-9a-zA-Z ]+/";
        if(!preg_match($partten, $address, $matchs) ) {
            return false;
        }
        return true;
    }

    function is_description($des) {
        $partten = "/^(?=.{1,30}$)[A-Za-z0-9]+(?:\s[A-Za-z0-9]+)?$/";
        if(!preg_match($partten, $des, $matchs) ) {
            return false;
        }
        return true;
    }

    function form_error($label_field) {
        global $error;
        if(!empty($error[$label_field])) {
            return "<p class='error'>{$error[$label_field]}</p>";
        }
    }

   
    function pages_author($author) {
        if(!empty($author)) {
            return "<p class='author'>$author</p>";
        }
    }
    

    function set_value($label_field) {
    global $label_field;
        if(!empty($label_field)) {
            return $label_field;
        }
    }
?>