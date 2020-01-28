<?php 
header("Content-Type:application/json"); 
//include_once("../../../common.php");
//include_once(TPATH_CLASS.'class.general.php');
//include_once(TPATH_CLASS.'configuration.php');
//include_once('../../../generalFunctions.php');
$postData = file_get_contents('php://input');

foreach ($postData as $key => $value) 
{
	$value = urldecode(stripslashes($value));
	$req .= "&$key=$value";
	$req .= '<br><br>';
}

foreach ($_REQUES as $key => $value) 
{
	$value = urldecode(stripslashes($value));
	$req1 .= "&$key=$value";
	$req1 .= '<br><br>';
}

$to2 = "mrunal.esw@gmail.com";
$subject1 = "Email script from Mpesa validation.php ".date('Y-m-d H:i:s');				
$message = $req.$req1;
$header = "From:no-reply@bbcsproducts.com \r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-type: text/html\r\n";
$emailsend1 = mail($to2,$subject1,$message,$header);

try
{
    //Set the response content type to application/json
    header("Content-Type:application/json");
    //read incoming request
    $postData = file_get_contents('php://input');
    $resp = '{"ResultCode":0,"ResultDesc":"Validation passed successfully"}';
    //$filePath = "\home\bbcspr5\public_html\cubejek\assets\libraries\Mpesa\mpesa.txt";
    //error log
    //$errorLog = "\home\bbcspr5\public_html\cubejek\assets\libraries\Mpesa\mpesaerror.txt";
    //Parse payload to json
    //$filePath = "\home\bbcspr5\public_html\cubejek\assets\libraries\Mpesa\messages.log";
    $filePath = "messages.log";
    //error log
    $errorLog = "errors.log";
    $jdata = json_decode($postData,true);
    //perform business operations here
    //open text file for logging messages by appending
    $file = fopen($filePath,"a");
    //log incoming request
    fwrite($file, $postData);
    fwrite($file,"\r\n");
} catch (Exception $ex){
    //append exception to file
    $logErr = fopen($errorLog,"a");
    fwrite($logErr, $ex->getMessage());
    fwrite($logErr,"\r\n");
    fclose($logErr);
    //set failure response
    $resp = '{"ResultCode": 1, "ResultDesc":"Validation failure due to internal service error"}';
}
    //log response and close file
    fwrite($file,$resp);
    fclose($file);
    //echo response


echo $resp;
exit;

if (!isset($_GET["token"]))
{
echo "Technical error";
exit();
}



if($_GET["token"]!='MpesPU_RstrongPasswo')
{
echo "Invalid authorization";
exit();
}



/* 
here you need to parse the json format 
and do your business logic e.g. 
you can use the Bill Reference number 
or mobile phone of a customer 
to search for a matching record on your database. 
*/ 

/* 
Reject an Mpesa transaction 
by replying with the below code 
*/ 

echo '{"ResultCode":1, "ResultDesc":"Failed", "ThirdPartyTransID": 0}'; 

/* 
Accept an Mpesa transaction 
by replying with the below code 
*/ 

echo '{"ResultCode":0, "ResultDesc":"Success", "ThirdPartyTransID": 0}';
 
?>