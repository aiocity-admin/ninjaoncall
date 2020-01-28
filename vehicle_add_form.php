<?php

include_once('common.php');

$generalobj->check_member_login();

$abc = 'admin,driver,company';

$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];



$generalobj->setRole($abc, $url);

$start = @date("Y");

$end = '1970';

$script="Vehicle";

$tbl_name = 'driver_vehicle';

	

$_REQUEST['id'] = base64_decode(base64_decode(trim($_REQUEST['id'] )));

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$action = ($id != '') ? 'Edit' : 'Add';

$action_show = ($id != '') ? $langage_lbl['LBL_VEHICLE_EDIT'] : $langage_lbl['LBL_VEHICLE_ACTION_ADD'];

$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;



if ($_SESSION['sess_user'] == 'driver') {

	$sql = "select rd.iCompanyId,c.iCountryId from `register_driver` AS rd LEFT JOIN country AS c ON c.vCountryCode=rd.vCountry where rd.iDriverId = '" . $_SESSION['sess_iUserId'] . "'";

	$db_usr = $obj->MySQLSelect($sql);

	$iCompanyId = $db_usr[0]['iCompanyId'];

	$iCountryId = $db_usr[0]['iCountryId'];

	$iDriverId = $_SESSION['sess_iUserId'];

}

if ($_SESSION['sess_user'] == 'company') {

	$iCompanyId = $_SESSION['sess_iCompanyId'];

	$sql = "select * from register_driver where iCompanyId = '" . $_SESSION['sess_iCompanyId'] . "' AND eStatus !='Deleted' order by vName ASC";

	$db_drvr = $obj->MySQLSelect($sql);

	

	$sql = "select cn.iCountryId from `company` AS c LEFT JOIN country AS cn ON cn.vCountryCode=c.vCountry where c.iCompanyId = '" . $_SESSION['sess_iCompanyId'] . "'";

	$db_usr = $obj->MySQLSelect($sql);

	$iCountryId = $db_usr[0]['iCountryId'];

	$iDriverId = isset($_POST['iDriverId']) ? $_POST['iDriverId'] : '';

}

$sql = "select * from driver_vehicle where iDriverVehicleId = '" . $id . "' ";

$db_mdl = $obj->MySQLSelect($sql);



// set all variables with either post (when submit) either blank (when insert)

$vLicencePlate = isset($_POST['vLicencePlate']) ? $_POST['vLicencePlate'] : (isset($_REQUEST['vLicencePlate']) ? $_REQUEST['vLicencePlate'] : "");

$iMakeId = isset($_POST['iMakeId']) ? $_POST['iMakeId'] : (isset($_REQUEST['iMakeId']) ? $_REQUEST['iMakeId'] : "");

$iModelId = isset($_POST['iModelId']) ? $_POST['iModelId'] : (isset($_REQUEST['iModelId']) ? $_REQUEST['iModelId'] : "");

$iYear = isset($_POST['iYear']) ? $_POST['iYear'] : (isset($_REQUEST['iYear']) ? $_REQUEST['iYear'] : "");

$eStatus_check = isset($_POST['eStatus']) ? $_POST['eStatus'] : 'off';

$eHandiCapAccessibility_check = isset($_POST['eHandiCapAccessibility']) ? $_POST['eHandiCapAccessibility'] : 'off';

$vCarType = isset($_POST['vCarType']) ? $_POST['vCarType'] : '';

$vRentalCarType = isset($_POST['vRentalCarType']) ? $_POST['vRentalCarType'] : '';

$vColour = isset($_POST['vColour']) ? $_POST['vColour'] : '';

$eStatus = ($eStatus_check == 'on') ? 'Active' : 'Inactive';

$eHandiCapAccessibility = ($eHandiCapAccessibility_check == 'on') ? 'Yes' : 'No';

$eType = isset($_POST['eType']) ? $_POST['eType'] : 'Ride';

 $extraField=isset($_REQUEST['extraField'])?$_REQUEST['extraField']:array();

