<?php
if(!defined('_TEST')){
    die('Access Denied');
}

try {
    if (class_exists('PDO')) {
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        $dsn = __DRIVER . ":host=" . _HOST . ";dbname=" . _DB;
        $conn = new PDO($dsn, _USER, _PASS, $options);

    }

} catch (Exception $ex) {
    // echo "Loi ket noi: " . $ex->getMessage();

require_once 'app/errors/404.php';
die();
}



?>