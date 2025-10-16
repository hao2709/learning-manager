<?php
date_default_timezone_set("Asia/Ho_Chi_Minh");

session_start();
ob_start();//header, cookie

require_once 'config.php';
require_once './includes/connect.php';
require_once './includes/database.php';
require_once './includes/session.php';

//email
require_once './includes/mailer/PHPMailer.php';
require_once './includes/mailer/SMTP.php';
require_once './includes/mailer/Exception.php';

require_once './includes/functions.php';

// $pass = '123456';
// $rel = password_hash($pass, PASSWORD_DEFAULT);
// echo $rel;

// $pass_user_input = 'hh123456';
// $rel_2 = password_verify($pass_user_input, $rel);
// if($rel_2){
//     echo 'mat khau dung';
// }else{
//     echo 'mat khau sai';
// }
// $rel = validateInt(7);
// var_dump($rel);

// $rel = sendMail('kubom01102004@gmail.com', 'testmail', 'Noi dung');

$module = _MODULES;
$action = _ACTION;

if(!empty($_GET['module'])) {
    $module = $_GET['module'];
}

if(!empty($_GET['action'])) {
    $action = $_GET['action'];
}

$path = 'app/' . $module . '/' . $action . '.php';

if(!empty($path)) {
    if(file_exists($path)) {
        require_once $path;
    }else{
        require_once 'app/errors/404.php';
    }
}else{
    require_once 'app/errors/500.php';

}







?>