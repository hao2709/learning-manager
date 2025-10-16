<?php
if (!defined('_TEST')) {
    die('Access Denied');
}

$data = [
    'title' => 'Phân quyền người dùng'
];
layout('header', $data);
layout('sidebar');

$filterGet = filterData('get');
if (!empty($filterGet['id'])) {
    $idUser = $filterGet['id'];

    $checkID = getOne("SELECT * FROM users WHERE id = $idUser");

    if (empty($checkID)) {
        redirect('?module=users&action=list');
    }
} else {
    setSessionFlash('msg', 'Người dùng không tồn tại.');
    setSessionFlash('msg_type', "danger");
}

if (isPost()) {
    $filter = filterData();
    if (!empty($filter['permission'])) {
        $permission = json_encode($filter['permission']);
    } else {
        $permission = '';
    }

    //update vao bang users
    $dataUpdate = [
        'permission' => $permission,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $condition = "id=" . $idUser;

    $checkUpdate = update('users', $dataUpdate, $condition);

    if ($checkUpdate) {
        setSessionFlash('msg', 'Phân quyền người dùng thành công.');
        setSessionFlash('msg_type', "success");
        redirect("?module=users&action=permission&id=$idUser");
    } else {
        setSessionFlash('msg', 'Phân quyền thất bại.');
        setSessionFlash('msg_type', "danger");
    }
}

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');

if (!empty($checkID['permission'])) {
    $permissionOld = json_decode($checkID['permission'], true);
} else {
}

?>

<div class="container">
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="post">
        <table class="table table-borderd">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Khóa học</th>
                    <th>Phân quyền</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $getDetailCourse = getAll("SELECT id, name FROM course");
                $dem = 1;
                foreach ($getDetailCourse as $item):

                ?>
                    <tr>
                        <td><?php echo $dem;
                            $dem++; ?></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><input type="checkbox" name="permission[]" <?php echo (!empty($permissionOld)) && in_array($item['id'], $permissionOld) ? 'checked' : false; ?> value="<?php echo $item['id']; ?>"></td>
                    </tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Xác nhận</button>
        <a href="?module=users&action=list" class="btn btn-success">Quay lại</a>
    </form>
</div>


<?php
layout('footer');
?>