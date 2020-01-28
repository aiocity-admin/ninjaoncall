<?php
include_once('../common.php');

require_once(TPATH_CLASS . "/Imagecrop.class.php");
$thumb = new thumbnail();

if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");
     $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();



$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$message_print_id=$id;
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
$action = ($id != '') ? 'Edit' : 'Add';

$tbl_name = 'vehicle_type';
$script = 'VehicleType';

$vVehicleType = isset($_POST['vVehicleType']) ? $_POST['vVehicleType'] : '';

$iVehicleCategoryId = isset($_POST['iVehicleCategoryId']) ? $_POST['iVehicleCategoryId'] : '';
$fPricePerKM = isset($_POST['fPricePerKM']) ? $_POST['fPricePerKM'] : '';
$fPricePerMin = isset($_POST['fPricePerMin']) ? $_POST['fPricePerMin'] : '';
$iBaseFare = isset($_POST['iBaseFare']) ? $_POST['iBaseFare'] : '';
$iMinFare = isset($_POST['iMinFare']) ? $_POST['iMinFare'] : '';
$fCommision = isset($_POST['fCommision']) ? $_POST['fCommision'] : '';
$iPersonSize = isset($_POST['iPersonSize']) ? $_POST['iPersonSize'] : '';
//$fPickUpPrice = isset($_POST['fPickUpPrice']) ? $_POST['fPickUpPrice'] : '';
$fNightPrice = isset($_POST['fNightPrice']) ? $_POST['fNightPrice'] : '';
//$tPickStartTime = isset($_POST['tPickStartTime']) ? $_POST['tPickStartTime'] : '';

$tMonPickStartTime = isset($_POST['tMonPickStartTime']) ? $_POST['tMonPickStartTime'] : '';
$tMonPickEndTime = isset($_POST['tMonPickEndTime']) ? $_POST['tMonPickEndTime'] : '';
$fMonPickUpPrice = isset($_POST['fMonPickUpPrice']) ? $_POST['fMonPickUpPrice'] : '';

$tTuePickStartTime = isset($_POST['tTuePickStartTime']) ? $_POST['tTuePickStartTime'] : '';
$tTuePickEndTime = isset($_POST['tTuePickEndTime']) ? $_POST['tTuePickEndTime'] : '';
$fTuePickUpPrice = isset($_POST['fTuePickUpPrice']) ? $_POST['fTuePickUpPrice'] : '';

$tWedPickStartTime = isset($_POST['tWedPickStartTime']) ? $_POST['tWedPickStartTime'] : '';
$tWedPickEndTime = isset($_POST['tWedPickEndTime']) ? $_POST['tWedPickEndTime'] : '';
$fWedPickUpPrice = isset($_POST['fWedPickUpPrice']) ? $_POST['fWedPickUpPrice'] : '';

$tThuPickStartTime = isset($_POST['tThuPickStartTime']) ? $_POST['tThuPickStartTime'] : '';
$tThuPickEndTime = isset($_POST['tThuPickEndTime']) ? $_POST['tThuPickEndTime'] : '';
$fThuPickUpPrice = isset($_POST['fThuPickUpPrice']) ? $_POST['fThuPickUpPrice'] : '';

$tFriPickStartTime = isset($_POST['tFriPickStartTime']) ? $_POST['tFriPickStartTime'] : '';
$tFriPickEndTime = isset($_POST['tFriPickEndTime']) ? $_POST['tFriPickEndTime'] : '';
$fFriPickUpPrice = isset($_POST['fFriPickUpPrice']) ? $_POST['fFriPickUpPrice'] : '';

$tSatPickStartTime = isset($_POST['tSatPickStartTime']) ? $_POST['tSatPickStartTime'] : '';
$tSatPickEndTime = isset($_POST['tSatPickEndTime']) ? $_POST['tSatPickEndTime'] : '';
$fSatPickUpPrice = isset($_POST['fSatPickUpPrice']) ? $_POST['fSatPickUpPrice'] : '';

$tSunPickStartTime = isset($_POST['tSunPickStartTime']) ? $_POST['tSunPickStartTime'] : '';
$tSunPickEndTime = isset($_POST['tSunPickEndTime']) ? $_POST['tSunPickEndTime'] : '';
$fSunPickUpPrice = isset($_POST['fSunPickUpPrice']) ? $_POST['fSunPickUpPrice'] : '';


//$tPickEndTime = isset($_POST['tPickEndTime']) ? $_POST['tPickEndTime'] : '';
$tNightStartTime = isset($_POST['tNightStartTime']) ? $_POST['tNightStartTime'] : '';
$tNightEndTime = isset($_POST['tNightEndTime']) ? $_POST['tNightEndTime'] : '';
$eStatus_picktime 	= isset($_POST['ePickStatus'])?$_POST['ePickStatus']:'off';
$ePickStatus 		= ($eStatus_picktime == 'on')?'Active':'Inactive';
$eStatus_nighttime 	= isset($_POST['eNightStatus'])?$_POST['eNightStatus']:'off';
$eNightStatus 		= ($eStatus_nighttime == 'on')?'Active':'Inactive';
$eType = isset($_POST['eType']) ? $_POST['eType'] : '';

$eFareType 		= isset($_POST['eFareType']) ? $_POST['eFareType'] : '';
$fFixedFare 		= isset($_POST['fFixedFare']) ? $_POST['fFixedFare'] : '';
$eAllowQty 		= isset($_POST['eAllowQty']) ? $_POST['eAllowQty'] : '';
$iMaxQty 		= isset($_POST['iMaxQty']) ? $_POST['iMaxQty'] : '';
$fPricePerHour 		= isset($_POST['fPricePerHour']) ? $_POST['fPricePerHour'] : '';

		

