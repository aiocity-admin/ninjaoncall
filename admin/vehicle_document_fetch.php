<?php
include_once('../common.php');
if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();

require_once(TPATH_CLASS . "/Imagecrop.class.php");
$thumb = new thumbnail();

$dropdownFields=array();


$rowid = isset($_REQUEST['rowid']) ? $_REQUEST['rowid'] : '';
$id = explode('-',$rowid);

  //print_r($id);
      
/*$sql = "select  dm.`doc_masterid`, dm.`doc_usertype`, dm.`doc_name`, dm.`ex_status`, dl.`doc_id`, dl.`doc_masterid`, dl.`doc_usertype`, dl.`doc_userid`, dl.`ex_date`, dl.`doc_file`,rd.`iDriverVehicleId` 
     from document_master as dm
	left join document_list  as dl on dl.doc_masterid= dm.doc_masterid
	left join  driver_vehicle as rd on  dl.doc_userid= rd.iDriverVehicleId
	where iDriverVehicleId='".$id[1]."' and dl.doc_usertype='car' and dm.doc_masterid='".$id[0]."' " ;
$db_user = $obj->MySQLSelect($sql);
if($db_user[0]['doc_name']){$vName = $db_user[0]['doc_file'];}else{$vName='Vehicle Insurance';}*/

$query="select * from ExtraFields where doc_masterid='$id[0]' and vFor='Document'";
 $db_extraFields = $obj->MySQLSelect($query);

$sql = "select  *  from document_master  where doc_masterid='".$id[0]."'" ;
$db_user_doc = $obj->MySQLSelect($sql);


$sql = "select * from document_list where doc_masterid='".$id[0]."' AND doc_userid='".$id[1]."'";
$db_user_li = $obj->MySQLSelect($sql);
?>


