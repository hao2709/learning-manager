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
  'title' => 'Quên mật khẩu'
];
layout('header-auth', $data);

if (isPost()) {
  $filter = filterData();
  $errors = [];
  //validate email
  if (empty(trim($filter['email']))) {
    $errors['email']['required'] = 'Email bắt buộc phải nhập';
  } else {
    //Dung dinh dang email, email da ton tai trong CSDL chua
    if (!validateEmail(trim($filter['email']))) {
      $errors['email']['isEmail'] = 'Email không hợp lý';
    }
  }

  if (empty($errors)) {
    //xu ly va gui mail
    if (!empty($filter['email'])) {
      $email = $filter['email'];

      $checkEmail = getOne("SELECT * FROM users WHERE email = '$email'");
      if (!empty($checkEmail)) {
        //update forgot_token vao bang users
        $forgot_token = sha1(uniqid() . time());
        $data = [
          'forget_token' => $forgot_token
        ];
        $condition = "id=" . $checkEmail['id'];
        $updateStatus = update('users', $data, $condition);

        if ($updateStatus) {

          $emailTo = $email;
          $subject = 'Reset mật khẩu tài khoản hệ thống!';
          $content = 'Bạn đang yêu cầu reset lại mật khẩu. <br>';
          $content .= 'Để thay đổi mật khẩu, bạn hãy click vào đường link bên dưới: <br>';
          $content .= _HOST_URL . '/?module=auth&action=reset&token=' . $forgot_token . '<br>';
          $content .= 'Cảm ơn các bạn đã ủng hộ!';

          //gui email
          sendMail($emailTo, $subject, $content);

          setSessionFlash('msg', 'Gửi yêu cầu thành công, vui lòng kiểm tra email.');
          setSessionFlash('msg_type', "success");
        }else{
          setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau.');
          setSessionFlash('msg_type', "danger");  
        }
      }
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
$errorsArr = getSessionFlash('errors');


?>



<section class="vh-100">
  <div class="container-fluid h-custom">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-9 col-lg-6 col-xl-5">
        <img src="<?php echo _HOST_URL_TEMPLATES; ?>./assets/image/login.webp"
          class="img-fluid" alt="Sample image">
      </div>
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
        <?php
        if (!empty($msg) && !empty($msg_type)) {
          getMsg($msg, $msg_type);
        }
        ?>
        <form method="POST" action="" enctype="multipart/form-data">
          <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
            <h2 class="fw-normal mb-5 me-3">Quên mật khẩu</h2>

          </div>

          <!-- Email input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input name="email" value="
            <?php
            if (!empty($oldData)) {
              echo oldData($oldData, 'email');
            }
            ?>" type="email" id="form3Example3" class="form-control form-control-lg"
              placeholder="Địa chỉ email" />

            <?php
            if (!empty($errorsArr)) {
              echo formError($errorsArr, 'email');
            }

            ?>

          </div>

          <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem;">Gửi</button>
          </div>

        </form>
      </div>
    </div>
  </div>

</section>




<?php
layout('footer');
?>