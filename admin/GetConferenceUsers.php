<?php
include_once('../common.php');

$ConferenceAttendeeRole=$_REQUEST['ConferenceAttendeeRole'];

if (trim($ConferenceAttendeeRole)=="All") {
 $sql = "select First_Name_exactly_shown_in_the_passport,Last_Name_exactly_shown_in_the_passport,UserId from conference";
}
else
{
    $sql = "select First_Name_exactly_shown_in_the_passport,Last_Name_exactly_shown_in_the_passport,UserId from conference where ConferenceAttendeeRole='$ConferenceAttendeeRole'";
    
}
$user_details = $obj->MySQLSelect($sql);

    echo json_encode($user_details);

?>