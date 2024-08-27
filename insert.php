<?php
require_once 'config.php';
$db = new mysqli(DBHOST, USER, PASSWORD, DATABASE);
$count = 0;
$fields = '';
if (isset($_POST['table'])) {
    $table = $_POST['table'];
    $columns = array();
    $values = array();
    foreach ($_POST as $col => $val) {
        if ($col != 'table' and $col != 'rowNumber') {
            if ($count++ != 0) $fields .= ', ';
            $col = $col;
            $val = $val;

                $fields .= "`$col` = '$val'";

        }
    }
    $query = "INSERT INTO `" . $table . "` SET $fields;";
    $db->query($query);
    echo "Record added";
} else {
    echo "Error in adding row";
}
