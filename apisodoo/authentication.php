<?php
$data=array();

if (isset($_REQUEST['key'])) {

  $api_key=$_REQUEST['key'];

  $query="select * from keys_of_apis where api_key='$api_key'";
$result=$obj->MySQLSelect($query);
if (count($result)<=0) {
$data["returnCode"]=0;
$data["error"]="Wrong Api key.";
echo json_encode($data);

exit;
}
else
{
	$api_id=$result[0]['id'];
	$api_url=$_SERVER['REQUEST_URI'];
	$date= Date('Y-m-d H:i:s');
	$qury="INSERT INTO `api_log`(`userid`, `api`,`date`) VALUES ('$api_id','$api_url','$date')";
		$obj->sql_query($qury); 	
}
}
else
{
$data["returnCode"]=0;
$data["error"]="key is required. Api key not found.";
echo json_encode($data);
exit;
}
?>