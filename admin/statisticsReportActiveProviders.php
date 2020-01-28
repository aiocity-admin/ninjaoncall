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
           data: {"loadtime":seconds,"beforeload":date1,"afterload":date2,"UserType":"SUPER_ADMIN","eType":"STATISTICS_ACTIVE_PROVIDERS"}, 
           success: function(data)
           {
               
           }
         });

}
</script><?php
if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$script   = "statisticsReportActiveProviders";



  //Start Sorting

  $sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : '';

  $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 0;
$iVehicleCategoryId=isset($_REQUEST['iVehicleCategoryId']) ? $_REQUEST['iVehicleCategoryId'] : "";
$providers=isset($_REQUEST['providers']) ? $_REQUEST['providers'] : "null";
$companies=isset($_REQUEST['company']) ? $_REQUEST['company'] : "null";
$list=isset($_REQUEST['list']) ? $_REQUEST['list'] : "";

 $ord = " ORDER BY  vCompany ASC,numberofdriver DESC";
    $ord2 = " ORDER BY  vCompany ASC,numberofdriver ASC";
  if($sortby == 1){
   if($order == 0)
{
    $ord = " ORDER BY numberofdriver ASC";
    $ord2 = ' ORDER BY numberofdriver ASC';
  }

    else
{
    $ord = " ORDER BY numberofdriver DESC";
    $ord2 = " ORDER BY numberofdriver DESC";

 }

  }

  

  if($sortby == 2){

   if($order == 0)
{
    $ord = " ORDER BY vCompany ASC";
    $ord2 = " ORDER BY c.vCompany ASC";

}
    else
{
    $ord = " ORDER BY vCompany DESC";
    $ord2 = " ORDER BY c.vCompany DESC";
}

  }

  if($sortby == 3){

   if($order == 0)
{
    $ord = " ORDER BY tRegistrationDate ASC";
    $ord2 = " ORDER BY rd.tRegistrationDate ASC";
}

    else
{
    $ord = " ORDER BY tRegistrationDate DESC";
    $ord2 = " ORDER BY rd.tRegistrationDate DESC";
}

  }

  if($sortby == 4){

   if($order == 0)
{
    $ord = " ORDER BY vCategory_EN ASC";
    $ord2 = " ORDER BY vCategory_EN ASC";
}

    else
    {
    $ord = " ORDER BY vCategory_EN DESC";

    $ord2 = " ORDER BY vCategory_EN DESC";
}
  }
  
  //End Sorting


 $subQuery="where rd.eStatus='Active' and (vcp.eStatus='Active' or vt.eType!='UberX' ) ";
 if (isset($_REQUEST['search'])) {


 if (trim($_REQUEST['company'])!='null')
{

 $company= explode(',', $_REQUEST['company']);
  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" c.iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";

}

 if (trim($iVehicleCategoryId)!='')
{


   if (trim($iVehicleCategoryId)!='null')
{

 $eType= explode(',', $iVehicleCategoryId);
  $subQuery_company='(';

  for ($i=0; $i <count($eType) ; $i++) { 


if(trim($eType[$i])=='Ride'||trim($eType[$i])=='Deliver')
{
$subQuery_company.=" vt.eType='".$eType[$i]."' or";

}else {

$subQuery_company.=" vcp.iVehicleCategoryId='".$eType[$i]."' or";


}
      

  //$subQuery_company.=" d.iCompanyId='$eType[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');

 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";

}




}
}



  //Pagination Start

  $per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page
  $sql ="";

  //$sql = "select count(Total) as Total from (SELECT  count(rd.iDriverId) as Total  FROM company c left join  register_driver rd on c.iCompanyId=rd.iCompanyId left join driver_vehicle dv on rd.iDriverId=dv.iDriverId left join vehicle_type vt on  dv.vCarType like concat('%',vt.iVehicleTypeId,'%') LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId $subQuery group by c.iCompanyId) t ";
if($list=="Yes")
{


 $sql="select count(numberofdriver) as Total from ( select CONCAT(rd.vName,' ',rd.MiddleName,' ',rd.vLastName) as numberofdriver, c.vCompany,c.iCompanyId,vt.iVehicleTypeId,case when vcp.vCategory_EN is NULL or vcp.vCategory_EN='' then vt.eType else vcp.vCategory_EN end as vCategory_EN,case when vcp.iVehicleCategoryId is NULL or vcp.iVehicleCategoryId='' then vt.eType else vcp.iVehicleCategoryId end as iVehicleCategoryId,rd.tRegistrationDate from company c left join  register_driver rd on c.iCompanyId=rd.iCompanyId left join driver_vehicle dv on rd.iDriverId=dv.iDriverId left join vehicle_type vt on  dv.vCarType like concat('%',vt.iVehicleTypeId,'%') LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  $subQuery group by rd.iDriverId,c.iCompanyId,vcp.iVehicleCategoryId,vt.eType   ) x   ";

}
else
{

 $sql="select count(numberofdriver) as Total from (select count(numberofdriver) as numberofdriver, vCompany,iVehicleCategoryId,vCategory_EN from ( select rd.iDriverId as numberofdriver, c.vCompany,c.iCompanyId,vt.iVehicleTypeId,case when vcp.vCategory_EN is NULL or vcp.vCategory_EN='' then vt.eType else vcp.vCategory_EN end as vCategory_EN,case when vcp.iVehicleCategoryId is NULL or vcp.iVehicleCategoryId='' then vt.eType else vcp.iVehicleCategoryId end as iVehicleCategoryId  from company c left join  register_driver rd on c.iCompanyId=rd.iCompanyId left join driver_vehicle dv on rd.iDriverId=dv.iDriverId left join vehicle_type vt on  dv.vCarType like concat('%',vt.iVehicleTypeId,'%') LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  $subQuery group by rd.iDriverId,c.iCompanyId,vcp.iVehicleCategoryId,vt.eType ) x  group by iCompanyId,iVehicleCategoryId) t";
}


//echo  $sql;

  $totalData = $obj->MySQLSelect($sql);

  $total_results = $totalData[0]['Total'];

  $total_pages = ceil($total_results / $per_page); //total pages we going to have

  $show_page = 1;

  

  //-------------if page is setcheck------------------//

  if (isset($_GET['page'])) {

    $show_page = $_GET['page'];             //it will telles the current page

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

  $page = isset($_GET['page']) ? intval($_GET['page']) : 0;

  $tpages=$total_pages;

  if ($page <= 0)

    $page = 1;

  //Pagination End

  


  //
   $sql_company_wallet="";

 //$sql_company_wallet="select count(rd.iDriverId) as numberofdriver, c.vCompany  FROM register_driver rd  right join company c on rd.iCompanyId=c.iCompanyId   $subQuery group by c.iCompanyId  $ord   LIMIT $start, $per_page";
if($list=="Yes")
{


 //$sql_company_wallet="select  numberofdriver, vCompany,iCompanyId,tRegistrationDate,iDriverId,iVehicleCategoryId,eType,vCategory_EN  from ( select CONCAT(rd.vName,' ',rd.MiddleName,' ',rd.vLastName)  as numberofdriver, c.vCompany,c.iCompanyId,vt.iVehicleTypeId,vt.eType,rd.tRegistrationDate,rd.iDriverId,vcp.iVehicleCategoryId,vcp.vCategory_EN   FROM company c left join  register_driver rd on c.iCompanyId=rd.iCompanyId left join driver_vehicle dv on rd.iDriverId=dv.iDriverId left join vehicle_type vt on  dv.vCarType like concat('%',vt.iVehicleTypeId,'%') LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  $subQuery group by rd.iDriverId  ) x    $ord   LIMIT $start, $per_page";

  $sql_company_wallet=" select CONCAT(rd.vName,' ',rd.MiddleName,' ',rd.vLastName) as numberofdriver, c.vCompany,c.iCompanyId,vt.iVehicleTypeId,case when vcp.vCategory_EN is NULL or vcp.vCategory_EN='' then vt.eType else vcp.vCategory_EN end as vCategory_EN,case when vcp.iVehicleCategoryId is NULL or vcp.iVehicleCategoryId='' then vt.eType else vcp.iVehicleCategoryId end as iVehicleCategoryId,rd.tRegistrationDate,rd.iDriverId from company c left join  register_driver rd on c.iCompanyId=rd.iCompanyId left join driver_vehicle dv on rd.iDriverId=dv.iDriverId left join vehicle_type vt on  dv.vCarType like concat('%',vt.iVehicleTypeId,'%') LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  $subQuery group by rd.iDriverId,c.iCompanyId,vcp.iVehicleCategoryId,vt.eType $ord2  LIMIT $start, $per_page";


}
else
{

 //$sql_company_wallet="select sum(numberofdriver) as numberofdriver, vCompany,iCompanyId  from (select count(numberofdriver) as numberofdriver, vCompany,iCompanyId  from ( select  rd.iDriverId as numberofdriver, c.vCompany,c.iCompanyId,vt.iVehicleTypeId,vt.eType   FROM company c left join  register_driver rd on c.iCompanyId=rd.iCompanyId left join driver_vehicle dv on rd.iDriverId=dv.iDriverId left join vehicle_type vt on  dv.vCarType like concat('%',vt.iVehicleTypeId,'%') LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  $subQuery group by rd.iDriverId  ) x  group by iCompanyId,iVehicleTypeId,eType ) xx   group by iCompanyId   $ord  LIMIT $start, $per_page";


  $sql_company_wallet="select count(numberofdriver) as numberofdriver, vCompany,iCompanyId,iVehicleCategoryId,vCategory_EN from ( select rd.iDriverId as numberofdriver, c.vCompany,c.iCompanyId,case when vcp.vCategory_EN is NULL or vcp.vCategory_EN='' then vt.eType else vcp.vCategory_EN end as vCategory_EN,case when vcp.iVehicleCategoryId is NULL or vcp.iVehicleCategoryId='' then vt.eType else vcp.iVehicleCategoryId end as iVehicleCategoryId  from company c left join  register_driver rd on c.iCompanyId=rd.iCompanyId left join driver_vehicle dv on rd.iDriverId=dv.iDriverId left join vehicle_type vt on  dv.vCarType like concat('%',vt.iVehicleTypeId,'%') LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  $subQuery group by rd.iDriverId,c.iCompanyId,vcp.iVehicleCategoryId,vt.eType ) x  group by iCompanyId,iVehicleCategoryId  $ord  LIMIT $start, $per_page";
}
   $data_drv = $obj->MySQLSelect($sql_company_wallet);


//echo  $sql_company_wallet;
  $endRecord = count($data_drv);

  $var_filter = "";

  foreach ($_REQUEST as $key=>$val) {

    if($key != "tpages" && $key != 'page')

    $var_filter.= "&$key=".stripslashes($val);

  }

  

  $reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages.$var_filter;




 //$sql_company_wallet="SELECT  t2.vCompany, `Tokens`, `Type`, `TDate`, CONCAT(t3.`vName`,' ',t3.`vLastName`) as Name, `ref_No`, `payment`, `payment_status`, `commission` FROM `barangay_tokens` t1 join company t2 on t1.BarangayId=t2.iCompanyId left outer join register_driver t3 on t1.DriverId =t3.iDriverId $subQuery $ord   LIMIT $start, $per_page";
?>


<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?=$SITE_NAME?> | Active Providers</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <?php include_once('global_files.php');?>
 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
    <style type="text/css">
      .inner
      {
     min-height: 400px !important;     
      }
button.multiselect.dropdown-toggle.btn.btn-default {
    height: 40px;
}
a{
  cursor: pointer;
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
                    <h2>Active Providers</h2>
                    <br>           <br>
                    <hr>
                   
                  </div>
 <!-- Searching  -->
<div class="row">
   
                  

  <div class="col-md-4" >
      <div class="company-container" >

    <?php 
    $query="SELECT vCompany,iCompanyId FROM `company`"; ?>
  <select id="company" name="company" class="form-control multiselect" multiple="multiple" size="10" >
    <?php
   $list_0f_company=$obj->MySQLSelect($query);
foreach ($list_0f_company as $key => $company) {

?>
<option value="<?php echo  $company['iCompanyId']; ?>" <?php
$Scompany=explode(',', $_REQUEST['company']);
  for ($i=0; $i <count($Scompany) ; $i++) { 


 if (trim($Scompany[$i])==$company['iCompanyId']) {
 echo 'selected';
}
} ?> ><?php echo  $company['vCompany']; ?></option>
<?php
}
     ?>
  </select>
</div>
 

 </div>

  <div class="col-md-4">

  <select  class="form-control miltiselect" data-text="Service Category" name='iVehicleCategoryId' id="iVehicleCategoryId" multiple="multiple" >
                                       

                                                <option value='Ride'   <?php
$Scompany= $eType;

  for ($i=0; $i <count($Scompany) ; $i++) { 


 if (trim($Scompany[$i])=='Ride') {
 echo 'selected';
}
}?> >Ride</option>
                                                <option value='Deliver'  <?php
  for ($i=0; $i <count($Scompany) ; $i++) { 


 if (trim($Scompany[$i])=='Deliver') {
 echo 'selected';
}
}?> >Delivery</option>

      <?
 // $vehicle_type_sql1 = "SELECT vt.*,vc.*,lm.vLocationName FROM vehicle_type AS vt LEFT JOIN vehicle_category AS vc ON vt.iVehicleCategoryId = vc.iVehicleCategoryId LEFT JOIN location_master AS lm ON lm.iLocationId = vt.iLocationid WHERE vt.eType='UberX' AND  vc.eStatus='Active' order by vt.vVehicleType_EN";
       $vehicle_type_sql1 = "SELECT * FROM  vehicle_category  WHERE    eStatus='Active'  and `iParentId`=0 order by vCategory_EN";
          $vehicle_type= $obj->MySQLSelect($vehicle_type_sql1);
//$Scompany=explode(',', $eType);

        foreach ($vehicle_type as $subkey => $subvalue) {
$isSelected="";

  for ($i=0; $i <count($Scompany) ; $i++) { 


 if (trim($Scompany[$i])==$subvalue['iVehicleCategoryId']) {
           $isSelected="selected";
}
}


           echo "<option value='".$subvalue['iVehicleCategoryId']."' $isSelected>".$subvalue['vCategory_EN']."</option>";

}         

       ?>
                                               
                      </select>

                              
                    </div>
  <div class="col-md-4" >
  <input type="button" name="search" id="search"  class="btn button11" value="Search">
    <input type="button" name="reset" id="reset"  class="btn button11" value="Reset">
    <?php if($endRecord>0) { ?>
     <input type="button" name="export" id="export" value="Export" class="btn button11"/>
 <?php   }?>
 
 </div>



</div>
  <!-- End Searching  -->
                  <!-- Details of provider wallet-->
                  <div class="row">
                    <div class="col-lg-12" style="overflow-x: auto;">
<br><br>
                      <div class="trips-table trips-table-driver trips-table-driver-res"> 
              <div class="trips-table-inner">
                  <form class="_list_form" id="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
   <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="1"  id="dataTables-example" aria-describedby="dataTables-example_info">
                                                <thead>
                                                  <tr>
                                                    <?php if($list=="Yes") {




?>
   <th>  <a href="javascript:void(0);" onClick="Redirect(2,<?php if($sortby == '2'){ echo $order; }else { ?>0<?php } ?>)">Company <?php if ($sortby == 2) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                    </th>
 <th>  <a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)"> Provider <?php if ($sortby == 1) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
     
                                              
  <th>  <a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)">Service Category <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                    </th>
                                                          <th>  <a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)">Sign Up Date <?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                    </th>

<?

                                                    } else { ?>
    <th>  <a href="javascript:void(0);" onClick="Redirect(2,<?php if($sortby == '2'){ echo $order; }else { ?>0<?php } ?>)">Company <?php if ($sortby == 2 ||$sortby == '') { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                    </th>

                                                    <th>  <a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)">Number of Providers <?php if ($sortby == 1 ||$sortby == '') { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
     
                                             

                                                      <th>  <a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)">Service Category <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                    </th>
                                                  <? } ?>

                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php
