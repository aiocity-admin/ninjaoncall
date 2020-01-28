<?php
include_once('../common.php');
if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
?>
<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?=$SITE_NAME?> | Tokens</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <?php include_once('global_files.php');?>
<style type="text/css">
  .inner
  {
    min-height: 400px !important;
  }

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
              
                            
                                <h2>Tokens</h2>

   
                            </div>
                        </div>
                        <hr />
<div>
<?php
if (isset($_REQUEST['payment']))
 {
if(trim($_REQUEST['payment'])=='success')
{
  if (trim($_SESSION['tokens'])!="") {  
$tokens=$_SESSION['tokens'];
$iAdminUserId=$_SESSION['sess_iAdminUserId'];
$credit_token="INSERT INTO `barangay_tokens`(`BarangayId`, `Tokens`, `Type`, `TDate`) VALUES ('$iAdminUserId','$tokens','Credit','". date("Y-m-d H:i:s")."')";
$obj->MySQLSelect($credit_token);
$_SESSION['tokens']="";
}
?>
<div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

  <strong>Success!</strong> Payment successful.
</div>
<br>
<?php

}
else {
  ?>
<div class="alert alert-danger">
  <strong>Failure!</strong> .
</div>
<br>

  <?php



}

}


 ?>
<div class="row">
  <div class="col-lg-12">
    <div class="col-lg-2">
      <button id="BuyTokens" class="btn btn-default">Buy Tokens</button>
</div>
    <div class="col-lg-8">
</div>
  <div class="col-lg-2">
    <label>Available: </label>
    <?php
    $BarangayId=$_SESSION['sess_iAdminUserId'];
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
<h4>History of past Token purchases</h4>

   <br>
<div class="row">
  <div class="col-lg-12">
    <div class="col-lg-3">
      
   
  <input type="date" name="fromDate" id="fromDate" value="<?php if(isset($_REQUEST['from'])) { echo $_REQUEST['from'];} ?>" class="form-control">
</div>
  <div class="col-lg-3">
    <input type="date" name="fromDate" id="toDate" value="<?php if(isset($_REQUEST['to'])) { echo $_REQUEST['to'];} ?>" class="form-control">
</div>
  <div class="col-lg-3" >
  <input type="button" name="search" id="search"  class="btn button11" value="Search">
    <input type="button" name="reset" id="reset"  class="btn button11" value="Reset">

 </div>
  <div class="col-lg-2">
 </div>
  </div>

</div>
  <br>  <br>

<?php 
$subQuery="where Type='Credit' ";
if (isset($_REQUEST['search'])) 
{

$subQuery.=" and TDate between '".$_REQUEST['from']." 00:00:00' and  '".$_REQUEST['to']." 23:59:59'";
}



$token_query="SELECT `ID`, `BarangayId`, `Tokens`, `Type`, `TDate` FROM `barangay_tokens` $subQuery order by `TDate` desc";
$data=$obj->MySQLSelect($token_query);
?>
   <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="1"  id="dataTables-example" aria-describedby="dataTables-example_info" style="width: 100%;">
                                                <thead>
                                                  <tr>
                                                    <th>Tokens</th>
                                                   <th>Date</th>
                                                    <th>Type</th>
                                                   <!-- <th>Tokens</th>-->
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php 
for ($i=0; $i <count($data) ; $i++)
 { 
 ?>
 <tr>
   <td><?php echo $data[$i]['Tokens']; ?></td>
  <td><?php echo $data[$i]['TDate']; ?></td>
    <td><?php echo $data[$i]['Type']; ?></td>
      <!--  <td><?php //echo $data[$i]['Tokens']; ?></td>-->
 </tr>
                                                  
<?php }?>

                                                </tbody>
                                              </table>

</div>


                    </div>
                    <?php include('valid_msg.php'); ?>
                   
                        
                   
                    <div class="admin-notes">
                            <h4>Notes:</h4>
                           <!-- <ul>
                                    <li>
                                           Vehicles module will list all Vehicles on this page.
                                    </li>
                                    <li>
                                            Administrator can Activate / Deactivate / Delete any Vehicle. 
                                    </li>
                                
                            </ul>-->
                    </div>
                    </div>
                </div>
                <!--END PAGE CONTENT -->
            </div>
            <!--END MAIN WRAPPER -->



</form>
<?php include_once('footer.php'); ?>


<!-- Modal -->
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

          <input type="button" class="btn btn-primary" name="" id="buyNow" value="Buy">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default colse-modal" data-dismiss="modal">Close</button>
        </div>
      </div>
      
</div>
</div>


    </body>
    <!-- END BODY-->

    <script type="text/javascript">
      
$(document).ready(function(){

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


$("#reset").click(function(){

  window.location.href="tokens.php";
});

    $('#dataTables-example').dataTable({
        fixedHeader: {
          footer: true
        },
        "order": [],
        "aaSorting": []});

$("#BuyTokens").click(function(){
  $("#numberOfToken").val('');
    $("#tokenError").text('');
$("#BuyTokensModel").modal({
            backdrop: 'static',
            keyboard: false
        });


});

$("#buyNow").click(function(){
  var numberOfToken=$("#numberOfToken").val();
  if (numberOfToken=="") 
  {
$("#tokenError").text("Please enter tokens");
return;
  }

window.location.href="payment.php?tokens="+numberOfToken;

});


});

    </script>

        <script src="../assets/js/jquery-ui.min.js"></script>
    <script src="../assets/plugins/dataTables/jquery.dataTables.js"></script>
</html>
<style type="text/css">
  

  .paginate_button 
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
  }
#BuyTokens
  {

        background: #219201;
    color: #FFFFFF;
  }

  div#dataTables-example_filter {
    display: inline;
    float: right;
}
div#dataTables-example_length {
    display: inline;
}
.current
{
  margin:5px;
  background: #219201;
    color: #FFFFFF;
}
.ui-helper-hidden-accessible div
{
display: none;
}
</style>