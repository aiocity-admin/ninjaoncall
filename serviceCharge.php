<?php
include_once('common.php');

$type_of_service=$_REQUEST['ServiceId'];
 $iUserId = $_REQUEST['ProviderId'];   
$iTripId= isset($_REQUEST['ProviderId'])?$_REQUEST['ProviderId']:0;
$sql="select vt.fCommision,vc.vCategory_EN,vt.vVehicleType from vehicle_type vt join vehicle_category on vc vt.iVehicleCategoryId=vc.iVehicleCategoryId where iVehicleTypeId='$type_of_service'";

$data=$obj->MySQLSelect($sql);

 $iBalance = $data[0]['fCommision'];

	 $eUserType = 'Driver';
    $eFor = 'Completed - '.$data[0]['vCategory_EN'].' - '.$data[0]['vVehicleType'];
    $eType = 'Debit';
    $tDescription = '#Completed - '.$data[0]['vCategory_EN'].' - '.$data[0]['vVehicleType'].'#';  
    $ePaymentStatus = 'Settelled';
    $dDate = Date('Y-m-d H:i:s');
 

$generalobj->InsertIntoUserWallet($iUserId, $eUserType, $iBalance, $eType, $iTripId, $eFor, $tDescription, $ePaymentStatus, $dDate); 

echo json_encode(array("msg"=>"success"));

?>