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
$message_print_id = $id;
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
$action = ($id != '') ? 'Edit' : 'Add';

$tbl_name = 'document_master';
$script = 'Document Master';

$doc_usertype = isset($_POST['doc_type']) ? $_POST['doc_type'] : '';
$doc_country1 = isset($_POST['country']) ? $_POST['country'] : '';
$Document_type = isset($_POST['Document_type']) ? $_POST['Document_type'] : '';
$exp = isset($_POST['exp']) ? $_POST['exp'] : '';
$eType = isset($_POST['eType']) ? $_POST['eType'] : 'Ride';
$backlink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$previousLink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
//exit();
$extrafield=isset($_REQUEST['extrafield']) ? $_REQUEST['extrafield'] : array();
$isRequired=isset($_REQUEST['isRequired']) ? $_REQUEST['isRequired'] : array();

$isType=isset($_REQUEST['isType']) ? $_REQUEST['isType'] : '';

$dropDownExtra=array();
$dropDown=array();

$numberOfFields=0;




$vTitle_store = array();
$sql = "SELECT vCode,vTitle,eDefault FROM `language_master` where eStatus='Active' ORDER BY `iDispOrder`";
$db_master = $obj->MySQLSelect($sql);

$count_all = count($db_master);
if ($count_all > 0) {
    for ($i = 0; $i < $count_all; $i++) {
        $vValue = 'doc_name_' . $db_master[$i]['vCode'];
        array_push($vTitle_store, $vValue);
        $$vValue = isset($_POST[$vValue]) ? $_POST[$vValue] : '';
    }
}
//print_r($vTitle_store);exit; 
if (isset($_POST['btnsubmit'])) {

     $sql1 = "SELECT vCountry FROM country where iCountryId='".$doc_country1."'";
	 $data_contry = $obj->MySQLSelect($sql1);
     $doc_country=$data_contry[0]['vCountry'];

    if ($eFareType == "Fixed") {
        $ePickStatus = "Inactive";
        $eNightStatus = "Inactive";
    } else {
        $ePickStatus = $ePickStatus;
        $eNightStatus = $eNightStatus;
    }


    if ($eNightStatus == "Active") {
        if ($tNightStartTime > $tNightEndTime) {
            header("Location:vehicle_type_action.php?id=" . $id . "&success=4");
            exit;
        }
    }
    if (SITE_TYPE == 'Demo') {
        header("Location:vehicle_type_action.php?id=" . $id . "&success=2");
        exit;
    }

    for ($i = 0; $i < count($vTitle_store); $i++) {

        $vValue = 'doc_name_' . $db_master[$i]['vCode'];
        // echo $_POST[$vTitle_store[$i]] ; exit;
        $q = "INSERT INTO ";
        $where = '';
        if ($id != '') {
            $q = "UPDATE ";
            $where = " WHERE `doc_masterid` = '" . $id . "'";
        }


        $query = $q . " `" . $tbl_name . "` SET             
            `doc_usertype` = '" . $doc_usertype . "',
            `doc_name` = '" . $Document_type . "' ,
            `country` = '" . $doc_country1 . "',
            `ex_status` = '".$exp."',
            `eType`='".$eType."',
            `IsType`='".$isType."',
            " . $vValue . " = '" . $_POST[$vTitle_store[$i]] . "'"
            . $where;
        $obj->sql_query($query);

        $id = ($id != '') ? $id : $obj->GetInsertId();


    }

  $obj->sql_query("delete from ExtraFields where doc_masterid='".$id."' and vFor='Document'");

  $obj->sql_query("delete from document_type_name where doc_masterid='".$id."' and vFor='Document'");

$documentType="";
if (trim($isType)=="1" || $isType==1) {

$numberOfValuesInDropDown=isset($_REQUEST['numberOfValuesInDropDown']) ? $_REQUEST['numberOfValuesInDropDown'] : 0;

for ($z=0; $z <=$numberOfValuesInDropDown ; $z++) { 
    # code...

if(isset($_REQUEST['documentTypeList_'.$z]))

{ 
$documentTypeList= $_REQUEST['documentTypeList_'.$z];

/*foreach ($documentTypeList as $key => $value) {
 //  $documentType.=$value."#";
}*/

$documentTypeList=str_replace("'", "`", $documentTypeList);
      $query ="insert into `document_type_name` SET             
            `doc_masterid` = '" . $id . "',
            `document_type_name` = '" . $documentTypeList . "',
             vFor='Document'
            "
            ;

        $obj->sql_query($query); 
$documentype_id=$obj->GetInsertId();
if(isset($_POST['dropDownExtrafieldsValues_'.$z])){

 $dropDownExtrafieldsValues = $_POST['dropDownExtrafieldsValues_'.$z];

$dropDownExtrafieldsRequiredValues=isset($_POST['dropDownExtrafieldsRequiredValues_'.$z]) ? $_POST['dropDownExtrafieldsRequiredValues_'.$z] : "";

$dropDownExtrafieldsValues=explode("#", $dropDownExtrafieldsValues);
$dropDownExtrafieldsRequiredValues=explode("#", $dropDownExtrafieldsRequiredValues);

for ($xx=0; $xx <count($dropDownExtrafieldsValues)-1; $xx++) { 

$value=str_replace("'", "`", $value);

     $query ="insert into `ExtraFields` SET             
            `doc_masterid` = '" . $id . "',
            `vLabel` = '" . $dropDownExtrafieldsValues[$xx] . "',
            `vFor` = 'Document',
            `isRequired`='".$dropDownExtrafieldsRequiredValues[$xx]."',
            `DocumentTypeId`= '".$documentype_id."'";

        $obj->sql_query($query); 

}



}

}
}
}



  $z=0;
  $i=0;
foreach ($extrafield as $key => $value) {
$reqVal=0;
if (count($isRequired)>0) {
    # code...

if (($z+1)==$isRequired[$i]) {
    # code...
    $reqVal=1;
    $i++;
   
}
$z++;
}
$value=str_replace("'", "`", $value);
      $query ="insert into `ExtraFields` SET             
            `doc_masterid` = '" . $id . "',
            `vLabel` = '" . $value . "' ,
            `vFor` = 'Document',
            `isRequired`='".$reqVal."',
             `DocumentTypeId`= '0'";
            ;

        $obj->sql_query($query); 
}
	$_SESSION['success'] = '1';
	if($action == "Edit"){
		$msg = "Document updated successfully.";
	}else{
		$msg = "Document added successfully.";
	}
    $_SESSION['var_msg'] = $msg;   
    // $obj->sql_query($query);
   header("Location:".$backlink);exit;
   // header("Location:document_master_list.php");
}

