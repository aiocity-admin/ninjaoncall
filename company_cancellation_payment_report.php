<?php
include_once('common.php');
$generalobj->check_member_login();


$script = 'Cancellation_Payment_Report';

function cleanNumber($num) {
	return str_replace(',','',$num);
}


$searchCompany = $_SESSION['sess_iUserId'];

$sql = "select iDriverId,CONCAT(vName,' ',vLastName) AS driverName,vEmail from register_driver WHERE eStatus != 'Deleted' and iCompanyId='$searchCompany' order by vName";
$db_drivers = $obj->MySQLSelect($sql);

$sql = "select iUserId,CONCAT(vName,' ',vLastName) AS riderName,vEmail from register_user WHERE eStatus != 'Deleted' order by vName";
$db_rider = $obj->MySQLSelect($sql);

//data for select fields
//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
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
        $ord = " ORDER BY vCategory_EN ASC";
    else
        $ord = " ORDER BY vCategory_EN DESC";
}
//End Sorting
// Start Search Parameters
$ssql = '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$searchDriver = isset($_REQUEST['searchDriver']) ? $_REQUEST['searchDriver'] : '';
$searchRider = isset($_REQUEST['searchRider']) ? $_REQUEST['searchRider'] : '';
$serachTripNo = isset($_REQUEST['serachTripNo']) ? $_REQUEST['serachTripNo'] : '';
$searchDriverPayment = isset($_REQUEST['searchDriverPayment']) ? $_REQUEST['searchDriverPayment'] : '';
$searchPaymentType = isset($_REQUEST['searchPaymentType']) ? $_REQUEST['searchPaymentType'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
$eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';
$searchPaymentByUser = isset($_REQUEST['searchPaymentByUser']) ? $_REQUEST['searchPaymentByUser'] : '';
/*if($searchPaymentByUser == ''){
    $searchPaymentByUser = 'Yes';
}*/

   if ($searchCompany != '') {
        $ssql .= " AND rd.iCompanyId ='" . $searchCompany . "'";
    }
if ($action == 'search') {
    if ($startDate != '') {
        $ssql .= " AND Date(trp.tTripRequestDate) >='" . $startDate . "'";
    }
    if ($endDate != '') {
        $ssql .= " AND Date(trp.tTripRequestDate) <='" . $endDate . "'";
    }
    if ($serachTripNo != '') {
        $ssql .= " AND trp.vRideNo ='" . $serachTripNo . "'";
    }
 
    if ($searchDriver != '') {
        $ssql .= " AND tr.iDriverId ='" . $searchDriver . "'";
    }
    if ($searchRider != '') {
        $ssql .= " AND tr.iUserId ='" . $searchRider . "'";
    }
    if ($searchDriverPayment != '') {
        $ssql .= " AND tr.ePaidToDriver ='" . $searchDriverPayment . "'";
    }
    if ($searchPaymentType != '') {
        $ssql .= " AND tr.vTripPaymentMode ='" . $searchPaymentType . "'";
    }
    if ($eType != '') {




if(trim($eType)=='Ride'||trim($eType)=='Deliver')
{
            $ssql .= " AND trp.eType ='" . $eType . "'";

}else {

$ssql.=" and vcp.iVehicleCategoryId='".$eType."'";


}

    }
}

if($searchPaymentByUser != ''){
     $ssql .= " AND tr.ePaidByPassenger ='" . $searchPaymentByUser . "'";
}
$trp_ssql = "";
if (SITE_TYPE == 'Demo') {
    $trp_ssql = " And trp.tTripRequestDate > '" . WEEK_DATE . "'";
}

//Pagination Start
$per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page

$sql = "SELECT tr.iTripId,tr.iTripOutstandId,tr.iDriverId,tr.iUserId, tr.fCommision, tr.fDriverPendingAmount,tr.ePaidByPassenger,tr.ePaidToDriver,tr.vTripPaymentMode,trp.eType,trp.vRideNo,trp.tTripRequestDate,c.vCompany,concat(rd.vName,' ',rd.vLastName) as drivername,concat(ru.vName,' ',ru.vLastName) as riderName FROM trip_outstanding_amount AS tr LEFT JOIN register_driver AS rd ON tr.iDriverId = rd.iDriverId left join trips t on tr.iTripId=t.iTripId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId LEFT JOIN register_user AS ru ON tr.iUserId = ru.iUserId LEFT JOIN trips AS trp ON trp.iTripId = tr.iTripId  LEFT JOIN company as c ON rd.iCompanyId = c.iCompanyId WHERE  1 = 1 $ssql $trp_ssql ";
$totalData = $obj->MySQLSelect($sql);

$driver_payment = 0.00;
$tot_site_commission = 0.00;

foreach ($totalData as $dtps) {
    $driver_payment = $dtps['fDriverPendingAmount'];
    $site_commission = $dtps['fCommision'];
   
    $tot_site_commission = $tot_site_commission + cleanNumber($site_commission);
    $tot_driver_refund = $tot_driver_refund + cleanNumber($driver_payment);  
}
 
$total_results = count($totalData);
$total_pages = ceil($total_results / $per_page); //total pages we going to have
$show_page = 1;

//-------------if page is setcheck------------------//
if (isset($_GET['page'])) {
    $show_page = $_GET['page'];             //it will telles the current page
    if ($show_page > 0 && $show_page <= $total_pages) {
        $start = ($show_page - 1) * $per_page;
        $end = $start + $per_page;
    } else {
        // error - show first set of results
        $start = 0;
        $end = $per_page;
    }
} else {
    // if page isn't set, show first set of results
    $start = 0;
    $end = $per_page;
}

// display pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$tpages = $total_pages;
if ($page <= 0)
    $page = 1;
//Pagination End
$sql = "SELECT tr.iTripId,tr.iTripOutstandId,tr.iDriverId,tr.iUserId, tr.fCommision, tr.fPendingAmount, tr.fDriverPendingAmount, tr.fWalletDebit,tr.ePaidByPassenger,tr.ePaidToDriver,tr.vTripPaymentMode,trp.eType,trp.vRideNo,trp.tTripRequestDate,tr.vTripAdjusmentId,c.vCompany,concat(rd.vName,' ',rd.vLastName) as drivername,concat(ru.vName,' ',ru.vLastName) as riderName ,case when vcp.vCategory_EN is NULL or vcp.vCategory_EN='' then t.eType else  vcp.vCategory_EN end as vCategory_EN ,vt.vVehicleType FROM trip_outstanding_amount AS tr LEFT JOIN register_driver AS rd ON tr.iDriverId = rd.iDriverId left join trips t on tr.iTripId=t.iTripId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId LEFT JOIN register_user AS ru ON tr.iUserId = ru.iUserId LEFT JOIN trips AS trp ON trp.iTripId = tr.iTripId  LEFT JOIN company as c ON rd.iCompanyId = c.iCompanyId WHERE  1 = 1 $ssql $trp_ssql $ord LIMIT $start, $per_page";
$db_trip = $obj->MySQLSelect($sql);

$endRecord = count($db_trip);
$var_filter = "";
foreach ($_REQUEST as $key => $val) {
    if ($key != "tpages" && $key != 'page')
        $var_filter .= "&$key=" . stripslashes($val);
}

$reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages . $var_filter;
$Today = Date('Y-m-d');
$tdate = date("d") - 1;
$mdate = date("d");
$Yesterday = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));

