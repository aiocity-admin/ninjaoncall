<?php include_once('common.php');

if (!isset($generalobjAdmin)) {

    require_once(TPATH_CLASS . "class.general_admin.php");

    $generalobjAdmin = new General_admin();

}

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$type= isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
$extraField_db=isset($_REQUEST['extraField']) ? $_REQUEST['extraField']: '';
$extraField_db=explode(",", $extraField_db);
$query_extra="select * from ExtraFields where doc_masterid='$id'  and vFor='$type'";
 $db_extraFields = $obj->MySQLSelect($query_extra);

	 if(count($db_extraFields)>0)
{
    $z=1;
foreach ($db_extraFields as $key => $value) {
    $isRequired=trim($value['IsRequired'])=="1"?"required":"";
   ?>
   <div class="row_id_<?=$id;?>" >
   <div class="col-md-6">
   <label><?=$value['vLabel'];?></label>
   <input type="text" value="<?=$extraField_db[$z-1]?>"  name="extraField[]" id="extraField_<?=$z;?>" class="extraField form-control"  <?=$isRequired;?> />
   </div>   <span id="extraField_<?=$z;?>-error" class="help-block"></span>

</div>
   <?php
   $z++;
}
    
} ?>
