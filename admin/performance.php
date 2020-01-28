<?php
include_once('../common.php');

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$script="performance";

$to= isset($_REQUEST['to'])? $_REQUEST['to']:date('Y-m-d H:i:s');
$from=isset($_REQUEST['from'])? $_REQUEST['from']:date('Y-m-d')." 00:00:00";



$sql="SELECT vValue FROM configurations WHERE vName='PERFORMANCE_THRESHOLD'";

$db_con=$obj->MySQLSelect($sql);

$threshold=$db_con[0]['vValue'];


$q="SELECT avg(`TotalTime`) TotalTime  FROM `performance` WHERE  `TimeFrom`>='$from' and TimeTo<='$to' and eType='SIGNIN'";
$result=$obj->MySQLSelect($q);
$TotalTime_SINGIN=$result[0]['TotalTime'];
If(trim($TotalTime_SINGIN)=="")
$TotalTime_SINGIN=0;

$q="SELECT avg(`TotalTime`) TotalTime FROM `performance` WHERE  `TimeFrom`>='$from' and TimeTo<='$to' AND UserType='COMPANY'";
$result=$obj->MySQLSelect($q);
$TotalTime_COMPANY_REPORTS=$result[0]['TotalTime'];
If(trim($TotalTime_COMPANY_REPORTS)=="")
$TotalTime_COMPANY_REPORTS=0;


$q="SELECT avg(`TotalTime`) TotalTime FROM `performance` WHERE  `TimeFrom`>='$from' and TimeTo<='$to'  AND UserType='SUPER_ADMIN'";

$result=$obj->MySQLSelect($q);
$TotalTime_ADMIN_REPORTS=$result[0]['TotalTime'];
If(trim($TotalTime_ADMIN_REPORTS)=="")
$TotalTime_ADMIN_REPORTS=0;
?>


<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?=$SITE_NAME?> | Performance</title>
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
                    <h2 style="margin-left: 10px;">Performance</h2>
                    <br>           <br>
                    <hr>
                   
                  </div>
 <!-- Searching  -->
<div class="row">
    <div class="col-lg-3">
      
                   <!--<div class='input-group date' id='fromDate'>-->

  <input type="text" placeholder="YYYY-MM-DD" name="fromDate" id="fromDate" value="<?=$from;?>" class="form-control">
<!--  <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>

                  </div> -->
</div>
  <div class="col-lg-3">
    <input type="text"  placeholder="YYYY-MM-DD" name="fromDate" id="toDate" value="<?=$to;?>"  class="form-control">
</div>



  <div class="col-lg-3" >
  <input type="button" name="search" id="search"  class="btn button11" value="Search">
    <input type="button" name="reset" id="reset"  class="btn button11" value="Reset">

 </div>

<div class="col-lg-3">
                   <label style="float: right;"><b>Threshold : </b><span> <?=$threshold?> Sec</span> </label>
</div>

</div>
  <!-- End Searching  -->
                  <!-- Details of provider wallet-->
                  <div class="row">
                    <div class="col-lg-12" style="overflow-x: auto;">
                      <?
//$date = new DateTime($from);
//$date->modify('-7 day');
//$tomorrowDATE = $date->format('Y-m-d H:i:s');


//$q="delete FROM `performance` WHERE  `TimeFrom`<='$tomorrowDATE'";
//$obj->sql_query($q);
                      ?> 
<br><br>
                      <div class="trips-table trips-table-driver trips-table-driver-res"> 
              <div class="trips-table-inner">
           


<div class="row">
  <div class="col-lg-4">
              <div class="panel panel-primary bg-gray-light">
                
                <div class="panel-heading">
                  <div class="panel-title-box">
                    <i class="fa fa-bar-chart"></i> Login Reports               </div>                                  
                </div>
                
                
                
                <div class="panel-body padding-0">
                  <div class="col-lg-12" style="height:200px;">
                    <canvas  class="chart-holder" id="performance-login" >
                      
                    </canvas>
                  </div>
                 <div>
                   <b>Current : </b><span> <?=round($TotalTime_SINGIN,2);?> Sec</span>
                                    <!--   <br>
                   <b>Threshold : </b><span> <?=$threshold?> Sec</span> -->
                 </div>
              </div> 
            </div>

