<?php                     
  require_once('lib/Braintree.php');       
  require_once('config.php');
  //https://github.com/yabacon/paystack-php/blob/master/SAMPLES.md
  
  //echo "<pre>";print_r($paystack);exit;
  try
  {
    $result = $gateway->transaction()->sale(
              [
                //'paymentMethodToken' => '4747xs',
                'paymentMethodToken' => '3tc677',
                'amount' => '5.00'
              ]
            );
                  
    $result->success;
    # true
    
    # Generated customer id
    echo "Status >> ".$result->success;echo "<br />";
    //echo "Token >> ".$result->creditCard->token;echo "<br />";
    //echo "uniqueNumberIdentifier >> ".$result->creditCard->uniqueNumberIdentifier;echo "<br />";
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