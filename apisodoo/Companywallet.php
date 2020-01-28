<?php

include_once('../common.php');
include_once("authentication.php");


$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
$end = isset($_REQUEST['end']) ? $_REQUEST['end'] : '';

$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
$providers = isset($_REQUEST['providers']) ? $_REQUEST['providers'] : '';
$company = isset($_REQUEST['company']) ? $_REQUEST['company'] : '';

$limit="";

if ($end!='') {
	$limit=" LIMIT $end";
}
else if ($end!='' && $start!='') {
$limit=" LIMIT $start,$end";
}

$subQuery="where 1=1 ";
 if (trim($endDate)!=''&&trim($startDate)!='') {
  # code...
$subQuery.=" and TDate between '".$startDate."' and  '".$endDate."'";
}
  if (trim($providers)!='')
{

 $Provider= explode(',', $_REQUEST['providers']);
  $subQuery_providers='(';
  for ($i=0; $i <count($Provider) ; $i++) { 
  $subQuery_providers.=" t1.DriverId='$Provider[$i]' or";
  }
   $subQuery_providers=rtrim($subQuery_providers,'or');
 $subQuery_providers.=")";
$subQuery.=" and $subQuery_providers";

}

 if (trim($company)!='')
{

 $company= explode(',', $_REQUEST['company']);
  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" BarangayId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";

}

 $sql_company_wallet="SELECT  t1.DriverId,t2.vCompany, `Tokens`, `Type`, `TDate`, CONCAT(t3.`vName`,' ',t3.`vLastName`) as ProviderName, `ref_No` as ReferenceNumber, `payment`, `payment_status`, `commission` FROM `barangay_tokens` t1 join company t2 on t1.BarangayId=t2.iCompanyId left outer join register_driver t3 on t1.DriverId =t3.iDriverId $subQuery  $limit";
  $data_drv = $obj->MySQLSelect($sql_company_wallet);
  
$data["retrunCode"]=1;
$data["data"]=$data_drv;

echo json_encode($data);?>