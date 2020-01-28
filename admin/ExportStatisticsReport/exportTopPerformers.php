<?php
include_once('../../common.php');


$filename = "Top Performers report.xlsx"; 
/*header("Content-Disposition: attachment;filename=\"$filename\"");
header("Content-Type:application/vnd.ms-excel");
header('Cache-Control:max-age=0');*/

 include_once('../../xlsxwriter.class.php'); /*you can get xlsxwriter.class.php from given GitHub link*/
   header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');


//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 1;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 1;
$promocode = isset($_REQUEST['promocode']) ? $_REQUEST['promocode'] : '';
$maxlimit= isset($_REQUEST['maxlimit']) ? $_REQUEST['maxlimit'] : '';
$reportType= isset($_REQUEST['reportType']) ? $_REQUEST['reportType'] : 'All';

$ord = '';
if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY numberofservice ASC";
  else
  $ord = " ORDER BY numberofservice DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY t.tTripRequestDate ASC";
  else
  $ord = " ORDER BY t.tTripRequestDate DESC";
}

if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY c.vCompany ASC";
  else
  $ord = " ORDER BY c.vCompany DESC";
}

if($sortby == 4){
  if($order == 0)
  $ord = " ORDER BY d.vName ASC";
  else
  $ord = " ORDER BY d.vName DESC";
}

if($sortby == 5){
  if($order == 0)
  $ord = " ORDER BY vcp.vCategory_EN ASC";
  else
  $ord = " ORDER BY vcp.vCategory_EN DESC";
}

if($sortby == 6){
  if($order == 0)
  $ord = " ORDER BY d.vEmail ASC";
  else
  $ord = " ORDER BY d.vEmail DESC";
}
//End Sorting


// Start Search Parameters
$ssql='';
$ssqlCom='';
$action = isset($_REQUEST['action']) ? $_REQUEST['action']: '';
$searchCompany = isset($_REQUEST['searchCompany']) ? $_REQUEST['searchCompany'] : '';
$searchDriver = isset($_REQUEST['searchDriver']) ? $_REQUEST['searchDriver'] : '';
$searchRider = isset($_REQUEST['searchRider']) ? $_REQUEST['searchRider'] : '';
$serachTripNo = isset($_REQUEST['serachTripNo']) ? $_REQUEST['serachTripNo'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
$vStatus = isset($_REQUEST['vStatus']) ? $_REQUEST['vStatus'] : 'complete';
$eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';
if($startDate!=''){
  $ssql.=" AND Date(t.tTripRequestDate) >='".$startDate."'";
}
if($endDate!=''){
  $ssql.=" AND Date(t.tTripRequestDate) <='".$endDate."'";
}
if($serachTripNo!=''){
  $ssql.=" AND t.vRideNo ='".$serachTripNo."'";
}


if($searchCompany!=''){




 if (trim($searchCompany)!='null')
{

 $company= explode(',', $searchCompany);
  $subQuery_company='(';

  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" d.iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";

}

  $ssql.=$subQuery;//" AND d.iCompanyId ='".$searchCompany."'";

}
if($searchDriver!=''){
  $ssql.=" AND t.iDriverId ='".$searchDriver."'";
}
if($searchRider!=''){
  $ssql.=" AND t.iUserId ='".$searchRider."'";
}
if($vStatus == "onRide") {
  $ssql .= " AND (t.iActive = 'On Going Trip' OR t.iActive = 'Active') AND t.eCancelled='No'";
}else if($vStatus == "cancel") {
  $ssql .= " AND (t.iActive = 'Canceled' OR t.eCancelled='yes')";
}else if($vStatus == "complete") {
  $ssql .= " AND t.iActive = 'Finished' AND t.eCancelled='No'";
}

if($eType!=''){


 if (trim($eType)!='null')
{

 $eType= explode(',', $eType);
  $subQuery_company='(';

  for ($i=0; $i <count($eType) ; $i++) { 


        if($eType[$i] == 'Ride'){
    $subQuery_company.="  t.eType ='".$eType[$i]."'  or";
  }
   else if($eType[$i] == 'Deliver') {
    $subQuery_company.="  t.eType ='".$eType[$i]."'  or";
  }else {
    $subQuery_company.="  vcp.iVehicleCategoryId ='".$eType[$i]."'  or";
  }

  //$subQuery_company.=" d.iCompanyId='$eType[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');

 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";

}

$ssql.=$subQuery;
  /*  if($eType == 'Ride'){
    $ssql.=" AND t.eType ='".$eType."' AND t.iRentalPackageId = 0 AND t.eHailTrip = 'No' ";
  }
   else if($eType == 'Deliver') {
    $ssql.=" AND t.eType ='".$eType."' ";
  }else {
    $ssql.=" AND vt.iVehicleTypeId ='".$eType."' ";
  }*/
  
}
$having="";
$having2="";
if($maxlimit!="")
{

  $having = " HAVING numberofservice >= $maxlimit ";
  $having2=" HAVING Total >= $maxlimit ";


  
}


$sql = "SELECT COUNT(t.iTripId) as numberofservice,c.vCompany , CONCAT(d.vName,' ',d.MiddleName,' ',d.vLastName) AS driverName,d.vEmail FROM trips t right JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1  $ssql $trp_ssql group by d.iDriverId $having $ord";



   $data = $obj->MySQLSelect($sql);
// Write data to file
$flag = false;

$writer = new XLSXWriter();

   $header = array(
  'Number of Service Requests'=>'string',
    'Company Name'=>'string',

  'Provider'=>'string',
  'Email Address'=>'string'

);

  

 $writer->writeSheetHeader('Sheet1', $header);

for ($i=0; $i <count($data) ; $i++) { 
  

 /*   if (!$flag) {
        echo implode("\t", array_keys($data[$i])) . "\r\n";
        $flag = true;
    }
    echo implode("\t", array_values($data[$i])) . "\r\n";*/
   $writer->writeSheetRow('Sheet1', $data[$i]);
}

 $writer->writeToStdOut();

?>