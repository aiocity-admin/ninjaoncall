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
debugger
 $.ajax({
           type: "POST",
           url: "LoadingTime/loadtime.php",
           data: {"loadtime":seconds,"beforeload":date1,"afterload":date2,"UserType":"COMPANY","eType":"TOKENS"}, 
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
    <title><?=$SITE_NAME?> | Tokens</title>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
      <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
    <style>
        .pagination ul{ margin-top:10px; }
    </style>
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
                 
                        <div class="row">



                            <div class="col-lg-12">
              
                            
                                <h2>Tokens</h2>

   
                            </div>
                        </div>
                        <hr />
<?php

//payment
if (isset($_REQUEST['payment']))
 {
  $_SESSION['success']='';
  //inserting tokens into database if payment done
if(trim($_REQUEST['payment'])=='success'&&trim($_SESSION['ref_no'])==trim($_REQUEST['refNo']))
{
  if (trim($_SESSION['tokens'])!="") {  
$tokens=$_SESSION['tokens'];
$iAdminUserId=$_SESSION['sess_iCompanyId'];
$ref_No=$_SESSION['ref_no'];
$payment=$_SESSION['totalAmount'];
$commission=$_SESSION['tokens_commision'];
$payment_status='Success';
/*$credit_token="INSERT INTO `barangay_tokens`(`BarangayId`, `Tokens`, `Type`, `TDate`,`ref_No`,`payment`,`commission`,`payment_status`) VALUES ('$iAdminUserId','$tokens','Credit','". date("Y-m-d H:i:s")."','$ref_No','$payment','$commission','$payment_status')";*/
$credit_token="update `barangay_tokens` set Type='Credit',`payment_status`='$payment_status' where ref_No='$ref_No'";

$obj->MySQLSelect($credit_token);
$_SESSION['tokens']="";
$_SESSION['ref_no']="";
$_SESSION['totalAmount']="";
$_SESSION['tokens_commision']="";
$payment_status="";
$_SESSION['success']="success";
header("location:tokens.php");
}
?>

<?php

}
else {
$ref_No=$_SESSION['ref_no'];
  $payment_status="Failure";
 $credit_token="update `barangay_tokens` set `payment_status`='$payment_status' where ref_No='$ref_No'";
 $obj->MySQLSelect($credit_token);
 $_SESSION['tokens']="";
 //$_SESSION['ref_no']="";
 $_SESSION['totalAmount']="";
 $_SESSION['tokens_commision']="";
 $payment_status="";

  ?>
  <!-- alert on payment failure-->
<div class="alert alert-danger">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

  <strong>Payment Failure.</strong>
</div>
<br>

  <?php



}

}

if ( $_SESSION['success']=='success') {
  # code...


 ?>
 <!--Success alert on payment successful-->
<div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

  <strong>Success!</strong> Payment successful.
</div>
<br><?php $_SESSION['success']=''; }?>
<div class="row">
  <div class="col-lg-12">
    <div class="col-lg-2">
      <button id="BuyTokens" class="btn btn-default button11">Purchase Tokens</button>
</div>
    <div class="col-lg-7">
</div>
  <div class="col-lg-3">
    <!-- Showing available tokens into company account -->
    <label>Token Balance : </label>
    <?php
    $BarangayId=$_SESSION['sess_iCompanyId'];
     $token_query="SELECT sum(`Tokens`) as Credit FROM `barangay_tokens` where Type='Credit' and BarangayId='$BarangayId'";
$Credit=$obj->MySQLSelect($token_query);
   $token_query="SELECT sum(`Tokens`) as Debit FROM `barangay_tokens` where Type='Debit' and BarangayId='$BarangayId'";
$Debit=$obj->MySQLSelect($token_query);

$token=$Credit[0]['Credit']-$Debit[0]['Debit'];
  ?>
    <span id="Available"><?php echo $token;?></span>
</div>
  </div>

</div>
  <br> 
<h4>History of Past Token Purchases</h4>

   <br>
   <!--Searching  -->
<div class="row">
  <div class="col-lg-12">
    <div class="col-lg-3">
      
   
  <input type="text"  placeholder="From Date" name="fromDate" id="fromDate" value="<?php if(isset($_REQUEST['from'])) { echo $_REQUEST['from'];} ?>" class="form-control"> <!-- <i class="fa fa-angle-down"></i>-->
</div>
  <div class="col-lg-3">
    <input type="text"  placeholder="To Date" name="toDate" id="toDate" value="<?php if(isset($_REQUEST['to'])) { echo $_REQUEST['to'];} ?>" class="form-control"> <!-- <i class="fa fa-angle-down"></i>-->
</div>
  <div class="col-lg-3" >
  <input type="button" name="search" id="search"  class="btn button11" value="Search">
    <input type="button" name="reset" id="reset"  class="btn button11" value="Reset">

 </div>
  <div class="col-lg-2">
 </div>
  </div>

</div>
  <!--Searching End  -->
  <br>  <br>

<?php 
$subQuery="where (Type='Credit' OR Type='' ) and BarangayId='$BarangayId' ";

  $from = isset($_REQUEST['from']) ? $_REQUEST['from'] : "";

    $to = isset($_REQUEST['to']) ? $_REQUEST['to'] : "";

if (isset($_REQUEST['search'])) 
{

$subQuery.=" and TDate between '".$from." 00:00:00' and  '".$to." 23:59:59'";
}


  $sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 3;

  $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 1;

  $ord = '';

  if($sortby == 1){

    if($order == 0)

    $ord = " ORDER BY `ref_No` ASC";

    else

    $ord = " ORDER BY `ref_No` DESC";

  }


  if($sortby == 2){

    if($order == 0)

    $ord = " ORDER BY  `Tokens` ASC";

    else

    $ord = " ORDER BY `Tokens` DESC";

  }

  if($sortby == 3){

    if($order == 0)

    $ord = " ORDER BY TDate ASC";

    else

    $ord = " ORDER BY TDate DESC";

  }

 if($sortby == 4){

    if($order == 0)

    $ord = " ORDER BY payment ASC";

    else

    $ord = " ORDER BY payment DESC";

  }
   if($sortby == 5){

    if($order == 0)

    $ord = " ORDER BY commission ASC";

    else

    $ord = " ORDER BY commission DESC";

  }
   if($sortby == 6){

    if($order == 0)

    $ord = " ORDER BY payment_status ASC";

    else

    $ord = " ORDER BY payment_status DESC";

  }

 $per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page


  $sql = "SELECT  count(`ID`) as Total   FROM `barangay_tokens` $subQuery";
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


$token_query="SELECT `ID`, `BarangayId`, `Tokens`, `Type`, `TDate`, `ref_No`, `payment`, `payment_status`, `commission`  FROM `barangay_tokens` $subQuery  $ord LIMIT $start, $per_page";
$data=$obj->MySQLSelect($token_query);

$endRecord = count($data);
$var_filter = "";
foreach ($_REQUEST as $key=>$val) {
    if($key != "tpages" && $key != 'page')
    $var_filter.= "&$key=".stripslashes($val);
}

$reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages.$var_filter;

?>
<!--List of past tokens purchases -->
<div class="trips-table trips-table-driver trips-table-driver-res"> 
              <div class="trips-table-inner">
 <!--   <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="1"  id="dataTables-example" aria-describedby="dataTables-example_info" style="width: 100%;">
                                                <thead>
                                                  <tr>
                                                     <th>Reference Number</th>
                                                    <th>Tokens</th>
                                                   <th>Date</th>
                                                 
                                                  <th>Total Purchase</th>
                                                    <th>Service Charge</th>
                                                      <th>Payment status</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php 
for ($i=0; $i <count($data) ; $i++)
 { 
 ?>
 <tr>
   <td><?php echo $data[$i]['ref_No']; ?></td>
   <td><?php echo $data[$i]['Tokens']; ?></td>
  <td><?php echo $data[$i]['TDate']; ?></td>
 
     <td><?php echo $data[$i]['payment']; ?></td>
        <td><?php echo $data[$i]['commission']; ?></td>
            <td><?php echo $data[$i]['payment_status']; ?></td>
 </tr>
                                                  
<?php }?>

                                                </tbody>
                                              </table> -->
  <form class="_list_form" id="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">


 <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="1"   aria-describedby="dataTables-example_info" style="width: 100%;">
                                                <thead>
                                                  <tr>
                                                             <th>
 <a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)">Reference Number <?php if ($sortby == 1) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                         <th>
 <a href="javascript:void(0);" onClick="Redirect(2,<?php if($sortby == '2'){ echo $order; }else { ?>0<?php } ?>)">Tokens <?php if ($sortby == 2) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                         <th>
 <a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)">Date <?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                         <th>
 <a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)">Total Purchase <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                         <th>
 <a href="javascript:void(0);" onClick="Redirect(5,<?php if($sortby == '5'){ echo $order; }else { ?>0<?php } ?>)">Service Charge <?php if ($sortby == 5) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                         <th>
 <a href="javascript:void(0);" onClick="Redirect(6,<?php if($sortby == '6'){ echo $order; }else { ?>0<?php } ?>)">Payment status <?php if ($sortby == 6) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                 
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php 
                                                  if (count($data)>0) {
                                                    
for ($i=0; $i <count($data) ; $i++)
 { 
 ?>
 <tr>
   <td><?php echo $data[$i]['ref_No']; ?></td>
   <td><?php echo $data[$i]['Tokens']; ?></td>
  <td><?php echo $data[$i]['TDate']; ?></td>
 
     <td><?php echo $data[$i]['payment']; ?></td>
        <td><?php echo $data[$i]['commission']; ?></td>
            <td><?php echo $data[$i]['payment_status']; ?></td>
 </tr>
                                                  
<?php }} else { ?>
                                 <tr><td colspan="8">No Record Found!</td></tr>
                                                      <?php
                                                    }?>

                                                </tbody>
                                              </table>
