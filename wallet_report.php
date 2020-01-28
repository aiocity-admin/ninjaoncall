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
           data: {"loadtime":seconds,"beforeload":date1,"afterload":date2,"UserType":"COMPANY","eType":"WALLET_REPORT"}, 
           success: function(data)
           {
               
           }
         });

}
</script>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=$SITE_NAME?> | <?=$langage_lbl['LBL_VEHICLE_DRIVER_TXT_ADMIN']; ?></title>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
      <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
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
      <!-- History of Provider wallet page-->
      <div class="page-contant">
        <div class="page-contant-inner">
                    <div id="add-hide-show-div">
                        <div class="row">



                            <div class="col-lg-12">
              
                            
                                <h2>Provider Wallet Report</h2>

   
                            </div>
                        </div>
                        <hr />
<div>

  <br>
  <!-- Searching  -->
<div class="row">
  <div class="col-lg-12">
    <div class="col-lg-3">
      
                   <!--<div class='input-group date' id='fromDate'>-->

  <input type="text" placeholder="YYYY-MM-DD" name="fromDate" id="fromDate" value="<?php if(isset($_REQUEST['from'])) { echo $_REQUEST['from'];} ?>" class="form-control">
<!--  <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>

                  </div> -->
</div>
  <div class="col-lg-3">
    <input type="text"  placeholder="YYYY-MM-DD" name="fromDate" id="toDate" value="<?php if(isset($_REQUEST['to'])) { echo $_REQUEST['to'];} ?>" class="form-control">
</div>


  <div class="col-lg-2" >
    <?php 



  $sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 3;

  $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 1;

  $ord = '';

  if($sortby == 1){

    if($order == 0)

    $ord = " ORDER BY b.vName ASC";

    else

    $ord = " ORDER BY b.vName DESC";

  }


  if($sortby == 2){

    if($order == 0)

    $ord = " ORDER BY  b.vEmail ASC";

    else

    $ord = " ORDER BY  b.vEmail DESC";

  }

  if($sortby == 3){

    if($order == 0)

    $ord = " ORDER BY a.dDate ASC";

    else

    $ord = " ORDER BY a.dDate DESC";

  }

  if($sortby == 4){

    if($order == 0)

    $ord = " ORDER BY a.eType ASC";

    else

    $ord = " ORDER BY a.eType DESC";

  }

  if($sortby == 5){

    if($order == 0)

    $ord = " ORDER BY a.tDescription ASC";

    else

    $ord = " ORDER BY a.tDescription DESC";

  }

  if($sortby == 6){

    if($order == 0)

    $ord = " ORDER BY a.iBalance ASC";

    else

    $ord = " ORDER BY a.iBalance DESC";

  }



$BarangayId=$_SESSION['sess_iUserId'];
    $query="SELECT vName,vLastName,iDriverId,vEmail FROM `register_driver` where `iCompanyId`='$BarangayId'"; ?>
  <select id="providers" name="providers" class="form-control multiselect" multiple="multiple">
    <?php
   $list_0f_providers=$obj->MySQLSelect($query);
foreach ($list_0f_providers as $key => $Provider) {

?>
<option value="<?php echo  $Provider['iDriverId']; ?>" <?php
$SProvider=explode(',', $_REQUEST['providers']);
  for ($i=0; $i <count($SProvider) ; $i++) { 


 if (trim($SProvider[$i])==$Provider['iDriverId']) {
 echo 'selected';
}
} ?> ><?php echo  $Provider['vName']." ".$Provider['vLastName']." (".$Provider['vEmail'].")"; ?></option>
<?php
}
     ?>
  </select>


 </div>
  <div class="col-lg-4" >
  <input type="button" name="search" id="search"  class="btn button11" value="Search">
    <input type="button" name="reset" id="reset"  class="btn button11" value="Reset">
    <input type="button" name="export" id="export" style="float: right;" value="Export" class="btn btn-default"/>

 </div>

  </div>

</div>
  <!-- End Searching  -->
  <br>  <br>

<?php 
//Preparing Query
$iCompanyId = $_SESSION['sess_iUserId'];
$subQuery="where a.eUserType='Driver' and iCompanyId='$iCompanyId' ";
  $from = isset($_REQUEST['from']) ? $_REQUEST['from'] : "";

    $to = isset($_REQUEST['to']) ? $_REQUEST['to'] : "";
        $providers = isset($_REQUEST['providers']) ? $_REQUEST['providers'] : "null";


