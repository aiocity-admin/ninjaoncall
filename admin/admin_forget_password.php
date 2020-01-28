<?php
	include_once('../common.php');
	$email = isset($_POST['femail'])?$_POST['femail']:'';
	//$action = isset($_POST['action'])?$_POST['action']:'';


		$sql = "SELECT `iAdminId`, `iGroupId`, CONCAT(`vFirstName`,' ', `vLastName`) as Name, `vEmail`, `vContactNo`, `vPassword`, `eStatus`, `eDefault` from administrators WHERE vEmail = '".$email."' and eStatus != 'Deleted'";
		$db_login = $obj->MySQLSelect($sql);

		if(count($db_login)>0)
		{

$mail['NAME']=$db_login[0]['Name'];
$mail['EMAIL']=$db_login[0]['vEmail'];



$forgetPasswordToken= generateRandomString();

$query="update administrators set forgetPasswordToken='".$forgetPasswordToken."'  WHERE iAdminId='".$db_login[0]['iAdminId']."'";
                    $obj->sql_query($query);

  $iAdminId=$generalobj->encrypt($db_login[0]['iAdminId']);

  $forgetPasswordToken=$generalobj->encrypt($forgetPasswordToken);

$url = $tconfig["tsite_url"].'admin/admin_reset_password.php?token='.$iAdminId."&key=".$forgetPasswordToken;
			$activation_text = '<a href="'.$url.'" target="_blank"> Reset Password </a>';

$mail['LINK']=$activation_text;

			$status = $generalobj->send_email_user("CUSTOMER_RESET_PASSWORD",$mail);
			if($status == 1)
			{
				$var_msg = "Reset Password link has been sent Successfully.";
				$error_msg = "1";
			}
			else
			{
				$var_msg = "Error in Sending password.";
				$error_msg = "0";
			}
		}
			else
			{
				 $var_msg = "Sorry ! The Email address you have entered is not found.";
				 $error_msg = "3";
			}

echo json_encode(array("errorCode"=> $error_msg,"msg"=>$var_msg));



      
function generateRandomString($length =4) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   //   $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
