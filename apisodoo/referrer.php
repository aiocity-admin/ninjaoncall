<?php
include_once('../common.php');
include_once("authentication.php");

$type = (isset($_REQUEST['referrertype']) && $_REQUEST['referrertype'] != '') ? $_REQUEST['referrertype'] : 'Driver';

if($type == 'Driver') {
	 $total_no_driver=array();
    $sql1 = "SELECT CONCAT(vName,' ',vLastName) AS currentdriverName,eStatus,iDriverId FROM register_driver";
    $data_drv1 = $obj->MySQLSelect($sql1);
    foreach ($data_drv1 as $key => $value) {
       $q1 = "SELECT CONCAT(rd.vName,' ',rd.vLastName) AS OrgdriverName,rd.eRefType as eRefType,rd1.iDriverId,rd1.iRefUserId FROM register_driver as rd LEFT JOIN register_driver as rd1 on rd1.iRefUserId=rd.iDriverId WHERE rd1.iRefUserId = '".$value['iDriverId']."' AND rd1.eRefType = 'Driver'";
        $data_drv2 = $obj->MySQLSelect($q1);

        $q2 = "SELECT CONCAT(ru.vName,' ',ru.vLastName) AS passangerName,CONCAT(rd1.vName,' ',rd1.vLastName) AS OrgdriverName,ru.eRefType as eRefType , ru.iUserId,ru.iRefUserId FROM register_user as ru LEFT JOIN register_driver as rd1 on rd1.iDriverId=ru.iRefUserId WHERE ru.iRefUserId = '".$value['iDriverId']."' AND ru.eRefType = 'Driver'";
        $data_drv3 = $obj->MySQLSelect($q2);
        if(!empty($data_drv2)){
            $results[$data_drv2[0]['iRefUserId']][] =  $data_drv2;
        }

        if(!empty($data_drv3)){
            $results[$data_drv3[0]['iRefUserId']][] =  $data_drv3;
        }

    }
    foreach ($results as $key => $value) {
       foreach ($value as $ky => $ve) {
          foreach ($ve as $k => $v) {
              $total_no_driver[$key][] = $v;
          }

       }
    }
$data1=array();
   if(count($total_no_driver) > 0){
                                                        $i=0;
                                                        foreach ($total_no_driver as $k => $val){
                                                          $temp["TotalBalance"]=  $generalobj->getTotalbalance($k, 'Driver');
                                                  
                                                             $temp["Name"]=$val[0]['OrgdriverName'];
                                                         $temp["TotalMembers"]=    count($val);
                                                 $data1[]=$temp;

                                                       $i++;
                                                    } 
                                                    } 

$data["retrunCode"]=1;
$data["data"]= $data1;

echo json_encode($data);
} else{
	 $total_no_user=array();

    $sql1 = "SELECT CONCAT(vName,' ',vLastName) AS currentUserName,eStatus,iUserId FROM register_user $ord";
    $data_rider1 = $obj->MySQLSelect($sql1);
    foreach ($data_rider1 as $key => $value) {
        $q1 = "SELECT CONCAT(ru.vName,' ',ru.vLastName) AS OrgdriverName,ru.eRefType as eRefType,rd1.iDriverId,rd1.iRefUserId FROM register_user as ru LEFT JOIN register_driver as rd1 on rd1.iRefUserId=ru.iUserId WHERE rd1.iRefUserId = '".$value['iUserId']."' AND rd1.eRefType = 'Rider'";
        $data_drv2 = $obj->MySQLSelect($q1);

        $q2 = "SELECT CONCAT(ru1.vName,' ',ru1.vLastName) AS OrgdriverName,ru.eRefType as eRefType , ru.iUserId,ru.iRefUserId FROM register_user as ru LEFT JOIN register_user as ru1 on ru1.iUserId=ru.iRefUserId WHERE ru.iRefUserId = '".$value['iUserId']."' AND ru.eRefType = 'Rider'";
        $data_drv3 = $obj->MySQLSelect($q2);

        if(!empty($data_drv2)){
            $result[$data_drv2[0]['iRefUserId']][] =  $data_drv2;
        }

        if(!empty($data_drv3)){
            $result[$data_drv3[0]['iRefUserId']][] =  $data_drv3;
        }
    }
    
    foreach ($result as $key => $value) {
       foreach ($value as $ky => $ve) {
          foreach ($ve as $k => $v) {
              $total_no_user[$key][] = $v;
          }

       }
    }
  $data1=array();
    if(count($total_no_user) > 0){
                                                        $i=0;
                                                        foreach ($total_no_user as $k => $val){
                                                          $temp["TotalBalance"]=  $generalobj->getTotalbalance($k, 'Rider');
                                                  
                                                             $temp["Name"]=$val[0]['OrgdriverName'];
                                                         $temp["TotalMembers"]=    count($val);
                                                             $data1[]=$temp;
                                                       $i++;
                                                    } 
                                                    } 

$data["retrunCode"]=1;
$data["data"]= $data1;

echo json_encode($data);
}

?>