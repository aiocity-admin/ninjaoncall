<?php
include_once('common.php');
include_once('admin/class.general_admin.php');
$generalobjAdmin=new    General_admin;

$section = isset($_REQUEST['section']) ? $_REQUEST['section'] : '';
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
$iCompanyId = isset($_REQUEST['searchCompany']) ? $_REQUEST['searchCompany'] : '';
$iDriverId = isset($_REQUEST['searchDriver']) ? $_REQUEST['searchDriver'] : '';
$iUserId = isset($_REQUEST['searchRider']) ? $_REQUEST['searchRider'] : '';
$serachTripNo = isset($_REQUEST['serachTripNo']) ? $_REQUEST['serachTripNo'] : '';
$vTripPaymentMode = isset($_REQUEST['searchPaymentType']) ? $_REQUEST['searchPaymentType'] : '';
$eDriverPaymentStatus = isset($_REQUEST['searchDriverPayment']) ? $_REQUEST['searchDriverPayment'] : '';
$ssql = "";

function converToTz($time, $toTz, $fromTz,$dateFormat="Y-m-d H:i:s") {

    $date = new DateTime($time, new DateTimeZone($fromTz));

    $date->setTimezone(new DateTimeZone($toTz));

    $time = $date->format($dateFormat);

    return $time;

}

