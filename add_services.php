<?php
	include_once('common.php');
	$generalobj->check_member_login();
	$abc = 'admin,driver,company';
	$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$generalobj->setRole($abc, $url);

	$start = @date("Y");
	$end = '1970';	
	$script="My Availability";
	$tbl_name = 'driver_vehicle';
	$tbl_name1 = 'service_pro_amount';
    $iDriverId = isset($_REQUEST['iDriverId']) ? $_REQUEST['iDriverId'] : '';
    $sql = "select iDriverVehicleId from driver_vehicle where iDriverId = '" . $iDriverId . "' AND eType='UberX'";
	$db_drv_veh=$obj->MySQLSelect($sql);
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $db_drv_veh[0]['iDriverVehicleId'];
	$action = ($id != '') ? 'Edit' : 'Add';

	$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
	$vLicencePlate = isset($_POST['vLicencePlate']) ? $_POST['vLicencePlate'] : '';
	$iMakeId = isset($_POST['iMakeId']) ? $_POST['iMakeId'] : '3';
	$iModelId = isset($_POST['iModelId']) ? $_POST['iModelId'] : '1';
	$fAmount = isset($_POST['fAmount']) ? $_POST['fAmount'] : '';
	$iYear = isset($_POST['iYear']) ? $_POST['iYear'] : Date('Y');
	$eStatus_check = isset($_POST['eStatus']) ? $_POST['eStatus'] : 'off';
	$vCarType = isset($_POST['vCarType']) ? $_POST['vCarType'] : '';
	$eStatus = ($eStatus_check == 'on') ? 'Active' : 'Inactive';
	$eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : 'UberX';
$iCompanyId=0;
	if ($_SESSION['sess_user'] == 'driver') {
		$sql = "select iCompanyId from `register_driver` where iDriverId = '" . $_SESSION['sess_iUserId'] . "'";
		$db_usr = $obj->MySQLSelect($sql);
		$iCompanyId = $db_usr[0]['iCompanyId'];
	}
	if ($_SESSION['sess_user'] == 'company') {
		$iCompanyId = $_SESSION['sess_iCompanyId'];
		$sql = "select * from register_driver where iCompanyId = '" . $_SESSION['sess_iCompanyId'] . "'";
		$db_drvr = $obj->MySQLSelect($sql);
	}
	/*Replace with ePricetype*/
	$chngamt="Disabled";
	if($ALLOW_SERVICE_PROVIDER_AMOUNT == "Yes"){
		$chngamt="Enabled";
	}

	if (isset($_POST['submit'])) {

		if(SITE_TYPE=='Demo' && $action=='Edit') {	


			$error_msg= $langage_lbl['LBL_EDIT_DELETE_RECORD'];
			header("Location:add_services.php?iDriverId=" .$iDriverId."&error_msg=".$error_msg."&success=2");
			exit;
		}
	//codeEdit for remove validation for edit atleast one service must be enable

	/*	if(!isset($_REQUEST['vCarType'])) {
			$error_msg = $langage_lbl['LBL_SELECT_CAR_TYPE'];
			header("Location:add_services.php?iDriverId=".$iDriverId."&error_msg=".$error_msg."&success=2");
			exit;
		}*/
if ($action=='Edit') {	
	# code...

		if (count($db_drv_veh)>1) 
		{
			foreach ($db_drv_veh as $key => $value) {
			
							$sql="delete from ".$tbl_name1." where iDriverVehicleId='".$value['iDriverVehicleId']."'";
							   $obj->MySQLSelect($sql);

			}
			   $sql = "delete from driver_vehicle where iDriverId = '" . $iDriverId . "' AND eType='UberX'";
	          $obj->MySQLSelect($sql);
	          $id='';
		}
}
		if($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX'){
			$vLicencePlate ='My Services';
		} else {
			$vLicencePlate = $vLicencePlate;
		}		
		
		if(SITE_TYPE=='Demo'){
			$str = ", eStatus = 'Active' ";
		} else {
			$str = ", eStatus = 'Active' ";
		}		

	$vCarType_r = isset($_REQUEST['vCarType']) ?  $_REQUEST['vCarType'] : '';

	$cartype = implode(",",$vCarType_r);

		 //    $obj->MySQLSelect("delete from driver_registered_service where iDriverId='$iDriverId'");

 
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

$service_a=$obj->MySQLSelect( "SELECT * FROM `driver_registered_service` where iVehicleCategoryId='$service' and iDriverId = '" . $iDriverId . "'");


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






		$q = "INSERT INTO ";
		$where = '';

		if ($id != '') {
			$q = "UPDATE ";
			$where = " WHERE `iDriverId` = '" .$iDriverId. "' AND eType='UberX'";
		}

		$query = $q . " `" . $tbl_name . "` SET		
		`vLicencePlate` = '" . $vLicencePlate . "',
		`iYear` = '" . $iYear . "',		
		`iCompanyId` = '" . $iCompanyId . "',
		`iDriverId` = '" . $iDriverId . "',
		`eType` = '".$eType."',
		`iMakeId`='".$iMakeId."',
		`iModelId`='".$iModelId."',
		`vCarType` = '" . $cartype . "' $str"
		. $where;
		$obj->sql_query($query);
		$id = ($id != '') ? $id : $obj->GetInsertId();
		
		if(!empty($fAmount)){
			$amt_man=$fAmount;	
			$sql = "select iServProAmntId,iDriverVehicleId from ".$tbl_name1." where iDriverVehicleId = '" . $id. "' ";
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
			$maildata['NAME'] = $db_status[0]['vName'];
			$maildata['DETAIL']="Your Services is Added For ".$db_compny[0]['vName']." and will process your document and activate your account ";
			$generalobj->send_email_user("VEHICLE_BOOKING",$maildata);
		}

		$var_msg =  $langage_lbl['LBL_Record_Updated_successfully'];
		header("Location:add_services.php?success=1&var_msg=".$var_msg."&iDriverId=".$iDriverId);
	}

