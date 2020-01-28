<?
include_once('common.php');
include_once('generalFunctions.php');
$script="Trips";
$tbl_name 	= 'register_driver';
 $generalobj->check_member_login();
 $abc = 'admin,company';
 $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
 $generalobj->setRole($abc,$url);
$action=(isset($_REQUEST['action'])?$_REQUEST['action']:'');

$vStatus=isset($_REQUEST['vStatus'])?$_REQUEST['vStatus']:'';
$eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';

$searchDriver = isset($_REQUEST['searchDriver']) ? $_REQUEST['searchDriver'] : '';
$searchRider = isset($_REQUEST['searchRider']) ? $_REQUEST['searchRider'] : '';
$serachTripNo = isset($_REQUEST['serachTripNo']) ? $_REQUEST['serachTripNo'] : '';

$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';

$ssql='';

$ord = ' ORDER BY t.iTripId DESC';


	if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY category_name ASC";
  else
  $ord = " ORDER BY category_name DESC";
}	
	

if($sortby == 2){

	if($vStatus == "Decline"||$vStatus == "Timeout"||$vStatus == "Yes" || $vStatus == "InProcess" )
			 {
			 }
			 else
			 {
  if($order == 0)
  $ord = " ORDER BY  t.vRideNo ASC";
  else
  $ord = " ORDER BY  t.vRideNo DESC";
}
}


if($sortby == 6){

			if($vStatus == "Decline"||$vStatus == "Timeout"||$vStatus == "Yes" || $vStatus == "InProcess" )
			 {
				  if($order == 0)
				   $ord = " ORDER BY t.dAddedDate ASC";
                 else
                  $ord = " ORDER BY t.dAddedDate DESC";
            }
            else{
                 if($order == 0)
                 $ord = " ORDER BY t.tTripRequestDate ASC";
                else
                 $ord = " ORDER BY t.tTripRequestDate DESC";
               }
}

if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY tSaddress ASC, tDaddress ASC";
  else
  $ord = " ORDER BY tSaddress DESC, tDaddress DESC";
}

if($sortby == 4){
  if($order == 0)
  $ord = " ORDER BY d.vName ASC,d.vLastName ASC";
  else
  $ord = " ORDER BY d.vName DESC,d.vLastName DESC";
}

if($sortby == 5){
  if($order == 0)
  $ord = " ORDER BY u.vName ASC,u.vLastName ASC";
  else
  $ord = " ORDER BY u.vName DESC,u.vLastName DESC";
}


if($sortby == 7){
  if($order == 0)
  $ord = " ORDER BY vVehicleType ASC";
  else
  $ord = " ORDER BY vVehicleType DESC";
}