$vTitle_store =array();
$sql = "SELECT * FROM `language_master` where eStatus='Active' ORDER BY `iDispOrder`";
$db_master = $obj->MySQLSelect($sql);
$count_all = count($db_master);
if($count_all > 0) {
  for($i=0;$i<$count_all;$i++) {
    $vValue = 'vVehicleType_'.$db_master[$i]['vCode'];
    array_push($vTitle_store ,$vValue);   
    $$vValue  = isset($_POST[$vValue])?$_POST[$vValue]:'';    
   
  }
}
 //print_r($vTitle_store);exit; 
	if(isset($_POST['btnsubmit']))
	{
		if($eFareType == "Fixed")
		{
			$ePickStatus = "Inactive";
			$eNightStatus = "Inactive";
		}
		else
		{
			$ePickStatus = $ePickStatus;
			$eNightStatus = $eNightStatus;
		}
	
	if(isset($_FILES['vLogo']) && $_FILES['vLogo']['name'] != ""){
		 $filecheck = basename($_FILES['vLogo']['name']);
		 $fileextarr = explode(".", $filecheck);
		 $ext = strtolower($fileextarr[count($fileextarr) - 1]);
		 $flag_error = 0;
		 if($ext != "png") {
			  $flag_error = 1;
			  $var_msg = "Upload only png image";
		 }
		 $data = getimagesize($_FILES['vLogo']['tmp_name']);
		 $width = $data[0];
		 $height = $data[1];
		 
		 if($width != 360 && $height != 360) {
		 
			  $flag_error = 1;
			  $var_msg = "Please Upload image only 360px * 360px";
		 }
		 if ($flag_error == 1) {
			
			
			if($action == "Add"){
			header("Location:vehicle_type_action.php?var_msg=".$var_msg."&success=3");
			exit;	
			}else{
			header("Location:vehicle_type_action.php?id=".$id."&var_msg=".$var_msg."&success=3");			
			exit;
			}	
			
			 // $generalobj->getPostForm($_POST, $var_msg, "vehicle_type_action.php?success=0&var_msg=".$var_msg);
			 // exit;
		 }
	}
	
	if(isset($_FILES['vLogo1']) && $_FILES['vLogo1']['name'] != ""){
		 $filecheck = basename($_FILES['vLogo1']['name']);
		 $fileextarr = explode(".", $filecheck);
		 $ext = strtolower($fileextarr[count($fileextarr) - 1]);
		 $flag_error = 0;
		 if($ext != "png") {
			  $flag_error = 1;
			  $var_msg = "Upload only png image";
		 }
		 $data = getimagesize($_FILES['vLogo1']['tmp_name']);
		 $width = $data[0];
		 $height = $data[1];
		 
		 if($width != 360 && $height != 360) {
		 
			  $flag_error = 1;
			  $var_msg = "Please Upload image only 360px * 360px";
		 }
		 if ($flag_error == 1) {
		 
			if($action == "Add"){
			header("Location:vehicle_type_action.php?var_msg=".$var_msg."&success=3");
			//$generalobj->getPostForm($_POST,$var_msg,"banner_action.php");
			exit;	
			}else{
			header("Location:vehicle_type_action.php?id=".$id."&var_msg=".$var_msg."&success=3");
			//$generalobj->getPostForm($_POST,$var_msg,"banner_action.php?id=".$id."&var_msg=".$var_msg);
			exit;
			}	
			  //$generalobj->getPostForm($_POST, $var_msg, "vehicle_type_action.php?success=0&var_msg=".$var_msg);
			  exit;
		 }
	}	
	
		if($ePickStatus == "Active"){  

		/*  if($tPickStartTime > $tPickEndTime){
			header("Location:vehicle_type_action.php?id=".$id."&success=3");exit;
		  }*/

		       if($tMonPickStartTime > $tMonPickEndTime){

		       	$varmsg = "Please Select  Monday Peak Start Time less than Monday Peak End Time.";
		        header("Location:vehicle_type_action.php?id=".$id."&success=3&varmsg=".$var_msg);exit;
		      }

		      if($tTuePickStartTime > $tTuePickEndTime){
		      	$varmsg = "Please Select  Tuesday Peak Start Time less than Tuesday Peak End Time.";
		       header("Location:vehicle_type_action.php?id=".$id."&success=3&varmsg=".$varmsg);exit;
		      }

		      if($tWedPickStartTime > $tWedPickEndTime){
		      	$varmsg = "Please Select  Wednesday Peak Start Time less than Wednesday Peak End Time.";
		        header("Location:vehicle_type_action.php?id=".$id."&success=3&varmsg=".$varmsg);exit;
		      }

		      if($tThuPickStartTime > $tThuPickEndTime){
		      	$varmsg = "Please Select  Thursday Peak Start Time less than Thursday Peak End Time.";
		        header("Location:vehicle_type_action.php?id=".$id."&success=3&varmsg=".$varmsg);exit;
		      }

		      if($tFriPickStartTime > $tFriPickEndTime){
		      		$varmsg = "Please Select  Friday Peak Start Time less than Friday Peak End Time.";
		       header("Location:vehicle_type_action.php?id=".$id."&success=3&varmsg=".$varmsg);exit;
		      }

		      if($tSatPickStartTime > $tSatPickEndTime){
		      		$varmsg = "Please Select  Saturday Peak Start Time less than Saturday Peak End Time.";
		        header("Location:vehicle_type_action.php?id=".$id."&success=3&varmsg=".$varmsg);exit;
		      }

		      if($tSunPickStartTime > $tSunPickEndTime){
		      	$varmsg = "Please Select  Sunday Peak Start Time less than Sunday Peak End Time.";
		        header("Location:vehicle_type_action.php?id=".$id."&success=3&varmsg=".$varmsg);exit;
		      }

		}  
		if($eNightStatus == "Active"){  
		  if($tNightStartTime > $tNightEndTime){
			header("Location:vehicle_type_action.php?id=".$id."&success=4");exit;
		  }
		}
		if(SITE_TYPE =='Demo'){
		   header("Location:vehicle_type_action.php?id=".$id."&success=2");exit;
		}

		for($i=0;$i<count($vTitle_store);$i++)
     	 {

     	 	 	 $vValue = 'vVehicleType_'.$db_master[$i]['vCode'];
     	 	 	// echo $_POST[$vTitle_store[$i]] ; exit;
				 $q = "INSERT INTO ";
		                   $where = ''; 
		        if ($id != '') {   
		        	  
						$q = "UPDATE ";
		       		  $where = " WHERE `iVehicleTypeid` = '" . $id . "'";
			    }


				 $query = $q . " `" . $tbl_name . "` SET
				`vVehicleType` = '".$vVehicleType. "',
				`iVehicleCategoryId` = '" .$iVehicleCategoryId. "',
				`eFareType` = '" . $eFareType . "',
				`fFixedFare` = '" . $fFixedFare . "',
				`fPricePerKM` = '" . $fPricePerKM . "',
				`fPricePerMin` = '" . $fPricePerMin . "',
				`iBaseFare` = '" . $iBaseFare . "',
				`iMinFare` = '" . $iMinFare . "',
				`fCommision` = '" . $fCommision . "',
				`iPersonSize` = '" . $iPersonSize. "',				
				`fNightPrice` = '" . $fNightPrice. "',				
				`tNightStartTime` = '" . $tNightStartTime. "',
				`tNightEndTime` = '" . $tNightEndTime. "',
				`ePickStatus` = '" . $ePickStatus. "',
				`eAllowQty` = '" . $eAllowQty. "',
				`fPricePerHour` = '" . $fPricePerHour. "',
				`iMaxQty` = '" . $iMaxQty. "',
				`eType` = '" . $eType. "',
				`eNightStatus` = '" . $eNightStatus. "',
				`tMonPickStartTime` = '" . $tMonPickStartTime. "',
				`tMonPickEndTime` = '" . $tMonPickEndTime. "',
				`fMonPickUpPrice` = '" . $fMonPickUpPrice. "',
				`tTuePickStartTime` = '" . $tTuePickStartTime. "',
				`tTuePickEndTime` = '" . $tTuePickEndTime. "',
				`fTuePickUpPrice` = '" . $fTuePickUpPrice. "',
				`tWedPickStartTime` = '" . $tWedPickStartTime. "',
				`tWedPickEndTime` = '" . $tWedPickEndTime. "',
				`fWedPickUpPrice` = '" . $fWedPickUpPrice. "',
				`tThuPickStartTime` = '" . $tThuPickStartTime. "',
				`tThuPickEndTime` = '" . $tThuPickEndTime. "',
				`fThuPickUpPrice` = '" . $fThuPickUpPrice. "',
				`tFriPickStartTime` = '" . $tFriPickStartTime. "',
				`tFriPickEndTime` = '" . $tFriPickEndTime. "',
				`fFriPickUpPrice` = '" . $fFriPickUpPrice. "',
				`tSatPickStartTime` = '" . $tSatPickStartTime. "',
				`tSatPickEndTime` = '" . $tSatPickEndTime. "',
				`fSatPickUpPrice` = '" . $fSatPickUpPrice. "',
				`tSunPickStartTime` = '" . $tSunPickStartTime. "',
				`tSunPickEndTime` = '" . $tSunPickEndTime. "',
				`fSunPickUpPrice` = '" . $fSunPickUpPrice. "',
				".$vValue." = '" .$_POST[$vTitle_store[$i]]. "'"
				. $where; 
				
				$obj->sql_query($query);
				$id = ($id != '') ? $id : $obj->GetInsertId();
				
		 }

		
		// exit;
		if(isset($_FILES['vLogo']) && $_FILES['vLogo']['name'] != ""){		
		
			$img_path = $tconfig["tsite_upload_images_vehicle_type_path"];
			$temp_gallery = $img_path . '/';
			$image_object = $_FILES['vLogo']['tmp_name'];
			$image_name = $_FILES['vLogo']['name'];
			
			$check_file_query = "select iVehicleTypeId,vLogo from vehicle_type where iVehicleTypeId=" . $id;
			$check_file = $obj->sql_query($check_file_query);		
			
			if($image_name != "") {
			
			
				if($message_print_id != "") {
					 $check_file['vLogo'] = $img_path . '/' . $id . '/android/' . $check_file[0]['vLogo'];						
					 $android_path = $img_path . '/' . $id . '/android';
					 $ios_path = $img_path . '/' . $id . '/ios';
					
					if ($check_file['vLogo'] != '' && file_exists($check_file['vLogo'])) {
						@unlink($android_path . '/'.$check_file[0]['vLogo']);
						@unlink($android_path . '/mdpi_'.$check_file[0]['vLogo']);
						@unlink($android_path . '/hdpi_'.$check_file[0]['vLogo']);
						@unlink($android_path . '/xhdpi_'.$check_file[0]['vLogo']);
						@unlink($android_path . '/xxhdpi_'.$check_file[0]['vLogo']);
						@unlink($android_path . '/xxxhdpi_'.$check_file[0]['vLogo']);
						@unlink($ios_path . '/'.$check_file[0]['vLogo']);
						@unlink($ios_path . '/1x_'.$check_file[0]['vLogo']);
						@unlink($ios_path . '/2x_'.$check_file[0]['vLogo']);
						@unlink($ios_path . '/3x_'.$check_file[0]['vLogo']);
					}
				}
				
				  $Photo_Gallery_folder = $img_path . '/' . $id . '/';
				 $Photo_Gallery_folder_android = $Photo_Gallery_folder . 'android/';
				 $Photo_Gallery_folder_ios = $Photo_Gallery_folder . 'ios/';
				if (!is_dir($Photo_Gallery_folder)) {
				   mkdir($Photo_Gallery_folder, 0777);
				   mkdir($Photo_Gallery_folder_android, 0777);
				   mkdir($Photo_Gallery_folder_ios, 0777);
				}	  
			 
			 $vVehicleType1 = str_replace(' ','',$vVehicleType);
			$img = $generalobj->general_upload_image_vehicle_android($image_object, $image_name, $Photo_Gallery_folder_android, $tconfig["tsite_upload_images_vehicle_type_size1_android"], $tconfig["tsite_upload_images_vehicle_type_size2_android"], $tconfig["tsite_upload_images_vehicle_type_size3_both"], $tconfig["tsite_upload_images_vehicle_type_size4_android"], '', '', 'Y', $tconfig["tsite_upload_images_vehicle_type_size5_both"], $Photo_Gallery_folder_android,$vVehicleType1,NULL);
			$img1 = $generalobj->general_upload_image_vehicle_ios($image_object, $image_name, $Photo_Gallery_folder_ios, '', '', $tconfig["tsite_upload_images_vehicle_type_size3_both"], $tconfig["tsite_upload_images_vehicle_type_size5_both"], '', '', 'Y', $tconfig["tsite_upload_images_vehicle_type_size5_ios"], $Photo_Gallery_folder_ios,$vVehicleType1,NULL);
			$vImage = "ic_car_".$vVehicleType1.".png";
			
			  
				$sql = "UPDATE ".$tbl_name." SET `vLogo` = '" . $vImage . "' WHERE `iVehicleTypeId` = '" . $id . "'";
			
				$obj->sql_query($sql);
			}
		}
	   
	    if(isset($_FILES['vLogo1']) && $_FILES['vLogo1']['name'] != ""){
			$img_path = $tconfig["tsite_upload_images_vehicle_type_path"];
			$temp_gallery = $img_path . '/';
			$image_object = $_FILES['vLogo1']['tmp_name'];
			$image_name = $_FILES['vLogo1']['name'];
			$check_file_query = "select iVehicleTypeId,vLogo1 from vehicle_type where iVehicleTypeId=" . $id;
			$check_file = $obj->sql_query($check_file_query);
				if($image_name != "") {
				  if($message_print_id != "") {
						$check_file['vLogo1'] = $img_path . '/' . $id . '/android/' . $check_file[0]['vLogo1'];
						$android_path = $img_path . '/' . $id . '/android';
						$ios_path = $img_path . '/' . $id . '/ios';
						
						if ($check_file['vLogo1'] != '' && file_exists($check_file['vLogo1'])) {
							@unlink($android_path . '/'.$check_file[0]['vLogo1']);
							@unlink($android_path . '/mdpi_hover_'.$check_file[0][0]['vLogo1']);
							@unlink($android_path . '/hdpi_hover_'.$check_file[0]['vLogo1']);
							@unlink($android_path . '/xhdpi_hover_'.$check_file[0]['vLogo1']);
							@unlink($android_path . '/xxhdpi_hover_'.$check_file[0]['vLogo1']);
							@unlink($android_path . '/xxxhdpi_hover_'.$check_file[0]['vLogo1']);
							@unlink($ios_path . '/'.$check_file[0]['vLogo1']);
							@unlink($ios_path . '/1x_hover_'.$check_file[0]['vLogo1']);
							@unlink($ios_path . '/2x_hover_'.$check_file[0]['vLogo1']);
							@unlink($ios_path . '/3x_hover_'.$check_file[0]['vLogo1']);
						}
					}
					$Photo_Gallery_folder = $img_path . '/' . $id . '/';
					$Photo_Gallery_folder_android = $Photo_Gallery_folder . '/android/';
					$Photo_Gallery_folder_ios = $Photo_Gallery_folder . '/ios/';
					if (!is_dir($Photo_Gallery_folder)) {
					   mkdir($Photo_Gallery_folder, 0777);
					   mkdir($Photo_Gallery_folder_android, 0777);
					   mkdir($Photo_Gallery_folder_ios, 0777);
					}	
					$vVehicleType1 = str_replace(' ','',$vVehicleType);			  
					  $img = $generalobj->general_upload_image_vehicle_android($image_object, $image_name, $Photo_Gallery_folder_android, $tconfig["tsite_upload_images_vehicle_type_size1_android"], $tconfig["tsite_upload_images_vehicle_type_size2_android"], $tconfig["tsite_upload_images_vehicle_type_size3_both"], $tconfig["tsite_upload_images_vehicle_type_size4_android"], '', '', 'Y', $tconfig["tsite_upload_images_vehicle_type_size5_both"], $Photo_Gallery_folder_android,$vVehicleType1,"hover_");
					  $img1 = $generalobj->general_upload_image_vehicle_ios($image_object, $image_name, $Photo_Gallery_folder_ios, '', '', $tconfig["tsite_upload_images_vehicle_type_size3_both"], $tconfig["tsite_upload_images_vehicle_type_size5_both"], '', '', 'Y', $tconfig["tsite_upload_images_vehicle_type_size5_ios"], $Photo_Gallery_folder_ios,$vVehicleType1,"hover_");
					  $vImage1 = "ic_car_".$vVehicleType1.".png";
					  
					  $sql = "UPDATE ".$tbl_name." SET `vLogo1` = '" . $vImage1 . "' WHERE `iVehicleTypeId` = '" . $id . "'";
					  $obj->sql_query($sql);
				}
	    }	
		
		// $obj->sql_query($query);
		 header("Location:vehicle_type_action.php?id=" . $id . '&success=1');
	}

