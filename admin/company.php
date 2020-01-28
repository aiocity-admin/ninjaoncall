<?php
include_once('../common.php');
?>
<script type="text/javascript">
  //calculate the time before calling the function in window.onload
  var date1=new Date();
var beforeload = date1.getTime();
var loadtime=0;
function getPageLoadTime() {
  //calculate the current time in afterload
    var date2=new Date();

  var afterload = date2.getTime();
  // now use the beforeload and afterload to calculate the seconds
  seconds = (afterload - beforeload) / 1000;
  // Place the seconds in the innerHTML to show the results
 // $("#load_time").text('Loaded in  ' + seconds + ' sec(s).');
 loadtime=seconds;
date1= date1.getFullYear() + '-' +
    ('00' + (date1.getMonth()+1)).slice(-2) + '-' +
    ('00' + date1.getDate()).slice(-2) + ' ' + 
    ('00' + date1.getHours()).slice(-2) + ':' + 
    ('00' + date1.getMinutes()).slice(-2) + ':' + 
    ('00' + date1.getSeconds()).slice(-2);

    date2= date2.getFullYear() + '-' +
    ('00' + (date2.getMonth()+1)).slice(-2) + '-' +
    ('00' + date2.getDate()).slice(-2) + ' ' + 
    ('00' + date2.getHours()).slice(-2) + ':' + 
    ('00' + date2.getMinutes()).slice(-2) + ':' + 
    ('00' + date2.getSeconds()).slice(-2);

 $.ajax({
           type: "POST",
           url: "../LoadingTime/loadtime.php",
           data: {"loadtime":seconds,"beforeload":date1,"afterload":date2,"UserType":"SUPER_ADMIN","eType":"COMPANY"}, 
           success: function(data)
           {
               
           }
         });

}
</script>
<?php
if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();

$script = 'Company';

 function getAvailableTokens($companyId)
{
   global $obj;

     $token_query="SELECT sum(`Tokens`) as Credit FROM `barangay_tokens` where Type='Credit' and BarangayId='$companyId'";
    
$Credit=$obj->MySQLSelect($token_query);

  $token_query="SELECT sum(`Tokens`) as Debit FROM `barangay_tokens` where Type='Debit' and BarangayId='$companyId'";
$Debit=$obj->MySQLSelect($token_query);
$token=0;


$token=$Credit[0]['Credit']-$Debit[0]['Debit'];

  return $token;
}
//$walletUpdated=false;
if (trim($_REQUEST['action'])=="wallet") 
{
  $BarangayId=$_REQUEST['companyid'];
    $eType=$_REQUEST['eType'];
$ref_No="Adjusted by admin";
    if (trim($eType)=="Credit") {
$ref_No="Added by admin";
    }
      $iBalance=$_REQUEST['iBalance'];

  $wallet_query="INSERT INTO `barangay_tokens`(`BarangayId`, `Tokens`, `Type`, `TDate`, `ref_No`) VALUES ('$BarangayId','$iBalance','$eType','".date("Y-m-d H:i:s")."','$ref_No')";
  $obj->MySQLSelect($wallet_query);
  $_REQUEST['action']="";
  $_REQUEST['eType']="";
  $_REQUEST['iBalance']="";
 $_SESSION['walletUpdated']=true;
 header("location:company.php");
}


//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
$ord = ' ORDER BY c.iCompanyId DESC';
if ($sortby == 1) {
    if ($order == 0)
        $ord = " ORDER BY c.vCompany ASC";
    else
        $ord = " ORDER BY c.vCompany DESC";
}

if ($sortby == 2) {
    if ($order == 0)
        $ord = " ORDER BY c.vEmail ASC";
    else
        $ord = " ORDER BY c.vEmail DESC";
}

if ($sortby == 3) {
    if ($order == 0)
        $ord = " ORDER BY `count` ASC";
    else
        $ord = " ORDER BY `count` DESC";
}

if ($sortby == 4) {
    if ($order == 0)
        $ord = " ORDER BY c.eStatus ASC";
    else
        $ord = " ORDER BY c.eStatus DESC";
}
//End Sorting

$cmp_ssql = "";
// if (SITE_TYPE == 'Demo') {
// $cmp_ssql = " And c.tRegistrationDate > '" . WEEK_DATE . "'";
// }
$dri_ssql = "";
if (SITE_TYPE == 'Demo') {
    $dri_ssql = " And rd.tRegistrationDate > '" . WEEK_DATE . "'";
}