if($action!='')
{
	$startDate=$_REQUEST['startDate'];
	$endDate=$_REQUEST['endDate'];
	if($startDate!=''){
		if($vStatus == "Decline"||$vStatus == "Timeout"||$vStatus == "Yes" || $vStatus == "InProcess")
			 {
	$ssql.=" AND Date(t.dAddedDate) >='".$startDate."'";


				}
				else 
				{
		$ssql.=" AND Date(t.tTripRequestDate) >='".$startDate."'";
	}
	}
	if($endDate!=''){

		if($vStatus == "Decline"||$vStatus == "Timeout"||$vStatus == "Yes" || $vStatus == "InProcess")
			 {
			 		$ssql.=" AND Date(t.dAddedDate) <='".$endDate."'";

				}
				else 
				{
		$ssql.=" AND Date(t.tTripRequestDate) <='".$endDate."'";
	}
	}


if($searchDriver!=''){

	if($vStatus == "Decline"||$vStatus == "Timeout"||$vStatus == "Yes" || $vStatus == "InProcess")
			 {
	$ssql.=" AND d.iDriverId ='".$searchDriver."'";

			 }else
			 {
	$ssql.=" AND t.iDriverId ='".$searchDriver."'";
}
}

$join="left join vehicle_type vt on vt.iVehicleTypeId=t.iVehicleTypeId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId ";
$SELECT=",vt.vVehicleType_".$default_lang."  as vVehicleType,case when vc.vCategory_".$default_lang." is NULL OR vc.vCategory_".$default_lang."='' then t.eType else vc.vCategory_".$default_lang." end  as sub_category_name,case when vcp.vCategory_".$default_lang." is NULL or vcp.vCategory_".$default_lang."='' then t.eType else vcp.vCategory_".$default_lang." end  as category_name,t.eType";

if($eType!=''){



 if (trim($eType)!='null')
{

 $eType2= explode(',', $eType);
  $subQuery_company='(';

  for ($i=0; $i <count($eType2) ; $i++) { 


	if($eType2[$i] == 'Ride'){

		if($vStatus == "Decline"||$vStatus == "Timeout"||$vStatus == "Yes" || $vStatus == "InProcess"){
			
    $subQuery_company .= "    t.eType = '".$eType2[$i]."' or";
}else{
		$subQuery_company.="  t.eType ='".$eType2[$i]."' AND t.iRentalPackageId = 0 AND t.eHailTrip = 'No' or";
	}
	} elseif($eType2[$i] == 'RentalRide'){
		$subQuery_company.="  t.eType ='Ride' AND t.iRentalPackageId > 0 or";
	} elseif($eType2[$i] == 'HailRide'){
		$subQuery_company.="  t.eType ='Ride' AND t.eHailTrip = 'Yes' or";
	} else if($eType2[$i] == 'Deliver') {

		
		$subQuery_company.="  t.eType ='".$eType2[$i]."' or";
	
}

	else
	{
    $subQuery_company .= "    vcp.iVehicleCategoryId = '".$eType2[$i]."' or";
    

	}
  //$subQuery_company.=" d.iCompanyId='$eType[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');

 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";

}

$ssql.=$subQuery;


}



if($vStatus == "onRide") {
	$ssql .= " AND (t.iActive = 'On Going Trip' OR t.iActive = 'Active') AND t.eCancelled='No'";
}else if($vStatus == "cancel") {
	$ssql .= " AND (t.iActive = 'Canceled' OR t.eCancelled='yes')";
}else if($vStatus == "complete") {
	$ssql .= " AND t.iActive = 'Finished' AND t.eCancelled='No'";
}
else if($vStatus == "Decline")
{
$ssql .="and rs.eStatus = '".$vStatus."' ";
}
else if($vStatus == "Timeout")
{
	$chk_str_date = @date('Y-m-d H:i:s', strtotime('-'.$RIDER_REQUEST_ACCEPT_TIME.' second'));

	$ssql .="and (rs.eStatus = 'Timeout' OR rs.eStatus  = 'Received') AND rs.eAcceptAttempted  = 'No' AND  rs.dAddedDate < '".$chk_str_date."'";
 
}
else if($vStatus == "InProcess")
{

$chk_str_date = @date('Y-m-d H:i:s', strtotime('-'.$RIDER_REQUEST_ACCEPT_TIME.' second'));

	$ssql .="and (rs.eStatus = 'Timeout' OR rs.eStatus  = 'Received') AND rs.eAcceptAttempted  = 'No' AND  rs.dAddedDate > '".$chk_str_date."'";
}
else if($vStatus == "Yes")
{
$ssql .="and rs.eAcceptAttempted = 'Yes' ";
}




}

$per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page


if($vStatus == "Decline"||$vStatus == "Timeout"||$vStatus == "Yes"||$vStatus == "InProcess")
	{
		$sql = "SELECT COUNT(t.iCabRequestId) AS Total FROM register_driver d left join driver_request rs on d.iDriverId=rs.iDriverId left join company c on d.iCompanyId=c.iCompanyId left join cab_request_now t on rs.iRequestId=t.iCabRequestId  $join LEFT JOIN  register_user u ON t.iUserId = u.iUserId WHERE 1=1 $ssql $trp_ssql";



	}
	else 
	{

		$sql = "SELECT  COUNT(t.iTripId) as Total FROM register_driver d RIGHT JOIN trips t ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  WHERE d.iCompanyId = '".$_SESSION['sess_iUserId']."'".$ssql."";

	}


$totalData = $obj->MySQLSelect($sql);
$total_results = $totalData[0]['Total'];
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
$tpages=$total_pages;
if ($page <= 0)
    $page = 1;

if($vStatus == "Decline"||$vStatus == "Timeout"||$vStatus == "Yes" || $vStatus=="InProcess")
	{

$sql="SELECT  d.iDriverId  ,c.vCompany,u.vName,u.MiddleName,u.vLastName,d.vName AS name, d.vLastName AS lname,u.iUserId,t.tSourceAddress as tSaddress ,t.tDestAddress as tDaddress ,t.dAddedDate AS tTripRequestDate $SELECT FROM register_driver d left join driver_request rs on d.iDriverId=rs.iDriverId left join company c on d.iCompanyId=c.iCompanyId left join cab_request_now t on rs.iRequestId=t.iCabRequestId  $join LEFT JOIN  register_user u ON t.iUserId = u.iUserId  WHERE d.iCompanyId = '".$_SESSION['sess_iUserId']."' $ssql $trp_ssql group by t.iCabRequestId  $ord LIMIT $start, $per_page";

	}
	else 
	{
$sql = "SELECT u.vName, u.vLastName,t.tEndDate, t.tTripRequestDate, t.vRideNo, t.iActive,d.vAvgRating, t.fOutStandingAmount, t.iFare, d.iDriverId, t.tSaddress, t.tDaddress,t.fTripGenerateFare, t.iRentalPackageId,t.eType, t.eHailTrip, d.vName AS name, d.vLastName AS lname,t.eCarType, t.vTimezone, t.iTripId,vt.vVehicleType_".$_SESSION['sess_lang']." as vVehicleType,t.fCommision,t.fTripGenerateFare,t.fTipPrice, t.fCancellationFare, t.eCancelled,case when vc.vCategory_".$default_lang." is NULL OR vc.vCategory_".$default_lang."='' then t.eType else vc.vCategory_".$default_lang." end  as sub_category_name,case when vcp.vCategory_".$default_lang." is NULL or vcp.vCategory_".$default_lang."='' then t.eType else vcp.vCategory_".$default_lang." end  as category_name FROM register_driver d RIGHT JOIN trips t ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  WHERE d.iCompanyId = '".$_SESSION['sess_iUserId']."'".$ssql."  $ord LIMIT $start, $per_page";
}
//echo $sql;
$db_trip = $obj->MySQLSelect($sql);


$endRecord = count($db_trip);
$var_filter = "";
foreach ($_REQUEST as $key=>$val) {
    if($key != "tpages" && $key != 'page')
    $var_filter.= "&$key=".stripslashes($val);
}

$reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages.$var_filter;


$Today=Date('Y-m-d');
$tdate=date("d")-1;
$mdate=date("d");
$Yesterday = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

$curryearFDate = date("Y-m-d",mktime(0,0,0,'1','1',date("Y")));
$curryearTDate = date("Y-m-d",mktime(0,0,0,"12","31",date("Y")));
$prevyearFDate = date("Y-m-d",mktime(0,0,0,'1','1',date("Y")-1));
$prevyearTDate = date("Y-m-d",mktime(0,0,0,"12","31",date("Y")-1));

$currmonthFDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$tdate,date("Y")));
$currmonthTDate = date("Y-m-d",mktime(0,0,0,date("m")+1,date("d")-$mdate,date("Y")));
$prevmonthFDate = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d")-$tdate,date("Y")));
$prevmonthTDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$mdate,date("Y")));

