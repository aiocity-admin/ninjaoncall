<?php
include_once('common.php');

		$status = "0";
        $iDriverId = isset($_REQUEST['iDriverId'])?$_REQUEST['iDriverId']:'';
        $services_string = isset($_REQUEST['Services'])?$_REQUEST['Services']:'';
        $services=explode(',', $services_string);
       $success=true;
       $REQUESTED_SERVICES="";
       for ($i=0; $i <count($services) ; $i++) { 
     	$sql = "SELECT ID  FROM `driver_registered_service` WHERE iDriverId='$iDriverId' and iVehicleCategoryId='$services[$i]'";
		$Data = $obj->MySQLSelect($sql);
		if(count($Data)<=0)
		{
       $sql = "INSERT INTO `driver_registered_service`(`iDriverId`, `iVehicleCategoryId`, `Status`) VALUES ('$iDriverId','".trim($services[$i])."','0')";
		 if(!$obj->sql_query($sql))
		 {
		 	$success=false;
		 	$REQUESTED_SERVICES.=$services[$i].",";
		 }
		}
       }
$sql="select concat(Suffix,' ',vName,' ',MiddleName,' ',vLastName) as Name,iCompanyId,vEmail from  register_driver where  iDriverId='$iDriverId' ";
		        $Driver_Details = $obj->MySQLSelect($sql);
	            $iCompanyId=$Driver_Details[0]["iCompanyId"];
	            $email=$Driver_Details[0]["vEmail"];
	            $Fname=$Driver_Details[0]["Name"];

                $company_email_query="SELECT  `vEmail` FROM `company` WHERE iCompanyId='$iCompanyId'";
		        $result_email = $obj->MySQLSelect($company_email_query);
		        $maildata['COMPANY'] =$result_email[0]["vEmail"];
		        $maildata['SERVICES']=$REQUESTED_SERVICES;
                $maildata['EMAIL'] = $email;
				$maildata['NAME'] = $Fname;
				$maildata['SOCIALNOTES'] = '';				
	            $generalobj->send_email_user("DRIVER_REQUEST_FOR_SERVICES_COMPANY",$maildata); 	


        if ($success) {
			$returnData['Action']="1";
			$returnData['message']="LBL_SERVICE_UPDATE_SUCCESS";
			}else{
			$returnData['Action']="0";
			$returnData['message']="LBL_HELP_DETAIL_NOT_AVAIL";
		}
        echo json_encode($returnData);
	
	?>