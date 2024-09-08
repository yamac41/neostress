<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {exit("NOT ALLOWED");}

define('DIRECT', TRUE);

require_once 'functions.php';
require_once 'cache_lib.php';
$user = new user;

$memoryCache = false;

function getCache($memoryCache, $key) {
    $cache_file =   $_SERVER['DOCUMENT_ROOT'] . '/cache/' . $key . ".cache";
    if (file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * 5))) {
        return file_get_contents($cache_file);
    } else {
        return false;
    }
}

function setCache($memoryCache, $key, $data) {
    $cache_file = $_SERVER['DOCUMENT_ROOT'] . '/cache/' . $key . ".cache";
    file_put_contents($cache_file, $data, LOCK_EX);
}

?>