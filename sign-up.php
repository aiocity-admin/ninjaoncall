<?php 
    include_once("common.php");
    error_reporting(0);
	$generalobj->go_to_home();
    $script="Driver Sign-Up";

    $sql="select * from  currency where eStatus='Active' ORDER BY  vName ASC";
    $db_currency=$obj->MySQLSelect($sql);

    $sql = "SELECT * FROM country WHERE eStatus = 'Active'" ;
    $db_code = $obj->MySQLSelect($sql);
    
	$meta_arr = $generalobj->getsettingSeo(5);
    $Mobile=$MOBILE_VERIFICATION_ENABLE;
	$error = isset($_REQUEST['error']) ? $_REQUEST['error'] : '';
	$var_msg = isset($_REQUEST['var_msg']) ? $_REQUEST['var_msg'] : '';
	
	
	$sql = "SELECT * FROM country WHERE eStatus = 'Active' ORDER BY  vCountry ASC";
	$db_country = $obj->MySQLSelect($sql);
	
	
    if(isset($_SESSION['postDetail'])) {
        $_REQUEST = $_SESSION['postDetail'];
        $user_type = isset($_REQUEST['user_type']) ? $_REQUEST['user_type'] : 'driver';
        $vEmail = isset($_REQUEST['vEmail']) ? $_REQUEST['vEmail'] : '';
        $vCountry = isset($_REQUEST['vCountry']) ? $_REQUEST['vCountry'] : '';
        $vCode = isset($_REQUEST['vCode']) ? $_REQUEST['vCode'] : '';
        $vPhone = isset($_REQUEST['vPhone']) ? $_REQUEST['vPhone'] : '';
		$vFirstName = isset($_REQUEST['vFirstName']) ? $_REQUEST['vFirstName'] : '';
		$vLastName = isset($_REQUEST['vLastName']) ? $_REQUEST['vLastName'] : '';
		$vCompany = isset($_REQUEST['vCompany']) ? $_REQUEST['vCompany'] : '';
		$vCaddress = isset($_REQUEST['vCaddress']) ? $_REQUEST['vCaddress'] : '';
		$vCadress2 = isset($_REQUEST['vCadress2']) ? $_REQUEST['vCadress2'] : '';
		$vState = isset($_REQUEST['vState']) ? $_REQUEST['vState'] : '';
		$vCity = isset($_REQUEST['vCity']) ? $_REQUEST['vCity'] : '';
        $vZip = isset($_REQUEST['vZip']) ? $_REQUEST['vZip'] : '';
		$vVat = isset($_REQUEST['vVat']) ? $_REQUEST['vVat'] : '';
		$vCurrencyDriver = isset($_REQUEST['vCurrencyDriver']) ? $_REQUEST['vCurrencyDriver'] : '';
        $vBarangay=isset($_POST['vBarangay'])?$_POST['vBarangay']:'';
   // $vOutVat=isset($_POST['vOutVat'])?$_POST['vOutVat']:'';
    $dBirthDate=isset($_POST['dBirthDate'])?$_POST['dBirthDate']:'';
/*		$vDay = isset($_REQUEST['vDay']) ? $_REQUEST['vDay'] : '';
		$vMonth = isset($_REQUEST['vMonth']) ? $_REQUEST['vMonth'] : '';
		$vYear = isset($_REQUEST['vYear']) ? $_REQUEST['vYear'] : '';*/
        $Suffix = isset($_POST['Suffix']) ? $_POST['Suffix'] : '';
        $MiddleName = isset($_POST['MiddleName']) ? $_POST['MiddleName'] : '';

		
		unset($_SESSION['postDetail']);
	}



    $vRefCode = isset($_REQUEST['vRefCode']) ? $_REQUEST['vRefCode'] : '';
    if(!empty($_COOKIE['vUserDeviceTimeZone'])){
        $vUserDeviceTimeZone = $_COOKIE['vUserDeviceTimeZone'];
        $sql = "SELECT vCountryCode FROM country WHERE vTimeZone LIKE '%".$vUserDeviceTimeZone."%' OR vAlterTimeZone LIKE '%".$vUserDeviceTimeZone."%' ORDER BY  vCountry ASC";
        $db_country_code = $obj->MySQLSelect($sql);
        if(!empty($db_country_code[0]['vCountryCode'])){
            $DEFAULT_COUNTRY_CODE_WEB = $db_country_code[0]['vCountryCode'];
        }
    }
