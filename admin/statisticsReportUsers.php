<?php
include_once('../common.php');?><script type="text/javascript">
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
           data: {"loadtime":seconds,"beforeload":date1,"afterload":date2,"UserType":"SUPER_ADMIN","eType":"STATISTICS_USERS"}, 
           success: function(data)
           {
               
           }
         });

}
</script>
<?php
if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$default_lang 	= $generalobj->get_default_lang();
$script = 'statisticsReportUsers';

$rdr_ssql = "";
if (SITE_TYPE == 'Demo') {
    $rdr_ssql = " And tRegistrationDate > '" . WEEK_DATE . "'";
}

//data for select fields
$sql = "select iCompanyId,vCompany from company WHERE eStatus != 'Deleted' $rdr_ssql";
$db_company = $obj->MySQLSelect($sql);

$sql = "select iDriverId,CONCAT(vName,' ',MiddleName,' ',vLastName) AS driverName from register_driver WHERE eStatus != 'Deleted' $rdr_ssql";
$db_drivers = $obj->MySQLSelect($sql);

$sql = "select iUserId,CONCAT(vName,' ',MiddleName,' ',vLastName) AS riderName from register_user WHERE eStatus != 'Deleted' $rdr_ssql";
$db_rider = $obj->MySQLSelect($sql);
//data for select fields

//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';

$list = isset($_REQUEST['list']) ? $_REQUEST['list'] : '';

$typeofreport=isset($_REQUEST['typeofreport']) ? $_REQUEST['typeofreport'] : 'Number of Registered Users';

$promocode = isset($_REQUEST['promocode']) ? $_REQUEST['promocode'] : '';
$ord = '';
$SELECT=" COUNT(distinct u.iUserId) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,c.iCompanyId ";

$groupby ="group by c.iCompanyId ";
if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY vVehicleType ASC";
  else
  $ord = " ORDER BY vVehicleType DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY riderName ASC";
  else
  $ord = " ORDER BY riderName DESC";
}

if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY c.vCompany ASC";
  else
  $ord = " ORDER BY c.vCompany DESC";
}


//End Sorting


