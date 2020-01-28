<?php
include_once('common.php');


/*$filename = "wallet report.xls"; 
header("Content-Disposition: attachment;filename=\"$filename\"");
header("Content-Type:application/vnd.ms-excel");
header('Cache-Control:max-age=0');*/

  include_once('xlsxwriter.class.php'); /*you can get xlsxwriter.class.php from given GitHub link*/
    $filename = "wallet report.xlsx";
   header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

$iCompanyId = $_SESSION['sess_iUserId'];
//preparing query 
$subQuery="where a.eUserType='Driver' and iCompanyId='$iCompanyId' ";
if (isset($_REQUEST['search'])) 
{
if (trim($_REQUEST['from'])!=''&&trim($_REQUEST['to'])!='') {
  # code...
$subQuery.=" and a.dDate between '".$_REQUEST['from']." 00:00:00' and  '".$_REQUEST['to']." 23:59:59'";
}
  if (trim($_REQUEST['providers'])!='null')
{

 $Provider= explode(',', $_REQUEST['providers']);
  $subQuery_providers='(';
  for ($i=0; $i <count($Provider) ; $i++) { 
  $subQuery_providers.=" b.iDriverId='$Provider[$i]' or";
  }
   $subQuery_providers=rtrim($subQuery_providers,'or');
 $subQuery_providers.=")";
$subQuery.=" and $subQuery_providers";

}

}

$token_query="SELECT b.vEmail as Email,b.vName as FirstName,b.vLastName as LastName,a.iBalance as Tokens,a.eType as Type,a.dDate as Date,a.tDescription as Description  FROM `user_wallet` a join register_driver b on a.iUserId=b.iDriverId $subQuery order by a.dDate desc";

$data=$obj->MySQLSelect($token_query);
// Write data to file
$flag = false;
 $writer = new XLSXWriter();
 $header = array(
  'Email'=>'string',
  'FirstName'=>'string',
  'LastName'=>'string',
  'Tokens'=>'0.00',
    'Type'=>'string',
  'Date'=>'datetime',

  'Description'=>'string');
 $writer->writeSheetHeader('Sheet1', $header);
for ($i=0; $i <count($data) ; $i++) { 
 	

    if (!$flag) {
       // echo implode("\t", array_keys($data[$i])) . "\r\n";

        $flag = true;
    }
   // echo implode("\t", array_values($data[$i])) . "\r\n";
        $writer->writeSheetRow('Sheet1', $data[$i]);

}

 $writer->writeToStdOut();


?>