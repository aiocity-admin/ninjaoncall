<?php
include_once('../common.php');
include_once(TPATH_CLASS.'/class.general.php');
include_once(TPATH_CLASS.'/configuration.php');
include_once('../generalFunctions.php');

date_default_timezone_set('Asia/Manila');


function send_notification_fun($registation_ids_new,$deviceTokens_arr_ios,$message,$userType) {
    $message = stripslashes($message);
		$alertMsg = $message;

		// echo "registation_ids_new => <pre>";
		// print_r($registation_ids_new);
		// echo "deviceTokens_arr_ios => <pre>";
		// print_r($deviceTokens_arr_ios);
		// exit;
		if(!empty($registation_ids_new)){
			$newArr = array();
			$newArr = array_chunk($registation_ids_new, 999);
			foreach($newArr as $newRegistration_ids){
				$Rmessage         = array("message" => $message);
				$result = send_notification($newRegistration_ids, $Rmessage,0);
			}
		}
		if(!empty($deviceTokens_arr_ios)){
			if($userType == "rider") {
				$result = sendApplePushNotification(0,$deviceTokens_arr_ios,$message,$alertMsg,0,'admin');
			}else {
				$result = sendApplePushNotification(1,$deviceTokens_arr_ios,$message,$alertMsg,0,'admin');
			}
		}
		$_SESSION['success'] = '1';
		$_SESSION['var_msg'] = 'Push Notification send successfully.';
		header("location:conference_notifications.php");
		exit;
}

if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");
     $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();

$sql = "select concat(vName,' ',vLastName) as DriverName,iDriverId from register_driver where eStatus = 'Active' order by vName";
$db_drv_list = $obj->MySQLSelect($sql);

$sql = "select concat(vName,' ',vLastName) as riderName,iUserId from register_user where eStatus = 'Active' order by vName";
$db_rdr_list = $obj->MySQLSelect($sql);

$sql_drv = "select concat(vName,' ',vLastName) as DriverName,iDriverId from register_driver where eStatus = 'Active' AND `eLogout` = 'No' order by vName";
$db_login_drv_list = $obj->MySQLSelect($sql_drv);

$sql_rdr = "select concat(vName,' ',vLastName) as riderName,iUserId from register_user where eStatus = 'Active' AND `eLogout` = 'No' order by vName";
$db_login_rdr_list = $obj->MySQLSelect($sql_rdr);

$sql_inactive_drv = "select concat(vName,' ',vLastName) as DriverName,iDriverId from register_driver where eStatus = 'Inactive' order by vName";
$db_inactive_drv_list = $obj->MySQLSelect($sql_inactive_drv);

$sql_inactive_rdr = "select concat(vName,' ',vLastName) as riderName,iUserId from register_user where eStatus = 'Inactive' order by vName";
$db_inactive_rdr_list = $obj->MySQLSelect($sql_inactive_rdr);

$tbl_name = 'pushnotification_log';
$script = 'Push Notification';

// set all variables with either post (when submit) either blank (when insert)
$eUserType = 'rider';//isset($_POST['eUserType']) ? $_POST['eUserType'] : '';
$iDriverId = isset($_POST['iDriverId']) ? $_POST['iDriverId'] : '';
$iRiderId = isset($_POST['iRiderId']) ? $_POST['iRiderId'] : '';
$tMessage = isset($_POST['tMessage']) ? $_POST['tMessage'] : '';
$dDate = date("Y-m-d H:i:s");
$Notification_Date = date("Y-m-d h:iA");
$ipAddress = $_SERVER['REMOTE_HOST'];