$data=$obj->MySQLSelect($sql_company_wallet);
if(count($data)>0)
{
foreach ($data as $key => $value) {
  
$VehicleCategory= trim($value['iVehicleCategoryId'])==""?$value['eType']:$value['iVehicleCategoryId'];

                                                  ?>
                                                  <tr>
                                                                                                      <td><?=$value['vCompany'];?></td>

                                                       <?php if($list!="Yes") { ?>
                                                 <td><a onclick="window.open('statisticsReportActiveProviders.php?search=1&list=Yes&company=<?=$value['iCompanyId'];?>&keyword=<?=$value['vCompany'];?>&eStatus=Active&iVehicleCategoryId=<?=$VehicleCategory;?>','_blank')"><?=$value['numberofdriver'];?></a> </td>
<? } else {
?>
  <td><a onclick="window.open('driver_action.php?id=<?=$value['iDriverId'];?>','_blank')"><?=$value['numberofdriver'];?></a> </td>
<?

} ?>

                                                 

                                                   <td><?

$cat= trim($value['vCategory_EN'])==""?$value['eType']:$value['vCategory_EN'];


                                                  echo  $cat=='Deliver'?'Delivery':$cat;?></td>

                                                  <?php if($list=="Yes") { ?>

                                                   <td>3<?=$value['tRegistrationDate'];?></td>

                                                  <? } ?>
                                                                                                  
                                                  
                                                  </tr>
<?php } }else {

  ?>
                                                  <td colspan="3">No Records Found.</td>


<? } ?>
                                                </tbody>
                                              </table>

                                            </form>
                <?php include('pagination_n.php'); ?>

                                            </div>
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

