<?
	include_once('../common.php');
	
	if(!isset($generalobjAdmin)){
		require_once(TPATH_CLASS."class.general_admin.php");
		$generalobjAdmin = new General_admin();
	}
	$generalobjAdmin->check_member_login();
	
	$default_lang 	= $generalobj->get_default_lang();
	
	//$hdn_del_id 	= isset($_POST['hdn_del_id'])?$_POST['hdn_del_id']:'';
	$tbl_name 		= 'sms_templates';
	$script 		= 'Settings';
	
	$iSMSId = isset($_REQUEST['iSMSId']) ? $_REQUEST['iSMSId'] : '';
	$estatus = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
	
	if($iSMSId != '' && $estatus != ''){
		  if(SITE_TYPE !='Demo'){ 
		$query = "UPDATE `".$tbl_name."` SET eStatus = '".$estatus."' WHERE iSMSId = '".$iSMSId."'";
		$obj->sql_query($query);
		
		//$query = "UPDATE company SET eStatus = '" . $status . "' WHERE iCompanyId = '" . $iCompanyId . "'";
		//$obj->sql_query($query);
		$var_msg="SMS ".$status." Successfully.";
		header("Location:sms_template.php?success=1&var_msg=".$var_msg);exit;
		
		
	}
	else{  
		header("Location:make.php?success=2");exit;
	}
	}
	
	/* if($hdn_del_id != ''){
		$query = "DELETE FROM `".$tbl_name."` WHERE iEmailId = '".$hdn_del_id."'";
		$obj->sql_query($query);
	} */
	
	$sql = "SELECT * FROM ".$tbl_name." ORDER BY iSMSId DESC";
	$db_data = $obj->MySQLSelect($sql);
	
	//$sql = "SELECT * FROM ".$tbl_name." ORDER BY iCountryId DESC";
	//$db_data = $obj->MySQLSelect($sql);
	
	//echo '<pre>'; print_R($db_data); echo '</pre>';
	
	
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
	
	<!-- BEGIN HEAD-->
	<head>
		<meta charset="UTF-8" />
		<title>Admin | SMS Template</title>
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
		
		<? include_once('global_files.php');?>
		<!-- <script type="text/javascript">
			/* function confirm_delete()
			{
				var confirm_ans = confirm("Are You sure You want to Delete this Page?");
				return confirm_ans;
				//document.getElementById(id).submit();
			} */
		</script> -->
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
							<h2>SMS Templates</h2>
							<!-- <a href="email_template_action.php">
								<input type="button" value="Add Email Template" class="add-btn">
							</a> -->
						</div>
					</div>
					<hr />
					<div class="table-list">
						<div class="row">
							<div class="col-lg-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										SMS Templates
									</div>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover" id="dataTables-example">
												<thead>
													<tr>
														<!--<th>Section</th>-->
														<th>SMS</th>
														<th>Status</th>
														<th>Action</th>
														<!-- <th>Delete</th> -->
													</tr>
												</thead>
												<tbody>
													<? 
														$count_all = count($db_data);
														if($count_all > 0) {
															for($i=0;$i<$count_all;$i++) {
																
															    $iSMSId = $db_data[$i]['iSMSId']; 
																$vSMS_Code = $db_data[$i]['vSMS_Code']; 																
																$eStatus = $db_data[$i]['eStatus'];
																
																
															?>
															<tr class="gradeA">
																<!--<td><?=$vSection;?></td>-->
																<td><?=$vSMS_Code;?></td>
																<td width="20%" align="center" class="center">
																	<? if($eStatus == 'Active') {
																		   $dis_img = "img/active-icon.png";
																			}else if($eStatus == 'Inactive'){
																			 $dis_img = "img/inactive-icon.png";
																				}else if($eStatus == 'Deleted'){
																				$dis_img = "img/delete-icon.png";
																				}?>
																		<img src="<?=$dis_img;?>" alt="<?=$eStatus;?>">
																</td>
																<td width="20%" align="center">
																	<a href="sms_template_action.php?id=<?=$iSMSId;?>" data-toggle="tooltip" title="Edit SMS Template">
																		<button class="remove_btn001">
																			<img src="img/edit-icon.png" alt="Edit">
																		</button>
																	</a>
																	<a href="sms_template.php?iSMSId=<?=$iSMSId;?>&status=Active" data-toggle="tooltip" title="Active SMS">
																		<img src="img/active-icon.png" alt="<?php echo $eStatus ?>" >
																	</a>
																	<a href="sms_template.php?iSMSId=<?=$iSMSId;?>&status=Inactive" data-toggle="tooltip" title="Inactive SMS">
																		<img src="img/inactive-icon.png" alt="<?php echo $eStatus; ?>" >
																	</a>
																</td>
																
																<!-- <td width="10%" align="center">
																	<form name="delete_form" id="delete_form" method="post" action="" onsubmit="return confirm_delete()" class="margin0">
																		<input type="hidden" name="hdn_del_id" id="hdn_del_id" value="<?=$iSMSId;?>">
																		<button class="btn btn-danger">
																			<i class="icon-remove icon-white"></i> Delete
																		</button>
																	</form>
																</td> -->
															</tr>
															<? } 
														} else { ?>
														<tr class="gradeA">
															<td colspan="4">No Records found.</td>
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
				$('#dataTables-example').dataTable();
			});
		</script>
	</body>
	<!-- END BODY-->    
</html>
