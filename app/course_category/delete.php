<?php
if(!defined('_TEST')){
    die('Access Denied');
}

$filter = filterData('get');

if(!empty($filter)){
    $cateID=$filter['id'];

    $checkCate = getOne("SELECT * FROM course_category WHERE id = $cateID");

    if(!empty($checkCate)){
        //kiem tra trong bang course
        $checkCourse = getRows("SELECT * FROM course WHERE category_id = $cateID");

        if($checkCourse > 0){
        //con ton tai khoa hoc cua linh vuc
        setSessionFlash('msg', 'Lĩnh vực đang còn khóa học.');
        setSessionFlash('msg_type', "danger");
        redirect('?module=course_category&action=list');            
        }else{
            $deleteStatus = delete('course_category',"id = $cateID");
            if($deleteStatus){
            setSessionFlash('msg', 'Xóa lĩnh vực thành công.');
            setSessionFlash('msg_type', "success");
            redirect('?module=course_category&action=list');
            }
        }
    }else{
        setSessionFlash('msg', 'Danh mục không tồn tại.');
        setSessionFlash('msg_type', "danger");
        redirect('?module=course_category&action=list');
    }
}else{
    setSessionFlash('msg', 'Danh mục không tồn tại.');
    setSessionFlash('msg_type', "danger");
}








?>