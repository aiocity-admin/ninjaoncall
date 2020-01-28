<?php

//date_default_timezone_set('Asia/Calcutta'); 

// Code for Local Server Only

error_reporting(0);

ini_set('memory_limit', '1024M');

$hst_arr = explode("/",$_SERVER["REQUEST_URI"]);

$hst_var = $hst_arr[1];





if($UpdateDatabase == 'yes')

{

	define( 'TSITE_SERVER',$hostName);

	define( 'TSITE_DB',$databaseName);

	define( 'TSITE_USERNAME',$userName);

	define( 'TSITE_PASS',$passwordName);

}

else

{



	if($_SERVER["HTTP_HOST"] == "localhost")

	{

		define( 'TSITE_SERVER','localhost');

		define( 'TSITE_DB','gz89f3do_ninjatest');

		define( 'TSITE_USERNAME','gz89f3do_ninja');

		define( 'TSITE_PASS','dyG5U60Zv6eB');

	}

	else

	{

		define( 'TSITE_SERVER','localhost');

		define( 'TSITE_DB','gz89f3do_ninjatest');

		define( 'TSITE_USERNAME','gz89f3do_ninja');

		define( 'TSITE_PASS','dyG5U60Zv6eB');

	}

	

}





/* function get_langcode($lang) {

	$sql = mysqli_query("SELECT vGMapLangCode FROM language_master WHERE vCode = '".$lang."'");

	$result = mysqli_fetch_object($sql);

	return $result->vGMapLangCode;

} */





?>