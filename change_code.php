<?
include 'common.php';

$sql = "select vPhoneCode from country where vCountryCode = '".$_REQUEST['id']."'";
$db_data = $obj->MySQLSelect($sql);

$iDriverId = $_SESSION['sess_iUserId'];
$sql="select * from register_driver where `iDriverId` = '".$iDriverId."'";
$edit_data=$obj->sql_query($sql);
if($_REQUEST['id'] != $edit_data[0]['vCountry'])
{
	$q = "UPDATE ";
	$tbl = 'register_driver';
	$where = " WHERE `iDriverId` = '".$iDriverId."'";
	$query = $q ." `".$tbl."` SET `ePhoneVerified` = 'No' ".$where;
	$obj->sql_query($query);
	
}

echo $db_data[0]['vPhoneCode'];
exit;
?>