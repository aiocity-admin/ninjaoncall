<?php
	include_once('common.php');
	include_once(TPATH_CLASS.'/class.general.php');
    include_once(TPATH_CLASS.'/configuration.php');
    include_once('generalFunctions.php');
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


	$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
	$eStatus_check = isset($_POST['eStatus']) ? $_POST['eStatus'] : 'off';
	$vCarType = isset($_POST['vCarType']) ? $_POST['vCarType'] : '';
	$eStatus = ($eStatus_check == 'on') ? 'Active' : 'Inactive';
	$eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : 'UberX';	  

		$iCompanyId = $_SESSION['sess_iCompanyId'];
	   
		      	if(isset($_REQUEST['submit']))
		      	{
		      		$ActivedServices="";
		      		$DeactivedServices="";

$sql="select iVehicleCategoryId from  driver_registered_service where iDriverId='$iDriverId' and  Status='1'";
$services=$obj->MySQLSelect($sql);
$services_array=array();
for ($i=0; $i <count($services) ; $i++) { 
$services_array[$i]=$services[$i]['iVehicleCategoryId'];
}

		      		$sql="update driver_registered_service set Status='0' where iDriverId='$iDriverId'";
                    $obj->MySQLSelect($sql);


$reverse_diff = array_diff($vCarType, $services_array); 
 $reverse_diff2 = array_diff($services_array,$vCarType); 

 $ActivedServices=implode(', ', $reverse_diff);

$DeactivedServices=implode(', ', $reverse_diff2);

if(count($services_array)>0 && $vCarType ==""  )
	$DeactivedServices=implode(', ', $services_array);



for ($i=0;$i<count($vCarType);$i++) {
$sql="update driver_registered_service set Status='1' where iDriverId='$iDriverId' and iVehicleCategoryId='$vCarType[$i]'";
$obj->MySQLSelect($sql); 
//$ActivedServices.=$vCarType[$i].",";
 $success=1;

}
		$email=$generalobj->get_value('register_driver', 'vEmail', 'iDriverId',$iDriverId,'','true');

$MESSAGE="";
if($ActivedServices!="")
$MESSAGE.= "Service(s) $ActivedServices has been Activated.";
if($DeactivedServices!="")
$MESSAGE.= "Service(s) $DeactivedServices has been deactivated.";
                $maildata['EMAIL'] = $email;
				$maildata['SERVICES'] = $ActivedServices;
				$maildata['MESSAGE'] = $MESSAGE;
   $generalobj->send_email_user("SERVICES_UPDATED_BY_COMPANY",$maildata); 


  $sql = "SELECT iGcmRegId,eDeviceType,iDriverId,vLang,tSessionId,iAppVersion FROM register_driver WHERE iDriverId='$iDriverId'";
        $result = $obj->MySQLSelect($sql);
$registation_ids_new=array();
$deviceTokens_arr_ios=array();

$alertMsg_db ="";
if($ActivedServices!="")
$alertMsg_db.= "Service(s) $ActivedServices has been Activated.";
if($DeactivedServices!="")
$alertMsg_db.= "Service(s) $DeactivedServices has been deactivated.";

             foreach ($result as $item) {
				if($item['eDeviceType'] == "Android"){
					array_push($registation_ids_new, $item['iGcmRegId']);
					}else{
					array_push($deviceTokens_arr_ios, $item['iGcmRegId']);
				}				
					
				
				$tSessionId= $item['tSessionId'];
				
				$final_message['tSessionId'] = $tSessionId;
				$final_message['vTitle'] = $alertMsg_db;
				$msg_encode  = json_encode($final_message,JSON_UNESCAPED_UNICODE);		
			}
$message = $alertMsg_db;
if(count($registation_ids_new) > 0){
				$Rmessage = array("message" => $message);
				
				$result = send_notification($registation_ids_new, $Rmessage,0);
				
			}
			if(count($deviceTokens_arr_ios) > 0){

				sendApplePushNotification(1,$deviceTokens_arr_ios,$msg_encode,$alertMsg,0);
			}
	      

}

//$sql="SELECT vcp.vCategory_EN FROM `vehicle_type` vt join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId join (SELECT iVehicleCategoryId,vCategory_EN FROM vehicle_category where iParentId='0' ) vcp on vcp.iVehicleCategoryId=vc.iParentId join company_services as cs on vt.iVehicleTypeId=cs.ServiceId where cs.CompanyId='$iCompanyId' GROUP by vCategory_EN";
$sql="SELECT iVehicleCategoryId as vCategory_EN FROM `driver_registered_service` where iDriverId='$iDriverId'";

	
$categories = $obj->MySQLSelect($sql);



$sql="SELECT iVehicleCategoryId FROM `driver_registered_service` where iDriverId='$iDriverId' and Status='1'";
//echo $sql;
$data = $obj->MySQLSelect($sql);
$providerCategories= array();
for ($i=0; $i <count($data) ; $i++) { 
$providerCategories[$i]=$data[$i]['iVehicleCategoryId'];
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
		      	<h2 class="header-page trip-detail driver-detail1">Services				
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

                                <!--   <fieldset>
                                  	<li>
										<b>Ride<br/>
										<div style="font-size: 12px;"></div></b>
										<div class="make-switch" data-on="success" data-off="warning">
											<input type="checkbox" <? if($ePricetype == "Provider"){ ?>onchange="check_box_value(this.value);" <? } ?> id="vCarType1_Ride" class="chk" name="vCarType[]" <?php if(in_array('Ride',$providerCategories)){?>checked <?php } ?> value="Ride" />
										</div>
									
									</li>
								</fieldset> 


								<fieldset>
								
									<li>
										<b>Delivery<br/>
										<div style="font-size: 12px;"></div></b>
										<div class="make-switch" data-on="success" data-off="warning">
											<input type="checkbox" <? if($ePricetype == "Provider"){ ?>onchange="check_box_value(this.value);" <? } ?> id="vCarType1_Delivery" class="chk" name="vCarType[]" <?php if(in_array('Delivery',$providerCategories)){?>checked <?php } ?> value="Delivery" />
										</div>
									
									</li>
								</fieldset> 	 -->

								<?php

								for ($i=0;$i<count($categories);$i++) {
									?>
<fieldset>
								
									<li>
										<b><?=$categories[$i]['vCategory_EN'];?><br/>
										<div style="font-size: 12px;"></div></b>
										<div class="make-switch" data-on="success" data-off="warning">
											<input type="checkbox" <? if($ePricetype == "Provider"){ ?>onchange="check_box_value(this.value);" <? } ?> id="vCarType1_Delivery" class="chk" name="vCarType[]" <?php if(in_array($categories[$i]['vCategory_EN'],$providerCategories)){?>checked <?php } ?> value="<?=$categories[$i]['vCategory_EN'];?>" />
										</div>
									
									</li>
								</fieldset> 	

								 	<?php
								 }

								?>	
		      			
							</ul>
						<strong>	
 <input type="submit" name="submit" class="save-vehicle" id="submit" /></strong>
		      				
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
