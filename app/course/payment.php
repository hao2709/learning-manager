<?php
if (!defined('_TEST')) {
    die('Access Denied');
}

// Lấy ID khóa học từ URL
$courseId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin khóa học
$course = getOne("SELECT * FROM course WHERE id = $courseId");

// Nếu không tìm thấy khóa học
if (empty($course)) {
    setSessionFlash('msg', 'Khóa học không tồn tại.');
    setSessionFlash('msg_type', 'danger');
    redirect('/manager_course/');
}

// ===== XỬ LÝ GỬI EMAIL KHI NHẤN NÚT KIỂM TRA =====
if (isPost() && isset($_POST['check_payment'])) {
    // Lấy email của học viên (ví dụ lưu trong session)
    $emailTo = $_SESSION['user_email'] ?? 'vinhlimited@gmail.com';

    $subject = 'Xác nhận đăng ký khóa học thành công!';
    $content  = 'Chúc mừng bạn đã đăng ký khóa học <strong>' . htmlspecialchars($course['name']) . '</strong> thành công.<br>';
    $content .= 'Vui lòng vào đường link dưới đây để học tập:<br>';
    $content .= '<a href="https://drive.google.com/drive/folders/1x-NjfjjsnOMtdFo1xfEBxgSCm3PzSb7n" target="_blank">Truy cập khóa học</a><br>';
    $content .= 'Chúc bạn học tập hiệu quả!';

    if (sendMail($emailTo, $subject, $content)) {
        setSessionFlash('msg', 'Đã gửi thông báo đến email của bạn.');
        setSessionFlash('msg_type', 'success');
    } else {
        setSessionFlash('msg', 'Không thể gửi email. Vui lòng thử lại.');
        setSessionFlash('msg_type', 'danger');
    }

    // redirect(getCurrentUrl()); // load lại trang để tránh gửi lại khi refresh
}

// Layout
$data = ['title' => 'Thanh toán'];
layout('header', $data);
layout('sidebar');
?>

<main class="app-main">
    <?php require_once('./public/layouts/breadcrumb.php'); ?>

    <div class="app-content">
        <div class="container">
            <h2 class="mb-4">Thanh toán khóa học</h2>

            <?php
            $msg = getSessionFlash('msg');
            $msg_type = getSessionFlash('msg_type');
            if (!empty($msg) && !empty($msg_type)) {
                getMsg($msg, $msg_type);
            }
            ?>

            <!-- Thông tin khóa học -->
            <div class="card shadow-sm mb-4">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="<?php echo $course['thumbnail']; ?>" 
                             class="img-fluid rounded-start" 
                             alt="<?php echo htmlspecialchars($course['name']); ?>">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo htmlspecialchars($course['name']); ?></h4>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                            <h5 class="text-danger"><?php echo number_format($course['price']); ?>₫</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hướng dẫn thanh toán -->
            <div class="card shadow-sm p-4">
                <h4 class="mb-3">Quét mã QR hoặc chuyển khoản thủ công</h4>
                
                <div class="row">
                    <!-- QR Code -->
                    <div class="col-md-4 text-center">
                        <img src="./public/assets/image/qr-code1.png" 
                             alt="QR Code" 
                             class="img-fluid rounded border" 
                             style="max-width:230px;">
                        <p class="mt-2 text-muted">Quét mã bằng ứng dụng ngân hàng</p>
                    </div>

                    <!-- Thông tin ngân hàng -->
                    <div class="col-md-8">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Ngân hàng:</strong> Vietcombank
                            </li>
                            <li class="list-group-item">
                                <strong>Số tài khoản:</strong> 123456789
                            </li>
                            <li class="list-group-item">
                                <strong>Chủ tài khoản:</strong> Nguyễn Văn A
                            </li>
                            <li class="list-group-item">
                                <strong>Nội dung chuyển khoản:</strong> 
                                ĐK <?php echo $course['id']; ?> - <?php echo htmlspecialchars($course['name']); ?>
                            </li>
                            <li class="list-group-item">
                                <strong>Số tiền:</strong> <?php echo number_format($course['price']); ?>₫
                            </li>
                        </ul>
                        <div class="mt-3">
                            <a href="/manager_course/" class="btn btn-secondary">Quay lại trang chủ</a>
                            <form method="post" style="display:inline;">
                                <button type="submit" name="check_payment" class="btn btn-success">Kiểm tra</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<?php layout('footer'); ?>
