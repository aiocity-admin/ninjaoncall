<?php
include_once('common.php');
$generalobj->check_member_login();
require_once(TPATH_CLASS . "/Imagecrop.class.php");
$thumb = new thumbnail();
$dropdownFields=array();

$script="Vehicle";
$abc = 'admin,driver,company';
$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$generalobj->setRole($abc, $url);
$tbl_name = 'driver_vehicle';

$success = isset($_GET['success']) ? $_GET['success'] :'';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$driverid = isset($_GET['driverid']) ? $_GET['driverid'] : '';
$error = isset($_GET['success']) && $_GET['success']==0 ? 1 : '';
$var_msg = isset($_REQUEST['var_msg']) ? $_REQUEST['var_msg'] : '';
$extraField=isset($_REQUEST['extraField'])?$_REQUEST['extraField']:array();
$documentType=isset($_REQUEST['documentType'])?$_REQUEST['documentType']:'';


if (isset($_POST['Submit'])) {
	
		$iVehicleId = isset($_REQUEST['iVehicleId1']) ? $_REQUEST['iVehicleId1'] : '';		
		$doc_name = $_POST['doc_name'];
		$doc_path = $_POST['doc_path2'];
    $expDate=$_POST['dLicenceExp'];
		

	  $image =$_FILES['file']['name'];
	 	$image_object = $_FILES['file']['tmp_name'];
		$masterid= $_POST['doc_id'];
					
if($expDate != ""){
  $sql = "select ex_date from document_list where doc_userid='".$iVehicleId."' and doc_masterid='".$masterid."'"; 
  $query = $obj->MySQLSelect($sql);
  $fetch = $query[0];

  if($fetch['ex_date'] != $expDate || $image_name == "") {  
           $subQuery="";
              $z=1;
foreach ($extraField as $key => $value) {
  $subQuery.=",ExtraField_".$z."='".$value."'";
  $z++;
}
 

      $sql="UPDATE `document_list` SET  ex_date='".$expDate."',`DocumentTypeName`='".$documentType."'  $subQuery  WHERE doc_userid='".$iVehicleId."' and doc_masterid='".$masterid."'";
      $query= $obj->sql_query($sql);                 
  } else {
      if ($image_name == "") {
       $var_msg = $langage_lbl['LBL_DOC_UPLOAD_ERROR_'];
       header("location:vehicle.php?success=0&id=" . $iVehicleId . "&var_msg=" . $var_msg);
       exit();
      }
  }
}
			
if($image != '') {
			$Photo_Gallery_folder = $doc_path . '/' .$iVehicleId. '/';
      if (!is_dir($Photo_Gallery_folder)) {
        mkdir($Photo_Gallery_folder, 0777);
      }
			//$img = $generalobj->general_upload_image($image_object, $image_name, $Photo_Gallery_folder, $tconfig["tsite_upload_documnet_size1"], $tconfig["tsite_upload_documnet_size2"], '', '', '', '', 'Y', '', $Photo_Gallery_folder);
      $vFile = $generalobj->fileupload($Photo_Gallery_folder, $image_object, $image, $prefix = '', $vaildExt = "pdf,doc,docx,jpg,jpeg,gif,png");
      $vImage = $vFile[0];
      if($vFile[2] == "1") {
        $var_msg = $vFile[1];
        header("location:vehicle.php?success=0&var_msg=" . $var_msg);
      } else {
        $var_msg = $langage_lbl['LBL_UPLOAD_MSG'];
      } 
      $tbl = 'document_list';
      $sql = "select doc_id from  ".$tbl."  where doc_userid='".$iVehicleId."' and doc_usertype='car'  and doc_masterid='".$masterid."'" ;
      $db_data = $obj->MySQLSelect($sql);

		  if (count($db_data) > 0) {

           $subQuery="";
              $z=1;
foreach ($extraField as $key => $value) {
  $subQuery.=",ExtraField_".$z++."='".$value."'";
}

  			$query="UPDATE `".$tbl."` SET `doc_file`='".$vImage."',`DocumentTypeName`='".$documentType."' $subQuery WHERE doc_userid='".$iVehicleId."' and doc_usertype='car'  and doc_masterid='".$masterid."'"; 
        $obj->sql_query($query);

			} else {
        $subQuery_col="";
          $subQuery_val="";
                $z=1;
foreach ($extraField as $key => $value) {
  $subQuery_col.=",ExtraField_".$z++;
    $subQuery_val.=",'".$value."'";
}

  			$query =" INSERT INTO `".$tbl."` ( `doc_masterid`, `doc_usertype`, `doc_userid`,`ex_date`,`doc_file`, `status`, `edate`,`DocumentTypeName` $subQuery_col) "  . "VALUES " . "( '".$masterid."', 'car', '".$iVehicleId."','".$expDate."','".$vImage."', 'Inactive', CURRENT_TIMESTAMP,'".$documentType."' $subQuery_val)";
  			$obj->sql_query($query);
		  }
		}

}

