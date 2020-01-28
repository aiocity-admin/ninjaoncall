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
           data: {"loadtime":seconds,"beforeload":date1,"afterload":date2,"UserType":"SUPER_ADMIN","eType":"DRIVER_PAY_REPORT"}, 
           success: function(data)
           {
               
           }
         });

}
</script>
<?php
$tbl_name 	= 'trips';
if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");
     $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$script='Driver Payment Report';

$action = isset($_REQUEST['action']) ? $_REQUEST['action']: '';
$searchCompany = isset($_REQUEST['searchCompany']) ? $_REQUEST['searchCompany'] : '';
$searchDriver = isset($_REQUEST['searchDriver']) ? $_REQUEST['searchDriver'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';

//data for select fields
$sql = "select iCompanyId,vCompany,vEmail from company WHERE eStatus != 'Deleted' order by vCompany";
$db_company = $obj->MySQLSelect($sql);


$sql = "select iDriverId,CONCAT(vName,' ',vLastName) AS driverName,vEmail from register_driver WHERE eStatus != 'Deleted' order by vName";
$db_drivers = $obj->MySQLSelect($sql);

//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';

$subQuery="";
 if (trim($_REQUEST['searchDriver'])!='null')
{

 $Provider= explode(',', $_REQUEST['searchDriver']);
  $subQuery_providers='(';
  for ($i=0; $i <count($Provider) ; $i++) { 
  $subQuery_providers.=" tr.iDriverId='$Provider[$i]' or";
  }
   $subQuery_providers=rtrim($subQuery_providers,'or');
 $subQuery_providers.=")";
$subQuery.=" and $subQuery_providers";

}

$sub_Query="";
 if (trim($_REQUEST['searchCompany'])!='null'&&trim($_REQUEST['searchCompany'])!='')
{

 $company= explode(',', $_REQUEST['searchCompany']);
  $subQuery_company='(';
  $sub_Query.="(";

  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" rd.iCompanyId='$company[$i]' or";
  $sub_Query.=" rd.iCompanyId='$company[$i]' or";

  }
   $subQuery_company=rtrim($subQuery_company,'or');
    $sub_Query=rtrim($sub_Query,'or');
 $subQuery_company.=")";
 $sub_Query.=")";
$subQuery.=" and $subQuery_company";

}



$ord = ' ORDER BY rd.iDriverId DESC';
if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY rd.iDriverId ASC";
  else
  $ord = " ORDER BY rd.iDriverId DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY rd.vName ASC";
  else
  $ord = " ORDER BY rd.vName DESC";
}

if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY rd.vBankAccountHolderName ASC";
  else
  $ord = " ORDER BY rd.vBankAccountHolderName DESC";
}

if($sortby == 4){
  if($order == 0)
  $ord = " ORDER BY rd.vBankName ASC";
  else
  $ord = " ORDER BY rd.vBankName DESC";
}
//End Sorting


// Start Search Parameters

//$ssql='';
$ssql=" AND tr.iActive = 'Finished' ";
$ssql1 = '';
if ($action == 'search') {
	if($startDate!=''){
		//$ssql.=" AND Date(tr.tEndDate) >='".$startDate."'";
	  $ssql.=" AND Date(tr.tTripRequestDate) >='".$startDate."'";
	}
	if($endDate!=''){
		//$ssql.=" AND Date(tr.tEndDate) <='".$endDate."'";
	  $ssql.=" AND Date(tr.tTripRequestDate) <='".$endDate."'";
	}
	// if ($searchCompany != '') {
 //        $ssql1 .= " AND rd.iCompanyId ='" . $searchCompany . "'";
 //    }
    // if ($searchDriver != '') {
    //     $ssql .= " AND tr.iDriverId ='" . $searchDriver . "'";
    // }

 $ssql1 .= $subQuery;
}
//Select dates
$Today=Date('Y-m-d');
$tdate=date("d")-1;
$mdate=date("d");
$Yesterday = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

