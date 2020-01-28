<script type="text/javascript">
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
           data: {"loadtime":seconds,"beforeload":date1,"afterload":date2,"UserType":"SUPER_ADMIN","eType":"REFERRER_REPORT"}, 
           success: function(data)
           {
               
           }
         });

}
</script>
<?php
include_once('../common.php');

if ($REFERRAL_SCHEME_ENABLE == "No") {
    header('Location: dashboard.php');
    exit;
}

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}


$generalobjAdmin->check_member_login();
$script = 'referrer';
$type = (isset($_REQUEST['reviewtype']) && $_REQUEST['reviewtype'] != '') ? $_REQUEST['reviewtype'] : 'Driver';


if($type == 'Driver') {
    $sql1 = "SELECT CONCAT(vName,' ',vLastName) AS currentdriverName,eStatus,iDriverId FROM register_driver";
    $data_drv1 = $obj->MySQLSelect($sql1);
    foreach ($data_drv1 as $key => $value) {
       $q1 = "SELECT CONCAT(rd.vName,' ',rd.vLastName) AS OrgdriverName,rd.eRefType as eRefType,rd1.iDriverId,rd1.iRefUserId FROM register_driver as rd LEFT JOIN register_driver as rd1 on rd1.iRefUserId=rd.iDriverId WHERE rd1.iRefUserId = '".$value['iDriverId']."' AND rd1.eRefType = 'Driver'";
        $data_drv2 = $obj->MySQLSelect($q1);

        $q2 = "SELECT CONCAT(ru.vName,' ',ru.vLastName) AS passangerName,CONCAT(rd1.vName,' ',rd1.vLastName) AS OrgdriverName,ru.eRefType as eRefType , ru.iUserId,ru.iRefUserId FROM register_user as ru LEFT JOIN register_driver as rd1 on rd1.iDriverId=ru.iRefUserId WHERE ru.iRefUserId = '".$value['iDriverId']."' AND ru.eRefType = 'Driver'";
        $data_drv3 = $obj->MySQLSelect($q2);
        if(!empty($data_drv2)){
            $results[$data_drv2[0]['iRefUserId']][] =  $data_drv2;
        }

        if(!empty($data_drv3)){
            $results[$data_drv3[0]['iRefUserId']][] =  $data_drv3;
        }

    }
    foreach ($results as $key => $value) {
       foreach ($value as $ky => $ve) {
          foreach ($ve as $k => $v) {
              $total_no_driver[$key][] = $v;
          }

       }
    }

} else{

    $sql1 = "SELECT CONCAT(vName,' ',vLastName) AS currentUserName,eStatus,iUserId FROM register_user $ord";
    $data_rider1 = $obj->MySQLSelect($sql1);
    foreach ($data_rider1 as $key => $value) {
        $q1 = "SELECT CONCAT(ru.vName,' ',ru.vLastName) AS OrgdriverName,ru.eRefType as eRefType,rd1.iDriverId,rd1.iRefUserId FROM register_user as ru LEFT JOIN register_driver as rd1 on rd1.iRefUserId=ru.iUserId WHERE rd1.iRefUserId = '".$value['iUserId']."' AND rd1.eRefType = 'Rider'";
        $data_drv2 = $obj->MySQLSelect($q1);

        $q2 = "SELECT CONCAT(ru1.vName,' ',ru1.vLastName) AS OrgdriverName,ru.eRefType as eRefType , ru.iUserId,ru.iRefUserId FROM register_user as ru LEFT JOIN register_user as ru1 on ru1.iUserId=ru.iRefUserId WHERE ru.iRefUserId = '".$value['iUserId']."' AND ru.eRefType = 'Rider'";
        $data_drv3 = $obj->MySQLSelect($q2);

        if(!empty($data_drv2)){
            $result[$data_drv2[0]['iRefUserId']][] =  $data_drv2;
        }

        if(!empty($data_drv3)){
            $result[$data_drv3[0]['iRefUserId']][] =  $data_drv3;
        }
    }
    
    foreach ($result as $key => $value) {
       foreach ($value as $ky => $ve) {
          foreach ($ve as $k => $v) {
              $total_no_user[$key][] = $v;
          }

       }
    }
   
}

$reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages.$var_filter;


$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : '';

?>

<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->

