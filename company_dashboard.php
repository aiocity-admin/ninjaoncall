<?php
	include_once('common.php');
	$generalobj->check_member_login();

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
           url: "LoadingTime/loadtime.php",
           data: {"loadtime":seconds,"beforeload":date1,"afterload":date2,"UserType":"COMPANY","eType":"DASHBOARD"}, 
           success: function(data)
           {
               
           }
         });

}
</script><?php
	
		$iCompanyId = $_SESSION['sess_iUserId'];


//	$company 	= $generalobjAdmin->getCompanyDetails();
	$driver 	= getDriverDetailsDashboard($iCompanyId,'');

	//$rider_count 		= getRiderCount();

$sql="SELECT COUNT(distinct u.iUserId) AS riders  FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1 and u.eStatus='Active' and c.iCompanyId='$iCompanyId'  group by c.iCompanyId ";
$rider_count=$obj->MySQLSelect($sql);

	$rider = $rider_count[0]['riders'];


	$totalEarns	= getTotalEarns($iCompanyId);
	$totalRides = getTripStates($iCompanyId,'total');
	$onRides = getTripStates($iCompanyId,'on ride');
	$finishRides = getTripStates($iCompanyId,'finished');
	$cancelRides = getTripStates($iCompanyId,'cancelled');
	$actDrive =getDriverDetailsDashboard($iCompanyId,'active');
	$inaDrive = getDriverDetailsDashboard($iCompanyId,'inactive');


	
