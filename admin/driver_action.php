<?php
include_once('../common.php');

require_once(TPATH_CLASS . "/Imagecrop.class.php");
$thumb = new thumbnail();

if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");
     $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();

$sql = "select vCountryCode,vCountry from country where eStatus='Active' ORDER BY vCountry ASC";
$db_country = $obj->MySQLSelect($sql);

$sql = "select vCode,vTitle from language_master where eStatus = 'Active' ORDER BY vTitle ASC";
$db_lang = $obj->MySQLSelect($sql);

$sql = "select iCompanyId,vCompany,eStatus from company where eStatus != 'Deleted' ORDER BY vCompany ASC";
$db_company = $obj->MySQLSelect($sql);

//For Currency
$sql="select vName,eDefault from currency where eStatus='Active' ORDER BY vName ASC";
$db_currency=$obj->MySQLSelect($sql);

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$action = ($id != '') ? 'Edit' : 'Add';

$tbl_name = 'register_driver';
$script = 'Driver';

// set all variables with either post (when submit) either blank (when insert)
$vName = isset($_POST['vName']) ? $_POST['vName'] : '';
$vLastName = isset($_POST['vLastName']) ? $_POST['vLastName'] : '';
$vEmail = isset($_POST['vEmail']) ? $_POST['vEmail'] : '';
$vUserName = isset($_POST['vEmail']) ? $_POST['vEmail'] : '';
$vPassword = isset($_POST['vPassword']) ? $_POST['vPassword'] : '';
$vPhone = isset($_POST['vPhone']) ? $_POST['vPhone'] : '';
$vCaddress = isset($_POST['vCaddress']) ? $_POST['vCaddress'] : '';
$vCountry = isset($_POST['vCountry']) ? $_POST['vCountry'] : $DEFAULT_COUNTRY_CODE_WEB;
$vCity = isset($_POST['vCity']) ? $_POST['vCity'] : '';
$vZip = isset($_POST['vZip']) ? $_POST['vZip'] : '';
$vState = isset($_POST['vState']) ? $_POST['vState'] : '';
$iCompanyId = isset($_POST['iCompanyId']) ? $_POST['iCompanyId'] : '';
$vCode = isset($_POST['vCode']) ? $_POST['vCode'] : '';
$eStatus = isset($_POST['eStatus']) ? $_POST['eStatus'] : 'Inactive';
$vLang = isset($_POST['vLang']) ? $_POST['vLang'] : '';
$vBarangay = isset($_POST['vBarangay']) ? $_POST['vBarangay'] : '';
$dBirthDate=isset($_POST['dBirthDate']) ? $_POST['dBirthDate'] : '';

//$dBirthDate = isset($_POST['dBirthDate']) ? $_POST['dBirthDate'] : '';
//$dBirthDate = $_POST['vYear'].'-'.$_POST['vMonth'].'-'.$_POST['vDay'];
$vPaymentEmail = isset($_POST['vPaymentEmail']) ? $_POST['vPaymentEmail'] : '';
$vBankAccountHolderName = isset($_POST['vBankAccountHolderName']) ? $_POST['vBankAccountHolderName'] : '';
$vAccountNumber = isset($_POST['vAccountNumber']) ? $_POST['vAccountNumber'] : '';
$vBankLocation = isset($_POST['vBankLocation']) ? $_POST['vBankLocation'] : '';
$vBankName = isset($_POST['vBankName']) ? $_POST['vBankName'] : '';
$vBIC_SWIFT_Code = isset($_POST['vBIC_SWIFT_Code']) ? $_POST['vBIC_SWIFT_Code'] : '';
$tProfileDescription = isset($_POST['tProfileDescription']) ? $_POST['tProfileDescription'] : '';
$vCurrencyDriver=isset($_POST['vCurrencyDriver']) ? $_POST['vCurrencyDriver'] : '';
$vPass = ($vPassword != "") ? $generalobj->encrypt_bycrypt($vPassword) : '';
$eGender = isset($_POST['eGender']) ? $_POST['eGender'] : '';
$oldImage = isset($_POST['oldImage']) ? $_POST['oldImage'] : '';
$backlink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$previousLink = isset($_POST['backlink']) ? $_POST['backlink'] : '';


$Suffix = isset($_POST['Suffix']) ? $_POST['Suffix'] : '';
$MiddleName = isset($_POST['MiddleName']) ? $_POST['MiddleName'] : '';


$eReftype = "Driver";

/*if($action == 'Add'){
	$vCountry = $DEFAULT_COUNTRY_CODE_WEB;
}*/

if (isset($_POST['btnsubmit'])) {
	if($SITE_VERSION == "v5"){
		$data_driver_pref = $generalobj->Update_User_Preferences($id,$_REQUEST);
		
		$_SESSION['success'] = '1';
		$_SESSION['var_msg'] = 'Preferences Updated successfully.';
		
		header("Location:driver_action.php?id=".$id);
		exit;
	}
}

