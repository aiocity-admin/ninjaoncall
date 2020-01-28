<?php
include_once('../common.php');
?><script type="text/javascript">
  //calculate the time before calling the function in window.onload
  var date1=new Date();
var beforeload = date1.getTime();
var loadtime=0;
function getPageLoadTime() {
  //calculate the current time in afterload
    var date2=new Date();

  var afterload = date2.getTime();
  // now use the beforeload and afterload to calculate the seconds
  seconds = (afterload - beforeload) / 1000;
  // Place the seconds in the innerHTML to show the results
 // $("#load_time").text('Loaded in  ' + seconds + ' sec(s).');
 loadtime=seconds;
date1= date1.getFullYear() + '-' +
    ('00' + (date1.getMonth()+1)).slice(-2) + '-' +
    ('00' + date1.getDate()).slice(-2) + ' ' + 
    ('00' + date1.getHours()).slice(-2) + ':' + 
    ('00' + date1.getMinutes()).slice(-2) + ':' + 
    ('00' + date1.getSeconds()).slice(-2);

    date2= date2.getFullYear() + '-' +
    ('00' + (date2.getMonth()+1)).slice(-2) + '-' +
    ('00' + date2.getDate()).slice(-2) + ' ' + 
    ('00' + date2.getHours()).slice(-2) + ':' + 
    ('00' + date2.getMinutes()).slice(-2) + ':' + 
    ('00' + date2.getSeconds()).slice(-2);

 $.ajax({
           type: "POST",
           url: "../LoadingTime/loadtime.php",
           data: {"loadtime":seconds,"beforeload":date1,"afterload":date2,"UserType":"SUPER_ADMIN","eType":"STATISTICS_TOP_PERFORMANCE"}, 
           success: function(data)
           {
               
           }
         });

}
</script>
<?

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$default_lang 	= $generalobj->get_default_lang();
$script = 'statisticsReportTopPerformers';

$rdr_ssql = "";
if (SITE_TYPE == 'Demo') {
    $rdr_ssql = " And tRegistrationDate > '" . WEEK_DATE . "'";
}


//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 1;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 1;
$promocode = isset($_REQUEST['promocode']) ? $_REQUEST['promocode'] : '';
$maxlimit= isset($_REQUEST['maxlimit']) ? $_REQUEST['maxlimit'] : '';
$reportType= isset($_REQUEST['reportType']) ? $_REQUEST['reportType'] : 'All';

$ord = '';
if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY numberofservice ASC";
  else
  $ord = " ORDER BY numberofservice DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY t.tTripRequestDate ASC";
  else
  $ord = " ORDER BY t.tTripRequestDate DESC";
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
  $ord = " ORDER BY vcp.vCategory_EN ASC";
  else
  $ord = " ORDER BY vcp.vCategory_EN DESC";
}

if($sortby == 6){
  if($order == 0)
  $ord = " ORDER BY d.vEmail ASC";
  else
  $ord = " ORDER BY d.vEmail DESC";
}


//End Sorting


