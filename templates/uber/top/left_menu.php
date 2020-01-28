<?php
$curr_url = basename($_SERVER['PHP_SELF']);
error_reporting(0);
//include 'common.php' ;
$user = $_SESSION["sess_user"];
if ($user == 'driver') {
     $sql = "select * from register_driver where iDriverId = '" . $_SESSION['sess_iUserId'] . "'";
     $db_data = $obj->sql_query($sql);
     if ($db_data[0]['vImage'] == "NONE" || $db_data[0]['vImage'] == '' ) {
          $db_data[0]['img'] = "";
     } else {

       $db_data[0]['img'] = $tconfig["tsite_upload_images_driver"] . '/' . $_SESSION['sess_iUserId'] . '/2_' . $db_data[0]['vImage'];
     }
}
if ($user == 'company') {
  $sql = "select * from company where iCompanyId = '" . $_SESSION['sess_iUserId'] . "'";

     $db_data = $obj->sql_query($sql);

     if ($db_data[0]['vImage'] == "NONE" || $db_data[0]['vImage'] =='') {
         $db_data[0]['img'] = "";
     } else {
       $db_data[0]['img'] = $tconfig["tsite_upload_images_compnay"] . '/' . $_SESSION['sess_iUserId'] . '/2_' . $db_data[0]['vImage'];
     }
}
if ($user == 'rider') {
     $sql = "select * from register_user where iUserId = '" . $_SESSION['sess_iUserId'] . "'";
     $db_data = $obj->sql_query($sql);
     if ($db_data[0]['vImgName'] != "NONE") {
          $db_data[0]['img'] = $tconfig["tsite_upload_images_passenger"] . '/' . $_SESSION['sess_iUserId'] . '/2_' . $db_data[0]['vImgName'];
     } else {
          $db_data[0]['img'] = "";
     }
}  //echo "<pre>";print_r($db_data);echo "</pre>";


