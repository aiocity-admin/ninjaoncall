<?php
include_once('../../common.php');


$filename = "online Providers report.xlsx"; 
/*header("Content-Disposition: attachment;filename=\"$filename\"");
header("Content-Type:application/vnd.ms-excel");
header('Cache-Control:max-age=0');*/

 include_once('../../xlsxwriter.class.php'); /*you can get xlsxwriter.class.php from given GitHub link*/
   header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

  $sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : '';

  $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
$iVehicleCategoryId=isset($_REQUEST['iVehicleCategoryId']) ? $_REQUEST['iVehicleCategoryId'] : "";
$providers=isset($_REQUEST['providers']) ? $_REQUEST['providers'] : "null";
$companies=isset($_REQUEST['company']) ? $_REQUEST['company'] : "null";
$list=isset($_REQUEST['list']) ? $_REQUEST['list'] : "";

$currentDateTime=Date('Y-m-d H:i:s');


$stop_date = new DateTime($currentDateTime);
$stop_date->modify('-1 day');
$dateLimitFrom= $stop_date->format('Y-m-d H:i:s');
 $ord = " ORDER BY  vCompany ASC,numberofdriver DESC";
    $ord2 = " ORDER BY  vCompany ASC,numberofdriver ASC";


$groupby="group by iCompanyId";

