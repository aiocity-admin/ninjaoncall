<?php
//phpinfo();
error_reporting(1);
$to = "anuragr.bbcs@gmail.com";
$subject = "Email script from ninjaoncall.com test script ".date('Y-m-d H:i:s');
$message = "";
$message .= "Email script from ninjaoncall.com test script ".date('Y-m-d H:i:s')."<br>";
$header = "From:admin@ninjaoncall.com \r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-type: text/html\r\n";
echo "<hr>";
echo $emailsend1 = mail ($to,$subject,$message,$header);
echo "<hr>";

$to1 = "admin@ninjaoncall.com";

echo "<hr>";
echo $emailsend1 = mail ($to1,$subject,$message,$header);
echo "<hr>";
?>