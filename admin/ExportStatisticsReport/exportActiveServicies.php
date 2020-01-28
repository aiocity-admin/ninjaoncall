<?php
include_once('../../common.php');


$filename = "Active Providers report.xlsx"; 
/*header("Content-Disposition: attachment;filename=\"$filename\"");
header("Content-Type:application/vnd.ms-excel");
header('Cache-Control:max-age=0');*/

 include_once('../../xlsxwriter.class.php'); /*you can get xlsxwriter.class.php from given GitHub link*/
   header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

   $sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 1;

  $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 0;
  $reportfor= isset($_REQUEST['reportfor']) ? $_REQUEST['reportfor'] : "Company";
$iVehicleCategoryId=isset($_REQUEST['iVehicleCategoryId']) ? $_REQUEST['iVehicleCategoryId'] : "";
$providers=isset($_REQUEST['providers']) ? $_REQUEST['providers'] : "null";
$companies=isset($_REQUEST['company']) ? $_REQUEST['company'] : "null";

  $ord = '';

  if($sortby == 1){

    if($order == 0)

    $ord = " ORDER BY c.vCompany ASC";

    else

    $ord = " ORDER BY c.vCompany DESC";

  }

  

  if($sortby == 2){

    if($order == 0)

    $ord = " ORDER BY vcp.vCategory_EN ASC";

    else

    $ord = " ORDER BY vcp.vCategory_EN  DESC";

  }


  if($sortby == 3){

    if($order == 0)

    $ord = " ORDER BY rd.vName ASC";

    else

    $ord = " ORDER BY rd.vName DESC";

  }
  
  //End Sorting


 $subQuery="where 1=1 and  ( vcp.eStatus='Active' or vt.eType!='UberX' )  ";
 if (isset($_REQUEST['search'])) {


  if (trim($_REQUEST['providers'])!='null')
{

 $Provider= explode(',', $_REQUEST['providers']);
  $subQuery_providers='(';
  for ($i=0; $i <count($Provider) ; $i++) { 
  $subQuery_providers.=" rd.iDriverId='$Provider[$i]' or";
  }
   $subQuery_providers=rtrim($subQuery_providers,'or');
 $subQuery_providers.=")";
$subQuery.=" and $subQuery_providers";

}

$sub_Query="where  ";

 if (trim($_REQUEST['company'])!='null')
{

 $company= explode(',', $_REQUEST['company']);
  $subQuery_company='(';
  $sub_Query.="(";

  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" c.iCompanyId='$company[$i]' or";
  $sub_Query.=" iCompanyId='$company[$i]' or";

  }
   $subQuery_company=rtrim($subQuery_company,'or');
    $sub_Query=rtrim($sub_Query,'or');
 $subQuery_company.=")";
 $sub_Query.=")";
$subQuery.=" and $subQuery_company";

}

 if (trim($iVehicleCategoryId)!='')
{


 if (trim($iVehicleCategoryId)!='null')
{

 $iVehicleCategoryId= explode(',', $iVehicleCategoryId);

  $subQuery_company='(';
  for ($i=0; $i <count($iVehicleCategoryId) ; $i++) { 



  if($reportfor=="Provider"&&(trim($iVehicleCategoryId[$i])=='Ride'||trim($iVehicleCategoryId[$i])=='Deliver'))
{
$subQuery_company.="  vt.eType='".$iVehicleCategoryId[$i]."' or";

}
else if(trim($iVehicleCategoryId[$i])!='Ride'&&trim($iVehicleCategoryId[$i])!='Deliver') {
$subQuery_company.="  vcp.iVehicleCategoryId='".$iVehicleCategoryId[$i]."' or";


}

  //$subQuery_company.=" c.iCompanyId='$company[$i]' or";
 
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";

}




}


}


  if($reportfor=="Provider")
{
   $sql_company_wallet="select c.vCompany,concat(rd.vName,' ',rd.MiddleName,' ',rd.vLastName) as name,case when vcp.vCategory_EN='' or vcp.vCategory_EN is NULL  then vt.eType else vcp.vCategory_EN end as vCategory_EN   FROM company c right join  register_driver rd on c.iCompanyId=rd.iCompanyId left join driver_vehicle dv on rd.iDriverId=dv.iDriverId left join vehicle_type vt on  dv.vCarType like concat('%',vt.iVehicleTypeId,'%')  or (  vt.iVehicleTypeId=dv.iDriverVehicleId and vt.eType!='UberX') LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId $subQuery group by vcp.iVehicleCategoryId,rd.iDriverId $ord ";


}else {
 $sql_company_wallet="select c.vCompany,vcp.vCategory_EN  FROM company c right join company_services cs on c.iCompanyId=cs.CompanyId left join vehicle_type vt on vt.iVehicleTypeId=cs.ServiceId LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId $subQuery group by vcp.iVehicleCategoryId,c.iCompanyId  $ord ";

}
   $data = $obj->MySQLSelect($sql_company_wallet);
// Write data to file
$flag = false;

$writer = new XLSXWriter();
if($reportfor!="Provider")
{
   $header = array(
  'Company Name'=>'string',
  'Service Category'=>'string'
);

  }
  else
  {
 $header = array(
  'Company Name'=>'string',
  'Provider'=>'string',
  'Service Category'=>'string'
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