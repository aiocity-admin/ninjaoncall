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

        $reset = isset($_REQUEST['reset']) ? $_REQUEST['reset'] : '';

?>
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="UTF-8" />
		<title>Admin | Login Page</title>
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
				</ul>
			</div>
		</div>
		<!-- PAGE CONTENT -->
		<div class="container animated fadeInDown">
			<div class="text-center"> <img src="../assets/img/<?php echo $logo;?>" id="Admin" alt=" Admin" /> </div>

			<div class="tab-content ">
				<div id="login" class="tab-pane active">	

<? if($reset!="")
{
	?>
<p style="padding:5px 0;" class="btn-block btn btn-rect btn-success" id="reset" ><?=$reset;?>   <a href="index.php" class="close" aria-label="close">x</a>
</p>
<?
}
?>

					<p style="display:none; padding:5px 0;" class="btn-block btn btn-rect btn-success" id="success" ></p>
					<p style="display:none; padding:5px 0;" class="btn-block btn btn-rect btn-danger text-muted text-center" id="errmsg"></p>
					<!--
						<form action="" class="form-signin" method = "post" id="login_box" onSubmit="return chkValid();">
						<p class="head_login_005">Login</p>
						<span class="glyphicon glyphicon-envelope form-control-feedback"></span> 
						<input type="text" placeholder="Email Address" class="form-control" name="vEmail" id="vEmail" required />
						<span class="glyphicon glyphicon-lock form-control-feedback"></span> 
						<input type="password" placeholder="Password" class="form-control" name="vPassword" id="vPassword" required />
						<input type="submit" class="btn text-muted text-center btn-default" value="SIGN IN"/>
						<br>
					</form>-->
					<div class="admin-home-tab">
						<ul class="nav nav-tabs">
							<li class="active" onClick="setCredentials('1', '<?=SITE_TYPE;?>');passLoginid('super001','1');"><a data-toggle="tab" href="#super001">Super Administrator</a></li>
							<li onClick="setCredentials('2', '<?=SITE_TYPE;?>');passLoginid('dispatch001','2');"><a data-toggle="tab" href="#dispatch001">Dispatcher Administrator</a></li>
							<li onClick="setCredentials('3', '<?=SITE_TYPE;?>');passLoginid('billing001','3');"><a data-toggle="tab" href="#billing001">Billing Administrator</a></li>

								<li onClick="setCredentials('4', '<?=SITE_TYPE;?>');passLoginid('conference001','4');"><a data-toggle="tab" href="#billing001">Conference Administrator</a></li>

									<li onClick="setCredentials('6', '<?=SITE_TYPE;?>');passLoginid('company001','6');"><a data-toggle="tab" href="#company001">Company Administrator</a></li>

							<!-- 	<li onClick="setCredentials('5', '<?=SITE_TYPE;?>');passLoginid('barangay001','5');"><a data-toggle="tab" href="#billing001">Barangay Administrator</a></li> -->
						</ul>
						<div class="tab-content clearfix custom-tab">
						  	<div class="tab-pane active" id="super001">
			          			<form action="" class="form-signin" method = "post" id="login_box" onSubmit="return chkValid();" style="margin:0 auto;border:0;">
									<br>
									<b><label for="email">Super Administrator E-mail</label>
										<input type="text" placeholder="Email Address" class="form-control" name="vEmail" id="vEmail" required Value="<?=(SITE_TYPE == "Demo")?'demo@demo.com':'';?>"/>
									</b>
									<b><label for="password">Password</label>
										<input type="password" placeholder="Password" class="form-control" name="vPassword" id="vPassword" required Value="<?=(SITE_TYPE == "Demo")?'123456':'';?>"/>
									</b>
									<input type="hidden" name="group_id" id="group_id" value="1"/>
									<input type="submit" class="btn text-muted text-center btn-default" value="SIGN IN" id="adminLogin"/>

																					<a href="#" id="forgot_a" style="float: right;">forgot password?</a>

									<br>
								</form>
							</div>
						</div>
						<? if(SITE_TYPE == "Demo") { ?>
							<div class="tab-content">
								<div id="super001" class="tab-pane active">
									<h3> Use below Detail for Demo Version</h3>
									
									<p><b>User Name:</b> demo@demo.com</p>
									<p><b>Password:</b> 123456 </p>
									<p>Super Administrator can manage whole system and other user's rights too.</p>
								</div>
								<div id="dispatch001" class="tab-pane">
									<h3> Use below Detail for Demo Version</h3>
									
									<p><b>User Name:</b> demo2@demo.com</p>
									<p><b>Password:</b> 123456 </p>
									<p>Call Center Panel / Administrator Dispatcher Panel / Manual Taxi Booking Panel. This panel allows one to see all taxi's on map using God's View. And book taxi's for customer's who would call to book a taxi.</p>
								</div>
								<div id="billing001" class="tab-pane">
									<h3> Use below Detail for Demo Version</h3>
									
									<p><b>User Name:</b> demo3@demo.com</p>
									<p><b>Password:</b> 123456 </p>
									<p>This use will have access to reports only. Will be used by Accounts Team to manage finances and see profits/revenue.</p>
								</div>
							</div>
						<? } ?>
						<div style="clear:both;"></div>
					</div>
					
				</div>
				<div id="forgot" class="tab-pane">
					<p style="display:none; padding:5px 0;" class="btn-block btn btn-rect btn-danger text-muted text-center" id="errmsg_email"></p>
					<form  class="form-signin" method="post" id="frmforget">
						<h3>Forgot Password?</h3><br>
						<input type="email"  required="required" placeholder="Your E-mail" name="femail"  class="form-control" id="femail"/>
						<br />
						<button class="btn text-muted text-center btn-success" type="button" onClick="forgotPass();">Recover Password</button>
						<a href="#" id="login_a" style="float: right;">Login</a>
					</form>
				</div>
			</div>
		</div>
		<!--END PAGE CONTENT -->
		<!-- PAGE LEVEL SCRIPTS -->
		<script src="../assets/plugins/jquery-2.0.3.min.js"></script>
		<script src="../assets/plugins/bootstrap/js/bootstrap.js"></script>
		<script src="../assets/js/login.js"></script>
		<script>