$dri_ssql = "";
if (SITE_TYPE == 'Demo') {
    $dri_ssql = " And rd.tRegistrationDate > '" . WEEK_DATE . "'";
}
if ($_SESSION['sess_user'] == 'driver') {
     $sql = "select iCompanyId from `register_driver` where iDriverId = '" . $_SESSION['sess_iUserId'] . "'";
     $db_usr = $obj->MySQLSelect($sql);
     $iCompanyId = $db_usr[0]['iCompanyId'];
	 
     // $sql = "SELECT * FROM " . $tbl_name . " where iCompanyId = '" . $iCompanyId . "' and iDriverId = '" . $_SESSION['sess_iUserId'] . "' and eStatus != 'Deleted'";
     // $db_driver_vehicle = $obj->MySQLSelect($sql);

    if($APP_TYPE == 'UberX'){
        $sql = "SELECT * FROM " . $tbl_name . " dv  where iCompanyId = '" . $iCompanyId . "'  and dv.eType == 'UberX' and dv.iDriverId = '" . $_SESSION['sess_iUserId'] . "' and dv.eStatus != 'Deleted' ORDER BY dv.iDriverVehicleId DESC";
		    $db_driver_vehicle = $obj->MySQLSelect($sql);
    } else {
        $sql = "SELECT dv.*,rd.vCountry,m.vTitle, mk.vMake,dv.vLicencePlate,dv.eStatus  FROM " . $tbl_name . " dv  JOIN model m ON dv.iModelId=m.iModelId  JOIN make mk ON  dv.iMakeId=mk.iMakeId JOIN register_driver as rd ON rd.iDriverId = dv.iDriverId where dv.iCompanyId = '" . $iCompanyId . "' and dv.iDriverId = '" . $_SESSION['sess_iUserId'] . "' and dv.eType != 'UberX' and dv.eStatus != 'Deleted' $dri_ssql ORDER BY dv.iDriverVehicleId DESC";
		    $db_driver_vehicle = $obj->MySQLSelect($sql);
    }

}

if ($_SESSION['sess_user'] == 'company') {
     $iCompanyId = $_SESSION['sess_iUserId'];
	 // $sql = "SELECT * FROM " . $tbl_name . " where iCompanyId = '" . $iCompanyId . "' and eStatus != 'Deleted'";
     // $db_driver_vehicle = $obj->MySQLSelect($sql);

      if($APP_TYPE == 'UberX'){
        $sql = "SELECT * FROM " . $tbl_name . " dv  where iCompanyId = '" . $iCompanyId . "' and dv.eType == 'UberX' and dv.eStatus != 'Deleted' GROUP BY dv.vLicencePlate ORDER BY dv.iDriverVehicleId DESC";
     
       $db_driver_vehicle = $obj->MySQLSelect($sql);

      }else{
         $sql = "SELECT dv.*,rd.vCountry,CONCAT(rd.vName,' ',rd.vLastName) AS driverName,m.vTitle, mk.vMake,dv.vLicencePlate,dv.eStatus  FROM " . $tbl_name . " dv  JOIN model m ON  dv.iModelId=m.iModelId  JOIN make mk ON  dv.iMakeId=mk.iMakeId JOIN register_driver as rd ON rd.iDriverId = dv.iDriverId where dv.iCompanyId = '" . $iCompanyId . "' and dv.eType != 'UberX' and dv.eStatus != 'Deleted' $dri_ssql GROUP BY dv.vLicencePlate ORDER BY dv.iDriverVehicleId DESC";
       $db_driver_vehicle = $obj->MySQLSelect($sql);

      }

}