// Start Search Parameters
$ssql='';
$ssqlCom='';
$action = isset($_REQUEST['action']) ? $_REQUEST['action']: '';
$searchCompany = isset($_REQUEST['searchCompany']) ? $_REQUEST['searchCompany'] : '';
$searchDriver = isset($_REQUEST['searchDriver']) ? $_REQUEST['searchDriver'] : '';
$searchRider = isset($_REQUEST['searchRider']) ? $_REQUEST['searchRider'] : '';
$serachTripNo = isset($_REQUEST['serachTripNo']) ? $_REQUEST['serachTripNo'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
$vStatus = isset($_REQUEST['vStatus']) ? $_REQUEST['vStatus'] : 'complete';
$eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';
if($startDate!=''){
	$ssql.=" AND Date(t.tTripRequestDate) >='".$startDate."'";
}
if($endDate!=''){
	$ssql.=" AND Date(t.tTripRequestDate) <='".$endDate."'";
}
if($serachTripNo!=''){
	$ssql.=" AND t.vRideNo ='".$serachTripNo."'";
}


if($searchCompany!=''){




 if (trim($searchCompany)!='null')
{

 $company= explode(',', $searchCompany);
  $subQuery_company='(';

  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" d.iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";

}

	$ssql.=$subQuery;//" AND d.iCompanyId ='".$searchCompany."'";

}
if($searchDriver!=''){
	$ssql.=" AND t.iDriverId ='".$searchDriver."'";
}
if($searchRider!=''){
	$ssql.=" AND t.iUserId ='".$searchRider."'";
}
if($vStatus == "onRide") {
	$ssql .= " AND (t.iActive = 'On Going Trip' OR t.iActive = 'Active') AND t.eCancelled='No'";
}else if($vStatus == "cancel") {
	$ssql .= " AND (t.iActive = 'Canceled' OR t.eCancelled='yes')";
}else if($vStatus == "complete") {
	$ssql .= " AND t.iActive = 'Finished' AND t.eCancelled='No'";
}

if($eType!=''){


 if (trim($eType)!='null')
{

 $eType= explode(',', $eType);
  $subQuery_company='(';

  for ($i=0; $i <count($eType) ; $i++) { 


        if($eType[$i] == 'Ride'){
    $subQuery_company.="  t.eType ='".$eType[$i]."'  or";
  }
   else if($eType[$i] == 'Deliver') {
    $subQuery_company.="  t.eType ='".$eType[$i]."'  or";
  }else {
    $subQuery_company.="  vcp.iVehicleCategoryId ='".$eType[$i]."'  or";
  }

  //$subQuery_company.=" d.iCompanyId='$eType[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');

 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";

}

$ssql.=$subQuery;
	/*	if($eType == 'Ride'){
		$ssql.=" AND t.eType ='".$eType."' AND t.iRentalPackageId = 0 AND t.eHailTrip = 'No' ";
	}
   else if($eType == 'Deliver') {
		$ssql.=" AND t.eType ='".$eType."' ";
	}else {
		$ssql.=" AND vt.iVehicleTypeId ='".$eType."' ";
	}*/
	
}
$having="";
$having2="";
if($maxlimit!="")
{

  $having = " HAVING numberofservice >= $maxlimit ";
  $having2=" HAVING Total >= $maxlimit ";


  
}

if(!empty($promocode) && isset($promocode)) {
	$ssql .= " AND t.vCouponCode LIKE '".$promocode."' AND t.iActive !='Canceled'";
}
$trp_ssql = "";
if(SITE_TYPE =='Demo'){
	$trp_ssql = " And t.tTripRequestDate > '".WEEK_DATE."'";
}





//data for select fields
$sql = "select iCompanyId,vCompany from company WHERE eStatus != 'Deleted' $rdr_ssql";
$db_company = $obj->MySQLSelect($sql);

$sql = "select iDriverId,CONCAT(vName,' ',MiddleName,' ',vLastName) AS driverName,vEmail from register_driver d WHERE eStatus != 'Deleted' $ssqlCom $rdr_ssql order by vName";
$db_drivers = $obj->MySQLSelect($sql);

$sql = "select iUserId,CONCAT(vName,' ',MiddleName,' ',vLastName) AS riderName from register_user WHERE eStatus != 'Deleted' $rdr_ssql";
$db_rider = $obj->MySQLSelect($sql);
//data for select fields

//Pagination Start
$per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page

/*if($reportType=="All")
{*/
$sql = "SELECT count(Total) as Total from (SELECT COUNT(distinct t.iTripId) AS Total  FROM trips t right JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql group by d.iDriverId $having2) t";
/*}
else
{
$sql = "SELECT count(numberofservice) as Total from (SELECT COUNT(t.iTripId) as numberofservice FROM trips t right JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql group by d.iDriverId,vcp.iVehicleCategoryId,t.eType $having2) t";
}*/

//echo $sql;
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

/*if ($reportType=="All") {*/
  # code...

$sql = "SELECT COUNT(t.iTripId) as numberofservice,t.vCancelComment,t.eHailTrip,t.iUserId,t.iFare,t.eType,d.iDriverId, t.tSaddress,t.tDaddress, t.fWalletDebit,t.eCarType,t.iActive, t.fCancellationFare,t.eCancelled, t.iRentalPackageId ,CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName, CONCAT(d.vName,' ',d.MiddleName,' ',d.vLastName) AS driverName, d.vAvgRating, c.vCompany,d.vEmail,c.iCompanyId FROM trips t right JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1  $ssql $trp_ssql group by d.iDriverId $having $ord  LIMIT $start, $per_page";
/*}
else
{
$sql = "SELECT COUNT(t.iTripId) as numberofservice,t.vCancelComment,t.eHailTrip,t.iUserId,t.iFare,t.eType,d.iDriverId, t.tSaddress,t.tDaddress, t.fWalletDebit,t.eCarType,t.iActive, t.fCancellationFare,t.eCancelled, t.iRentalPackageId ,CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName, CONCAT(d.vName,' ',d.MiddleName,' ',d.vLastName) AS driverName, d.vAvgRating, c.vCompany,d.vEmail,c.iCompanyId,vcp.iVehicleCategoryId,vcp.vCategory_EN FROM trips t right JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql group by d.iDriverId,vcp.iVehicleCategoryId,t.eType $having $ord  LIMIT $start, $per_page";
}*/
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
                                <h2>Top Performers </h2>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <?php include('valid_msg.php'); ?>
					<form name="frmsearch" id="frmsearch" action="javascript:void(0);" method="post" >
						<div class="Posted-date mytrip-page">
							<input type="hidden" name="action" value="search" />
							<h3>Search ...</h3>
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
<!-- <div class="col-lg-2">
  <select class="form-control" name="reportType" id="reportType">
    <option value="All" <?php if ($reportType=="All") {
echo "selected";    } ?>> All</option>
    <option value="ByCategory" <?php if ($reportType=="ByCategory") {
     echo "selected";
    } ?>> By Category</option>

  </select>
</div> -->
							<input type="text" id="dp4" name="startDate" placeholder="From Date" class="form-control" value="" readonly=""style="cursor:default; background-color: #fff" />
							<input type="text" id="dp5" name="endDate" placeholder="To Date" class="form-control" value="" readonly="" style="cursor:default; background-color: #fff"/>
                            <div class="col-lg-3">

                              <input type="number" min="0" placeholder="Limit" value="<?=$maxlimit;?>" name="maxlimit" id="maxlimit" class="form-control">
                            </div>
					<!-- 		
							<div class="col-lg-3">
								  <select class="form-control filter-by-001" name = 'vStatus' >
									   <option value="">All</option>
									   <option value="onRide" <?php if($vStatus == "onRide") { echo "selected"; } ?>>On Going <?php echo $langage_lbl_admin['LBL_RIDE_TXT_ADMIN'];?> </option>
									   <option value="complete" <?php if($vStatus == "complete") { echo "selected"; } ?>>Completed</option>
									   <option value="cancel" <?php if($vStatus == "cancel") { echo "selected"; } ?>>Cancelled</option>
									   
								  </select>
							</div>
 -->
							<!-- <div class="col-lg-2">
								  <input type="text" id="serachTripNo" name="serachTripNo" placeholder="<?php echo $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'];?> Number" class="form-control search-trip001" value="<?php echo $serachTripNo; ?>"/>
							</div> -->



 

							</span>
						</div>

						<div class="row">
						

<div class="col-lg-3 select001">
              <select class="form-control miltiselect" name = 'searchCompany' id="searchCompany" data-text="Select Company" multiple="multiple">
                 <?php 
foreach ($db_company as $company) {

?>
<option value="<?php echo  $company['iCompanyId']; ?>" <?php
$Scompany=explode(',', $searchCompany);
  for ($i=0; $i <count($Scompany) ; $i++) { 


 if (trim($Scompany[$i])==$company['iCompanyId']) {
 echo 'selected';
}
} ?> ><?php echo  $company['vCompany']; ?></option>
<?php
}
     ?>

              </select>
            </div>
						 <div class="col-lg-3">
                                       <select  class="form-control miltiselect" data-text="Service type" name='eType' id="eType" multiple="multiple" >
                                       

                                                <option value='Ride'   <?php
$Scompany= $eType;

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
            </div>

					<!-- 	<div class="col-lg-3">
							<select class="form-control filter-by-text driver_container" name = 'searchDriver' data-text="Select <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?>">
							   <option value="">Select <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?></option>
							   <?php foreach($db_drivers as $dbd){ ?>
							   <option value="<?php echo $dbd['iDriverId']; ?>" <?php if($searchDriver == $dbd['iDriverId']) { echo "selected"; } ?>><?php echo $generalobjAdmin->clearName($dbd['driverName']." (".$dbd['vEmail'].")"); ?></option>
							   <?php } ?>
							</select>
						</div> -->





				<!-- 		<div class="col-lg-3">
							<select class="form-control filter-by-text" name = 'searchRider' data-text="Select <?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?>">
								<option value="">Select <?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?></option>
							   <?php foreach($db_rider as $dbr){ ?>
							   <option value="<?php echo $dbr['iUserId']; ?>" <?php if($searchRider == $dbr['iUserId']) { echo "selected"; } ?>><?php echo $generalobjAdmin->clearName($dbr['riderName']); ?></option>
							   <?php } ?>
							</select>
						</div> -->
				
						</div>
					<div class="tripBtns001"><b>
					<input type="button" value="Search" class="btnalt button11" id="Search" name="Search" title="Search" />
					<input type="button" value="Reset" class="btnalt button11" onClick="window.location.href='statisticsReportTopPerformers.php'"/>          <input type="button" value="Export" class="btnalt button11" id="export" name="export" title="Export" />
</b>


					</div>
					</form>
					<div class="table-list">
						<div class="row">
							<div class="col-lg-12">
									<div class="table-responsive">
										
										<form class="_list_form" id="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
										<table class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
												<?php if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){ ?>
													<th width="10%" class="align-left"><a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)">Number of Service Requests <?php if ($sortby == 1) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
													<?php } ?> 
												
													
													<th width="12%"><a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)">Company <?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
													<th width="12%"><a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)"><?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>

                              <th width="12%"><a href="javascript:void(0);" onClick="Redirect(6,<?php if($sortby == '6'){ echo $order; }else { ?>0<?php } ?>)">Email <?php if ($sortby == 6) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
											
	<!-- 										<?php if($reportType=="ByCategory") { ?>
      <th width="12%"><a href="javascript:void(0);" onClick="Redirect(5,<?php if($sortby == '5'){ echo $order; }else { ?>0<?php } ?>)">Category <?php if ($sortby == 5) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
<? } ?> -->
												</tr>
											</thead>
											<tbody>
												<? if(count($db_trip)>0) {
												for($i=0;$i<count($db_trip);$i++)
												{
														$eTypenew = $db_trip[$i]['eType'];
														if($eTypenew == 'Ride'){
															$trip_type = 'Ride';
														} else if($eTypenew == 'UberX') {
															$trip_type = 'Other Services';
														} else {
															$trip_type = 'Delivery';
														}
														?>
														<tr class="gradeA">
														<?php if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){ ?> 
															<!-- <td align="left">
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
															</td> -->
															<?php } ?> 
														
															<td><a href="#" onclick="javascript:openTrip('<?=$db_trip[$i]['iDriverId'];?>','<?=$db_trip[$i]['iCompanyId']?>')"> <?=$db_trip[$i]['numberofservice'];?></td>
													
															<td> 
																<?=$generalobjAdmin->clearCmpName($db_trip[$i]['vCompany']);?>
															</td>
															<td>
																<!-- <a href="driver_action.php?id=<?=$db_trip[$i]['iDriverId']?>" target="_blank"><?=$generalobjAdmin->clearName($db_trip[$i]['driverName']." (".$db_trip[$i]['vEmail'].")");?></a> -->
																<?=$generalobjAdmin->clearName($db_trip[$i]['driverName']);?>
															</td>
                              <td><?=$db_trip[$i]['vEmail'];?></td>
<!-- <?php if($reportType=="ByCategory") { ?>
														<td> 

                                <?
                               echo $db_trip[$i]['iVehicleCategoryId']!=""?$generalobjAdmin->clearCmpName($db_trip[$i]['vCategory_EN']):$db_trip[$i]['eType'];

                                ?>
                              </td>
													<? } ?> -->
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
<input type="hidden" name="searchCompany" id="searchCompany_pageForm" value="<?php echo $searchCompany; ?>" >
<input type="hidden" name="searchDriver" id="searchDriver_pageForm" value="<?php echo $searchDriver; ?>" >
<input type="hidden" name="searchRider" value="<?php echo $searchRider; ?>" >
<input type="hidden" name="serachTripNo" value="<?php echo $serachTripNo; ?>" >
<input type="hidden" name="startDate" value="<?php echo $startDate; ?>" >
<input type="hidden" name="endDate" value="<?php echo $endDate; ?>" >
<input type="hidden" name="vStatus" value="<?php echo $vStatus; ?>" >
<input type="hidden" name="eType" value="<?php echo $_REQUEST['eType']; ?>" >

<input type="hidden" name="reportType" value="<?php echo $reportType; ?>" >

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
	/*	$("#Search").on('click', function(){
			 if($("#dp5").val() < $("#dp4").val()){
				 alert("From date should be lesser than To date.")
				 return false;
			 }else {
				var action = $("#_list_form").attr('action');
                var formValus = $("#frmsearch").serialize();
                window.location.href = action+"?"+formValus;
			 }
		});*/
		$(function () {
		 /* $("select.filter-by-text").each(function(){
			  $(this).select2({
					placeholder: $(this).attr('data-text'),
					allowClear: true
			  }); //theme: 'classic'
			});*/

      $("#searchCompany").multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"275px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Company',
    maxHeight:400
  });
      $("#eType").multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"275px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Service Category',
    maxHeight:400
  });

