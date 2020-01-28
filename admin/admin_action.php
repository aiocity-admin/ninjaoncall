<?php
include_once('../common.php');
require_once(TPATH_CLASS . "/Imagecrop.class.php");
$thumb = new thumbnail();

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
$ksuccess = isset($_REQUEST['ksuccess']) ? $_REQUEST['ksuccess'] : 0;
$action = ($id != '') ? 'Update' : 'Add';

$tbl_name = 'administrators';
$script = 'Admin';

$sql1 = "SELECT iGroupId,vGroup FROM admin_groups WHERE 1";
$db_group = $obj->MySQLSelect($sql1);

// set all variables with either post (when submit) either blank (when insert)
$vFirstName = isset($_POST['vFirstName']) ? $_POST['vFirstName'] : '';
$vLastName = isset($_POST['vLastName']) ? $_POST['vLastName'] : '';
$vEmail = isset($_POST['vEmail']) ? $_POST['vEmail'] : '';
$vPassword = isset($_POST['vPassword']) ? $_POST['vPassword'] : '';
$eStatus = isset($_POST['eStatus']) ? $_POST['eStatus'] : '';
$iGroupId = isset($_POST['iGroupId']) ? $_POST['iGroupId'] : '';
$backlink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$previousLink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$vPass = ($vPassword != "") ? $generalobj->encrypt_bycrypt($vPassword) : '';
/*
//$PhoneNumber = isset($_POST['PhoneNumber']) ? $_POST['PhoneNumber'] : '';
//$JobTitle = isset($_POST['JobTitle']) ? $_POST['JobTitle'] : '';
//$Company= isset($_POST['Company']) ? $_POST['Company'] : '';
//$Picture = ''; 
     // $Phone_Number= '';
           // $Job_Title= '';
          //  $_Picture= '';
if(isset($_FILES['Picture']))
{
if ($_FILES["Picture"]["error"] > 0)
  {
  echo "Error: " . $_FILES["Picture"]["error"] . "<br>";
  }
else
  {
  move_uploaded_file($_FILES["Picture"]["tmp_name"],
      "barangayPictures/" . $_FILES["Picture"]["name"]);
  $Picture= "barangayPictures/" . $_FILES["Picture"]["name"];
  }
}
*/


if (isset($_POST['submit'])) {

    if ($id != "" && SITE_TYPE == 'Demo') {
        // header("Location:admin_action.php?id=" . $id . '&success=2');
		$_SESSION['success'] = '2';
		header("location:".$backlink);
        exit;
    }

    //Add Custom validation
    require_once("library/validation.class.php");
    $validobj = new validation();
    $validobj->add_fields($_POST['vFirstName'], 'req', 'First Name is required');
    $validobj->add_fields($_POST['vLastName'], 'req', 'Last Name is required');
    $validobj->add_fields($_POST['vEmail'], 'req', 'Email Address is required.');
    $validobj->add_fields($_POST['vEmail'], 'email', 'Please enter valid Email Address.');
    if ($action == "Add") {
		$validobj->add_fields($_POST['vPassword'], 'req', 'Password is required.');
	}
    //$validobj->add_fields($_POST['vPhone'], 'req', 'Phone Number is required.');
    if ($_SESSION['sess_iGroupId'] == 1) {
        $validobj->add_fields($_POST['iGroupId'], 'req', 'Group is required.');
    }
    $error = $validobj->validate();

    //Other Validations
    if ($vEmail != "") {
        if ($id != "") {
            $msg1 = $generalobj->checkDuplicateAdminNew('iAdminId', 'administrators', Array('vEmail'), $id, "");
        } else {
            $msg1 = $generalobj->checkDuplicateAdminNew('vEmail', 'administrators', Array('vEmail'), "", "");
        }
        
        if ($msg1 == 1) {
            $error .= '* Email Address is already exists.<br>';
        }
    }

    if ($error) {
        $success = 3;
        $newError = $error;
        //exit;
    } else {
        //echo '<pre>'; print_r($_POST); exit;

		 $passPara = '';
		 if($vPass != ""){
			 $passPara = "`vPassword` = '" . $vPass . "',";
		 }
		 
		 $groupSave = "";
		 if($_SESSION['sess_iGroupId'] == 1) {
			$groupSave = "`iGroupId` = '" . $iGroupId . "'";
		 }
		
        $q = "INSERT INTO ";
        $where = '';
        if ($action == 'Update') {
            $str = ", eStatus = 'Inactive' ";
        } else {
            $str = '';
        }
        if ($id != '') {
            $q = "UPDATE ";
            $where = " WHERE `iAdminId` = '" . $id . "'";
        }

        $query = $q . " `" . $tbl_name . "` SET
			`vFirstName` = '" . $vFirstName . "',
			`vLastName` = '" . $vLastName . "',
			`vEmail` = '" . $vEmail . "',
			$passPara
			$groupSave
			 " . $where;
        $obj->sql_query($query);
/*$query_create_barangay_details="CREATE TABLE `barangay_details` (
  `ID` int(11) NOT NULL,
  `iAdminId` int(11) NOT NULL,
  `Phone_Number` text NOT NULL,
  `Job_Title` text NOT NULL,
  `Picture` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1; ALTER TABLE `barangay_details`
  ADD PRIMARY KEY (`ID`); ALTER TABLE `barangay_details`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";
  $obj->sql_query($query_create_barangay_details);
*/
 /*$sql = "SELECT iAdminId FROM " . $tbl_name . " WHERE  vEmail= '" . $vEmail . "'";
 echo  $sql;
    $db_data = $obj->MySQLSelect($sql);
$iAdminId=$db_data[0]['iAdminId'];
 $query_barangay_details = $q . " `barangay_details` SET   `iAdminId` = '" . $iAdminId . "', `Phone_Number` = '" . $PhoneNumber . "',   `Job_Title` = '" . $JobTitle . "', Company='".$Company."' ".$where;

if (isset($_REQUEST['id'])&&trim($Picture)!="") {
  

             $query_barangay_details.=", `Picture` = '" . $Picture . "'";
}
elseif (!isset($_REQUEST['id'])) {
 
  $query_barangay_details.=", `Picture` = '" . $Picture . "'";
}
  $db_data = $obj->MySQLSelect($query_barangay_details);*/

        $id = ($id != '') ? $id : $obj->GetInsertId();
        if ($action == "Add") {
            $_SESSION['success'] = '1';
            $_SESSION['var_msg'] = 'Record Inserted Successfully.';
        } else {
            $_SESSION['success'] = '1';
            $_SESSION['var_msg'] = 'Record Updated Successfully.';
        }
        header("location:".$backlink);
    }
}
// for Edit

