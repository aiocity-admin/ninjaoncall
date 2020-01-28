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
              
                            
                                <h2>History of Tokens sales to the Provider</h2>

   
                            </div>
                        </div>
                        <hr />
<div>

<div class="row">
  <div class="col-lg-12">
    <div class="col-lg-2">
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
  <br>  <br>
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
  <div class="col-lg-3">
    <input type="button" name="export" id="export" style="float: right;" value="Export" class="button11"/>
 </div>
  </div>

</div>
  <br>  <br>

<?php 
$subQuery="where a.Type='Debit' ";
if (isset($_REQUEST['search'])) 
{

$subQuery.=" and a.TDate between '".$_REQUEST['from']." 00:00:00' and  '".$_REQUEST['to']." 23:59:59'";
}



$token_query="SELECT a.`ID`, a.`BarangayId`, a.`Tokens`, a.`Type`, `TDate`,b.vName,b.vLastName FROM `barangay_tokens` a join register_driver b on a.DriverId=b.iDriverId $subQuery order by `TDate` desc";
$data=$obj->MySQLSelect($token_query);
?>
   <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="1"  id="dataTables-example" aria-describedby="dataTables-example_info" style="width: 100%;">
                                                <thead>
                                                  <tr>
                                                    <th>Tokens</th>
                                                   <th>Date</th>
                                                    <th>Type</th>
                                                     <th>Driver</th>
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
        <td><?php echo $data[$i]['vName']." ".$data[$i]['vLastName']; ?></td>

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

<script type="text/javascript">
  function exportToExcel(){
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
}
$(document).ready(function(){


$("#export").click(function(){
var from=$("#fromDate").val();
var to=$("#toDate").val();
//exportToExcel();
window.location.href="export_History_of_Tokens _sales.php?from="+from+"&to="+to;

});

$("#search").click(function(){

var from=$("#fromDate").val();
var to=$("#toDate").val();
if (from==""||to=="") {
alert("Please select Date.");
}
else{
window.location.href="token_sales_to_the_provider.php?search=1&from="+from+"&to="+to;
}

});


$("#reset").click(function(){

  window.location.href="token_sales_to_the_provider.php";
});

    $('#dataTables-example').dataTable({
        fixedHeader: {
          footer: true
        },
        "order": [],
        "aaSorting": []});

  });

</script>


    </body>
    <!-- END BODY-->

 

   <!--     <script src="../assets/js/jquery-ui.min.js"></script>
    <script src="../assets/plugins/dataTables/jquery.dataTables.js"></script>-->

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>








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