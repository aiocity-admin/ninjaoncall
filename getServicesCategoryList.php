<?php 
include_once('common.php');

$iDriverId = isset($_REQUEST['iDriverId']) ? $_REQUEST['iDriverId'] :'';
$iCompanyId = isset($_REQUEST['iCompanyId']) ? $_REQUEST['iCompanyId'] :'';
$GetAll= isset($_REQUEST['GetAll']) ? $_REQUEST['GetAll'] :'Yes';

if($iDriverId!="")
{
if($GetAll=="No")
{
$sql_cat="SELECT vcp.vCategory_EN FROM `vehicle_type` vt join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId join (SELECT iVehicleCategoryId,vCategory_EN FROM vehicle_category where iParentId='0' ) vcp on vcp.iVehicleCategoryId=vc.iParentId join company_services as cs on vt.iVehicleTypeId=cs.ServiceId where cs.CompanyId='$iCompanyId' and vcp.vCategory_EN not in (SELECT drs.iVehicleCategoryId as vCategory_EN FROM driver_registered_service drs where drs.iDriverId = '$iDriverId') GROUP by vCategory_EN order by vCategory_EN";

	$db_cat = $obj->MySQLSelect($sql_cat);

	$ser_cat="SELECT drs.iVehicleCategoryId as vCategory_EN FROM driver_registered_service drs where drs.iDriverId = '$iDriverId'";
	$ser_cat = $obj->MySQLSelect($ser_cat);
	
	 $db_cat_array=array();

	 for ($i=0; $i <count($ser_cat) ; $i++) { 
	 	 $db_cat_array[$i]= $ser_cat[$i]['vCategory_EN'];
	 }
	if(!in_array("Ride", $db_cat_array))
	{
    array_push($db_cat, array("vCategory_EN"=>"Ride"));
	}
	if(!in_array("Delivery", $db_cat_array))
	{
    array_push($db_cat, array("vCategory_EN"=>"Delivery"));
	}

}else
{
	$sql_cat = "SELECT drs.iVehicleCategoryId as vCategory_EN  FROM   driver_registered_service drs where drs.iDriverId = '" . $iDriverId . "'  ORDER BY vCategory_EN";
	$db_cat = $obj->MySQLSelect($sql_cat);
}

}
else if ($iCompanyId!="") 
{
$sql_cat="SELECT vcp.vCategory_EN FROM `vehicle_type` vt join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId join (SELECT iVehicleCategoryId,vCategory_EN FROM vehicle_category where iParentId='0' ) vcp on vcp.iVehicleCategoryId=vc.iParentId join company_services as cs on vt.iVehicleTypeId=cs.ServiceId where cs.CompanyId='$iCompanyId' GROUP by vCategory_EN order by vCategory_EN";
$db_cat = $obj->MySQLSelect($sql_cat);
array_push($db_cat, array("vCategory_EN"=>"Ride"));
array_push($db_cat, array("vCategory_EN"=>"Delivery"));
}
else
{
	$sql_cat = "SELECT vCategory_EN FROM `vehicle_category` where eStatus='Active' and iParentId=0 ORDER BY vCategory_EN";

	$db_cat = $obj->MySQLSelect($sql_cat);
array_push($db_cat, array("vCategory_EN"=>"Ride"));
array_push($db_cat, array("vCategory_EN"=>"Delivery"));

}


echo json_encode($db_cat);
?>