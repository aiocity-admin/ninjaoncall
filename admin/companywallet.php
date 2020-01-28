<?php
include_once('../common.php');
?><script type="text/javascript">
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
           data: {"loadtime":seconds,"beforeload":date1,"afterload":date2,"UserType":"SUPER_ADMIN","eType":"COMPANY_WALLET_REPORT"}, 
           success: function(data)
           {
               
           }
         });

}
</script><?php
if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();



  //Start Sorting

  $sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 5;

  $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 1;

  $ord = ' ORDER BY TDate DESC';

  if($sortby == 1){

    if($order == 0)

    $ord = " ORDER BY ref_No ASC";

    else

    $ord = " ORDER BY ref_No DESC";

  }

  

  if($sortby == 2){

    if($order == 0)

    $ord = " ORDER BY TDate ASC";

    else

    $ord = " ORDER BY TDate DESC";

  }

  

  if($sortby == 3){

    if($order == 0)

    $ord = " ORDER BY Tokens ASC";

    else

    $ord = " ORDER BY Tokens DESC";

  }

  if($sortby == 4){

    if($order == 0)

    $ord = " ORDER BY Type ASC";

    else

    $ord = " ORDER BY Type DESC";

  }
  if($sortby == 5){

    if($order == 0)

    $ord = " ORDER BY TDate ASC";

    else

    $ord = " ORDER BY TDate DESC";

  }

  if($sortby == 6){

    if($order == 0)

    $ord = " ORDER BY t3.`vName` ASC";

    else

    $ord = " ORDER BY t3.`vName` DESC";

  }
    if($sortby == 7){

    if($order == 0)

    $ord = " ORDER BY payment ASC";

    else

    $ord = " ORDER BY payment DESC";

  }
  if($sortby == 8){

    if($order == 0)

    $ord = " ORDER BY `payment_status` ASC";

    else

    $ord = " ORDER BY `payment_status` DESC";

  }
  if($sortby == 9){

    if($order == 0)

    $ord = " ORDER BY `commission` ASC";

    else

    $ord = " ORDER BY `commission` DESC";

  }

  //End Sorting


?>

<?php
 $subQuery="where 1=1 ";
 if (isset($_REQUEST['search'])) {

 if (trim($_REQUEST['from'])!=''&&trim($_REQUEST['to'])!='') {
  # code...
$subQuery.=" and TDate between '".$_REQUEST['from']." 00:00:00' and  '".$_REQUEST['to']." 23:59:59'";
}
  if (trim($_REQUEST['providers'])!='null')
{

 $Provider= explode(',', $_REQUEST['providers']);
  $subQuery_providers='(';
  for ($i=0; $i <count($Provider) ; $i++) { 
  $subQuery_providers.=" DriverId='$Provider[$i]' or";
  }
   $subQuery_providers=rtrim($subQuery_providers,'or');
 $subQuery_providers.=")";
$subQuery.=" and $subQuery_providers";

}

 if (trim($_REQUEST['company'])!='null')
{

 $company= explode(',', $_REQUEST['company']);
  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" BarangayId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";

}



}



  //Pagination Start

  $per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page


  $sql = "SELECT  count(t2.vCompany) as Total FROM `barangay_tokens` t1 join company t2 on t1.BarangayId=t2.iCompanyId left outer join register_driver t3 on t1.DriverId =t3.iDriverId $subQuery  $ord ";


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

  $tpages=$total_pages;

  if ($page <= 0)

    $page = 1;

  //Pagination End

  

 // $sql = "SELECT  t2.vCompany, `Tokens`, `Type`, `TDate`, CONCAT(t3.`vName`,' ',t3.`vLastName`) as Name, `ref_No`, `payment`, `payment_status`, `commission` FROM `barangay_tokens` t1 join company t2 on t1.BarangayId=t2.iCompanyId left outer join register_driver t3 on t1.DriverId =t3.iDriverId $subQuery   $ord LIMIT $start, $per_page";

  //
 $sql_company_wallet="SELECT  t2.vCompany, `Tokens`, `Type`, `TDate`, CONCAT(t3.`vName`,' ',t3.`vLastName`) as Name, `ref_No`,CAST(`payment`  AS DECIMAL(12,2)) as payment, `payment_status`, `commission` FROM `barangay_tokens` t1 join company t2 on t1.BarangayId=t2.iCompanyId left outer join register_driver t3 on t1.DriverId =t3.iDriverId $subQuery $ord   LIMIT $start, $per_page";

  $data_drv = $obj->MySQLSelect($sql_company_wallet);

  $endRecord = count($data_drv);

  $var_filter = "";

  foreach ($_REQUEST as $key=>$val) {

    if($key != "tpages" && $key != 'page')

    $var_filter.= "&$key=".stripslashes($val);

  }

  

  $reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages.$var_filter;




 //$sql_company_wallet="SELECT  t2.vCompany, `Tokens`, `Type`, `TDate`, CONCAT(t3.`vName`,' ',t3.`vLastName`) as Name, `ref_No`, `payment`, `payment_status`, `commission` FROM `barangay_tokens` t1 join company t2 on t1.BarangayId=t2.iCompanyId left outer join register_driver t3 on t1.DriverId =t3.iDriverId $subQuery $ord   LIMIT $start, $per_page";