?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
   <!-- <title><?=$COMPANY_NAME?>| Signup</title>-->
	<title><?php echo $meta_arr['meta_title'];?></title>
	<meta name="keywords" value="<?=$meta_arr['meta_keyword'];?>"/>
	<meta name="description" value="<?=$meta_arr['meta_desc'];?>"/>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
    <link href="assets/css/checkbox.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/radio.css" rel="stylesheet" type="text/css" />
    <?php include_once("top/validation.php");?>


        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
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
        <!-- End: Top Menu-->
    <!-- contact page-->
    <div class="page-contant">
        <div class="page-contant-inner">
            <h2 class="header-page-sinu trip-detail"><?=$langage_lbl['LBL_SIGNUP_SIGNUP']; ?>
               
            </h2>
             <p><?=$langage_lbl['LBL_SIGN_UP_TELL_US_A_BIT_ABOUT_YOURSELF']; ?></p>
            <!-- trips detail page -->
            <form name="frmsignup" id="frmsignup" method="post" action="signup_a.php">
                <div class="driver-signup-page">
                <?php
                if ($error != "") {
                ?>
                    <div class="row">
                        <div class="col-sm-12 alert alert-danger">
                             <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?=$var_msg; ?>
                        </div>
                    </div>
                <?php 
                    }
                ?>
                    <?php /*<h3><?=$langage_lbl['LBL_Contact_Info']; ?></h3> */ ?>
                    <p><?=$langage_lbl['LBL_IF_YOU_ARE_AN_INDIVIDUAL']; ?></p>
                    <p><?=$langage_lbl['LBL_IF_YOU_ARE_A_COMPANY']; ?></p>
                    <div class="individual-driver">
                        <h4><?=$langage_lbl['LBL_ARE_YOU_AN_INDIVIDUAL']; ?></h4>
                        <span>
                            <em><?=$langage_lbl['LBL_Member_Type:']; ?> </em>
                            <div class="radio-but"> 
                            <b>
                                <input id="r1" name="user_type" type="radio" value="driver" <?php if($user_type == 'driver') { echo 'checked'; } ?> onChange="show_company(this.value);" checked="checked">
                                <label for="r1"><?=$langage_lbl['LBL_SIGNUP_INDIVIDUAL_DRIVER']; ?></label>
                            </b> 
                            <b>
                                <input id="r2" name="user_type" type="radio" value="company" <?php if($user_type == 'company') { echo 'checked'; } ?> onChange="show_company(this.value);" class="">
                                <label for="r2"><?=$langage_lbl['LBL_Company']; ?></label>
                            </b> 
                            </div>
                        </span> 
                        
                    </div>
                    <div class="create-account">
                        <h3><?=$langage_lbl['LBL_SIGN_UP_CREATE_ACCOUNT']; ?></h3>
                        <input type="hidden" placeholder="" name="userType" id="userType" class="create-account-input" value="" />
                        <span class="newrow">
                            <strong id="emailCheck"><label><?=$langage_lbl['LBL_EMAIL_TEXT_SIGNUP']; ?> <span class="red">*</span> </label>
                            <input type="text"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" Required placeholder="<?=$langage_lbl['LBL_EMAIL_name@email.com']; ?>" name="vEmail" class="create-account-input " id="vEmail_verify" value="<?php echo $vEmail; ?>" /></strong>
                           

						   <strong><label><?=$langage_lbl['LBL_PASSWORD']; ?> <span class="red">*</span> </label>
                            <input id="pass" type="password" name="vPassword" placeholder="<?=$langage_lbl['LBL_PASSWORD']; ?>" class="create-account-input create-account-input1 " required value="" /></strong>
                        </span> 
                        <span>
                            <?php /*<input type="hidden" name="mobile_verification"  id="mobile_verification" value="<?=$Mobile;?>"> */ ?>
                            <input type="hidden" placeholder="" name="iRefUserId" id="iRefUserId"  class="create-account-input" value="" />
                            <input type="hidden" placeholder="" name="eRefType" id="eRefType" class="create-account-input" value=""  />
                        </span> 

                        <!--<span class="c_country newrow">
                            <strong>
                                <select name="vCountry" class="custom-select-new" onChange="changeCode(this.value); ">
                                    
                                    <? for($i=0;$i<count($db_code);$i++) { ?>
                                    <option value="<?=$db_code[$i]['vCountryCode']?>" <? if($db_code[$i]['vCountryCode']== $DEFAULT_COUNTRY_CODE_WEB){echo 'selected';}?>><?=$db_code[$i]['vCountry']?></option>
                                    <? } ?>
                                </select>
                            </strong>
                        </span> -->
                         
                      
                         <?php if($REFERRAL_SCHEME_ENABLE == 'Yes'){ ?>
                               <span class="newrow"><strong id="refercodeCheck"><input id="vRefCode" type="text" name="vRefCode" placeholder="<?=$langage_lbl['LBL_SIGNUP_REFERAL_CODE']; ?>" class="create-account-input create-account-input1 vRefCode_verify" value="<?php echo $vRefCode; ?>" onBlur="validate_refercode(this.value)"/></strong></span> 
                        <?php }  ?>

                    </div>
                    <div class="create-account">
                        <h3 class="company" style="display: none;"><?=$langage_lbl['LBL_Company_Information']; ?></h3>
                        <h3 class="driver"><?=$langage_lbl['LBL_Driver_Information']; ?></h3>
                        <span class="driver newrow">
                            <strong><label>Suffix <span class="red"></span> </label>
                           <input type="text" class="create-account-input" name="Suffix"  id="Suffix" value="<?=$Suffix;?>" placeholder="Suffix" >
                       </strong>
                           <strong><label><?=$langage_lbl['LBL_SIGN_UP_FIRST_NAME_HEADER_TXT']; ?> <span class="red">*</span> </label>
                            <input name="vFirstName" type="text" class="create-account-input" placeholder="<?=$langage_lbl['LBL_SIGN_UP_FIRST_NAME_HEADER_TXT']; ?>" id="vFirstName"value="<?php echo $vFirstName; ?>" /></strong>
                        </span> 
                   <span class="driver newrow">
                             <strong><label>Middle Name <span class="red"></span> </label>
                                 <input type="text" class="create-account-input" name="MiddleName"  id="MiddleName" value="<?= $MiddleName; ?>" placeholder="Middle Name">
                            </strong>

                            <strong><label><?=$langage_lbl['LBL_SIGN_UP_LAST_NAME_HEADER_TXT']; ?> <span class="red">*</span> </label>
                            <input name="vLastName" type="text" class="create-account-input create-account-input1" placeholder="<?=$langage_lbl['LBL_SIGN_UP_LAST_NAME_HEADER_TXT']; ?>" id="vLastName" value="<?php echo $vLastName; ?>" /></strong>
                        </span> 



                        <span class="company newrow" style="display: none;">
                            <strong><label><?=$langage_lbl['LBL_COMPANY_SIGNUP']; ?><span class="red">*</span> </label>
                            <input type="text" id="company_name" placeholder="<?=$langage_lbl['LBL_COMPANY_SIGNUP']; ?>" class="create-account-input" name="vCompany" value="<?php echo $vCompany; ?>"  /></strong>
                           <strong><label><?=$langage_lbl['LBL_VAT_NUMBER_SIGNUP']; ?></label>
                            <input name="vVat" type="text" class="create-account-input" placeholder="<?=$langage_lbl['LBL_VAT_NUMBER_SIGNUP']; ?>" value="<?php echo $vVat; ?>" /></strong>
                        </span>
                      <!--   <span class="company newrow" style="display: none;">
                             

                            <strong><label>Output Vat Number</label>
                            <input name="vOutVat" type="text" class="create-account-input" placeholder="Output Vat Number" value="<?php echo $vOutVat; ?>" /></strong>
                        </span> -->
                        
						     <span class="newrow">
										<strong><label><?= $langage_lbl['LBL_COUNTRY_TXT'] ?> <span class="red">*</span>  </label>
											<select  class="custom-select-new" required name='vCountry' id="vCountry" onChange="setState(this.value,'');" >
													<option value=""><?= $langage_lbl['LBL_SELECT_TXT'] ?></option>
													<? for($i=0;$i<count($db_country);$i++){ ?>
													<option value = "<?= $db_country[$i]['vCountryCode'] ?>" <?if($DEFAULT_COUNTRY_CODE_WEB==$db_country[$i]['vCountryCode']){?>selected<? } ?>><?= $db_country[$i]['vCountry'] ?></option>
													<? } ?>
											</select>
										 </strong>	
									<strong><label id="lbl_vstate"> <?= $langage_lbl['LBL_STATE_TXT'] ?></label>
												<select class="custom-select-new" name = 'vState' id="vState" onChange="setCity(this.value,'');" >
												<option value=""><?= $langage_lbl['LBL_SELECT_TXT'] ?></option>
												</select>
							</strong>
							</span>	
							  <span class="newrow">
									<strong><label id="lbl_city"> <?= $langage_lbl['LBL_CITY_TXT'] ?></label>
												<select class="custom-select-new" name = 'vCity' id="vCity">
													<option value=""><?= $langage_lbl['LBL_SELECT_TXT'] ?></option>
												</select>
									</strong>

                                    <strong id="row_vBarangay">
                                        <label>Barangay<span class="red">*</span></label>
                                                <select required  class="custom-select-new" name = 'vBarangay' id="vBarangay">
                                                    <option value=""><?= $langage_lbl['LBL_SELECT_TXT'] ?></option>
                                                </select>
                                    </strong>
						   
							
                        </span>
                              <span class="newrow">
                                  

                                   <strong><label><?=$langage_lbl['LBL_ADDRESS_SIGNUP']; ?><span class="red">*</span> </label>
                            <input name="vCaddress" type="text" class="create-account-input" placeholder="House Number, Building Number, and Street Name" value="<?php echo $vCaddress; ?>" /></strong>
                              <strong><label><?=$langage_lbl['LBL_ZIP_CODE_SIGNUP']; ?><span class="red"></span></label>
                            <input name="vZip" minlength="4" maxlength="6" type="text" class="create-account-input create-account-input1" placeholder="<?=$langage_lbl['LBL_ZIP_CODE_SIGNUP']; ?>" value="<?php echo $vZip; ?>" /></strong>
                              </span>

						<span class="newrow">
                          
                          
							<strong><label><?=$langage_lbl['LBL_SIGNUP_777-777-7777']; ?><span class="red">*</span></label>
                            <strong class="ph_no newrow" id="mobileCheck"><input required maxlength="10" type="text"  id="vPhone" value="<?php echo $vPhone; ?>" placeholder="<?=$langage_lbl['LBL_SIGNUP_777-777-7777']; ?>" class="create-account-input create-account-input1 vPhone_verify" name="vPhone"/></strong>
                           
                            <strong class="c_code"><input type="text"  name="vCode" readonly  class="create-account-input " value="<?php echo $vCode; ?>" id="code" /></strong></strong>    

                              <strong class='driver'>
                            <label><?=$langage_lbl['LBL_SELECT_CURRENCY_SIGNUP']; ?> <span class="red">*</span> </label>
                                <select class="custom-select-new" required name = 'vCurrencyDriver'>
                                    <?php for($i=0;$i<count($db_currency);$i++){ ?>
                                    <option value = "<?= $db_currency[$i]['vName'] ?>" <?if($db_currency[$i]['eDefault']=="Yes"){?>selected<?} ?>>
                                    <?= $db_currency[$i]['vName'] ?>
                                    </option>
                                    <? } ?>
                                </select>
                         </strong>                        
                        </span> 
						
