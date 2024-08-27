<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1, shrink-to-fit=no">
    <link
            href="//maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"
            rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="style.css">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script
            src="//maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
<table id="links">
    <tr>
        <td><a href="admin.php?link=student">Student</a></td>
        <td><a href="admin.php?link=course">Course</a></td>
        <td><a href="admin.php?link=section">Section</a></td>
        <td><a href="admin.php?link=grade_report">Grade Report</a></td>
        <td><a href="admin.php?link=prerequisite">Prerequisite</a></td>
    </tr>

</table>
<?php
require_once 'config.php';
$db = new mysqli(DBHOST, USER, PASSWORD, DATABASE);
$table = isset($_GET['link']) ? $_GET['link'] : 'student';
$query = $db->query('DESCRIBE `' . $table . '`');
$fields = array();
$i = 0;
while ($row = $query->fetch_assoc()) {
    $fields[$i]['Field'] = $row['Field'];
    $fields[$i]['Type'] = $row['Type'];
    $fields[$i]['Null'] = $row['Null'];
    $fields[$i]['Default'] = $row['Default'];
    $i++;
}
$number_of_columns = count($fields);

$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.key_column_usage WHERE table_schema='" . DATABASE . "' and table_name = '" . $table . "' AND CONSTRAINT_NAME = 'PRIMARY'";
$query = $db->query($sql);
$row = $query->fetch_assoc();
$key = $row['COLUMN_NAME'];
?>
<form id="table-form" method="post">
    <input type="hidden" name="table" value="<?php echo $table;?>">
    <table id="table">
        <thead>
        <tr>
            <th>Field</th>
            <th>Type</th>
            <th>Null</th>
            <th>Default</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($fields as $field): ?>
            <tr>
                <td>
                    <?php echo $field['Field'] ?>
                </td>
                <td>
                    <?php echo $field['Type'] ?>
                </td>
                <td>
                    <?php echo $field['Null'] ?>
                </td>
                <td>
                    <?php echo $field['Default'] ?>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <table id="table-add">
        <thead>
        <tr>
            <th>Field</th>
            <th>Type</th>
            <th>Null</th>
            <th>Default</th>
        </tr>
        </thead>
        <tr>
            <td><input name="field" type="text"></td>
            <td><input name="type" type="text"></td>
            <td><input name="null" type="checkbox"></td>
            <td><input name="default" type="text"></td>
        </tr>
        <tr colspan="4"><td><button id="new_row" class="btn btn-default" type="button"
                                    onclick="addField();">Add Field
                </button></td></tr>
        <tbody>
        </tbody>
    </table>

</form>
</body>
<script>
    function addField(){
        $.ajax({
            url: 'addField.php',
            type: 'post',
            cache : false,
            data: $('#table-form').serialize(),
            success: function(response) {
                alert(response);
                location.reload();
            }
        });
    }
</script>

</html>