if (isset($_POST['submit'])) {
     
	if(!empty($id) && SITE_TYPE =='Demo'){
		$_SESSION['success'] = 2;
		header("Location:driver.php?id=".$id);exit;
	}
	
	require_once("library/validation.class.php");
    $validobj = new validation();
    $validobj->add_fields($_POST['vName'], 'req', ' Name is required');
    $validobj->add_fields($_POST['vLastName'], 'req', 'Last Name is required');
    $validobj->add_fields($_POST['vEmail'], 'req', 'Email Address is required.');
    $validobj->add_fields($_POST['vEmail'], 'email', 'Please enter valid Email Address.');
    // $validobj->add_fields($_POST['eGender'], 'req', 'Please choose gender.');
    if ($action == "Add") {
		$validobj->add_fields($_POST['vPassword'], 'req', 'Password is required.');
	}
    $validobj->add_fields($_POST['vPhone'], 'req', 'Phone Number is required.');
    $validobj->add_fields($_POST['vCountry'], 'req', 'Country is required.');
    $validobj->add_fields($_POST['vCaddress'], 'req', 'Address is required.');
    $validobj->add_fields($_POST['vLang'], 'req', 'Language is required.');
    $validobj->add_fields($_POST['iCompanyId'], 'req', 'Company is required.');
       $validobj->add_fields($_POST['dBirthDate'], 'req', 'Date of Birth is required.');
  //  $validobj->add_fields($_POST['dBirthDate'], 'req', 'Birth Date is required.');
	//$validobj->add_fields($_POST['vYear'], 'req', 'Birth Year is required.');
	//$validobj->add_fields($_POST['vMonth'], 'req', 'Birth Month is required.');
	//$validobj->add_fields($_POST['vDay'], 'req', 'Birth Day is required.');
    $validobj->add_fields($_POST['vCurrencyDriver'], 'req', 'Currency is required.');
	
    $error = $validobj->validate();
	
	
	//Other Validations
    if ($vEmail != "") {
        if ($id != "") {
            $msg1 = $generalobj->checkDuplicateAdminNew('iDriverId', $tbl_name, Array('vEmail'), $id, "");
        } else {
            $msg1 = $generalobj->checkDuplicateAdminNew('vEmail', $tbl_name, Array('vEmail'), "", "");
        }
        
        if ($msg1 == 1) {
            $error .= '* Email Address is already exists.<br>';
        }
    }
	$error .= $validobj->validateFileType($_FILES['vImage'], 'jpg,jpeg,png,gif,bmp', '* Image file is not valid.');
	//Other Validations
	
	if ($error) {
        $success = 3;
        $newError = $error;
        //exit;
    } 
	else
	{
		$vRefCodePara = '';
		$q = "INSERT INTO ";
		$where = '';
		if ($action == 'Edit') {
			 $str = " ";
		} else {
			 $str = " , eStatus = '$eStatus' ";
			 $vRefCode = $generalobj->ganaraterefercode($eReftype);
			 $vRefCodePara = "`vRefCode` = '" . $vRefCode . "',";
		}
		
		if(SITE_TYPE=='Demo')
		{
			$str = " , eStatus = 'active' ";
		}
		 
		if ($id != '') {
			$q = "UPDATE ";
			$where = " WHERE `iDriverId` = '" . $id . "'";
		}

		if ($action == 'Add') {
            $str1 = "`tRegistrationDate` = '".date("Y-m-d H:i:s")."',";
        } else {
            $str1 = '';
        }

		$passPara = '';
		if($vPass != ""){
			$passPara = "`vPassword` = '" . $vPass . "',";
		}
		
		if ($action == 'Edit') {
			$sql="select iDriverVehicleId from driver_vehicle where iDriverId='".$id."'";
			$data_vehicle = $obj->MySQLSelect($sql);
			
			if(count($data_vehicle) > 0){
				$sql = "UPDATE driver_vehicle set iCompanyId='".$iCompanyId."',vCarType='' WHERE iDriverId='".$id."'";
				$obj->sql_query($sql);
			}
		}
		
		 $query = $q . " `" . $tbl_name . "` SET
			`vName` = '" . $vName . "',
			`vLastName` = '" . $vLastName . "',
			`vCountry` = '" . $vCountry . "',
			`vCaddress` = '" . $vCaddress . "',
			`vCity` = '" . $vCity ."',
			`vZip` = '" . $vZip . "',
			`vState` = '" . $vState. "',
			`vCode` = '" . $vCode . "',
			`vEmail` = '" . $vEmail . "',
			`vLoginId` = '" . $vEmail . "',
			`dBirthDate`='".$dBirthDate."',
			 $passPara		
			`iCompanyId` = '" . $iCompanyId . "',
			`vPhone` = '" . $vPhone . "',
			`vImage` = '" . $oldImage . "',
			 $vRefCodePara
			`vPaymentEmail` = '" . $vPaymentEmail . "',
			`eGender` = '" . $eGender . "',
			`vBankAccountHolderName` = '" . $vBankAccountHolderName . "',
			`vBankLocation` = '" . $vBankLocation . "',
			`vBankName` = '" .$vBankName . "',
			`vAccountNumber` = '" . $vAccountNumber . "',
			`vBIC_SWIFT_Code` = '" . $vBIC_SWIFT_Code . "',
			`tProfileDescription` = '" . $tProfileDescription . "',
			`vCurrencyDriver`='" . $vCurrencyDriver . "',
			`vBarangay`='".$vBarangay."',
			`Suffix`='".$Suffix."',
			`MiddleName`='".$MiddleName."',
			 $str1
			`vLang` = '" . $vLang . "' $str" . $where;
		$obj->sql_query($query);
		if($id == "") {
			$id = $obj->GetInsertId();
		}
		
/* $Service=isset($_REQUEST["Service"]) ? $_REQUEST['Service'] : '';

          			      	$obj->MySQLSelect("delete  from driver_registered_service where iDriverId='$id'");

          if($Service!="")
          {

		      foreach ($Service as $key => $value)
		       {

		      	$query_service="INSERT INTO `driver_registered_service`( `iDriverId`, `iVehicleCategoryId`) VALUES ('$id','$value')";

		      	$obj->MySQLSelect($query_service);
		      
		       }
		   }*/

		if ($action == 'Add') {
			if($SITE_VERSION == "v5"){
				$set_driver_pref = $generalobj->Insert_Default_Preferences($id);
			}
			if($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') {
				$query ="SELECT GROUP_CONCAT(iVehicleTypeId)as countId FROM `vehicle_type` WHERE `eType` = 'UberX'";
				$result = $obj->MySQLSelect($query);
				
				$Drive_vehicle['iDriverId'] = $id;
				$Drive_vehicle['iCompanyId'] = "1";
				$Drive_vehicle['iMakeId'] = "3";
				$Drive_vehicle['iModelId'] = "1";
				$Drive_vehicle['iYear'] = Date('Y');
				$Drive_vehicle['vLicencePlate'] = "My Services";
				$Drive_vehicle['eStatus'] = "Active";
				$Drive_vehicle['eCarX'] = "Yes";
				$Drive_vehicle['eType'] = "UberX";
				$Drive_vehicle['eCarGo'] = "Yes";
				if(SITE_TYPE=='Demo'){
					$Drive_vehicle['vCarType'] = $result[0]['countId'];
				}else{
					$Drive_vehicle['vCarType'] = "";
				}
				//$Drive_vehicle['vCarType'] = $result[0]['countId'];
				$iDriver_VehicleId=$obj->MySQLQueryPerform('driver_vehicle',$Drive_vehicle,'insert');

				if($APP_TYPE == 'UberX') {
					$sql = "UPDATE register_driver set iDriverVehicleId='".$iDriver_VehicleId."' WHERE iDriverId='".$id."'";
					$obj->sql_query($sql);
				}
						
				/*$sql="select iVehicleTypeId,iVehicleCategoryId,eFareType,fFixedFare,fPricePerHour from vehicle_type where 1=1";
				$data_vehicles = $obj->MySQLSelect($sql);
				//echo "<pre>";print_r($data_vehicles);exit;
				
				if($data_vehicles[$i]['eFareType'] != "Regular")
				{
					for($i=0 ; $i < count($data_vehicles); $i++){
						$Data_service['iVehicleTypeId'] = $data_vehicles[$i]['iVehicleTypeId'];
						$Data_service['iDriverVehicleId'] = $iDriver_VehicleId;
						
						if($data_vehicles[$i]['eFareType'] == "Fixed"){
							$Data_service['fAmount'] = $data_vehicles[$i]['fFixedFare'];
						}
						else if($data_vehicles[$i]['eFareType'] == "Hourly"){
							$Data_service['fAmount'] = $data_vehicles[$i]['fPricePerHour'];
						}
						$data_service_amount = $obj->MySQLQueryPerform('service_pro_amount',$Data_service,'insert');
					}
				} */

				if($APP_TYPE == 'Ride-Delivery-UberX') {
					if(SITE_TYPE=='Demo')
					{
						$query ="SELECT GROUP_CONCAT(iVehicleTypeId)as countId FROM `vehicle_type` WHERE (`eType` = 'Ride' OR `eType` = 'Deliver')";
						$result = $obj->MySQLSelect($query);
						$Drive_vehicle_Ride['iDriverId'] = $id;
						$Drive_vehicle_Ride['iCompanyId'] = "1";
						$Drive_vehicle_Ride['iMakeId'] = "5";
						$Drive_vehicle_Ride['iModelId'] = "18";
						$Drive_vehicle_Ride['iYear'] = "2014";
						$Drive_vehicle_Ride['vLicencePlate'] = "CK201";
						$Drive_vehicle_Ride['eStatus'] = "Active";
						$Drive_vehicle_Ride['eCarX'] = "Yes";
						$Drive_vehicle_Ride['eCarGo'] = "Yes";
						$Drive_vehicle_Ride['eType'] = "Ride";	
						$Drive_vehicle_Ride['vCarType'] = $result[0]['countId'];
						$iDriver_VehicleId=$obj->MySQLQueryPerform('driver_vehicle',$Drive_vehicle_Ride,'insert');
						$sql = "UPDATE register_driver set iDriverVehicleId='".$iDriver_VehicleId."' WHERE iDriverId='".$id."'";
						$obj->sql_query($sql);


						$query ="SELECT GROUP_CONCAT(iVehicleTypeId)as countId FROM `vehicle_type` WHERE (`eType` = 'Ride' OR `eType` = 'Deliver')";
						$result = $obj->MySQLSelect($query);
						$Drive_vehicle_Deliver['iDriverId'] = $id;
						$Drive_vehicle_Deliver['iCompanyId'] = "1";
						$Drive_vehicle_Deliver['iMakeId'] = "5";
						$Drive_vehicle_Deliver['iModelId'] = "18";
						$Drive_vehicle_Deliver['iYear'] = "2014";
						$Drive_vehicle_Deliver['vLicencePlate'] = "CK201";
						$Drive_vehicle_Deliver['eStatus'] = "Active";
						$Drive_vehicle_Deliver['eCarX'] = "Yes";
						$Drive_vehicle_Deliver['eCarGo'] = "Yes";
						$Drive_vehicle_Deliver['eType'] = "Delivery";	
						$Drive_vehicle_Deliver['vCarType'] = $result[0]['countId'];
						$iDriver_VehicleId=$obj->MySQLQueryPerform('driver_vehicle',$Drive_vehicle_Deliver,'insert');
					}
				}
				 
			} else {
				if(SITE_TYPE=='Demo')
				{
					if($APP_TYPE == 'Delivery'){
						$app_type='Deliver';
					} else {
						$app_type= $APP_TYPE;
					}

					$query ="SELECT GROUP_CONCAT(iVehicleTypeId)as countId FROM `vehicle_type`  WHERE `eType` = '".$app_type ."'";
					$result = $obj->MySQLSelect($query);
					$Drive_vehicle['iDriverId'] = $id;
					$Drive_vehicle['iCompanyId'] = "1";
					$Drive_vehicle['iMakeId'] = "5";
					$Drive_vehicle['iModelId'] = "18";
					$Drive_vehicle['iYear'] = "2014";
					$Drive_vehicle['vLicencePlate'] = "CK201";
					$Drive_vehicle['eStatus'] = "Active";
					$Drive_vehicle['eCarX'] = "Yes";
					$Drive_vehicle['eCarGo'] = "Yes";
					$Drive_vehicle['eType'] = $app_type;		
					$Drive_vehicle['vCarType'] = $result[0]['countId'];
					$iDriver_VehicleId=$obj->MySQLQueryPerform('driver_vehicle',$Drive_vehicle,'insert');
					$sql = "UPDATE register_driver set iDriverVehicleId='".$iDriver_VehicleId."' WHERE iDriverId='".$id."'";
					$obj->sql_query($sql);
				}
			}
		}
		
		if ($_FILES['vImage']['name'] != "") {
			
			$image_object = $_FILES['vImage']['tmp_name'];
			$image_name = $_FILES['vImage']['name'];
			$img_path = $tconfig["tsite_upload_images_driver_path"];
			$temp_gallery = $img_path . '/';
			$check_file = $img_path . '/' . $id. '/' .$oldImage;
			
			if ($oldImage != '' && file_exists($check_file)) {
				@unlink($img_path . '/' . $id. '/' . $oldImage);
				@unlink($img_path . '/' . $id. '/1_' . $oldImage);
				@unlink($img_path . '/' . $id. '/2_' . $oldImage);
				@unlink($img_path . '/' . $id. '/3_' . $oldImage);
			}
			
			$Photo_Gallery_folder = $img_path . '/' . $id . '/';
			if (!is_dir($Photo_Gallery_folder)) {
				mkdir($Photo_Gallery_folder, 0777);
			}
			$img1 = $generalobj->general_upload_image($image_object, $image_name, $Photo_Gallery_folder, '','','', '', '', '', 'Y', '', $Photo_Gallery_folder);

			if($img1!=''){
				if(is_file($Photo_Gallery_folder.$img1)) {
				   include_once(TPATH_CLASS."/SimpleImage.class.php");
				   $img = new SimpleImage();
				   list($width, $height, $type, $attr)= getimagesize($Photo_Gallery_folder.$img1);
				   if($width < $height){
					  $final_width = $width;
				   }else{
					  $final_width = $height;
				   }
				   $img->load($Photo_Gallery_folder.$img1)->crop(0, 0, $final_width, $final_width)->save($Photo_Gallery_folder.$img1);
				   $img1 = $generalobj->img_data_upload($Photo_Gallery_folder,$img1,$Photo_Gallery_folder, $tconfig["tsite_upload_images_member_size1"], $tconfig["tsite_upload_images_member_size2"], $tconfig["tsite_upload_images_member_size3"],"");
				}
			}
			$vImgName = $img1;
			$sql = "UPDATE ".$tbl_name." SET `vImage` = '" . $vImgName . "' WHERE `iDriverId` = '" . $id . "'";
			$obj->sql_query($sql);
		}
		if ($action == "Add") {
			$_SESSION['success'] = '1';
			$_SESSION['var_msg'] = $langage_lbl_admin["LBL_DRIVER_TXT_ADMIN"].' Insert Successfully.';
		} else {
			$_SESSION['success'] = '1';
			$_SESSION['var_msg'] = $langage_lbl_admin["LBL_DRIVER_TXT_ADMIN"].' Updated Successfully.';
		}
		
		if ($action == 'Add') {
/*
			$maildata['EMAIL'] = $vEmail;
			$maildata['NAME'] = $vName.' '.$vLastName;
			$maildata['PASSWORD'] =  $langage_lbl_admin["LBL_PASSWORD"].": ".$vPassword;
			$maildata['SOCIALNOTES'] = '';
			$generalobj->send_email_user("MEMBER_REGISTRATION_USER",$maildata); */


$nortification_query="SELECT `Event`, `ActionBy`, `NotifyCompany`, `NotifyProvider`, `NotifyCustomer`, `NotifyAdministrator`, `AdditionalEmail` FROM `nortification_settings` WHERE `Event`='Driver Signup' and  `ActionBy`='Super Admin'";

$result_nortification = $obj->MySQLSelect($nortification_query);
if(count($result_nortification)>0)
{

if($result_nortification[0]['NotifyCompany']=="on")
{
	   $company_email_query="SELECT  `vEmail` FROM `company` WHERE iCompanyId='$iCompanyId'";
		$result_email = $obj->MySQLSelect($company_email_query);
		        $maildata['COMPANY'] =$result_email[0]["vEmail"];
                $maildata['EMAIL'] = $vEmail;
               	$maildata['NAME'] = $vName.' '.$vLastName;
					$maildata['PASSWORD'] =  $langage_lbl_admin["LBL_PASSWORD"].": ".$vPassword;
				$maildata['SOCIALNOTES'] = '';

$generalobj->send_email_user("DRIVER_REGISTRATION_COMPANY",$maildata); 

}


if($result_nortification[0]['AdditionalEmail']!="")
{
	  
                $maildata['ADDITIONALEMAIL'] = $result_nortification[0]['AdditionalEmail'];
                  $maildata['EMAIL'] = $vEmail;
					$maildata['NAME'] = $vName.' '.$vLastName;
			$maildata['PASSWORD'] =  $langage_lbl_admin["LBL_PASSWORD"].": ".$vPassword;
				$maildata['SOCIALNOTES'] = '';

        $generalobj->send_email_user("DRIVER_REGISTRATION_ADDITIONALEMAIL",$maildata); 

}


	//$iCompanyId
	            /*  if($result_nortification[0]['NotifyCustomer']=="on")
	               {
	               	$maildata['EMAIL'] = $vEmail;
					$maildata['NAME'] = $vName.' '.$vLastName;
				    $maildata['PASSWORD'] =  $langage_lbl_admin["LBL_PASSWORD"].": ".$vPassword;
				    $maildata['SOCIALNOTES'] = '';

	     			$generalobj->send_email_user("MEMBER_REGISTRATION_USER",$maildata);
					}*/


             if($result_nortification[0]['NotifyProvider']=="on")
                {
	            $maildata['EMAIL'] = $vEmail;
					$maildata['NAME'] = $vName.' '.$vLastName;
				$maildata['PASSWORD'] =  $langage_lbl_admin["LBL_PASSWORD"].": ".$vPassword;
				$maildata['SOCIALNOTES'] = '';
                $generalobj->send_email_user("DRIVER_REGISTRATION_USER",$maildata);
				}
			if($result_nortification[0]['NotifyAdministrator']=="on")
                {
                $maildata['EMAIL'] = $vEmail;
				$maildata['NAME'] = $vName.' '.$vLastName;
				$maildata['PASSWORD'] =  $langage_lbl_admin["LBL_PASSWORD"].": ".$vPassword;
				$maildata['SOCIALNOTES'] = '';
				$generalobj->send_email_user("DRIVER_REGISTRATION_ADMIN",$maildata); 
				}
			
}





		}
		
		//End :: Upload Image Script
		header("Location:".$backlink);exit;
	}
}
// for Edit

