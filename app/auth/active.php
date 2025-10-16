
<?php
if (!defined('_TEST')) {
    die('Access Denied');
}

$data = [
    'title' => 'Kích hoạt tài khoản'
];
layout('header-auth',$data);


$filter = filterData('get');

 //xu ly link hop le
if(!empty($filter['token'])):
    $token = $filter['token'];
    $checkToken = getOne("SELECT * FROM users WHERE active_token = '$token'");


?>
<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <img src="<?php echo _HOST_URL_TEMPLATES; ?>./assets/image/login.webp"
                    class="img-fluid" alt="Sample image">
            </div>

            <?php
                if(!empty($checkToken)):
                    //thuc hien update du lieu
                    $data = [
                        'status' => 1,
                        'active_token' => null,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    $condition = "id = " . $checkToken['id'];
                    update('users', $data, $condition);
                ?>
                <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                    <h2 class="fw-normal mb-5 me-3">Kích hoạt tài khoản thành công</h2>
                </div>

                <a href="<?php echo _HOST_URL; ?>?module=auth&action=login"
                    class="link-danger" style=" font-size: 20px; color: blue !important;">Đăng nhập ngay</a>

            </div>
                <?php
                else:
                    ?>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

                <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                    <h2 class="fw-normal mb-5 me-3">Kích hoạt tài khoản không thành công. Đường link đã hết hạn.</h2>
                </div>

            </div>


            <?php
                endif;
            ?>


        </div>
    </div>

</section>
<?php
//duong link sai, khong hop le
else:
    ?>
<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <img src="<?php echo _HOST_URL_TEMPLATES; ?>./assets/image/login.webp"
                    class="img-fluid" alt="Sample image">
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

                <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                    <h2 class="fw-normal mb-5 me-3">Đường link kích hoạt đã hết hạn hoặc không tồn tại.</h2>
                </div>

                <a href="<?php echo _HOST_URL; ?>?module=auth&action=login"
                    class="link-danger" style=" font-size: 20px; color: blue !important;">Quay trở lại</a>

            </div>
        </div>
    </div>

</section>
    <?php
endif;

?>






<?php
layout('footer');




?>