<?php                     
  require_once('lib/Braintree.php');       
  require_once('config.php');
  //https://github.com/yabacon/paystack-php/blob/master/SAMPLES.md
  
  //echo "<pre>";print_r($paystack);exit;
  try
  {
    $result = $gateway->customer()->create([
                      'firstName' => 'Chintan',
                      'lastName' => 'Shah',
                      //'company' => 'Chintan Global Co.',
                      'email' => 'chintan.icreate1@gmail.com'
                      //'phone' => '919824012546',
                      //'fax' => '919824012546',
                      //'website' => 'http://chintanshah.com'
                  ]);
                  
    $result->success;
    # true
    
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