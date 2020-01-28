<?php
include_once('../common.php');
	if(!isset($generalobjAdmin)){
	require_once(TPATH_CLASS."class.general_admin.php");
	$generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
	
$start = @date("Y");
$end = '1970';

$tbl_name = 'driver_vehicle';
$tbl_name1 = 'service_pro_amount';
$script = 'Driver';

$iDriverId = isset($_REQUEST['iDriverId']) ? $_REQUEST['iDriverId'] :'';

$sql = "select iDriverVehicleId from driver_vehicle where iDriverId = '" . $iDriverId . "' AND eType='UberX'";
$db_drv_veh = $obj->MySQLSelect($sql);

$id = isset($_POST['id']) ? $_POST['id'] : $db_drv_veh[0]['iDriverVehicleId'];
$action = ($id != '') ? 'Edit' : 'Add';

$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
$backlink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$previousLink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$vLicencePlate = isset($_POST['vLicencePlate']) ? $_POST['vLicencePlate'] : '';
$iCompanyId = isset($_POST['iCompanyId']) ? $_POST['iCompanyId'] : '';
$iMakeId = isset($_POST['iMakeId']) ? $_POST['iMakeId'] : '3';
$iModelId = isset($_POST['iModelId']) ? $_POST['iModelId'] : '1';
$fAmount = isset($_POST['fAmount']) ? $_POST['fAmount'] : '';
$iYear = isset($_POST['iYear']) ? $_POST['iYear'] : Date('Y');
$eStatus_check = isset($_POST['eStatus']) ? $_POST['eStatus'] : 'off';
$vCarType = isset($_POST['vCarType']) ? $_POST['vCarType'] : '';
$eStatus = ($eStatus_check == 'on') ? 'Active' : 'Inactive';
$eType = isset($_POST['eType']) ? $_POST['eType'] : 'UberX';

$sql = "select * from driver_vehicle where iDriverVehicleId = '" . $id . "' ";
$db_mdl = $obj->MySQLSelect($sql);

$sql = "select iCompanyId from `register_driver` where iDriverId = '" . $iDriverId . "'";
$db_usr = $obj->MySQLSelect($sql);
$iCompanyId = $db_usr[0]['iCompanyId'];

/*$sql = "SELECT * from make WHERE eStatus='Active' ORDER By vMake ASC";
$db_make = $obj->MySQLSelect($sql);

$sql = "SELECT * from company WHERE eStatus='Active'";
$db_company = $obj->MySQLSelect($sql);*/

if (isset($_POST['submit'])) {
	
	if(SITE_TYPE=='Demo' && $id != '')
	{
		$_SESSION['success'] = 2;
		header("Location:manage_service_type.php?iDriverId=".$iDriverId);exit;
	}
	require_once("library/validation.class.php");
	$validobj = new validation();
	
	//codeEdit for remove validation for edit atleast one service must be enable
	/*if(empty($_REQUEST['vCarType'])) {
		$validobj->add_fields($_POST['vCarType'], 'req', 'You must select at least one '.$langage_lbl_admin["LBL_CAR_TXT_ADMIN"].' type!');
	}
	$error = $validobj->validate();*/
		
/*	if ($error) {
        $success = 3;
        $newError = $error;
        //exit;
    } else {
		*/
		if($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') {
			$vLicencePlate	= 'My Services';
		} else {
			$vLicencePlate = $vLicencePlate;
		}
		
		$q = "INSERT INTO ";
		$where = '';

		if ($action == 'Edit') {
			$str = ' ';
		} else {
			$eStatus = 'Active';
		}
			$vCarType_r = isset( $_REQUEST['vCarType']) ?  $_REQUEST['vCarType'] : '';


		$cartype = implode(",", $vCarType_r);

 		//$obj->MySQLSelect("delete from driver_registered_service where iDriverId='$iDriverId'");

 		$selected_category_array = array();
		$all_requested_category_array = array();
		$all_requested_services=$obj->MySQLSelect( "SELECT `iVehicleCategoryId` FROM `driver_registered_service` where iDriverId = '" . $iDriverId . "'");

		if(count($all_requested_services) > 0){
			foreach ($all_requested_services as $key => $value) {
				array_push($all_requested_category_array,$value['iVehicleCategoryId']);
			}	
		}


		      foreach ($vCarType_r as $key => $value) {

		      	if($value!='Ride'&& $value!="Delivery")
		      	{

		      	

$service=$obj->MySQLSelect("SELECT vcp.vCategory_EN FROM `vehicle_type` vt join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId  join (SELECT iVehicleCategoryId,vCategory_EN FROM vehicle_category where iParentId='0' ) vcp on vcp.iVehicleCategoryId=vc.iParentId where iVehicleTypeId='$value'");


$service=$service[0]['vCategory_EN'];
}
else
{
	$service=$value;
}

array_push($selected_category_array,$service);
$service_a=$obj->MySQLSelect("SELECT * FROM `driver_registered_service` where iVehicleCategoryId='$service' and iDriverId = '" . $iDriverId . "'");

		
if (count($service_a)==0) {


		      	$query_service="INSERT INTO `driver_registered_service`( `iDriverId`, `iVehicleCategoryId`) VALUES ('$iDriverId','$service')";
      	$obj->MySQLSelect($query_service);
		      }


		      
		      }



if(count($all_requested_category_array) > 0 && count($selected_category_array) > 0){

	$remain_categories = array_diff($all_requested_category_array,$selected_category_array);
	
	foreach ($remain_categories as $key => $value) {
		// delete query -- here

		$obj->MySQLSelect("UPDATE  `driver_registered_service` SET `Status` = 1  WHERE iDriverId='$iDriverId' and iVehicleCategoryId = '$value'");	

		//$obj->MySQLSelect("DELETE FROM `driver_registered_service` WHERE iDriverId='$iDriverId' and iVehicleCategoryId = '$value'");	
	}
}




		if ($id != '') {
			$q = "UPDATE ";
			$where = " WHERE `iDriverId` = '" .$iDriverId. "' AND `iDriverVehicleId` = '" . $id . "'";
		}

		$query = $q . " `" . $tbl_name . "` SET
		`iModelId` = '" . $iModelId . "',
		`vLicencePlate` = '" . $vLicencePlate . "',
		`iYear` = '" . $iYear . "',
		`iMakeId` = '" . $iMakeId . "',
		`iCompanyId` = '" . $iCompanyId . "',
		`iDriverId` = '" . $iDriverId . "',
		`eStatus` = 'Active',
		`eType` = '".$eType."',
		`vCarType` = '" . $cartype . "' $str"
		. $where;

		$obj->sql_query($query);
		$id = ($id != '') ? $id : $obj->GetInsertId();

		if($id != "" && $db_mdl[0]['eStatus'] != $eStatus) {
			if($SEND_TAXI_EMAIL_ON_CHANGE == 'Yes') {
				$sql23 = "SELECT m.vMake, md.vTitle,rd.vEmail, rd.vName, rd.vLastName, c.vName as companyFirstName
					FROM driver_vehicle dv, register_driver rd, make m, model md, company c
					WHERE dv.eStatus != 'Deleted' AND dv.iDriverId = rd.iDriverId  AND dv.iCompanyId = c.iCompanyId AND dv.iModelId = md.iModelId AND dv.iMakeId = m.iMakeId AND dv.iDriverVehicleId = '".$id."'";
				$data_email_drv = $obj->MySQLSelect($sql23);
				$maildata['EMAIL'] =$data_email_drv[0]['vEmail'];
				$maildata['NAME'] = $data_email_drv[0]['vName'];
				$maildata['DETAIL']="Your ".$langage_lbl_admin['LBL_TEXI_ADMIN']." ".$data_email_drv[0]['vTitle']." For COMPANY ".$data_email_drv[0]['companyFirstName'] ." is temporarly ".$eStatus;
				$generalobj->send_email_user("ACCOUNT_STATUS",$maildata);
			}
		}

		if(!empty($fAmount)){
			$amt_man=$fAmount;
			$sql = "select iServProAmntId,iDriverVehicleId from ".$tbl_name1." where iDriverVehicleId = '" . $id . "' ";
			$db_drv_price=$obj->MySQLSelect($sql);

			if(count($db_drv_price) > 0){
				$sql="delete from ".$tbl_name1." where iDriverVehicleId='".$db_drv_price[0]['iDriverVehicleId']."'";
				$obj->sql_query($sql);	
			}
			
			foreach($amt_man as $key=>$value)
			{
				if($value != ""){
					$q = "Insert Into ";
					$query = $q . " `" . $tbl_name1 . "` SET
					`iDriverVehicleId` = '" . $id . "',
					`iVehicleTypeId` = '" . $key . "',
					`fAmount` = '" . $value . "'";
					$db_parti_price=$obj->sql_query($query);
				}
			}
			
		}


		if($action=="Add")
		{
			$sql="SELECT * FROM company WHERE iCompanyId = '" . $iCompanyId . "'";
			$db_compny = $obj->MySQLSelect($sql);

			$sql="SELECT * FROM register_driver WHERE iDriverId = '" . $iDriverId . "'";
			$db_status = $obj->MySQLSelect($sql);

			$maildata['EMAIL'] =$db_status[0]['vEmail'];
			$maildata['NAME'] = $db_status[0]['vName']." ".$db_status[0]['vLastName'];
			$maildata['DETAIL']="Thanks for adding your ".$langage_lbl_admin['LBL_TEXI_ADMIN'].".<br />We will soon verify and check it's documentation and proceed ahead with activating your account.<br />We will notify you once your account become active and you can then take ".$langage_lbl_admin['LBL_RIDE_TXT_ADMIN']." with ". $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'].".";
			$generalobj->send_email_user("VEHICLE_BOOKING",$maildata);
		}

		if ($action == "Add") {
            $_SESSION['success'] = '1';
            $_SESSION['var_msg'] = $langage_lbl_admin["LBL_TEXI_ADMIN"].' Inserted Successfully.';
        } else {
            $_SESSION['success'] = '1';
            $_SESSION['var_msg'] = $langage_lbl_admin["LBL_TEXI_ADMIN"].' Updated Successfully.';
        }

        header("location:".$backlink);
	//}
}


// for Edit
if ($action == 'Edit') {
	$sql = "SELECT t.*,t1.fAmount,t1.iServProAmntId,t1.iVehicleTypeId AS `VehicleId`,t1.iDriverVehicleId AS `DriverVehilceId` FROM $tbl_name AS t LEFT JOIN $tbl_name1 t1 ON t.iDriverVehicleId=t1.iDriverVehicleId
			WHERE t.iDriverId = '" . $iDriverId . "' AND t.iDriverVehicleId = '".$db_drv_veh[0]['iDriverVehicleId']."'";
	$db_data = $obj->MySQLSelect($sql);
	$vLabel = $id;
	if (count($db_data) > 0) {
		foreach ($db_data as $key => $value) {
			$iMakeId = $value['iMakeId'];
			$iModelId = $value['iModelId'];
			$vLicencePlate = $value['vLicencePlate'];
			$iYear = $value['iYear'];
			$eCarX = $value['eCarX'];
			$eCarGo = $value['eCarGo'];
			$iDriverId = $value['iDriverId'];
			$vCarType = $value['vCarType'];
			$iCompanyId=$value['iCompanyId'];
			$eStatus=$value['eStatus'];
			$iDriverVehicleId =$value['iDriverVehicleId'];
			$eType = $value['eType'];
			$fAmount[$value['VehicleId']]=$value['fAmount'];
		}
	}
}
$vCarTyp = explode(",", $vCarType);

//$Vehicle_type_name = ($APP_TYPE == 'Delivery')? 'Deliver':$APP_TYPE ;	
if($APP_TYPE == 'Delivery'){
	$Vehicle_type_name = 'Deliver';
} else if($APP_TYPE == 'Ride-Delivery-UberX'){
	$Vehicle_type_name = 'UberX';
} else {
	$Vehicle_type_name = $APP_TYPE;
}

if($Vehicle_type_name == "Ride-Delivery"){
	$vehicle_type_sql = "SELECT * FROM  vehicle_type WHERE(eType ='Ride' OR eType ='Deliver') AND iCountryId='-1'";
	$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);
} else {
	if($Vehicle_type_name == 'UberX'){
			$userSQL = "SELECT c.iCountryId from register_driver AS rd LEFT JOIN country AS c ON c.vCountryCode=rd.vCountry where rd.iDriverId='".$iDriverId."'";
			$drivers = $obj->MySQLSelect($userSQL);
			$iCountryId = $drivers[0]['iCountryId'];
			
			$getvehiclecat = "SELECT vc.iVehicleCategoryId, vc.vCategory_EN AS main_cat FROM vehicle_category AS vc WHERE vc.eStatus='Active' AND vc.iParentId='0'";
			$vehicle_type_data = $obj->MySQLSelect($getvehiclecat);
			$i = 0;
			foreach ($vehicle_type_data as $key => $val) {
				$vehicle_type_sql = "SELECT vt.vVehicleType,vc.iParentId,vc.vCategory_".$_SESSION['sess_lang'].",vc.iVehicleCategoryId from  vehicle_type as vt  left join vehicle_category as vc on vt.iVehicleCategoryId = vc.iVehicleCategoryId where vt.eType='".$Vehicle_type_name."' AND vc.iParentId ='".$val['iVehicleCategoryId']."'  AND vc.eStatus='Active' GROUP BY vc.iVehicleCategoryId";
				$vehicle_type_dataOld = $obj->MySQLSelect($vehicle_type_sql);
				$vehicle_type_data[$i]['SubCategory'] = $vehicle_type_dataOld;
				$j = 0;
				foreach ($vehicle_type_dataOld as $subkey => $subvalue) {
					$vehicle_type_sql1 = "SELECT vt.*,vc.*,lm.vLocationName FROM vehicle_type AS vt LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId LEFT JOIN location_master AS lm ON lm.iLocationId = vt.iLocationid WHERE vt.eType='".$Vehicle_type_name."' AND vc.iVehicleCategoryId = '".$subvalue['iVehicleCategoryId']."' AND (lm.iCountryId='".$iCountryId."' || vt.iLocationid='-1')";
					$vehicle_type_dataNew = $obj->MySQLSelect($vehicle_type_sql1);
					$vehicle_type_data[$i]['SubCategory'][$j]['VehicleType'] = $vehicle_type_dataNew;
					$j++;
				}

				$i++;
			} 
	} else {
		$vehicle_type_sql = "SELECT * FROM vehicle_type WHERE eType='".$Vehicle_type_name."' AND iCountryId='-1'";		
		$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);
	}
}