$extraFieldData="";

	$sql = "SELECT * from make WHERE eStatus='Active' ORDER BY vMake ASC";

	$db_make = $obj->MySQLSelect($sql);


	$Owner_First_Name = isset($_POST['Owner_First_Name']) ? $_POST['Owner_First_Name'] : '';
	$Owner_Middle_Name = isset($_POST['Owner_Middle_Name']) ? $_POST['Owner_Middle_Name'] : '';
	$Owner_Last_Name = isset($_POST['Owner_Last_Name']) ? $_POST['Owner_Last_Name'] : '';
	$Owner_Address = isset($_POST['Owner_Address']) ? $_POST['Owner_Address'] : '';
	$Province = isset($_POST['Province']) ? $_POST['Province'] : '';
	$City = isset($_POST['City']) ? $_POST['City'] : '';
$vBarangay = isset($_POST['vBarangay']) ? $_POST['vBarangay'] : '';
$vCountry = isset($_POST['vCountry']) ? $_POST['vCountry'] : '';
	if (isset($_POST['submit'])) {



	// if(SITE_TYPE=='Demo' && $action=='Edit')

	// {

		// $error_msg="Edit / Delete Record Feature has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.";

		 // header("Location:vehicle_add_form.php?id=" .$id."&error_msg=".$error_msg."&success=2");

		 // exit;

	// }



		if(!isset($_REQUEST['vCarType'])) {

			$error_msg = $langage_lbl['LBL_SELECT_CAR_TYPE'];

			header("Location:vehicle_add_form.php?id=".$id."&error_msg=".$error_msg."&success=2&iMakeId=".$iMakeId."&iModelId=".$iModelId."&iYear=".$iYear."&vLicencePlate=".$vLicencePlate);

			exit;

		}



		if($APP_TYPE == 'UberX'){

			$vLicencePlate ='My Services';

		}else{

			$vLicencePlate = $vLicencePlate;

		}

		

		$dsql="";

		if($id!='')

		{

			$dsql=" and iDriverVehicleId != '$id'";

		}
		$db_li_plate=array();
if ($action=="add") {


		$sql="select * from driver_vehicle where vLicencePlate='".$vLicencePlate."' and eStatus!='Deleted' ".$dsql;

		$db_li_plate=$obj->MySQLSelect($sql);


}
		if(count($db_li_plate)>0){

			$error_msg=  $langage_lbl['LBL_LICENCE_PLATE_EXIST'];

			header("Location:vehicle_add_form.php?id=".$id."&error_msg=".$error_msg."&success=2");

			exit;

		}

		else

		{

			$q = "INSERT INTO ";

			$where = '';
$driver_update=		"`iDriverId` = '" . $iDriverId . "',";


			if ($action == 'Edit') {

				$str = ' ';

			} else {

				if(SITE_TYPE=='Demo')

				$str = ", eStatus = 'Active' ";

				else

				$str = ", eStatus = 'Inactive' ";

			}



			$cartype = implode(",", $_REQUEST['vCarType']);



			$vRentalCarType = '';

			if(!empty($_REQUEST['vRentalCarType'])){

				$vRentalCarType = implode(",", $_REQUEST['vRentalCarType']);

			}



			$rental_query = " `vRentalCarType` = '" . $vRentalCarType . "', ";



			if ($id != '') {

				$q = "UPDATE ";
$driver_update="";

$query="select vLicencePlate from driver_vehicle where iDriverVehicleId='$id'";

 
$db_data_vLicencePlate = $obj->MySQLSelect($query);

$vLicencePlate_update=$db_data_vLicencePlate[0]['vLicencePlate'];


				//$where = " WHERE `iDriverVehicleId` = '" . $id . "'";

				$where = " WHERE `vLicencePlate` = '" . $vLicencePlate_update . "'";

			}


$subQuery="";
                            $z=1;
foreach ($extraField as $key => $value) {
    $subQuery.="ExtraField_".$z++."='".$value."',";
}



		$query = $q . " `" . $tbl_name . "` SET

		`iModelId` = '" . $iModelId . "',

		`vLicencePlate` = '" . $vLicencePlate . "',

		`iYear` = '" . $iYear . "',

		`iMakeId` = '" . $iMakeId . "',

		`iCompanyId` = '" . $iCompanyId . "',".$driver_update."
eStatus='Active',

		`vColour` = '" . $vColour . "',

		`eType`  = '" . $eType . "',
			`Owner_First_Name` = '" . $Owner_First_Name . "',
			`Owner_Middle_Name` = '" . $Owner_Middle_Name . "',
			`Owner_Last_Name` = '" . $Owner_Last_Name . "',
			`Owner_Address` = '" . $Owner_Address . "',
			`Province` = '" . $Province . "',
			`City` = '" . $City . "',
			`Barangay` = '" . $vBarangay . "',$subQuery
`vCountry`='".$vCountry."',
		`eHandiCapAccessibility` = '" . $eHandiCapAccessibility . "',

		$rental_query

		`vCarType` = '" . $cartype . "'"

		. $where;

		

		$obj->sql_query($query);
		$id = ($id != '') ? $id : $obj->GetInsertId();



		if($action=="Add")

		{

			$sql="SELECT * FROM company WHERE iCompanyId = '" . $iCompanyId . "'";

			$db_compny = $obj->MySQLSelect($sql);



			$sql="SELECT * FROM register_driver WHERE iDriverId = '" . $iDriverId . "'";

			$db_status = $obj->MySQLSelect($sql);



			$maildata['EMAIL'] =$db_status[0]['vEmail'];

			$maildata['NAME'] = $db_status[0]['vName']." ".$db_status[0]['vLastName'];

			$maildata['DETAIL']="Thanks for adding your vehicle.<br />We will soon verify and check it's documentation and proceed ahead with activating your account.<br />We will notify you once your account become active and you can then take rides with passengers.";

			

			$generalobj->send_email_user("VEHICLE_BOOKING",$maildata);

			//$maildata['DETAIL']="Vehicle is Added For ".$db_compny[0]['vCompany']." . Below is link to activate.<br>

			$maildata['DETAIL']="You can active this Vehicle by clicking below link<br>

			<p><a href='".$tconfig["tsite_url"]."admin/vehicle_add_form.php?id=$id'>Active this Vehicle</a></p>";

			$generalobj->send_email_user("VEHICLE_BOOKING_ADMIN",$maildata);

				$var_msg = $langage_lbl['LBL_RECORD_INSERT_MSG'];

			} else {

				$var_msg = $langage_lbl['LBL_Record_Updated_successfully.'];

			}

		header("Location:vehicle.php?success=1&var_msg=".$var_msg);

	}

	}



