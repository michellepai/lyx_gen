<?php
$opCount;
if (isset($_POST['section']) && $_POST['section'] == 'op_sum'){
    include 'operation_summary.php';
}   
do {
    $opCount++;
} while (isset($_POST["ops" . $opCount . "_name"]));

for ($i = 1; $i <= $opCount; $i++) {
        if (isset($_POST['section']) &&($_POST['section'] == 'input' . $i || $_POST['section'] == 'output' . $i)) {
        include 'param_table.php';
        }
}
?>