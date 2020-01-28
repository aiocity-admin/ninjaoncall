<?php
	include_once('../common.php');
	
	if(!isset($generalobjAdmin)){
		require_once(TPATH_CLASS."class.general_admin.php");
		$generalobjAdmin = new General_admin();
	}
	$generalobjAdmin->check_member_login();
	
	$id 	= isset($_REQUEST['id'])?$_REQUEST['id']:'';
	$action = isset($_REQUEST['action'])?$_REQUEST['action']:'view';
	$iLocatioId = isset($_REQUEST['iLocatioId'])?$_REQUEST['iLocatioId']:'';	
	$status 	= isset($_REQUEST['status'])?$_REQUEST['status']:'';
	$success	= isset($_REQUEST['success'])?$_REQUEST['success']:0;
	$script 	= "Location Wise Fare";
	
	if($iLocatioId != '' && $status != ''){
		if(SITE_TYPE !='Demo'){
			$query = "UPDATE location_wise_fare SET eStatus = '".$status."' WHERE iLocatioId = '".$iLocatioId."'";
			$obj->sql_query($query);
			$success = "1";
			$succe_msg =" Location Wise Fare".$status." successfully.";
			header("Location:location_wise_fare.php?action=view&success=1&succe_msg=".$succe_msg);exit;
		}
		else{
			header("Location:location_wise_fare.php?success=2");exit;
		}
	}
	
	
	$hdn_del_id = isset($_REQUEST['hdn_del_id'])?$_REQUEST['hdn_del_id']:'';
	if($action == 'delete' && $hdn_del_id != '')
	{
		
		if(SITE_TYPE !='Demo'){
			$query = "DELETE FROM location_wise_fare WHERE iLocatioId = '".$hdn_del_id."'";
			$obj->sql_query($query);
			$action = "view";
			$success = "1";
			$succe_msg = "Location Wise Fare deleted successfully.";
			header("Location:location_wise_fare.php?action=view&success=1&succe_msg=".$succe_msg);
			exit;
		}
		else{
			header("Location:location_wise_fare.php?success=2");exit;
		}
	}
	$cmp_ssql = "";
	if(SITE_TYPE =='Demo'){
		$cmp_ssql = " And tRegistrationDate > '".WEEK_DATE."'";
	}
	
	if($action == 'view')
	{
		/* $sql = "SELECT ct.*,st.iStateId,st.vState,c.iCountryId,c.vCountry FROM city AS ct 
		left join country AS c ON c.iCountryId =ct.iCountryId
		left join state AS st ON st.iStateId=ct.iStateId
		WHERE  ct.eStatus != 'Deleted'".$cmp_ssql;
		$data_drv = $obj->MySQLSelect($sql); */
		
		$sql = "SELECT ls.*,lm1.vLocationName as vToname,lm2.vLocationName as vFromname FROM `location_wise_fare` ls left join location_master lm1 on ls.iToLocationId = lm1.iLocationId left join location_master lm2 on ls.iFromLocationId = lm2.iLocationId WHERE 1 = 1".$cmp_ssql;
		$data_drv = $obj->MySQLSelect($sql);
		
	}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD-->
<head>
<meta charset="UTF-8" />
		<title>Admin | Location Wise Fare </title>
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
		
		<? include_once('global_files.php');?>
		
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
					<div id="add-hide-show-div">
						<div class="row">
							<div class="col-lg-12">
								<h2>Location Wise Fare</h2>
								<a href="location_wise_fare_action.php"><input type="button" id="show-add-form" value="ADD A Location" class="add-btn"></a>
								<input type="button" id="cancel-add-form" value="CANCEL" class="cancel-btn">
							</div>
						</div>
						<hr />
					</div>
					<? if($success == 1) { ?>
						<div class="alert alert-success alert-dismissable">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
							<?php echo isset($_REQUEST['succe_msg'])? $_REQUEST['succe_msg'] : ''; ?>
						</div><br/>
						<? }elseif ($success == 2) { ?>
						<div class="alert alert-danger alert-dismissable">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
							"Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
						</div><br/>
					<? } ?>
					<div class="table-list">
						<div class="row">
							<div class="col-lg-12">
								<div class="panel panel-default">									
									<div class="panel-body">
										<div class="table-responsive"  id="data_drv001">
											<table class="table table-striped table-bordered table-hover admin-td-button" id="dataTables-example">
												<thead>
													<tr>
														<th>To City/Airport Location Name</th>
														<th>From City/Airport Location Name </th>
														<th>Flat Fare</th>												
														<th align="center" style="text-align:center;">Action</th>
													</tr>
												</thead>
												<tbody>
													<?php for($i=0;$i<count($data_drv);$i++) {?>
														<tr class="gradeA">
															<td><? echo $data_drv[$i]['vToname']; ?></td>
															<td><? echo $data_drv[$i]['vFromname']; ?></td>
															<td><? echo $data_drv[$i]['fFlatfare']; ?></td>				
															
															<td  class="veh_act"  align="center" style="text-align:center;">
																<?php if($data_drv[$i]['eStatus']!="Deleted"){?>
																	<a href="location_wise_fare_action.php?id=<?= $data_drv[$i]['iLocatioId']; ?>" data-toggle="tooltip" title="Edit Location Wise Fare">
																		<img src="img/edit-icon.png" alt="Edit">
																	</a>
																<?php }?>
																
															
																<?php if($data_drv[$i]['eStatus']!="Deleted"){?>
																	<form name="delete_form" id="delete_form" method="post" action="" onSubmit="return confirm_delete()" class="margin0">
																		<input type="hidden" name="hdn_del_id" id="hdn_del_id" value="<?= $data_drv[$i]['iLocatioId']; ?>">
																		<input type="hidden" name="action" id="action" value="delete">
																		<button class="remove_btn001" data-toggle="tooltip" title="Delete Location Wise Fare">
																			<img src="img/delete-icon.png" alt="Delete">
																		</button>
																	</form>
																	
																	
																<?php }?>
															</td>
														</tr>
													<? } ?>
													
												</tbody>
											</table>
										</div>
										
									</div>
								</div>
							</div> <!--TABLE-END-->
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<!--END PAGE CONTENT -->
		</div>
		<!--END MAIN WRAPPER -->
		
		
		<? include_once('footer.php');?>
		<script src="../assets/plugins/dataTables/jquery.dataTables.js"></script>
		<script src="../assets/plugins/dataTables/dataTables.bootstrap.js"></script>
		<script>
           
			
            $(document).ready(function () {
				$('#dataTables-example').dataTable({
					"order": [[ 2, "desc" ]]
				});
			});
            function confirm_delete()
            {
				var confirm_ans = confirm("Are You sure You want to Delete this Location Wise Fare?");
				return confirm_ans;
				//document.getElementById(id).submit();
			}
           
		</script>
	</body>
	<!-- END BODY-->
</html>
