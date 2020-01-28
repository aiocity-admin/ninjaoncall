<?php
include_once('../../common.php');


$filename = "users report.xlsx"; 
/*header("Content-Disposition: attachment;filename=\"$filename\"");
header("Content-Type:application/vnd.ms-excel");
header('Cache-Control:max-age=0');*/

 include_once('../../xlsxwriter.class.php'); 
   header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');


//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';

$list = isset($_REQUEST['list']) ? $_REQUEST['list'] : '';

$typeofreport=isset($_REQUEST['typeofreport']) ? $_REQUEST['typeofreport'] : 'Number of Registered Users';

$promocode = isset($_REQUEST['promocode']) ? $_REQUEST['promocode'] : '';
$ord = '';
$SELECT=" COUNT(distinct u.iUserId) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,c.iCompanyId ";

$groupby ="group by c.iCompanyId ";
if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY vVehicleType ASC";
  else
  $ord = " ORDER BY vVehicleType DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY riderName ASC";
  else
  $ord = " ORDER BY riderName DESC";
}

if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY c.vCompany ASC";
  else
  $ord = " ORDER BY c.vCompany DESC";
}


//End Sorting


// Start Search Parameters
$ssql='';
$action = isset($_REQUEST['action']) ? $_REQUEST['action']: '';
$searchCompany = isset($_REQUEST['searchCompany']) ? $_REQUEST['searchCompany'] : '';
$searchDriver = isset($_REQUEST['searchDriver']) ? $_REQUEST['searchDriver'] : '';
$searchRider = isset($_REQUEST['searchRider']) ? $_REQUEST['searchRider'] : '';
$serachTripNo = isset($_REQUEST['serachTripNo']) ? $_REQUEST['serachTripNo'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
$vStatus = isset($_REQUEST['vStatus']) ? $_REQUEST['vStatus'] : '';
$eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';

 if (trim($searchCompany)!='null')
{

 $company= explode(',', $searchCompany);
}
$iVehicleTypeId = isset($_REQUEST['iVehicleTypeId']) ? $_REQUEST['iVehicleTypeId'] : '';

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
  if (trim($typeofreport)!="Number of First Time Users" && trim($typeofreport)!="Number of Active Users")
  {

 if (trim($searchCompany)!='null')
{

  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" d.iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery=" and $subQuery_company";

}
$ssql.=$subQuery;
  //$ssql.=" AND d.iCompanyId ='".$searchCompany."'";
}

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

  if($eType == 'Ride'){
    $ssql.=" AND t.eType ='".$eType."' AND t.iRentalPackageId = 0 AND t.eHailTrip = 'No' ";
  } elseif($eType == 'RentalRide'){
    $ssql.=" AND t.eType ='Ride' AND t.iRentalPackageId > 0";
  } elseif($eType == 'HailRide'){
    $ssql.=" AND t.eType ='Ride' AND t.eHailTrip = 'Yes'";
  } else {
    $ssql.=" AND t.eType ='".$eType."' ";
  }
}


if(trim($typeofreport)=="Number of Active Users")
{



  $groupby =" group by u.iUserId ";

  $header = array(
     'User'=>'string',
  'Company'=>'string'
   
);
  if($searchCompany!=''){

 if (trim($searchCompany)!='null')
{

  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.="  c.iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery=" and $subQuery_company";
$sub_sql=$subQuery;
}

 // $sub_sql=" and  C.iCompanyId ='".$searchCompany."'";

}

if($list=='Yes')
{
  $SELECT="CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName, c.vCompany";

$SELECT="SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1 and u.eStatus='Active' $ssql $trp_ssql $sub_sql $groupby  $ord";

}
else{
$SELECT="u.iUserId ,CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName, c.vCompany";




$SELECT="SELECT COUNT(distinct u.iUserId) AS riderName, c.vCompany FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1 and u.eStatus='Active' $ssql $trp_ssql  $sub_sql group by c.iCompanyId  $ord";




}


}
else if(trim($typeofreport)=="Number of Services by Category")
{

if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY vCategory_EN ASC";
  else
  $ord = " ORDER BY vCategory_EN DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY riderName ASC";
  else
  $ord = " ORDER BY riderName DESC";
}

