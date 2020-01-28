<?php
include_once('../common.php');
	if(!isset($generalobjAdmin)){
	require_once(TPATH_CLASS."class.general_admin.php");
	$generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$iCompanyId=$_REQUEST['iCompanyId'];
$vCarType = isset($_POST['vCarType']) ? $_POST['vCarType'] : '';
$assiged_services = isset($_POST['assiged_services']) ? $_POST['assiged_services'] : '';

if (isset($_POST['submit'])) {

$query_delete="delete from company_services where CompanyId='$iCompanyId'";	
$obj->MySQLSelect($query_delete);

for ($i=0; $i <count($vCarType) ; $i++) 
{ 

$query="INSERT INTO `company_services`(`CompanyId`, `ServiceId`) VALUES ('$iCompanyId','$vCarType[$i]')";
$obj->MySQLSelect($query);

$assiged_services=str_replace($vCarType[$i].',', '', $assiged_services);
}
$assiged_services=rtrim(trim($assiged_services), ',');

if ($assiged_services!="") {
	# code...

$assiged_services=explode(',', $assiged_services);

for ($i=0; $i <count($assiged_services) ; $i++) { 

$query_providerList="SELECT distinct `iDriverId`,`vCarType` FROM `driver_vehicle` where iCompanyId=$iCompanyId and vCarType like '%$assiged_services[$i]%'";
$providerList=$obj->MySQLSelect($query_providerList);
foreach ($providerList as $key => $value)
 {
$provide_CarType=str_replace($assiged_services[$i].',', '', $value['vCarType']);
$provide_CarType=str_replace($assiged_services[$i], '', $provide_CarType);
$iDriverId=$value['iDriverId'];
$update_provider="update driver_vehicle set vCarType='$provide_CarType' where iDriverId='$iDriverId' and iCompanyId='$iCompanyId'";
$obj->MySQLSelect($update_provider);
 }
}
}

header("Location:company.php?assiged=1");

}

?>
<!DOCTYPE html>
<html lang="en">
	<!-- BEGIN HEAD-->
	<head>
		<meta charset="UTF-8" />
		<title><?=$SITE_NAME?> | Services</title>
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<meta content="" name="keywords" />
		<meta content="" name="description" />
		<meta content="" name="author" />
		<link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
		<? include_once('global_files.php');?>
		<link href="../assets/css/jquery-ui.css" rel="stylesheet" />
		<link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
		<link rel="stylesheet" href="../assets/validation/validatrix.css" />
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
							<h2><?= $action." ".$langage_lbl_admin['LBL_SERVICE_ADMIN'];?></h2>
							<a href="company.php" class="back_link">
								<input type="button" value="<?=$langage_lbl_admin['LBL_BACK_SERVICE_LISTING_ADMIN'];?>" class="add-btn">
							</a>
						</div>
					</div>
					<hr />
					<div class="body-div">
						<div class="form-group">
							<? if ($success == 3) {?>
                            <div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <?php print_r($error); ?>
                            </div><br/>
                            <?} ?>
							<form name="vehicle_form" id="vehicle_form" method="post" action="">	
							<input type="hidden" name="iCompanyId" value="<?=$iCompanyId;?>">
								

	<div class="checkbox-group required add-services-hatch car-type-custom">
								<ul>
								<?php


$getvehiclecat = "SELECT vc.iVehicleCategoryId, vc.vCategory_EN AS main_cat FROM vehicle_category AS vc WHERE vc.eStatus='Active' AND vc.iParentId='0'";
			$vehicle_type_data = $obj->MySQLSelect($getvehiclecat);
			$i = 0;
			foreach ($vehicle_type_data as $key => $val) {
				$vehicle_type_sql = "SELECT vt.vVehicleType,vc.iParentId,vc.vCategory_EN,vc.iVehicleCategoryId from  vehicle_type as vt  left join vehicle_category as vc on vt.iVehicleCategoryId = vc.iVehicleCategoryId where   vc.iParentId ='".$val['iVehicleCategoryId']."'  AND vc.eStatus='Active' GROUP BY vc.iVehicleCategoryId";
				$vehicle_type_dataOld = $obj->MySQLSelect($vehicle_type_sql);

				$vehicle_type_data[$i]['SubCategory'] = $vehicle_type_dataOld;
			$j = 0;
				foreach ($vehicle_type_dataOld as $subkey => $subvalue) {
					$vehicle_type_sql1 = "SELECT vt.*,vc.*,lm.vLocationName FROM vehicle_type AS vt LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId LEFT JOIN location_master AS lm ON lm.iLocationId = vt.iLocationid WHERE  vc.iVehicleCategoryId = '".$subvalue['iVehicleCategoryId']."'";
					$vehicle_type_dataNew = $obj->MySQLSelect($vehicle_type_sql1);
					$vehicle_type_data[$i]['SubCategory'][$j]['VehicleType'] = $vehicle_type_dataNew;
					$j++;
				}

				$i++;
			} 
$query="select ServiceId from company_services where CompanyId='$iCompanyId'";

$assiged=$obj->MySQLSelect($query);
//print_r($vehicle_type_data);

$assiged_services='';
									foreach ($vehicle_type_data as $key => $value) {
										foreach ($value['SubCategory'] as $Vehicle_Type) {
										if(!empty($Vehicle_Type['VehicleType'])) { ?>
										<fieldset>
											<?php
											$vname=$Vehicle_Type['vCategory_EN'];

											 ?>
											 
											<legend>
												<strong><?php echo $vname;?></strong>
											</legend>
										
											<?php foreach($Vehicle_Type['VehicleType'] as $val) {
												
									  			$vehicle_typeName =$val['vVehicleType_EN'];

									  		if(!empty($val['vLocationName'])) {
												$localization = '(Location : '.$val["vLocationName"].')';
											} else {
												$localization = '';
											}
									  		?>
												<li style="list-style: outside none none;">
													<b><?php echo $vehicle_typeName;?><br/>
														<span style="font-size: 12px;"><?php echo $localization;?></span>
													</b>
													<div class="make-switch" data-on="success" data-off="warning">
														<input type="checkbox" value="<?=$val['iVehicleTypeId'] ?>" class="chk" name="vCarType[]" id="vCarType_<?=$val['iVehicleTypeId'] ?>" <?php foreach($assiged as $k => $service_assiged) { 
															if ($val['iVehicleTypeId']==$service_assiged['ServiceId']) {
																echo "checked";
																$assiged_services.=$val['iVehicleTypeId'].",";
															}

														} ?>/>
													</div>
													<?php 
											
													?>
												</li>
											<?php 



										} ?>
										</fieldset>
								<?php }
									}
								} ?>
								<input type="hidden" name="assiged_services" value="<?=$assiged_services;?>">
								</ul>
							</div>


							<!--	<div class="checkbox-group required add-services-hatch car-type-custom">
							
										<div class="trips-table trips-table-driver trips-table-driver-res"> 
              <div class="trips-table-inner">	
											 
											
										<table class="table table-striped table-bordered table-hover" id="dataTables-example">
											<thead>
											<tr>
											<th>Name</th>
											<th>Allow</th>
</tr>
</thead>
<tbody>
	<?php 

$query="SELECT vc.iVehicleCategoryId, vc.vLogo,vc.vCategory_EN as vCategory, vc.eStatus, vc.iDisplayOrder, (select count(iVehicleCategoryId) from vehicle_category where iParentId = vc.iVehicleCategoryId) as SubCategories FROM vehicle_category as vc WHERE eStatus = 'Active' ";


$services = $obj->MySQLSelect($query);

$query="select ServiceId from company_services where CompanyId='$iCompanyId'";

$assiged=$obj->MySQLSelect($query);
foreach ($services as $key => $services_list) {
	# code...

	?>
	<tr>
		<td>
			<?php echo $services_list['vCategory'];
 ?>

		</td>
				<td>
					

												<li style="list-style: outside none none;">
													
													 <div class="make-switch" data-on="success" data-off="warning">
														<input type="checkbox" class="chk" name="vCarType[]" id="vCarType_<?=$services_list['iVehicleCategoryId'] ?>" value="<?=$services_list['iVehicleCategoryId'];?>" <?php foreach($assiged as $k => $service_assiged) { 
															if ($services_list['iVehicleCategoryId']==$service_assiged['ServiceId']) {
																echo "checked";
															}

														} ?> />
													</div> 
												
												
													
												</li>

				</td>

	</tr>
<?php } ?>
</tbody>
										</table>
										
										</div></div>
								
							
							</div>-->
							<div class="row" style="display: none;">
								<div class="col-lg-12">
								  <label>Status</label>
								</div>
								<div class="col-lg-6">
								  <div class="make-switch" data-on="success" data-off="warning">
									   <input type="checkbox" name="eStatus" id="eStatus" <?= ($id != '' && $eStatus == 'Inactive') ? '' : 'checked'; ?> />
								  </div>
								</div>
							</div>
							<div class="clear"></div>
								<div class="row">
                                    <div class="col-lg-3 divSubmit" >
                                        <input type="submit" class="btn btn-default" name="submit" id="submit" value="Save" onclick="return check_empty();">
                                       
                                        <a href="company.php" class="btn btn-default back_link">Cancel</a>
                                    </div>
                                </div>
							</form>
						</div>
					</div>
                    <div style="clear:both;"></div>
				</div>
			</div>
			<!--END PAGE CONTENT -->
		</div>
		<!--END MAIN WRAPPER -->
		<? include_once('footer.php');?>
		<script src="../assets/js/jquery-ui.min.js"></script>
<script src="../assets/plugins/dataTables/jquery.dataTables.js"></script>
		<script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
	</body>
	<!-- END BODY-->
</html>

<script>
$(document).ready(function() {
	var referrer;


    $('#dataTables-example').dataTable({
        fixedHeader: {
          footer: true
        },
        "order": [],
        "aaSorting": []});
});

function check_box_value(val1)
{
	if($('#vCarType_'+val1).is(':checked'))
	{
		$("#amt1_"+val1).show();
		$("#fAmount_"+val1).focus();
	} else {
		$("#amt1_"+val1).hide();
	}	
}
function check_empty()
{	
	var err=0;
	$("input[type=checkbox]:checked").each ( function() {
		var tmp="fAmount_"+$(this).val();
		var tmp1="desc_"+$(this).val();
		var tmp1_val=$("#"+tmp1).val();

		if ( $("#"+tmp).val() == "" )
		{
			alert('Please Enter Amount for '+tmp1_val+'.');
			$("#"+tmp).focus();
			err=1;
			return false;
		}
	});
	if(err == 1)
	{
		return false;
	} else {
		document.vehicle_form.submit();
	}	
}
</script>

<style type="text/css">


  .paginate_button 
  {
        display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.428571429;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
        color: #333333;
    background-color: #ffffff;
    border-color: #cccccc;
  }
.button11,.current
  {

        background: #219201;
    color: #FFFFFF;
  }
  #dataTables-example_paginate
  {
  	float: left;
  	margin:10px;
  }
  .divSubmit
  {
  	float: right;
  }
  </style>