// for Edit
if ($action == 'Edit') {

   $sql = "SELECT * FROM " . $tbl_name . " WHERE doc_masterid = '" . $id . "'";
   $db_data = $obj->MySQLSelect($sql);
   

    $vLabel = $id;
    if (count($db_data) > 0) {
        for ($i = 0; $i < count($db_master); $i++) {

            foreach ($db_data as $key => $value) {
                $vValue = 'doc_name_' . $db_master[$i]['vCode'];
                $$vValue = $value[$vValue];
                $doc_usertype = $value['doc_usertype'];
                $doc_country = $value['country'];
                $doc_name = $value['doc_name'];
                $exp = $value['ex_status'];
                $eType = $value['eType'];
                $isType=$value['IsType'];
                $documentType=$value['ListOfTypes'];
            }
        }
    }

     $query = "SELECT * FROM ExtraFields WHERE doc_masterid = '" . $id . "'  and vFor='Document' and DocumentTypeId='0'";
   $extrafield = $obj->MySQLSelect($query);
   $numberOfFields=count($extrafield);

 $query = "SELECT * FROM document_type_name WHERE doc_masterid = '" . $id . "'  and vFor='Document'";
   $dropDown = $obj->MySQLSelect($query);
for ($i=0; $i <count($dropDown) ; $i++) { 

 $query = "SELECT * FROM ExtraFields WHERE doc_masterid = '" . $id . "'  and vFor='Document' and DocumentTypeId='".$dropDown[$i]['ID']."'";
   $dropDownExtra_db = $obj->MySQLSelect($query);
$dropDownExtra[$dropDown[$i]['ID']]=$dropDownExtra_db;

}

   
}

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> 
<html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD-->
<head>
    <meta charset="UTF-8" />
    <title>Admin | <?php echo $langage_lbl_admin['LBL_DOCUMENT_TYPE']; ?> <?= $action; ?></title>
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
                        <h2> <?php echo $langage_lbl_admin['LBL_DOCUMENT_TYPE']; ?> </h2>
                        <a href="javascript:void(0);" class="back_link">
                            <input type="button" value="Back to Listing" class="add-btn">
                       </a>