if (isset($_POST['submit'])) {
     // echo "<pre>"; print_r($_REQUEST); die;
		if(SITE_TYPE =='Demo'){
			$_SESSION['success'] = 3;
			$_SESSION['var_msg'] = "Sending push notification has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.";
			header("Location:conference_notifications.php");exit;
		}
		
			$set_table = 'register_user';
			$set_userId = 'iUserId';
			if(!empty($iRiderId)){
				$userArr = $iRiderId;
			}else {
				foreach($db_rdr_list as $dbr) {
					$userArr[] = $dbr['iUserId'];
				}
			}
		
		$deviceTokens_arr_ios = array();
		$registation_ids_new = array();
		// echo "<pre>"; print_r($userArr); die;
		foreach($userArr as $usAr){
			//send_notification_fun($usAr);
		/*	$q = "INSERT INTO ";
			$query = $q . " `" . $tbl_name . "` SET
			`eUserType` = '" . $eUserType . "',
			`iUserId` = '" . $usAr . "',
			`tMessage` = '" . $tMessage . "',
			`dDateTime` = '" . $dDate . "',
			`IP_ADDRESS` = '" . $ipAddress . "'";
			$responce = $obj->sql_query($query);
			
*/
	$query =   "insert into `conference_nortifications_log` SET
		
			`UserId` = '" . $usAr . "',
			`Message` = '" . $tMessage . "',
			`Notification_Date` = '" . $dDate . "'";

		$responce= $obj->sql_query($query);
		

			$gcmIds = get_value($set_table, 'eDeviceType,iGcmRegId', $set_userId,$usAr);
			//print_r($gcmIds);die;
			if($gcmIds[0]['iGcmRegId'] != '' && strlen($gcmIds[0]['iGcmRegId']) > 15){
				if($gcmIds[0]['eDeviceType'] == 'Android') {
					array_push($registation_ids_new, $gcmIds[0]['iGcmRegId']);
				}else {
					array_push($deviceTokens_arr_ios, $gcmIds[0]['iGcmRegId']);
				}
			}
		}
		//$tMessage=str_replace('\r\n','\n',$tMessage);
		
		$tMessage = trim(stripslashes($obj->SqlEscapeString($tMessage)));
		$tMessage = str_replace(array('\r', '\n'), array(chr(13), chr(10)), $tMessage);

		$tMessage="Message sent at: $Notification_Date (PHT)\n\n".$tMessage;
		// echo "<br>";
		// $tMessage = nl2br($tMessage,false); die;
		send_notification_fun($registation_ids_new,$deviceTokens_arr_ios,$tMessage,$eUserType);
}
/*add for test*/
if($_GET['send_notification_fun'] == 'test'){
	$tMessage = $_GET['tMessage'];
	$eUserType = $_GET['eUserType'];
	$deviceTokens_arr_ios = $_GET['deviceTokens_arr_ios'];
	$message = stripslashes($tMessage);
	if($eUserType == "rider") {
		$result = sendApplePushNotification(0,$deviceTokens_arr_ios,$message,$tMessage,0,'admin');
	}else {
		$result = sendApplePushNotification(1,$deviceTokens_arr_ios,$message,$tMessage,0,'admin');
	}
}
?>
<!DOCTYPE html>
<html lang="en">
     <head>
          <meta charset="UTF-8" />
          <title><?=$SITE_NAME?> | Send Push-Notification </title>
          <meta content="width=device-width, initial-scale=1.0" name="viewport" />
          <?
          include_once('global_files.php');
          ?>
          <!-- On OFF switch -->
          <link href="../assets/css/jquery-ui.css" rel="stylesheet" />
          <link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
<style type="text/css">
	.inner{
		min-height: 500px;
	}
