<?php
include_once("../common.php");
if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$company_id = isset($_REQUEST['company_id'])?$_REQUEST['company_id']:'';

$subQuery="";
 if (trim($company_id)!='null')
{

 $company= explode(',', $company_id);
  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";

}

if(isset($company_id) && !empty($company_id)) {
	$sql = 	"select iDriverId,CONCAT(vName,' ',MiddleName,' ',vLastName) AS driverName,vEmail from register_driver WHERE eStatus != 'Deleted' $subQuery   order by vName,MiddleName,vLastName ";
	$db_drivers = $obj->MySQLSelect($sql);
	//echo "<option value=''>Select Providers</option>";
	foreach($db_drivers as $dbd) { 
	   echo "<option value='".$dbd["iDriverId"]."'>".$generalobjAdmin->clearName($dbd['driverName'])." (".$dbd['vEmail'].")"."</option>";
	}
} else {
	$sql = 	"select iDriverId,CONCAT(vName,' ',vLastName) AS driverName from register_driver WHERE eStatus != 'Deleted'";
	$db_drivers = $obj->MySQLSelect($sql);
	echo "<option value=''>Select Driver</option>";
	foreach($db_drivers as $dbd) { 
		echo "<option value='".$dbd["iDriverId"]."'>".$generalobjAdmin->clearName($dbd['driverName'])."</option>";
	}
}
?>