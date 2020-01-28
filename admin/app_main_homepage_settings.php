<?php
include_once('../common.php');
include_once(TPATH_CLASS.'/class.general.php');
include_once(TPATH_CLASS.'/configuration.php');
include_once('../generalFunctions.php');

if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");
     $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$script = 'app_main_homepage_settings';
$RIDE_DELIVERY_SHOW_TYPE = $generalobj->getConfigurations("configurations","RIDE_DELIVERY_SHOW_TYPE");

$var_msg = isset($_REQUEST['var_msg']) ? $_REQUEST['var_msg'] : '';
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : '';

if(isset($_POST['frm_type']) && $_POST['frm_type']!="") {
  	if(SITE_TYPE =='Demo'){
	   $success = 0;
	   $var_msg = "Edit / Delete Record Feature has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.";
	   header("Location:app_main_homepage_settings.php.php?success=".$success."&msg=".$var_msg);exit;
	   exit;
	}

	$activeTab = str_replace(" ","_",$_REQUEST['frm_type']);
	$RIDE_DELIVERY_SHOW_TYPE = $_REQUEST['RIDE_DELIVERY_SHOW_TYPE'];
	$where = " vName = 'RIDE_DELIVERY_SHOW_TYPE' AND eType = '".$_REQUEST['frm_type']."'";
	$sql = "UPDATE configurations SET `vValue` = '" . $RIDE_DELIVERY_SHOW_TYPE . "' WHERE $where";
	$res = $obj->sql_query($sql);

	if(isset($_FILES['RIDE_GRID_ICON_NAME']) && $_FILES['RIDE_GRID_ICON_NAME']['name'] != "") {
	    $filecheck = basename($_FILES['RIDE_GRID_ICON_NAME']['name']);
	    $fileextarr = explode(".", $filecheck);
	    $ext = strtolower($fileextarr[count($fileextarr) - 1]);
	    $flag_error = 0;
	    if($ext != "png") {
	      $flag_error = 1;
	      $var_msg = "Upload only png  Ride Icon image";
	    }
	    $data = getimagesize($_FILES['RIDE_GRID_ICON_NAME']['tmp_name']);

	    $width = $data[0];
	    $height = $data[1];
	     
	    if($width != 360 && $height != 360) {
	      $flag_error = 1;
	      $var_msg = "Please Upload Ride Icon image only 360px * 360px";
	    }

		if ($flag_error == 1) {
			header("Location:app_main_homepage_settings.php?success=0&var_msg=".$var_msg);
			exit;
		}
	}
	if(isset($_FILES['DELIVERY_GRID_ICON_NAME']) && $_FILES['DELIVERY_GRID_ICON_NAME']['name'] != "") {
	    $filecheck = basename($_FILES['DELIVERY_GRID_ICON_NAME']['name']);
	    $fileextarr = explode(".", $filecheck);
	    $ext = strtolower($fileextarr[count($fileextarr) - 1]);
	    $flag_error = 0;
	    if($ext != "png") {
	      $flag_error = 1;
	      $var_msg = "Upload only png Delivery Icon image";
	    }
	    $data = getimagesize($_FILES['DELIVERY_GRID_ICON_NAME']['tmp_name']);

	    $width = $data[0];
	    $height = $data[1];
	     
	    if($width != 360 && $height != 360) {
	      $flag_error = 1;
	      $var_msg = "Please Upload Delivery Icon image only 360px * 360px";
	    }

		if($flag_error == 1) {
			header("Location:app_main_homepage_settings.php?success=0&var_msg=".$var_msg);
			exit;
		}
	}
	  
	if(isset($_FILES['RIDE_BANNER_IMG_NAME']) && $_FILES['RIDE_BANNER_IMG_NAME']['name'] != "") {
		$filecheck = basename($_FILES['RIDE_BANNER_IMG_NAME']['name']);
		$fileextarr = explode(".", $filecheck);
		$ext = strtolower($fileextarr[count($fileextarr) - 1]);
		$flag_error = 0;
		if($ext != "png" && $ext != "jpg" && $ext != "jpeg" && $ext != "gif" && $ext != "bmp") {
		  $flag_error = 1;
		  $var_msg = "You have selected wrong file format for Image. Valid formats are jpg,jpeg,gif,png,bmp.";
		}

		if($flag_error == 1) {
			header("Location:app_main_homepage_settings.php?success=0&var_msg=".$var_msg);
			exit;
		}
	}

	if(isset($_FILES['DELIVERY_BANNER_IMG_NAME']) && $_FILES['DELIVERY_BANNER_IMG_NAME']['name'] != "") {
		$filecheck = basename($_FILES['DELIVERY_BANNER_IMG_NAME']['name']);
		$fileextarr = explode(".", $filecheck);
		$ext = strtolower($fileextarr[count($fileextarr) - 1]);
		$flag_error = 0;
		if($ext != "png" && $ext != "jpg" && $ext != "jpeg" && $ext != "gif" && $ext != "bmp") {
		  $flag_error = 1;
		  $var_msg = "You have selected wrong file format for Image. Valid formats are jpg,jpeg,gif,png,bmp.";
		}

		if($flag_error == 1) {
			header("Location:app_main_homepage_settings.php?success=0&var_msg=".$var_msg);
			exit;
		}
	}

	if(isset($_FILES['RIDE_GRID_ICON_NAME']) && $_FILES['RIDE_GRID_ICON_NAME']['name'] != "") {
	  $currrent_upload_time = time();   
      $img_path = $tconfig["tsite_upload_images_vehicle_category_path"];      
      $temp_gallery = $img_path . '/';
      $image_object = $_FILES['RIDE_GRID_ICON_NAME']['tmp_name'];
      $image_name = $_FILES['RIDE_GRID_ICON_NAME']['name'];


      $check_file_query = "select vName,vValue from configurations where vName='RIDE_GRID_ICON_NAME'";
      $check_file = $obj->MySQLSelect($check_file_query);

      
      if($image_name != "") {
        $check_file['RIDE_GRID_ICON_NAME'] = $img_path . '/' . $check_file[0]['vValue'];

		if ($check_file['RIDE_GRID_ICON_NAME'] != '' && file_exists($check_file['RIDE_GRID_ICON_NAME'])) {
			@unlink($check_file['RIDE_GRID_ICON_NAME']);
		}
        $Photo_Gallery_folder = $img_path . '/';
         
        $Photo_Gallery_folder_android = $Photo_Gallery_folder;

        if (!is_dir($Photo_Gallery_folder)) {
           mkdir($Photo_Gallery_folder, 0777);
           mkdir($Photo_Gallery_folder_android, 0777);
           mkdir($Photo_Gallery_folder_ios, 0777);
        }   
       	
       	$vVehicleType1 = 'ride_icon';

        $img = $generalobj->general_upload_image_vehicle_category_android($image_object, $image_name, $Photo_Gallery_folder_android,'','','','', '', '', 'Y','', $Photo_Gallery_folder_android,$vVehicleType1,NULL);

        $img_time = explode("_", $img);
        $time_val = $img_time[0];

        $filecheck = basename($_FILES['RIDE_GRID_ICON_NAME']['name']);
        $fileextarr = explode(".", $filecheck);
        $ext = strtolower($fileextarr[count($fileextarr) - 1]);

        $vImage = "ic_car_ride_icon_".$time_val.".".$ext; 
        $where = " vName = 'RIDE_GRID_ICON_NAME' AND eType = '".$_REQUEST['frm_type']."'";
        $sql = "UPDATE configurations SET `vValue` = '" . $vImage . "' WHERE $where";
        $obj->sql_query($sql);
      }
    }

	if(isset($_FILES['RIDE_BANNER_IMG_NAME']) && $_FILES['RIDE_BANNER_IMG_NAME']['name'] != "") {
	 $currrent_upload_time = time();   
      $img_path = $tconfig["tsite_upload_images_vehicle_category_path"];      
      $temp_gallery = $img_path . '/';
      $image_object = $_FILES['RIDE_BANNER_IMG_NAME']['tmp_name'];
      $image_name = $_FILES['RIDE_BANNER_IMG_NAME']['name'];

      $data = getimagesize($_FILES['RIDE_BANNER_IMG_NAME']['tmp_name']);
      $imgwidth = $data[0];
      $imgheight = $data[1];

      $aspectRatio = $width / $height;
      $aspect = round($aspectRatio, 2);
      if($aspect != "1.78") {
        echo"<script>alert('Please upload image with recommended dimensions and aspect ratio 16:9. Otherwise image will look stretched.');</script>";
      }

      if($width < 2880) {
         echo"<script>alert('Your Image upload size is less than recommended. Image will look stretched.');</script>";
      }

      if($width > 2880) {
        echo"<script>alert('Uploaded image size is larger than recommended size, Image may take much time to load.');</script>";
      }

      $check_file_query = "select vName,vValue from configurations where vName='RIDE_BANNER_IMG_NAME'";
      $check_file = $obj->MySQLSelect($check_file_query);

      
      if($image_name != "") {
        $check_file['RIDE_BANNER_IMG_NAME'] = $img_path . '/' . $check_file[0]['vValue'];

		if ($check_file['RIDE_BANNER_IMG_NAME'] != '' && file_exists($check_file['RIDE_BANNER_IMG_NAME'])) {
			@unlink($check_file['RIDE_BANNER_IMG_NAME']);
		}
        $Photo_Gallery_folder = $img_path . '/';
         
        $Photo_Gallery_folder_android = $Photo_Gallery_folder;

        if (!is_dir($Photo_Gallery_folder)) {
           mkdir($Photo_Gallery_folder, 0777);
           mkdir($Photo_Gallery_folder_android, 0777);
           mkdir($Photo_Gallery_folder_ios, 0777);
        }   
       	
       	$vVehicleType1 = 'ride_banner';

        $img = $generalobj->general_upload_image_vehicle_category_android($image_object, $image_name, $Photo_Gallery_folder_android,'','','','', '', '', 'Y','', $Photo_Gallery_folder_android,$vVehicleType1,NULL);

        $img_time = explode("_", $img);
        $time_val = $img_time[0];

        $filecheck = basename($_FILES['RIDE_BANNER_IMG_NAME']['name']);
        $fileextarr = explode(".", $filecheck);
        $ext = strtolower($fileextarr[count($fileextarr) - 1]);

        $vImage = "ic_car_ride_banner_".$time_val.".".$ext; 
        $where = " vName = 'RIDE_BANNER_IMG_NAME' AND eType = '".$_REQUEST['frm_type']."'";
        $sql = "UPDATE configurations SET `vValue` = '" . $vImage . "' WHERE $where";
        $obj->sql_query($sql);
      }
    }

    if(isset($_FILES['DELIVERY_GRID_ICON_NAME']) && $_FILES['DELIVERY_GRID_ICON_NAME']['name'] != "") {
	  $currrent_upload_time = time();   
      $img_path = $tconfig["tsite_upload_images_vehicle_category_path"];      
      $temp_gallery = $img_path . '/';
      $image_object = $_FILES['DELIVERY_GRID_ICON_NAME']['tmp_name'];
      $image_name = $_FILES['DELIVERY_GRID_ICON_NAME']['name'];


      $check_file_query = "select vName,vValue from configurations where vName='DELIVERY_GRID_ICON_NAME'";
      $check_file = $obj->MySQLSelect($check_file_query);

      
      if($image_name != "") {
        $check_file['DELIVERY_GRID_ICON_NAME'] = $img_path . '/' . $check_file[0]['vValue'];

		if ($check_file['DELIVERY_GRID_ICON_NAME'] != '' && file_exists($check_file['DELIVERY_GRID_ICON_NAME'])) {
			@unlink($check_file['DELIVERY_GRID_ICON_NAME']);
		}
        $Photo_Gallery_folder = $img_path . '/';
         
        $Photo_Gallery_folder_android = $Photo_Gallery_folder;

        if (!is_dir($Photo_Gallery_folder)) {
           mkdir($Photo_Gallery_folder, 0777);
           mkdir($Photo_Gallery_folder_android, 0777);
           mkdir($Photo_Gallery_folder_ios, 0777);
        }   
       	
       	$vVehicleType1 = 'delivery_icon';

        $img = $generalobj->general_upload_image_vehicle_category_android($image_object, $image_name, $Photo_Gallery_folder_android,'','','','', '', '', 'Y','', $Photo_Gallery_folder_android,$vVehicleType1,NULL);

        $img_time = explode("_", $img);
        $time_val = $img_time[0];

        $filecheck = basename($_FILES['DELIVERY_GRID_ICON_NAME']['name']);
        $fileextarr = explode(".", $filecheck);
        $ext = strtolower($fileextarr[count($fileextarr) - 1]);

        $vImage = "ic_car_delivery_icon_".$time_val.".".$ext; 
        $where = " vName = 'DELIVERY_GRID_ICON_NAME' AND eType = '".$_REQUEST['frm_type']."'";
        $sql = "UPDATE configurations SET `vValue` = '" . $vImage . "' WHERE $where";
        $res = $obj->sql_query($sql);
      }
    }

    if(isset($_FILES['DELIVERY_BANNER_IMG_NAME']) && $_FILES['DELIVERY_BANNER_IMG_NAME']['name'] != "") {
	  $currrent_upload_time = time();   
      $img_path = $tconfig["tsite_upload_images_vehicle_category_path"];      
      $temp_gallery = $img_path . '/';
      $image_object = $_FILES['DELIVERY_BANNER_IMG_NAME']['tmp_name'];
      $image_name = $_FILES['DELIVERY_BANNER_IMG_NAME']['name'];

      $data = getimagesize($_FILES['DELIVERY_BANNER_IMG_NAME']['tmp_name']);
      $imgwidth = $data[0];
      $imgheight = $data[1];

      $aspectRatio = $width / $height;
      $aspect = round($aspectRatio, 2);
      if($aspect != "1.78") {
        echo"<script>alert('Please upload image with recommended dimensions and aspect ratio 16:9. Otherwise image will look stretched.');</script>";
      }

      if($width < 2880) {
         echo"<script>alert('Your Image upload size is less than recommended. Image will look stretched.');</script>";
      }

      if($width > 2880) {
        echo"<script>alert('Uploaded image size is larger than recommended size, Image may take much time to load.');</script>";
      }

      $check_file_query = "select vName,vValue from configurations where vName='DELIVERY_BANNER_IMG_NAME'";
      $check_file = $obj->MySQLSelect($check_file_query);

      
      if($image_name != "") {
        $check_file['DELIVERY_BANNER_IMG_NAME'] = $img_path . '/' . $check_file[0]['vValue'];

		if ($check_file['DELIVERY_BANNER_IMG_NAME'] != '' && file_exists($check_file['DELIVERY_BANNER_IMG_NAME'])) {
			@unlink($check_file['DELIVERY_BANNER_IMG_NAME']);
		}
        $Photo_Gallery_folder = $img_path . '/';
         
        $Photo_Gallery_folder_android = $Photo_Gallery_folder;

        if (!is_dir($Photo_Gallery_folder)) {
           mkdir($Photo_Gallery_folder, 0777);
           mkdir($Photo_Gallery_folder_android, 0777);
           mkdir($Photo_Gallery_folder_ios, 0777);
        }   
       	
       	$vVehicleType1 = 'delivery_banner';

        $img = $generalobj->general_upload_image_vehicle_category_android($image_object, $image_name, $Photo_Gallery_folder_android,'','','','', '', '', 'Y','', $Photo_Gallery_folder_android,$vVehicleType1,NULL);

        $img_time = explode("_", $img);
        $time_val = $img_time[0];

        $filecheck = basename($_FILES['DELIVERY_BANNER_IMG_NAME']['name']);
        $fileextarr = explode(".", $filecheck);
        $ext = strtolower($fileextarr[count($fileextarr) - 1]);

        $vImage = "ic_car_delivery_banner_".$time_val.".".$ext; 
        $where = " vName = 'DELIVERY_BANNER_IMG_NAME' AND eType = '".$_REQUEST['frm_type']."'";
        $sql = "UPDATE configurations SET `vValue` = '" . $vImage . "' WHERE $where";
        $obj->sql_query($sql);
      }
    }

	if($res) {
		$success = 1;
		$var_msg = "Successfully updated main screen settings";
	}
	else {
		$success = 0;
		$var_msg = "Error in update main screen settings";
	}
	header("Location:app_main_homepage_settings.php?success=".$success."&var_msg=".$var_msg);
	exit;
}

