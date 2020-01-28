<?php 
include_once('../../common.php');


$filename = "booking Providers report.xlsx"; 
/*header("Content-Disposition: attachment;filename=\"$filename\"");
header("Content-Type:application/vnd.ms-excel");
header('Cache-Control:max-age=0');*/

 include_once('../../xlsxwriter.class.php'); 
   header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
$ord = ' ORDER BY vCompany ASC';

if ($sortby == 1) {
    if ($order == 0)
        $ord = " ORDER BY rvName ASC";
    else
        $ord = " ORDER BY vName DESC";
}
//End Sorting

// Start Search Parameters
$ssql = '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$iDriverId = isset($_REQUEST['iDriverId']) ? $_REQUEST['iDriverId'] : '';
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
$searchCompany = isset($_REQUEST['searchCompany']) ? $_REQUEST['searchCompany'] : '';
$eType= isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';
$date1=$startDate.' '."00:00:00";
$date2=$endDate.' '."23:59:59";

if ($startDate != '' && $endDate != '') {
	$ssql .= " AND rs.tDate between '$date1' and '$date2'";
  $ssql_trip .= " AND t.tTripRequestDate between '$date1' and '$date2'";
}
if ($iDriverId != '') {
	$ssql .= " AND rd.iDriverId = '".$iDriverId."'";

  $ssql_trip .= " AND rd.iDriverId = '".$iDriverId."'";

}
 $ssql2="";

if($searchCompany!="")
{
    $ssql .= " AND c.iCompanyId = '".$searchCompany."' ";
    $ssql2 .= " AND iCompanyId = '".$searchCompany."' ";

    $ssql_trip .= " AND c.iCompanyId = '".$searchCompany."' ";


}

if($eType!="")
{

 if (trim($eType)!='null')
{

 $eType2= explode(',', $eType);
  $subQuery_company='(';

  for ($i=0; $i <count($eType2) ; $i++) { 

 if ($eType2[$i]=='Ride' || $eType2[$i]=="Deliver")  
{

    $subQuery_company .= " crn.eType = '".$eType2[$i]."' or";

}else{
    $subQuery_company .= " vcp.iVehicleCategoryId = '".$eType2[$i]."' or";
  }

  //$subQuery_company.=" d.iCompanyId='$eType[$i]' or";
  }
   $subQuery_company=rtrim($subQuery_company,'or');

 $subQuery_company.=")";
$subQuery.=" and $subQuery_company";
$ssql.=$subQuery;
  $ssql_trip.=$subQuery;

}


 

}

        $chk_str_date = @date('Y-m-d H:i:s', strtotime('-'.$RIDER_REQUEST_ACCEPT_TIME.' second'));
    $sql = "SELECT rd.iDriverId , rd.vLastName ,rd.vName,rd.MiddleName ,c.vCompany,c.iCompanyId,
COUNT(case when rs.eStatus = 'Accept' then 1 else NULL end) `Accept` ,
COUNT(case when rs.eStatus != '' then 1 else NULL  end) `Total Request` ,
COUNT(case when (rs.eStatus  = 'Decline' AND rs.eAcceptAttempted  = 'No') then 1 else NULL end) `Decline` ,
COUNT(case when rs.eAcceptAttempted  = 'Yes' then 1 else NULL end) `Missed` ,
COUNT(case when ((rs.eStatus  = 'Timeout' OR rs.eStatus  = 'Received') AND rs.eAcceptAttempted  = 'No' AND  rs.dAddedDate < '".$chk_str_date."')  then 1 else NULL end) `Timeout`,
COUNT(case when ((rs.eStatus  = 'Timeout' OR rs.eStatus  = 'Received') AND rs.eAcceptAttempted  = 'No' AND rs.dAddedDate > '".$chk_str_date."' ) then 1 else NULL end) `inprocess`
FROM register_driver rd 
left join driver_request rs on rd.iDriverId=rs.iDriverId left join company c on rd.iCompanyId=c.iCompanyId left join cab_request_now crn on rs.iRequestId=crn.iCabRequestId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = crn.iVehicleTypeId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId
WHERE 1=1 $ssql GROUP by rs.iDriverId $ord";
				
				//echo  $sql;
		$db_dlip = $obj->MySQLSelect($sql);
				#echo "<pre>";print_r($db_dlip); exit;
               /* $header .= "Company Name" . "\t";

				$header .=  . "\t";
				$header .= "Total ".$langage_lbl_admin['LBL_TRIP_TXT_ADMIN']." Requests". "\t";
                $header .= "Requests Accepted" . "\t";
				$header .= "Requests Decline" . "\t";
				$header .= "Requests Timeout" . "\t";
				$header .= "Missed Attempts" . "\t";
				$header .= "In Process Request" . "\t";
				$header .= "Acceptance Percentage" . "\t";*/

