<?php
include_once('../common.php');


		$loadtime  = isset($_REQUEST["loadtime"]) ? $_REQUEST["loadtime"] : '';
		$beforeload  = isset($_REQUEST["beforeload"]) ? $_REQUEST["beforeload"] : '';
		$afterload  = isset($_REQUEST["afterload"]) ? $_REQUEST["afterload"] : '';
		$eType  = isset($_REQUEST["eType"]) ? $_REQUEST["eType"] : '';
		$UserType  = isset($_REQUEST["UserType"]) ? $_REQUEST["UserType"] : '';


$diff_query="INSERT INTO `performance`(`eType`, `TimeFrom`, `TimeTo`, `TotalTime`,`UserType`) VALUES ('$eType','$beforeload','$afterload','$loadtime','$UserType')";
$obj->sql_query($diff_query);
echo "success";
?>