// for Edit
//$sql = "SELECT t.*,t1.fAmount,t1.iServProAmntId,t1.iVehicleTypeId AS `VehicleId`,t1.iDriverVehicleId AS `DriverVehilceId` from  $tbl_name as t left join $tbl_name1 t1 on t.iDriverVehicleId=t1.iDriverVehicleId where t.iDriverId = '" . $iDriverId . "' AND t.iDriverVehicleId = '".$db_drv_veh[0]['iDriverVehicleId']."' and t.iCompanyId='$iCompanyId' ";
$sql = "SELECT t.*,t1.fAmount,t1.iServProAmntId,t1.iVehicleTypeId AS `VehicleId`,t1.iDriverVehicleId AS `DriverVehilceId` from  $tbl_name as t left join $tbl_name1 t1 on t.iDriverVehicleId=t1.iDriverVehicleId where t.iDriverId = '" . $iDriverId . "' and t.iCompanyId='$iCompanyId' AND eType='UberX'";

	
$db_data = $obj->MySQLSelect($sql);
$vLabel = $id;
if (count($db_data) > 0) {
	foreach ($db_data as $key => $value) {
		$vLicencePlate = $value['vLicencePlate'];
		$iYear = $value['iYear'];
		$eCarX = $value['eCarX'];
		$eType = $value['eType'];
		$eCarGo = $value['eCarGo'];
		$iDriverId = $value['iDriverId'];
		$vCarType = $value['vCarType'];
		$iCompanyId=$value['iCompanyId'];
		$eStatus=$value['eStatus'];
		$iDriverVehicleId =$value['iDriverVehicleId'];
		$fAmount[$value['VehicleId']]=$value['fAmount'];
	}
}
$vCarTyp = explode(",", $vCarType);

