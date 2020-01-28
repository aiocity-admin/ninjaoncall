<?php
include_once('../common.php');

$vEmail = isset($_POST['vEmail']) ? $_POST['vEmail'] : '';
$vPassword = isset($_POST['vPassword']) ? $_POST['vPassword'] : '';
$remember= isset($_POST['remember']) ? $_POST['remember'] : '';
$iGroupId = isset($_POST['iGroupId']) ? $_POST['iGroupId'] : '';
   // $vPass = $generalobj->encrypt($vPassword);

    $sql = "select * from administrators where iGroupId='$iGroupId' and vEmail='$vEmail'";
    
    //echo  $sql;


$db_login = $obj->MySQLSelect($sql);

if (count($db_login)>0) {
$hash = $db_login[0]['vPassword'];
 		$checkValid=$generalobj->check_password($vPassword, $hash);
  if($checkValid)
  {

  	echo 1;

       $_SESSION['sess_iAdminUserId']=$db_login[0]['iAdminId'];
		$_SESSION['sess_iGroupId']=$db_login[0]['iGroupId'];
		$_SESSION["sess_vAdminFirstName"]=$db_login[0]['vFirstName'];
		$_SESSION["sess_vAdminLastName"]=$db_login[0]['vLastName'];
		$_SESSION["sess_vAdminEmail"]=$db_login[0]['vEmail'];
			$_SESSION["isConferenceAdmin"]=1;
			
		if($remember == "Yes")
		{
			setcookie ("member_login_cookie", $vEmail, time()+2592000);
			setcookie ("member_password_cookie", $vPassword, time()+2592000);
		}
		else
		{
			setcookie ("member_login_cookie", "", time());
			setcookie ("member_password_cookie", "", time());
		}

  }
  else
  {
echo 0;

  }
} else
  {
echo 0;

  }

?>