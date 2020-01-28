<?php
include_once('../common.php');

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$default_lang 	= $generalobj->get_default_lang();
$script = 'Trips';

$rdr_ssql = "";
if (SITE_TYPE == 'Demo') {
    $rdr_ssql = " And tRegistrationDate > '" . WEEK_DATE . "'";
}



//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
$promocode = isset($_REQUEST['promocode']) ? $_REQUEST['promocode'] : '';
$vStatus = isset($_REQUEST['vStatus']) ? $_REQUEST['vStatus'] : '';

$eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';

$ord = ' ORDER BY t.iTripId DESC';

if($eType == 'Deliver') {

	if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY t.eType ASC";
  else
  $ord = " ORDER BY t.eType DESC";
}	
	
}

	else
	{

		if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY vc.vCategory_".$default_lang." ASC";
  else
  $ord = " ORDER BY vc.vCategory_".$default_lang." DESC";
}
    

	}



if($sortby == 2){

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
  $ord = " ORDER BY c.vCompany ASC";
  else
  $ord = " ORDER BY c.vCompany DESC";
}

if($sortby == 4){
  if($order == 0)
  $ord = " ORDER BY d.vName ASC";
  else
  $ord = " ORDER BY d.vName DESC";
}

if($sortby == 5){
  if($order == 0)
  $ord = " ORDER BY u.vName ASC";
  else
  $ord = " ORDER BY u.vName DESC";
}
//End Sorting