$monday = date( 'Y-m-d', strtotime( 'sunday this week -1 week' ) );
$sunday = date( 'Y-m-d', strtotime( 'saturday this week' ) );

$Pmonday = date( 'Y-m-d', strtotime('sunday this week -2 week'));
$Psunday = date( 'Y-m-d', strtotime('saturday this week -1 week'));
if($host_system == 'cubetaxiplus') {
  $canceled_icon = "canceled-invoice.png";
  $invoice_icon = "driver-view-icon.png";
} else if($host_system == 'ufxforall') {
  $canceled_icon = "ufxforall-canceled-invoice.png";
  $invoice_icon = "ufxforall-driver-view-icon.png";
} else if($host_system == 'uberridedelivery4') {
  $canceled_icon = "ride-delivery-canceled-invoice.png";
  $invoice_icon = "ride-delivery-driver-view-icon.png";
} else if($host_system == 'uberdelivery4') {
  $canceled_icon = "delivery-canceled-invoice.png";
  $invoice_icon = "delivery-driver-view-icon.png";
} else {
  $invoice_icon = "driver-view-icon.png";
  $canceled_icon = "canceled-invoice.png";
}
?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=$SITE_NAME?> | <?=$langage_lbl['LBL_HEADER_TRIPS_TXT']; ?></title>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
   
    <!-- <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" /> -->
    <!-- End: Default Top Script and css-->
