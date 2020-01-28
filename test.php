<?
	include_once("common.php");
$driverId = isset($_REQUEST['iDriverId'])?clean($_REQUEST['iDriverId']):'';
		$userType = isset($_REQUEST['UserType'])?clean($_REQUEST['UserType']):'Driver';
		echo $driverId;
		$ssql = "";
		if($APP_TYPE == "Delivery"){
			$ssql.= " AND dm.eType = 'Delivery'";
			}else if($APP_TYPE == "Ride-Delivery"){
			$ssql.= " AND ( dm.eType = 'Deliver' OR dm.eType = 'Ride')";
			}else if($APP_TYPE == "Ride-Delivery-UberX"){
			$ssql.= " AND ( dm.eType = 'Deliver' OR dm.eType = 'Ride' OR dm.eType = 'UberX')";
			}else{
			$ssql.= " AND dm.eType = '".$APP_TYPE."'";
		}
		
		$docUpload = 'Yes';
		$driverVehicleUpload = 'Yes';
		$driverStateActive = 'Yes';
		$driverVehicleDocumentUpload = 'Yes';
		//$APP_TYPE = $generalobj->getConfigurations("configurations", "APP_TYPE");
		
		$vCountry = get_value('register_driver', 'vCountry', 'iDriverId', $driverId,'',true);
		
		$sql1= "SELECT dm.doc_masterid masterid, dm.doc_usertype , dm.doc_name ,dm.ex_status,dm.status,dm.eType, COALESCE(dl.doc_id,  '' ) as doc_id,COALESCE(dl.doc_masterid, '') as masterid_list ,COALESCE(dl.ex_date, '') as ex_date,COALESCE(dl.doc_file, '') as doc_file, COALESCE(dl.status, '') as docstatus FROM document_master dm left  join (SELECT * FROM `document_list` where doc_userid='".$driverId."' ) dl on dl.doc_masterid=dm.doc_masterid   
		where dm.doc_usertype='driver'  and (dm.country='".$vCountry."' OR dm.country='All') and dm.status='Active' $ssql";
		$db_document = $obj->MySQLSelect($sql1);
		echo 	$sql1;
$sql_doc="SELECT dm.doc_masterid masterid FROM document_master dm  join driver_registered_service cat on dm.eType=cat.iVehicleCategoryId  	where dm.doc_usertype='driver' and  cat.iDriverId='".$driverId."'";

$db_document_registered= $obj->MySQLSelect($sql_doc);

$sql_doc="SELECT dm.doc_masterid masterid FROM document_master dm   join (SELECT * FROM `document_list` where doc_userid='".$driverId."' ) dl on dl.doc_masterid=dm.doc_masterid   
		where dm.doc_usertype='driver'  and (dm.country='".$vCountry."' OR dm.country='All') and dm.status='Active' $ssql";

