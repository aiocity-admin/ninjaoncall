<?php
include_once('../common.php');
	
if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();

$vCountryCode = isset($_REQUEST['vCountryCode']) ? $_REQUEST['vCountryCode'] : '';
if($vCountryCode != "") {
	$sql="SELECT eEnableToll,iCountryId FROM  `country` WHERE vCountryCode = '".$vCountryCode."'";
	$data = $obj->MySQLSelect($sql);
	echo $data[0]['eEnableToll'];exit;
}

?>