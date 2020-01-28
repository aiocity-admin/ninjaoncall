<?php                     
  require_once('lib/Braintree.php');       
  require_once('config.php');
  //https://github.com/yabacon/paystack-php/blob/master/SAMPLES.md
  
  //echo "<pre>";print_r($paystack);exit;
  try
  {
    $updateResult = $gateway->customer()->update(
                                    '867528419',
                                    [
                                      'firstName' => 'Mike1',
                                      'lastName' => 'Jones 1',
                                      'company' => 'Jones Co.1',
                                      'email' => 'mike.jones1@example.com',
                                      'phone' => '281.330.8004',
                                      'fax' => '419.555.1235',
                                      'website' => 'http://example1.com'
                                    ]
                                );
  
  
    $updateResult->success;
    # true
    
    $updateResult->customer->id;
    # Generated customer id
    echo "Status >> ".$updateResult->success;echo "<br />";
    echo "Customer Id >> ".$updateResult->customer->id;echo "<br />";
    echo "<pre>";print_r($updateResult);exit;                  
    
    /*$result = get_object_vars($customer);
    $status = $result['status']; // 1 
    $response = get_object_vars($result['data']); 
    $vStripcusId = $response['customer_code'];
    $id = $response['id']; 
    echo "<pre>";print_r($response);
    echo "<pre>";print_r($result);exit;*/
  } catch(Exception $e){
    echo "Message >>".$e->getMessage(); 
    echo "<pre>";print_r($e);
    die($e);
  }

  exit;

?>