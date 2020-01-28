<?php 
include_once('common.php');

$filename = "reviews.xlsx"; 

 include_once('xlsxwriter.class.php'); /*you can get xlsxwriter.class.php from given GitHub link*/
   header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
$type=(isset($_REQUEST['reviewtype']) && $_REQUEST['reviewtype'] !='')?$_REQUEST['reviewtype']:'Driver';
$reviewtype=$type;

$option = isset($_REQUEST['option'])?stripslashes($_REQUEST['option']):"";
$keyword = isset($_REQUEST['keyword'])?stripslashes($_REQUEST['keyword']):"";
$searchDate = isset($_REQUEST['searchDate'])?$_REQUEST['searchDate']:"";
$ssql = '';
if($keyword != ''){
    if($option != '') {
        if (strpos($option, 'r.eStatus') !== false) {
            $ssql.= " AND ".stripslashes($option)." LIKE '".$keyword."'";
        }else {
            $option_new = $option;
            if($option == 'drivername'){
              $option_new = "CONCAT(rd.vName,' ',rd.vLastName)";
            } 
            if($option == 'ridername'){
              $option_new = "CONCAT(ru.vName,' ',ru.vLastName)";
            }
            $ssql.= " AND ".stripslashes($option_new)." LIKE '%".$keyword."%'";
        }
    }else {
        $ssql.= " AND (t.vRideNo LIKE '%".$keyword."%' OR  concat(rd.vName,' ',rd.vLastName) LIKE '%".$keyword."%' OR concat(ru.vName,' ',ru.vLastName) LIKE '%".$keyword."%' OR r.vRating1 LIKE '%".$keyword."%')";
    }
}
// End Search Parameters


//Pagination Start
$chkusertype ="";
$ratingFor="";
$ratingOf="";
if($type == "Driver")
{
	$chkusertype = "Passenger";
    $ratingFor="Provider";
    $ratingOf="User";
           $sub=",CONCAT(CONCAT(rd.vName,' ',rd.vLastName) ,' (',rd.vAvgRating,')' ),CONCAT(ru.vName,' ',ru.vLastName)";



}
else
{
	$chkusertype = "Driver";
    $ratingFor="User";
    $ratingOf="Provider";
     $sub=",CONCAT(CONCAT(ru.vName,' ',ru.vLastName),' (',ru.vAvgRating,')'),CONCAT(rd.vName,' ',rd.vLastName)";

}

	
$iCompanyId=$_SESSION['sess_iUserId'];


 $sql = "SELECT t.vRideNo$sub,r.vRating1,r.tDate,r.vMessage

FROM ratings_user_driver as r 
LEFT JOIN trips as t ON r.iTripId=t.iTripId 
LEFT JOIN register_driver as rd ON rd.iDriverId=t.iDriverId 
LEFT JOIN register_user as ru ON ru.iUserId=t.iUserId 
WHERE 1=1 AND r.eUserType='".$chkusertype."' And ru.eStatus!='Deleted' and rd.iCompanyId='$iCompanyId'   $ssql $adm_ssql $ord";

$data=$obj->MySQLSelect($sql);


$writer = new XLSXWriter();
 $header = array(
  'Ride/Job Number'=>'string',
  $ratingFor.' Name (Average Rate)'=>'string',
  $ratingOf.' Name'=>'string',
  'Rate'=>'string',
'Date'=>'datetime',
'Message'=>'string'
);
 $writer->writeSheetHeader('Sheet1', $header);

for ($i=0; $i <count($data) ; $i++) { 
    

            $writer->writeSheetRow('Sheet1', $data[$i]);
}

 $writer->writeToStdOut();
			?>