?>
<!DOCTYPE html>
<html lang="en">
	<!-- BEGIN HEAD-->
	<head>
		<meta charset="UTF-8" />
		<title><?=$SITE_NAME?> |  <?php echo $langage_lbl_admin['LBL_SERVICE_ADMIN'];?> <?= $action; ?></title>
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
							<a href="Driver.php" class="back_link">
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
							<input type="hidden" name="iDriverId"  value="<?= $iDriverId?>"/>
							<input type="hidden" name="iCompanyId"  value="<?= $iCompanyId?>"/>
							<input type="hidden" name="iMakeId"  value="<?= $iMakeId?>"/>
							<input type="hidden" name="iModelId"  value="<?= $iModelId?>"/>
							<input type="hidden" name="iYear"  value="<?= $iYear?>"/>
							<input type="hidden" name="vLicencePlate"  value="<?= $vLicencePlate;?>"/>
							<input type="hidden" name="id" value="<?= $iDriverVehicleId;?>"/>
							<input type="hidden" name="eType"  value="<?= $eType;?>"/>
							<input type="hidden" name="previousLink" id="previousLink" value="<?php echo $previousLink; ?>"/>
							<input type="hidden" name="backlink" id="backlink" value="driver.php"/>
								<div class="row">
									<div class="col-lg-12">
										<label><?=$langage_lbl_admin['LBL_SERVICE_ADMIN'];?> Type <span class="red">*</span></label>
									</div>
								</div>
								<div class="checkbox-group required add-services-hatch car-type-custom">
								<ul>

