<?
include '../common.php';
$iVehicleCategoryId = isset($_REQUEST['iVehicleCategoryId']) ? $_REQUEST['iVehicleCategoryId'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$tbl_name = 'vehicle_type';

if(!empty($iVehicleCategoryId)){
    $select_order = $obj->MySQLSelect("SELECT count(iVehicleTypeId) AS iDisplayOrder FROM ".$tbl_name." WHERE iVehicleCategoryId = ".$iVehicleCategoryId ."");
    $iDisplayOrder  = isset($select_order[0]['iDisplayOrder']) ? $select_order[0]['iDisplayOrder'] : 0;
    $iDisplayOrder  = $iDisplayOrder + 1; // Maximum order number
    
	$iDisplayOrder = !empty($_REQUEST['iDisplayOrder']) ? $_REQUEST['iDisplayOrder'] : $iDisplayOrder;
    $temp = 1;

	$query1 = $obj->MySQLSelect("SELECT count(iVehicleTypeId) as maxnumber FROM ".$tbl_name." WHERE iVehicleCategoryId = ".$iVehicleCategoryId." ORDER BY iDisplayOrder");
	$maxnum = isset($query1[0]['maxnumber']) ? $query1[0]['maxnumber'] : 0;
	$dataArray = array();
	for ($i=1; $i <= $maxnum ; $i++) { 
	$dataArray[] = $i;
	$temp = $iDisplayOrder;
	}
	?>
	<select name="iDisplayOrder" class="form-control">
      <? foreach($dataArray as $arr):?>
      <option <?= $arr == $temp ? ' selected="selected"' : '' ?> value="<?=$arr;?>" >
        -- <?= $arr ?> --
      </option>
      <? endforeach; ?>
      <?if($action=="Add") {?>
        <option value="<?=$temp;?>">-- <?= $temp ?> -- </option>
      <? }?>
    </select>
<?php exit(); } ?>