if($APP_TYPE == 'Delivery'){
	$Vehicle_type_name = 'Deliver';
} else if($APP_TYPE == 'Ride-Delivery-UberX'){
	$Vehicle_type_name = 'UberX';
} else {
	$Vehicle_type_name = $APP_TYPE;
}

if($Vehicle_type_name == "Ride-Delivery"){
		$vehicle_type_sql = "SELECT * from  vehicle_type where(eType ='Ride' or eType ='Deliver')";
		$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);
} else {
		if($Vehicle_type_name == 'UberX') {
			$userSQL = "SELECT c.iCountryId from register_driver AS rd LEFT JOIN country AS c ON c.vCountryCode=rd.vCountry where rd.iDriverId='".$iDriverId."'";
			$drivers = $obj->MySQLSelect($userSQL);
			$iCountryId = $drivers[0]['iCountryId'];

			$getvehiclecat = "SELECT vc.iVehicleCategoryId, vc.vCategory_EN as main_cat FROM vehicle_category as vc WHERE vc.eStatus='Active' AND vc.iParentId='0'";
			$vehicle_type_data = $obj->MySQLSelect($getvehiclecat);
			$i = 0;
			foreach ($vehicle_type_data as $key => $val) {
				$vehicle_type_sql = "SELECT vt.vVehicleType,vc.iParentId,vc.vCategory_".$_SESSION['sess_lang'].",vc.iVehicleCategoryId from  vehicle_type as vt  left join vehicle_category as vc on vt.iVehicleCategoryId = vc.iVehicleCategoryId where vt.eType='".$Vehicle_type_name."' AND vc.iParentId ='".$val['iVehicleCategoryId']."'  AND vc.eStatus='Active' GROUP BY vc.iVehicleCategoryId";
				$vehicle_type_dataOld = $obj->MySQLSelect($vehicle_type_sql);
				$vehicle_type_data[$i]['SubCategory'] = $vehicle_type_dataOld;
				$j = 0;
				foreach ($vehicle_type_dataOld as $subkey => $subvalue) {
//codeEdited for shows service asigned to company
					//$vehicle_type_sql1 = "SELECT vt.*,vc.*,lm.vLocationName from  vehicle_type as vt  left join vehicle_category as vc on vt.iVehicleCategoryId = vc.iVehicleCategoryId left join location_master as lm ON lm.iLocationId = vt.iLocationid where vt.eType='".$Vehicle_type_name."' and vc.iVehicleCategoryId = '".$subvalue['iVehicleCategoryId']."' AND (lm.iCountryId='".$iCountryId."' || vt.iLocationid='-1')";




					$vehicle_type_sql1 = "SELECT vt.*,vc.*,lm.vLocationName from  vehicle_type as vt  left join vehicle_category as vc on vt.iVehicleCategoryId = vc.iVehicleCategoryId left join location_master as lm ON lm.iLocationId = vt.iLocationid join company_services as cs on vt.iVehicleTypeId=cs.ServiceId where cs.CompanyId='".$iCompanyId."' and vt.eType='".$Vehicle_type_name."' and vc.iVehicleCategoryId = '".$subvalue['iVehicleCategoryId']."' AND (lm.iCountryId='".$iCountryId."' || vt.iLocationid='-1')";


					$vehicle_type_dataNew = $obj->MySQLSelect($vehicle_type_sql1);
					$vehicle_type_data[$i]['SubCategory'][$j]['VehicleType'] = $vehicle_type_dataNew;
					$j++;
				}

				$i++;
			}
			
		} else {
			$vehicle_type_sql = "SELECT * from  vehicle_type  where eType='".$Vehicle_type_name."' ";
			$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);
		}
	}
	
?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=$SITE_NAME?> | <?=$langage_lbl['LBL_HEADER_MY_SERVICES'];?></title>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
    <!-- End: Default Top Script and css-->