// $sql = "select iDriverVehicleId from driver_vehicle where iDriverId = '".$_SESSION['sess_iUserId']."'";
// $iDriverVehicleId = $obj->MySQLSelect($sql);
// $iDriverVehicleId=$iDriverVehicleId[0]['iDriverVehicleId'];

if(isset($iDriverVehicleId)){
  $sql = "select * from register_driver where iDriverVehicleId = '".$iDriverVehicleId. "'";
  $db_data = $obj->MySQLSelect($sql);
}

if ($action == 'delete') {
     // to check user is valid or not to delete vehicle

      // if(SITE_TYPE == 'Demo')
     // {
           // header("Location:vehicle.php?success=2");
           // exit;

     // }
     $valid_user = false;
     foreach ($db_driver_vehicle as $val) {
          if ($val['iDriverVehicleId'] == $id)
               $valid_user = true;
     }
     if (!$valid_user) {
          $var_msg = $langage_lbl['LBL_VEHICLE_DELETE_ERROR_MSG'];
          header("Location:vehicle.php?success=0&var_msg=". $var_msg);
     } else {

          $sql = "select count(*) as trip_cnt from trips where iDriverVehicleId = '" . $id . "' AND  iActive IN ('Active', 'On Going Trip')";
          $db_usr = $obj->MySQLSelect($sql);

          $sql1 = "SELECT count(iDriverId) as drivers FROM register_driver WHERE iDriverId = '".$driverid."' AND iDriverVehicleId = '".$id."'";
          $db_driver_data = $obj->MySQLSelect($sql1);
          if (count($db_usr) > 0 && $db_usr[0]['trip_cnt'] > 0) {
               $varmsg = $langage_lbl['LBL_TRIP_VEHICLE_DELETE_ERROR_MSG'];
               header("Location:vehicle.php?success=0&var_msg=". $varmsg);
               exit;
          } elseif(count($db_driver_data) > 0 && $db_driver_data[0]['drivers'] > 0){
               $varmsg = $langage_lbl['LBL_VEHICLE_DELETE_ERROR_MSG'];
               header("Location:vehicle.php?success=0&var_msg=". $varmsg);
               exit;
          } else {
                /*$sql= "SELECT * FROM register_driver WHERE iDriverId = '".$driverid."' AND iDriverVehicleId = '".$id."'";
                $avail_driver = $obj->MySQLSelect($sql);

                if(!empty($avail_driver)) {
                    $query = "UPDATE register_driver SET vAvailability = 'Not Avilable', `iDriverVehicleId`= '0' WHERE iDriverId = '".$driverid."' AND iDriverVehicleId = '" . $id . "'";
                    $obj->sql_query($query);
                }*/
$reason= isset($_REQUEST['reason'])? $_REQUEST['reason']:'';

$query="select vLicencePlate from driver_vehicle where iDriverVehicleId='$id'";

 
$db_data_vLicencePlate = $obj->MySQLSelect($query);

$vLicencePlate_update=$db_data_vLicencePlate[0]['vLicencePlate'];

               $query = "UPDATE `driver_vehicle` SET eStatus = 'Deleted',Reason = '$reason' WHERE vLicencePlate = '" . $vLicencePlate_update . "'";
               $obj->sql_query($query);



               $var_msg = $langage_lbl['LBL_DELETE_VEHICLE'];
               header("Location:vehicle.php?success=1&var_msg=". $var_msg);
               exit;
          }
     }
}

 if ($action=='Inactive'||$action=='Active') {

$reason= isset($_REQUEST['reason'])? $_REQUEST['reason']:'';

$query="select vLicencePlate from driver_vehicle where iDriverVehicleId='$id'";

 
$db_data_vLicencePlate = $obj->MySQLSelect($query);

$vLicencePlate_update=$db_data_vLicencePlate[0]['vLicencePlate'];


               $query = "UPDATE `driver_vehicle` SET eStatus = '$action',Reason = '$reason' WHERE vLicencePlate = '" . $vLicencePlate_update . "'";
               $obj->sql_query($query);

   
               header("Location:vehicle.php?success=1&var_msg=$action successfully.");
               exit;

}