</form>
                                            </div></div><div style="margin-top: 10px;">
                      <?php include_once("pagging.php"); ?>
                              
                                    </div>

</div>


                    </div>
                   
                        
                   
                   
                 
              
            </div>   
             <form name="pageForm" id="pageForm"  method="post" >
<input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
<input type="hidden" name="tpages" id="tpages" value="<?php echo $tpages; ?>">

<input type="hidden" name="from"  value="<?php echo $from; ?>" >
<input type="hidden" name="to"  value="<?php echo $to; ?>" >
<?php if($to!=""&&$from!="") {?>
<input type="hidden" name="search"  value="search" >
<? } ?>
<input type="hidden" name="iRatingId" id="iMainId01" value="" >


<input type="hidden" name="option" value="<?php echo $option; ?>" >
<input type="hidden" name="keyword" value="<?php echo $keyword; ?>" >
<input type="hidden" name="sortby" id="sortby" value="<?php echo $sortby; ?>" >
<input type="hidden" name="order" id="order" value="<?php echo $order; ?>" >
<input type="hidden" name="method" id="method" value="" >
</form> 

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

<!--Modal for purchasing tokens -->
  <div class="modal fade" id="BuyTokensModel" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close colse-modal" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Buy Tokens</h4>
        </div>
        <div class="modal-body">