// Start Search Parameters
$ssql='';
$action = isset($_REQUEST['action']) ? $_REQUEST['action']: '';
$searchCompany = isset($_REQUEST['searchCompany']) ? $_REQUEST['searchCompany'] : '';
$searchDriver = isset($_REQUEST['searchDriver']) ? $_REQUEST['searchDriver'] : '';
$searchRider = isset($_REQUEST['searchRider']) ? $_REQUEST['searchRider'] : '';
$serachTripNo = isset($_REQUEST['serachTripNo']) ? $_REQUEST['serachTripNo'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
$vStatus = isset($_REQUEST['vStatus']) ? $_REQUEST['vStatus'] : '';
$eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';

 if (trim($searchCompany)!='null')
{

 $company= explode(',', $searchCompany);
}
$iVehicleTypeId = isset($_REQUEST['iVehicleTypeId']) ? $_REQUEST['iVehicleTypeId'] : '';

if($startDate!=''){
	$ssql.=" AND Date(t.tTripRequestDate) >='".$startDate."'";
}
if($endDate!=''){
	$ssql.=" AND Date(t.tTripRequestDate) <='".$endDate."'";
}
if($serachTripNo!=''){
	$ssql.=" AND t.vRideNo ='".$serachTripNo."'";
}
if($searchCompany!=''){
  if (trim($typeofreport)!="Number of First Time Users" && trim($typeofreport)!="Number of Active Users")
  {

 if (trim($searchCompany)!='null')
{

  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" d.iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery=" and $subQuery_company";

}
$ssql.=$subQuery;
	//$ssql.=" AND d.iCompanyId ='".$searchCompany."'";
}

}
if($searchDriver!=''){
	$ssql.=" AND t.iDriverId ='".$searchDriver."'";
}
if($searchRider!=''){
	$ssql.=" AND t.iUserId ='".$searchRider."'";
}
if($vStatus == "onRide") {
	$ssql .= " AND (t.iActive = 'On Going Trip' OR t.iActive = 'Active') AND t.eCancelled='No'";
}else if($vStatus == "cancel") {
	$ssql .= " AND (t.iActive = 'Canceled' OR t.eCancelled='yes')";
}else if($vStatus == "complete") {
	$ssql .= " AND t.iActive = 'Finished' AND t.eCancelled='No'";
}

if($eType!=''){

	if($eType == 'Ride'){
		$ssql.=" AND t.eType ='".$eType."' AND t.iRentalPackageId = 0 AND t.eHailTrip = 'No' ";
	} elseif($eType == 'RentalRide'){
		$ssql.=" AND t.eType ='Ride' AND t.iRentalPackageId > 0";
	} elseif($eType == 'HailRide'){
		$ssql.=" AND t.eType ='Ride' AND t.eHailTrip = 'Yes'";
	} else {
		$ssql.=" AND t.eType ='".$eType."' ";
	}
}

if(!empty($promocode) && isset($promocode)) {
	$ssql .= " AND t.vCouponCode LIKE '".$promocode."' AND t.iActive !='Canceled'";
}
$trp_ssql = "";
if(SITE_TYPE =='Demo'){
	$trp_ssql = " And t.tTripRequestDate > '".WEEK_DATE."'";
}




//Pagination Start
$per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page


if(trim($typeofreport)=="Number of Active Users")
{


	$groupby =" group by u.iUserId ";


if($list=='Yes')
{

      if($searchCompany!=''){

 if (trim($searchCompany)!='null')
{

  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" d.iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery=" and $subQuery_company";

}

  $sub_sql=$subQuery;//" and  d.iCompanyId ='".$searchCompany."'";

}
$sql = "SELECT count(Total) as Total from (SELECT COUNT(distinct u.iUserId) AS Total  FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1  and u.eStatus='Active'  $ssql $trp_ssql  $sub_sql $groupby) t ";
}
else{


      if($searchCompany!=''){

 if (trim($searchCompany)!='null')
{

  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
 $sub_sql=" and $subQuery_company";

}



}

$sql = "SELECT count(Total) as Total from (SELECT COUNT(distinct u.iUserId) AS Total  FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1  and u.eStatus='Active'  $ssql $trp_ssql group by c.iCompanyId) t where 1=1  $sub_sql";
}
}
else if(trim($typeofreport)=="Number of Services by Category")
{

if($list=='Yes')
{
     

  $SELECT="t.iTripId AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vcp.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId";

  $groupby ='';//" group by iVehicleCategoryId,iCompanyId ";

if($iVehicleTypeId!="")
  $sub_query=" and iVehicleCategoryId='$iVehicleTypeId'";


if($iVehicleTypeId=="Ride" || $iVehicleTypeId=="Deliver")
  $sub_query=" and eType='$iVehicleTypeId'";


  $sql="select  count(riderName) as Total  from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql  $ord  ) xx WHERE 1=1  $sub_query $groupby";

}
else {


$SELECT="t.iTripId AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vcp.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId";

  $groupby =" group by iVehicleCategoryId,iCompanyId,eType ";


  $sql="SELECT count(riderName) as Total from (select count(riderName) as riderName,vCompany, vVehicleType,vCategory_EN,eType,iCompanyId,iVehicleTypeId,iVehicleCategoryId  from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql  $ord  ) xx $groupby ) finaltable";







}
}
else if(trim($typeofreport)=="Number of Services Requested")
{

if( $list=='Yes'&& $iVehicleTypeId!="")
{


  $SELECT=" u.iUserId as Total";

 $join="left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId ";

  $groupby =" group by u.iUserId,c.iCompanyId,vcp.iVehicleCategoryId ";
$sub_query="";
if($iVehicleTypeId!="")
  $sub_query=" and vcp.iVehicleCategoryId='$iVehicleTypeId'";


if($iVehicleTypeId=="Ride" || $iVehicleTypeId=="Deliver")
  $sub_query=" and t.eType='$iVehicleTypeId'";

if($iVehicleTypeId=="Ride" || $iVehicleTypeId=="Deliver")
{
  $join="";

 
     $groupby =" group by u.iUserId,t.eType ";

}

  $sql="select count(Total) as Total from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId right JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId  $join WHERE 1=1 $ssql $trp_ssql $sub_query  $groupby ) x  ";

}


else if($list=='Yes')
{



  $SELECT="count(u.iUserId) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vcp.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId";

  $groupby =" group by iVehicleCategoryId,iCompanyId,eType ";

/*if($iVehicleTypeId!="")
  $sub_query=" and iVehicleCategoryId='$iVehicleTypeId'";


if($iVehicleTypeId=="Ride" || $iVehicleTypeId=="Deliver")
  $sub_query=" and eType='$iVehicleTypeId'";*/

  $sql="select count(riderName) as Total from ( select count(riderName) as riderName  from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql group by u.iUserId,c.iCompanyId,vcp.iVehicleCategoryId,t.eType  ) xx $groupby) x";


}
else {

//  $SELECT="count(u.iUserId) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vcp.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId";

 // $groupby =" group by iVehicleCategoryId,iCompanyId,eType ";

 // $sql="select count(riderName) as Total from (select sum(riderName) as riderName  from ( select count(riderName) as riderName,vCompany, vVehicleType,vCategory_EN,eType,iCompanyId,iVehicleTypeId,iVehicleCategoryId  from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql group by u.iUserId,c.iCompanyId,vcp.iVehicleCategoryId,t.eType $ord  ) xx $groupby ) xxx group by iCompanyId ) x ";

    $sql="select count(vCategory_EN) as Total from (select sum(noofservice) as vCategory_EN,count(1) as riderName,vCompany,iCompanyId from (select count(1) as noofservice, riderName,vCompany,iVehicleCategoryId,vCategory_EN,iCompanyId from (SELECT u.iUserId AS riderName,c.vCompany ,case when vcp.iVehicleCategoryId is NULL then t.eType else vcp.iVehicleCategoryId end as iVehicleCategoryId,case when vcp.vCategory_EN is NULL or vcp.vCategory_EN='' then t.eType else vcp.vCategory_EN end as vCategory_EN,c.iCompanyId FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql group by u.iUserId,c.iCompanyId,vcp.iVehicleCategoryId,t.eType  ) xx GROUP by riderName,iCompanyId  ) xxx  GROUP by noofservice,iCompanyId) finaltable";

}

}
else if (trim($typeofreport)=="Number of First Time Users") {



	 $SELECT=" COUNT(distinct u.iUserId) AS ridercount,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vc.vCategory_EN,t.eType,CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName,u.iUserId,c.iCompanyId ";

  $groupby =" group by vt.iVehicleTypeId ";

  if($searchCompany!=''){

 if (trim($searchCompany)!='null')
{

  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.=" iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery=" and $subQuery_company";

}

  $sub_sql=$subQuery;//" AND iCompanyId ='".$searchCompany."'";

}

if($list=='Yes')
{
$sql="select count(riderName) as Total from (select count(vVehicleType) as vVehicleTypeCount, riderName,vCompany,vVehicleType,vCategory_EN,eType,iUserId,iCompanyId from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1 $ssql $trp_ssql $groupby) t where ridercount=1  group by iUserId) tt where vVehicleTypeCount=1 $sub_sql";
}
else{

  $sql="select count(riderName) as Total from (select  count(iUserId) as riderName,vCompany,vVehicleType,vCategory_EN,eType,iCompanyId from (select count(vVehicleType) as vVehicleTypeCount, riderName,vCompany,vVehicleType,vCategory_EN,eType,iUserId,iCompanyId from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1 $ssql $trp_ssql $groupby) t where ridercount=1  group by iUserId) tt where vVehicleTypeCount=1 $sub_sql  GROUP by iCompanyId ) finaltable ";
}

}
else {

$ssql="where 1=1";
if($startDate!=''){
  $ssql.=" AND Date(tRegistrationDate) >='".$startDate."'";
}
if($endDate!=''){
  $ssql.=" AND Date(tRegistrationDate) <='".$endDate."'";
}

  $sql="SELECT  count(iUserId) as Total FROM `register_user` $ssql";


}
//echo $sql;

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

if(trim($typeofreport)=="Number of Active Users")
{
$SELECT="u.iUserId,t.tStartDate ,t.tEndDate, t.tTripRequestDate,t.vCancelReason,t.vCancelComment,t.eHailTrip,t.iFare,t.eType,d.iDriverId, t.tSaddress,t.vRideNo,t.tDaddress, t.fWalletDebit,t.eCarType,t.iActive, t.fCancellationFare,t.eCancelled, t.iRentalPackageId ,CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName,CONCAT(d.vName,' ',d.MiddleName,' ',d.vLastName) AS driverName, d.vAvgRating, c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,t.iTripId,c.iCompanyId";

	$groupby =" group by u.iUserId ";


  if($searchCompany!=''){

 if (trim($searchCompany)!='null')
{

  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.="  c.iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery=" and $subQuery_company";
$sub_sql=$subQuery;
}

 // $sub_sql=" and  C.iCompanyId ='".$searchCompany."'";

}

if($list=='Yes')
{
$SELECT="SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1 and u.eStatus='Active' $ssql $trp_ssql $sub_sql $groupby  $ord LIMIT $start, $per_page";
}
else{

/*if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY vVehicleType ASC";
  else
  $ord = " ORDER BY vVehicleType DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY riderName ASC";
  else
  $ord = " ORDER BY riderName DESC";
}

if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY vCompany ASC";
  else
  $ord = " ORDER BY vCompany DESC";
}*/



//$SELECT = "SELECT count(riderName) as riderName,vCompany,iCompanyId from () xx where 1=1  $sub_sql";

$SELECT="SELECT COUNT(distinct u.iUserId) AS riderName,t.tStartDate ,t.tEndDate, t.tTripRequestDate,t.vCancelReason,t.vCancelComment,t.eHailTrip,t.iFare,t.eType,d.iDriverId, t.tSaddress,t.vRideNo,t.tDaddress, t.fWalletDebit,t.eCarType,t.iActive, t.fCancellationFare,t.eCancelled, t.iRentalPackageId, d.vAvgRating, c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,t.iTripId,c.iCompanyId  FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1 and u.eStatus='Active' $ssql $trp_ssql  $sub_sql group by c.iCompanyId  $ord LIMIT $start, $per_page";

	//$SELECT="select count(iUserId) as riderName,vCompany,iCompanyId from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1 $ssql $trp_ssql $groupby   LIMIT $start, $per_page ) x where 1=1 $sub_sql group by iCompanyId $ord ";



}


}
else if(trim($typeofreport)=="Number of Services by Category")
{
  $ord = " ORDER BY vCompany ASC";

if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY vCategory_EN ASC";
  else
  $ord = " ORDER BY vCategory_EN DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY riderName ASC";
  else
  $ord = " ORDER BY riderName DESC";
}

if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY vCompany ASC";
  else
  $ord = " ORDER BY vCompany DESC";
}