<?php  if (isset($_REQUEST['search'])) {
 ?>
       <input type="hidden" name="search" id="search" value="1" >
     <? } ?>


    <input type="hidden" name="iVehicleCategoryId" id="iVehicleCategoryId_frm" value="<?php echo $iVehicleCategoryId; ?>" >

    <input type="hidden" name="company" id="company_frm" value="<?php echo $companies; ?>" >

    <input type="hidden" name="sortby" id="sortby" value="<?php echo $sortby; ?>" >

    <input type="hidden" name="order" id="order" value="<?php echo $order; ?>" >


    <input type="hidden" name="list" id="list" value="<?php echo $list; ?>" >

  </form>
                    
    <?php
    include_once('footer.php');   

    ?>
<!-- <script src="../assets/js/jquery-ui.min.js"></script>
<script src="../assets/plugins/dataTables/jquery.dataTables.js"></script> -->

  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $tconfig["tsite_url_main_admin"]?>css/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/select2/select2.min.css" />
  <script src="js/plugins/select2.min.js"></script>
    </body>
  
   
 <script type="text/javascript">
   $(document).ready(function(){

      $("#fromDate").datetimepicker({format: 'YYYY-MM-DD'});
      $("#toDate").datetimepicker({format: 'YYYY-MM-DD'});


//Sending request for searching
$("#search").click(function(){

var provider=$("#providers").val();
var company=$("#company").val();
var iVehicleCategoryId=$("#iVehicleCategoryId").val();
var list=$("#list").val();

post('statisticsReportActiveProviders.php', {search:1,providers:provider,company:company,iVehicleCategoryId:iVehicleCategoryId,list:list });

//window.location.href="statisticsReportActiveProviders.php?search=1&providers="+provider+"&company="+company+"&iVehicleCategoryId="+iVehicleCategoryId;


});

//Sending request for export

$("#export").click(function(){

var provider=$("#providers").val();
var company=$("#company").val();
var iVehicleCategoryId=$("#iVehicleCategoryId").val();
var list=$("#list").val();
post('ExportStatisticsReport/exportActiveProviders.php', {search:1,providers:provider,company:company,iVehicleCategoryId:iVehicleCategoryId,list:list });


});


 /*   $('#dataTables-example').dataTable({
        fixedHeader: {
          footer: true
        },
        "order": [],
        "aaSorting": []});
*/
//reset page
$("#reset").click(function(){

  window.location.href="statisticsReportActiveProviders.php";
});

    

$("#company").multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"275px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Company',
    maxHeight:300
  });

 //  $("#company").multiselect('selectAll', false);
  //  $("#company").multiselect('updateButtonText');

      $("#iVehicleCategoryId").multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"275px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Service Category',
    maxHeight:400
  });
    $("select.filter-by-text").each(function(){
        $(this).select2({
          placeholder: $(this).attr('data-text'),
          allowClear: true
        }); //theme: 'classic'
      });
   });
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
</html>
    <script type="text/javascript" src="<?php echo $tconfig["tsite_url_main_admin"]?>js/removeselectall.js"></script>

<script type="text/javascript">
  window.onload = getPageLoadTime;

</script>