<?php
include_once('../common.php');

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$script = 'notification settings';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$event = isset($_REQUEST['event']) ? $_REQUEST['event'] : '';
$actionBy = isset($_REQUEST['actionBy']) ? $_REQUEST['actionBy'] : '';
$NotifyCompany = isset($_REQUEST['NotifyCompany']) ? $_REQUEST['NotifyCompany'] : 'off';
$NotifyProvider = isset($_REQUEST['NotifyProvider']) ? $_REQUEST['NotifyProvider'] : 'off';
$NotifyCustomer = isset($_REQUEST['NotifyCustomer']) ? $_REQUEST['NotifyCustomer'] : 'off';
$NotifyAdministrator = isset($_REQUEST['NotifyAdministrator']) ? $_REQUEST['NotifyAdministrator'] : 'off';
$AdditionalEmails = isset($_REQUEST['AdditionalEmails']) ? $_REQUEST['AdditionalEmails'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$tbl_name="nortification_settings";
if (isset($_REQUEST['submit'])) {
  # code...
$AdditionalEmails= rtrim($AdditionalEmails,",");

        $q = "INSERT INTO ";
        $where = '';
        if ($id != '') {

            $q = "UPDATE ";
            $where = " WHERE `ID` = '" . $id . "'";
        }
    $query = $q . " `" . $tbl_name . "` SET

    `Event`='".$event."',
    `ActionBy`='".$actionBy."',
    `NotifyCompany`='".$NotifyCompany."',
    `NotifyProvider`='".$NotifyProvider."',
    `NotifyCustomer`='".$NotifyCustomer."',
    `NotifyAdministrator`='".$NotifyAdministrator."',
    `AdditionalEmail`='".$AdditionalEmails."'
  "
        . $where;

        $obj->sql_query($query);
        $id = ($id != '') ? $id : $obj->GetInsertId();

 
  $_SESSION['var_msg']="Record has been Saved.";
  header("Location:nortification_settings_list.php");        
}
else
{
    $_SESSION['var_msg']="";
}
if ($action == 'Edit') {

    $sql = "SELECT * FROM " . $tbl_name . " WHERE ID = '" . $id . "'";
    $db_data = $obj->MySQLSelect($sql);

    $vLabel = $id;
    if (count($db_data) > 0)

     {
 $event= $db_data[0]['Event'];
  $actionBy=   $db_data[0]['ActionBy'];
   $NotifyCompany= $db_data[0]['NotifyCompany'];
    $NotifyProvider= $db_data[0]['NotifyProvider'];
    $NotifyCustomer= $db_data[0]['NotifyCustomer'];
    $NotifyAdministrator= $db_data[0]['NotifyAdministrator'];
   $AdditionalEmails= $db_data[0]['AdditionalEmail'];


      }

    }

?>
<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?=$SITE_NAME?> | Admin</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
            <link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

        <?php include_once('global_files.php');?>
        <link href="../assets/css/jquery-ui.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
    <link rel="stylesheet" href="../assets/validation/validatrix.css" />
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
                                <h2>Email Notification</h2>
                                <input type="button" id="" onclick="window.location.href='nortification_settings_list.php'" value="Back" class="add-btn">
                            </div>

                        </div>
                        <hr />
                    </div>
                    <?php include('valid_msg.php'); ?>
					<div class="panel-heading">
                     <form method="post">

                     <div class="row">
                       <div class="col-lg-6">
                       <label>Event</label>    
                         <select class="form-control" name="event" id="event" required>
                          <option value="">Select</option>
                           <option value="Driver Signup" <?php if (trim($event)=="Driver Signup") echo "selected"; ?>>Driver Signup</option>
                            <option value="Passenger Signup" <?php if (trim($event)=="Passenger Signup") echo "selected"; ?>>Passenger Signup</option>
                             <option value="Company Signup" <?php if (trim($event)=="Company Signup") echo "selected"; ?>>Company Signup</option>
                               <option value="Performance" <?php if (trim($event)=="Performance") echo "selected"; ?>>Performance</option>
                         </select>

                       </div> </div>
                        <div class="row" id="actionByRow">
                       <div class="col-lg-6">
                      <label>  Action by   </label>
                            <select class="form-control" name="actionBy" id="actionBy" required>
                          <option value="">Select</option>
                           <option value="Driver" <?php if (trim($actionBy)=="Driver") echo "selected"; ?>>Driver</option>
                            <option value="Passenger" <?php if (trim($actionBy)=="Passenger") echo "selected"; ?>>Passenger</option>
                           <option value="Super Admin" <?php if (trim($actionBy)=="Super Admin") echo "selected"; ?>>Super Admin</option>

                            <option value="Company" <?php if (trim($actionBy)=="Company") echo "selected"; ?>>Company</option>


                         </select>
                       </div> </div>
                        <div class="row">
                       <div class="col-lg-2">
                        <label>Notify Company   </label>  
                         
                       </div> 
             <div class="col-lg-6">
           <div class="make-switch" data-on="success" data-off="warning" data-on-label='Yes' data-off-label='No'>
                         <input type="checkbox" name="NotifyCompany" id="NotifyCompany" <?= ($NotifyCompany == 'off') ? '' : 'checked'; ?> />
                      </div>
           </div>



                     </div>
                         <div class="row">
                       <div class="col-lg-2">
                       <label> Notify Provider </label>   
                         
                       </div>
    <div class="col-lg-6">
           <div class="make-switch" data-on="success" data-off="warning" data-on-label='Yes' data-off-label='No'>
                         <input type="checkbox" name="NotifyProvider" id="NotifyProvider" <?= ($NotifyProvider == 'off') ? '' : 'checked'; ?> />
                      </div>
           </div>

                        </div>
                         <div class="row">
                       <div class="col-lg-2">
                        <label>Notify Customer   </label>
                         
                       </div>

   <div class="col-lg-6">
           <div class="make-switch" data-on="success" data-off="warning" data-on-label='Yes' data-off-label='No'>
                         <input type="checkbox" name="NotifyCustomer" id="NotifyCustomer" <?= ($NotifyCustomer == 'off') ? '' : 'checked'; ?> />
                      </div>
           </div>
                        </div>
                         <div class="row">
                  <div class="col-lg-2">
                       <label>Notify Administrator   </label>
                         
                       </div>
            <div class="col-lg-6">
              <div class="make-switch" data-on="success" data-off="warning" data-on-label='Yes' data-off-label='No'>
                <input type="checkbox" name="NotifyAdministrator" id="NotifyAdministrator" <?= ($NotifyAdministrator == 'off') ? '' : 'checked'; ?> />
                      </div>
           </div>


                        </div>
                         <div class="row">
                        <div class="col-lg-6">
                       <label> Additional Email Addresses   </label> <br>
                         <strong>Note :</strong> <span>Separate with comma ( <b style="font-size:20px">,</b> )</span>
                       </div> </div> 
 <div class="row">
                   <div class="col-lg-6">
                       <input type="text" value="<?=$AdditionalEmails?>" name="AdditionalEmails" id="AdditionalEmails" class="form-control">
                         
                       </div>
                     </div>
                         <div class="row">
                        <div class="col-lg-6">
                           <input type="submit" name="submit" id="submit" value="submit" class="btn btn-default">
                         
                       </div>
                        </div>
                  
			</form>
                    
                    
                  
                    </div>
                </div>
                <!--END PAGE CONTENT -->
            </div>
            <!--END MAIN WRAPPER -->
            

    <?php
    include_once('footer.php');
    ?>
            <script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>

    </body>
    <!-- END BODY-->
</html>
<style type="text/css">
  .row
  {
    margin-top: 10px;
  }

</style>
<script type="text/javascript">
  
  $("#event").change(function(){

    if($(this).val().trim().toLowerCase()=="performance")
    {

      $("#actionBy").removeAttr("required");
      $("#actionByRow").hide();
    }
 else
 {
              $("#actionBy").attr("required","required");
                    $("#actionByRow").show();


 }
  });
  $(document).ready(function(){

    $("#event").trigger("change");
  });

</script>