</style>
<script>
	$(document).ready(function(){
		<?php if(isset($_SESSION["isConferenceAdmin"])) {
			if (trim($_SESSION["isConferenceAdmin"])!="") {
			?>

			var li="<a href='conference_notifications.php' style='color:white'><i class='fa fa-globe'></i> Send Conference Push-Notification</a>";

		$(".main-sidebar").html(li);
<?php }}?>
		
$("#eUserType").change(function()
{

var ConferenceAttendeeRole=$("#eUserType option:selected").val();

$.ajax({
  type: 'POST',
  url: "GetConferenceUsers.php",
  data: {"ConferenceAttendeeRole":ConferenceAttendeeRole.trim()}, 
  success: function(data) { 

  	data=JSON.parse(data);
  	  	var optionList="";
  	for (var i =0;i< data.length ; i++) {
 
  	
optionList+="<option value='"+data[i].UserId+"'>"+data[i].First_Name_exactly_shown_in_the_passport+" "+data[i].Last_Name_exactly_shown_in_the_passport+"</option>";
  }
$("#iRiderId").html(optionList);

//$("#iRiderId option").prop("selected",true);
   },
});

});

	});
</script>
<!--<style type="text/css">
.main-sidebar
	{

		display: none;
	}	

</style>-->
     </head>
     <!-- END  HEAD-->
     <!-- BEGIN BODY-->
     <body class="padTop53 " >

          <!-- MAIN WRAPPER -->
          <div id="wrap">
               <?
               include_once('header.php');
              // include_once('left_menu.php');
               ?>
               <!--PAGE CONTENT -->
               <div id="content">
                    <div class="inner">
                         <div class="row">
                              <div class="col-lg-12">
                                   <h2>Send Push-Notification </h2>
                                   <!--a href="driver_subscriptions.php" class="back_link">
                                        <input type="button" value="Back to Listing" class="add-btn">
                                   </a-->
                              </div>
                         </div>
                         <hr />
                         <div class="body-div">
                              <div class="form-group">
									<?php include('valid_msg.php'); ?>
									<form id="_notification_form" name="_notification_form" method="post" action="javascript:void(0);" >
                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Select User Type<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <select class="form-control" name = 'eUserType' id="eUserType" required>
                                                  		<option value="">Select</option>
                                                  		 	<option value="All">All</option>
                                                  <option value="Observe"> Observer</option> 
