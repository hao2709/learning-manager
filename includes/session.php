<?php
if(!defined('_TEST')){
    die('Access Denied');
}

//set session
function setSession($key, $value){
    if(!empty(session_id())){
        $_SESSION[$key] = $value;
        return true;
    }else{
        return false;
    }
}

//lay du lieu tu session
function getSession($key = ''){
    if(empty($key)){
        return $_SESSION;
    }else{
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
    }
    return false;
}


//xoa session
function removeSession($key = '') {
    if(empty($key)){
        session_destroy();
        return true;
    }else{
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
        return true;
    } 
    return false;
}

//tao session flash
function setSessionFlash($key, $value){ 
    $key = $key . 'Flash';

    $rel = setSession($key, $value);
    return $rel;
}


//lay session flash
function getSessionFlash($key){ 
    $key = $key . 'Flash';
    $rel = getSession($key);

    removeSession($key);
    return $rel;
}

?>