<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

    <!-- BEGIN HEAD-->

    <head>

        <meta charset="UTF-8" />

        <title><?=$SITE_NAME?> | Referral Report</title>

        <meta content="width=device-width, initial-scale=1.0" name="viewport" />

        <link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

        <? include_once('global_files.php');?>

    </head>

    <!-- END  HEAD-->

    <!-- BEGIN BODY-->

    <body class="padTop53">

        <!-- MAIN WRAPPER -->

        <div id="wrap">

            <? include_once('header.php'); ?>

            <? include_once('left_menu.php'); ?>

            <!--PAGE CONTENT -->

            <div id="content">

                <div class="inner">

                    <div id="add-hide-show-div">

                        <div class="row">

                            <div class="col-lg-12">

                                <h2>Referral Report</h2>

                            </div>

                        </div>

                        <hr />

                    </div>

                    <?php include('valid_msg.php'); ?>

                    <div class="table-list">

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="panel panel-default">

                                    <div class="panel-heading referrer-page-tab">

                                        <ul class="nav nav-tabs">

                                            <li <?php if ($type == 'Driver') { ?> class="active" <?php } ?>>

                                                <a data-toggle="tab"  onclick="getReview('Driver')"  href="#home" ><?= $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']; ?></a></li>

                                            <li <?php if ($type == 'Rider') { ?> class="active" <?php } ?>>

                                                <a data-toggle="tab" onClick="getReview('Rider')"  href="#menu1"><?= $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN']; ?></a></li>

                                        </ul>

                                    </div>

                                    <div class="panel-body">

                                        <div class="table-responsive">

                                            <form class="_list_form" id="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">

                                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">

                                                <thead>

                                                    <tr>
                                                       <th width="35%">Member Name</th>
                                                        <th width="25%">Total Members Referred</th>
                                                        <th width="25%">Total Amount Earned <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='Amount earned in wallet once refferal do a successful first trip.'></i></th>                                                              
                                                        <th width="15%">Detail</th>
                                                    </tr>
                                                </thead>
                                                <tbody>                                                               
                                                <?php
                                                if($type == 'Driver') { 
                                                    
                                                    if(count($total_no_driver) > 0){
                                                        $i=0;
                                                        foreach ($total_no_driver as $k => $val){
                                                            $totalbalance = $generalobj->getTotalbalance($k, 'Driver');
                                                    ?>
                                                         <tr>
                                                            <td><?= $generalobjAdmin->clearName($val[0]['OrgdriverName'])?></td>
                                                            <td><?= count($val);?></td>
                                                            <td><?= ($totalbalance > 0) ? $generalobj->trip_currency($totalbalance):'--';?></td>
                                                            <td> <a href="referrer_action.php?id=<?php echo $k; ?>&eUserType=Driver" data-toggle="tooltip" title="View Details">
                                                                        <img src="img/view-details.png" alt="View Details">
                                                                    </a>
                                                            </td>
                                                        </tr>
                                                <? $i++;
                                                    } 
                                                    } 
                                                } else {
                                                   
                                                    if(count($total_no_user) > 0){
                                                        $i=0;
                                                        foreach ($total_no_user as $k => $val){
                                                            $totalbalance = $generalobj->getTotalbalance($k, 'Rider');
                                                    ?>
                                                         <tr>
                                                            <td><?= $generalobjAdmin->clearName($val[0]['OrgdriverName'])?></td>
                                                            <td><?= count($val);?></td>
                                                            <td><?= ($totalbalance > 0) ? $generalobj->trip_currency($totalbalance):'--';?></td>
                                                            <td> 
                                                                <a href="referrer_action.php?id=<?php echo $k; ?>&eUserType=Rider" data-toggle="tooltip" title="View Details">

                                                                    <img src="img/view-details.png" alt="View Details">

                                                                </a>
                                                            </td>
                                                        </tr>
                                                <? $i++;
                                                    } } 
                                                    }
                                                ?>
                                                </tbody>

                                            </table>

                                            </form>

                                            <form name="frmreview" id="frmreview" method="post" action="">

                                                <input type="hidden" name="reviewtype" value="" id="reviewtype">

                                                <input type="hidden" name="action" value="" id="action">

                                            </form>

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
 <script src="../assets/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/plugins/dataTables/dataTables.bootstrap.js"></script>
  <script>
    $(document).ready(function () {
      $('#dataTables-example').dataTable({
        "order": [[ 1, "desc" ]],
        "iDisplayLength": 25
      });
    });


  </script>
        <script>

            $("#setAllCheck").on('click',function(){

                if($(this).prop("checked")) {

                    jQuery("#_list_form input[type=checkbox]").each(function() {

                        if($(this).attr('disabled') != 'disabled'){

                            this.checked = 'true';

                        }

                    });

                }else {

                    jQuery("#_list_form input[type=checkbox]").each(function() {

                        this.checked = '';

                    });

                }

            });


            $("#Search").on('click', function(){

                var action = $("#_list_form").attr('action');

                var formValus = $("#frmsearch").serialize();

                window.location.href = action+"?"+formValus;

            });

            

            $('.entypo-export').click(function(e){

                 e.stopPropagation();

                 var $this = $(this).parent().find('div');

                 $(".openHoverAction-class div").not($this).removeClass('active');

                 $this.toggleClass('active');

            });

            

            $(document).on("click", function(e) {

                if ($(e.target).is(".openHoverAction-class,.show-moreOptions,.entypo-export") === false) {

                  $(".show-moreOptions").removeClass("active");

                }

            });

            

            function getReview(type)

            {

                $('#reviewtype').val(type);

                document.frmreview.submit();

            }

        </script>

    </body>

    <!-- END BODY-->

</html>

<script type="text/javascript">
  window.onload = getPageLoadTime;

</script>