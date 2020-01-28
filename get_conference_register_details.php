<?php

include_once("common.php");

$UserId=isset($_REQUEST['UserId']) ?$_REQUEST['UserId']:'';


	$sql="select *  from  `conference_register_details` where UserId='$UserId'";
 $result=$obj->MySQLSelect($sql);

if (count( $result)>0) {
echo json_encode($result);

}
else
{
	//echo "No Record Found";
	echo json_encode(array());
}


?>