</head>
<body>
  <!-- home page -->
    <div id="main-uber-page">
    <!-- Left Menu -->
    <?php include_once("top/left_menu.php");?>
    <!-- End: Left Menu-->
        <!-- Top Menu -->
        <?php include_once("top/header_topbar.php");?>
        <!-- End: Top Menu-->
        <!-- contact page-->
		<div class="page-contant">
			<div class="page-contant-inner">
			  	<h2 class="header-page"><?if($vStatus=="cancel")
			  	                            echo "Cancelled";
			  	                            else if ($vStatus=="complete") 
                                             echo "Completed";	
                                            else if ($vStatus=="onRide") 
                                            	echo "on going";
		  	                            


			  	?> <?=$langage_lbl['LBL_COMPANY_TRIP_HEADER_TRIPS_TXT']; ?></h2>

			<!--   	<? if($vStatus!="") { ?>

<input type="button" class="btn btn-info" style="float: right;" value="Close" onclick="window.top.close()">
			  	 <? }?> -->
		  		<!-- trips page -->
			  	<div class="trips-page">
			  		<form name="search" action="" method="post" onSubmit="return checkvalid()">
			  		<input type="hidden" name="action" value="search" />
				    	<div class="Posted-date">
				      		<h3><?=$langage_lbl['LBL_COMPANY_TRIP_SEARCH_RIDES_POSTED_BY_DATE']; ?></h3>
				      		<span>
				      			<input type="text" id="dp4" name="startDate" placeholder="<?=$langage_lbl['LBL_WALLET_FROM_DATE']; ?>" class="form-control" value=""/>
				      			<input type="text" id="dp5" name="endDate" placeholder="<?=$langage_lbl['LBL_WALLET_TO_DATE']; ?>" class="form-control" value=""/>
					      	</span>
				      	</div>
				    	<div class="time-period">
				      		<h3><?=$langage_lbl['LBL_COMPANY_TRIP_SEARCH_RIDES_POSTED_BY_TIME_PERIOD']; ?></h3>
				      		<span>
								<a onClick="return todayDate('dp4','dp5');"><?=$langage_lbl['LBL_COMPANY_TRIP_Today']; ?></a>
								<a onClick="return yesterdayDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMPANY_TRIP_Yesterday']; ?></a>
								<a onClick="return currentweekDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMPANY_TRIP_Current_Week']; ?></a>
								<a onClick="return previousweekDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMPANY_TRIP_Previous_Week']; ?></a>
								<a onClick="return currentmonthDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMPANY_TRIP_Current_Month']; ?></a>
								<a onClick="return previousmonthDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMPANY_TRIP_Previous Month']; ?></a>
								<a onClick="return currentyearDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMAPNY_TRIP_Current_Year']; ?></a>
								<a onClick="return previousyearDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMPANY_TRIP_Previous_Year']; ?></a>
				      		</span> 
				      		<b><button class="driver-trip-btn"><?=$langage_lbl['LBL_COMPANY_TRIP_Search']; ?></button>
				      		<button onClick="window.location.href='company-trip'" class="driver-trip-btn" type="button"><?=$langage_lbl['LBL_MYTRIP_RESET']; ?></button></b> 
			      		</div>
		      		</form>
			    	<div class="trips-table"> 
			      		<div class="trips-table-inner">
                        <div class="driver-trip-table">
			        		<table width="100%" border="0" cellpadding="0" cellspacing="1" id="dataTables-example">
			          			<thead>
									<tr>
									<?php if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){ ?>

											<th width="8%" class="align-left"><a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)">Service Category <?php if ($sortby == 1) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
										
										<?php }

										 ?>
										 	<th width="8%" class="align-left"><a href="javascript:void(0);" onClick="Redirect(2,<?php if($sortby == '2'){ echo $order; }else { ?>0<?php } ?>)"><?=$langage_lbl['LBL_MYTRIP_RIDE_NO_TXT']; ?> <?php if ($sortby == 2) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>


											<th width="8%" class="align-left"><a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)"><?=$langage_lbl['LBL_Pick_Up']; ?> <?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>

	        								<th width="8%" class="align-left"><a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)"><?=$langage_lbl['LBL_COMPANY_TRIP_DRIVER']; ?> <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>	        								

											<th width="8%" class="align-left"><a href="javascript:void(0);" onClick="Redirect(5,<?php if($sortby == '5'){ echo $order; }else { ?>0<?php } ?>)"><?=$langage_lbl['LBL_COMPANY_TRIP_RIDER']; ?> <?php if ($sortby == 5) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>

											<th width="8%" class="align-left"><a href="javascript:void(0);" onClick="Redirect(6,<?php if($sortby == '6'){ echo $order; }else { ?>0<?php } ?>)"><?=$langage_lbl['LBL_COMPANY_TRIP_Trip_Date']; ?> <?php if ($sortby == 6) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>

