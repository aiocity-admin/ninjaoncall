<?php
include_once('../common.php');


$sql="SELECT vValue FROM configurations WHERE vName='PERFORMANCE_THRESHOLD'";

$db_con=$obj->MySQLSelect($sql);

$threshold=$db_con[0]['vValue'];

$to= date('Y-m-d');
$from=date('Y-m-d');
$to= $to." 23:59:59";
$from= $from." 00:00:00";

$q="SELECT avg(`TotalTime`) TotalTime  FROM `performance` WHERE  `TimeFrom`>='$from' and TimeTo<='$to' and eType='SIGNIN'";
$result=$obj->MySQLSelect($q);
$TotalTime_SINGIN=$result[0]['TotalTime'];
If(trim($TotalTime_SINGIN)=="")
$TotalTime_SINGIN=0;

$q="SELECT avg(`TotalTime`) TotalTime FROM `performance` WHERE  `TimeFrom`>='$from' and TimeTo<='$to' and eType like'%REPORT%' AND UserType='COMPANY'";
$result=$obj->MySQLSelect($q);
$TotalTime_COMPANY_REPORTS=$result[0]['TotalTime'];
If(trim($TotalTime_COMPANY_REPORTS)=="")
$TotalTime_COMPANY_REPORTS=0;


$q="SELECT avg(`TotalTime`) TotalTime FROM `performance` WHERE  `TimeFrom`>='$from' and TimeTo<='$to' and eType like'%REPORT%' AND UserType='SUPER_ADMIN'";

$result=$obj->MySQLSelect($q);
$TotalTime_ADMIN_REPORTS=$result[0]['TotalTime'];
If(trim($TotalTime_ADMIN_REPORTS)=="")
$TotalTime_ADMIN_REPORTS=0;

/*$TotalTime_SINGIN=10;
$TotalTime_ADMIN_REPORTS=10;
$threshold=5;*/
if ($TotalTime_SINGIN>=$threshold) {

	sendMail($TotalTime_SINGIN." Seconds",$threshold." SECONDS",'Sign in');
}

if ($TotalTime_COMPANY_REPORTS>=$threshold) {

		sendMail($TotalTime_COMPANY_REPORTS." Seconds",$threshold." SECONDS",'Company Pages');

}
if ($TotalTime_ADMIN_REPORTS>=$threshold) {

		sendMail($TotalTime_ADMIN_REPORTS." Seconds",$threshold." SECONDS",'Admin Pages');

}

function sendMail($current,$threshold, $CONTENT)
{
global $obj;
global $generalobj;
	$nortification_query="SELECT `Event`, `ActionBy`, `NotifyCompany`, `NotifyProvider`, `NotifyCustomer`, `NotifyAdministrator`, `AdditionalEmail` FROM `nortification_settings` WHERE `Event`='Performance'";

$result_nortification = $obj->MySQLSelect($nortification_query);
if(count($result_nortification)>0)
{

if($result_nortification[0]['AdditionalEmail']!="")
{
      
                $maildata['ADDITIONALEMAIL'] = $result_nortification[0]['AdditionalEmail'];
                $maildata['CURRENT'] = $current;
                $maildata['CONTENT'] = $CONTENT;
                $maildata['THRESHOLD']=$threshold;

        $generalobj->send_email_user("SERVER_PERFORMANCE",$maildata); 

}



            if($result_nortification[0]['NotifyAdministrator']=="on")
                {
                $maildata['ADMIN'] = '1';
                $maildata['CURRENT'] = $current;
                $maildata['CONTENT'] =  $CONTENT;
                $maildata['THRESHOLD']=$threshold;

                $generalobj->send_email_user("SERVER_PERFORMANCE",$maildata);            
                   }
            
  }

}
?>
 <?php
$date = new DateTime($from);
$date->modify('-7 day');
$tomorrowDATE = $date->format('Y-m-d H:i:s');


$q="delete FROM `performance` WHERE  `TimeFrom`<='$tomorrowDATE'";
$obj->sql_query($q);
 ?>