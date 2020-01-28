<?php
include_once('common.php');

require_once(TPATH_CLASS . "Imagecrop.class.php");
$thumb = new thumbnail();
$rowid = isset($_REQUEST['rowid']) ? $_REQUEST['rowid'] : '';
$id = explode('-',$rowid);
$dropdownFields=array();

$query="select * from ExtraFields where doc_masterid='$id[0]' and vFor='Document'";
    $db_extraFields = $obj->MySQLSelect($query);
$selectExtraField="";
    for ($i=1; $i <=count($db_extraFields) ; $i++) { 
       $selectExtraField.=",dl.ExtraField_".$i;
    }

$sql = "select  dm.`doc_masterid`, dm.`doc_usertype`, dm.`doc_name`, dm.doc_name_".$_SESSION['sess_lang']." as document , dm.`ex_status`, dl.`doc_id`, dl.`doc_masterid`, dl.`doc_usertype`, dl.`doc_userid`, dl.`ex_date`, dl.`doc_file`,rd.`iDriverId`,dl.DocumentTypeName   $selectExtraField from document_master as dm left join document_list  as dl on  dl.doc_masterid= dm.doc_masterid left join  register_driver as rd on  dl.doc_userid= rd.iDriverId where dl.doc_usertype='driver' AND  iDriverId='".$id[1]."' and dm.doc_masterid='".$id[0]."'" ;	
$db_user = $obj->MySQLSelect($sql);

$sql1="select doc_name,ex_status,doc_name_".$_SESSION['sess_lang']." as document,IsType from document_master where doc_masterid='".$id[0]."'";
$db_user1 = $obj->MySQLSelect($sql1);
    