$writer = new XLSXWriter();


				 $header = array("Company Name"=>'string',
  $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']." Name" =>'string',
 "Total ".$langage_lbl_admin['LBL_TRIP_TXT_ADMIN']." Requests" =>'string',
  
  "Requests Accepted"=>'string',
  "Requests Decline"=>'string',
  "Requests Timeout"=>'string',
 "Missed Attempts"=>'string',
  "In Process Request"=>'string',
  "Cancelled"=>'string',
  "Requests Completed "=>'string',

  "On going "=>'string',
  "Acceptance Percentage "=>'string');
			   
			    $total_trip_req ="";
                $total_trip_acce_req ="";
                $total_trip_dec_req ="";
										 $writer->writeSheetHeader('Sheet1', $header);

			for ($j = 0; $j < count($db_dlip); $j++) {
					
					  $sql_acp="SELECT COUNT(case when t.iActive = 'Canceled' OR t.eCancelled='yes' then 1 else NULL end) `Cancel` ,
																		COUNT(case when t.iActive = 'Finished' AND t.eCancelled='No'   then 1 else NULL  end) `Finish`,COUNT(case when (t.iActive = 'On Going Trip' OR t.iActive = 'Active') AND t.eCancelled='No' then 1 else NULL end) `OnGoingTrip` 
																		FROM trips t left join register_driver rd on rd.iDriverId=t.iDriverId left join company c on rd.iCompanyId=c.iCompanyId  LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId left join vehicle_category vc on vc.iVehicleCategoryId=vt.iVehicleCategoryId left join (SELECT * FROM  vehicle_category  WHERE  `iParentId`=0) as vcp on vcp.iVehicleCategoryId=vc.iParentId  where t.iDriverId='".$db_dlip[$j]['iDriverId']."'".$ssql_trip;
									$db_acp = $obj->MySQLSelect($sql_acp);

					
					$Accept = $db_dlip[$j]['Accept'];
					$tAccept = $tAccept + $Accept;
					$Request = $db_dlip[$j]['Total Request'];
					$tRequest =$tRequest + $Request ;
					$Decline = $db_dlip[$j]['Decline'];
					$tDecline =$tDecline + $Decline;
					$Timeout = $db_dlip[$j]['Timeout'];
					$tTimeout = $tTimeout + $Timeout ;
					
                    $missed = $db_dlip[$j]['Missed'];
                    $tmissed = $tmissed + $missed ;
                    $inprocess = $db_dlip[$j]['inprocess'];
                    $tinprocess = $tinprocess + $inprocess ;

					
															  $Cancel = $db_acp[0]['Cancel'];
															  $tCancel = $tCancel + $Cancel ;

															  $Finish = $db_acp[0]['Finish'];
                                //$tFinish = $tFinish +$Finish;

                               $OnGoingTrip = $db_acp[0]['OnGoingTrip'];
                                $tOnGoingTrip = $tOnGoingTrip + $OnGoingTrip;


				//	$aceptance_percentage= (100 * ($Accept))/$Request;
 $aceptance_percentage= (100 * ($Accept+$missed))/$Request;

								
					$data [0]= $db_dlip[$j]['vCompany'];
		
					$data [1]= $db_dlip[$j]['vName'].' '.$db_dlip[$j]['vLastName'];
					$data [2]= $Request;
					$data [3]= $Accept ;
                    $data [4]= $Decline ;
                    $data [5]= $Timeout ;
					$data [6]= $missed;
					$data [7]= $inprocess;
					$data [8]= $Cancel;

					$data [9]= $Finish;

					$data [10]= $OnGoingTrip;

					///print_r($data);

					$data [11]= round($aceptance_percentage,2).' %';

					  $writer->writeSheetRow('Sheet1',  $data);

					
				}




 $writer->writeToStdOut();

				?>