<!-- codeEdited for remove fare									

	<th><?=$langage_lbl['LBL_COMPANY_TRIP_FARE_TXT']; ?></th>

 -->					
	<th width="8%" class="align-left"><a href="javascript:void(0);" onClick="Redirect(7,<?php if($sortby == '7'){ echo $order; }else { ?>0<?php } ?>)"><?=$langage_lbl['LBL_COMPANY_TRIP_Car_Type']; ?><?php if ($sortby == 7) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>

<!-- codeEdited for remove invoice											<th><?=$langage_lbl['LBL_COMPANY_TRIP_View_Invoice']; ?></th>
 -->									</tr>
								</thead>
								<tbody>
								<? 
									for($i=0;$i<count($db_trip);$i++)
									{

										$eType2 = $db_trip[$i]['eType'];
										if($eType2 == 'Ride'){
											$trip_type = 'Ride';
										} else if($eType2 == 'UberX') {
											$trip_type = 'Other Services';
										} else {
											$trip_type = 'Delivery';
										}
										$systemTimeZone = date_default_timezone_get();
										if($db_trip[$i]['tTripRequestDate']!= "" && $db_trip[$i]['vTimeZone'] != "")  {
										    $dBookingDate = converToTz($db_trip[$i]['tTripRequestDate'],$db_trip[$i]['vTimeZone'],$systemTimeZone);
										} else {
										    $dBookingDate = $db_trip[$i]['tTripRequestDate'];
										}
								?>
									<tr class="gradeA">
									<!-- <?php if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){ ?>
										<td ><? 
										if($db_trip[$i]['eHailTrip'] == "Yes" && $db_trip[$i]['iRentalPackageId'] > 0){
                                            echo "Rental " . $trip_type."<br/> ( Hail )";
                                        } else if($db_trip[$i]['iRentalPackageId'] > 0){
                                            echo "Rental " . $trip_type;
                                        } else if ($db_trip[$i]['eHailTrip'] == "Yes") {
                                            echo "Hail ".$trip_type;
                                        } else {
                                            echo $trip_type;
                                        }

/*										if($db_trip[$i]['eHailTrip'] != "Yes"){
											echo $trip_type;
										}else{
											echo "Hail ".$trip_type;
										}*/
										?></td>
									<?php } ?> -->

									<td><?=$db_trip[$i]['category_name'];?></td>
										<td align="center"><?=$db_trip[$i]['vRideNo'];?></td>
										<?php if($APP_TYPE == 'UberX'){ ?>
											<td width="25%"><?=$db_trip[$i]['tSaddress'];?></td>
										<?php } else { 
											if(!empty($db_trip[$i]['tDaddress'])) {?>
												<td width="25%"><?=$db_trip[$i]['tSaddress'].' -> '.$db_trip[$i]['tDaddress'];?></td>
											<?php } else { ?>
												<td width="25%"><?=$db_trip[$i]['tSaddress'];?></td>
											<?php }
										} ?> 
										<td>
											<?=$generalobj->clearName($db_trip[$i]['name']." ".$db_trip[$i]['lname']);?>
										</td>
										<td>
											<?=$generalobj->clearName($db_trip[$i]['vName']." ".$db_trip[$i]['vLastName']);?>
										</td>
										<td data-order="<?=$db_trip[$i]['iTripId']?>"><?= date('d-M-Y',strtotime($dBookingDate));?></td>
										<!-- codeEdited for remove fare	
										<td align="center">

											<?php /*if($db_trip[$i]['fCancellationFare'] > 0 || ($db_trip[$i]['iActive'] == "Canceled" && $db_trip[$i]['fWalletDebit'] > 0)){

												$total_main_price = $db_trip[$i]['fCancellationFare'];
											} else {*/
												$total_main_price = ($db_trip[$i]['fTripGenerateFare'] + $db_trip[$i]['fTipPrice'] - $db_trip[$i]['fCommision']- $db_trip[$i]['fTax2']-$db_trip[$i]['fTax1'] - $db_trip[$i]['fOutStandingAmount']);
											//}
												?>
											<?=$generalobj->trip_currency($total_main_price);?>
										</td>-->
										<td align="center">
											<?=$db_trip[$i]['vVehicleType'];?>
										</td>
								<!-- 
                                       codeEdited for remove invoice	
									<?php if($db_trip[$i]['iActive'] == 'Canceled' && $db_trip[$i]['fTripGenerateFare'] <= 0){?>
										<td class="center">
										<img src="assets/img/<?php echo $canceled_icon;?>" title="<?=$langage_lbl['LBL_MYTRIP_CANCELED_TXT']; ?>">
										</td>
									<?php } else if(($db_trip[$i]['iActive'] == 'Finished' && $db_trip[$i]['eCancelled'] == "Yes") || ($db_trip[$i]['iActive'] == 'Canceled' && $db_trip[$i]['fTripGenerateFare'] > 0)) {?>
									<td align="center" width="10%">
									  	<a target = "_blank" href="invoice.php?iTripId=<?=base64_encode(base64_encode($db_trip[$i]['iTripId']))?>">
												<img alt="" src="assets/img/<?php echo $invoice_icon;?>">
										 </a>
										<div style="font-size: 12px;">Cancelled</div>
									</td>
									<? } else {?>	
										<td align="center" width="10%">
										  <a target = "_blank" href="invoice.php?iTripId=<?=base64_encode(base64_encode($db_trip[$i]['iTripId']))?>">
												<img alt="" src="assets/img/<?php echo $invoice_icon;?>">
										 </a>
										</td>
									<?php } ?> -->
									</tr>
								<? } ?>		
								</tbody>
			        		</table>




			      		</div>	</div>

                  <div style="margin-top: 10px;">
                      <?php include_once("pagging.php"); ?>
                              
                                    </div>

			    </div>
			    <!-- -->
			    <? //if(SITE_TYPE=="Demo"){?>
			    <!-- <div class="record-feature"> <span><strong>“Edit / Delete Record Feature”</strong> has been disabled on the Demo Admin Version you are viewing now.
			      This feature will be enabled in the main product we will provide you.</span> </div>
			      <?php //}?> -->
			    <!-- -->
			  </div>
			  <!-- -->
			  <div style="clear:both;"></div>
			</div>

			<form  id="_list_form" action="company-trip" method="post" >