<div class="upload-content">
    <h4><?php echo $db_user_doc[0]['doc_name']; ?></h4>
    <form class="form-horizontal" id="frm6" method="post" enctype="multipart/form-data" action="vehicle_document_action.php?id=<?php echo $id[1] ; ?>" name="frm6">
        <input type="hidden" name="action" value ="document"/>
        <input type="hidden" name="doc_type" value="<?php echo $id[0]; ?>" />
        <input type="hidden" name="doc_path" value =" <?php echo $tconfig["tsite_upload_vehicle_doc"]; ?>"/>
        
        <div class="form-group">
            <div class="col-lg-12">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-preview thumbnail" style="width: 100%; height: 150px; ">
                        <?php if ($db_user_li[0]['doc_file'] == '') { 
                             echo 'No '.$db_user_doc[0]['doc_name'].' Photo';
                        } else { ?>
                            <?php
                            $file_ext = $generalobj->file_ext($db_user_li[0]['doc_file']);
                            if ($file_ext == 'is_image') {
							    ?>
                                <img src = "<?= $tconfig["tsite_upload_vehicle_doc_panel"] . '/' . $id[1] . '/' . $db_user_li[0]['doc_file'] ?>" style="width:200px;" alt ="Licence not found"/>
                            <?php } else { ?>
                                <a href="<?= $tconfig["tsite_upload_vehicle_doc_panel"] . '/' . $id[1] . '/' . $db_user_li[0]['doc_file'] ?>" target="_blank"><?php echo $db_user_doc[0]['doc_name']; ?></a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div>
                        <span class="btn btn-file btn-success"><span class="fileupload-new" style="text-transform: uppercase;"><?php echo $db_user_doc[0]['doc_name']; ?></span>
                            <span class="fileupload-exists">Change</span>
                            <input type="file" name="vehicle_doc" /></span>
                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
                        <input type="hidden" name="vehicle_doc_hidden"  id="vehicle_doc" value="<?php echo ($db_user_li[0]['doc_file'] !="") ? $db_user_li[0]['doc_file'] : '';?>" />
                    </div>
                    <div class="upload-error"><span class="file_error"></span></div>
                </div>
            </div>
        </div>
        <?php if($db_user_doc[0]['ex_status']=='yes') { ?>
        EXP. DATE<br>
         <div class="col-lg-13">
         <div class="col-lg-13 exp-date">
            <div class="input-group input-append date" id="dp122">
                <input class="form-control" type="text" name="dLicenceExp" value="<?php echo ($db_user_li[0]['ex_date'] !="") ? $db_user_li[0]['ex_date'] : ' ';?>" readonly="" required/>
                <span class="input-group-addon add-on"><i class="icon-calendar"></i></span>
            </div>
            <div class="exp-error"><span class="exp_error"></span></div>
        </div>           
        </div>
        <?php }  ?>
 <?php  if(trim($db_user_doc[0]["IsType"])=="0" || $db_user_doc[0]["IsType"]==0) 
{
    $z=1;
foreach ($db_extraFields as $key => $value) {
    $isRequired=trim($value['IsRequired'])=="1"?"required":"";
   ?><br>
   <label><?=$value['vLabel'];?></label>
   <input type="text" value="<?=$db_user_li[0]['ExtraField_'.$z]?>"  name="extraField[]" id="extraField_<?=$z;?>" class="extraField form-control"  <?=$isRequired;?> />
   <?php
   $z++;
}
    
} ?>

<?php

if (trim($db_user_doc[0]["IsType"])=="1" || $db_user_doc[0]["IsType"]==1) {
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

    if (trim($dropdown[$i]['document_type_name'])==trim($db_user_li[0]["DocumentTypeName"])) {
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
        
        <input type="submit" class="save" name="save" value="Save">
        <input type="button" class="cancel" data-dismiss="modal" name="cancel" value="Cancel">
    </form>
</div>

<script>
    $(function () {
        // var nowTemp = new Date();
        // var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
        $('#dp3').datepicker({
        
            // onRender: function (date) {
                // return date.valueOf() < now.valueOf() ? 'disabled' : '';
           // }
        });
        //formInit();
    });
    
       $(function () {
       newDate = new Date('Y-M-D');
		$('#dp122').datetimepicker({
			 showClose: true,
			format: 'YYYY-MM-DD',
            minDate: moment(),
			//minDate: moment().subtract(1,'d'),
			ignoreReadonly: true,
            keepInvalid:true
		});
});
$(document).ready(function() {
    $('#frm6').validate({
        ignore: 'input[type=hidden]',
        errorClass: 'help-block error',
        errorElement: 'span',
        errorPlacement: function(error, element) {
            if (element.attr("name") == "vehicle_doc")
            {
                error.insertAfter("span.file_error");
            } else if(element.attr("name") == "dLicenceExp"){
                error.insertAfter("span.exp_error");
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            vehicle_doc: {
                required: {
                    depends: function(element) {
                        if ($("#vehicle_doc").val() == "") { 
                            return true;
                        } else { 
                            return false;
                        } 
                    }
                },
                extension: "jpg|jpeg|png|gif|pdf|doc|docx"
            }
        },
        messages: {
            vehicle_doc: {
                required: 'Please Upload Image.',
                extension: 'Please Upload valid file format. Valid formats are pdf,doc,docx,jpg,jpeg,gif,png'
            }
        }
    });


var dropdownFields=<? echo json_encode($dropdownFields);?>;
var db_user=<? echo json_encode($db_user_li);?>;

$("#documentType").change(function(){

var data=dropdownFields[$('#documentType option:selected').data("id")];
var html="";
if (typeof data!="undefined") {
for (var i = 0; i < data.length; i++) {
  var db_val="";
    var isRequired=data[i]['IsRequired'].trim()=="1"?"required":"";
  if (typeof db_user[0]!== "undefined") 
db_val=db_user[0]['ExtraField_'+(i+1)];

  html+=' <br> <label>'+data[i]['vLabel']+'</label><input type="text" value="'+db_val+'"  name="extraField[]" class="extraField form-control" '+isRequired+' />';

}
}
$("#dropdownExtrafieldsContainer").html(html);

});

$("#documentType").trigger('change');


});
</script>