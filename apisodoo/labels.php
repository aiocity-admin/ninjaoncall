<?php
include_once('../common.php');
include_once("authentication.php");

$data["retrunCode"]=1;
$data["data"]=$langage_lbl_admin;

echo json_encode($data);
?>