// Start Search Parameters
$option = isset($_REQUEST['option']) ? $_REQUEST['option'] : "";
$keyword = isset($_REQUEST['keyword']) ? stripslashes($_REQUEST['keyword']) : "";
$searchDate = isset($_REQUEST['searchDate']) ? $_REQUEST['searchDate'] : "";
$eStatus = isset($_REQUEST['eStatus']) ? $_REQUEST['eStatus'] : "";

$ssql = '';
if ($keyword != '') {
    $keyword_new = $keyword;
    $chracters = array("(", "+", ")");
    $removespacekeyword =  preg_replace('/\s+/', '', $keyword);
    $keyword_new = trim(str_replace($chracters, "", $removespacekeyword));
    if ($option != '') {
        $option_new = $option;
        if($option == 'MobileNumber'){
            $option_new = "CONCAT(c.vCode,'',c.vPhone)";
        }
        if($eStatus != ''){
            $ssql .= " AND " . stripslashes($option_new) . " LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%' AND c.eStatus = '".$generalobjAdmin->clean($eStatus)."'";
        } else {
             $ssql .= " AND " . stripslashes($option_new) . " LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%'";
        }
    } else {
        if($eStatus != ''){
            $ssql .= " AND (c.vCompany LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%' OR c.vEmail LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%' OR (concat(c.vCode,'',c.vPhone) LIKE '%".$generalobjAdmin->clean($keyword_new)."%')) AND c.eStatus = '" . $generalobjAdmin->clean($eStatus) . "'";
        } else {
            $ssql .= " AND (c.vCompany LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%' OR c.vEmail LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%' OR (concat(c.vCode,'',c.vPhone) LIKE '%".$generalobjAdmin->clean($keyword_new)."%'))";
        }
    }
} else if($eStatus != '' && $keyword == '') {
     $ssql.= " AND c.eStatus = '".$generalobjAdmin->clean($eStatus)."'";
}
// End Search Parameters
//Pagination Start
$per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page
if(!empty($eStatus)) { 
    $eStatus_sql = "";
} else {
    $eStatus_sql = " AND eStatus != 'Deleted'"; 
}

$sql = "SELECT COUNT(iCompanyId) AS Total FROM company AS c WHERE 1 = 1 $eStatus_sql $ssql $cmp_ssql";
$totalData = $obj->MySQLSelect($sql);
$total_results = $totalData[0]['Total'];
$total_pages = ceil($total_results / $per_page); //total pages we going to have
$show_page = 1;

//-------------if page is setcheck------------------//
if (isset($_GET['page'])) {
    $show_page = $_GET['page'];             //it will telles the current page
    if ($show_page > 0 && $show_page <= $total_pages) {
        $start = ($show_page - 1) * $per_page;
        $end = $start + $per_page;
    } else {
        // error - show first set of results
        $start = 0;
        $end = $per_page;
    }
} else {
    // if page isn't set, show first set of results
    $start = 0;
    $end = $per_page;
}
// display pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$tpages = $total_pages;
if ($page <= 0)
    $page = 1;
//Pagination End
if(!empty($eStatus)) { 
   $equery = "";
} else {
   $equery = " AND  c.eStatus != 'Deleted'"; 
}

$sql = "SELECT c.iCompanyId, c.vCompany, c.vEmail,(SELECT count(rd.iDriverId) FROM register_driver AS rd WHERE rd.iCompanyId=c.iCompanyId AND rd.eStatus != 'Deleted' $dri_ssql) AS `count`, c.vCode,c.vPhone, c.eStatus FROM company AS c WHERE 1 = 1 $equery $ssql $cmp_ssql $ord LIMIT $start, $per_page";

$data_drv = $obj->MySQLSelect($sql);
$endRecord = count($data_drv);

$sql1 = "SELECT doc_masterid as total FROM `document_master` WHERE `doc_usertype` ='company' AND status = 'Active'";
$doc_count_query = $obj->MySQLSelect($sql1);
$doc_count = count($doc_count_query);

$var_filter = "";
foreach ($_REQUEST as $key => $val) {
    if ($key != "tpages" && $key != 'page')
        $var_filter .= "&$key=" . stripslashes($val);
}

$reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages . $var_filter;
?>
<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?= $SITE_NAME ?> | Company</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <?php include_once('global_files.php'); ?>
        <style>
            
        </style>
    </head>
    <!-- END  HEAD-->

    <!-- BEGIN BODY-->
    <body class="padTop53 " >
        <!-- Main LOading -->
        <!-- MAIN WRAPPER -->
        <div id="wrap">
            <?php include_once('header.php'); ?>
            <?php include_once('left_menu.php'); ?>

            <!--PAGE CONTENT -->
            <div id="content">
                <div class="inner">
                    <div id="add-hide-show-div">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2>Company</h2>
                                <!--<input type="button" id="" value="ADD A DRIVER" class="add-btn">-->
                            </div>
                        </div>
                        <hr />
                    </div>
                    <?php include('valid_msg.php'); ?>


                    <form name="frmsearch" id="frmsearch" action="javascript:void(0);" method="post">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="admin-nir-table">
                            <tbody>
                                <tr>
                                    <td width="5%"><label for="textfield"><strong>Search:</strong></label></td>
                                    <td width="10%" class="padding-right10"><select name="option" id="option" class="form-control">
                                            <option value="">All</option>
                                            <option  value="c.vCompany" <?php
                                            if ($option == "c.vCompany") {
                                                echo "selected";
                                            }
                                            ?> >Name</option>
                                            <option value="c.vEmail" <?php
                                            if ($option == 'c.vEmail') {
                                                echo "selected";
                                            }
                                            ?> >E-mail</option>
                                            <option value="MobileNumber" <?php if ($option == 'MobileNumber') { echo "selected"; }  ?> >Mobile</option>
                                            <!--<option value="c.eStatus" <?php
                                            if ($option == 'c.eStatus') {
                                                echo "selected";
                                            }
                                            ?> >Status</option>-->
                                        </select>
                                    </td>
                                    <td width="15%" class="searchform"><input type="Text" id="keyword" name="keyword" value="<?php echo $keyword; ?>"  class="form-control" /></td>
                                    <td width="12%" class="estatus_options" id="eStatus_options" >
                                        <select name="eStatus" id="estatus_value" class="form-control">
                                            <option value="" >Select Status</option>
                                            <option value='Active' <?php if ($eStatus == 'Active') { echo "selected"; } ?> >Active</option>
                                            <option value="Inactive" <?php if ($eStatus == 'Inactive') {echo "selected"; } ?> >Inactive</option>
                                            <option value="Deleted" <?php if ($eStatus == 'Deleted') {echo "selected"; } ?> >Delete</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="submit" value="Search" class="btnalt button11" id="Search" name="Search" title="Search" />
                                        <input type="button" value="Reset" class="btnalt button11" onClick="window.location.href = 'company.php'"/>
                                    </td>
                                    <td width="30%"><a class="add-btn" href="company_action.php" style="text-align: center;">Add Company</a></td>
                                </tr>
                            </tbody>
                        </table>

                    </form>

<?php 
if (isset($_REQUEST['assiged'])) 
{
?>
<br>
 <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     Services Successfully Updated.
  </div>
<?php

}

if ($_SESSION['walletUpdated']&& !isset($_REQUEST['action'])!="") {
 ?>
<br>
 <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     Wallet Successfully Updated.
  </div>
 <?php
 $_SESSION['walletUpdated']=false;
}

