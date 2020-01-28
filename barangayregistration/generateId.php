<?php
	include_once('../common.php');
$type=isset($_REQUEST['type']) ? $_REQUEST['type'] : '';

 do
 {
$id=generateRandomString();
if($type=="tag")
{
$query="select * from barangay_inhabitants_head where tag_number='$id'";

}else
{
$query="select * from barangay_inhabitants_head where gen_barangay_ID='$id'";

}
$result = $obj->MySQLSelect($query);
 }while (count($result) != 0);
//print_r($result);
echo $id;

function generateRandomString($length = 12) {
    $characters = '0123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>