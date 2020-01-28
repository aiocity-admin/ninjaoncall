<?php  
  include_once("common.php");
	global $generalobj;

//  $emailsend = $generalobj->send_email_smtp("chiragd.esw@gmail.com","no-reply@bbcsproducts.com","CubeTaxi4Plus","Twilio Call","Twilio Call",$replyto="");


  require_once(TPATH_CLASS .'twilio/Services/Twilio.php');
//  echo "hello";exit; 
  $account_sid = getConfigurations("configurations","MOBILE_VERIFY_SID_TWILIO");
  $auth_token = getConfigurations("configurations","MOBILE_VERIFY_TOKEN_TWILIO");
  $twilioMobileNum= getConfigurations("configurations","MOBILE_NO_TWILIO");

  $client = new Services_Twilio($account_sid, $auth_token);

  $toMobileNum= "+".$code.$mobileNo;      
  
  //echo $twilioMobileNum;
  //echo $toMobileNum;
  //echo $fpass;
  try{
    //  $sms = $client->account->messages->sendMessage($twilioMobileNum,$toMobileNum,$fpass);
     // $returnArr['action'] ="1";
     // echo "<pre>";print_r($sms);exit;
  } catch (Services_Twilio_RestException $e) {
     // $returnArr['action'] ="0";
     // echo "<pre>";print_r($e);
     // echo "<pre>";print_r($returnArr);exit;
  } 
?>