// for Edit

if ($action == 'Edit') {

	if($_SESSION['sess_user'] == 'driver'){

		$ssql = "and iDriverId = '".$_SESSION['sess_iUserId']."'";

	}else{

		$ssql = "and iCompanyId = '".$_SESSION['sess_iCompanyId']."'";

	}



	$sql = "SELECT * from  $tbl_name   where iDriverVehicleId = '" . $id . "' ".$ssql." ";

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

			$eType = $value['eType'];

			$iDriverId = $value['iDriverId'];

			$eHandiCapAccessibility=$value['eHandiCapAccessibility'];

			$vCarType = $value['vCarType'];

			$vRentalCarType = $value['vRentalCarType'];

			$vColour = $value['vColour'];


			$Owner_First_Name=$value['Owner_First_Name'];
			$Owner_Last_Name=$value['Owner_Last_Name'];
			$Owner_Middle_Name=$value['Owner_Middle_Name'];
			$Owner_Address=$value['Owner_Address'];
			$Province=$value['Province'];
			$City=$value['City'];
			$vBarangay=$value['Barangay'];
$vCountry=$value['vCountry'];

			$extraFieldData.=$value['ExtraField_1'].",";
			$extraFieldData.=$value['ExtraField_2'].",";
			$extraFieldData.=$value['ExtraField_3'].",";
			$extraFieldData.=$value['ExtraField_4'].",";
			$extraFieldData.=$value['ExtraField_5'].",";
		}

	} else {

		header("location:vehicle.php");

	}

}

$vCarTyp = explode(",", $vCarType);

$vRentalCarTyp = explode(",", $vRentalCarType);

