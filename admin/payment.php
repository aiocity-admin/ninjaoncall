
<?php


include_once('../common.php');
//error_reporting(0);
//ini_set('display_errors', 1);
require  '../PayMaya-PHP-SDK-master/sample/autoload.php';
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
PayMaya\PayMayaSDK::getInstance()->initCheckout('pk-nRO7clSfJrojuRmShqRbihKPLdGeCnb9wiIWF8meJE9', 'sk-jZK0i8yZ30ph8xQSWlNsF9AMWfGOd3BaxJjQ2CDCCZb', 'SANDBOX');

    $sql = "select * from administrators where iAdminId = '". $_SESSION['sess_iAdminUserId']."'";

    $user_details = $obj->MySQLSelect($sql);
$tokens=$_REQUEST['tokens'];

$_SESSION['tokens']=$tokens;
$Peso =$tokens;

        $itemCheckout = new PayMaya\API\Checkout();
        $address = new Address();
      //  $address->line1 = "6th Floor, LaunchPad Building";
       // $address->line2 = "Reliance Street";
      //  $address->city = "Mandaluyong City";
      //  $address->state = "Metro Manila";
       // $address->zipCode = $user_details[0]['vZip'];
       // $address->countryCode = "PH";
        $contact = new Contact();
        $contact->phone = $user_details[0]['vContactNo'];
        $contact->email = $user_details[0]['vEmail'];
        $buyer = new Buyer();
        $buyer->firstName = $user_details[0]['vFirstName'];
        $buyer->middleName = "";
        $buyer->lastName = $user_details[0]['vLastName'];
        $buyer->contact = $contact;
      //  $buyer->shippingAddress = $address;
       // $buyer->billingAddress = $address;
        $itemCheckout->buyer = $buyer;
        $itemAmountDetails = new PayMaya\Model\Checkout\ItemAmountDetails();
        $itemAmountDetails->shippingFee = "00.00";
        $itemAmountDetails->tax = "00.00";
        $itemAmountDetails->subtotal = $Peso;
        $itemAmountDetails->serviceCharge = "00.00";
        $itemAmount = new PayMaya\Model\Checkout\ItemAmount();
        $itemAmount->currency = "PHP";
        $itemAmount->value = $Peso;
        $itemAmount->details = $itemAmountDetails;
        $item = new PayMaya\Model\Checkout\Item();
        $item->value = 1;
        $item->name = "Tokens";
        $item->code = "";//$data['firstname'];
        $item->description = "";
        $item->quantity = $tokens;
        $item->amount = $itemAmount;
        $item->totalAmount = $itemAmount;
        $itemCheckout->items = array($item);
        $itemCheckout->totalAmount = $itemAmount;
        // The requestReferenceNumber is an identifier for the customer's order.
     $itemCheckout->requestReferenceNumber = "123456789";
        // Redirect URLs are used to direct the customer to the correct
        // page based on the transaction outcome.
        // Redirect URLs should not be used to set the status of an order
        // to "paid" as the customer might quit the browser prior the redirection.
        $itemCheckout->redirectUrl = array(
            "success" => "http://localhost/ninjatest/admin/tokens.php?payment=success",
            "failure" => "http://localhost/ninjatest/admin/tokens.php?payment=failure",
            "cancel" => "http://localhost/ninjatest/admin/tokens.php?payment=cancel"
        );
        $itemCheckout->execute();
        $itemCheckout->retrieve();

         header("Location:" . $itemCheckout->url);
        echo "Checkout ID:  $itemCheckout->id \n"; // Checkout ID
        echo "Checkout URL: $itemCheckout->url \n"; // Checkout URL
      


?>




 