?>

                    <div class="table-list">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="admin-nir-export">
                                    <div class="changeStatus col-lg-12 option-box-left">
                                        <span class="col-lg-2 new-select001">
                                            <select name="changeStatus" id="changeStatus" class="form-control" onChange="ChangeStatusAll(this.value);">
                                                <option value="" >Select Action</option>
                                                <option value='Active' <?php
                                                if ($option == 'Active') {
                                                    echo "selected";
                                                }
                                                ?> >Make Active</option>
                                                <option value="Inactive" <?php
                                                if ($option == 'Inactive') {
                                                    echo "selected";
                                                }
                                                ?> >Make Inactive</option>
                                                <?php if($eStatus != 'Deleted') { ?>
                                                <option value="Deleted" <?php
                                                if ($option == 'Delete') {
                                                    echo "selected";
                                                }
                                                ?> >Make Delete</option>
                                                <?php } ?>
                                            </select>
                                        </span>
                                    </div>
                                    <?php  if (!empty($data_drv)) { ?>
                                    <div class="panel-heading">
                                        <form name="_export_form" id="_export_form" method="post" >
                                            <button type="button" onClick="showExportTypes('company')" >Export</button>
                                        </form>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div style="clear:both;"></div>
                                <div class="table-responsive">
                                    <form class="_list_form" id="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th align="center" width="3%" style="text-align:center;"><input type="checkbox" id="setAllCheck" ></th>
                                                    <th width="20%"><a href="javascript:void(0);" onClick="Redirect(1,<?php
                                                        if ($sortby == '1') {
                                                            echo $order;
                                                        } else {
                                                            ?>0<?php } ?>)">Company Name <?php
                                                           if ($sortby == 1) {
                                                               if ($order == 0) {
                                                                   ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php
                                                                }
                                                            } else {
                                                                ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                    </th>

                                                    <th width="13%" class='align-center'><a href="javascript:void(0);" onClick="Redirect(3,<?php
                                                        if ($sortby == '3') {
                                                            echo $order;
                                                        } else {
                                                            ?>0<?php } ?>)"><?php echo $langage_lbl_admin['LBL_DASHBOARD_DRIVERS_ADMIN']; ?><?php
                                                                if ($sortby == 3) {
                                                                     if ($order == 0) { ?>
                                                                     <i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?>
                                                                     <i class="fa fa-sort-amount-desc" aria-hidden="true"></i>
                                                                     <?php
                                                                }
                                                            } else {
                                                                ?>  <i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>

                                                    <th width="20%"><a href="javascript:void(0);" onClick="Redirect(2,<?php
                                                        if ($sortby == '2') {
                                                            echo $order;
                                                        } else {
                                                            ?>0<?php } ?>)">Email <?php
                                                       if ($sortby == 2) {
                                                           if ($order == 0) {
                                                               ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php
                                                                }
                                                            } else {
                                                                ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>

                                                    <th width="15%">Mobile</th>
                                                  <!--codeEdited for wallet-->  
                                                    <th width="10%"  >Wallet</th>

                                                    <?php if($doc_count != 0) { ?>
                                                    <th width="8%" class='align-center'>View/Edit Documents</th>
                                                    <?php } ?>
                                                    <th width="6%" class='align-center' style="text-align:center;"><a href="javascript:void(0);" onClick="Redirect(4,<?php
                                                        if ($sortby == '4') {
                                                            echo $order;
                                                        } else {
                                                            ?>0<?php } ?>)">Status <?php
                                                        if ($sortby == 4) {
	                                                      if ($order == 0) {
	                                                          ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php
                                                                }
                                                            } else {
                                                                ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                             <th width="6%" class="align-center">Manage Services</th>

                                             <th width="6%" align="center" style="text-align:center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($data_drv)) {
                                                    for ($i = 0; $i < count($data_drv); $i++) {
                                                        $default = '';
                                                        if ($data_drv[$i]['iCompanyId'] == 1) {
                                                            $default = 'disabled';
                                                        }
                                                        ?>
                                                        <tr class="gradeA">
                                                            <td align="center" style="text-align:center;"><input type="checkbox" id="checkbox" name="checkbox[]" <?php echo $default; ?> value="<?php echo $data_drv[$i]['iCompanyId']; ?>" />&nbsp;</td>
                                                            <td>
																<? if($APP_TYPE == "Ride"){?>
																	<a href="javascript:void(0);" onClick="show_company_details('<?=$data_drv[$i]['iCompanyId'];?>')" style="text-decoration: underline;"><?= $generalobjAdmin->clearCmpName($data_drv[$i]['vCompany']); ?></a>
																<? }else{?>
																	<?= $generalobjAdmin->clearCmpName($data_drv[$i]['vCompany']); ?>
																<? }?>
															</td>
                                                            <?php  if($data_drv[$i]['count'] == 0) { ?>
                                                                <td align="center"><? echo $data_drv[$i]['count']; ?></td>
                                                            <?php } else { ?>
                                                                <td align="center"><a href="driver.php?iCompanyId=<?= $data_drv[$i]['iCompanyId']; ?>" target="_blank"><? echo $data_drv[$i]['count']; ?></a></td>
                                                            <?php } ?>
                                                            <td><?= $generalobjAdmin->clearEmail($data_drv[$i]['vEmail']); ?></td>
                                                            <td>
                                                                <?php if(!empty($data_drv[$i]['vPhone'])){?>
                                                                (+<?= $data_drv[$i]['vCode']?>) <?= $generalobjAdmin->clearPhone($data_drv[$i]['vPhone']); ?>
                                                                 <?php } ?>   
                                                                </td>
                                                                <!--codeEdited for wallet-->
                                                                <td>
                                                                    <label><? echo getAvailableTokens($data_drv[$i]['iCompanyId']); ?></label>
                                                                  <div class="row tokensRow">
        <div class="col-lg-4 tokensDiv">
 <input type="button" name="addTokens" id="addTokens" onclick="Add_money_driver('<?php echo $data_drv[$i]['iCompanyId'];?>')" class="btn btn-xs addTokens btn-success" value="Add">
</div><div class="col-lg-4 tokensDiv">
  <input type="button" name="AdjustTokens" id="AdjustTokens" onclick="Adjust_money_driver('<?php echo $data_drv[$i]['iCompanyId'];?>')"  class="btn btn-xs AdjustTokens btn-success" value="Adjust">
</div>
  </div> 

                                                                </td>

                                                                 <!--end codeEdited for wallet-->
                                                            <?php if($doc_count != 0) { ?>
                                                            <td align="center" ><a href="company_document_action.php?id=<?= $data_drv[$i]['iCompanyId']; ?>&action=edit" target="_blank">
                                                                    <img src="img/edit-doc.png" alt="Edit Document" >
                                                                </a></td>
                                                            <?php } ?>
                                                            <td align="center" style="text-align:center;">
                                                                <?php
                                                                if ($data_drv[$i]['eStatus'] == 'Active') {
                                                                    $dis_img = "img/active-icon.png";
                                                                } else if ($data_drv[$i]['eStatus'] == 'Inactive') {
                                                                    $dis_img = "img/inactive-icon.png";
                                                                } else if ($data_drv[$i]['eStatus'] == 'Deleted') {
                                                                    $dis_img = "img/delete-icon.png";
                                                                }
                                                                ?>
                                                                <img src="<?= $dis_img; ?>" alt="image" data-toggle="tooltip" title="<?php echo $data_drv[$i]['eStatus']; ?>">
                                                            </td>
                                                            <td> <a href="company_services.php?iCompanyId=<?= $data_drv[$i]['iCompanyId']; ?>" data-toggle="tooltip" title="Edit Service Type">
                                                                        <img src="img/view-details.png" alt="Edit Document" >
                                                                    </a></td>
                                                           
                                                            <td align="center" style="text-align:center;" class="action-btn001">
                                                                <? if( count($data_drv) > 1 ) { //$data_drv[$i]['iCompanyId']!=1;
                                                                ?>
                                                  <div class="share-button share-button4 openHoverAction-class" style="display:block;">
                                                  <label class="entypo-export"><span><img src="images/settings-icon.png" alt=""></span></label>
                                                         <div class="social show-moreOptions openPops_<?= $data_drv[$i]['iCompanyId']; ?>">
                                                                    <ul>
                                                                        <li class="entypo-twitter" data-network="twitter"><a href="company_action.php?id=<?= $data_drv[$i]['iCompanyId']; ?>" data-toggle="tooltip" title="Edit">
                                                                                <img src="img/edit-icon.png" alt="Edit">
                                                                            </a></li>
                                                                        <li class="entypo-facebook" data-network="facebook"><a href="javascript:void(0);" onClick="changeStatus('<?php echo $data_drv[$i]['iCompanyId']; ?>', 'Inactive')"  data-toggle="tooltip" title="Make Active">
                                                                                <img src="img/active-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >
                                                                            </a></li>
                                                                        <li class="entypo-gplus" data-network="gplus"><a href="javascript:void(0);" onClick="changeStatus('<?php echo $data_drv[$i]['iCompanyId']; ?>', 'Active')" data-toggle="tooltip" title="Make Inactive">
                                                                                <img src="img/inactive-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >
                                                                            </a></li>
                                                                        <?php if($eStatus != 'Deleted') { ?>
                                                                        <li class="entypo-gplus" data-network="gplus"><a href="javascript:void(0);" onClick="changeStatusDeletecd('<?php echo $data_drv[$i]['iCompanyId']; ?>')"  data-toggle="tooltip" title="Delete">
                                                                                <img src="img/delete-icon.png" alt="Delete" >
                                                                            </a></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <?php } else { ?>
                                                            <a href="company_action.php?id=<?= $data_drv[$i]['iCompanyId']; ?>" data-toggle="tooltip" title="Edit">
                                                                <img src="img/edit-icon.png" alt="Edit">
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                                <tr class="gradeA">
                                                    <td colspan="8"> No Records Found.</td>
                                                </tr>
                                        <?php } ?>
                                            </tbody>
                                        </table>
                                    </form>
                            <?php include('pagination_n.php'); ?>
                                </div>
                            </div> <!--TABLE-END-->
                        </div>
                    </div>
                    <div class="admin-notes">
                        <h4>Notes:</h4>
                        <ul>
                            <li>Company module will list all companies on this page.</li>
                            <li>Admin can Activate / Deactivate / Delete any company. Default Company cannot be Activated / Deactivated / Deleted.</li>
                            <li>Admin can export data in XLS or PDF format.</li>
                            <!--li>"Export by Search Data" will export only search result data in XLS or PDF format.</li-->
                        </ul>
                    </div>
                </div>
            </div>
            <!--END PAGE CONTENT -->
        </div>
        <!--END MAIN WRAPPER -->
        <form name="pageForm" id="pageForm" action="action/company.php" method="post" >
            <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
            <input type="hidden" name="tpages" id="tpages" value="<?php echo $tpages; ?>">
            <input type="hidden" name="iCompanyId" id="iMainId01" value="" >
            <input type="hidden" name="eStatus" id="eStatus" value="<?php echo $eStatus; ?>" >
            <input type="hidden" name="status" id="status01" value="" >
            <input type="hidden" name="statusVal" id="statusVal" value="" >
            <input type="hidden" name="option" value="<?php echo $option; ?>" >
            <input type="hidden" name="keyword" value="<?php echo $keyword; ?>" >
            <input type="hidden" name="sortby" id="sortby" value="<?php echo $sortby; ?>" >
            <input type="hidden" name="order" id="order" value="<?php echo $order; ?>" >
            <input type="hidden" name="method" id="method" value="" >
        </form>
		
		<div  class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
					<div class="modal-dialog" >
						<div class="modal-content">
							<div class="modal-header">
								<h4>
								<i aria-hidden="true" class="fa fa-building-o" style="margin:2px 5px 0 2px;"></i>
								Company Details
								<button type="button" class="close" data-dismiss="modal">x</button>
								</h4>
							</div>
							<div class="modal-body" style="max-height: 450px;overflow: auto;">
								<div id="imageIcons" style="display:none">
								  <div align="center">                                                                       
									<img src="default.gif"><br/>                                                            
									<span>Retrieving details,please Wait...</span>                       
								  </div>    
								 </div>
								 <div id="comp_detail"></div>
							</div>
						</div>
					</div>
					
				</div>



<!--modal for adding tokens to  wallet-->

     <div  class="modal fade" id="driver_add_wallet_money" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
                         <div class="modal-dialog" >
                              <div class="modal-content nimot-class">
                                   <div class="modal-header">
                                        <h4><i style="margin:2px 5px 0 2px;" class= "fa fa-google-wallet"></i>Add Tokens
                                        <button type="button" class="close" data-dismiss="modal">x</button>
                                        </h4>
                                   </div>
                                   <?php

                                  
                                   
                                    ?>
                                   <form class="form-horizontal" id="add_money_frm" method="POST" enctype="multipart/form-data"   action="" name="add_money_frm">
                                        <input type="hidden" id="action" name="action" value="wallet">
                                        <input type="hidden"  name="eType" id="eType" value="Credit">
                                        <input type="hidden"  name="companyid" id="companyid" value="">                               
                                                
                                        <div class="col-lg-12">
                                             <div class="input-group input-append" >
                                          <h5>Entered Tokens Will Be added Directly To Company's Account.</h5>

                                        <div class="ddtt">
                                                  <h4>Enter tokens</h4>
                                                  <input type="text" name="iBalance" id="iBalance" class="form-control iBalance add-ibalance" onKeyup="checkzero(this.value,'add_money-error');">
                                                  </div>
                                                    <span class="error" id="add_money-error"></span>

                                                  <div id="iLimitmsg"></div>                                                 
                                             </div>
                                        </div>
                                        <div class="nimot-class-but">
                                             <input type="button" onClick="check_add_money();" class="btn-primary save"  id="add_money" name="<?= $langage_lbl['LBL_save']; ?>" value="<?= $langage_lbl['LBL_Save']; ?>">

                                             <button type="button" class="btn btn-danger btn-ok" data-dismiss="modal">Close</button>
                                        </div>
                                   </form>
                              
                            <div style="clear:both;"></div>
                              </div>
                         </div>
                         
                    </div>

<!--end modal for adding tokens to  wallet-->

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
                                        <input type="hidden" id="action" name="action" value="wallet">
                                        <input type="hidden"  name="eType" id="eType" value="Debit">
                                        <input type="hidden"  name="companyid" id="companyid-adjust" value="">                               
                                                
                                       <h5>Entered Tokens Will Be removed Directly To Company's Account.</h5>
                                       <h4>Enter tokens</h4>
                                   <input type="text" name="iBalance" id="txtAdjustTokens" class="form-control txtAdjustTokens" onKeyup="checkzero(this.value,'txtAdjustTokens-error');"/>

                                   <span class="error" id="txtAdjustTokens-error"></span>
                                   <br><br>
                                   <input type="submit" class="btn btn-primary" name="btnAdjustTokens" id="btnAdjustTokens" value="save"/>
                                 <button type="button" class="btn btn-danger btn-adjust" data-dismiss="modal">Close</button>
                                 <br><br>
                                 </form>
</div></div>
                              </div>
                         </div>
                         
                    </div>

<!--end of modal for adjusting tokens-->


<?php
include_once('footer.php');
?>
        <script>
       /*    $(document).ready(function() {  
                $('#eStatus_options').hide(); 
                $('#option').each(function(){
                  if (this.value == 'c.eStatus') {
                      $('#eStatus_options').show(); 
                      $('.searchform').hide(); 
                  }
                });
            });
          $(function() {
              $('#option').change(function(){
                  if($('#option').val() == 'c.eStatus') {
                      $('#eStatus_options').show();
                      $("input[name=keyword]").val("");
                      $('.searchform').hide(); 
                  } else {
                      $('#eStatus_options').hide();
                      $("#estatus_value").val("");
                      $('.searchform').show();
                  } 
              });
          });*/

            $("#setAllCheck").on('click', function () {
                if ($(this).prop("checked")) {
                    jQuery("#_list_form input[type=checkbox]").each(function () {
                        if ($(this).attr('disabled') != 'disabled') {
                            this.checked = 'true';
                        }
                    });
                } else {
                    jQuery("#_list_form input[type=checkbox]").each(function () {
                        this.checked = '';
                    });
                }
            });

            $("#Search").on('click', function () {
                //$('html').addClass('loading');
                var action = $("#_list_form").attr('action');
                //alert(action);
                var formValus = $("#frmsearch").serialize();
//                alert(action+formValus);
                window.location.href = action + "?" + formValus;
            });

            $('.entypo-export').click(function (e) {
                e.stopPropagation();
                var $this = $(this).parent().find('div');
                $(".openHoverAction-class div").not($this).removeClass('active');
                $this.toggleClass('active');
            });

            $(document).on("click", function (e) {
                if ($(e.target).is(".openHoverAction-class,.show-moreOptions,.entypo-export") === false) {
                    $(".show-moreOptions").removeClass("active");
                }
            });
			
			function show_company_details(companyid){
				$("#comp_detail").html('');
				$("#imageIcons").show();
				$("#detail_modal").modal('show');
				
				if(companyid != ""){
					var request = $.ajax({
							type: "POST",
							url: "ajax_company_details.php",
							data: "iCompanyId="+companyid,
							datatype: "html",
							success: function(data){
								$("#comp_detail").html(data);
								$("#imageIcons").hide();
							}
						});
				}
			}

                function checkzero(userlimit,id)

{         $("#"+id).text("");
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

//opening modal for ADDING TOKENS TO Provider account
function Add_money_driver(driverid){         
     $("#driver_add_wallet_money").modal('show');
       $("#add_money-error").text("");
     $(".add-ibalance").val("");
     if(driverid != ""){                
     var setDriverId = $('#companyid').val(driverid);
      
     }              
}
//end opening modal for ADDING TOKENS TO Provider account

//opening modal for adjust TOKENS 
function Adjust_money_driver(driverid){         
     $("#driver_adjust_wallet_money").modal('show');
     $("#txtAdjustTokens").val('');
     $("#txtAdjustTokens-error").text("");
     $("#companyid-adjust").val(driverid);
                 
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
			
        </script>
    </body>
    <!-- END BODY-->
</html>
<script type="text/javascript">
  window.onload = getPageLoadTime;

</script>