$curryearFDate = date("Y-m-d",mktime(0,0,0,'1','1',date("Y")));
$curryearTDate = date("Y-m-d",mktime(0,0,0,"12","31",date("Y")));
$prevyearFDate = date("Y-m-d",mktime(0,0,0,'1','1',date("Y")-1));
$prevyearTDate = date("Y-m-d",mktime(0,0,0,"12","31",date("Y")-1));

$currmonthFDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$tdate,date("Y")));
$currmonthTDate = date("Y-m-d",mktime(0,0,0,date("m")+1,date("d")-$mdate,date("Y")));
$prevmonthFDate = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d")-$tdate,date("Y")));
$prevmonthTDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$mdate,date("Y")));

$monday = date( 'Y-m-d', strtotime( 'sunday this week -1 week' ) );
$sunday = date( 'Y-m-d', strtotime( 'saturday this week' ) );

$Pmonday = date( 'Y-m-d', strtotime('sunday this week -2 week'));
$Psunday = date( 'Y-m-d', strtotime('saturday this week -1 week'));

$per_page = $DISPLAY_RECORD_NUMBER;	
$sql = "select COUNT( DISTINCT rd.iDriverId ) AS Total from register_driver AS rd 
LEFT JOIN trips AS tr ON tr.iDriverId=rd.iDriverId
WHERE tr.eDriverPaymentStatus='Unsettelled' $ssql $ssql1";
$totalData = $obj->MySQLSelect($sql);
$total_results = $totalData[0]['Total'];
$total_pages = ceil($total_results / $per_page); //total pages we going to have
$show_page = 1;