if($list=='Yes')
{

   // $SELECT=" CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vc.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId";

  $SELECT=" CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vcp.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId,u.iUserId";

  $groupby ='';//" group by iVehicleCategoryId,iCompanyId ";

if($iVehicleTypeId!="")
  $sub_query=" and iVehicleCategoryId='$iVehicleTypeId'";


if($iVehicleTypeId=="Ride" || $iVehicleTypeId=="Deliver")
  $sub_query=" and eType='$iVehicleTypeId'";

  //$SELECT="SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql $sub_query   $groupby $ord LIMIT $start, $per_page";
  $SELECT="select  riderName,vCompany, vVehicleType,vCategory_EN,eType,iCompanyId,iVehicleTypeId,iVehicleCategoryId,iUserId  from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql   ) xx WHERE 1=1  $sub_query $groupby $ord  LIMIT $start, $per_page";

}
else {

	$SELECT="t.iTripId AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vcp.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId";

	$groupby =" group by iVehicleCategoryId,iCompanyId,eType ";


	$SELECT="select count(riderName) as riderName,vCompany, vVehicleType,vCategory_EN,eType,iCompanyId,iVehicleTypeId,iVehicleCategoryId  from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql ) xx $groupby  $ord   LIMIT $start, $per_page";

}
}
else if(trim($typeofreport)=="Number of Services Requested")
{
  $ord=" ORDER BY vCompany ASC,vCategory_EN asc ";
if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY vCategory_EN ASC";
  else
  $ord = " ORDER BY vCategory_EN DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY riderName ASC";
  else
  $ord = " ORDER BY riderName DESC";
}

