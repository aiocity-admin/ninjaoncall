<?php                     
  require_once('lib/Braintree.php');       
  require_once('config.php');
  //https://github.com/yabacon/paystack-php/blob/master/SAMPLES.md
  //https://developers.braintreepayments.com/guides/paypal/server-side/php
  
  try
  {
  $result = $gateway->transaction()->sale([
  'amount' => '2.00',
  //'paymentMethodNonce' => "d2a95cfb-cf21-0f53-6ede-b3d656e31d6c",
  'paymentMethodNonce' => "0709b18b-9db6-0c30-5d18-defd4dcb2619",
  'customerId' => '845388334',
  'options' => [
    'storeInVaultOnSuccess' => true,
  ]
]);

if ($result->success) {
  // See $result->transaction for details
} else {
  // Handle errors
}
                  
    $result->success;
    # true
    echo "<pre>";print_r($result);exit;
    $result->customer->id;
    # Generated customer id
    echo "Status >> ".$result->success;echo "<br />";
    echo "Customer Id >> ".$result->customer->id;echo "<br />";
    echo "<pre>";print_r($result);exit;                  
    
    /*$result = get_object_vars($customer);
    $status = $result['status']; // 1 
    $response = get_object_vars($result['data']); 
    $vStripcusId = $response['customer_code'];
    $id = $response['id']; 
    echo "<pre>";print_r($response);
    echo "<pre>";print_r($result);exit;*/
  } catch(Exception $e){
    print_r($e);
    die($e);
  }

  exit;

?>