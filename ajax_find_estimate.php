<?
include_once("common.php");

global $generalobj;
$dist_fare = isset($_REQUEST['dist_fare'])?$_REQUEST['dist_fare']:'';
$time_fare = isset($_REQUEST['time_fare'])?$_REQUEST['time_fare']:'';
$fromLoc = isset($_REQUEST['fromLoc'])?$_REQUEST['fromLoc']:'';
$from_lat = isset($_REQUEST['from_lat'])?$_REQUEST['from_lat']:'';
$from_long = isset($_REQUEST['from_long'])?$_REQUEST['from_long']:'';
$to_lat = isset($_REQUEST['to_lat'])?$_REQUEST['to_lat']:'';
$to_long = isset($_REQUEST['to_long'])?$_REQUEST['to_long']:'';
$eType = isset($_REQUEST['eType'])?$_REQUEST['eType']:'';

function mediaTimeDeFormater($seconds) {
    $ret = "";
   
    $hours = (string)floor($seconds / 3600);
    $secs = (string)$seconds % 60;
    $mins = (string)floor(($seconds - ($hours * 3600)) / 60);

    if (strlen($hours) == 1)
        $hours = "0" . $hours;
    if (strlen($secs) == 1)
        $secs = "0" . $secs;
    if (strlen($mins) == 1)
        $mins = "0" . $mins;

    if ($hours == 0){
        $mint="";
        $secondss="";
        if($mins > 01){
            $mint = "$mins Mins";
        }else{
            $mint = "$mins Min";
        }
        if($secs > 01){
            $secondss = "$secs Seconds";
        }else{
            $secondss = "$secs Second";
        }
         $ret = "$mint $secondss";
    } else {
        $mint="";
        $secondss="";
        if($mins > 01){
            $mint = "$mins Mins";
        }else{
            $mint = "$mins Min";
        }
        if($secs > 01){
            $secondss = "$secs Seconds";
        }else{
            $secondss = "$secs Second";
        }
        if($hours > 01){
          $ret = "$hours Hrs $mint"; // $secondss
        }else{
          $ret = "$hours hr $mint"; //$secondss
        }
    }
    return  $ret;
}

