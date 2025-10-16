<?php
const _TEST = true;

const _MODULES = 'dashboard';
const _ACTION = 'index';

//khai bao db
const _HOST = 'localhost';
const _USER = 'root';
const _PASS = '';
const _DB = 'qlkh';
const __DRIVER = 'mysql';

// debug error
const _DEBUG = true;

//thiet lap hsost
define('_HOST_URL','http://'. $_SERVER['HTTP_HOST'] . '/manager_course'); 
define('_HOST_URL_TEMPLATES',_HOST_URL . '/public'); 

//thiet lap path
define('_PATH_URL', __DIR__);
define('_PATH_URL_TEMPLATES', _PATH_URL . '/public');


?>