function mediaTimeDeFormater($seconds) {
    $ret = "";
   
    $hours = (string )floor($seconds / 3600);
    $secs = (string )$seconds % 60;
    $mins = (string )floor(($seconds - ($hours * 3600)) / 60);

    if (strlen($hours) == 1)
        $hours = "0" . $hours;
    if (strlen($secs) == 1)
        $secs = "0" . $secs;
    if (strlen($mins) == 1)
        $mins = "0" . $mins;

    if ($hours == 0){
        $mint="";
        $secondss="";
        if($mins > 01){
            $mint = "$mins mins";
        }else{
            $mint = "$mins min";
        }
        if($secs > 01){
            $secondss = "$secs seconds";
        }else{
            $secondss = "$secs second";
        }
         $ret = "$mint $secondss";
    } else {
        $mint="";
        $secondss="";
        if($mins > 01){
            $mint = "$mins mins";
        }else{
            $mint = "$mins min";
        }
        if($secs > 01){
            $secondss = "$secs seconds";
        }else{
            $secondss = "$secs second";
        }
        if($hours > 01){
          $ret = "$hours hrs $mint $secondss";
        }else{
          $ret = "$hours hr $mint $secondss";
        }
    }
    return  $ret;
}
function cleanData(&$str) {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"'))
        $str = '"' . str_replace('"', '""', $str) . '"';
}
if ($section == 'cancelled_trip') {

    $dlp_ssql = "";
    if (SITE_TYPE == 'Demo') {
        $dlp_ssql = " And dl.dLoginDateTime > '" . WEEK_DATE . "'";
    }

	//Start Sorting
	$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
	$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
	$ord = ' ORDER BY t.iTripId DESC';
	if($sortby == 1){
	  if($order == 0)
	  $ord = " ORDER BY t.tStartDate ASC";
	  else
	  $ord = " ORDER BY t.tStartDate DESC";
	}

	if($sortby == 2){
	  if($order == 0)
	  $ord = " ORDER BY t.eCancelled ASC";
	  else
	  $ord = " ORDER BY t.eCancelled DESC";
	}

	if($sortby == 4){
	  if($order == 0)
	  $ord = " ORDER BY d.vName ASC";
	  else
	  $ord = " ORDER BY d.vName DESC";
	}

    if($sortby == 5){
      if($order == 0)
      $ord = " ORDER BY t.eType ASC";
      else
      $ord = " ORDER BY t.eType DESC";
    }
	//End Sorting
	
	
		
// Start Search Parameters
$ssql='';
$action = isset($_REQUEST['action']) ? $_REQUEST['action']: '';
$iDriverId = isset($_REQUEST['iDriverId']) ? $_REQUEST['iDriverId'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$serachTripNo = isset($_REQUEST['serachTripNo']) ? $_REQUEST['serachTripNo'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
$vStatus = isset($_REQUEST['vStatus']) ? $_REQUEST['vStatus'] : '';
$eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';
$iCompanyId= isset($_REQUEST['iCompanyId']) ? $_REQUEST['iCompanyId'] : '';
if($iCompanyId!="")
{
$sub_qry="and d.iCompanyId='$iCompanyId'";
}

if($action == 'search')
{
	if($startDate!=''){
		$ssql.=" AND Date(t.tTripRequestDate) >='".$startDate."'";
	}
	if($endDate!=''){
		$ssql.=" AND Date(t.tTripRequestDate) <='".$endDate."'";
	}
	if($iDriverId!=''){
		$ssql.=" AND t.iDriverId ='".$iDriverId."'";
	}
	if($serachTripNo!=''){
		$ssql.=" AND t.vRideNo ='".$serachTripNo."'";
	}
    if($eType!=''){
        //$ssql.=" AND t.eType ='".$eType."'";
                if(trim($eType)=='Ride'||trim($eType)=='Deliver')
{
$ssql.=" and vt.eType='".$eType."'";

}else {

$ssql.=" and vcp.iVehicleCategoryId='".$eType."'";


}
    }
}

				
/*$sql_admin = "SELECT t.tTripRequestDate,t.tStartDate ,t.tEndDate,t.eHailTrip,t.eCancelled,t.vCancelReason,t.vCancelComment,d.iDriverId, t.tSaddress,t.vRideNo,t.eType, t.tDaddress, t.fWalletDebit,t.eCarType,t.iTripId,t.iActive ,CONCAT(d.vName,' ',d.vLastName) AS dName FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId
WHERE 1=1 And t.iActive='Canceled' $ssql $trp_ssql $ord ";*/

 $sql_admin = "SELECT t.tTripRequestDate ,t.tEndDate,t.eCancelled,t.vCancelReason,t.vCancelComment,t.eHailTrip,d.iDriverId, t.tSaddress,t.vRideNo,t.eCancelledBy,t.tDaddress, t.fWalletDebit,t.eCarType,t.iTripId,t.iActive, t.eType ,CONCAT(d.vName,' ',d.vLastName) AS dName,case when vcp.vCategory_EN is NULL or vcp.vCategory_EN='' then t.eType else  vcp.vCategory_EN end as vCategory_EN ,vt.vVehicleType  FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId LEFT JOIN company as c ON d.iCompanyId = c.iCompanyId WHERE 1=1 $sub_qry AND (t.iActive='Canceled' OR t.eCancelled='yes') $ssql $trp_ssql $ord";
/*echo  $sql_admin;
exit;*/

$db_dlip = $obj->MySQLSelect($sql_admin);
// echo "<pre>";print_r($db_dlip); exit;
if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){
    $header .= "Service Category". "\t";
}
$header .= $langage_lbl_admin['LBL_TRIP_TXT_ADMIN']." Date" . "\t";
$header .= "Cancel By". "\t";
$header .= "Cancel Reason" . "\t";
$header .= $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']." Name" . "\t";
$header .= $langage_lbl_admin['LBL_TRIP_TXT_ADMIN']." No". "\t";
$header .= "Address". "\t";
			   
						
for ($j = 0; $j < count($db_dlip); $j++) {

		/*$eType = $db_dlip[$j]['eType'];
        if($eType == 'Ride'){
            $trip_type = 'Ride';
        } else if($eType == 'UberX') {
            $trip_type = 'Other Services';
        }  else if($eType == 'Deliver') {
            $trip_type = 'Delivery';
        }

		$vCancelReason = $db_dlip[$j]['vCancelReason'];
		$trip_cancel = ($vCancelReason != '')? $vCancelReason: '--';
		$eCancelled = $db_dlip[$j]['eCancelled'];
		$CanceledBy = ($eCancelled == 'Yes' && $vCancelReason != '' )? 'Driver': 'Passenger';
					
		if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){
           if($db_dlip[$j]['eHailTrip'] != "Yes"){
                $data .= $trip_type . "\t";
            }else{
               $data .= $trip_type ." ( Hail )". "\t";
            } 
        }*/	

                       $data .= $db_dlip[$j]['vCategory_EN']. "\t";
		
		$data .= $generalobj->DateTime($db_dlip[$j]['tTripRequestDate'],'no'). "\t";
		$data .= $CanceledBy. "\t";
		$data .= $trip_cancel . "\t";
		$data .= $generalobj->clearName($db_dlip[$j]['dName']) ."\t";
		$data .= $db_dlip[$j]['vRideNo'] ."\t";
		$str = "";
		if($db_dlip[$j]['tDaddress'] != ""){
			$str = ' -> '.$db_dlip[$j]['tDaddress'];
		}
		// $data .= $db_dlip[$j]['tSaddress'].$str;
		$string = $db_dlip[$j]['tSaddress'].$str;
		$data .= str_replace(array("\n", "\r", "\r\n", "\n\r"),' ',$string);
		$data .= "\n";
	}
	// echo "<pre>";print_r($data);exit;
	ob_clean();
	header("Content-type: application/octet-sdleam; charset=utf-8");
	header("Content-Disposition: attachment; filename=cancelled_trip.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";
	exit;
}

if ($section == 'driver_payment') {
    $eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';
    $trp_ssql = "";
    if (SITE_TYPE == 'Demo') {
        $trp_ssql = " And tr.tTripRequestDate > '" . WEEK_DATE . "'";
    }

    $ord = ' ORDER BY tr.iTripId DESC';

    if ($sortby == 1) {
        if ($order == 0)
            $ord = " ORDER BY rd.vName ASC";
        else
            $ord = " ORDER BY rd.vName DESC";
    }

    if ($sortby == 2) {
        if ($order == 0)
            $ord = " ORDER BY ru.vName ASC";
        else
            $ord = " ORDER BY ru.vName DESC";
    }

    if ($sortby == 3) {
        if ($order == 0)
            $ord = " ORDER BY tr.tTripRequestDate ASC";
        else
            $ord = " ORDER BY tr.tTripRequestDate DESC";
    }

    if ($sortby == 4) {
        if ($order == 0)
            $ord = " ORDER BY d.vName ASC";
        else
            $ord = " ORDER BY d.vName DESC";
    }

    if ($sortby == 5) {
        if ($order == 0)
            $ord = " ORDER BY u.vName ASC";
        else
            $ord = " ORDER BY u.vName DESC";
    }

    if ($sortby == 6) {
        if ($order == 0)
            $ord = " ORDER BY tr.eType ASC";
        else
            $ord = " ORDER BY tr.eType DESC";
    }

    $ssql = "";
    if ($startDate != '') {
        $ssql .= " AND Date(tTripRequestDate) >='" . $startDate . "'";
    }
    if ($endDate != '') {
        $ssql .= " AND Date(tTripRequestDate) <='" . $endDate . "'";
    }
    if ($iCompanyId != '') {
        $ssql .= " AND rd.iCompanyId = '" . $iCompanyId . "'";
    }
    if ($iDriverId != '') {
        $ssql .= " AND tr.iDriverId = '" . $iDriverId . "'";
    }

    if ($iUserId != '') {
        $ssql .= " AND tr.iUserId = '" . $iUserId . "'";
    }
	if ($serachTripNo != '') {
        $ssql .= " AND tr.vRideNo ='" . $serachTripNo . "'";
    }

    if ($vTripPaymentMode != '') {
        $ssql .= " AND tr.vTripPaymentMode = '" . $vTripPaymentMode . "'";
    }
    if ($eDriverPaymentStatus != '') {
        $ssql .= " AND tr.eDriverPaymentStatus = '" . $eDriverPaymentStatus . "'";
    }

    if ($eType != '') {

        if(trim($eType)=='Ride'||trim($eType)=='Deliver')
{
$ssql.=" and vt.eType='".$eType."'";

}else {

$ssql.=" and vcp.iVehicleCategoryId='".$eType."'";


}
  /*      if($eType == 'Ride'){
            $ssql.=" AND tr.eType ='".$eType."' AND tr.iRentalPackageId = 0 AND tr.eHailTrip = 'No' ";
        } elseif($eType == 'RentalRide'){
            $ssql.=" AND tr.eType ='Ride' AND tr.iRentalPackageId > 0";
        } elseif($eType == 'HailRide'){
            $ssql.=" AND tr.eType ='Ride' AND tr.eHailTrip = 'Yes'";
        } else {
            $ssql.=" AND tr.eType ='".$eType."' ";
        }*/
    }

    //$sql_admin = "SELECT * from trips WHERE 1=1 ".$ssql." ORDER BY iTripId DESC";
 /*   $sql_admin = "SELECT tr.iTripId,tr.vRideNo,tr.iDriverId,tr.iUserId,tr.tTripRequestDate,tr.fTripGenerateFare,tr.fCommision, tr.fDiscount, tr.fWalletDebit, tr.fTipPrice,tr.eDriverPaymentStatus,tr.ePaymentCollect,tr.vTripPaymentMode,tr.iActive,tr.eType, tr.iRentalPackageId, tr.eHailTrip,c.vCompany,concat(rd.vName,' ',rd.vLastName) as drivername,concat(ru.vName,' ',ru.vLastName) as riderName FROM trips AS tr LEFT JOIN register_driver AS rd ON tr.iDriverId = rd.iDriverId LEFT JOIN register_user AS ru ON tr.iUserId = ru.iUserId LEFT JOIN company as c ON rd.iCompanyId = c.iCompanyId 
		WHERE if(tr.iActive ='Canceled',if(tr.vTripPaymentMode='Card',1=1,0),1=1) AND tr.iActive ='Finished'  $ssql $trp_ssql $ord";*/


$sql_admin = "SELECT tr.iTripId,tr.vRideNo,tr.iDriverId,tr.iUserId,tr.tTripRequestDate, tr.eType, tr.eHailTrip,tr.fTripGenerateFare,tr.fCommision, tr.fDiscount, tr.fWalletDebit, tr.fTipPrice,tr.eDriverPaymentStatus,tr.ePaymentCollect,tr.vTripPaymentMode,tr.iActive,tr.fOutStandingAmount, tr.iRentalPackageId,c.vCompany,concat(rd.vName,' ',rd.vLastName) as drivername,concat(ru.vName,' ',ru.vLastName) as riderName,tr.ServiceFeeType,tr.RevenueShare,tr.FixedCommision,case when vcp.vCategory_EN is NULL or vcp.vCategory_EN='' then tr.eType else  vcp.vCategory_EN end as vCategory_EN,vt.vVehicleType FROM trips AS tr LEFT JOIN register_driver AS rd ON tr.iDriverId = rd.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = tr.iVehicleTypeId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  LEFT JOIN register_user AS ru ON tr.iUserId = ru.iUserId LEFT JOIN company as c ON rd.iCompanyId = c.iCompanyId WHERE  if(tr.iActive ='Canceled',if(tr.vTripPaymentMode='Card',1=1,0),1=1) AND tr.iActive ='Finished' $ssql $trp_ssql $ord ";

    $db_trip = $obj->MySQLSelect($sql_admin);
    //    echo "<pre>";print_r($db_trip); exit;

    if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){
        $header .= "Service Category". "\t";
    }
    $header .= $langage_lbl_admin['LBL_TRIP_TXT_ADMIN']." No." . "\t";
    $header .= $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']. " Name" . "\t";
    $header .= $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN']. " Name" . "\t";
    $header .= $langage_lbl_admin['LBL_TRIP_TXT_ADMIN']." Date" . "\t";
    $header .= "A=Total Fare" . "\t";
    $header .= "B=Platform Fees" . "\t";
    $header .= "C= Promo Code Discount" . "\t";
    $header .= "D = Wallet Debit" . "\t";
    if ($ENABLE_TIP_MODULE == "Yes") {
        $header .= "E = Tip" . "\t";
    }
    $header .= $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']. " pay Amount" . "\t";
    $header .= $langage_lbl_admin['LBL_TRIP_TXT_ADMIN']." Status" . "\t";
    $header .= "Payment method" . "\t";
    $header .= $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']. " Payment Status";

    $driver_payment = 0.00;
    $total_tip = 0.00;
    $tot_fare = 0.00;
    $tot_site_commission = 0.00;
    $tot_promo_discount = 0.00;
    $tot_driver_refund = 0.00;
    $tot_wallentPayment = 0.00;
    
    for ($j = 0; $j < count($db_trip); $j++) {

        $totalfare = $db_trip[$j]['fTripGenerateFare'];
        $site_commission = $db_trip[$j]['fCommision'];
        $promocodediscount = $db_trip[$j]['fDiscount'];
        $wallentPayment = $db_trip[$j]['fWalletDebit'];
        $fTipPrice = $db_trip[$j]['fTipPrice'];
        
        //$driver_payment = $totalfare - $site_commission;
        if($db_trip[$j]['vTripPaymentMode'] == "Cash"){
          $driver_payment =  $promocodediscount + $wallentPayment - $site_commission;
        }else{
          $driver_payment = $totalfare - $site_commission + $fTipPrice;
        }
        
        $tot_fare = $tot_fare + $totalfare;
        $tot_site_commission = $tot_site_commission + $site_commission;
        $tot_promo_discount = $tot_promo_discount + $promocodediscount;
        $tot_wallentPayment = $tot_wallentPayment + $wallentPayment;
        $total_tip = $total_tip + $fTipPrice;
        $tot_driver_refund = $tot_driver_refund + $driver_payment;
        
        if ($db_trip[$j]['eMBirr'] == "Yes") {
            $paymentmode = "M-birr";
        } else {
            $paymentmode = $db_trip[$j]['vTripPaymentMode'];
        }

      /*  $eType = $db_trip[$j]['eType'];
        if($eType == 'Ride'){
            $trip_type = 'Ride';
        } else if($eType == 'UberX') {
            $trip_type = 'Other Services';
        }  else if($eType == 'Deliver') {
            $trip_type = 'Delivery';
        }

        if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){
           if($db_trip[$j]['eHailTrip'] == "Yes" && $db_trip[$j]['iRentalPackageId'] > 0){
               $data .=  "Rental " . $trip_type." ( Hail )". "\t";
            } else if($db_trip[$j]['iRentalPackageId'] > 0){
                $data .=  "Rental " . $trip_type. "\t";
            } else if($db_trip[$j]['eHailTrip'] == "Yes"){
                $data .= "Hail ".$trip_type . "\t";
            } else {
               $data .= $trip_type . "\t";
            } 
        }*/

        $data .= $db_trip[$j]['vCategory_EN'] . "\t";



        $data .= $db_trip[$j]['vRideNo'] . "\t";
        $data .= $generalobj->clearName($db_trip[$j]['drivername']) . "\t";
        $data .= $generalobj->clearName($db_trip[$j]['riderName']) . "\t";
        $data .= date('d-m-Y', strtotime($db_trip[$j]['tTripRequestDate'])) . "\t";
        $data .= ($db_trip[$j]['fTripGenerateFare'] != "" && $db_trip[$j]['fTripGenerateFare'] != 0) ? $db_trip[$j]['fTripGenerateFare'] . "\t" : "- \t";
        $data .= ($db_trip[$j]['fCommision'] != "" && $db_trip[$j]['fCommision'] != 0) ? $db_trip[$j]['fCommision'] . "\t" : "- \t";
        $data .= ($db_trip[$j]['fDiscount'] != "" && $db_trip[$j]['fDiscount'] != 0) ? $db_trip[$j]['fDiscount'] . "\t" : "- \t";
        $data .= ($db_trip[$j]['fWalletDebit'] != "" && $db_trip[$j]['fWalletDebit'] != 0) ? $db_trip[$j]['fWalletDebit'] . "\t" : "- \t";
        if ($ENABLE_TIP_MODULE == "Yes") {
            $data .= ($db_trip[$j]['fTipPrice'] != "" && $db_trip[$j]['fTipPrice'] != 0) ? $db_trip[$j]['fTipPrice'] . "\t" : "- \t";
        }
        $data .= ($driver_payment != "" && $driver_payment != 0) ? $driver_payment . "\t" : "- \t";
        $data .= $db_trip[$j]['iActive'] . "\t";
        $data .= $paymentmode . "\t";
        $data .= $db_trip[$j]['eDriverPaymentStatus'] . "\n";
    }
    $data .= "\n\t\t\t\t\t\t\t\t\tTotal Fare\t" . $tot_fare. "\n";
    $data .= "\t\t\t\t\t\t\t\t\tTotal Platform Fees\t" . $tot_site_commission . "\n";
    $data .= "\t\t\t\t\t\t\t\t\tTotal Promo Discount\t" .$tot_promo_discount . "\n";
    $data .= "\t\t\t\t\t\t\t\t\tTotal Wallet Debit\t" .$tot_wallentPayment . "\n";
    if ($ENABLE_TIP_MODULE == "Yes") {
        $data .= "\t\t\t\t\t\t\t\t\tTotal Tip Amount\t" .$total_tip . "\n";
        //$data .= "\t\t\t\t\t\t\t\t\tTotal Driver Payment\t" . $generalobj->trip_currency($tot_driver_refund+$total_tip) . "\n";
        $data .= "\t\t\t\t\t\t\t\t\tTotal ".$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']." Payment\t" . $tot_driver_refund . "\n";
    }else {
        $data .= "\t\t\t\t\t\t\t\t\tTotal ".$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']." Payment\t" . $tot_driver_refund . "\n";
    }
    $data = str_replace("\r", "", $data);
    #echo "<br>".$data; exit;
    ob_clean();
    header("Content-type: application/octet-stream; charset=utf-8");
    header("Content-Disposition: attachment; filename=payment_reports.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    print "$header\n$data";
    exit;
}

if ($section == 'driver_log_report') {
    $searchCompany = $_SESSION['sess_iUserId'];


    $dlp_ssql = "";

    $ord = ' ORDER BY dlr.iDriverLogId DESC';

    //Start Sorting
    $sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
    $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
    $ord = ' ORDER BY dlr.iDriverLogId DESC';

    if ($sortby == 1) {
        if ($order == 0)
            $ord = " ORDER BY rd.vName ASC";
        else
            $ord = " ORDER BY rd.vName DESC";
    }

    if ($sortby == 2) {
        if ($order == 0)
            $ord = " ORDER BY rd.vEmail ASC";
        else
            $ord = " ORDER BY rd.vEmail DESC";
    }

    if ($sortby == 3) {
        if ($order == 0)
            $ord = " ORDER BY dlr.dLoginDateTime ASC";
        else
            $ord = " ORDER BY dlr.dLoginDateTime DESC";
    }

    if ($sortby == 4) {
        if ($order == 0)
            $ord = " ORDER BY dlr.dLogoutDateTime ASC";
        else
            $ord = " ORDER BY dlr.dLogoutDateTime DESC";
    }
        // Start Search Parameters
        $ssql = '';
        $iDriverId = isset($_REQUEST['iDriverId']) ? $_REQUEST['iDriverId'] : '';
        $startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
        $endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
        $vEmail = isset($_REQUEST['vEmail']) ? $_REQUEST['vEmail'] : '';

        if ($startDate != '' && $endDate != '') {
            $ssql .= " AND dlr.dLoginDateTime BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
        }
        if ($iDriverId != '') {
            $ssql .= " AND rd.iDriverId = '" . $iDriverId . "'";
        }
        if ($vEmail != '') {
            $ssql .= " AND rd.vEmail = '" . $vEmail . "'";
        }

                //$sql_admin = "SELECT * from dlips WHERE 1=1 ".$ssql." ORDER BY iDriverLogId DESC";
                $sql = "SELECT rd.vName, rd.vLastName, rd.vEmail, dlr.dLoginDateTime, dlr.dLogoutDateTime
                        FROM driver_log_report AS dlr
                        LEFT JOIN register_driver AS rd ON rd.iDriverId = dlr.iDriverId where 1=1 AND rd.eStatus != 'Deleted' AND rd.iCompanyId='$searchCompany' $ssql $ord";
                $db_dlip = $obj->MySQLSelect($sql);
                #echo "<pre>";print_r($db_dlip); exit;

                $header .= $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']." Name" . "\t";
                $header .= "Email". "\t";
                $header .= "Log DateTime" . "\t";
                $header .= "Logout TimeDate" . "\t";
                $header .= "Total Hours Login" . "\t";
               
                for ($j = 0; $j < count($db_dlip); $j++) {
                    
                    
                  $dstart = $db_dlip[$j]['dLoginDateTime'];
                  if( $db_dlip[$j]['dLogoutDateTime'] == '0000-00-00 00:00:00' || $db_dlip[$j]['dLogoutDateTime'] == '' ){
                        $dLogoutDateTime = '--';
                        $totalTimecount = '--';
                 
                 }else{
                      
                        $dLogoutDateTime = $db_dlip[$j]['dLogoutDateTime'];
                        $totalhours = $generalobjAdmin->get_left_days_jobsave($dLogoutDateTime,$dstart);
                        $totalTimecount = mediaTimeDeFormater ($totalhours);
                   }
                           
                    $data .= $generalobjAdmin->clearName($db_dlip[$j]['vName'].'  '.$db_dlip[$j]['vLastName']) . "\t";
                    $data .= $generalobjAdmin->clearEmail($db_dlip[$j]['vEmail']). "\t";
                    $data .= $generalobjAdmin->DateTime($db_dlip[$j]['dLoginDateTime']) . "\t";
                    $data .= $generalobjAdmin->DateTime($db_dlip[$j]['dLogoutDateTime']) ."\t";
                    $data .= $totalTimecount ."\n";
                    
                     }
                
                ob_clean();
                header("Content-type: application/octet-sdleam; charset=utf-8");
                header("Content-Disposition: attachment; filename= driver_log_report.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                print "$header\n$data";
                exit;
}


if ($section == 'cancellation_driver_payment') {
    $eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';
    $searchPaymentByUser = isset($_REQUEST['searchPaymentByUser']) ? $_REQUEST['searchPaymentByUser'] : '';
/*    if($searchPaymentByUser == ''){
        $searchPaymentByUser = 'Yes';
    }*/

    $trp_ssql = "";
    if (SITE_TYPE == 'Demo') {
        $trp_ssql = " And tr.tTripRequestDate > '" . WEEK_DATE . "'";
    }

    $ord = ' ORDER BY tr.iTripId DESC';

    if ($sortby == 1) {
        if ($order == 0)
            $ord = " ORDER BY rd.vName ASC";
        else
            $ord = " ORDER BY rd.vName DESC";
    }

    if ($sortby == 2) {
        if ($order == 0)
            $ord = " ORDER BY ru.vName ASC";
        else
            $ord = " ORDER BY ru.vName DESC";
    }

    if ($sortby == 3) {
        if ($order == 0)
            $ord = " ORDER BY trp.tTripRequestDate ASC";
        else
            $ord = " ORDER BY trp.tTripRequestDate DESC";
    }

    if ($sortby == 4) {
        if ($order == 0)
            $ord = " ORDER BY d.vName ASC";
        else
            $ord = " ORDER BY d.vName DESC";
    }

    if ($sortby == 5) {
        if ($order == 0)
            $ord = " ORDER BY u.vName ASC";
        else
            $ord = " ORDER BY u.vName DESC";
    }

    if ($sortby == 6) {
        if ($order == 0)
            $ord = " ORDER BY trp.eType ASC";
        else
            $ord = " ORDER BY trp.eType DESC";
    }

    $ssql = "";
    if ($startDate != '') {
        $ssql .= " AND Date(trp.tTripRequestDate) >='" . $startDate . "'";
    }
    if ($endDate != '') {
        $ssql .= " AND Date(trp.tTripRequestDate) <='" . $endDate . "'";
    }
    if ($serachTripNo != '') {
        $ssql .= " AND trp.vRideNo ='" . $serachTripNo . "'";
    }
    if ($iCompanyId != '') {
        $ssql .= " AND rd.iCompanyId ='" . $iCompanyId . "'";
    }
    if ($iDriverId != '') {
        $ssql .= " AND tr.iDriverId ='" . $iDriverId . "'";
    }
    if ($iUserId != '') {
        $ssql .= " AND tr.iUserId ='" . $iUserId . "'";
    }
    if ($eDriverPaymentStatus != '') {
        $ssql .= " AND tr.ePaidToDriver ='" . $eDriverPaymentStatus . "'";
    }
    if ($vTripPaymentMode != '') {
        $ssql .= " AND tr.vTripPaymentMode ='" . $vTripPaymentMode . "'";
    }
    if ($eType != '') {
        $ssql .= " AND trp.eType ='" . $eType . "'";
    }

    if($searchPaymentByUser != ''){
         $ssql .= " AND tr.ePaidByPassenger ='" . $searchPaymentByUser . "'";
    }
   // $sql_admin = "SELECT tr.iTripId,tr.iTripOutstandId,tr.fPendingAmount,tr.iDriverId,tr.iUserId, tr.fCommision, tr.fDriverPendingAmount, tr.fWalletDebit,tr.ePaidByPassenger,tr.ePaidToDriver,tr.vTripPaymentMode,trp.eType,trp.vRideNo,trp.tTripRequestDate, tr.vTripAdjusmentId,c.vCompany,concat(rd.vName,' ',rd.vLastName) as drivername,concat(ru.vName,' ',ru.vLastName) as riderName FROM trip_outstanding_amount AS tr LEFT JOIN register_driver AS rd ON tr.iDriverId = rd.iDriverId LEFT JOIN register_user AS ru ON tr.iUserId = ru.iUserId LEFT JOIN trips AS trp ON trp.iTripId = tr.iTripId  LEFT JOIN company as c ON rd.iCompanyId = c.iCompanyId WHERE 1 = 1 $ssql $trp_ssql $ord";

    $sql_admin = "SELECT tr.iTripId,tr.iTripOutstandId,tr.iDriverId,tr.iUserId, tr.fCommision, tr.fPendingAmount, tr.fDriverPendingAmount, tr.fWalletDebit,tr.ePaidByPassenger,tr.ePaidToDriver,tr.vTripPaymentMode,trp.eType,trp.vRideNo,trp.tTripRequestDate,tr.vTripAdjusmentId,c.vCompany,concat(rd.vName,' ',rd.vLastName) as drivername,concat(ru.vName,' ',ru.vLastName) as riderName ,case when vcp.vCategory_EN is NULL or vcp.vCategory_EN='' then t.eType else  vcp.vCategory_EN end as vCategory_EN ,vt.vVehicleType FROM trip_outstanding_amount AS tr LEFT JOIN register_driver AS rd ON tr.iDriverId = rd.iDriverId left join trips t on tr.iTripId=t.iTripId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId LEFT JOIN register_user AS ru ON tr.iUserId = ru.iUserId LEFT JOIN trips AS trp ON trp.iTripId = tr.iTripId  LEFT JOIN company as c ON rd.iCompanyId = c.iCompanyId WHERE  1 = 1 $ssql $trp_ssql $ord";

    $db_trip = $obj->MySQLSelect($sql_admin);

    if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){
        $header .= "Service Category \t";
    }
    $header .= $langage_lbl_admin['LBL_TRIP_TXT_ADMIN']." No." . "\t";
    $header .= $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']. " Name" . "\t";
    $header .= $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN']. " Name" . "\t";
    $header .= $langage_lbl_admin['LBL_TRIP_TXT_ADMIN']." Date" . "\t";
    $header .= "Total Cancellation Fees" . "\t";
    $header .= "Platform Fees" . "\t";
    $header .= $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']. " Pay Amount" . "\t";
    $header .= "Adjustment Booking No" . "\t";
   

    $driver_payment = 0.00;
    $tot_site_commission = 0.00;

    
    for ($j = 0; $j < count($db_trip); $j++) {
       
        $site_commission = $db_trip[$j]['fCommision'];
        $driver_payment = $db_trip[$j]['fDriverPendingAmount'];

        $tot_site_commission = $tot_site_commission + $site_commission;
        $tot_driver_refund = $tot_driver_refund + $driver_payment;
        
        $paymentmode = $db_trip[$j]['vTripPaymentMode'];

        $eType = $db_trip[$j]['eType'];
    /*    if($eType == 'Ride'){
            $trip_type = 'Ride';
        } else if($eType == 'UberX') {
            $trip_type = 'Other Services';
        }  else if($eType == 'Deliver') {
            $trip_type = 'Delivery';
        }*/

        $q = "SELECT vRideNo FROM trips WHERE iTripId = '".$db_trip[$j]['vTripAdjusmentId']."'";
        $db_bookingno = $obj->MySQLSelect($q);

      /*  if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){
           if($db_trip[$j]['eHailTrip'] != "Yes"){
                $data .= $trip_type . "\t";
            }else{
               $data .= $trip_type ." ( Hail )". "\t";
            } 
        }*/

         $data .= $db_trip[$j]['vCategory_EN']."\t";

        if($db_bookingno[0]['vRideNo'] != "" && $db_bookingno[0]['vRideNo'] != 0) { 
            $paymentstatus = "Paid in Trip# " .$db_bookingno[0]['vRideNo'];
        } else if($db_trip[$j]['ePaidByPassenger'] == 'No'){
            $paymentstatus = "Not Paid";
        } else{
            $paymentstatus = "Paid By Card";
        }

        $TotalCancelledprice = $db_trip[$j]['fPendingAmount'] > $db_trip[$j]['fWalletDebit']? $db_trip[$j]['fPendingAmount']:$db_trip[$j]['fWalletDebit'];

        $data .= $db_trip[$j]['vRideNo'] . "\t";
        $data .= $generalobjAdmin->clearName($db_trip[$j]['drivername']) . "\t";
        $data .= $generalobjAdmin->clearName($db_trip[$j]['riderName']) . "\t";
        $data .= date('d-m-Y', strtotime($db_trip[$j]['tTripRequestDate'])) . "\t";
        $data .= ($TotalCancelledprice != "" && $TotalCancelledprice != 0) ? $TotalCancelledprice . "\t" : "- \t";
        $data .= ($db_trip[$j]['fCommision'] != "" && $db_trip[$j]['fCommision'] != 0) ? $db_trip[$j]['fCommision'] . "\t" : "- \t";
        $data .= ($driver_payment != "" && $driver_payment != 0) ? $driver_payment . "\t" : "- \t";
        //$data .= ($db_bookingno[0]['vRideNo'] != "" && $db_bookingno[0]['vRideNo'] != 0) ? $db_bookingno[0]['vRideNo'] . "\n" : "- \n";
        $data .=  $paymentstatus."\n";
    }

    $data .= "\t\t\t\t\t\t\t\t\tTotal Platform Fees\t" .$tot_site_commission . "\n";
    $data .= "\t\t\t\t\t\t\t\t\tTotal ".$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']." Payment\t" .$tot_driver_refund. "\n";

    $data = str_replace("\r", "", $data);

    ob_clean();
    header("Content-type: application/octet-stream; charset=utf-8");
    header("Content-Disposition: attachment; filename=payment_reports.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    print "$header\n$data";
    exit;
}


if ($section == 'driver_trip_detail') {
        $searchCompany = $_SESSION['sess_iUserId'];

        //Start Sorting
        $sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
        $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
        $ord = ' ORDER BY t.tStartdate DESC';

        if ($sortby == 1) {
            if ($order == 0)
                $ord = " ORDER BY t.tStartDate ASC";
            else
                $ord = " ORDER BY t.tStartDate DESC";
        }

        if ($sortby == 2) {
            if ($order == 0)
                $ord = " ORDER BY d.vName ASC";
            else                    
                $ord = " ORDER BY d.vName DESC";
        }
        //End Sorting

        $cmp_ssql = "";
        if(SITE_TYPE =='Demo'){
            $cmp_ssql = " And t.tStartDate > '".WEEK_DATE."'";
        }

        // Start Search Parameters
        $ssql = '';
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        $iDriverId = isset($_REQUEST['iDriverId']) ? $_REQUEST['iDriverId'] : '';
        $startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
        $serachTripNo = isset($_REQUEST['serachTripNo']) ? $_REQUEST['serachTripNo'] : '';
        $endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
        $date1=$startDate.' '."00:00:00";
        $date2=$endDate.' '."23:59:59";

        if($startDate!=''){
            $ssql.=" AND Date(t.tStartDate) >='".$startDate."'";
        }
        if($endDate!=''){
            $ssql.=" AND Date(t.tStartDate) <='".$endDate."'";
        }
        if ($iDriverId != '') {
            $ssql .= " AND d.iDriverId = '".$iDriverId."'";
        }
        if($serachTripNo!=''){
            $ssql.=" AND t.vRideNo ='".$serachTripNo."'";
        }

                
            /*     $sql_admin = "SELECT u.vName, u.vLastName, d.vAvgRating,t.fGDtime,t.tStartdate,t.tEndDate, t.tTripRequestDate, t.iFare, d.iDriverId, t.tSaddress,t.vRideNo, t.tDaddress, d.vName AS name,c.vName AS comp,c.vCompany, d.vLastName AS lname,t.eCarType,t.iTripId,vt.vVehicleType,t.iActive FROM register_driver d RIGHT JOIN trips t ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId JOIN company c ON c.iCompanyId=d.iCompanyId
                 WHERE 1=1 AND t.iActive = 'Finished' AND t.eCancelled='No' $ssql $cmp_ssql $ord ";*/


$sql_admin = "SELECT u.vName, u.vLastName, d.vAvgRating,t.fGDtime,t.tStartdate,t.tEndDate, t.tTripRequestDate, t.iFare, d.iDriverId, t.tSaddress,t.vRideNo, t.tDaddress, d.vName AS name,c.vName AS comp,c.vCompany, d.vLastName AS lname,t.eCarType,t.iTripId,vt.vVehicleType,t.iActive FROM register_driver d
RIGHT JOIN trips t ON d.iDriverId = t.iDriverId
LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId
LEFT JOIN  register_user u ON t.iUserId = u.iUserId JOIN company c ON c.iCompanyId=d.iCompanyId
WHERE 1=1 AND t.iActive = 'Finished' AND t.eCancelled='No' and d.iCompanyId='$searchCompany' $ssql $cmp_ssql $ord";
                
                $db_dlip = $obj->MySQLSelect($sql_admin);
                #echo "<pre>";print_r($db_dlip); exit;

                $header .= $langage_lbl_admin['LBL_TRIP_TXT_ADMIN']."  No" . "\t";
                $header .= "Address". "\t";
                $header .= $langage_lbl_admin['LBL_TRIP_TXT_ADMIN']."  Date" . "\t";
                $header .= $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']. "\t";
                $header .= "Estimated Time" . "\t";
                $header .= "Actual Time" . "\t";
                $header .= "Variance" . "\t";
        
                        
             for ($j = 0; $j < count($db_dlip); $j++) {
                    
                                
                     $data .= $db_dlip[$j]['vRideNo']. "\t";
                     $data .=  $db_dlip[$j]['tSaddress'].' -> '.$db_dlip[$j]['tDaddress'] . "\t";
                     $data .= $generalobjAdmin->DateTime($db_dlip[$j]['tStartdate']). "\t";
                     $data .= $generalobjAdmin->clearName($db_dlip[$j]['name']." ".$db_dlip[$j]['lname']) . "\t";
                    
                                                                    $ans=$generalobjAdmin->set_hour_min($db_dlip[$j]['fGDtime']);
                                                                    if($ans['hour']!=0)
                                                                    {
                                                                        $ans1= $ans['hour']." Hours ".$ans['minute']." Minutes";
                                                                    }
                                                                    else
                                                                    {
                                                                        $ans1='';
                                                                        if($ans['minute']!= 0)
                                                                        {
                                                                            $ans1.= $ans['minute']." Minutes ";
                                                                        }
                                                                            
                                                                            $ans1.= $ans['second']." Seconds";
                                                                    }
                
                        $data .= $ans1. "\t";
                        
                                                                     $a=strtotime($db_dlip[$j]['tStartdate']);
                                                                     $b=strtotime($db_dlip[$j]['tEndDate']);;
                                                                    $diff_time=($b-$a);
                                                                    //$diff_time=$diff_time*1000;
                                                                    $ans_diff=$generalobjAdmin->set_hour_min($diff_time);
                                                                    //print_r($ans);exit;
                                                                    if($ans_diff['hour']!=0)
                                                                    {
                                                                        $ans_diff12 = $ans_diff['hour']." Hours ".$ans_diff['minute']." Minutes";
                                                                    }
                                                                    else
                                                                    {
                                                                        $ans_diff12 ='';
                                                                        
                                                                        if($ans_diff['minute']!= 0){
                                                                            $ans_diff12.= $ans_diff['minute']." Minutes ";
                                                                        }
                                                                            $ans_diff12.= $ans_diff['second']." Seconds";
                                                                    }   

                         $data .= $ans_diff12. "\t";
                        
                                                                        $ori_time=$db_dlip[$j]['fGDtime'];
                                                                        $tak_time=$diff_time;
                                                                        $ori_diff=$ori_time-$tak_time;
                                                                        echo $ans_ori=$generalobjAdmin->set_hour_min(abs($ori_diff));
                                                                        if($ans_ori['hour']!=0)
                                                                    {
                                                                        $ans2.= $ans_ori['hour']." Hours ".$ans_ori['minute']." Minutes";
                                                                        if($ori_diff < 0)
                                                                        {
                                                                            $ans2.= " Late";
                                                                        }
                                                                        else{
                                                                            
                                                                        $ans2.= " Early";}
                                                                    }
                                                                    else
                                                                    {
                                                                        $ans2 = ''; 
                                                                        if($ans_ori['minute']!= 0){
                                                                            $ans2.= $ans_ori['minute']." Minutes ";
                                                                        }
                                                                        $ans2.= $ans_ori['second']." Seconds";
                                                                        
                                                                        if($ori_diff < 0)
                                                                        {
                                                                            $ans2.= " Late";
                                                                        }
                                                                        else{
                                                                                $ans2.= " Early";
                                                                            }
                                                                    }
                        $data .= $ans2. "\n";
                   }
                
                
                ob_clean();
                header("Content-type: application/octet-sdleam; charset=utf-8");
                header("Content-Disposition: attachment; filename=driver_trip_detail.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                print "$header\n$data";
                exit;
}

?>