<!-- codeEdited for adding DOB
 -->  <span class="newrow driver" style="display: none">
                                  

                                   <strong><label>Date of Birth<span class="red">*</span> </label>
                            <input  name="dBirthDate" id="dBirthDate" type="text" class="create-account-input" placeholder="YYYY-MM-DD" value="<?php echo $dBirthDate; ?>" /></strong>


                            <strong>
                                <label>Services <span class="red">*</span> </label>
                                       <select   class="form-control miltiselect" data-text="Service" name='Service[]' id="Service" multiple="multiple" >
                                       

                                                <option value='Ride'  >Ride</option>
                                                <option value='Delivery' >Delivery</option>

      <?

       $vehicle_type_sql1 = "SELECT * FROM  vehicle_category  WHERE    eStatus='Active'  and `iParentId`=0 order by vCategory_EN";


/* $vehicle_type_sql1="select vcp.iVehicleCategoryId,vcp.vCategory_EN  FROM  company_services cs left join vehicle_type vt on vt.iVehicleTypeId=cs.ServiceId LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId where cs.CompanyId='$searchCompany' group by vcp.iVehicleCategoryId,cs.CompanyId order by vcp.vCategory_EN  ";*/
 
          $vehicle_type= $obj->MySQLSelect($vehicle_type_sql1);

        foreach ($vehicle_type as $subkey => $subvalue) {


           echo "<option value='".$subvalue['vCategory_EN']."' >".$subvalue['vCategory_EN']."</option>";

}         

       ?>
                                               
                      </select>

                      <span id="Service-error" class="help-block error"></span>
                            </strong>
                              
                              </span>



                           



						 <span class="newrow">
							 <!--  <strong><label><?=$langage_lbl['LBL_ADDRESS2_SIGNUP']; ?></label>
                           <input name="vCadress2" type="text" class="create-account-input create-account-input1" placeholder="<?=$langage_lbl['LBL_ADDRESS2_SIGNUP']; ?>" value="<?php echo $vCaddress2; ?>" />
                            </strong> -->
                            
                          
                         
                        </span>
						
