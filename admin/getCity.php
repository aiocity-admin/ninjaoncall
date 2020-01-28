<?php
	include_once('../common.php');

$iStateId=$_REQUEST['State'];
$query="select vCity,iCityId from city where iCountryId='168' and iStateId='$iStateId'"; 
$data=$obj->sql_query($query);
echo json_encode($data);
?>

