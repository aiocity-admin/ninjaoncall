<?php
include_once('../common.php');

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();


//Deleting Record
if (isset($_REQUEST['delete']))
{
$id=$_REQUEST['delete'];
    $sql="delete from nortification_settings where ID='$id'";
      $_SESSION['var_msg']="Record has been deleted.";

  $obj->MySQLSelect($sql);
}

?>
<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?=$SITE_NAME?> | Email Notification</title>
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
    <body class="padTop53">
      <!-- Main LOading -->
      <!-- MAIN WRAPPER -->
      <div id="wrap">
          <?php include_once('header.php'); ?>
          <?php include_once('left_menu.php'); ?>
          <!--PAGE CONTENT -->
            <div id="content">
                <div class="inner">
              

                  <!-- List of Nortification-->


                                        <?php 
 if(trim($_SESSION['var_msg'])!="")
 {
  ?>
   <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                <?php echo $_SESSION['var_msg']; ?>
                            </div><br/>
  <?php
  $_SESSION['var_msg']="";
 }

 ?>
                  <div class="row">
                    <div class="col-lg-12">
                      <h2>Email Notifications</h2>

<br><br>
                      <div class="trips-table trips-table-driver trips-table-driver-res"> 
              <div class="trips-table-inner">
<div class="row">
  <div class="col-lg-12" >

  <input type="button" class="add-btn" name="Add" value="Add" onclick="window.location.href='nortification_settings.php'">

</div>

</div>
   <hr>
   <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="1"  id="dataTables-example" aria-describedby="dataTables-example_info" style="width: 100%;">
                                                <thead>
                                                  <tr>
                                                    <th>Event</th>
                                                 
                                                   <th>Action By</th>
                                                <th>Notify Company</th>
                                                   <th>Notify Provider</th>
                                                      <th>Notify Customer</th>
                                                         <th>Notify Administrator</th>
                                                            <th>Additional Email</th>
                                           <th>Action</th>

                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php
                                                  $sql="select * from nortification_settings";
$data=$obj->MySQLSelect($sql);

foreach ($data as $key => $value) {
  

                                                  ?>
                                                  <tr><td><?=$value['Event'];?></td>
                                                    <td><?=$value['ActionBy'];?></td>
                                                    <td><?=$value['NotifyCompany'];?></td>
                                                    <td><?=$value['NotifyProvider'];?></td>
                                                    <td><?=$value['NotifyCustomer'];?></td>
                                                    <td><?=$value['NotifyAdministrator'];?></td>
                                                    <td><?=$value['AdditionalEmail'];?></td>
<td><a href="javascript:window.location.href='nortification_settings.php?id='+<?=$value['ID']?>+'&action=Edit'">Edit</a> | 
  <a href="javascript:window.location.href='nortification_settings_list.php?delete='+<?=$value['ID']?>">Delete</a>


  <?php  ?> </td>
                                                  </tr>
<?php } ?>
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>

                      
                    </div>
                    
                  </div>
                       <!--End List -->
                    </div>
                </div>
                <!--END PAGE CONTENT -->
            </div>
            <!--END MAIN WRAPPER -->

                    
    <?php
    include_once('footer.php');   

    ?>
<script src="../assets/js/jquery-ui.min.js"></script>
<script src="../assets/plugins/dataTables/jquery.dataTables.js"></script>
    </body>
    <style type="text/css">
        div#dataTables-example_filter {
    display: inline;
    float: right;
}
div#dataTables-example_length {
    display: inline;
}


  .paginate_button,.dataTables_length select, input[type='search']
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
    margin:2px;
  }
  .current
{
  
  background: #219201;
  color: #FFFFFF;
}
th
{
    cursor: pointer;
}
th:nth-of-type(8), td:nth-of-type(8) {
    width: 85px !important;
}
    </style>
   
 <script type="text/javascript">
   $(document).ready(function(){

    $('#dataTables-example').dataTable({
        fixedHeader: {
          footer: true
        },
        "order": [],
        "aaSorting": []});
   });

 </script>
</html>