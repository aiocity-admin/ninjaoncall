<?php

include_once('common.php');
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.

header("Pragma: no-cache"); // HTTP 1.0.

header("Expires: 0"); // Proxies. 
clearstatcache();


//error_reporting(0);
//ini_set('display_errors', 1);
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
//  pk-IhdmiKvEgv3yrtsyXWWeVJMtMKyjXcwYocmeGiVn1Uo
//      sk-ch9Z13RoL9YJUqVVfahYU4VY8OO3vG1Bpmi2pwGpKes

//PayMaya\PayMayaSDK::getInstance()->initCheckout('pk-IhdmiKvEgv3yrtsyXWWeVJMtMKyjXcwYocmeGiVn1Uo', 'sk-ch9Z13RoL9YJUqVVfahYU4VY8OO3vG1Bpmi2pwGpKes', 'SANDBOX');

//PayMaya\PayMayaSDK::getInstance()->initCheckout('pk-nRO7clSfJrojuRmShqRbihKPLdGeCnb9wiIWF8meJE9', 'sk-jZK0i8yZ30ph8xQSWlNsF9AMWfGOd3BaxJjQ2CDCCZb', 'SANDBOX');


$sql_PAYMAYA_SK="SELECT vValue FROM configurations WHERE vName='PAYMAYA_SK'";
$db_PAYMAYA_SK=$obj->MySQLSelect($sql_PAYMAYA_SK);

$sql_PAYMAYA_PK="SELECT vValue FROM configurations WHERE vName='PAYMAYA_PK'";
$db_PAYMAYA_PK=$obj->MySQLSelect($sql_PAYMAYA_PK);


$sql_PAYMAYA_COMMISON="SELECT vValue FROM configurations WHERE vName='PAYMAYA_COMMISON_DRIVER'";
$db_PAYMAYA_COMMISON=$obj->MySQLSelect($sql_PAYMAYA_COMMISON);

$PAYMAYA_SK = $db_PAYMAYA_SK[0]['vValue'];
$PAYMAYA_PK = $db_PAYMAYA_PK[0]['vValue'];
$PAYMAYA_COMMISON = floatval($db_PAYMAYA_COMMISON[0]['vValue']);

//PayMaya\PayMayaSDK::getInstance()->initCheckout('pk-nRO7clSfJrojuRmShqRbihKPLdGeCnb9wiIWF8meJE9', 'sk-jZK0i8yZ30ph8xQSWlNsF9AMWfGOd3BaxJjQ2CDCCZb', 'SANDBOX');


PayMaya\PayMayaSDK::getInstance()->initCheckout($PAYMAYA_PK,$PAYMAYA_SK , 'SANDBOX');


$sess_iUserId=$_REQUEST['UserId'];

$_SESSION['sess_iUserId']=$sess_iUserId;
    $sql = "select * from register_driver where iDriverId = '".$sess_iUserId."'";

    $user_details = $obj->MySQLSelect($sql);
$iBalance=$_REQUEST['Balance'];

$_SESSION['iBalance_Driver']=$iBalance;
$commision=(($iBalance*$PAYMAYA_COMMISON)/100);
$Peso =$iBalance+$commision;
$_SESSION['tokens_commision_Driver']=$commision;
$_SESSION['totalAmount_Driver']=$Peso;

do
{
$ref_no=generateRandomString();
$ref_no="D-".$ref_no;
$ref_query="select * from user_wallet where ref_No='$ref_no'";
 $ref = $obj->MySQLSelect($ref_query);
}while (count($ref) != 0);
$_SESSION['ref_no_Driver']=$ref_no;

$iAdminUserId=$_SESSION['sess_iCompanyId'];

$ePaymentStatus="";


$eType='';
$eUserType='Driver';
$iTripId=0;
 $eFor="";
// $tDescription='#LBL_PAYMENT_FAILURE#';
 $tDescription='#LBL_PAYMENT_FAILURE# \nReference number : '.$ref_no;
$dDate=date("Y-m-d H:i:s");
          $sql = "INSERT INTO `user_wallet` (`iUserId`,`eUserType`,`iBalance`,`iTripId`, `eFor`, `tDescription`, `ePaymentStatus`, `dDate`,`ref_No`,`ServiceFee`) VALUES ('" .$sess_iUserId. "','" . $eUserType . "', '" . $iBalance . "', '" . $iTripId . "', '" . $eFor . "', '" . $tDescription . "', '" . $ePaymentStatus . "', '" . $dDate . "','".$ref_no."','".$PAYMAYA_COMMISON."')";
          
        $result = $obj->MySQLInsert($sql);

        $sql = "SELECT * FROM currency WHERE eStatus = 'Active'";
        $db_curr = $obj->MySQLSelect($sql);
        $where = " iUserWalletId = '" . $result . "'";

        $_SESSION['iUserWalletId']=$result;

        for ($i = 0; $i < count($db_curr); $i++) {
            $data_currency_ratio['fRatio_' . $db_curr[$i]['vName']] = $db_curr[$i]['Ratio'];
            $obj->MySQLQueryPerform("user_wallet", $data_currency_ratio, 'update', $where);
        }



unset($_REQUEST);
        $itemCheckout = new PayMaya\API\Checkout();
        $address = new Address();
      //  $address->line1 = "6th Floor, LaunchPad Building";
       // $address->line2 = "Reliance Street";
      //  $address->city = "Mandaluyong City";
      //  $address->state = "Metro Manila";
       // $address->zipCode = $user_details[0]['vZip'];
       // $address->countryCode = "PH";
        $contact = new Contact();
        $contact->phone = $user_details[0]['vPhone'];
        $contact->email = $user_details[0]['vEmail'];
        $buyer = new Buyer();
        $buyer->firstName = $user_details[0]['vName']." ".$user_details[0]['vLastName'];
        $buyer->middleName = "";
        $buyer->lastName = "";
        $buyer->contact = $contact;
      //  $buyer->shippingAddress = $address;
       // $buyer->billingAddress = $address;
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
        $item->code = "";//$data['firstname'];
        $item->description = "";
        $item->quantity = $iBalance;
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
            "success" => "https://www.ninjaoncall.com/creditDriver.php?payment=success&refNo=$ref_no&eType=Credit",
            "failure" => "https://www.ninjaoncall.com/creditDriver.php?payment=failure",
            "cancel" => "https://www.ninjaoncall.com/creditDriver.php?payment=cancel"
        );
        $itemCheckout->execute();
        $itemCheckout->retrieve();

         header("Location:" . $itemCheckout->url);
 //echo "<script>window.location.href='$itemCheckout->url';</script>";

        echo "Checkout ID:  $itemCheckout->id \n"; // Checkout ID
        echo "Checkout URL: $itemCheckout->url \n"; // Checkout URL
      
function generateRandomString($length = 12) {
    //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>