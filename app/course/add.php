<?php
if (!defined('_TEST')) {
    die('Access Denied');
}


$data = [
    'title' => 'Thêm mới khóa học'
];
layout('header', $data);
layout('sidebar');



if(isPost()){
    $filter = filterData();
    $errors = [];
    //validate name
    if(empty(trim($filter['name']))) {
        $errors['name'] ['required']= 'Tên khóa học bắt buộc phải nhập';
    }else{
        if(strlen(trim($filter['name'])) < 5) {
            $errors['name']['length'] = 'Tên khóa học phải nhất 5 ký tự';
        }
    }

    //validate slug
    if(empty(trim($filter['slug']))) {
        $errors['slug'] ['required']= 'Slug bắt buộc phải nhập';
    }

    //validate price
    if(empty(trim($filter['price']))) {
        $errors['price'] ['required']= 'Giá bắt buộc phải nhập';
    }

    //validate description
    if(empty(trim($filter['description']))) {
        $errors['description'] ['required']= 'Mô tả bắt buộc phải nhập';
    }

    if(empty($errors)) {
        //xu ly thumbnail upload len
        $uploadDir = './public/uploads/';
        if(!file_exists($uploadDir)){
            mkdir($uploadDir, 0777, true);//tao moi thu muc
        }

    $fileName = basename($_FILES['thumbnail']['name']);
    $targetFile = $uploadDir . time() . '-' . $fileName;
    $thumb = '';


    $checkMove = move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFile);
    if($checkMove) {
        $thumb =$targetFile;
    }

        $dataInsert = [
            'name' => $filter['name'],
            'slug' => $filter['slug'],
            'price' => $filter['price'],
            'description' => $filter['description'],
            'thumbnail' => $thumb,
            'category_id' => $filter['category_id'],
            'created_at' => date('Y-m-d H:i:s'),

        ];

        $insertStatus = insert('course', $dataInsert);
        if($insertStatus){
            setSessionFlash('msg', 'Thêm khóa học thành công.');
            setSessionFlash('msg_type', "success");
            redirect('?module=course&action=list');
        }else{
        setSessionFlash('msg', 'Thêm khóa học thất bại.');
        setSessionFlash('msg_type', "danger");
        }
    }else{
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

<div class="container add-user">
    <h2>Thêm mới khóa học</h2>
    <hr>
    <?php 
    if(!empty($msg) && !empty($msg_type)){
                 getMsg($msg, $msg_type);
    }
    ?> 
    <form action="" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-6 pd-3">
                <label for="name">Tên khóa học</label>
                <input id="name" name="name" type="text" class="form-control" value="<?php 
                        if(!empty($oldData)){
                            echo oldData($oldData, 'name');
                        }
                         ?>" placeholder="Tên khóa học">
                            <?php 
                                if(!empty($errorsArr)){
                                echo formError($errorsArr, 'name');
                                } 
                            ?>
            </div>
            <div class="col-6 pd-3">
                <label for="slug">Đường dẫn</label>
                <input id="slug" type="text" name="slug" class="form-control" value="<?php 
                        if(!empty($oldData)){
                            echo oldData($oldData, 'slug');
                        }
                         ?>" placeholder="slug">
                            <?php 
                                if(!empty($errorsArr)){
                                echo formError($errorsArr, 'slug');
                                } 
                            ?>
            </div>
            <div class="col-6 pd-3">
                <label for="description">Mô tả khóa học</label>
                <input id="description" name="description" type="text" class="form-control" value="<?php 
                        if(!empty($oldData)){
                            echo oldData($oldData, 'description');
                        }
                         ?>" placeholder="Mô tả">
                            <?php 
                                if(!empty($errorsArr)){
                                echo formError($errorsArr, 'description');
                                } 
                            ?>
            </div>
            <div class="col-6 pd-3">
                <label for="price">Giá</label>
                <input id="price" name="price" type="text" class="form-control" value="<?php 
                        if(!empty($oldData)){
                            echo oldData($oldData, 'price');
                        }
                         ?>" placeholder="Giá">
                            <?php 
                                if(!empty($errorsArr)){
                                echo formError($errorsArr, 'price');
                                } 
                            ?>
            </div>
            <div class="col-6 pd-3">
                <label for="thumbnail">Thumbnail</label>
                <input id="thumbnail" name="thumbnail" type="file" class="form-control" placeholder="Địa chỉ">
                <img width="200px" id="previewImage" class="preview-image p-3" src="#" alt="">
            </div>
            <div class="col-3 pd-3">
                <label for="group">Lĩnh vực</label>
                <select name="category_id" id="group" class="form-select form-control">
                    <?php
                    $getGroup = getAll("SELECT * FROM course_category");
                    foreach ($getGroup as $item):
                    ?>
                        <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                    <?php
                    endforeach;
                    ?>
                </select>
            </div>


        </div>
        <button type="submit" class="btn btn-success btn-add mt-3">Xác nhận</button>
    </form>
</div>

<script>
    const thumbInput = document.getElementById('thumbnail');
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

<script>
    function createSlug(strig){
        return strig.toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }
    document.getElementById('name').addEventListener('input', function(){
        const getValue = this.value;
        document.getElementById('slug').value = createSlug(getValue);
    });
</script>

<?php
layout('footer');
?>