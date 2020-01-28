<?php

include_once("common.php");


$Country=isset($_REQUEST['Country']) ?$_REQUEST['Country']:'';
$LastName=isset($_REQUEST['LastName']) ?$_REQUEST['LastName']:'';
$FirstName=isset($_REQUEST['FirstName']) ?$_REQUEST['FirstName']:'';
$Preferred_Name_to_Appear_on_the_Official_Name=isset($_REQUEST['Preferred_Name_to_Appear_on_the_Official_Name']) ?$_REQUEST['Preferred_Name_to_Appear_on_the_Official_Name']:'';
$Position_in_Scouting=isset($_REQUEST['Position_in_Scouting']) ?$_REQUEST['Position_in_Scouting']:'';
$Gender=isset($_REQUEST['Gender']) ?$_REQUEST['Gender']:'';
$Religion=isset($_REQUEST['Religion']) ?$_REQUEST['Religion']:'';
$Age=isset($_REQUEST['Age']) ?$_REQUEST['Age']:'';
$Email=isset($_REQUEST['Email']) ?$_REQUEST['Email']:'';
$TelephoneNumber=isset($_REQUEST['TelephoneNumber']) ?$_REQUEST['TelephoneNumber']:'';
$MobileNumber=isset($_REQUEST['MobileNumber']) ?$_REQUEST['MobileNumber']:'';
$HomeAddress=isset($_REQUEST['HomeAddress']) ?$_REQUEST['HomeAddress']:'';
$PassportNumber=isset($_REQUEST['PassportNumber']) ?$_REQUEST['PassportNumber']:'';
$Number_of_Accompanying_Person_to_be_Registered=isset($_REQUEST['Number_of_Accompanying_Person_to_be_Registered']) ?$_REQUEST['Number_of_Accompanying_Person_to_be_Registered']:'';
$Is_going_to_attend_more_than_one_event=isset($_REQUEST['Is_going_to_attend_more_than_one_event']) ?$_REQUEST['Is_going_to_attend_more_than_one_event']:'';
$Is_the_attendee_flight_going_to_Manila_already_been_booked=isset($_REQUEST['Is_the_attendee_flight_going_to_Manila_already_been_booked']) ?$_REQUEST['Is_the_attendee_flight_going_to_Manila_already_been_booked']:'';
$flight_going_to_Manila_Date=isset($_REQUEST['flight_going_to_Manila_Date']) ?$_REQUEST['flight_going_to_Manila_Date']:'';
$flight_going_to_Manila_Time=isset($_REQUEST['flight_going_to_Manila_Time']) ?$_REQUEST['flight_going_to_Manila_Time']:'';
$flight_going_to_Manila_Name_of_Airline=isset($_REQUEST['flight_going_to_Manila_Name_of_Airline']) ?$_REQUEST['flight_going_to_Manila_Name_of_Airline']:'';
$flight_going_to_Manila_Flight_Number=isset($_REQUEST['flight_going_to_Manila_Flight_Number']) ?$_REQUEST['flight_going_to_Manila_Flight_Number']:'';
$flight_going_to_Manila_Remarks=isset($_REQUEST['flight_going_to_Manila_Remarks']) ?$_REQUEST['flight_going_to_Manila_Remarks']:'';
$Is_the_attendee_flight_departing_from_Manila_already_been_booked=isset($_REQUEST[
  'Is_the_attendee_flight_departing_from_Manila_already_been_booked']) ?$_REQUEST['Is_the_attendee_flight_departing_from_Manila_already_been_booked']:'';
