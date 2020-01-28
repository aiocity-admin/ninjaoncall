<?php
include_once('common.php');
$generalobj->check_member_login();
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
           url: "LoadingTime/loadtime.php",
           data: {"loadtime":seconds,"beforeload":date1,"afterload":date2,"UserType":"COMPANY","eType":"REVIEW"}, 
           success: function(data)
           {
               
           }
         });

}
</script>
<?php
/*error_reporting(0);
ini_set('display_errors', 1);*/
$script = 'Review';

$type=(isset($_REQUEST['reviewtype']) && $_REQUEST['reviewtype'] !='')?$_REQUEST['reviewtype']:'Driver';
$reviewtype=$type;


  $sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 5;

  $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 1;

  $ord = '';

  if($sortby == 1){

    if($order == 0)

    $ord = " ORDER BY vRideNo ASC";

    else

    $ord = " ORDER BY vRideNo DESC";

  }

  

   if($reviewtype=='Driver'){
 if($sortby == 2){

    if($order == 0)

    $ord = " ORDER BY rd.vName ASC";

    else

    $ord = " ORDER BY rd.vName DESC";

  }
   if($sortby == 3){

    if($order == 0)

    $ord = " ORDER BY ru.vName ASC";

    else

    $ord = " ORDER BY ru.vName DESC";

  }

            }else
            {
               if($sortby == 2){

    if($order == 0)

    $ord = " ORDER BY ru.vName ASC";

    else

    $ord = " ORDER BY ru.vName DESC";

  }
                     if($sortby == 3){

    if($order == 0)

    $ord = " ORDER BY rd.vName ASC";

    else

    $ord = " ORDER BY rd.vName DESC";

  }                                                  
                
                  }

 

  

 

  if($sortby == 4){

    if($order == 0)

    $ord = " ORDER BY r.vRating1  ASC";

    else

    $ord = " ORDER BY r.vRating1  DESC";

  }
  if($sortby == 5){

    if($order == 0)

    $ord = " ORDER BY r.tDate ASC";

    else

    $ord = " ORDER BY r.tDate DESC";

  }

  if($sortby == 6){

    if($order == 0)

    $ord = " ORDER BY r.vMessage ASC";

    else

    $ord = " ORDER BY r.vMessage  DESC";

  }
   

if (isset($_REQUEST['delete'])) 
{
   $iRatingId=$_REQUEST['id'];
        $query = "DELETE FROM ratings_user_driver WHERE iRatingId = '" . $iRatingId . "'";
            $obj->sql_query($query);
            
            $var_msg = 'Record deleted successfully.';     
}

// Start Search Parameters
$option = isset($_REQUEST['option'])?stripslashes($_REQUEST['option']):"";
$keyword = isset($_REQUEST['keyword'])?stripslashes($_REQUEST['keyword']):"";
$searchDate = isset($_REQUEST['searchDate'])?$_REQUEST['searchDate']:"";
$ssql = '';
if($keyword != ''){
    if($option != '') {
        if (strpos($option, 'r.eStatus') !== false) {
            $ssql.= " AND ".stripslashes($option)." LIKE '".$keyword."'";
        }else {
            $option_new = $option;
            if($option == 'drivername'){
              $option_new = "CONCAT(rd.vName,' ',rd.vLastName)";
            } 
            if($option == 'ridername'){
              $option_new = "CONCAT(ru.vName,' ',ru.vLastName)";
            }
            $ssql.= " AND ".stripslashes($option_new)." LIKE '%".$keyword."%'";
        }
    }else {
        $ssql.= " AND (t.vRideNo LIKE '%".$keyword."%' OR  concat(rd.vName,' ',rd.vLastName) LIKE '%".$keyword."%' OR concat(ru.vName,' ',ru.vLastName) LIKE '%".$keyword."%' OR r.vRating1 LIKE '%".$keyword."%')";
    }
}
// End Search Parameters

$chkusertype ="";
if($type == "Driver")
{
  $chkusertype = "Passenger";
}
else
{
  $chkusertype = "Driver";
} 
$iCompanyId=$_SESSION['sess_iUserId'];


  //Pagination Start

  $per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page


  $sql = "SELECT  count(r.iTripId) as Total  FROM ratings_user_driver as r 
LEFT JOIN trips as t ON r.iTripId=t.iTripId 
LEFT JOIN register_driver as rd ON rd.iDriverId=t.iDriverId 
LEFT JOIN register_user as ru ON ru.iUserId=t.iUserId 
WHERE 1=1 AND r.eUserType='".$chkusertype."' And ru.eStatus!='Deleted' and rd.iCompanyId='$iCompanyId'   $ssql";

  $totalData = $obj->MySQLSelect($sql);

  $total_results = $totalData[0]['Total'];

  $total_pages = ceil($total_results / $per_page); //total pages we going to have

  $show_page = 1;


