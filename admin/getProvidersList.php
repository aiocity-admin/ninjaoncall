<?php 

include_once('../common.php');

$VehicleId=$_REQUEST['VehicleId'];
$CompanyId=$_REQUEST['CompanyId'];


$query="select vLicencePlate from driver_vehicle where iDriverVehicleId='$VehicleId'";

  $db_data_vLicencePlate = $obj->MySQLSelect($query);
  //echo $query;
$vLicencePlate=$db_data_vLicencePlate[0]['vLicencePlate'];
$query="SELECT iDriverId,vName,vLastName,vEmail FROM `register_driver`  WHERE  iCompanyId='$CompanyId'";



//echo $query;
  $db_data = $obj->MySQLSelect($query);

$query="SELECT distinct a.iDriverId FROM `register_driver` a join driver_vehicle b on a.iDriverId=b.iDriverId and a.iCompanyId=b.iCompanyId  WHERE  a.iCompanyId='$CompanyId' and b.vLicencePlate='$vLicencePlate'" ;
//echo $query;
  $db_data2 = $obj->MySQLSelect($query);

for ($i=0; $i <count($db_data); $i++) 
{ 

for ($j=0; $j <count($db_data2) ; $j++) { 

if ($db_data[$i]['iDriverId']==$db_data2[$j]['iDriverId']) {
$db_data[$i]['isChecked']=1;

}

else
{
	if (isset($db_data[$i]['isChecked'])) {
	
	$db_data[$i]['isChecked']=$db_data[$i]['isChecked']!=1?0:1;
}
else {
$db_data[$i]['isChecked']=0;
}
}

}

}




echo json_encode($db_data);
?>