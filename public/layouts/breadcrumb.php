<?php
if (!defined('_TEST')) {
    die('Access Denied');
}

require_once('./public/layouts/header.php');
require_once('./public/layouts/sidebar.php');

?>



<!--begin::App Content Header-->
<div class="app-content-header text-center">
    <!--begin::Container-->
                 <img class="logo" src="<?php echo _HOST_URL_TEMPLATES; ?>./assets/image/logo.png" alt="">

    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6 text-center">
                <!-- <h3 class="mb-0">Dashboard</h3> -->
                 <!-- <img class="logo" src="<?php echo _HOST_URL_TEMPLATES; ?>./assets/image/logo.png" alt=""> -->
            </div>
            <!-- <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </div> -->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->
<style>
.logo {
   width: 7%;
    text-align: center;
}

</style>