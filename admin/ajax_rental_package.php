<?
include_once("../common.php");

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();

$iRentalPackageId = isset($_REQUEST['iRentalPackageId'])?$_REQUEST['iRentalPackageId']:''; 
$iVehicleTypeId = isset($_REQUEST['iVehicleTypeId'])?$_REQUEST['iVehicleTypeId']:''; 
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$fPrice = isset($_POST['fPrice']) ? $_POST['fPrice'] : '';
$fKiloMeter = isset($_POST['fKiloMeter']) ? $_POST['fKiloMeter'] : '';
$fHour = isset($_POST['fHour']) ? $_POST['fHour'] : '';
$fPricePerKM = isset($_POST['fPricePerKM']) ? $_POST['fPricePerKM'] : '';
$fPricePerHour = isset($_POST['fPricePerHour']) ? $_POST['fPricePerHour'] : '';


$tbl_name= 'rental_package';

$vTitle_store = array();
	$sql = "SELECT * FROM `language_master` where eStatus='Active' ORDER BY `iDispOrder`";
	$db_master = $obj->MySQLSelect($sql);
	$count_all = count($db_master);
	 if ($count_all > 0) {
		for ($j = 0; $j < $count_all; $j++) {
			$vValue = 'vPackageName_'. $db_master[$j]['vCode'];
			array_push($vTitle_store, $vValue);
			$$vValue = isset($_POST[$vValue]) ? $_POST[$vValue] : '';
		}
	}
	
if (isset($_POST['btnsubmit123'])) {
	
	
	//echo "<pre>"; print_r($_POST); exit;
	for ($k = 0; $k < count($vTitle_store); $k++) {
	
		$vValue = 'vPackageName_' . $db_master[$k]['vCode'];
        
        $q = "INSERT INTO ";
        $where = '';
		if ($id != '') {

            $q = "UPDATE ";
            $where = " WHERE `iRentalPackageId` = '" . $id . "'";
        }
			$query = $q . " `" . $tbl_name . "` SET
			`iVehicleTypeId` = '" . $iVehicleTypeId . "',
			`fPrice` = '" . $fPrice . "',
			`fKiloMeter` = '" . $fKiloMeter . "',				
			`fHour` = '" . $fHour . "',				
			`fPricePerKM` = '" . $fPricePerKM . "',				
			`fPricePerHour` = '" . $fPricePerHour . "',				
			" . $vValue . " = '" . $_POST[$vTitle_store[$k]] . "'"
			. $where;
        $obj->sql_query($query);
        $id = ($id != '') ? $id : $obj->GetInsertId();		
	}
	header("Location:".$tconfig["tsite_url_main_admin"]."rental_package.php?id=".$iVehicleTypeId); exit;
	
	
}

if ($action == 'Edit') {
	
	 $sql = "SELECT * FROM " . $tbl_name . " WHERE iRentalPackageId = '" . $iRentalPackageId . "'";
    $db_data = $obj->MySQLSelect($sql);
	 if (count($db_data) > 0) {
	 
		for ($i = 0; $i < count($db_master); $i++) {
		
			 foreach ($db_data as $key => $value) {
			 
				$vValue = 'vPackageName_' . $db_master[$i]['vCode'];
                $$vValue = $value[$vValue];
                $iVehicleTypeId = $value['iVehicleTypeId'];
                $fKiloMeter = $value['fKiloMeter'];
                $fHour = $value['fHour'];
                $fPricePerKM = $value['fPricePerKM'];
                $fPrice = $value['fPrice'];
                $fPricePerHour = $value['fPricePerHour'];             
				 
			 }
		}		 
	 }	
}

$conn = "";



$conn .='<div class="modal-body">	
	  <form id="rental_package" name="rental_package" method="POST" action="ajax_rental_package.php">
	  <div class="form-group">

	  <input type="hidden" name="iVehicleTypeId" value="'.$iVehicleTypeId.'">
	  <input type="hidden" name="iRentalPackageId" value="'.$iRentalPackageId.'">
	  <input type="hidden" name="id" value="'.$iRentalPackageId.'">
	  <input type="hidden" name="action" value="'.$action.'">
	  ';
	  
	  
 if($count_all > 0) {
	 for($i=0;$i<$count_all;$i++) {
		$vCode = $db_master[$i]['vCode'];
		$vTitle = $db_master[$i]['vTitle'];
		$eDefault = $db_master[$i]['eDefault'];

		$vValue = 'vPackageName_'.$vCode;

		$required = ($eDefault == 'Yes')?'required':'';
		$required_msg = ($eDefault == 'Yes')?'<span class="red"> *</span>':'';
		$conn .='<label for="exampleInputEmail1">Package Name ('.$vTitle.')'.$required_msg.'</label>
		  <input type="text" class="form-control"
		  id="'.$vValue.'"  name="'.$vValue.'" value="'.$$vValue.'" '.$required.' placeholder="'.$vTitle.'" >' ;
		  if($vCode == $default_lang  && count($db_master) > 1){
			  $conn .='<button type ="button" name="allLanguage" id="allLanguage" class="btn btn-primary" onClick="getAllLanguageCode();">Convert To All Language</button>';
			  }
	
	 }
 }
 
 $conn .='<label for="exampleInputEmail1">Price</label>
		  <input type="text" class="form-control"
		  id="fPrice" value="'.$fPrice.'" name = "fPrice" placeholder="fPrice">' ;	
		  
		   $conn .='<label for="exampleInputEmail1">KiloMeter</label>
		  <input type="text" class="form-control"
		  id="fKiloMeter" name="fKiloMeter" value="'.$fKiloMeter.'" placeholder="KiloMeter">' ;	
		  
		   $conn .='<label for="exampleInputEmail1">Hour</label>	   
		  <input type="text" class="form-control"
		  id="fHour"  name="fHour" value="'.$fHour.'" placeholder="Hour">' ;	
		  
		    $conn .='<label for="exampleInputEmail1">PricePerKM</label>	   
		  <input type="text" class="form-control"
		  id="fPricePerKM" name="fPricePerKM" value="'.$fPricePerKM.'" placeholder="PricePerKM">' ;	
		  
		    $conn .='<label for="exampleInputEmail1">PricePerHour</label>	   
		  <input type="text" class="form-control"
		  id="fPricePerHour" name="fPricePerHour" value="'.$fPricePerHour.'" placeholder="PricePerHour">' ;	
		
	  
	 $conn .=' </div><input type="submit" class="btn btn-default"  name="btnsubmit123" id="btnsubmit" value="submit" >
	</form>';
	
	
$conn .='</div>';
            
 echo $conn; exit; 
 ?>
 

 
<script type="text/javascript" language="javascript">
 
function getAllLanguageCode(){
			alert(hello);
     
      
      
		}
</script>
       

  

       
         