$groupby_inner="group by iCompanyId,numberofdriver";

  if($sortby == 1){
   if($order == 0)
{
    $ord = " ORDER BY numberofdriver ASC";
    $ord2 = ' ORDER BY numberofdriver ASC';
  }

    else
{
    $ord = " ORDER BY numberofdriver DESC";
    $ord2 = " ORDER BY numberofdriver DESC";

 }

  }

  

  if($sortby == 2){

   if($order == 0)
{
    $ord = " ORDER BY vCompany ASC";
    $ord2 = " ORDER BY c.vCompany ASC";

}
    else
{
    $ord = " ORDER BY vCompany DESC";
    $ord2 = " ORDER BY c.vCompany DESC";
}

  }

  if($sortby == 3){

   if($order == 0)
{
    $ord = " ORDER BY tRegistrationDate ASC";
    $ord2 = " ORDER BY rd.tRegistrationDate ASC";
}

    else
{
    $ord = " ORDER BY tRegistrationDate DESC";
    $ord2 = " ORDER BY rd.tRegistrationDate DESC";
}

  }

  if($sortby == 4){

   if($order == 0)
{
    $ord = " ORDER BY vCategory_EN,eType ASC";
    $ord2 = " ORDER BY vcp.vCategory_EN,vt.eType ASC";
}

    else
    {
    $ord = " ORDER BY vCategory_EN,eType DESC";

    $ord2 = " ORDER BY vcp.vCategory_EN,vt.eType DESC";
}
  }
  
  //End Sorting
 function fetchtripstatustimeMAXinterval(){
    global $generalobj, $obj,$FETCH_TRIP_STATUS_TIME_INTERVAL;
    
    //$FETCH_TRIP_STATUS_TIME_INTERVAL = $generalobj->getConfigurations("configurations", "FETCH_TRIP_STATUS_TIME_INTERVAL");
    $FETCH_TRIP_STATUS_TIME_INTERVAL_ARR = explode("-",$FETCH_TRIP_STATUS_TIME_INTERVAL);
    
    $FETCH_TRIP_STATUS_TIME_INTERVAL_MAX = $FETCH_TRIP_STATUS_TIME_INTERVAL_ARR[1];
    
    return $FETCH_TRIP_STATUS_TIME_INTERVAL_MAX;
  }
    $cmpMinutes = ceil((fetchtripstatustimeMAXinterval() + 60) / 60);

    $str_date = @date('Y-m-d H:i:s', strtotime('-'.$cmpMinutes.' minutes'));
 $subQuery="where rd.eStatus='Active' and (vcp.eStatus='Active' or vt.eType!='UberX' ) and (dlr.dLoginDateTime<='$currentDateTime'  and  dlr.dLogoutDateTime='0000-00-00 00:00:00')  AND tLocationUpdateDate > '$str_date'";


 //$subQuery="where rd.eStatus='Active' and (vcp.eStatus='Active' or vt.eType!='UberX' ) and (dlr.dLoginDateTime<='$currentDateTime'  and  dlr.dLogoutDateTime='0000-00-00 00:00:00') ";
  if (isset($_REQUEST['search'])) {


 if (trim($_REQUEST['company'])!='null')
{

 $company= explode(',', $_REQUEST['company']);
  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" c.iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";

}

 if (trim($iVehicleCategoryId)!='')
{


   if (trim($iVehicleCategoryId)!='null')
{

  $groupby=" group by iCompanyId,iVehicleCategoryId,eType ";

$groupby_inner=" group by rd.iDriverId,c.iCompanyId,vcp.iVehicleCategoryId,vt.eType ";


 $eType= explode(',', $iVehicleCategoryId);
  $subQuery_company='(';

  for ($i=0; $i <count($eType) ; $i++) { 


if(trim($eType[$i])=='Ride'||trim($eType[$i])=='Deliver')
{
$subQuery_company.=" dv.eType='".$eType[$i]."' or";

}else {

$subQuery_company.=" vcp.iVehicleCategoryId='".$eType[$i]."' or";


}
      

  //$subQuery_company.=" d.iCompanyId='$eType[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');

 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";

}




}
}



if($list=="Yes")
{


 //$sql_company_wallet="select  numberofdriver, vCompany,iCompanyId,tRegistrationDate,iDriverId,iVehicleCategoryId,eType,vCategory_EN  from ( select CONCAT(rd.vName,' ',rd.MiddleName,' ',rd.vLastName)  as numberofdriver, c.vCompany,c.iCompanyId,vt.iVehicleTypeId,vt.eType,rd.tRegistrationDate,rd.iDriverId,vcp.iVehicleCategoryId,vcp.vCategory_EN   FROM company c left join  register_driver rd on c.iCompanyId=rd.iCompanyId left join driver_vehicle dv on rd.iDriverId=dv.iDriverId left join vehicle_type vt on  dv.vCarType like concat('%',vt.iVehicleTypeId,'%') LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  $subQuery group by rd.iDriverId  ) x    $ord   LIMIT $start, $per_page";

  $sql_company_wallet=" select CONCAT(rd.vName,' ',rd.MiddleName,' ',rd.vLastName) as numberofdriver, c.vCompany,case when vcp.vCategory_EN is Null then vt.eType else vcp.vCategory_EN end as vCategory_EN,rd.tRegistrationDate,dlr.dLoginDateTime from company c left join  register_driver rd on c.iCompanyId=rd.iCompanyId right join driver_log_report dlr on (dlr.iDriverId=rd.iDriverId and dlr.dLoginDateTime>'$dateLimitFrom')  left join driver_vehicle dv on rd.iDriverId=dv.iDriverId left join vehicle_type vt on  dv.vCarType like concat('%',vt.iVehicleTypeId,'%') LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  $subQuery  $groupby_inner  $ord2";


}
else
{

 //$sql_company_wallet="select sum(numberofdriver) as numberofdriver, vCompany,iCompanyId  from (select count(numberofdriver) as numberofdriver, vCompany,iCompanyId  from ( select  rd.iDriverId as numberofdriver, c.vCompany,c.iCompanyId,vt.iVehicleTypeId,vt.eType   FROM company c left join  register_driver rd on c.iCompanyId=rd.iCompanyId left join driver_vehicle dv on rd.iDriverId=dv.iDriverId left join vehicle_type vt on  dv.vCarType like concat('%',vt.iVehicleTypeId,'%') LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  $subQuery group by rd.iDriverId  ) x  group by iCompanyId,iVehicleTypeId,eType ) xx   group by iCompanyId   $ord  LIMIT $start, $per_page";

  $sql_company_wallet="select count(numberofdriver) as numberofdriver, vCompany,case when vCategory_EN is Null then eType else vCategory_EN end as vCategory_EN  from ( select rd.iDriverId as numberofdriver, c.vCompany,c.iCompanyId,vt.iVehicleTypeId,vt.eType,vcp.iVehicleCategoryId,vcp.vCategory_EN from company c left join  register_driver rd on c.iCompanyId=rd.iCompanyId right join driver_log_report dlr on (dlr.iDriverId=rd.iDriverId and dlr.dLoginDateTime>'$dateLimitFrom') left join driver_vehicle dv on rd.iDriverId=dv.iDriverId left join vehicle_type vt on  dv.vCarType like concat('%',vt.iVehicleTypeId,'%') LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  $subQuery $groupby_inner ) x  $groupby  $ord ";
}


   $data = $obj->MySQLSelect($sql_company_wallet);
// Write data to file
$flag = false;

$writer = new XLSXWriter();
if($list=="Yes")
{
   $header = array(
  'Provider'=>'string',
  'Company'=>'string',
  'Service Category '=>'string',
    'Sing Up Date'=>'datetime',
        'Online from'=>'datetime'


);

  }
  else
  {
 $header = array(
  'Number of Providers'=>'string',
  'Company'=>'string',
  'Service Category '=>'string'
);
}
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