<!--                         <a href="document_master_list.php">
                            <input type="button" value="Back to Listing" class="add-btn">
                        </a> -->
                    </div>
                </div>
                <hr />
                <div class="body-div">


                    <div class="form-group">
                        <? if ($success == 1) {?>
                        <div class="alert alert-success alert-dismissable msgs_hide">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
						<?= $langage_lbl_admin['LBL_DOCUMENT_TYPE']; ?> Updated successfully.
                        </div><br/>
                        <? } elseif ($success == 2) { ?>
                        <div class="alert alert-danger alert-dismissable ">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                            "Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
                        </div><br/>
                        <? } elseif ($success == 3) { ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
						<?php echo $_REQUEST['varmsg']; ?> 
                        </div><br/>	
                        <? } elseif ($success == 4) { ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                            "Please Select Night Start Time less than Night End Time." 
                        </div><br/>	
                        <? } ?>
                        <? if($_REQUEST['var_msg'] !=Null) { ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            Record  Not Updated .
                        </div><br/>
                        <? } ?>

                        <form id="vtype" method="post" action="" enctype="multipart/form-data" >
                            <input type="hidden" name="id" value="<?= $id; ?>"/>
                            <input type="hidden" name="previousLink" id="previousLink" value="<?php echo $previousLink; ?>"/>
                            <input type="hidden" name="backlink" id="backlink" value="document_master_list.php"/>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>Document For <span class="red"> *</span></label>
                                </div>
                                <div class="col-lg-6">
                                    <select  class="form-control" name = 'doc_type'  id="doc_type" required>
                                        <?php if($APP_TYPE != "UberX") { ?>
                                        <option value="car" <?php if ($doc_usertype == "car") echo 'selected="selected"'; ?> >Car</option>
                                        <?php } ?>
                                        <option value="company"<?php if ($doc_usertype == "company") echo 'selected="selected"'; ?>>Company</option>
                                        <option value="driver"<?php if ($doc_usertype == "driver") echo 'selected="selected"'; ?>><?php echo $langage_lbl_admin['LBL_RIDER_DRIVER_RIDE_DETAIL']?></option>
                                    </select>
                                </div>
                            </div>
                            <?php if($APP_TYPE == 'Ride-Delivery' || $APP_TYPE == 'Ride-Delivery-UberX'){?>
                            <div class="row" id="servicetype">
                                <div class="col-lg-12">
                                    <label>Service Category<span class="red">*</span></label>
                                </div>
                                <div class="col-lg-6">
                                    <select  class="form-control" name = 'eType' required id='etypedelivery'>
                                        <option value="Ride" <?php if ($eType == "Ride") echo 'selected="selected"'; ?> >Ride</option>
                                        <option value="Delivery"<?php if ($eType == "Delivery") echo 'selected="selected"'; ?>>Delivery</option>
                                      <!--   <option value="UberX" <?php if ($eType == "UberX") echo 'selected="selected"'; ?> class="servicetype-uberx" >Other Services</option> -->
                                        <?php //codeEdited for list of service categories
                                        $sql_cat = "SELECT vCategory_EN FROM `vehicle_category` where eStatus='Active' and iParentId=0 ORDER BY vCategory_EN";
$db_cat = $obj->MySQLSelect($sql_cat);
foreach ($db_cat as $key => $value) {

    ?>
       <option value="<?=$value['vCategory_EN'];?>" <?php if ($eType == $value['vCategory_EN']) echo 'selected="selected"'; ?> class="servicetype-uberx" ><?=$value['vCategory_EN'];?></option>
    <?
}

                                        ?>

                                    </select>
                                </div>
                            </div>
                            <?php } else { ?>
                                <input type="hidden" name="eType" value="<?= $APP_TYPE?>" id='etypedelivery'>
                            <?php } ?>

                            <div class="row">
                                <div class="col-lg-12">
                                    <label>Country <span class="red"> *</span></label>
                                </div>
                                <div class="col-lg-6">
                                    <select id="country"  class="form-control" name = 'country'  required >
                                          <option value="All">All Country</option>
										<?php 
											// country 
											$sql = "SELECT iCountryId,vCountry,vCountryCode FROM country WHERE eStatus='Active' ORDER BY iCountryId ASC";
											$db_data1= $obj->MySQLSelect($sql);
											foreach ($db_data1 as $value) { ?>
											<option <?php if($db_data[0]['country'] == $value['vCountryCode']){ echo 'selected';}?> value="<?php echo $value['vCountryCode']; ?>"><?php echo $value['vCountry']; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                           
                            <div class="row">

                                <div class="col-lg-12">
                                    <label>Expire On Date <span class="red"> *</span> 
									<i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='Yes option will ask for Date'></i>
                                    </label>
                                </div>
                                <div class="col-lg-6">
                                    <input type="radio"  name="exp"  id="exp"  value="yes"  <?php if ($exp == "yes") echo 'checked="checked"'; ?>  required > Yes
                                    <input type="radio"  name="exp"   id="exp" value="no"  <?php if ($exp == "no") echo 'checked="checked"'; ?>  required > No
                                </div>
                            </div>
							
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>Document Name <span class="red"> *</span> 
                                        <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='Name of Document for admin use. e.g. Insurance, Driving Licence... etc'></i>

                                    </label>
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="Document_type"  id="Docmaster"  value="<?= $doc_name; ?>"  required>
                                </div>
                            </div>
							<?php
							if ($count_all > 0) {
							    for ($i = 0; $i < $count_all; $i++) {
							        $vCode = $db_master[$i]['vCode'];
							        $vTitle = $db_master[$i]['vTitle'];
							        $eDefault = $db_master[$i]['eDefault'];
							        $vValue = 'doc_name_' . $vCode;
							        $required = ($eDefault == 'Yes') ? 'required' : '';
							        $required_msg = ($eDefault == 'Yes') ? '<span class="red"> *</span>' : '';
							        ?>
							        <div class="row">
                                        <div class="col-lg-12">
                                            <label><?php echo $langage_lbl_admin['LBL_DOCUMENT_TYPE']; ?> (<?= $vTitle; ?>)<?php echo $required_msg;?> 
											 <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='Name of Document as per language. e.g. Insurance, Driving Licence... etc'></i>
											</label>

                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" name="<?= $vValue; ?>" id="<?= $vValue; ?>" value="<?= $$vValue; ?>" placeholder="<?= $vTitle; ?>Value" <?= $required; ?>>

                                        </div>
                                    </div>
                                    <? }
                                    } ?>

                                         <div class="row">
                                        <div class="col-lg-12">
                                            <input type="checkbox" value="0" name="isType" id="isType"> <label> Add types of document?
                                             <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='Giving the user a DROP LIST to choose from.

 E.g.,

Document Name:  Government Issued ID

Number of Allowed Document Types    2

Document Type 1  Passport

Document Type 2  Driver’s License'></i>
                                            </label>

                                        </div>
                                    </div>

                                     <div class="row documentTypeRow" style="display: none">
                                        <div class="col-lg-12">
                                            <label>Enter name of document type
                                             <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='Giving the user a DROP LIST to choose from.

 E.g.,

Document Name:  Government Issued ID

Number of Allowed Document Types    2

Document Type 1  Passport

Document Type 2  Driver’s License'></i>
                                            </label>

                                        </div>
                                           <div class="col-lg-6">
                                            <input type="text" class="form-control" name="documentType" id="documentType">
                                            <span id="errorDocumentType"></span>
                                          <a style="display: none;float: right;" href="#" id="resetToAdd">Reset</a>


                                        </div>
                                        <div class="col-lg-2">
                                            <input type="button" data-action="add" data-rowno="add" value="Add" class="btn btn-md btn-primary" name="addType" id="addType">
                                        </div>
                                   
                                    </div>
                                     <div class="row documentTypeRow" style="display: none">
                                        <div class="col-lg-6">
                                            <table class='table' id="documentTypeContainer">
                                                
                                            </table>
                                                <input type="hidden" name="numberOfValuesInDropDown" id="numberOfValuesInDropDown">
                                            
                                        </div>
                                    </div>

                                      <div class="row" id="commanExtraFields">
                                <div class="col-lg-12" >
                                    <label>Extra Fields
                                        <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='These are extra text Fields. e.g. Insurance Number, Driving Licence Number... etc'></i>

                                    </label>
                                </div>
                                <div class="col-lg-6">
                                 <select class="form-control" id="extrafields">
                                     <option value="">Select Number of field</option>
                                    <option value="1" <?php if ($numberOfFields==1) {
                                        echo 'selected';
                                    } ?>>1</option>
                                   <option value="2" <?php if ($numberOfFields==2) {
                                        echo 'selected';
                                    } ?>>2</option>
                                   <option value="3" <?php if ($numberOfFields==3) {
                                        echo 'selected';
                                    } ?>>3</option>
                                    <option value="4" <?php if ($numberOfFields==4) {
                                        echo 'selected';
                                    } ?>>4</option>
                                   <option value="5" <?php if ($numberOfFields==5) {
                                        echo 'selected';
                                    } ?>>5</option>

                                 </select>
                                </div>
                            </div>
                            <div id="extrafieldsContainer">
                                
                            </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="submit" class="save btn-info" name="btnsubmit" id="btnsubmit" value="<?= $action . " " . $langage_lbl_admin['LBL_DOCUMENT_TYPE']; ?>" >
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <div style="clear:both;"></div>
                    </div>

                </div>

                <!--END PAGE CONTENT -->
            </div>
            <!--END MAIN WRAPPER -->



            <!-- Modal -->
  <div class="modal fade" id="myModalExtraFileds" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Extra Fields</h4>
        </div>
        <div class="modal-body">

                              <div class="row">
                                <div class="col-lg-12">
                                    <label>Extra Fields
                                        <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='These are extra text Fields. e.g. Insurance Number, Driving Licence Number... etc'></i>

                                    </label>
                                </div>
                                <div class="col-lg-6">
                                 <select class="form-control dropDownExtrafields">
                                     <option value="">Select Number of field</option>
                                    <option value="1" <?php if ($numberOfFields==1) {
                                        echo 'selected';
                                    } ?>>1</option>
                                   <option value="2" <?php if ($numberOfFields==2) {
                                        echo 'selected';
                                    } ?>>2</option>
                                   <option value="3" <?php if ($numberOfFields==3) {
                                        echo 'selected';
                                    } ?>>3</option>
                                    <option value="4" <?php if ($numberOfFields==4) {
                                        echo 'selected';
                                    } ?>>4</option>
                                   <option value="5" <?php if ($numberOfFields==5) {
                                        echo 'selected';
                                    } ?>>5</option>

                                 </select>
                                </div>
                            </div>
<input type="hidden" id="target">
          <div class="dropDownExtrafieldsContainer">
                                
         </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  

			
    <? include_once('footer.php'); ?>

    <script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>	
    <script>
        var ii=1;

        $(document).ready(function() {
            var referrer;
            if($("#previousLink").val() == "" ){
                referrer =  document.referrer;  
            }else { 
                referrer = $("#previousLink").val();
            }
            if(referrer == "") {
                referrer = "document_master_list.php";
            }else {
                $("#backlink").val(referrer);
            }
            $(".back_link").attr('href',referrer);



$(".dropDownExtrafields").change(function(){
    var numberOfFields=$(this).val();
var fields="";
if (numberOfFields!='') {
fields=' <div class="row"> <div class="col-lg-12"> <label>Enter labels of extra fields                                </label>                                </div>                                 ';
}

    for (var i = 1; i <= numberOfFields; i++) {
var vLabel="";
var val="";
var isChecked="";

        fields+='<div class="row" style="margin-left:1px"><div class="col-lg-6">    <input type="text" class="form-control dropDownExtrafield" name="dropDownExtrafield[]"  id="dropDownExtrafield_'+i+'" value="'+val+'"   placeholder="Enter label"  required> </div><div class="col-lg-6"> <input type="checkbox" value="'+i+'" name="dropDownisRequired[]" class="dropDownisRequired" '+isChecked+'/> Is Required </div></div><br>';      
    }
    if (numberOfFields!='') {

    fields+='</div> <div class="row"><center><input type="button"  value="Submit" class="btn btn-button1 saveDropDownExtraFields"  /></center></div>';
}
    $(".dropDownExtrafieldsContainer").html(fields);

$(".saveDropDownExtraFields").click(function(){
var eValue="";
var eIsRequired="";

$(".dropDownExtrafield").each(function(){

eValue+=$(this).val()+"#";


});

$(".dropDownisRequired").each(function(){

if($(this).is(":checked"))
eIsRequired+="1#";
 else
 eIsRequired+="0#";



});


$("#dropDownExtrafieldsValues_"+$("#target").val()).val(eValue);
$("#dropDownExtrafieldsRequiredValues_"+$("#target").val()).val(eIsRequired);

    $("#myModalExtraFileds").modal('hide');


});





});




$("#extrafields").change(function(){
    var numberOfFields=$(this).val();
var fields="";
if (numberOfFields!='') {
fields=' <div class="row"> <div class="col-lg-12"> <label>Enter labels of extra fields                                </label>                                </div>                                 ';
}

    for (var i = 1; i <= numberOfFields; i++) {
var vLabel=<?php echo json_encode($extrafield); ?>;
var val="";
var isChecked="";
try
{
 val=vLabel.length==0?'':vLabel[i-1]['vLabel'];
 isChecked=vLabel[i-1]['IsRequired']==1?"checked":"";
}catch(ex){}
        fields+='<div class="row" style="margin-left:1px"><div class="col-lg-6">    <input type="text" class="form-control" name="extrafield[]"  id="extrafield_'+i+'" value="'+val+'"   placeholder="Enter label"  required> </div><div class="col-lg-6"> <input type="checkbox" value="'+i+'" name="isRequired[]" class="isRequired" '+isChecked+'/> Is Required </div></div>';      
    }
    fields+='</div>';
    $("#extrafieldsContainer").html(fields);

});

<?php if ($action == 'Edit') { ?>
  var dropDownExtra=<? echo json_encode($dropDownExtra);?>;
    var dropDown=<? echo json_encode($dropDown);?>;

$("#extrafields").trigger("change");
documentTypesOnEdit("<?=$isType?>",dropDownExtra,dropDown);
setTimeout(function(){
    $("#isType").trigger("change");
},0);

<?php } ?>




$("#isType").change(function(){

    if($(this).is(":checked"))
    {
        $(".documentTypeRow").css("display","");

            $("#commanExtraFields").css("display","none");

            $("#extrafieldsContainer").css("display","none");
    $("#extrafields").val("").change();

        $(this).val("1");

    }
   else
   {
    $(".documentTypeRow").css("display","none");
            $(this).val("0");

            $("#commanExtraFields").css("display","");

            $("#extrafieldsContainer").css("display","");

   }
});

$("#addType").click(function(){
    if ($("#documentType").val().trim()=="") {
                $("#errorDocumentType").css("color","red");

        $("#errorDocumentType").text("*Please enter of name document type.");
    }
    else
    {
         var id=$(this).attr("data-rowno").trim();
addRow($("#documentType").val(),id);
        $("#errorDocumentType").text("");

$("#documentType").val("");
    $("#resetToAdd").hide();

   }
});


$("#resetToAdd").click(function(e){
   e.preventDefault();
        $("#addType").attr("data-action","add");

        $("#addType").val("Add");
    $("#resetToAdd").hide();
    $("#documentType").val("");
});


        });


function openModal(i)
{
    $("#target").val(i);
    $(".dropDownExtrafields").val("").change();
    $("#myModalExtraFileds").modal('show');

 var eValue=$("#dropDownExtrafieldsValues_"+i).val();
  var eIsRequired=$("#dropDownExtrafieldsRequiredValues_"+i).val();

if (eValue.trim()!="") 
{
eValue=eValue.split("#");
eIsRequired=eIsRequired.split("#");
$(".dropDownExtrafields").val(eValue.length-1).change();

var x=0;
$(".dropDownExtrafield").each(function(){

$(this).val(eValue[x]);
x++;

});

 x=0;
$(".dropDownisRequired").each(function(){

if(eIsRequired[x].trim()=="1")
$(this).attr("checked","checked")
else
$(this).removeAttr("checked"); 

x++;

});



}

}

function addRow(v,id)
{
            var action=$("#addType").attr("data-action").trim();

    if (action.trim()=="add") {
     $("#documentTypeContainer").append("<tr><td><span>"+v+"</span><input type='hidden' value='"+v+"' class='documentTypeList' name='documentTypeList_"+ii+"' id='documentTypeList_"+ii+"' /></td><td style='width:170px'><a href='#' class='EditRow' >Edit</a> | <a href='#' class='deleteRow' >Delete</a> | <a  onClick=openModal("+ii+") style='cursor: pointer;' >Extra Fields</a> <input type='hidden' class='dropDownExtrafieldsValues' name='dropDownExtrafieldsValues_"+ii+"' id='dropDownExtrafieldsValues_"+ii+"' value='' />  <input type='hidden' class='dropDownExtrafieldsRequiredValues' name='dropDownExtrafieldsRequiredValues_"+ii+"' id='dropDownExtrafieldsRequiredValues_"+ii+"'value='' /> </td></tr>");

$("#numberOfValuesInDropDown").val(ii);

$(".deleteRow").unbind("click");
$(".EditRow").unbind("click");

$(".deleteRow").click(function(event){
  event.preventDefault();
  if (confirm("Do you want to delete?")) {
$(this).parent("td").parent("tr").remove();
}
});


$(".EditRow").click(function(event){
  event.preventDefault();
  var target=$(this).parent("td").parent("tr").find(".documentTypeList");
$("#documentType").val(target.val());
$("#addType").attr("data-action","edit");
$("#addType").attr("data-rowno",target.attr('id'));
$("#addType").val("Edit");
    $("#resetToAdd").show();

});

ii++;

}
else
{
$("#"+id).parent("td").find("span").text(v);
$("#"+id).val(v);
$("#addType").attr("data-action","add");
$("#addType").val("Add");

}
}

   function documentTypesOnEdit(isType,dropDownExtra,dropDown)
   {
    
if (isType.trim()=="1")
    $("#isType").attr("checked","checked");
else
    $("#isType").removeAttr("checked");


      




for (var i = 0; i < dropDown.length; i++) {
  
    addRow(dropDown[i].document_type_name,"");
var eValue="";
var eIsRequired="";
    for (var j = 0; j < dropDownExtra[dropDown[i].ID].length; j++) 
    {
        eValue+= dropDownExtra[dropDown[i].ID][j].vLabel+"#";
        eIsRequired+= dropDownExtra[dropDown[i].ID][j].IsRequired+"#";
    }

$("#dropDownExtrafieldsRequiredValues_"+(i+1)).val(eIsRequired);
$("#dropDownExtrafieldsValues_"+(i+1)).val(eValue);


}


   }

        $('[data-toggle="tooltip"]').tooltip();
        var successMSG1 = '<?php echo $success; ?>';

        if (successMSG1 != '') {
            setTimeout(function () {
                $(".msgs_hide").hide(1000)
            }, 5000);
        }

        if($("#doc_type option:selected").val() == 'car'){
            $(".servicetype-uberx").hide();
        } else {
            $(".servicetype-uberx").show();
        }

        if($("#doc_type option:selected").val() == 'company'){
           $("#servicetype").hide();
        } else {
            $("#servicetype").show();
        }

        $('#doc_type').on('change', function (e) {
            var valueSelected = this.value;
            if(valueSelected == 'company'){
                $("#servicetype").hide();
                $(".servicetype-uberx").show();
            } else if(valueSelected == 'car'){
                $(".servicetype-uberx").hide();
                $("#servicetype").show();
            } else {
                $("#servicetype").show();
                $(".servicetype-uberx").show();
            }
        });

 
    </script>
</body>
<!-- END BODY-->
</html>
