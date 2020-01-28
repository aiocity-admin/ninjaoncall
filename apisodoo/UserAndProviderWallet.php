<?php
include_once('../common.php');
include_once("authentication.php");

$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
$end = isset($_REQUEST['end']) ? $_REQUEST['end'] : '';
 $user_available_balance="";
$limit="";
if ($end!='') {
	$limit=" LIMIT $end";
}
else if ($end!='' && $start!='') {
$limit=" LIMIT $start,$end";
}


   $startDate = isset($_REQUEST['startDate'])?$_REQUEST['startDate']:"";
    $endDate = isset($_REQUEST['endDate'])?$_REQUEST['endDate']:"";
    $eUserType = isset($_REQUEST['eUserType'])?$_REQUEST['eUserType']:"";
    $eFor = isset($_REQUEST['searchBalanceType'])?$_REQUEST['searchBalanceType']:"";
    $Payment_type = isset($_REQUEST['searchPaymentType'])?$_REQUEST['searchPaymentType']:"";
if (trim($eUserType)=="") 
{
	
$data["returnCode"]=0;
$data["error"]="eUserType is required.Please pass Driver or Rider.";
echo json_encode($data);

	exit;
}


    if ($eUserType == "Driver") {

        $iDriverId = isset($_REQUEST['iDriverId'])? $_REQUEST['iDriverId']:"";
        if (trim($iDriverId)=="") {

$data["returnCode"]=0;
$data["error"]="iDriverId is required.Please pass Driver Id.";
echo json_encode($data);

	  exit;
        }
        $iUserId = "";
        $user_available_balance = $generalobj->get_user_available_balance($iDriverId, $eUserType);
    }

    if ($eUserType == "Rider") {

        $iUserId = isset($_REQUEST['iUserId'])? $_REQUEST['iUserId']:"";
            if (trim($iUserId)=="") {


      $data["returnCode"]=0;
$data["error"]="iUserId is required.Please pass Rider Id.";
echo json_encode($data);
	  exit;
        }
        $iDriverId = "";
        $user_available_balance = $generalobj->get_user_available_balance($iUserId, $eUserType);
    }

    if ($iDriverId != '') {
        $ssql .= " AND iUserId = '" . $iDriverId . "'";
    }
    if ($iUserId != '') {
        $ssql .= " AND iUserId = '" . $iUserId . "'";
    }

    if ($startDate != '') {
        $ssql .= " AND Date(dDate) >='" . $startDate . "'";
    }
    if ($endDate != '') {
        $ssql .= " AND Date(dDate) <='" . $endDate . "'";
    }

    if ($eUserType) {
        $ssql .= " AND eUserType = '" . $eUserType . "'";
    }
    if ($eFor != '') {
        $ssql .= " AND eFor = '" . $eFor . "'";
    }

    if ($Payment_type != '') {

          if(strtolower(trim($Payment_type))=="failure")
        {
          $ssql .= " AND eType  is NULL ";  
        }
        else {
        $ssql .= " AND eType = '" . $Payment_type . "'";

        }

       // $ssql .= " AND eType = '" . $Payment_type . "'";
    }

       $sql = "SELECT * From user_wallet where 1=1 $ssql $limit"; /*LIMIT $start,$per_page*/
    $db_result = $obj->MySQLSelect($sql);


$data["retrunCode"]=1;
$data["data"]=$db_result;
$data["available_balance"]= $user_available_balance;
echo json_encode($data);
?>