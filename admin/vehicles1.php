<?php
	include_once('../common.php');

	if(!isset($generalobjAdmin)){
		require_once(TPATH_CLASS."class.general_admin.php");
		$generalobjAdmin = new General_admin();
	}
	$generalobjAdmin->check_member_login();

	$id 	= isset($_REQUEST['id'])?$_REQUEST['id']:'';
	$action = isset($_REQUEST['action'])?$_REQUEST['action']:'view';
	$actionSearch = isset($_REQUEST['actionSearch'])?$_REQUEST['actionSearch']:'';
	$iDriverId = isset($_REQUEST['iDriverId'])?$_REQUEST['iDriverId']:'';
	$status 	= isset($_REQUEST['status'])?$_REQUEST['status']:'';
	$success	= isset($_REQUEST['success'])?$_REQUEST['success']:0;
	$iDriverVehicleId=isset($_REQUEST['iDriverVehicleId'])?$_REQUEST['iDriverVehicleId']:'';
	$script 	= "Vehicle";
	

	if($iDriverVehicleId != '' && $status != ''){
		if(SITE_TYPE !='Demo'){
		$query = "UPDATE driver_vehicle SET eStatus = '".$status."' WHERE iDriverVehicleId = '".$iDriverVehicleId."'";
		$obj->sql_query($query);
		if($SEND_TAXI_EMAIL_ON_CHANGE == 'Yes') {
			$sql23 = "SELECT m.vMake, md.vTitle,rd.vEmail, rd.vName, rd.vLastName, c.vName as companyFirstName
					FROM driver_vehicle dv, register_driver rd, make m, model md, company c
					WHERE
					  dv.eStatus != 'Deleted'
					  AND dv.iDriverId = rd.iDriverId
					  AND dv.iCompanyId = c.iCompanyId
					  AND dv.iModelId = md.iModelId
					  AND dv.iMakeId = m.iMakeId AND dv.iDriverVehicleId = '".$iDriverVehicleId."'";
			$data_email_drv = $obj->MySQLSelect($sql23);
			$maildata['EMAIL'] =$data_email_drv[0]['vEmail'];
			$maildata['NAME'] = $data_email_drv[0]['vName'];
			$maildata['DETAIL']="Your ".$langage_lbl_admin['LBL_TEXI_ADMIN']." ".$data_email_drv[0]['vTitle']." For COMPANY ".$data_email_drv[0]['companyFirstName'] ." is temporarly ".$status;
			$generalobj->send_email_user("ACCOUNT_STATUS",$maildata);
		}
		$succe_msg = $langage_lbl_admin['LBL_TEXI_ADMIN']." ".$status." Successfully.";
		header("Location:vehicles.php?success=1&succe_msg=".$succe_msg);exit;
	}
	else{
		header("Location:vehicles.php?success=2");exit;
	}
	if($APP_TYPE == 'UberX'){

		$sql = "SELECT dv.*,rd.vName, rd.vLastName,dv.vLicencePlate, c.vCompany FROM driver_vehicle dv, register_driver rd,company c
				WHERE  dv.eStatus != 'Deleted'  AND dv.iDriverId = rd.iDriverId  AND dv.iCompanyId = c.iCompanyId
				  ". $cmp_ssql;
		$data_drv = $obj->MySQLSelect($sql);

	}else{

		$sql = "SELECT dv.*, m.vMake, md.vTitle,rd.vEmail, rd.vName, rd.vLastName, c.vName as companyFirstName, c.vLastName as companyLastName
				FROM driver_vehicle dv, register_driver rd, make m, model md, company c
				WHERE
				  dv.eStatus != 'Deleted'
				  AND dv.iDriverId = rd.iDriverId
				  AND dv.iCompanyId = c.iCompanyId
				  AND dv.iModelId = md.iModelId
				  AND dv.iMakeId = m.iMakeId";
		$data_drv = $obj->MySQLSelect($sql);

	}		

		$sql = "select iDriverId from driver_vehicle WHERE iDriverVehicleId = '".$iDriverVehicleId."'";
		$db_member = $obj->MySQLSelect($sql);

		$sql = "select count(iDriverVehicleId) as total from driver_vehicle WHERE iDriverId = '".$db_member[0]['iDriverId']."' AND eStatus='Active'";
		$db_vehicle_NO = $obj->MySQLSelect($sql);


		if($db_vehicle_NO[0]['total']>0)
		{

		}
		else {
			$query = "UPDATE register_driver SET eStatus = 'inactive' WHERE iDriverId = '".$db_member[0]['iDriverId']."'";
			$obj->sql_query($query);

		}
	}

	$sql = "select * from country";
	$db_country = $obj->MySQLSelect($sql);

	$sql = "select * from language_master where eStatus = 'Active'";
	$db_lang = $obj->MySQLSelect($sql);
	$hdn_del_id = isset($_REQUEST['hdn_del_id'])?$_REQUEST['hdn_del_id']:'';
	if($action == 'delete' && $hdn_del_id != '')
	{
		//$query = "DELETE FROM `".$tbl_name."` WHERE iDriverId = '".$id."'";
		if(SITE_TYPE !='Demo'){
		$query = "UPDATE driver_vehicle SET eStatus = 'Deleted' WHERE iDriverVehicleId = '".$hdn_del_id."'";
		$obj->sql_query($query);
		$action = "view";
		$success = "1";
		$succe_msg =$langage_lbl_admin['LBL_TEXI_ADMIN']." deleted successfully.";
		}
		else{
			header("Location:vehicles.php?success=2");exit;
		}
		$sql = "select iDriverId from driver_vehicle WHERE iDriverVehicleId = '".$hdn_del_id."'";
		$db_member = $obj->MySQLSelect($sql);

		$sql = "select count(iDriverVehicleId) as total from driver_vehicle WHERE iDriverId = '".$db_member[0]['iDriverId']."' AND eStatus='Active'";
		$db_vehicle_NO = $obj->MySQLSelect($sql);


		if($db_vehicle_NO[0]['total']>0)
		{

		}
		else {
	$query = "UPDATE register_driver SET eStatus = 'inactive' WHERE iDriverId = '".$db_member[0]['iDriverId']."'";
			$obj->sql_query($query);

		}


		header("Location:vehicles.php?action=view&success=1&succe_msg=".$succe_msg);
		exit;
	}
	
	$cmp_ssql = "";
	if(SITE_TYPE =='Demo'){
		$cmp_ssql = " And rd.tRegistrationDate > '".WEEK_DATE."'";
	}
	
	$sql = "select * from company WHERE 1=1 order by vCompany";
	$db_company = $obj->MySQLSelect($sql);	
	$ssql ='';
	
	if($actionSearch!='')
	{
		$iCompanyId = $_REQUEST['iCompanyId'];
		$iDriverId = $_REQUEST['iDriverId'];
		if($iCompanyId!='')
		{
			if($iCompanyId!='' && $iDriverId!='')
			{			
				$ssql .= " AND dv.iDriverId = '$iDriverId' AND dv.iCompanyId = '$iCompanyId'";	
			}
			else
			{
				$ssql .= " AND dv.iCompanyId = '$iCompanyId'";	
			}		
		}
		else if($iDriverId!='')
		{
			$ssql .= " AND dv.iDriverId = '$iDriverId' ";				 
		}
	}
	
	if($action == 'view')
	{
		if($APP_TYPE == 'UberX'){

			$sql = "SELECT dv.*,rd.vName, rd.vLastName,dv.vLicencePlate, c.vCompany FROM driver_vehicle dv, register_driver rd,company c
				WHERE  dv.eStatus != 'Deleted'  AND dv.iDriverId = rd.iDriverId  AND dv.iCompanyId = c.iCompanyId
				  ". $cmp_ssql;
		$data_drv = $obj->MySQLSelect($sql);	

		}else{   
			/*$sql = "SELECT dv.*, m.vMake, md.vTitle, rd.vName, rd.vLastName, c.vCompany 
				FROM driver_vehicle dv, register_driver rd, make m, model md, company c
				WHERE
				  dv.eStatus != 'Deleted'
				  AND dv.iDriverId = rd.iDriverId
				  AND dv.iCompanyId = c.iCompanyId
				  AND dv.iModelId = md.iModelId
				  AND dv.iMakeId = m.iMakeId". $cmp_ssql .$ssql ."
				  ORDER BY dv.iDriverVehicleId DESC";*/  
          
        //Chirag Changes  
      	$sql = "SELECT dv.*, m.vMake, md.vTitle, rd.vName, rd.vLastName, c.vCompany 
				FROM driver_vehicle dv, register_driver rd, make m, model md, company c
				WHERE
          dv.eStatus IN('Active','InActive','Deleted')
				  AND dv.iDriverId = rd.iDriverId
				  AND dv.iCompanyId = c.iCompanyId
				  AND dv.iModelId = md.iModelId
				  AND dv.iMakeId = m.iMakeId". $cmp_ssql .$ssql ."
				  ORDER BY dv.iDriverVehicleId DESC";
		$data_drv = $obj->MySQLSelect($sql);

		}
		
		//echo '<pre>'; print_R($data_drv); echo '</pre>';exit;
	}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

	<!-- BEGIN HEAD-->
	<head>
		<meta charset="UTF-8" />
		<title>Admin | <?php echo $langage_lbl_admin['LBL_VEHICLE_TXT_ADMIN'];?></title>
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
								<h2><?php echo $langage_lbl_admin['LBL_VEHICLE_CAPITAL_TXT_ADMIN'];?></h2>
								<a href="vehicle_add_form.php"><input type="button" id="show-add-form" value="ADD <?php echo $langage_lbl_admin['LBL_VEHICLE_CAPITAL_TXT_ADMIN'];?>" class="add-btn"></a>
								<input type="button" id="cancel-add-form" value="CANCEL" class="cancel-btn">
							</div>
						</div>
						<hr />
					</div>
					<? if($success == 1) { ?>
						<div class="alert alert-success alert-dismissable msgs_hide">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
							<?php echo isset($_REQUEST['succe_msg'])? $_REQUEST['succe_msg'] : ''; ?>
						</div><br/>
					<? } elseif ($success == 2) { ?>
						<div class="alert alert-danger alert-dismissable msgs_hide">
								 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
								 "Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
						</div><br/>
					<? } ?>
					
					<? if($success == 3) { ?>
								<div class="alert alert-success alert-dismissable msgs_hide">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
									<?=$langage_lbl_admin['LBL_TEXI_ADMIN'];?> Updated successfully.
								</div><br/>
								<? }else if($success == 4){?>
								<div class="alert alert-danger alert-dismissable msgs_hide">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
									<?= isset($_REQUEST['error_msg']) ? $_REQUEST['error_msg'] : ' '; ?>
								</div><br/>
							<?} ?>
					<div class="table-list">
						<div class="row">
							<div class="col-lg-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<?php echo $langage_lbl_admin['LBL_VEHICLE_TXT_ADMIN'];?>
									</div>
									<form name="frmsearch" id="frmsearch" action="javascript:void(0);" method="POST" >
									<input type="hidden" name="actionSearch" id="actionSearch" value="search" />

										<div class="panel-heading">								
										  <select name="iCompanyId" id="iCompanyId" class="form-control input-sm" style="width:18%;display:table-row-group;"  onchange="driverList(this.value);">
											<option value="">Search By Company Name</option>
											<?for($j=0;$j<count($db_company);$j++){?>
											<option value="<?=$db_company[$j]['iCompanyId'];?>" <?if($iCompanyId == $db_company[$j]['iCompanyId']){?>selected <?}?>><?=$db_company[$j]['vCompany'];?></option>
											<?}?>
										  </select>
										   <select name="iDriverId" id="iDriverId" class="form-control input-sm" style="width:18%;display:table-row-group;">
												
											  </select>
											  <button class="btn btn-default" onClick="search_filters();">Search</button>
										</div>
									</form>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover" id="dataTables-example">
												<thead>												
													<tr>
														<th style="display:none;"></th>
														<th><?php echo $langage_lbl_admin['LBL_VEHICLE_CAPITAL_TXT_ADMIN'];?></th>
														<th>COMPANY</th>
														<th><?php echo $langage_lbl_admin['LBL_DRIVER_NAME_ADMIN'];?></th>
														<th>STATUS</th>
														<?php if($APP_TYPE != 'UberX'){ ?> 
														<th>EDIT DOCUMENTS</th>
														<?php } ?> 
														<th align="center" style="text-align:center;">ACTION</th>
													</tr>
												</thead>
												<tbody>
													<?php for($i=0;$i<count($data_drv);$i++) {?>
														<tr class="gradeA">
														<?php

														if($APP_TYPE == 'UberX'){
															$vname = $data_drv[$i]['vLicencePlate'];

														}else{

															$vname = $data_drv[$i]['vTitle'];
														}

														 ?>
															<td data-order="<?=$data_drv[$i]['iDriverVehicleId']; ?>"  style="display:none;"></td>
															<td ><? echo $data_drv[$i]['vMake'].' '.$vname; ?></td>
															<td ><? echo $data_drv[$i]['vCompany']; ?></td>
															<td ><? echo $data_drv[$i]['vName'].' '.$data_drv[$i]['vLastName']; ?></td>
															<td width="10%" align="center">
																<? if($data_drv[$i]['eStatus'] == 'Active') {
																			$dis_img = "img/active-icon.png";
																		}else if($data_drv[$i]['eStatus'] == 'Inactive'){
																			 $dis_img = "img/inactive-icon.png";
																		}else if($data_drv[$i]['eStatus'] == 'Deleted'){
																			$dis_img = "img/delete-icon.png";
																		}?>
																			<img src="<?=$dis_img;?>" alt="image">
																		
															</td>
															<?php if($APP_TYPE != 'UberX'){ ?> 
															<td align="center" width="12%">
																<a href="vehicle_document_action.php?id=<?= $data_drv[$i]['iDriverVehicleId']; ?>&vehicle=<?= $data_drv[$i]['vMake']?>" data-toggle="tooltip" title="Edit <?=$langage_lbl_admin['LBL_TEXI_ADMIN'];?> Document">
																	<img src="img/edit-doc.png" alt="Edit Document" >
																</a>
															</td>
															<?php } ?> 
															<td width="16%" class="veh_act"  align="center" style="text-align:center;">
																<a href="vehicle_add_form.php?id=<?= $data_drv[$i]['iDriverVehicleId']; ?>&vehicle=<?= $data_drv[$i]['vMake']?>" data-toggle="tooltip" title="Edit <?=$langage_lbl_admin['LBL_TEXI_ADMIN'];?>">
																	<img src="img/edit-icon.png" alt="Edit" >
																</a>
																
																<a href="vehicles.php?iDriverVehicleId=<?= $data_drv[$i]['iDriverVehicleId']; ?>&status=Active" data-toggle="tooltip" title="Active <?=$langage_lbl_admin['LBL_TEXI_ADMIN'];?>	">
																		<img src="img/active-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >
																</a>
																<a href="vehicles.php?iDriverVehicleId=<?= $data_drv[$i]['iDriverVehicleId']; ?>&status=Inactive" data-toggle="tooltip" title="Inactive <?=$langage_lbl_admin['LBL_TEXI_ADMIN'];?>	">
																		<img src="img/inactive-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >
																</a>
																
																<form name="delete_form" id="delete_form" method="post" action="" onSubmit="return confirm_delete()" class="margin0">
																	<input type="hidden" name="hdn_del_id" id="hdn_del_id" value="<?= $data_drv[$i]['iDriverVehicleId']; ?>">
																	<input type="hidden" name="action" id="action" value="delete">
																	<button class="remove_btn001" data-toggle="tooltip" title="Delete <?=$langage_lbl_admin['LBL_TEXI_ADMIN'];?>">
																		<img src="img/delete-icon.png" alt="Delete">
																	</button>
																</form>
																
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
                    <div style="clear:both;"></div>
				</div>
			</div>
			<!--END PAGE CONTENT -->
		</div>
		<!--END MAIN WRAPPER -->


		<? include_once('footer.php');?>
		<script src="../assets/plugins/dataTables/jquery.dataTables.js"></script>
		<script src="../assets/plugins/dataTables/dataTables.bootstrap.js"></script>
		<script>
			var successMsg ='<?php echo $success?>';
			if(successMsg != ''){
				
				setTimeout(function() {
				        $(".msgs_hide").hide(1000)
				    }, 5000);


			}
			$(document).ready(function () {
				$('#dataTables-example').dataTable({
					order: [[0, 'desc']]
				});
			});
			function confirm_delete()
			{
				var confirm_ans = confirm("Are You sure You want to Delete this <?=$langage_lbl_admin['LBL_TEXI_ADMIN'];?>?");
				return confirm_ans;
				document.getElementById('delete_form').submit();
			}
			function changeCode(id)
			{
				var request = $.ajax({
					type: "POST",
					url: 'change_code.php',
					data: 'id=' + id,
					success: function (data)
					{
						document.getElementById("code").value = data;
					}
				});
			}
			
			$('#iDriverId').hide();
			function driverList(companyId,iDriverId){
				
				var request = $.ajax({
					type: "POST",
					url: 'ajax_driver_list.php',
					data: {id:companyId,iDriverId:iDriverId},
					success: function (data)
					{					
						if(data != ''){
							$('#iDriverId').show();
							$('#iDriverId').html(data);					
							
						}else{
							$('#iDriverId').hide();						
							
						}
						
						//document.getElementById("code").value = data;
					}
				});
				
				
			}
			
			function search_filters() {
				document.frmsearch.action="";
				document.frmsearch.submit();
			}
		</script>
		<?php 
			if($actionSearch!=''){		
				?>
				<script> $('#iDriverId').show();	
					var iDriverId = '<?php echo $iDriverId?>';
					var iCompanyId = '<?php echo $iCompanyId?>';
					driverList(iCompanyId,iDriverId);
				</script>
				
			<?php }
			?>
	</body>
	<!-- END BODY-->
</html>
