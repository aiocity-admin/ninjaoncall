<?php
include_once('../common.php');


$filename = "company report.xlsx"; 
/*header("Content-Disposition: attachment;filename=\"$filename\"");
header("Content-Type:application/vnd.ms-excel");
header('Cache-Control:max-age=0');*/

 include_once('../xlsxwriter.class.php'); /*you can get xlsxwriter.class.php from given GitHub link*/
   header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

 $subQuery="where 1=1 ";
 if (isset($_REQUEST['search'])) {

 if (trim($_REQUEST['from'])!=''&&trim($_REQUEST['to'])!='') {
  # code...
$subQuery.=" and TDate between '".$_REQUEST['from']." 00:00:00' and  '".$_REQUEST['to']." 23:59:59'";
}
  if (trim($_REQUEST['providers'])!='null')
{

 $Provider= explode(',', $_REQUEST['providers']);
  $subQuery_providers='(';
  for ($i=0; $i <count($Provider) ; $i++) { 
  $subQuery_providers.=" DriverId='$Provider[$i]' or";
  }
   $subQuery_providers=rtrim($subQuery_providers,'or');
 $subQuery_providers.=")";
$subQuery.=" and $subQuery_providers";

}

 if (trim($_REQUEST['company'])!='null')
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



}


 $sql_company_wallet="SELECT   (case when `ref_No`='' and Type='Debit' then 'Add to Provider' when `ref_No`='' and Type='Credit' then 'Adjust to provider' else `ref_No` end) as `ref_No` ,t2.vCompany as Company, `Tokens`, `Type`, `TDate` as Date, CONCAT(t3.`vName`,' ',t3.`vLastName`) as Provider, case when `payment`='' then '0' else payment end as payment, `payment_status` as Status, `commission` as ServiceFee FROM `barangay_tokens` t1 join company t2 on t1.BarangayId=t2.iCompanyId left outer join register_driver t3 on t1.DriverId =t3.iDriverId $subQuery order by TDate desc";

$data=$obj->MySQLSelect($sql_company_wallet);
// Write data to file
$flag = false;

$writer = new XLSXWriter();
 $header = array(
    'Refference number'=>'string',
  'Company Name'=>'string',
  'Tokens'=>'0.00',
  'Type'=>'string',
  'Date'=>'string',
  'Provider'=>'string',
 'Payment'=>'0.00',
  'Status'=>'string',
   'Service fee'=>'0.00'
);
 $writer->writeSheetHeader('Sheet1', $header);

for ($i=0; $i <count($data) ; $i++) { 
  

   $writer->writeSheetRow('Sheet1', $data[$i]);
}

 $writer->writeToStdOut();

?>