//-------------if page is setcheck------------------//
if (isset($_REQUEST['page'])) {
    $show_page = $_REQUEST['page'];             //it will telles the current page
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
$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 0;
$tpages=$total_pages;
if ($page <= 0)
    $page = 1;
//Pagination End
	
$sql = "select rd.iDriverId,tr.eDriverPaymentStatus,concat(rd.vName,' ',rd.vLastName) as dname,rd.vCountry,rd.vBankAccountHolderName,rd.vAccountNumber,rd.vBankLocation,rd.vBankName,rd.vBIC_SWIFT_Code from register_driver AS rd LEFT JOIN trips AS tr ON tr.iDriverId=rd.iDriverId WHERE tr.eDriverPaymentStatus='Unsettelled' $ssql $ssql1 GROUP BY rd.iDriverId $ord LIMIT $start, $per_page";
//echo $sql;
$db_payment = $obj->MySQLSelect($sql);
$endRecord = count($db_payment);
$var_filter = "";
foreach ($_REQUEST as $key=>$val) {
    if($key != "tpages" && $key != 'page')
    $var_filter.= "&$key=".stripslashes($val);
}
$reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages.$var_filter;

for($i=0;$i<count($db_payment);$i++) {
	$db_payment[$i]['cashPayment'] = $generalobjAdmin->getAllCashCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
	$db_payment[$i]['cardPayment'] = $generalobjAdmin->getAllCardCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
	$db_payment[$i]['walletPayment'] = $generalobjAdmin->getAllWalletCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
	$db_payment[$i]['promocodePayment'] = $generalobjAdmin->getAllPromocodeCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
  $db_payment[$i]['tripoutstandingAmount'] = $generalobjAdmin->getAllOutstandingAmountCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
	if ($ENABLE_TIP_MODULE == "Yes") {
		$db_payment[$i]['tipPayment'] = $generalobjAdmin->getAllTipCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
		$db_payment[$i]['transferAmount'] = $generalobjAdmin->getTransforAmountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
	}else {
		$db_payment[$i]['transferAmount'] = $generalobjAdmin->getTransforAmountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
	}
}
	
?>
<!DOCTYPE html>
<html lang="en">

<!-- BEGIN HEAD-->
<head>
	<meta charset="UTF-8" />
    <title><?=$SITE_NAME?> | <?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Payment Report</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="keywords" />
	<meta content="" name="description" />
	<meta content="" name="author" />
    <? include_once('global_files.php');?>
     <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
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
				<div class="row">
					<div class="col-lg-12">
						<h2><?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Payment Report</h2>
					</div>
				</div>
				<hr />
				<?php include('valid_msg.php'); ?>
						<form name="frmsearch" id="frmsearch" action="javascript:void(0);" method="post" >
						<div class="Posted-date mytrip-page">
								<input type="hidden" name="action" value="search" />
								<div>
									<div style="float: left;"><h3>Search by Date...</h3></div>
									<div style="text-align: right;font-size:15px;"><a href="cancellation_payment_report.php" target="_blank" class="btn btn-primary">View Cancelled Jobs Payment Report</a></div>
								</div>
								<span>
 							<a onClick="return todayDate('dp4','dp5');"><?=$langage_lbl_admin['LBL_MYTRIP_Today']; ?></a>
								<a onClick="return yesterdayDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Yesterday']; ?></a>
								<a onClick="return currentweekDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Current_Week']; ?></a>
								<a onClick="return previousweekDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Previous_Week']; ?></a>
								<a onClick="return currentmonthDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Current_Month']; ?></a>
								<a onClick="return previousmonthDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Previous Month']; ?></a>
								<a onClick="return currentyearDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Current_Year']; ?></a>
								<a onClick="return previousyearDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Previous_Year']; ?></a>
						 	</span> 
							<!-- 	<span> --> 
                </div>
                <div class="row"><div class="col-md-3">
								<input autocomplete="off" type="text" id="dp4" name="startDate" placeholder="From Date" class="form-control" value=""/>
                </div> 
                <div class="col-md-3">
								<input autocomplete="off" type="text" id="dp5" name="endDate" placeholder="To Date" class="form-control" value="" />
</div>
								<div class="col-lg-2 select001">
                    <?php 
    $query="SELECT vCompany,iCompanyId FROM `company`"; ?>
                                    <select class="form-control" multiple name='searchCompany' data-text="Select Company" id="searchCompany">

    <?php
   $list_0f_company=$obj->MySQLSelect($query);
foreach ($list_0f_company as $key => $company) {

?>
<option value="<?php echo  $company['iCompanyId']; ?>" <?php
$Scompany=explode(',', $_REQUEST['searchCompany']);
  for ($i=0; $i <count($Scompany) ; $i++) { 


 if (trim($Scompany[$i])==$company['iCompanyId']) {
 echo 'selected';
}
} ?> ><?php echo  $company['vCompany']; ?></option>
<?php
}
     ?>

                                     <!--    <option value="">Select Company</option>
                                            <?php foreach ($db_company as $dbc) { ?>
                                            <option value="<?php echo $dbc['iCompanyId']; ?>" <?php if ($searchCompany == $dbc['iCompanyId']) {
                                                echo "selected";
                                            } ?>><?php echo $generalobjAdmin->clearCmpName($dbc['vCompany']); ?> - ( <?php echo $generalobjAdmin->clearEmail($dbc['vEmail']); ?> )</option>
                                        <?php } ?> -->
                                    </select>
                </div>
                              
 <div class="col-lg-3">
    <?php 
    $query="SELECT vName,vLastName,iDriverId,vEmail,MiddleName FROM `register_driver` as rd where $sub_Query  order by vName,MiddleName,vLastName";
     ?>
                                    <select multiple class="form-control" name='searchDriver' data-text="Select <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?>" id='searchDriver'>

                                          <?php
   $list_0f_providers=$obj->MySQLSelect($query);
foreach ($list_0f_providers as $key => $Provider) {

?>
<option value="<?php echo  $Provider['iDriverId']; ?>" <?php
$SProvider=explode(',', $_REQUEST['searchDriver']);
  for ($i=0; $i <count($SProvider) ; $i++) { 


 if (trim($SProvider[$i])==$Provider['iDriverId']) {
 echo 'selected';
}
} ?> ><?php echo  $Provider['vName']." ".$Provider['MiddleName']." ".$Provider['vLastName']." (".$Provider['vEmail'].")"; ?></option>
<?php
}
     ?>
                                       <!--  <option value="">Select <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?></option>
                                            <?php foreach ($db_drivers as $dbd) { ?>
                                            <option value="<?php echo $dbd['iDriverId']; ?>" <?php if ($searchDriver == $dbd['iDriverId']) {
                                                echo "selected";
                                            } ?>><?php echo $generalobjAdmin->clearName($dbd['driverName']); ?> - ( <?php echo $generalobjAdmin->clearEmail($dbd['vEmail']); ?> )</option>
                                        <?php } ?> -->
                                    </select>
                                </div>

                                </div>
                                <div class="tripBtns001">
                                <b>
									<input type="submit" value="Search" class="btnalt button11" id="Search" name="Search" title="Search" />
									<input type="button" value="Reset" class="btnalt button11" onClick="window.location.href = 'driver_pay_report.php'"/>
									<?php if(count($db_payment) > 0){ ?>
									<button type="button" onClick="exportlist()" class="export-btn001" >Export</button></b>
									<?php } ?>
                                </div>
<!-- 							</span>
 -->
              
							<div class="tripBtns001">
							</div>
<!-- 							</div>
 -->
						</form>

                      <form name="_list_form" id="_list_form" class="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
							<input type="hidden" id="actionpay" name="action" value="pay_driver">
							<input type="hidden" name="ePayDriver" id="ePayDriver" value="">
							<input type="hidden" name="prev_start" id="prev_start" value="<?=$startDate?>">
							<input type="hidden" name="prev_end" id="prev_end" value="<?=$endDate?>">
							<input type="hidden" name="prev_order" id="prev_order" value="<?=$order?>">
							<input type="hidden" name="prev_sortby" id="prev_sortby" value="<?=$sortby?>">
							<input type="hidden" name="prevsearchDriver" id="prevsearchDriver" value="<?=$searchDriver?>">
							<input type="hidden" name="prevsearchCompany" id="prevsearchCompany" value="<?=$searchCompany?>">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example123" >
								<thead>
									<tr>
										<!-- <th><a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)"><?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Code <?php if ($sortby == 1) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th> -->
										<th><a href="javascript:void(0);" onClick="Redirect(2,<?php if($sortby == '2'){ echo $order; }else { ?>0<?php } ?>)"><?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Name <?php if ($sortby == 2) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
										<th><a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)"><?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Account Name <?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
										<th><a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)">Bank Name <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
										<th>Account Number</th>
										<th>Sort Code</th>
										<!--<th>Total Cash Payment</th>
										<th>Total Card Payment</th>
                    <th>Tip</th>-->
                    <th><div style="text-align: CENTER;font-size: 17px;">A</div> <br/> Total Trip Commission Take From <?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']?> For Cash Trips</th>
                    <th><div style="text-align: CENTER;font-size: 17px;">B</div> <br/> Total Trip Amount Pay to <?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']?> For Card Trips</th>
										<?php if ($ENABLE_TIP_MODULE == "Yes") { ?>
										<th><div style="text-align: CENTER;font-size: 17px;">C</div> <br/> Total Tip Amount Pay to <?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']?></th>
										<?php } ?>
										<th><div style="text-align: CENTER;font-size: 17px;">D</div> <br/> Total Wallet Adjustment Amount Pay to <?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']?> For Cash Trips</th>
										<th><div style="text-align: CENTER;font-size: 17px;">E</div> <br/> Total Promocode Discount Amount Pay to <?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']?> For Cash Trips</th>
										<th><div style="text-align: CENTER;font-size: 17px;">F</div> <br/> Total Trip Outstanding Amount Take From <?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']?> For Cash Trips</th>
                    <th>Final Amount Pay to <?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']?> <br >G = B-A<?php if($APP_TYPE != 'UberX') {?>+C<?php } ?>+D+E-F</th>
										
										<th>Final Amount to take back from <?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']?> <br >H = B-A<?php if($APP_TYPE != 'UberX') {?>+C<?php } ?>+D+E-F</th>										
										<th><?=$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Payment Status</th> 
										<th></th>                            
									</tr>
								</thead>
								<tbody>
							<? if(count($db_payment) > 0){
                          	for($i=0;$i<count($db_payment);$i++) { ?>
									<tr class="gradeA">
									  <!-- <td><?=$db_payment[$i]['iDriverId'];?></td> -->
									  <td><?=$generalobjAdmin->clearName($db_payment[$i]['dname']);?></td>
									  <td><?=($db_payment[$i]['vBankAccountHolderName'] != "")?$db_payment[$i]['vBankAccountHolderName']:'---';?></td>
									  <td><?=($db_payment[$i]['vBankName'] != "")?$db_payment[$i]['vBankName']:'---';?></td>
									  <td><?=($db_payment[$i]['vAccountNumber'] != "")?$db_payment[$i]['vAccountNumber']:'---';?></td>
									  <td><?=($db_payment[$i]['vBIC_SWIFT_Code'] != "")?$db_payment[$i]['vBIC_SWIFT_Code']:'---';?></td>
									  <td style="text-align:right;"><?=$db_payment[$i]['cashPayment'];?></td>
									  <td style="text-align:right;"><?=$db_payment[$i]['cardPayment'];?></td>
									  <?php if ($ENABLE_TIP_MODULE == "Yes") { ?>
										<td style="text-align:right;"><?=$db_payment[$i]['tipPayment'];?></td>
									  <?php } ?>
									  <td style="text-align:right;"><?=$db_payment[$i]['walletPayment'];?></td>
									  <td style="text-align:right;"><?=$db_payment[$i]['promocodePayment'];?></td>
                    <td style="text-align:right;"><?=$db_payment[$i]['tripoutstandingAmount'];?></td>
									  
									  <td style="text-align:right;">
									  <?php 
										if($db_payment[$i]['transferAmount'] > 0)
										{
											echo $db_payment[$i]['transferAmount'];	
										}
										else
										{
											echo "---";
										}
									  ?>
									  </td>
									  
									  <td style="text-align:right;">
									  <?php 
										if($db_payment[$i]['transferAmount'] >= 0)
										{
											echo "---";
										}
										else
										{
											echo $db_payment[$i]['transferAmount'];	
										}
									  ?>
									  </td>
									  
									  <td ><?
						  						if($db_payment[$i]['eDriverPaymentStatus'] == "Unsettelled"){
	                                                echo "Unsettled";
	                                            }else{
	                                                echo $db_trip[$i]['eDriverPaymentStatus']; 
	                                            }?> 
	                                            <br /><a href="payment_report.php?action=search&startDate=<?=$startDate;?>&endDate=<?=$endDate;?>&searchDriver=<?=$db_payment[$i]['iDriverId'];?>&searchDriverPayment=Unsettelled" target="_blank">[View Detail]</a></td>
									  <td>
										  <? if($db_payment[$i]['eDriverPaymentStatus'] == 'Unsettelled'){ ?>
											  <input class="validate[required]" type="checkbox" value="<?=$db_payment[$i]['iDriverId']?>" id="iTripId_<?=$db_payment[$i]['iDriverId']?>" name="iDriverId[]">
										  <? } ?>
										</td>
									</tr>
							<? } ?>
                            <tr class="gradeA">
                              <td colspan="14" align="right"><div class="row">
									<span style="margin:26px 13px 0 0;">
										<a onClick="javascript:Paytodriver(); return false;" href="javascript:void(0);"><button class="btn btn-primary">Mark As Settled</button></a>
									</span>
							</div></td>
                            </tr>
                          
                          <?}else{?>
                          <tr class="gradeA">
                               <td colspan="13" style="text-align:center;"> No Payment Details Found.</td>
                          </tr>
                          <?}?>
						</tbody>
					</table>
					</form>
					<?php include('pagination_n.php'); ?>
				</div>
			</div>
        </div>
       <!--END PAGE CONTENT -->
    </div>
    <!--END MAIN WRAPPER -->
	
