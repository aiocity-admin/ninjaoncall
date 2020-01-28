<?php
//include_once('../../../include_taxi_webservices.php');
//include_once('../configuration.php');
$gateway = new Braintree_Gateway([
    'environment' => 'sandbox',
    'merchantId' => 'vcpmh24mb72p6355',
    'publicKey' => '25354477ks5t6nwb',
    'privateKey' => '7f9e0688dcf6b1426c44098f508e529f'
]);

/*$config = new Braintree_Configuration([
    'environment' => 'sandbox',
    'merchantId' => 'vcpmh24mb72p6355',
    'publicKey' => '25354477ks5t6nwb',
    'privateKey' => '7f9e0688dcf6b1426c44098f508e529f'
]);
$gateway = new Braintree\Gateway($config);    */
//echo "<pre>";print_r($gateway);exit;
?>