if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY vCompany ASC";
  else
  $ord = " ORDER BY vCompany DESC";
}

if($list=='Yes')
{

   // $SELECT=" CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vc.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId";

  $SELECT=" CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vcp.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId,u.iUserId";

  $groupby ='';//" group by iVehicleCategoryId,iCompanyId ";

if($iVehicleTypeId!="")
  $sub_query=" and iVehicleCategoryId='$iVehicleTypeId'";


if($iVehicleTypeId=="Ride" || $iVehicleTypeId=="Deliver")
  $sub_query=" and eType='$iVehicleTypeId'";


  $SELECT="select  riderName,vCompany,case when vCategory_EN='' or vCategory_EN is NULL then eType else vCategory_EN end  from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql   ) xx WHERE 1=1  $sub_query $groupby $ord";

    $header = array(
     'User'=>'string',
  'Company'=>'string',
  'Category'=>'string'
   
);

}
else {

  $SELECT="t.iTripId AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vcp.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId";

  $groupby =" group by iVehicleCategoryId,iCompanyId,eType ";


  $SELECT="select count(riderName) as riderName,vCompany,case when vCategory_EN=''  or vCategory_EN is NULL  then eType else vCategory_EN end   from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql ) xx $groupby  $ord ";

      $header = array(
     'Service Requested'=>'string',
  'Company'=>'string',
  'Category'=>'string'
   
);

}
}
else if(trim($typeofreport)=="Number of Services Requested")
{
    $ord=" ORDER BY vCompany ASC,vCategory_EN asc ";

if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY vCategory_EN ASC";
  else
  $ord = " ORDER BY vCategory_EN DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY riderName ASC";
  else
  $ord = " ORDER BY riderName DESC";
}

if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY vCompany ASC";
  else
  $ord = " ORDER BY vCompany DESC";
}

if( $list=='Yes'&& $iVehicleTypeId!="")
{


  $SELECT=" CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName,c.vCompany,case when vcp.vCategory_EN='' or vcp.vCategory_EN is NULL then t.eType else vcp.vCategory_EN end as vCategory_EN";

 $join="left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId ";

  $groupby =" group by u.iUserId,c.iCompanyId,vcp.iVehicleCategoryId ";
$sub_query="";
if($iVehicleTypeId!="")
  $sub_query=" and vcp.iVehicleCategoryId='$iVehicleTypeId'";


if($iVehicleTypeId=="Ride" || $iVehicleTypeId=="Deliver")
  $sub_query=" and t.eType='$iVehicleTypeId'";

if($iVehicleTypeId=="Ride" || $iVehicleTypeId=="Deliver")
{
  $join="";

  if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY t.eType ASC";
  else
  $ord = " ORDER BY t.eType DESC";
}

   $SELECT=" CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName,c.vCompany,t.eType as vCategory_EN ";
     $groupby =" group by u.iUserId,t.eType ";

}

  $SELECT="SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId right JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId  $join WHERE 1=1 $ssql $trp_ssql $sub_query  $groupby  $ord ";

    $header = array(
     'User'=>'string',
  'Company'=>'string',
  'Category'=>'string'
   
);

}


