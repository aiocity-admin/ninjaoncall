<?php
include_once('common.php');
$generalobj->check_member_login();
$script = 'Approve Documents';

//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 6;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 1;
$docId = isset($_REQUEST['docId'])?stripslashes($_REQUEST['docId']):"";
$loggedInCompany =$_SESSION['sess_iUserId'];
$ord = '';
$msg="";
if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY dl.doc_usertype ASC";
  else
  $ord = " ORDER BY dl.doc_usertype DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY Name ASC";
  else
  $ord = " ORDER BY Name DESC";
}
if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY dm.doc_name ASC";
  else
  $ord = " ORDER BY dm.doc_name DESC";
}
if($sortby == 4){
  if($order == 0)
  $ord = " ORDER BY dl.ex_date ASC";
  else
  $ord = " ORDER BY dl.ex_date DESC";
}
if($sortby == 5){
  if($order == 0)
  $ord = " ORDER BY dm.eType ASC";
  else
  $ord = " ORDER BY dm.eType DESC";
}
if($sortby == 6){
  if($order == 0)
  $ord = " ORDER BY dl.edate ASC";
  else
  $ord = " ORDER BY dl.edate DESC";
}

if($sortby == 7){
  if($order == 0)
  $ord = " ORDER BY rd.vEmail ASC";
  else
  $ord = " ORDER BY rd.vEmail DESC";
}


// Start Search Parameters
$option = isset($_REQUEST['option'])?$_REQUEST['option']:"";
$keyword = isset($_REQUEST['keyword'])?$_REQUEST['keyword']:"";
$status = isset($_REQUEST['status'])?$_REQUEST['status']:"";

$ssql = '';

if(isset($_POST['ChangeStatus']))
{
$statusUpdate = isset($_REQUEST['statusUpdate'])?$_REQUEST['statusUpdate']:"";

    $sql="update document_list set IsApprove='$statusUpdate' where doc_id='$docId'";
    $obj->MySQLSelect($sql);
    $msg="Record has been updated.";
}

if (isset($_REQUEST['search'])) 
{
if($keyword != ''){
    $keyword_s=str_replace(" ", "", $keyword);
    if($option != '') {
        // if($option == 'company'){           
        //     $ssql.= " AND  dl.doc_usertype='".stripslashes($option)."' and c.vCompany LIKE '%".stripslashes($keyword_s)."%'";
        // }
        // else  
         if($option == 'driver'){           
            $ssql.= " AND  dl.doc_usertype='".stripslashes($option)."' and concat(rd.Suffix,rd.vName,rd.MiddleName,rd.vLastName) LIKE '%".stripslashes($keyword_s)."%'";
        }
        else   if($option == 'car'){           
            $ssql.= " AND  dl.doc_usertype='".stripslashes($option)."'";
        }
       else {           
            $ssql.= " AND  ".stripslashes($option)."  LIKE '%".stripslashes($keyword_s)."%'";
        }
        if($status != '' && $status!='Expired') {
     $ssql.= " AND dl.IsApprove = '".$status."'";
}
    } else {
        if($status != ''){
            $ssql.= " AND (rd.vEmail LIKE '%".$keyword_s."%' OR  concat(rd.Suffix,rd.vName,rd.MiddleName,rd.vLastName) LIKE '%".$keyword_s."%' OR   dm.doc_usertype LIKE '%".$keyword_s."%' OR dm.doc_name LIKE '%".$keyword."%')";
             if($status != '' && $status!='Expired') 
     $ssql.= " AND dl.IsApprove = '".$status."'";
        } else {
            $ssql.= " AND (rd.vEmail LIKE '%".$keyword_s."%' OR concat(rd.Suffix,rd.vName,rd.MiddleName,rd.vLastName) LIKE '%".$keyword_s."%' OR dm.doc_usertype LIKE '%".$keyword_s."%' OR dm.doc_name LIKE '%".$keyword_s."%')";
        }
    }
} else if($status != '' && $keyword == '' && $status!='Expired') {
     $ssql.= " AND dl.IsApprove = '".$status."'";
}

if($status=='Expired')
{
     $ssql.= " AND dl.ex_date < '".date("Y-m-d")."' and ex_status='yes' ";

}
}


//Pagination Start
$per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page
$sql = "SELECT COUNT(dl.doc_id) AS Total FROM `document_list` dl left join document_master dm on dm.doc_masterid=dl.doc_masterid  LEFT join register_driver rd ON rd.iDriverId=dl.doc_userid and dl.doc_usertype!='company' and rd.eStatus!='Deleted'  WHERE 1=1 and rd.iCompanyId='$loggedInCompany' and dl.status!='Deleted' $eStatussql $ssql";
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

