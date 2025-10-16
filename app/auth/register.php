<?php
if (!defined('_TEST')) {
    die('Access Denied');
}

$data = [
    'title' => 'Đăng ký tài khoản'
];
layout('header-auth',$data);

if(isPost()) {
    $filter = filterData();
    $errors = [];

    //validate fullname
    if(empty(trim($filter['fullname']))) {
        $errors['fullname'] ['required']= 'Họ tên bắt buộc phải nhập';
    }else{
        if(strlen(trim($filter['fullname'])) < 5) {
            $errors['fullname']['length'] = 'Họ tên phải nhất 5 ký tự';
        }
    }
    
    //validate email
    if(empty(trim($filter['email']))) {
        $errors['email'] ['required']= 'Email bắt buộc phải nhập';
    }else{
        //Dung dinh dang email, email da ton tai trong CSDL chua
        if(!validateEmail(trim($filter['email']))) {
            $errors['email']['isEmail'] = 'Email không hợp lý';
        }else{
            $email = $filter['email'];

            $checkEmail = getRows("SELECT * FROM users WHERE email = '$email'");
            if($checkEmail >0){
                $errors['email']['check'] = 'Email này đã tồn tại';
            }
        }
    }

    //validate phone
    if(empty($filter['phone'])) {
        $errors['phone'] ['required']= 'Số điện thoại bắt buộc phải nhập';
    }else{
        if(!isPhone($filter['phone'])) {
            $errors['phone']['isPhone'] = 'Số điện thoại không đúng định dạng';
        }
    }

    //validate password MK > 6 ký tự
    if(empty(trim($filter['password']))) {
        $errors['password'] ['required']= 'Mật khóa bắt buộc phải nhập';
    }else{
        if(strlen(trim($filter['password'])) < 6) {
            $errors['password']['length'] = 'Mật khóa phải lớn hơn 6 ký tự';
        }
    }

    //validate confirm password
    if(empty(trim($filter['password']))) {
        $errors['confirm_pass'] ['required']= 'Vui lòng nhập lại mật khẩu';
    }else{
        if(trim($filter['password']) != trim($filter['confirm_pass'])) {
            $errors['confirm_pass']['like'] = 'Mật khẩu nhập lại không khớp ';
        }
    }





    if(empty($errors)) {
        //table: users, data
        $activeToken = sha1(uniqid() . time());

        $data =[
            'fullname' =>$filter['fullname'],
            'address' =>$filter['address'],
            'phone' =>$filter['phone'],
            'password'=> password_hash($filter['password'],PASSWORD_DEFAULT),
            'email' =>  $filter['email'],
            'active_token' => $activeToken,
            'group_id' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('users', $data);

        if($insertStatus) {
            $emailTo = $filter['email'];
            $subject = 'Kích hoạt tài khoản hệ thống!';
            $content = 'Chúc mừng bạn đã đăng ký thành công. <br>';
            $content .= 'Để kích hoạt tài khoản, bạn hãy click vào đường link bên dưới: <br>';
            $content .= _HOST_URL . '/?module=auth&action=active&token=' . $activeToken .'<br>';
            $content .= 'Cảm ơn các bạn đã ủng hộ!';

            //gui email
            sendMail($emailTo, $subject,$content);

            setSessionFlash('msg', 'Đăng ký thành công, vui lòng kích hoạt tài khoản.');
            setSessionFlash('msg_type', "success");
        }else{
            setSessionFlash('msg', 'Đăng ký không thành công, vui lòng thử lại sau.');
            setSessionFlash('msg_type', "danger");
        }

    }else{
        setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào.');
        setSessionFlash('msg_type', "danger");

        setSessionFlash('oldData', $filter);
        setSessionFlash('errors', $errors);
    }

    $msg = getSessionFlash('msg');
    $msg_type = getSessionFlash('msg_type');
    $oldData = getSessionFlash('oldData');
    $errorsArr = getSessionFlash('errors');


}



?>


<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <img src="<?php echo _HOST_URL_TEMPLATES; ?>./assets/image/login.webp"
                    class="img-fluid" alt="Sample image">
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

             <?php //getMsg($msg, $msg_type);
            // if (!empty($msg)) echo getMsg($msg, $msg_type);

            if(!empty($msg) && !empty($msg_type)){
                 getMsg($msg, $msg_type);
            }
            ?> 
            

                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                        <h2 class="fw-normal mb-5 me-3">Đăng kí tài khoản</h2>

                    </div>

                    <!-- Email input name, email, sdt, mat khau -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="fullname" type="text" value="<?php 
                        if(!empty($oldData)){
                            echo oldData($oldData, 'fullname');
                        }
                         ?>"class="form-control form-control-lg"
                            placeholder="Họ tên" />
                            <?php 
                                if(!empty($errorsArr)){
                                echo formError($errorsArr, 'fullname');
                                }
                                
                                // if (!empty($errorsArr)) echo formError($errorsArr, 'fullname');
                            ?>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="email" value="<?php 
                        
                        
                        if(!empty($oldData)){
                            echo oldData($oldData, 'email');
                        }
                        ?>"type="text" class="form-control form-control-lg"
                            placeholder="Địa chỉ email" />
                            <?php 
                                
                                if(!empty($errorsArr)){
                                echo formError($errorsArr, 'email');
                                }
                                // if (!empty($errorsArr)) echo formError($errorsArr, 'email');

                            ?>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="phone" value="<?php 
                         
                        if(!empty($oldData)){
                                echo oldData($oldData, 'phone');
                                }
                        ?>"type="text" class="form-control form-control-lg" 
                            placeholder="Nhập số điện thoại" />
                            <?php 
                                if(!empty($errorsArr)){
                                echo formError($errorsArr, 'phone');
                                } 
                                
                                // if (!empty($errorsArr)) echo formError($errorsArr, 'phone');
                            ?> 
                    </div>

                    <div data-mdb-input-init class="form-outline mb-3">
                        <input name="password" type="password" id="form3Example4" class="form-control form-control-lg"
                            placeholder="Nhập mật khẩu" />
                            <?php 
                                if(!empty($errorsArr)){
                                echo formError($errorsArr, 'password');
                                } 
                                
                                // if (!empty($errorsArr)) echo formError($errorsArr, 'password');

                            ?>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="confirm_pass" type="password" class="form-control form-control-lg"
                            placeholder="Nhập lại mật khẩu" />
                            <?php 
                                if(!empty($errorsArr)){
                                echo formError($errorsArr, 'confirm_pass');
                                } 
                                
                                // if (!empty($errorsArr)) echo formError($errorsArr, 'confirm_pass');

                            ?>
                    </div>



                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Đăng ký</button>
                        <p class="small fw-bold mt-2 pt-1 mb-0">Bạn đã có tài khoản? <a href="<?php echo _HOST_URL; ?>?module=auth&action=login"
                                class="link-danger">Đăng nhập ngay</a></p>
                    </div>

                </form>
            </div>
        </div>
    </div>

</section>




<?php
layout('footer');
?>