if ($action == 'Edit') {
     $sql = "SELECT * FROM " . $tbl_name . " WHERE iDriverId = '" . $id . "'";
     $db_data = $obj->MySQLSelect($sql);
     // $vPass = $generalobj->decrypt($db_data[0]['vPassword']);
	 if($db_data[0]['eStatus'] == "active") {
		 $actionType = "approve";
	 }else {
		 $actionType = "pending";
	 }
     $vLabel = $id;
     if (count($db_data) > 0) {
          foreach ($db_data as $key => $value) {
               $vName = $value['vName'];
               $iCompanyId = $value['iCompanyId'];
               $vLastName = $generalobjAdmin->clearName(" ".$value['vLastName']);
               $vCaddress = $value['vCaddress'];
               $vCountry = $value['vCountry'];
               $vCity = $value['vCity'];
               $vZip = $value['vZip'];
			   $vState = $value['vState'];
			   $vCode = $value['vCode'];
               $vEmail = $generalobjAdmin->clearEmail($value['vEmail']);
               $vUserName = $value['vLoginId'];
               $vPassword = $value['vPassword'];
               $vBarangay=$value['vBarangay'];
               $dBirthDate=$value['dBirthDate'];
			   /* $dBirthDate = $value['dBirthDate'];
				if($dBirthDate == "0000-00-00")
				{
					$dBirthDate = "";
				} */
/*				$dBirthYear = date("Y",strtotime($value['dBirthDate']));
				$dBirthMonth = date("m",strtotime($value['dBirthDate']));
				$dBirthDay = date("d",strtotime($value['dBirthDate']));
				if($dBirthYear == "0000" ||  $dBirthMonth == "00" || $dBirthDay == "00")
				{
					$dBirthDate = "";
				}*/
			   $eGender = $value['eGender'];
               $vPhone = $generalobjAdmin->clearPhone($value['vPhone']);
               $vLang = $value['vLang'];
               $oldImage = $value['vImage'];
               $vCurrencyDriver=$value['vCurrencyDriver'];               
               $vPaymentEmail=$value['vPaymentEmail'];
               $vBankAccountHolderName=$value['vBankAccountHolderName'];
               $vAccountNumber=$value['vAccountNumber'];
               $vBankLocation=$value['vBankLocation'];
               $vBankName=$value['vBankName'];
               $vBIC_SWIFT_Code=$value['vBIC_SWIFT_Code'];
               $tProfileDescription=$value['tProfileDescription'];

               $MiddleName=$value['MiddleName'];
               $Suffix=$value['Suffix'];
          }

/*
$sql = "select iVehicleCategoryId from driver_registered_service  WHERE iDriverId = '" . $id . "'";
			$db_services = $obj->MySQLSelect($sql);*/
     }
	 
	 if($SITE_VERSION == "v5"){
		 $sql="select * from preferences where eStatus ='Active'";
		 $data_preference = $obj->MySQLSelect($sql);
		 
		$data_driver_pref = $generalobj->Get_User_Preferences($id);
	 }
}
?>
<!DOCTYPE html>
<html lang="en">
     <head>
          <meta charset="UTF-8" />
          <title><?=$SITE_NAME?> | <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?>  <?= $action; ?></title>
          <meta content="width=device-width, initial-scale=1.0" name="viewport" />
          <?
          include_once('global_files.php');
          ?>
          <!-- On OFF switch -->
          <link href="../assets/css/jquery-ui.css" rel="stylesheet" />
          <link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />

          	   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
     </head>
     <!-- END  HEAD-->
     <!-- BEGIN BODY-->
     <body class="padTop53 " >

          <!-- MAIN WRAPPER -->
          <div id="wrap">
               <?
               include_once('header.php');
               include_once('left_menu.php');
               ?>
               <!--PAGE CONTENT -->
               <div id="content">
                    <div class="inner">
                         <div class="row">
                              <div class="col-lg-12">
                                   <h2><?= $action; ?> <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?>  <?= $vName; ?></h2>
                                   <a href="javascript:void(0);" class="back_link">
                                        <input type="button" value="Back to Listing" class="add-btn">
                                   </a>
                              </div>
                         </div>
                         <hr />
                         <div class="body-div">
                              <div class="form-group">
									<? if ($success == 2) {?>
									<div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        "Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
									</div><br/>
									<?} ?>
									<? if ($success == 3) {?>
									<div class="alert alert-danger alert-dismissable">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<?php print_r($error); ?>
									</div><br/>
									<?} ?>
									<form id="_driver_form" name="_driver_form" method="post" action="" enctype="multipart/form-data">
										<input type="hidden" name="actionOf" id="actionOf" value="<?php echo $action; ?>"/>
                                        <input type="hidden" name="id" id="iDriverId" value="<?= $id; ?>"/>
										<input type="hidden" name="oldImage" value="<?= $oldImage; ?>"/>
										<input type="hidden" name="previousLink" id="previousLink" value="<?php echo $previousLink; ?>"/>
										<input type="hidden" name="backlink" id="backlink" value="driver.php"/>
                                       <?php if($id){?>
                                        <div class= "row col-md-12" id="hide-profile-div">
											<? $class = ($SITE_VERSION == "v5") ? "col-lg-3" : "col-lg-4";?>
                                             <div class="<?=$class?>">
                                                  <b><?php if ($oldImage == 'NONE' || $oldImage == '') { ?>
                                                        <img src="../assets/img/profile-user-img.png" alt="" >
                                                    <?} else { 
                                                    	if(file_exists('../webimages/upload/Driver/' .$id. '/3_' .$oldImage)) { 
                                                    ?>
                                                        <img src = "<?php echo $tconfig["tsite_upload_images_driver"]. '/' .$id. '/3_' .$oldImage ?>" class="img-ipm" />
                                                    <? 	} else { ?>
                                                    	<img src="../assets/img/profile-user-img.png" alt="" >
                                                    <?php }
                                            		} ?>
                                                   </b>
                                             </div>
											<? if($SITE_VERSION == "v5"){ ?>
											 <div class="col-lg-4">
											 <fieldset class="col-md-12 field">
                                                 <legend class="lable"><h4 class="headind1"> Preferences: </h4></legend>
												  <p>
													<div class=""> <? foreach($data_driver_pref as $val){?>
															<img data-toggle="tooltip" class="borderClass-aa1 border_class-bb1" title="<?=$val['pref_Title']?>" src="<?=$tconfig["tsite_upload_preference_image_panel"].$val['pref_Image']?>">
																	<? } ?>
													</div>
														
														<span class="col-md-12"><a href="" data-toggle="modal" data-target="#myModal" id="show-edit-language-div" class="hide-language1">
														<i class="fa fa-pencil" aria-hidden="true"></i>
														Manage Preferences</a></span>
												</p>
												</fieldset>
                                             </div>
											<? } ?>
                                        </div>
                                        <?php }?>
                                   <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Suffix<span class="red"> </span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" maxlength="5" class="form-control" name="Suffix"  id="Suffix" value="<?=$Suffix;?>" placeholder="Suffix" >
                                             </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>First Name<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" maxlength="25" class="form-control" name="vName"  id="vName" value="<?= $vName; ?>" placeholder="First Name" >
                                             </div>
                                        </div>

                                       <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Middle Name<span class="red"> </span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" maxlength="25" class="form-control" name="MiddleName"  id="MiddleName" value="<?= $MiddleName; ?>" placeholder="Middle Name" >
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Last Name<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" maxlength="25" class="form-control" name="vLastName"  id="vLastName" value="<?= $vLastName;?>" placeholder="Last Name" >
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Email<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" maxlength="30" class="form-control" name="vEmail"  id="vEmail" value="<?= $vEmail; ?>" placeholder="Email" >
                                             </div><div id="emailCheck"></div>
                                        </div>
										<div class="row">
                                             <div class="col-lg-12">
                                                  <label>Password <span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="password" class="form-control" name="vPassword"  id="vPassword" value="" placeholder="Password" >
                                             </div>
                                        </div>

										<div class="row">
											<div class="col-lg-12">
												<label>Gender</label>
											</div>
											<div class="col-lg-6 ">
												<input id="r4" name="eGender" type="radio" value="Male"
												  <?php if ($eGender == 'Male' && $action!= "Add") { echo 'checked'; } ?> >
												<label for="r4">Male</label>&nbsp;&nbsp;&nbsp;&nbsp;
												<input id="r5" name="eGender" type="radio" value="Female" class="required" 
													<?php if ($eGender == 'Female' && $action!= "Add") { echo 'checked'; } ?> >
												<label for="r5">Female</label>
											</div>
										</div>
										<!-- codeEdited for DOB -->
											<div class="row">
                                             <div class="col-lg-12">
                                                  <label>Date of Birth <span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" class="form-control" name="dBirthDate"  id="dBirthDate" value="<?= $dBirthDate; ?>" placeholder="YYYY-MM-DD" >
                                             </div>
                                        </div>
										<!--<div class="row">
                                             <div class="col-lg-12">
                                                  <label>Birth date <span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" id="dp5" name="dBirthDate" placeholder="From Date"  readonly class="form-control" value="<?= $dBirthDate ?>" style="cursor:default; background-color: #fff" required />
                                             </div>
                                        </div>-->
										