for ($i = 0; $i < count($db_driver_vehicle); $i++) {
  $sql = "select vMake from make where iMakeId = '" . $db_driver_vehicle[$i]['iMakeId'] . "' where vMake !=''";
  $name1 = $obj->MySQLSelect($sql);
  $sql = "select vTitle from model where iModelId = '" . $db_driver_vehicle[$i]['iModelId'] . "' WHERE vTitle !=''";
  $name2 = $obj->MySQLSelect($sql);
  $db_msk[$i] = $name1[0]['vMake'] . ' ' . $name2[0]['vTitle'];
}

?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=$SITE_NAME?> | <?=$langage_lbl['LBL_MANAGE_VEHICLE']; ?></title>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
    <link rel="stylesheet" href="assets/css/bootstrap-fileupload.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/vehicles.css">
    <style>
        .fileupload-preview  { line-height:150px;}
        
        span.fileupload-new {
    white-space: initial;
}
.extraField , .documentType
{
width: 95%;
margin: auto;
}

    </style>
    <!-- End: Default Top Script and css-->
</head>
<body>
     <!-- home page -->
    <div id="main-uber-page">
     <!-- Top Menu -->
    <!-- Left Menu -->
    <?php include_once("top/left_menu.php");?>
    <!-- End: Left Menu-->
        <?php include_once("top/header_topbar.php");?>
        <!-- End: Top Menu-->
        <!-- contact page-->
        <div class="page-contant">
            <div class="page-contant-inner">
          
                <h2 class="header-page add-car-vehicle"><?=$langage_lbl['LBL_MANAGE_VEHICLE']; ?>
                  <?php if($APP_TYPE != 'UberX'){ ?>
                    <a href="vehicle-add"><?=$langage_lbl['LBL_ADD_VEHICLE_TXT']; ?></a><?php } ?>
                </h2>
                
                
              <?php 
                  if(SITE_TYPE =='Demo'){
              ?>
              <div class="demo-warning">
                <p><?=$langage_lbl['LBL_SINCE_THIS']; ?></p>
                </div>
              <?php
                }
              ?>
              
          <!-- driver vehicles page -->
            <div class="driver-vehicles-page-new">
            <?php
                if ($error) {
            ?>
                <div class="row">
                    <div class="col-sm-12 alert alert-danger">
                         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?= $var_msg ?>
                    </div>
                </div>
            <?php 
                }
                if ($success==1) {
            ?>
                <div class="row">
                    <div class="alert alert-success paddiing-10">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?= $var_msg ?>
                    </div>
                </div>
            <?php
                }else if($success==2) {
            ?>
                <div class="row">
                    <div class="alert alert-danger paddiing-10">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?=$langage_lbl['LBL_VEHICLE_EDIT_DELETE_RECORD']; ?>
                    </div>
                </div>
            <?
                }
            ?>
                <div class="vehicles-page">
                    <div class="accordion">
					
                        <?php
                            if (count($db_driver_vehicle) > 0) {
                                for ($i = 0; $i < count($db_driver_vehicle); $i++) {
                        ?>
                        <input type="hidden" name="iVehicleId" value = "<?php echo $db_driver_vehicle[$i]['iDriverVehicleId']; ?>"/>
                            <div class="accordion-section">
                                <div class="accordionheading">
                                   <?php if($APP_TYPE == 'UberX'){
                                      $displayname =  $db_driver_vehicle[$i]['vLicencePlate'];
                                    } else {
                                      $displayname =$db_driver_vehicle[$i]['vMake']."   ".$db_driver_vehicle[$i]['vTitle']."  ".$db_driver_vehicle[$i]['vLicencePlate']."  "  ;
                                      }?> 
                                    <h3><?php echo $displayname  ;?></h3>
                                    <span> 
                                        <b>
                                          <?php 
                                          $class_name = ($db_driver_vehicle[$i]['eStatus'] == "Active")? 'badge success-vehicle-active': 'badge success-vehicle-inactive';
                                          ?>
                                           
                                      <span class="<?php echo $class_name; ?>">
                                         <i class="<?= ($db_driver_vehicle[$i]['eStatus'] == "Active") ? 'icon-eye-open' : 'icon-eye-close' ?>"></i> <?= ucfirst($db_driver_vehicle[$i]['eStatus']); ?>
                                      </span>
                                      <?php $btn_text= $db_driver_vehicle[$i]['eStatus']=='Inactive'?'Active':'Inactive'; 


                                      ?>
<?php if(trim($_SESSION["sess_user"])=="company")
{ 
  ?>
                                      <a href="#" id="Suspend" class="active" onClick="confirm_delete('<?= $db_driver_vehicle[$i]['iDriverVehicleId'] ?>','<?= $db_driver_vehicle[$i]['iDriverId'] ?>','<?php echo $btn_text;?>');"><?php echo $btn_text;?></a>
                                    <?php }?>
                                            <a href ="vehicle_add_form.php?id=<?=base64_encode(base64_encode($db_driver_vehicle[$i]['iDriverVehicleId'])) ?>" class="active"><?=$langage_lbl['LBL_VEHICLE_EDIT']; ?></a>
                                            <?php if($APP_TYPE != 'UberX'){?> 
                                            <a class="active active2" onClick="confirm_delete('<?= $db_driver_vehicle[$i]['iDriverVehicleId'] ?>','<?= $db_driver_vehicle[$i]['iDriverId'] ?>','delete');" href="javascript:void(0);"><?=$langage_lbl['LBL_DELETE']; ?></a><?php } ?>

                                        </b>
                                        <?php                      
                                        $sql1= "SELECT dm.doc_masterid masterid, dm.doc_usertype , dm.doc_name ,dm.ex_status,dm.status, dl.doc_masterid masterid_list ,dl.ex_date,dl.doc_file , dl.status, dm.eType,dl.ExtraField_1,dl.ExtraField_2,dl.ExtraField_3,dl.ExtraField_4,dl.ExtraField_5,dl.DocumentTypeName,dm.IsType FROM document_master dm left join (SELECT * FROM `document_list` where doc_userid='" .$db_driver_vehicle[$i]['iDriverVehicleId']."') dl on dl.doc_masterid=dm.doc_masterid where dm.doc_usertype='car' and dm.status='Active' and (dm.country ='".$db_driver_vehicle[$i]['vCountry']."' OR dm.country ='All')";
                                      $db_userdoc = $obj->MySQLSelect($sql1);



                                      if($APP_TYPE != 'UberX' && !empty($db_userdoc)){?> 
                                        <strong><a id="accordion-click-<?php echo $i;?>" class="accordion-section-title" href="#accordion-<?php echo $i;?>">&nbsp;</a></strong> 

                                        <?php } ?>

                                        </span>
                                        <?php if ($_SESSION['sess_user'] == 'company') { ?>
                                        <div style=" clear: both;margin: 8px 0 0 10px;"><?php echo $langage_lbl['LBL_DRIVER_NAME_ADMIN'];?> :<?php

 $query_provider = "SELECT CONCAT(rd.vName,' ',rd.vLastName) AS driverName  FROM " . $tbl_name . " dv  JOIN model m ON dv.iModelId=m.iModelId JOIN make mk ON  dv.iMakeId=mk.iMakeId JOIN register_driver as rd ON rd.iDriverId = dv.iDriverId where dv.iCompanyId = '" . $iCompanyId . "' and dv.eType != 'UberX' and dv.eStatus != 'Deleted' $dri_ssql and dv.vLicencePlate='".$db_driver_vehicle[$i]['vLicencePlate']."'";
       $db_providers = $obj->MySQLSelect($query_provider);
       $providers="";
for ($z=0; $z <count($db_providers) ; $z++) { 
          $providers.=" ".$generalobj->clearName($db_providers[$z]['driverName']).",";                              
}
 echo rtrim($providers,",");
                                          ?></div>
                                        <?php } ?>
                                </div>
                <div id="accordion-<?php echo $i;?>" class="accordion-section-content">
                    <div class="driver-vehicles-page-new">
                        <h2><?=$langage_lbl['LBL_DOCUMENTS']; ?></h2>                                           
										<ul>
											<?php
											for ($s = 0; $s < count($db_userdoc); $s++) {
                        if($db_userdoc[$s]['eType'] == 'UberX'){
                            $etypeName = 'Service';
                        } else {
                            $etypeName = $db_userdoc[$s]['eType'];
                        }
											?>
										
                    <li>
                    <form id="<?= $s ?>" class="upload_docform" method="post" action="" enctype="multipart/form-data">
									  <input type="hidden" name="iVehicleId1" value = "<?php echo $db_driver_vehicle[$i]['iDriverVehicleId']; ?>"/>
									  <input type="hidden" name="doc_name" value="<?php echo $db_userdoc[$s]['doc_name']; ?>">
										<input type="hidden" name="doc_id" value="<?php echo $db_userdoc[$s]['masterid']; ?>">
										<input type="hidden" name="doc_path2" value="<?php echo $tconfig["tsite_upload_vehicle_doc"];?>">  
                    <input type="hidden" name="accordion" value="accordion-click-<?php echo $i;?>">
                    <h4> 
                      <?php echo $db_userdoc[$s]['doc_name']; ?>
                      <?php if($APP_TYPE == 'Ride-Delivery' || $APP_TYPE == 'Ride-Delivery-UberX'){ ?>
                        <div style="font-size: 10px;font-weight: normal;color:#362f2d">(For <?= $etypeName; ?>)</div>
                      <?php } ?>   
                    </h4>
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-preview thumbnail" style="width: 200px; height: 150px; ">
													 <?php if ($db_userdoc[$s]['doc_file'] != '') { ?>
															<?php
															$file_ext = $generalobj->file_ext($db_userdoc[$s]['doc_file']);
															if ($file_ext == 'is_image') {
																?>
																<img src = "<?= $tconfig["tsite_upload_vehicle_doc_panel"] . '/' .$db_driver_vehicle[$i]['iDriverVehicleId']. '/' . $db_userdoc[$s]['doc_file']; ?>" style="width:200px;cursor:pointer;" alt ="<?php echo $db_userdoc[$s]['doc_name'];?>" data-toggle="modal" data-target="#myModallicence"/>
															<?php } else { ?>
																<a href="<?= $tconfig["tsite_upload_vehicle_doc_panel"] . '/' .$db_driver_vehicle[$i]['iDriverVehicleId'].'/' . $db_userdoc[$s]['doc_file']; ?>" target="_blank"><?php echo $db_userdoc[$s]['doc_name']; ?></a>
															<?php } ?>
															<?php
														} else {
															echo $db_userdoc[$s]['doc_name'] . ' not found';
														}
														?>
                            </b> 
                            </div><br>
													
													 <div class="select-image1">
                                <span class="btn btn-file btn-success">
                                <span class="fileupload-new"><?php echo $db_userdoc[$s]['doc_name']; ?></span>
                                <input type="file"  name="file" <?if($db_userdoc[$s]['doc_file'] == ""){?>required<?}?> class="ins" accept="image/*,.doc, .docx,.pdf" onChange="validate_fileextension(<?php echo $s;?>,this.value)"/>
                                </span>
                                <div class="fileerror error" style="font-weight: bold;"></div>
                                <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">X</a>
                            </div>
													<?php 
												
															if($db_userdoc[$s]['ex_status'] == 'yes'){?>
																 <div class="col-lg-13 exp-date">
																	<div class="input-group input-append date dp123">
																		<input class="form-control readonly" required type="text" name="dLicenceExp"  value="<?php echo isset($db_userdoc[$s]['ex_date']) ? $db_userdoc[$s]['ex_date'] : ''; ?>"  />
																		<span class="input-group-addon add-on"><i class="icon-calendar"></i></span>
																	</div>
																</div>
														<?php }?>

	                      <?php
//codeEdited for adding extra fields
$query_extra_fields="select * from ExtraFields where doc_masterid='".$db_userdoc[$s]['masterid']."'  and vFor='Document'";

    $db_extraFields = $obj->MySQLSelect($query_extra_fields);

 


                         if(trim($db_userdoc[$s]["IsType"])=="0" || $db_userdoc[$s]["IsType"]==0)
{
    $z=1;
foreach ($db_extraFields as $key => $value) {
    $isRequired=trim($value['IsRequired'])=="1"?"required":"";
   ?><br>
   <label><?=$value['vLabel'];?></label>
   <input type="text" value="<?=$db_userdoc[$s]['ExtraField_'.$z]?>"  name="extraField[]" id="extraField_<?=$z;?>" class="extraField form-control"  <?=$isRequired;?> />
   <?php
   $z++;
}
    
} 

?>
<?php

if (trim($db_userdoc[$s]["IsType"])=="1" || $db_userdoc[$s]["IsType"]==1) {

$dropdown_q="SELECT `ID`, `document_type_name` FROM `document_type_name` where `doc_masterid`='".$db_userdoc[$s]['masterid']."' and `vFor`='Document'";
    $dropdown=$obj->MySQLSelect($dropdown_q);
    $datafields="";
?>
<br>
           

<select class="form-control documentType"  id="documentType" name="documentType"  required>
    <option value=''>Select document</option>
    <?php

for ($z=0; $z <count($dropdown) ; $z++) { 
    $selected="";

$extraField_q="SELECT `ID`, `doc_masterid`, `vLabel`, `vFor`, `IsRequired`, `DocumentTypeId` FROM `ExtraFields` where doc_masterid='".$db_userdoc[$s]['masterid']."' and vFor='Document' and DocumentTypeId='".$dropdown[$z]['ID']."'";
$variable=$obj->MySQLSelect($extraField_q);

    $dropdownFields[$dropdown[$z]['ID']]=$variable;
    $n=1;
    foreach ($variable as $key => $value) {
      $isRequired=trim($value['IsRequired'])=="1"?"required":"";
      if (trim($dropdown[$z]['document_type_name'])==trim($db_userdoc[$s]["DocumentTypeName"])) {

    $datafields.='<br>
   <label>'.$value['vLabel'].'</label>
   <input type="text" value="'.$db_userdoc[$s]['ExtraField_'.$n].'"  name="extraField[]" id="extraField_'.$n.'" class="extraField form-control"  '.$isRequired.' />';
  }
   $n++;

    }

    if (trim($dropdown[$z]['document_type_name'])==trim($db_userdoc[$s]["DocumentTypeName"])) {
        $selected="selected";
    }
echo "<option data-id='".$dropdown[$z]['ID']."' value='".$dropdown[$z]['document_type_name']."'  $selected>".$dropdown[$z]['document_type_name']."</option>";

}

?>
</select>
<div id="dropdownExtrafieldsContainer" class="dropdownExtrafieldsContainer">
  <?php echo $datafields; ?>


</div>


<?php 



}
 ?>

                                                </div>
												  <abbr><input type="submit" name="Submit" class="save-document" value="<?=$langage_lbl['LBL_Save_Documents']; ?>"></abbr> 
                                           </form>
                                            </li>
										<?php }?>
                                      </ul>
									  
                                    </div>
                                </div>
                                <!--end .accordion-section-content-->
                            </div>
                        <!--end .accordion-section-->
                        <?php
                                }
                            }
                        ?>
                    </div>
                </div>
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
    <script type="text/javascript" src="assets/js/accordion.js"></script>
    <script src="assets/plugins/jasny/js/bootstrap-fileupload.js"></script>
	<link rel="stylesheet" type="text/css" media="screen" href="admin/css/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
	<script type="text/javascript" src="admin/js/moment.min.js"></script>
	<script type="text/javascript" src="admin/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
      var delete_id,delete_driverid,vAction;