//Pagination Start

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
    $page = isset($_GET['page']) ? intval($_GET['page']) : 0;

  $tpages=$total_pages;

  if ($page <= 0)

    $page = 1;



 $sql = "SELECT r.iRatingId,r.iTripId,r.vRating1,r.tDate,r.eUserType,r.vMessage,CONCAT(rd.vName,' ',rd.vLastName) as driverName ,rd.vAvgRating,CONCAT(ru.vName,' ',ru.vLastName) as passangerName,ru.vAvgRating as passangerrate,t.iDriverId,t.iUserId,t.vRideNo 
FROM ratings_user_driver as r 
LEFT JOIN trips as t ON r.iTripId=t.iTripId 
LEFT JOIN register_driver as rd ON rd.iDriverId=t.iDriverId 
LEFT JOIN register_user as ru ON ru.iUserId=t.iUserId 
WHERE 1=1 AND r.eUserType='".$chkusertype."' And ru.eStatus!='Deleted' and rd.iCompanyId='$iCompanyId'   $ssql  $ord LIMIT $start, $per_page";
			
//$ssql $adm_ssql $ord
$data_drv = $obj->MySQLSelect($sql);
//echo "<pre>";
//print_r($data_drv);
//exit;
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
        <title><?=$SITE_NAME?> | Reviews</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <?php include_once("top/top_script.php");?>
       <link rel="stylesheet" type="text/css" href="admin/css/admin_new/admin_style.css">

    </head>
    <!-- END  HEAD-->
    
    <!-- BEGIN BODY-->
    <body class="padTop53 " >
        <!-- Main LOading -->
        <!-- MAIN WRAPPER -->
        <div id="wrap">
           
  <!-- home page -->
    <div id="main-uber-page">
      <!-- Left Menu -->
      <?php include_once("top/left_menu.php");?>
      <!-- End: Left Menu-->
      <!-- Top Menu -->
      <?php include_once("top/header_topbar.php");?>
      <!-- End: Top Menu-->
      <!-- History of Provider wallet page-->
      <div class="page-contant">
        <div class="page-contant-inner">
                    <div id="add-hide-show-div">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2>Reviews</h2>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <?php include('valid_msg.php'); ?>
					<div class="panel-heading">
                                         <form name="frmsearch" id="frmsearch" action="javascript:void(0);" method="post">

                    <div class="row">
                                    <div class="col-md-1">
                                      <label for="textfield"><strong>Search:</strong></label>
                                    </div>
                                    <div class="col-md-3">
                                      <select name="option" id="option" class="form-control">
                                          <option value="">All</option>
                                          <option value="t.vRideNo" <?php if ($option == "t.vRideNo") { echo "selected"; } ?> >Ride Number</option>
                                          <option value="drivername" <?php if ($option == "drivername") {echo "selected"; } ?> >Driver Name</option>
                                          <option value="ridername" <?php if ($option == "ridername") {echo "selected"; } ?> >Rider Name</option>
                                          <option value="r.vRating1" <?php if ($option == 'r.vRating1') {echo "selected"; } ?> >Rate</option>
                                    </select>
                                    </div>
                                     <div class="col-md-4"><input type="Text" id="keyword" name="keyword" value="<?php echo $keyword; ?>"  class="form-control" />
 <input type="hidden" name="reviewtype" id="reviewtype2" value="<?php echo $reviewtype; ?>" >
 
                                    </div>
                                     <div class="col-md-4">
                                      <input type="submit" value="Search" class="btn btnalt button11" id="Search" name="Search" title="Search" />
                                      <input type="button" value="Reset" class="btn btnalt button11" onClick="window.location.href='review.php'"/>
                                      <?php if(!empty($data_drv)) { ?>
                                      <button type="button" id="export" class="btn btnalt button11" >Export</button>
                                      <?php } ?>
                                        </div>                                    
                             </div>
						
						
                        
                      </form>
                      <hr>
                      <?php 
 if(trim($var_msg)!="")
 {
  ?>
   <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                <?php echo $var_msg; ?>
                            </div><br/>
  <?php
 }

 ?>
                    <div class="table-list">
                        <div class="row">
                            <div class="col-lg-12">
                                
                                    <div style="clear:both;"></div>
                                        <div class="">
                                            
											<div class="panel-heading">
                        <form class="_list_form" id="_list_form2" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                                                  <ul class="nav nav-tabs">
													  <li <?php if($reviewtype=='Driver'){?> class="active" <?php } ?>>
														  <a data-toggle="tab"  onclick="getReview('Driver')"  href="#home" ><b><?=$langage_lbl_admin['LBL_DRIVERS_NAME_ADMIN'];?></b></a></li>
													  <li <?php if($reviewtype=='Passenger'){?> class="active" <?php } ?>>
														  <a data-toggle="tab" onclick="getReview('Passenger')"  href="#menu1"><b><?=$langage_lbl_admin['LBL_DASHBOARD_USERS_ADMIN'];?></b></a></li>
												  </ul>
                                                                      </form>

											</div>
                      <div class="trips-table trips-table-driver trips-table-driver-res"> 
              <div class="trips-table-inner">
										<!-- 	<table class="table table-bordered table-hover" id="dataTables-example" aria-describedby="dataTables-example_info">
                                                            <thead>
                                                                 <tr>
																 <th><?=$langage_lbl_admin['LBL_RIDE_TXT_ADMIN'];?> Number </th>
																	<?php if($reviewtype=='Driver'){?>
                                                                      <th ><?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?>  Name (Average Rate)</th>
																	  
                                                                      <th ><?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?>  Name</th>
																	  
																	<?php }else{?>
																	   <th ><?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?>   Name(Average Rate)
                                     </th>
																	   <th ><?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Name </th>
																	<?}?>
																	
                                                                      <th  class="align-center">Rate</th>
                                                                     
                                                                      <th  class="align-center">Date </th>
																	  
																	  <th>Comment </th>
																	  
                                                                      <th >Delete</th>
                                                                 </tr>
                                                            </thead>
															<tbody>
                                                    <?php 
                                                   
                                                    for ($i = 0; $i < count($data_drv); $i++) { 
                                                        
                                                        $default = '';
                                                        if($data_drv[$i]['eDefault']=='Yes'){
                                                                $default = 'disabled';
                                                        } ?>
                                                    <tr class="gradeA">
																 <td width="10%"><?php echo $data_drv[$i]['vRideNo']; ?></td>

																 <?php if($reviewtype=='Driver'){?>
																		
                                                                      <td><? echo $data_drv[$i]['driverName'];

																		  echo " <b dir='ltr'> ( ".$data_drv[$i]['vAvgRating']." )</b>";
																		  ?></td>
																	  
                                                                      <td><? echo $data_drv[$i]['passangerName']; ?></td>
																	  <?php }else{?>
																	  <td><? echo $data_drv[$i]['passangerName']; 
																		   echo " <b dir='ltr'>( ".$data_drv[$i]['passangerrate']." ) </b>";
																		  ?></td>
																	   <td><? echo $data_drv[$i]['driverName']; ?></td>
                                                                      
																	  <?}?>
																	    <td align="center"> <?= $data_drv[$i]['vRating1'] ?> </td>
                                                                     
                                                                    
                                                                      <td align="center" ><?= $data_drv[$i]['tDate'];?></td>
                                                                      <td > <?= $data_drv[$i]['vMessage'] ?></td>  
																	   <td align="center" >
																			<a href="javascript:void(0);" onClick="changeStatusDelete('<?php echo $data_drv[$i]['iRatingId']; ?>')"  data-toggle="tooltip" title="Delete">
                                                                                <img src="admin/img/delete-icon.png" alt="Delete" >
                                                                            </a>
                                                                      </td>
                                                                 </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table> -->
 











  <form class="_list_form" id="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
   <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="1"  id="dataTables-example" aria-describedby="dataTables-example_info">
                                                <thead>
                                                  <tr>
                                                    <th>  <a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)"><?=$langage_lbl_admin['LBL_RIDE_TXT_ADMIN'];?> Number<?php if ($sortby == 1) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                                <!--     <th>Company Name </th> -->


                                                 <th>  <a href="javascript:void(0);" onClick="Redirect(2,<?php if($sortby == '2'){ echo $order; }else { ?>0<?php } ?>)">
                                                 <?php if($reviewtype=='Driver'){
                                                                      echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']." Name (Average Rate)";?>  
                                    
                                    
                                  <?php }else{?>
                                    <?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN']."  Name(Average Rate)";?>  
                                  <?}?>
                                   <?php if ($sortby == 2) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                    </th>

                                                   <th> 
 <a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)"><?php 
 if($reviewtype=='Driver'){
                                                                    
                                    
                                                                     echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN']." Name";?>  
                                    
                                  <?php }else{?>
                                     <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']." Name";?>  
                                  <?}?>
                                   <?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                   </th>
                                                <th>
 <a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)">Rate <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                   <th>
 <a href="javascript:void(0);" onClick="Redirect(5,<?php if($sortby == '5'){ echo $order; }else { ?>0<?php } ?>)">Date/Time<?php if ($sortby == 5) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                   </th>
                                                      <th>
 <a href="javascript:void(0);" onClick="Redirect(6,<?php if($sortby == '6'){ echo $order; }else { ?>0<?php } ?>)">Comment<?php if ($sortby == 6) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                      </th>
                                                         <th>
 Action
                                                         </th>
                                                  </tr>
                                                </thead>
                                                  <tbody>
                                                    <?php 
                                                   if(count($data_drv)>0){
                                                    for ($i = 0; $i < count($data_drv); $i++) { 
                                                        
                                                        $default = '';
                                                        if($data_drv[$i]['eDefault']=='Yes'){
                                                                $default = 'disabled';
                                                        } ?>
                                                    <tr class="gradeA">
                                 <td width="15%"><?php echo $data_drv[$i]['vRideNo']; ?></td>

                                 <?php if($reviewtype=='Driver'){?>
                                    
                                                                      <td width="15%"><? echo $data_drv[$i]['driverName'];

                                      echo " <b dir='ltr'> ( ".$data_drv[$i]['vAvgRating']." )</b>";
                                      ?></td>
                                    
                                                                      <td width="15%"><? echo $data_drv[$i]['passangerName']; ?></td>
                                    <?php }else{?>
                                    <td><? echo $data_drv[$i]['passangerName']; 
                                       echo " <b dir='ltr'>( ".$data_drv[$i]['passangerrate']." ) </b>";
                                      ?></td>
                                     <td width="15%"><? echo $data_drv[$i]['driverName']; ?></td>
                                                                      
                                    <?}?>
                                      <td align="center" width="15%"> <?= $data_drv[$i]['vRating1'] ?> </td>
                                                                     
                                                                    
                                                                      <td align="center" ><?= $data_drv[$i]['tDate'];?></td>
                                                                      <td > <?= $data_drv[$i]['vMessage'] ? $data_drv[$i]['vMessage'] : '-' ?></td>  
                                     <td width="15%" align="center" >
                                      <a href="javascript:void(0);" onClick="changeStatusDelete('<?php echo $data_drv[$i]['iRatingId']; ?>')"  data-toggle="tooltip" title="Delete">
                                                                                <img src="admin/img/delete-icon.png" alt="Delete" >
                                                                            </a>
                                                                      </td>
                                                                 </tr>
                                                    <?php } } else
                                                    {
                                                      ?>
                                                      <tr><td colspan="8">No Record Found!</td></tr>
                                                      <?php
                                                    }?>
                                                    </tbody>
                                              </table>

                                            </form>










                                              </div>
                                          </div>
                                </div> <!--TABLE-END-->
                                    
                            </div>

                        </div>
                        <div style="margin-top: 10px;">
                      <?php include_once("pagging.php"); ?>
                              
                                    </div>
               </div></div></div></div>
                    </div>
                </div>
                <!--END PAGE CONTENT -->
            </div>
            <!--END MAIN WRAPPER -->
            
 <form name="pageForm" id="pageForm" action="action/review.php" method="post" >