/*	$sql="SELECT dm.doc_name_".$default_lang.",dl.doc_usertype,rd.iDriverId,CONCAT(rd.vName,' ',rd.vLastName) AS `Driver`,CONCAT(rdn.vName,' ',rdn.vLastName) AS `DriverName`,dv.iDriverVehicleId, c.vCompany,dl.edate,c.iCompanyId,rd.iDriverId FROM `document_list` AS dl LEFT JOIN document_master AS dm ON dm.doc_masterid=dl.doc_masterid
	LEFT JOIN company AS c ON (c.iCompanyId=dl.doc_userid AND dl.doc_usertype='company')
	LEFT JOIN register_driver AS rd ON (rd.iDriverId=dl.doc_userid AND dl.doc_usertype='driver')
	LEFT JOIN driver_vehicle AS dv ON (dv.iDriverVehicleId=dl.doc_userid AND dl.doc_usertype='car')
	LEFT JOIN register_driver AS rdn ON rdn.iDriverId=dv.iDriverId where c.iCompanyId='$iCompanyId' and dl.doc_usertype!='company' ORDER BY dl.edate DESC LIMIT 0,10";*/

	$sql="SELECT dm.doc_name_".$default_lang.",dl.doc_usertype,rd.iDriverId,CONCAT(rd.vName,' ',rd.vLastName) AS `Driver`,CONCAT(rdn.vName,' ',rdn.vLastName) AS `DriverName`,dv.iDriverVehicleId, c.vCompany,dl.edate,c.iCompanyId,rd.iDriverId FROM `document_list` AS dl LEFT JOIN document_master AS dm ON dm.doc_masterid=dl.doc_masterid
	LEFT JOIN company AS c ON (c.iCompanyId=dl.doc_userid AND dl.doc_usertype='company')
	LEFT JOIN register_driver AS rd ON (rd.iDriverId=dl.doc_userid AND dl.doc_usertype='driver')
	LEFT JOIN driver_vehicle AS dv ON (dv.iDriverVehicleId=dl.doc_userid AND dl.doc_usertype='car')
	LEFT JOIN register_driver AS rdn ON rdn.iDriverId=dv.iDriverId  where rd.iCompanyId='$iCompanyId' and dl.doc_usertype!='company' ORDER BY dl.edate DESC LIMIT 0,10";


	$db_notification = $obj->MySQLSelect($sql);

	
	if(isset($_REQUEST['allnotification']))
	{
		$sql="SELECT dm.doc_name_".$default_lang.",dl.doc_usertype,rd.iDriverId,CONCAT(rd.vName,' ',rd.vLastName) AS `Driver`,CONCAT(rdn.vName,' ',rdn.vLastName) AS `DriverName`,dv.iDriverVehicleId, c.vCompany,dl.edate FROM `document_list` AS dl
		LEFT JOIN document_master AS dm ON dm.doc_masterid=dl.doc_masterid
		LEFT JOIN company AS c ON (c.iCompanyId=dl.doc_userid AND dl.doc_usertype='company')
		LEFT JOIN register_driver AS rd ON (rd.iDriverId=dl.doc_userid AND dl.doc_usertype='driver')
		LEFT JOIN driver_vehicle AS dv ON (dv.iDriverVehicleId=dl.doc_userid AND dl.doc_usertype='car')
		LEFT JOIN register_driver AS rdn ON rdn.iDriverId=dv.iDriverId  where rd.iCompanyId='$iCompanyId' and dl.doc_usertype!='company' ORDER BY dl.edate DESC";

		$db_notification = $obj->MySQLSelect($sql);

	}

		//echo $sql;

			$sql="SELECT t.iTripId,rd.vImage,t.iDriverId,rd.vName,rd.vLastName,t.tEndDate,t.tSaddress,t.tDaddress,t.iActive FROM trips t JOIN register_driver rd ON t.iDriverId=rd.iDriverId  WHERE rd.iCompanyId='$iCompanyId' AND  iActive='Finished' ORDER BY tEndDate DESC LIMIT 0,4";
	$db_finished = $obj->MySQLSelect($sql);

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
	<!-- BEGIN HEAD-->
    	<head>
		<meta charset="UTF-8" />
		<title><?=$SITE_NAME;?> | Dashboard</title>
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<![endif]-->
		<!-- GLOBAL STYLES -->
    <?php include_once("top/top_script.php");?>
		<link rel="stylesheet" href="admin/css/style.css" />
		<link rel="stylesheet" href="admin/css/new_main.css" />
		<link rel="stylesheet" href="admin/css/adminLTE/AdminLTE.min.css" />
		<script type="text/javascript" src="admin/js/plugins/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="admin/js/plugins/morris/raphael-min.js"></script>
        <script type="text/javascript" src="admin/js/plugins/morris/morris.min.js"></script> 
		<script type="text/javascript" src="admin/js/actions.js"></script>
        <!-- END THIS PAGE PLUGINS-->
		<!--END GLOBAL STYLES -->

		<!-- PAGE LEVEL STYLES -->
		<!-- END PAGE LEVEL  STYLES -->
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
	</head>
    <!-- END  HEAD-->
    <!-- BEGIN BODY-->
	<body class="padTop53">
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
							<h1> Dashboard </h1>
						</div>
					</div>
					<hr />
					<!--BLOCK SECTION -->
					
					<!--div class="row">
						<div class="col-lg-12">
							<div style="text-align: center;">
								<a class="quick-btn" href="company.php">
									<i class="icon-check icon-2x"></i>
									<span>Company</span>
									<span class="label label-danger"><? //=count($company);?></span>
								</a>
								<a class="quick-btn" href="driver.php">
									<i class="icon-envelope icon-2x"></i>
									<span>Driver</span>
									<span class="label label-success"><? //=count($driver);?></span>
								</a>
								<a class="quick-btn" href="vehicles.php">
									<i class="icon-bolt icon-2x"></i>
									<span>Vehicle</span>
									<span class="label label-default"><? //=count($vehicle);?></span>
								</a>
								<a class="quick-btn" href="rider.php">
									<i class="icon-signal icon-2x"></i>
									<span>Rider</span>
									<span class="label label-warning"><? //=count($rider);?></span>
								</a>
								<a class="quick-btn" href="trip.php">
									<i class="icon-external-link icon-2x"></i>
									<span>Trips</span>
									<span class="label btn-metis-2"><? //=count($trips);?></span>
								</a>
							</div>
						</div>
					</div-->
					<!--END BLOCK SECTION -->
					<div class="row">
					<div class="col-lg-6">
					<div class="panel panel-primary bg-gray-light" >
                            <div class="panel-heading" >
								<div class="panel-title-box">
								<i class="fa fa-bar-chart"></i> Site Statistics
								</div>                                  
							</div>
							<div class="row padding_005">
                            <div class="col-lg-6"><a href="statisticsReportUsersForCompany.php?action=search&searchCompany=<?=$iCompanyId?>&typeofreport=Number of Active Users&list=Yes">
								<div class="info-box bg-aqua">
									<span class="info-box-icon"><i class="fa fa-users"></i></span>

									<div class="info-box-content">
										<span class="info-box-text"><?php echo $langage_lbl_admin['LBL_DASHBOARD_USERS_ADMIN'];?> </span>
										<span class="info-box-number"><?=number_format($rider);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
							<!-- /.col -->
							<div class="col-lg-6"><a href="driver.php?type=approve" >
								<div class="info-box bg-yellow">
									<span class="info-box-icon"><i class="fa fa-male"></i></span>

									<div class="info-box-content">
										<span class="info-box-text"><?php echo $langage_lbl_admin['LBL_DASHBOARD_DRIVERS_ADMIN'];?> </span>
										<span class="info-box-number"><?=number_format($driver);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
						<!-- 	<div class="col-lg-6"><a href="company.php">
								<div class="info-box bg-red">
									<span class="info-box-icon"><i class="fa fa-building-o"></i></span>

									<div class="info-box-content">
										<span class="info-box-text">Companies</span>
										<span class="info-box-number"><?=number_format($company);?></span>
									</div>
								</div></a>
							</div> -->

							<div class="col-lg-6"><a href="company_trip_detail.php" >
								<div class="info-box bg-green">
									<span class="info-box-icon"><i class="fa fa-money"></i></span>

									<div class="info-box-content">
										<span class="info-box-text">Total Earnings</span>
										<!--<span class="info-box-number"><?=number_format($totalEarns,2);?></span>-->
										<span class="info-box-number"><?=$generalobj->trip_currency($totalEarns,'','',2);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
							</div>
                        </div>
					</div>
					
					<div class="col-lg-6">
					<div class="panel panel-primary bg-gray-light" >
							<div class="panel-heading" >
								<div class="panel-title-box">
								   <i class="fa fa-area-chart"></i> <?php echo $langage_lbl_admin['LBL_RIDE_STATISTICS_ADMIN'];?>
								</div>                                  
							</div>
							<div class="row padding_005">
                            <div class="col-lg-6"><a href="company-trip?vStatus=all" >
								<div class="info-box bg-aqua">
									<span class="info-box-icon"><i class="fa fa-cubes"></i></span>

									<div class="info-box-content">
										<span class="info-box-text"><?php echo $langage_lbl_admin['LBL_TOTAL_RIDES_ADMIN'];?> </span>
										<span class="info-box-number"><?=number_format($totalRides);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
							<!-- /.col -->
							<div class="col-lg-6"><a href="company-trip?vStatus=onRide&action=search" >
								<div class="info-box bg-yellow">
									<span class="info-box-icon"><i class="fa fa-clone"></i></span>

									<div class="info-box-content">
										<span class="info-box-text"><?php echo $langage_lbl_admin['LBL_ON_RIDES_ADMIN'];?> </span>
										<span class="info-box-number"><?=number_format($onRides);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
							
							<div class="col-lg-6"><a href="company-trip?vStatus=cancel&action=search" >
								<div class="info-box bg-red">
									<span class="info-box-icon"><i class="fa fa-times-circle-o"></i></span>

									<div class="info-box-content">
										<span class="info-box-text"><?php echo $langage_lbl_admin['LBL_CANCELLED_RIDES_ADMIN'];?> </span>
										<span class="info-box-number"><?=number_format($cancelRides);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
							<!-- /.col -->


							<div class="col-lg-6"><a href="company-trip?vStatus=complete&action=search" >
								<div class="info-box bg-green">
									<span class="info-box-icon"><i class="fa fa-check"></i></span>

									<div class="info-box-content">
										<span class="info-box-text"><?php echo $langage_lbl_admin['LBL_COMPLETED_RIDES_ADMIN'];?> </span>
										<span class="info-box-number"><?=number_format($finishRides);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
							</div>
                        </div>
					</div>
					</div>
					
					<hr />
					<div class="row">
					<div class="col-lg-6">
					<div class="panel panel-primary bg-gray-light" >
                            <div class="panel-heading" >
								<div class="panel-title-box">
								   <i class="fa fa-bar-chart"></i> <?php echo $langage_lbl_admin['LBL_RIDES_NAME_ADMIN'];?>
								</div>                                  
							</div>
							<div class="panel-body padding-0">
							<div class="col-lg-6">
								<div class="chart-holder" id="dashboard-rides" style="height: 200px;"></div>
							</div>
								<div class="col-lg-6">
								<h3><?php echo $langage_lbl_admin['LBL_RIDES_NAME_ADMIN'];?>  Count : <?=number_format($totalRides);?></h3>
								<p>Today : 
									<b><?=number_format(getTripDateStates($iCompanyId,'today'));?></b>
								</p>
								<p>This Month : 
									<b><?=number_format(getTripDateStates($iCompanyId,'month'));?></b>
								</p>
								<p>This Year : 
									<b><?=number_format(getTripDateStates($iCompanyId,'year'));?></b>
								</p>
								<br />
								<p>
									* This is count for all <?=$langage_lbl_admin['LBL_RIDES_NAME_ADMIN'];?> (Finished, ongoing, cancelled.)
								</p>
							</div>
							</div>
						</div>
						<!-- END VISITORS BLOCK -->
					</div>
					
					<div class="col-lg-6">
					<div class="panel panel-primary bg-gray-light" >
                            <div class="panel-heading" >
								<div class="panel-title-box">
								   <i class="fa fa-bar-chart"></i> <?php echo $langage_lbl_admin['LBL_DRIVERS_NAME_ADMIN'];?>
								</div>                                  
							</div>
							<div class="panel-body padding-0">
							<div class="col-lg-6">
								<div class="chart-holder" id="dashboard-drivers" style="height: 200px;"></div>
							</div>
								<div class="col-lg-6">
								<h3><?php echo $langage_lbl_admin['LBL_DRIVERS_NAME_ADMIN'];?>  Count : <?=number_format($driver);?></h3>
								<p>Today : <b><?=number_format(count(getDriverDateStatus($iCompanyId,'today')));?></b></p>
								<p>This Month : <b><?=number_format(count(getDriverDateStatus($iCompanyId,'month')));?></b></p>
								<p>This Year : <b><?=number_format(count(getDriverDateStatus($iCompanyId,'year')));?></b></p>
							</div>
							</div>
						</div>
						<!-- END VISITORS BLOCK -->
					</div>
					</div>
					<!-- COMMENT AND NOTIFICATION  SECTION -->
					<div class="row">
						<div class="col-lg-6">
						<div class="chat-panel panel panel-success">
								<div class="panel-heading">
									<div class="panel-title-box">
									   <i class="icon-comments"></i> Latest <?php echo $langage_lbl_admin['LBL_RIDES_NAME_ADMIN'];?>
									   <a class="btn btn-info btn-sm ride-view-all001" href="company-trip" >View All</a>
									</div>                                  
								</div>
								<?php  for($i=0,$n=$i+2;$i<count($db_finished);$i++,$n++){?>
									<div class="panel-heading" style="background:none;">
										<ul class="chat">
											<?php if($n%2==0){ ?>
											<a href=<?echo "invoice.php?iTripId=".$db_finished[$i]['iTripId'];?>>
												<li class="left clearfix">
													<span class="chat-img pull-left">
														<? if($db_finished[$i]['vImage']!='' && $db_finished[$i]['vImage']!="NONE" && file_exists( "webimages/upload/Driver/".$db_finished[$i]['iDriverId']."/".$db_finished[$i]['vImage'])){?>
															<img src="../webimages/upload/Driver/<?php echo $db_finished[$i]['iDriverId']."/".$db_finished[$i]['vImage'];?>" alt="User Avatar" class="img-circle"  height="50" width="50"/>
														<? }else{?>

														<img src="assets/img/profile-user-img.png" alt="" class="img-circle"  height="50" width="50">
														<?}?>
													</span>
													<div class="chat-body clearfix">
														<div class="header">
															<strong class="primary-font "> <?php //echo $generalobjAdmin->clearName($db_finished[$i]['vName']." ".$db_finished[$i]['vLastName']); ?> </strong>
															<small class="pull-right text-muted label label-danger">
																<i class="icon-time"></i>
																<?php
																	$regDate=$db_finished[$i]['tEndDate'];
																	$dif=strtotime(Date('Y-m-d H:i:s'))-strtotime($regDate);
																	if($dif<60)
																	{
																		$time=floor($dif/(60));
																		echo "Just Now";
																	}
																	else if($dif<3600)
																	{
																		$time=floor($dif/(60));
																		$texts = "Minute";
																		if($time > 1) {
																			$texts = "Minutes";
																		}
																		echo $time." $texts ago";
																	}
																	else if($dif<86400)
																	{
																		$time=floor($dif/(60*60));
																		$texts = "Hour";
																		if($time > 1) {
																			$texts = "Hours";
																		}
																		echo $time." $texts ago";
																	}
																	else
																	{
																		$time=floor($dif/(24*60*60));
																		$texts = "Day";
																		if($time > 1) {
																			$texts = "Days";
																		}
																		echo $time." $texts ago";
																	}
																?>
															</small>
														</div>
														<br />
														<p>
															<?php echo $db_finished[$i]['tSaddress']." --> ".$db_finished[$i]['tDaddress']."<br/>";
																echo "<b>Status: ".$db_finished[$i]['iActive']."</b>";
															?>
														</p>
													</div>
												</li>
												</a>
												<?php } else { ?>
												<li class="right clearfix">
													<a href=<?echo "invoice.php?iTripId=".$db_finished[$i]['iTripId'];?>>
													<span class="chat-img pull-right">
														<? if($db_finished[$i]['vImage']!='' && $db_finished[$i]['vImage']!="NONE"){?>
															<?php if(file_exists( "webimages/upload/Driver/".$db_finished[$i]['iDriverId']."/".$db_finished[$i]['vImage'])){ ?>
															<img src="../webimages/upload/Driver/<?php echo $db_finished[$i]['iDriverId']."/".$db_finished[$i]['vImage'];?>" alt="User Avatar" class="img-circle"  height="50" width="50"/>
														<?php  } else {?>
															<img src="assets/img/profile-user-img.png" alt="" class="img-circle"  height="50" width="50">
														<?php } ?>
														<? }else{?>

														<img src="assets/img/profile-user-img.png" alt="" class="img-circle"  height="50" width="50">
														<?}?>
													</span>
													<div class="chat-body clearfix">
														<div class="header">
															<small class=" text-muted label label-info">
																<i class="icon-time"></i> <?php
																	$regDate=$db_finished[$i]['tEndDate'];
																	$dif=strtotime(Date('Y-m-d H:i:s'))-strtotime($regDate);
																	if($dif<60)
																	{
																		$time=floor($dif/(60));
																		echo "Just Now";
																	}
																	else if($dif<3600)
																	{
																		$time=floor($dif/(60));
																		$texts = "Minute";
																		if($time > 1) {
																			$texts = "Minutes";
																		}
																		echo $time." $texts ago";
																	}
																	else if($dif<86400)
																	{
																		$time=floor($dif/(60*60));
																		$texts = "Hour";
																		if($time > 1) {
																			$texts = "Hours";
																		}
																		echo $time." $texts ago";
																	}
																	else
																	{
																		$time=floor($dif/(24*60*60));
																		$texts = "Day";
																		if($time > 1) {
																			$texts = "Days";
																		}
																		echo $time." $texts ago";
																	}
																?></small>
																<strong class="pull-right primary-font"> <?php //echo $generalobjAdmin->clearName($db_finished[$i]['vName']." ".$db_finished[$i]['vLastName']); ?></strong>
														</div>
														<br />
														<p>
															<?php echo $db_finished[$i]['tSaddress']." --> ".$db_finished[$i]['tDaddress']."<br/>";
																echo "<b>Status: ".$db_finished[$i]['iActive']."</b>";
															?>
														</p>
													</div>
												</a>
												</li>
											<?php }?>
										</ul>
									</div>
								<?php } ?>
						</div>


					</div>
					<div class="col-lg-6">
						<div class="panel panel-danger">
								<div class="panel-heading">
									<div class="panel-title-box">
									   <i class="icon-bell"></i> Notifications Alerts Panel
									</div>                                  
								</div>

							<div class="panel-body">
								<?php
								if(count($db_notification)>0)
								{
								for($i=0;$i<count($db_notification);$i++) {?>
										<div class="list-group">
											<?php
												if($db_notification[$i]['doc_usertype']=='driver'){
													$url = "driver_document_action.php";
													$id = $db_notification[$i]['iDriverId'];
													if($db_notification[$i]['doc_name_'.$default_lang] != ''){
														$msg = strtoupper($db_notification[$i]['doc_name_'.$default_lang])." uploaded by ".$langage_lbl['LBL_DRIVER_TXT_ADMIN']." : ".$db_notification[$i]['Driver'];
													} else {
														$msg = $db_notification[$i]['doc_name_'.$default_lang]." uploaded by ".$langage_lbl['LBL_DRIVER_TXT_ADMIN']." : ".$db_notification[$i]['Driver'];
													}
												}
												else if($db_notification[$i]['doc_usertype']=='company')
												{
													$url = "company_document_action.php";
													$id = $db_notification[$i]['iCompanyId'];
													if($db_notification[$i]['doc_name_'.$default_lang] != ''){
														$msg = strtoupper( $db_notification[$i]['doc_name_'.$default_lang])." uploaded by ".$db_notification[$i]['doc_usertype']." : ".$db_notification[$i]['vCompany'];
													} else {
														$msg = $db_notification[$i]['doc_name_'.$default_lang]." uploaded by ".$db_notification[$i]['doc_usertype']." : ".$db_notification[$i]['vCompany'];
													}
												}
												else if($db_notification[$i]['doc_usertype']=='car')
												{
													$url = "vehicle";
													$id = $db_notification[$i]['iDriverVehicleId'];
													if($db_notification[$i]['doc_name_'.$default_lang] != ''){
														$msg =strtoupper($db_notification[$i]['doc_name_'.$default_lang])." uploaded by ".$langage_lbl['LBL_DRIVER_TXT_ADMIN'] ." : ".$db_notification[$i]['DriverName'];
													} else {
														$msg =$db_notification[$i]['doc_name_'.$default_lang]." uploaded by ".$langage_lbl['LBL_DRIVER_TXT_ADMIN'] ." : ".$db_notification[$i]['DriverName'];
													}
												}
												?>
												<a href="<?=$url;?>?id=<?echo $id;?>&action=edit" class="list-group-item">
													<i class=" icon-comment"></i>
													<?=$msg ;?>
													<span class="pull-right text-muted small">
													<em>
														<?php 
														
														/*$reDate=$db_notification[$i]['edate'];

															$dif=strtotime(Date('Y-m-d H:i:s'))-strtotime($reDate);
															if($dif<3600)
															{
																$time=floor($dif/(60));
																echo $time." minites ago";
															}
															else if($dif<86400)
															{
																$time=floor($dif/(60*60));
																echo $time." hour ago";
															}
															else
															{
																$time=floor($dif/(24*60*60));
																echo $time." Days ago";
															}	*/											
															
															$reDate=$db_notification[$i]['edate']; 
															$dif=strtotime(Date('Y-m-d H:i:s'))-strtotime($reDate);
															if($dif<60)
															{
																$time=floor($dif/(60));
																echo "Just Now";
															}
															else if($dif<3600)
															{
																$time=floor($dif/(60));
																$texts = "Minute";
																if($time > 1) {
																	$texts = "Minutes";
																}
																echo $time." $texts ago";
															}
															else if($dif<86400)
															{
																$time=floor($dif/(60*60));
																$texts = "Hour";
																if($time > 1) {
																	$texts = "Hours";
																}
																echo $time." $texts ago";
															}
															else
															{
																$time=floor($dif/(24*60*60));
																$texts = "Day";
																if($time > 1) {
																	$texts = "Days";
																}
																echo $time." $texts ago";
															}


														?>
													</em>
													</span>
												</a>

												</div>

								<?} }
											else
											{
												echo "No Notification";
											}

											?>
								</div>

							</div>



						</div>
					</div>
					<!-- END COMMENT AND NOTIFICATION  SECTION -->
				</div>
			</div>

			<!--END PAGE CONTENT -->
		</div>

 <?php include_once('footer/footer_home.php');?>
