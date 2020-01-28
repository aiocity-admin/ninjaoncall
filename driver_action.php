<?php
	include_once('common.php');
	
	require_once(TPATH_CLASS . "/Imagecrop.class.php");
	$thumb = new thumbnail();
	$generalobj->check_member_login();
	$sql = "select * from country where eStatus='Active' ORDER BY  vCountry ASC ";
	$db_country = $obj->MySQLSelect($sql);
	
	$sql="select * from  currency where eStatus='Active' ORDER BY vName ASC ";
    $db_currency=$obj->MySQLSelect($sql);
					
	if($_REQUEST['id'] != '' && $_SESSION['sess_iCompanyId'] != ''){
		
		$sql = "select * from register_driver where iDriverId = '".$_REQUEST['id']."' AND iCompanyId = '".$_SESSION['sess_iCompanyId']."'";
		$db_cmp_id = $obj->MySQLSelect($sql);
		
		if(!count($db_cmp_id) > 0) 
		{
			header("Location:driver.php?success=0&var_msg=".$langage_lbl['LBL_NOT_YOUR_DRIVER']);
		}
	}
	
	$var_msg = isset($_REQUEST["var_msg"]) ? $_REQUEST["var_msg"] : '';
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
	$action = ($id != '') ? 'Edit' : 'Add';
	$iCompanyId = $_SESSION['sess_iUserId'];
	$tbl_name = 'register_driver';
	$script = 'Driver';
	
	$sql = "select * from language_master where eStatus = 'Active' ORDER BY vTitle ASC";
	$db_lang = $obj->MySQLSelect($sql);
	
	$sql = "select * from company where eStatus != 'Deleted'";
	$db_company = $obj->MySQLSelect($sql);
	
	// set all variables with either post (when submit) either blank (when insert)
	$vName = isset($_POST['vName']) ? $_POST['vName'] : '';
	
	$vLastName = isset($_POST['vLastName']) ? $_POST['vLastName'] : '';
	$vEmail = isset($_POST['vEmail']) ? $_POST['vEmail'] : '';
	$vUserName = isset($_POST['vEmail']) ? $_POST['vEmail'] : '';
	$vPassword = isset($_POST['vPassword']) ? $_POST['vPassword'] : '';
	$vPhone = isset($_POST['vPhone']) ? $_POST['vPhone'] : '';
	$vCountry = isset($_POST['vCountry']) ? $_POST['vCountry'] : '';
	$vCaddress = isset($_POST['vCaddress']) ? $_POST['vCaddress'] : '';
	$vState = isset($_POST['vState']) ? $_POST['vState'] : '';
	$vCity = isset($_POST['vCity']) ? $_POST['vCity'] : '';
	$vZip = isset($_POST['vZip']) ? $_POST['vZip'] : '';
	$vCode = isset($_POST['vCode']) ? $_POST['vCode'] : '';
	$eStatus = isset($_POST['eStatus']) ? $_POST['eStatus'] : '';
	$vLang = isset($_POST['vLang']) ? $_POST['vLang'] : '';
	$vImage = isset($_POST['vImage']) ? $_POST['vImage'] : '';
	$vPass = ($vPassword != "") ? $generalobj->encrypt_bycrypt($vPassword) : '';
	$vCurrencyDriver = isset($_REQUEST['vCurrencyDriver']) ? $_REQUEST['vCurrencyDriver'] : '';
	$vPaymentEmail = isset($_REQUEST['vPaymentEmail']) ? $_REQUEST['vPaymentEmail'] : '';
	$vBankAccountHolderName = isset($_REQUEST['vBankAccountHolderName']) ? $_REQUEST['vBankAccountHolderName'] : '';
	$vAccountNumber = isset($_REQUEST['vAccountNumber']) ? $_REQUEST['vAccountNumber'] : '';
	$vBankLocation = isset($_REQUEST['vBankLocation']) ? $_REQUEST['vBankLocation'] : '';
	$vBankName = isset($_REQUEST['vBankName']) ? $_REQUEST['vBankName'] : '';
	$vBIC_SWIFT_Code = isset($_REQUEST['vBIC_SWIFT_Code']) ? $_REQUEST['vBIC_SWIFT_Code'] : '';
	$tProfileDescription = isset($_REQUEST['tProfileDescription']) ? $_REQUEST['tProfileDescription'] : '';
	$vBarangay=isset($_REQUEST['vBarangay']) ? $_REQUEST['vBarangay'] : '';
	$dBirthDate=isset($_REQUEST['dBirthDate']) ? $_REQUEST['dBirthDate'] : '';
    
    $vBarangay= trim($vBarangay)=='' ? '0' :$vBarangay ;