<!--                         <span class="newrow sign-up-gender">
                            <b id="li_dob">
                                <strong>
								<?=$langage_lbl['LBL_Date_of_Birth']; ?></strong>
                                <select name="vDay" data="DD" class="custom-select-new">
                                    <option><?=$langage_lbl['LBL_DATE_SIGNUP']; ?></option>
                                    <?php for($i=1;$i<=31;$i++) {?>
                                    <option value="<?=$i?>">
                                    <?=$i?>
                                    </option>
                                    <?php }?>
                                </select>
                                <select data="MM" name="vMonth" class="custom-select-new">
                                    <option><?=$langage_lbl['LBL_MONTH_SIGNUP']; ?></option>
                                    <?php for($i=1;$i<=12;$i++) {?>
                                    <option value="<?=$i?>">
                                    <?=$i?>
                                    </option>
                                    <?php }?>
                                </select>
                                <select data="YYYY" name="vYear" class="custom-select-new">
                                    <option><?=$langage_lbl['LBL_YEAR_SIGNUP']; ?></option>
                                    <?php for($i=(date("Y")-$START_BIRTH_YEAR_DIFFERENCE);$i >= ((date("Y")-1)-$BIRTH_YEAR_DIFFERENCE);$i--) {?>
                                    <option value="<?=$i?>">
                                    <?=$i?>
                                    </option>
                                    <?php }?>
                                </select>
                            </b>
							<strong class='gender-span001 driver'><div class="radio-but"> 
							<em><?=$langage_lbl['LBL_GENDER_TXT']; ?>: </em>
                            <b><input id="r4" name="eGender" type="radio" value="Male" >
                                <label for="r4"><?=$langage_lbl['LBL_MALE_TXT']; ?></label></b><b> <input id="r5" name="eGender" type="radio" value="Female" class="required">
                                <label for="r5"><?=$langage_lbl['LBL_FEMALE_TXT']; ?></label></b></div></strong>
                        </span> -->
                        <span class="newrow">
                            <strong class="captcha-signup"><label><?=$langage_lbl['LBL_CAPTCHA_SIGNUP']; ?><span class="red">*</span></label>
                            <input id="POST_CAPTCHA" class="create-account-input" size="5" maxlength="5" name="POST_CAPTCHA" type="text">
							 <em class="captcha-dd"><img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' alt="" class="chapcha-img" />&nbsp;<?=$langage_lbl['LBL_CAPTCHA_CANT_READ_SIGNUP']; ?> <a href='javascript: refreshCaptcha();'><?=$langage_lbl['LBL_CLICKHERE_SIGNUP']; ?></a></em>
                             </strong>
                        </span>
						<span class="newrow">
						<strong class="captcha-signup1">
							<abbr><?=$langage_lbl['LBL_SIGNUP_Agree_to']; ?> <a href="terms_condition.php" target="_blank"><?=$langage_lbl['LBL_SIGN_UP_TERMS_AND_CONDITION']; ?></a>
                                <div class="checkbox-n">
                                    <input id="c1" name="remember-me" type="checkbox" class="required" value="remember">
                                    <label for="c1"></label>
                                </div>
                            </abbr>
                            </strong>
						 </span>
                   
                    <p><button type="submit" class="submit" name="SUBMIT"><?=$langage_lbl['LBL_BTN_SIGN_UP_SUBMIT_TXT']; ?></button></p>
                    </div>
                </div>
            </form>
            <div class="col-lg-12">
                <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="H2"><?=$langage_lbl['LBL_SIGNUP_PHONE_VERI']; ?></h4>
                            </div>
                            <div class="modal-body">
                                <form role="form" name="verification" id="verification">
                                    <p class="help-block"><?=$langage_lbl['LBL_SIGNUP_PHONE_VERI_TEXT']; ?></p>
                                    <div class="form-group">
                                        <label><?=$langage_lbl['LBL_SIGNUP_ENTER_CODE']; ?></label>
                                        <input class="form-control" type="text" id="vCode1"/>
                                    </div>
                                    <p class="help-block" id="verification_error"></p>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" onClick="check_verification('verify')"><?=$langage_lbl['LBL_SIGNUP_VERIFY']; ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- -->
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
    <?php include_once('top/footer_script.php');
    $lang = get_langcode($_SESSION['sess_lang']);?>
	<script type="text/javascript" src="assets/js/validation/jquery.validate.min.js" ></script>
    <?php if($lang != 'en') { ?>
    <script type="text/javascript" src="assets/js/validation/localization/messages_<?= $lang; ?>.js" ></script>
    <?php } ?>
	<script type="text/javascript" src="assets/js/validation/additional-methods.js" ></script>

    <script type="text/javascript">

    $(document).ready(function() {
           var refcode = $('#vRefCode').val();
           if(refcode != ""){
            validate_refercode(refcode);
           }
    });
    function show_company(user)
    {

        $("input[type=hidden][name=userType]").val(user);
        if(user=='company')
        {
            $(".company").show();
            $(".driver").hide();
            /*$("#li_dob").hide();*/
            $("#vRefCode").hide();
            $('#div-phone').show();
            $("#dBirthDate").hide();
        }
        else if(user=='driver')
        {
            $(".company").hide();
            $(".driver").show();
           /* $("#li_dob").show();*/
            $("#vRefCode").show();
            $('#div-phone').hide();
            $("#dBirthDate").show();
        }
    }
        
    var errormessage;
    $('#frmsignup').validate({
		ignore: 'input[type=hidden]',
		errorClass: 'help-block error',
		onkeypress: true,
		errorElement: 'span',
		errorPlacement: function (error, e) {
			e.parents('.newrow > strong').append(error);
		},
		highlight: function (e) {
			$(e).closest('.newrow').removeClass('has-success has-error').addClass('has-error');
			$(e).closest('.newrow strong input').addClass('has-shadow-error');
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
                                    return $('input[name=userType]').val();
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
			vPassword: {required: true,noSpace: true, minlength: 6, maxlength: 16},
			vPhone: {required: true, minlength: 3,digits: true,
				remote: {
					url: 'ajax_driver_mobile_new.php',
					type: "post",
					data: { iDriverId: '',
                            usertype:  function(e){
                                return $('input[name=userType]').val();
                            }
                        },
				}
			},
			vCompany: {required: function(e){
                            return $('input[name=user_type]:checked').val() == 'company';
                        }, minlength: function(e){
							if($('input[name=user_type]:checked').val() == 'company') { return 2; } else {return false;}
                        },maxlength: function(e){
                            if($('input[name=user_type]:checked').val() == 'company') { return 30; } else { return false;}
                        }},
			vFirstName: {required: function(e){
							return $('input[name=user_type]:checked').val() == 'driver';
						}, minlength: function(e){
							if($('input[name=user_type]:checked').val() == 'driver') { return 2; } else {return false;}
                        },maxlength: function(e){
							if($('input[name=user_type]:checked').val() == 'driver') { return 30; } else { return false;}
                        }},
			vLastName: {required: function(e){
							return $('input[name=user_type]:checked').val() == 'driver';
						}, minlength: function(e){
							if($('input[name=user_type]:checked').val() == 'driver') { return 2; } else {return false;}
                        },maxlength: function(e){
							if($('input[name=user_type]:checked').val() == 'driver') { return 30; } else { return false;}
                        }},
			vCaddress: {required: true, minlength: 2},
            dBirthDate:{required: function(e){
                            return $('input[name=user_type]:checked').val() == 'driver';
                        }},
			//vCity: {required: true},
			//vZip: {required: true},
			// eGender: {required: true},
            vCountry:{required:true},
 
			POST_CAPTCHA: {required: true, remote: {
							url: 'ajax_captcha_new.php',
							type: "post",
							data: {iDriverId: ''},
						}},
			'remember-me': {required: true},
		},
		messages: {
			vPassword:{maxlength: 'Please enter less than 16 characters.'},
			vEmail: {remote: function(){ return errormessage; }},
			'remember-me': {required: '<?= addslashes($langage_lbl['LBL_AGREE_TERMS_MSG']); ?>'},
			vPhone: {minlength: 'Please enter at least three Number.',digits: 'Please enter proper mobile number.',remote: '<?= $langage_lbl['LBL_PHONE_EXIST_MSG']; ?>'},
            vCompany: {
                required: 'Company Name is required.',
                minlength: 'Company Name at least 2 characters long.',
                maxlength: 'Please enter less than 30 characters.'
            },
            vFirstName: {
                required: 'First Name is required.',
                minlength: 'First Name at least 2 characters long.',
                maxlength: 'Please enter less than 30 characters.'
            },
            vLastName: {
                required: 'Last Name is required.',
                minlength: 'Last Name at least 2 characters long.',
                maxlength: 'Please enter less than 30 characters.'
            },
            vBarangay:
            {
                required:'Barangay is required.'
            },
            vCountry:{
                required:'Country is required.'
            },
			POST_CAPTCHA: {remote: '<?= addslashes($langage_lbl['LBL_CAPTCHA_MATCH_MSG']); ?>'}
		}
	});
	
	
		$('#verification').bind('keydown',function(e){
            if(e.which == 13){
                check_verification('verify'); return false;
            }
        });
        
        
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
		  /*ajax for unique username*/
        

        $(document).ready(function(){
              /*jQuery.validator.addMethod("noSpace", function(value, element) { 
                  return value.indexOf("") < 0 && value != ""; 
                }, "<?= $langage_lbl['LBL_NO_SPACE_ERROR_MSG']?>");*/


$(".submit").click(function(e){
    
    if($('input[name=user_type]:checked').val() == 'driver' && ($("#Service").val()=="null" || $("#Service").val()==null))
    {
$("#Service-error").text("Please select Services.").show();
e.preventDefault();
    }
    else
    {
$("#Service-error").text("").hide();

    }
});

//codeEDITED FOR DOB
      $("#dBirthDate").datetimepicker({format: 'YYYY-MM-DD'});

            $.validator.addMethod("noSpace", function(value, element) {
                return this.optional(element) || /^\S+$/i.test(value);
            }, "<?= addslashes($langage_lbl['LBL_NO_SPACE_ERROR_MSG']);?>");

            $("#company").hide();
            $("#radio_1").prop("checked", true)
            $( "#company_name" ).removeClass( "required" );
             show_company('driver');
			 
			var newUser = $("input[name=user_type]:checked").val();
            $("input[type=hidden][name=userType]").val(newUser); 
			if(newUser=='company')
			{
				$(".company").show();
				$(".driver").hide();
				/*$("#li_dob").hide();*/
				$("#vRefCode").hide();
				$('#div-phone').show();
			}
			else if(newUser=='driver')
			{
				$(".company").hide();
				$(".driver").show();
				/*$("#li_dob").show();*/
				$("#vRefCode").show();
				$('#div-phone').hide();
			}


            setBarangay('<?php echo $vBarangay;?>','<?php echo $vCity; ?>','<?php echo $vState; ?>');

$("#vCity").change(function(){
   // var vCountry=$("#vCountry").val();
   var vState=$("#vState").val();
    var vCity=$("#vCity").val();

setBarangay('',vCity,vState);
});


 $("#Service").multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"480px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Service Category',
    maxHeight:500
  });

        });

        function setBarangay(selected,vCity,vState)
{
     

    
$.ajax({
        type: "POST",
        url: 'getCityBarangay.php',
        data: {iCityId: vCity, iStateId: vState},
        success: function (dataHtml)
        {
            var html='<option value="">Select</option>';
            dataHtml=JSON.parse(dataHtml);
            for (var i = 0; i < dataHtml.length; i++) {
                var isSelected='';
if (selected==dataHtml[i].ID) 
{
isSelected='selected';
}

                html +="<option value='"+dataHtml[i].ID+"' "+isSelected+" >"+dataHtml[i].Barangay+"</option>";
            }
            $("#vBarangay").html(html);
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
                        $(".vRefCode_verify").addClass('required-active');
						$('#refercodeCheck').append('<div class="required-label" id="referCheck" >* <?= addslashes($langage_lbl['LBL_REFER_CODE_ERROR']); ?></div>');
                        $('#vRefCode').attr("placeholder", "<?= addslashes($langage_lbl['LBL_SIGNUP_REFERAL_CODE']); ?>");
                        $('#vRefCode').val("");
                        return false;
                        }else{
                            var reponse = data.split('|');              
                            $('#iRefUserId').val(reponse[0]);
                            $('#eRefType').val(reponse[1]);
                        }
                    }
                });
            
            }   
            
        }
		
		
		function refreshCaptcha()
		{
			var img = document.images['captchaimg'];
			img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
		}
		
		function setState(id,selected)
		{
           
			changeCode(id);
			

    if (id=='PH') 
            {
$("#lbl_vstate").text("Province");
$("#lbl_city").text("City/Municipality");

$("#row_vBarangay").css("display","");
$("#vBarangay").attr("required",true);
$("#vBarangay").prop("required",true);

            }
            else
            {
                $("#lbl_vstate").text("State");
$("#lbl_city").text("City");
$("#row_vBarangay").css("display","none");
$("#vBarangay").attr("required",false);
$("#vBarangay").prop("required",false);
            }

			var fromMod = 'driver';
			var request = $.ajax({
				type: "POST",
				url: 'change_stateCity.php',
				data: {countryId: id, selected: selected,fromMod:fromMod},
				success: function (dataHtml)
				{
					$("#vState").html(dataHtml);
					if(selected == '')
						setCity('',selected);
				}
			});
		}
		
		function setCity(id,selected)
		{
			var fromMod = 'driver';
			var request = $.ajax({
				type: "POST",
				url: 'change_stateCity.php',
				data: {stateId: id, selected: selected,fromMod:fromMod},
				success: function (dataHtml)
				{
					$("#vCity").html(dataHtml);
				}
			});
		}
    </script>
    <!-- End: Footer Script -->
</body>
</html>
<style >
    
    button.multiselect.dropdown-toggle.btn.btn-default {
    height: 30px !important;
}
span.input-group-addon {
    display: none;
}
input.form-control.multiselect-search {
    width: 90% !important;
}
button.btn.btn-default.multiselect-clear-filter {
    width: 90%;
}
</style>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $tconfig["tsite_url_main_admin"]?>css/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/bootstrap-datetimepicker.min.js"></script>
   <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/removeselectall.js"></script>

