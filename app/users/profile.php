<?php
if (!defined('_TEST')) {
    die('Access Denied');
}



?>

<?php
if (!defined('_TEST')) {
    die('Access Denied');
}


$data = [
    'title' => 'Profile'
];
layout('header', $data);
layout('sidebar');

$getData = filterData('get');


//lay thong tin user
$token = getSession('token_login');

if(!empty($token)){
  $checkTokenLogin = getOne("SELECT * FROM token_login WHERE token = '$token'");
  if(!empty($checkTokenLogin)){
    $user_id = $checkTokenLogin['user_id'];
    $detailUser = getOne("SELECT * FROM users WHERE id = $user_id");

  }
}


if (isPost()) {
    $filter = filterData();
    $errors = [];
    //validate fullname
    if (empty(trim($filter['fullname']))) {
        $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
    } else {
        if (strlen(trim($filter['fullname'])) < 5) {
            $errors['fullname']['length'] = 'Họ tên phải nhất 5 ký tự';
        }
    }

    if($filter['email'] != $detailUser['email']) {
    //validate email
    if (empty(trim($filter['email']))) {
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        //Dung dinh dang email, email da ton tai trong CSDL chua
        if (!validateEmail(trim($filter['email']))) {
            $errors['email']['isEmail'] = 'Email không hợp lý';
        } else {
            $email = $filter['email'];

            $checkEmail = getRows("SELECT * FROM users WHERE email = '$email'");
            if ($checkEmail > 0) {
                $errors['email']['check'] = 'Email này đã tồn tại';
            }
        }
    }    
    }


    //validate phone
    if (empty($filter['phone'])) {
        $errors['phone']['required'] = 'Số điện thoại bắt buộc phải nhập';
    } else {
        if (!isPhone($filter['phone'])) {
            $errors['phone']['isPhone'] = 'Số điện thoại không đúng định dạng';
        }
    }

    //validate password MK > 6 ký tự
    if (!empty(trim($filter['password']))) {
        if(strlen(trim($filter['password'])) < 6) {
            $errors['password']['length'] = 'Mật khẩu phải lớn hơn 6 ký tự';
        }
    }
    if (empty($errors)) {

        $dataUpdate = [
            'fullname' => $filter['fullname'],
            'email' => $filter['email'],
            'phone' => $filter['phone'],
            'address' => (!empty($filter['address'])) ? $filter['address'] : null,
            'updated_at' => date('Y-m-d H:i:s'),

        ];

        if(!empty($_FILES['avatar']['name'])){
        //xu ly avatar upload len
        $uploadDir = './public/uploads/';
        if(!file_exists($uploadDir)){
            mkdir($uploadDir, 0777, true);//tao moi thu muc
        }

    $fileName = basename($_FILES['avatar']['name']);
    $targetFile = $uploadDir . time() . '-' . $fileName;
    $thumb = '';


    $checkMove = move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile);
    $targetFile = ltrim($targetFile, '.');
    if($checkMove) {
        $thumb =$targetFile;
    }
    $dataUpdate['avatar'] = $thumb;
}   

        if(!empty($filter['password'])) {
            $dataUpdate['password'] = password_hash($filter['password'], PASSWORD_DEFAULT);
        }

        $condition = "id=". $user_id;


        $updateStatus = update('users', $dataUpdate, $condition);
        if ($updateStatus) {
            setSessionFlash('msg', 'Cập nhật thành công.');
            setSessionFlash('msg_type', "success");
            redirect('?module=users&action=profile');
        } else {
            setSessionFlash('msg', 'Cập nhật thất bại.');
            setSessionFlash('msg_type', "danger");
        }
    } else {
        setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào.');
        setSessionFlash('msg_type', "danger");

        setSessionFlash('oldData', $filter);
        setSessionFlash('errors', $errors);
    }
}


$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');

$oldData = getSessionFlash('oldData');
if(!empty($detailUser)) {
    $oldData = $detailUser;
}

$errorsArr = getSessionFlash('errors');







?>

<div class="container add-user">
    <h2>Thông tin tài khoản</h2>
    <hr>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-6 pd-3">
                <label for="fullname">Họ và tên</label>
                <input id="fullname" name="fullname" type="text" class="form-control" value="<?php
                                                                                                if (!empty($oldData)) {
                                                                                                    echo oldData($oldData, 'fullname');
                                                                                                }
                                                                                                ?>" placeholder="Họ tên">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'fullname');
                }
                ?>
            </div>
            <div class="col-6 pd-3">
                <label for="email">Email</label>
                <input id="email" type="text" name="email" class="form-control" value="<?php
                                                                                        if (!empty($oldData)) {
                                                                                            echo oldData($oldData, 'email');
                                                                                        }
                                                                                        ?>" placeholder="Email">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'email');
                }
                ?>
            </div>
            <div class="col-6 pd-3">
                <label for="phone">Số điện thoại</label>
                <input ad="phone" name="phone" type="text" class="form-control" value="<?php
                                                                                        if (!empty($oldData)) {
                                                                                            echo oldData($oldData, 'phone');
                                                                                        }
                                                                                        ?>" placeholder="Số điện thoại">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'phone');
                }
                ?>
            </div>
            <div class="col-6 pd-3">
                <label for="password">Mật khẩu</label>
                <input id="password" name="password" type="password" class="form-control" value="<?php
                                                                                                    if (!empty($oldData)) {
                                                                                                        echo oldData($oldData, 'password');
                                                                                                    }
                                                                                                    ?>" placeholder="Mật khẩu">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'password');
                }
                ?>
            </div>
            <div class="col-6 pd-3">
                <label for="address">Địa chỉ</label>
                <input type="address" name="address" class="form-control" value="<?php
                                                                                                    if (!empty($oldData)) {
                                                                                                        echo oldData($oldData, 'address');
                                                                                                    }
                                                                                                    ?>" placeholder="Địa chỉ">
            </div>
            <div class="col-6 pd-3">
                <label for="avatar">Ảnh đại diện</label>
                <input id="avatar" name="avatar" type="file" class="form-control" placeholder="Thay ảnh đại diện">
                <img width="200px" id="previewImage" class="preview-image p-3" src="<?php echo _HOST_URL; ?><?php echo !empty($oldData['avatar']) ? $oldData['avatar'] : false; ?>" alt="">
            </div>

        </div>
        <button type="submit" class="btn btn-success btn-add mt-3">Xác nhận</button>
    </form>
</div>



<?php
layout('footer');
?>

<script>
    const thumbInput = document.getElementById('avatar');
    const previewImg = document.getElementById('previewImage');

    thumbInput.addEventListener('change', function(){
        const file = this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                previewImg.setAttribute('src', e.target.result);
                previewImg.style.display = 'block!important';
            }
            reader.readAsDataURL(file);
        }else{
            previewImg.style.display = 'none';
        }
    });
</script>