$("#adminLogin").click(function(e){

if($("#group_id").val().trim()=="4"||$("#group_id").val().trim()=="5")
{
	var vEmail =$("#vEmail").val();
var vPassword=$("#vPassword").val();
if (vEmail.trim()!=""&&vPassword.trim()!="") {
e.preventDefault();

var iGroupId=$("#group_id").val().trim();

//alert("Conference admin");
var url="AdminLogin.php";
	$("#errmsg").css("display","none");
               $("#errmsg").text("");
//$(".form-signin").attr('action', url);
//$(".form-signin").submit();

 $.ajax({
           type: "POST",
           url: url,
           data: {vPassword:vPassword,vEmail:vEmail,iGroupId:iGroupId}, 
           success: function(data)
           {
               //alert(data); 
               
if (data.trim()=="0") {
	$("#errmsg").css("display","");
               $("#errmsg").text("Invalid Email or Password");
           }

           if (data.trim()=="1") {
           	if (iGroupId=="4") {
               window.location.href="conference_notifications.php";
           }
        else if (iGroupId=="5") {


        	 window.location.href="barangay_providers.php";
        }
           }
           }
         });
         

}
}

});

$("#forgot_a").click(function(){

	$("#login").removeClass('active');
	$("#forgot").addClass('active');
});