if($APP_TYPE == 'Ride-Delivery-UberX'){

	$Vehicle_type_name = 'Ride-Delivery';

} else if($APP_TYPE == 'Delivery') {

	$Vehicle_type_name = 'Deliver';

} else {

	$Vehicle_type_name = $APP_TYPE;

}



if($Vehicle_type_name == "Ride-Delivery"){

	$vehicle_type_sql = "SELECT vt.*,lm.vLocationName from  vehicle_type as vt left join location_master as lm ON lm.iLocationId = vt.iLocationid where(vt.eType ='Ride' or vt.eType ='Deliver') AND (vt.iLocationid='-1' OR lm.iCountryId='".$iCountryId."')";

	$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);

} else {

	if($APP_TYPE == 'UberX'){

		$vehicle_type_sql = "SELECT vt.*,vc.*,lm.vLocationName from  vehicle_type as vt  left join vehicle_category as vc on vt.iVehicleCategoryId = vc.iVehicleCategoryId left join location_master as lm ON lm.iLocationId = vt.iLocationid where vt.eType='".$Vehicle_type_name."' AND (vt.iLocationid='-1' OR lm.iCountryId='".$iCountryId."')";

		$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);

	}else{

		$vehicle_type_sql = "SELECT vt.*,c.vCountry,ct.vCity,st.vState,lm.vLocationName from  vehicle_type as vt left join country as c ON c.iCountryId = vt.iCountryId left join state as st ON st.iStateId = vt.iStateId left join city as ct ON ct.iCityId = vt.iCityId  left join location_master as lm ON lm.iLocationId = vt.iLocationid where vt.eType='".$Vehicle_type_name."' AND (vt.iLocationid='-1' OR lm.iCountryId='".$iCountryId."')";

		$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);

	}

}



?>

<!DOCTYPE html>