$flight_departing_from_Manila_Date=isset($_REQUEST['flight_departing_from_Manila_Date']) ?$_REQUEST['flight_departing_from_Manila_Date']:'';
$flight_departing_from_Manila_Time=isset($_REQUEST['flight_departing_from_Manila_Time']) ?$_REQUEST['flight_departing_from_Manila_Time']:'';
$flight_departing_from_Manila_Name_of_Airline=isset($_REQUEST['flight_departing_from_Manila_Name_of_Airline']) ?$_REQUEST['flight_departing_from_Manila_Name_of_Airline']:'';
$flight_departing_from_Manila_Flight_Number=isset($_REQUEST['flight_departing_from_Manila_Flight_Number']) ?$_REQUEST['flight_departing_from_Manila_Flight_Number']:'';
$flight_departing_from_Manila_Remarks=isset($_REQUEST['flight_departing_from_Manila_Remarks']) ?$_REQUEST['flight_departing_from_Manila_Remarks']:'';
$Accommodation_check_in=isset($_REQUEST['Accommodation_check_in']) ?$_REQUEST['Accommodation_check_in']:'';
$Accommodation_check_out=isset($_REQUEST['Accommodation_check_out']) ?$_REQUEST['Accommodation_check_out']:'';
$Hotel_Choice=isset($_REQUEST['Hotel_Choice']) ?$_REQUEST['Hotel_Choice']:'';
$Hotel_Room_Type=isset($_REQUEST['Hotel_Room_Type']) ? $_REQUEST['Hotel_Room_Type']:'';
$If_twin_room_or_double_room=isset($_REQUEST['If_twin_room_or_double_room']) ? $_REQUEST['If_twin_room_or_double_room']:'';
$Blood_Type=isset($_REQUEST['Blood Type']) ?$_REQUEST['Blood Type']:'';
$Any_dietary_requirements=isset($_REQUEST['Any_dietary_requirements']) ? $_REQUEST['Any_dietary_requirements']:'';
$If_dietary_requirements_yes_please_specify_requirements=isset($_REQUEST['If_dietary_requirements_yes_please_specify_requirements']) ?$_REQUEST['If_dietary_requirements_yes_please_specify_requirements']:'';
$help_in_case_of_emergency=isset($_REQUEST['help_in_case_of_emergency']) ? $_REQUEST['help_in_case_of_emergency']:'';
$Is_this_application_endorsed_by_the_NSO=isset($_REQUEST['Is_this_application_endorsed_by_the_NSO']) ?$_REQUEST['Is_this_application_endorsed_by_the_NSO']:'';
$Who_approved_the_endorsement=isset($_REQUEST['Who_approved_the_endorsement']) ? $_REQUEST['Who_approved_the_endorsement']:'';
$What_is_hisher_position_in_the_NSO=isset($_REQUEST['What_is_hisher_position_in_the_NSO']) ? $_REQUEST['What_is_hisher_position_in_the_NSO']:'';
$Date_of_Approval=isset($_REQUEST['Date_of_Approval']) ? $_REQUEST['Date_of_Approval']:'';
$NSO_Official_Email=isset($_REQUEST['NSO_Official_Email']) ? $_REQUEST['NSO_Official_Email']:'';
$Last_Name_of_the_Person_Completing_this_Form=isset($_REQUEST['Last_Name_of_the_Person_Completing_this_Form']) ? $_REQUEST['Last_Name_of_the_Person_Completing_this_Form']:'';
$First_Name_of_the_Person_Completing_this_Form=isset($_REQUEST['First_Name_of_the_Person_Completing_this_Form']) ? $_REQUEST['First_Name_of_the_Person_Completing_this_Form']:'';
$agree_that_any_information_you_entered=isset($_REQUEST['agree_that_any_information_you_entered']) ? $_REQUEST['agree_that_any_information_you_entered']:'';
$National_Scout_Organization=isset($_REQUEST['National_Scout_Organization']) ? $_REQUEST['National_Scout_Organization']:'';

$Date_of_Issue=isset($_REQUEST['Date_of_Issue']) ? $_REQUEST['Date_of_Issue']:'';
$Place_of_Issue=isset($_REQUEST['Place_of_Issue']) ? $_REQUEST['Place_of_Issue']:'';
$Date_of_Expiry=isset($_REQUEST['Date_of_Expiry']) ? $_REQUEST['Date_of_Expiry']:'';
$Date_of_Birth=isset($_REQUEST['Date_of_Birth']) ? $_REQUEST['Date_of_Birth']:'';
$Place_of_Birth=isset($_REQUEST['Place_of_Birth']) ? $_REQUEST['Place_of_Birth']:'';
$Nationality=isset($_REQUEST['Nationality']) ? $_REQUEST['Nationality']:'';

$UserId=isset($_REQUEST['UserId']) ? $_REQUEST['UserId']:'';

$Accompanying_Person_Name=array();
$Is_he_or_she_attending_the_Accompanying_Persons_Program=array();
$Persons_Category_for_the_26th_APR_Scout_Conference=array();
$Relationship=array();