<script src="assets/js/jquery-ui.min.js"></script>
<script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $tconfig["tsite_url_main_admin"]?>css/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/bootstrap-datetimepicker.min.js"></script>
 <?php include_once('top/footer_script.php');?>
       <link rel="stylesheet" type="text/css" href="admin/css/admin_new/admin_style.css">
	</body>
	<style type="text/css">
		body{
			background-color: white;
		}

	</style>
</html>
<script>
	$(document).ready(function(){
			/* Donut dashboard chart */
			 var total_ride = '<?=$totalRides;?>';
			 var complete_ride = '<?=$finishRides;?>';
			 var cancel_ride = '<?=$cancelRides;?>';
			 var on_ride = '<?=$onRides;?>';			
			
			 //var total_ride = 0;	
			 //var complete_ride = 0;
			 //var cancel_ride = 0;
			 //var on_ride = 0;
			
	        if(complete_ride > 0 || cancel_ride > 0 || total_ride > 0 ) 
			{
			    Morris.Donut({
				element: 'dashboard-rides',
				data: [
					{label: "On Going", value: on_ride},
					{label: "Completed", value: complete_ride},
					{label: "Cancelled", value: cancel_ride}
				],
				
				formatter: function (x) { return (x/total_ride *100).toFixed(2)+'%'+ ' ('+x+')'; },
				colors: ['#33414E', '#1caf9a', '#FEA223'],
				resize: true
				});
			} 
			else
			{					
				Morris.Donut({
				element: 'dashboard-rides',
				data: [
					{label: "On Going", value: on_ride},
					{label: "Completed", value: complete_ride},
					{label: "Cancelled", value: cancel_ride}
				],
				backgroundColor: '#f7f7f7',				
				formatter: function (x) { return (0)+' %'+ ' ('+x+')'; },
				colors: ['#33414E', '#1caf9a', '#FEA223'],
				resize: true
				});					
			}
				
			
			
			var total_drive = '<?=$driver;?>';
			var active_drive = '<?=$actDrive;?>';
			var inactive_drive = '<?=$inaDrive;?>';
			Morris.Donut({
				element: 'dashboard-drivers',
				data: [
					{label: "Active", value: active_drive},
					{label: "Pending", value: inactive_drive},
				],
				formatter: function (x) { return (x/total_drive *100).toFixed(2)+'%'+ '('+x+')'; },
				colors: ['#33414E', '#1caf9a', '#FEA223'],
				resize: true
			});
			/* END Donut dashboard chart */
	});