// Start Search Parameters
$ssql='';
$ssqlCompany='';
$action = isset($_REQUEST['action']) ? $_REQUEST['action']: '';
$searchCompany = isset($_REQUEST['searchCompany']) ? $_REQUEST['searchCompany'] : '';
$searchDriver = isset($_REQUEST['searchDriver']) ? $_REQUEST['searchDriver'] : '';
$searchRider = isset($_REQUEST['searchRider']) ? $_REQUEST['searchRider'] : '';
$serachTripNo = isset($_REQUEST['serachTripNo']) ? $_REQUEST['serachTripNo'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';


if($startDate!=''){

	if($vStatus == "Decline"||$vStatus == "Timeout"||$vStatus == "Yes" || $vStatus == "InProcess")
			 {
	$ssql.=" AND Date(t.dAddedDate) >='".$startDate."'";


				}else{

	$ssql.=" AND Date(t.tTripRequestDate) >='".$startDate."'";
}
}
if($endDate!=''){
	if($vStatus == "Decline"||$vStatus == "Timeout"||$vStatus == "Yes" || $vStatus == "InProcess")
			 {
			 		$ssql.=" AND Date(t.dAddedDate) <='".$endDate."'";

				}else{
	$ssql.=" AND Date(t.tTripRequestDate) <='".$endDate."'";
}
}
if($serachTripNo!=''){
	$ssql.=" AND t.vRideNo ='".$serachTripNo."'";
}
if($searchCompany!=''){
	$ssql.=" AND d.iCompanyId ='".$searchCompany."'";

	$ssqlCompany=" and iCompanyId ='".$searchCompany."'";
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
if($searchRider!=''){
	$ssql.=" AND t.iUserId ='".$searchRider."'";
}
$join="left join vehicle_type vt on vt.iVehicleTypeId=t.iVehicleTypeId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId ";
$SELECT=",vt.vVehicleType_".$default_lang."  as vVehicleType,vc.vCategory_".$default_lang." as sub_category_name,vcp.vCategory_".$default_lang." as category_name,t.eType";

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


echo  $_REQUEST['tpages'];


/*
	if($eType == 'Ride'){

		if($vStatus == "Decline"||$vStatus == "Timeout"||$vStatus == "Yes" || $vStatus == "InProcess"){
			
    $ssql .= " AND   t.eType = '".$eType."' ";
}else{
		$ssql.=" AND t.eType ='".$eType."' AND t.iRentalPackageId = 0 AND t.eHailTrip = 'No' ";
	}
	} elseif($eType == 'RentalRide'){
		$ssql.=" AND t.eType ='Ride' AND t.iRentalPackageId > 0";
	} elseif($eType == 'HailRide'){
		$ssql.=" AND t.eType ='Ride' AND t.eHailTrip = 'Yes'";
	} else if($eType == 'Deliver') {

		
		$ssql.=" AND t.eType ='".$eType."' ";
	
}

	else
	{
    $ssql .= " AND   vt.iVehicleTypeId = '".$eType."' ";
    

	}*/
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







//data for select fields
$sql = "select iCompanyId,vCompany from company WHERE eStatus != 'Deleted' $rdr_ssql";
$db_company = $obj->MySQLSelect($sql);

$sql = "select iDriverId,CONCAT(vName,' ',MiddleName,' ',vLastName) AS driverName,vEmail from register_driver WHERE eStatus != 'Deleted'  $ssqlCompany $rdr_ssql order by driverName";
$db_drivers = $obj->MySQLSelect($sql);

$sql = "select iUserId,CONCAT(vName,' ',MiddleName,' ',vLastName) AS riderName,vEmail from register_user WHERE eStatus != 'Deleted' $rdr_ssql";
$db_rider = $obj->MySQLSelect($sql);
//data for select fields



if(!empty($promocode) && isset($promocode)) {
	$ssql .= " AND t.vCouponCode LIKE '".$promocode."' AND t.iActive !='Canceled'";
}
$trp_ssql = "";
if(SITE_TYPE =='Demo'){
	$trp_ssql = " And t.tTripRequestDate > '".WEEK_DATE."'";
}

//Pagination Start
$per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page


if($vStatus == "Decline"||$vStatus == "Timeout"||$vStatus == "Yes"||$vStatus == "InProcess")
	{
		$sql = "SELECT COUNT(t.iCabRequestId) AS Total FROM register_driver d left join driver_request rs on d.iDriverId=rs.iDriverId left join company c on d.iCompanyId=c.iCompanyId left join cab_request_now t on rs.iRequestId=t.iCabRequestId  $join LEFT JOIN  register_user u ON t.iUserId = u.iUserId WHERE 1=1 $ssql $trp_ssql";



	}else{
$sql = "SELECT COUNT(t.iTripId) AS Total  FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  WHERE 1=1 $ssql $trp_ssql";


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
//Pagination End
if($vStatus == "Decline"||$vStatus == "Timeout"||$vStatus == "Yes" || $vStatus=="InProcess")
	{

$sql="SELECT  d.iDriverId  ,c.vCompany,CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName, CONCAT(d.vName,' ',d.MiddleName,' ',d.vLastName) AS driverName,u.iUserId,t.tSourceAddress as tSaddress,t.tDestAddress as tDaddress,t.dAddedDate as tTripRequestDate $SELECT FROM register_driver d left join driver_request rs on d.iDriverId=rs.iDriverId left join company c on d.iCompanyId=c.iCompanyId left join cab_request_now t on rs.iRequestId=t.iCabRequestId  $join LEFT JOIN  register_user u ON t.iUserId = u.iUserId WHERE 1=1 $ssql $trp_ssql group by t.iCabRequestId $ord LIMIT $start, $per_page";

	}else{
$sql = "SELECT t.tStartDate ,t.tEndDate, t.tTripRequestDate,t.vCancelReason,t.vCancelComment,t.eHailTrip,t.iUserId,t.iFare,t.eType,d.iDriverId, t.tSaddress,t.vRideNo,t.tDaddress, t.fWalletDebit,t.eCarType,t.iTripId,t.iActive, t.fCancellationFare,t.eCancelled, t.iRentalPackageId ,CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName,CONCAT(d.vName,' ',d.MiddleName,' ',d.vLastName)AS driverName, d.vAvgRating, c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,t.iTripId,vc.vCategory_".$default_lang." as sub_category_name,vcp.vCategory_".$default_lang." as category_name FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  WHERE 1=1 $ssql $trp_ssql $ord LIMIT $start, $per_page";
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
?>
<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?=$SITE_NAME?> | <?php echo $langage_lbl_admin['LBL_TRIPS_TXT_ADMIN'];?></title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <?php include_once('global_files.php');?>
            <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
    </head>
    <!-- END  HEAD-->
    
    <!-- BEGIN BODY-->
    <body class="padTop53 " >
        <!-- Main LOading -->
        <!-- MAIN WRAPPER -->
        <div id="wrap">
            <?php include_once('header.php'); ?>
            <?php include_once('left_menu.php'); ?>
            <!--PAGE CONTENT -->
            <div id="content">
                <div class="inner">
                    <div id="add-hide-show-div">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2><?php echo $langage_lbl_admin['LBL_TRIPS_TXT_ADMIN'];?> </h2>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <?php include('valid_msg.php'); ?>
					<form name="frmsearch" id="frmsearch" action="javascript:void(0);" method="post" >
						<div class="Posted-date mytrip-page">
							<input type="hidden" name="action" value="search" />
							<h3>Search <?php echo $langage_lbl_admin['LBL_TRIPS_TXT_ADMIN'];?> ...</h3>
							<span>
							<a style="cursor:pointer" onClick="return todayDate('dp4','dp5');"><?=$langage_lbl_admin['LBL_MYTRIP_Today']; ?></a>
							<a style="cursor:pointer" onClick="return yesterdayDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Yesterday']; ?></a>
							<a style="cursor:pointer" onClick="return currentweekDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Current_Week']; ?></a>
							<a style="cursor:pointer" onClick="return previousweekDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Previous_Week']; ?></a>
							<a style="cursor:pointer" onClick="return currentmonthDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Current_Month']; ?></a>
							<a style="cursor:pointer" onClick="return previousmonthDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Previous Month']; ?></a>
							<a style="cursor:pointer" onClick="return currentyearDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Current_Year']; ?></a>
							<a style="cursor:pointer" onClick="return previousyearDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Previous_Year']; ?></a>
							</span> 
							<span>
							<input type="text" id="dp4" name="startDate"  placeholder="From Date" class="form-control" value="" readonly=""style="cursor:default; background-color: #fff" />
							<input type="text" id="dp5" name="endDate"  placeholder="To Date" class="form-control" value="" readonly="" style="cursor:default; background-color: #fff"/>
							
							<div class="col-lg-3">
								  <select class="form-control filter-by-001" name = 'vStatus' id="vStatus" >
									   <option value="">All</option>
									   <option value="onRide" <?php if($vStatus == "onRide") { echo "selected"; } ?>>On Going <?php echo $langage_lbl_admin['LBL_RIDE_TXT_ADMIN'];?> </option>
									   <option value="complete" <?php if($vStatus == "complete") { echo "selected"; } ?>>Completed</option>
									   <option value="cancel" <?php if($vStatus == "cancel") { echo "selected"; } ?>>Cancelled</option>

									   <option value="Decline" <?php if($vStatus == "Decline") { echo "selected"; } ?>>Declined</option>			
									    <option value="Timeout" <?php if($vStatus == "Timeout") { echo "selected"; } ?>>Timeout</option>
									  <option value="Yes" <?php if($vStatus == "Yes") { echo "selected"; } ?>>Missed</option>

									  <option value="InProcess" <?php if($vStatus == "InProcess") { echo "selected"; } ?>>In Process </option>


								  </select>
							</div>
							<div class="col-lg-2">
								  <input type="text" id="serachTripNo" name="serachTripNo" placeholder="<?php echo $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'];?> Number" class="form-control search-trip001" value="<?php echo $serachTripNo; ?>"/>
							</div>
							</span>
						</div>

						<div class="row">
						
						<div class="col-lg-3">
							<select class="form-control filter-by-text" name = 'searchCompany' id="searchCompany" data-text="Select Company">
							   <option value="">Select Company</option>
							   <?php foreach($db_company as $dbc){ ?>
							   <option value="<?php echo $dbc['iCompanyId']; ?>" <?php if($searchCompany == $dbc['iCompanyId']) { echo "selected"; } ?>><?php echo $generalobjAdmin->clearCmpName($dbc['vCompany']); ?></option>
							   <?php } ?>
							</select>
						</div>
						<div class="col-lg-3">
							<select class="form-control filter-by-text driver_container" id="searchDriver" name = 'searchDriver' data-text="Select <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?>">
							   <option value="">Select <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?></option>
							   <?php foreach($db_drivers as $dbd){ ?>
							   <option value="<?php echo $dbd['iDriverId']; ?>" <?php if($searchDriver == $dbd['iDriverId']) { echo "selected"; } ?>><?php echo $generalobjAdmin->clearName($dbd['driverName']." (".$dbd['vEmail'].")"); ?></option>
							   <?php } ?>
							</select>
						</div>
						<div class="col-lg-3">
							<select class="form-control filter-by-text" id="searchRider" name = 'searchRider' data-text="Select <?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?>">
								<option value="">Select <?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?></option>
							   <?php foreach($db_rider as $dbr){ ?>
							   <option value="<?php echo $dbr['iUserId']; ?>" <?php if($searchRider == $dbr['iUserId']) { echo "selected"; } ?>><?php echo $generalobjAdmin->clearName($dbr['riderName'])." (".$dbr['vEmail'].")"; ?></option>
							   <?php } ?>
							</select>
						</div>
						<?php if($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'Ride-Delivery'){ ?>
					<!-- 	<div class="col-lg-2">
							  <select class="form-control" name = 'eType' >
								   <option value="">Service Type</option>
								   <option value="Ride" <?php if($eType == "Ride") { echo "selected"; } ?>><?php echo $langage_lbl_admin['LBL_RIDE_TXT_ADMIN_SEARCH'];?> </option>
								   <option value="HailRide" <?php if($eType == "HailRide") { echo "selected"; } ?>> Hail <?php echo $langage_lbl_admin['LBL_RIDE_TXT_ADMIN_SEARCH'];?> </option>
								   <? if(ENABLE_RENTAL_OPTION == 'Yes') { ?>
								   <option value="RentalRide" <?php if($eType == "RentalRide") { echo "selected"; } ?>>Rental <?php echo $langage_lbl_admin['LBL_RIDE_TXT_ADMIN_SEARCH'];?> </option>
								   <?php } ?>
								   <option value="Deliver" <?php if($eType == "Deliver") { echo "selected"; } ?>>Delivery</option>
								   <option value="UberX" <?php if($eType == "UberX") { echo "selected"; } ?>>Other Services</option>
							  </select>
						</div> -->

             <div class="col-lg-3 select001">


 <select  class="form-control miltiselect" data-text="Service type" name='eType' id="eType" multiple="multiple" >
                                       

                                                <option value='Ride'   <?php
$Scompany= explode(',', $eType);

  for ($i=0; $i <count($Scompany) ; $i++) { 


 if (trim($Scompany[$i])=='Ride') {
 echo 'selected';
}
}?> >Ride</option>
                                                <option value='Deliver'  <?php
  for ($i=0; $i <count($Scompany) ; $i++) { 


 if (trim($Scompany[$i])=='Deliver') {
 echo 'selected';
}
}?> >Delivery</option>

      <?
 // $vehicle_type_sql1 = "SELECT vt.*,vc.*,lm.vLocationName FROM vehicle_type AS vt LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId LEFT JOIN location_master AS lm ON lm.iLocationId = vt.iLocationid WHERE vt.eType='UberX' AND  vc.eStatus='Active' order by vt.vVehicleType_EN";
       $vehicle_type_sql1 = "SELECT * FROM  vehicle_category  WHERE    eStatus='Active'  and `iParentId`=0 order by vCategory_EN";
          $vehicle_type= $obj->MySQLSelect($vehicle_type_sql1);
//$Scompany=explode(',', $eType);

        foreach ($vehicle_type as $subkey => $subvalue) {
$isSelected="";

  for ($i=0; $i <count($Scompany) ; $i++) { 


 if (trim($Scompany[$i])==$subvalue['iVehicleCategoryId']) {
           $isSelected="selected";
}
}


           echo "<option value='".$subvalue['iVehicleCategoryId']."' $isSelected>".$subvalue['vCategory_EN']."</option>";

}         

       ?>
                                               
                      </select>

                           <!--   <select  class="form-control filter-by-text" data-text="Service type" name='eType' id="eType" >
                         <option value=''>Service type</option>

                                                <option value='Ride' <?php  if ($eType=='Ride')  echo 'selected'; ?>>Ride</option>
                                                <option value='Deliver' <?php  if ($eType=='Deliver') echo 'selected'; ?>>Delivery</option>

      <?
  $vehicle_type_sql1 = "SELECT vt.*,vc.*,lm.vLocationName FROM vehicle_type AS vt LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId LEFT JOIN location_master AS lm ON lm.iLocationId = vt.iLocationid WHERE vt.eType='UberX' AND  vc.eStatus='Active' order by vt.vVehicleType_EN";
          $vehicle_type= $obj->MySQLSelect($vehicle_type_sql1);

        foreach ($vehicle_type as $subkey => $subvalue) {
$isSelected="";
          if ($eType==$subvalue['iVehicleTypeId']) {
           $isSelected="selected";
                     }
           echo "<option value='".$subvalue['iVehicleTypeId']."' $isSelected>".$subvalue['vVehicleType_EN']." (".$subvalue['vCategory_EN'].")</option>";

}         

       ?>
                                               
                      </select> -->
            </div>


						<?php } ?>



               
						</div>
					<div class="tripBtns001"><b>
					<input type="submit" value="Search" class="btnalt button11" id="Search" name="Search" title="Search" />
					<input type="button" value="Reset" class="btnalt button11" onClick="window.location.href='trip.php'"/></b>
					</div>
					</form>
					<div class="table-list">
						<div class="row">
							<div class="col-lg-12">
									<div class="table-responsive">
										
										<form class="_list_form" id="_list_form" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
										<table class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
												<?php if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){ ?>

<th width="10%" class="align-left">Service Category</th>
													<th width="10%" class="align-left"><?php echo $langage_lbl_admin['LBL_VEHICLE_SUB_CATEGORY_TXT_ADMIN'];?></th>
													<!-- <th width="10%" class="align-left"><a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)"><?php echo $langage_lbl_admin['LBL_VEHICLE_SUB_CATEGORY_TXT_ADMIN'];?><?php if ($sortby == 1) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th> -->
													<?php } ?> 
													<th class="align-center"><?php echo $langage_lbl_admin['LBL_TRIP_NO_ADMIN'];?></th>
													<th>Address</th>
													<th width="8%" class="align-left"><a href="javascript:void(0);" onClick="Redirect(2,<?php if($sortby == '2'){ echo $order; }else { ?>0<?php } ?>)"><?php echo $langage_lbl_admin['LBL_TRIP_DATE_ADMIN'];?> <?php if ($sortby == 2) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
													
													<th width="12%"><a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)">Company <?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
													<th width="12%"><a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)"><?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
													<th width="12%"><a href="javascript:void(0);" onClick="Redirect(5,<?php if($sortby == '5'){ echo $order; }else { ?>0<?php } ?>)"><?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?> <?php if ($sortby == 5) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
												<!-- codeEdited for remove fare
												 	<th width="8%" class="align-right"><?php echo $langage_lbl_admin['LBL_DRIVER_TRIP_FARE_TXT'];?></th> -->
													<th class="align-center">Service Type<!-- <?php echo $langage_lbl_admin['LBL_TEXI_ADMIN'];?>  --></th>
												<!-- codeEdited for remove invoice	<th class="align-center">View Invoice</th> -->
												</tr>
											</thead>
											<tbody>
												<? if(!empty($db_trip)) {
												for($i=0;$i<count($db_trip);$i++)
												{
														$eTypenew = $db_trip[$i]['eType'];
														$category='--';
														if($eTypenew == 'Ride'){
															$trip_type = 'Ride';
															$category='Ride';

														} else if($eTypenew == 'UberX') {

														$trip_type= $db_trip[$i]['sub_category_name'];
														$category= $db_trip[$i]['category_name'];
														
														} else if($eTypenew=='Deliver') {
															$trip_type = 'Delivery';
															$category=  'Delivery';
														}
														else {
														$trip_type= $db_trip[$i]['sub_category_name'];
															$category= $db_trip[$i]['category_name'];
														

														}
														?>
														<tr class="gradeA">
															<td><?=$category;?></td>
														<?php if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){ ?> 
															<td align="left">
															<?  if($db_trip[$i]['eHailTrip'] == "Yes" && $db_trip[$i]['iRentalPackageId'] > 0){
																	echo "Rental " . $trip_type."<br/> ( Hail )";
																} else if($db_trip[$i]['iRentalPackageId'] > 0){
																	echo "Rental " . $trip_type;
																} else if ($db_trip[$i]['eHailTrip'] == "Yes") {
																	echo "Hail ".$trip_type;
																} else {
																	echo $trip_type;
																} 
																?>
															</td>
															<?php } ?> 
															<td align="center">
															 <a href="invoice.php?iTripId=<?=$db_trip[$i]['iTripId'];?>" target="_blank">	<?=$db_trip[$i]['vRideNo'];?></a>
															</td>
															<td width="30%"><? echo $db_trip[$i]['tSaddress'];
															if($APP_TYPE != "UberX" && !empty($db_trip[$i]['tDaddress'])){echo ' -> '.$db_trip[$i]['tDaddress']; } ?>
															</td>
															<!--<td><?= date('d F, Y',strtotime($db_trip[$i]['tStartDate']));?></td>-->
															<!-- <td align="center"><?if($db_trip[$i]['tStartDate']=="0000-00-00 00:00:00"){echo date('d-F-Y',strtotime($db_trip[$i]['tTripRequestDate']));}else{echo date('d-F-Y',strtotime($db_trip[$i]['tStartDate']));}?></td>-->
															<td align="center"><?php echo date('d-F-Y',strtotime($db_trip[$i]['tTripRequestDate'])); ?></td>
															<td> 
																<?=$generalobjAdmin->clearCmpName($db_trip[$i]['vCompany']);?>
															</td>
															<td>
																<a href="driver_action.php?id=<?=$db_trip[$i]['iDriverId']?>" target="_blank"><?=$generalobjAdmin->clearName($db_trip[$i]['driverName']);?></a>
															</td>

															<td>
																<? if($db_trip[$i]['eHailTrip'] != "Yes"){?>
																	<a href="rider_action.php?id=<?=$db_trip[$i]['iUserId']?>" target="_blank"><?=$generalobjAdmin->clearName($db_trip[$i]['riderName']);?></a>
																<? } else{
																	echo " ---- ";
																} ?>
															</td>
														<!-- codeEdited for remove fare	<td align="right">
															<?php if($db_trip[$i]['fCancellationFare'] > 0) {
																echo $generalobj->trip_currency($db_trip[$i]['fCancellationFare']);
															} else { 
																echo $generalobj->trip_currency($db_trip[$i]['iFare']);
															} ?>
															</td> -->
															<td align="center">
																<?=$db_trip[$i]['vVehicleType'];?>
															</td>
															<!-- <td align="center" width="10%">
															<?if(($db_trip[$i]['iActive'] == 'Finished' && $db_trip[$i]['eCancelled'] == "Yes") || ($db_trip[$i]['fCancellationFare'] > 0) || ($db_trip[$i]['iActive'] == 'Canceled' && $db_trip[$i]['fWalletDebit'] > 0)) {?>
															  	<button class="btn btn-primary" onclick='return !window.open("invoice.php?iTripId=<?=$db_trip[$i]['iTripId']?>","_blank")';">
																	<i class="icon-th-list icon-white"><b>View Invoice</b></i>
																</button>
																<div style="font-size: 12px;">Cancelled</div>
															<? } else if($db_trip[$i]['iActive'] == 'Finished') { ?>
																
																<button class="btn btn-primary" onclick='return !window.open("invoice.php?iTripId=<?=$db_trip[$i]['iTripId']?>","_blank")';">
																	<i class="icon-th-list icon-white"><b>View Invoice</b></i>
																</button>
															
															<?php }else {
																if($db_trip[$i]['iActive']== "Active" OR $db_trip[$i]['iActive']== "On Going Trip")
																{
																	if($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') {
																		echo "On Job";
																	} else {
																		echo "On Ride";
																	}
																} else if($db_trip[$i]['iActive']== "Canceled"  && $db_trip[$i]['vCancelReason'] != '' ) { ?>
																	<a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#uiModal1_<?=$db_trip[$i]['iTripId'];?>">Cancel Reason</a>
																	
																<?php } else if($db_trip[$i]['iActive']== "Canceled" && $db_trip[$i]['fWalletDebit'] < 0) {

																	echo "Cancelled"; ?>
																	
																	
															<?php 	} else {
																	echo $db_trip[$i]['iActive'];
																}
															} ?>
															</td> -->
														</tr>
														  <div class="clear"></div>
														 <div class="modal fade" id="uiModal1_<?=$db_trip[$i]['iTripId'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
															  <div class="modal-content image-upload-1" style="width:400px;">
																   <div class="upload-content" style="width:350px;">
																		<h3><?=$langage_lbl_admin['LBL_RIDE_TXT_ADMIN'];?> Cancel Reason</h3>	
																		<h4>Cancel Reason: <?=stripcslashes($db_trip[$i]['vCancelReason']." ".$db_trip[$i]['vCancelComment']);?></h4>
																		<input type="button" class="save" data-dismiss="modal" name="cancel" value="Close">
																   </div>
															  </div>
														 </div>
												<?php } }else { ?>
													<tr class="gradeA">
														<td colspan="10"> No Records Found.</td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
										</form>
										<?php include('pagination_n.php'); ?>
									</div>
							</div>
						</div>
					</div>
                <div class="clear"></div>
			</div>
        </div>
       <!--END PAGE CONTENT -->
    </div>
    <!--END MAIN WRAPPER -->

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
	<? include_once('footer.php');?>
	<link rel="stylesheet" href="../assets/plugins/datepicker/css/datepicker.css" />
	<link rel="stylesheet" href="css/select2/select2.min.css" />
	<script src="js/plugins/select2.min.js"></script>
	<script src="../assets/js/jquery-ui.min.js"></script>
	<script src="../assets/plugins/datepicker/js/bootstrap-datepicker.js"></script>
    <script>
			$('#dp4').datepicker()
            .on('changeDate', function (ev) {
                if (ev.date.valueOf() < endDate.valueOf()) {
                    $('#alert').show().find('strong').text('The start date can not be greater then the end date');
                } else {
                    $('#alert').hide();
                    startDate = new Date(ev.date);
                    $('#startDate').text($('#dp4').data('date'));
                }
                $('#dp4').datepicker('hide');
            });
			$('#dp5').datepicker()
            .on('changeDate', function (ev) {
                if (ev.date.valueOf() < startDate.valueOf()) {
                    $('#alert').show().find('strong').text('The end date can not be less then the start date');
                } else {
                    $('#alert').hide();
                    endDate = new Date(ev.date);
                    $('#endDate').text($('#dp5').data('date'));
                }
                $('#dp5').datepicker('hide');
            });
	
         $(document).ready(function () {
			 if('<?=$startDate?>'!=''){
				 $("#dp4").val('<?=$startDate?>');
				 $("#dp4").datepicker('update' , '<?=$startDate?>');
			 }
			 if('<?=$endDate?>'!=''){
				 $("#dp5").datepicker('update' , '<?= $endDate;?>');
				 $("#dp5").val('<?= $endDate;?>');
			 }
			 
         });
		 
		 function setRideStatus(actionStatus) {
			 window.location.href = "trip.php?type="+actionStatus;
		 }
		 function todayDate()
		 {
			 $("#dp4").val('<?= $Today;?>');
			 $("#dp5").val('<?= $Today;?>');
		 }
		 function reset() {
			location.reload();
			
		}	
		 function yesterdayDate()
		 {
			 $("#dp4").val('<?= $Yesterday;?>');
			 $("#dp4").datepicker('update' , '<?= $Yesterday;?>');
			 $("#dp5").datepicker('update' , '<?= $Yesterday;?>');
			 $("#dp4").change();
			 $("#dp5").change();
			 $("#dp5").val('<?= $Yesterday;?>');
		 }
		 function currentweekDate(dt,df)
		 {
			 $("#dp4").val('<?= $monday;?>');
			 $("#dp4").datepicker('update' , '<?= $monday;?>');
			 $("#dp5").datepicker('update' , '<?= $sunday;?>');
			 $("#dp5").val('<?= $sunday;?>');
		 }
		 function previousweekDate(dt,df)
		 {
			 $("#dp4").val('<?= $Pmonday;?>');
			 $("#dp4").datepicker('update' , '<?= $Pmonday;?>');
			 $("#dp5").datepicker('update' , '<?= $Psunday;?>');
			 $("#dp5").val('<?= $Psunday;?>');
		 }
		 function currentmonthDate(dt,df)
		 {
			 $("#dp4").val('<?= $currmonthFDate;?>');
			 $("#dp4").datepicker('update' , '<?= $currmonthFDate;?>');
			 $("#dp5").datepicker('update' , '<?= $currmonthTDate;?>');
			 $("#dp5").val('<?= $currmonthTDate;?>');
		 }
		 function previousmonthDate(dt,df)
		 {
			 $("#dp4").val('<?= $prevmonthFDate;?>');
			 $("#dp4").datepicker('update' , '<?= $prevmonthFDate;?>');
			 $("#dp5").datepicker('update' , '<?= $prevmonthTDate;?>');
			 $("#dp5").val('<?= $prevmonthTDate;?>');
		 }
		 function currentyearDate(dt,df)
		 {
			 $("#dp4").val('<?= $curryearFDate;?>');
			 $("#dp4").datepicker('update' , '<?= $curryearFDate;?>');
			 $("#dp5").datepicker('update' , '<?= $curryearTDate;?>');
			 $("#dp5").val('<?= $curryearTDate;?>');
		 }
		 function previousyearDate(dt,df)
		 {
			 $("#dp4").val('<?= $prevyearFDate;?>');
			 $("#dp4").datepicker('update' , '<?= $prevyearFDate;?>');
			 $("#dp5").datepicker('update' , '<?= $prevyearTDate;?>');
			 $("#dp5").val('<?= $prevyearTDate;?>');
		 }
		$("#Search").on('click', function(){
			 if($("#dp5").val() < $("#dp4").val()){
				 alert("From date should be lesser than To date.")
				 return false;
			 }else {
			 //	$("#frmsearch").submit();

			 var	startDate=$("#dp4").val();
            var endDate=$("#dp5").val();
             var vStatus=$("#vStatus").val();
            var serachTripNo=$("#serachTripNo").val();
            var searchCompany=$("#searchCompany").val();
            var searchRider=$("#searchRider").val();
               var searchDriver=$("#searchDriver").val();
                var eType=$("#eType").val();

//startDate=typeof startDate=='undefined'?'':startDate;
//endDate=typeof endDate=='undefined'?'':endDate;

                post('trip.php',{startDate:startDate,endDate:endDate,vStatus:vStatus,serachTripNo:serachTripNo,searchCompany:searchCompany,searchRider:searchRider,searchDriver:searchDriver,eType:eType});
				//var action = $("#_list_form").attr('action');
              //  var formValus = $("#frmsearch").serialize();
              //  window.location.href = action+"?"+formValus;
			 }
		});
		$(function () {
		  $("select.filter-by-text").each(function(){
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
		 $("#eType").multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"275px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Service Category',
    maxHeight:400
  });


function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}
    </script>
</body>
<!-- END BODY-->
</html>
<style type="text/css">
	button.multiselect.dropdown-toggle.btn.btn-default {
    height: 40px;
}

</style>