<input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
<input type="hidden" name="tpages" id="tpages" value="<?php echo $tpages; ?>">
<input type="hidden" name="iRatingId" id="iMainId01" value="" >
<input type="hidden" name="reviewtype" id="reviewtype" value="<?php echo $reviewtype; ?>" >
<input type="hidden" name="status" id="status01" value="" >
<input type="hidden" name="statusVal" id="statusVal" value="" >
<input type="hidden" name="option" value="<?php echo $option; ?>" >
<input type="hidden" name="keyword" value="<?php echo $keyword; ?>" >
<input type="hidden" name="sortby" id="sortby" value="<?php echo $sortby; ?>" >
<input type="hidden" name="order" id="order" value="<?php echo $order; ?>" >
<input type="hidden" name="method" id="method" value="" >
</form> 
   
   <?php //include_once('footer/footer_home.php');?>
<script src="assets/js/jquery-ui.min.js"></script>
<script src="assets/plugins/dataTables/jquery.dataTables.js"></script>

 <?php
//include_once "admin/main_functions.php";
  include_once('top/footer_script.php');?>

 
        <script>
  $(document).ready(function(){

       /*     $('#dataTables-example').dataTable({
        fixedHeader: {
          footer: true
        },
        "bSort": true,

        "order": [],
        "aaSorting": []});*/

           $("#export").click(function(){

   var formValus = $("#frmsearch").serialize();
                window.location.href = "export_review.php?"+formValus;

           }); 


           });

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
			function getReview(type)
			{
				$('#reviewtype').val(type);
				var action = $("#_list_form").attr('action');
                var formValus = $("#pageForm").serialize();
                window.location.href = action+"?"+formValus;
			}
            

            function changeStatusDelete(id) {
  
  if (confirm('Are you sure to delete this review?'))
   {
                window.location.href = "review.php?id="+id+"&delete=1";
  }

}
        </script>
		 <script>
		  
	</script>
    </body>
    <style type="text/css">
      body
      {
        background-color: white;
      }
    .button11
  {

        background: #219201;
    color: #FFFFFF;
  }




    </style>
    <!-- END BODY-->
</html>
<script type="text/javascript">
  window.onload = getPageLoadTime;

</script>