$("#Search").click(function(){

var startDate=$("#dp4").val();
var endDate=$("#dp5").val();
var searchCompany=$("#searchCompany").val();
var eType=$("#eType").val();
var maxlimit=$("#maxlimit").val();
if(maxlimit<0)
{
$("#maxlimit").after("<span class='error'>Limit Can't be negative.</span>");return;
}

post('statisticsReportTopPerformers.php', {action:"search",startDate:startDate,endDate:endDate,searchCompany:searchCompany,eType:eType,maxlimit:maxlimit });

//window.location.href="statisticsReportTopPerformers.php?action=search&startDate="+startDate+"&endDate="+endDate+"&searchCompany="+searchCompany+"&eType="+eType+"&maxlimit="+maxlimit;

});


$("#export").click(function(){


  var startDate=$("#dp4").val();
var endDate=$("#dp5").val();
var searchCompany=$("#searchCompany").val();
var eType=$("#eType").val();
var maxlimit=$("#maxlimit").val();
var sortby=$("#sortby").val();
var order=$("#order").val();
if(maxlimit<0)
{
$("#maxlimit").after("<span class='error'>Limit Can't be negative.</span>");return;
}

post('ExportStatisticsReport/exportTopPerformers.php', {action:"search",startDate:startDate,endDate:endDate,searchCompany:searchCompany,eType:eType,maxlimit:maxlimit,order:order,sortby:sortby});


});


		});
/*		$('#searchCompany').change(function() {
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

		});*/



		function openTrip(iDriverId,iCompanyId)
		{

var searchCompany=$("#searchCompany_pageForm").val();
var searchDriver= $("#searchDriver_pageForm").val();

$("#searchCompany_pageForm").val(iCompanyId);
$("#searchDriver_pageForm").val(iDriverId);
$("#pageForm").attr("action","trip.php").attr("target", "_blank").attr("method", "post");
$("#pageForm").submit();
$("#pageForm").attr("action","").attr("method", "post").removeAttr("target");
$("#searchCompany_pageForm").val(searchCompany);
$("#searchDriver_pageForm").val(searchDriver);
		}



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
  .dropdown-toggle.btn.btn-default {
    height: 50px;
}
</style>
<script type="text/javascript">
  window.onload = getPageLoadTime;

</script>
