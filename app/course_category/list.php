<?php
if (!defined('_TEST')) {
    die('Access Denied');
}


$data = [
    'title' => 'Danh sách lĩnh vực'
];
layout('header', $data);
layout('sidebar');

$filter = filterData();
$chuoiWhere = '';
$cate = '0';
$keyword = '';

if(isGet()) {
    if(isset($filter['keyword'])){
        $keyword = $filter['keyword'];
    }

    if(!empty($keyword)) {
        if(strpos($chuoiWhere, 'WHERE') == false) {
            $chuoiWhere .= ' WHERE ';
        }else{
            $chuoiWhere .= ' AND ';
        }
        $chuoiWhere .= "name LIKE '%$keyword%' OR slug LIKE '%$keyword%'";
    }

}

//xu ly phan trang
$maxData = getRows("SELECT id FROM course_category");
$perPage = 3; //so dong du lieu 1 trang
$maxPage = ceil($maxData / $perPage);//tinh so trang
$offset = 0;
$page = 1;
//get page
if(isset($filter['page'])) {
    $page = $filter['page'];
}

if($page > $maxPage || $page < 1) {
    $page = 1;

}

$offset = ($page - 1) * $perPage; 

$getDetailCate = getAll("SELECT *
FROM course_category $chuoiWhere
ORDER BY created_at DESC
LIMIT $offset, $perPage
");

if(!empty($keyword)){
    $maxData = getRows("SELECT * FROM course_category $chuoiWhere");
    $maxPage = ceil($maxData / $perPage);//tinh so trang

}



//xu ly query
if(!empty($_SERVER['QUERY_STRING'])){
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('&page='.$page, '', $queryString);
}

    $msg = getSessionFlash('msg');
    $msg_type = getSessionFlash('msg_type');
?>

<div class="container grid-user">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
            <?php
            if(!empty($filter['id']) && $filter['type'] == 'edit'){
                require_once 'edit.php'; 

            }else{
                require_once 'add.php';
            }

            ?>
            

            </div>
            <div class="col-6">
                <h2>Danh sách lĩnh vực</h2>
    <?php 
    if(!empty($msg) && !empty($msg_type)){
                 getMsg($msg, $msg_type);
    }
    ?> 
        <form class="mb-3" action="" method="get">
            <input type="hidden" name="module" value="course_category" id="">
            <input type="hidden" name="action" value="list" id="">
            <div class="row">

                <div class="col-9">
                    <input type="text" class="form-control" value="<?php echo (!empty($keyword)) ? $keyword : false; ?>" name="keyword" placeholder="Nhập thông tin tìm kiếm...">
                </div>
                <div class="col-3"><button class="btn btn-primary" type="submit">Tìm kiếm</button></div>
            </div>

        </form>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Thời gian</th>
                    <th scope="col">Sửa</th>
                    <th scope="col">Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($getDetailCate as $key => $item):

                ?>
                <tr>
                    <th scope="row"><?php echo $key + 1 ?></th>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['created_at']; ?></td>
                    <td><a href="?module=course_category&action=list&id=<?php echo $item['id']; ?>&type=edit" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></a></td>
                    <td><a href="?module=course_category&action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a></td>
                </tr>
                <?php
                    endforeach;
                ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <!-- xu ly nut "truoc" -->
                <?php
                    if($page > 1):
                ?>
                <li class="page-item"><a class="page-link" href="?<?php echo $queryString; ?>&page=<?php echo $page - 1; ?>">Trước</a></li>
                <?php endif; ?>

                <!-- tinh vi tro bat dau -->
                 <?php 
                    $start = $page - 1;
                    if($start < 1){
                        $start = 1;
                    }   
                 ?>

                <?php
                    if($start > 1):
                ?>
                <li class="page-item"><a class="page-link" href="?<?php echo $queryString; ?>&page=<?php echo $page - 1; ?>">...</a></li>

                <?php endif; 
                
                    $end = $page + 1;
                    if($end > $maxPage){
                        $end = $maxPage;
                    }
                
                ?>
                
                <?php for($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' :false; ?>"><a class="page-link" href="?<?php echo $queryString; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>


                <?php
                    endfor;
                    if($end < $maxPage):
                ?>
                <li class="page-item"><a class="page-link" href="?<?php echo $queryString; ?>&page=<?php echo $page + 1; ?>">...</a></li>

                <?php endif; 
                ?>

                <!-- xu ly nut "sau" -->
                <?php
                    if($page < $maxPage):
                ?>
                <li class="page-item"><a class="page-link" href="?<?php echo $queryString; ?>&page=<?php echo $page + 1; ?>">Sau</a></li>
                <?php endif; ?>
            </ul>
        </nav>
            </div>
        </div>
    </div>
</div>





<?php
layout('footer');
?>