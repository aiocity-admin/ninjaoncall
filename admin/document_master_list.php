<?php
include_once('../common.php');

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$script = 'Document Master';

//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
$ord = ' ORDER BY dm.doc_name ASC';
if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY c.vCountry ASC";
  else
  $ord = " ORDER BY c.vCountry DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY dm.doc_usertype ASC";
  else
  $ord = " ORDER BY dm.doc_usertype DESC";
}

if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY dm.doc_name ASC";
  else
  $ord = " ORDER BY dm.doc_name DESC";
}

if($sortby == 4){
  if($order == 0)
  $ord = " ORDER BY dm.status ASC";
  else
  $ord = " ORDER BY dm.status DESC";
}

if($sortby == 5){
  if($order == 0)
  $ord = " ORDER BY dm.eType ASC";
  else
  $ord = " ORDER BY dm.eType DESC";
}
//End Sorting


// Start Search Parameters
$option = isset($_REQUEST['option'])?stripslashes($_REQUEST['option']):"";
$keyword = isset($_REQUEST['keyword'])?stripslashes($_REQUEST['keyword']):"";
$eType_value = isset($_REQUEST['eType_value'])?stripslashes($_REQUEST['eType_value']):"";
$ssql = '';
if($keyword != ''){
    if($option != '') {
        if($eType_value != ''){
            $ssql.= " AND ".stripslashes($option)." LIKE '%".stripslashes($keyword)."%' AND dm.eType = '".$generalobjAdmin->clean($eType_value)."'";
        } else {
            $ssql.= " AND ".stripslashes($option)." LIKE '%".stripslashes($keyword)."%'";
        }

    } else {
        if($eType_value != ''){
            $ssql.= " AND (c.vCountry LIKE '%".$keyword."%' OR dm.doc_usertype LIKE '%".$keyword."%' OR dm.doc_name LIKE '%".$keyword."%' OR dm.status LIKE '%".$keyword."%') AND dm.eType = '".$generalobjAdmin->clean($eType_value)."'";
        } else {
            $ssql.= " AND (c.vCountry LIKE '%".$keyword."%' OR dm.doc_usertype LIKE '%".$keyword."%' OR dm.doc_name LIKE '%".$keyword."%' OR dm.status LIKE '%".$keyword."%')";
        }
    }
} else if($eType_value != '' && $keyword == '') {
     $ssql.= " AND dm.eType = '".$generalobjAdmin->clean($eType_value)."'";
}


if($option == "dm.status"){	
    $eStatussql = " AND dm.status = '$keyword'";
}else{
    $eStatussql = " AND dm.status != 'Deleted'";
}
// End Search Parameters


//Pagination Start
$per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page
$sql = "SELECT COUNT(dm.doc_masterid) AS Total FROM `document_master` AS dm LEFT JOIN `country` AS c ON c.vCountryCode=dm.country WHERE 1=1 $eStatussql $ssql";
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
$tpages=$total_pages;
if ($page <= 0)
    $page = 1;
//Pagination End

$sql = "SELECT dm.doc_masterid, dm.doc_usertype , dm.doc_name, dm.status , c.vCountry, dm.eType FROM `document_master` AS dm LEFT JOIN `country` AS c ON c.vCountryCode=dm.country WHERE 1=1 $eStatussql $ssql $ord LIMIT $start, $per_page"; 
$data_drv = $obj->MySQLSelect($sql);

$endRecord = count($data_drv);

$var_filter = "";
foreach ($_REQUEST as $key=>$val) {
    if($key != "tpages" && $key != 'page')
    $var_filter.= "&$key=".stripslashes($val);
}
$reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages.$var_filter;

?>