if ($action == 'Update') {
    $sql = "SELECT * FROM " . $tbl_name . "  WHERE iAdminId = '" . $id . "'";
    $db_data = $obj->MySQLSelect($sql);
    //echo "<pre>";print_R($db_data);echo "</pre>";
    // $vPass = $generalobj->decrypt($db_data[0]['vPassword']);
    $vLabel = $id;
    if (count($db_data) > 0) {
        foreach ($db_data as $key => $value) {
            $vFirstName = $value['vFirstName'];
            $vLastName = $generalobjAdmin->clearName(" ".$value['vLastName']);
            $vEmail = $generalobjAdmin->clearEmail($value['vEmail']);
            // $vUserName = $value['vUserName'];
            $vPassword = $value['vPassword'];
            $iGroupId = $value['iGroupId'];
          //  $Phone_Number= $value['Phone_Number'];
           // $Job_Title= $value['Job_Title'];
           // $_Picture= $value['Picture'];
           // $Company=$value['Company'];
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
        <title><?=$SITE_NAME?> | Admin <?= $action; ?></title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
        <?
        include_once('global_files.php');
        ?>
        <!-- On OFF switch -->
        <link href="../assets/css/jquery-ui.css" rel="stylesheet" />
        <link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
    </head>
    <!-- END  HEAD-->
    <!-- BEGIN BODY-->
    <body class="padTop53 " >

        <!-- MAIN WRAPPER -->
        <div id="wrap">
            <?
            include_once('header.php');
            include_once('left_menu.php');
            ?>
            <!--PAGE CONTENT -->
            <div id="content">
                <div class="inner">
                    <div class="row">
                        <div class="col-lg-12">
							<h2><?= $action; ?> Admin <?= $vFirstName; ?></h2>
							<a class="back_link" href="company.php">
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
                            <form name="_admin_form" id="_admin_form" method="post" action="" enctype="multipart/form-data">
                                <input type="hidden" name="actionOf" id="actionOf" value="<?php echo $action; ?>"/>
                                <input type="hidden" name="id" id="iAdminId" value="<?php echo $id; ?>"/>
                                <input type="hidden" name="previousLink" id="previousLink" value="<?php echo $previousLink; ?>"/>
                                <input type="hidden" name="backlink" id="backlink" value="admin.php"/>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>First Name<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" maxlength="25" class="form-control" name="vFirstName"  id="vName" value="<?= $vFirstName; ?>" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Last Name<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" maxlength="25" class="form-control" name="vLastName"  id="vLastName" value="<?= $vLastName; ?>" placeholder="Last Name">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Email<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" maxlength="30" class="form-control" name="vEmail" id="vEmail" value="<?= $vEmail; ?>" placeholder="Email">
                                    </div><div id="emailCheck"></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Password<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="password" class="form-control" name="vPassword"  id="vPassword" value="" placeholder="Password">
                                    </div>
                                </div>

<!--                                 <div class="row">
                                    <div class="col-lg-12">
                                        <label>Phone<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vPhone"  id="vContactNo" value="<?= $vContactNo; ?>" placeholder="Phone" >
                                    </div>
                                </div> -->
                                    <?php if ($_SESSION['sess_iGroupId'] == 1) { ?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label>Group<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <select  class="form-control" name = 'iGroupId' id='AdminTypes'>
                                                <option value="">--select--</option>
                                                <? for ($i = 0; $i < count($db_group); $i++) { ?>
                                                <option value = "<?= $db_group[$i]['iGroupId'] ?>" <?= ($db_group[$i]['iGroupId'] == $iGroupId) ? 'selected' : ''; ?>><?= $db_group[$i]['vGroup'] ?>
                                                </option>
                                                <? } ?>
                                            </select>
                                        </div>
                                    </div>

 <!-- <div class="row" style="display: none;" id="company-row">
                                        <div class="col-lg-12">

                                            <label>Company<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <select  class="form-control" name = 'Company' id='Company'>
                                                <option value="">--select--</option>
                                                <?
  $sql = "SELECT iCompanyId,vCompany FROM company";
    $db_company = $obj->MySQLSelect($sql);

                                                 for ($i = 0; $i < count($db_company); $i++) { ?>
                                                <option value = "<?= $db_company[$i]['iCompanyId']?>" <?= ($db_company[$i]['iCompanyId'] == $Company) ? 'selected' : ''; ?>><?= $db_company[$i]['vCompany']?>
                                                </option>
                                                <? } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="BarangayDetails"></div>-->
                                <?php } ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="submit" class="btn btn-default" name="submit" id="submit" value="<?= $action; ?> Admin" >
                                        <input type="reset" value="Reset" class="btn btn-default">
                                       <!--  <a href="javascript:void(0);" onclick="reset_form('_admin_form');" class="btn btn-default">Reset</a> -->
                                        <a href="admin.php" class="btn btn-default back_link">Cancel</a>
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
        <?
        include_once('footer.php');
        ?>
    </body>
    <!-- END BODY-->
</html>
<script>
$(document).ready(function() {
	var referrer;
	if($("#previousLink").val() == "" ){
		referrer =  document.referrer;	
		//alert(referrer);
	}else { 
		referrer = $("#previousLink").val();
	}
	if(referrer == "") {
		referrer = "admin.php";
	}else {
		$("#backlink").val(referrer);
	}
	$(".back_link").attr('href',referrer);


var htmlContent='   <div class="row"><div class="col-lg-12">                                        <label> Phone Number<span class="red"> *</span></label>                                    </div>                                    <div class="col-lg-6">    <input type="text" class="form-control" name="PhoneNumber"  id="PhoneNumber" value="<?= $Phone_Number; ?>" placeholder="Phone Number" required> </div> </div> <div class="row"><div class="col-lg-12">                                        <label>Job Title<span class="red"> *</span></label>                                    </div>                                    <div class="col-lg-6">    <input type="text" class="form-control" name="JobTitle" value="<?= $Job_Title;?>"  id="JobTitle" value="" placeholder="Job Title" required> </div> </div><div class="row"><div class="col-lg-12">                                        <label>Picture<span class="red"> </span></label> </div> <div class="col-lg-6">    <input type="file" class="form-control" name="Picture" value=""<?= $_Picture; ?>"  id="Picture" > </div> </div>';

<?php if (isset($_REQUEST['id'])) {

$sql="select iGroupId from administrators where iAdminId='".$_REQUEST['id']."'";
//echo $sql;
   $db_data_group_id = $obj->MySQLSelect($sql);
   $Group_id= $db_data_group_id[0]['iGroupId'];
   if ($Group_id=='5'||$Group_id==5) {
    ?>

$("#BarangayDetails").html(htmlContent);
$("#company-row").css("display","block");
$("#Company").attr('required', 'required');

    <?php
} }?>

$("#AdminTypes").change(function(){

var admintype=$(this).val();
if (admintype==5) {

$("#BarangayDetails").html(htmlContent);
$("#company-row").css("display","block");
$("#Company").attr('required', 'required');
}
else
{
$("#BarangayDetails").html("");
$("#company-row").css("display","none");
$("#Company").removeAttr('required');


}
});

});
</script>