<?php
include_once('../common.php');

if(!isset($generalobjAdmin)){
	require_once(TPATH_CLASS."class.general_admin.php");
	$generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();

$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:'';
$success	= isset($_REQUEST['success'])?$_REQUEST['success']:0;
$action 	= ($id != '')?'Edit':'Add';

$tbl_name 	= 'masking_numbers';
$script 	= 'masking_numbers';

$sql = "select iCountryId,vCountry,vCountryCode from country ORDER BY  vCountry ASC ";
$db_country = $obj->MySQLSelect($sql);
// set all variables with either post (when submit) either blank (when insert)
$mask_number = isset($_POST['mask_number'])?$_POST['mask_number']:'';
$eStatus_check = isset($_POST['eStatus'])?$_POST['eStatus']:'off';
$eStatus = ($eStatus_check == 'on')?'Active':'Inactive';
$vCountry = isset($_POST['vCountry'])?$_POST['vCountry']:'';

$backlink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$previousLink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$adding_date= date("Y-m-d H:i:s");

	if(isset($_POST['submit'])) {
		if(SITE_TYPE=='Demo' && $id != "")
		{
			$_SESSION['success'] = '2';
			header("location:".$backlink);
			exit;
		}

		//Add Custom validation
		require_once("library/validation.class.php");
		$validobj = new validation();
		$validobj->add_fields($_POST['mask_number'], 'req', 'Masking number is required');		
		
		$error = $validobj->validate();
			
		if ($error) {
			$success = 3;
			$newError = $error;
		} else {

			$q = "INSERT INTO ";
			$where = '';

			if($id != '' ){
				$q = "UPDATE ";
				$where = " WHERE `masknum_id` = '".$id."'";
			}


			$query = $q ." `".$tbl_name."` SET
			`mask_number` = '".$mask_number."',
			`adding_date` = '".$adding_date."',
			`vCountry` = '".$vCountry."',
			`eStatus` = '".$eStatus."'"
			.$where;

			$obj->sql_query($query);
			$id = ($id != '')?$id:$obj->GetInsertId();
			
			if ($action == "Add") {
				$_SESSION['success'] = '1';
				$_SESSION['var_msg'] = 'Masking number Inserted Successfully.';
			} else {
				$_SESSION['success'] = '1';
				$_SESSION['var_msg'] = 'Masking numbermasknum_id Updated Successfully.';
			}
			header("location:".$backlink);
			
		}								
	}
		
	// for Edit
	if($id != '') {
		$sql = "SELECT * FROM ".$tbl_name." WHERE masknum_id = '".$id."'";
		$db_data = $obj->MySQLSelect($sql);

		$vLabel = $id;
		if(count($db_data) > 0) {
			foreach($db_data as $key => $value) {
				
				$mask_number	 = $value['mask_number'];
				$vCountry	 = $value['vCountry'];
				$eStatus = $value['eStatus'];
			}
		}
	}
	
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

	<!-- BEGIN HEAD-->
	<head>
		<meta charset="UTF-8" />
		<title>Admin | Masking Number <?=$action;?></title>
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<link href="css/bootstrap-select.css" rel="stylesheet" />
		<link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

		<? include_once('global_files.php');?>
		<!-- On OFF switch -->
		<link href="../assets/css/jquery-ui.css" rel="stylesheet" />
		<link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
	</head>
	<!-- END  HEAD-->
	<!-- BEGIN BODY-->
	<body class="padTop53 " >

		<!-- MAIN WRAPPER -->
		<div id="wrap">
			<? include_once('header.php'); ?>
			<? include_once('left_menu.php'); ?>
			<!--PAGE CONTENT -->
			<div id="content">
				<div class="inner">
					<div class="row">
						<div class="col-lg-12">
							<h2><?=$action;?> Masking Number</h2>
							<a href="masking_numbers.php" class="back_link">
								<input type="button" value="Back to Listing" class="add-btn">
							</a>
						</div>
					</div>
					<hr />
					<div class="body-div">
						<div class="form-group">
							<? if ($success == 2) {?>
                            <div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                "Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
                            </div><br/>
                            <?} ?>
                            <? if ($success == 3) {?>
                            <div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
								<?php print_r($error); ?>
                            </div><br/>
                            <?} ?>
							<form method="post" name="_masking_form" id="_masking_form" action="">
								<input type="hidden" name="previousLink" id="previousLink" value="<?php echo $previousLink; ?>"/>
								<input type="hidden" name="backlink" id="backlink" value="masking_numbers.php"/>
								<input type="hidden" name="id" value="<?=$id;?>"/>

								<div class="row">
									<div class="col-lg-12">
										<label>Masking Number<span class="red"> *</span></label>
									</div>
									<div class="col-lg-4">
										<input type="text" class="form-control" name="mask_number"  id="mask_number" value="<?=$mask_number;?>" placeholder="Masking Number" >
									</div>
								</div>
								
								<div class="row">
								 <div class="col-lg-12">
									  <label>Country <span class="red"> *</span></label>
								 </div>
								 <div class="col-lg-4">
									  <select class="form-control" name = 'vCountry' id="vCountry" required>
										   <option value="">Select</option>
										   <? for($i=0;$i<count($db_country);$i++){ ?>
										   <option value = "<?= $db_country[$i]['vCountryCode'] ?>" <?if($DEFAULT_COUNTRY_CODE_WEB == $db_country[$i]['vCountryCode'] && $action == 'Add') { ?> selected <?php } else if($vCountry==$db_country[$i]['vCountryCode']){?>selected<? } ?>><?= $db_country[$i]['vCountry'] ?></option>
										   <? } ?>
									  </select>
								 </div>
								</div>
								

								<div class="row">
									<div class="col-lg-12">
										<label>Status</label>
									</div>
									<div class="col-lg-6">
										<div class="make-switch" data-on="success" data-off="warning">
											<input type="checkbox" name="eStatus" <?=($id != '' && $eStatus == 'Inactive')?'':'checked';?>/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<input type="submit" class="btn btn-default" name="submit" id="submit" value="<?=$action;?> Masking Number">
										<input type="reset" value="Reset" class="btn btn-default">
										<!-- <a href="javascript:void(0);" onclick="reset_form('_masking_form');" class="btn btn-default">Reset</a> -->
                                        <a href="masking_numbers.php" class="btn btn-default back_link">Cancel</a>
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
		<? include_once('footer.php');?>

<script>
$(document).ready(function() {
	var referrer;
	if($("#previousLink").val() == "" ){ 
		referrer =  document.referrer;		
	}else { 
		referrer = $("#previousLink").val();
	}
	if(referrer == "") {
		referrer = "masking_numbers.php";
	}else { 
		$("#backlink").val(referrer);		
	}
	$(".back_link").attr('href',referrer); 	
});
</script>
		<script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
		<script src="js/bootstrap-select.js"></script>
	</body>
	<!-- END BODY-->
</html>