<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title><?=$SITE_NAME?> | <?=$langage_lbl['LBL_Vehicle']; ?> <?= $action_show; ?></title>

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

        <!-- contact page-->

        <div class="page-contant">

		    <div class="page-contant-inner page-trip-detail">

		      	<h2 class="header-page trip-detail driver-detail1"><?= $action_show; ?> <?=$langage_lbl['LBL_Vehicle']; ?><a href="vehicle.php"><img src="assets/img/arrow-white.png" alt="" /><?=$langage_lbl['LBL_BACK_MY_TAXI_LISTING']; ?></a></h2>

		      	<!-- trips detail page -->

		      	<div class="driver-add-vehicle"> 

		      	<? if($success == 1) { ?>

					<div class="alert alert-success alert-dismissable">

						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>

						<?=$langage_lbl['LBL_Record_Updated_successfully.']; ?>

					</div>

					<? }else if($success == 2){?>

					<div class="alert alert-danger alert-dismissable">

						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>

						<?= isset($_REQUEST['error_msg']) ? $_REQUEST['error_msg'] : ' '; ?>

					</div>

				<?} ?>

					<form method="post" action="">

						<input type="hidden" name="id" value="<?=base64_encode(base64_encode($id)); ?>"/>

						<?php if($APP_TYPE != 'UberX'){ ?>

						<?php if($APP_TYPE == 'Ride-Delivery' || $APP_TYPE == 'Ride-Delivery-UberX') {?>

						<span> 

		  					<b style="width:100%">

                            <label>Service Category<span class="red">*</span></label>

						        <select  class="custom-select-new" name = 'eType' required id='etypedelivery'>

                                    <option value="Ride" <?php if ($eType == "Ride") echo 'selected="selected"'; ?> >Ride</option>

                                    <option value="Delivery"<?php if ($eType == "Delivery") echo 'selected="selected"'; ?>>Delivery</option>

                                </select>

		        			</b>

		        		</span>

		        		<?php } else { ?>

                            <input type="hidden" name="eType" value="<?= $APP_TYPE?>" id='etypedelivery'>

                        <?php } ?>

		      			<span> 

		  					<b>

                            <label><?=$langage_lbl['LBL_CHOOSE_MAKE']; ?><span class="red">*</span></label>

						        <select name = "iMakeId" id="iMakeId" class="custom-select-new" data-key="<?=$langage_lbl['LBL_CHOOSE_MAKE']; ?>" onChange="get_model(this.value, '')" required>

									<option value=""><?=$langage_lbl['LBL_CHOOSE_MAKE']; ?></option>

									<?php for ($j = 0; $j < count($db_make); $j++) { ?>

										<option value="<?= $db_make[$j]['iMakeId'] ?>" <?php if ($iMakeId == $db_make[$j]['iMakeId']) { ?> selected <?php } ?>><?= $db_make[$j]['vMake'] ?></option>

									<?php } ?>

								</select>

		        			</b> 

		        			<b id="carmdl">

                             <label><?=$langage_lbl['LBL_CHOOSE_VEHICLE_MODEL']; ?><span class="red">*</span></label>

		        				<select name = "iModelId" id="iModelId" data-key="<?=$langage_lbl['LBL_CHOOSE_VEHICLE_MODEL']; ?>" class="custom-select-new validate[required]" required>

									<option value=""><?=$langage_lbl['LBL_CHOOSE_VEHICLE_MODEL']; ?> </option>

									<?php for ($j = 0; $j < count($db_model); $j++) { ?>

										<option value="<?= $db_model[$j]['iModelId'] ?>" <?php if ($iModelId == $db_model[$j]['iModelId']) { ?> selected <?php }?>><?= $db_model[$j]['vModel'] ?></option>

									<?php } ?>

								</select>

		    				</b> 

						</span> 

						<span> 

							<b>

                             <label><?=$langage_lbl['LBL_CHOOSE_YEAR']; ?><span class="red"></span></label>

		        				<select name = "iYear" data-key="<?=$langage_lbl['LBL_CHOOSE_YEAR']; ?>" id="iYear" class="custom-select-new" >

									<option value=""><?=$langage_lbl['LBL_CHOOSE_YEAR']; ?> </option>

									<?php for ($j = $start; $j >= $end; $j--) { ?>

										<option value="<?= $j ?>" <? if($iYear == $j){?> selected <?} ?>><?= $j ?></option>

									<?php } ?>

								</select>

		        			</b> 

		        			<b>

                             <label id="lbl_plate_number">Permit/<?=$langage_lbl['LBL_LICENCE_PLATE_TXT'];?><span class="red">*</span></label>

		        				<input type="text" class="form-control" maxlength="20" name="vLicencePlate"  id="vLicencePlate" value="<?= $vLicencePlate; ?>" placeholder="<?=$langage_lbl['LBL_LICENCE_PLATE_TXT']; ?>" onBlur="check_licence_plate(this.value,'<?=$id?>')" required >

								<span id="plate_warning" class="error"></span>

		        			</b> 

		    			</span>



						<span>

							<?if($_SESSION['sess_user'] == 'company') {?>

								<b>

	                            <label><?=$langage_lbl['LBL_CHOOSE_DRIVER']; ?><span class="red"> *</span></label>

									<select name = "iDriverId" id="iDriverId" class="custom-select-new" required>

										<option value=""><?=$langage_lbl['LBL_CHOOSE_DRIVER']; ?></option>

										<?php for ($j = 0; $j < count($db_drvr); $j++) { ?>

											<option value="<?= $db_drvr[$j]['iDriverId'] ?>" <? if($db_drvr[$j]['iDriverId'] == $iDriverId){?> selected <?} ?>><?= $generalobj->clearName($db_drvr[$j]['vName'].' '.$db_drvr[$j]['vLastName']); ?></option>

										<?php } ?>

									</select>

								</b>

							<? } ?>



							<b>

	                            <label><?=$langage_lbl['LBL_VEHICLE_TITLE'] . " " .$langage_lbl['LBL_COLOR_ADD_VEHICLES']; ?></label>

								<input type="text" class="form-control" name="vColour"  id="vColour" value="<?= $vColour; ?>"  placeholder="<?=$langage_lbl['LBL_VEHICLE_COLOR']; ?>" >

							</b>

						</span>





		<span> <b>



										<label>Owner’s First Name<span class="red"> *</span></label>
								
										<input required type="text" class="form-control" name="Owner_First_Name"  id="Owner_First_Name" value="<?= $Owner_First_Name; ?>"  placeholder="Owner's First Name">
									</b>		
			<b>	
										<label>Owner’s Middle Name</label>
								
										<input type="text" class="form-control" name="Owner_Middle_Name"  id="Owner_Middle_Name" value="<?= $Owner_Middle_Name; ?>"  placeholder="Owner's Middle Name">
									

				</b>

						</span>

	<span><b>
										<label>Owner’s Last Name<span class="red"> *</span></label>
								
										<input required type="text" class="form-control" name="Owner_Last_Name"  id="Owner_Last_Name" value="<?= $Owner_Last_Name; ?>"  placeholder="Owner's Last Name">
									



</b> <b>

										<label><?=$langage_lbl['LBL_COUNTRY_TXT']; ?><span class="red"> *</span></label>
								
										<select onChange="setState(this.value,'<?=$Province?>');" name = "vCountry" id="vCountry" class="form-control" required>
<?php $query="select vCountry,iCountryId,vCountryCode from country  where eStatus='Active' ORDER BY  vCountry ASC"; 
$db_country=$obj->sql_query($query);
?>

											<option value=""><?= $langage_lbl['LBL_SELECT_TXT'] ?></option>
											<?php 	for ($z=0; $z <count($db_country) ; $z++) {  ?> ?>

										<option value = "<?= $db_country[$z]['vCountryCode'] ?>" <?if($DEFAULT_COUNTRY_CODE_WEB == $db_country[$z]['vCountryCode'] && $action == 'Add') { ?> selected <?php } else if($vCountry==$db_country[$z]['vCountryCode']){?>selected<? } ?>><?= $db_country[$z]['vCountry'] ?></option>
										<?php }?>
										</select>
							</b> </span>	


	<span>

<b>

										<label id="lbl_vstate">Province <span class="red"> </span></label>
								
										<select  onChange="setCity(this.value,'<?=$City?>');"  name = "Province" id="Province" class="form-control">


											<option value=""><?= $langage_lbl['LBL_SELECT_TXT'] ?></option>
										
										</select>
							</b> 
		<b>

										<label id="lbl_city">City  <span class="red"> </span></label>
									

										<select name = "City" id="City" class="form-control">
											<option value=""><?= $langage_lbl['LBL_SELECT_TXT'] ?></option>
												
										</select>
									




</b> </span>
<span>
	<b id="row_vBarangay">
		<label>Barangay<span class="red">*</span></label>
										
												<select required class="form-control vBarangay" name = 'vBarangay' id="vBarangay" >

													<option value="">select</option>
												</select></b>
	<b>

	
										<label>House Number, Building Number, and Street Name<span class="red">*</span></label>
								
										<textarea type="text" class="form-control" name="Owner_Address"  id="Owner_Address"   placeholder="House Number, Building Number, and Street Name" required><?= trim($Owner_Address);?></textarea>
								

</b>
</span>
						

						<span id="handicapaccess">

							<b>

                            <label><?=$langage_lbl['LBL_HANDICAP_QUESTION_ADD_VEHICLES']; ?></label>

								<div class="col-lg-6">

									  <div class="make-switch" data-on="success" data-off="warning" data-on-label='Yes' data-off-label='No'>

										   <input type="checkbox" name="eHandiCapAccessibility" id="eHandiCapAccessibility" <?= ($eHandiCapAccessibility == 'No') ? '' : 'checked'; ?> />

									  </div>

								 </div>

							</b>

						</span>

						

						

		    			<h3>Service Type<span class="red">*</span></h3>

		    			<?php } ?>

		    			<div class="car-type-vehicle ">

		    				<ul>

		    				<div id="vehicleTypes001">

		      				<?php

								foreach ($vehicle_type_data as $key => $value) {

									if($APP_TYPE == 'UberX'){

										$vName = 'vCategory_'.$_SESSION["sess_lang"];

										$vehicle_typeName =$value[$vName].'-'.$value['vVehicleType_'.$_SESSION["sess_lang"]];	

									}else{



									$vehicle_typeName = $value['vVehicleType_'.$_SESSION["sess_lang"]];

									$localization = 'Location : ';

                                   if(($value['iLocationid'] == "-1")) {

                                      	$localization = "All Locations";

                                    } else {

										$localization.= $value['vLocationName'];

                                    }

									}?>

								<li>

									<b>

										<div><?=$vehicle_typeName; ?></div>

										<div style="font-size: 12px;text-transform: capitalize;"><?php echo "( ".$localization ." )";?></div>

									</b>

									<div class="make-switch" data-on="success" data-off="warning" style="margin:10px 0 0">

										<input type="checkbox" class="chk" name="vCarType[]" <?php if(in_array($value['iVehicleTypeId'],$vCarTyp)){?>checked<?php } ?> value="<?=$value['iVehicleTypeId'] ?>" />

									</div>

								</li>

						<?php } ?>

							</div>
							<div id="extrafields"></div>

						</ul>

		      				<strong><input type="submit" class="save-vehicle" name="submit" id="submit" value="<?= $action_show; ?> <?=$langage_lbl['LBL_Vehicle']; ?>"> </strong>

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

    <?php include_once('top/footer_script.php');?>

    <script src="assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>