<label>Enter Tokens</label>
<input type="number" name="numberOfToken" id="numberOfToken" class="form-control"/>

          <span class="red" id="tokenError"></span>
        <br>
        <br>

          <input type="button" class="btn button11" name="" id="buyNow" value="Buy">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default colse-modal" data-dismiss="modal">Close</button>
        </div>
      </div>
      
</div>
</div>
<!--End Modal for purchasing tokens -->

    <script type="text/javascript">
      
$(document).ready(function(){

      $("#fromDate").datetimepicker({format: 'YYYY-MM-DD'});
      $("#toDate").datetimepicker({format: 'YYYY-MM-DD'});

//Sending search request
$("#search").click(function(){

var from=$("#fromDate").val();
var to=$("#toDate").val();
if (from==""||to=="") {
alert("Please select Date.");
}
else{
window.location.href="tokens.php?search=1&from="+from+"&to="+to;
}

});

//reset the page
$("#reset").click(function(){

  window.location.href="tokens.php";
});

    $('#dataTables-example').dataTable({
        fixedHeader: {
          footer: true
        },
        "order": [],
        "aaSorting": []});

//Reset purchasing tokens modal
$("#BuyTokens").click(function(){
  $("#numberOfToken").val('');
    $("#tokenError").text('');
$("#BuyTokensModel").modal({
            backdrop: 'static',
            keyboard: false
        });


});

//sending request for purchasing tokens
$("#buyNow").click(function(){
  var numberOfToken=$("#numberOfToken").val();
  if (numberOfToken=="") 
  {
$("#tokenError").text("Please enter tokens.");
return;
  }
  else if(!(/^\d*$/.test(numberOfToken)))
{
  $("#tokenError").text("Invalid number of tokens.");
  return;

} else if(numberOfToken<10)
   {
$("#tokenError").text("You can buy tokens equal to or greater than 10.");
return;
   }

window.location.href="payment.php?tokens="+numberOfToken;

});




});






    </script>

       
</html>
<style type="text/css">
  
body
{
  background-color: white;
}
 /*.paginate_button 
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
#buyNow
{
  width: 100%;
}
/*i.fa.fa-angle-down {
 
    position: absolute;
    margin-left: 86%;
    margin-top: 10px;
}
input#fromDate,input#toDate {
    position: absolute;
    left: 0;
    top: 0;
}*/
</style>
<script type="text/javascript">
  window.onload = getPageLoadTime;

</script>