<!-- 									<div class="row">
											<div class="col-md-6">
												<label class="date-birth">
												<?=$langage_lbl['LBL_Date_of_Birth']; ?><label>
												<select name="vDay" Id="vDay" data="DD" class="custom-select-new required">
													<option value="">Date</option>
													<?php for($i=1;$i<=31;$i++) {?>
													<option value="<?=$i?>" <?=($i == $dBirthDay )?'Selected': '';?>>
													<?=$i?>
													</option>
													<?php }?>
												</select>
												<select data="MM" Id="vMonth" name="vMonth" class="custom-select-new required" >
													<option value="">Month</option>
													<?php for($i=1;$i<=12;$i++) {?>
													<option value="<?=$i?>" <?=($i == $dBirthMonth )?'Selected': '';?>>
													<?=$i?>
													</option>
													<?php }?>
												</select>
												<select data="YYYY" Id="vYear" name="vYear" class="custom-select-new required">
													<option value="">Year</option>
													 <?php for($i=(date("Y")-$START_BIRTH_YEAR_DIFFERENCE);$i >= (date("Y")-$BIRTH_YEAR_DIFFERENCE);$i--) {?>
													<option value="<?=$i?>" <?=($i == $dBirthYear )?'Selected': '';?>>
													<?=$i?>
													</option>
													<?php }?>
												</select>
																						
											</div>
										</div> -->	

										 <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Profile Picture</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="file" class="form-control" name="vImage"  id="vImage" placeholder="Name Label" style="padding-bottom: 39px;">
                                             </div>
                                        </div>

                                        <div class="row">
											<div class="col-lg-12">
												<label>Country <span class="red"> *</span></label>
											</div>
											<div class="col-lg-6">
												<select class="form-control" name = 'vCountry' id="vCountry" onChange="setState(this.value,''),changeCode(this.value);" >
													<option value="">Select</option>
													<? for($i=0;$i<count($db_country);$i++){ ?>
													<option value = "<?= $db_country[$i]['vCountryCode'] ?>" <?if($DEFAULT_COUNTRY_CODE_WEB == $db_country[$i]['vCountryCode'] && $action == 'Add') { ?> selected <?php } else if($vCountry==$db_country[$i]['vCountryCode']){?>selected<? } ?>><?= $db_country[$i]['vCountry'] ?></option>
													<? } ?>
												</select>
											</div>
										</div>
										
										<div class="row">
											<div class="col-lg-12">
												<label id="lbl_vstate">Province</label>
											</div>
											<div class="col-lg-6">
												<select class="form-control" name = 'vState' id="vState" onChange="setCity(this.value,'');" >
													<option value="">Select</option>
												</select>
											</div>
										</div>
										
										<div class="row">
											<div class="col-lg-12">
												<label id="lbl_city">City/Municipality</label>
											</div>
											<div class="col-lg-6">
												<select class="form-control" name = 'vCity' id="vCity"  >
													<option value="">Select</option>
												</select>
											</div>
										</div>

										<div class="row BarangayRow" id="row_vBarangay">
											<div class="col-lg-12">
												<label>Barangay <span class="red"> *</span></label>
											</div>
											<div class="col-lg-6">
												<select required class="form-control" name = 'vBarangay' id="vBarangay"  >
													<option value="">Select</option>
												</select>
											</div>
										</div>

										<div class="row">
                                             <div class="col-lg-12">
                                                  <label>House Number, Building Number, and Street Name <span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" maxlength="50" class="form-control" name="vCaddress"  id="vCaddress" value="<?= $vCaddress ?>" placeholder="House Number, Building Number, and Street Name" >
                                             </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label><?=$langage_lbl['LBL_ZIP_CODE_SIGNUP']; ?><span class="red"></span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                <input type="text" maxlength="5" class="form-control" name="vZip"  id="vZip" value="<?= $vZip; ?>" placeholder="<?=$langage_lbl['LBL_ZIP_CODE_SIGNUP']; ?>" >
                                             </div>
                                        </div>
										
                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Phone<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" class="form-select-2" id="code" name="vCode" value="<?= $vCode ?>"  readonly style="width: 10%;height: 36px;text-align: center;"/ >
                                                  <input type="text" maxlength="15" class="form-control"  style="margin-top: 5px; width:90%;" name="vPhone"  id="vPhone" value="<?= $vPhone; ?>" placeholder="Phone" >
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Company<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
												
                                                  <select  class="form-control" name = 'iCompanyId'  id= 'iCompanyId' >
                                                       <option value="">--select--</option>
                                                       <?
														for ($i = 0; $i < count($db_company); $i++) { 
															$status_cmp = ($db_company[$i]['eStatus'] == "Inactive") ? " (Inactive)" : "";   
														?>
                                                       <option value = "<?= $db_company[$i]['iCompanyId'] ?>" <?= ($db_company[$i]['iCompanyId'] == $iCompanyId) ? 'selected' : ''; ?>>
														<?=$generalobjAdmin->clearCmpName($db_company[$i]['vCompany'].$status_cmp); ?>
                                                       </option>
                                                       <? } ?>
                                                  </select>
                                             </div>
                                        </div>
										<?php 
										if(count($db_lang) <=1){ ?>
										<input name="vLang" type="hidden" class="create-account-input" value="<?php echo $db_lang[0]['vCode'];?>"/>
										<?php }else{ ?>
                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Language<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <select  class="form-control" name = 'vLang' >
                                                       <option value="">--select--</option>
                                                       <? for ($i = 0; $i < count($db_lang); $i++) { ?>
                                                       <option value = "<?= $db_lang[$i]['vCode'] ?>" <?= ($db_lang[$i]['vCode'] == $vLang) ? 'selected' : ''; ?>>
														<?= $db_lang[$i]['vTitle'] ?>
                                                       </option>
                                                       <? } ?>
                                                  </select>
                                             </div>
                                        </div>
										<?php } ?>
