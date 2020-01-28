<?php
include_once('common.php');
$generalobj->check_member_login();

require_once(TPATH_CLASS . "/Imagecrop.class.php");
$thumb = new thumbnail();

$script="Vehicle";
$abc = 'admin,driver,company';
$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$generalobj->setRole($abc, $url);
$tbl_name = 'driver_vehicle';

$success = isset($_GET['success']) ? $_GET['success'] :'';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$driverid = isset($_GET['driverid']) ? $_GET['driverid'] : '';
$error = isset($_GET['success']) && $_GET['success']==0 ? 1 : '';
$var_msg = isset($_REQUEST['var_msg']) ? $_REQUEST['var_msg'] : '';
 $iCompanyId=0;


$dri_ssql = "";
if (SITE_TYPE == 'Demo') {
    $dri_ssql = " And rd.tRegistrationDate > '" . WEEK_DATE . "'";
}

//list of vehicles
if ($_SESSION['sess_user'] == 'company') {
     $iCompanyId = $_SESSION['sess_iUserId'];

      if($APP_TYPE == 'UberX'){
        $sql = "SELECT * FROM " . $tbl_name . " dv  where iCompanyId = '" . $iCompanyId . "' and dv.eType == 'UberX' and dv.eStatus != 'Deleted' GROUP BY dv.vLicencePlate ORDER BY dv.iDriverVehicleId DESC ";
     
       $db_driver_vehicle = $obj->MySQLSelect($sql);
      }else{
         $sql = "SELECT dv.*,rd.vCountry,CONCAT(rd.vName,' ',rd.vLastName) AS driverName,m.vTitle, mk.vMake,dv.vLicencePlate,dv.eStatus  FROM " . $tbl_name . " dv  JOIN model m ON  ( dv.iModelId=m.iModelId or dv.iModelId=0)  JOIN make mk ON  dv.iMakeId=mk.iMakeId JOIN register_driver as rd ON rd.iDriverId = dv.iDriverId where dv.iCompanyId = '" . $iCompanyId . "' and dv.eType != 'UberX' and dv.eStatus != 'Deleted' $dri_ssql  GROUP BY dv.vLicencePlate ORDER BY dv.iDriverVehicleId DESC";
       $db_driver_vehicle = $obj->MySQLSelect($sql);
      }

}
//end list of vehicles
?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=$SITE_NAME?> | <?=$langage_lbl['LBL_ASSIGN_TO_PROVIDER']; ?></title>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
    <link rel="stylesheet" href="assets/css/bootstrap-fileupload.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/vehicles.css">
    <style>
        .fileupload-preview  { line-height:150px;}
        .dataTables_length select{ border:1px solid #dcdcdc;padding:5px; }
        #providers_table tbody tr td{word-break : break-word; }
    </style>
    <!-- End: Default Top Script and css-->
</head>
<body>
     <!-- home page -->
    <div id="main-uber-page">
     <!-- Top Menu -->
    <!-- Left Menu -->
    <?php include_once("top/left_menu.php");?>
    <!-- End: Left Menu-->
        <?php include_once("top/header_topbar.php");?>
        <!-- End: Top Menu-->
        <!-- contact page-->
        <div class="page-contant">
            <div class="page-contant-inner">
          
                <h2 class="header-page add-car-vehicle"><?=$langage_lbl['LBL_ASSIGN_TO_PROVIDER']; ?>
                
                </h2>
                
                
              <?php 
                  if(SITE_TYPE =='Demo'){
              ?>
              <div class="demo-warning">
                <p><?=$langage_lbl['LBL_SINCE_THIS']; ?></p>
                </div>
              <?php
                }
              ?>
              
          <!-- driver vehicles page -->

            <div class="driver-vehicles-page-new">
               <!-- alert msg -->
            <?php
                if ($error) {
            ?>
                <div class="row">
                    <div class="col-sm-12 alert alert-danger">
                         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?= $var_msg ?>
                    </div>
                </div>
            <?php 
                }
                if ($success==1) {
            ?>
                <div class="row">
                    <div class="alert alert-success paddiing-10">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?= $var_msg ?>
                    </div>
                </div>
            <?php
                }else if($success==2) {
            ?>
                <div class="row">
                    <div class="alert alert-danger paddiing-10">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?=$langage_lbl['LBL_VEHICLE_EDIT_DELETE_RECORD']; ?>
                    </div>
                </div>
            <?
                }
            ?>
             <!--end alert msg -->
                <div class="vehicles-page">
                    <div class="accordion">
                      <!--List of vehicles -->

<div class="trips-table trips-table-driver trips-table-driver-res"> 
              <div class="trips-table-inner">

<table class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
       <tr>
        <th>Vehicle</th>
        <th>Vehicle Type</th>
          <th>Providers</th>
         
        </tr>
</thead>
        
                        <?php
                            if (count($db_driver_vehicle) > 0) {
                                for ($i = 0; $i < count($db_driver_vehicle); $i++) {
                        ?>
                         <tr>
        <td>
                      
                                <input type="hidden" name="iVehicleId" class="iVehicleId" value = "<?php echo $db_driver_vehicle[$i]['iDriverVehicleId']; ?>"/> 

                                   <?php if($APP_TYPE == 'UberX'){
                                      $displayname =  $db_driver_vehicle[$i]['vLicencePlate'];
                                    } else {
                                      $displayname =$db_driver_vehicle[$i]['vMake']."   ".$db_driver_vehicle[$i]['vTitle']."  ".$db_driver_vehicle[$i]['vLicencePlate']."  "  ;
                                      }?> 
                                    <h5 style="display: inline;"><?php echo $displayname  ;?></h5>
                            </td>
                            <td>
                                <h5 style="display: inline;"><?php echo $db_driver_vehicle[$i]['eType'];?></h5>
                            </td>
                            <td>
                                 <input style="float: right;margin-right: 20px;" type="button" class="btn btn-default btn-providers" name="btn-providers" onClick="ListOfProviders('<?php echo $db_driver_vehicle[$i]['iDriverVehicleId'];?>','<?php echo  $iCompanyId; ?>')"  value="Providers">
</td></tr>
                      
                        <?php
                                }
                            }
                            else{
?>
                  <tr>
        <td>No Records Found.</td><td></td></tr>
<?php

                            }
                        ?>
                          </table>

</div>
</div>
                      <!--End List of vehicles -->



					
                      
                    </div>

                </div>
            </div>
          <div style="clear:both;"></div>
        </div>
  </div>
 
    <!-- footer part -->
    <?php include_once('footer/footer_home.php');?>
    <!-- footer part end -->
            <!-- End:contact page-->
            <div style="clear:both;"></div>
    </div>
    <!-- home page end-->
    <!-- Footer Script -->
    <?php include_once('top/footer_script.php');?>
    <script type="text/javascript" src="assets/js/accordion.js"></script>
    <script src="assets/plugins/jasny/js/bootstrap-fileupload.js"></script>
	<link rel="stylesheet" type="text/css" media="screen" href="admin/css/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
	<script type="text/javascript" src="admin/js/moment.min.js"></script>
	<script type="text/javascript" src="admin/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">

 //Geting List of providers
function ListOfProviders(VehicleId,CompanyId)
{
  $("#providersList").modal({backdrop: 'static', keyboard: false});

$("#errorChk").text("");


 $.ajax({
           type: "POST",
           url: "getProvidersList.php",
           data: {VehicleId:VehicleId,CompanyId:CompanyId}, 
           success: function(data)
           {
                           //console.log(data);

               data=JSON.parse(data);

               var htmlTable="<thead><tr><th>Select</th><th>Name</th><th>Email</th></tr></thead>";
               for (var i = 0; i < data.length; i++) {

               var checked=data[i].isChecked.toString().trim()=="1"?'checked':'';

                htmlTable+="<tr><td><input "+checked+" type='checkbox' name='providers[]' class='chkProviders' value='"+data[i].iDriverId+"'></td><td>"+data[i].vName+" "+data[i].vLastName+"</td><td>"+data[i].vEmail+"</td></tr>";
               }
               htmlTable+="<tr><td><input type='hidden' name='VehicleId' value='"+VehicleId+"'><input type='hidden' name='CompanyId' value='"+CompanyId+"'></td></tr>";
               $("#providers_table").html(htmlTable);
var chk=true;
               $(".chkProviders").click(function(){

chk=false;
               });

$("#providers_table td").click(function()
{
  
if(chk)
{
  var obj=$(this).parent("tr").first("td").find(".chkProviders");

if (obj.is(":checked")) 
{
  obj.removeAttr("checked");
   obj.prop("checked", false);

}
 else
 {
 obj.attr("checked","checked");
 obj.prop("checked", true);
}
}
chk=true;
});


           }
         });
}
//End Geting List of providers


$(document).ready(function(){

$('#dataTables-example').dataTable({
    'aoColumnDefs': [{
        'bSortable': false,
        'aTargets': [-1] /* 1st one, start by the right */
    }]
    
    //"aaSorting": []  
    });

//Request for assign vehicle to providers
  $("#submit_providers").click(function(e){
e.preventDefault();
var form = $(".frm-provider");
    var url = "assignProviders.php";
var valid=false;
$(".chkProviders").each(function()
  {

if ($(this).is(":checked")) {
  valid=true;
}
  });
if (valid) {
    $.ajax({
           type: "POST",
           url: url,
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {
               //alert(data); 
               //location.reload();
               window.location.href="assign-to-providers?success=1&var_msg="+data;
           }
         });

}
else 
{
$("#errorChk").text("*Please select atleast one provider.");
}

  });
//End Request for assign vehicle to providers

});
  
</script>








<!-- Modal for provider list -->
  <div class="modal fade" id="providersList" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Providers</h4>
        </div>
        <div class="modal-body" >
<form class='frm-provider'>
            <center>
<div style="max-height: 350px;overflow: auto;">
  <table id="providers_table" >
 </table> 
 </div>
 <span class="error" id="errorChk"></span>
      <!--<br>-->

        <input style="background: #219201;color: #FFFFFF;margin-top: 20px;" class='btn btn-default' type='submit' id='submit_providers' value="Submit">
      
</center>
        </form>
        </div>

        <div class="modal-footer" style="margin-top: 0px;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
</div>
</div>

<!--end Modal for provider list -->

</body>
<!-- end body -->
</html>
<style type="text/css">
  #providers_table th,#providers_table td{
    cursor: pointer;
  }

 #providers_table th{
  background-color: #a2e5e1;
  height: 30px;
  padding: 5px;
}
 #providers_table{
  width: 100%;
 }
 input.chkProviders {
    margin: 5px;
}
 #providers_table tr:hover{
    background-color: #4280f3;

  }
  input.btn.btn-default.btn-providers {
    background-color: #219201;
    color: white;
}
</style>

<script src="assets/js/jquery-ui.min.js"></script>
<script src="assets/plugins/dataTables/jquery.dataTables.js"></script>