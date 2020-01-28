<?php
include_once('../common.php');
include_once("authentication.php");

$sql = "select iUserId,vName as FirstName,MiddleName,vLastName as LastName,Suffix,vEmail from register_user WHERE eStatus != 'Deleted' order by vName";
$db_rider = $obj->MySQLSelect($sql);

$data["retrunCode"]=1;
$data["data"]=$db_rider;

echo json_encode($data);
?>