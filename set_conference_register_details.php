<?php

include_once("common.php");



$UserId=isset($_REQUEST['UserId']) ?$_REQUEST['UserId']:'';
$DateofBirth=isset($_REQUEST['DateofBirth']) ?$_REQUEST['DateofBirth']:'';

$PositionInScouting=isset($_REQUEST['PositionInScouting']) ?$_REQUEST['PositionInScouting']:'';
$Gender=isset($_REQUEST['Gender']) ?$_REQUEST['Gender']:'';
$Religion=isset($_REQUEST['Religion']) ?$_REQUEST['Religion']:'';
$NationalScoutIDNumber=isset($_REQUEST['NationalScoutIDNumber']) ?$_REQUEST['NationalScoutIDNumber']:'';
$NationalScoutOrganization=isset($_REQUEST['NationalScoutOrganization']) ?$_REQUEST['NationalScoutOrganization']:'';

$Country=isset($_REQUEST['Country']) ?$_REQUEST['Country']:'';
$YearEnteredScouting=isset($_REQUEST['YearEnteredScouting']) ?$_REQUEST['YearEnteredScouting']:'';



$validate=true;
$msg="";

if($DateofBirth=="")
{
$msg.="Please select Date of Birth\n";
$validate=false;
}
if($PositionInScouting=="")
  {
  $msg.="Please enter Position In Scouting\n";
  $validate=false;
}
if($Gender=="")
  {
   $msg.="Please select Gender\n";
   $validate=false;
}
if($Religion=="")
  {
   $msg.="Please enter your Religion\n";
   $validate=false;
}
if($NationalScoutOrganization=="")
  {
     $msg.="Please enter National Scout Organization\n";
     $validate=false;
}

if($Country=="")
  {
     $msg.="Please select Country\n";
     $validate=false;
}
if($YearEnteredScouting=="")
  {
     $msg.="Please select Year Entered Scouting\n";
     $validate=false;
}


if ($validate) 
{
	$sql="INSERT INTO `conference_register_details`(`UserId`, `DateofBirth`, `PositionInScouting`, `Gender`, `Religion`, `NationalScoutIDNumber`, `NationalScoutOrganization`, `Country`, `YearEnteredScouting`) VALUES ('$UserId','$DateofBirth','$PositionInScouting','$Gender','$Religion','$NationalScoutIDNumber','$NationalScoutOrganization','$Country','$YearEnteredScouting')";
 $obj->MySQLSelect($sql);
 

    echo "success";

}
else
{

	echo $msg;
}

?>
