<?php
require_once 'config.php';
$db = new mysqli(DBHOST, USER, PASSWORD, DATABASE);
if (isset($_POST['table'])) {
    $table = trim($_POST['table']);
    $field = trim($_POST['field']);
    $type = trim($_POST['type']);
    $null = isset($_POST['null'])?'NULL':'NOT NULL';
    $default = $_POST['default']!=''?"DEFAULT '".$_POST['default']."'":"";
    $sql="ALTER TABLE `".$table."` ADD `{$field}` ". $type ." ".$null." ".$default;
    $db->query($sql);
    $msg=$db->error;
    if ($msg){
        echo $msg;
    }
    else {
        echo "Column added";
    }
}
else{
    echo "error";
}