for ($z=0; $z<10 ; $z++) { 
$i=$z+1;
$Accompanying_Person_Name[$z]=isset($_REQUEST[$i.'_Name']) ? $_REQUEST[$i.'_Name']:'';
$Is_he_or_she_attending_the_Accompanying_Persons_Program[$z]=isset($_REQUEST[$i.'_Is_he_or_she_attending_the_Accompanying_Persons_Program']) ? $_REQUEST[$i.'_Is_he_or_she_attending_the_Accompanying_Persons_Program']:'';
$Relationship[$z]=isset($_REQUEST[$i.'_Relationship']) ? $_REQUEST[$i.'_Relationship']:'';
$Persons_Category_for_the_26th_APR_Scout_Conference[$z]=isset($_REQUEST['Persons_Category_for_the_26th_APR_Scout_Conference'.$i]) ? $_REQUEST['Persons_Category_for_the_26th_APR_Scout_Conference'.$i]:'';

}

$Is_heshe_going_to_attend_more_than_one_event=isset($_REQUEST['Is_heshe_going_to_attend_more_than_one_event']) ? $_REQUEST['Is_heshe_going_to_attend_more_than_one_event']:'';
$Event_to_be_Attended_by_the_Participant=isset($_REQUEST['Event_to_be_Attended_by_the_Participant']) ? $_REQUEST['Event_to_be_Attended_by_the_Participant']:'';
$Registration_Fee_for_26th_APR_Scout_Conference_USD1=isset($_REQUEST['Registration_Fee_for_26th_APR_Scout_Conference_USD1']) ? $_REQUEST['Registration_Fee_for_26th_APR_Scout_Conference_USD1']:'';
$Registration_Fee_for_9th_APR_Scout_Youth_Forum_USD=isset($_REQUEST['Registration_Fee_for_9th_APR_Scout_Youth_Forum_USD']) ? $_REQUEST['Registration_Fee_for_9th_APR_Scout_Youth_Forum_USD']:'';
$Registration_Fee_for_26th_APR_Scout_Conference_USD2=isset($_REQUEST['Registration_Fee_for_26th_APR_Scout_Conference_USD2']) ? $_REQUEST['Registration_Fee_for_26th_APR_Scout_Conference_USD2']:'';
$If_attending_separate_ancillary_events_throughout_the_duration=isset($_REQUEST['If_attending_separate_ancillary_events_throughout_the_duration']) ? $_REQUEST['If_attending_separate_ancillary_events_throughout_the_duration']:'';
$Participants_Category_for_26th_APR_Scout_Conference1=isset($_REQUEST['Participants_Category_for_26th_APR_Scout_Conference1']) ? $_REQUEST['Participants_Category_for_26th_APR_Scout_Conference1']:'';
$Participants_Category_for_26th_APR_Scout_Conference2=isset($_REQUEST['Participants_Category_for_26th_APR_Scout_Conference2']) ? $_REQUEST['Participants_Category_for_26th_APR_Scout_Conference2']:'';
$Participants_Category_for_9th_APR_Scout_Youth_Forum1=isset($_REQUEST['Participants_Category_for_9th_APR_Scout_Youth_Forum1']) ? $_REQUEST['Participants_Category_for_9th_APR_Scout_Youth_Forum1']:'';
$Participants_Category_for_9th_APR_Scout_Youth_Forum2=isset($_REQUEST['Participants_Category_for_9th_APR_Scout_Youth_Forum2']) ? $_REQUEST['Participants_Category_for_9th_APR_Scout_Youth_Forum2']:'';
$Participants_Category_for_APR_Course_for_Leader_Trainers=isset($_REQUEST['Participants_Category_for_APR_Course_for_Leader_Trainers']) ? $_REQUEST['Participants_Category_for_APR_Course_for_Leader_Trainers']:'';
$Nationality=isset($_REQUEST['Nationality']) ? $_REQUEST['Nationality']:'';
$Total_Amount=isset($_REQUEST['Total_Amount']) ? $_REQUEST['Total_Amount']:'';
$Customer_ID=isset($_REQUEST['Customer_ID']) ? $_REQUEST['Customer_ID']:'';
$IP_Address=isset($_REQUEST['IP_Address']) ? $_REQUEST['IP_Address']:'';
$ID=isset($_REQUEST['ID']) ? $_REQUEST['ID']:'';
$UTM_Source=isset($_REQUEST['UTM_Source']) ? $_REQUEST['UTM_Source']:'';
$UTM_Medium=isset($_REQUEST['UTM_Medium']) ? $_REQUEST['UTM_Medium']:'';
$UTM_Campaign=isset($_REQUEST['UTM_Campaign']) ? $_REQUEST['UTM_Campaign']:'';
$UTM_Term=isset($_REQUEST['UTM_Term']) ? $_REQUEST['UTM_Term']:'';
$UTM_Content=isset($_REQUEST['UTM_Content']) ? $_REQUEST['UTM_Content']:'';
$Notification=isset($_REQUEST['Notification']) ? $_REQUEST['Notification']:'';