</head>
<body>
<!-- home page -->
<div id="main-uber-page">
<!-- Left Menu -->
<?php include_once("top/left_menu.php");?>
<!-- End: Left Menu-->
    <!-- Top Menu -->
        <?php include_once("top/header_topbar.php");?>
        <link rel="stylesheet" href="assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
        <!-- End: Top Menu-->
        <!-- Add Service page-->
        <div class="page-contant">
		    <div class="page-contant-inner page-trip-detail">
		      	<h2 class="header-page trip-detail driver-detail1"> <?=$langage_lbl['LBL_HEADER_MY_SERVICES'];?>
					<? if(($Vehicle_type_name == 'UberX') && $_SESSION['sess_user'] == "company"){?>
						<a href="providerlist">
							<img src="assets/img/arrow-white.png" alt="">
							<?=$langage_lbl['LBL_BACK_To_Listing']; ?>
						</a>
					<? }?>
				</h2>
		      	<!-- Service detail page -->
		      	<div class="driver-add-vehicle"> 
		      	<? if($success == 1) { ?>
					<div class="alert alert-success alert-dismissable">
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						<?=$langage_lbl['LBL_Record_Updated_successfully.']; ?>
					</div>
					<? } else if($success == 2) {?>
					<div class="alert alert-danger alert-dismissable">
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						<?= isset($_REQUEST['error_msg']) ? $_REQUEST['error_msg'] : ' '; ?>
					</div>
				<? } ?>
					<form name="frm1" method="post" action="">
						<input type="hidden" name="iDriverId"  value="<?= $iDriverId?>"/>
						<input type="hidden" name="iCompanyId"  value="<?= $iCompanyId?>"/>
						<input type="hidden" name="id" value="<?= $iDriverVehicleId;?>"/>
						<input type="hidden" name="vLicencePlate"  value="<?= $vLicencePlate;?>"/>
						<input type="hidden" name="eType"  value="<?= $eType;?>"/>
		    			<div class="car-type add-car-services-hatch add-services-hatch add-services-taxi">				          
				          	<ul>

