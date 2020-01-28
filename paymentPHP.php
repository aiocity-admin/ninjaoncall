<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<?php

error_reporting(0);
ini_set('display_errors', 1);
require __DIR__  . '/PayMaya-PHP-SDK-master/sample/autoload.php';
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


        $itemCheckout = new PayMaya\API\Checkout();
        $address = new Address();
        $address->line1 = "6th Floor, LaunchPad Building";
        $address->line2 = "Reliance Street";
        $address->city = "Mandaluyong City";
        $address->state = "Metro Manila";
        $address->zipCode = "12345";
        $address->countryCode = "PH";
        $contact = new Contact();
        $contact->phone = "+63(2)1234567890";
        $contact->email = "paymayabuyer1@gmail.com";
        $buyer = new Buyer();
        $buyer->firstName = "Juan";
        $buyer->middleName = "Reyes";
        $buyer->lastName = "Santos";
        $buyer->contact = $contact;
        $buyer->shippingAddress = $address;
        $buyer->billingAddress = $address;
        $itemCheckout->buyer = $buyer;
        $itemAmountDetails = new PayMaya\Model\Checkout\ItemAmountDetails();
        $itemAmountDetails->shippingFee = "0.00";
        $itemAmountDetails->tax = "0.00";
        $itemAmountDetails->subtotal = "2590.00";
        $itemAmountDetails->serviceCharge = "90.00";
        $itemAmount = new PayMaya\Model\Checkout\ItemAmount();
        $itemAmount->currency = "PHP";
        $itemAmount->value = "2590.00";
        $itemAmount->details = $itemAmountDetails;
        $item = new PayMaya\Model\Checkout\Item();
        $item->value = "2500.00";
        $item->name = "Some Item";
        $item->code = $data['firstname'];
        $item->description = "Some Item";
        $item->quantity = "1";
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
            "success" => "http://localhost/sucess.php?id=123456789",
            "failure" => "http://localhost/failure.php",
            "cancel" => "http://localhost/cancel.php"
        );
        $itemCheckout->execute();
        $itemCheckout->retrieve();
      //  print_r(   $itemCheckout->retrieve());
        // Uncomment to redirect the user to the Checkout page.
         header("Location:" . $itemCheckout->url);
        echo "Checkout ID:  $itemCheckout->id \n"; // Checkout ID
        echo "Checkout URL: $itemCheckout->url \n"; // Checkout URL
        //exit;


?>




 


