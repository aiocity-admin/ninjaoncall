<?php include_once("common.php");
$POST_CAPTCHA = $_POST['POST_CAPTCHA'];
$SESS_CAPTCHA = $_SESSION['SESS_CAPTCHA'];

// echo "<pre>";print_r($_POST);exit;
if($POST_CAPTCHA == $SESS_CAPTCHA)
{
	if($_POST)
	{	

		$user_type=$_POST['user_type'];
		if($user_type=='driver')
		{
			$table_name="register_driver";
			$msg= $generalobj->checkDuplicateFront('vEmail', 'register_driver' , Array('vEmail'),$tconfig["tsite_url"]."sign-up.php?error=1&var_msg=Email already Exists", "Email already Exists","" ,"");
		}
		else
		{
			$table_name="company";
			$msg= $generalobj->checkDuplicateFront('vEmail', 'company' , Array('vEmail'),$tconfig["tsite_url"]."sign-up.php?error=1&var_msg=Email already Exists", "Email already Exists","" ,"");
		}


		if($user_type=='driver')
		{
			$eReftype = "Driver";
			$Data['vRefCode'] = $generalobj->ganaraterefercode($eReftype);
			$Data['iRefUserId'] = $_POST['iRefUserId'];
			$Data['eRefType'] = $_POST['eRefType']; 
			$Data['dRefDate']=Date('Y-m-d H:i:s');
			$Data['dBirthDate']=$_POST['dBirthDate'];
			$Data['Suffix'] = $_POST['Suffix'];
		    $Data['MiddleName'] = $_POST['MiddleName'];


		}
		$Data['vName'] = $_POST['vFirstName'];
		$Data['vLastName'] = $_POST['vLastName'];


		$Data['vLang'] = $_SESSION['sess_lang'];
		$Data['vPassword'] = $generalobj->encrypt_bycrypt($_REQUEST['vPassword']);
		$Data['vEmail'] = $_POST['vEmail'];
		//$Data['dBirthDate'] = $_POST['vYear'].'-'.$_POST['vMonth'].'-'.$_POST['vDay'];
		$Data['vPhone'] = $_POST['vPhone'];
		$Data['vCaddress'] = $_POST['vCaddress'];
		$Data['vCadress2'] = $_POST['vCadress2'];
		$Data['vCity'] = $_POST['vCity'];
		$Data['vCountry'] = $_POST['vCountry'];
		$Data['vState'] = $_POST['vState'];
		$Data['vZip'] = $_POST['vZip'];
		$Data['vCode'] = $_POST['vCode'];
		$Data['vBackCheck'] = $_POST['vBackCheck'];
		$Data['vInviteCode'] = $_POST['vInviteCode'];
		$Data['vFathersName'] = $_POST['vFather'];
		$Data['vCompany'] = $_POST['vCompany'];
		$Data['tRegistrationDate']=Date('Y-m-d H:i:s');
	
				$Data['vBarangay'] = $_POST['vBarangay'];


		if(SITE_TYPE=='Demo')
		{
			$Data['eStatus'] = 'Active';
		}
		if($user_type=='driver')
		{
			$table='register_driver';
			$Data['vCurrencyDriver'] = $_POST['vCurrencyDriver'];
			$Data['eGender'] = $_POST['eGender'];
			$user_type='driver';
			$Data['iCompanyId'] = 1;
		}
		else
		{
			$table='company';
			$user_type='company';
			
				$Data['vVat'] = $_POST['vVat'];

		//$Data['OUTPUT_VAT'] = $_POST['vOutVat'];
		}

		$id = $obj->MySQLQueryPerform($table,$Data,'insert');
		


		if($user_type=='driver') {

  $Service= $_REQUEST['Service'];

		      foreach ($Service as $key => $value) {

		      	$query_service="INSERT INTO `driver_registered_service`( `iDriverId`, `iVehicleCategoryId`) VALUES ('$id','$value')";

		      	$obj->MySQLSelect($query_service);
		      
		      }
		}

		if($SITE_VERSION == "v5" && $user_type=='driver'){
			$set_driver_pref = $generalobj->Insert_Default_Preferences($id);
		}

		// user_wallet table insert data
//		$eFor = "Referrer";
//		$tDescription = "Referal amount credit ".$REFERRAL_AMOUNT." into your account";
//		$dDate = Date('Y-m-d H:i:s');
//		$ePaymentStatus = "Unsettelled";
//		$REFERRAL_AMOUNT; 
//
//		if($user_type=='driver'){
//
//			if($_POST['vRefCode'] != "" && !empty($_POST['vRefCode'])){
//				$generalobj->InsertIntoUserWallet($_POST['iRefUserId'],$_POST['eRefType'],$REFERRAL_AMOUNT,'Credit',0,$eFor,$tDescription,$ePaymentStatus,$dDate);
//			}	
//		}	

		if($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX'){
			if($user_type=='driver') {
				$query ="SELECT GROUP_CONCAT(iVehicleTypeId) as countId FROM `vehicle_type` WHERE `eType` = 'UberX'";
				$result = $obj->MySQLSelect($query);
				
				$Drive_vehicle['iDriverId'] = $id;
				$Drive_vehicle['iCompanyId'] = "1";
				$Drive_vehicle['iMakeId'] = "3";
				$Drive_vehicle['iModelId'] = "1";
				$Drive_vehicle['iYear'] = Date('Y');
				$Drive_vehicle['vLicencePlate'] = "My Services";
				$Drive_vehicle['eStatus'] = "Active";
				$Drive_vehicle['eType'] = "UberX";
				$Drive_vehicle['eCarX'] = "Yes";
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
			}
		}
		else
		{
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

		if($id != "")
		{
			$_SESSION['sess_iUserId'] = $id;
			if($user_type=='driver')
			{
				$_SESSION['sess_iCompanyId'] = 1;
				$_SESSION["sess_vName"] = $Data['vName'].' '.$Data['vLastName'];
				$_SESSION["sess_vCurrency"]= $Data['vCurrencyDriver'];
			}
			else
			{
				$_SESSION['sess_iCompanyId'] = $id;
				$_SESSION["sess_vName"] = $Data['vCompany'];
			}
			
			$_SESSION["sess_company"] = $Data['vCompany'];
			$_SESSION["sess_vEmail"] = $Data['vEmail'];
			$_SESSION["sess_user"] =$user_type;
			$_SESSION["sess_new"]=1;

			//$maildata['EMAIL'] = $_SESSION["sess_vEmail"];
			//$maildata['NAME'] = $_SESSION["sess_vName"];
			//$maildata['PASSWORD'] = $langage_lbl['LBL_PASSWORD'].": ". $_REQUEST['vPassword'];
			//$maildata['SOCIALNOTES'] ='';
			//$generalobj->send_email_user("MEMBER_REGISTRATION_USER",$maildata);


			$vEmail= $_SESSION["sess_vEmail"];
            $vName= $_SESSION["sess_vName"];
            $vPassword =$_REQUEST['vPassword'];
			if($user_type=='driver')
			{
				 // $generalobj->send_email_user("DRIVER_REGISTRATION_ADMIN",$maildata);
				  	
				 // $generalobj->send_email_user("DRIVER_REGISTRATION_USER",$maildata);
			$nortification_query="SELECT `Event`, `ActionBy`, `NotifyCompany`, `NotifyProvider`, `NotifyCustomer`, `NotifyAdministrator`, `AdditionalEmail` FROM `nortification_settings` WHERE `Event`='Driver Signup' and  `ActionBy`='Driver'";

$result_nortification = $obj->MySQLSelect($nortification_query);
if(count($result_nortification)>0)
{

if($result_nortification[0]['NotifyCompany']=="on")
{
	   $company_email_query="SELECT  `vEmail` FROM `company` WHERE iCompanyId='$iCompanyId'";
		$result_email = $obj->MySQLSelect($company_email_query);
		        $maildata['COMPANY'] =$result_email[0]["vEmail"];
                $maildata['EMAIL'] = $vEmail;
               	$maildata['NAME'] = $vName;
					$maildata['PASSWORD'] =  $langage_lbl_admin["LBL_PASSWORD"].": ".$vPassword;
				$maildata['SOCIALNOTES'] = '';

$generalobj->send_email_user("DRIVER_REGISTRATION_COMPANY",$maildata); 

}


if($result_nortification[0]['AdditionalEmail']!="")
{
	  
                $maildata['ADDITIONALEMAIL'] = $result_nortification[0]['AdditionalEmail'];
                  $maildata['EMAIL'] = $vEmail;
					$maildata['NAME'] = $vName;
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
					$maildata['NAME'] = $vName;
				$maildata['PASSWORD'] =  $langage_lbl_admin["LBL_PASSWORD"].": ".$vPassword;
				$maildata['SOCIALNOTES'] = '';
                $generalobj->send_email_user("DRIVER_REGISTRATION_USER",$maildata);
				}
			if($result_nortification[0]['NotifyAdministrator']=="on")
                {
                $maildata['EMAIL'] = $vEmail;
				$maildata['NAME'] = $vName;
				$maildata['PASSWORD'] =  $langage_lbl_admin["LBL_PASSWORD"].": ".$vPassword;
				$maildata['SOCIALNOTES'] = '';
				$generalobj->send_email_user("DRIVER_REGISTRATION_ADMIN",$maildata); 
				}
			
}




			}else{

				//$generalobj->send_email_user("COMPANY_REGISTRATION_ADMIN",$maildata);
				//$generalobj->send_email_user("COMPANY_REGISTRATION_USER",$maildata);
			$nortification_query="SELECT `Event`, `ActionBy`, `NotifyCompany`, `NotifyProvider`, `NotifyCustomer`, `NotifyAdministrator`, `AdditionalEmail` FROM `nortification_settings` WHERE `Event`='Company Signup' and  `ActionBy`='Company'";

$result_nortification = $obj->MySQLSelect($nortification_query);
if(count($result_nortification)>0)
{

if($result_nortification[0]['NotifyCompany']=="on")
{
	     $maildata['EMAIL'] = $vEmail;
				$maildata['NAME'] = $vName;
				$maildata['PASSWORD'] =  $langage_lbl_admin["LBL_PASSWORD"].": ".$vPassword;
				$maildata['SOCIALNOTES'] = '';

$generalobj->send_email_user("COMPANY_REGISTRATION_USER",$maildata);
}


if($result_nortification[0]['AdditionalEmail']!="")
{
	  
                $maildata['ADDITIONALEMAIL'] = $result_nortification[0]['AdditionalEmail'];
                  $maildata['EMAIL'] = $vEmail;
					$maildata['NAME'] = $vName;
			$maildata['PASSWORD'] =  $langage_lbl_admin["LBL_PASSWORD"].": ".$vPassword;
				$maildata['SOCIALNOTES'] = '';

        $generalobj->send_email_user("COMPANY_REGISTRATION_ADDITIONALEMAIL",$maildata); 

}



			if($result_nortification[0]['NotifyAdministrator']=="on")
                {
                $maildata['EMAIL'] = $vEmail;
				$maildata['NAME'] = $vName;
				$maildata['PASSWORD'] =  $langage_lbl_admin["LBL_PASSWORD"].": ".$vPassword;
				$maildata['SOCIALNOTES'] = '';
                $generalobj->send_email_user("COMPANY_REGISTRATION_ADMIN",$maildata);				}
			
}




			}
			#header("Location:profile.php?first=yes");
			
			if($APP_TYPE == 'UberX' && $user_type=='driver'){
				header("Location:add_services.php");
				exit;		
			}else{
				header("Location:profile.php?first=yes");
				exit;
			}
		}
	}
}
else
{
	$_SESSION['postDetail'] = $_REQUEST;
	header("Location:".$tconfig["tsite_url"]."sign-up.php?error=1&var_msg=Captcha did not match.");
	exit;
}
?>
