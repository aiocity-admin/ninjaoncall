<?php
$username = "";
$password = "";

$con = mysqli_connect("localhost","gz89f3do_ninja","dyG5U60Zv6eB","gz89f3do_ninjatest");

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
echo "connected";



if(mysqli_query($con,"GRANT ALL PRIVILEGES ON *.* TO 'gz89f3do_ninja'@'localhost' IDENTIFIED BY 'dyG5U60Zv6eB'"))
{
	echo "exe 0";
}else
{
		echo "error 0";

}

if(mysqli_query($con,'set global SQL_MODE="NO_ENGINE_SUBSTITUTION"'))
{
	echo "exe 1";
}else
{
		echo "error 1";

}

        $query = "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))";
if(mysqli_query($con,$query))
{
		echo "exe 2";
}
else
{
		echo "error 2";

}
mysqli_close($con);



//$obj->MySQLSelect('set global SQL_MODE="NO_ENGINE_SUBSTITUTION"');

?>