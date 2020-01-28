<?php
include_once('common.php');


$filename = "report.xlsx"; 
/*header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");
header('Cache-Control: max-age=0');*/
 include_once('xlsxwriter.class.php'); /*you can get xlsxwriter.class.php from given GitHub link*/
   header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

//preparing query 
$subQuery="";
$subQuery="where a.Type='Debit' ";
if (isset($_REQUEST['search'])) 
{
if (trim($_REQUEST['from'])!=''&&trim($_REQUEST['to'])!='') {
  # code...

$subQuery.=" and a.TDate between '".$_REQUEST['from']." 00:00:00' and  '".$_REQUEST['to']." 23:59:59'";
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


}

$token_query="SELECT  a.`Tokens`,`TDate`,b.vName,b.vLastName FROM `barangay_tokens` a join register_driver b on a.DriverId=b.iDriverId $subQuery order by `TDate` desc";
$data=$obj->MySQLSelect($token_query);
// Write data to file
$flag = false;


$writer = new XLSXWriter();
 $header = array(
  'Tokens'=>'0.00',
  'Date'=>'datetime',
  'FirstName'=>'string',
  'LastName'=>'string');
 $writer->writeSheetHeader('Sheet1', $header);

for ($i=0; $i <count($data) ; $i++) { 
 	
  /*  if (!$flag) {
        echo implode("\t", array_keys($data[$i])) . "\r\n";
        $flag = true;
    }
    echo implode("\t", array_values($data[$i])) . "\r\n";*/
            $writer->writeSheetRow('Sheet1', $data[$i]);
}

 $writer->writeToStdOut();
?>