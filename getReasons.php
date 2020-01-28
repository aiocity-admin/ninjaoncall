<?php
include_once('common.php');

$reason_for=$_REQUEST['reason_for'];

$sql="select Reason from reasons where Reason_For like '%$reason_for%' order by Reason";
$data=$obj->MySQLSelect($sql);

echo json_encode($data);

?>