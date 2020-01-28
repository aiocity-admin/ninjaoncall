<?php
include_once('common.php');


$sql="SELECT `iCompanyId`, `vCompany` FROM `company` where eStatus='Active'";
$data=$obj->MySQLSelect($sql);

echo json_encode($data);

?>