function get_currency($from_Currency, $to_Currency, $amount) {
	$forignalamount = $amount;
	$amount = urlencode($amount);
	$from_Currency = urlencode($from_Currency);
	$to_Currency = urlencode($to_Currency);
	//$url = "http://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency";
	$url = "https://finance.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency";
	$ch = curl_init();
	$timeout = 0;
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt ($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$rawdata = curl_exec($ch);
	curl_close($ch);
	$data = explode('bld>', $rawdata);
	$data = explode($to_Currency, $data[1]);
	$ftollprice = round($data[0], 2);
	if($ftollprice == 0 || $ftollprice == 0.00){
	$ftollprice = $amount;
	} 
	//return round($data[0], 2);
	return $ftollprice;
}
$countrydata = $generalobj->fetch_address_geocode($fromLoc);

$ENABLE_TOLL_COST = $generalobj->getConfigurations("configurations","ENABLE_TOLL_COST");
$TOLL_COST_APP_ID = $generalobj->getConfigurations("configurations","TOLL_COST_APP_ID");
$TOLL_COST_APP_CODE = $generalobj->getConfigurations("configurations","TOLL_COST_APP_CODE");
$APPLY_SURGE_ON_FLAT_FARE = $generalobj->getConfigurations("configurations","APPLY_SURGE_ON_FLAT_FARE");

$sql = "select vName from currency where eStatus='Active' AND eDefault='Yes'";
$db_currency = $obj->MySQLSelect($sql);

if($dist_fare != '' && $time_fare != "") {
	$priceRatio = 1;
	$db_country = array();
	$pickuplocationarr = array($from_lat,$from_long);
	$dropofflocationarr = array($to_lat,$to_long);

    $GetVehicleIdfromGeoLocation = $generalobj->GetVehicleTypeFromGeoLocation($pickuplocationarr);

    if($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'Ride-Delivery') {
    	$sql = "select vt.*,lm.iCountryId as ConId from vehicle_type as vt LEFT JOIN location_master as lm on lm.iLocationId = vt.iLocationid WHERE vt.iLocationid in ($GetVehicleIdfromGeoLocation) AND vt.eType = '".$eType."'";
    } else {
		$sql = "select vt.*,lm.iCountryId as ConId from vehicle_type as vt LEFT JOIN location_master as lm on lm.iLocationId = vt.iLocationid WHERE vt.iLocationid in ($GetVehicleIdfromGeoLocation) AND vt.eType = '".$APP_TYPE."'";
    }
	$db_vType = $obj->MySQLSelect($sql);
	
	if(!empty($countrydata)) {
		$countrycode = strtolower($countrydata['country_code']);
		$sql2 = "select eUnit from country WHERE LOWER(vCountryCode) LIKE '".$countrycode."'";
		$db_country = $obj->MySQLSelect($sql2);
	}

	$eUnit = $DEFAULT_DISTANCE_UNIT;

	if(!empty($db_country)) {
		if($db_country[0]['eUnit'] == 'KMs' || $db_country[0]['eUnit'] == '') {
			$dist_fare_new = $dist_fare;
			$eUnit = "KMs";
		} else {
			$dist_fare_new = $dist_fare * 0.621371;
			$eUnit = "Miles";
		}
	} else {
		if($eUnit == "KMs") {
			$dist_fare_new = $dist_fare;
		} else {
			$dist_fare_new = $dist_fare * 0.621371;
		}
	}
	
	//0.621371 for miles
	// echo $dist_fare_new;
	$cont = '';
	if(!empty($db_vType)) {
		$dist_fare_new = number_format(round($dist_fare_new,2),2,".","");
		$time_fare = number_format(round($time_fare,2),2,".","");
		$time_fare_seconds = $time_fare*60;
		$cont .= '<ul>';
    	for($i=0;$i<count($db_vType);$i++) {
			$fPricePerKM = $db_vType[$i]['fPricePerKM'];
			
			if($db_vType[$i]['iLocationid'] == "-1") {
				if($eUnit != $DEFAULT_DISTANCE_UNIT) {
					if($eUnit == "KMs") {
						$fPricePerKM = round($db_vType[$i]['fPricePerKM'] * 0.621371,2);
					} else if($eUnit == "Miles") {
						$fPricePerKM = round($db_vType[$i]['fPricePerKM'] / 0.621371,2) ;
					}
				}
			}
			// echo "\n==>".$fPricePerKM;
			
			$Minimum_Fare = round($db_vType[$i]['iMinFare']* $priceRatio,2);
			$Minute_Fare = round($db_vType[$i]['fPricePerMin']*$time_fare,2) * $priceRatio;
			$Distance_Fare = round($fPricePerKM*$dist_fare_new,2)* $priceRatio;
			$iBaseFare = round($db_vType[$i]['iBaseFare'],2)* $priceRatio;
			$total_fare= $iBaseFare+$Minute_Fare+$Distance_Fare;

			$iVehicleTypeId = $db_vType[$i]['iVehicleTypeId'];
			$fPickUpPrice = "1";
			$fNightPrice = "1";
			$fSurgePrice = "1";

			## Checking For Flat Trip ##
			if(!empty($pickuplocationarr) && !empty($dropofflocationarr)){
			   $data_flattrip = $generalobj->checkFlatTripnew($pickuplocationarr,$dropofflocationarr,$iVehicleTypeId);
			   $eFlatTrip = $data_flattrip['eFlatTrip']; 
			   $fFlatTripPrice = $data_flattrip['Flatfare'];
			   $total_fare_fixfare = $fFlatTripPrice;
			} else {
		       $eFlatTrip = "No"; 
		       $fFlatTripPrice = 0;
			}
			## Checking For Flat Trip ##

			## Start Toll Condition ##
			if($ENABLE_TOLL_COST == 'Yes') {
				$newFromtoll = $from_lat.",".$from_long;
				$newTotoll = $to_lat.",".$to_long;

				$url = "https://tce.cit.api.here.com/2/calculateroute.json?app_id=".$TOLL_COST_APP_ID."&app_code=".$TOLL_COST_APP_CODE."&waypoint0=".$newFromtoll."&waypoint1=".$newTotoll."&mode=fastest;car";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL, $url);
				$result = curl_exec($ch);
				curl_close($ch);

				$tolldata = json_decode($result);
				$toll_cost = $tolldata->costs;
				$tollCurrency = $toll_cost->currency;
				$tollDetails = $toll_cost->details;
				$tollCost = $tollDetails->tollCost;

				if($tollCost != '0.0' && $tollCost != "" && $tollCost != '0' ) {
					$vTollPriceCurrencyCode = $tollCurrency;
			    	$fTollPrice = $tollCost;
				    if($fTollPrice != "" )
					{
						$fTollPrice_Original = $fTollPrice;
						$vTollPriceCurrencyCode = strtoupper($vTollPriceCurrencyCode);
						$default_currency = $db_currency[0]['vName'];
						$sql=" SELECT round(($fTollPrice/(SELECT Ratio FROM currency where vName='".$vTollPriceCurrencyCode."'))*(SELECT Ratio FROM currency where vName='".$default_currency."' ) ,2)  as price FROM currency  limit 1";
						$result_toll = $obj->MySQLSelect($sql);
						$fTollPrice = $result_toll[0]['price'];
						if($fTollPrice == 0){
							$fTollPrice = get_currency($vTollPriceCurrencyCode,$default_currency,$fTollPrice_Original);
						}
						$fTollPrice = $fTollPrice;
					} else {
						$fTollPrice = "0";
					}
				}

				if($fTollPrice > 0){
					$toll_div = "<tr>
									<td>".$langage_lbl['LBL_TOLL_TXT']."</td>
									<td style='width:2%'></td>
									<td>".$generalobj->trip_currency($fTollPrice)."</td>
								</tr>";
					$total_fare = $total_fare + $fTollPrice;
				}
			}
			## End Toll Condition ##

			## Start surge chagre block ##
			
			$data_surgePrice = $generalobj->checkSurgePrice($iVehicleTypeId,"");

			if($data_surgePrice['Action'] == "0") {
				$surgetype = $data_surgePrice['surgetype'];
				if($data_surgePrice['surgetype'] == "PickUp"){
					$fPickUpPrice = $data_surgePrice['surgeprice'];
					$fSurgePrice = $data_surgePrice['surgeprice'];
				} else {
					$fNightPrice = $data_surgePrice['surgeprice'];
					$fSurgePrice = $data_surgePrice['surgeprice'];
    			}
			}


			$surge_text = "";
			if($fPickUpPrice > "1" || $fNightPrice > "1") {
				if($eFlatTrip == 'Yes' && $fFlatTripPrice > 0){
					$surge_diff = round(($total_fare_fixfare * $fSurgePrice) - $total_fare_fixfare,2);
					if($APPLY_SURGE_ON_FLAT_FARE == 'No'){
						$surge_diff = 0;
					}
				} else{
					$surge_diff = round(($total_fare * $fSurgePrice) - $total_fare,2);
				}

				if($surge_diff > 0){
					$surge_text = "<tr>
									<td>".$surgetype." ".$langage_lbl['LBL_SURCHARGE_DIFF_TXT']." <br>(".number_format($fSurgePrice,2)." X)</td>
									<td style='width:2%'></td>
									<td>".$generalobj->trip_currency($surge_diff)."</td>
								</tr>";
				}

				$total_fare = $total_fare + $surge_diff;

				if($surge_diff > 0){
					$total_fare_fixfare = $total_fare_fixfare + $surge_diff;
				}
			}
			## end surge chagre block ##
			// min fare condition
			$total_generate_fare = $total_fare;
			if($total_fare < $Minimum_Fare) {
				$total_fare = $Minimum_Fare;
			} 

			$cont_vehicle = '';
			if($eFlatTrip == 'Yes' && $fFlatTripPrice > 0){
				$cont_vehicle .= "<div id='content_".$iVehicleTypeId."' style='vertical-align:top; margin:0px; padding:0px; float:left; width:100%;' title='Click to close.' >
						<!-- <b title='Close'>X</b> -->
						<h3 align='center' style='vertical-align:top; margin:0px; padding:0px; float:left; width:100%; text-align:center;margin-top: -30px;font-weight:500;'>".$langage_lbl['LBL_FARE_ESTIMATION_TXT']."</h3>
						<div class='demo1'>
						<table width='100%' border='0' cellpadding='0' cellspacing='0' align='center'>";
						$cont_vehicle .= "<tr>
								<td>".$langage_lbl['LBL_FIXED_FARE_TXT_ADMIN']."</td><td style='width:2%'></td>
								<td>".$generalobj->trip_currency($fFlatTripPrice)."</td>
							</tr>
								".$surge_text."
							<tr>
								<td colspan='3'><hr/></td>
							</tr>
							<tr>
								<td><b style='font-size:17px'>".$langage_lbl['LBL_Total_Fare_TXT']."</b></td><td style='width:2%'></td>
								<td>".$generalobj->trip_currency($total_fare_fixfare)."</td>
							</tr>
					</table>
					</div>
				</div>";
			} else {
					$cont_vehicle .= "<div id='content_".$iVehicleTypeId."' style='vertical-align:top; margin:0px; padding:0px; float:left; width:100%;' title='Click to close.' >
						<!-- <b title='Close'>X</b> -->
						<h3 align='center' style='vertical-align:top; margin:0px; padding:0px; float:left; width:100%; text-align:center;margin-top: -30px;font-weight:500;'>".$langage_lbl['LBL_FARE_ESTIMATION_TXT']."</h3>
						<div class='demo1'>
						<table width='100%' border='0' cellpadding='0' cellspacing='0' align='center'>";
						if($total_generate_fare < $Minimum_Fare) {
							$cont_vehicle .= "<tr>
											<td>".$langage_lbl['LBL_MINIMUM_FARE']."</td>
											<td style='width:2%'></td>
											<td>".$generalobj->trip_currency($Minimum_Fare)."</td>
										</tr>";
						}
						$cont_vehicle .= "<tr>
								<td>".$langage_lbl['LBL_BASE_FARE_SMALL_TXT']."</td><td style='width:2%'></td>
								<td>".$generalobj->trip_currency($iBaseFare)."</td>
							</tr>
							<tr>
								<td>".$langage_lbl['LBL_RIDER_DISTANCE_TXT']." (".$dist_fare_new." ".$eUnit.")</td><td style='width:2%'></td>
								<td>".$generalobj->trip_currency($Distance_Fare)."</td>
							</tr>
							<tr>
								<td>".$langage_lbl['LBL_TIME_TXT']." (".mediaTimeDeFormater($time_fare_seconds).")</td><td style='width:2%'></td>
								<td>".$generalobj->trip_currency($Minute_Fare)."</td>
							</tr>
								".$surge_text.$toll_div."

							<tr>
								<td colspan='3'><hr/></td>
							</tr>
							<tr>
								<td><b style='font-size:17px'>".$langage_lbl['LBL_Total_Fare_TXT']."</b></td><td style='width:2%'></td>
								<td>".$generalobj->trip_currency($total_fare)."</td>
							</tr>
					</table>
					</div>
				</div>";
			}
			if($eFlatTrip == 'Yes' && $fFlatTripPrice > 0){
				$total_fare = $total_fare_fixfare;
			} else{
				$total_fare = $total_fare;
			}
				$cont .= '<li ><label>'.$db_vType[$i]['vVehicleType'].'<img src="assets/img/question-icon.jpg" id="tooltip_'.$db_vType[$i]['iVehicleTypeId'].'" data-mycontent="'.trim($cont_vehicle).'" alt="" <!--title="'.$langage_lbl['LBL_APPROX_DISTANCE_TXT'].' '.$langage_lbl['LBL_FARE_ESTIMATE_TXT'].'-->"><b>'.$generalobj->trip_currency($total_fare).'</b></label></li>';	
			// echo $db_vType[$i]['vVehicleType']." ==== ".$cont_vehicle."<br>";
			?>
			<script>
				// console.log('<?=$db_vType[$i]['iVehicleTypeId']?>');
				var vehicletypeid = '<?=$db_vType[$i]['iVehicleTypeId']?>';
				$("#tooltip_"+vehicletypeid).tooltipster({
					animation: 'grow',
					content: $("#tooltip_"+vehicletypeid).data('mycontent'),
					multiple: true,
					contentAsHTML : true,
					 contentCloning: true,
					 interactive: true,
					// side: ['right', 'left','top', 'bottom'],
					position: 'top',
					trigger: 'click',
					maxWidth: 490,
					theme: 'tooltipster-pink',
					functionReady: function(origin, tooltip) {
						tooltip.on("click", function() {
							tooltip.hide();
						});
					}
				});
			</script>
	<?  }
		$cont .= '<li><p>'.stripcslashes($langage_lbl['LBL_HOME_PAGE_GET_FIRE_ESTIMATE_TXT']).'</p></li>';
		$cont .= '</ul>';
	} else {
		$cont .= "<h4>".$langage_lbl['LBL_SORRY_NO_VEHICLES']."</h4>";
	}
    echo $cont; exit;
}
?>