if($host_system == 'cubetaxiplus') {
  $logo = "menu-logo.png";
} else if($host_system == 'ufxforall') {
  $logo = "menu-ufxforall-logo.png";
} else if($host_system == 'uberridedelivery4') {
  $logo = "menu-ride-delivery-logo.png";
} else if($host_system == 'uberdelivery4') {
  $logo = "menu-delivery-logo-only.png";
} else {
  $logo = "menu-logo.png";
}
?>
<span id="shadowbox" onClick="menuClose()"></span>
<nav>
  <button id="navBtnShow" onClick="menuOpen()">
  <div></div>
  <div></div>
  <div></div>
  </button>
  <ul id="listMenu">
    <span class="desktop">
    <div class="menu-logo">
		 <section id="navBtn" class="navBtnNew navOpen" onClick="menuClose()">
		  <div></div>
		  <div></div>
		  <div></div>
		</section>
	
		<?php if(($db_data[0]['img'] == '' || $db_data[0]['img'] == 'NONE') && ($user=="")){ ?>
		<a href="index.php" class="logo-left signin"><img src="assets/img/<?php echo $logo;?>" alt=""></a>
		<label><a href="sign-in" class="<?php echo strstr($_SERVER['SCRIPT_NAME'],'/sign-in') || strstr($_SERVER['SCRIPT_NAME'],'/login-new')?'active':'' ?>">
		  <i aria-hidden="true" class="fa fa-user"></i><?=$langage_lbl['LBL_HEADER_TOPBAR_SIGN_IN_TXT'];?></a></label>
		<?php } else {?>
		<strong><a href="index.php" class="logo-left"><img src="<?php echo $db_data[0]['img']; ?>" alt=""></a></strong>
    <p><!-- <?php echo $db_data[0]['vName'].' '.$db_data[0]['vLastName']; ?> -->
      <?if ($_SESSION['sess_user'] == 'driver' || $_SESSION['sess_user'] == 'rider') { echo  $generalobj->cleanall(htmlspecialchars($db_data[0]['vName'] . " " . $db_data[0]['vLastName'])); }?><?if ($_SESSION['sess_user'] == 'company') { echo  $generalobj->cleanall(htmlspecialchars($db_data[0]['vCompany'])); }?>
    </p>
		<?php } ?>
    </div>
    <div class="menu-left-new">
		<?php if($user=="") {?>
			<li><a href="how-it-works" class="<?=(isset($script) && $script == 'How It Works')?'active':'';?>"><?=$langage_lbl['LBL_HOW_IT_WORKS']; ?></a></li>
			<li><a href="trust-safty-insurance" class="<?=(isset($script) && $script == 'Trust Safty Insurance')?'active':'';?>"><?=$langage_lbl['LBL_SAFETY_AND_INSURANCE']; ?></a></li>
			<li><a href="terms-condition" class="<?=(isset($script) && $script == 'Terms Condition')?'active':'';?>"><?=$langage_lbl['LBL_FOOTER_TERMS_AND_CONDITION']; ?></a></li>
			<li><a href="legal" class="<?=(isset($script) && $script == 'Legal')?'active':'';?>"><?=$langage_lbl['LBL_LEGAL']; ?></a></li>
			  <li><a href="faq" class="<?=(isset($script) && $script == 'Faq')?'active':'';?>"><?=$langage_lbl['LBL_FAQs']; ?></a></li>
		<?php } else {
						if($user == 'driver') { ?>

							<li><a href="profile" class="<?=(isset($script) && $script == 'Profile')?'active':'';?>"><b><img alt="" src="assets/img/my-profile-icon.png"></b><span><?=$langage_lbl['LBL_HEADER_TOPBAR_PROFILE_TITLE_TXT'];?></span></a></li>
							<?php if($APP_TYPE != 'UberX'){ ?>
									<li><a href="vehicle" class="<?=(isset($script) && $script == 'Vehicle')?'active':'';?>"><b><img alt="" src="assets/img/my-taxi.png"></b><span><?=$langage_lbl['LBL_HEADER_TOPBAR_VEHICLES'];?></span></a></li>
							<?php } 
              if($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') {?>
								<li><a href="add_services.php?iDriverId=<?=$_SESSION['sess_iUserId'];?>" class="<?=(isset($script) && $script == 'My Availability')?'active':'';?>"><b><img alt="" src="assets/img/repairing-service-2.png"></b><span><?=$langage_lbl['LBL_HEADER_MY_SERVICES'];?></span></a></li>
                <li><a href="add_availability.php" class="<?=(isset($script) && $script == 'My Services')?'active':'';?>"><b><img alt="" src="assets/img/avilable.png"></b><span><?=$langage_lbl['LBL_HEADER_MY_AVAILABILITY'];?></span></a></li>
							<?php } 
               if($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') {?>
							<li><a href="provider-job" class="<?=(isset($script) && $script == 'Trips')?'active':'';?>"><b><img alt="" src="assets/img/my-trips.png"></b><span><?=$langage_lbl['LBL_HEADER_TOPBAR_TRIPS_TEXT'];?></span></a></li>
              <?php } else { ?>
              <li><a href="driver-trip" class="<?=(isset($script) && $script == 'Trips')?'active':'';?>"><b><img alt="" src="assets/img/my-trips.png"></b><span><?=$langage_lbl['LBL_HEADER_TOPBAR_TRIPS_TEXT'];?></span></a></li>
              <?php } ?>
					<!-- codeEdited remove my earnings link from provider login
          		<li><a href="payment-request" class="<?=(isset($script) && $script == 'Payment Request')?'active':'';?>"><b><img alt="" src="assets/img/myearnings.png"></b><span><?=$langage_lbl['LBL_HEADER_MY_EARN']; ?></span></a></li> -->
							<?php if($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') {
                if($WALLET_ENABLE == 'Yes'){ ?> 
								<li><a href="provider_wallet" class="<?=(isset($script) && $script == 'Rider Wallet')?'active':'';?>"><b><img alt="" src="assets/img/my-wallet.png"></b><span><?=$langage_lbl['LBL_RIDER_WALLET'];?></span></a></li>
							<? }
              } else { 
              if($WALLET_ENABLE == 'Yes'){ ?> 
                <li><a href="driver_wallet" class="<?=(isset($script) && $script == 'Rider Wallet')?'active':'';?>"><b><img alt="" src="assets/img/my-wallet.png"></b><span><?=$langage_lbl['LBL_RIDER_WALLET'];?></span></a></li>
              <? }
              } ?>
							<li class="logout"><a href="logout"><b><img alt="" src="assets/img/sign-out.png"></b><span><?=$langage_lbl['LBL_LOGOUT'];?></span></a></li>

				  <?php } else if($user == 'company') {
$user_id=$_SESSION['sess_iUserId'];
$query="SELECT doc_file FROM `document_list` where doc_usertype='company' and doc_userid='$user_id'";
  $documents = $obj->MySQLSelect($query);

  if (count($documents)!=0||$db_data[0]['eStatus']=='Active') {
  
  
            ?>
             <li><a href="company_dashboard.php" class="<?=(isset($script) && $script == 'company_dashboard')?'active':'';?>"><b class="icon-dashboard" style="font-size:24px"></b><span>Dashboard</span></a></li>

					<!--   <li><a href="approveDocuments.php" class="<?=(isset($script) && $script == 'Approve Documents')?'active':'';?>"><b><img alt="" src="assets/img/my-profile.png"></b><span>Uploaded Document</span></a></li> -->

							 <li><a href="profile" class="<?=(isset($script) && $script == 'Profile')?'active':'';?>"><b><img alt="" src="assets/img/my-profile.png"></b><span><?=$langage_lbl['LBL_HEADER_TOPBAR_PROFILE_TITLE_TXT'];?></span></a></li>
							<? if($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'UberX'){?>
                 <li><a href="providerlist" class="<?=(isset($script) && $script == 'Driver')?'active':'';?>"><b><img alt="" src="assets/img/my-profile-icon.png"></b><span><?=$langage_lbl['LBL_HEADER_TOPBAR_DRIVER']; ?></span></a></li>
              <? } else { ?>
                <li><a href="driverlist" class="<?=(isset($script) && $script == 'Driver')?'active':'';?>"><b><img alt="" src="assets/img/my-profile-icon.png"></b><span><?=$langage_lbl['LBL_HEADER_TOPBAR_DRIVER']; ?></span></a></li>
              <? } ?>
							<?php if($APP_TYPE != 'UberX'){ ?>
							<li><a href="vehicle" class="<?=(isset($script) && $script == 'Vehicle')?'active':'';?>"><b><img alt="" src="assets/img/my-taxi.png"></b><span><?=$langage_lbl['LBL_HEADER_TOPBAR_VEHICLES'];?></span></a></li>
							<?php } ?>

               <li><a href="assign-to-providers" ><b ><img alt="" src="assets/img/my-taxi.png"></b><span>Assign to Providers</span></a></li>
 <li><a href="tokens" ><b class="fa fa-dot-circle-o icon-user1"></b> <span>Tokens</span></a></li>
   <li id="reports"><a href="#" ><b class="fa fa-files-o icon-user1"></b><span>Reports</span><span style="float: right;" class="fa fa-angle-down"></span></a>



<li class="report" style="margin-left: 20px;"><a href="company_cancelled_trip.php" ><b class="fa fa-file-o icon-user1"></b><span> Cancelled Trips </span></a> </li>
<li class="report" style="margin-left: 20px;"><a href="company_payment_report.php" ><b class="fa fa-file-o icon-user1"></b><span> Payment Report </span></a> </li>
<li class="report" style="margin-left: 20px;"><a href="company_driver_log_report.php" ><b class="fa fa-file-o icon-user1"></b><span>Provider Log Report </span></a> </li>
<li class="report" style="margin-left: 20px;"><a href="company_trip_detail.php" ><b class="fa fa-file-o icon-user1"></b><span>Total Earning Report</span></a></li>

<li class="report" style="margin-left: 20px;"><a href="wallet-report" ><b class="fa fa-file-o icon-user1"></b><span>Provider Wallet Report</span></a></li>

<li class="report" style="margin-left: 20px;"><a href="company_driver_trip_detail.php" ><b class="fa fa-file-o icon-user1"></b><span>Trip/Job Time Variance </span></a> </li>
<li class="report" style="margin-left: 20px;"><a href="sales" ><b class="fa fa-file-o icon-user1"></b><span>Token Sales to Provider</span></a></li>
<li class="report" style="margin-left: 20px;"><a href="company_driver_pay_report.php" ><b class="fa fa-file-o icon-user1"></b><span> Provider Payment Report </span></a> </li>




<li class="report" style="margin-left: 20px;"><a href="company_cancellation_payment_report.php" ><b class="fa fa-file-o icon-user1"></b><span>Cancellation Payment Report </span></a> </li>

   </li>


<li><a href="review.php" class="<?=(isset($script) && $script == 'Review')?'active':'';?>"><b class="fa fa-comments-o"></b><span><?=$langage_lbl['LBL_HEADER_TOPBAR_REVIEW'];?></span></a></li>
  
  <li id="report_statistics"><a href="#" class="<?=(isset($script) && $script == 'statistics')?'active':'';?>"><b class="fa fa-sitemap" aria-hidden="true"></b> <span><?=$langage_lbl['LBL_HEADER_TOPBAR_ANALYTICS'];?></span><span style="float: right;" class="fa fa-angle-down"></span></a>

<li class="report_statistics" style="margin-left: 20px;"><a href="statisticsReportUsersForCompany.php" ><b class="fa fa-file-o icon-user1"></b><span>User</span></a></li>
<li class="report_statistics" style="margin-left: 20px;"><a href="statisticsBookingsCompany.php" ><b class="fa fa-file-o icon-user1"></b><span>Bookings</span></a></li>
<li class="report_statistics" style="margin-left: 20px;"><a href="statisticsReportActiveServicesCompany.php" ><b class="fa fa-file-o icon-user1"></b><span>Active Services</span></a></li>

<li class="report_statistics" style="margin-left: 20px;"><a href="statisticsReportTopPerformersCompany.php" ><b class="fa fa-file-o icon-user1"></b><span>Top Performers</span></a></li>
<li class="report_statistics" style="margin-left: 20px;"><a href="statisticsReportOnlineProvidersCompany.php" ><b class="fa fa-file-o icon-user1"></b><span>Online Providers</span></a></li>

<li class="report_statistics" style="margin-left: 20px;"><a href="statisticsReportActiveProvidersCompany.php" ><b class="fa fa-file-o icon-user1"></b><span>Active Providers</span></a></li>






  </li>


							<li><a href="company-trip" class="<?=(isset($script) && $script == 'Trips')?'active':'';?>"><b><img alt="" src="assets/img/my-trips.png"></b><span><?=$langage_lbl['LBL_HEADER_TOPBAR_TRIPS'];?></span></a></li>
							<!--<li><a href="booking.php" class="<?=(isset($script) && $script == 'Booking')?'active':'';?>">My Bookings</a></li>-->
            <?php }else
            {

              ?>
              <li ><a href="profile"><b></b><span>Please Upload Required Documents.</span></a></li>
              <?php
            } ?>
						<!-- 	<li class="logout"><a href="logout"><b><img alt="" src="assets/img/sign-out.png"></b><span><?=$langage_lbl['LBL_LOGOUT'];?></span></a></li> -->
							
				  <?php  } else if($user == 'rider') {?>
              <?php if($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'UberX') {  ?>
                 <li><a href="<?php echo $tconfig['tsite_url']; ?>profile-user" class="<?=(isset($script) && $script == 'Profile')?'active':'';?>"><b><img alt="" src="assets/img/my-profile.png"></b><span><?=$langage_lbl['LBL_HEADER_TOPBAR_PROFILE_TITLE_TXT'];?></span></a></li>
              <?php } else { ?>
                 <li><a href="<?php echo $tconfig['tsite_url']; ?>profile-rider" class="<?=(isset($script) && $script == 'Profile')?'active':'';?>"><b><img alt="" src="assets/img/my-profile.png"></b><span><?=$langage_lbl['LBL_HEADER_TOPBAR_PROFILE_TITLE_TXT'];?></span></a></li>
              <? }?>
							
              <li><a href="<?php echo $tconfig['tsite_url']; ?>mytrip" class="<?=(isset($script) && $script == 'Trips')?'active':'';?>"><b><img alt="" src="assets/img/my-trips.png"></b><span><?=$langage_lbl['LBL_HEADER_TOPBAR_TRIPS'];?></span></a></li>
							<!-- <li><a href="<?php echo $tconfig['tsite_url']; ?>mobi" ><b><img alt="" src="assets/img/my-taxi.png"></b><span><?=$langage_lbl['LBL_BOOK_A_RIDE'];?></span></a></li> -->
              <? if($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'UberX' ) { 
               if($WALLET_ENABLE == 'Yes'){ ?> 
           <!--   codeEdited for remvoe wallet from rider

            <li><a href="<?php echo $tconfig['tsite_url']; ?>user_wallet" class="<?=(isset($script) && $script == 'Rider Wallet')?'active':'';?>"><b><img alt="" src="assets/img/my-wallet.png"></b><span><?=$langage_lbl['LBL_RIDER_WALLET'];?></span></a></li> -->
              <? }
               } else { 
                if($WALLET_ENABLE == 'Yes'){ ?>
                  <li><a href="<?php echo $tconfig['tsite_url']; ?>rider_wallet" class="<?=(isset($script) && $script == 'Rider Wallet')?'active':'';?>"><b><img alt="" src="assets/img/my-wallet.png"></b><span><?=$langage_lbl['LBL_RIDER_WALLET'];?></span></a></li>
              <?php } } ?>
			  <li><a href="<?php echo $tconfig['tsite_url']; ?>barangay.php?userId=<?=$_SESSION['sess_iUserId']?>" class="<?=(isset($script) && $script == 'Rider Wallet')?'active':'';?>"><b><img alt="" src="https://www.aiocity.com//webimages//icons//VehicleCategory//287//android//ic_car_EN_1567612696.png"></b><span>Barangay Registration</span></a></li>
							<li class="logout"><a href="logout"><b><img alt="" src="assets/img/sign-out.png"></b><span><?=$langage_lbl['LBL_LOGOUT'];?></span></a></li>
			<?php }
				 } ?>
			<?php if($user==""){ ?>

			<b>
			  <a href="sign-up-rider" class="<?=(isset($script) && $script == 'Rider Sign-Up')?'active':'';?>"><?=$langage_lbl['LBL_LEFTMENU_SIGN_UP_TO_RIDE']; ?></a>
			  <a class="<?=(isset($script) && $script == 'Driver Sign-Up')?'active':'';?>" href="sign-up.php"><?=$langage_lbl['LBL_LEFTMENU_BECOME_A_DRIVER']; ?></a>
			</b>

			<?php } ?>

			<div style="clear:both;"></div>
    </div>
    </span>
    <span class="mobile">
      <div class="menu-logo">
    		<section id="navBtn" class="navBtnNew navOpen" onClick="menuClose()">
    		  <div></div>
    		  <div></div>
    		  <div></div>
    		</section>
    		<img src="assets/img/<?php echo $logo;?>" alt="">
      </div>
      <!-- Top Menu Mobile -->
      <div class="menu-left-new">
        <?php if($user == 'driver'){ ?>

          <li><a href="profile" class="<?=(isset($script) && $script == 'Profile')?'active':'';?>"><?=$langage_lbl['LBL_MY_PROFILE_HEADER_TXT']; ?></a></li>
          <li><a href="vehicle" class="<?=(isset($script) && $script == 'Vehicle')?'active':'';?>"><?=$langage_lbl['LBL_LEFT_MENU_VEHICLES']; ?></a></li>
          <? if($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') { ?>
            <li><a href="provider-job" class="<?=(isset($script) && $script == 'Trips')?'active':'';?>"><?=$langage_lbl['LBL_LEFT_MENU_TRIPS']; ?></a></li>
          <? } else { ?>
            <li><a href="driver-trip" class="<?=(isset($script) && $script == 'Trips')?'active':'';?>"><?=$langage_lbl['LBL_LEFT_MENU_TRIPS']; ?></a></li>
          <? } ?>
          <li><a href="payment-request" class="<?=(isset($script) && $script == 'Payment Request')?'active':'';?>"><?=$langage_lbl['LBL_PAYMENT']; ?></a></li>

        <?php } else if($user == 'company'){ ?>

              <li><a href="profile" class="<?=(isset($script) && $script == 'Profile')?'active':'';?>"> <?=$langage_lbl['LBL_MY_PROFILE_HEADER_TXT']; ?></a></li>
              <li><a href="driver" class="<?=(isset($script) && $script == 'Driver')?'active':'';?>"><?=$langage_lbl['LBL_DRIVER']; ?></a></li>
              <li><a href="vehicle" class="<?=(isset($script) && $script == 'Vehicle')?'active':'';?>"><?=$langage_lbl['LBL_LEFT_MENU_VEHICLES']; ?></a></li>
              <li><a href="company-trip" class="<?=(isset($script) && $script == 'Trips')?'active':'';?>"><?=$langage_lbl['LBL_LEFT_MENU_TRIPS']; ?></a></li>

        <?php } else if($user == 'rider') { ?>
              <?php if($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'UberX') {  ?>
                <li><a href="profile-user" class="<?=(isset($script) && $script == 'Profile')?'active':'';?>"><?=$langage_lbl['LBL_MY_PROFILE_HEADER_TXT']; ?></a></li>
              <?php } else { ?>
                <li><a href="profile-rider" class="<?=(isset($script) && $script == 'Profile')?'active':'';?>"><?=$langage_lbl['LBL_MY_PROFILE_HEADER_TXT']; ?></a></li>
              <? }?>
              <li><a href="mytrip" class="<?=(isset($script) && $script == 'Trips')?'active':'';?>"><?=$langage_lbl['LBL_LEFT_MENU_TRIPS']; ?></a></li>

         <?php } ?>
          <!-- End Top Menu Mobile -->

        <li><a href="index.php" class="<?=(isset($script) && $script == 'Home')?'active':'';?>"><?=$langage_lbl['LBL_HOME']; ?></a></li>
        <li><a href="about-us" class="<?=(isset($script) && $script == 'About Us')?'active':'';?>"><?=$langage_lbl['LBL_ABOUT_US_TXT']; ?></a></li>
        <li><a href="help-center" class="<?=(isset($script) && $script == 'Help Center')?'active':'';?>"><?=$langage_lbl['LBL_HELP_CENTER']; ?></a></li>
        <li><a href="contact-us" class="<?=(isset($script) && $script == 'Contact Us')?'active':'';?>"><?=$langage_lbl['LBL_CONTACT_US_TXT']; ?></a></li>

        <?php  if($user=="") {  ?>

        <li><a href="sign-in" class="<?=(isset($script) && $script == 'Login Main')?'active':'';?>"><?=$langage_lbl['LBL_LEFT_MENU_LOGIN']; ?></a></li>
        <b>
          <a href="sign-up-rider" class="<?=(isset($script) && $script == 'Rider Sign-Up')?'active':'';?>"><?=$langage_lbl['LBL_LEFTMENU_SIGN_UP_TO_RIDE']; ?></a>
          <a class="<?=(isset($script) && $script == 'Driver Sign-Up')?'active':'';?>" href="sign-up.php"><?=$langage_lbl['LBL_LEFTMENU_BECOME_A_DRIVER']; ?></a>
        </b>

        <?php  } else { ?>

          <li><a href="logout"><?=$langage_lbl['LBL_LOGOUT']; ?></a></li>
          <div style="clear:both;"></div>

        <?php } ?>

      </div> 
    </span>
  </ul>
</nav>
<script type="text/javascript">
  
$(document).ready(function(){

  $(".report").hide();
  $("#reports").click(function(){

  $(".report_statistics").hide('30');

  $(".report").toggle('30');

  });



  $(".report_statistics").hide();
  $("#report_statistics").click(function(){

  $(".report").hide('30');

  $(".report_statistics").toggle('30');

  });

});

</script>