$sql = "SELECT * FROM configurations WHERE eType = 'App Settings' AND iSettingId IN ('172','173','174','175','176') ORDER BY eType, vOrder";
$data_gen = $obj->MySQLSelect($sql);

foreach ($data_gen as $key => $value) {	
	$db_gen[$value['eType']][$key]['iSettingId'] = $value['iSettingId'];
	$db_gen[$value['eType']][$key]['tDescription'] = $value['tDescription'];
	$db_gen[$value['eType']][$key]['vValue'] = $value['vValue'];
	$db_gen[$value['eType']][$key]['tHelp'] = $value['tHelp'];
	$db_gen[$value['eType']][$key]['vName'] = $value['vName'];
	$db_gen[$value['eType']][$key]['eInputType'] = $value['eInputType'];
	$db_gen[$value['eType']][$key]['tSelectVal'] = $value['tSelectVal'];
}
/*echo "<pre>";print_r($db_gen);exit;*/
?>
<!DOCTYPE html>
<html lang="en">
     <head>
          <meta charset="UTF-8" />
          <title><?=$SITE_NAME?> | App Main Screen Settings </title>
          <meta content="width=device-width, initial-scale=1.0" name="viewport" />
          <?  include_once('global_files.php'); ?>
     </head>
     <!-- END  HEAD-->
     <!-- BEGIN BODY-->
     <body class="padTop53">
          <!-- MAIN WRAPPER -->
          <div id="wrap">
               <? include_once('header.php');
               include_once('left_menu.php');
               ?>
               <!--PAGE CONTENT -->
               <div id="content">
                    <div class="inner">
                         <div class="row">
                              <div class="col-lg-12">
                                   <h2>App Main Screen Settings</h2>
                              </div>
                         </div>
                         <hr />
                         <div class="body-div">
                 			<div class="row">
								<div class="col-lg-12">
								<? if ($_REQUEST['success']==1) {?>
									<div class="alert alert-success alert-dismissable">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button> 
										<?= $var_msg ?>
									</div>
									<?}else if($_REQUEST['success']==2){ ?>
									<div class="alert alert-danger alert-dismissable">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<?= $langage_lbl['LBL_EDIT_DELETE_RECORD']; ?>
									</div>
									<?php 
									} else if(isset($_REQUEST['success']) && $_REQUEST['success']==0){?>
									<div class="alert alert-danger alert-dismissable">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> 
										<?= $var_msg ?>
									</div>
									<? }
								?>
								</div>
							</div>
                              <div class="form-group">
								<form id="app_main_screen_form" name="app_main_screen_form" method="post" action="" enctype="multipart/form-data">
                                    <?php
										foreach ($db_gen as $key => $value) {
											$cnt = count($value);
											$tab1 = ceil(count($value)/2);
											$tab2 = $cnt - $tab1;
											$newKey = str_replace(" ","_",$key);
									?>
										<div id="<?=$newKey?>" class="tab-pane <?php echo $activeTab == $newKey?'active':''?>">
											<form method="POST" action="" name="frm_<?=$key?>">
												<input type="hidden" name="frm_type" value="<?=$key?>">
												<div class="row">
													<div class="col-lg-6">
													<?
														$i = 0;
														$temp = true;
														foreach ($value as $key1 => $value1) {
															$i++;
															if($tab1 < $i && $temp) {
																$temp = false;
														?>
<!-- 													</div>
													<div class="col-lg-12"> -->
													<? } ?>
														<div class="form-group">
															<label id="<?=$value1['vName']?>_LABEL"><?=$value1['tDescription']?><?php if($value1['tHelp']!=""){?> <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='<?= htmlspecialchars($value1['tHelp'], ENT_QUOTES, 'UTF-8') ?>'></i><?php }?></label>
															<?php if($value1['eInputType'] == 'Textarea') { ?>
																<textarea class="form-control" rows="5" name="<?=$value1['vName']?>"><?=$value1['vValue']?></textarea>
															<?php
																} elseif ($value1['eInputType'] == 'Select') {
																	$optionArr = explode(',', $value1['tSelectVal']);
															?>	
																<select class="form-control" name="<?=$value1['vName']?>" id="<?=$value1['vName']?>">
																<?php
																	foreach ($optionArr as $oKey => $oValue) {
																		$selected = $oValue==$value1['vValue']?'selected':'';
																?>
																	<option value="<?=$oValue?>" <?=$selected?>><?=$oValue?></option>
																<?php
																	}
																?>
																</select>

															<?php
																} else {
																	if($value1['vName'] == 'RIDE_GRID_ICON_NAME' || $value1['vName'] == 'RIDE_BANNER_IMG_NAME'  || $value1['vName'] == 'DELIVERY_GRID_ICON_NAME' || $value1['vName'] == 'DELIVERY_BANNER_IMG_NAME' ) {
															?>
															<? if($value1['vValue'] != '') { ?>  
															<div style="margin: 5px 0;" id="<?=$value1['vName']?>_IMG">
																<img src="<?=$tconfig['tsite_upload_images_vehicle_category']."/".$value1['vValue'];?>" style="height:100px;">
				                                            </div>
				                                            <?}?>
																<input type="file" name="<?=$value1['vName']?>" class="form-control" value="<?=$value1['vValue']?>" id="<?=$value1['vName']?>">
																<?php if($value1['vName'] == 'RIDE_GRID_ICON_NAME' || $value1['vName'] == 'DELIVERY_GRID_ICON_NAME') { ?>
																<div class="note_icon">[Note: Upload only png image size of 360px*360px.]</div>
																<?php } else { ?>
																<div class="note_banner">[Note: Recommended dimension for banner image is 2880 * 1620.]</div>
																<?php } ?>
															<?		} else { 
															?>

																<input type="text" name="<?=$value1['vName']?>" class="form-control" value="<?=$value1['vValue']?>" >
															<?php }
														} ?>
														</div>
													<? } ?>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-6">
														<div class="form-group">
															<button class="btn btn-primary save-configuration" type="submit">Save Changes</button>
														</div>
													</div>
												</div>
											</form>
										</div>
									<? } ?>
										</div>
                                   </form>
                              </div>
                         </div>
                    </div>
               </div>
               <!--END PAGE CONTENT -->
          </div>
          <!--END MAIN WRAPPER -->

	<?php include_once('footer.php'); ?>
	<script>		
	$(function() {
	    var ride_delivery = $('#RIDE_DELIVERY_SHOW_TYPE').val();
    	if(ride_delivery == 'Icon') {
            $('#RIDE_GRID_ICON_NAME').show();
            $('#RIDE_GRID_ICON_NAME_LABEL').show();
            $('#RIDE_GRID_ICON_NAME_IMG').show();
            $('#DELIVERY_GRID_ICON_NAME').show();
            $('#DELIVERY_GRID_ICON_NAME_LABEL').show();
            $('#DELIVERY_GRID_ICON_NAME_IMG').show();
            $('#RIDE_BANNER_IMG_NAME').hide();
            $('#RIDE_BANNER_IMG_NAME_LABEL').hide();
            $('#RIDE_BANNER_IMG_NAME_IMG').hide();
            $('#DELIVERY_BANNER_IMG_NAME').hide();
            $('#DELIVERY_BANNER_IMG_NAME_LABEL').hide();
            $('#DELIVERY_BANNER_IMG_NAME_IMG').hide();
            $('.note_icon').show();
            $('.note_banner').hide();
        } else if(ride_delivery == 'Banner') {
        	$('#RIDE_GRID_ICON_NAME').hide();
        	$('#RIDE_GRID_ICON_NAME_LABEL').hide();
        	$('#RIDE_GRID_ICON_NAME_IMG').hide();
            $('#DELIVERY_GRID_ICON_NAME').hide();
            $('#DELIVERY_GRID_ICON_NAME_LABEL').hide();
            $('#DELIVERY_GRID_ICON_NAME_IMG').hide();
            $('#RIDE_BANNER_IMG_NAME').show();
            $('#RIDE_BANNER_IMG_NAME_LABEL').show();
            $('#RIDE_BANNER_IMG_NAME_IMG').show();
            $('#DELIVERY_BANNER_IMG_NAME').show();
            $('#DELIVERY_BANNER_IMG_NAME_LABEL').show();
            $('#DELIVERY_BANNER_IMG_NAME_IMG').show();
            $('.note_icon').hide();
            $('.note_banner').show();
        } else if(ride_delivery == 'Icon-Banner') {
            $('#RIDE_GRID_ICON_NAME').show();
            $('#RIDE_GRID_ICON_NAME_LABEL').show();
            $('#RIDE_GRID_ICON_NAME_IMG').show();
            $('#DELIVERY_GRID_ICON_NAME').show();
            $('#DELIVERY_GRID_ICON_NAME_LABEL').show();
            $('#DELIVERY_GRID_ICON_NAME_IMG').show();
            $('#RIDE_BANNER_IMG_NAME').show();
            $('#RIDE_BANNER_IMG_NAME_LABEL').show();
            $('#RIDE_BANNER_IMG_NAME_IMG').show();
            $('#DELIVERY_BANNER_IMG_NAME').show();
            $('#DELIVERY_BANNER_IMG_NAME_LABEL').show();
            $('#DELIVERY_BANNER_IMG_NAME_IMG').show();
            $('.note_icon').show();
            $('.note_banner').show();
        }

	    $('#RIDE_DELIVERY_SHOW_TYPE').change(function(){
	        if($('#RIDE_DELIVERY_SHOW_TYPE').val() == 'Icon') {
	            $('#RIDE_GRID_ICON_NAME').show();
	            $('#RIDE_GRID_ICON_NAME_LABEL').show();
	            $('#RIDE_GRID_ICON_NAME_IMG').show();
	            $('#DELIVERY_GRID_ICON_NAME').show();
	            $('#DELIVERY_GRID_ICON_NAME_LABEL').show();
	            $('#DELIVERY_GRID_ICON_NAME_IMG').show();
	            $('#RIDE_BANNER_IMG_NAME').hide();
	            $('#RIDE_BANNER_IMG_NAME_LABEL').hide();
	            $('#RIDE_BANNER_IMG_NAME_IMG').hide();
	            $('#DELIVERY_BANNER_IMG_NAME').hide();
	            $('#DELIVERY_BANNER_IMG_NAME_LABEL').hide();
	            $('#DELIVERY_BANNER_IMG_NAME_IMG').hide();
              $('.note_icon').show();
              $('.note_banner').hide();
	        } else if($('#RIDE_DELIVERY_SHOW_TYPE').val() == 'Banner') {
	        	$('#RIDE_GRID_ICON_NAME').hide();
	        	$('#RIDE_GRID_ICON_NAME_LABEL').hide();
	        	$('#RIDE_GRID_ICON_NAME_IMG').hide();
	            $('#DELIVERY_GRID_ICON_NAME').hide();
	            $('#DELIVERY_GRID_ICON_NAME_LABEL').hide();
	            $('#DELIVERY_GRID_ICON_NAME_IMG').hide();
	            $('#RIDE_BANNER_IMG_NAME').show();
	            $('#RIDE_BANNER_IMG_NAME_LABEL').show();
	            $('#RIDE_BANNER_IMG_NAME_IMG').show();
	            $('#DELIVERY_BANNER_IMG_NAME').show();
	            $('#DELIVERY_BANNER_IMG_NAME_LABEL').show();
	            $('#DELIVERY_BANNER_IMG_NAME_IMG').show();
              $('.note_icon').hide();
              $('.note_banner').show();
	        } else if($('#RIDE_DELIVERY_SHOW_TYPE').val() == 'Icon-Banner') {
	            $('#RIDE_GRID_ICON_NAME').show();
	            $('#RIDE_GRID_ICON_NAME_LABEL').show();
	            $('#RIDE_GRID_ICON_NAME_IMG').show();
	            $('#DELIVERY_GRID_ICON_NAME').show();
	            $('#DELIVERY_GRID_ICON_NAME_LABEL').show();
	            $('#DELIVERY_GRID_ICON_NAME_IMG').show();
	            $('#RIDE_BANNER_IMG_NAME').show();
	            $('#RIDE_BANNER_IMG_NAME_LABEL').show();
	            $('#RIDE_BANNER_IMG_NAME_IMG').show();
	            $('#DELIVERY_BANNER_IMG_NAME').show();
	            $('#DELIVERY_BANNER_IMG_NAME_LABEL').show();
	            $('#DELIVERY_BANNER_IMG_NAME_IMG').show();
              $('.note_icon').show();
              $('.note_banner').show();
	        }
	    });
	});
	</script>
</body>
<!-- END BODY-->
</html>
