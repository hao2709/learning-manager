<?php
if (!defined('_TEST')) {
    die('Access Denied');
}

function layout($layoutName, $data = [])
{

    if (file_exists(_PATH_URL_TEMPLATES . '/layouts/' . $layoutName . '.php')) {
        require_once _PATH_URL_TEMPLATES . '/layouts/' . $layoutName . '.php';
    }
}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//ham gui mail
function sendMail($emailTo, $subject, $content)
{

    $mail = new PHPMailer(true);


    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'tqhao2709@gmail.com';                     //SMTP username
        $mail->Password   = 'bfbomtxatuwpscpz';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('tqhao2709@gmail.com', 'Khóa học');
        $mail->addAddress($emailTo);     //Add a recipient

        //Content
        $mail->CharSet = "UTF-8";
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;

        $mail->SMTPOptions = array(
            'ssl' => [
                'verify_peer' => true,
                'verify_depth' => 3,
                'allow_self_signed' => true,
            ],
        );

        return $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

//kiem tra phuong thucc post
function isPost() {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }
    return false;
}

//kiem tra phuong thucc get
function isGet() {
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }
    return false;
}

//loc du lieu filterData('GET')
function filterData($method = '') {
    $filterArr = [];
    if(empty($method)) {
        if(isGet()){
            if(!empty($_GET)) {
                foreach($_GET as $key => $value) {
                    $key = strip_tags($key);
                    if(is_array($value)){
                        $filterArr[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    }else{
                        $filterArr[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
        if(isPost()){
            if(!empty($_POST)) {
                foreach($_POST as $key => $value) {
                    $key = strip_tags($key);
                    if(is_array($value)){
                        $filterArr[$key] = filter_input(INPUT_POST,$key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    }else{
                        $filterArr[$key] = filter_input(INPUT_POST,$key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    }else{
        if($method == 'get'){
            if(!empty($_GET)) {
                foreach($_GET as $key => $value) {
                    $key = strip_tags($key);
                    if(is_array($value)){
                        $filterArr[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    }else{
                        $filterArr[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }else if($method == 'post'){
            if(!empty($_POST)) {
                foreach($_POST as $key => $value) {
                    $key = strip_tags($key);
                    if(is_array($value)){
                        $filterArr[$key] = filter_input(INPUT_POST,$key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    }else{
                        $filterArr[$key] = filter_input(INPUT_POST,$key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    }

    return $filterArr;

}

//validate email
function validateEmail($email) {
    if(!empty($email)) {
        $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    return $checkEmail;
}

//validate  int
function validateInt($number) {
    if(!empty($number)) {
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    }
    return $checkNumber;
}

//validate phone
function isPhone($phone) {
    $phoneFirst = false;
    if($phone[0] == '0') {
        $phoneFirst = true;
        $phone = substr($phone, 1);
        // echo $phone;
    }

    $checkPhone = false;
    if(validateInt($phone)) {
        $checkPhone = true;
    }

    if($phoneFirst && $checkPhone) {
        return true;
    }
    return false;
}

//thong bao loi
function getMsg($msg ,$type = 'success'){
    echo '<div class="annouce-message alert alert-'. $type .'">';
    echo $msg;
    echo '</div>';
}

//hien thi loi
// function formError($errors, $fieldName) {
//     return (!empty($errors[$fieldName])) ? '<div class="error">' .reset($errors['$fieldName']) . '</div>' : false;
// }

function formError($errors, $fieldName) {
    if (!empty($errors[$fieldName])) {
        $error = $errors[$fieldName];

        // Nếu là mảng lỗi (nhiều lỗi 1 trường), lấy phần tử đầu tiên
        if (is_array($error)) {
            $errorMsg = reset($error);
        } else {
            $errorMsg = $error;
        }

        return '<div class="error">' . htmlspecialchars($errorMsg) . '</div>';
    }
    return '';
}

//hien thi lai gia tri cu
function oldData($oldData, $fieldName){
    return !empty($oldData[$fieldName]) ? $oldData[$fieldName] : null;
}

//ham chuyen huong
function redirect($path, $pathFull = false) {
    if($pathFull){
        header("Location: $path");
        exit();
    }else{
        $url =_HOST_URL . $path;
        header("Location: $url");
        exit(); 
    }
}

//ham checklogin
function isLogin(){
    $checkLogin = false;
    $tokenLogin = getSession('token_login');
    $checkToken = getOne("SELECT * FROM token_login WHERE token = '$tokenLogin'");
    if(!empty($checkToken)){
    $checkLogin = true;

    }else{
    removeSession('token_login');
    }
    return $checkLogin;
}