$curryearFDate = date("Y-m-d", mktime(0, 0, 0, '1', '1', date("Y")));
$curryearTDate = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y")));
$prevyearFDate = date("Y-m-d", mktime(0, 0, 0, '1', '1', date("Y") - 1));
$prevyearTDate = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y") - 1));

$currmonthFDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $tdate, date("Y")));
$currmonthTDate = date("Y-m-d", mktime(0, 0, 0, date("m") + 1, date("d") - $mdate, date("Y")));
$prevmonthFDate = date("Y-m-d", mktime(0, 0, 0, date("m") - 1, date("d") - $tdate, date("Y")));
$prevmonthTDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $mdate, date("Y")));

$monday = date('Y-m-d', strtotime('sunday this week -1 week'));
$sunday = date('Y-m-d', strtotime('saturday this week'));

$Pmonday = date('Y-m-d', strtotime('sunday this week -2 week'));
$Psunday = date('Y-m-d', strtotime('saturday this week -1 week'));
?>
<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?= $SITE_NAME ?> | Cancellation Payment Report</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <?php include_once("top/top_script.php");?>
		<style>
			.setteled-class{
				background-color:#bddac5
			}
		</style>
    </head>
    <!-- END  HEAD-->

    <!-- BEGIN BODY-->
    <body class="padTop53 " >
            <!-- home page -->
    <div id="main-uber-page">
      <!-- Left Menu -->
      <?php include_once("top/left_menu.php");?>
      <!-- End: Left Menu-->
      <!-- Top Menu -->
      <?php include_once("top/header_topbar.php");?>
      <!-- End: Top Menu-->
      <!-- History of Provider wallet page-->
      <div class="page-contant">
        <div class="page-contant-inner">
                    <div id="add-hide-show-div">
                    <div id="add-hide-show-div">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2>Cancellation Payment Report</h2>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <?php include('valid_msg.php'); ?>
                    <form name="frmsearch" id="frmsearch" action="javascript:void(0);" method="post" >
                        <div class="Posted-date mytrip-page payment-report">
                            <input type="hidden" name="action" value="search" />
                            <h3>Search <?php echo $langage_lbl_admin['LBL_PASSANGER_TXT_ADMIN'];?>...</h3>
                            <span>
                                <a style="cursor:pointer" onClick="return todayDate('dp4', 'dp5');"><?= $langage_lbl_admin['LBL_MYTRIP_Today']; ?></a>
                                <a style="cursor:pointer" onClick="return yesterdayDate('dFDate', 'dTDate');"><?= $langage_lbl_admin['LBL_MYTRIP_Yesterday']; ?></a>
                                <a style="cursor:pointer" onClick="return currentweekDate('dFDate', 'dTDate');"><?= $langage_lbl_admin['LBL_MYTRIP_Current_Week']; ?></a>
                                <a style="cursor:pointer" onClick="return previousweekDate('dFDate', 'dTDate');"><?= $langage_lbl_admin['LBL_MYTRIP_Previous_Week']; ?></a>
                                <a style="cursor:pointer" onClick="return currentmonthDate('dFDate', 'dTDate');"><?= $langage_lbl_admin['LBL_MYTRIP_Current_Month']; ?></a>
                                <a style="cursor:pointer" onClick="return previousmonthDate('dFDate', 'dTDate');"><?= $langage_lbl_admin['LBL_MYTRIP_Previous Month']; ?></a>
                                <a style="cursor:pointer" onClick="return currentyearDate('dFDate', 'dTDate');"><?= $langage_lbl_admin['LBL_MYTRIP_Current_Year']; ?></a>
                                <a style="cursor:pointer" onClick="return previousyearDate('dFDate', 'dTDate');"><?= $langage_lbl_admin['LBL_MYTRIP_Previous_Year']; ?></a>
                            </span> 
                            <span>
                                <input type="text" id="dp4" name="startDate" placeholder="From Date" class="form-control" value="" readonly="" style="cursor:default; background-color: #fff" />
                                <input type="text" id="dp5" name="endDate" placeholder="To Date" class="form-control" value="" readonly="" style="cursor:default; background-color: #fff"/>

     <div class="col-lg-3" style="display: none;">
                                <select class="form-control" name='searchPaymentType' data-text="Select <?php echo $langage_lbl_admin['LBL_PASSANGER_TXT_ADMIN'];?>">
                                    <option value="">Select Payment Type</option>
                                    <option value="Cash" <?if($searchPaymentType == "Cash"){?>selected <?}?>>Cash</option>
                                    <option value="Card" <?if($searchPaymentType == "Card"){?>selected <?}?>>Card</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <select class="form-control" name='searchDriverPayment' data-text="Select <?php echo $langage_lbl_admin['LBL_PASSANGER_TXT_ADMIN'];?>">
                                    <option value="">Select <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Payment Status</option>
                                    <option value="Yes" <?php if($searchDriverPayment == "Yes"){?>selected <?php } ?>>Settelled</option>
                                    <option value="No" <?php if($searchDriverPayment == "No"){?>selected <?php } ?>>Unsettelled</option>
                                </select>
                            </div>

                                <div class="col-lg-2">
                                  <input type="text" id="serachTripNo" name="serachTripNo" placeholder="<?php echo $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'];?> Number" class="form-control search-trip001" value="<?php echo $serachTripNo; ?>"/>
                            </div>
                            </span>
                        </div>

                        <div class="row payment-report payment-report1 payment-report2">


                                <div class="col-lg-3 select001">
                                    <select class="form-control filter-by-text driver_container" name = 'searchDriver' data-text="Select <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?>">
                                        <option value="">Select <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?></option>
                                            <?php foreach ($db_drivers as $dbd) { ?>
                                            <option value="<?php echo $dbd['iDriverId']; ?>" <?php if ($searchDriver == $dbd['iDriverId']) {
                                                echo "selected";
                                            } ?>><?php echo $generalobj->clearName($dbd['driverName']); ?> - ( <?php echo $dbd['vEmail']; ?> )</option>
                                        <?php } ?>
                                    </select>
                                </div>

                            <div class="col-lg-3 select001">
                                <select class="form-control filter-by-text" name = 'searchRider' data-text="Select <?php echo $langage_lbl_admin['LBL_PASSANGER_TXT_ADMIN'];?>">
                                    <option value="">Select <?php echo $langage_lbl_admin['LBL_PASSANGER_TXT_ADMIN'];?></option>
                                    <?php foreach ($db_rider as $dbr) { ?>
                                        <option value="<?php echo $dbr['iUserId']; ?>" <?php if ($searchRider == $dbr['iUserId']) {
                                        echo "selected";
                                    } ?>><?php echo $generalobj->clearName($dbr['riderName']); ?> - ( <?php echo $dbr['vEmail']; ?> )</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php if($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'Ride-Delivery'){ ?>
                         
<div class="col-lg-3 select001">

  <select  class="form-control miltiselect" name='eType' id="eType" >
                                       <option value="">Select Category</option>

                                                <option value='Ride'   <?php



 if (trim($eType)=='Ride') {
 echo 'selected';
}
?> >Ride</option>
                                                <option value='Deliver'  <?php


 if (trim($eType)=='Deliver') {
 echo 'selected';
}
?> >Delivery</option>

      <?
 // $vehicle_type_sql1 = "SELECT vt.*,vc.*,lm.vLocationName FROM vehicle_type AS vt LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId LEFT JOIN location_master AS lm ON lm.iLocationId = vt.iLocationid WHERE vt.eType='UberX' AND  vc.eStatus='Active' order by vt.vVehicleType_EN";
       $vehicle_type_sql1 = "SELECT * FROM  vehicle_category  WHERE    eStatus='Active'  and `iParentId`=0 order by vCategory_EN";
          $vehicle_type= $obj->MySQLSelect($vehicle_type_sql1);
//$Scompany=explode(',', $eType);

        foreach ($vehicle_type as $subkey => $subvalue) {
$isSelected="";



 if (trim($eType)==$subvalue['iVehicleCategoryId']) {
           $isSelected="selected";
}



           echo "<option value='".$subvalue['iVehicleCategoryId']."' $isSelected>".$subvalue['vCategory_EN']."</option>";

}         

       ?>
                                               
                      </select>

                              
                    </div>
                        <?php } ?>
                            <div class="col-lg-2 select001">
                                <select class="form-control" name='searchPaymentByUser' data-text="Paid By <?php echo $langage_lbl_admin['LBL_PASSANGER_TXT_ADMIN'];?>">
                                    <option value="">Select User Payment Status</option>
                                    <option value="Yes" <?if($searchPaymentByUser == "Yes"){?>selected <?}?>>Paid By <?php echo $langage_lbl_admin['LBL_PASSANGER_TXT_ADMIN'];?> - Yes</option>
                                    <option value="No" <?if($searchPaymentByUser == "No"){?>selected <?}?>>Paid By <?php echo $langage_lbl_admin['LBL_PASSANGER_TXT_ADMIN'];?> -No</option>
                                </select>
                            </div>
						
                        </div>
<center>
                       
                        <div class="tripBtns001"><b>
                                <input type="submit" value="Search" class="btn btnalt button11" id="Search" name="Search" title="Search" />
                                <input type="button" value="Reset" class="btn btnalt button11" onClick="window.location.href = 'company_cancellation_payment_report.php'"/>
                                <?php  if(count($db_trip) > 0){ ?>
                                <button type="button" onClick="reportExportTypes('cancellation_driver_payment')" class="btn button11 export-btn001" >Export</button></b>
                                <?php } ?>
                        </div>
                    </center><br>
                    </form>
                    <div class="table-list">
                        <div class="row">
                            <div class="col-lg-12">
                               <div class="table-responsive">
                                    <form name="_list_form" id="_list_form" class="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                                        <input type="hidden" id="actionpayment" name="actionpayment" value="pay_driver">
                                        <input type="hidden" name="iTripId" id="iTripId" value="">
                                        <input type="hidden" name="ePayDriver" id="ePayDriver" value="">
                                        <table class="table table-bordered" id="dataTables-example123" >
                                            <thead>
                                                <tr>
                                                    <?php if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){?>
                                                   <th><a href="javascript:void(0);" onClick="Redirect(6,<?php if ($sortby == '6') {
                                                        echo $order;
                                                    } else { ?>0<?php } ?>)">Service Category<?php if ($sortby == 6) {
                                                        if ($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php }
                                                    } else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                                    <?php } ?>
                                                    <th>Cancelled <?php echo $langage_lbl_admin['LBL_RIDE_NO_ADMIN']; ?> </th>
                                                    <th><a href="javascript:void(0);" onClick="Redirect(1,<?php if ($sortby == '1') {
                                                            echo $order;
                                                        } else { ?>0<?php } ?>)"><?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']; ?> <?php if ($sortby == 1) {
                                                            if ($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php }
                                                        } else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                                    <th><a href="javascript:void(0);" onClick="Redirect(2,<?php if ($sortby == '2') {
                                                        echo $order;
                                                            } else {
                                                            ?>0<?php } ?>)"><?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN']; ?> <?php if ($sortby == 2) {
                                                                if ($order == 0) {
                                                                ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php }
                                                    } else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                    </th>

                                                    <th><a href="javascript:void(0);" onClick="Redirect(3,<?php if ($sortby == '3') {
                                                echo $order;
                                            } else { ?>0<?php } ?>)"><?= $langage_lbl_admin['LBL_TRIP_DATE_ADMIN']; ?> <?php if ($sortby == 3) {
                                                if ($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php }
                                            } else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                                    <th width="8%">Total Cancellation Fees</th>
                                                    <th style="text-align:right;">Platform Fees</th>
                                                    <th width="10%" style="text-align:right;"><?= $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']; ?> Pay Amount</th>
                                                    <th width="10%">User Payment Status</th>
                                                    <th width="10%"><?= $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']; ?> Payment Status</th> 
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?
                                                $set_unsetarray = array();
                                                if(count($db_trip) > 0){
                                                for($i=0;$i<count($db_trip);$i++)
                                                {
													$class_setteled ="";
													if($db_trip[$i]['ePaidToDriver'] == 'Yes'){
														$class_setteled = "setteled-class";
													}
                                                    $set_unsetarray[] = $db_trip[$i]['ePaidToDriver'];
                                                   /* $eTypenew = $db_trip[$i]['eType'];
                                                    if($eTypenew == 'Ride'){
                                                        $trip_type = 'Ride';
                                                    } else if($eTypenew == 'UberX') {
                                                        $trip_type = 'Other Services';
                                                    } else {
                                                        $trip_type = 'Delivery';
                                                    }
*/
                                                    $q = "SELECT vRideNo FROM trips WHERE iTripId = '".$db_trip[$i]['vTripAdjusmentId']."'";
                                                    $db_bookingno = $obj->MySQLSelect($q);

                                                ?>
                                                <tr class="gradeA <?=$class_setteled?>">
                                                    <?php if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){ ?> 
                                                    <td align="left">
                                                   <!--  <? if($db_trip[$i]['eHailTrip'] != "Yes"){
                                                            echo $trip_type;
                                                        }else{
                                                            echo $trip_type." ( Hail )";
                                                        }
                                                        ?> -->
                                                        <?=$db_trip[$i]['vCategory_EN'];?>

                                                        
                                                    </td>
                                                    <?php } ?>
                                                    <td> <a href="invoice.php?iTripId=<?=$db_trip[$i]['iTripId'];?>" target="_blank"><?= $db_trip[$i]['vRideNo']; ?></a></td>
                                                    <td><?= $generalobj->clearName($db_trip[$i]['drivername']); ?></td>
                                                    <td><?= $generalobj->clearName($db_trip[$i]['riderName']); ?></td>
                                                    <td><?= $generalobj->DateTime($db_trip[$i]['tTripRequestDate']); ?></td>
                                                    <td><? $TotalCancelledprice = $db_trip[$i]['fPendingAmount'] > $db_trip[$i]['fWalletDebit'] ? $db_trip[$i]['fPendingAmount']:$db_trip[$i]['fWalletDebit'];
                                                     echo $generalobj->trip_currency($TotalCancelledprice); ?></td>
                                                    <td align="right"><?php if ($db_trip[$i]['fCommision'] != "" && $db_trip[$i]['fCommision'] != 0) {
                                                            echo $generalobj->trip_currency($db_trip[$i]['fCommision']);
                                                        } else {
                                                            echo '-';
                                                        } ?></td>
                                                    </td>
                                                    <td align="right">
                                                    <?php
                                                    if ($db_trip[$i]['fDriverPendingAmount'] != "" && $db_trip[$i]['fDriverPendingAmount'] != 0) {
                                                        echo $generalobj->trip_currency($db_trip[$i]['fDriverPendingAmount']);
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                     <td><?if(!empty($db_bookingno[0]['vRideNo'])) { ?> Paid in Trip# 
                                                        <a href="invoice.php?iTripId=<?=$db_trip[$i]['vTripAdjusmentId'];?>" target="_blank"><?= $db_bookingno[0]['vRideNo'];?></a>
                                                     <?   } else if($db_trip[$i]['ePaidByPassenger'] == 'No') {
                                                            echo"<b>Not Paid</b>";
                                                     } else {
                                                       echo"Paid By Card"; 
                                                     } ?></td>
                                                    <td> 
                                                        <? if($db_trip[$i]['ePaidToDriver'] == 'No'){ 
                                                            echo "Unsettelled";
                                                        } else { 
                                                            echo "settelled";
                                                        } ?>
                                                    </td>
                                                    <td>
                                                        <? 
                                                        if($db_trip[$i]['ePaidToDriver'] == 'No'){
                                                        ?>
                                                        <input class="validate[required]" type="checkbox" value="<?= $db_trip[$i]['iTripId'] ?>" id="iTripId_<?= $db_trip[$i]['iTripId'] ?>" name="iTripId[]">
                                                        <?
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <? } ?>
                                                <tr class="gradeA">
                                                    <td colspan="9" align="right">Total Platform Fees</td>
                                                    <td colspan="2"  align="right" colspan="2"><?= $generalobj->trip_currency($tot_site_commission); ?></td>
                                                </tr>

                                                <tr class="gradeA">
                                                    <td colspan="9" align="right">Total <?= $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']; ?> Payment</td>
                                                    <td colspan="2" align="right" colspan="2"><?= $generalobj->trip_currency($tot_driver_refund); ?></td>
                                                </tr>

                                                <?php if (in_array("No", $set_unsetarray)) { ?>
                                                    <tr class="gradeA">
                                                        <td colspan="11" align="right"><div class="row payment-report-button">
                                                        <span style="margin-right: 15px;">
                                                        <a onClick="PaytodriverforCancel()" href="javascript:void(0);"><button class="btn btn-primary" type="button">Mark As Settelled</button></a>
                                                        </span>
                                                        </div>
                                                        </td>
                                                    </tr>
                                                <? }
                                                } else {?>
                                                    <tr class="gradeA">
                                                        <td colspan="11" style="text-align:center;">No Payment Details Found.</td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </form>
 <div style="margin-top: 10px;">
                      <?php include_once("pagging.php"); ?>
                              
                                    </div>                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <!--END PAGE CONTENT -->
            </div>
        <!--END MAIN WRAPPER -->

<form name="pageForm" id="pageForm" action="action/cancellation_payment_report.php" method="post" >
    <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
    <input type="hidden" name="tpages" id="tpages" value="<?php echo $tpages; ?>">
    <input type="hidden" name="sortby" id="sortby" value="<?php echo $sortby; ?>" >
    <input type="hidden" name="order" id="order" value="<?php echo $order; ?>" >
    <input type="hidden" name="action" value="<?php echo $action; ?>" >
    <input type="hidden" name="searchCompany" value="<?php echo $searchCompany; ?>" >
    <input type="hidden" name="searchDriver" value="<?php echo $searchDriver; ?>" >
    <input type="hidden" name="searchRider" value="<?php echo $searchRider; ?>" >
    <input type="hidden" name="serachTripNo" value="<?php echo $serachTripNo; ?>" >
    <input type="hidden" name="searchPaymentType" value="<?php echo $searchPaymentType; ?>" >
    <input type="hidden" name="searchDriverPayment" value="<?php echo $searchDriverPayment; ?>" >
    <input type="hidden" name="startDate" value="<?php echo $startDate; ?>" >
    <input type="hidden" name="endDate" value="<?php echo $endDate; ?>" >
    <input type="hidden" name="vStatus" value="<?php echo $vStatus; ?>" >
    <input type="hidden" name="eType" value="<?php echo $eType; ?>" >
    <input type="hidden" name="searchPaymentByUser" value="<?php echo $searchPaymentByUser; ?>" >
    <input type="hidden" name="method" id="method" value="" >
</form>


     <?php include_once('footer/footer_home.php');?>

 <?php include_once('top/footer_script.php');?>

<link rel="stylesheet" href="assets/plugins/datepicker/css/datepicker.css" />
<link rel="stylesheet" href="admin/css/select2/select2.min.css" />
       <link rel="stylesheet" type="text/css" href="admin/css/admin_new/admin_style.css">
<script src="assets/js/jquery-ui.min.js"></script>

<script src="admin/js/plugins/select2.min.js"></script>
<script src="assets/plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script>
    $('#dp4').datepicker()
        .on('changeDate', function (ev) {
        var endDate = $('#dp5').val();
        if (ev.date.valueOf() < endDate.valueOf()) {
            $('#alert').show().find('strong').text('The start date can not be greater then the end date');
        } else {
            $('#alert').hide();
            var startDate = new Date(ev.date);
            $('#startDate').text($('#dp4').data('date'));
        }
        $('#dp4').datepicker('hide');
    });
    $('#dp5').datepicker()
    .on('changeDate', function (ev) {
        var startDate = $('#dp4').val();
        if (ev.date.valueOf() < startDate.valueOf()) {
            $('#alert').show().find('strong').text('The end date can not be less then the start date');
        } else {
            $('#alert').hide();
            var endDate = new Date(ev.date);
            $('#endDate').text($('#dp5').data('date'));
        }
        $('#dp5').datepicker('hide');
    });

    $(document).ready(function () {
        $("#dp5").click(function(){
             $('#dp5').datepicker('show');
             $('#dp4').datepicker('hide');
        });

        $("#dp4").click(function(){
             $('#dp4').datepicker('show');
             $('#dp5').datepicker('hide');
        });

        if ('<?= $startDate ?>' != '') {
            $("#dp4").val('<?= $startDate ?>');
            $("#dp4").datepicker('update', '<?= $startDate ?>');
        }
        if ('<?= $endDate ?>' != '') {
            $("#dp5").datepicker('update', '<?= $endDate; ?>');
            $("#dp5").val('<?= $endDate; ?>');
        }
    });

    function setRideStatus(actionStatus) {
        window.location.href = "trip.php?type=" + actionStatus;
    }
    function todayDate() {
        $("#dp4").val('<?= $Today; ?>');
        $("#dp5").val('<?= $Today; ?>');
    }
    function reset() {
        location.reload();
    }
    function yesterdayDate()
    {
        $("#dp4").val('<?= $Yesterday; ?>');
        $("#dp4").datepicker('update', '<?= $Yesterday; ?>');
        $("#dp5").datepicker('update', '<?= $Yesterday; ?>');
        $("#dp4").change();
        $("#dp5").change();
        $("#dp5").val('<?= $Yesterday; ?>');
    }
    function currentweekDate(dt, df)
    {
        $("#dp4").val('<?= $monday; ?>');
        $("#dp4").datepicker('update', '<?= $monday; ?>');
        $("#dp5").datepicker('update', '<?= $sunday; ?>');
        $("#dp5").val('<?= $sunday; ?>');
    }
    function previousweekDate(dt, df)
    {
        $("#dp4").val('<?= $Pmonday; ?>');
        $("#dp4").datepicker('update', '<?= $Pmonday; ?>');
        $("#dp5").datepicker('update', '<?= $Psunday; ?>');
        $("#dp5").val('<?= $Psunday; ?>');
    }
    function currentmonthDate(dt, df)
    {
        $("#dp4").val('<?= $currmonthFDate; ?>');
        $("#dp4").datepicker('update', '<?= $currmonthFDate; ?>');
        $("#dp5").datepicker('update', '<?= $currmonthTDate; ?>');
        $("#dp5").val('<?= $currmonthTDate; ?>');
    }
    function previousmonthDate(dt, df)
    {
        $("#dp4").val('<?= $prevmonthFDate; ?>');
        $("#dp4").datepicker('update', '<?= $prevmonthFDate; ?>');
        $("#dp5").datepicker('update', '<?= $prevmonthTDate; ?>');
        $("#dp5").val('<?= $prevmonthTDate; ?>');
    }
    function currentyearDate(dt, df)
    {
        $("#dp4").val('<?= $curryearFDate; ?>');
        $("#dp4").datepicker('update', '<?= $curryearFDate; ?>');
        $("#dp5").datepicker('update', '<?= $curryearTDate; ?>');
        $("#dp5").val('<?= $curryearTDate; ?>');
    }
    function previousyearDate(dt, df)
    {
        $("#dp4").val('<?= $prevyearFDate; ?>');
        $("#dp4").datepicker('update', '<?= $prevyearFDate; ?>');
        $("#dp5").datepicker('update', '<?= $prevyearTDate; ?>');
        $("#dp5").val('<?= $prevyearTDate; ?>');
    }
    $("#Search").on('click', function () {
        if ($("#dp5").val() < $("#dp4").val()) {
            alert("From date should be lesser than To date.")
            return false;
        } else {
            var action = $("#_list_form").attr('action');
            var formValus = $("#frmsearch").serialize();
            window.location.href = action + "?" + formValus;
        }
    });
    $(function () {
        $("select.filter-by-text").each(function () {
            $(this).select2({
                placeholder: $(this).attr('data-text'),
                allowClear: true
            }); //theme: 'classic'
        });
    });
    $('#searchCompany').change(function() {
        var company_id = $(this).val(); //get the current value's option
        $.ajax({
            type:'POST',
            url:'ajax_find_driver_by_company.php',
            data:{'company_id':company_id},
            cache: false,
            success:function(data){
                $(".driver_container").html(data);
            }
        });
    });
            function reportExportTypes(section) {
            var action = "export_reports.php";
            var formValus = $("#pageForm").serialize();
            //alert(formValus);
            window.location.href = action+'?section='+section+'&'+formValus;
            return false;
    }
</script><style type="text/css">
  body
{
  background-color: white;
}

.button11
  {

        background: #219201;
    color: #FFFFFF;
  }

 

.ui-helper-hidden-accessible div
{
display: none;
}
#search
{
  margin-left: 5px;
}


input#serachTripNo {
    width: 100%;
    background: none !important;

}
.select001
{
    margin-top: 10px;
    margin-bottom: 10px;
}
</style>
</body>
<!-- END BODY-->
</html>