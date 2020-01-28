<?php
include_once('common.php');
?><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">


<style type="text/css">
  .alert{
    margin-top: 40px;
    padding: 50px;
  }

</style>
<?
if ($_REQUEST['payment'] =='success') 
{
  $url_ref_no=$_REQUEST['refNo'];
  $ref_No=$_SESSION['ref_no_Driver'];

if (trim($url_ref_no)==trim($ref_No)) {
  # code...


$payment=$_SESSION['totalAmount_Driver'];
$commission=$_SESSION['tokens_commision_Driver'];

$dDate=date("Y-m-d H:i:s");

    $eFor = 'Withdrawl';
    $eType = $_REQUEST['eType'];
    $iTripId = 0;
 //$tDescription = '#LBL_AMOUNT_CREDIT#';  
  $tDescription = '#LBL_AMOUNT_CREDIT#     Reference number : '.$ref_No;  

        $ePaymentStatus = 'Settelled';

       $iUserWalletId= $_SESSION['iUserWalletId'];


        $where = " iUserWalletId = '" . $iUserWalletId . "'";

                    $data_currency_ratio['eType'] = $eType;

                    $data_currency_ratio['tDescription'] = $tDescription;
                                        $data_currency_ratio['ePaymentStatus'] = $ePaymentStatus;

    $data_currency_ratio['eFor'] = $eFor;

    $data_currency_ratio['ePaymentStatus'] = $ePaymentStatus;
        $data_currency_ratio['dDate'] = $dDate;


 $obj->MySQLQueryPerform("user_wallet", $data_currency_ratio, 'update', $where);

$_SESSION['ref_no_Driver']="";
$_SESSION['totalAmount_Driver']="";
$_SESSION['tokens_commision_Driver']="";

echo '<center><div class="alert alert-success">
      <!--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->

 <i class="fa fa-check" aria-hidden="true"></i> <strong>Payment successfully done.</strong>
</div></center>';

}
else{
  echo '<center><div class="alert alert-danger">
      <!--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->

 <i class="fa fa-check" aria-hidden="true"></i> <strong>Payment Failure.</strong>
</div></center>';
}

   }
   else {

echo '<center>
<div class="alert alert-danger">
     <!-- <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->

 <i class="fa fa-check" aria-hidden="true"></i> <strong>Payment Failure.</strong>
</div></center>';

   }

     ?>