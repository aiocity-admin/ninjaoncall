<?php
include_once('../../common.php');

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();

$reload = $_SERVER['REQUEST_URI']; 

$urlparts = explode('?',$reload);
$parameters = $urlparts[1];

$iAirportLocationId = isset($_REQUEST['iAirportLocationId']) ? $_REQUEST['iAirportLocationId'] : '';
$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
$statusVal = isset($_REQUEST['statusVal']) ? $_REQUEST['statusVal'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'view';
$hdn_del_id = isset($_REQUEST['hdn_del_id']) ? $_REQUEST['hdn_del_id'] : '';
$checkbox = isset($_REQUEST['checkbox']) ? implode(',',$_REQUEST['checkbox']) : '';
$method = isset($_REQUEST['method']) ? $_REQUEST['method'] : '';

//Start Location deleted
if ($method == 'delete' && $iAirportLocationId != '') {
	if(SITE_TYPE !='Demo'){
        $query = "DELETE FROM airport_location_master WHERE iAirportLocationId = '" . $iAirportLocationId . "'";
        $obj->sql_query($query);
        $_SESSION['success'] = '1';
        $_SESSION['var_msg'] = 'Airport FIFO Zone Deleted Successfully.';   
	} else {
            $_SESSION['success'] = '2';
	}
	header("Location:".$tconfig["tsite_url_main_admin"]."location-airport.php?".$parameters); exit;
}
//End Location deleted

//Start Change single Status
if ($iAirportLocationId != '' && $status != '') {
	if(SITE_TYPE !='Demo') {
            $query = "UPDATE airport_location_master SET eStatus = '" . $status . "' WHERE iAirportLocationId = '" . $iAirportLocationId . "'";
            $obj->sql_query($query);
            $_SESSION['success'] = '1';
            if($status == 'Active') {
                   $_SESSION['var_msg'] = 'Airport FIFO Zone activated successfully.';
            }else {
                   $_SESSION['var_msg'] = 'Airport FIFO Zone inactivated successfully.';
            }

	} else {
            $_SESSION['success']=2;
	}
        header("Location:".$tconfig["tsite_url_main_admin"]."location-airport.php?".$parameters);
        exit;
}
//End Change single Status

//Start Change All Selected Status
if($checkbox != "" && $statusVal != "") {
	if(SITE_TYPE !='Demo'){
        if($statusVal == "Deleted") {
            $query = "DELETE FROM airport_location_master WHERE iAirportLocationId IN (" . $checkbox . ")";
            $obj->sql_query($query);
            $_SESSION['success'] = '1';
            $_SESSION['var_msg'] = 'Airport FIFO Zone(s) deleted successfully.';
        } else {
            $query = "UPDATE airport_location_master SET eStatus = '" . $statusVal . "' WHERE iAirportLocationId IN (" . $checkbox . ")";
            $obj->sql_query($query);
            $_SESSION['success'] = '1';
            $_SESSION['var_msg'] = 'Airport FIFO Zone(s) updated successfully.';
        }
	}
	else{
		$_SESSION['success']=2;
	}
        header("Location:".$tconfig["tsite_url_main_admin"]."location-airport.php?".$parameters);
        exit;
}
//End Change All Selected Status

?>