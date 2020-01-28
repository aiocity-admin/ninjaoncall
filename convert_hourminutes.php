<?php
	include_once('include_config.php');
	include_once(TPATH_CLASS.'configuration.php');
	function getLanguageLabelsArr($lCode = '', $directValue = "") {
		global $obj;
		
		/* find default language of website set by admin */
		$sql = "SELECT  `vCode` FROM  `language_master` WHERE eStatus = 'Active' AND `eDefault` = 'Yes' ";
		$default_label = $obj->MySQLSelect($sql);
		
		if ($lCode == '') {
			$lCode = (isset($default_label[0]['vCode']) && $default_label[0]['vCode']) ? $default_label[0]['vCode'] : 'EN';
		}
		
		
		$sql = "SELECT  `vLabel` , `vValue`  FROM  `language_label` WHERE  `vCode` = '" . $lCode . "' UNION SELECT `vLabel` , `vValue`  FROM  `language_label_other` WHERE  `vCode` = '" . $lCode . "' ";
		$all_label = $obj->MySQLSelect($sql);
		
		$x = array();
		for ($i = 0; $i < count($all_label); $i++) {
			$vLabel = $all_label[$i]['vLabel'];
			$vValue = $all_label[$i]['vValue'];
			$x[$vLabel] = $vValue;
		}
		
		
		/*$sql = "SELECT  `vLabel` , `vValue`  FROM  `language_label_other`  WHERE  `vCode` = '" . $lCode . "' ";
		$all_label = $obj->MySQLSelect($sql);
		
		for ($i = 0; $i < count($all_label); $i++) {
			$vLabel = $all_label[$i]['vLabel'];
			
			$vValue = $all_label[$i]['vValue'];
			$x[$vLabel] = $vValue;
		}     */
		
		$x['vCode'] = $lCode; // to check in which languge code it is loading
		
		if ($directValue == "") {
			$returnArr['Action'] = "1";
			$returnArr['LanguageLabels'] = $x;
			
			return $returnArr;
			} else {
			return $x;
		}
	}
	
echo calculateAdditionalTime('2018-04-28 12:34:37','2018-04-28 14:23:00','1','EN');
function calculateAdditionalTime($startDate ,$endDate, $rentalTimeHours,$userlangcode){
	$languageLabelsArr = getLanguageLabelsArr($userlangcode, "1");
	echo $TotalTimeInseconds_trip=@round(abs(strtotime($startDate) - strtotime($endDate)),2);
	echo"<br/>";
	$RentalDefineseconds =  $rentalTimeHours * 3600;
	if($TotalTimeInseconds_trip > $RentalDefineseconds){
		echo $secondsDiff= $TotalTimeInseconds_trip - $RentalDefineseconds;
		echo"<br/>";die;
		$AdditionTime = mediaTimeDeFormater($MinutesDiff,$userlangcode);
	} else {
		$AdditionTime = "0.00 "." ".$languageLabelsArr['LBL_MINUTE'];
	}
	return $AdditionTime;
}

function mediaTimeDeFormater($minutes,$userlangcode) {
	$seconds = @round(abs($minutes * 60));
	$languageLabelsArr = getLanguageLabelsArr($userlangcode, "1");
    $ret = "";

    $hours = floor($seconds / 3600);
    $secs = $seconds % 60;
    $mins = floor(($seconds - ($hours * 3600)) / 60);

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
            $mint = "$mins";
        }else{
            $mint = "$mins";
        }
        if($secs > 01){
            $secondss = "$secs";
        }else{
            $secondss = "$secs";
        }
        
        $LBL_MINUTES_TXT = ($mins > 1)? $languageLabelsArr['LBL_MINUTES_TXT'] : $languageLabelsArr['LBL_MINUTE'];
        if($mins > 0){
       	 	$ret = $mint.":".$secondss." ".$LBL_MINUTES_TXT;
       	} else {
       		$ret = $secondss." ".$languageLabelsArr['LBL_SECONDS_TXT'];
       	}
    } else {
        $mint="";
        $secondss="";
        if($mins > 01){
            $mint = "$mins";
        }else{
            $mint = "$mins";
        }
        if($secs > 01){
            $secondss = "$secs";
        }else{
            $secondss = "$secs";
        }
        if($hours > 01){
          $ret = $hours.":".$mint.":".$secondss." ".$languageLabelsArr['LBL_HOURS_TXT'];
        }else{
          $ret = $hours.":".$mint.":".$secondss." ".$languageLabelsArr['LBL_HOUR_TXT'];
        }
    }
    return  $ret;
}