// for Edit
if ($action == 'Edit') {
     $sql = "SELECT * FROM " . $tbl_name . " WHERE iVehicleTypeid = '" . $id . "'";
     $db_data = $obj->MySQLSelect($sql);
	 
     $vLabel = $id;
     if (count($db_data) > 0) {
     	 for($i=0;$i<count($db_master);$i++)
          {

          	 foreach ($db_data as $key => $value) {
          	 	 $vValue = 'vVehicleType_'.$db_master[$i]['vCode'];          	 	 
             	 $$vValue = $value[$vValue];
              	 $vVehicleType = $value['vVehicleType'];
              	 $iCountryId = $value['iCountryId'];
              	 $iStateId = $value['iStateId'];
              	 $iCityId = $value['iCity'];
              	 $iCountryId = $value['iCountryId'];
              	 $vVehicleType = $value['vVehicleType'];
              	 $vVehicleType = $value['vVehicleType'];
              	 $iVehicleCategoryId = $value['iVehicleCategoryId'];
              	 $fPricePerKM = $value['fPricePerKM'];
              	 $fPricePerMin = $value['fPricePerMin'];
              	 $iBaseFare = $value['iBaseFare'];
               	 $iMinFare = $value['iMinFare'];
              	 $fCommision = $value['fCommision'];
		         $iPersonSize = $value['iPersonSize'];
		         $fPricePerHour = $value['fPricePerHour'];
		         //$fPickUpPrice = $value['fPickUpPrice'];
		         $fNightPrice = $value['fNightPrice'];
		        // $tPickStartTime = $value['tPickStartTime'];
		        // $tPickEndTime = $value['tPickEndTime'];
		         $tNightStartTime = $value['tNightStartTime'];
		         $tNightEndTime = $value['tNightEndTime'];
		         $ePickStatus = $value['ePickStatus'];
		         $eNightStatus = $value['eNightStatus'];
		          $tMonPickStartTime = $value['tMonPickStartTime'];
				  $tMonPickEndTime = $value['tMonPickEndTime'];
				  $fMonPickUpPrice = $value['fMonPickUpPrice'];
				  $tTuePickStartTime = $value['tTuePickStartTime'];
				  $tTuePickEndTime = $value['tTuePickEndTime'];
				  $fTuePickUpPrice = $value['fTuePickUpPrice'];
				  $tWedPickStartTime = $value['tWedPickStartTime'];
				  $tWedPickEndTime = $value['tWedPickEndTime'];
				  $fWedPickUpPrice = $value['fWedPickUpPrice'];
				  $tThuPickStartTime = $value['tThuPickStartTime'];
				  $tThuPickEndTime = $value['tThuPickEndTime'];					  
				  $fThuPickUpPrice = $value['fThuPickUpPrice'];
				  $tFriPickStartTime = $value['tFriPickStartTime'];
				  $tFriPickEndTime = $value['tFriPickEndTime'];
				  $fFriPickUpPrice = $value['fFriPickUpPrice'];
				  $tSatPickStartTime = $value['tSatPickStartTime'];
				  $tSatPickEndTime = $value['tSatPickEndTime'];
				  $fSatPickUpPrice = $value['fSatPickUpPrice'];
				  $tSunPickStartTime = $value['tSunPickStartTime'];					  
				  $tSunPickEndTime = $value['tSunPickEndTime'];
				  $fSunPickUpPrice = $value['fSunPickUpPrice'];
				  $vLogo = $value['vLogo'];
				 $eType = $value['eType'];
				 $fFixedFare = $value['fFixedFare'];
				$eFareType = $value['eFareType'];
				$eAllowQty = $value['eAllowQty'];
				$iMaxQty = $value['iMaxQty'];
        	  }
          }         
     }
}   

	$sql_vehicle = "SELECT * FROM " . $tbl_name;
    $db_data_vehicle = $obj->MySQLSelect($sql_vehicle);
	
	$sql_country = "SELECT * FROM country";
	$db_data_country = $obj->MySQLSelect($sql_country);
	
	$sql_state = "SELECT * FROM state where iCountryId='".$iCountryId."'";
	$db_data_state = $obj->MySQLSelect($sql_state);
	
	$sql_city = "SELECT * FROM city where iStateId='".$iStateId."'";
	$db_data_city = $obj->MySQLSelect($sql_city);

		if($APP_TYPE == 'UberX'){ 
     		 //$sql_cat ="SELECT * FROM  vehicle_category";
			 $sql_cat="select *  from vehicle_category where iParentId='0'";
    		 $db_data_cat = $obj->MySQLSelect($sql_cat);
    	}

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

     <!-- BEGIN HEAD-->
     <head>
          <meta charset="UTF-8" />
          <title>Admin | <?php echo $langage_lbl_admin['LBL_VEHICLE_TYPE_SMALL_TXT'];?> <?= $action; ?></title>
          <meta content="width=device-width, initial-scale=1.0" name="viewport" />
          <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
          <?
          include_once('global_files.php');
          ?>
          <!-- On OFF switch -->
          <link href="../assets/css/jquery-ui.css" rel="stylesheet" />
		  <link href="css/bootstrap-select.css" rel="stylesheet" />
          <link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
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
                                   <h2> <?php echo "Vehicle Type Areawise";?> </h2>
                                   <a href="vehicle_type_areawise.php">
                                        <input type="button" value="Back to Listing" class="add-btn">
                                   </a>
                              </div>
                         </div>
                         <hr />
                         <div class="body-div">
                              <div class="form-group">
                                   <? if ($success == 1) {?>
                                   <div class="alert alert-success alert-dismissable msgs_hide">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                        <?=$langage_lbl_admin['LBL_VEHICLE_TYPE_SMALL_TXT'];?> Updated successfully.
                                   </div><br/>
                                   <? } elseif ($success == 2) { ?>
                         						<div class="alert alert-danger alert-dismissable ">
                         								 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                         								 "Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
                         						</div><br/>
                         					<? } elseif ($success == 3) { ?>
                                   <div class="alert alert-danger alert-dismissable">
                         								 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                         								<?php echo $_REQUEST['varmsg'];?> 
                         						</div><br/>	
                         					<? } elseif ($success == 4) { ?>
                                   <div class="alert alert-danger alert-dismissable">
                         								 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                         								 "Please Select Night Start Time less than Night End Time." 
                         						</div><br/>	
                         					<? } ?>
									<? if($_REQUEST['var_msg'] !=Null) { ?>
								<div class="alert alert-danger alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									Record  Not Updated .
								</div><br/>
							<? } ?>		
								   <div id="price1" >

                                   </div><br/>
								   <div id="price" ></div><br/>
                                   <form id="vtype" method="post" action="" enctype="multipart/form-data" >
                                        <input type="hidden" name="id" value="<?= $id; ?>"/>
                                      

                                        <?php if($APP_TYPE == 'UberX'){ ?> 
                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label><?=$langage_lbl_admin['LBL_VEHICLE_CATEGORY_ADMIN'];?><span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                <select  class="form-control" name = 'iVehicleCategoryId' required>
													<option value="">--select--</option>
													<? for ($i = 0; $i < count($db_data_cat); $i++) { ?>
													<option value = "<?php echo $db_data_cat[$i]['iVehicleCategoryId'] ?>" <?php ($db_data_cat[$i]['iVehicleCategoryId'] == $iVehicleCategoryId) ? 'selected' : ''; ?>><?php echo $db_data_cat[$i]['vCategory_EN'];?>
													</option>
													<?php 
													$sql="SELECT * FROM  `vehicle_category` WHERE  `iParentId` = '".$db_data_cat[$i]['iVehicleCategoryId']."' ";
													$db_data2 = $obj->MySQLSelect($sql);
													for ($j = 0; $j < count($db_data2); $j++)
													{
													?>
													<option value = "<?php echo $db_data2[$j]['iVehicleCategoryId'] ?>"
													<?php
														if($db_data2[$j]['iVehicleCategoryId'] == $iVehicleCategoryId)
															echo 'selected';
													?>
													>
														<?php echo "&nbsp;&nbsp;|-- ".$db_data2[$j]['vCategory_EN']; ?></option>
													<?
													}
													}
													?>
												</select>
                                             </div>
                                        </div>

                                        <?php } ?>
                                             <?php if($APP_TYPE == 'Ride-Delivery'){ ?> 

	                                          <div class="row">
	                                             <div class="col-lg-12">
	                                                  <label>Vehicle Category Type<span class="red"> *</span></label>
	                                             </div>
	                                             <div class="col-lg-6">
	                                                  <select  class="form-control" name = 'eType' required>
	                                                      
	                                                     	<option value="Ride" <?php if($eType=="Ride") echo 'selected="selected"'; ?> >Ride</option>
	                                                     	<option value="Deliver"<?php if($eType =="Deliver") echo 'selected="selected"'; ?>>Delivery</option>
	                                                  </select>
	                                             </div>
	                                        </div>
                                         <?php } else{ 
                                         	$Vehicle_type_name = ($APP_TYPE == 'Delivery')? 'Deliver':$APP_TYPE ;
                                         	?>

                                          <input type="hidden" name="eType" value="<?= $Vehicle_type_name; ?>"/>

                                         	<?php } ?>

                                        <div class="row">

                                             <div class="col-lg-12">
                                                  <label><?php echo $langage_lbl_admin['LBL_VEHICLE_TYPE_SMALL_TXT'];?><span class="red"> *</span> 
												  <? if($APP_TYPE != "UberX"){ ?>
													  <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='Type of vehicle like Small car, Luxury car, SUV, VAN for example'></i>
												  <? } ?>
													 </label>
                                             </div>
                                             <div class="col-lg-6">
											 <select  id="vehicletype" name="vehicletype" onChange="changedetail(this.value)" class="selectpicker" data-live-search="true">
	<!--add here-->											
											<?php
											foreach($db_data_vehicle as $vehicle):?>
												<?php if($vehicle['iVehicleTypeId']==$id):?>
												<option selected="selected" value="<?php echo $vehicle['iVehicleTypeId'];?>"><?php echo $vehicle['vVehicleType'];?></option>
												<?php else:?>
												<option value="<?php echo $vehicle['iVehicleTypeId'];?>"><?php echo $vehicle['vVehicleType'];?></option>
												<?php endif;?>
												<?php endforeach;?>
												
											</select> </div>
                                        </div> 
										
										
										<div class="row">

                                             <div class="col-lg-12">
                                                  <label><?php echo "Country";?><span class="red"> *</span></label>
                                             </div>
                                            <div class="col-lg-6">
											<select id="country" onChange="showState(this.value);" name="vCountry" class="selectpicker" data-live-search="true">
												
											<?php
												foreach($db_data_country as $country):?>
												<?php if($country['iCountryId'] == $iCountryId):?>
												<option selected="selected" value="<?php echo $country['iCountryId'];?>"><?php echo $country['vCountry'];?></option>
												<?php else:?>
												<option value="<?php echo $country['iCountryId'];?>"><?php echo $country['vCountry'];?></option>
												<?php endif;?>
												<?php endforeach;?>
												</select>
											</div>
                                        </div>
										<div class="row">
									<div class="col-lg-12">
										<label>State Name<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
											<select id="state" onChange="showCity(this.value);" name="vCity" class="selectpicker" data-live-search="true">
											
												
											<?php
											foreach($db_data_state as $state):?>
												<?php if($state['iStateId']==$iStateId):?>
											<option selected="selected" value="<?php echo $state['iStateId'];?>"><?php echo $state['vState'];?></option>
												<?php else:?>
												<option value="<?php echo $state['iStateId'];?>"><?php echo $state['vState'];?></option>
												<?php endif;?>
												<?php endforeach;?>
												
											</select>
											
										</div>
								</div>
								
								<div class="row">

                                             <div class="col-lg-12">
                                                  <label><?php echo "City";?><span class="red"> *</span></label>
                                             </div>
                                            <div class="col-lg-6">
											<select id="city" name="vCity" class="selectpicker" data-live-search="true">
												
											<?php
												foreach($db_data_city as $city):?>
												<?php if($city['iCityId'] == $iCityId):?>
												<option selected="selected" value="<?php echo $city['iCityId'];?>"><?php echo $country['vCity'];?></option>
												<?php else:?>
												<option value="<?php echo $city['iCityId'];?>"><?php echo $city['vCity'];?></option>
												<?php endif;?>
												<?php endforeach;?>
												</select>
											</div>
                                        </div>
                                         <?
                                        if($count_all > 0) {
                                          for($i=0;$i<$count_all;$i++) {
                                            $vCode = $db_master[$i]['vCode'];
                                            $vTitle = $db_master[$i]['vTitle'];
                                            $eDefault = $db_master[$i]['eDefault'];

                                            $vValue = 'vVehicleType_'.$vCode;

                                            $required = ($eDefault == 'Yes')?'required':'';
                                            $required_msg = ($eDefault == 'Yes')?'<span class="red"> *</span>':'';
                                          ?>
                                           <div class="row">
                                             <div class="col-lg-12">
                                             <label><?php echo $langage_lbl_admin['LBL_VEHICLE_TYPE_SMALL_TXT'];?> (<?=$vTitle;?>) <span class="red"> *</span></label>
                                                 
                                             </div>
                                             <div class="col-lg-6">
                                             <input type="text" class="form-control" name="<?=$vValue;?>" id="<?=$vValue;?>" value="<?=$$vValue;?>" placeholder="<?=$vTitle;?>Value" <?=$required;?>>
                                                 
                                             </div>
                                        </div>
                                        <? }
										} ?>
								  
										 <?php if($APP_TYPE == 'UberX'){ ?> 

											<div class="row">
												 <div class="col-lg-12">
													  <label><?php echo $langage_lbl_admin['LBL_FARE_TYPE_TXT_ADMIN'];?> <span class="red"> *</span></label>
												 </div>
												 <div class="col-lg-6">
													  <select  class="form-control" name='eFareType' id="eFareType" required onchange="get_faretype(this.value)">
															<option value="Regular"<?
															if($eFareType == "Regular")
															{
																echo 'selected="selected"';
															}
															?>>Time And Distance</option>
															<option value="Fixed"<?
															if($eFareType == "Fixed")
															{
																echo 'selected="selected"';
															}
															?>>Fixed</option>
															<option value="Hourly"<?
															if($eFareType == "Hourly")
															{
																echo 'selected="selected"';
															}
															?>>Hourly</option>
													  </select>
												 </div>
											</div>
									 <?php }else{?>
									   <input type="hidden" name="eFareType" value="Regular"/>

										<?php }?> 
										<div class="row" id="fixed_div" style="display:none;">
                                            <div class="col-lg-12">
                                                <label><?php echo $langage_lbl_admin['LBL_FIXED_FARE_TXT_ADMIN'];?><span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="fFixedFare"  id="fFixedFare" value="<?= $fFixedFare; ?>" >
                                            </div>
                                        </div>
                                        
										<div id="Regular_div1">
										 <?php// if($APP_TYPE != 'UberX'){ ?>
											<div class="row" id="hide-km">
												 <div class="col-lg-12">
													  <label> Price Per Km<span class="red"> *</span></label>
												 </div>
												 <div class="col-lg-6">
													  <input type="text" class="form-control" name="fPricePerKM"  id="fPricePerKM" value="<?= $fPricePerKM; ?>"  onchange="getpriceCheck(this.value)">
												 </div>

											</div>
											<?php// } ?> 

											<div class="row" id="hide-price">
												 <div class="col-lg-12">
													  <label><?php echo $langage_lbl_admin['LBL_PRICE_MIN_TXT_ADMIN'];?><span class="red"> *</span></label>
												 </div>
												 <div class="col-lg-6">
													  <input type="text" class="form-control" name="fPricePerMin"  id="fPricePerMin" value="<?= $fPricePerMin; ?>"   onChange="getpriceCheck(this.value)">

												 </div>
											</div>
												<div class="row" id="hide-priceHour">
												 <div class="col-lg-12">
													  <label>Price Per Hour<span class="red"> *</span></label>
												 </div>
												 <div class="col-lg-6">
													  <input type="text" class="form-control" name="fPricePerHour"  id="fPricePerHour" value="<?= $fPricePerHour; ?>"   onChange="getpriceCheck(this.value)">

												 </div>
											</div>
											<?php //if($APP_TYPE != 'UberX'){ ?> 
											 <div class="row" id="hide-minimumfare">
												 <div class="col-lg-12">
													  <label>Minimum Fare<span class="red"> *</span> <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='The minimum fare is the least amount you have to pay. For eg : if you travel a distance of 1 km  , the actual fare will be $10 (base fare $6 + $2/km + $2/min) assuming that it takes 1 min to travel but still you are liable to pay the minimum fare which is $15 for example.'></i></label>
												 </div>
												 <div class="col-lg-6">
													  <input type="text" class="form-control" name="iMinFare"  id="iMinFare" value="<?= $iMinFare; ?>" onchange="getpriceCheck(this.value)">

												 </div>
											</div>
											<div class="row" id="hide-basefare">
												 <div class="col-lg-12">
													  <label> Base Fare<span class="red"> *</span> <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='Base fare is the price that the taxi meter will start at a certain point. Let say if you set base fare $3 then the meter will be set at $3 to begin, and not $0.'></i></label>
												 </div>
												 <div class="col-lg-6">
													  <input type="text" class="form-control" name="iBaseFare"  id="iBaseFare" value="<?= $iBaseFare; ?>" onChange="getpriceCheck(this.value)">
												 </div>
											</div>
											<?php // } ?> 
										</div>										
										<div class="row">
                                             <div class="col-lg-12">
                                                  <label> Commision (%)<span class="red"> *</span> <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='This is % amount that will go to site for each ride.'></i></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" class="form-control" name="fCommision"  id="fCommision" value="<?= $fCommision; ?>" required onChange="getpriceCheck(this.value)">

                                             </div>
                                        </div>
										<div id="Regular_div2">
										<?php //if($APP_TYPE != 'UberX'){ ?> 
											<div class="row">
												 <div class="col-lg-12">
													  <label> Available Seats/Person capacity<span class="red"> *</span> <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='Number of seats available for riders'></i></label>
												 </div>
												 <div class="col-lg-6">
													  <input type="text" class="form-control" name="iPersonSize"  id="iPersonSize" value="<?= $iPersonSize; ?>"  onChange="getpriceCheck(this.value),onlydigit(this.value)">

												 </div>
												 <div id="digit"></div>
											</div>                                       	
	                                        <div class="row">
	                                          <div class="col-lg-12">
	                                                  
	                                                  <label>Peak Time Surcharge On/Off <span class="red"> *</span> <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='This is a multiplier X  to the standard fares causing the fare to be higher than the standard fare during certain times the day; i.e. if X is 1.2 during some point of time then the standard fare will be multiplied by 1.2 to get the final fare.'></i></label>
	                                          </div>
	                                          <div class="col-lg-6">
	    										<div class="make-switch" data-on="success" data-off="warning">
	    											<input type="checkbox" id="ePickStatus" onChange="showhidepickuptime();" name="ePickStatus" <?=($id != '' && $ePickStatus == 'Inactive')?'':'checked';?>/>
	    											</div>
	                        					</div>
	                                        </div>                                       
	                                       
	                                        <div id="showpickuptime" style="display:none;">
		                                        <div class="row">
		                                        	 <div class="col-lg-12 main-table001">
		                                        	 	<div class="main-table001">
			                                        	 	
				                                        	 	<table  class="col-lg-2">	
					                                        	 	<tr>
					                                        	 		<td align="center"><b>Monday</b></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> Start Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tMonPickStartTime"  id="tMonPickStartTime" value="<?= $tMonPickStartTime; ?>" placeholder="Select Pickup Start Time" ></td>
					                                        	 	</tr>	
					                                        	 		<tr>
					                                        	 		<td> End Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tMonPickEndTime"  id="tMonPickEndTime" value="<?= $tMonPickEndTime; ?>" placeholder="Select Pickup End Time" ></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> Price</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td>  <input type="text" class="form-control" name="fMonPickUpPrice"  id="fMonPickUpPrice" value="<?= $fMonPickUpPrice; ?>" placeholder="Enter Price"  onchange="getpriceCheck(this.value)"></td>
					                                        	 	</tr>
				                                        	 	</table>

				                                        	 	<table   class="col-lg-2">	
					                                        	 	<tr>
					                                        	 		<td align="center"><b>Tuesday</b></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td>  Start Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tTuePickStartTime"  id="tTuePickStartTime" value="<?= $tTuePickStartTime; ?>" placeholder="Select Pickup Start Time" ></td>
					                                        	 	</tr>	
					                                        	 		<tr>
					                                        	 		<td> End Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tTuePickEndTime"  id="tTuePickEndTime" value="<?= $tTuePickEndTime; ?>" placeholder="Select Pickup End Time" ></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> Price</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" class="form-control" name="fTuePickUpPrice"  id="fTuePickUpPrice" value="<?= $fTuePickUpPrice; ?>" placeholder="Enter Price"   onchange="getpriceCheck(this.value)"></td>
					                                        	 	</tr>
				                                        	 	</table>

				                                        	 	<table    class="col-lg-2">	
					                                        	 	<tr>
					                                        	 		<td align="center"><b>Wednesday</b></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> Start Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tWedPickStartTime"  id="tWedPickStartTime" value="<?= $tWedPickStartTime; ?>" placeholder="Select Pickup Start Time" ></td>
					                                        	 	</tr>	
					                                        	 		<tr>
					                                        	 		<td> End Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tWedPickEndTime"  id="tWedPickEndTime" value="<?= $tWedPickEndTime; ?>" placeholder="Select Pickup End Time" ></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> Price</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td>  <input type="text" class="form-control" name="fWedPickUpPrice"  id="fWedPickUpPrice" value="<?= $fWedPickUpPrice; ?>"  placeholder="Enter Price"   onchange="getpriceCheck(this.value)"></td>
					                                        	 	</tr>
				                                        	 	</table>

				                                        	 	<table   class="col-lg-2">	
					                                        	 	<tr>
					                                        	 		<td align="center"><b>Thursday</b></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> Start Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tThuPickStartTime"  id="tThuPickStartTime" value="<?= $tThuPickStartTime; ?>" placeholder="Select Pickup Start Time" ></td>
					                                        	 	</tr>	
					                                        	 		<tr>
					                                        	 		<td> End Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tThuPickEndTime"  id="tThuPickEndTime" value="<?= $tThuPickEndTime; ?>" placeholder="Select Pickup End Time" ></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> Price</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td>  <input type="text" class="form-control" name="fThuPickUpPrice"  id="fThuPickUpPrice" value="<?= $fThuPickUpPrice; ?>" placeholder="Enter Price"  onchange="getpriceCheck(this.value)"></td>
					                                        	 	</tr>
				                                        	 	</table>

				                                        	 	<table   class="col-lg-2">	
					                                        	 	<tr>
					                                        	 		<td align="center"><b>Friday</b></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> Start Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tFriPickStartTime"  id="tFriPickStartTime" value="<?= $tFriPickStartTime; ?>" placeholder="Select Pickup Start Time" ></td>
					                                        	 	</tr>	
					                                        	 		<tr>
					                                        	 		<td> End Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tFriPickEndTime"  id="tFriPickEndTime" value="<?= $tFriPickEndTime; ?>" placeholder="Select Pickup End Time" ></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> Price</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td>  <input type="text" class="form-control" name="fFriPickUpPrice"  id="fFriPickUpPrice" value="<?= $fFriPickUpPrice; ?>" placeholder="Enter Price"  onchange="getpriceCheck(this.value)"></td>
					                                        	 	</tr>
				                                        	 	</table>

				                                        	 	<table   class="col-lg-2">	
					                                        	 	<tr>
					                                        	 		<td align="center"><b>Saturday</b></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> Start Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tSatPickStartTime"  id="tSatPickStartTime" value="<?=$tSatPickStartTime; ?>" placeholder="Select Pickup Start Time" ></td>
					                                        	 	</tr>	
					                                        	 		<tr>
					                                        	 		<td> End Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tSatPickEndTime"  id="tSatPickEndTime" value="<?= $tSatPickEndTime; ?>" placeholder="Select Pickup End Time" ></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> Price</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td>  <input type="text" class="form-control" name="fSatPickUpPrice"  id="fSatPickUpPrice" value="<?= $fSatPickUpPrice; ?>" placeholder="Enter Price"  onchange="getpriceCheck(this.value)"></td>
					                                        	 	</tr></table>  	  
					                                        	 	<table  class="col-lg-2">	
					                                        	 	<tr>
					                                        	 		<td align="center"><b>Sunday</b></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> Start Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tSunPickStartTime"  id="tSunPickStartTime" value="<?= $tSunPickStartTime; ?>" placeholder="Select Pickup Start Time" ></td>
					                                        	 	</tr>	
					                                        	 		<tr>
					                                        	 		<td> End Time</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> <input type="text" readonly class=" form-control" name="tSunPickEndTime"  id="tSunPickEndTime" value="<?= $tSunPickEndTime; ?>" placeholder="Select Pickup End Time" ></td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td> Price</td>
					                                        	 	</tr>
					                                        	 	<tr>
					                                        	 		<td>  <input type="text" class="form-control" name="fSunPickUpPrice"  id="fSunPickUpPrice" value="<?= $fSunPickUpPrice; ?>" placeholder="Enter Price"  onchange="getpriceCheck(this.value)"></td>
					                                        	 	</tr>                             	 	                                    	 		
			                                        	 	
		                                        	 			</table>
		                                        	 		</div>
		                                        	 </div>
		                                        </div>
		                                    </div> 
                                        
	                                        <div class="row">
	                                         	 <div class="col-lg-12">                                                 
	                                                 <label> Night Charges On/Off <span class="red"> *</span> <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='This is a multiplier X  to the standard fares causing the fare to be higher than the standard fare during night time; i.e. if X is 1.2 during some point of time then the standard fare will be multiplied by 1.2 to get the final fare.'></i></label>
	                                          	</div>
	                                          	<div class="col-lg-6">
	                        						<div class="make-switch" data-on="success" data-off="warning">
	                        							<input type="checkbox" id="eNightStatus" onChange="showhidenighttime();" name="eNightStatus" <?=($id != '' && $eNightStatus == 'Inactive')?'':'checked';?>/>
	                        						</div>
	                        					</div>
	                                        </div>                         
                                     
                                          	<div id="shownighttime" style="display:none;">
	                                            <div class="row">
	                                               <div class="col-lg-12">
	                                                    <label> Night Charges Start Time <span class="red"> *</span></label>
	                                               </div>
	                                               <div class="col-lg-6">
	                                                    <input type="text" readonly class=" form-control" name="tNightStartTime"  id="tNightStartTime" value="<?= $tNightStartTime; ?>" placeholder="Select Night Start Time" >
	                                               </div>
	                                            </div>
		                                        <div class="row">
		                                            <div class="col-lg-12">
		                                                    <label> Night Charges End Time <span class="red"> *</span></label>
		                                            </div>
		                                            <div class="col-lg-6">
		                                                    <input type="text" readonly class=" form-control" name="tNightEndTime"  id="tNightEndTime" value="<?= $tNightEndTime; ?>" placeholder="Select Night End Time" >
		                                            </div>
		                                        </div> 
	                                          	<div class="row">
	                                            	 <div class="col-lg-12">
	                                                  <label> Night Time Surcharge (X)  <span class="red"> *</span></label>
	                                           		  </div>
	                                            	 <div class="col-lg-6">
	                                                  <input type="text" class="form-control" name="fNightPrice"  id="fNightPrice" value="<?= $fNightPrice; ?>" placeholder="Enter Price"   onchange="getpriceCheck(this.value)">

	                                           		  </div>
	                                       		</div>
                                         	</div> 
                                         		<?php //} ?> 

										</div>	
										<?php if($APP_TYPE != 'UberX'){ ?> 
                                         <div class="row">
                                             <div class="col-lg-12">
                                                  <label><?php echo $langage_lbl_admin['LBL_VEHICLE_TYPE_SMALL_TXT'];?> Picture (Gray image) <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='This is used to represent the vehicle type as a icon in application.'></i></label>
                                             </div>
                                             <div class="col-lg-6">
                                             	  <? if($vLogo != '') { ?>
                                             	  <img src="<?=$tconfig['tsite_upload_images_vehicle_type']."/".$id."/ios/3x_".$vLogo;?>" style="width:100px;height:100px;">
                                             	  <?}?>
                                                  <input type="file" class="form-control" name="vLogo" <?php echo $required_rule; ?> id="vLogo" placeholder="" style="padding-bottom: 39px;">
                                                  <br/>
                                                  [Note: Upload only png image size of 360px*360px.]
                                             </div>
                                        </div>										
										<div class="row">
                                             <div class="col-lg-12">
                                                  <label><?php echo $langage_lbl_admin['LBL_VEHICLE_TYPE_SMALL_TXT'];?> Picture (Orange image) <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='This is used to represent the vehicle type as a icon in application. Oragen icon is used to represent the vehicle type as a selected.'></i></label>
                                             </div>
                                             <div class="col-lg-6">
                                             	  <? if($vLogo != '') { ?>
                                             	  <img src="<?=$tconfig['tsite_upload_images_vehicle_type']."/".$id."/ios/3x_hover_".$vLogo;?>" style="width:100px;height:100px;">
                                             	  <?}?>
                                                  <input type="file" class="form-control" name="vLogo1" <?php echo $required_rule; ?> id="vLogo1" placeholder="" style="padding-bottom: 39px;">
                                                  <br/>
                                                  [Note: Upload only png image size of 360px*360px.]
                                             </div>
                                        </div>
                                        <?php } ?> 
										 <?php if($APP_TYPE == 'UberX'){ ?> 
										 <div id="show-in-fixed">
											 <div class="row">
												 <div class="col-lg-12">
													  <label>Allow Quantity <span class="red"> *</span></label>
												 </div>
											 <div class="col-lg-6">
												  <select  class="form-control" name='eAllowQty' id="AllowQty" onchange="get_AllowQty(this.value)">
														<option value="Yes"<?
														if($eAllowQty == "Yes")
														{
															echo 'selected="selected"';
														}
														?>>Yes</option>
														<option value="No"<?
														if($eAllowQty == "No")
														{
															echo 'selected="selected"';
														}
														?>>No</option>
												  </select>
											 </div>
											</div>
											<div class="row" id="iMaxQty">
												<div class="col-lg-12">
													<label>Maximum Quantity<span class="red"> *</span></label>
												</div>
												<div class="col-lg-6">
													<input type="text" class="form-control" name="iMaxQty"  id="iMaxQty" value="<?= $iMaxQty; ?>"  onchange="getpriceCheck(this.value)" >
												</div>
											</div>
                                        </div>
										 
										 <?php } ?>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <input type="submit" class="save btn-info" name="btnsubmit" id="btnsubmit" value="<?= $action." ".$langage_lbl_admin['LBL_VEHICLE_TYPE_SMALL_TXT'];?>" >
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


          <?
          include_once('footer.php');
          ?>
          <script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
          <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
		      <script type="text/javascript" src="js/moment.min.js"></script>
		      <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
			  <!--For Faretype-->			
			
			<script type="text/javascript">
				function changedetail(id){
					//alert(id);
				 $.ajax({
				   type: "POST",
				   url: "functions_area.php",
				   data: "vehicletype_id="+id,
				   dataType:"json",
					success: function(data){
					
					
					if(data.success){           
						document.write//alert(data.status);
						
						//$('#state').html("Hello");
						} else {
							
							alert(data.iStateId);
							var iVehicleTypeId = data.iVehicleTypeId;
							show_country(data.iCountryId);
							showState(data.iStateId);
							showCity(data.iCityId);
							
						  // $('#msg').html(data).fadeIn('slow');
						 // $('#state').html(data); //also show a success message 
						  //$('#state').selectpicker('refresh');
						  //CityId=$('#state option:selected').val();
							/*  var json_obj = $.parseJSON(data);//parse JSON
								alert(json_obj.json);
							 */	// var a=JSON.stringify(data);
							
							//showCity(CityId);
						   
						}
				 }
				});

				}

