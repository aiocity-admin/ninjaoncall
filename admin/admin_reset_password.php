<?php


  include_once('../common.php');
	

	if(!isset($generalobjAdmin)) {
		require_once(TPATH_CLASS."class.general_admin.php");
		$generalobjAdmin = new General_admin();
	}


	$generalobjAdmin->go_to_home();


if($host_system == 'cubetaxiplus') {
  $logo = "logo.png";
} else if($host_system == 'ufxforall') {
  $logo = "ufxforall-logo.png";
}else if($host_system == 'uberridedelivery4') {
  $logo = "ride-delivery-logo.png";
} else if($host_system == 'uberdelivery4') {
  $logo = "delivery-logo-only.png";
} else 
{
  $logo = "logo.png";
}

        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';

        $forgetPasswordToken = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
$msg="";

if (isset($_POST['Submit'])) {

        $vPassword = isset($_REQUEST['vPassword']) ? $_REQUEST['vPassword'] : '';
        $ConfirmPassword = isset($_REQUEST['ConfirmPassword']) ? $_REQUEST['ConfirmPassword'] : '';


        	 $iAdminId=$generalobj->decrypt($token);

        	 $forgetPasswordToken=$generalobj->decrypt($forgetPasswordToken);

        	 $sql = "SELECT CONCAT(`vFirstName`,' ', `vLastName`) as Name from administrators WHERE forgetPasswordToken = '".$forgetPasswordToken."' and eStatus != 'Deleted' and iAdminId='".$iAdminId."'";
		$db_login = $obj->MySQLSelect($sql);

        if(count($db_login)<=0)
        {
       $msg="Reset Password Link has been expired.";
        }	 

       else if ($vPassword==$ConfirmPassword) 
        {

        	 $vPassword= $generalobj->encrypt_bycrypt($vPassword);
        	$query="update administrators set vPassword='".$vPassword."',forgetPasswordToken=''  WHERE iAdminId='".$iAdminId."'";
                    $obj->sql_query($query);

        	header("Location:index.php?reset=Password  has been changed Successfully.");
        }else
        {
        	$msg="Password mis-match";
        }
}

?>
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="UTF-8" />
		<title>Admin | Reset Password </title>
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<link rel="icon" href="../favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" href="css/login.css" />
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" href="../assets/css/animate/animate.min.css" />
		<link rel="stylesheet" href="../assets/plugins/magic/magic.css" />
		<link rel="stylesheet" href="css/font-awesome.css" />
		<link rel="stylesheet" href="../assets/plugins/font-awesome-4.6.3/css/font-awesome.min.css" />
		
	</head>
	<!-- END HEAD -->
	<!-- BEGIN BODY -->
	<body class="nobg loginPage">
		<input type="hidden" name="hdf_class" id="hdf_class" value="<?php echo $_SESSION['edita'];?>">
		<div class="topNav">
			<div class="userNav">
				<ul>
					<li><a href="../index.php" title=""><i class="icon-reply"></i><span>Main website</span></a></li>
					<li><a href="../rider" title=""><i class="icon-user"></i><span><?=$langage_lbl_admin['LBL_RIDER']?> Login</span></a></li>
					<li><a href="../driver" title=""><i class="icon-comments"></i><span><?=$langage_lbl_admin['LBL_DRIVER']?> Login</span></a></li>
					<li><a href="index.php" title=""><i class="icon-reply"></i><span>Admin Login</span></a></li>

				</ul>
			</div>
		</div>
		<!-- PAGE CONTENT -->
		<div class="container animated fadeInDown">
			<div class="text-center"> <img src="../assets/img/<?php echo $logo;?>" id="Admin" alt=" Admin" /> </div>

			<div class="tab-content ">
				<div id="login" class="tab-pane active">				
					<p style="display:none; padding:5px 0;" class="btn-block btn btn-rect btn-success" id="success" ></p>
					<p style="display:none;padding:5px 0;" class="btn-block btn btn-rect btn-danger text-muted text-center errmsg" id="errmsg"></p>

					<? if($msg!="") { ?>
					<p style="padding:5px 0;" class="btn-block btn btn-rect btn-danger text-muted text-center errmsg" ><?=$msg;?></p>
					<? } ?>
					<div class="admin-home-tab">
						<ul class="nav nav-tabs">
							<li class="active" style="width: 100%;"><a data-toggle="tab" href="#super001">Reset Password</a></li>
							
						</ul>
						<div class="tab-content clearfix custom-tab">
						  	<div class="tab-pane active" id="super001">
			          			<form action="" class="form-signin" method = "post" id="login_box"  style="margin:0 auto;border:0;">
									<br>
									<input type="hidden" name="token" value="<?=$token;?>">
									<b><label for="email">Password</label>
										<input type="password" placeholder="Password" class="form-control" name="vPassword" id="vPassword" required />
									</b>
									<b><label for="password">Confirm Password</label>
										<input type="password" placeholder="Confirm Password" class="form-control" name="ConfirmPassword" id="ConfirmPassword" required />
									</b>
									<input type="submit" name="Submit" onclick="return validate();" class="btn text-muted text-center btn-default" value="Submit" id="adminLogin"/>

																				
									<br>
								</form>
							</div>
						</div>
						
					</div>
					
				</div>
			
			</div>
		</div>
		<!--END PAGE CONTENT -->
		<!-- PAGE LEVEL SCRIPTS -->
		<script src="../assets/plugins/jquery-2.0.3.min.js"></script>
		<script src="../assets/plugins/bootstrap/js/bootstrap.js"></script>
		<script src="../assets/js/login.js"></script>

		<!--END PAGE LEVEL SCRIPTS -->
	</body>
	<!-- END BODY -->

<script type="text/javascript">
	function validate() {
		var isValid=true;
		

var vPassword=$("#vPassword").val();
var ConfirmPassword=$("#ConfirmPassword").val();
	$(".errmsg").text("").hide();

if(vPassword.trim()=="")
{
	$("#errmsg").text("Please enter password.");

$("#errmsg").show();
isValid=false;
}
else if(ConfirmPassword.trim()=="")
{
		$("#errmsg").text("Please confirm password.");
isValid=false;

	$("#errmsg").show();

}else if(vPassword!=ConfirmPassword)
{
	isValid=false;

		$("#errmsg").show();
		$("#errmsg").text("Password mis-match.");

}


		return isValid;

	}
</script>

</html>	
