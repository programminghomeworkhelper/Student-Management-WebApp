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
        <td><a href="index.php?link=student">Student</a></td>
        <td><a href="index.php?link=course">Course</a></td>
        <td><a href="index.php?link=section">Section</a></td>
        <td><a href="index.php?link=grade_report">Grade Report</a></td>
        <td><a href="index.php?link=prerequisite">Prerequisite</a></td>
    </tr>

</table>
<?php
require_once 'config.php';
$db = new mysqli(DBHOST, USER, PASSWORD, DATABASE);
$table = isset($_GET['link']) ? $_GET['link'] : 'student';
$query = $db->query('DESCRIBE `' . $table . '`');
$fields = array();
while ($row = $query->fetch_assoc()) {
    $fields[] = $row['Field'];
}
$number_of_columns = count($fields);

$query = $db->query('SELECT * FROM `' . $table . '`');
$records = array();
while ($row = $query->fetch_assoc()) {
    $records[] = $row;
}
$sql="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.key_column_usage WHERE table_schema='".DATABASE."' and table_name = '".$table."' AND CONSTRAINT_NAME = 'PRIMARY'";
$query = $db->query($sql);
$row = $query->fetch_assoc();
$key=$row['COLUMN_NAME'];
?>
<form id="generate-form" method="post">
    <table id="form">
        <thead>
        <tr>
            <?php foreach ($fields as $field): ?>
                <th>
                    <?php echo "$field"; ?>
                </th>
            <?php endforeach; ?>
            <th>&nbsp</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($records as $rowNumber=>$record): ?>
            <tr>
                <?php foreach ($record as $column): ?>
                    <td>
                        <?php echo $column ?>
                    </td>
                <?php endforeach; ?>
                <td>
                   <button type="button" class="btn btn-default" aria-label="Left Align" id="deleteButton<?php echo $rowNumber; ?>" onclick="deleteRow('<?php echo $rowNumber; ?>')">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-default" aria-label="Left Align" id="editButton<?php echo $rowNumber; ?>" onclick="updateRow('<?php echo $rowNumber; ?>')">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-default" style="display:none" aria-label="Left Align" id="okButton<?php echo $rowNumber; ?>">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <?php for ($i = 0; $i < $number_of_columns; $i++): ?>
                <td>
                    <button type="button" class="btn btn-default" aria-label="Left Align" onclick="sortTable('<?php echo $fields[$i]; ?>','asc')">
                        <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
                    </button>

                    <button type="button" class="btn btn-default" aria-label="Left Align" onclick="sortTable('<?php echo $fields[$i]; ?>','desc')">
                        <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
                    </button>

                </td>
            <?php endfor; ?>
        </tr>
        </tbody>
    </table>
    <div id="newRow">
    <table>
        <tr>
            <?php foreach ($fields as $field): ?>
                <th>
                    <?php echo "$field"; ?>
                </th>
            <?php endforeach; ?>
        </tr>
        <tr>
            <input type="hidden" name="table" id="table" value="<?php echo $table ?>">
            <input type="hidden"  name="rowNumber" id="rowNumber"/>
            <?php foreach ($fields as $field): ?>
            <td>
                <input type="text" name="<?php echo $field; ?>"/>
            </td>
            <?php endforeach; ?>
            <td>
            <button id="enter" class="btn btn-default" name="enter"
                    onclick="return false;">Enter
            </button>
            <button id="update" class="btn btn-default" name="update"
                    onclick="return false;">Update
            </button>
            </td>
        </tr>
    </table>
    </div>

    <div class="row">
        <div class="col-12 text-right">
            <button id="new_row" class="btn btn-default"
                    onclick="return false;">New Row
            </button>
        </div>

    </div>

</form>
</body>
<script>
    $(document).ready(
        function () {
            $("#new_row").click(
                function () {
                    $(this).hide();
                    $('#newRow').show();
                    $('#update').hide();
                });
        });
    $("#enter").click(
        function () {
            $.ajax({
                url: 'insert.php',
                type: 'post',
                cache : false,
                data: $('#generate-form').serialize(),
                success: function(response) {
                    alert(response);
                    location.reload();
                }
            });
        });
    $("#update").click(
        function () {
            let rows=<?php echo json_encode($records); ?>;
            let rowData=JSON.stringify(rows[$('#rowNumber').val()]);

            var data = {};
            $.each($('#generate-form').serializeArray(), function(_, kv) {
                data[kv.name] = kv.value;
            });

            $.ajax({
                url: 'update.php',
                type: 'post',
                cache : false,
                data: {updated:rowData,newData:data},
                success: function(response) {
                    alert(response);
                    location.reload();
                }
            });
        });
</script>
<script type="text/javascript">
    function deleteRow(rowNumber){
        let rows=<?php echo json_encode($records); ?>;
        let rowData=rows[rowNumber];
        $.ajax({
            url: 'delete.php',
            type: 'post',
            cache : false,
            data: {data:rowData,
                    table:'<?php echo $table; ?>'},
            success: function(response) {
                alert(response);
                location.reload();
            }
        });
    }

    function updateRow(rowNumber){
        let rows=<?php echo json_encode($records); ?>;
        let rowData=rows[rowNumber];
        rowData['rowNumber']=rowNumber;
        rowData['table']='<?php echo $table;?>';
        const div=document.getElementById("newRow");
        div.style.display="block";
        const inputElements=div.getElementsByTagName("input");
        for (i=0;i<inputElements.length;i++) {
            let elemId=inputElements[i].name;
            inputElements[i].value=rowData[elemId];
        }
        document.getElementById("deleteButton"+rowNumber).style.display="none";
        document.getElementById("editButton"+rowNumber).style.display="none";
        document.getElementById("okButton"+rowNumber).style.display="block";

        document.getElementById("update").style.display="block";
        document.getElementById("enter").style.display="none";
        document.getElementById("new_row").style.display="none";
    }


    function sortTable(columnName,direction){
        let table='<?php echo $table; ?>';
        $.ajax({
            url:'sort.php',
            type:'post',
            data:{columnName:columnName,direction:direction,table:table},
            success: function(response){
                $("#form tr:not(:first)").remove();
                $("#form").append(response);

            }
        });
    }
</script>

</html>