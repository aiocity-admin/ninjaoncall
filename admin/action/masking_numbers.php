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

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$masknum_id = isset($_REQUEST['masknum_id']) ? $_REQUEST['masknum_id'] : '';
$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
$statusVal = isset($_REQUEST['statusVal']) ? $_REQUEST['statusVal'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'view';
$hdn_del_id = isset($_REQUEST['hdn_del_id']) ? $_REQUEST['hdn_del_id'] : '';
$checkbox = isset($_REQUEST['checkbox']) ? implode(',',$_REQUEST['checkbox']) : '';
$method = isset($_REQUEST['method']) ? $_REQUEST['method'] : '';


//Start Masking Number deleted
if ($method == 'delete' && $masknum_id != '') {
	if(SITE_TYPE !='Demo'){
            $query = "DELETE FROM masking_numbers WHERE masknum_id = '" . $masknum_id . "'";
            $obj->sql_query($query);
            $_SESSION['success'] = '1';
            $_SESSION['var_msg'] = 'Masking Number deleted successfully.';   
	}
	else{
            $_SESSION['success'] = '2';
	}
	header("Location:".$tconfig["tsite_url_main_admin"]."masking_numbers.php?".$parameters); exit;
}
//End Masking Number deleted

//Start Change single Status
if ($masknum_id != '' && $status != '') {
	if(SITE_TYPE !='Demo'){
            $query = "UPDATE masking_numbers SET eStatus = '" . $status . "' WHERE masknum_id = '" . $masknum_id . "'";
            $obj->sql_query($query);
            $_SESSION['success'] = '1';
            if($status == 'Active') {
                   $_SESSION['var_msg'] = 'Masking Number activated successfully.';
            }else {
                   $_SESSION['var_msg'] = 'Masking Number inactivated successfully.';
            }
	}
	else{
            $_SESSION['success']=2;
	}
        header("Location:".$tconfig["tsite_url_main_admin"]."masking_numbers.php?".$parameters);
        exit;
}
//End Change single Status

//Start Change All Selected Status
if($checkbox != "" && $statusVal != "") {
	if(SITE_TYPE !='Demo'){
        if($statusVal == "Deleted"){
            $query = "DELETE FROM masking_numbers WHERE masknum_id IN (" . $checkbox . ")";
            $obj->sql_query($query);
            $_SESSION['success'] = '1';
            $_SESSION['var_msg'] = 'Masking Number(s) delete successfully.';
        } else {
            $query = "UPDATE masking_numbers SET eStatus = '" . $statusVal . "' WHERE masknum_id IN (" . $checkbox . ")";
            $obj->sql_query($query);
            $_SESSION['success'] = '1';
            $_SESSION['var_msg'] = 'Masking Number(s) updated successfully.';
        }
	}
	else{
		$_SESSION['success']=2;
	}
        header("Location:".$tconfig["tsite_url_main_admin"]."masking_numbers.php?".$parameters);
        exit;
}
//End Change All Selected Status

?>