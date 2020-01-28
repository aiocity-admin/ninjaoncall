<?php                     
  require_once('lib/Braintree.php');       
  require_once('config.php');
  //https://github.com/yabacon/paystack-php/blob/master/SAMPLES.md
  
  //echo "<pre>";print_r($paystack);exit;
  try
  {
    $result = $gateway->customer()->create([
                      'firstName' => 'Mike',
                      'lastName' => 'Jones',
                      'company' => 'Jones Co.',
                      'email' => 'mike.jones@example.com',
                      'phone' => '281.330.8004',
                      'fax' => '419.555.1235',
                      'website' => 'http://example.com'
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