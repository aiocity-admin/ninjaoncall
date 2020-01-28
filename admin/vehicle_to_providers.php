<?php
include_once('../common.php');
if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$script = "Vehicle";
$abc = 'admin,driver,company';
$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//$generalobj->setRole($abc, $url);
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

$admin_id=$_SESSION['sess_iAdminUserId'];

  $sql = "SELECT Company from barangay_details WHERE iAdminId='$admin_id'";
  $db_company= $obj->MySQLSelect($sql);


     $iCompanyId =  $db_company[0]['Company'];
	 // $sql = "SELECT * FROM " . $tbl_name . " where iCompanyId = '" . $iCompanyId . "' and eStatus != 'Deleted'";
     // $db_driver_vehicle = $obj->MySQLSelect($sql);

      if($APP_TYPE == 'UberX'){
        $sql = "SELECT * FROM " . $tbl_name . " dv  where iCompanyId = '" . $iCompanyId . "' and dv.eType == 'UberX' and dv.eStatus != 'Deleted' GROUP BY dv.vLicencePlate ORDER BY dv.iDriverVehicleId DESC ";
     
       $db_driver_vehicle = $obj->MySQLSelect($sql);
      }else{
         $sql = "SELECT dv.*,rd.vCountry,CONCAT(rd.vName,' ',rd.vLastName) AS driverName,m.vTitle, mk.vMake,dv.vLicencePlate,dv.eStatus  FROM " . $tbl_name . " dv  JOIN model m ON dv.iModelId=m.iModelId JOIN make mk ON  dv.iMakeId=mk.iMakeId JOIN register_driver as rd ON rd.iDriverId = dv.iDriverId where dv.iCompanyId = '" . $iCompanyId . "' and dv.eType != 'UberX' and dv.eStatus != 'Deleted' $dri_ssql  GROUP BY dv.vLicencePlate ORDER BY dv.iDriverVehicleId DESC";
       $db_driver_vehicle = $obj->MySQLSelect($sql);
      }





if(isset($iDriverVehicleId)){
  $sql = "select * from register_driver where iDriverVehicleId = '".$iDriverVehicleId. "'";
  $db_data = $obj->MySQLSelect($sql);
}




for ($i = 0; $i < count($db_driver_vehicle); $i++) {
  $sql = "select vMake from make where iMakeId = '" . $db_driver_vehicle[$i]['iMakeId'] . "' where vMake !=''";
  $name1 = $obj->MySQLSelect($sql);
  $sql = "select vTitle from model where iModelId = '" . $db_driver_vehicle[$i]['iModelId'] . "' WHERE vTitle !=''";
  $name2 = $obj->MySQLSelect($sql);
  $db_msk[$i] = $name1[0]['vMake'] . ' ' . $name2[0]['vTitle'];
}

?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=$SITE_NAME?> | Barangay Vehicles</title>
    <!-- Default Top Script and css -->
        <?php include_once('global_files.php');?>
   
    <style>
        .fileupload-preview  { line-height:150px;}
    </style>
    <!-- End: Default Top Script and css-->
</head>
<body>
     <!-- home page -->
        <?php include_once('header.php'); ?>
            <?php include_once('left_menu.php'); ?>
       <div id="content">
                <div class="inner">
                    <div id="add-hide-show-div">
           
         
                <h2 class="header-page add-car-vehicle">Barangay Vehicles</h2>
                <br>


                 <br><hr><br>   
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
           
                <div class="row success" style="display: none;">
                    
                </div>
           
                <div class="vehicles-page">
                    <div class="accordion">
					<table class="table table-striped table-bordered table-hover">
            <thead>
       <tr>
        <th>Vehicle</th>
          <th><span style="float: right;margin-right: 13%;">Providers</span></th>
         
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
                               <input style="float: right;margin-right: 20px;display: inline;" type="button" class="btn btn-default btn-providers" name="btn-providers" onClick="ListOfProviders('<?php echo $db_driver_vehicle[$i]['iDriverVehicleId'];?>','<?php echo  $iCompanyId; ?>')"  value="Assign to Providers">
</td></tr>
                        <!--end .accordion-section-->
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
            </div>

  <div class="admin-notes">
                            <h4>Notes:</h4>
                            <ul>
                                    <li>
                                           Assign to providers module will list all Vehicles on this page.
                                    </li>
                                    <li>
                                            Administrator can assign any Vehicle to the Providers. 
                                    </li>
                                
                            </ul>
                    </div>






          <div style="clear:both;"></div>
        </div>
  </div>
 
    <!-- footer part -->
<?php include_once('footer.php'); ?>
    <!-- footer part end -->
            <!-- End:contact page-->
            <div style="clear:both;"></div>
    </div>
    <!-- home page end-->
    <!-- Footer Script -->
   
    <script type="text/javascript">
 
function ListOfProviders(VehicleId,CompanyId)
{
  $("#providersList").modal({backdrop: 'static', keyboard: false});


 $.ajax({
           type: "POST",
           url: "getProvidersList.php",
           data: {VehicleId:VehicleId,CompanyId:CompanyId}, // serializes the form's elements.
           success: function(data)
           {
                           console.log(data);

               data=JSON.parse(data);

               var htmlTable="<thead><tr><th>Select</th><th>Name</th><th>Email</th></tr></thead>";
               for (var i = 0; i < data.length; i++) {

               var checked=data[i].isChecked.toString().trim()=="1"?'checked':'';

                htmlTable+="<tr><td><input "+checked+" type='checkbox' name='providers[]' value='"+data[i].iDriverId+"'></td><td>"+data[i].vName+" "+data[i].vLastName+"</td><td>"+data[i].vEmail+"</td></tr>";
               }
               htmlTable+="<tr><td><input type='hidden' name='VehicleId' value='"+VehicleId+"'><input type='hidden' name='CompanyId' value='"+CompanyId+"'></td></tr>";
               $("#providers_table").html(htmlTable);
           }
         });
}



$(document).ready(function(){

  $("#submit_providers").click(function(e){
e.preventDefault();
var form = $(".frm-provider");
    var url = "assignProviders.php";

    $.ajax({
           type: "POST",
           url: url,
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {
                            $(".success").css("display","block");

              $(".success").append('<div class="alert alert-success paddiing-10"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'+data+'</div>');

              $("#providersList").modal('hide');
               
           }
         });


  });
});
  
</script>

<style type="text/css">
.inner
{
  min-height: 400px !important;
}
 
  .modal-content {
    width: 70%;
}

.providers_table
{
  width: 100%;
}
td,th{
  padding-left: 10px;
}
</style>







<!-- Modal -->
  <div class="modal fade" id="providersList" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Providers</h4>
        </div>
        <div class="modal-body">
<form class='frm-provider'>
            <center>

  <table id="providers_table">
 </table> 
      <br>

        <input style="background: #219201;color: #FFFFFF;" class='btn btn-default' type='submit' id='submit_providers' value="Submit">
      
</center>
        </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
</div>
</div>


</body>
</html>

