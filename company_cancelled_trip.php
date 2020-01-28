<?
include_once('common.php');
$generalobj->check_member_login();

?>
<script type="text/javascript">
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
           url: "LoadingTime/loadtime.php",
           data: {"loadtime":seconds,"beforeload":date1,"afterload":date2,"UserType":"COMPANY","eType":"CANCELLED_TRIP_REPORT"}, 
           success: function(data)
           {
               
           }
         });

}
</script>
<?
$tbl_name 	= 'register_driver';

$script='CancelledTrips';

$iCompanyId= $_SESSION['sess_iUserId'];

$sql = "select iDriverId,CONCAT(vName,' ',vLastName) AS driverName,vEmail from register_driver WHERE eStatus != 'Deleted' and iCompanyId='$iCompanyId' order by vName";
$db_drivers = $obj->MySQLSelect($sql);
//data for select fields

//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
$ord = ' ORDER BY t.iTripId DESC';
if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY t.tTripRequestDate ASC";
  else
  $ord = " ORDER BY t.tTripRequestDate DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY t.eCancelledBy ASC";
  else
  $ord = " ORDER BY t.eCancelledBy DESC";
}

if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY t.vCancelReason ASC";
  else
  $ord = " ORDER BY t.vCancelReason DESC";
}