<?php if ($action == 'Edit') { ?>

	<script>

		window.onload = function () {

			get_model('<?php echo $db_mdl[0]['iMakeId']; ?>', '<?php echo $db_mdl[0]['iModelId']; ?>');

			//get_vehicleType('<?= $iDriverId; ?>','<?= $vCarType; ?>','<?= $eType?>');

		};

	</script>

<?} ?>

<!-- 	<script>

		window.onload = function () {

			var appType = '<?= $APP_TYPE?>';

			if(appType == 'Ride-Delivery-UberX' || appType == 'Ride-Delivery'){

				SelectedAppType= 'Ride';

			} else if(appType == 'Delivery'){

				SelectedAppType == 'Deliver';

			}else {

				SelectedAppType= appType;

			}

		get_vehicleType('','',SelectedAppType);

		};

	</script> -->







<?php if ($error_msg != ' ') { ?>

	<script>

		window.onload = function () {

			get_model('<?php echo $iMakeId; ?>', '<?php echo $iModelId; ?>');



			//codeEdited for barnagay 
            setBarangay('<?php echo $vBarangay;?>','<?php echo $City; ?>','<?php echo $Province; ?>');


$("#City").change(function(){
   // var vCountry=$("#vCountry").val();
   var vState=$("#Province").val();
    var vCity=$("#City").val();

setBarangay('',vCity,vState);
});

$("#vCountry").trigger('change');

setCity('<?php echo $Province; ?>','<?php echo $City; ?>')
		};

	</script>

<?} ?>