</script>
<script type="text/javascript">
  window.onload = getPageLoadTime;

</script>

<?php

 function getDriverDetailsDashboard ($iCompanyId,$status)
		{
			$cmp_ssql = "";
			if(SITE_TYPE =='Demo'){
				$cmp_ssql = " And rd.tRegistrationDate > '".WEEK_DATE."'";
			}
			global $obj;
			$ssl = "";
			if(isset($status) && $status != "" && $status == "active") {
				$ssl = " AND rd.eStatus = '".$status."'";
			} else if(isset($status) && $status != "" && $status == "inactive") {
				$ssl = " AND rd.eStatus = '".$status."'";
			}

			$ssql1 = "AND (rd.vEmail != '' OR rd.vPhone != '')";

			$sql = "SELECT count(rd.iDriverId) as Total FROM register_driver rd LEFT JOIN company c ON rd.iCompanyId = c.iCompanyId and c.eStatus != 'Deleted' WHERE c.iCompanyId='$iCompanyId' and   rd.eStatus != 'Deleted'".$ssl.$ssql1.$cmp_ssql;
			$data = $obj->MySQLSelect($sql);

			return $data[0]['Total'];
		}


		 function getRiderCount ($status="")
		{
			global $obj;
			$cmp_ssql = "";
			if(SITE_TYPE =='Demo'){
				$cmp_ssql = " And tRegistrationDate > '".WEEK_DATE."'";
			}

			$ssql1 = "AND (vEmail != '' OR vPhone != '')";
			if($status=="all")
				$sql = "SELECT count(iUserId) as tot_rider FROM register_user WHERE 1 = 1 ".$ssql1.$cmp_ssql;
			else
				$sql = "SELECT count(iUserId) FROM register_user WHERE eStatus != 'Deleted'".$ssql1.$cmp_ssql;
			$data = $obj->MySQLSelect($sql);

			return $data;
		}

			 function getTotalEarns($iCompanyId) {
			$cmp_ssql = "";
			if(SITE_TYPE =='Demo'){
				$cmp_ssql = " And t.tEndDate > '".WEEK_DATE."'";
			}
			global $obj;
			$sql = "SELECT SUM( `fCommision` ) AS total FROM trips t join register_driver rd on  t.iDriverId=rd.iDriverId  WHERE  rd.iCompanyId='$iCompanyId' ".$cmp_ssql;
			$data = $obj->MySQLSelect($sql);
			$result = $data[0]['total'];
			return $result;
		}

			 function getTripStates($iCompanyId,$tripStatus=NULL,$startDate="",$endDate="")
		{
			$cmp_ssql = "";
			$dsql = "";
			if(SITE_TYPE =='Demo'){
				$cmp_ssql = " And t.tTripRequestDate > '".WEEK_DATE."'";
			}
			global $obj;
			$data = array();
			
			if($startDate!= "" && $endDate != "")
			{
				$dsql = " AND t.tTripRequestDate BETWEEN '".$startDate."' AND '".$endDate."'";
				//$dsql = " AND tTripRequestDate >= '".$startDate."' OR tTripRequestDate <= '".$endDate."' ";
			}
			
			if($tripStatus != "") {
				if($tripStatus == "on ride") {
					$ssl = " AND (t.iActive = 'On Going Trip' OR t.iActive = 'Active') AND t.eCancelled='No'";
				}else if($tripStatus == "cancelled") {
					$ssl = " AND (t.iActive = 'Canceled' OR t.eCancelled='yes')";
				}else if($tripStatus == "finished") {
					$ssl = " AND t.iActive = 'Finished' AND t.eCancelled='No'";
				}else {
					$ssl = "";
				}
				
				$sql = "SELECT COUNT(t.iTripId) as tot FROM trips t join register_driver rd on  t.iDriverId=rd.iDriverId     WHERE rd.iCompanyId='$iCompanyId' ".$cmp_ssql.$ssl.$dsql;
				$data = $obj->MySQLSelect($sql);
			}
			return $data[0]['tot'];
		}



			 function getTripDateStates($iCompanyId,$time) {
			global $obj;
			$data = array();
			$cmp_ssql = "";
			if(SITE_TYPE =='Demo'){
				$cmp_ssql = " And t.tEndDate > '".WEEK_DATE."'";
			}
			if($time == "month") {
				$startDate = date('Y-m')."-01 00:00:00";
				$endDate = date('Y-m')."-31 23:59:59";
				$ssl = " AND t.tTripRequestDate BETWEEN '".$startDate."' AND '".$endDate."'";
			}else if($time == "year") {
				$startDate1 = date('Y')."-00-01 00:00:00";
				$endDate1 = date('Y')."-12-31 23:59:59";
				$ssl = " AND t.tTripRequestDate BETWEEN '".$startDate1."' AND '".$endDate1."'";
			}else {
				$startDate2 = date('Y-m-d')." 00:00:00";
				$endDate2 = date('Y-m-d')." 23:59:59";
				$ssl = " AND t.tTripRequestDate BETWEEN '".$startDate2."' AND '".$endDate2."'";
			}
			
			$sql = "SELECT count(t.iTripId) as total FROM trips t join register_driver rd on  t.iDriverId=rd.iDriverId     WHERE rd.iCompanyId='$iCompanyId' ".$ssl.$cmp_ssql;
			$data = $obj->MySQLSelect($sql);
			return $data[0]['total'];
		}


		function getDriverDateStatus($iCompanyId,$time) {
			$cmp_ssql = "";
			if(SITE_TYPE =='Demo'){
				$cmp_ssql = " And rd.tRegistrationDate > '".WEEK_DATE."'";
			}
			global $obj;
			$data = array();
			if($time == "month") {
				$startDate = date('Y-m')."-00 00:00:00";
				$endDate = date('Y-m')."-31 23:59:59";
				$ssl = " AND rd.tRegistrationDate BETWEEN '".$startDate."' AND '".$endDate."'";
			}else if($time == "year") {
				$startDate1 = date('Y')."-00-00 00:00:00";
				$endDate1 = date('Y')."-12-31 23:59:59";
				$ssl = " AND rd.tRegistrationDate BETWEEN '".$startDate1."' AND '".$endDate1."'";
			}else {
				$startDate2 = date('Y-m-d')." 00:00:00";
				$endDate2 = date('Y-m-d')." 23:59:59";
				$ssl = " AND rd.tRegistrationDate BETWEEN '".$startDate2."' AND '".$endDate2."'";
			}

			$ssql1 = "AND (rd.vEmail != '' OR rd.vPhone != '')";

			$sql = "SELECT rd.*, c.vCompany companyFirstName, c.vLastName companyLastName FROM register_driver rd LEFT JOIN company c ON rd.iCompanyId = c.iCompanyId and c.eStatus != 'Deleted' WHERE c.iCompanyId='$iCompanyId' and   rd.eStatus != 'Deleted'".$ssl.$ssql1.$cmp_ssql;
			$data = $obj->MySQLSelect($sql);
			return $data;
		}

?>