else if($list=='Yes')
{



  $SELECT="count(u.iUserId) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vcp.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId";

  $groupby =" group by iVehicleCategoryId,iCompanyId,eType ";

/*if($iVehicleTypeId!="")
  $sub_query=" and iVehicleCategoryId='$iVehicleTypeId'";


if($iVehicleTypeId=="Ride" || $iVehicleTypeId=="Deliver")
  $sub_query=" and eType='$iVehicleTypeId'";*/

  $SELECT="select count(riderName) as riderName,vCompany,CASE when vCategory_EN='' or vCategory_EN is NULL then eType else vCategory_EN end as vCategory_EN from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql group by u.iUserId,c.iCompanyId,vcp.iVehicleCategoryId,t.eType   ) xx  $groupby $ord";

  $header = array(
     'User'=>'string',
  'Company'=>'string',
  'Category'=>'string'
   
);

}
else {

  $header = array(
      'Company'=>'string',
    'User'=>'string',
  'Category'=>'string'
   
);
/*  $SELECT="count(u.iUserId) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vcp.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId";

  $groupby =" group by iVehicleCategoryId,iCompanyId,eType ";



  $SELECT="select sum(riderName) as riderName,vCompany,count(1) as vCategory_EN  from ( select count(riderName) as riderName,vCompany, vVehicleType,vCategory_EN,eType,iCompanyId,iVehicleTypeId,iVehicleCategoryId  from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql group by u.iUserId,c.iCompanyId,vcp.iVehicleCategoryId,t.eType  ) xx  $groupby ) xxx   group by iCompanyId $ord";*/

    $SELECT="select vCompany,count(1) as riderName,sum(noofservice) as vCategory_EN from (select count(1) as noofservice, riderName,vCompany,iVehicleCategoryId,vCategory_EN,iCompanyId from (SELECT u.iUserId AS riderName,c.vCompany ,case when vcp.iVehicleCategoryId is NULL then t.eType else vcp.iVehicleCategoryId end as iVehicleCategoryId,case when vcp.vCategory_EN is NULL or vcp.vCategory_EN='' then t.eType else vcp.vCategory_EN end as vCategory_EN,c.iCompanyId FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql group by u.iUserId,c.iCompanyId,vcp.iVehicleCategoryId,t.eType  ) xx GROUP by riderName,iCompanyId  ) xxx  GROUP by noofservice,iCompanyId $ord ";

}


}
else if (trim($typeofreport)=="Number of First Time Users") {

if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY vVehicleType ASC";
  else
  $ord = " ORDER BY vVehicleType DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY riderName ASC";
  else
  $ord = " ORDER BY riderName DESC";
}

if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY vCompany ASC";
  else
  $ord = " ORDER BY vCompany DESC";
}



  if($searchCompany!=''){

 if (trim($searchCompany)!='null')
{

  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.="  iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery=" and $subQuery_company";
$sub_sql=$subQuery;
}

  //$sub_sql=" AND iCompanyId ='".$searchCompany."'";

}

  $SELECT=" COUNT(distinct u.iUserId) AS ridercount,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vc.vCategory_EN,t.eType,CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName,u.iUserId,c.iCompanyId ";

  $groupby =" group by vt.iVehicleTypeId ";

if($list=='Yes')
{
$SELECT="select riderName,vCompany,vCategory_EN from (select count(vVehicleType) as vVehicleTypeCount, riderName,vCompany,vCategory_EN,iCompanyId from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1 $ssql $trp_ssql $groupby) t where ridercount=1  group by iUserId) tt where vVehicleTypeCount=1 $sub_sql $ord";



  $header = array(
     'User'=>'string',
  'Company'=>'string',
  'Category'=>'string'
   
);

}
else{

  $header = array(
     'User'=>'string',
  'Company'=>'string'
   
);


  $SELECT="select  count(iUserId) as riderName,vCompany from (select count(vVehicleType) as vVehicleTypeCount, riderName,vCompany,vVehicleType,vCategory_EN,eType,iUserId,iCompanyId from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1 $ssql  $trp_ssql $groupby  ) t where ridercount=1  group by iUserId) tt where vVehicleTypeCount=1  $sub_sql   GROUP by iCompanyId  $ord";
}
}
else {






$ssql="where 1=1";
if($startDate!=''){
  $ssql.=" AND Date(tRegistrationDate) >='".$startDate."'";
}
if($endDate!=''){
  $ssql.=" AND Date(tRegistrationDate) <='".$endDate."'";
}

  $SELECT="SELECT  CONCAT(vName,' ',MiddleName,' ',vLastName) AS riderName,vEmail FROM `register_user` $ssql $ord";


   $header = array(
  'Name'=>'string',
    'Email Address'=>'string'
);

}


$sql = $SELECT;

   $data = $obj->MySQLSelect($sql);
// Write data to file
$flag = false;

$writer = new XLSXWriter();



  

 $writer->writeSheetHeader('Sheet1', $header);

for ($i=0; $i <count($data) ; $i++) { 
  
   $writer->writeSheetRow('Sheet1', $data[$i]);
}

 $writer->writeToStdOut();

?>