<script>
			   function setBarangay(selected,vCity,vState)
{
     

    
$.ajax({
        type: "POST",
        url: 'getCityBarangay.php',
        data: {iCityId: vCity, iStateId: vState},
        success: function (dataHtml)
        {
            var html='<option value="">Select</option>';
            dataHtml=JSON.parse(dataHtml);
            for (var i = 0; i < dataHtml.length; i++) {
                var isSelected='';
if (selected==dataHtml[i].ID) 
{
isSelected='selected';
}

                html +="<option value='"+dataHtml[i].ID+"' "+isSelected+" >"+dataHtml[i].Barangay+"</option>";
            }
            $("#vBarangay").html(html);
        }
    });
}
		function setState(id,selected)
		{
			
			if (id=='PH') 
			{
$("#lbl_vstate").text("Province");
$("#lbl_city").text("City/Municipality");

$("#row_vBarangay").css("display","");
$("#vBarangay").attr("required",true);
$("#vBarangay").prop("required",true);




			}
			else
			{
				$("#lbl_vstate").text("State");
$("#lbl_city").text("City");

$("#row_vBarangay").css("display","none");

$("#vBarangay").attr("required",false);
$("#vBarangay").prop("required",false);
			}
			var fromMod = 'driver';
			var request = $.ajax({
				type: "POST",
				url: 'change_stateCity.php',
				data: {countryId: id, selected: selected,fromMod:fromMod},
				success: function (dataHtml)
				{
					$("#City").html('<option value=""><?= addslashes($langage_lbl['LBL_SELECT_TXT']) ?></option>');
					$("#Province").html(dataHtml);
					if(selected == '')
						setCity('',selected);
				}
			});
		}

	function setCity(id,selected)
		{
			
			// alert(selected);
			var fromMod = 'driver';
			var request = $.ajax({
				type: "POST",
				url: 'change_stateCity.php',
				data: {stateId: id, selected: selected,fromMod:fromMod},
				success: function (dataHtml)
				{
					$("#City").html(dataHtml);
				}
			});
		}