$Productaprsc_accompanying1=isset($_REQUEST['Productaprsc_accompanying1']) ? $_REQUEST['Productaprsc_accompanying1']:'';
$Productaprsc_accompanying2=isset($_REQUEST['Productaprsc_accompanying2']) ? $_REQUEST['Productaprsc_accompanying2']:'';
$Productaprsc_accompanying3=isset($_REQUEST['Productaprsc_accompanying3']) ? $_REQUEST['Productaprsc_accompanying3']:'';
$Productaprsc_accompanying4=isset($_REQUEST['Productaprsc_accompanying4']) ? $_REQUEST['Productaprsc_accompanying4']:'';
$Productaprsc_accompanying5=isset($_REQUEST['Productaprsc_accompanying5']) ? $_REQUEST['Productaprsc_accompanying5']:'';
$Productaprsc_accompanying6=isset($_REQUEST['Productaprsc_accompanying6']) ? $_REQUEST['Productaprsc_accompanying6']:'';
$Productaprsc_accompanying7=isset($_REQUEST['Productaprsc_accompanying7']) ? $_REQUEST['Productaprsc_accompanying7']:'';
$Productaprsc_accompanying8=isset($_REQUEST['Productaprsc_accompanying8']) ? $_REQUEST['Productaprsc_accompanying8']:'';
$Productaprsc_accompanying9=isset($_REQUEST['Productaprsc_accompanying9']) ? $_REQUEST['Productaprsc_accompanying9']:'';
$Productaprsc_accompanying10=isset($_REQUEST['Productaprsc_accompanying10']) ? $_REQUEST['Productaprsc_accompanying10']:'';
$Productaprsc_cd=isset($_REQUEST['Productaprsc_cd']) ? $_REQUEST['Productaprsc_cd']:'';

$Productaprsc_d=isset($_REQUEST['Productaprsc_d']) ? $_REQUEST['Productaprsc_d']:'';
$Productaprsc_o=isset($_REQUEST['Productaprsc_o']) ? $_REQUEST['Productaprsc_o']:'';
$Productaprsc_cm=isset($_REQUEST['Productaprsc_cm']) ? $_REQUEST['Productaprsc_cm']:'';
$Productaprsc_sm=isset($_REQUEST['Productaprsc_sm']) ? $_REQUEST['Productaprsc_sm']:'';
$Productaprsc_g=isset($_REQUEST['Productaprsc_g']) ? $_REQUEST['Productaprsc_g']:'';
$Productaprsc_ap=isset($_REQUEST['Productaprsc_ap']) ? $_REQUEST['Productaprsc_ap']:'';
$Productaprsc_yp=isset($_REQUEST['Productaprsc_yp']) ? $_REQUEST['Productaprsc_yp']:'';
$Productaprsc_i=isset($_REQUEST['Productaprsc_i']) ? $_REQUEST['Productaprsc_i']:'';
$Productaprsc_s=isset($_REQUEST['Productaprsc_s']) ? $_REQUEST['Productaprsc_s']:'';
$Productaprsc_non_apr_obs=isset($_REQUEST['Productaprsc_non_apr_obs']) ? $_REQUEST['Productaprsc_non_apr_obs']:'';
$Productaprsc_non_apr_ap=isset($_REQUEST['Productaprsc_non_apr_ap']) ? $_REQUEST['Productaprsc_non_apr_ap']:'';
$Productaprsc_non_apr_i=isset($_REQUEST['Productaprsc_non_apr_i']) ? $_REQUEST['Productaprsc_non_apr_i']:'';
$Productaprsyf_yfod=isset($_REQUEST['Productaprsyf_yfod']) ? $_REQUEST['Productaprsyf_yfod']:'';
$Productaprsyf_yfo=isset($_REQUEST['Productaprsyf_yfo']) ? $_REQUEST['Productaprsyf_yfo']:'';
$Productaprsc_syf=isset($_REQUEST['Productaprsc_syf']) ? $_REQUEST['Productaprsc_syf']:'';
$ConferenceAttendeeRole=isset($_REQUEST['ConferenceAttendeeRole']) ? $_REQUEST['ConferenceAttendeeRole']:'';
$Conference_register_Details_Id=isset($_REQUEST['Conference_register_Details_Id']) ? $_REQUEST['Conference_register_Details_Id']:'';