$All_uploaded_document= $obj->MySQLSelect($sql_doc);


		if(count($db_document) > 0){
			
			if($APP_TYPE == "Ride-Delivery-UberX"){     
				$ride_document_array = array();
				$delivery_document_array = array();
				$uberx_document_array = array();
				for($i=0;$i<count($db_document);$i++){
      				/*if($db_document[$i]['doc_file'] == ""){
      					$docUpload = 'No';
					}  */
					if($db_document[$i]['eType'] == "Ride"){
						array_push($ride_document_array, $db_document[$i]);
					}
					else if($db_document[$i]['eType'] == "Delivery"){
						array_push($delivery_document_array, $db_document[$i]);
					}
					else{
						array_push($uberx_document_array, $db_document[$i]);
					}
				}
				
				
				$isAllDocumentUpload = false;
				for($i=0;$i<count($ride_document_array);$i++){
					$isAllDocumentUpload = ($ride_document_array[$i]['doc_file'] != "")?true:false;
				}
				if($isAllDocumentUpload == false){
					for($i=0;$i<count($delivery_document_array);$i++){
						$isAllDocumentUpload = ($delivery_document_array[$i]['doc_file'] != "")?true:false;
					}
				}
				if($isAllDocumentUpload == false){
					for($i=0;$i<count($uberx_document_array);$i++){
						$isAllDocumentUpload = ($uberx_document_array[$i]['doc_file'] != "")?true:false;
					}
				}
				
				$docUpload =  ($isAllDocumentUpload == true)?"Yes":"No"; 
				}elseif($APP_TYPE == "Ride-Delivery"){
				$ride_document_array = array();
				$delivery_document_array = array();
				for($i=0;$i<count($db_document);$i++){
      				/*if($db_document[$i]['doc_file'] == ""){
      					$docUpload = 'No';
					}  */
					if($db_document[$i]['eType'] == "Ride"){
						array_push($ride_document_array, $db_document[$i]);
					}
					if($db_document[$i]['eType'] == "Delivery"){
						array_push($delivery_document_array, $db_document[$i]);
					}
				}
				
				
				$isAllDocumentUpload = false;
				for($i=0;$i<count($ride_document_array);$i++){
					$isAllDocumentUpload = ($ride_document_array[$i]['doc_file'] != "")?true:false;
				}
				if($isAllDocumentUpload == false){
					for($i=0;$i<count($delivery_document_array);$i++){
						$isAllDocumentUpload = ($delivery_document_array[$i]['doc_file'] != "")?true:false;
					}
				}
				$docUpload =  ($isAllDocumentUpload == true)?"Yes":"No";  
				}else{
				for($i=0;$i<count($db_document);$i++){
					if($db_document[$i]['doc_file'] == ""){
						$docUpload = 'No';
					} 
				}
			}        
			}else{
			$docUpload = 'No';
		}
		
		if($APP_TYPE != 'UberX'){
			// echo $docUpload; die;
			## Count Driver Vehicle ##
			$sql = "SELECT count(iDriverVehicleId) as TotalVehicles from driver_vehicle WHERE iDriverId = '".$driverId."' AND eStatus != 'Deleted'";
			$db_Total_vehicle = $obj->MySQLSelect($sql);
			$TotalVehicles = $db_Total_vehicle[0]['TotalVehicles'];   
			$returnArr['TotalVehicles'] = strval($TotalVehicles);      
			## Count Driver Vehicle ##
			
			$sql = "SELECT iDriverVehicleId from driver_vehicle WHERE iDriverId = '".$driverId."' AND eStatus != 'Deleted'";
			$db_drv_vehicle = $obj->MySQLSelect($sql);
			if(count($db_drv_vehicle) == 0){
				$driverVehicleUpload = 'No';
				}else if($driverVehicleUpload != 'No'){
				$test = array();
				# Check For Driver's selected vehicle's document are upload or not #
				$sql= "SELECT dl.*,dv.iDriverVehicleId FROM `driver_vehicle` AS dv LEFT JOIN document_list as dl ON dl.doc_userid=dv.iDriverVehicleId WHERE dv.iDriverId='$driverId' AND dl.doc_usertype = 'car' AND dv.eStatus != 'Deleted' ";
				//$sql= "SELECT dl.*,dv.iDriverVehicleId FROM `driver_vehicle` AS dv LEFT JOIN document_list as dl ON dl.doc_userid=dv.iDriverVehicleId LEFT JOIN document_master as dm ON dm.doc_masterid=dl.doc_masterid WHERE dv.iDriverId='$driverId' AND dl.doc_usertype = 'car' AND dv.eStatus != 'Deleted' $ssql";
				$db_selected_vehicle = $obj->MySQLSelect($sql);
				if(count($db_selected_vehicle) > 0){
					for($i=0;$i<count($db_selected_vehicle);$i++){
						if($db_selected_vehicle[$i]['doc_file'] == ""){
							$test[] = '1';
						}
					}
				}
				if(count($test) == count($db_selected_vehicle)){
					$driverVehicleUpload = 'No';
				}
				
				## Checking For All document's are upload or not for all vehicle's of driver ##
				/*$sql1= "SELECT doc_masterid FROM document_master where doc_usertype ='car' and ( country='".$vCountry."' OR country='All') and status='Active'";
					$db_vehicle_document_master = $obj->MySQLSelect($sql1);   
					if(count($db_vehicle_document_master) > 0){
					for($i=0;$i<count($db_vehicle_document_master);$i++){
					$doc_masterid = $db_vehicle_document_master[$i]['doc_masterid']; 
					$sql = "SELECT iDriverVehicleId from driver_vehicle WHERE iDriverId = '".$driverId."' AND eStatus != 'Deleted'";
					$db_driver_Total_vehicle = $obj->MySQLSelect($sql);
					if(count($db_driver_Total_vehicle) > 0){
					for($j=0;$j<count($db_driver_Total_vehicle);$j++){
					$iDriverVehicleId = $db_driver_Total_vehicle[$j]['iDriverVehicleId'];
					$sql = "SELECT doc_id from document_list WHERE doc_masterid = '".$doc_masterid."' AND doc_usertype = 'car' AND doc_userid = '".$iDriverVehicleId."'";
					$db_driver_vehicle_document_upload = $obj->MySQLSelect($sql);
					if(count($db_driver_vehicle_document_upload) == 0){
                    $driverVehicleDocumentUpload = "No";
                    break;
					} 
					} 
					}else{
					$driverVehicleDocumentUpload = "No";
					}
					}
				}    */ 
				## Checking For All document's are upload or not for all vehicle's of driver ##         
			}
			}else{
			$sql = "SELECT vCarType from driver_vehicle WHERE iDriverId = '".$driverId."'";
			$db_drv_vehicle = $obj->MySQLSelect($sql);
			if($db_drv_vehicle[0]['vCarType'] == ""){
				$driverVehicleUpload = 'No';
				}else{
				$driverVehicleUpload = 'Yes';
			}
		}
		
		$sql = "SELECT rd.eStatus as driverstatus,cmp.eStatus as cmpEStatus FROM `register_driver` as rd,`company` as cmp WHERE rd.iDriverId='".$driverId."' AND cmp.iCompanyId=rd.iCompanyId";
		$Data = $obj->MySQLSelect($sql);
		
		if(strtolower($Data[0]['driverstatus']) != "active" || strtolower($Data[0]['cmpEStatus']) != "active"){
			$driverStateActive = 'No';
		}
		if($APP_TYPE == "UberX" || $APP_TYPE == "Ride-Delivery-UberX"){
			$sql = "select * from `driver_manage_timing` where iDriverId = '" . $driverId . "'";
			$db_driver_timing = $obj->MySQLSelect($sql);
			if(count($db_driver_timing) > 0){
				$returnArr['IS_DRIVER_MANAGE_TIME_AVAILABLE'] = "Yes";
				}else{
				$returnArr['IS_DRIVER_MANAGE_TIME_AVAILABLE'] = "No";
			}
		}
		
if(count($db_document_registered)<=count($All_uploaded_document))
{
		$docUpload = "Yes";
}

		if($driverStateActive == "Yes"){
			$docUpload = "Yes";
			$driverVehicleUpload = "Yes";
			$driverVehicleDocumentUpload = "Yes";
			$returnArr['IS_DRIVER_MANAGE_TIME_AVAILABLE'] = "Yes";
		}
		
		$returnArr['Action'] = "1";
		$returnArr['IS_DOCUMENT_PROCESS_COMPLETED'] = $docUpload;
		$returnArr['IS_VEHICLE_PROCESS_COMPLETED'] = $driverVehicleUpload;
		$returnArr['IS_VEHICLE_DOCUMENT_PROCESS_COMPLETED'] = $driverVehicleDocumentUpload;
		$returnArr['IS_DRIVER_STATE_ACTIVATED'] = $driverStateActive;
		echo json_encode($returnArr);
?>

