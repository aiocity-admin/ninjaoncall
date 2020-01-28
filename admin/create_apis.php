<?php
include_once('../common.php');
if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$script = 'apis';

//adding key
if (isset($_REQUEST['add']))
{

  $api_key=$_REQUEST['api_key'];
  $username=$_REQUEST['username'];

$query="select * from keys_of_apis where api_key='$api_key'";
$result=$obj->MySQLSelect($query);

if (count($result)==0) {

  $sql="insert into  keys_of_apis(api_key,username) values('$api_key','$username')";
  $obj->MySQLSelect($sql);
  $_SESSION['create_apis']="Key has been added.";
  header("Location:create_apis.php");
}
else
{
  $_SESSION['create_apis']="Key is already exists.";

}
}
//end adding key
//Deleting key
if (isset($_REQUEST['delete']))
{
$id=$_REQUEST['delete'];
    $sql="delete from keys_of_apis where id='$id'";
      $_SESSION['create_apis']="Key has been deleted.";

  $obj->MySQLSelect($sql);

}
//end Deleting key

?>
<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?=$SITE_NAME?> | API's Keys</title>
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
                  <!-- Adding APIs-->
                  <div class="row">
                    <h2>API's Keys</h2>
                    <br>           <br>
                    <hr>
                    <div class="col-lg-12">
                      <form action="" method="post" id="frm">
                        <div class="row">
                           <div class="col-lg-3">
                     <input required="" type="text" placeholder="Username" name="username" id="username" class="form-control">
                     <br>
                      <span class="error" id="useraname_error"></span>
                    </div>
                      <div class="col-lg-3">

                      <input readonly required  type="text" placeholder="API key" name="api_key" id="api_key" class="form-control">
                       <br>
                      <span class="error" id="api_key_error"></span>
                    </div>
                       <div class="col-lg-2">
                      <a class="btn btn-primary" id="Generate">Generate</a>
                      
                    </div>
                    </div>
                                            <div class="row">

                 
                    <div class="col-lg-1">
                      <input type="hidden" name="add" value="add">
                      <input type="button" name="add" value="Save" class="btn btn-default save" id="save">
                      
                    </div></div>

                    </form>
                    </div>

                  </div>
                  <!--End Adding APIs-->

                  <!-- List of APIs-->


                                        <?php 
 if(trim($_SESSION['create_apis'])!="")
 {
  ?>
   <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                <?php echo $_SESSION['create_apis']; ?>
                            </div><br/>
  <?php

  if (!isset($_REQUEST['add']))
 {
  $_SESSION['create_apis']="";
  }

if (isset($_REQUEST['delete']))
{
  $_SESSION['create_apis']="";

}

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
                                                    <th>Key</th>
                                                    <th>Username</th>
                                                    <th>Delete</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php
                                                  $sql="select * from  keys_of_apis";
                                                  $data=$obj->MySQLSelect($sql);

                                                 foreach ($data as $key => $value) {
                                                  ?>
                                                  <tr><td><?=$value['api_key'];?></td><td><?=$value['username'];?></td>
<td><a href="javascript:if(confirm('Do you want to delete?')){window.location.href='create_apis.php?delete='+<?=$value['id']?>;}">Delete</a><?php } ?> </td>
                                                  </tr>
 
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>

                      
                    </div>
                    
                  </div>
                       <!--End List of APIs-->
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
#Generate
{
  cursor: pointer;
}
.ui-helper-hidden-accessible {
    display: none;
}
.inner {
    margin-left: 10px;
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


$("#save").click(function(){
var isValid=true;
var username=$("#username").val();
var api_key=$("#api_key").val();
  $("#useraname_error").text("");
  $("#api_key_error").text("");

if (username.trim()=="") 
{
  isValid=false;
  $("#useraname_error").text("*Please enter username.");
}
if (api_key.trim()=="") 
{
    isValid=false;

  $("#api_key_error").text("*Please click on generate button to generate key.");
}
if (isValid)
{
$("#frm").submit();
}
 

});

$("#Generate").click(function(){
 $.ajax({
        type: "POST",
        url: "generate_api_key.php",
        success: function(data){
          $("#api_key_error").text("");
      $("#api_key").val(data);
        },
        error: function(data){
            
        }
    });

});


   });

 </script>
</html>