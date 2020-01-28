<?php
include_once('common.php');
$generalobj->check_member_login();


    $BarangayId=$_SESSION['sess_iCompanyId'];
     $token_query="SELECT sum(`Tokens`) as Credit FROM `barangay_tokens` where Type='Credit' and BarangayId='$BarangayId'";
$Credit=$obj->MySQLSelect($token_query);
   $token_query="SELECT sum(`Tokens`) as Debit FROM `barangay_tokens` where Type='Debit' and BarangayId='$BarangayId'";
$Debit=$obj->MySQLSelect($token_query);

$Available_token=$Credit[0]['Credit']-$Debit[0]['Debit'];
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$hdn_del_id = isset($_REQUEST['hdn_del_id']) ? $_REQUEST['hdn_del_id'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'view';
$var_msg = isset($_REQUEST["var_msg"]) ? $_REQUEST["var_msg"] : '';
$iCompanyId = $_SESSION['sess_iUserId'];

$sql = "select * from country";
$db_country = $obj->MySQLSelect($sql);

$sql = "select * from language_master where eStatus = 'Active'";
$db_lang = $obj->MySQLSelect($sql);

$sql="select count(case when (numberofuploadeddoc is NULL or numberofuploadeddoc<numberofaasignedservices) and rd.eStatus!='Active' then 1 else null end) as Open ,count(case when rd.eStatus='Active' then 1 else null end) as Active,count(case when numberofuploadeddoc is NOT NULL and numberofuploadeddoc>=numberofaasignedservices and rd.eStatus!='Active' then 1 else null end) as Pending,count(case when numberofuploadeddoc is NOT NULL and numberofuploadeddoc>=numberofaasignedservices and rd.eStatus='Inactive' then 1 else null end) as Inactive from register_driver rd left OUTER join (SELECT count(1) numberofaasignedservices, `iDriverId` FROM `driver_registered_service` GROUP by iDriverId) t1 on rd.iDriverId=t1.iDriverId left OUTER join (SELECT COUNT(1) as numberofuploadeddoc, `doc_id`, `doc_masterid`, `doc_usertype`, `doc_userid`, `ex_date`, `doc_file`, `status`, `edate` FROM `document_list` WHERE doc_usertype='Driver' GROUP by doc_userid,doc_id) t2 on t1.iDriverId=t2.doc_userid where rd.iCompanyId='$BarangayId' AND rd.eStatus!='Deleted'";

	$data_status = $obj->MySQLSelect($sql);

$script = 'Driver';
if (strtolower($action) == 'delete') {
	if(SITE_TYPE != 'Demo')
	{
		$query = "UPDATE register_driver SET eStatus = 'Deleted' WHERE iDriverId = '" . $hdn_del_id . "'";
		$obj->sql_query($query);
		$var_msg = $langage_lbl['LBL_COMPNAY_FRONT_DELETE_TEXT'];
		header("Location:driver.php?success=1&var_msg=". $var_msg);
		exit();
	} else {
		header("Location:driver.php?success=2");
		exit();
	}
}

if (strtolower($action) == 'inactive' ||strtolower($action) == 'active') {
	if(SITE_TYPE != 'Demo')
	{
		$hdn_suspend_id=isset($_REQUEST['hdn_suspend_id'])?$_REQUEST['hdn_suspend_id']:'';
		$query = "UPDATE register_driver SET eStatus = '$action' WHERE iDriverId = '" . $hdn_suspend_id . "'";
		echo $query;
		$obj->sql_query($query);
		
		header("Location:driver.php?success=1&var_msg=Provider ".$action."d successfully");
		exit();
	} else {
		header("Location:driver.php?success=2");
		exit();
	}
}
	
if ($_POST['action'] == "addmoney") {
      $iBalance = $_REQUEST['iBalance'];

  if ($Available_token>= $iBalance) {
    # code...

    $eUserType = $_REQUEST['eUserType'];
      $iUserId = $_REQUEST['iDriverId'];   
    $eFor = $_REQUEST['eFor'];
    $eType = $_REQUEST['eType'];
    $iTripId = 0;
    $tDescription = '#LBL_AMOUNT_CREDIT#';  
    $ePaymentStatus = 'Settelled';
    $dDate = Date('Y-m-d H:i:s');
    $BarangayId=$_SESSION['sess_iCompanyId'];
    $generalobj->InsertIntoUserWallet($iUserId, $eUserType, $iBalance, $eType, $iTripId, $eFor, $tDescription, $ePaymentStatus, $dDate);    
$debit_query="INSERT INTO `barangay_tokens`(`BarangayId`, `Tokens`, `Type`, `TDate`, `DriverId`) VALUES ('$BarangayId','$iBalance','Debit','$dDate','$iUserId')";
 $obj->MySQLSelect($debit_query);
 $_POST['action']="";
    $var_msg = 'Added tokens successfully';   
  header("Location:"."providerlist?success=1&var_msg=".$var_msg); exit;
   //exit;
  }
  else{
      
  $var_msg='You do not have enough tokens';   
  header("Location:"."providerlist?error=1&var_msg=".$var_msg); exit;


  }
}

if ($_POST['action'] =='adjustmoney') 
{
	 $iBalance = $_REQUEST['txtAdjustTokens'];
	 $eUserType = $_REQUEST['eUserType'];
      $iUserId = $_REQUEST['iDriverId'];   
    $eFor = 'Withdrawl';
    $eType = $_REQUEST['eType'];
    $iTripId = 0;
    $tDescription = '#LBL_ADJUSTED_BY_COMPANY#';  
    $ePaymentStatus = 'Settelled';
    $dDate = Date('Y-m-d H:i:s');
    $BarangayId=$_SESSION['sess_iCompanyId'];

     $generalobj->InsertIntoUserWallet($iUserId, $eUserType, $iBalance, $eType, $iTripId, $eFor, $tDescription, $ePaymentStatus, $dDate);   
     $ref_No='Adjusted Provider`s Tokens'; 

$debit_query="INSERT INTO `barangay_tokens`(`BarangayId`, `Tokens`, `Type`, `TDate`, `DriverId`,`ref_No`,`payment`,`payment_status`,`commission`) VALUES ('$BarangayId','$iBalance','Credit','$dDate','$iUserId','$ref_No','0','','0')";
 $obj->MySQLSelect($debit_query);
 $_POST['action']="";
    $var_msg = 'Tokens adjusted successfully';   
  header("Location:"."providerlist?success=1&var_msg=".$var_msg); exit;
}

	
$vName = isset($_POST['vName']) ? $_POST['vName'] : '';
$vLname = isset($_POST['vLname']) ? $_POST['vLname'] : '';
$vEmail = isset($_POST['vEmail']) ? $_POST['vEmail'] : '';
$vPassword = isset($_POST['vPassword']) ? $_POST['vPassword'] : '';
$vPhone = isset($_POST['vPhone']) ? $_POST['vPhone'] : '';
$vCode = isset($_POST['vCode']) ? $_POST['vCode'] : '';
$vCountry = isset($_POST['vCountry']) ? $_POST['vCountry'] : '';
$vLang = isset($_POST['vLang']) ? $_POST['vLang'] : '';
$vPass = $generalobj->encrypt($vPassword);
$eStatus = isset($_POST['eStatus']) ? $_POST['eStatus'] : '';
$tbl_name = "register_driver";
	
if (isset($_POST['submit'])) {
	$q = "INSERT INTO ";
	$where = '';
	
	if ($action == 'Edit') {
		$eStatus = ", eStatus = 'Inactive' ";
	} else {
		$eStatus = '';
	}
	
	if ($id != '') {
		$q = "UPDATE ";
		$where = " WHERE `iDriverId` = '" . $id . "'";
	}
	
	$query = $q . " `" . $tbl_name . "` SET
    `vName` = '" . $vName . "',
    `vLastName` = '" . $vLname . "',
    `vCountry` = '" . $vCountry . "',
    `vCode` = '" . $vCode . "',
    `vEmail` = '" . $vEmail . "',
    `vLoginId` = '" . $vEmail . "',
    `vPassword` = '" . $vPass . "',
    `vPhone` = '" . $vPhone . "',
    `vLang` = '" . $vLang . "',
    `eStatus` = '" . $eStatus . "',
    `iCompanyId` = '" . $iCompanyId . "'" . $where;
	$obj->sql_query($query);

	$id = ($id != '') ? $id : $obj->GetInsertId();
	if(SITE_TYPE != 'Demo'){
		if ($action == 'Edit') {
			$var_msg = $langage_lbl['LBL_COMPNAY_FRONT_UPDATE_DRIVER_TEXT'];
			header("Location:driver.php?id=" . $id . "&success=1&var_msg=". $var_msg);
			exit;
      	} else {
      		$var_msg = $langage_lbl['LBL_COMPNAY_FRONT_ADD_DRIVER_TEXT'];
			header("Location:driver.php?id=" . $id . "&success=1&var_msg=". $var_msg);
			exit;
	    }
	} else {
		header("Location:driver.php?success=2");
		exit;
	}
}
$dri_ssql = "";
if (SITE_TYPE == 'Demo') {
	$dri_ssql = " And tRegistrationDate > '" . WEEK_DATE . "'";
}


$option = isset($_REQUEST['option'])?stripslashes($_REQUEST['option']):"";
$keyword = isset($_REQUEST['keyword'])?stripslashes($_REQUEST['keyword']):"";
$eStatus = isset($_REQUEST['eStatus']) ? $_REQUEST['eStatus'] : "";

$subQuery="";
if ($eStatus!="") {
if($eStatus=="Open")	
 $subQuery.= " and	(numberofuploadeddoc is NULL or numberofuploadeddoc<numberofaasignedservices) and rd.eStatus!='Active'  and rd.eStatus!='Deleted' ";
else if($eStatus=="Pending")
	 $subQuery.= " and	 numberofuploadeddoc is NOT NULL and numberofuploadeddoc>=numberofaasignedservices and rd.eStatus!='Active' and rd.eStatus!='Deleted' ";
else if($eStatus=="Inactive")
	 $subQuery.= " and	 numberofuploadeddoc is NOT NULL and numberofuploadeddoc>=numberofaasignedservices and rd.eStatus='Inactive' and rd.eStatus!='Deleted' ";
	else 
		    $subQuery.= " AND rd.eStatus = '".$generalobj->clean($eStatus)."'";

}

if($keyword != '') {
    $keyword_new = $keyword;
/*    if($execute) {

   // $chracters = array("(", "+", ")");
   // $removespacekeyword =  preg_replace('/\s+/', '', $keyword);
   // $keyword_new = trim(str_replace($chracters, "", $removespacekeyword));
  }*/



    if($option != '') {
        $option_new = $option;
        if($option == 'MobileNumber'){
          $option_new = "CONCAT(rd.vCode,'',rd.vPhone)";
        }
        if($option == 'DriverName'){
          $option_new = "CONCAT(vName,' ',rd.vLastName)";
        } 
        if($eStatus != ''){
           // $ssql.= " AND ".stripslashes($option_new)." LIKE '%".$generalobj->clean($keyword_new)."%' AND rd.eStatus = '".$generalobj->clean($eStatus)."'";
                 $ssql.= " AND ".stripslashes($option_new)." LIKE '%".$generalobj->clean($keyword_new)."%' ";
        } else {
            $ssql.= " AND ".stripslashes($option_new)." LIKE '%".$generalobj->clean($keyword_new)."%'";
        }
    } else {
      if($eStatus != ''){
        $ssql.= " AND (concat(rd.vName,' ',rd.vLastName) LIKE '%".$generalobj->clean($keyword_new)."%' OR  rd.vEmail LIKE '%".$generalobj->clean($keyword_new)."%' OR (concat(rd.vCode,'',rd.vPhone) LIKE '%".$generalobj->clean($keyword_new)."%')) ";
        // $ssql.= " AND (concat(rd.vName,' ',rd.vLastName) LIKE '%".$generalobj->clean($keyword_new)."%' OR  rd.vEmail LIKE '%".$generalobj->clean($keyword_new)."%' OR (concat(rd.vCode,'',rd.vPhone) LIKE '%".$generalobj->clean($keyword_new)."%')) AND rd.eStatus = '".$generalobj->clean($eStatus)."'";
      } else {
        $ssql.= " AND (concat(rd.vName,' ',rd.vLastName) LIKE '%".$generalobj->clean($keyword_new)."%' OR rd.vEmail LIKE '%".$generalobj->clean($keyword_new)."%' OR (concat(rd.vCode,'',rd.vPhone) LIKE '%".$generalobj->clean($keyword_new)."%'))";
      }
  }
} else if($eStatus != '' && $keyword == '') {
   //  $ssql.= " AND rd.eStatus = '".$generalobj->clean($eStatus)."'";
}else
{
	     $ssql.= " and rd.eStatus != 'Deleted'";
}


if ($action == 'view') {
	//$sql = "SELECT * FROM register_driver rd where rd.iCompanyId = '" . $iCompanyId . "' $ssql $dri_ssql order by tRegistrationDate DESC,vName asc,MiddleName asc,vLastName asc";

	//count(case when (numberofuploadeddoc is NULL or numberofuploadeddoc<numberofaasignedservices) and rd.eStatus!='Active' then 1 else null end) as Open ,count(case when rd.eStatus='Active' then 1 else null end) as Active,count(case when numberofuploadeddoc is NOT NULL and numberofuploadeddoc>=numberofaasignedservices and rd.eStatus!='Active' then 1 else null end) as Pending,count(case when numberofuploadeddoc is NOT NULL and numberofuploadeddoc>=numberofaasignedservices and rd.eStatus='Inactive' then 1 else null end) as Inactive

$sql = "SELECT rd.*,
case when (numberofuploadeddoc is NULL or numberofuploadeddoc<numberofaasignedservices) and rd.eStatus!='Active' then 'Open' when rd.eStatus='Active' then 'Active' when numberofuploadeddoc is NOT NULL and numberofuploadeddoc>=numberofaasignedservices and rd.eStatus!='Active' then 'Pending' when numberofuploadeddoc is NOT NULL and numberofuploadeddoc>=numberofaasignedservices and rd.eStatus='Inactive' then 'Inactive' else null end as provider_status 
 FROM register_driver rd left OUTER join (SELECT count(1) numberofaasignedservices, `iDriverId` FROM `driver_registered_service` GROUP by iDriverId) t1 on rd.iDriverId=t1.iDriverId left OUTER join (SELECT COUNT(1) as numberofuploadeddoc, `doc_id`, `doc_masterid`, `doc_usertype`, `doc_userid`, `ex_date`, `doc_file`, `status`, `edate` FROM `document_list` WHERE doc_usertype='Driver' GROUP by doc_userid,doc_id) t2 on t1.iDriverId=t2.doc_userid  where rd.iCompanyId = '" . $iCompanyId . "' $ssql $subQuery $dri_ssql order by rd.tRegistrationDate DESC,rd.vName asc,rd.MiddleName asc,rd.vLastName asc";

//echo $sql;
	
	$data_drv = $obj->MySQLSelect($sql);
    
    
    
	$sql1 = "SELECT doc_masterid as total FROM `document_master` WHERE `doc_usertype` ='driver' AND status = 'Active'";
	$doc_count_query = $obj->MySQLSelect($sql1);
	$doc_count = count($doc_count_query);
}
if ($action == 'edit') {
	// echo "<script>document.getElementById('cancel-add-form').style.display='';document.getElementById('show-add-form').style.display='none';document.getElementById('add-hide-div').style.display='none';</script>";
}





?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title><?=$SITE_NAME?> | <?=$langage_lbl['LBL_VEHICLE_DRIVER_TXT_ADMIN']; ?></title>
		<!-- Default Top Script and css -->
		<?php include_once("top/top_script.php");?>
		<link rel="stylesheet" type="text/css" href="admin/css/admin_new/admin_style.css">
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
			<!-- Driver page-->
			<div class="page-contant">
				<div class="page-contant-inner">
					<h2 class="header-page-d1 trip-detail driver-detail1"><?=$langage_lbl['LBL_DRIVER_COMPANY_TXT']; ?><a href="javascript:void(0);" onClick="add_driver_form();"><?=$langage_lbl['LBL_ADD_DRIVER_COMPANY_TXT']; ?></a></h2>
					<!-- driver list page -->
					<div class="trips-page trips-page1">
						<? if ($_REQUEST['success']==1) {?>
							<div class="alert alert-success alert-dismissable">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button> 
								<?= $var_msg ?>
							</div>
							<?}else if($_REQUEST['error']==1){ ?>
							<div class="alert alert-danger alert-dismissable">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<?= $var_msg ?>
							</div>
							<?php 
							}
							else if($_REQUEST['success']==2){ ?>
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
						<div class="trips-table trips-table-driver trips-table-driver-res"> 
							<div class="trips-table-inner">
								<!-- <div class="driver-trip-table"> -->
			<label>Search ...</label>

<div class="row">

	<div class="col-lg-2">
		 <select name="option" id="option" class="form-control">
                                          <option value="">All</option>
                                          <option  value="DriverName" <?php if ($option == "DriverName") { echo "selected"; } ?> ><?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Name</option>
                                       
                                          <option value="vEmail" <?php if ($option == 'vEmail') {echo "selected"; } ?> >E-mail</option>
                                          <option value="MobileNumber" <?php if ($option == 'MobileNumber') {echo "selected"; } ?> >Mobile</option>
                                      
                                    </select>
	</div>
	<div class="col-lg-3">
		<input type="Text" id="keyword" name="keyword" value="<?php echo $generalobj->clearName($keyword); ?>"  class="form-control" />
	</div>
	<div class="col-lg-2">
		<select name="eStatus" id="estatus_value" class="form-control">
                                            <option value="" >Select Status</option>
                                            <option value='Active' <?php if ($eStatus == 'Active') { echo "selected"; } ?> >Active</option>
                                             <option value="Open" <?php if ($eStatus == 'Open') {echo "selected"; } ?> >Open</option>
                                            <option value="Pending" <?php if ($eStatus == 'Pending') {echo "selected"; } ?> >Pending</option>
                                            <option value="Inactive" <?php if ($eStatus == 'Inactive') {echo "selected"; } ?> >Inactive</option>
                                            <option value="Deleted" <?php if ($eStatus == 'Deleted') {echo "selected"; } ?> >Delete</option>
                                        </select>
	</div>
	<div class="col-lg-4">
		  <input type="button" value="Search" class="btn btnalt button11" id="Search" name="Search" title="Search" />
                                      <input type="button" value="Reset" class="btn btnalt button11" onClick="window.location.href='driver.php'"/>
	</div>

</div> 
<br>
<center>
<div style="margin: auto;">	
	<a href="driver.php?eStatus=Active">  <span class="status_active status">Active </span> <b> <?=$data_status[0]['Active'];?></b></a>
	<a href="driver.php?eStatus=Open"><span class="status_open status">Open </span> <b> <?=$data_status[0]['Open'];?></b></a>
	<a href="driver.php?eStatus=Pending"> <span class="status_pending status">Pending </span><b> <?=$data_status[0]['Pending'];?></b></a>
	<a href="driver.php?eStatus=Inactive"> <span class="status_inactive status">Inactive </span><b> <?=$data_status[0]['Inactive'];?></b></a>

</div>
</center>
<br>


									<table class="table"   cellpadding="0" cellspacing="1" id="dataTables-example" aria-describedby="dataTables-example_info" style="width: 100%;">
										<thead>
											<tr>

																								<th ><?=$langage_lbl['LBL_USER_NAME_HEADER_SLIDE_TXT']; ?></th>

												<th ><?=$langage_lbl['LBL_DRIVER_EMAIL_LBL_TXT']; ?></th>
												
												<th ><?=$langage_lbl['LBL_MOBILE_NUMBER_HEADER_TXT']; ?></th>
											
								           <th>Status</th>
								           <th>Tokens</th>

												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<? for ($i = 0; $i < count($data_drv); $i++) { ?>
												<tr class="gradeA">
													<td width="20%"><?= $generalobj->clearName($data_drv[$i]['vName'] . ' '.$data_drv[$i]['MiddleName'].' ' . $data_drv[$i]['vLastName']); ?></td>
													<td width="20%"><?= $generalobj->clearEmail($data_drv[$i]['vEmail']); ?></td>
													<td width="20%">
														<?php if(!empty($data_drv[$i]['vPhone'])){?>
						                                (+<?= $data_drv[$i]['vCode'];?>) 
						                                <?= $generalobj->clearMobile($data_drv[$i]['vPhone']);?>
						                                <?php } ?>
                                 					</td>
													
<td align="center" width="20%">
<?php 
if($eStatus!="")
{


	if($eStatus=="Open")	
	$status="<span class='badge status_open' > $eStatus</span>";

else if($eStatus=="Pending")
		$status="<span class='badge status_pending' > $eStatus</span>";

else if($eStatus=="Inactive")
		$status="<span class='badge status_inactive' > $eStatus</span>";

	else 
		   	$status="<span class='badge status_active' > $eStatus</span>";


}else
{



	if($data_drv[$i]['provider_status']=="Open")	
	$status="<span class='badge status_open' > ".$data_drv[$i]['provider_status']."</span>";

else if($data_drv[$i]['provider_status']=="Pending")
		$status="<span class='badge status_pending' > ".$data_drv[$i]['provider_status']."</span>";

else if($data_drv[$i]['provider_status']=="Inactive")
		$status="<span class='badge status_inactive' > ".$data_drv[$i]['provider_status']."</span>";

	else 
		   	$status="<span class='badge status_active' > ".$data_drv[$i]['provider_status']."</span>";

	
/*	$status="<span class='badge status_active'>Active</span>";
	if(strtolower(trim($data_drv[$i]['eStatus']))=="inactive")
$status="<span class='badge status_inactive' > Inactive</span>";
else if(strtolower(trim($data_drv[$i]['eStatus']))=="deleted")
	$status="<span class='badge success-vehicle-inactive' > Deleted</span>";
	*/

}

echo $status;
?>
	
</td>
								           <td width="20%" align="center" class="td_available_balance">
<!--ADDING TOKENS TO Provider account-->

    <div class="row tokensRow">
    	<div class="col-sm-3">

 <?php $user_available_balance = $generalobj->get_user_available_balance($data_drv[$i]['iDriverId'], "Driver");

echo "<label class='available_balance'>".$user_available_balance."</label><br>";
    ?>
    	</div>
    	<div class="col-sm-2 tokensDiv">
 <input type="button" name="addTokens" id="addTokens" onclick="Add_money_driver('<?php echo $data_drv[$i]['iDriverId'];?>')" class="btn btn-sm btn-primary addTokens"/ value="Add">
</div><div class="col-sm-2 tokensDiv">
  <input type="button" name="AdjustTokens" id="AdjustTokens" onclick="Adjust_money_driver('<?php echo $data_drv[$i]['iDriverId'];?>')"  class="btn btn-sm btn-primary AdjustTokens" value="Adjust">
</div>
  </div>
 <!--end ADDING TOKENS TO Provider account-->
								           </td>


										


   <td align="center" width="20%" style="text-align:center;" class="action-btn001">
                                                                <div class="share-button openHoverAction-class" style="display: block;">
                                                                    <label class="entypo-export"><span><img src="admin/images/settings-icon.png" alt=""></span></label>
                                                                    <div class="social show-moreOptions openPops_<?= $data_drv[$i]['iDriverId']; ?>">
                                                                    	
                                                                        <ul>
                                                                           <li>
                                                                           
                                                                           	<label class="close-btn">x</label>
                                                                           </li>
                                                                          <li class="entypo-twitter" data-network="twitter">
														<? if($APP_TYPE != "UberX" && $APP_TYPE != "Ride-Delivery-UberX"){
																echo $data_drv[$i]['vLang']; 
															} else {?>
															<a href="add_services.php?iDriverId=<?= $data_drv[$i]['iDriverId']; ?>">
																<button class="btn btn-primary">
																	<i class="icon-pencil icon-white"></i>
																	<?=$langage_lbl['LBL_SERVICES_WEB'];?>
																</button>
															</a><?
															}
														 ?>
													</li>
													<?php if($APP_TYPE == "UberX" || $APP_TYPE == "Ride-Delivery-UberX") { ?>
													<li class="entypo-twitter" data-network="twitter">
														<a href="add_availability.php?iDriverId=<?= $data_drv[$i]['iDriverId']; ?>">
															<button class="btn btn-primary">
																<i class="icon-pencil icon-white"></i>
																<?= $langage_lbl['LBL_AVAILABILITY'];?>
															</button>
														</a>
													</li>
													<?php } ?>


													<?php if($doc_count != 0 ) { ?>
													<li class="entypo-twitter" data-network="twitter">
														<a href="driver_document_action.php?id=<?= $data_drv[$i]['iDriverId']; ?>&action=edit">
															<button class="btn btn-primary">
																<i class="icon-pencil icon-white"></i> <?=$langage_lbl['LBL_EDIT_DOCUMENTS_TXT']; ?>
															</button>
														</a>
													</li>
													<?php } ?>
													<li class="entypo-twitter" data-network="twitter">
	<form name="suspend_form_<?= $data_drv[$i]['iDriverId']; ?>" id="suspend_form_<?= $data_drv[$i]['iDriverId']; ?>" method="post" action="" class="margin0">
<input type="hidden" name="hdn_suspend_id" id="hdn_suspend_id" value="<?= $data_drv[$i]['iDriverId']; ?>">
<?php 
$btntext='Inactive';
if(strtolower(trim($data_drv[$i]['eStatus']))=="inactive")
$btntext='Active';
 ?>
 <input type="hidden" name="action" id="action" value="<?=$btntext;?>">
<input type="button" class="btn btn-primary"  value="<?=$btntext;?>" onclick="confirm_delete('<?= $data_drv[$i]['iDriverId'];?>','Suspend',this);";>


</form>
</li>
                                                                          
                                                                      <li class="entypo-twitter" data-network="twitter">
														<a href="driver_action.php?id=<?= $data_drv[$i]['iDriverId']; ?>&action=edit">
															<button class="btn btn-primary">
																<i class="icon-pencil icon-white"></i> <?=$langage_lbl['LBL_DRIVER_EDIT']; ?>
															</button>
														</a>
													</li>

                                                   <li class="entypo-twitter" data-network="twitter">
														<a href="active_services.php?iDriverId=<?= $data_drv[$i]['iDriverId'];?>">
															<button class="btn btn-primary">
																<i class="icon-pencil icon-white"></i>Requested Services
															</button>
														</a>
													</li>

													<li class="entypo-twitter" data-network="twitter">
														<form name="delete_form_<?= $data_drv[$i]['iDriverId']; ?>" id="delete_form_<?= $data_drv[$i]['iDriverId']; ?>" method="post" action="" class="margin0">
															<input type="hidden" name="hdn_del_id" id="hdn_del_id" value="<?= $data_drv[$i]['iDriverId']; ?>">
															<input type="hidden" name="action" id="action" value="delete">
															<button type="button" class="btn btn-danger" onClick="confirm_delete('<?= $data_drv[$i]['iDriverId']; ?>','delete',this);">
																<i class="icon-remove icon-white"></i> <?=$langage_lbl['LBL_DRIVER_DELETE']; ?>
															</button>
														</form>
												</li>
                                                                           
                                                                           
                                                                          
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </td> 

												</tr>
											<? } ?>
										</tbody>
									</table>
							<!-- 	</div>   -->
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


<!--modal for adding tokens to provider wallet-->

     <div  class="modal fade" id="driver_add_wallet_money" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
                         <div class="modal-dialog" >
                              <div class="modal-content nimot-class">
                                   <div class="modal-header">
                                        <h4><i style="margin:2px 5px 0 2px;" class= "fa fa-google-wallet"></i>Add Tokens
                                        <button type="button" class="close" data-dismiss="modal">x</button>
                                        </h4>
                                   </div>
                                   <?php

                                    if($Available_token>0) {
                                   
                                    ?>
                                   <form class="form-horizontal" id="add_money_frm" method="POST" enctype="multipart/form-data"   action="" name="add_money_frm">
                                        <input type="hidden" id="action" name="action" value="addmoney">
                                        <input type="hidden"  name="eTransRequest" id="eTransRequest" value="">
                                        <input type="hidden"  name="eType" id="eType" value="Credit">
                                        <input type="hidden"  name="eFor" id="eFor" value="Deposit">
                                        <input type="hidden"  name="iDriverId" id="iDriver-Id" value="">                               
                                        <input type="hidden"  name="eUserType" id="eUserType" value="Driver">           
                                        <div class="col-lg-12">
                                             <div class="input-group input-append" >
                                                  <h5><?= $langage_lbl['LBL_ADD_WALLET_DESC_TXT']; ?></h5>
                                        <div class="ddtt">
                                                  <h4>Enter tokens</h4>
                                                  <input type="text" name="iBalance" maxlength="5" id="iBalance" class="form-control iBalance add-ibalance" onKeyup="checkzero(this.value,'add_money-error');">
                                                  </div>
                                                    <span class="error" id="add_money-error"></span>

                                                  <div id="iLimitmsg"></div>                                                 
                                             </div>
                                        </div>
                                        <div class="nimot-class-but">
                                             <input type="button" onClick="check_add_money();" class="btn btn-primary save"  id="add_money" name="<?= $langage_lbl['LBL_save']; ?>" value="<?= $langage_lbl['LBL_Save']; ?>">

                                             <button type="button" class="btn btn-danger btn-ok" data-dismiss="modal">Close</button>
                                        </div>
                                   </form>
                                 <?php  } else
                                 {
?>
<div style="margin-left: 20%;">
<h5>You do not have not enough tokens</h5>

 <div class="nimot-class-but">
                                           
                                             <button type="button" class="btn btn-primary btn-ok" data-dismiss="modal">Ok</button>
                                        </div></div>
<?php

                                 }?>
                            <div style="clear:both;"></div>
                              </div>
                         </div>
                         
                    </div>

<!--end modal for adding tokens to provider wallet-->

<!--modal for adjusting tokens-->
  <div  class="modal fade" id="driver_adjust_wallet_money" tabindex="-1" role="dialog"  aria-hidden="true" >
                         <div class="modal-dialog" >
                              <div class="modal-content ">
                                   <div class="modal-header">
                                        <h4><i style="margin:2px 5px 0 2px;" class= "fa fa-google-wallet"></i>Adjust Tokens
                                        <button type="button" class="close" data-dismiss="modal">x</button>
                                        </h4>
                                   </div>
                                      <div class="col-lg-12">
                                             <div class="input-group input-append">
                                             	<form method="post">
                                        <input type="hidden" id="action" name="action" value="adjustmoney">
                                        <input type="hidden"  name="eTransRequest" id="eTransRequest" value="">
                                        <input type="hidden"  name="eType" id="eType" value="Debit">
                                        <input type="hidden"  name="iDriverId" id="iDriver-Id-adjust" value="">                               
                                        <input type="hidden"  name="eUserType" id="eUserType" value="Driver">     
                                             	
                                       <h5>Entered Tokens Will Be removed Directly To Provider's Account.</h5>
                                       <h4>Enter tokens</h4>
                                   <input type="text" name="txtAdjustTokens" id="txtAdjustTokens" class="form-control txtAdjustTokens" onKeyup="checkzero(this.value,'txtAdjustTokens-error');"/>

                                   <span class="error" id="txtAdjustTokens-error"></span>
                                   <br>
                                   <input type="submit" class="btn btn-primary" name="btnAdjustTokens" id="btnAdjustTokens" value="save"/>
                                 <button type="button" class="btn btn-danger btn-adjust" data-dismiss="modal">Close</button>
                                 </form>
</div></div>
                              </div>
                         </div>
                         
                    </div>

<!--end of modal for adjusting tokens-->




<!-- Footer Script -->
<?php include_once('top/footer_script.php');?>
<script src="assets/js/jquery-ui.min.js"></script>
<script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript">
	var close=true;

	$(document).ready(function () {


	 	$('#dataTables-example').dataTable({
			"aaSorting": [],
			"searching":false

		});

          
$("#Search").click(function(){

var option=$("#option").val();
var keyword=$("#keyword").val();
var eStatus=$("#estatus_value").val();


post('driver.php', {option:option,keyword:keyword,eStatus:eStatus});



});


 //moreOption();


	$("body").on("click",".share-button",function(){

if (close) {
if (!$(this).find(".social").is(":visible")) 
{
$(".share-button .social").css("display","none");
	$(this).find(".social").css("display","block");
}
else
{
	$(".share-button .social").css("display","none");
}
	}
	close=true;
});

$("select").change(function(){

 //moreOption();
});


$(".close-btn").click(function(){
	
	$(".share-button .social").css("display","none");
	close=false;
});
//on click save modal adjust
$("#btnAdjustTokens").click(function(){

	var tokens=$("#txtAdjustTokens").val();
	var isValid=true;
	  $("#txtAdjustTokens-error").text("");
	  if (tokens=='') 
	  {
$("#txtAdjustTokens-error").text("Please enter tokens.");
isValid=false;
	  }
	   if(tokens != ""){
        if (tokens == 0)
        {       
            $("#txtAdjustTokens-error").text('You Can Not Enter Zero Number');
            isValid=false;
        } 
        else if(tokens < 0) {
         $("#txtAdjustTokens-error").text('You Can Not Enter Negative Number');
         isValid=false;
      }
    }
return isValid;
});
//end on click save modal adjust
	});
var selected;
$(".action").focus(function(){

selected=$(this).find('option:selected').index();
});

function moreOption()
{
	$(".share-button").unbind( "click" );

	$(".share-button").click(function(){

if (close) {
if (!$(this).find(".social").is(":visible")) 
{
$(".share-button .social").css("display","none");
	$(this).find(".social").css("display","block");
}
else
{
	$(".share-button .social").css("display","none");
}
	}
	close=true;
});
}
	function confirm_delete(id,action,e)
	{

		if (action.trim().toLowerCase()=='delete')
		{
		bootbox.confirm('<?= addslashes($langage_lbl['LBL_DELETE_DRIVER_CONFIRM_MSG']); ?>', function(result) {
			if(result){
				document.getElementById('delete_form_'+id).submit();
			}
		});
	}
	else
	{

		bootbox.confirm('Are You sure You want to '+$(e).val()+' this Provider?', function(result1) {
			if(result1){
				document.getElementById('suspend_form_'+id).submit();
			}
			 else{
					$(e).find('option').eq(selected).prop('selected', true);
			}
		});
	

	}
	}
	function changeCode(id)
	{
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
	
	function add_driver_form(){
		window.location.href = "driver_action.php";
	}
	function checkzero(userlimit,id){   
	    if(isNaN(userlimit)){
	        $('#iBalance').val('');
	        return false
	       
	    }else{
	        $("#"+id).text("");
        if(userlimit != ""){
            if (userlimit == 0)
            {       
                 $("#"+id).html('You Can Not Enter Zero Number');
            } else if(userlimit <= 0) {
               $("#"+id).html('You Can Not Enter Negative Number');
          } else {
              $("#"+id).html('');
            } 
        } else{
             $("#"+id).html('');
        }       
	    }
	//let regex = /^[0-9\b]+$/; 
	
	 
	    
	              
} 
//opening modal for ADDING TOKENS TO Provider account
function Add_money_driver(driverid){         
     $("#driver_add_wallet_money").modal('show');
       $("#add_money-error").text("");
     $(".add-ibalance").val("");
     if(driverid != ""){                
     var setDriverId = $('#iDriver-Id').val(driverid);
      
     }              
}
//end opening modal for ADDING TOKENS TO Provider account

//opening modal for adjust TOKENS 
function Adjust_money_driver(driverid){         
     $("#driver_adjust_wallet_money").modal('show');
     $("#txtAdjustTokens").val('');
     $("#txtAdjustTokens-error").text("");
     $("#iDriver-Id-adjust").val(driverid);
                 
}
//end opening modal for adjust TOKENS

function check_add_money() {

     var iBalance = $(".add-ibalance").val();
      $("#add_money-error").text("");
     if (iBalance == '') {
          $("#add_money-error").text("Please Enter Tokens");
          return false;
     } else if (iBalance == 0) {
           $("#add_money-error").text("You Can Not Enter Zero Number");
          return false;
     } else {
          $("#add_money").val('Please wait ...').attr('disabled','disabled');
          $('#add_money_frm').submit();
     }
}   


function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$("[name='dataTables-example_length']").each(function(){
			$(this).wrap("<em class='select-wrapper'></em>");
			$(this).after("<em class='holder'></em>");
		});
		$("[name='dataTables-example_length']").change(function(){
			var selectedOption = $(this).find(":selected").text();
			$(this).next(".holder").text(selectedOption);
		}).trigger('change');

		setTimeout(function(){
$(".paginate_button").mouseup(function(){
debugger
// moreOption();

});
},0);
	})
</script>
<!-- End: Footer Script -->
	</body>
<style type="text/css">
	.page-contant-inner
	{
width: 80%;
margin-right: 12%;
	}
.nimot-class-but
{
	margin:10px;
}

.available_balance
{
	font-size: 80%;
	margin:0px !important;
}
.available_balance
{
	padding-top: 0px !important;
}

select#action {
    font-size: 80%;
}



td.td_available_balance {
    padding-right: 9px !important;
    padding-left: 1px !important;
}


button.btn.btn-danger.btn-adjust {
    margin: 10px;
}


.social.show-moreOptions {
    position: absolute;
     z-index: 10;
}
.share-button ul
{
	margin-top:-216px  !important;
	    margin-left: -135px    !important;
	     z-index: 10;
}
.share-button ul::after
{
	    top: -33% !important;
}

@media only screen and (max-width:800px)
{
 #AdjustTokens {
    margin: 5px;
   
}
}

.button11
  {

        background: #219201;
    color: #FFFFFF;
  }


.success-vehicle-active
{

background-color: #5fa659;
padding: 8px;
}
.success-vehicle-inactive
{
	background-color: #d9534f;
	padding: 8px;

}

.status_active
{
background-color: rgb(56, 118, 29);

}
	.status_open
	{
background-color: rgb(241, 194, 50);

    }
	.status_pending
	{
		background-color: rgb(61, 133, 198);

}
	.status_inactive
	{
background-color: #d9534f;



}
.status
{
color: white;
padding-left: 10px;	
padding-right: 10px;
padding-bottom: 5px;
padding-top: 5px;
margin-left: 20px;
margin-right: 5px;

}
</style>



</html>