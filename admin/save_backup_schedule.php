<?php 
include_once('../common.php');

$tbl_name = "configurations";
$q = "UPDATE ";

if(isset($_REQUEST['BACKUP_ENABLE'])) {
foreach($_REQUEST as $key=>$value) {
	$where = " WHERE `vName` = '".$key."'";
	$query = $q . " `" . $tbl_name . "` SET
	`vValue` = '" . $value . "'"
	. $where;
	$obj->sql_query($query);
}
}else {
	$where = " WHERE `vName` = 'BACKUP_ENABLE'";
	$query = $q . " `" . $tbl_name . "` SET
	`vValue` = 'No'"
	. $where;
	$obj->sql_query($query);
}
?>