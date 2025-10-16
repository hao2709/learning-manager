<?php
if (!defined('_TEST')) {
    die('Access Denied');
}

//Truy van nhieu dong du lieu
function getAll($sql)
{
    global $conn;
    $stm = $conn->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

//dem so dong tra ve
function getRows($sql){
    global $conn;
    $stm = $conn->prepare($sql);
    $stm->execute();
    return $stm-> rowCount();
}

//truy van 1 dong du lieu
function getOne($sql)
{
    global $conn;
    $stm = $conn->prepare($sql);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_ASSOC);
    return $result;
}

//insert du lieu
function insert($table, $data)
{
    global $conn;

    $keys = array_keys($data);
    $cot = implode(',', $keys);
    $place = ':' . implode(', :', $keys);

    $sql = "INSERT INTO $table ($cot) VALUES ($place)"; //name ->placehoder
    $stm = $conn->prepare($sql);
    $rel = $stm->execute($data);

    return $rel;
}

//update de lieu
function update($table, $data, $condition = '')//condition la dieu kien
{
    global $conn;
    $update = '';
    foreach($data as $key => $value) {
        $update .= $key . '=:' .$key .',';
    }
    $update = trim($update, ',');//xoa dau phay cuoi

    if(!empty($condition)) {
        $sql = "UPDATE $table SET $update WHERE $condition";
    }else{
        $sql = "UPDATE $table SET $update"; 
    }

    $tmp = $conn->prepare($sql);

    //thuc thi cau lenh
    $rel = $tmp->execute($data);
    return $rel;
}

//delete du lieu
function delete($table, $condition='')
{
    global $conn;
    if(!empty($condition)) {
        $sql = "DELETE FROM $table WHERE $condition";
    }else{
        $sql = "DELETE FROM $table";
    }

    $stm = $conn->prepare($sql);
    $rel = $stm->execute();
    return $rel;
}


//ham lay ID du lieu moi insert
function lastID() {
    global $conn;
    return $conn->lastInsertId();

}