<form name="pageForm" id="pageForm" action="action/driver_pay_report.php" method="post" >
<input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
<input type="hidden" name="tpages" id="tpages" value="<?php echo $tpages; ?>">
<input type="hidden" name="sortby" id="sortby" value="<?php echo $sortby; ?>" >
<input type="hidden" name="order" id="order" value="<?php echo $order; ?>" >
<input type="hidden" name="startDate" value="<?php echo $startDate; ?>" >
<input type="hidden" name="endDate" value="<?php echo $endDate; ?>" >
<input type="hidden" name="method" id="method" value="" >
</form>
<? include_once('footer.php');?>
<link rel="stylesheet" href="../assets/plugins/datepicker/css/datepicker.css" />
<link rel="stylesheet" href="css/select2/select2.min.css" />
<script src="js/plugins/select2.min.js"></script>
<!-- <script src="../assets/js/jquery-ui.min.js"></script> -->
<script src="../assets/plugins/datepicker/js/bootstrap-datepicker.js"></script>
    <script>
		$('#dp4').datepicker()
		.on('changeDate', function (ev) {
			var endDate = $('#dp5').val();
			if (ev.date.valueOf() < endDate.valueOf()) {
				$('#alert').show().find('strong').text('The start date can not be greater then the end date');
			} else {
				$('#alert').hide();
				var startDate = new Date(ev.date);
				$('#startDate').text($('#dp4').data('date'));
			}
			$('#dp4').datepicker('hide');
		});
		$('#dp5').datepicker()
		.on('changeDate', function (ev) {
			var startDate = $('#dp4').val();
			if (ev.date.valueOf() < startDate.valueOf()) {
				$('#alert').show().find('strong').text('The end date can not be less then the start date');
			} else {
				$('#alert').hide();
				var endDate = new Date(ev.date);
				$('#endDate').text($('#dp5').data('date'));
			}
			$('#dp5').datepicker('hide');
		});
	
		$(document).ready(function () {
			$("#dp5").click(function(){
                 $('#dp5').datepicker('show');
                 $('#dp4').datepicker('hide');
            });

            $("#dp4").click(function(){
                 $('#dp4').datepicker('show');
                 $('#dp5').datepicker('hide');
            });

			if('<?=$startDate?>'!=''){
					$("#dp4").val('<?=$startDate?>');
					$("#dp4").datepicker('update' , '<?=$startDate?>');
			}
			if('<?=$endDate?>'!=''){
					$("#dp5").datepicker('update' , '<?= $endDate;?>');
					$("#dp5").val('<?= $endDate;?>');
			}
		});
	 
	function setRideStatus(actionStatus) {
	 window.location.href = "trip.php?type="+actionStatus;
	}
	function todayDate()
	{
	 $("#dp4").val('<?= $Today;?>');
	 $("#dp5").val('<?= $Today;?>');
	}
	function reset() {
		location.reload();
		
	}	
	function yesterdayDate()
	{
	 $("#dp4").val('<?= $Yesterday;?>');
	 $("#dp4").datepicker('update' , '<?= $Yesterday;?>');
	 $("#dp5").datepicker('update' , '<?= $Yesterday;?>');
	 $("#dp4").change();
	 $("#dp5").change();
	 $("#dp5").val('<?= $Yesterday;?>');
	}
	function currentweekDate(dt,df)
	{
	 $("#dp4").val('<?= $monday;?>');
	 $("#dp4").datepicker('update' , '<?= $monday;?>');
	 $("#dp5").datepicker('update' , '<?= $sunday;?>');
	 $("#dp5").val('<?= $sunday;?>');
	}
	function previousweekDate(dt,df)
	{
	 $("#dp4").val('<?= $Pmonday;?>');
	 $("#dp4").datepicker('update' , '<?= $Pmonday;?>');
	 $("#dp5").datepicker('update' , '<?= $Psunday;?>');
	 $("#dp5").val('<?= $Psunday;?>');
	}
	function currentmonthDate(dt,df)
	{
	 $("#dp4").val('<?= $currmonthFDate;?>');
	 $("#dp4").datepicker('update' , '<?= $currmonthFDate;?>');
	 $("#dp5").datepicker('update' , '<?= $currmonthTDate;?>');
	 $("#dp5").val('<?= $currmonthTDate;?>');
	}
	function previousmonthDate(dt,df)
	{
	 $("#dp4").val('<?= $prevmonthFDate;?>');
	 $("#dp4").datepicker('update' , '<?= $prevmonthFDate;?>');
	 $("#dp5").datepicker('update' , '<?= $prevmonthTDate;?>');
	 $("#dp5").val('<?= $prevmonthTDate;?>');
	}
	function currentyearDate(dt,df)
	{
	 $("#dp4").val('<?= $curryearFDate;?>');
	 $("#dp4").datepicker('update' , '<?= $curryearFDate;?>');
	 $("#dp5").datepicker('update' , '<?= $curryearTDate;?>');
	 $("#dp5").val('<?= $curryearTDate;?>');
	}
	function previousyearDate(dt,df)
	{
	 $("#dp4").val('<?= $prevyearFDate;?>');
	 $("#dp4").datepicker('update' , '<?= $prevyearFDate;?>');
	 $("#dp5").datepicker('update' , '<?= $prevyearTDate;?>');
	 $("#dp5").val('<?= $prevyearTDate;?>');
	}
    
    function exportlist(){
		$("#actionpay").val("export");
		$("#_list_form").attr("action","export_driver_pay_details.php");
		document._list_form.submit();
    }
	
	$("#Search").on('click', function(){
		 if($("#dp5").val() < $("#dp4").val()){
			 alert("From date should be lesser than To date.")
			 return false;
		 }else {
      debugger
              var companies = $('#searchCompany').val(); //get the current value's option
        var providers = $('#searchDriver').val(); //get the current value's option

      $("#_list_form").attr('action',"<?php echo $_SERVER['PHP_SELF'] ?>");
			var action =  $("#_list_form").attr('action');
           var sortby=$("#sortby").val();
var order=$("#order").val();
var page=$("#page").val();
var startDate=$("#dp4").val();
var endDate=$("#dp5").val();
			var formValus = $("#frmsearch").submit();
			//window.location.href = action+"?"+formValus;

post("<?php echo $_SERVER['PHP_SELF'] ?>", {action:'search',searchDriver:providers,searchCompany:companies,order:order,sortby:sortby,page:page,endDate:endDate,startDate:startDate });
		 }
	});
	$(function () {
        $("select.filter-by-text").each(function () {
            $(this).select2({
                placeholder: $(this).attr('data-text'),
                allowClear: true
            }); //theme: 'classic'
        });


          $('#searchDriver').multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"150px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Providers',
    maxHeight:300
  });

           $('#searchCompany').multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"150px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Company',
    maxHeight:300
  });

    });

 $('#searchCompany').change(function() {
      debugger
      $("#searchCompany").multiselect('updateButtonText');
        var company_id = $(this).val(); //get the current value's option
        $.ajax({
            type:'POST',
            url:'ajax_find_driver_by_multiple_company.php',
            data:{'company_id':company_id.toString()},
        cache: false,
            success:function(data){
              
                $("#searchDriver").html(data);
                bindProviders();
            }
        });
    });

  function bindProviders()
   {
          $("#searchDriver").multiselect('destroy');

   $('#searchDriver').multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"200px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Providers',
    maxHeight:300
  });

   }
  
	// $('#searchCompany').change(function() {
 //        var company_id = $(this).val(); //get the current value's option
 //        $.ajax({
 //            type:'POST',
 //            url:'ajax_find_driver_by_company.php',
 //            data:{'company_id':company_id},
 //            cache: false,
 //            success:function(data){
 //                $(".driver_container").html(data);
 //            }
 //        });
 //    });

 function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}
</script>
</body>
<!-- END BODY-->
</html>
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/removeselectall.js"></script>

<script type="text/javascript">
  window.onload = getPageLoadTime;

</script>