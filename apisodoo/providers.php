<?php
include_once('../common.php');
include_once("authentication.php");


$sql = "select iDriverId,vName as FirstName,MiddleName,vLastName as LastName,Suffix,vEmail from register_driver WHERE eStatus != 'Deleted' order by vName";
$db_drivers = $obj->MySQLSelect($sql);

$data["retrunCode"]=1;
$data["data"]=$db_drivers;

echo json_encode($data);
?>