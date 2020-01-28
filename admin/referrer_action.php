<?php
include_once('../common.php');

if($REFERRAL_SCHEME_ENABLE == "No"){
	
header('Location: dashboard.php'); exit;

}

if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");
     $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();

$script = 'view-referrer';  	
	
$id =$_REQUEST['id'];
$etype ="";	
$type=(isset($_REQUEST['eUserType'])?$_REQUEST['eUserType']:'');

if($type == 'Driver'){
	$tablename ='register_driver';
	$iUserId = "iDriverId";
}else{	
	$tablename = 'register_user';
	$iUserId = 'iUserId';
}	

$query = "SELECT concat(vName, ' ' ,vLastName) as MemberName FROM ".$tablename." WHERE ".$iUserId." = '".$id."' ";			
$result = $obj->MySQLSelect($query);	
$MemberName = $generalobjAdmin->clearName($result[0]['MemberName']);

if($type == 'Driver'){

	$q1 = "SELECT rd.vName,rd.vLastName,concat(rd.vName, ' ' ,rd.vLastName) as OrgDriverName,rd.eRefType,rd.iDriverId,rd.iRefUserId,rd.dRefDate FROM register_driver as rd LEFT JOIN register_driver as rd1 on rd1.iDriverId=rd.iRefUserId WHERE rd.iRefUserId = '".$id."'";
	$result_driver = $obj->MySQLSelect($q1);

	$q2 = "SELECT ru.vName,ru.vLastName,concat(ru.vName, ' ' ,ru.vLastName) as OrgRiderName,ru.eRefType, ru.iUserId,ru.iRefUserId,ru.dRefDate FROM register_user as ru LEFT JOIN register_driver as rd1 on rd1.iDriverId=ru.iRefUserId WHERE ru.iRefUserId = '".$id."'";
	$result_rider = $obj->MySQLSelect($q2);

} else {

	$q3 = "SELECT rd1.vName,rd1.vLastName,concat(rd1.vName, ' ' ,rd1.vLastName) as OrgDriverName,ru.eRefType,rd1.iDriverId,rd1.iRefUserId,rd1.dRefDate FROM register_user as ru LEFT JOIN register_driver as rd1 on rd1.iRefUserId=ru.iUserId WHERE rd1.iRefUserId = '".$id."' AND rd1.eRefType = 'Rider'";
	$result_driver = $obj->MySQLSelect($q3);

    $q4 = "SELECT ru.vName,ru.vLastName,concat(ru.vName, ' ' ,ru.vLastName) as OrgRiderName,ru.eRefType, ru.iUserId,ru.iRefUserId,ru.dRefDate FROM register_user as ru LEFT JOIN register_user as ru1 on ru1.iUserId=ru.iRefUserId WHERE ru.iRefUserId = '".$id."' AND ru.eRefType = 'Rider'";
    $result_rider = $obj->MySQLSelect($q4);

}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

     <!-- BEGIN HEAD-->
     <head>
          <meta charset="UTF-8" />
          <title>Admin | Referrer</title>
          <meta content="width=device-width, initial-scale=1.0" name="viewport" />
          <link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

          <? include_once('global_files.php');?>
		  
     </head>
     <!-- END  HEAD-->
     <!-- BEGIN BODY-->
     <body class="padTop53 " >

          <!-- MAIN WRAPPER -->
          <div id="wrap">
               <? include_once('header.php'); ?>
               <? include_once('left_menu.php'); ?>

               <!--PAGE CONTENT -->
               <div id="content">
                    <div class="inner">
					
					<div class="row">
						<div class="col-lg-12">
							<h2><?=$MemberName;?>  Referral Details</h2>
							<a href="javascript:void(0);" class="back_link">
								<input type="button" value="Back to Listing" class="add-btn">
							</a>
						</div>
					</div>
					<hr />                       
							
						
                         <div class="table-list">
                              <div class="row">
                                   <div class="col-lg-12">
                                        <div class="panel panel-default">
                                             <div class="panel-heading">
                                             	<strong>
                                             	<?php echo $MemberName;?>'s Referral <?php echo $langage_lbl_admin['LBL_DRIVERS_NAME_ADMIN'];?>                                      
                                             </strong>
                                             </div>
                                             <div class="panel-body">
                                                  <div class="table-responsive">
                                                       <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                            <thead>
                                                                <tr>
																	<th width="35%">Referred Member Name</th>
																	<th width="35%">Date Referred</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>										 
																	
															<?php 
																 $count = count($result_driver);
																 if($count > 0){
																 for($i=0;$i<count($result_driver);$i++){ ?>
																	 <tr class="gradeA">
																		<!--<td ><?=$result_driver[$i]['vName'];?> <?= $result_driver[$i]['vLastName'];?></td>-->
                                    <td ><?=$generalobjAdmin->clearName($result_driver[$i]['OrgDriverName']);?></td> 
																		<?php 
																			$time = strtotime($result_driver[$i]['dRefDate']);
																			$myFormatForView = date("jS F Y", $time);
																		?>
																		<td><?= $myFormatForView ?></td> 
																	 </tr>																 
																	 
																<?php  }
																	
																 }else{ ?>																 
																  <tr class="gradeA">
																  <td colspan ="3" align="center"> No Details Found </td> 
																  </tr>
																 
																<?php  } 	?>                                                               

                                                            </tbody>
                                                       </table>
													   
                                                  </div>

                                             </div>
                                        </div>
                                   </div> <!--TABLE-END-->
                              </div>
                         </div>
						 
							
                         <div class="table-list">
                              <div class="row">
                                   <div class="col-lg-12">
                                        <div class="panel panel-default">
                                             <div class="panel-heading">
                                             <strong>
                                             	<?php echo $MemberName;?>'s Referral <?php echo $langage_lbl_admin['LBL_PASSANGER_TXT_ADMIN'];?>   												
                                             </strong>
                                             </div>
											 </hr>
											 
                                             <div class="panel-body">
                                                  <div class="table-responsive">
                                                       <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                            <thead>
                                                                <tr>
																	<th width="35%">Referred Member Name</th>
																	<th width="35%">Date Referred</th>						
                                                                </tr>
                                                            </thead>
                                                            <tbody>                                                                 
																 <?php 
																  $count = count($result_rider);
																 if($count > 0){
																 for($i=0;$i<count($result_rider);$i++){ ?>										 
																	 
																	 <tr class="gradeA">
																		<!--<td><?= $result_rider[$i]['vName'];?> <?= $result_rider[$i]['vLastName'];?></td>-->
                                    <td ><?=$generalobjAdmin->clearName($result_rider[$i]['OrgRiderName']);?></td> 
																		<?php 
																			$time = strtotime($result_rider[$i]['dRefDate']);
																			$myFormatForView = date("jS F Y", $time);		 
																		?>
																		<td><?= $myFormatForView ?></td>
																	 </tr>																 
																	 
																<?php  }
																	
																 } else { ?>																 
																  <tr class="gradeA">
																  <td colspan ="3" align="center"> No Details Found </td> 
																  </tr>
																 
																<?php  } 	?> 
																           
                                                                

                                                            </tbody>
                                                       </table>
													   
                                                  </div>

                                             </div>
                                        </div>
                                   </div> <!--TABLE-END-->
                              </div>
                         </div>
                    </div>
               </div>

               <!--END PAGE CONTENT -->
          </div>
          <!--END MAIN WRAPPER -->


          <? include_once('footer.php');?>
          <script>
			function confirm_delete(action,id)
			{
					 //alert(action);alert(id);
				 var confirm_ans = confirm("Are You sure You want to Delete this Rider?");
					   //alert(confirm_ans);
				 if(confirm_ans=='false')
					 {
						return false;
						}
					 else
					 {
						 $('#action').val(action);
						 $('#iRatingId').val(id);
						 document.frmreview.submit();
					}
													 
			 }
			 function getReview(type)
			{
				
				$('#reviewtype').val(type);
				document.frmreview.submit();
					
			}
			
			$(document).ready(function() {
				var referrer;
				referrer =  document.referrer;
				if(referrer == "") {
					referrer = "referrer.php";
				}
				$(".back_link").attr('href',referrer);
			});
			
			
		</script>
     </body>
     <!-- END BODY-->
</html>