if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY vCompany ASC";
  else
  $ord = " ORDER BY vCompany DESC";
}

if( $list=='Yes'&& $iVehicleTypeId!="")
{


  $SELECT=" CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vcp.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId,u.iUserId";

 $join="left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId ";

  $groupby =" group by u.iUserId,c.iCompanyId,vcp.iVehicleCategoryId ";
$sub_query="";
if($iVehicleTypeId!="")
  $sub_query=" and vcp.iVehicleCategoryId='$iVehicleTypeId'";


if($iVehicleTypeId=="Ride" || $iVehicleTypeId=="Deliver")
  $sub_query=" and t.eType='$iVehicleTypeId'";

if($iVehicleTypeId=="Ride" || $iVehicleTypeId=="Deliver")
{
  $join="";
if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY t.eType ASC";
  else
  $ord = " ORDER BY t.eType DESC";
}
   $SELECT=" CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,t.eType as vCategory_EN,c.iCompanyId,u.iUserId";
     $groupby =" group by u.iUserId,t.eType ";

}

  $SELECT="SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId right JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId  $join WHERE 1=1 $ssql $trp_ssql $sub_query  $groupby  $ord  LIMIT $start, $per_page  ";

}


else if($list=='Yes')
{



  $SELECT="count(u.iUserId) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vcp.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId";

  $groupby =" group by iVehicleCategoryId,iCompanyId,eType ";



  $SELECT="select count(riderName) as riderName,vCompany, vVehicleType,vCategory_EN,eType,iCompanyId,iVehicleTypeId,iVehicleCategoryId  from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql group by u.iUserId,c.iCompanyId,vcp.iVehicleCategoryId,t.eType   ) xx  $groupby $ord LIMIT $start, $per_page ";


}
else {

 // $SELECT="count(u.iUserId) AS riderName,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vcp.vCategory_EN,t.eType,c.iCompanyId,vt.iVehicleTypeId,vcp.iVehicleCategoryId";

  //$groupby =" group by iVehicleCategoryId,iCompanyId,eType ";



//  $SELECT="select sum(riderName) as riderName,vCompany, vVehicleType,eType,iCompanyId,count(1) as vCategory_EN,iVehicleCategoryId  from ( select count(riderName) as riderName,vCompany, vVehicleType,vCategory_EN,eType,iCompanyId,iVehicleTypeId,iVehicleCategoryId  from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql group by u.iUserId,c.iCompanyId,vcp.iVehicleCategoryId,t.eType  ) xx  $groupby ) xxx   group by iCompanyId $ord LIMIT $start, $per_page ";


  $SELECT="select sum(noofservice) as vCategory_EN,count(1) as riderName,vCompany,iCompanyId from (select count(1) as noofservice, riderName,vCompany,iVehicleCategoryId,vCategory_EN,iCompanyId from (SELECT u.iUserId AS riderName,c.vCompany ,case when vcp.iVehicleCategoryId is NULL then t.eType else vcp.iVehicleCategoryId end as iVehicleCategoryId,case when vcp.vCategory_EN is NULL or vcp.vCategory_EN='' then t.eType else vcp.vCategory_EN end as vCategory_EN,c.iCompanyId FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId WHERE 1=1 $ssql $trp_ssql group by u.iUserId,c.iCompanyId,vcp.iVehicleCategoryId,t.eType  ) xx GROUP by riderName,iCompanyId  ) xxx  GROUP by noofservice,iCompanyId $ord LIMIT $start, $per_page ";


}


}
else if (trim($typeofreport)=="Number of First Time Users") {

if($sortby == 3){
  if($order == 0)
  $ord = " ORDER BY vVehicleType ASC";
  else
  $ord = " ORDER BY vVehicleType DESC";
}

if($sortby == 2){
  if($order == 0)
  $ord = " ORDER BY riderName ASC";
  else
  $ord = " ORDER BY riderName DESC";
}

if($sortby == 1){
  if($order == 0)
  $ord = " ORDER BY vCompany ASC";
  else
  $ord = " ORDER BY vCompany DESC";
}



  if($searchCompany!=''){

 if (trim($searchCompany)!='null')
{

  $subQuery_company='(';
  for ($i=0; $i <count($company) ; $i++) { 
  $subQuery_company.="  iCompanyId='$company[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');
 $subQuery_company.=")";
$subQuery=" and $subQuery_company";
$sub_sql=$subQuery;
}

  //$sub_sql=" AND iCompanyId ='".$searchCompany."'";

}

	$SELECT=" COUNT(distinct u.iUserId) AS ridercount,c.vCompany, vt.vVehicleType_".$default_lang." as vVehicleType,vc.vCategory_EN,t.eType,CONCAT(u.vName,' ',u.MiddleName,' ',u.vLastName) AS riderName,u.iUserId,c.iCompanyId ";

	$groupby =" group by vt.iVehicleTypeId ";

if($list=='Yes')
{
$SELECT="select * from (select count(vVehicleType) as vVehicleTypeCount, riderName,vCompany,vVehicleType,vCategory_EN,eType,iUserId,iCompanyId from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1 $ssql $trp_ssql $groupby) t where ridercount=1  group by iUserId) tt where vVehicleTypeCount=1 $sub_sql $ord  LIMIT $start, $per_page";
}
else{


  //$sql="select count(riderName) as Total from (select  count(iUserId) as riderName,vCompany,vVehicleType,vCategory_EN,eType,iCompanyId from (select count(vVehicleType) as vVehicleTypeCount, riderName,vCompany,vVehicleType,vCategory_EN,eType,iUserId,iCompanyId from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1 $ssql $trp_ssql $groupby) t where ridercount=1  group by iUserId) tt where vVehicleTypeCount=1 $sub_sql  GROUP by iCompanyId ) finaltable ";

  $SELECT="select  count(iUserId) as riderName,vCompany,vVehicleType,vCategory_EN,eType,iCompanyId from (select count(vVehicleType) as vVehicleTypeCount, riderName,vCompany,vVehicleType,vCategory_EN,eType,iUserId,iCompanyId from (SELECT $SELECT FROM trips t LEFT JOIN register_driver d ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId LEFT JOIN company c ON c.iCompanyId=d.iCompanyId left join vehicle_category vc on vt.iVehicleCategoryId=vc.iVehicleCategoryId WHERE 1=1 $ssql  $trp_ssql $groupby  ) t where ridercount=1  group by iUserId) tt where vVehicleTypeCount=1  $sub_sql   GROUP by iCompanyId  $ord LIMIT $start, $per_page";
}
}
else {






$ssql="where 1=1";
if($startDate!=''){
  $ssql.=" AND Date(tRegistrationDate) >='".$startDate."'";
}
if($endDate!=''){
  $ssql.=" AND Date(tRegistrationDate) <='".$endDate."'";
}

if($sortby == 4){
  if($order == 0)
  $ord = " ORDER BY vEmail ASC";
  else
  $ord = " ORDER BY vEmail DESC";
}

  $SELECT="SELECT  CONCAT(vName,' ',MiddleName,' ',vLastName) AS riderName,iUserId,vEmail FROM `register_user` $ssql $ord LIMIT $start, $per_page";


}

$sql = $SELECT;
//echo $sql;
$db_trip = $obj->MySQLSelect($sql);

$endRecord = count($db_trip);
$var_filter = "";
foreach ($_REQUEST as $key=>$val) {
    if($key != "tpages" && $key != 'page')
    $var_filter.= "&$key=".stripslashes($val);
}

$reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages.$var_filter;
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
?>
<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?=$SITE_NAME?> | Users</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <?php include_once('global_files.php');?>
        <style type="text/css">
          select.form-control {
    height: 46px !important;
}
        </style>
     
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
                                <h2> Users </h2>

<?

if ($list=="Yes") {

  ?>
  <input type="button" name="" value="Close" style=" " class="btn btn-info float-right" onclick="window.top.close();">
  <?
  # code...
}
?>

                            </div>
                        </div>
                        <hr />
                    </div>
                    <?php include('valid_msg.php'); ?>
					<form name="frmsearch" id="frmsearch" action="javascript:void(0);" method="post" >
						<div class="Posted-date mytrip-page">
							<input type="hidden" name="action" value="search" />
							<h3>Search ...</h3>
							<span>
							<a style="cursor:pointer" onClick="return todayDate('dp4','dp5');"><?=$langage_lbl_admin['LBL_MYTRIP_Today']; ?></a>
							<a style="cursor:pointer" onClick="return yesterdayDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Yesterday']; ?></a>
							<a style="cursor:pointer" onClick="return currentweekDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Current_Week']; ?></a>
							<a style="cursor:pointer" onClick="return previousweekDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Previous_Week']; ?></a>
							<a style="cursor:pointer" onClick="return currentmonthDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Current_Month']; ?></a>
							<a style="cursor:pointer" onClick="return previousmonthDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Previous Month']; ?></a>
							<a style="cursor:pointer" onClick="return currentyearDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Current_Year']; ?></a>
							<a style="cursor:pointer" onClick="return previousyearDate('dFDate','dTDate');"><?=$langage_lbl_admin['LBL_MYTRIP_Previous_Year']; ?></a>
							</span> 

							<span>
								<br>
								<div class="col-lg-3 select001">
								  <select class="form-control " name = 'typeofreport' id="typeofreport" >
									   <option value="Number of Registered Users" <?php if($typeofreport == "Number of Registered Users") { echo "selected"; } ?>> Number of Registration</option>
									   	<option value="Number of Active Users" <?php if($typeofreport == "Number of Active Users") { echo "selected"; } ?>>Number of Active Users</option>

									   <option value="Number of First Time Users" <?php if($typeofreport == "Number of First Time Users") { echo "selected"; } ?>>Number of First Time Users</option>
									   <option value="Number of Services by Category" <?php if($typeofreport == "Number of Services by Category") { echo "selected"; } ?>>Number of Services by Category</option>
                        <option value="Number of Services Requested" <?php if($typeofreport == "Number of Services Requested") { echo "selected"; } ?>>Number of Services Requested</option>
								  </select>
							</div>
							<input type="text" id="dp4" name="startDate" placeholder="From Date" class="form-control" value="" readonly=""style="cursor:default; background-color: #fff" />
							<input type="text" id="dp5" name="endDate" placeholder="To Date" class="form-control" value="" readonly="" style="cursor:default; background-color: #fff"/>
						
					
							</span>
						</div>
              <div class="col-lg-3 select001" id="company_container">
              <select class="form-control " multiple="multiple" name = 'searchCompany' id="searchCompany" data-text="Select Company">
                 <?php foreach($db_company as $dbc){ 
                  $selected='';
$Scompany= $company;
  for ($i=0; $i <count($Scompany) ; $i++) { 


 if (trim($Scompany[$i])==$dbc['iCompanyId']) {
  $selected='selected';
}
}
                  ?>
                 <option value="<?php echo  $dbc['iCompanyId']; ?>" <?php echo  $selected; ?>><?php echo $generalobjAdmin->clearCmpName($dbc['vCompany']); ?></option>
                 <?php } ?>
              </select>
            </div>
		<div class="tripBtns001"><b>

  <input type="hidden" name="list" id='searchList' value="<?php echo $list; ?>" >
<input type="hidden" name="iVehicleTypeId" id="searchVehicleID" value="<?php echo $iVehicleTypeId; ?>" >
					<input type="button" value="Search" class="btnalt button11" id="Search" name="Search" title="Search" />
					<input type="button" value="Reset" class="btnalt button11" onClick="window.location.href='statisticsReportUsers.php'"/>
                   <input type="button" value="Export" class="btnalt button11" id="export" name="export" title="Export" />

        </b>
					</div>
					</form>
          
					<div class="table-list">
						<div class="row">
							<div class="col-lg-12">
									<div class="table-responsive">
										  <?php if(trim($typeofreport)=="Number of Registered Users")
                      { 

echo "<h4 style='background-color: lightgray;padding:10px;'>Number of registration <strong>".$total_results."</strong></h4>";
                        } ?>
										<form class="_list_form" id="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
										<table class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
												<?php ?> 
											<?php if(trim($typeofreport)!="Number of Registered Users")
                      { ?>
													
													<th><a href="javascript:void(0);" onClick="Redirect(1,<?php if($sortby == '1'){ echo $order; }else { ?>0<?php } ?>)">Company <?php if ($sortby == 1) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
												<? } ?>
													<th><a href="javascript:void(0);" onClick="Redirect(2,<?php if($sortby == '2'){ echo $order; }else { ?>0<?php } ?>)">


                            <?php  if(trim($typeofreport)=="Number of Services by Category")
                                    echo 'Service Requested';
                                      else if(trim($typeofreport)=="Number of Services Requested"&&$iVehicleTypeId!='')
                                   echo "User";
                                   else if(trim($typeofreport)=="Number of Services Requested")
                                   echo "Number of Users";
                                   else 
                                   echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];

                              ?> <?php if ($sortby == 2) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
												
												 	<?php if((trim($list)=="Yes" || trim($typeofreport)=="Number of Services by Category" )&& trim($typeofreport)!="Number of Registered Users" && trim($typeofreport)!="Number of Active Users" && trim($typeofreport)!="Number of Services Requested" )
{?>

													<th class="align-center"><a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)">Service Category<?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
												<? }?>


                        <?php if (trim($typeofreport)=="Number of Services Requested") {
                          ?>
  <th class="align-center"><a href="javascript:void(0);" onClick="Redirect(3,<?php if($sortby == '3'){ echo $order; }else { ?>0<?php } ?>)"><?php echo trim($list)=="Yes"?  'Service Category':'Number of Services'; ?> <?php if ($sortby == 3) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>


                         <?
                        } ?>

  <?php if(trim($typeofreport)=="Number of Registered Users")
                      { ?>
                          
                          <th><a href="javascript:void(0);" onClick="Redirect(4,<?php if($sortby == '4'){ echo $order; }else { ?>0<?php } ?>)">Email <?php if ($sortby == 4) { if($order == 0) { ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php } }else { ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                        <? } ?>

												</tr>
											</thead>
											<tbody>
												<? if(!empty($db_trip)) {
												for($i=0;$i<count($db_trip);$i++)
												{
														$eTypenew = $db_trip[$i]['eType'];
														if($eTypenew == 'Ride'){
															$trip_type = 'Ride';
														} else if($eTypenew == 'UberX') {
															$trip_type = 'Other Services';
														} else {
															$trip_type = 'Delivery';
														}
														?>
														<tr class="gradeA">
													
															
															 <?php if(trim($typeofreport)!="Number of Registered Users")
                      { ?>
															<td> 
																<?=$generalobjAdmin->clearCmpName($db_trip[$i]['vCompany']);?>
															</td>
															<? } ?>
															<td>
																<? if($db_trip[$i]['eHailTrip'] != "Yes"){
                                  $iVehicleCategoryId="";
                                                  if(trim($list)=="Yes"||trim($typeofreport)!="Number of Services Requested")  {             
$iVehicleCategoryId= trim($db_trip[$i]['iVehicleCategoryId'])!=""?$db_trip[$i]['iVehicleCategoryId']:$eTypenew;
}
                                    
                                    if((trim($list)!="Yes" && trim($typeofreport)!="Number of Registered Users")||(trim($typeofreport)=="Number of Services Requested"&&$iVehicleTypeId==""))
                                    {                                
																	?>
																	<a href="#" class="openlist" data-company='<?=$db_trip[$i]['iCompanyId']?>' data-vehicletype='<?=$iVehicleCategoryId?>' ><?=$generalobjAdmin->clearName($db_trip[$i]['riderName']);?></a>
																

<?

																 }else {

                                  ?>
                                  <a href="rider_action.php?id=<?=$db_trip[$i]['iUserId']?>" target="_blank"><?=$generalobjAdmin->clearName($db_trip[$i]['riderName']);?></a>
                                  <?
                               
                                 }

                                }


                                  else{
																	echo " ---- ";
																} ?>
															</td>
														<?php if((trim($list)=="Yes" || trim($typeofreport)=="Number of Services by Category" || trim($typeofreport)=="Number of Services Requested")&& trim($typeofreport)!="Number of Registered Users" && trim($typeofreport)!="Number of Active Users" )
{ ?>
															<td >
																<?php $cat= trim($db_trip[$i]['vCategory_EN'])!="" ? $db_trip[$i]['vCategory_EN']:$eTypenew; ?>

																<?
                                if(trim($typeofreport)=="Number of Services Requested"&&trim($list)!="Yes")
                                {
                               ?><a href="#" class="openlist" data-company='<?=$db_trip[$i]['iCompanyId']?>' data-vehicletype='<?=$iVehicleCategoryId?>' ><?=$generalobjAdmin->clearName( $cat);?></a>
                             <?
                           }
                                 else{
                                  echo $cat;
                                }
                                ?>
															</td>
															<?php } ?>



  <?php if(trim($typeofreport)=="Number of Registered Users")
                      { ?>
<td><?=$generalobjAdmin->clearName($db_trip[$i]['vEmail']);?></td>

                     <? }?>
														</tr>
														  <div class="clear"></div>
														 <div class="modal fade" id="uiModal1_<?=$db_trip[$i]['iTripId'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
															  <div class="modal-content image-upload-1" style="width:400px;">
																   <div class="upload-content" style="width:350px;">
																		<h3><?=$langage_lbl_admin['LBL_RIDE_TXT_ADMIN'];?> Cancel Reason</h3>	
																		<h4>Cancel Reason: <?=stripcslashes($db_trip[$i]['vCancelReason']." ".$db_trip[$i]['vCancelComment']);?></h4>
																		<input type="button" class="save" data-dismiss="modal" name="cancel" value="Close">
																   </div>
															  </div>
														 </div>
												<?php } }else { ?>
													<tr class="gradeA">
														<td colspan="10"> No Records Found.</td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
										</form>
										<?php include('pagination_n.php'); ?>
									</div>
							</div>
						</div>
					</div>
                <div class="clear"></div>
			</div>
        </div>
       <!--END PAGE CONTENT -->
    </div>
    <!--END MAIN WRAPPER -->

<form name="pageForm" id="pageForm" action="" method="post" >
<input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
<input type="hidden" name="tpages" id="tpages" value="<?php echo $tpages; ?>">
<input type="hidden" name="sortby" id="sortby" value="<?php echo $sortby; ?>" >
<input type="hidden" name="order" id="order" value="<?php echo $order; ?>" >
<input type="hidden" name="action" value="<?php echo $action; ?>" >
<input type="hidden" name="searchCompany" id="searchCompany_frm" value="<?php echo $searchCompany; ?>" >
<input type="hidden" name="searchDriver" value="<?php echo $searchDriver; ?>" >
<input type="hidden" name="searchRider" value="<?php echo $searchRider; ?>" >
<input type="hidden" name="serachTripNo" value="<?php echo $serachTripNo; ?>" >
<input type="hidden" name="startDate" value="<?php echo $startDate; ?>" >
<input type="hidden" name="endDate" value="<?php echo $endDate; ?>" >
<input type="hidden" name="vStatus" value="<?php echo $vStatus; ?>" >
<input type="hidden" name="eType" value="<?php echo $eType; ?>" >
<input type="hidden" name="method" id="method" value="" >
<input type="hidden" name="list" id="list" value="<?php echo $list; ?>" >
<input type="hidden" name="iVehicleTypeId" id="iVehicleTypeId" value="<?php echo $iVehicleTypeId; ?>" >


<input type="hidden" name="typeofreport" value="<?php echo $typeofreport; ?>" >


</form>
	<? include_once('footer.php');?>
	<link rel="stylesheet" href="../assets/plugins/datepicker/css/datepicker.css" />
	<link rel="stylesheet" href="css/select2/select2.min.css" />
	<script src="js/plugins/select2.min.js"></script>
	<script src="../assets/js/jquery-ui.min.js"></script>
	<script src="../assets/plugins/datepicker/js/bootstrap-datepicker.js"></script>
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
    <script>
			$('#dp4').datepicker()
            .on('changeDate', function (ev) {
                if (ev.date.valueOf() < endDate.valueOf()) {
                    $('#alert').show().find('strong').text('The start date can not be greater then the end date');
                } else {
                    $('#alert').hide();
                    startDate = new Date(ev.date);
                    $('#startDate').text($('#dp4').data('date'));
                }
                $('#dp4').datepicker('hide');
            });
			$('#dp5').datepicker()
            .on('changeDate', function (ev) {
                if (ev.date.valueOf() < startDate.valueOf()) {
                    $('#alert').show().find('strong').text('The end date can not be less then the start date');
                } else {
                    $('#alert').hide();
                    endDate = new Date(ev.date);
                    $('#endDate').text($('#dp5').data('date'));
                }
                $('#dp5').datepicker('hide');
            });
	
         $(document).ready(function () {
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
	/*	$("#Search").on('click', function(){
			 if($("#dp5").val() < $("#dp4").val()){
				 alert("From date should be lesser than To date.")
				 return false;
			 }else {
				var action = $("#_list_form").attr('action');
                var formValus = $("#frmsearch").serialize();
                window.location.href = action+"?"+formValus;
			 }
		});*/
		$(function () {
		  $("select.filter-by-text").each(function(){
			  $(this).select2({
					placeholder: $(this).attr('data-text'),
					allowClear: true
			  }); //theme: 'classic'
			});
		});
		$('#searchCompany').change(function() {
		    var company_id = $(this).val(); //get the current value's option
		    $.ajax({
		        type:'POST',
		        url:'ajax_find_driver_by_company.php',
		        data:{'company_id':company_id},
				cache: false,
		        success:function(data){
		            $(".driver_container").html(data);
		        }
		    });
		});

$("#searchCompany").multiselect({
   enableCaseInsensitiveFiltering: true,
   buttonWidth:"275px",
    includeSelectAllOption : true,
    nonSelectedText: 'Select Company',
    maxHeight:300
  });


$("#Search").click(function(){


var typeofreport=$("#typeofreport").val();
var startDate=$("#dp4").val();

var endDate=$("#dp5").val();

var searchVehicleID=$("#searchVehicleID").val();
var searchList=$("#searchList").val();
var searchCompany=$("#searchCompany").val();

post('statisticsReportUsers.php', {action:"search",typeofreport:typeofreport,startDate:startDate,endDate:endDate,searchCompany:searchCompany,list:searchList,iVehicleTypeId:searchVehicleID});

//window.location.href="statisticsReportUsers.php?action=search&typeofreport="+typeofreport+"&startDate="+startDate+"&endDate="+endDate+"&searchCompany="+searchCompany+"&list="+searchList+"&iVehicleTypeId="+searchVehicleID
});


$("#export").click(function(){


var sortby=$("#sortby").val();
var order=$("#order").val();
var typeofreport=$("#typeofreport").val();
var startDate=$("#dp4").val();

var endDate=$("#dp5").val();

var searchVehicleID=$("#searchVehicleID").val();
var searchList=$("#searchList").val();
var searchCompany=$("#searchCompany").val();

post('ExportStatisticsReport/exportReportUsers.php', {action:"search",typeofreport:typeofreport,startDate:startDate,endDate:endDate,searchCompany:searchCompany,list:searchList,iVehicleTypeId:searchVehicleID});

});

$("#typeofreport").change(function(){

  if($(this).val()=="Number of Registered Users")
  {
$("#company_container").hide();
  }else{
$("#company_container").show();

  }
});

$("#typeofreport").trigger('change');
$(".openlist").click(function(e){


e.preventDefault();
$("#list").val('Yes');

var company=$("#searchCompany_frm").val();
$("#searchCompany_frm").val($(this).data("company"));
$("#iVehicleTypeId").val($(this).data("vehicletype"));

$("#pageForm").attr("action","statisticsReportUsers.php").attr("target", "_blank").attr("method", "GET");
$("#pageForm").submit();
$("#pageForm").attr("action","").attr("method", "post").removeAttr("target");

$("#list").val('');
$("#searchCompany_frm").val(company);

});

	/*	function openTrips(iCompanyId)
		{
var searchCompany=$("#searchCompany_frm").val();

$("#searchCompany_frm").val(iCompanyId);
$("#pageForm").attr("action","trip.php").attr("target", "_blank").attr("method", "GET");
$("#pageForm").submit();
$("#pageForm").attr("action","").attr("method", "post").removeAttr("target");
$("#searchCompany_frm").val(searchCompany);
		
		}*/

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
<style type="text/css">
  button.multiselect.dropdown-toggle.btn.btn-default {
    height: 46px;
}
</style>
<script type="text/javascript">
  window.onload = getPageLoadTime;

</script>