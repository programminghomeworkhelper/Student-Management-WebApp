<?php

include "config.php";

$columnName = $_POST['columnName'];
$direction = $_POST['direction'];
$table = $_POST['table'];

$db = new mysqli(DBHOST, USER, PASSWORD, DATABASE);


$query = $db->query('DESCRIBE `' . $table . '`');
$fields = array();
while ($row = $query->fetch_assoc()) {
    $fields[] = $row['Field'];
}
$number_of_columns = count($fields);

$select_query = 'SELECT * FROM `' . $table . '` ORDER BY ' . $columnName . ' ' . $direction;
$result = $db->query($select_query);
$records = array();


while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

$html = '';

foreach ($records as $rowNumber => $record):
    $html .= '<tr>';
    foreach ($record as $column):
        $html .= '<td>' . $column . '</td>';
    endforeach;
    $html .= '<td>
                   <button type="button" class="btn btn-default" aria-label="Left Align" onclick="deleteRow(' . $rowNumber . ')">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-default" aria-label="Left Align"  onclick="updateRow(' . $rowNumber . ')">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </button>
                </td>
            </tr>';
endforeach;
$html .= '<tr>';
for ($i = 0; $i < $number_of_columns; $i++):
    $html .= '<td>
                    <button type="button" class="btn btn-default" aria-label="Left Align" onclick="sortTable(\'' . $fields[$i] . '\',\'asc\')">
                        <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-default" aria-label="Left Align" onclick="sortTable(\'' . $fields[$i] . '\',\'desc\')">
                        <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
                    </button>
                </td>';
endfor;
$html .= '</tr>';
echo $html;
