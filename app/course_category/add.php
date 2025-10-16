<?php
if (!defined('_TEST')) {
    die('Access Denied');
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
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('course_category', $dataCate);

        if($insertStatus){
            setSessionFlash('msg', 'Thêm thành công.');
            setSessionFlash('msg_type', "success");
            // redirect('?module=course_category&action=list');
        }else{
            setSessionFlash('msg', 'Thêm thất bại.');
            setSessionFlash('msg_type', "danger");
            // redirect('?module=course_category&action=list');
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
<h2>Thêm mới lĩnh vực</h2>

<form action="" method="post">
    <div class="form-group">
        <label for="name">Tên lĩnh vực</label>
        <input id="name" name="name" type="text" class="form-control" placeholder="Tên lĩnh vực" /> 
    </div>
    <div class="form-group">
        <label for="slug">Slug</label>
        <input id="slug" name="slug" type="text" class="form-control" placeholder="slug" /> 
    </div>

    <button type="submit" class="btn btn-success m-3">Thêm</button>
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