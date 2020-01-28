<?php
include_once('../common.php');
include_once("authentication.php");


$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
$end = isset($_REQUEST['end']) ? $_REQUEST['end'] : '';
$limit="";

if ($end!='') {
	$limit=" LIMIT $end";
}
else if ($end!='' && $start!='') {
$limit=" LIMIT $start,$end";
}


$ssql = '';
$iDriverId = isset($_REQUEST['iDriverId']) ? $_REQUEST['iDriverId'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
$vEmail = isset($_REQUEST['vEmail']) ? $_REQUEST['vEmail'] : '';

if ($startDate != '' && $endDate != '') {
	$search_startDate = $startDate.' 00:00:00';
	$search_endDate = $endDate.' 23:59:00';
    $ssql .= " AND dlr.dLoginDateTime BETWEEN '" . $search_startDate . "' AND '" . $search_endDate . "'";
}
if ($iDriverId != '') {
    $ssql .= " AND rd.iDriverId = '" . $iDriverId . "'";
}
if ($vEmail != '') {
    $ssql .= " AND rd.vEmail = '" . $vEmail . "'";
}

$sql = "SELECT rd.iDriverId,rd.vName, rd.vLastName, rd.vEmail, dlr.dLoginDateTime, dlr.dLogoutDateTime
FROM driver_log_report AS dlr LEFT JOIN register_driver AS rd ON rd.iDriverId = dlr.iDriverId where 1=1 AND rd.eStatus != 'Deleted' $ssql  $limit";
$db_log_report_total_time = $obj->MySQLSelect($sql);

$data["retrunCode"]=1;
$data["data"]=$db_log_report_total_time;

echo json_encode($data);
?>