if($db_user[0]['document']== ''){ $vName = $db_user1[0]['document'];}else{ $vName=$db_user[0]['document'];}
?>
<div class="upload-content">
    <h4><?php echo $vName; ?></h4>
    <form class="form-horizontal frm6" id="frm6" method="post" enctype="multipart/form-data" action="driver_document_action.php?id=<?php echo $id[1] ; ?>&master=<?php echo $id[0] ; ?> " name="frm6">
        <input type="hidden" name="action" value ="document"/>
		<input type="hidden" name="user" value ="<?php echo $user;?>"/>
		<input type="hidden" name="doc_type" value="<?php echo $id[0]; ?>" />
        <input type="hidden" name="doc_path" value =" <?php  echo $tconfig["tsite_upload_driver_doc_path"]; ?>"/>
        
        <div class="form-group">
            <div class="col-lg-12">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-preview thumbnail" style="width: 100%; height: 150px; ">
                        <?php if ($db_user[0]['doc_file'] == '') { 
                            echo $langage_lbl['LBL_NO'] .$vName. $langage_lbl['LBL_PHOTO'];
                        } else { ?>
                            <?php
                            $file_ext = $generalobj->file_ext($db_user[0]['doc_file']);
                            if ($file_ext == 'is_image') { ?>
                                <img src = "<?= $tconfig["tsite_upload_driver_doc"]. '/' . $id[1] . '/' . $db_user[0]['doc_file'] ?>" style="width:100%;object-fit: contain;" alt ="<?php echo $db_user[0]['doc_name']; ?> not found"/>
                            <?php } else { ?>
                                <a href="<?= $tconfig["tsite_upload_driver_doc"]. '/' . $id[1] . '/' . $db_user[0]['doc_file'] ?>" target="_blank"><?php echo $db_user[0]['doc_name']; ?></a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div>
                        <span class="btn btn-file btn-success"><span class="fileupload-new"><?=$langage_lbl['LBL_UPLOAD']; ?> <?php echo $vName ?> <?=$langage_lbl['LBL_PHOTO']; ?></span>
                            <span class="fileupload-exists"><?=$langage_lbl['LBL_CHANGE']; ?></span>
                            <input type="file" name="driver_doc" /></span>
                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><?=$langage_lbl['LBL_REMOVE_TEXT']; ?></a>
                        <input type="hidden" name="driver_doc_hidden"  id="driver_doc" value="<?php echo ($db_user[0]['doc_file'] !="") ? $db_user[0]['doc_file'] : '';?>" />
                    </div>
                    <div class="upload-error"><span class="file_error"></span></div>
                </div>
            </div>
        </div>
        <?php if($db_user[0]['ex_status']=='yes' || $db_user1[0]['ex_status']=='yes') { ?>
        <h5><b><?=$langage_lbl['LBL_EXP_DATE']; ?></b></h5>
        <div class="col-lg-13 exp-date">
            <div class="input-group input-append date" id="dp123" data-date="" data-date-format="yyyy-mm-dd">
                <input class="form-control" type="text" name="dLicenceExp" value="<?php if($db_user[0]['ex_date'] != ''){ echo $db_user[0]['ex_date'];}?>" readonly="" required/>
                <span class="input-group-addon add-on"><i class="icon-calendar"></i></span>
            </div>
            <div class="exp-error"><span class="exp_error"></span></div>
        </div>
        <?php }  ?>

<?php if (trim($db_user1[0]["IsType"])=="0" || $db_user1[0]["IsType"]==0)
{
    $z=1;
foreach ($db_extraFields as $key => $value) {
    $isRequired=trim($value['IsRequired'])=="1"?"required":"";
   ?><br>
   <label><?=$value['vLabel'];?></label>
   <input type="text" value="<?=$db_user[0]['ExtraField_'.$z]?>"  name="extraField[]" id="extraField_<?=$z;?>" class="extraField form-control"  <?=$isRequired;?> />
   <?php
   $z++;
}
    
} ?>
<?php

if (trim($db_user1[0]["IsType"])=="1" || $db_user1[0]["IsType"]==1) {


$dropdown_q="SELECT `ID`, `document_type_name` FROM `document_type_name` where `doc_masterid`='".$id[0]."' and `vFor`='Document'";
    $dropdown=$obj->MySQLSelect($dropdown_q);
?>
<br>
           

<select class="form-control" id="documentType" name="documentType" style="width:100%" required>
    <option value=''>Select document</option>
    <?php

for ($i=0; $i <count($dropdown) ; $i++) { 
    $selected="";

$extraField_q="SELECT `ID`, `doc_masterid`, `vLabel`, `vFor`, `IsRequired`, `DocumentTypeId` FROM `ExtraFields` where doc_masterid='$id[0]' and vFor='Document' and DocumentTypeId='".$dropdown[$i]['ID']."'";

    $dropdownFields[$dropdown[$i]['ID']]=$obj->MySQLSelect($extraField_q);

    if (trim($dropdown[$i]['document_type_name'])==trim($db_user[0]["DocumentTypeName"])) {
        $selected="selected";
    }
echo "<option data-id='".$dropdown[$i]['ID']."' value='".$dropdown[$i]['document_type_name']."'  $selected>".$dropdown[$i]['document_type_name']."</option>";

}

?>
</select>
<div id="dropdownExtrafieldsContainer"></div>

<?php 
}
 ?>
        
        <input type="submit" class="save save11" name="save" value="<?= $langage_lbl['LBL_Save']; ?>">
        <input type="button" class="cancel cancel11" data-dismiss="modal" name="cancel" value="<?= $langage_lbl['LBL_CANCEL_TXT']; ?>">
    </form>
</div>
<script>
$(document).ready(function() {
    $('#frm6').validate({
        ignore: 'input[type=hidden]',
        errorClass: 'help-block error',
        errorElement: 'span',
        errorPlacement: function(error, element) {
            if (element.attr("name") == "driver_doc")
            {
                error.insertAfter("span.file_error");
            }  else if(element.attr("name") == "dLicenceExp"){
                error.insertAfter("span.exp_error");
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            driver_doc: {
                required: {
                    depends: function(element) {
                        if ($("#driver_doc").val() == "") { 
                            return true;
                        } else { 
                            return false;
                        } 
                    }
                },
                accept: "image/*,.doc,.docx,.pdf"
            }
        },
        messages: {
            driver_doc: {
                required: '<?= addslashes($langage_lbl['LBL_UPLOAD_IMG']); ?>',
                accept: '<?= addslashes($langage_lbl['LBL_UPLOAD_IMG_ERROR']);?>'
            }
        }
    });
});

$(function () {

    // var nowTemp = new Date();
    // var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    $('#dp123').datepicker({
        // onRender: function (date) {
            // return date.valueOf() < now.valueOf() ? 'disabled' : '';
       // }
    });
       });
    //formInit();
var dropdownFields=<? echo json_encode($dropdownFields);?>;
var db_user=<? echo json_encode($db_user);?>;

$("#documentType").change(function(){

var data=dropdownFields[$('#documentType option:selected').data("id")];
var html="";
for (var i = 0; i < data.length; i++) {
  var db_val="";
    var isRequired=data[i]['IsRequired'].trim()=="1"?"required":"";
  if (typeof db_user[0]!== "undefined") 
db_val=db_user[0]['ExtraField_'+(i+1)];

  html+=' <br> <label>'+data[i]['vLabel']+'</label><input type="text" value="'+db_val+'"  name="extraField[]" class="extraField form-control" '+isRequired+' />';

}
$("#dropdownExtrafieldsContainer").html(html);

});

$("#documentType").trigger('change');
 
</script>