?>


<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?=$SITE_NAME?> | Company Wallet</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <?php include_once('global_files.php');?>
 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
    <style type="text/css">
      .inner
      {
     min-height: 400px !important;     
      }

    </style>
    </head>
    <!-- END  HEAD-->
    <!-- BEGIN BODY-->
    <body class="padTop53">
      <!-- Main LOading -->
      <!-- MAIN WRAPPER -->
      <div id="wrap">
          <?php include_once('header.php'); ?>
          <?php include_once('left_menu.php'); ?>
          <!--PAGE CONTENT -->
            <div id="content">
                <div class="inner">
                  <div class="row">
                    <h2>Company wallet</h2>
                    <br>           <br>
                    <hr>
                   
                  </div>
 <!-- Searching  -->
<div class="row">
    <div class="col-lg-2">
      
                   <!--<div class='input-group date' id='fromDate'>-->

  <input type="text" placeholder="YYYY-MM-DD" name="fromDate" id="fromDate" value="<?php if(isset($_REQUEST['from'])) { echo $_REQUEST['from'];} ?>" class="form-control">
<!--  <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>

                  </div> -->
</div>
  <div class="col-lg-2">
    <input type="text"  placeholder="YYYY-MM-DD" name="fromDate" id="toDate" value="<?php if(isset($_REQUEST['to'])) { echo $_REQUEST['to'];} ?>" class="form-control">
</div>


  <div class="col-lg-2" >
    <?php 
    $query="SELECT vCompany,iCompanyId FROM `company`"; ?>
  <select id="company" name="company" class="form-control multiselect" multiple="multiple" size="10">
    <?php
   $list_0f_company=$obj->MySQLSelect($query);
foreach ($list_0f_company as $key => $company) {

?>
<option value="<?php echo  $company['iCompanyId']; ?>" <?php
$Scompany=explode(',', $_REQUEST['company']);
  for ($i=0; $i <count($Scompany) ; $i++) { 


 if (trim($Scompany[$i])==$company['iCompanyId']) {
 echo 'selected';
}
} ?> ><?php echo  $company['vCompany']; ?></option>
<?php
}
     ?>
  </select>


 </div>

   <div class="col-lg-2" >
    <?php 
    $query="SELECT vName,vLastName,iDriverId,vEmail FROM `register_driver`"; ?>
  <select id="providers" name="providers" class="form-control multiselect" multiple="multiple" size="10">
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

 $data=$obj->MySQLSelect($sql_company_wallet);
     ?>
  </select>


 </div>
  <div class="col-lg-4" >
  <input type="button" name="search" id="search"  class="btn button11" value="Search">
    <input type="button" name="reset" id="reset"  class="btn button11" value="Reset">
    <?php  if($total_results>0)
         { ?>
    <input type="button" name="export" id="export" style="float: right;" value="Export" class="btn btn-default"/>
  <?php } ?>

 </div>



</div>
  <!-- End Searching  -->
                  <!-- Details of provider wallet-->
                  <div class="row">
                    <div class="col-lg-12" style="overflow-x: auto;">
