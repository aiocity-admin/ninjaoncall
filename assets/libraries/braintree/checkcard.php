<?php                     
  require_once('lib/Braintree.php');       
  require_once('config.php');
  //https://developers.braintreepayments.com/reference/request/payment-method/find/php
  
  //echo "<pre>";print_r($paystack);exit;
  try
  {
    $result = $gateway->paymentMethod()->find('4747xs');
                  
    $result->success;
    # true
    
    # Generated customer id
    echo "customerId >> ".$result->customerId;echo "<br />";
    echo "Token >> ".$result->creditCard->token;echo "<br />";
    echo "uniqueNumberIdentifier >> ".$result->creditCard->uniqueNumberIdentifier;echo "<br />";
    echo "<pre>";print_r($result);exit;                  
    
    /*$result = get_object_vars($customer);
    $status = $result['status']; // 1 
    $response = get_object_vars($result['data']); 
    $vStripcusId = $response['customer_code'];
    $id = $response['id']; 
    echo "<pre>";print_r($response);
    echo "<pre>";print_r($result);exit;*/
  } catch(Exception $e){
echo    $error3 = $e->getMessage(); 
    echo "<pre>";print_r($e);
    die($e);
  }

  exit;

?>