$validate=true;
$msg="";

if($Country=="")
{
$msg.="Please select country\n";
$validate=false;
}
if($LastName=="")
  {
  $msg.="Please enter Last Name\n";
  $validate=false;
}
if($FirstName=="")
  {
   $msg.="Please enter First Name\n";
   $validate=false;
}
if($Preferred_Name_to_Appear_on_the_Official_Name=="")
  {
   $msg.="Please enter Preferred_Name_to_Appear_on_the_Official_Name\n";
   $validate=false;
}
if($Position_in_Scouting=="")
  {
     $msg.="Please enter Position in Scouting\n";
     $validate=false;
}
if($Gender=="")
  {
      $msg.="Please select Gender\n";
      $validate=false;
}
if($Religion=="")
  {
    $msg.="Please enter Religion\n";
    $validate=false;
}
if($Age=="")
  {
   $msg.="Please enter age\n";
   $validate=false;
}
if($Email=="")
  {
    $msg.="Please enter Email\n";
    $validate=false;
}
if($PassportNumber=="")
  {
     $msg.="Please enter Passport Number\n";
     $validate=false;
}
if ($ConferenceAttendeeRole=="") {
     $msg.="Please select Conference Attendee Role\n";
     $validate=false;
}
if ($UserId=="") {
     $msg.="Please log in first\n";
     $validate=false;
}
$sql="select Email from `conference` where Email='$Email'";
 $result=$obj->MySQLSelect($sql);
 if (count( $result)>0) {

      $msg.="Email id already exists\n";
     $validate=false;
 }

