<?php
include_once('../common.php');
$script = 'reasons';

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();

//adding reason
if (isset($_REQUEST['add']))
{

  $reason=$_REQUEST['reason'];
  $Reason_For=$_REQUEST['Reason_For'];

  $sql="insert into reasons(Reason,Reason_For) values('$reason','$Reason_For')";
  $obj->MySQLSelect($sql);
  $_SESSION['var_msg']="Reason has been added.";
  header("Location:reasons.php");
}
//end adding reason
//Deleting reason
if (isset($_REQUEST['delete']))
{
$id=$_REQUEST['delete'];
    $sql="delete from reasons where ID='$id'";
      $_SESSION['var_msg']="Reason has been deleted.";

  $obj->MySQLSelect($sql);
}
//end Deleting reason

?>
<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?=$SITE_NAME?> | Reasons</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <?php include_once('global_files.php');?>

    <style type="text/css">
      .inner
      {
     min-height: 400px !important;     
     margin-left: 10px;
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
                  <!-- Adding reason-->
                  <div class="row">
                    <h2>Reasons</h2>
                    <br>           <br>
                    <hr>
                    <div class="col-lg-12">
                      <form action="" method="post">
                      <div class="col-lg-3">

                      <input required="" type="text" placeholder="Reason" name="reason" class="form-control">
                      
                    </div>
                    <div class="col-lg-3">
                      <select class="form-control" name="Reason_For" required="">
                        <option value="">Select</option>
                        <option value="Delete Vehicle">Delete Vehicle</option>
                        <option value="Inactive Vehicle">Inactive Vehicle</option>

                      </select>
                      
                    </div>
                    <div class="col-lg-3">
                      <input type="submit" name="add" value="ADD" class="btn btn-button1">
                      
                    </div>
   <div class="col-lg-3">
                      
                      
                    </div>
                    </form>
                    </div>

                  </div>
                  <!--End Adding reason-->

                  <!-- List of reasons-->


                                        <?php 
 if(trim($_SESSION['var_msg'])!="")
 {
  ?>
   <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                <?php echo $_SESSION['var_msg']; ?>
                            </div><br/>
  <?php
 }

 ?>
                  <div class="row">
                    <div class="col-lg-12">
<br><br>
                      <div class="trips-table trips-table-driver trips-table-driver-res"> 
              <div class="trips-table-inner">
   <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="1"  id="dataTables-example" aria-describedby="dataTables-example_info" style="width: 100%;">
                                                <thead>
                                                  <tr>
                                                    <th>Reason</th>
                                                 
                                                   <th>Reason For</th>
                                                <th>Delete</th>
                                                
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php
                                                  $sql="select * from reasons";
$data=$obj->MySQLSelect($sql);

foreach ($data as $key => $value) {
  

                                                  ?>
                                                  <tr><td><?=$value['Reason'];?></td><td><?=$value['Reason_For'];?></td>
<td><?php  if (strtolower($value['Reason'])!='other') {
  # code...
 ?> <a href="javascript:window.location.href='reasons.php?delete='+<?=$value['ID']?>">Delete</a><?php } ?> </td>
                                                  </tr>
<?php } ?>
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>

                      
                    </div>
                    
                  </div>
                       <!--End List of reasons-->
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
      margin:5px;

  }
  .current
{
  background: #219201;
  color: #FFFFFF;
}
.ui-helper-hidden-accessible {
    display: none;
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