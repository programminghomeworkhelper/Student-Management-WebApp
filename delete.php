<?php
require_once 'config.php';
$db = new mysqli(DBHOST, USER, PASSWORD, DATABASE);
$count = 0;
$where = '';
if (isset($_POST['table'])) {
    $table = $_POST['table'];
    $columns = array();
    $values = array();
    foreach ($_POST['data'] as $col => $val) {
            if ($count++ != 0) $where .= 'and ';
            $col = $col;
            $val = $val;
            $where .= "`$col` = '$val'";
    }
    $query = "DELETE FROM `" . $table . "` WHERE $where";
    $db->query($query);
    echo "Deleted";
} else {
    echo "Error in deleting row";
}
