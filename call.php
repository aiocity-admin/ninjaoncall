<?php
// Require the bundled autoload file - the path may need to change
// based on where you downloaded and unzipped the SDK
include_once('common.php');
require 'assets/libraries/twilio-php-master/Twilio/autoload.php';

use Twilio\Twiml;

$response = new Twiml();
$country_code = $_REQUEST['FromCountry'];
$org_num = $_REQUEST['From'];

if(!empty($org_num)) {
	$sql = "SELECT tm.* FROM trip_call_masking  as tm LEFT JOIN trips as t on t.iTripId =  tm.iTripid WHERE (t.iActive != 'Canceled' && t.iActive != 'Finished')";
	$db_TripMaskData = $obj->MySQLSelect($sql);
	if(!empty($db_TripMaskData)){
		foreach ($db_TripMaskData as $key => $value) {
			$driver_phone = "+".$value['DriverPhoneCode'].$value['DriverPhone'];
			$rider_phone = "+".$value['UserPhoneCode'].$value['RiderPhone'];
			switch (true) {
				case ($driver_phone == $org_num):
				$call_masknum =  $value['mask_number'];
				$orgPhonefor_call = $value['RiderPhone'];
				break;

				case ($rider_phone == $org_num):
				$call_masknum =  $value['mask_number'];
				$orgPhonefor_call = $value['DriverPhone'];
				break;
			}
		}
	}
  
  if($call_masknum == ""){
     $call_masknum = $_REQUEST['Called'];
     $orgPhonefor_call = "+".$db_TripMaskData[0]['DriverPhoneCode'].$db_TripMaskData[0]['DriverPhone'];
  }
  
	if(SITE_TYPE == 'Demo'){
		$dial = $response->dial(['callerId' => $call_masknum, 'timeLimit' => 600]);
	} else {
		$dial = $response->dial(['callerId' => $call_masknum]);
	}
		$dial->number($orgPhonefor_call);
}
ob_clean();
//$dial->callerId('8582120490');
echo $response;
exit;
// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
$sid = 'AC32b01bb7ff56e7e08be1a019361f7722';
$token = 'c328da6286cbdb96938c37311c5c4b94';
$client = new Client($sid, $token);

$call = $client->calls->create(
  '8584270668', // Call this number
  '8582120490', // From a valid Twilio number
  array(
      'url' => 'https://twimlets.com/holdmusic?Bucket=com.twilio.music.ambient'
  )
);