</form>
			<form name="pageForm" id="pageForm" action="" method="post" >
<input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
<input type="hidden" name="tpages" id="tpages" value="<?php echo $tpages; ?>">
<input type="hidden" name="sortby" id="sortby" value="<?php echo $sortby; ?>" >
<input type="hidden" name="order" id="order" value="<?php echo $order; ?>" >
<input type="hidden" name="action" value="<?php echo $action; ?>" >
<input type="hidden" name="searchCompany" value="<?php echo $searchCompany; ?>" >
<input type="hidden" name="searchDriver" value="<?php echo $searchDriver; ?>" >
<input type="hidden" name="searchRider" value="<?php echo $searchRider; ?>" >
<input type="hidden" name="serachTripNo" value="<?php echo $serachTripNo; ?>" >
<input type="hidden" name="startDate" value="<?php echo $startDate; ?>" >
<input type="hidden" name="endDate" value="<?php echo $endDate; ?>" >
<input type="hidden" name="vStatus" value="<?php echo $vStatus; ?>" >
<input type="hidden" name="eType" value="<?php echo $eType; ?>" >
<input type="hidden" name="method" id="method" value="" >
</form>
		</div>
    <!-- footer part -->
    <?php include_once('footer/footer_home.php');?>
    <!-- footer part end -->
        <!-- End:contact page-->
        <div style="clear:both;"></div>
    </div>
    <!-- home page end-->
    <!-- Footer Script -->
    <?php include_once('top/footer_script.php');?>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>

           <link rel="stylesheet" type="text/css" href="admin/css/admin_new/admin_style.css">

    <script type="text/javascript">
         $(document).ready(function () {
         	$( "#dp4" ).datepicker({
         		dateFormat: "yy-mm-dd",
         		changeYear: true,
     		  	changeMonth: true,
     		  	yearRange: "-100:+10"
         	});
         	$( "#dp5" ).datepicker({
         		dateFormat: "yy-mm-dd",
         		changeYear: true,
     		  	changeMonth: true,
     		  	yearRange: "-100:+10"
         	});
			 if('<?=$startDate?>'!=''){
				 $("#dp4").val('<?=$startDate?>');
				 $("#dp4").datepicker('refresh');
			 }
			 if('<?=$endDate?>'!=''){
				 $("#dp5").val('<?= $endDate;?>');
				 $("#dp5").datepicker('refresh');
			 }
			/*<?php  if($APP_TYPE == 'UberX' || $APP_TYPE == 'Delivery'){ ?>
			$('#dataTables-example').DataTable( {
			  "order": [[ 4, "desc" ]]
			    } );
			<?php } else { ?>
			$('#dataTables-example').DataTable( {
			  "order": [[ 5, "desc" ]]
			    } );
			<?php }  ?>*/


            //$('#dataTables-example').dataTable();
			// formInit();
         });
      
		 function todayDate()
		 {
			 $("#dp4").val('<?= $Today;?>');
			 $("#dp5").val('<?= $Today;?>');
		 }
		 function yesterdayDate()
		 {
			 $("#dp4").val('<?= $Yesterday;?>');
			 $("#dp5").val('<?= $Yesterday;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');			 
		 }
		 function currentweekDate(dt,df)
		 {
			 $("#dp4").val('<?= $monday;?>');			 
			 $("#dp5").val('<?= $sunday;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');
		 }
		 function previousweekDate(dt,df)
		 {
			 $("#dp4").val('<?= $Pmonday;?>');
			 $("#dp5").val('<?= $Psunday;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');
		 }
		 function currentmonthDate(dt,df)
		 {
			 $("#dp4").val('<?= $currmonthFDate;?>');
			 $("#dp5").val('<?= $currmonthTDate;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');
		 }
		 function previousmonthDate(dt,df)
		 {
			 $("#dp4").val('<?= $prevmonthFDate;?>');
			 $("#dp5").val('<?= $prevmonthTDate;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');
		 }
		 function currentyearDate(dt,df)
		 {
			 $("#dp4").val('<?= $curryearFDate;?>');
			 $("#dp5").val('<?= $curryearTDate;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');
		 }
		 function previousyearDate(dt,df)
		 {
			 $("#dp4").val('<?= $prevyearFDate;?>');
			 $("#dp5").val('<?= $prevyearTDate;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');
		 }
	 	function checkvalid(){
			 if($("#dp5").val() < $("#dp4").val()){
				 //bootbox.alert("<h4>From date should be lesser than To date.</h4>");
			 	bootbox.dialog({
				 	message: "<h4><?php echo addslashes($langage_lbl['LBL_FROM_TO_DATE_ERROR_MSG']);?></h4>",
				 	buttons: {
				 		danger: {
				      		label: "OK",
				      		className: "btn-danger"
				   	 	}
			   	 	}
		   	 	});
			 	return false;
		 	}
	 	}
    </script>
    
    <script type="text/javascript">
    $(document).ready(function(){
        $("[name='dataTables-example_length']").each(function(){
            $(this).wrap("<em class='select-wrapper'></em>");
            $(this).after("<em class='holder'></em>");
        });
        $("[name='dataTables-example_length']").change(function(){
            var selectedOption = $(this).find(":selected").text();
            $(this).next(".holder").text(selectedOption);
        }).trigger('change');
    })
</script>
    <!-- End: Footer Script -->
</body>
</html>
