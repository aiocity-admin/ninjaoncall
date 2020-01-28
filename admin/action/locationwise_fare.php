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
$iLocatioId = isset($_REQUEST['iLocatioId']) ? $_REQUEST['iLocatioId'] : '';
$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
$statusVal = isset($_REQUEST['statusVal']) ? $_REQUEST['statusVal'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'view';
$hdn_del_id = isset($_REQUEST['hdn_del_id']) ? $_REQUEST['hdn_del_id'] : '';
$checkbox = isset($_REQUEST['checkbox']) ? implode(',',$_REQUEST['checkbox']) : '';
$method = isset($_REQUEST['method']) ? $_REQUEST['method'] : '';

//Start make deleted
if ($method == 'delete' && $iLocatioId != '') {
    if(SITE_TYPE !='Demo'){
            $query = "DELETE FROM location_wise_fare WHERE iLocatioId = '".$iLocatioId."'";
            $obj->sql_query($query);
            $_SESSION['success'] = '1';
            $_SESSION['var_msg'] = 'Location Wise Fare deleted successfully.';   
    }
    else{
            $_SESSION['success'] = '2';
    }
    header("Location:".$tconfig["tsite_url_main_admin"]."locationwise_fare.php?".$parameters); exit;
}
//End make deleted

//Start Change single Status
if ($iLocatioId != '' && $status != '') {
    if(SITE_TYPE !='Demo'){
            $query = "UPDATE location_wise_fare SET eStatus = '".$status."' WHERE iLocatioId = '".$iLocatioId."'";
            $obj->sql_query($query);
            $_SESSION['success'] = '1';
            if($status == 'Active') {
                   $_SESSION['var_msg'] = 'Location Wise Fare activated successfully.';
            }else {
                   $_SESSION['var_msg'] = 'Location Wise Fare inactivated successfully.';
            }
    }
    else{
            $_SESSION['success']= 2;
    }
        header("Location:".$tconfig["tsite_url_main_admin"]."locationwise_fare.php?".$parameters);
        exit;
}
//End Change single Status

//Start Change All Selected Status
if($checkbox != "" && $statusVal != "") {
    if(SITE_TYPE !='Demo'){
         $query = "UPDATE location_wise_fare SET eStatus = '" . $statusVal . "' WHERE iLocatioId IN (" . $checkbox . ")";
         $obj->sql_query($query);
         $_SESSION['success'] = '1';
         $_SESSION['var_msg'] = 'Location Wise Fare(s) updated successfully.';
    }
    else{
        $_SESSION['success']=2;
    }
        header("Location:".$tconfig["tsite_url_main_admin"]."locationwise_fare.php?".$parameters);
        exit;
}
//End Change All Selected Status

?>