<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?=$SITE_NAME?> | Document List</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <?php include_once('global_files.php');?>
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
                                <h2>Manage Documents</h2>
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
                                    <td width="10%" class=" padding-right10"><select name="option" id="option" class="form-control">
                                        <option value="">All</option>
                                        <option value="c.vCountry" <?php if ($option == "c.vCountry") { echo "selected"; } ?> >Country</option>
                                        <option value="dm.doc_name" <?php if ($option == 'dm.doc_name') {echo "selected"; } ?> >Document Name</option>
                                        <option value="dm.doc_usertype" <?php if ($option == 'dm.doc_usertype') {echo "selected"; } ?> >Document For</option>
                                        <? if($APP_TYPE == 'Ride-Delivery' || $APP_TYPE == 'Ride-Delivery-UberX'){?>
                                        <option value="dm.eType" <?php if ($option == 'dm.eType') {echo "selected"; } ?> >Service Type</option>
                                        <? } ?>
                                        <option value="dm.status" <?php if ($option == 'dm.status') {echo "selected"; } ?> >Status</option>
                                    </select>
                                    </td>
                                    <td width="15%" class="searchform"><input type="Text" id="keyword" name="keyword" value="<?php echo $keyword; ?>"  class="form-control" /></td>
                                    <td width="12%" class="eType_options" id="eType_options" >
                                        <select name="eType_value" id="eType_value" class="form-control">
                                            <option value="" >Select Service Type</option>
                                            <option value='Ride' <?php if ($eType_value == 'Ride') { echo "selected"; } ?> >Ride</option>
                                            <option value="Delivery" <?php if ($eType_value == 'Delivery') {echo "selected"; } ?> >Delivery</option>
                                         <!--    <option value="UberX" <?php if ($eType_value == 'UberX') {echo "selected"; } ?> >Other Services</option> -->
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
                                    </td>

                                    <td>
                                      <input type="submit" value="Search" class="btnalt button11" id="Search" name="Search" title="Search" />
                                      <input type="button" value="Reset" class="btnalt button11" onClick="window.location.href='document_master_list.php'"/>
                                    </td>
                                    <td width="30%"><a class="add-btn" href="document_master_add.php" style="text-align: center;"> Add Document Name</a></td>
                                </tr>
                              </tbody>
                        </table>
                        
                      </form>
                    <div class="table-list">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="admin-nir-export">
                                    <div class="changeStatus col-lg-12 option-box-left">
                                    <span class="col-lg-2 new-select001">
                                            <select name="changeStatus" id="changeStatus" class="form-control" onchange="ChangeStatusAll(this.value);">
                                                    <option value="" >Select Action</option>
                                                    <option value='Active' <?php if ($option == 'Active') { echo "selected"; } ?> >Make Active</option>
                                                    <option value="Inactive" <?php if ($option == 'Inactive') {echo "selected"; } ?> >Make Inactive</option>
                                                    <option value="Deleted" <?php if ($option == 'Delete') {echo "selected"; } ?> >Make Delete</option>
                                            </select>
                                    </span>
                                    </div>
                                    <?php if(!empty($data_drv)) { ?>
                                    <div class="panel-heading">
                                        <form name="_export_form" id="_export_form" method="post" >
                                            <button type="button" onclick="showExportTypes('Document_Master')" >Export</button>
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
														<th width="20%"><a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)">Country <?php if ($sortby == 1) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
												
														<th width="25%"><a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)">Document Name <?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>

                                                        <th width="20%"><a href="javascript:void(0);" onClick="Redirect(2,<?php if($sortby == '2'){ echo $order; }else { ?>0<?php } ?>)">Document For <?php if ($sortby == 2) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>

                                                        <? if($APP_TYPE == 'Ride-Delivery' || $APP_TYPE == 'Ride-Delivery-UberX') {?>
                                                        <th width="8%"><a href="javascript:void(0);" onClick="Redirect(5,<?php if($sortby == '5'){ echo $order; }else { ?>0<?php } ?>)">Service Category <?php if ($sortby == 5) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                                        <? } ?>

                                                        <th width="8%" align="center" style="text-align:center;"><a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)">Status <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                                        <th width="8%" class="align-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <? if(!empty($data_drv)) {
													for ($i = 0; $i < count($data_drv); $i++) { ?>
                                                    <tr class="gradeA">
														<td align="center" style="text-align:center;"><input type="checkbox" id="checkbox" name="checkbox[]" value="<?php echo $data_drv[$i]['doc_masterid']; ?>" />&nbsp;</td>
                                                        <td><?= ($data_drv[$i]['vCountry'] == "") ? "All" : $data_drv[$i]['vCountry']; ?></td>
                                                        <td><?= $data_drv[$i]['doc_name']; ?></td>
                                                        <td><?php if($APP_TYPE == "UberX" && $data_drv[$i]['doc_usertype']=="driver" ) {
                                                                echo $langage_lbl_admin['LBL_RIDER_DRIVER_RIDE_DETAIL'];
                                                            } else if($data_drv[$i]['doc_usertype']=="car") {
                                                                echo 'car';  
                                                            } else {
                                                                echo $data_drv[$i]['doc_usertype'];  
                                                            } ?></td>
                                                        <? if($APP_TYPE == 'Ride-Delivery' || $APP_TYPE == 'Ride-Delivery-UberX') {
                                                            if($data_drv[$i]['eType'] == 'UberX'){
                                                                $etype ='Other Services';
                                                            } else {
                                                                $etype = $data_drv[$i]['eType'];
                                                            }
                                                        ?>
                                                            <td><?= $etype; ?></td>
                                                        <? } ?>   
                                                        <td align="center">
                                                            <? if($data_drv[$i]['status'] == 'Active') {
                                                                $dis_img = "img/active-icon.png";
                                                            }else if($data_drv[$i]['status'] == 'Inactive'){
                                                                $dis_img = "img/inactive-icon.png";
                                                            }else if($data_drv[$i]['status'] == 'Deleted'){
                                                                $dis_img = "img/delete-icon.png";
                                                            }?>
                                                            <img src="<?= $dis_img; ?>" alt="<?=$data_drv[$i]['status'];?>" data-toggle="tooltip" title="<?=$data_drv[$i]['status'];?>">
                                                        </td>
														<td align="center" style="text-align:center;" class="action-btn001">
															<div class="share-button openHoverAction-class" style="display: block;">
																<label class="entypo-export"><span><img src="images/settings-icon.png" alt=""></span></label>
																<div class="social show-moreOptions openPops_<?= $data_drv[$i]['doc_masterid']; ?>">
																	<ul style="height:178px;">
																		<li class="entypo-twitter" data-network="twitter"><a href="document_master_add.php?id=<?= $data_drv[$i]['doc_masterid']; ?>" data-toggle="tooltip" title="Edit">
																			<img src="img/edit-icon.png" alt="Edit">
																		</a></li>
																		<?php if ($data_drv[$i]['eDefault'] != 'Yes') { ?>
																		<li class="entypo-facebook" data-network="facebook"><a href="javascript:void(0);" onclick="changeStatus('<?php echo $data_drv[$i]['doc_masterid']; ?>','Inactive')"  data-toggle="tooltip" title="Make Active">
																			<img src="img/active-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >
																		</a></li>
																		<li class="entypo-gplus" data-network="gplus"><a href="javascript:void(0);" onclick="changeStatus('<?php echo $data_drv[$i]['doc_masterid']; ?>','Active')" data-toggle="tooltip" title="Make Inactive">
																			<img src="img/inactive-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >	
																		</a></li>
																		<li class="entypo-gplus" data-network="gplus"><a href="javascript:void(0);" onclick="changeStatusDelete('<?php echo $data_drv[$i]['doc_masterid']; ?>')"  data-toggle="tooltip" title="Delete">
																			<img src="img/delete-icon.png" alt="Delete" >
																		</a></li>
																		<?php } ?>
																	</ul>
																</div>
															</div>
														</td>
                                                    </tr>
                                                    <?php } }else { ?>
                                                        <tr class="gradeA">
                                                            <td colspan="7"> No Records Found.</td>
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
                                    <li>
                                            Document List module will list all document lists on this page.
                                    </li>
                                    <li>
                                            Administrator can Activate / Deactivate / Delete any document list.
                                    </li>
                                    <li>
                                            Administrator can export data in XLS or PDF format.
                                    </li>
                            </ul>
                    </div>
                    </div>
                </div>
                <!--END PAGE CONTENT -->
            </div>
            <!--END MAIN WRAPPER -->
            
<form name="pageForm" id="pageForm" action="action/document_master_list.php" method="post" >
<input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
<input type="hidden" name="tpages" id="tpages" value="<?php echo $tpages; ?>">
<input type="hidden" name="doc_masterid" id="iMainId01" value="" >
<input type="hidden" name="status" id="status01" value="" >
<input type="hidden" name="statusVal" id="statusVal" value="" >
<input type="hidden" name="option" value="<?php echo $option; ?>" >
<input type="hidden" name="keyword" value="<?php echo $keyword; ?>" >
<input type="hidden" name="sortby" id="sortby" value="<?php echo $sortby; ?>" >
<input type="hidden" name="order" id="order" value="<?php echo $order; ?>" >
<input type="hidden" name="eType_value" id="eType_value" value="<?php echo $eType_value; ?>" >
<input type="hidden" name="method" id="method" value="" >
</form>
    <?php
    include_once('footer.php');
    ?>
        <script>
            $("#setAllCheck").on('click',function(){
                if($(this).prop("checked")) {
                    jQuery("#_list_form input[type=checkbox]").each(function() {
                        if($(this).attr('disabled') != 'disabled'){
                            this.checked = 'true';
                        }
                    });
                }else {
                    jQuery("#_list_form input[type=checkbox]").each(function() {
                        this.checked = '';
                    });
                }
            });
            
            $("#Search").on('click', function(){
                //$('html').addClass('loading');
                var action = $("#_list_form").attr('action');
                var formValus = $("#frmsearch").serialize();
                window.location.href = action+"?"+formValus;
            });
            
            $('.entypo-export').click(function(e){
                 e.stopPropagation();
                 var $this = $(this).parent().find('div');
                 $(".openHoverAction-class div").not($this).removeClass('active');
                 $this.toggleClass('active');
            });
            
            $(document).on("click", function(e) {
                if ($(e.target).is(".openHoverAction-class,.show-moreOptions,.entypo-export") === false) {
                  $(".show-moreOptions").removeClass("active");
                }
            });
/*            $(document).ready(function() {
                $('#eType_options').hide(); 
                $('#option').each(function(){
                  if (this.value == 'dm.eType') {
                      $('#eType_options').show(); 
                      $('.searchform').hide(); 
                  }
                });
            });
            $(function() {
                $('#option').change(function(){
                  if($('#option').val() == 'dm.eType') {
                      $('#eType_options').show();
                      $("input[name=keyword]").val("");
                      $('.searchform').hide(); 
                  } else {
                      $('#eType_options').hide();
                      $("#eType_value").val("");
                      $('.searchform').show();
                  } 
                });
            });*/
        </script>
    </body>
    <!-- END BODY-->
</html>