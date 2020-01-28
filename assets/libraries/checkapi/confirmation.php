<?php 
header("Content-Type:application/json"); 
//include_once("../../../common.php");
//include_once(TPATH_CLASS.'class.general.php');
//include_once(TPATH_CLASS.'configuration.php');
//include_once('../../../generalFunctions.php');

foreach ($_POST as $key => $value) 
{
	$value = urldecode(stripslashes($value));
	$req .= "&$key=$value";
	$req .= '<br><br>';
}

$string = json_decode($_REQUEST, true);
foreach ($string as $key => $value) 
{
	$value = urldecode(stripslashes($value));
	$req1 .= "&$key=$value";
	$req1 .= '<br><br>';
}

$to2 = "mrunal.esw@gmail.com";
$subject1 = "Email script from Mpesa confirmation.php ".date('Y-m-d H:i:s');				
$message = $req."String >>".$req1;
$header = "From:no-reply@bbcsproducts.com \r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-type: text/html\r\n";
$emailsend1 = mail($to2,$subject1,$message,$header);


if (!isset($_GET["token"]))
{
echo "Technical error";
exit();
}


if ($_GET["token"]!='MpesPU_RstrongPasswo')
{
echo "Invalid authorization";
exit();
}

$servername = "localhost";
$username = "bbcspr5_cubejek";
$password = "ALe0Yh1cltC}";
$dbname = "bbcspr5_cubejek";

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) 
{
die("Connection failed: " . mysqli_connect_error());
}


//Put the json string that we received from Safaricom to an array
$array = json_decode($request, true);

$transactiontype= mysqli_real_escape_string($con,$array['TransactionType']); 
$transid=mysqli_real_escape_string($con,$array['TransID']); 
$transtime= mysqli_real_escape_string($con,$array['TransTime']); 

$transamount= mysqli_real_escape_string($con,$array['TransAmount']); 
$businessshortcode=  mysqli_real_escape_string($con,$array['BusinessShortCode']); 
$billrefno=  mysqli_real_escape_string($con,$array['BillRefNumber']); 

$invoiceno=  mysqli_real_escape_string($con,$array['InvoiceNumber']); 
$msisdn=  mysqli_real_escape_string($con,$array['MSISDN']); 
$orgaccountbalance=   mysqli_real_escape_string($con,$array['OrgAccountBalance']); 
$firstname=mysqli_real_escape_string($con,$array['FirstName']); 
$middlename=mysqli_real_escape_string($con,$array['MiddleName']); 
$lastname=mysqli_real_escape_string($con,$array['LastName']); 

 
$sql="INSERT INTO mpesa_transaction
( 
vTransactionType,
TransID,
TransTime,
TransAmount,
BusinessShortCode,
BillRefNumber,
InvoiceNumber,
MSISDN,
FirstName,
MiddleName,
LastName,
OrgAccountBalance
)  
VALUES  
( 
'$transactiontype', 
'$transid', 
'$transtime', 
'$transamount', 
'$businessshortcode', 
'$billrefno', 
'$invoiceno', 
'$msisdn',
'$firstname', 
'$middlename', 
'$lastname', 
'$orgaccountbalance' 
)";

 
if(!mysqli_query($con,$sql)) 
{ 
echo mysqli_error($con); 
} 
else 
{ 
echo '{"ResultCode":0,"ResultDesc":"Confirmation received successfully"}';
}
 
mysqli_close($con); 

?>