$sql = "SELECT dl.doc_id,dl.doc_masterid, dl.doc_usertype , dl.doc_file, dl.IsApprove ,dl.ex_date,dl.IsApprove,dm.doc_name,dm.eType,  concat(rd.Suffix,' ',rd.vName,' ',rd.MiddleName,' ',rd.vLastName) as Name, case when dl.ex_date < '".date("Y-m-d")."' and ex_status='yes' then 'true' else 'false' end as IsExpired,dl.edate,dl.doc_userid,rd.vEmail FROM `document_list` dl left join document_master dm on dm.doc_masterid=dl.doc_masterid  LEFT join register_driver rd ON rd.iDriverId=dl.doc_userid and dl.doc_usertype!='company' and rd.eStatus!='Deleted'  WHERE 1=1 and rd.iCompanyId='$loggedInCompany' and dl.status!='Deleted' $eStatussql $ssql $ord LIMIT $start, $per_page"; 
//echo $sql;
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
   <?php include_once("top/top_script.php");?>
      <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
        <link rel="stylesheet" type="text/css" href="admin/css/admin_new/admin_style.css">

        </head>
    <!-- END  HEAD-->
    
    <!-- BEGIN BODY-->
    <body class="padTop53 " >
        <!-- Main LOading -->
        <!-- MAIN WRAPPER -->
        <div id="wrap">
            <!-- Left Menu -->
      <?php include_once("top/left_menu.php");?>
      <!-- End: Left Menu-->
      <!-- Top Menu -->
      <?php include_once("top/header_topbar.php");?>
      <!-- End: Top Menu-->
            <!--PAGE CONTENT -->
             <div class="page-contant">
        <div class="page-contant-inner">
                    <div id="add-hide-show-div">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2>Documents</h2>
                            </div>
                        </div>
                        <hr />
                    </div>                
             <div class="row">
                <div class="col-md-1">
                    Search
                </div>
                 <div class="col-md-2">
                    <select name="option" id="option" class="form-control">
                        <option value="">Select</option>
                        <option value="driver" <? if($option=='driver') echo "selected"; ?>>Driver</option>
                        <option value="car" <? if($option=='car') echo "selected"; ?>>Car</option>
                        <option value="dm.eType" <? if($option=='dm.eType') echo "selected"; ?>>Service Category</option>

                    </select>
                </div>
                 <div class="col-md-2">
                    <input type="text" value="<?=$keyword;?>" name="keyword" id="keyword"  class="form-control">
                </div>
                 <div class="col-md-2">
                     <select name="status" id="status" class="form-control">
                        <option value="">Select</option>
                        <option value="Approved" <? if($status=='Approved') echo "selected"; ?>>Approved</option>
                        <option value="NotApproved"  <? if($status=='NotApproved') echo "selected"; ?>>Not Approved</option>
                        <option value="Rejected"  <? if($status=='Rejected') echo "selected"; ?>>Rejected</option>
                        <option value="Expired"  <? if($status=='Expired') echo "selected"; ?>>Expired</option>
                        
                    </select>
                </div>
                 <div class="col-md-2">
                    <input type="button" id="search" name="search" value="Search" class="btn btn-default">
                    <input type="button" onclick="window.location.href='approveDocuments.php'" name="reset" value="Reset" class="btn btn-default">

                </div>
             </div>
