<?php
include_once('../common.php');
include_once("authentication.php");

$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
$end = isset($_REQUEST['end']) ? $_REQUEST['end'] : '';

$subQuery="where a.eUserType='Driver'";
$limit="";
if ($end!='') {
	$limit=" LIMIT $end";
}
else if ($end!='' && $start!='') {
$limit=" LIMIT $start,$end";
}

$token_query="SELECT b.vEmail,b.vName,b.vLastName,b.iDriverId,a.iBalance,a.eType,a.dDate,a.tDescription,a.ref_No as ReferenceNumber FROM `user_wallet` a join register_driver b on a.iUserId=b.iDriverId $subQuery order by a.dDate desc $limit";
$result=$obj->MySQLSelect($token_query);

$data["retrunCode"]=1;
$data["data"]=$result;

echo json_encode($data);
?>