function getReasons(reason_for)
{
     $(".error-reason").text('');
        $(".error-reason-other").text('');

   $.ajax({
           type: "POST",
           url: 'getReasons.php',
           data: {"reason_for":reason_for}, 
           success: function(data)
           {
            data=JSON.parse(data);

               var html='<option value="">Select</option>';
               for(var i=0;i<data.length;i++)
               {
                html+='<option value="'+data[i].Reason+'">'+data[i].Reason+'</option>';
               }
               $("#deleting_reason").html(html);
                $("#deleting_reason_other").css("display","none");
   $("#deleting_reason").trigger("change");
           }
         });


}

        function confirm_delete(id, driverid,action)
        {
			//alert('sdf');
      var reason_for='';
            var tsite_url = '<?php echo $tconfig["tsite_url"]; ?>';
            if (id != '') {
               var confirm_ans;
               if (action.trim().toLowerCase()=="delete") {
                reason_for='Delete Vehicle';
                 confirm_ans= confirm('<?= addslashes($langage_lbl['LBL_DELETE_VEHICLE_CONFIRM_MSG']);?>');
               }
               else
               {
                 reason_for='Inactive Vehicle';
                                 confirm_ans= confirm('Are You sure You want to '+action+' Vehicle?');

               }
                 if (confirm_ans == true) {
                  delete_id=id;
                  delete_driverid=driverid;
                  vAction=action;
                  if (vAction.trim().toLowerCase()=='active') 
                  {
                     window.location.href = "vehicle.php?action="+vAction+"&id="+delete_id+"&driverid="+ delete_driverid
                  }
                  else
                  {
 getReasons(reason_for);
                $("#Reason").modal({backdrop: 'static', keyboard: false});
              }

                      //window.location.href = "vehicle.php?action=delete&id="+id+"&driverid="+ driverid;
                 }
                 }
            //document.getElementById(id).submit();
        }
		
		function del_veh_doc(id,type,img){
			ans=confirm('<?= addslashes($langage_lbl['LBL_CONFIRM_DELETE_DOC']);?>');
			if(ans == true)
			{

				var request=$.ajax({
						type: "POST",
						url: "ajax_delete_docimage.php",
						data: "veh_id="+id+"&type="+type+"&img="+img+"&doc_type=veh_doc",
						success:function(data){
							var url      = window.location.href; 
							$("#"+type+"_"+id).load(url+" #"+type+"_"+id);
						}
					});
			}else{
				return false;
			}
		}
		
		$(function () {
			newDate = new Date('Y-M-D');
			$('.dp123').datetimepicker({
				format: 'YYYY-MM-DD',
				minDate: moment(),
				ignoreReadonly: true,
        keepInvalid:true
			});
		});

    function validate_fileextension(formid,filename) {
        var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'doc' , 'docx' , 'pdf'];
        if ($.inArray(filename.split('.').pop().toLowerCase(), fileExtension) == -1) {
            $( "#"+formid+ " .fileerror" ).html( "Only formats are allowed : "+fileExtension.join(', ') );
            $('.save-document').prop("disabled", true);
            return false;
        } else {
            $('.save-document').prop("disabled", false);
            $( "#"+formid+ " .fileerror" ).html("");
        }
    } 

