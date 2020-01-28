<?php

include_once('common.php');
header("Cache-Control: no-cache, no-store, must-revalidate"); 

header("Pragma: no-cache"); 

header("Expires: 0"); 
clearstatcache();

require  'PayMaya-PHP-SDK-master/sample/autoload.php';
use PayMaya\API\Checkout;
use PayMaya\Model\Checkout\Buyer;
use PayMaya\Model\Checkout\Item;
use PayMaya\Model\Checkout\ItemAmount;
use PayMaya\Model\Checkout\ItemAmountDetails;
use PayMaya\Model\Checkout\Address;
use PayMaya\Model\Checkout\Contact;

use PayMaya\API\Customization;
use PayMaya\API\Webhook;
// Note: Please remove "SANDBOX" if you're in production environment.

//Paymaya Keys
$sql_PAYMAYA_SK="SELECT vValue FROM configurations WHERE vName='PAYMAYA_SK'";
$db_PAYMAYA_SK=$obj->MySQLSelect($sql_PAYMAYA_SK);

$sql_PAYMAYA_PK="SELECT vValue FROM configurations WHERE vName='PAYMAYA_PK'";
$db_PAYMAYA_PK=$obj->MySQLSelect($sql_PAYMAYA_PK);


$sql_PAYMAYA_COMMISON="SELECT vValue FROM configurations WHERE vName='PAYMAYA_COMMISON'";
$db_PAYMAYA_COMMISON=$obj->MySQLSelect($sql_PAYMAYA_COMMISON);


$PAYMAYA_SK = $db_PAYMAYA_SK[0]['vValue'];
$PAYMAYA_PK = $db_PAYMAYA_PK[0]['vValue'];
$PAYMAYA_COMMISON = floatval($db_PAYMAYA_COMMISON[0]['vValue']);




PayMaya\PayMayaSDK::getInstance()->initCheckout($PAYMAYA_PK,$PAYMAYA_SK, 'SANDBOX');

    $sql = "select * from company where iCompanyId = '". $_SESSION['sess_iCompanyId']."'";

    $user_details = $obj->MySQLSelect($sql);
$tokens=$_REQUEST['tokens'];

$_SESSION['tokens']=$tokens;
$commision=(($tokens*$PAYMAYA_COMMISON)/100);
$Peso =$tokens+$commision;
$_SESSION['tokens_commision']=$commision;
$_SESSION['totalAmount']=$Peso;



do
{
$ref_no=generateRandomString();
$ref_query="select * from barangay_tokens where ref_No='$ref_no'";
 $ref = $obj->MySQLSelect($ref_query);
}while (count($ref) != 0);
$_SESSION['ref_no']=$ref_no;
$iAdminUserId=$_SESSION['sess_iCompanyId'];
$payment_status="Failure";
$credit_token="INSERT INTO `barangay_tokens`(`BarangayId`, `Tokens`, `Type`, `TDate`,`ref_No`,`payment`,`commission`,`payment_status`) VALUES ('$iAdminUserId','$tokens','','". date("Y-m-d H:i:s")."','$ref_no','$Peso','$commision','$payment_status')";
$obj->MySQLSelect($credit_token);
unset($_REQUEST);
        $itemCheckout = new PayMaya\API\Checkout();
        $address = new Address();
        $contact = new Contact();
        $contact->phone = $user_details[0]['vPhone'];
        $contact->email = $user_details[0]['vEmail'];
        $buyer = new Buyer();
        $buyer->firstName = $user_details[0]['vCompany'];
        $buyer->middleName = "";
        $buyer->lastName = "";
        $buyer->contact = $contact;
        $itemCheckout->buyer = $buyer;
        $itemAmountDetails = new PayMaya\Model\Checkout\ItemAmountDetails();
        $itemAmountDetails->shippingFee = "00.00";
        $itemAmountDetails->tax = "00.00";
        $itemAmountDetails->subtotal = $Peso;
        $itemAmountDetails->serviceCharge = $commision;
        $itemAmount = new PayMaya\Model\Checkout\ItemAmount();
        $itemAmount->currency = "PHP";
        $itemAmount->value = $Peso;
        $itemAmount->details = $itemAmountDetails;
        $item = new PayMaya\Model\Checkout\Item();
        $item->value = 1;
        $item->name = "Tokens";
        $item->code = "";
        $item->description = "";
        $item->quantity = $tokens;
        $item->amount = $itemAmount;
        $item->totalAmount = $itemAmount;
        $itemCheckout->items = array($item);
        $itemCheckout->totalAmount = $itemAmount;
        // The requestReferenceNumber is an identifier for the customer's order.
     $itemCheckout->requestReferenceNumber = $ref_no;
        // Redirect URLs are used to direct the customer to the correct
        // page based on the transaction outcome.
        // Redirect URLs should not be used to set the status of an order
        // to "paid" as the customer might quit the browser prior the redirection.
        $itemCheckout->redirectUrl = array(
             "success" => "https://www.ninjaoncall.com/tokens.php?payment=success&refNo=$ref_no",
            "failure" => "https://www.ninjaoncall.com/tokens.php?payment=failure",
            "cancel" => "https://www.ninjaoncall.com/tokens.php?payment=cancel"
        );
        $itemCheckout->execute();
        $itemCheckout->retrieve();

         header("Location:" . $itemCheckout->url);

      
function generateRandomString($length = 12) {
      $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>