<fieldset>
								  <legend><strong>Ride</strong></legend>
								
									<li>
										<b>Ride<br/>
										<div style="font-size: 12px;"></div></b>
										<div class="make-switch" data-on="success" data-off="warning">
											<input type="checkbox" <? if($ePricetype == "Provider"){ ?>onchange="check_box_value(this.value);" <? } ?> id="vCarType1_Ride" class="chk" name="vCarType[]" <?php if(in_array('Ride',$vCarTyp)){?>checked <?php } ?> value="Ride" />
										</div>
									
									</li>
								</fieldset> 


								<fieldset>
								  <legend><strong>Delivery</strong></legend>
								
									<li>
										<b>Delivery<br/>
										<div style="font-size: 12px;"></div></b>
										<div class="make-switch" data-on="success" data-off="warning">
											<input type="checkbox" <? if($ePricetype == "Provider"){ ?>onchange="check_box_value(this.value);" <? } ?> id="vCarType1_Delivery" class="chk" name="vCarType[]" <?php if(in_array('Delivery',$vCarTyp)){?>checked <?php } ?> value="Delivery" />
										</div>
									
									</li>
								</fieldset> 



		      				<?php		
		      				$mycount=0;		
								foreach ($vehicle_type_data as $value1) {
									foreach ($value1['SubCategory'] as $Vehicle_Type) {
										if(!empty($Vehicle_Type['VehicleType'])) {
											if($Vehicle_type_name == 'UberX'){
												$vName = 'vCategory_'.$_SESSION['sess_lang'];
												$vehicleName =$Vehicle_Type[$vName];
											}else{
												$vehicle_typeName = $Vehicle_Type['vVehicleType'];
											}
											$iParentcatId = $Vehicle_Type['iParentId'];
											$sql_query = "SELECT ePriceType FROM vehicle_category WHERE iVehicleCategoryId = '".$iParentcatId."' ";
											$ePricetype_data = $obj->MySQLSelect($sql_query);
											$ePricetype = $ePricetype_data[0]['ePriceType'];
 $mycount++;
										 ?>
							
 								<fieldset>
								  <legend><strong><?=$vehicleName; ?></strong></legend>
								  <?php foreach($Vehicle_Type['VehicleType'] as $val) {
								  	
								  	$VehicleName1 = 'vVehicleType_'.$_SESSION['sess_lang'];
									if($val['eFareType'] == 'Fixed') {
										$eFareType = 'Fixed';
										$fAmount_old = $val['fFixedFare'];
									} else if($val['eFareType'] == 'Hourly') {
										$eFareType = 'Per hour';
										$fAmount_old = $val['fPricePerHour'];
									} else {
										$eFareType = '';
										$fAmount_old = $val['fFixedFare'];
									}
								  $vehicle_typeName =$val[$VehicleName1];

								  if(!empty($val['vLocationName'])) {
										$localization = '(Location : '.$val["vLocationName"].')';
									} else {
										$localization = '';
									}
								  ?>
									<li>
										<b><?php echo $vehicle_typeName;?><br/>
										<div style="font-size: 12px;"><?php echo $localization;?></div></b>
										<div class="make-switch" data-on="success" data-off="warning">
											<input type="checkbox" <? if($ePricetype == "Provider"){ ?>onchange="check_box_value(this.value);" <? } ?> id="vCarType1_<?=$val['iVehicleTypeId'] ?>" class="chk" name="vCarType[]" <?php if(in_array($val['iVehicleTypeId'],$vCarTyp)){?>checked<?php } ?> value="<?=$val['iVehicleTypeId'] ?>" />
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
											<input type="hidden" name="desc" id="desc_<?=$val['iVehicleTypeId']?>" value="<?=$val[$VehicleName1] ?>">
											<?php if($val['eFareType'] != 'Regular'){ ?>	
											<input class="form-control" type="text" name="fAmount[<?=$val['iVehicleTypeId']?>]" value="<?=$famount_val;?>" placeholder="Enter Amount for <?=$val[$VehicleName1] ?>" id="fAmount_<?=$val['iVehicleTypeId']?>" maxlength="10"><label><?php echo $eFareType;?></label>
											</div>
										<?	}
										}
										?>
									</li>
								  <?php } ?>
								</fieldset> 
							
						<?php 		}
								} 
						}

						 ?>
							</ul>
							<?php if ($mycount==0) {

echo "No Service is not assiged.";

}else
{ 
	//if($_SESSION['sess_user'] == "company"){?>
		      				<strong><input type="submit" class="save-vehicle"  name="submit" id="submit" value="<?=$langage_lbl['LBL_SUBMIT_BUTTON_TXT']; ?>" onclick="return check_empty();"> </strong>
		      			<?php } //} ?>

		  				</div>

					<!-- -->
					</form>
				</div>
                <div style="clear:both;"></div>
			</div>
		</div>
    <!-- footer part -->
    <?php include_once('footer/footer_home.php');?>
    <!-- footer part end -->
    	    <!-- End:contact page-->
             <div style="clear:both;"></div>
	</div>
    <!-- home page end-->
    <!-- Footer Script -->
    <?php include_once('top/footer_script.php');
    ?>
    <script src="assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
    
<script>

	<?php 

//code commented for driver can update service 
	//if(trim($_SESSION['sess_user']) == "driver"){ ?>
	/*	$(document).ready(function(){ 
			
$(".switch-off").parent().parent().remove();
$("fieldset").each(function(){

	if(!$(this).find('.has-switch').length)
		$(this).remove();
});

});*/
<?php //} ?>

	function check_box_value(val1)
	{
		if($('#vCarType1_'+val1).is(':checked'))
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
		}else{
			document.frm1.submit();
		}	
	}
	
</script>
<!-- End: Footer Script -->
</body>
</html>