$(document).ready(function(){

  $("#deleting_reason").change(function(){

      var reason=$("#deleting_reason option:selected").val();
      if (reason.trim().toLowerCase()=="other") {
$("#deleting_reason_other").css("display","block");

      }
      else
      {
        $("#deleting_reason_other").css("display","none");
      }
    });

$("#submit_reason").click(function(){
var isValid=true;
     var reason=$("#deleting_reason option:selected").val();
      $(".error-reason").text('');
        $(".error-reason-other").text('');
     if (reason.trim()=='') {
      $(".error-reason").text('*Please select reason');
      isValid=false;
     }
     if ($("#deleting_reason_other").is(":visible")) {
if ($("#deleting_reason_other").val().trim()=='') {
       $(".error-reason-other").text('*Please enter reason');
        isValid=false;
     }
      
     }

    if (reason.trim().toLowerCase()=="other") {
reason=$("#deleting_reason_other").val();

      }
     if (isValid) { 
  window.location.href = "vehicle.php?action="+vAction+"&id="+delete_id+"&driverid="+ delete_driverid+"&reason="+reason;
}
});



var dropdownFields=<? echo json_encode($dropdownFields);?>;
var db_user=<? echo json_encode($db_userdoc);?>;

$(".documentType").change(function(){

var thisObj=$(this);
for (var j = 0; j < db_user.length; j++) {
  

var data=dropdownFields[$(this).find('option:selected').data("id")];
var html="";
for (var i = 0; i < data.length; i++) {
  var db_val="";
    var isRequired=data[i]['IsRequired'].trim()=="1"?"required":"";
  if (typeof db_user[j]!== "undefined") 
//db_val=db_user[j]['ExtraField_'+(i+1)];

  html+=' <br> <label>'+data[i]['vLabel']+'</label><input type="text" value=""  name="extraField[]" class="extraField form-control" '+isRequired+' />';

}
thisObj.next(".dropdownExtrafieldsContainer").html(html);
}

});

//$(".documentType").trigger('change');
   $(".readonly").keydown(function(e){
        e.preventDefault();
    });
$("#<?=$_POST['accordion']?>").trigger('click');

    });





  
</script>







<!-- Modal -->
  <div class="modal fade" id="Reason" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Reason</h4>
        </div>
        <div class="modal-body">
<select id="deleting_reason" class="form-control">


</select>
<span class="error error-reason"></span>
<br>
          <textarea placeholder="Reason" id="deleting_reason_other" class="form-control" style="display: none;"></textarea>
          <span class="error error-reason-other"></span>

<br>
          <input type="button" class="btn btn-primary" name="" id="submit_reason" value="Submit">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
</div>
</div>




</body>
</html>

<style type="text/css">
.save-document
{
margin:auto !important;
}
</style>