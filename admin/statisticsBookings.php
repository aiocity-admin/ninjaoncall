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
           data: {"loadtime":seconds,"beforeload":date1,"afterload":date2,"UserType":"SUPER_ADMIN","eType":"RIDE_ACCEPTANCE_REPORT"}, 
           success: function(data)
           {
               
           }
         });

}
</script><?php
if(!isset($generalobjAdmin)){
require_once(TPATH_CLASS."class.general_admin.php");
$generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
//$script   = "Driver Accept Report";
$script   = "STATISTICS BOOKING";



//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
$ord = ' ORDER BY vCompany ASC';

if ($sortby == 1) {
    if ($order == 0)
        $ord = " ORDER BY rvName ASC";
    else
        $ord = " ORDER BY vName DESC";
}
//End Sorting

// Start Search Parameters
$ssql = '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$iDriverId = isset($_REQUEST['iDriverId']) ? $_REQUEST['iDriverId'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
$searchCompany = isset($_REQUEST['searchCompany']) ? $_REQUEST['searchCompany'] : '';
$eType= isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';
$date1=$startDate.' '."00:00:00";
$date2=$endDate.' '."23:59:59";

if ($startDate != '' && $endDate != '') {
	$ssql .= " AND rs.tDate between '$date1' and '$date2'";
  $ssql_trip .= " AND t.tTripRequestDate between '$date1' and '$date2'";
}
if ($iDriverId != '') {
	$ssql .= " AND rd.iDriverId = '".$iDriverId."'";

  $ssql_trip .= " AND rd.iDriverId = '".$iDriverId."'";

}
 $ssql2="";

if($searchCompany!="")
{
    $ssql .= " AND c.iCompanyId = '".$searchCompany."' ";
    $ssql2 .= " AND iCompanyId = '".$searchCompany."' ";

    $ssql_trip .= " AND c.iCompanyId = '".$searchCompany."' ";


}

if($eType!="")
{

 if (trim($eType)!='null')
{

 $eType2= explode(',', $eType);
  $subQuery_company='(';

  for ($i=0; $i <count($eType2) ; $i++) { 

 if ($eType2[$i]=='Ride' || $eType2[$i]=="Deliver")  
{

    $subQuery_company .= " crn.eType = '".$eType2[$i]."' or";

}else{
    $subQuery_company .= " vcp.iVehicleCategoryId = '".$eType2[$i]."' or";
  }

  //$subQuery_company.=" d.iCompanyId='$eType[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');

 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";
$ssql.=$subQuery;
  $ssql_trip.=$subQuery;

}


 

}




$sql = "select iCompanyId,vCompany from company WHERE eStatus != 'Deleted' $rdr_ssql";
$db_company = $obj->MySQLSelect($sql);

$sql = "select iDriverId, CONCAT(vName,' ',MiddleName,' ',vLastName) AS driverName,vEmail from register_driver WHERE eStatus != 'Deleted' $ssql2  order by vName";
$db_drivers = $obj->MySQLSelect($sql);

//Pagination Start
$per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page

$sql = "SELECT COUNT( DISTINCT rs.iDriverId ) AS Total FROM  register_driver rd 
left join driver_request rs on rd.iDriverId=rs.iDriverId left join company c on rd.iCompanyId=c.iCompanyId left join cab_request_now crn on rs.iRequestId=crn.iCabRequestId  LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = crn.iVehicleTypeId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId
WHERE 1=1 $ssql GROUP by rs.iDriverId";
$totalData = $obj->MySQLSelect($sql);
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
$chk_str_date = @date('Y-m-d H:i:s', strtotime('-'.$RIDER_REQUEST_ACCEPT_TIME.' second'));
/*$sql = "SELECT rd.iDriverId , rd.vLastName ,rd.vName,rd.MiddleName ,c.vCompany,c.iCompanyId,
COUNT(case when rs.eStatus = 'Accept' then 1 else NULL end) `Accept` ,
COUNT(case when rs.eStatus != '' then 1 else NULL  end) `Total Request` ,
COUNT(case when (rs.eStatus  = 'Decline' AND rs.eAcceptAttempted  = 'No') then 1 else NULL end) `Decline` ,
COUNT(case when rs.eAcceptAttempted  = 'Yes' then 1 else NULL end) `Missed` ,
COUNT(case when ((rs.eStatus  = 'Timeout' OR rs.eStatus  = 'Received') AND rs.eAcceptAttempted  = 'No' AND  rs.dAddedDate < '".$chk_str_date."')  then 1 else NULL end) `Timeout`,
COUNT(case when ((rs.eStatus  = 'Timeout' OR rs.eStatus  = 'Received') AND rs.eAcceptAttempted  = 'No' AND rs.dAddedDate > '".$chk_str_date."' ) then 1 else NULL end) `inprocess`
FROM register_driver rd 
left join driver_request rs on rd.iDriverId=rs.iDriverId left join company c on rd.iCompanyId=c.iCompanyId left join cab_request_now crn on rs.iRequestId=crn.iCabRequestId 
WHERE 1=1 $ssql GROUP by rs.iDriverId $ord LIMIT $start, $per_page";*/

$sql = "SELECT rd.iDriverId , rd.vLastName ,rd.vName,rd.MiddleName ,c.vCompany,c.iCompanyId,
COUNT(case when rs.eStatus = 'Accept' then 1 else NULL end) `Accept` ,
COUNT(case when rs.eStatus != '' then 1 else NULL  end) `Total Request` ,
COUNT(case when (rs.eStatus  = 'Decline' AND rs.eAcceptAttempted  = 'No') then 1 else NULL end) `Decline` ,
COUNT(case when rs.eAcceptAttempted  = 'Yes' then 1 else NULL end) `Missed` ,
COUNT(case when ((rs.eStatus  = 'Timeout' OR rs.eStatus  = 'Received') AND rs.eAcceptAttempted  = 'No' AND  rs.dAddedDate < '".$chk_str_date."')  then 1 else NULL end) `Timeout`,
COUNT(case when ((rs.eStatus  = 'Timeout' OR rs.eStatus  = 'Received') AND rs.eAcceptAttempted  = 'No' AND rs.dAddedDate > '".$chk_str_date."' ) then 1 else NULL end) `inprocess`
FROM register_driver rd 
left join driver_request rs on rd.iDriverId=rs.iDriverId left join company c on rd.iCompanyId=c.iCompanyId left join cab_request_now crn on rs.iRequestId=crn.iCabRequestId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = crn.iVehicleTypeId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId
WHERE 1=1 $ssql GROUP by rs.iDriverId $ord LIMIT $start, $per_page";

/*$sql = "select  x.*,COUNT(case when t.iActive = 'Canceled' then 1 else NULL end) `Canceled`,COUNT(case when  t.iActive = 'Finished' then 1 else NULL end) `Finished`,COUNT(case when  t.iActive = 'On Going Trip' then 1 else NULL end) `OnGoingTrip` from (SELECT rd.iDriverId , rd.vLastName ,rd.vName,rd.MiddleName ,c.vCompany,c.iCompanyId,
COUNT(case when rs.eStatus = 'Accept' then 1 else NULL end) `Accept` ,
COUNT(case when rs.eStatus != '' then 1 else NULL  end) `Total Request` ,
COUNT(case when (rs.eStatus  = 'Decline' AND rs.eAcceptAttempted  = 'No') then 1 else NULL end) `Decline` ,
COUNT(case when rs.eAcceptAttempted  = 'Yes' then 1 else NULL end) `Missed` ,
COUNT(case when ((rs.eStatus  = 'Timeout' OR rs.eStatus  = 'Received') AND rs.eAcceptAttempted  = 'No' AND  rs.dAddedDate < '".$chk_str_date."')  then 1 else NULL end) `Timeout`,
COUNT(case when ((rs.eStatus  = 'Timeout' OR rs.eStatus  = 'Received') AND rs.eAcceptAttempted  = 'No' AND rs.dAddedDate > '".$chk_str_date."' ) then 1 else NULL end) `inprocess`
FROM register_driver rd 
left join driver_request rs on rd.iDriverId=rs.iDriverId left join company c on rd.iCompanyId=c.iCompanyId left join cab_request_now crn on rs.iRequestId=crn.iCabRequestId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = crn.iVehicleTypeId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId
WHERE 1=1 $ssql GROUP by rs.iDriverId) x left join trips t on t.iDriverId=x.iDriverId group by x.iDriverId  $ord LIMIT $start, $per_page";
*/
//echo $sql;
$db_res = $obj->MySQLSelect($sql);
$endRecord = count($db_res);

$var_filter = "";
foreach ($_REQUEST as $key => $val) {
    if ($key != "tpages" && $key != 'page')
        $var_filter .= "&$key=" . stripslashes($val);
}
$reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages . $var_filter;
//echo "<pre>"; print_r($db_log_report); exit;

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
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

     <!-- BEGIN HEAD-->
     <head>
          <meta charset="UTF-8" />
          <title><?=$SITE_NAME?> | Ride Acceptance Report</title>
          <meta content="width=device-width, initial-scale=1.0" name="viewport" />
          <link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

          <? include_once('global_files.php');?>         
              <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
     </head>
     <!-- END  HEAD-->
     <!-- BEGIN BODY-->
     <body class="padTop53 " >

          <!-- MAIN WRAPPER -->
          <div id="wrap">
               <? include_once('header.php'); ?>
               <? include_once('left_menu.php'); ?>

               <!--PAGE CONTENT -->
               <div id="content">
                    <div class="inner">
						<div class="row">
							   <div class="col-lg-12">
									<h2><?php echo $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'];?> Request Report</h2>
								   
							   </div>
						</div>
						<hr />
                         <div class="table-list">
                              <div class="row">
                                   <div class="col-lg-12">
											<form name="frmsearch" id="frmsearch" action="javascript:void(0);" method="post">
												<div class="Posted-date mytrip-page mytrip-page-select payment-report">
													<input type="hidden" name="action" value="search" />
													<h3>Search by Date...</h3>
													<span>
													<a onClick="return todayDate('dp4','dp5');"><?=$langage_lbl_admin['LBL_MYTRIP_Today']; ?></a>
													<a onClick="return yesterdayDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Yesterday']; ?></a>
													<a onClick="return currentweekDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Current_Week']; ?></a>
													<a onClick="return previousweekDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Previous_Week']; ?></a>
													<a onClick="return currentmonthDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Current_Month']; ?></a>
													<a onClick="return previousmonthDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Previous Month']; ?></a>
													<a onClick="return currentyearDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Current_Year']; ?></a>
													<a onClick="return previousyearDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Previous_Year']; ?></a>
													</span> 
													<span>
													<input type="text" id="dp4" name="startDate" placeholder="From Date" class="form-control" value=""/>
													<input type="text" id="dp5" name="endDate" placeholder="To Date" class="form-control" value=""/>
  <div class="col-lg-3 select001">
              <select class="form-control filter-by-text" name = 'searchCompany' id="searchCompany" data-text="Select Company">
                 <option value="">Select Company</option>
                 <?php foreach($db_company as $dbc){ ?>
                 <option value="<?php echo $dbc['iCompanyId']; ?>" <?php if($searchCompany == $dbc['iCompanyId']) { echo "selected"; } ?>><?php echo $generalobjAdmin->clearCmpName($dbc['vCompany']); ?></option>
                 <?php } ?>
              </select>
            </div>

													<div class="col-lg-3 select001">
														<select class="form-control filter-by-text  driver_container" name = 'iDriverId' data-text="Select <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?>" id='iDriverId'>
														   <option value="">Select <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?></option>
														   <?php foreach($db_drivers as $dbd){ ?>
														   <option value="<?php echo $dbd['iDriverId']; ?>" <?php if($iDriverId == $dbd['iDriverId']) { echo "selected"; } ?>><?php echo $generalobjAdmin->clearName($dbd['driverName']); ?> - ( <?php echo $generalobjAdmin->clearEmail($dbd['vEmail']); ?> )</option>
														   <?php } ?>
														</select>
													</div>

                          
													</span>
												</div>
  <div class="col-lg-3 select001">
             <br>


               <select  class="form-control miltiselect filter-by-text" data-text="Select Service Category" name='eType' id="eType"  >
                                                                <option value=''>Select Service Category</option>


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

												<div class="tripBtns001"><b>
												<input type="submit" value="Search" class="btnalt button11" id="Search" name="Search" title="Search" />
												<input type="button" value="Reset" class="btnalt button11" onClick="window.location.href='statisticsBookings.php'"/>
												<button type="button"  id="export" class="export-btn001" >Export</button>
												</b>
												</div>
											</form>
                          <div class="table-responsive">
												  <form name="_list_form" id="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                              <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                 <tr>
                                  <th>Company Name</th>

																<th><?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Name</th>
																<th>Total <?php echo $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'];?> Requests <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='<?= htmlspecialchars('Total trip request (i.e 30 second timout screen) received from riders to drivers', ENT_QUOTES, 'UTF-8') ?>'></i></th>     
																<th>Requests Accepted <?php echo $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'];?> Requests <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='<?= htmlspecialchars('Total trip request accepted by drivers', ENT_QUOTES, 'UTF-8') ?>'></i></th>
                                <th>Requests Declined <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='<?= htmlspecialchars('Total trip request declined by drivers', ENT_QUOTES, 'UTF-8') ?>'></i></th>
                                <th>Requests Timeout <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='<?= htmlspecialchars('Total trip request driver has missed to attend. Neither Accepted not Declined.', ENT_QUOTES, 'UTF-8') ?>'></i></th>
                                <th>Missed Attempts <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='<?= htmlspecialchars('Total trip request driver has tried to accept but due to competitive algorithm, another driver has accepted it.', ENT_QUOTES, 'UTF-8') ?>'></i></th>
                                <th>In Process Requests <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='<?= htmlspecialchars('Requests sent from rider but no drivers have accepted it yet. Values in this column will remain for some seconds until any driver accept it.', ENT_QUOTES, 'UTF-8') ?>'></i></th>
																 <th><?php echo $langage_lbl_admin['LBL_TRIPS_TXT_ADMIN'];?> Cancelled  <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='<?= htmlspecialchars('Total Cancelled trips.', ENT_QUOTES, 'UTF-8') ?>'></i></th> 
																<th>Requests Completed <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='<?= htmlspecialchars('Total Completed trips.', ENT_QUOTES, 'UTF-8') ?>'></i></th> 
                                 <th>On going <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='<?= htmlspecialchars('Total on going trips.', ENT_QUOTES, 'UTF-8') ?>'></i> </th> 

																<th>Acceptance Percentage <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='<?= htmlspecialchars('Ratio of Total Requests Accepted + Total Missed Attempts out of Total Requests sent.', ENT_QUOTES, 'UTF-8') ?>'></i></th> 
                                </tr>
                                </thead>
                                <tbody>
                                <?php  
                                $total_trip_req ="";
                                $total_trip_acce_req ="";
                                $total_trip_dec_req ="";

                                if (count($db_res)>0) {
                             
   
                                for($i=0;$i<count($db_res);$i++) {

                                // $sql_acp = "SELECT rd.vName, rd. vLastName, rs.tDate,count(rs.iDriverId)as totalacp FROM driver_request AS rs LEFT JOIN register_driver AS rd ON rd.iDriverId = rs.iDriverId WHERE rs.eStatus ='Accept' AND rs.iDriverId ='".$db_res[$i]['iDriverId']."'";
														   //echo "<hr>";  
														    // $sql_tip="SELECT COUNT(trips.iTripId) as Finished from trips WHERE trips.iActive='Finished' and trips.iDriverId='".$db_res[$i]['iDriverId']."'";
															  $sql_acp="SELECT 
																		COUNT(case when t.iActive = 'Canceled' OR t.eCancelled='yes' then 1 else NULL end) `Cancel` ,
																		COUNT(case when t.iActive = 'Finished' AND t.eCancelled='No'   then 1 else NULL  end) `Finish`,COUNT(case when (t.iActive = 'On Going Trip' OR t.iActive = 'Active') AND t.eCancelled='No' then 1 else NULL end) `OnGoingTrip` 
																		FROM trips t left join register_driver rd on rd.iDriverId=t.iDriverId left join company c on rd.iCompanyId=c.iCompanyId  LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  where t.iDriverId='".$db_res[$i]['iDriverId']."'".$ssql_trip;
                                   // echo $sql_acp;
                                $db_acp = $obj->MySQLSelect($sql_acp);
															 //echo"<pre>";print_r($db_acp);
                              //  $db_tip = $obj->MySQLSelect($sql_tip);
															//echo "<pre>";print_r($db_acp);exit;
                              // $sql_dec = "SELECT count(iDriverId) as totaldec FROM `driver_request` WHERE eStatus  IN ('Decline', AND iDriverId ='".$db_res[$i]['iDriverId']."'";
                              // $db_dec= $obj->MySQLSelect($sql_dec);                                          

                             // $db_acp_val = $db_acp[0]['Accept'];
                             // $db_acp_val = $db_acp[0]['Accept'];
                            
                             // $db_dec_val = $db_dec[0]['Total Request'];
                             // $total = $db_acp_val + $db_dec_val;
                             // $Finished = $db_acp_val - $db_dec_val ;

                             // $total_trip_req = $total_trip_req + $total;
                             // $total_trip_acce_req = $total_trip_acce_req + $db_acp_val;
                             // $total_trip_dec_req = $total_trip_dec_req + $db_dec_val;   
                             // $total_finish=$total_finish+$Finished	;														 

                             // $percentage = (100 * $db_acp_val)/$total;
                              // $aceptance_percentage = number_format($percentage, 2);        
															    
															  $Accept = $db_res[$i]['Accept'];
															  $tAccept = $tAccept + $Accept;
															  $Request = $db_res[$i]['Total Request'];
															  $tRequest =$tRequest + $Request ;
															  $Decline = $db_res[$i]['Decline'];
															  $tDecline =$tDecline + $Decline;
															  $Timeout = $db_res[$i]['Timeout'];
															  $tTimeout = $tTimeout + $Timeout ;
                                $missed = $db_res[$i]['Missed'];
                                $tmissed = $tmissed + $missed ;
                                $inprocess = $db_res[$i]['inprocess'];
                                $tinprocess = $tinprocess + $inprocess ;

															  $Cancel = $db_acp[0]['Cancel'];
															  $tCancel = $tCancel + $Cancel ;

															  $Finish = $db_acp[0]['Finish'];
                                //$tFinish = $tFinish +$Finish;

                               $OnGoingTrip = $db_acp[0]['OnGoingTrip'];
                                $tOnGoingTrip = $tOnGoingTrip + $OnGoingTrip;
                                
                                if($Finish < 0){
                                  $Finish = 0;
                                }
															  $tFinish = $tFinish + $Finish ;
															 $aceptance_percentage= (100 * ($Accept+$missed))/$Request;
															  
															  ?>
															
                                  <tr class="gradeA">
                                                                          <td><?= $db_res[$i]['vCompany'];?></td>

                                      <td><?=$generalobjAdmin->clearName($db_res[$i]['vName'].' '.$db_res[$i]['MiddleName'].' '.$db_res[$i]['vLastName']); ?></td>
                                      <td><?= $Request;?></td>
                                      <td><a href="#" onclick="openTrip('<?= $db_res[$i]['iDriverId'];?>','<?= $db_res[$i]['iCompanyId'];?>','All')"><?= $Accept; ?></a></td>
                                      <td><a href="#" onclick="openTrip('<?= $db_res[$i]['iDriverId'];?>','<?= $db_res[$i]['iCompanyId'];?>','Decline')"><?= $Decline; ?></a></td>
                                      <td><a href="#" onclick="openTrip('<?= $db_res[$i]['iDriverId'];?>','<?= $db_res[$i]['iCompanyId'];?>','Timeout')"><?= $Timeout; ?></a></td>
                                      <td><a href="#" onclick="openTrip('<?= $db_res[$i]['iDriverId'];?>','<?= $db_res[$i]['iCompanyId'];?>','Yes')"><?= $missed; ?></a></td>
                                      <td><a href="#" onclick="openTrip('<?= $db_res[$i]['iDriverId'];?>','<?= $db_res[$i]['iCompanyId'];?>','InProcess')"><?= $inprocess; ?></a></td>
                                       <td><a href="#" onclick="openTrip('<?= $db_res[$i]['iDriverId'];?>','<?= $db_res[$i]['iCompanyId'];?>','cancel')"> <?= $Cancel; ?></a></td> 
                                       <td><a href="#"  onclick="openTrip('<?= $db_res[$i]['iDriverId'];?>','<?= $db_res[$i]['iCompanyId'];?>','complete')"><?= $Finish; ?></a></td> 
                                       <td><a href="#" onclick="openTrip('<?= $db_res[$i]['iDriverId'];?>','<?= $db_res[$i]['iCompanyId'];?>','onRide')"><?= $OnGoingTrip; ?></a></td> 

                                      <td><?= round($aceptance_percentage,2).' %'; ?></td>      
                                  </tr>

                              <? 	
															 } 
														   ?>                                                              
                              </tbody>
                              <tr class="gradeA" align="center">
                                  <td colspan="2"><b>TOTAL</b></td>
                                  <td><?= $tRequest;?></td>
	                                <td><?=$tAccept; ?></td>
                                  <td><?=$tDecline; ?></td>
                                  <td><?=$tTimeout; ?></td>
                                  <td><?= $tmissed; ?></td>
                                  <td><?= $tinprocess; ?></td>
                                  <td><?=$tCancel; ?></td>
                                  <td><?=$tFinish; ?></td> 
                                 <td><?=$tOnGoingTrip; ?></td> 


                                  
                                  <td></td>
                              </tr>
                            <? }else { ?>
                              <tr class="gradeA" align="center"><td colspan="12">No result found.</td> </tr>

                              <?
                            } ?>

                         </table>
													   </form>
												<?php include('pagination_n.php'); ?>
											</div>
                                   </div> <!--TABLE-END-->
                              </div>
                         </div>
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
<input type="hidden" name="iDriverId" value="<?php echo $iDriverId; ?>" >
<input type="hidden" name="startDate" value="<?php echo $startDate; ?>" >
<input type="hidden" name="endDate" value="<?php echo $endDate; ?>" >
<input type="hidden" name="vStatus" id="eStatus_pageForm" value="<?php echo $vStatus; ?>" >

<input type="hidden" name="searchDriver" id="searchDriver_pageForm" value="<?php echo $iDriverId; ?>" >

<input type="hidden" name="searchCompany" id="searchCompany_pageForm" value="<?php echo $searchCompany; ?>" >
<input type="hidden" name="eType" value="<?php echo $eType; ?>" >

<input type="hidden" name="method" id="method" value="" >
</form>
	<? include_once('footer.php');?>
	<link rel="stylesheet" href="../assets/plugins/datepicker/css/datepicker.css" />
	<link rel="stylesheet" href="css/select2/select2.min.css" />
	<script src="js/plugins/select2.min.js"></script>
	<script src="../assets/plugins/datepicker/js/bootstrap-datepicker.js"></script>
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
			 if('<?=$startDate?>'!=''){
				 $("#dp4").val('<?=$startDate?>');
				 $("#dp4").datepicker('update' , '<?=$startDate?>');
			 }
			 if('<?=$endDate?>'!=''){
				 $("#dp5").datepicker('update' , '<?= $endDate;?>');
				 $("#dp5").val('<?= $endDate;?>');
			 }
			 
			 $("select.filter-by-text").each(function(){
			  $(this).select2({
					placeholder: $(this).attr('data-text'),
					allowClear: true
			  }); //theme: 'classic'
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

         });
		 
		 function setRideStatus(actionStatus) {
			 window.location.href = "trip.php?type="+actionStatus;
		 }
		 function todayDate()
		 {
			//alert('sa');
			 $("#dp4").val('<?= $Today;?>');
			 $("#dp5").val('<?= $Today;?>');
		 }
		 function resetform()
		 {
		 	//location.reload();
			document.search.reset();
			document.getElementById("iDriverId").value=" ";
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
		function checkvalid(){
			 if($("#dp5").val() < $("#dp4").val()){
				 alert("From date should be lesser than To date.")
				 return false;
			 }
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


$("#export").click(function(){

var searchDriver=$("#iDriverId").val();
var searchCompany=$("#searchCompany").val();
var eType=$("#eType").val();

var startDate=$("#dp4").val(); 
var endDate=$("#dp5").val();
post('ExportStatisticsReport/exportBookings.php', {search:1,iDriverId:searchDriver,searchCompany:searchCompany,eType:eType,startDate:startDate,endDate:endDate });


});

/*  $("#eType").multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"275px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Service Category',
    maxHeight:400
  });*/
    function openTrip(iDriverId,iCompanyId,eStatus)
    {
var searchCompany=$("#searchCompany_pageForm").val();
var searchDriver= $("#searchDriver_pageForm").val();
var eStatus_frm=$("#eStatus_pageForm").val();

$("#searchCompany_pageForm").val(iCompanyId);
$("#searchDriver_pageForm").val(iDriverId);
$("#eStatus_pageForm").val(eStatus);

$("#pageForm").attr("action","trip.php").attr("target", "_blank").attr("method", "GET");
$("#pageForm").submit();
$("#pageForm").attr("action","").attr("method", "post").removeAttr("target");

$("#searchCompany_pageForm").val(searchCompany);
$("#searchDriver_pageForm").val(searchDriver);
$("#eStatus_pageForm").val(eStatus_frm);

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
</html>
<style type="text/css">
  button.multiselect.dropdown-toggle.btn.btn-default {
    height: 40px;
}
</style>
<script type="text/javascript">
  window.onload = getPageLoadTime;

</script>