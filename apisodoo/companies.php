<?php
include_once('../common.php');
include_once("authentication.php");

$sql = "select iCompanyId,vCompany AS CompanyName,vEmail from company WHERE eStatus != 'Deleted' order by vCompany";
$db_company = $obj->MySQLSelect($sql);

$data["retrunCode"]=1;
$data["data"]=$db_company;

echo json_encode($data);
?>