$("#login_a").click(function(){

	$("#forgot").removeClass('active');
	$("#login").addClass('active');
});

			var testLink = '<?php echo $_SESSION['current_link']; ?>';
			function setCredentials(tpd, site_type) {
				if(site_type == "Demo") 
				{
					if(tpd == 2){
						$("#vEmail").val('demo2@demo.com');
						$("#vPassword").val('123456');
					}
					else if(tpd == 3) 
					{
						$("#vEmail").val('demo3@demo.com');
						$("#vPassword").val('123456');
					}
					else 
					{
						$("#vEmail").val('demo@demo.com');
						$("#vPassword").val('123456');
					}
				}
			}

			function passLoginid(tabid,login_group_id) {
				$(".custom-tab .tab-pane").attr('id',tabid);
				$("#group_id").val(login_group_id);
				if(tabid == "dispatch001") {
					$("label[for = email]").text("Dispatcher Administrator E-mail");   
				} else if(tabid == "billing001") {
					$("label[for = email]").text("Billing Administrator E-mail");  
				} 
           else if(tabid == "conference001") {
					$("label[for = email]").text("Conference Administrator E-mail");  
				} 
 else if(tabid == "barangay001") {
					$("label[for = email]").text("Barangay Administrator E-mail");  
				} 
				else if(tabid =="company001")
				{
					$("label[for = email]").text("Company Administrator E-mail");  

				}

				else {
					$("label[for = email]").text("Super Administrator E-mail"); 
				}
			}
			
			$('input').keyup(function(){
				$this = $(this);
				if($this.val().length == 1)
				{
					var x =  new RegExp("[\x00-\x80]+"); // is ascii
					
					var isAscii = x.test($this.val());
					if(isAscii)
					{
						$this.attr("dir", "ltr");
					}
					else
					{
						$this.attr("dir", "rtl");
					}
				}
				
			});
			function change_heading(heading, addClass, removeClass)
			{
				document.getElementById("login").innerHTML= heading;
				document.getElementById(addClass).className = "tab-pane";
				document.getElementById(removeClass).className = "tab-pane active";
			}
			function chkValid()
			{
				$("#reset").hide().text("");
				var id = document.getElementById("vEmail").value;
				var pass = document.getElementById("vPassword").value;


				if(id == '' || pass == '')
				{
					document.getElementById("errmsg").style.display = '';
					setTimeout(function() {document.getElementById('errmsg').style.display='none';},2000);
				}
				else
				{
					var request = $.ajax({
						type: "POST",
						url: 'ajax_login_action.php',
						data: $("#login_box").serialize(),
						
						success: function(dataHTml)
						{// alert(data);
							dataHTml = dataHTml.trim();
							if(dataHTml == 1){
								document.getElementById("errmsg").innerHTML = 'You are not active.Please contact administrator to activate your account.';
								document.getElementById("errmsg").style.display = '';
								return false;
							}
							else if(dataHTml == 2){
								
								document.getElementById("errmsg").style.display = 'none';
								var hdf_class=$("#hdf_class").val();
								if(hdf_class!="")
								{
									window.location = "admin/languages.php";
								}
								else
								{
									if(testLink == "") {
										testLink = "dashboard.php";
									}
									window.location = testLink;
								}
								return true; // success registration
							}
							else if(dataHTml == 3) {
								document.getElementById("errmsg").innerHTML = 'Invalid combination of username & Password';
								document.getElementById("errmsg").style.display = '';
								return false;
								
							}
							else {
								document.getElementById("errmsg").innerHTML = 'Invalid Email or Password';
								document.getElementById("errmsg").style.display = '';
								//setTimeout(function() {document.getElementById('errmsg1').style.display='none';},2000);
								return false;
							}
						}
					});
					
					request.fail(function(jqXHR, textStatus) {
						alert( "Request failed: " + textStatus );
					});
					
				}
				return false;
			}
			function forgotPass()
			{
				var id = document.getElementById("femail").value;
				if(id == '')
				{
					
					document.getElementById("errmsg_email").style.display = '';
					document.getElementById("errmsg_email").innerHTML = 'Please enter Email Address';
					return false;
				}
				else {
					
					var request = $.ajax({
						type: "POST",
						url: 'admin_forget_password.php',
						data: $("#frmforget").serialize(),
						beforeSend:function()
						{
							//alert(data);
						},
						success: function(data)
						{
							data=JSON.parse(data);
							if(data.errorCode == 1)
							{
								//document.getElementById("page_title").innerHTML= "Login";
								document.getElementById("forgot").className = "tab-pane";
								document.getElementById("login").className = "tab-pane active";
								document.getElementById("success").innerHTML = 'Reset Password link has been sent Successfully.';
								document.getElementById("success").style.display = '';
								return false;
							}
							else if(data.errorCode == 0)
							{
								document.getElementById("errmsg_email").innerHTML = 'Error in Sending Password.';
								document.getElementById("errmsg_email").style.display = '';
								return false;
								
							}
							else if(data.errorCode == 3)
							{
								document.getElementById("errmsg_email").innerHTML = 'Sorry ! The Email address you have entered is not found.';
								document.getElementById("errmsg_email").style.display = '';
								return false;
							}
							return false;
						}
					});
					request.fail(function(jqXHR, textStatus) {
						alert( "Request failed: " + textStatus );
						return false;
					});
					
					return false;
				}
				return false;
			}
			
		</script>
		<!--END PAGE LEVEL SCRIPTS -->
	</body>
	<!-- END BODY -->
</html>	
<style type="text/css">
	
	div#forgot {
    margin-left: 33% !important;
    /* margin: 25%; */
    width: 400px !important;
}

form#frmforget {
    width: 100%;
}
.text-center {
    margin: 20px auto;
      padding: 6px 12px; 
    max-width: 450px;
}
.admin-home-tab .nav-tabs li {
    width: 20%;
}
.admin-home-tab {
    max-width: 550px
    }
</style>