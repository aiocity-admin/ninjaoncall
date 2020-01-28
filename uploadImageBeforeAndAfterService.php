<?
	include_once("common.php");

global $generalobj,$tconfig;

		$iMemberId 	= isset($_REQUEST['DriverId'])?clean($_REQUEST['DriverId']):'';		
		$image_name = $vImage	= isset($_FILES['vImage']['name'])?$_FILES['vImage']['name']:'';
		$image_object	= isset($_FILES['vImage']['tmp_name'])?$_FILES['vImage']['tmp_name']:'';
		$image_name = "123.jpg";
		
	
			

	$Photo_Gallery_folder = $tconfig['tsite_upload_trip_images_path'];
			
		if(!is_dir($Photo_Gallery_folder))
		mkdir($Photo_Gallery_folder, 0777);


	$vFile = $generalobj->fileupload($Photo_Gallery_folder,$image_object,$image_name,$prefix='', $vaildExt="pdf,doc,docx,jpg,jpeg,gif,png");	$vImageName = $vFile[0];		
		if($vImageName != ''){						


						$returnArr['Action']="1";
						$returnArr['vImageName']=$vImageName;
			
			}else{
			$returnArr['Action']="0";
			$returnArr['message'] ="LBL_TRY_AGAIN_LATER_TXT";
		}
		
		echo json_encode($returnArr);
		?>