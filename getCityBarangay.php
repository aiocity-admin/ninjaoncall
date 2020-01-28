<?php
include_once('common.php');

$iCityId=$_REQUEST['iCityId'];
$iStateId=$_REQUEST['iStateId'];
//$iCountryId=$_REQUEST['iCountryId'];

$sql="select Barangay,ID from barangay where iCityId='$iCityId' and iStateId='$iStateId' and eStatus='Active'";

$data=$obj->MySQLSelect($sql);

echo json_encode($data);

?>