<option value="WOSM or Guest"> WOSM/Guest</option> 
<option value="Youth Participant"> Youth Participant</option> 
<option value="Delegate"> Delegate</option> 
<option value="RSC"> RSC</option> 
<option value="Chief Delegate"> Chief Delegate</option> 
<option value="WSC"> WSC</option> 
<option value="IFSR"> IFSR</option> 
<option value="Accompanying Person"> Accompanying Person</option> 
<option value="WOSM"> WOSM</option> 
<option value="IC"> IC</option> 
<option value="BSA Chairman"> BSA Chairman</option> 
<option value="BSA National Director"> BSA National Director</option> 
<option value="Regional Director"> Regional Director</option> 
<option value="WSC Vice Chairperson"> WSC Vice Chairperson</option> 
<option value="Commissioner"> Commissioner</option> 
<option value="Moot Organizing Team"> Moot Organizing Team</option> 
<option value="WAGGGS"> WAGGGS</option> 
<option value="Delegate/Youth Participant"> Delegate/Youth Participant</option> 
<option value="Scout Leader"> Scout Leader</option> 
<option value="Guest"> Guest</option> 
<option value="IAF"> IAF</option> 
<option value="24th WSJ"> 24th WSJ </option> 
<option value="WSC Member"> WSC Member</option> 
<option value="WOSM Youth Advisor"> WOSM Youth Advisor</option> 
<option value="Chief Commissioner"> Chief Commissioner</option> 
<option value="Ambassador"> Ambassador</option> 
<option value="Foundation Coor"> Foundation Coor</option> 
<option value="APR Sub-Committee"> APR Sub-Committee</option> 
<option value="Interim Coordinator"> Interim Coordinator</option> 
<option value="Invitee"> Invitee </option> 
												
												</select>
                                             </div>
                                        </div>
                                     
                                        
                                        <div class="row set-dd-css" id="logindriverRw">
                                             <div class="col-lg-12">
                                                  <label>Select users<span class="red"> </span></label>
                                             </div>
                                             <div class="col-lg-6">
                                             <select class="form-control filter-by-text" name = 'iRiderId[]' id="iRiderId" multiple data-text="All <?php echo $langage_lbl_admin['LBL_RIDERS_ADMIN'] ?>">
												
												
												</select>
                                             </div>
                                        </div>
                              
                                      
                                
                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Message<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <textarea name="tMessage" class="form-control" id="tMessage" rows="4" required maxlength="240" ></textarea>
                                             </div>
                                        </div>
                                       
                                        <div class="row">
											 <div class="col-lg-12">
												<input type="submit" class="btn btn-default" name="submit" id="submit" onClick="submit_form();" value="Send Notification" >
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

	<?php include_once('footer.php'); ?>
	<link rel="stylesheet" href="../assets/plugins/datepicker/css/datepicker.css" />
	<link rel="stylesheet" href="css/select2/select2.min.css" type="text/css" >
	<style>
	.error {
		color:red;
		font-weight: normal;
	}
	.select2-container--default .select2-search--inline .select2-search__field{
		width:500px !important;
	}
	</style>
	<script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
	<script src="../assets/plugins/datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="js/plugins/select2.min.js"></script>
	<script>
		function submit_form(){
			var joinTxt = '';
			if( $("#_notification_form").valid() ) {
				
					if($("#iRiderId").val() == '' || $("#iRiderId").val() == null){
						joinTxt = 'All <?php echo $langage_lbl_admin['LBL_RIDERS_ADMIN'] ?>';
					}else {
						var len = $('#iRiderId option:selected').length;
						joinTxt = 'Selected '+len+' <?php echo $langage_lbl_admin['LBL_RIDERS_ADMIN'] ?>(s)';
					}
			
				
				if(confirm("Confirm to send push notification to "+joinTxt+"?")){
					$("#_notification_form").attr('action','');
					$("#_notification_form").submit();
				}else {
					
				}
			}
		}
		
		
		$(function () {
		  $("select.filter-by-text").each(function(){
			  $(this).select2({
					placeholder: $(this).attr('data-text'),
					allowClear: true
			  }); //theme: 'classic'
			});
		});
		
		/*function showUsers(userType) {
			if(userType == 'driver'){
				$("#driverRw").show();
				$("#riderRw").hide();
				$("#logindriverRw").hide();
				$("#loginriderRw").hide();
				$("#inactive_driverRw").hide();
				$("#inactive_riderRw").hide();
			} else if(userType == 'rider') {
				$("#riderRw").show();
				$("#driverRw").hide();
				$("#logindriverRw").hide();
				$("#loginriderRw").hide();
				$("#inactive_driverRw").hide();
				$("#inactive_riderRw").hide();
			} else if(userType == 'logged_driver') {
				$("#logindriverRw").show();
				$("#riderRw").hide();
				$("#driverRw").hide();
				$("#loginriderRw").hide();
				$("#inactive_driverRw").hide();
				$("#inactive_riderRw").hide();
			} else if(userType == 'logged_rider') {
				$("#loginriderRw").show();
				$("#riderRw").hide();
				$("#driverRw").hide();
				$("#logindriverRw").hide();
				$("#inactive_driverRw").hide();
				$("#inactive_riderRw").hide();
			} else if(userType == 'inactive_driver') {
				$("#inactive_driverRw").show();
				$("#riderRw").hide();
				$("#driverRw").hide();
				$("#logindriverRw").hide();
				$("#loginriderRw").hide();
				$("#inactive_riderRw").hide();
			} else if(userType == 'inactive_rider') {
				$("#inactive_riderRw").show();
				$("#loginriderRw").hide();
				$("#riderRw").hide();
				$("#driverRw").hide();
				$("#logindriverRw").hide();
				$("#inactive_driverRw").hide();
			}
		}*/
	</script>
</body>
<!-- END BODY-->
</html>