</div>



  <div class="col-lg-4">
              <div class="panel panel-primary bg-gray-light">
                
                <div class="panel-heading">
                  <div class="panel-title-box">
                    <i class="fa fa-bar-chart"></i> Company Reports  </div>                                  
                </div>
                
                
                
                <div class="panel-body padding-0">
                  <div class="col-lg-12" style="height:200px;">
                    <canvas  class="chart-holder" id="performance-company-reports" >
                      
                    </canvas>
                  </div>
                  <div>
                   <b>Current : </b><span> <?=round($TotalTime_COMPANY_REPORTS,2);?> Sec</span>
                                <!--       <br>
                   <b>Threshold : </b><span> <?=$threshold?> Sec</span> -->
                 </div>
              </div> 
            </div>

</div>
  <div class="col-lg-4">
              <div class="panel panel-primary bg-gray-light">
                
                <div class="panel-heading">
                  <div class="panel-title-box">
                    <i class="fa fa-bar-chart"></i> Admin Reports  </div>                                  
                </div>
                
                
                
                <div class="panel-body padding-0">
                  <div class="col-lg-12" style="height:200px;">
                    <canvas  class="chart-holder" id="performance-admin-reports" >
                      
                    </canvas>
                  </div>
                  <div>
                   <b>Current : </b><span> <?=round($TotalTime_ADMIN_REPORTS,2);?> Sec</span>
                  <!--  <br>
                   <b>Threshold : </b><span> <?=$threshold?> Sec</span> -->
                 </div>
              </div> 
            </div>

</div>


                                            </div>
                                          </div>

<div class="admin-notes">
                        <h4>Notes:</h4>
                        <ul>
                            <li>Admin can see the average page load time on this page.</li>
                          
                        </ul>
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
<script type="text/javascript" src="../gauge/gauge.min.js"></script>
    </body>
  
  

 <script type="text/javascript">

   $(document).ready(function(){
    var today = new Date();
var todayEndOfDay = new Date().setHours(23,59,59,999);
      $("#fromDate").datetimepicker({  
                sideBySide: true,     
format:"YYYY-MM-DD HH:mm:ss"
});
      $("#toDate").datetimepicker({  
                sideBySide: true,      
format:"YYYY-MM-DD HH:mm:ss"
 
      });


//Sending request for searching
$("#search").click(function(){

var from=$("#fromDate").val();
var to=$("#toDate").val();

if (from.trim()=="" || to.trim()=="") {
  alert("Please select date.");
  return;
}

let  diff = Math.floor(( (new Date(to)).getTime() -  (new Date(from)).getTime()) / 86400000); // ms per day
if(diff < 0){
    alert('To date is less than From date');
    return false;
}



window.location.href="performance.php?search=1&from="+from+"&to="+to;


});




$("#reset").click(function(){

  window.location.href="performance.php";
});

//Sign In performance
var target = document.getElementById('performance-login'); // your canvas element

setGauge(<?=$TotalTime_SINGIN?>,<?=$threshold?>,0,30,target);

//Company report performance

 target = document.getElementById('performance-company-reports'); // your canvas element
setGauge(<?=$TotalTime_COMPANY_REPORTS?>,<?=$threshold?>,0,30,target);

//admin report performance

 target = document.getElementById('performance-admin-reports'); // your canvas element
setGauge(<?=$TotalTime_ADMIN_REPORTS?>,<?=$threshold?>,0,30,target);



   });



function setGauge(value,maxValue,minValue,animationSpeed,target)
{

var opts = {
  angle: 0.15, // The span of the gauge arc
  lineWidth: 0.44, // The line thickness
  radiusScale: 1, // Relative radius
  pointer: {
    length: 0.6, // // Relative to gauge radius
    strokeWidth: 0.035, // The thickness
    color: '#000000' // Fill color
  },
  limitMax: false,     // If false, max value increases automatically if value > maxValue
  limitMin: false,     // If true, the min value of the gauge will be fixed
  colorStart: '#6FADCF',   // Colors
  colorStop: '#8FC0DA',    // just experiment with them
  strokeColor: '#E0E0E0',  // to see which ones work best for you
  generateGradient: true,
  highDpiSupport: true,     // High resolution support
staticLabels: {
  font: "10px sans-serif",  // Specifies font
  labels: [0,maxValue/2,maxValue],  // Print labels at these values
  color: "#000000",  // Optional: Label text color
  fractionDigits: 0  // Optional: Numerical precision. 0=round off.
}        
};
var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
gauge.maxValue = maxValue; // set max gauge value
gauge.setMinValue(minValue);  // Prefer setter over gauge.minValue = 0
gauge.animationSpeed = animationSpeed; // set animation speed (32 is default value)
gauge.set(value); // set actual value
}

 </script>
</html>