<!-- 
                                       <div class="row">
                                       	 <div class="col-lg-12">
                                <label>Services <span class="red">*</span> </label>
                                             </div>

										<div class="col-md-6">


                                       <select   class="form-control miltiselect" name='Service[]' id="Service" multiple="multiple" required="true">
                                       

                                                <option value='Ride' <? foreach ($db_services as $key => $value) {
                                                	if($value['iVehicleCategoryId']=='Ride')
                                                		echo 'selected';
                                                } ?>  >Ride</option>
                                                <option value='Delivery' <? foreach ($db_services as $key => $value) {
                                                	if($value['iVehicleCategoryId']=='Delivery')
                                                		echo 'selected';
                                                } ?> >Delivery</option>

      <?

       $vehicle_type_sql1 = "SELECT * FROM  vehicle_category  WHERE    eStatus='Active'  and `iParentId`=0 order by vCategory_EN";



 
          $vehicle_type= $obj->MySQLSelect($vehicle_type_sql1);

        foreach ($vehicle_type as $subkey => $subvalue) {

    $isSelected="";
        	 foreach ($db_services as $key => $value) {
                                                	if($value['iVehicleCategoryId']==$subvalue['vCategory_EN'])
                                                	$isSelected="selected";	
                                                } 


           echo "<option value='".$subvalue['vCategory_EN']."' $isSelected >".$subvalue['vCategory_EN']."</option>";

}         

       ?>
                                               
                      </select>

                        </div>
                                       	
                                       </div> -->

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Currency <span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <select class="form-control" name = 'vCurrencyDriver' >
                                                       <option value="">--select--</option>
                                                       <? for($i=0;$i<count($db_currency);$i++){ ?>
                                                       <option value = "<?= $db_currency[$i]['vName'] ?>" <?if($vCurrencyDriver==$db_currency[$i]['vName']){?>selected<?} else if($db_currency[$i]['eDefault']=="Yes" && $vCurrencyDriver==''){?>selected<?}?>><?= $db_currency[$i]['vName'] ?></option>
                                                       <? } ?>
                                                  </select>
                                             </div>
                                        </div>                                     
