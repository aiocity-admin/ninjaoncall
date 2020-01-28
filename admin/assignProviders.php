<?php
include_once('../common.php');


$providers=$_POST['providers'];
$CompanyId=$_POST['CompanyId'];
$VehicleId=$_POST['VehicleId'];



$query="select   `iCompanyId`, `iMakeId`, `iModelId`, `iYear`, `vLicencePlate`, `vColour`, `eStatus`, `vInsurance`, `vPermit`, `vRegisteration`, `eCarX`, `eCarGo`, `vCarType`, `vRentalCarType`, `eHandiCapAccessibility`, `eType`, `Owner_First_Name`, `Owner_Middle_Name`, `Owner_Last_Name`, `Owner_Address`, `Province`, `City`, `Barangay`, `Vehicle_details`, `Reason` from driver_vehicle where iDriverVehicleId='$VehicleId'";

  $db_data = $obj->MySQLSelect($query);



$query="select vLicencePlate from driver_vehicle where iDriverVehicleId='$VehicleId'";


  $db_data_vLicencePlate = $obj->MySQLSelect($query);
  //echo $query;
$db_data_vLicencePlate = $obj->MySQLSelect($query);

$vLicencePlate=$db_data_vLicencePlate[0]['vLicencePlate'];

 $obj->MySQLSelect("delete from driver_vehicle where vLicencePlate='$vLicencePlate'");

$i=0;
foreach ($providers as $providers_id)
{ 

	

$iCompanyId=$db_data[$i]['iCompanyId'];
$iMakeId=$db_data[$i]['iMakeId'];
$iModelId=$db_data[$i]['iModelId'];
$iYear=$db_data[$i]['iYear'];
$vLicencePlate=$db_data[$i]['vLicencePlate'];
$vColour=$db_data[$i]['vColour'];
$eStatus=$db_data[$i]['eStatus'];
$vInsurance=$db_data[$i]['vInsurance'];
$vPermit=$db_data[$i]['vPermit'];
$vRegisteration=$db_data[$i]['vRegisteration'];
$eCarX=$db_data[$i]['eCarX'];
$eCarGo=$db_data[$i]['eCarGo'];
$vCarType=$db_data[$i]['vCarType'];
$vRentalCarType=$db_data[$i]['vRentalCarType'];
$eHandiCapAccessibility=$db_data[$i]['eHandiCapAccessibility'];
$eType=$db_data[$i]['eType'];


$Owner_First_Name=$db_data[$i]['Owner_First_Name'];
$Owner_Middle_Name=$db_data[$i]['Owner_Middle_Name'];
$Owner_Last_Name=$db_data[$i]['Owner_Last_Name'];
$Owner_Address=$db_data[$i]['Owner_Address'];
$Province=$db_data[$i]['Province'];
$City=$db_data[$i]['City'];
$Barangay=$db_data[$i]['Barangay'];
$Vehicle_details=$db_data[$i]['Vehicle_details'];
$Reason=$db_data[$i]['Reason'];


$query="INSERT INTO `driver_vehicle`(`iDriverId`, `iCompanyId`, `iMakeId`, `iModelId`, `iYear`, `vLicencePlate`, `vColour`, `eStatus`, `vInsurance`, `vPermit`, `vRegisteration`, `eCarX`, `eCarGo`, `vCarType`, `vRentalCarType`, `eHandiCapAccessibility`, `eType`, `Owner_First_Name`, `Owner_Middle_Name`, `Owner_Last_Name`, `Owner_Address`, `Province`, `City`, `Barangay`, `Vehicle_details`, `Reason`) VALUES ('$providers_id','$iCompanyId','$iMakeId','$iModelId','$iYear','$vLicencePlate','$vColour','$eStatus','$vInsurance','$vPermit','$vRegisteration','$eCarX','$eCarGo','$vCarType','$vRentalCarType','$eHandiCapAccessibility','$eType','$Owner_First_Name','$Owner_Middle_Name','$Owner_Last_Name','$Owner_Address','$Province','$City','$Barangay','$Vehicle_details','$Reason')";

$obj->MySQLSelect($query);



}
echo "Taxi has been assigned successfully.";

?>