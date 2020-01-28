<?php
    include_once('../common.php');
   // error_reporting(E_ALL);
  //  ini_set('display_errors', 1);
	include_once(TPATH_CLASS.'/class.general.php');
    include_once(TPATH_CLASS.'/configuration.php');
    include_once('../generalFunctions.php');

     $iDriverId= isset($_REQUEST['iDriverId'])?$_REQUEST['iDriverId']:"";
     $iCompanyId= isset($_REQUEST['iCompanyId'])?$_REQUEST['iCompanyId']:"";
     $document= isset($_REQUEST['document'])?$_REQUEST['document']:"";
     $Url= isset($_REQUEST['url'])?$_REQUEST['url']:"";
     $status= isset($_REQUEST['status'])?$_REQUEST['status']:"";

     $email="";
     $name="";
    if($iDriverId!="")
    {
  $sql = "SELECT vEmail,concat(Suffix,' ',vName,' ',MiddleName,' ',vLastName)  as Name FROM register_driver WHERE iDriverId='$iDriverId'";
  $result = $obj->MySQLSelect($sql);
  $email= $result[0]['vEmail'];
   $name= $result[0]['Name'];
	}
     if($iCompanyId!="")
     {
     	  $sql = "SELECT vEmail,vCompany FROM company WHERE iCompanyId='$iCompanyId'";
          $result = $obj->MySQLSelect($sql);
          $email= $result[0]['vEmail'];
          $name= $result[0]['vCompany'];
     }

     $maildata['EMAIL'] = $email;
	 $maildata['Document'] = $document;
	 $maildata['Url'] = $Url;
	 $maildata['Name'] = $name;
   $maildata['Status'] = $status;


if($status=="resubmit")
   $generalobj->send_email_user("RESUBMIT_DOCUMENT",$maildata); 
else 
     $generalobj->send_email_user("DOCUMENT_STATUS_CHANGED",$maildata); 

if($iDriverId!="")
{

  $sql = "SELECT iGcmRegId,eDeviceType,iDriverId,vLang,tSessionId,iAppVersion FROM register_driver WHERE iDriverId='$iDriverId'";
  $result = $obj->MySQLSelect($sql);
$registation_ids_new=array();
$deviceTokens_arr_ios=array();

if($status=="resubmit")
$alertMsg_db = "Re-submit Document $document";
else 
$alertMsg_db = "Document $document is $status by admin";
             foreach ($result as $item) {
				if($item['eDeviceType'] == "Android"){
					array_push($registation_ids_new, $item['iGcmRegId']);
					}else{
					array_push($deviceTokens_arr_ios, $item['iGcmRegId']);
				}				
					
				
				$tSessionId= $item['tSessionId'];
				
				$final_message['tSessionId'] = $tSessionId;
				$final_message['vTitle'] = $alertMsg_db;
				$msg_encode  = json_encode($final_message,JSON_UNESCAPED_UNICODE);		
			}
$message = $alertMsg_db;

if(count($registation_ids_new) > 0){
				$Rmessage = array("message" => $message);
				
				$result = send_notification($registation_ids_new, $Rmessage,0);
				
			}
			if(count($deviceTokens_arr_ios) > 0){

				sendApplePushNotification(1,$deviceTokens_arr_ios,$msg_encode,$alertMsg,0);
			}
}

echo "success";
?>