<!--codeEdited for removing bank details-->
                                       <!--  <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Payment Email</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="email"  class="form-control" name="vPaymentEmail"  id="vPaymentEmail" value="<?= $vPaymentEmail ?>" placeholder="Payment Email" >
                                             </div>
                                        </div>


                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Account Holder Name</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text"  class="form-control" name="vBankAccountHolderName"  id="vBankAccountHolderName" value="<?= $vBankAccountHolderName ?>" placeholder="Account Holder Name" >
                                             </div>
                                        </div>


                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Account Number</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text"  class="form-control" name="vAccountNumber"  id="vAccountNumber" value="<?=$vAccountNumber ?>" placeholder="Account Number" >
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Name of Bank</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text"  class="form-control" name="vBankName"  id="vBankName" value="<?= $vBankName ?>" placeholder="Name of Bank" >
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Bank Location</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" class="form-control" name="vBankLocation"  id="vBankLocation" value="<?= $vBankLocation ?>" placeholder="Bank Location" >
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>BIC/SWIFT Code</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text"  class="form-control" name="vBIC_SWIFT_Code"  id="vBIC_SWIFT_Code" value="<?= $vBIC_SWIFT_Code ?>" placeholder="BIC/SWIFT Code" >
                                             </div>
                                        </div> -->
                                        <!--end removing bank details-->

                                        <?php if($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX'){?>
                                        <div style="clear: both;"></div>
                                        <div class="row">
                                          <div class="col-lg-12">
                                            <label>Service Description :</label>
                                          </div>
                                          <div class="col-lg-6"><textarea name="tProfileDescription" rows="3" cols="40" class="form-control" id="tProfileDescription" placeholder="Service Description"><?= $tProfileDescription;?></textarea>
                                          </div>
                                        </div>
                                        <?php } ?>
                                        <div class="row">
											 <div class="col-lg-12">
												<input type="submit" class="btn btn-default" name="submit" id="submit" value="<?= $action; ?> <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?>" >
												<input type="reset" value="Reset" class="btn btn-default">
												<!-- <a href="javascript:void(0);" onClick="reset_form('_driver_form');" class="btn btn-default">Reset</a> -->
												<a href="driver.php" class="btn btn-default back_link">Cancel</a>
											</div>
                                        </div>
                                   </form>
                              </div>
                         </div>
                    </div>
               </div>
               <!--END PAGE CONTENT -->
          </div>
          <!--END MAIN WRAPPER -->
		  
	  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-medium">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
					<h4 class="modal-title " id="myModalLabel">Manage <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Preferences</h4>
				</div>
				<div class="modal-body">
					<span>
								<form name="frm112" action="" method="POST">
									<? foreach($data_preference as $value){?>
                                  
										<div class="preferences-chat">
											<b class="car-preferences-right-part1"><?=$value['vName']?></b>
                                            
										  <b class="car-preferences-right-part-a">
                                          <span data-toggle="tooltip" title="<?=$value['vYes_Title']?>"><a href="#"><img class="borderClass-aa1 borderClass-aa2" src="<?=$tconfig["tsite_upload_preference_image_panel"].$value['vPreferenceImage_Yes']?>" alt="" id="img_Yes_<?=$value['iPreferenceId']?>" onClick="checked_val('<?=$value['iPreferenceId']?>','Yes')"/></a></span></b>
										  <b class="car-preferences-right-part-a"><span data-toggle="tooltip" title="<?=$value['vNo_Title']?>"><a href="#"><img class="borderClass-aa1 borderClass-aa2" src="<?=$tconfig["tsite_upload_preference_image_panel"].$value['vPreferenceImage_No']?>" alt="" id="img_No_<?=$value['iPreferenceId']?>" onClick="checked_val('<?=$value['iPreferenceId']?>','No')"/></a></span></b>
										</div>
                                        
                                        
										<span style="display:none;">
											<input type="radio" name="vChecked_<?=$value['iPreferenceId']?>" id="Yes_<?=$value['iPreferenceId']?>" value="Yes">
											<input type="radio" name="vChecked_<?=$value['iPreferenceId']?>" id="No_<?=$value['iPreferenceId']?>" value="No">
										</span> 
									<?}?>
									<p class="car-preferences-right-part-b">
                                        <input name="btnsubmit" type="submit" value="<?= $langage_lbl['LBL_Save']; ?>" class="save-but1">
                                        
                                    </p>
                                    
								</form>
							</span>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

		<?php include_once('footer.php'); ?>
	
		<script type='text/javascript' src='../assets/js/jquery-ui.min.js'></script>
		<script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
		<script>
		function changeCode(id) {
			var request = $.ajax({
				 type: "POST",
				 url: 'change_code.php',
				 data: 'id=' + id,
				 success: function (data)
				 {
					document.getElementById("code").value = data;
					//window.location = 'profile.php';
				 }
			});
		}
		
		$(document).ready(function() {
			var referrer;
			if($("#previousLink").val() == "" ){
				referrer =  document.referrer;	
			}else { 
				referrer = $("#previousLink").val();
			}
			if(referrer == "") {
				referrer = "driver.php";
			}else {
				$("#backlink").val(referrer);
			}
			$(".back_link").attr('href',referrer);
			var date = new Date();
			var currentMonth = date.getMonth();
			var currentDate = date.getDate();
			var currentYear = date.getFullYear();
/*			$("#dp56").datepicker({
				endDate: new Date(),
				autoclose: true 
			},'update' , '<?=$dBirthDate?>');*/

//codeEDITED FOR DOB AND BARANGAY
            setBarangay('<?php echo $vBarangay;?>','<?php echo $vCity; ?>','<?php echo $vState; ?>');


$("#vCity").change(function(){
   // var vCountry=$("#vCountry").val();
   var vState=$("#vState").val();
    var vCity=$("#vCity").val();

setBarangay('',vCity,vState);
});
      $("#dBirthDate").datetimepicker({format: 'YYYY-MM-DD'});
//end  barnagay and  date of birth

		});
		
   function setBarangay(selected,vCity,vState)
{
     

    
$.ajax({
        type: "POST",
        url: '../getCityBarangay.php',
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

		function setCity(id,selected)
		{
			var fromMod = 'driver';
			var request = $.ajax({
				type: "POST",
				url: 'change_stateCity.php',
				data: {stateId: id, selected: selected,fromMod:fromMod},
				success: function (dataHtml)
				{
					$("#vCity").html(dataHtml);
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
					$("#vState").html(dataHtml);
					if(selected == '')
						setCity('',selected);
				}
			});
		}
			$('#dp5').datepicker({
				maxDate: 0,	
				  onRender: function(date) {
					return date.valueOf() > new Date().valueOf() ? 'disabled' : '';
				}
			});	
		setState('<?php echo $vCountry; ?>','<?php echo $vState; ?>');
		changeCode('<?php echo $vCountry; ?>');
		setCity('<?php echo $vState; ?>','<?php echo $vCity; ?>');
		
		  function checked_val(id,value){
				$("#img_Yes_"+id).removeClass('border_class-aa1');
				$("#img_No_"+id).removeClass('border_class-aa1');
				
				$("#img_"+value+"_"+id).addClass('border_class-aa1');
				
				$("#Yes_"+id).prop("checked", false);
				$("#No_"+id).prop("checked", false);
				
				$("#"+value+"_"+id).prop("checked", true);
				return false;
			}
			
			$(window).on("load",function(){	
			<? if(count($data_driver_pref) > 0){ ?>
				var dataarr = '<?=json_encode($data_driver_pref)?>';
				var arr1 = JSON.parse(dataarr);
				for(var i=0;i<arr1.length;i++){
					checked_val(arr1[i].pref_Id,arr1[i].pref_Type)
				}
			<? } ?>
			}); 
		
		 $("#Service").multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"100%",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Service Category',
    maxHeight:500
  });
		</script>
</body>
<!-- END BODY-->
</html>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $tconfig["tsite_url_main_admin"]?>css/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/bootstrap-datetimepicker.min.js"></script>