if (isset($_REQUEST['search'])) 
{
if (trim($from)!=''&&trim($to)!='') {
  # code...
$subQuery.=" and a.dDate between '".$from." 00:00:00' and  '".$to." 23:59:59'";
}
  if (trim($providers)!='null')
{

 $Provider= explode(',', $providers);
  $subQuery_providers='(';
  for ($i=0; $i <count($Provider) ; $i++) { 
  $subQuery_providers.=" b.iDriverId='$Provider[$i]' or";
  }
   $subQuery_providers=rtrim($subQuery_providers,'or');
 $subQuery_providers.=")";
$subQuery.=" and $subQuery_providers";

}

}

 //Pagination Start

  $per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page


  $sql = "SELECT  count(b.iDriverId ) as Total  FROM `user_wallet` a join register_driver b on a.iUserId=b.iDriverId $subQuery";
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


$token_query="SELECT b.vEmail,b.vName,b.vLastName,b.iDriverId,a.iBalance,a.eType,a.dDate,a.tDescription FROM `user_wallet` a join register_driver b on a.iUserId=b.iDriverId $subQuery  $ord LIMIT $start, $per_page";
$data=$obj->MySQLSelect($token_query);

$endRecord = count($data);
$var_filter = "";
foreach ($_REQUEST as $key=>$val) {
    if($key != "tpages" && $key != 'page')
    $var_filter.= "&$key=".stripslashes($val);
}

$reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages.$var_filter;


?>
<!--Listing History of Provider wallet -->
<div class="trips-table trips-table-driver trips-table-driver-res"> 
              <div class="trips-table-inner">
 <!--   <table class="table" cellpadding="0" cellspacing="1"  id="dataTables-example" aria-describedby="dataTables-example_info" style="width: 100%;">
                                                <thead>
                                                  <tr>
                                                    <th>Name</th>
                                                   <th>Username</th>
                                                   <th>Date</th>
                                               <th>Type</th>
<th>Description</th>
                                                    <th>Tokens</th>
                                                
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php 
for ($i=0; $i <count($data) ; $i++)
 { 
 ?>
 <tr>
   <td><?php echo $data[$i]['vName']." ".$data[$i]['vLastName']; ?></td>
  <td><?php echo $data[$i]['vEmail']; ?></td>
    <td><?php echo $data[$i]['dDate']; ?></td>
    <td><?php echo $data[$i]['eType']; ?></td>
    <td>
      <?php

                        $pat = '/\#([^\"]*?)\#/';
                                           preg_match($pat,  $data[$i]['tDescription'], $tDescription_value);
                                           $tDescription_translate = $langage_lbl[$tDescription_value[1]];
                                           $row_tDescription = str_replace($tDescription_value[0], $tDescription_translate,  $data[$i]['tDescription']);
                                           if (trim($row_tDescription)=="") {
                                          echo $data[$i]['tDescription'];
                                           }else{
                                            echo str_replace("#", "", $row_tDescription);
                                        }

                                            ?>


    </td>


    <td><?php echo $data[$i]['iBalance']; ?></td>
       

 </tr>
                                                  
<?php }?>

                                                </tbody>
                                              </table> -->

  <form class="_list_form" id="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">

 <table class="table" cellpadding="0" cellspacing="1"  id="" aria-describedby="dataTables-example_info" style="width: 100%;">
                                                <thead>
                                                  <tr>
                                                          <th>
 <a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)">Name <?php if ($sortby == 1) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                      <th>
 <a href="javascript:void(0);" onClick="Redirect(2,<?php if($sortby == '2'){ echo $order; }else { ?>0<?php } ?>)">Username <?php if ($sortby == 2) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                      <th>
 <a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)">Date <?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                      <th>
 <a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)">Type <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                     <th>
 <a href="javascript:void(0);" onClick="Redirect(5,<?php if($sortby == '5'){ echo $order; }else { ?>0<?php } ?>)">Description <?php if ($sortby == 5) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                      <th>
 <a href="javascript:void(0);" onClick="Redirect(6,<?php if($sortby == '6'){ echo $order; }else { ?>0<?php } ?>)">Tokens <?php if ($sortby == 6) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                  
                                                
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php 
                                                  if(count($data)>0){
for ($i=0; $i <count($data) ; $i++)
 { 
 ?>
 <tr>
   <td><?php echo $data[$i]['vName']." ".$data[$i]['vLastName']; ?></td>
  <td><?php echo $data[$i]['vEmail']; ?></td>
    <td><?php echo $data[$i]['dDate']; ?></td>
    <td><?php echo $data[$i]['eType']; ?></td>
    <td>
      <?php

                        $pat = '/\#([^\"]*?)\#/';
                                           preg_match($pat,  $data[$i]['tDescription'], $tDescription_value);
                                           $tDescription_translate = $langage_lbl[$tDescription_value[1]];
                                           $row_tDescription = str_replace($tDescription_value[0], $tDescription_translate,  $data[$i]['tDescription']);
                                           if (trim($row_tDescription)=="") {
                                          echo $data[$i]['tDescription'];
                                           }else{
                                            echo str_replace("#", "", $row_tDescription);
                                        }

                                            ?>


    </td>


    <td><?php echo $data[$i]['iBalance']; ?></td>
       

      <!--  <td><?php //echo $data[$i]['Tokens']; ?></td>-->
 </tr>
                                                  
<?php } }else  {
                                                      ?>
                                                      <tr><td colspan="6">No Record Found!</td></tr>
                                                      <?php
                                                    }?>

                                                </tbody>
                                              </table>

