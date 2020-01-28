<?php
	
	include_once 'common.php';
	if (!isset($generalobjAdmin)) {
		require_once(TPATH_CLASS . "class.general_admin.php");
		$generalobjAdmin = new General_admin();
	
	}
		
	$_REQUEST['action']=(base64_decode(base64_decode(trim($_REQUEST['action'])))); 
	$_REQUEST['id'] = $generalobj->decrypt($_REQUEST['id']);	
	$action =  $_REQUEST['action']; 
	$userid = isset($_REQUEST['id'])?$_REQUEST['id']:''; 	
	$vPhone = isset($_POST['vPhone']) ? $_POST['vPhone'] : '';
	$vPhoneCode = isset($_POST['vPhoneCode']) ? $_POST['vPhoneCode'] : '';
	$vCountry = isset($_POST['vCountry']) ? $_POST['vCountry'] : '';

	$iRefUserId = isset($_POST['iRefUserId']) ? $_POST['iRefUserId'] : '';
	$eRefType = isset($_POST['eRefType']) ? $_POST['eRefType'] : '';

	$_SESSION['sess_iUserId'] =$userid;
	if($action == 'rider'){				
		$where = " iUserId = '$userid'";
		$table = "register_user";
	} else {
		$where = "iDriverId = '$userid'";
		$table = "register_driver";
	}
	$db_sql = "select * from $table WHERE $where";
	$db_user = $obj->MySQLSelect($db_sql);
	$vEmail = isset($_POST['vEmail']) ? $_POST['vEmail'] : $db_user[0]['vEmail'];		
	//echo ""; print_r($_SESSION);exit;
	//echo "<pre>"; print_r($_POST);exit;
	if (isset($_POST['submit'])) {			
		if($action == 'rider'){			
			$data['vPhone']= $vPhone;
			$data['vPhoneCode']= $vPhoneCode;
			$data['vCountry']= $vCountry;
			$data['vEmail']= $vEmail;

			$data['iRefUserId'] = $iRefUserId;
			$data['eRefType'] = $eRefType; 
			$data['dRefDate']=Date('Y-m-d H:i:s');

			$where = " iUserId = '$userid'";
			$table = "register_user";
			$_SESSION["sess_user"] = "rider";			
		}else{		
			$data['vPhone']= $vPhone;
			$data['vCode']= $vPhoneCode;
			$data['vCountry']= $vCountry;
			$data['vEmail']= $vEmail;

			$data['iRefUserId'] = $iRefUserId;
			$data['eRefType'] = $eRefType; 
			$data['dRefDate']=Date('Y-m-d H:i:s');

			$where = "iDriverId = '$userid'";
			$table = "register_driver";
			$_SESSION["sess_user"] = "driver";
		}			
		$id = $obj->MySQLQueryPerform($table,$data,'update',$where);
		
		if($id > 0){
		
			$db_sql = "select * from $table WHERE $where";
			$db_user = $obj->MySQLSelect($db_sql);				
			$_SESSION['sess_iMemberId']=$userid;
			
			$_SESSION["sess_vName"]= $db_user[0]['vName'];
			$_SESSION["sess_vLastName"]= $db_user[0]['vLastName'];
			$_SESSION["sess_vEmail"] = $db_user[0]['vEmail'];
			$_SESSION["sess_eGender"]= $db_user[0]['eGender'];
			
			if($action == 'rider'){
				$_SESSION["sess_vImage"]= $db_user[0]['vImgName'];
				$filename="profile_rider.php";
			}else{
				$_SESSION["sess_vImage"]= $db_user[0]['vImage'];
				$filename="profile.php";
			}
			$link = $tconfig["tsite_url"].$filename;
			
			
		}
	}
	//echo ""; print_r($_SESSION); exit;
	$generalobj->go_to_home();
	
	$sql = "select * from country where eStatus='Active'";
	$db_country = $obj->MySQLSelect($sql);

?>
<!DOCTYPE html>
	<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
 <!--   <title><?=$SITE_NAME?> | Login Page</title>-->
   <title><?php echo $meta_arr['meta_title'];?></title>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
    <!-- End: Default Top Script and css-->
    <style>
    .header-page-a p:before {background: none;}
    </style>