<br><br>
                      <div class="trips-table trips-table-driver trips-table-driver-res"> 
              <div class="trips-table-inner">
                  <form class="_list_form" id="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
   <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="1"  id="dataTables-example" aria-describedby="dataTables-example_info">
                                                <thead>
                                                  <tr>
                                                    <th>  <a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)">Refference number <?php if ($sortby == 1) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                                <!--     <th>Company Name </th> -->
                                                 <th>  <a href="javascript:void(0);" onClick="Redirect(2,<?php if($sortby == '2'){ echo $order; }else { ?>0<?php } ?>)">Company Name <?php if ($sortby == 2) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                    </th>

                                                   <th> 
 <a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)">Tokens <?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                   </th>
                                                <th>
 <a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)">Type <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                </th>
                                                   <th>
 <a href="javascript:void(0);" onClick="Redirect(5,<?php if($sortby == '5'){ echo $order; }else { ?>0<?php } ?>)">Date/Time<?php if ($sortby == 5) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                   </th>
                                                      <th>
 <a href="javascript:void(0);" onClick="Redirect(6,<?php if($sortby == '6'){ echo $order; }else { ?>0<?php } ?>)">Provider<?php if ($sortby == 6) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                      </th>
                                                         <th>
 <a href="javascript:void(0);" onClick="Redirect(7,<?php if($sortby == '7'){ echo $order; }else { ?>0<?php } ?>)">Payment<?php if ($sortby == 7) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                         </th>
                                                            <th>  <a href="javascript:void(0);" onClick="Redirect(8,<?php if($sortby == '8'){ echo $order; }else { ?>0<?php } ?>)">Status<?php if ($sortby == 8) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a> </th>
                                                               <th>  <a href="javascript:void(0);" onClick="Redirect(9,<?php if($sortby == '9'){ echo $order; }else { ?>0<?php } ?>)">Service fee<?php if ($sortby == 9) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php

                                                  if($total_results>0)
                                                  {

                                                  

foreach ($data as $key => $value) {
  

                                                  ?>
                                                  <tr>
                                                    <td>
                                              <? if(trim($value['ref_No'])==""&&trim($value['Type'])=="Debit") 
                                                 echo "Add to provider";
                                                   if(trim($value['ref_No'])==""&&trim($value['Type'])=="Credit") 
                                                 echo "Adjust to provider";
                                                     else 
                                                 echo $value['ref_No'];?></td>
                                                  <td><?=$value['vCompany'];?></td>
                                                  <td><?=$value['Tokens'];?></td>
                                                  <td><?=$value['Type'];?></td>
                                                  <td><?=$value['TDate'];?></td>
                                                  <td><?=$value['Name'];?></td>
                                                  <td><?=$value['payment'];?></td>
                                                  <td><?=$value['payment_status'];?></td>
                                                  <td><?=$value['commission'];?></td>
                                                  </tr>
<?php }}else { ?>
<tr><td colspan="10">No Details Found.</td></tr>
<?php } ?>
                                                </tbody>
                                              </table>

                                            </form>
                <?php include('pagination_n.php'); ?>

                                            </div>
                                          </div>

                      
                    </div>
                    
                  </div>
                                        <!-- Details of provider wallet-->

                    </div>
                </div>
                <!--END PAGE CONTENT -->
            </div>
            <!--END MAIN WRAPPER -->
<form name="pageForm" id="pageForm"  method="post" >

    <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">

    <input type="hidden" name="tpages" id="tpages" value="<?php echo $tpages; ?>">

   

    <input type="hidden" name="sortby" id="sortby" value="<?php echo $sortby; ?>" >

    <input type="hidden" name="order" id="order" value="<?php echo $order; ?>" >


  </form>
                    
    <?php
    include_once('footer.php');   

    ?>
<!-- <script src="../assets/js/jquery-ui.min.js"></script>
<script src="../assets/plugins/dataTables/jquery.dataTables.js"></script> -->

  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $tconfig["tsite_url_main_admin"]?>css/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/bootstrap-datetimepicker.min.js"></script>


    </body>
  
   
 <script type="text/javascript">
   $(document).ready(function(){

      $("#fromDate").datetimepicker({format: 'YYYY-MM-DD'});
      $("#toDate").datetimepicker({format: 'YYYY-MM-DD'});


//Sending request for searching
$("#search").click(function(){

var from=$("#fromDate").val();
var to=$("#toDate").val();
var provider=$("#providers").val();
var company=$("#company").val();


window.location.href="companywallet.php?search=1&from="+from+"&to="+to+"&providers="+provider+"&company="+company;


});

//Sending request for export

$("#export").click(function(){

var from=$("#fromDate").val();
var to=$("#toDate").val();
var provider=$("#providers").val();
var company=$("#company").val();


window.location.href="export_company_wallet.php?search=1&from="+from+"&to="+to+"&providers="+provider+"&company="+company;


});

 /*   $('#dataTables-example').dataTable({
        fixedHeader: {
          footer: true
        },
        "order": [],
        "aaSorting": []});
*/
//reset page
$("#reset").click(function(){

  window.location.href="companywallet.php";
});

     $('#providers').multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"150px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Providers',
    maxHeight:300
      });

$("#company").multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"150px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Company',
    maxHeight:300
  });



   });


 </script>
     <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/removeselectall.js"></script>

</html>
<script type="text/javascript">
  window.onload = getPageLoadTime;

</script>