</form>


                                            </div></div>

                                              <div style="margin-top: 10px;">
                      <?php include_once("pagging.php"); ?>
                              
                                    </div>
                                            <!--End Listing History of Provider wallet -->

</div>


                    </div>
                   
                        
                   
                  
                    </div>
                </div>
                <!--END PAGE CONTENT -->
            </div>
            <!--END MAIN WRAPPER -->

 <form name="pageForm" id="pageForm"  method="post" >
<input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
<input type="hidden" name="tpages" id="tpages" value="<?php echo $tpages; ?>">
<input type="hidden" name="iRatingId" id="iMainId01" value="" >

<input type="hidden" name="providers"  value="<?php echo $providers; ?>" >

<input type="hidden" name="from"  value="<?php echo $from; ?>" >
<input type="hidden" name="to"  value="<?php echo $to; ?>" >
<?php if((trim($to)!=""&&trim($from)!="")||trim($providers)!="null") {?>
<input type="hidden" name="search"  value="search" >
<? } ?>

<input type="hidden" name="option" value="<?php echo $option; ?>" >
<input type="hidden" name="keyword" value="<?php echo $keyword; ?>" >
<input type="hidden" name="sortby" id="sortby" value="<?php echo $sortby; ?>" >
<input type="hidden" name="order" id="order" value="<?php echo $order; ?>" >
<input type="hidden" name="method" id="method" value="" >
</form> 

<script type="text/javascript">
 /* function exportToExcel(){
var htmls = "";
            var uri = 'data:application/vnd.ms-excel;base64,';
            var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'; 
            var base64 = function(s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            };

            var format = function(s, c) {
                return s.replace(/{(\w+)}/g, function(m, p) {
                    return c[p];
                })
            };

            htmls = $("#dataTables-example").html();

            var ctx = {
                worksheet : 'Worksheet',
                table : htmls
            }


            var link = document.createElement("a");
            link.download = "export.xls";
            link.href = uri + base64(format(template, ctx));
            link.click();
}*/
$(document).ready(function(){

      $("#fromDate").datetimepicker({format: 'YYYY-MM-DD'});
      $("#toDate").datetimepicker({format: 'YYYY-MM-DD'});

//Sending request for export data
$("#export").click(function(){
var from=$("#fromDate").val();
var to=$("#toDate").val();
var provider=$("#providers").val();

//exportToExcel();
window.location.href="export_wallet_report.php?search=1&from="+from+"&to="+to+"&providers="+provider;


});

//Sending request for searching
$("#search").click(function(){

var from=$("#fromDate").val();
var to=$("#toDate").val();
var provider=$("#providers").val();

/*if (from==""||to=="") {
alert("Please select Date.");
}
else{*/
window.location.href="wallet-report?search=1&from="+from+"&to="+to+"&providers="+provider;

/*}
*/
});

//reset page
$("#reset").click(function(){

  window.location.href="wallet-report";
});

    $('#dataTables-example').dataTable({
        fixedHeader: {
          footer: true
        },
        "order": [],
        "aaSorting": []});


     $('.multiselect').multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"150px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Providers',
     maxHeight:300
  });

  });

</script>


    </body>
    <!-- END BODY-->
      <?php include_once('footer/footer_home.php');?>
<script src="assets/js/jquery-ui.min.js"></script>
<script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $tconfig["tsite_url_main_admin"]?>css/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/bootstrap-datetimepicker.min.js"></script>
 <?php include_once('top/footer_script.php');?>
       <link rel="stylesheet" type="text/css" href="admin/css/admin_new/admin_style.css">










</html>
<style type="text/css">
  body
{
  background-color: white;
}

 /* .paginate_button 
  {
        display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.428571429;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
        color: #333333;
    background-color: #ffffff;
    border-color: #cccccc;
  }*/
.button11
  {

        background: #219201;
    color: #FFFFFF;
  }

 

.ui-helper-hidden-accessible div
{
display: none;
}
#search
{
  margin-left: 5px;
}
</style>
<script type="text/javascript">
  window.onload = getPageLoadTime;

</script>