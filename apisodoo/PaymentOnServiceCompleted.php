<?php
include_once('../common.php');
include_once("authentication.php");

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
$searchCompany = isset($_REQUEST['searchCompany']) ? $_REQUEST['searchCompany'] : '';
$searchDriver = isset($_REQUEST['searchDriver']) ? $_REQUEST['searchDriver'] : '';
$searchRider = isset($_REQUEST['searchRider']) ? $_REQUEST['searchRider'] : '';
$serachTripNo = isset($_REQUEST['serachTripNo']) ? $_REQUEST['serachTripNo'] : '';
$searchDriverPayment = isset($_REQUEST['searchDriverPayment']) ? $_REQUEST['searchDriverPayment'] : '';
$searchPaymentType = isset($_REQUEST['searchPaymentType']) ? $_REQUEST['searchPaymentType'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
$eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';

    if ($startDate != '') {
        $ssql .= " AND Date(tr.tTripRequestDate) >='" . $startDate . "'";
    }
    if ($endDate != '') {
        $ssql .= " AND Date(tr.tTripRequestDate) <='" . $endDate . "'";
    }
    if ($serachTripNo != '') {
        $ssql .= " AND tr.vRideNo ='" . $serachTripNo . "'";
    }
    if ($searchCompany != '') {
        $ssql .= " AND rd.iCompanyId ='" . $searchCompany . "'";
    }
    if ($searchDriver != '') {
        $ssql .= " AND tr.iDriverId ='" . $searchDriver . "'";
    }
    if ($searchRider != '') {
        $ssql .= " AND tr.iUserId ='" . $searchRider . "'";
    }
    if ($searchDriverPayment != '') {
        $ssql .= " AND tr.eDriverPaymentStatus ='" . $searchDriverPayment . "'";
    }
    if ($searchPaymentType != '') {
        $ssql .= " AND tr.vTripPaymentMode ='" . $searchPaymentType . "'";
    }
    if ($eType != '') {
        if($eType == 'Ride'){
            $ssql.=" AND tr.eType ='".$eType."' AND tr.iRentalPackageId = 0 AND tr.eHailTrip = 'No' ";
        } elseif($eType == 'RentalRide'){
            $ssql.=" AND tr.eType ='Ride' AND tr.iRentalPackageId > 0";
        } elseif($eType == 'HailRide'){
            $ssql.=" AND tr.eType ='Ride' AND tr.eHailTrip = 'Yes'";
        } else {
            $ssql.=" AND tr.eType ='".$eType."' ";
        }
        //$ssql .= " AND tr.eType ='" . $eType . "'";
    }



$sql = "SELECT tr.iTripId,tr.vRideNo,tr.iDriverId,tr.iUserId,tr.tTripRequestDate, tr.eType, tr.eHailTrip,tr.fTripGenerateFare,tr.fCommision, tr.fDiscount, tr.fWalletDebit, tr.fTipPrice,tr.eDriverPaymentStatus,tr.ePaymentCollect,tr.vTripPaymentMode,tr.iActive,tr.fOutStandingAmount, tr.iRentalPackageId,c.vCompany,concat(rd.vName,' ',rd.vLastName) as drivername,concat(ru.vName,' ',ru.vLastName) as riderName,tr.ServiceFeeType,tr.RevenueShare,tr.FixedCommision FROM trips AS tr LEFT JOIN register_driver AS rd ON tr.iDriverId = rd.iDriverId LEFT JOIN register_user AS ru ON tr.iUserId = ru.iUserId LEFT JOIN company as c ON rd.iCompanyId = c.iCompanyId WHERE  if(tr.iActive ='Canceled',if(tr.vTripPaymentMode='Card',1=1,0),1=1) AND tr.iActive ='Finished' $ssql  $limit";
$db_trip = $obj->MySQLSelect($sql);

$data["retrunCode"]=1;
$data["data"]=$db_trip;

echo json_encode($data);
?>