if ($validate) 
{

$sql="INSERT INTO `conference`(`Submitted_At`, `Country`,`Last_Name_exactly_shown_in_the_passport`, `First_Name_exactly_shown_in_the_passport`, `Preferred_Name`, `Position_in_Scouting`, `Gender`, `Religion`, `Age`, `Email`, `Telephone_Number`, `Mobile_Number`, `Home_Address`, `Passport_Number`, `Number_of_Accompanying_Person_to_be_Registered`,`Hotel_Choice`, `Hotel_Room_Type1`, `Any_dietary_requirements`, `Is_this_application_endorsed_by_the_NSO`,`Is_the_attendees_flight_going_to_Manila_already_been_booked`, `Date1`, `Time1`, `Name_of_Airline1`, `Flight_Number1`, `Remarks1`, `Is_the_attendees_flight_departing_from_Manila`, `Date2`, `Time2`, `Name_of_Airline2`, `Flight_Number2`, `Remarks2`, `Accommodation_Checkin_Date`, `Accommodation_Checkout_Date`,`Hotel_Room_Type2`, `If_twin_room_or_double_room`, `Blood_Type`,`If_yes_please_specify_requirements`, `help_in_case_of_emergency`, `Who_approved_the_endorsement`,`What_is_hisher_position_in_the_NSO`, `Date_of_Approval`, `NSO_Official_Email`, `Last_Name_of_the_Person_Completing_this_Form`, `First_Name_of_the_Person_Completing_this_Form`, `agree_that_any_information_you_entered`,National_Scout_Organization,`Date_of_Issue`, `Place_of_Issue`, `Date_of_Expiry`, `Date_of_Birth`, `Place_of_Birth`, `Nationality`,`1_Name`, `1_Relationship`,`1_Is_he_or_she_attending_the_Accompanying_Persons_Program`, `Persons_Category_for_the_26th_APR_Scout_Conference1`, `2_Name`, `2_Relationship`, `2_Is_he_or_she_attending_the_Accompanying_Persons_Program`, `Persons_Category_for_the_26th_APR_Scout_Conference2`, `3_Name`, `3_Relationship`, `3_Is_he_or_she_attending_the_Accompanying_Persons_Program`, `Persons_Category_for_the_26th_APR_Scout_Conference3`, `4_Name`, `4_Relationship`, `4_Is_he_or_she_attending_the_Accompanying_Persons_Program`, `Persons_Category_for_the_26th_APR_Scout_Conference4`, `5_Name`, `5_Relationship`, `5_Is_he_or_she_attending_the_Accompanying_Persons_Program`, `Persons_Category_for_the_26th_APR_Scout_Conference5`, `6_Name`, `6_Relationship`, `6_Is_he_or_she_attending_the_Accompanying_Persons_Program`, `Persons_Category_for_the_26th_APR_Scout_Conference6`, `7_Name`, `7_Relationship`, `7_Is_he_or_she_attending_the_Accompanying_Persons_Program`, `Persons_Category_for_the_26th_APR_Scout_Conference8`, `8_Name`, `8_Relationship`, `8_Is_he_or_she_attending_the_Accompanying_Persons_Program`, `Persons_Category_for_the_26th_APR_Scout_Conference7`, `9_Name`, `9_Relationship`, `9_Is_he_or_she_attending_the_Accompanying_Persons_Program`, `Persons_Category_for_the_26th_APR_Scout_Conference9`, `10_Name`, `10_Relationship`, `10_Is_he_or_she_attending_the_Accompanying_Persons_Program`, `Persons_Category_for_the_26th_APR_Scout_Conference10`, `Is_heshe_going_to_attend_more_than_one_event`, `Event_to_be_Attended_by_the_Participant`, `Registration_Fee_for_26th_APR_Scout_Conference_USD1`, `Registration_Fee_for_26th_APR_Scout_Conference_USD2`, `Registration_Fee_for_9th_APR_Scout_Youth_Forum_USD`, `If_attending_separate_ancillary_events_throughout_the_duration`, `Participants_Category_for_26th_APR_Scout_Conference1`, `Participants_Category_for_26th_APR_Scout_Conference2`, `Participants_Category_for_9th_APR_Scout_Youth_Forum1`, `Participants_Category_for_9th_APR_Scout_Youth_Forum2`, `Participants_Category_for_APR_Course_for_Leader_Trainers`, `Total_Amount`, `Customer_ID`, `IP_Address`, `ID`, `UTM_Source`, `UTM_Medium`, `UTM_Campaign`, `UTM_Term`, `UTM_Content`, `Notification`,
 `Productaprsc_accompanying1`, `Productaprsc_accompanying2`, `Productaprsc_accompanying3`, `Productaprsc_accompanying4`, `Productaprsc_accompanying5`, `Productaprsc_accompanying6`, `Productaprsc_accompanying7`, `Productaprsc_accompanying8`, `Productaprsc_accompanying9`, `Productaprsc_accompanying10`, `Productaprsc_cd`, `Productaprsc_d`, `Productaprsc_o`, `Productaprsc_cm`, `Productaprsc_sm`, `Productaprsc_g`, `Productaprsc_ap`, `Productaprsc_yp`, `Productaprsc_i`, `Productaprsc_s`, `Productaprsc_non_apr_obs`, `Productaprsc_non_apr_ap`, `Productaprsc_non_apr_i`, `Productaprsyf_yfod`, `Productaprsyf_yfo`, `Productaprsc_syf`,ConferenceAttendeeRole,UserId,Conference_register_Details_Id) VALUES ('".date('Y-m-d H:i:s')."','$Country','$LastName','$FirstName','$Preferred_Name_to_Appear_on_the_Official_Name','$Position_in_Scouting','$Gender','$Religion','$Age','$Email','$TelephoneNumber','$MobileNumber','$HomeAddress','$PassportNumber','$Number_of_Accompanying_Person_to_be_Registered','$Hotel_Choice','$Hotel_Room_Type','$Any_dietary_requirements','$Is_this_application_endorsed_by_the_NSO','$Is_the_attendee_flight_going_to_Manila_already_been_booked','$flight_going_to_Manila_Date','$flight_going_to_Manila_Time','$flight_going_to_Manila_Name_of_Airline','$flight_going_to_Manila_Flight_Number','$flight_going_to_Manila_Remarks','$Is_the_attendee_flight_departing_from_Manila_already_been_booked','$flight_departing_from_Manila_Date','$flight_departing_from_Manila_Time','$flight_departing_from_Manila_Name_of_Airline','$flight_departing_from_Manila_Flight_Number','$flight_departing_from_Manila_Remarks','$Accommodation_check_in','$Accommodation_check_out','$Hotel_Room_Type','$If_twin_room_or_double_room','$Blood_Type','$If_dietary_requirements_yes_please_specify_requirements','$help_in_case_of_emergency','$Who_approved_the_endorsement','$What_is_hisher_position_in_the_NSO','$Date_of_Approval','$NSO_Official_Email','$Last_Name_of_the_Person_Completing_this_Form','$First_Name_of_the_Person_Completing_this_Form','$agree_that_any_information_you_entered','$National_Scout_Organization','$Date_of_Issue','$Place_of_Issue','$Date_of_Expiry','$Date_of_Birth','$Place_of_Birth','$Nationality','$Accompanying_Person_Name[0]','$Relationship[0]','$Is_he_or_she_attending_the_Accompanying_Persons_Program[0]','$Persons_Category_for_the_26th_APR_Scout_Conference[0]','$Accompanying_Person_Name[1]','$Relationship[1]','$Is_he_or_she_attending_the_Accompanying_Persons_Program[1]','$Persons_Category_for_the_26th_APR_Scout_Conference[1]','$Accompanying_Person_Name[2]','$Relationship[2]','$Is_he_or_she_attending_the_Accompanying_Persons_Program[2]','$Persons_Category_for_the_26th_APR_Scout_Conference[2]','$Accompanying_Person_Name[3]','$Relationship[3]','$Is_he_or_she_attending_the_Accompanying_Persons_Program[3]','$Persons_Category_for_the_26th_APR_Scout_Conference[3]','$Accompanying_Person_Name[4]','$Relationship[4]','$Is_he_or_she_attending_the_Accompanying_Persons_Program[4]','$Persons_Category_for_the_26th_APR_Scout_Conference[4]','$Accompanying_Person_Name[5]','$Relationship[5]','$Is_he_or_she_attending_the_Accompanying_Persons_Program[5]','$Persons_Category_for_the_26th_APR_Scout_Conference[5]','$Accompanying_Person_Name[6]','$Relationship[6]','$Is_he_or_she_attending_the_Accompanying_Persons_Program[6]','$Persons_Category_for_the_26th_APR_Scout_Conference[6]','$Accompanying_Person_Name[7]','$Relationship[7]','$Is_he_or_she_attending_the_Accompanying_Persons_Program[7]','$Persons_Category_for_the_26th_APR_Scout_Conference[7]','$Accompanying_Person_Name[8]','$Relationship[8]','$Is_he_or_she_attending_the_Accompanying_Persons_Program[8]','$Persons_Category_for_the_26th_APR_Scout_Conference[8]','$Accompanying_Person_Name[9]','$Relationship[9]','$Is_he_or_she_attending_the_Accompanying_Persons_Program[9]','$Persons_Category_for_the_26th_APR_Scout_Conference[9]','$Is_heshe_going_to_attend_more_than_one_event','$Event_to_be_Attended_by_the_Participant','$Registration_Fee_for_26th_APR_Scout_Conference_USD1','$Registration_Fee_for_26th_APR_Scout_Conference_USD2','$Registration_Fee_for_9th_APR_Scout_Youth_Forum_USD','$If_attending_separate_ancillary_events_throughout_the_duration','$Participants_Category_for_26th_APR_Scout_Conference1','$Participants_Category_for_26th_APR_Scout_Conference2','$Participants_Category_for_9th_APR_Scout_Youth_Forum1','$Participants_Category_for_9th_APR_Scout_Youth_Forum2','$Participants_Category_for_APR_Course_for_Leader_Trainers','$Total_Amount','$Customer_ID','$IP_Address','$ID','$UTM_Source','$UTM_Medium','$UTM_Campaign','$UTM_Term','$UTM_Content','$Notification','$Productaprsc_accompanying1','$Productaprsc_accompanying2','$Productaprsc_accompanying3','$Productaprsc_accompanying4','$Productaprsc_accompanying5','$Productaprsc_accompanying6','$Productaprsc_accompanying7','$Productaprsc_accompanying8','$Productaprsc_accompanying9','$Productaprsc_accompanying10','$Productaprsc_cd','$Productaprsc_d','$Productaprsc_o','$Productaprsc_cm','$Productaprsc_sm','$Productaprsc_g','$Productaprsc_ap','$Productaprsc_yp','$Productaprsc_i','$Productaprsc_s','$Productaprsc_non_apr_obs','$Productaprsc_non_apr_ap','$Productaprsc_non_apr_i','$Productaprsyf_yfod','$Productaprsyf_yfo','$Productaprsc_syf','$ConferenceAttendeeRole','$UserId','$Conference_register_Details_Id')";



 $obj->MySQLSelect($sql);
 

    echo "success";

}
else
{

echo $msg;

}