$('#etypedelivery').on('change', function() {

	get_vehicleType('<?= $iDriverId; ?>','<?php echo $vCarType; ?>',this.value,'<?= $vRentalCarType?>');



	if(this.value == 'Delivery'){

		$("#handicapaccess").hide();

	} else {

		$("#handicapaccess").show();

	}

});



<?php if ($_SESSION['sess_user'] == 'company') { ?>

$('#iDriverId').on('change', function() {

	get_vehicleType(this.value,'<?php echo $vCarType; ?>',$( "#etypedelivery" ).val(),'<?= $vRentalCarType?>');

});

<? } ?>



function get_vehicleType(iDriverId,selected='',eType='',rentalselected='') {

	if(eType == 'Delivery'){

		var eType = 'Deliver';

	} else {

		var eType = eType;

	}

	$("#vehicleTypes001").html('Wait...');

	var request = $.ajax({

		type: "POST",

		url: 'ajax_find_vehicleType.php',

		data: "iDriverId=" + iDriverId +"&selected="+selected+"&eType="+eType+"&Front=front&rentalselected="+rentalselected,

		success: function (data) {

			$("#vehicleTypes001").html(data);

				$(".chk").change(function(){
/*$('.chk').attr('checked', false);
$('.chk').prop('checked', false);
	$("#vehicleTypes001 .make-switch div").removeClass("switch-on");
	$("#vehicleTypes001 .make-switch div").addClass("switch-off");
           $(this).parent(".make-switch div").removeClass("switch-off");
      $(this).parent(".make-switch div").addClass("switch-on");
            $(this).attr('checked', true);  
             $(this).prop('checked', true);  */
				

if ($(this).is(":checked")) {
$("#lbl_plate_number").html($(this).data("platenumber")+'<span class="red">* </span>');
getExtraFields($(this).val());
}
else{
	$(".row_id_"+$(this).val()).remove();
}
	


});
						<?php if ($action == 'Edit') { ?>
$(".chk").trigger("change");
						<?php } ?>
		

		}

	});



	request.fail(function (jqXHR, textStatus) {

		alert("Request failed: " + textStatus);

	});

}

function getExtraFields(id)
{
	$(".row_id_"+id).remove();
$.ajax({
	type: "POST",
	url: 'getExtraFieldsVehicles.php',
	data: "id="+id+"&type=vehicle_type"+"&extraField=<?=$extraFieldData;?>",
	success: function (data){	
			
		$("#extrafields").html(data);
	}
	});
}

function get_model(model, modelid) {

	var request = $.ajax({

		type: "POST",

		url: 'ajax_find_model_new.php',

		data: "action=get_model&model=" + model + "&iModelId=" + modelid,

		success: function (data) {
			$("#iModelId").empty().append(data);

			var selectedOption = $('#iModelId').find(":selected").text();

			if(selectedOption != "") {

				$('#iModelId').next(".holder").text(selectedOption);

			}

		}

	});



	request.fail(function (jqXHR, textStatus) {

		alert("Request failed: " + textStatus);

	});

}

	

	function check_licence_plate(plate,id1){

		var request= $.ajax({

			type: "POST",

			url: 'ajax_find_plate.php',

			data: "plate="+plate+"&id="+id1,

			success: function (data){			

				if($.trim(data) == 'yes') {

					$('input[type="submit"]').removeAttr('disabled');

					$("#plate_warning").html("");

				}else {

					$("#plate_warning").html(data);

					$('input[type="submit"]').attr('disabled','disabled');

				}

			}

		});

	}

</script>

<!-- End: Footer Script -->

</body>

</html>
<style type="text/css">
	.form-control
	{
		height: 46px;
		width: 99%;
	}

</style>