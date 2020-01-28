<?php
include_once('../common.php');
include_once("authentication.php");
if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");
     $generalobjAdmin = new General_admin();
}

$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
$end = isset($_REQUEST['end']) ? $_REQUEST['end'] : '';
$limit="";

if ($end!='') {
	$limit=" LIMIT $end";
}
else if ($end!='' && $start!='') {
$limit=" LIMIT $start,$end";
}

$ssql = '';

$action = isset($_REQUEST['action']) ? $_REQUEST['action']: '';
$searchCompany = isset($_REQUEST['searchCompany']) ? $_REQUEST['searchCompany'] : '';
$searchDriver = isset($_REQUEST['searchDriver']) ? $_REQUEST['searchDriver'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';

//data for select fields
$sql = "select iCompanyId,vCompany,vEmail from company WHERE eStatus != 'Deleted' order by vCompany";
$db_company = $obj->MySQLSelect($sql);


$sql = "select iDriverId,CONCAT(vName,' ',vLastName) AS driverName,vEmail from register_driver WHERE eStatus != 'Deleted' order by vName";
$db_drivers = $obj->MySQLSelect($sql);

//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';

$ord = ' ORDER BY rd.iDriverId DESC';
if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY rd.iDriverId ASC";
  else
  $ord = " ORDER BY rd.iDriverId DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY rd.vName ASC";
  else
  $ord = " ORDER BY rd.vName DESC";
}

if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY rd.vBankAccountHolderName ASC";
  else
  $ord = " ORDER BY rd.vBankAccountHolderName DESC";
}

if($sortby == 4){
  if($order == 0)
  $ord = " ORDER BY rd.vBankName ASC";
  else
  $ord = " ORDER BY rd.vBankName DESC";
}
//End Sorting


// Start Search Parameters

//$ssql='';
$ssql=" AND tr.iActive = 'Finished' ";
$ssql1 = '';

	if($startDate!=''){
		//$ssql.=" AND Date(tr.tEndDate) >='".$startDate."'";
	  $ssql.=" AND Date(tr.tTripRequestDate) >='".$startDate."'";
	}
	if($endDate!=''){
		//$ssql.=" AND Date(tr.tEndDate) <='".$endDate."'";
	  $ssql.=" AND Date(tr.tTripRequestDate) <='".$endDate."'";
	}
	if ($searchCompany != '') {
        $ssql1 .= " AND rd.iCompanyId ='" . $searchCompany . "'";
    }
    if ($searchDriver != '') {
        $ssql .= " AND tr.iDriverId ='" . $searchDriver . "'";
    }

$sql = "select rd.iDriverId,tr.eDriverPaymentStatus,concat(rd.vName,' ',rd.vLastName) as dname,rd.vCountry,rd.vBankAccountHolderName,rd.vAccountNumber,rd.vBankLocation,rd.vBankName,rd.vBIC_SWIFT_Code from register_driver AS rd LEFT JOIN trips AS tr ON tr.iDriverId=rd.iDriverId WHERE tr.eDriverPaymentStatus='Unsettelled' $ssql $ssql1 GROUP BY rd.iDriverId $ord $limit";
$db_payment = $obj->MySQLSelect($sql);


for($i=0;$i<count($db_payment);$i++) {
	$db_payment[$i]['cashPayment'] = $generalobjAdmin->getAllCashCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
	$db_payment[$i]['cardPayment'] = $generalobjAdmin->getAllCardCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
	$db_payment[$i]['walletPayment'] = $generalobjAdmin->getAllWalletCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
	$db_payment[$i]['promocodePayment'] = $generalobjAdmin->getAllPromocodeCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
  $db_payment[$i]['tripoutstandingAmount'] = $generalobjAdmin->getAllOutstandingAmountCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
	if ($ENABLE_TIP_MODULE == "Yes") {
		$db_payment[$i]['tipPayment'] = $generalobjAdmin->getAllTipCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
		$db_payment[$i]['transferAmount'] = $generalobjAdmin->getTransforAmountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
	}else {
		$db_payment[$i]['transferAmount'] = $generalobjAdmin->getTransforAmountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
	}
}

$data["retrunCode"]=1;
$data["data"]=$db_payment;
echo json_encode($data);
?>