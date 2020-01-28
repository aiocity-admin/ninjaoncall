<?
include_once("common.php");
$iDriverId = isset($_REQUEST['iDriverId'])?$_REQUEST['iDriverId']:'';
$vCarType = isset($_REQUEST['selected'])?$_REQUEST['selected']:'';
$vCarTyp = explode(",", $vCarType);

$vRentalCarType = isset($_REQUEST['rentalselected'])?$_REQUEST['rentalselected']:'';
$vRentalCarTyp = explode(",", $vRentalCarType);


$eType = isset($_REQUEST['eType'])?$_REQUEST['eType']:'';
$Front = isset($_REQUEST['Front'])?$_REQUEST['Front']:'';
if(!empty($eType))
{
	//$Vehicle_type_name = ($APP_TYPE == 'Delivery')? 'Deliver':$APP_TYPE ;
	$Vehicle_type_name = $eType;

	if(!empty($iDriverId)) {
		$userSQL = "SELECT c.iCountryId FROM register_driver AS rd LEFT JOIN country AS c ON c.vCountryCode=rd.vCountry where rd.iDriverId='".$iDriverId."'";
		$drivers = $obj->MySQLSelect($userSQL);
		$iCountryId = $drivers[0]['iCountryId'];

		if($iCountryId != '') {
			if($Vehicle_type_name == 'UberX') {
				$vehicle_type_sql = "SELECT vt.*,vc.iVehicleCategoryId,vc.vCategory_".$default_lang.",lm.vLocationName from  vehicle_type as vt  left join vehicle_category as vc on vt.iVehicleCategoryId = vc.iVehicleCategoryId left join location_master as lm ON lm.iLocationId = vt.iLocationid where vt.eType='".$Vehicle_type_name."' AND (lm.iCountryId='".$iCountryId."' OR vt.iLocationId = '-1')";
				$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);
			} else {
				$vehicle_type_sql = "SELECT vt.*,c.vCountry,ct.vCity,st.vState,lm.vLocationName from  vehicle_type as vt left join country as c ON c.iCountryId = vt.iCountryId left join state as st ON st.iStateId = vt.iStateId left join city as ct ON ct.iCityId = vt.iCityId left join location_master as lm ON lm.iLocationId = vt.iLocationid where vt.eType='".$Vehicle_type_name."' AND (lm.iCountryId='".$iCountryId."'OR vt.iLocationId = '-1')";		
				$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);
			}
		}
	} else {
		if($Vehicle_type_name == 'UberX') {
			$vehicle_type_sql = "SELECT vt.*,vc.iVehicleCategoryId,vc.vCategory_".$default_lang.",lm.vLocationName from  vehicle_type as vt  left join vehicle_category as vc on vt.iVehicleCategoryId = vc.iVehicleCategoryId left join location_master as lm ON lm.iLocationId = vt.iLocationid where vt.eType='".$Vehicle_type_name."'";
			$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);
		} else {
			$vehicle_type_sql = "SELECT vt.*,c.vCountry,ct.vCity,st.vState,lm.vLocationName from  vehicle_type as vt left join country as c ON c.iCountryId = vt.iCountryId left join state as st ON st.iStateId = vt.iStateId left join city as ct ON ct.iCityId = vt.iCityId left join location_master as lm ON lm.iLocationId = vt.iLocationid where vt.eType='".$Vehicle_type_name."'";
			$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);
		}

	}

	foreach ($vehicle_type_data as $key => $value) {
		if($Vehicle_type_name =='UberX') {
			$vname = $value['vCategory_'.$default_lang].'-'.$value['vVehicleType_'.$default_lang];
		} else {
			$vname= $value['vVehicleType_'.$default_lang];
			$vCountry = $value['vCountry'];
			$vCity = $value['vCity'];
			$vState = $value['vState'];
		}
		if($Front == 'front'){ ?>
			<?php
			$localization = '';
			if(!empty($value['vLocationName'])) {
				$localization.= $value['vLocationName'];
			}
			?>
			<li>
				<b>
					<div><?php echo $vname;?></div>
					<div style="font-size: 12px;text-transform: capitalize;">
						<?php if(!empty($value['vLocationName'])) {
								echo "( Location : ".$localization.")";
							} else if($value['iLocationid'] == "-1") {
								echo "( All Locations )";
						}?>	
					</div>
				</b>
				<div class="make-switch make-swith001" data-on="success" data-off="warning">
					<input type="checkbox" class="chk" name="vCarType[]" id="vCarType_<?= $key;?>" <?php if(in_array($value['iVehicleTypeId'],$vCarTyp)){?>checked<?php } ?> value="<?=$value['iVehicleTypeId'] ?>" data-plateNumber="<?=$value['vLicencePlateLabel'];?>"/>
				</div>
				<?php if(ENABLE_RENTAL_OPTION == 'Yes') {?>
					<? $checkrentalquery = "SELECT count(iRentalPackageId) as totalrental FROM  `rental_package` WHERE iVehicleTypeId = '".$value['iVehicleTypeId']."'";
					$rental_data = $obj->MySQLSelect($checkrentalquery);
					if($rental_data[0]['totalrental'] > 0) {
					?>
						<div id="<?= 'RentalVehicleType_'.$key;?>" style="display: none;">
							<div class="RentalCheckbox">
								<input type="checkbox" class="chk" name="vRentalCarType[]" <?php if(in_array($value['iVehicleTypeId'],$vRentalCarTyp)){?>checked<?php } ?> value="<?=$value['iVehicleTypeId'] ?>"/> Accept rental request for <?php echo $vname;?> vehicle type?
							</div>
						</div>
					<? } 
				} ?>
		</li>
		<? } else { ?>
			<div class="row">
				<div class="col-lg-6">
					<div class="col-lg-6">
						<div><?php echo $vname;?></div>
						<div style="font-size: 12px;">
							<?php
							$localization = '';
							if(!empty($value['vLocationName'])) {
								$localization.= $value['vLocationName'];
							}
							if(!empty($value['vLocationName'])) {
								echo "( Location : ".$localization.")";
							} else if($value['iLocationid'] == "-1") {
								echo "( All Locations )";
							}
							?>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="make-switch make-swith001" data-on="success" data-off="warning">
							 <input type="checkbox" class="chk" name="vCarType[]" id="vCarType_<?= $key;?>" <?php if(in_array($value['iVehicleTypeId'],$vCarTyp)){?>checked<?php } ?> value="<?=$value['iVehicleTypeId'] ?>" data-plateNumber="<?=$value['vLicencePlateLabel'];?>"/> 
						</div>
					</div>
				</div>
			</div>
			<?php if(ENABLE_RENTAL_OPTION == 'Yes') {?>
				<? $checkrentalquery = "SELECT count(iRentalPackageId) as totalrental FROM  `rental_package` WHERE iVehicleTypeId = '".$value['iVehicleTypeId']."'";
				$rental_data = $obj->MySQLSelect($checkrentalquery);
				if($rental_data[0]['totalrental'] > 0) {
				?>
					<div class="row" id="<?= 'RentalVehicleType_'.$key;?>" style="display: none;">
						<div class="col-lg-6">
							<div class="col-lg-12">
								<div>
									<input type="checkbox" class="chk" name="vRentalCarType[]" <?php if(in_array($value['iVehicleTypeId'],$vRentalCarTyp)){?>checked<?php } ?> value="<?=$value['iVehicleTypeId'] ?>"/> Accept rental request for <?php echo $vname;?> vehicle type?
								</div>
							</div>
						</div>
					</div>
				<? } ?>
			<? }
		} ?>
<?	}
}

if(ENABLE_RENTAL_OPTION == 'Yes') {
	foreach ($vehicle_type_data as $key => $value) {?>
<script>
	$(function(){ 
		if ($("#vCarType_"+<?=$key?>).is(':checked')){ 
		  $("#RentalVehicleType_"+<?=$key?>).show();
		} else { 
		  $("#RentalVehicleType_"+<?=$key?>).hide();
		}

        $("#vCarType_"+<?=$key?>).on('change.bootstrapSwitch',function(event)
        {
            if($(this).is(':checked'))
            {
                $("#RentalVehicleType_"+<?=$key?>).show();
            } else {
                $("#RentalVehicleType_"+<?=$key?>).hide();
            }
        })
    });
</script>
<? } 
} ?>
<script>
	$(".make-swith001").bootstrapSwitch();
</script>