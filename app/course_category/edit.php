<?php
if (!defined('_TEST')) {
    die('Access Denied');
}

$filter = filterData('get');

if(!empty($filter['id'])){
    $cateId = $filter['id'];
    $checkCate = getOne("SELECT * FROM course_category WHERE id = $cateId");

    if(empty($checkCate)){
        redirect('?module=course_category&action=list');
    }
}else{
        setSessionFlash('msg', 'Đã có lỗi xảy ra.');
        setSessionFlash('msg_type', "danger");
 
}

if(isPost()){
    $filter = filterData();
    $errors = [];
    //validate name
    if(empty(trim($filter['name']))) {
        $errors['name'] ['required']= 'Tên lĩnh vực bắt buộc phải nhập';
    }

    //validate slug
    if(empty(trim($filter['slug']))) {
        $errors['slug'] ['required']= 'Slug bắt buộc phải nhập';
    }

    if(empty($errors)){
        //insert data bao bang category
        $dataCate = [
            'name' => $filter['name'],
            'slug' => $filter['slug'],
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $condition = "id=".$cateId;

        $insertStatus = update('course_category', $dataCate,$condition);

        if($insertStatus){
            setSessionFlash('msg', 'Sửa thành công.');
            setSessionFlash('msg_type', "success");
            // redirect('?module=course_category&action=list');
        }else{
            setSessionFlash('msg', 'Thêm thất bại.');
            setSessionFlash('msg_type', "danger");
            redirect('?module=course_category&action=list');
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
    if(!empty($checkCate)) {
        $oldData = $checkCate;
    }
    $errorsArr = getSessionFlash('errors');


?>
<h2>Chỉnh sửa lĩnh vực</h2>

<form action="" method="post">
    <div class="form-group">
        <label for="name">Tên lĩnh vực</label>
        <input id="name" name="name" type="text" class="form-control" value="<?php 
                        if(!empty($oldData)){
                            echo oldData($oldData, 'name');
                        }
                         ?>" placeholder="Tên lĩnh vực" /> 
    </div>
    <div class="form-group">
        <label for="slug">Slug</label>
        <input id="slug" name="slug" type="text" class="form-control" value="<?php 
                        if(!empty($oldData)){
                            echo oldData($oldData, 'slug');
                        }
                         ?>" placeholder="slug" /> 
    </div>

    <button type="submit" class="btn btn-success m-3">Sửa</button>
</form>


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