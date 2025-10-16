<?php
if (!defined('_TEST')) {
    die('Access Denied');
}

$data = [
    'title' => 'Trang chủ'
];
layout('header', $data);
layout('sidebar');


?>

<!--begin::App Main-->
<main class="app-main">

    <?php
    require_once('./public/layouts/breadcrumb.php');

    ?>
    <div class="app-content">
        <div class="container-fluid">

            <!-- Thanh tìm kiếm & lọc lĩnh vực -->
            <div class="row mb-4">
                <div class="col-md-8 mx-auto">
                    <form action="" method="get" class="d-flex shadow-sm p-3 bg-white rounded">
                        <input type="hidden" name="module" value="course">
                        <input type="hidden" name="action" value="list">

                        <!-- Ô tìm kiếm -->
                        <input type="text" name="keyword" class="form-control me-2"
                            placeholder="Nhập tên khóa học..."
                            value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">

                        <!-- Lọc lĩnh vực -->
                        <select name="category_id" class="form-select me-2">
                            <option value="">Tất cả lĩnh vực</option>
                            <?php
                            $categories = getAll("SELECT * FROM course_category ORDER BY name ASC");
                            foreach ($categories as $cate):
                                $selected = (isset($_GET['category_id']) && $_GET['category_id'] == $cate['id']) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $cate['id']; ?>" <?php echo $selected; ?>>
                                    <?php echo htmlspecialchars($cate['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- Nút tìm kiếm -->
                        <button type="submit" class="btn btn-success">Tìm</button>
                    </form>
                </div>
            </div>

            <!-- Tiêu đề danh sách -->
            <h2 style="color: #373737;" class="mb-3 text-center">Danh sách khóa học</h2>

            <!-- Danh sách khóa học -->
            <div class="row">
                <?php
                // Lấy dữ liệu khóa học từ DB
                $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
                $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

                $where = [];
                if ($keyword !== '') {
                    $keyword = addslashes($keyword);
                    $where[] = "(name LIKE '%$keyword%' OR description LIKE '%$keyword%')";
                }
                if ($category_id > 0) {
                    $where[] = "category_id = $category_id";
                }
                $whereSQL = !empty($where) ? "WHERE " . implode(" AND ", $where) : '';

                $courses = getAll("SELECT * FROM course $whereSQL ORDER BY created_at DESC LIMIT 12");

                if (!empty($courses)):
                    foreach ($courses as $course):
                ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <!-- Ảnh đại diện -->
                                <img src="<?php echo $course['thumbnail']; ?>"
                                    class="card-img-top"
                                    alt="<?php echo htmlspecialchars($course['name']); ?>"
                                    style="height:180px; object-fit:cover;">

                                <!-- Nội dung khóa học -->
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-truncate">
                                        <?php echo htmlspecialchars($course['name']); ?>
                                    </h5>
                                    <p class="card-text text-muted small mb-2">
                                        <?php echo substr(strip_tags($course['description']), 0, 60) . '...'; ?>
                                    </p>

                                    <div class="mt-auto">
                                        <span class="fw-bold text-danger h6">
                                            <?php echo number_format($course['price']); ?>₫
                                        </span>
                                        <a href="?module=course&action=detail&id=<?php echo $course['id']; ?>"
                                            class="btn btn-success btn-sm float-end">
                                            Xem chi tiết
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    endforeach;
                else:
                    ?>
                    <p class="text-center text-muted">Không tìm thấy khóa học phù hợp.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>

</main>
<!--end::App Main-->


<?php
layout('footer');
?>