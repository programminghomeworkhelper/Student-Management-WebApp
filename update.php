<?php
require_once 'config.php';
$db = new mysqli(DBHOST, USER, PASSWORD, DATABASE);
$count = 0;
$fields = '';
$where='';

if (isset($_POST['newData']['table'])) {
    $table = $_POST['newData']['table'];
    $columns = array();
    $values = array();
    foreach ($_POST['newData'] as $col => $val) {
        if ($col != 'table' and $col != 'rowNumber') {
            if ($count++ != 0) $fields .= ', ';
            $col = $col;
            $val = $val;
            $fields .= "`$col` = '$val'";
        }
    }
    $updated=json_decode($_POST['updated']);
    $count=0;
    foreach ($updated as $col => $val) {
        if ($count++ != 0) $where .= 'and ';
        $col = $col;
        $val = $val;
        $where .= "`$col` = '$val'";
    }
    $query = "UPDATE `" . $table . "` SET $fields WHERE $where;";
    $db->query($query);
    echo "Row updated ";
} else {
    echo "Error in updating row";
}