<fieldset>
								  <legend><strong>Ride</strong></legend>
								
									<li style="list-style: outside none none;">
										<b>Ride<br/>
										<div style="font-size: 12px;"></div></b>
										<div class="make-switch" data-on="success" data-off="warning">
											<input type="checkbox" <? if($ePricetype == "Provider"){ ?>onchange="check_box_value(this.value);" <? } ?> id="vCarType1_Ride" class="chk" name="vCarType[]" <?php if(in_array('Ride',$vCarTyp)){?>checked <?php } ?> value="Ride" />
										</div>
									
									</li>
								</fieldset> 


								<fieldset>
								  <legend><strong>Delivery</strong></legend>
								
									<li style="list-style: outside none none;">
										<b>Delivery<br/>
										<div style="font-size: 12px;"></div></b>
										<div class="make-switch" data-on="success" data-off="warning">
											<input type="checkbox" <? if($ePricetype == "Provider"){ ?>onchange="check_box_value(this.value);" <? } ?> id="vCarType1_Delivery" class="chk" name="vCarType[]" <?php if(in_array('Delivery',$vCarTyp)){?>checked <?php } ?> value="Delivery" />
										</div>
									
									</li>
								</fieldset> 

								<?php
									foreach ($vehicle_type_data as $key => $value) {
										foreach ($value['SubCategory'] as $Vehicle_Type) {
										if(!empty($Vehicle_Type['VehicleType'])) { ?>
										<fieldset>
											<?php
											if($Vehicle_type_name =='UberX'){
												$vname = $Vehicle_Type['vCategory_'.$_SESSION['sess_lang']];
												$vehicle_Name = $Vehicle_Type['vVehicleType'];
											} else {
												$vname= $Vehicle_Type['vVehicleType'];	
											}
											$iParentcatId = $Vehicle_Type['iParentId'];
											$sql_query = "SELECT ePriceType FROM vehicle_category WHERE iVehicleCategoryId = '".$iParentcatId."' ";
											$ePricetype_data = $obj->MySQLSelect($sql_query);
											$ePricetype = $ePricetype_data[0]['ePriceType'];
											 ?>
											 
											<legend>
												<strong><?php echo $vname;?></strong>
											</legend>
										
											<?php foreach($Vehicle_Type['VehicleType'] as $val) {
												if($val['eFareType'] == 'Fixed'){
													$eFareType = 'Fixed';
													$fAmount_old = $val['fFixedFare'];
												} else if($val['eFareType'] == 'Hourly'){
													$eFareType = 'Per hour';
													$fAmount_old = $val['fPricePerHour'];
												}else{
													$eFareType = '';
													$fAmount_old = '';
												
												}
									  			$vehicle_typeName =$val['vVehicleType_'.$_SESSION['sess_lang']];

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
														<input type="checkbox" class="chk" name="vCarType[]" id="vCarType_<?=$val['iVehicleTypeId'] ?>" <? if($ePricetype == "Provider"){ ?>onchange="check_box_value(this.value);" <? } ?> <?php if(in_array($val['iVehicleTypeId'],$vCarTyp)){?>checked<?php } ?> value="<?=$val['iVehicleTypeId'] ?>"/>
													</div>
													<?php 
													if($ePricetype == "Provider"){
														$p001="style='display:none;'";
														if(in_array($val['iVehicleTypeId'],$vCarTyp)){
															$p001="style='display:block;'";
														}
														$fAmount_new = $fAmount[$val['iVehicleTypeId']];
														$famount_val = (empty($fAmount_new)) ? $fAmount_old : $fAmount_new ;
														?>
													<div class="hatchback-search" id="amt1_<?=$val['iVehicleTypeId'] ?>" <? echo $p001;?>>
														<input type="hidden" name="desc" id="desc_<?=$val['iVehicleTypeId']?>" value="<?=$val['vVehicleType_'.$default_lang] ?>">
														<?php if($val['eFareType'] != 'Regular'){ ?>	
														<input class="form-control" type="text" name="fAmount[<?=$val['iVehicleTypeId']?>]" value="<?=$famount_val;?>" placeholder="Enter Amount for <?=$val['vVehicleType_'.$default_lang] ?>" id="fAmount_<?=$val['iVehicleTypeId']?>" maxlength="10"><label class="fare_type"><?php echo $eFareType;?></label>
														</div>
													<? 	}
													}
													?>
												</li>
											<?php } ?>
										</fieldset>
								<?php }
									}
								} ?>
								</ul>
							</div>
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
                                    <div class="col-lg-12">
                                        <input type="submit" class="btn btn-default" name="submit" id="submit" value="<?= $action." ".$langage_lbl_admin['LBL_SERVICE_ADMIN']; ?>" onclick="return check_empty();">
                                        <a href="javascript:void(0);" onclick="reset_form('vehicle_form');" class="btn btn-default">Reset</a>
                                        <a href="vehicles.php" class="btn btn-default back_link">Cancel</a>
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
		<script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
	</body>
	<!-- END BODY-->
</html>

<script>
$(document).ready(function() {
	var referrer;
	if($("#previousLink").val() == "" ){
		referrer =  document.referrer;	
		//alert(referrer);
	} else { 
		referrer = $("#previousLink").val();
	}
	if(referrer == "") {
		referrer = "vehicles.php";
	} else {
		$("#backlink").val(referrer);
	}
	$(".back_link").attr('href',referrer);
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