</head>
<body>
<!-- home page -->
	<div id="main-uber-page">
    <!-- Left Menu -->
    <?php include_once("top/left_menu.php");?>
    <!-- End: Left Menu-->
		<!-- Top Menu -->
        <?php include_once("top/header_topbar.php");?>
        <!-- End: Top Menu-->
		<!-- contact page-->
		<div class="page-contant reset-password">
			<div class="page-contant-inner">
				<h2 class="header-page-a"><?=$langage_lbl['LBL_COMPLATE_PROCESS_TXT'];?>
				<?if(SITE_TYPE =='Demo'){?>
				<p><?=$langage_lbl['LBL_SINCE_IT_IS_DEMO'];?></p>
				<?}?>
				</h2>
				<div class="login-form">	
				<?php	  
							
					if($success == 1) { ?>
					 <div class="alert alert-danger alert-dismissable">
					  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					 <?php echo $_REQUEST['var_msg']; ?>
				 </div><br/>
				 <? } ?>
				
						<div class="login-form-left reset-password-page">
							<form name="resetpassword12" action="" class="form-signin" method = "post" id="resetpassword12" >	
							<input type="hidden" value="<?=$action; ?>" id="action" name="action12" >
								<?php if($db_user[0]['vEmail'] == ""){?>
								<span class="newrow">
									<b>						
								    <label><?= $langage_lbl['LBL_EMAIL_TEXT'] ?></label>
								    <input name="vEmail" id="vEmail" type="text" placeholder="<?=$langage_lbl['LBL_EMAIL_TEXT']; ?>" class="login-input-b" value="" required style="width:100%;"/>
									</b>
								</span>
								<?php } ?>
								<span class="newrow" style="">
								<b>						
								<label><?= $langage_lbl['LBL_COUNTRY_TXT'] ?></label>
								<select class="select-reset-password"  name ="vCountry" id="vCountry" onChange="changeCode(this.value);" required style="margin: 0 0 15px;">
									<? for($i=0;$i<count($db_country);$i++){ ?>
									<option value ="<?= $db_country[$i]['vCountryCode'] ?>" <?php if($db_country[$i]['vCountryCode'] == $DEFAULT_COUNTRY_CODE_WEB){echo "selected";}?>><?= $db_country[$i]['vCountry'] ?></option>
									<? } ?>
								</select></b>
								</span>
								<span class="newrow">
								<b>
								<label><?=$langage_lbl['LBL_SIGNUP_777-777-7777']; ?></label>
									<input name="vPhoneCode" id="code" type="text" class="login-input-a" value="" required />
									<input name="vPhone" id="vPhone" type="text" placeholder="<?=$langage_lbl['LBL_SIGNUP_777-777-7777']; ?>" class="login-input-b" value="" required />
								</b> 
								</span>
								<?php if($REFERRAL_SCHEME_ENABLE == 'Yes'){ ?>
		                            <span class="newrow">
		                            	<b>
		                            	<label><?= $langage_lbl['LBL_SIGNUP_REFERAL_CODE'] ?></label>
		                            	<div id="refercodeCheck">
		                            	 <input name="vRefCode" id="vRefCode" type="text" placeholder="<?=$langage_lbl['LBL_SIGNUP_REFERAL_CODE']; ?>" class="login-input-b" value="" style="width:100%;" onBlur="validate_refercode(this.value)"/>
		                            	 <input type="hidden" placeholder="" name="iRefUserId" id="iRefUserId"  class="create-account-input" value="" />
                            			<input type="hidden" placeholder="" name="eRefType" id="eRefType" class="create-account-input" value=""  />
		                            	</div>
		                            	</b>
		                            </span> 
		                        <?php }  ?>
								<span class="newrow">
		                           <b>
									<strong class="captcha-signup1">
		                                <div class="checkbox-n" style="float: left;">
		                                    <input id="c1" name="RememberMe" type="checkbox" class="required login-input-b" value="remember" required>
		                                    <label for="c1"></label>
		                                </div>
									<abbr style="float: left; padding: 0 15px;"><?=$langage_lbl['LBL_SIGNUP_Agree_to']; ?>  <a href="terms_condition.php" target="_blank" style="color: #219201;margin: 0;float: none; "><?=$langage_lbl['LBL_SIGN_UP_TERMS_AND_CONDITION']; ?>  </a> and <a href="privacy-policy" target="_blank" style="color: #219201;margin: 0;float: none; "><?=$langage_lbl['LBL_PRIVACY_POLICY_TEXT']; ?>  </a>

		                            </abbr>
		                            </strong>
		                            </b>
                            	</span>
								<b>
								<input type="submit" class="submit-but" name="submit" value="<?=$langage_lbl['LBL_SIGN_UP']; ?>" />								
								</b> 
							</form>								
						</div>	
						
				</div>				
				<div style="clear:both;"></div>
			</div>
		</div>
	<!-- footer part -->
    <?php include_once('footer/footer_home.php');?>
    <!-- footer part end -->
    		<!-- -->
            <div style="clear:both;"></div>
	</div>
	<!-- home page end-->
    <!-- Footer Script -->
    <?php include_once('top/footer_script.php');?>
    <!-- End: Footer Script -->
  <script type="text/javascript" src="assets/js/validation/jquery.validate.min.js" ></script>
	<script type="text/javascript" src="assets/js/validation/additional-methods.js" ></script>
    <script>
    	var errormessage;
	 $('#resetpassword12').validate({						
	    //onsubmit: true,
	    onkeyup: function(element) {$(element).valid()},
		ignore: 'input[type=hidden]',
		errorClass: 'help-block error',
		errorElement: 'span',		 
		errorPlacement: function (error, e) {
			e.parents('.newrow > b').append(error);
		},
		highlight: function (e) {
			$(e).closest('.newrow').removeClass('has-success has-error').addClass('has-error');
			$(e).closest('.newrow b input').addClass('has-shadow-error');
			$(e).closest('.help-block').remove();
		},
		success: function (e) {
			e.prev('input').removeClass('has-shadow-error');
			e.closest('.newrow').removeClass('has-success has-error');
			e.closest('.help-block').remove();
			e.closest('.help-inline').remove();
		},
		rules: {			
			vEmail: {required: true, email: true,
				remote: {
						url: 'ajax_validate_email_new.php',
						type: "post",
					    data: {
                            iDriverId: '',
                            usertype:  function(e){
                                return $('input[name=action12]').val();
                            }
                        },
                        dataFilter: function(response) {
                            //response = $.parseJSON(response);
                            if (response == 'deleted')  {
                                errormessage = "<?= addslashes($langage_lbl['LBL_CHECK_DELETE_ACCOUNT']); ?>";
                                return false;
                            } else if(response == 'false'){
                                errormessage = "<?= addslashes($langage_lbl['LBL_EMAIL_EXISTS_MSG']); ?>";
                                return false;
                            } else {
                                return true;
                            }
                        },
					}
		    },		
			vPhone: {required: true,minlength: 3,digits: true,
						remote: {
							onkeyup: true,
							url: 'ajax_mobile_new_check.php',
							type: "post",						
							data: {userType:function () {return $("#action").val();}},
						}
			},
			RememberMe:{required: true}
			
		},
		messages: {
			vEmail: {remote: function(){ return errormessage; }},
			vPhone: {minlength: 'Please enter at least three Number.',digits: 'Please enter proper mobile number.',remote: 'Phone Number is already exists.'}
		}
	});
	
	function changeCode(id) {
		var request = $.ajax({
			type: "POST",
			url: 'change_code.php',
			data: 'id=' + id,
			success: function (data)
			{
				document.getElementById("code").value = data;
			}
		});
	}
	
	function validate_refercode(id){        

	if(id == ""){
	    return true;
	}else{


	    var request = $.ajax({
	        type: "POST",
	        url: 'ajax_validate_refercode.php',
	        data: 'refcode=' +id,
	        success: function (data)
	        {
	            if(data == 0){
					$("#referCheck").remove();
					$('#refercodeCheck').append('<div class="required-label error" id="referCheck" >* <?= addslashes($langage_lbl['LBL_REFER_CODE_ERROR']); ?></div>');
		            $('#vRefCode').attr("placeholder", "<?= addslashes($langage_lbl['LBL_SIGNUP_REFERAL_CODE']); ?>");
		            $('#vRefCode').val("");
		            return false;
	            } else {
	            	$("#referCheck").remove();
	                var reponse = data.split('|');              
	                $('#iRefUserId').val(reponse[0]);
	                $('#eRefType').val(reponse[1]);
	            }
	        }
	    });

	}   

	}

	$(document).ready(function () {
		var code = '<?=$DEFAULT_COUNTRY_CODE_WEB?>';
		changeCode(code);
	});
		
	</script>
	
</body>
</html>