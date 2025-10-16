<?php
if (!defined('_TEST')) {
    die('Access Denied');
}

// Lấy ID từ URL
$courseId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin khóa học
$course = getOne("SELECT * FROM course WHERE id = $courseId");

// Nếu không tìm thấy khóa học, quay lại danh sách
if (empty($course)) {
    setSessionFlash('msg', 'Khóa học không tồn tại.');
    setSessionFlash('msg_type', 'danger');
    redirect('?module=course&action=list');
}

// Layout
$data = ['title' => 'Chi tiết khóa học'];
layout('header', $data);
layout('sidebar');
?>

<main class="app-main">
    <?php require_once('./public/layouts/breadcrumb.php'); ?>

    <div style="margin-top: 20px;" class="app-content">
        <div class="container">
            <div class="row">
                <!-- Ảnh khóa học -->
                <div class="col-md-5">
                    <img src="<?php echo $course['thumbnail']; ?>"
                        class="img-fluid rounded shadow-sm"
                        alt="<?php echo htmlspecialchars($course['name']); ?>">
                </div>

                <!-- Thông tin khóa học -->
                <div class="col-md-7">
                    <h2><?php echo htmlspecialchars($course['name']); ?></h2>
                    <p class="text-muted">
                        <strong>Giá:</strong>
                        <span class="text-danger h4"><?php echo number_format($course['price']); ?>₫</span>
                    </p>
                    <p><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>

                    <a href="/manager_course/" class="btn btn-secondary">Quay lại trang chủ</a>
                    <a href="?module=course&action=payment&id=<?php echo $course['id']; ?>"
                        class="btn btn-success">
                        Đăng ký ngay
                    </a>


                </div>
            </div>
        </div>
    </div>
</main>

<?php layout('footer'); ?>