$Suffix = isset($_POST['Suffix']) ? $_POST['Suffix'] : '';
$MiddleName = isset($_POST['MiddleName']) ? $_POST['MiddleName'] : '';
	//$dBirthDate="";
	/*	if($_POST['vYear'] != "" && $_POST['vMonth'] != "" && $_POST['vDay'] != "") {
		$dBirthDate=$_POST['vYear'].'-'.$_POST['vMonth'].'-'.$_POST['vDay'];
	}*/
	
	if (isset($_POST['submit'])) {
		// if(SITE_TYPE=='Demo' && $action=='Edit')
		// {
			// header("Location:driver_action.php?id=" . $id . '&success=2');
			// exit;
		// }
		$iCompanyId = $_SESSION['sess_iUserId'];
		
		
		//Start :: Upload Image Script
		if(!empty($id)){
			
			if(isset($_FILES['vImage'])){
				$id = $_REQUEST['id'];
				$img_path = $tconfig["tsite_upload_images_driver_path"];
				$temp_gallery = $img_path . '/';
				$image_object = $_FILES['vImage']['tmp_name'];
				$image_name = $_FILES['vImage']['name'];
				$check_file_query = "select iDriverId,vImage from register_driver where iDriverId=" . $id;
				$check_file = $obj->sql_query($check_file_query);
				if ($image_name != "") {
					$check_file['vImage'] = $img_path . '/' . $id . '/' . $check_file[0]['vImage'];
					
					if ($check_file['vImage'] != '' && file_exists($check_file['vImage'])) {
						unlink($img_path . '/' . $id. '/' . $check_file[0]['vImage']);
						unlink($img_path . '/' . $id. '/1_' . $check_file[0]['vImage']);
						unlink($img_path . '/' . $id. '/2_' . $check_file[0]['vImage']);
						unlink($img_path . '/' . $id. '/3_' . $check_file[0]['vImage']);
					}
					
					$filecheck = basename($_FILES['vImage']['name']);
					$fileextarr = explode(".", $filecheck);
					$ext = strtolower($fileextarr[count($fileextarr) - 1]);
					$flag_error = 0;
					if ($ext != "jpg" && $ext != "gif" && $ext != "png" && $ext != "jpeg" && $ext != "bmp") {
						$flag_error = 1;
						$var_msg = $langage_lbl['LBL_UPLOAD_IMG_ERROR'];
					}
					/*if ($_FILES['vImage']['size'] > 1048576) {
						$flag_error = 1;
						$var_msg = "Image Size is too Large";
					}*/
					if ($flag_error == 1) {
						$generalobj->getPostForm($_POST, $var_msg, "driver_action?success=0&var_msg=" . $var_msg);
						exit;
					} else {
						
						$Photo_Gallery_folder = $img_path . '/' . $id . '/';
						
						if (!is_dir($Photo_Gallery_folder)) {
							mkdir($Photo_Gallery_folder, 0777);
						}
						$img = $generalobj->general_upload_image($image_object, $image_name, $Photo_Gallery_folder, $tconfig["tsite_upload_images_member_size1"], $tconfig["tsite_upload_images_member_size2"], $tconfig["tsite_upload_images_member_size3"], '', '', '', 'Y', '', $Photo_Gallery_folder);
						$vImage = $img;
					}
				}else{
                    $vImage = $check_file[0]['vImage'];
				}
				//die();
			}
		}
		//End :: Upload Image Script
		$vRefCodePara = '';
		$q = "INSERT INTO ";
		$where = '';
		if ($action == 'Edit') {
			//$str = ", eStatus = 'Inactive' ";
			$str ="";
		} else {
			
			if(SITE_TYPE=='Demo')
			{	
				$str = ", eStatus = 'active' ";
			}
			else
			{
				$sqlc = "select vValue from configurations where vName = 'DEFAULT_CURRENCY_CODE'";
				$db_currency = $obj->MySQLSelect($sqlc);				
				$defaultCurrency = $db_currency[0]['vValue'];
	
				$str = ", vCurrencyDriver = '$defaultCurrency'";
			}
			$eReftype = "Driver";
			$refercode = $generalobj->ganaraterefercode($eReftype);
			$dRefDate  = Date('Y-m-d H:i:s');
			$vRefCodePara = "`vRefCode` = '" . $refercode . "',";
		}
		if ($id != '') {
			$q = "UPDATE ";
			$where = " WHERE `iDriverId` = '" . $id . "'";
			
			$sql="select * from ".$tbl_name .$where;
			$edit_data=$obj->sql_query($sql);
			
			if($vEmail != $edit_data[0]['vEmail'])
			{
				$query = $q ." `".$tbl_name."` SET `eEmailVerified` = 'No' ".$where;
				$obj->sql_query($query);
			}

			if($vPhone != $edit_data[0]['vPhone'])
			{
				$query = $q ." `".$tbl_name."` SET `ePhoneVerified` = 'No' ".$where;
				$obj->sql_query($query);
			}

			if($vCode != $edit_data[0]['vCode'])
			{
				$query = $q ." `".$tbl_name."` SET `ePhoneVerified` = 'No' ".$where;
				$obj->sql_query($query);		
			}		
		}
		
		$passPara = '';
		if($vPass != ""){
			$passPara = "`vPassword` = '" . $vPass . "',";
		}

		if ($action == 'Add') {
            $str1 = "`tRegistrationDate` = '".date("Y-m-d H:i:s")."',";
        } else {
            $str1 = '';
        }

		 $query = $q . " `" . $tbl_name . "` SET
		`vName` = '" . $vName . "',
		`vLastName` = '" . $vLastName . "',
		`vCountry` = '" . $vCountry . "',
		`vCaddress` = '" . $vCaddress . "',
		`vState` = '" . $vState . "',
		`vCity` = '" . $vCity . "',
		`vZip` = '" . $vZip . "',
		`vCode` = '" . $vCode . "',
		`vEmail` = '" . $vEmail . "',
		`vBarangay`='".$vBarangay."',
		`vLoginId` = '" . $vEmail . "',
		`dBirthDate`='".$dBirthDate."',
		$passPara
		`iCompanyId` = '" . $iCompanyId . "',
		`vPhone` = '" . $vPhone . "',
		`vImage` = '" . $vImage . "',
		`vPaymentEmail` = '" . $vPaymentEmail . "',
		`vBankAccountHolderName` = '" . $vBankAccountHolderName . "',
		`vAccountNumber` = '" . $vAccountNumber . "',
		`vBankLocation` = '" . $vBankLocation . "',
		`vBankName` = '" . $vBankName . "',
		`vBIC_SWIFT_Code` = '" . $vBIC_SWIFT_Code . "',
		`Suffix`='".$Suffix."',
			`MiddleName`='".$MiddleName."',
		$vRefCodePara
		`dRefDate` = '" . $dRefDate . "',
		`tProfileDescription` = '" . $tProfileDescription . "',
		 $str1
		`vLang` = '" . $vLang . "' $str" . $where; 
		
		$obj->sql_query($query);
		//echo $query;

		$id = ($id != '') ? $id : $obj->GetInsertId();

/*
 $Service=isset($_REQUEST["Service"]) ? $_REQUEST['Service'] : '';

          			      	$obj->MySQLSelect("delete  from driver_registered_service where iDriverId='$id'");

          if($Service!="")
          {

		      foreach ($Service as $key => $value)
		       {

		      	$query_service="INSERT INTO `driver_registered_service`( `iDriverId`, `iVehicleCategoryId`) VALUES ('$id','$value')";

		      	$obj->MySQLSelect($query_service);
		      
		       }
		   }
*/




		if ($action == "Add") {
           
			if($action == "Add") {
				if($SITE_VERSION == "v5"){
					$set_driver_pref = $generalobj->Insert_Default_Preferences($id);
				}
				
				if($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') {
						$query ="SELECT GROUP_CONCAT(iVehicleTypeId)as countId FROM `vehicle_type`";
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
						
						/*if($ALLOW_SERVICE_PROVIDER_AMOUNT == "Yes"){
							$sql="select iVehicleTypeId,iVehicleCategoryId,eFareType,fFixedFare,fPricePerHour from vehicle_type where 1=1";
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
							}
						}*/
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
		
			if(isset($_FILES['vImage'])) {
                $img_path = $tconfig["tsite_upload_images_driver_path"];
                $temp_gallery = $img_path . '/';
                $image_object = $_FILES['vImage']['tmp_name'];
                $image_name = $_FILES['vImage']['name'];
                $check_file_query = "select iDriverId,vImage from register_driver where iDriverId=" . $id;
                $check_file = $obj->sql_query($check_file_query);
                if ($image_name != "") {
					$check_file['vImage'] = $img_path . '/' . $id . '/' . $check_file[0]['vImage'];
					
					if ($check_file['vImage'] != '' && file_exists($check_file['vImage'])) {
						unlink($img_path . '/' . $id. '/' . $check_file[0]['vImage']);
						unlink($img_path . '/' . $id. '/1_' . $check_file[0]['vImage']);
						unlink($img_path . '/' . $id. '/2_' . $check_file[0]['vImage']);
						unlink($img_path . '/' . $id. '/3_' . $check_file[0]['vImage']);
					}
					
					$filecheck = basename($_FILES['vImage']['name']);
					$fileextarr = explode(".", $filecheck);
					$ext = strtolower($fileextarr[count($fileextarr) - 1]);
					$flag_error = 0;
					if ($ext != "jpg" && $ext != "gif" && $ext != "png" && $ext != "jpeg" && $ext != "bmp") {
						$flag_error = 1;
						$var_msg = $langage_lbl['LBL_UPLOAD_IMG_ERROR'];
					}
					/*if ($_FILES['vImage']['size'] > 1048576) {
						$flag_error = 1;
						$var_msg = "Image Size is too Large";
					}*/
					if ($flag_error == 1) {
						$generalobj->getPostForm($_POST, $var_msg, "driver_action?success=0&var_msg=" . $var_msg);
						exit;
						} else {
						
						$Photo_Gallery_folder = $img_path . '/' . $id . '/';
						if (!is_dir($Photo_Gallery_folder)) {
							mkdir($Photo_Gallery_folder, 0777);
						}
						$img = $generalobj->general_upload_image($image_object, $image_name, $Photo_Gallery_folder, $tconfig["tsite_upload_images_member_size1"], $tconfig["tsite_upload_images_member_size2"], $tconfig["tsite_upload_images_member_size3"], '', '', '', 'Y', '', $Photo_Gallery_folder);
						$vImage = $img;
						
						$sql = "UPDATE ".$tbl_name." SET `vImage` = '" . $vImage . "' WHERE `iDriverId` = '" . $id . "'";
						$obj->sql_query($sql);
					}
				}
			}
		}
		$id = ($id != '') ? $id : $obj->GetInsertId();
		if($action== 'Edit')
		{
			$var_msg = $langage_lbl['LBL_Record_Updated_successfully.'];
		}
		else
		{
			$var_msg = $langage_lbl['LBL_RECORD_INSERT_MSG'];
		}
		
		/*$maildata['NAME'] =$vName;
		$maildata['EMAIL'] =  $vEmail;
		$maildata['PASSWORD'] =  $langage_lbl['LBL_PASSWORD']." : ".$vPassword;
		$maildata['SOCIALNOTES'] = '';
		$generalobj->send_email_user("MEMBER_REGISTRATION_USER",$maildata);
*/




$nortification_query="SELECT `Event`, `ActionBy`, `NotifyCompany`, `NotifyProvider`, `NotifyCustomer`, `NotifyAdministrator`,`AdditionalEmail` FROM `nortification_settings` WHERE `Event`='Driver Signup' and  `ActionBy`='Company'";

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







		if($_REQUEST['id'] == '') {
			$generalobj->send_email_user("DRIVER_REGISTRATION_ADMIN",$maildata);
			$generalobj->send_email_user("DRIVER_REGISTRATION_USER",$maildata);
		}
		header("Location:driver.php?id=" . $id . '&success=1&var_msg='.$var_msg);
		exit;
	}
	// for Edit
	
	if ($action == 'Edit') {
		$sql = "SELECT * FROM " . $tbl_name . " WHERE iDriverId = '" . $id . "'";
		$db_data = $obj->MySQLSelect($sql);		
		$vLabel = $id;
		if (count($db_data) > 0) {
			foreach ($db_data as $key => $value) {
				$vName = $value['vName'];
				$iCompanyId = $value['iCompanyId'];
				$vLastName = $generalobj->clearName(" ".$value['vLastName']);
				$vCountry = $value['vCountry'];
				$vCaddress = $value['vCaddress'];
				$vState = $value['vState'];
				$vCity = $value['vCity'];
				$vZip = $value['vZip'];
				$vCode = $value['vCode'];
				$vEmail = $generalobj->clearEmail($value['vEmail']);
				$vUserName = $value['vLoginId'];
				$vCurrencyDriver = $value['vCurrencyDriver'];
				$vPassword = $value['vPassword'];
				$vPhone = $generalobj->clearMobile($value['vPhone']);
				$vLang = $value['vLang'];
				$vImage = $value['vImage'];
				$vPaymentEmail = $value['vPaymentEmail'];
				$vBankAccountHolderName = $value['vBankAccountHolderName'];
				$vAccountNumber = $value['vAccountNumber'];
				$vBankLocation = $value['vBankLocation'];
				$vBankName = $value['vBankName'];
				$vBIC_SWIFT_Code = $value['vBIC_SWIFT_Code'];
				$tProfileDescription = $value['tProfileDescription'];
				$vBarangay=$value['vBarangay'];
				$dBirthDate=$value['dBirthDate'];

				  $MiddleName=$value['MiddleName'];
               $Suffix=$value['Suffix'];
			}
			
			$sql = "select iCountryId from country where 1=1 and eStatus = 'Active' AND vCountryCode='".$vCountry."'";
			$db_cntr = $obj->MySQLSelect($sql);
		
			$sql = "select iStateId, vState from state where 1=1 and eStatus = 'Active' and iCountryId = '".$db_cntr[0]['iCountryId']."'";
			$db_state = $obj->MySQLSelect($sql);
			
			$sql = "select iCityId, vcity from city where iStateId = '".$vState."' and eStatus = 'Active'";
			$db_city = $obj->MySQLSelect($sql);
/*
$sql = "select iVehicleCategoryId from driver_registered_service  WHERE iDriverId = '" . $id . "'";
			$db_services = $obj->MySQLSelect($sql);*/

		}
		
		if($SITE_VERSION == "v5"){
			$data_driver_pref = $generalobj->Get_User_Preferences($id);
		}
	}

if($action == 'Add'){
	$action_lbl = $langage_lbl['LBL_ACTION_ADD'];
} elseif($action == 'Edit') {
	$action_lbl = $langage_lbl['LBL_ACTION_EDIT'];
	$action_lbl_update = $langage_lbl['LBL_ACTION_UPDATE'];
}
?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title><?=$SITE_NAME?> | <?=$langage_lbl['LBL_VEHICLE_DRIVER_TXT_ADMIN']; ?> <?= $action; ?></title>
		<!-- Default Top Script and css -->
		<?php include_once("top/top_script.php");?>
		   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
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
			<!-- End: Top Menu-->
			<!-- contact page-->
			<div class="page-contant ">
				<div class="page-contant-inner page-trip-detail">

					<h2 class="header-page trip-detail driver-detail1"><?= $action_lbl; ?> <?=$langage_lbl['LBL_VEHICLE_DRIVER_TXT_ADMIN']; ?> <?= $vName; ?>
					<? if($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'UberX'){?>
						<a href="providerlist">
							<img src="assets/img/arrow-white.png" alt=""> <?=$langage_lbl['LBL_BACK_To_Listing']; ?>
						</a>
					<? } else { ?>
					<a href="driverlist">
						<img src="assets/img/arrow-white.png" alt=""> <?=$langage_lbl['LBL_BACK_To_Listing']; ?>
						</a>
					<? } ?>
				</h2>
					<!-- login in page -->
					<div class="driver-action-page">
						<? if ($success == 1) {?>
							<div class="alert alert-success alert-dismissable">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<?php echo $langage_lbl['LBL_Record_Updated_successfully']; ?>
							</div>
							<?}else if($success == 2){ ?>
							<div class="alert alert-danger alert-dismissable">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<?php echo $langage_lbl['LBL_EDIT_DELETE_RECORD']; ?>
							</div>
							<?php 
							}
						?>
						<form id="frm1" method="post" class="companydriver" enctype="multipart/form-data">
							 <!-- onSubmit="return editPro('login')" -->
							<input  type="hidden" class="edit" name="action" value="login">
							<div id="hide-profile-div" class="row col-md-12">
								<?php if($id){?>
									<?php if ($vImage == 'NONE' || $vImage == '') { ?>
										<img src="assets/img/profile-user-img.png" alt="">
										<?}else{?>
										<div class="col-lg-2">
                                        <b class="img-b"><img class="img-ipm1" src = "<?php echo $tconfig["tsite_upload_images_driver"]. '/' .$id. '/3_' .$vImage ?>"/></b></div>
									<?}?>
								<? }?>
								
								<? if($SITE_VERSION == "v5"){ ?>
								<div class="col-lg-5 col-vs">
                                 <fieldset class="col-md-12 field-a">
								<legend class="lable-b"><h4 class="headind-a1"><?=$langage_lbl['LBL_PREFERENCES_TEXT']?>: </h4></legend>
									
										<div class="div-img1"> 
											<? foreach($data_driver_pref as $val){?>
													<img data-toggle="tooltip" class="borderClass-aa1 border_class-bb1" title="<?=$val['pref_Title']?>" src="<?=$tconfig["tsite_upload_preference_image_panel"].$val['pref_Image']?>">
											<? } ?>
										</div>
											
										<span class="col-md-12 span-box"><a href="preferences.php?id=<?=$id?>&d_name=<?=$vName?>" id="show-edit-language-div" class="hide-language">
										<i class="fa fa-pencil" aria-hidden="true"></i>
										<?=$langage_lbl['LBL_MANAGE_PREFERENCES_TXT']?></a></span>

									</fieldset>
									</div>
								<? } ?>
							</div>
							<div class="driver-action-page-right validation-form driver-action-new">
								<div class="row-a1">


									<div class="action-driv">
	                       <div class="col-md-6">
										<span>
											<label>Suffix<span class="red"></span></label>

											<input type="text" class="driver-action-page-input" name="Suffix" maxlength="5"  id="Suffix" value="<?= $generalobj->cleanall(htmlspecialchars($Suffix)); ?>" placeholder="Suffix">
											<div id="Suffix_validate"></div>
										</span> 
									</div>

									<div class="col-md-6">
										<span>
											<label><?=$langage_lbl['LBL_YOUR_FIRST_NAME']; ?><span class="red">*</span></label>

											<input type="text" class="driver-action-page-input" maxlength="25" name="vName"  id="vName" value="<?= $generalobj->cleanall(htmlspecialchars($vName)); ?>" placeholder="<?=$langage_lbl['LBL_YOUR_FIRST_NAME']; ?>">
											<div id="vName_validate"></div>
										</span> 
									</div>
								
									</div>

                       <div class="action-driv">

                       		<div class="col-md-6">
										<span>
											<label>Middle Name<span class="red"></span></label>	

											<input type="text" class="driver-action-page-input" maxlength="25" name="MiddleName"  id="MiddleName" value="<?= $generalobj->cleanall(htmlspecialchars($MiddleName)); ?>" placeholder="Middle Name">
											<div id="MiddleName_validate"></div>
										</span> 
									</div>
									
									<div class="col-md-6">
										<span>
											<label><?=$langage_lbl['LBL_YOUR_LAST_NAME']; ?><span class="red">*</span></label>	

											<input type="text" class="driver-action-page-input" maxlength="25" name="vLastName"  id="vLastName" value="<?= $generalobj->cleanall(htmlspecialchars($vLastName)); ?>" placeholder="<?=$langage_lbl['LBL_YOUR_LAST_NAME']; ?>">
											<div id="vLastName_validate"></div>
										</span> 
									</div>
									</div>

                                    <div class="action-driv">
									<div class="col-md-4">
										<span>
											<label><?=$langage_lbl['LBL_EMAIL_TEXT_SIGNUP']; ?><span class="red">*</span></label>

											<input type="email" class="driver-action-page-input " maxlength="30"  name="vEmail"  id="vEmail" value="<?= $vEmail; ?>" placeholder="<?=$langage_lbl['LBL_EMAIL_TEXT_SIGNUP']; ?>" required <?php  if(!empty($_REQUEST['id'])){?> readonly <?php } ?>>
											<!--onChange="validate_email(this.value)"-->
											<div id="vEmail_validate"></div>
											<div style="float: none;margin-top: 14px;" id="emailCheck"></div>
										</span> 
									</div>
									<div class="col-md-4">
										<span>
											<label><?=$langage_lbl['LBL_PROFILE_RIDER_PASSWORD']; ?><span class="red">*</span></label>
											<input type="password" class="driver-action-page-input" name="vPassword"  id="vPassword" value="" placeholder="<?=$langage_lbl['LBL_COMPANY_DRIVER_PASSWORD']; ?>"  <?php if ($action != 'Edit') { ?> required <?php } ?>>
											<div id="vPassword_validate"></div>
										</span> 
									</div>
									<div class="col-md-4">
										<span>
											<label><?=$langage_lbl['LBL_SELECT_IMAGE']; ?></label>
											<input type="file" class="driver-action-page-input" name="vImage"  id="vImage" placeholder="Name Label" accept="image/*" onChange="validate_fileextension(this.value);">
										</span>
										<div class="fileerror error"></div> 
									</div>
									</div>
                                    <div class="action-driv">
								    <div class="col-md-4"> 
										<span>
											<label><?=$langage_lbl['LBL_COUNTRY_TXT']; ?><span class="red">*</span></label>
											<select class="custom-select-new" name = 'vCountry' onChange="changeCode(this.value);setState(this.value,'<?=$vState?>');" required>
												<option value=""><?= $langage_lbl['LBL_SELECT_TXT'] ?></option>
												<? for($i=0;$i<count($db_country);$i++){ ?>
													<option value = "<?= $db_country[$i]['vCountryCode'] ?>" <?if($DEFAULT_COUNTRY_CODE_WEB == $db_country[$i]['vCountryCode'] && $action == 'Add') { ?> selected <?php } else if($vCountry==$db_country[$i]['vCountryCode']){?>selected<? } ?>><?= $db_country[$i]['vCountry'] ?></option>
												<? } ?>
											</select>
											<div id="vCountry_validate"></div>
										</span>
									</div>
									<div class="col-md-4"> 
										<span>
											<label id="lbl_vstate"><!-- <?=$langage_lbl['LBL_STATE_TXT']; ?> -->Province</label>
											<select class="custom-select-new" name = 'vState' id="vState" onChange="setCity(this.value,'<?=$vCity?>');">
												<option value=""><?= $langage_lbl['LBL_SELECT_TXT'] ?></option>
												<?  for($i=0;$i<count($db_state);$i++){ ?>
													<option value = "<?= $db_state[$i]['iStateId'] ?>" <?if($vState == $db_state[$i]['iStateId']) { ?> selected <?php } ?>><?= $db_state[$i]['vState'] ?></option>
												<? } ?>
											</select>
										</span>
									</div>
									<div class="col-md-4"> 
										<span>
											<label id="lbl_city"><!-- <?=$langage_lbl['LBL_CITY_TXT']; ?> -->City/Municipality</label>
											<select class="custom-select-new" name = 'vCity' id="vCity" >
												<option value=""><?= $langage_lbl['LBL_SELECT_TXT'] ?></option>
												<?  for($i=0;$i<count($db_city);$i++){ ?>
													<option value = "<?= $db_city[$i]['iCityId'] ?>" <?if($vCity == $db_city[$i]['iCityId']) { ?> selected <?php } ?>><?= $db_city[$i]['vcity'] ?></option>
												<? } ?>
											</select>
										</span>
									</div>
                                    </div>
									</div>
									
									<div style="clear:both"> </div>
									<div class="row">
										<div class="action-driv"> 

											<div class="col-md-4" id="row_vBarangay">
												<span>
												<label>Barangay<span class="red">*</span></label>
										
												<select style="color:#666" required  class="driver-action-page-input vBarangay" name = 'vBarangay' id="vBarangay" >

													<option value="">select</option>
												</select>
<div id="vBarangay_validate"></div>
												</span>
											</div>
									

										
											<div class="col-md-8"> 
												<span>
													<label><!--<?=$langage_lbl['LBL_PROFILE_ADDRESS']; ?>-->
House Number, Building Number, and Street Name<span class="red">*</span></label>
													<input type="text" class="driver-action-page-input"  name="vCaddress"  id="vCaddress" value="<?= $generalobj->cleanall(htmlspecialchars($vCaddress)); ?>" placeholder="House Number, Building Number, and Street Name" required>
													<div id="vCaddress_validate"></div>
												</span>
											</div>
										</div>
										<div class="action-driv">
											<div class="col-md-4"> 
												<span>
													<label><?=$langage_lbl['LBL_ZIP_CODE_SIGNUP']; ?><!-- <span class="red">*</span> --></label>
													<input type="text" class="driver-action-page-input" name="vZip"  id="vZip" value="<?= $vZip; ?>" placeholder="<?=$langage_lbl['LBL_ZIP_CODE_SIGNUP']; ?>" >
													<div id="vZip_validate"></div>
												</span>
											</div>
	<div class="col-md-4">   
												<span class="driver-phone-number">
													<label><?=$langage_lbl['LBL_Phone_Number']; ?><span class="red">*</span></label>
													<input type="text" class="input-phNumber1" id="code" name="vCode" value="<?= $vCode ?>" readonly >

													<input name="vPhone" type="text" maxlength="15" value="<?= $vPhone; ?>" class="driver-action-page-input input-phNumber2" placeholder="<?=$langage_lbl['LBL_Phone_Number']; ?>" " title="Please enter proper mobile number." required />
													<div id="vPhone_validate"></div>
												</span>
											</div>

											<div class="col-md-4">
												<span>       
													<label><?=$langage_lbl['LBL_PROFILE_SELECT_LANGUAGE']; ?><span class="red">*</span></label>                         
													<select  class="custom-select-new" name = 'vLang' required>

														<option value="">--<?=$langage_lbl['LBL_SELECT_LANGUAGE_TXT']; ?>--</option>

														<? for ($i = 0; $i < count($db_lang); $i++) { ?>
															<option value = "<?= $db_lang[$i]['vCode'] ?>" <?= ($db_lang[$i]['vCode'] == $vLang) ? 'selected' : ''; ?>><?= $db_lang[$i]['vTitle'] ?></option>
														<? } ?>
													</select>
													<div id="vLang_validate"></div>
												</span>
											</div>

											
										</div>
										<div class="action-driv">
                                         <div class="col-md-4">
												<span>
												<label><?='Date of Birth' ?><span class="red">*</span></label>
													<input required type="text" class="driver-action-page-input" name="dBirthDate" value="<?=$dBirthDate;?>" placeholder="YYYY-MM-DD" id="dBirthDate">

													<div id="dBirthDate_validate"></div>
												</span>

											</div>

										<div class="col-md-4">
												<span>
												<label><?=$langage_lbl['LBL_SELECT_CURRENCY_SIGNUP']; ?></label>
													<select class="custom-select-new" name = 'vCurrencyDriver'>
														<?php for($i=0;$i<count($db_currency);$i++){ ?>
														<option value = "<?= $db_currency[$i]['vName'] ?>" <?if($action == "Add" && $db_currency[$i]['eDefault']=="Yes"){?>selected<?}else if($db_currency[$i]['vName'] == $vCurrencyDriver) { ?> selected <?php } ?>>
														<?= $db_currency[$i]['vName'] ?>
														</option>
														<? } ?>
													</select>
												</span>
											</div>

<!-- 
										<div class="col-md-4">
<span>

                                <label>Services <span class="red">*</span> </label>
                                       <select   class="form-control miltiselect" data-text="Service" name='Service[]' id="Service" multiple="multiple" >
                                       

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

                      </span>
                        </div></div>

										<div class="action-driv"> -->

										<?php if ($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') { ?>

											<div class="col-md-4">
											<span>
												<label><?=$langage_lbl['LBL_PROFILE_DESCRIPTION']; ?></label>                                            
												<textarea name="tProfileDescription" rows="3" cols="40" class="form-control" id="tProfileDescription" placeholder="<?= $langage_lbl['LBL_PROFILE_DESCRIPTION']; ?>"><?= $tProfileDescription ?></textarea>
											</span>
											</div>
									
										<?php } ?></div>
<!-- 									<? if($action == "Add"){?>
									<div class="col-md-6 driver-action1" style="margin-top: 25px;"><span>
										<b id="li_dob">
												<strong>
												<?=$langage_lbl['LBL_Date_of_Birth']; ?></strong>
												<select name="vDay" data="DD" class="custom-select-new" required oninvalid="this.setCustomValidity('Please Select Date')" onChange="setCustomValidity('')">
													<option value=""><?=$langage_lbl['LBL_DATE_SIGNUP']; ?></option>
													<?php for($i=1;$i<=31;$i++) {?>
													<option value="<?=$i?>">
													<?=$i?>
													</option>
													<?php }?>
												</select>
												<select data="MM" name="vMonth" class="custom-select-new" required oninvalid="this.setCustomValidity('Please Select Month')" onChange="setCustomValidity('')">
													<option value=""><?=$langage_lbl['LBL_MONTH_SIGNUP']; ?></option>
													<?php for($i=1;$i<=12;$i++) {?>
													<option value="<?=$i?>">
													<?=$i?>
													</option>
													<?php }?>
												</select>
												<select data="YYYY" name="vYear" class="custom-select-new" required oninvalid="this.setCustomValidity('Please Select Year')" onChange="setCustomValidity('')">
													<option value=""><?=$langage_lbl['LBL_YEAR_SIGNUP']; ?></option>
													 <?php for($i=(date("Y")-1);$i >= ((date("Y")-1)-$BIRTH_YEAR_DIFFERENCE);$i--) {?>
													<option value="<?=$i?>">
													<?=$i?>
													</option>
													<?php }?>
												</select>
											</b>
										</span></div>
									<? } ?> -->
									<div style="clear:both"></div>
									<!--codeEdited Remove bank details -->
									<!--<div class="panel-group bank-detail1-main" style="margin-top:25px">
										<div class="panel panel-default">
										 <a data-toggle="collapse" href="#collapse1">
										  <div class="panel-heading bank-detail1">
											<h4 class="panel-title">Bank Details</h4>
										  </div></a>
										  <div id="collapse1" class="panel-collapse collapse in">
											<div class="panel-body">
												<div class="row">
													<div class="col-md-4">
														<span>
															<label><?=$langage_lbl['LBL_PAYMENT_EMAIL_TXT']; ?></label>
															<input type="email" class="driver-action-page-input" name="vPaymentEmail"  id="vPaymentEmail" value="<?= $vPaymentEmail; ?>" placeholder="<?=$langage_lbl['LBL_PAYMENT_EMAIL_TXT']; ?>" >
														</span>
													</div>
													<div class="col-md-4">
														<span>
															<label><?=$langage_lbl['LBL_ACCOUNT_HOLDER_NAME']; ?></label>
															<input type="text" class="driver-action-page-input" name="vBankAccountHolderName"  id="vBankAccountHolderName" value="<?= $vBankAccountHolderName; ?>" placeholder="<?=$langage_lbl['LBL_ACCOUNT_HOLDER_NAME']; ?>" >
														</span>
													</div>
													<div class="col-md-4">
														<span>
															<label><?=$langage_lbl['LBL_ACCOUNT_NUMBER']; ?></label>
															<input type="text" class="driver-action-page-input" name="vAccountNumber"  id="vAccountNumber" value="<?= $vAccountNumber; ?>" placeholder="<?=$langage_lbl['LBL_ACCOUNT_NUMBER']; ?>" >
														</span>
													</div>
												</div>
												<div class="row">
													<div class="col-md-4">
														<span>
															<label><?=$langage_lbl['LBL_NAME_OF_BANK']; ?></label>
															<input type="text" class="driver-action-page-input" name="vBankName"  id="vBankName" value="<?= $vBankName; ?>" placeholder="<?=$langage_lbl['LBL_NAME_OF_BANK']; ?>" >
														</span>
													</div>
													<div class="col-md-4">
														<span>
															<label><?=$langage_lbl['LBL_BANK_LOCATION']; ?></label>
															<input type="text" class="driver-action-page-input" name="vBankLocation"  id="vBankLocation" value="<?= $vBankLocation; ?>" placeholder="<?=$langage_lbl['LBL_BANK_LOCATION']; ?>" >
														</span>
													</div>
													<div class="col-md-4">
														<span>
															<label><?=$langage_lbl['LBL_BIC_SWIFT_CODE']; ?></label>
															<input type="text" class="driver-action-page-input" name="vBIC_SWIFT_Code"  id="vBIC_SWIFT_Code" value="<?= $vBIC_SWIFT_Code; ?>" placeholder="<?=$langage_lbl['LBL_BIC_SWIFT_CODE']; ?>" >
														</span>
													</div>
												</div>
											</div>
										  </div>
										</div>
									 </div>-->
									  <!--End Remove bank details-->
									<p>

									<input type="submit" class="save-but" name="submit" id="submit" value="<?= $action_lbl_update;?>"> 

									</p>
									<div style="clear:both;"></div>
								</div>  
							</div>                      
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
		$lang = get_langcode($_SESSION['sess_lang']);?>
		<style>
		span.help-block{
		margin:0;
		padding: 0;
		}
		</style>
		<script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/validation/jquery.validate.min.js" ></script>
		<?php if($lang != 'en') { ?>
			<script type="text/javascript" src="assets/js/validation/localization/messages_<?= $lang; ?>.js" ></script>
		<?php } ?>
		<script type="text/javascript" src="assets/js/validation/additional-methods.js" ></script>
		<script>
			function changeCode(id)
			{
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
/*			function validate_email(id)
			{
				var request = $.ajax({
					type: "POST",
					url: 'ajax_validate_email.php',
					data: 'id=' +id+'&usr=company',
					success: function (data)
					{
						if(data==0)
						{
							$('#emailCheck').html('<i class="icon icon-remove alert-danger alert"> <?=$langage_lbl['LBL_EMAIL_EXISTS_MSG']; ?></i>');
							$('input[type="submit"]').attr('disabled','disabled');
						} else if(data==1) {
							var eml= /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
							result=eml.test(id);
							if(result==true)
							{
								$('#emailCheck').html('<i class="icon icon-ok alert-success alert"><?= $langage_lbl['LBL_VALID'] ?></i>');
								$('input[type="submit"]').removeAttr('disabled');
							}
						} else if(data=='deleted') {
							$('#emailCheck').html('<i class="icon icon-remove alert-danger alert"> <?=$langage_lbl['LBL_CHECK_DELETE_ACCOUNT']; ?></i>');
							$('input[type="submit"]').attr('disabled','disabled');
						}
					}
				});
			}*/
		    function validate_fileextension(filename) {
		        var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
		        if ($.inArray(filename.split('.').pop().toLowerCase(), fileExtension) == -1) {
		            $( ".fileerror" ).html( "Only formats are allowed : "+fileExtension.join(', ') );
		            $('.save-but').prop("disabled", true);
		            return false;
		        } else {
		            $('.save-but').prop("disabled", false);
		            $( ".fileerror" ).html("");
		        }
		    } 
			$(document).ready(function(){
				$('[data-toggle="tooltip"]').tooltip();


//codeEdited for barnagay and  date of birth
            setBarangay('<?php echo $vBarangay;?>','<?php echo $vCity; ?>','<?php echo $vState; ?>');


$("#vCity").change(function(){
   // var vCountry=$("#vCountry").val();
   var vState=$("#vState").val();
    var vCity=$("#vCity").val();

setBarangay('',vCity,vState);
});
      $("#dBirthDate").datetimepicker({ maxDate: new Date(),format: 'YYYY-MM-DD'});
//end  barnagay and  date of birth
			});
			
		function setState(id,selected)
		{
			// changeCode(id);
			// if(id != selected){
				$("#vState + em").html('<?= addslashes($langage_lbl['LBL_SELECT_TXT']) ?>');
				$("#vCity + em").html('<?= addslashes($langage_lbl['LBL_SELECT_TXT']) ?>');
			// }
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
					$("#vCity").html('<option value=""><?= addslashes($langage_lbl['LBL_SELECT_TXT']) ?></option>');
					$("#vState").html(dataHtml);
					if(selected == '')
						setCity('',selected);
				}
			});
		}
		
		/* $("select[name='vCountry']").change(function(){
			$("#vState + em").html('<?=$langage_lbl['LBL_SELECT_TXT']?>');
			// $("#vState").html('');
			$("#vCity + em").html('<?=$langage_lbl['LBL_SELECT_TXT']?>');
		})
		 */
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
					$("#vCity").html(dataHtml);
				}
			});
		}
		 /*  <? if($action == "Edit"){ ?>
			var country = '<?=$vCountry?>';
			var state = '<?=$vState?>';
			var city = '<?=$vCity?>';
			
			// setState(country,state);
			// setCity(state,city);
		<? } ?>   */

		var errormessage;
		$('.companydriver').validate({
			ignore: 'input[type=hidden]',
			errorClass: 'help-block error',
			errorElement: 'span',
			errorPlacement: function (error, element) {
				
	            var name = $(element).attr("name");
	            error.appendTo($("#" + name + "_validate"));
       },
			rules: {
												Service: {required: true},

								vName: {required: true, minlength: 2,maxlength:30},
				vLastName: {required: true, minlength: 2,maxlength:30},
				vEmail:{required: true, email: true,
					remote: {
						url: 'ajax_validate_email.php',
						type: "post",
						cache: false,
					    data: {
					    	id:function(e){
                                return $('#vEmail').val();
                            },
                            uid:'<?php echo $id;?>',
	                        usr:'driver'
	                    },
	                    dataFilter: function(response) {
	                        //response = $.parseJSON(response);
	                        if (response == 'deleted')  {
	                            errormessage = "<?= addslashes($langage_lbl['LBL_CHECK_DELETE_ACCOUNT']); ?>";
	                            return false;
	                        } else if(response == 0){
	                            errormessage = "<?= addslashes($langage_lbl['LBL_EMAIL_EXISTS_MSG']); ?>";
	                            return false;
	                        } else {
	                            return true;
	                        }
	                    },
	                    async: false
					}
				},

				vPassword: {noSpace: true, minlength: 6, maxlength: 16},
				vPhone: {
					required: true,minlength: 3,digits: true,
					remote: {
							url: 'ajax_driver_mobile_new.php',
							type: "post",
							data: {iDriverId:'<?php echo $id;?>',usertype:'driver'},
							async: false
							},
				}
			},
			messages: {
				vName: {
	                required: 'First Name is required.',
	                minlength: 'First Name at least 2 characters long.',
	                maxlength: 'Please enter less than 30 characters.'
	            },
	            vLastName: {
	                required: 'Last Name is required.',
	                minlength: 'Last Name at least 2 characters long.',
	                maxlength: 'Please enter less than 30 characters.'
	            },
				vPassword:{maxlength: 'Please enter less than 16 characters.'},
				vEmail: {remote: function(){ return errormessage; }},
				vPhone: {minlength: 'Please enter at least three Number.',digits: 'Please enter proper mobile number.',remote: '<?= addslashes($langage_lbl['LBL_PHONE_EXIST_MSG']); ?>'}
			},
			submitHandler: function(form) {  
				
/*
				  if(($("#Service").val()=="null" || $("#Service").val()==null))
    {
$("#serviceerror").remove();
$(".multiselect-container").after("<span id='serviceerror' class='error'>Please select Services.</span>");

}*/
                   if ($(form).valid()) 
                       form.submit(); 
                   return false; // prevent normal form posting
            }
			
		});


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

 $("#Service").multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"300px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Service Category',
    maxHeight:500
  });

		</script>
		<!-- End: Footer Script -->
	</body>
</html>
 <style type="text/css">
	

span.multiselect-native-select {
    margin-top: 0px;
}
i.glyphicon.glyphicon-search {
    display: none;
}

button.btn.btn-default.multiselect-clear-filter {
   
    height: 24px;
}

i.glyphicon.glyphicon-remove-circle {
    margin-right: 48%;
}
span.multiselect-selected-text {
    margin: 5px;
}
b.caret {
    display: none;
}
span.input-group-btn {
    margin-top: -29px;
        margin-left: 260px;

}
input.form-control.multiselect-search {
    width: 90%;
}
</style>

  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $tconfig["tsite_url_main_admin"]?>css/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/bootstrap-datetimepicker.min.js"></script>