<br>

      <div class="table-responsive">
          <?php  if($msg!="")  { ?>
<div class="alert alert-success alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Success!</strong> <?=$msg;?>
</div>
               <?php  }   ?>      

                                            <form class="_list_form" id="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">

                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th width="10%"><a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)">Document For <?php if ($sortby == 1) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                                          <th width="20%"><a href="javascript:void(0);" onClick="Redirect(2,<?php if($sortby == '2'){ echo $order; }else { ?>0<?php } ?>)">Name <?php if ($sortby == 2) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>

                                                           <th width="20%"><a href="javascript:void(0);" onClick="Redirect(7,<?php if($sortby == '7'){ echo $order; }else { ?>0<?php } ?>)">Email <?php if ($sortby == 7) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>


                                                
                                                        <th width="20%"><a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)">Document Name <?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>

                                                        <th width="20%"><a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)">Expiry Date  <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>

                                                        <? if($APP_TYPE == 'Ride-Delivery' || $APP_TYPE == 'Ride-Delivery-UberX') {?>
                                                        <th width="8%"><a href="javascript:void(0);" onClick="Redirect(5,<?php if($sortby == '5'){ echo $order; }else { ?>0<?php } ?>)">Service Category <?php if ($sortby == 5) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                                        <? } ?>

                                                        <th width="20%"><a href="javascript:void(0);" onClick="Redirect(6,<?php if($sortby == '6'){ echo $order; }else { ?>0<?php } ?>)">Upload Date  <?php if ($sortby == 6) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                                        <th width="8%" align="center" style="text-align:center;">Status</th>
                                                         <th width="8%" class="align-center">View</th>

                                                        <th width="8%" class="align-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <? if(!empty($data_drv)) {
                                                    for ($i = 0; $i < count($data_drv); $i++) {
                                                $dis_img="admin/img/inactive-icon.png";
                                                     ?>
                                                    <tr class="gradeA">
                                                       
                                                        <td><?= $data_drv[$i]['doc_usertype']; ?></td>
                                                     <td><?=$data_drv[$i]['Name'];?></td>
                                                       <td><?=$data_drv[$i]['vEmail'];?></td>
                                                        <td><?= $data_drv[$i]['doc_name']; ?></td>
                                                        
                                                            <td><?= $data_drv[$i]['ex_date']=="0000-00-00"?'--':$data_drv[$i]['ex_date'];?>
                                                            <?php  if($data_drv[$i]['IsExpired'] == 'true'){
                                                                ?>
                                                              
                                                                 <img src="admin/img/expired-icon.png" alt="<?=$data_drv[$i]['IsExpired'];?>" data-toggle="tooltip" title="Expired">
                                                                 <?php                                                                
                                                            } ?>
                                                                


                                                            </td>
                                                             <td><?= $data_drv[$i]['eType']; ?></td>
                                                             <td><?= $data_drv[$i]['edate']; ?></td>

                                                        <td align="center">
                                                            <? if($data_drv[$i]['IsApprove'] == 'Approved') {
                                                                $dis_img = "admin/img/active-icon.png";
                                                            }else if($data_drv[$i]['IsApprove'] == 'NotApproved'){
                                                                $dis_img = "admin/img/inactive-icon.png";
                                                            }else if($data_drv[$i]['IsApprove'] == 'Rejected'){
                                                                $dis_img = "admin/img/delete-icon.png";
                                                            }?>
                                                            <img src="<?= $dis_img; ?>" alt="<?=$data_drv[$i]['IsApprove'];?>" data-toggle="tooltip" title="<?=$data_drv[$i]['IsApprove'];?>">
                                                        </td>
                                                        <td>
                                                            <?php                                              
                                                                   $file_ext = $generalobj->file_ext($data_drv[$i]['doc_file']);
                                                                   $url="";
                                                                   if($data_drv[$i]['doc_usertype']=="car")
                                                                   $url=$tconfig["tsite_upload_vehicle_doc_panel"];
                                                                    else if ($data_drv[$i]['doc_usertype']=="driver") 
                                                                   $url=$tconfig["tsite_upload_driver_doc"];
                                                                    else if ($data_drv[$i]['doc_usertype']=="company")
                                                                     $url=$tconfig["tsite_upload_compnay_doc"];
                                                          if(trim($data_drv[$i]['doc_file'])!="")
                                                         $url=$url . '/' . $data_drv[$i]['doc_userid'] . '/' . $data_drv[$i]['doc_file'];
                                                                   else
                                                                     $url="";



                                                    if ($file_ext == 'is_image') {

                                                        ?>

                                                        <a href="<?=$url;?>" target="_blank">View</a>

                                                        <!-- data-toggle="modal" data-target="#myModallicence" -->

                                                    <?php } else { ?>

                                                        <p><a href="<?=$url;?>" target="_blank"><!-- <?php echo $data_drv[$i]['doc_name']; ?> --> View</a></p>

                                                    <?php } ?>
                                                        </td>
                                                        <td align="center" style="text-align:center;" class="action-btn001">
                                                            <div class="share-button openHoverAction-class" style="display: block;">
                                                                <label class="entypo-export"><span><img src="admin/images/settings-icon.png" alt=""></span></label>
                                                                <div class="social show-moreOptions openPops_<?= $data_drv[$i]['doc_masterid']; ?>">
                                                                    <ul style="height:178px;">
                                                                      <!--   <li class="entypo-twitter" data-network="twitter"><a href="document_master_add.php?id=<?= $data_drv[$i]['doc_masterid']; ?>" data-toggle="tooltip" title="Edit">
                                                                            <img src="img/edit-icon.png" alt="Edit">
                                                                        </a></li> -->
                                                                        <?php if ($data_drv[$i]['eDefault'] != 'Yes') { ?>
                                                                        <li class="entypo-facebook" data-network="facebook"><a href="javascript:void(0);" onclick="changeDocumentStatus('<?php echo $data_drv[$i]['doc_id']; ?>','Approved','<?php echo $data_drv[$i]['doc_userid']; ?>','<?php echo $data_drv[$i]['doc_usertype'];?>','<?php echo $data_drv[$i]['doc_name']; ?>','<?php echo $url;?>')"  data-toggle="tooltip" title="Make Approve">
                                                                            <img src="admin/img/active-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >
                                                                        </a></li>
                                                                        <li class="entypo-gplus" data-network="gplus"><a href="javascript:void(0);" onclick="changeDocumentStatus('<?php echo $data_drv[$i]['doc_id']; ?>','NotApproved','<?php echo $data_drv[$i]['doc_userid']; ?>','<?php echo $data_drv[$i]['doc_usertype'];?>','<?php echo $data_drv[$i]['doc_name']; ?>','<?php echo $url;?>')" data-toggle="tooltip" title="Make Unapprove">
                                                                            <img src="admin/img/inactive-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >    
                                                                        </a></li>
                                                                        <li class="entypo-gplus" data-network="gplus"><a href="javascript:void(0);" onclick="changeDocumentStatus('<?php echo $data_drv[$i]['doc_id']; ?>','Rejected','<?php echo $data_drv[$i]['doc_userid']; ?>','<?php echo $data_drv[$i]['doc_usertype'];?>','<?php echo $data_drv[$i]['doc_name']; ?>','<?php echo $url;?>')"  data-toggle="tooltip" title="Reject">
                                                                            <img src="admin/img/delete-icon.png" alt="Delete" >
                                                                        </a></li>
                                                                        <li class="entypo-gplus" data-network="gplus"><a href="javascript:void(0);" onclick="requestToResubmit('<?php echo $data_drv[$i]['doc_userid']; ?>','<?php echo $data_drv[$i]['doc_usertype'];?>','<?php echo $data_drv[$i]['doc_name']; ?>','<?php echo $url;?>')"   data-toggle="tooltip" title="Resubmit">
                                                                            <img src="admin/img/resubmit-icon.png" alt="Resubmit" >
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
                                            <div style="margin-top: 10px;">
                      <?php include_once("pagging.php"); ?>
                              
                                    </div>
                                    </div>


                <!--END PAGE CONTENT -->
            </div>
                 </div>

 <form name="pageForm" id="pageForm" action="action/approveDocuments.php" method="post" >
<input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
<input type="hidden" name="tpages" id="tpages" value="<?php echo $tpages; ?>">
<input type="hidden" name="option" value="<?php echo $option; ?>" >
<input type="hidden" name="keyword" value="<?php echo $keyword; ?>" >
<input type="hidden" name="sortby" id="sortby" value="<?php echo $sortby; ?>" >
<input type="hidden" name="order" id="order" value="<?php echo $order; ?>" >
<input type="hidden" name="status" id="status" value="<?php echo $status; ?>" >
<input type="hidden" name="method" id="method" value="" >
</form>
              </div>
            <!--END MAIN WRAPPER -->
            

   <?php include_once('footer/footer_home.php');?>
   <?php include_once('top/footer_script.php');?>
     <script>
        $(document).ready(function(){
            $("#search").click(function(){
                var keyword=$("#keyword").val();
                   var option=$("#option").val();
                      var status=$("#status").val();
                      post('', {search:1,option:option,keyword:keyword,status:status});

            });
        });
        
        function requestToResubmit(id,type,docName,url,status='resubmit',isAsync=true)
        {
            debugger
            var data={iDriverId:id,document:docName,url:url,status:status};
            if(type=='company')
            {
      data={iCompanyId:id,document:docName,url:url,status:status};
            }


            $.ajax({
  url: "admin/documentResubmitRequest.php",
  type: "POST",
  data: data,
  cache: false,
  async:isAsync,
  success: function(res){
    console.log(res);
    if(res=="success" && status=='resubmit')
   alert("Request has been sent.");
  }
});
        }
           function changeDocumentStatus(id,status,userId,type,docName,url)
           {
             if(status=="NotApproved")
              status="unapproved";
            requestToResubmit(userId,type,docName,url,status,false);
post('', {ChangeStatus:1,statusUpdate:status,docId:id});
           }
            
        
            $('.entypo-export').click(function(e){
                 e.stopPropagation();
                 var $this = $(this).parent().find('div');
                 $(".openHoverAction-class div").not($this).removeClass('active');
                 $this.toggleClass('active');
            });
            
      function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
   form.setAttribute("id", "tempFrm");
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
    </body>
    <!-- END BODY-->
</html>

<style type="text/css">
  body
{
  background-color: white;
}


.button11
  {

        background: #219201;
    color: #FFFFFF;
        margin-top: 20px;
  }

.ui-helper-hidden-accessible div
{
display: none;
}

</style>