function show_country(id){
	
	$.ajax({
	   type: "POST",
	   url: "functions_area.php",
	   data: "country_main_id="+id,
		success: function(data){
		
		
		if(data.success){           
			document.write//alert(data.status);
			
			//$('#state').html("Hello");
			} else {
				
				//alert(data);
			  // $('#msg').html(data).fadeIn('slow');
			  $('#country').html(data); //also show a success message 
			  $('#country').selectpicker('refresh');
			  
			   
			}
	 }
	});
	
}
function showState(id){
	alert(id);
//$('#stateloading').show();
 $.ajax({
   type: "POST",
   url: "functions_area.php",
   data: "country_id="+id,
    success: function(data){
	
	
    if(data.success){           
        document.write//alert(data.status);
		
		//$('#state').html("Hello");
        } else {
			
			alert(data);
          // $('#msg').html(data).fadeIn('slow');
          $('#state').html(data); //also show a success message 
		  $('#state').selectpicker('refresh');
		  CityId=$('#state option:selected').val();
			/*  var json_obj = $.parseJSON(data);//parse JSON
				alert(json_obj.json);
			 */	// var a=JSON.stringify(data);
			
			showCity(CityId);
           
        }
 }
});

}

function showCity(id){
	///alert(id);
	//$('#cityloading').show();
 $.ajax({
   type: "POST",
   url: "functions_area.php",
   data: "state_id="+id,
    success: function(data){
	
	
    if(data.success){           
        document.write//alert(data.status);
        } else {
			
          // $('#msg').html(data).fadeIn('slow');
          $('#city').html(data); //also show a success message 
		  $('#city').selectpicker('refresh');
           
        }
 }
});

}
			
			
				$('[data-toggle="tooltip"]').tooltip();
				window.onload = function() {
					
					var vid = $("#vid").val();
					var eFareType = $("#eFareType").val();
					var AllowQty = $("#AllowQty").val();					
					if(vid == '')
					{
						get_faretype('Regular');
					}
					else
					{
						get_faretype(eFareType);
					}					
					
					if(AllowQty == 'Yes'){
					$("#iMaxQty").show();
					$("#iMaxQty").attr('required','required');
					}else{
					$("#iMaxQty").hide();
					$("#iMaxQty").removeAttr('required');
					
					}					
					 
					var appTYpe = '<?php echo $APP_TYPE;?>';				
					
					if(appTYpe == 'UberX' && eFareType=='Regular'){
						$("#Regular_div2").show();
						$("#Regular_div1").show();					
					
					}else if(appTYpe == 'Ride' || appTYpe == 'Delivery' || appTYpe == 'Ride-Delivery'){
						$("#Regular_div2").show();
						$("#Regular_div1").show();				
					
					}else{
						$("#Regular_div2").hide();
						$("#Regular_div1").show();
					
					}
				};
				 var successMSG1 = '<?php echo $success;?>';

                    if(successMSG1 != ''){                       
                         setTimeout(function() {
                            $(".msgs_hide").hide(1000)
                        }, 5000);
                    }
				
				function get_faretype(val)
				{				
					var appTYpe = '<?php echo $APP_TYPE;?>';
					if(appTYpe == 'UberX'){
						
						if(val == "Fixed")
						{							
							$("#fixed_div").show();
							$("#Regular_div1").hide();
							$("#Regular_div2").hide();
							$("#hide-priceHour").hide();
							$("#hide-basefare").hide();
							$("#hide-minimumfare").hide();
							$("#hide-price").hide();
							$("#hide-km").hide();							
							$("#show-in-fixed").show();						
					
							$("#fFixedFare").attr('required','required');
							$("#iMaxQty").attr('required','required');							
							$("#fPricePerKM").removeAttr('required');
							$("#fPricePerMin").removeAttr('required');
							$("#iBaseFare").removeAttr('required');
							$("#iPersonSize").removeAttr('required');
							$("#fPickUpPrice").removeAttr('required');
							$("#tPickStartTime").removeAttr('required');
							$("#tPickEndTime").removeAttr('required');
							$("#tNightStartTime").removeAttr('required');
							$("#tNightEndTime").removeAttr('required');
							$("#fPricePerHour").removeAttr('required');
							$("#iMinFare").removeAttr('required');
						}else if(val == "Regular"){					
						
							$("#fixed_div").hide();
							$("#Regular_div2").show();
							$("#Regular_div1").show();
							$("#show-in-fixed").hide();	
							$("#hide-priceHour").hide();
							$("#hide-km").show();
							$("#hide-basefare").show();
							$("#hide-minimumfare").show();
							$("#hide-price").show();
							$("#fPricePerHour").removeAttr('required');
							$("#iMaxQty").removeAttr('required');	
							$("#fFixedFare").removeAttr('required');
							$("#fPricePerKM").attr('required','required');
							$("#iMinFare").attr('required','required');
							$("#fPricePerMin").attr('required','required');
							$("#iBaseFare").attr('required','required');
							$("#iPersonSize").attr('required','required');
							$("#fPickUpPrice").attr('required','required');
							$("#tPickStartTime").attr('required','required');
							$("#tPickEndTime").attr('required','required');
							$("#tNightStartTime").attr('required','required');
							$("#tNightEndTime").attr('required','required');
							
						
						}
						else
						{
										
							$("#fixed_div").hide();
							$("#Regular_div1").show();
							$("#Regular_div2").hide();						
							$("#hide-basefare").hide();
							$("#hide-minimumfare").hide();
							$("#hide-price").hide();
							$("#hide-km").hide();
							$("#hide-priceHour").show();
							$("#show-in-fixed").hide();							
							$("#fFixedFare").removeAttr('required');
							$("#iMaxQty").removeAttr('required');
							$("#iMinFare").removeAttr('required');
							$("#fPricePerHour").attr('required','required');	
							
							
							
							/* $("#fPricePerKM").attr('required','required');
							$("#fPricePerMin").attr('required','required');
							$("#iBaseFare").attr('required','required');
							$("#iPersonSize").attr('required','required');
							$("#fPickUpPrice").attr('required','required');
							$("#tPickStartTime").attr('required','required');
							$("#tPickEndTime").attr('required','required');
							$("#tNightStartTime").attr('required','required');
							$("#tNightEndTime").attr('required','required'); */
							
							$("#iBaseFare").removeAttr('required');
							$("#fPricePerKM").removeAttr('required');
							$("#fPricePerMin").removeAttr('required');							
							$("#iPersonSize").removeAttr('required');
							$("#fPickUpPrice").removeAttr('required');
							$("#tPickStartTime").removeAttr('required');
							$("#tPickEndTime").removeAttr('required');
							$("#tNightStartTime").removeAttr('required');
							$("#tNightEndTime").removeAttr('required');	
							
							
						}
					}else{
						$("#Regular_div1").show();
						$("#Regular_div2").show();
						$("#fFixedFare").hide();
						$("#show-in-fixed").hide();
						$("#hide-priceHour").hide();						
						$("#fFixedFare").removeAttr('required');
						$("#iMaxQty").removeAttr('required');	
						$("#fPricePerHour").removeAttr('required');
						$("#fPricePerKM").attr('required','required');
						$("#iMinFare").attr('required','required');
						$("#fPricePerMin").attr('required','required');
						$("#iBaseFare").attr('required','required');
						$("#iPersonSize").attr('required','required');
						$("#fPickUpPrice").attr('required','required');
						$("#tPickStartTime").attr('required','required');
						$("#tPickEndTime").attr('required','required');
						$("#tNightStartTime").attr('required','required');
						$("#tNightEndTime").attr('required','required');					
					
					}
				}
				function get_AllowQty(val){
					if(val == "Yes"){
					$("#iMaxQty").show();
					$("#iMaxQty").attr('required','required');
						
					}else{
					
					$("#iMaxQty").hide();
					$("#iMaxQty").removeAttr('required');
					}
				
					
				}
			</script>
			<!--For Faretype End--> 
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
				    function validate_email(id)
                    {

                        var request = $.ajax({
                             type: "POST",
                             url: 'validate_email.php',
                             data: 'id=' +id,
                             success: function (data)
                             {
								if(data==0)
								{
                                  $('#emailCheck').html('<i class="icon icon-remove alert-danger alert">Already Exist,Select Another</i>');
								 $('input[type="submit"]').attr('disabled','disabled');
								}
								else if(data==1)
								{
									var eml=/^[-.0-9a-zA-Z]+@[a-zA-z]+\.[a-zA-z]{2,3}$/;
									result=eml.test(id);
									if(result==true)
									{
									$('#emailCheck').html('<i class="icon icon-ok alert-success alert"> Valid</i>');
									$('input[type="submit"]').removeAttr('disabled');
									}
									else
									{
										$('#emailCheck').html('<i class="icon icon-remove alert-danger alert"> Enter Proper Email</i>');
										 $('input[type="submit"]').attr('disabled','disabled');
									}
								}
                             }
                        });
                   }
				 function getpriceCheck(id)
				    {
						/*var km_rs=document.getElementById('fPricePerKM').value;
						var min_rs=document.getElementById('fPricePerMin').value;
						var base_rs=document.getElementById('iBaseFare').value;
						var com_rs=document.getElementById('fCommision').value;
						if(km_rs != 0 && min_rs !=0 && base_rs != 0 && com_rs != 0)
						{
						}*/

						if(id>0)
						{
							$('input[type="submit"]').removeAttr('disabled');
						}
						else
						{
								$('#price').html('<i class="alert-danger alert"> You can not EnterAny price Zero or Letter</i>');
								$('input[type="submit"]').attr('disabled','disabled');
						}
					}
					function onlydigit(id)
					{
						var digi=/^[1-9]{1}$/;
						result=digi.test(id);
						if(result==true)
						{
							$('input[type="submit"]').removeAttr('disabled');
						}
						else
						{
							$('#digit').html('<i class="alert-danger alert">Only Decimal Number less Than 10</i>');
								$('input[type="submit"]').attr('disabled','disabled');
						}

					}
					
					/*function checkDates() {   
	                    if (tPickStartTime.val() != '' && tPickEndTime.val() != '') {
	                        if (Date.parse(tPickStartTime.val()) > Date.parse(tPickEndTime.val())) {
	                            alert('End date should be before start date');
	                            endDate.val(tPickStartTime.val());
	                        }
	                    }
	                }*/


                        /*$(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tPickStartTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				});
                        });
                        
                        $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tPickEndTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				})
                        }); */
                         $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tMonPickStartTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				});
                        });
                        
                        $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tMonPickEndTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				})
                        });

                         $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tTuePickStartTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				});
                        });
                        
                        $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tTuePickEndTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				})
                        });

                         $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tWedPickStartTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				});
                        });
                        
                        $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tWedPickEndTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				})
                        });


                         $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tThuPickStartTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				});
                        });
                        
                        $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tThuPickEndTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				})
                        });


                         $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tFriPickStartTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				});
                        });
                        
                        $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tFriPickEndTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				})
                        });

                         $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tSatPickStartTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				});
                        });
                        
                        $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tSatPickEndTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				})
                        });

                         $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tSunPickStartTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				});
                        });
                        
                        $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tSunPickEndTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				})
                        });
                                
                           
                        $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tNightStartTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				});
                        });
                        
                        $(function () {
                    				newDate = new Date('Y-M-D');
                              $('#tNightEndTime').datetimepicker({
                    					format: 'HH:mm:ss',
                    					//minDate: moment().format('l'),
                    					ignoreReadonly: true,
                    					//sideBySide: true,
                    				})
                        });    
                        
                        /*
                        $(function () {
                            $('#startTime, #endTime').datetimepicker({
                                format: 'hh:mm',
                                pickDate: false,
                                pickSeconds: false,
                                pick12HourFormat: false            
                            });
                        });
                        */
                        /* $(document).ready(function() {
                            $.validator.addMethod("tPickEndTime", function(value, element) {
                                var startDate = $('#tPickStartTime').val();
                                return Date.parse(startDate) <= Date.parse(value) || value == "";
                            }, "* End date must be after start date");
                            $('#vtype').validate();
                        });*/
                        
                        function showhidepickuptime(){
                           if($('input[name=ePickStatus]').is(':checked')){
                                //alert('Checked');
                                $("#showpickuptime").show();
                           }else{
                                //alert('Not checked');
                                $("#showpickuptime").hide();
                           }
                        }
                        
                        function showhidenighttime(){
                           if($('input[name=eNightStatus]').is(':checked')){
                                //alert('Checked');
                                $("#shownighttime").show();
                           }else{
                                //alert('Not checked');
                                $("#shownighttime").hide();
                           }
                        }
                        showhidepickuptime();
                        showhidenighttime();
          </script>
          <script src="js/bootstrap-select.js"></script>
     </body>
     <!-- END BODY-->
</html>
