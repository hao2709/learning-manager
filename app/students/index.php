<?php
if (!defined('_TEST')) {
    die('Access Denied');
}

$data = [
    'title' => 'Phân quyền người dùng'
];
layout('header', $data);
layout('sidebar');

$filter = filterData();
$keyword = '';

if(isGet()){
    if(isset($filter['keyword'])){
        $keyword = $filter['keyword'];
    }
}

//lay du lieu user
$permissionArr = [];
$userDetail = getAll("SELECT fullname, email, permission FROM users");
if(!empty($userDetail)){
    foreach($userDetail as $key => $item){
        $permissionJson = json_decode($item['permission'], true);
        $permissionArr[$key] = $permissionJson;
    }
}



?>

<div class="container">
    <form action="" method="get">
        <div class="row text-center-hienu">
            <input type="hidden" value="students" name="module">

            <div class="col-7">
                <select name="keyword" id="" class="form-select form-control">
                    <option value="0">Chọn khóa học</option>

                    <?php
                    $getCourseDetail = getAll("SELECT id,name FROM course");
                    foreach ($getCourseDetail as $item):
                    ?>
                        <option value="<?php echo $item['id']; ?>" <?php if($keyword == $item['id']){echo 'selected';} ?> ><?php echo $item['name']; ?></option>
                    <?php
                    endforeach;
                    ?>
                </select>
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-success">Duyệt</button>
            </div>
        </div>
    </form>
    
    <div class="row text-center-hienu">
        <div class="col-9">
    <table class="table table-borderd">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên học viên</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $dem = 0;
                foreach($permissionArr as $key => $item):
                    if(!empty($item)):
                    if(in_array($keyword, $item)):
            ?>
            <tr>
                <td><?php echo $dem+1; $dem++; ?></td>
                <td><?php echo $userDetail[$key]['fullname']; ?></td>
                <td><?php echo $userDetail[$key]['email']; ?></td>
            </tr>
            <?php
                endif;
                endif;
                endforeach;
            ?>
        </tbody>
    </table>
        </div>
    </div>
</div>


<?php
layout('footer');
?>