/*

,

$1_Name=isset($_REQUEST['1_Name']) ? $_REQUEST['1_Name']:'';
$2_Name=isset($_REQUEST['2_Name']) ? $_REQUEST['2_Name']:'';
$3_Name=isset($_REQUEST['3_Name']) ? $_REQUEST['3_Name']:'';
$4_Name=isset($_REQUEST['4_Name']) ? $_REQUEST['4_Name']:'';
$5_Name=isset($_REQUEST['5_Name']) ? $_REQUEST['5_Name']:'';
$6_Name=isset($_REQUEST['6_Name']) ? $_REQUEST['6_Name']:'';
$7_Name=isset($_REQUEST['7_Name']) ? $_REQUEST['7_Name']:'';
$8_Name=isset($_REQUEST['8_Name']) ? $_REQUEST['8_Name']:'';
$9_Name=isset($_REQUEST['9_Name']) ? $_REQUEST['9_Name']:'';
$10_Name=isset($_REQUEST['10_Name']) ? $_REQUEST['10_Name']:'';


$1_Is_he_or_she_attending_the_Accompanying_Persons_Program=isset($_REQUEST['1_Is_he_or_she_attending_the_Accompanying_Persons_Program']) ? $_REQUEST['1_Is_he_or_she_attending_the_Accompanying_Persons_Program']:'';
$2_Is_he_or_she_attending_the_Accompanying_Persons_Program=isset($_REQUEST['2_Is_he_or_she_attending_the_Accompanying_Persons_Program']) ? $_REQUEST['2_Is_he_or_she_attending_the_Accompanying_Persons_Program']:'';
$3_Is_he_or_she_attending_the_Accompanying_Persons_Program=isset($_REQUEST['3_Is_he_or_she_attending_the_Accompanying_Persons_Program']) ? $_REQUEST['3_Is_he_or_she_attending_the_Accompanying_Persons_Program']:'';
$4_Is_he_or_she_attending_the_Accompanying_Persons_Program=isset($_REQUEST['4_Is_he_or_she_attending_the_Accompanying_Persons_Program']) ? $_REQUEST['4_Is_he_or_she_attending_the_Accompanying_Persons_Program']:'';
$5_Is_he_or_she_attending_the_Accompanying_Persons_Program=isset($_REQUEST['5_Is_he_or_she_attending_the_Accompanying_Persons_Program']) ? $_REQUEST['5_Is_he_or_she_attending_the_Accompanying_Persons_Program']:'';
$6_Is_he_or_she_attending_the_Accompanying_Persons_Program=isset($_REQUEST['6_Is_he_or_she_attending_the_Accompanying_Persons_Program']) ? $_REQUEST['6_Is_he_or_she_attending_the_Accompanying_Persons_Program']:'';
$7_Is_he_or_she_attending_the_Accompanying_Persons_Program=isset($_REQUEST['7_Is_he_or_she_attending_the_Accompanying_Persons_Program']) ? $_REQUEST['7_Is_he_or_she_attending_the_Accompanying_Persons_Program']:'';
$8_Is_he_or_she_attending_the_Accompanying_Persons_Program=isset($_REQUEST['8_Is_he_or_she_attending_the_Accompanying_Persons_Program']) ? $_REQUEST['8_Is_he_or_she_attending_the_Accompanying_Persons_Program']:'';
*/

 ?>