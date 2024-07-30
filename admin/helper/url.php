<?php

function base_url($url = "") {
    global $config;
    return $config['base_url'].$url;
}

function redirect($url = "") {
    global $config;
    $path = $config['base_url'].$url;
    header("Location: {$path}");
}