if($sortby == 4){
  if($order == 0)
  $ord = " ORDER BY d.vName ASC";
  else
  $ord = " ORDER BY d.vName DESC";
}
if($sortby == 5){
  if($order == 0)
  $ord = " ORDER BY vCategory_EN ASC";
  else
  $ord = " ORDER BY vCategory_EN DESC";
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

$trp_ssql = "";
if(SITE_TYPE =='Demo'){
	$trp_ssql = " And t.tTripRequestDate > '".WEEK_DATE."'";
}


//Pagination Start
$per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page
/*$sql = "SELECT COUNT(t.iTripId) AS Total FROM trips t
	LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId
	WHERE 1=1 AND (t.iActive='Canceled' OR t.eCancelled='yes') $ssql $trp_ssql";*/


 $sql = "SELECT COUNT(t.iTripId) AS Total   FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId LEFT JOIN company as c ON d.iCompanyId = c.iCompanyId WHERE d.iCompanyId='$iCompanyId' AND (t.iActive='Canceled' OR t.eCancelled='yes') $ssql $trp_ssql";

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

 $sql = "SELECT t.tTripRequestDate ,t.tEndDate,t.eCancelled,t.vCancelReason,t.vCancelComment,t.eHailTrip,d.iDriverId, t.tSaddress,t.vRideNo,t.eCancelledBy,t.tDaddress, t.fWalletDebit,t.eCarType,t.iTripId,t.iActive, t.eType ,CONCAT(d.vName,' ',d.vLastName) AS dName,case when vcp.vCategory_EN is NULL or vcp.vCategory_EN='' then t.eType else  vcp.vCategory_EN end as vCategory_EN ,vt.vVehicleType  FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId LEFT JOIN company as c ON d.iCompanyId = c.iCompanyId WHERE d.iCompanyId='$iCompanyId' AND (t.iActive='Canceled' OR t.eCancelled='yes') $ssql $trp_ssql $ord LIMIT $start, $per_page";

$db_trip = $obj->MySQLSelect($sql);

// echo "<pre>"; print_r($db_trip); die;

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
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

<!-- BEGIN HEAD-->
<head>
	<meta charset="UTF-8" />
    <title><?=$SITE_NAME?> | Cancelled <?php echo $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'];?> </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="keywords" />
	<meta content="" name="description" />
	<meta content="" name="author" />
    <?php include_once("top/top_script.php");?>

    <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
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
				<div class="row">
                <div class="col-lg-12">
				 <h2>Cancelled <?php echo $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'];?></h2>
                 </div>
				</div>
				<hr />
                <div class="">
					<div class="table-list">
						<div class="row">
								<div class="col-lg-12">
										<div class="table-responsive">
											<?php include('valid_msg.php'); ?>
												<form name="frmsearch" id="frmsearch" action="javascript:void(0);" id="cancel_trip">
												<div class="Posted-date mytrip-page mytrip-page-select payment-report">
													<input type="hidden" name="action" value="search" />
													<h3><?=$langage_lbl_admin['LBL_MYTRIP_SEARCH_RIDES_POSTED_BY_DATE']; ?></h3>
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
												
													<div class="col-lg-2">
													  <input type="text" id="serachTripNo" name="serachTripNo" placeholder="<?php echo $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'];?> Number" class="form-control search-trip001" value="<?php echo $serachTripNo; ?>"/>
													</div>
													</span>
												</div>
<div class="row">
														<div class="col-lg-4 select001">
															<select class="form-control filter-by-text" name = 'iDriverId' data-text="Select <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?>">
															   <option value="">Select <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?></option>
															   <?php foreach($db_drivers as $dbd){ ?>
															   <option value="<?php echo $dbd['iDriverId']; ?>" <?php if($iDriverId == $dbd['iDriverId']) { echo "selected"; } ?>><?php echo $generalobj->clearName($dbd['driverName']); ?> - ( <?php echo $generalobj->clearEmail($dbd['vEmail']); ?> )</option>
															   <?php } ?>
															</select>
														</div>
													<div class="col-lg-4 select001">

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

                              
                   
													</div> </div> 
													<center>
												<div class="tripBtns001"><b>
												<input type="submit" value="Search" class="btn btnalt button11" id="Search" name="Search" title="Search" />
												<input type="button" value="Reset" class="btn btnalt button11" onClick="window.location.href = 'company_cancelled_trip.php'"/>
												<?php if(!empty($db_trip)) { ?>
												<button type="button" onClick="reportExportTypes('cancelled_trip')" class="btn export-btn001 button11" >Export</button>
												<?php }?>
											</b>
												</div>
												</center>
												<br>
											</form>
											<form name="_list_form" class="_list_form" id="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
											<table class="table table-striped table-bordered table-hover" id="dataTables-example">
												<thead>
													<tr>
												<?php if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){?>
													<th width="10%" class="align-left"><a href="javascript:void(0);" onClick="Redirect(5,<?php if($sortby == '5'){ echo $order; }else { ?>0<?php } ?>)">Service Category <?php if ($sortby == 5) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
													<?php } ?> 
														<th><a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)"><?php echo $langage_lbl_admin['LBL_TRIP_DATE_ADMIN'];?> <?php if ($sortby == 1) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>														
														<th><a href="javascript:void(0);" onClick="Redirect(2,<?php if($sortby == '2'){ echo $order; }else { ?>0<?php } ?>)">Cancel By <?php if ($sortby == 2) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
														
														<th width="12%"><a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)">Cancel Reason <?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
														
														<th width="12%"><a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)"><?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Name <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
														<th><?php echo $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'];?> No</th>
														<th>Address</th>
													</tr>
												</thead>
												<tbody>
													<? 
													if(!empty($db_trip)) {
													for($i=0;$i<count($db_trip);$i++)
													{
													/*	$eTypenew = $db_trip[$i]['eType'];
														if($eTypenew == 'Ride'){
															$trip_type = 'Ride';
														} else if($eTypenew == 'UberX') {
															$trip_type = 'Other Services';
														} else {
															$trip_type = 'Delivery';
														}
														$vCancelReason = $db_trip[$i]['vCancelReason'];
														$trip_cancel = ($vCancelReason != '')? $vCancelReason: '--';
														$eCancelled = $db_trip[$i]['eCancelled'];
														
														$CanceledBy = $db_trip[$i]['eCancelledBy'];*/
													?>
														<tr class="gradeA">
															<?php if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){ ?> 
															<td align="left">
														<!-- 	<? if($db_trip[$i]['eHailTrip'] != "Yes"){
																	echo $trip_type;
																}else{
																	echo $trip_type." ( Hail )";
																}
																?> -->
																<?=$db_trip[$i]['vCategory_EN'];?> 
															</td>
															<?php } ?> 
															<td><?= $generalobj->DateTime($db_trip[$i]['tTripRequestDate'],'no');?></td>
															<td align="left">
																<?=$CanceledBy;?>

															</td>
															<td align="left">
																<?=$trip_cancel;?>
															</td>	
															<td>
																<?=$generalobj->clearName($db_trip[$i]['dName']);?>
															</td>
															<td>
																<?php 
																
																
																													
																if($CanceledBy == "Driver")
																{
																	if($db_trip[$i]['iActive'] == "Finished")
																	{
																?>
																<a href="javascript:void(0);" onclick='javascript:window.open("invoice.php?iTripId=<?=$db_trip[$i]['iTripId']?>","_blank")'; ><?=$db_trip[$i]['vRideNo'];?></a>
																
																<?php 
																	}
																	else
																	{
																	?>
																	<?=$db_trip[$i]['vRideNo'];?>
																	<?php 
																	}
																} else {?>
																<?=$db_trip[$i]['vRideNo'];?>
																<?php }?>
															</td>
															<td width="30%" data-order="<?=$db_trip[$i]['iTripId']?>"><?php echo $db_trip[$i]['tSaddress'].' -> '.$db_trip[$i]['tDaddress'];?></td>
														</tr>
													<? } } else{?>
                                                    <tr class="gradeA">
                                                        <td colspan="6" style="text-align:center;"> No Records Found.</td>
                                                    </tr>
                                                    <?php } ?>
												</tbody>
											</table>
											</form>
              <div style="margin-top: 10px;">
                      <?php include_once("pagging.php"); ?>
                              
                                    </div>										</div>
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
<input type="hidden" name="serachTripNo" value="<?php echo $serachTripNo; ?>" >
<input type="hidden" name="iDriverId" value="<?php echo $iDriverId; ?>" >
<input type="hidden" name="startDate" value="<?php echo $startDate; ?>" >
<input type="hidden" name="endDate" value="<?php echo $endDate; ?>" >
<input type="hidden" name="vStatus" value="<?php echo $vStatus; ?>" >
<input type="hidden" name="eType" value="<?php echo $eType; ?>" >
<input type="hidden" name="iCompanyId" value="<?php echo $iCompanyId; ?>" >

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
				var action = $("#_list_form").attr('action');
                var formValus = $("#frmsearch").serialize();
                window.location.href = action+"?"+formValus;
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
		 
		    function reportExportTypes(section) {
            var action = "export_reports.php";
            var formValus = $("#pageForm").serialize();
            //alert(formValus);
            window.location.href = action+'?section='+section+'&'+formValus;
            return false;
    }
    </script>
</body>
<!-- END BODY-->
</html>
	<style type="text/css">
  body
{
  background-color: white;
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
#eType
{
	height: 40px !important;
}
</style>
<script type="text/javascript">
  window.onload = getPageLoadTime;

</script>