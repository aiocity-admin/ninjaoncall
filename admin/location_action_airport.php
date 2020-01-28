<?
	include_once('../common.php');

	if(!isset($generalobjAdmin)){
		require_once(TPATH_CLASS."class.general_admin.php");
		$generalobjAdmin = new General_admin();
	}
	$generalobjAdmin->check_member_login();

	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:'';
	$success	= isset($_REQUEST['success'])?$_REQUEST['success']:0;
	$action 	= ($id != '')?'Edit':'Add';
	$var_msg = isset($_REQUEST['var_msg'])?$_REQUEST['var_msg']:'';
	
	$backlink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
    $previousLink = isset($_POST['backlink']) ? $_POST['backlink'] : '';

	$tbl_name 	= 'airport_location_master';
	$script = 'AirPort Location';

	// set all variables with either post (when submit) either blank (when insert)
	$vLocationName = isset($_POST['vLocationName']) ? $_POST['vLocationName'] : '';
	$tDriverLongitude = isset($_POST['tDriverLongitude']) ? $_POST['tDriverLongitude'] : '';
	$tDriverLatitude = isset($_POST['tDriverLatitude']) ? $_POST['tDriverLatitude'] : '';
	$tPassengerLongitude = isset($_POST['tPassengerLongitude']) ? $_POST['tPassengerLongitude'] : '';
	$tPassengerLatitude = isset($_POST['tPassengerLatitude']) ? $_POST['tPassengerLatitude'] : '';
	$eStatus_check = isset($_POST['eStatus']) ? $_POST['eStatus'] : 'off';
	$eStatus = ($eStatus_check == 'on') ? 'Active' : 'Inactive';
	$iCountry = isset($_POST['iCountry']) ? $_POST['iCountry'] : '';
	

	if($iCountry != ""){
		$sql="SELECT iCountryId FROM country WHERE vCountry LIKE '".$iCountry."'";
		$data = $obj->MySQLSelect($sql);
		$iCountryId = $data[0]['iCountryId'];
	}

	if(isset($_POST['submit'])) {

		if(SITE_TYPE=='Demo')
		{
			header("Location:location_action_airport.php?id=".$id.'&success=2');
			exit;
		}

		if(empty($tDriverLongitude) || empty($tDriverLatitude) || empty($tPassengerLatitude) || empty($tPassengerLongitude)) {
	       	$var_msg = 'Please select/draw the area on map shown in right hand side.';
	        header("Location:location_action_airport.php?id=".$id.'&success=3&var_msg='.$var_msg);
	        exit;
		} else {

			if($id != '' ){
				$q = "UPDATE ";
				$where = " WHERE `iAirportLocationId` = '".$id."'";
			} else {
				$q = "INSERT INTO ";
				$where = '';
			}

			$query1 = $q ." `".$tbl_name."` SET
			`vLocationName` = '".$vLocationName."',
			`iCountryId` = '".$iCountryId."',
			`tDriverLongitude` = '".$tDriverLongitude."',
			`tDriverLatitude` = '".$tDriverLatitude."',
			`tPassengerLatitude` = '".$tPassengerLatitude."',
			`tPassengerLongitude` = '".$tPassengerLongitude."',
			`eStatus` = '".$eStatus."'"
			.$where; //die;
			$obj->sql_query($query1);
			

			$id = ($id != '')? $id : $obj->GetInsertId();
			
			if ($action == "Add") {
	            $_SESSION['success'] = '1';
	            $_SESSION['var_msg'] = 'Loaction Insert Successfully.';
	        } else {
	            $_SESSION['success'] = '1';
	            $_SESSION['var_msg'] = 'Loaction Updated Successfully.';
	        }
			header("Location:".$backlink);exit;
		}

	}

	// for Edit
	if($action == 'Edit') {
		$sql = "SELECT lm.*,c.vCountry FROM airport_location_master AS lm LEFT JOIN country AS c ON c.iCountryId= lm.iCountryId WHERE lm.iAirportLocationId = '".$id."'";
		$db_data = $obj->MySQLSelect($sql);
		
		$vLabel = $id;
		if(count($db_data) > 0) {
			foreach($db_data as $key => $value) {
				$vLocationName = $value['vLocationName'];
				$tDriverLongitude = $value['tDriverLongitude'];
				$tDriverLatitude = $value['tDriverLatitude'];
				$tPassengerLongitude = $value['tPassengerLongitude'];
				$tPassengerLatitude = $value['tPassengerLatitude'];
				$vCountry = $value['vCountry'];
				$eStatus = $value['eStatus'];
			}
		}
	}

$sql = "SELECT iCountryId,vCountry,vCountryCode FROM country WHERE eStatus = 'Active'";
$db_country = $obj->MySQLSelect($sql);

//for default country
$sql = "SELECT vCountry FROM country WHERE eStatus = 'Active' AND vCountryCode = '$DEFAULT_COUNTRY_CODE_WEB'" ;
$db_def_con = $obj->MySQLSelect($sql);

// Get lat and long by address         
$address = $db_def_con[0]['vCountry']; // Google HQ
$prepAddr = str_replace(' ','+',$address);
$geocode=file_get_contents('//maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
$output= json_decode($geocode);
$latitude = $output->results[0]->geometry->location->lat;
$longitude = $output->results[0]->geometry->location->lng;

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

	<!-- BEGIN HEAD-->
	<head>
		<meta charset="UTF-8" />
		<title>Admin | Airport FIFO Zone <?=$action;?></title>
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />

		<link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

		<? include_once('global_files.php');?>
		<!-- On OFF switch -->
		<link href="../assets/css/jquery-ui.css" rel="stylesheet" />
		<link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />

		<style>
		.location_icon  li {background: none;}.notes-main{ float: left;line-height: 1; } .notes-main-text{float: left;line-height: 1;padding-left: 10px;}.map-icon{width: 16px; height: 16px; overflow: hidden; position: relative;}.map-icon-img{position: absolute;left: 0px;user-select: none;border: 0px;padding: 0px;margin: 0px;max-width: none;width: 16px;height: 192px;}
		@media screen and (max-width: 480px) { .notes-main-text {float: none;padding-left: 25px;} }
	</style>
	</head>
	<!-- END  HEAD-->
	<!-- BEGIN BODY-->
	<body class="padTop53 " >

		<!-- MAIN WRAPPER -->
		<div id="wrap">
			<? include_once('header.php'); ?>
			<? include_once('left_menu.php'); ?>
			<!--PAGE CONTENT -->
			<div id="content">
				<div class="inner">
					<div class="row">
						<div class="col-lg-12">
							<h2><?=$action;?> Airport FIFO Zone</h2>
							<a href="location-airport.php">
								<input type="button" value="Back to Listing" class="add-btn">
							</a>
						</div>
					</div>
					<hr />
					<div class="body-div">
						<? if($success == 1) { ?>
						<div class="alert alert-success alert-dismissable">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
							Record Updated successfully.
						</div><br/>
						<? }elseif ($success == 2) { ?>
							<div class="alert alert-danger alert-dismissable">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
								"Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
							</div><br/>
						<? } elseif ($success == 3) { ?>
						<div class="alert alert-danger alert-dismissable">
							 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
							 <?php echo $var_msg;?>
						</div><br/>
						<?php }?>
						<div class="row">
							<div class="col-lg-5">
								<div class="form-group">
									<form method="post" name="location_form" id="location_form" action="">
										<input type="hidden" name="id" value="<?=$id;?>" id="iLocationid"/>
										<input type="hidden" name="previousLink" id="previousLink" value="<?php echo $previousLink; ?>"/>
										<input type="hidden" name="backlink" id="backlink" value="location-airport.php"/>
										<input type="hidden" class="form-control" name="tDriverLatitude"  id="tDriverLatitude" value="<?=$tDriverLatitude;?>">
										<input type="hidden" class="form-control" name="tDriverLongitude"  id="tDriverLongitude" value="<?=$tDriverLongitude;?>">
										<input type="hidden" class="form-control" name="cLatitude"  id="cLatitude">
										<input type="hidden" class="form-control" name="cLongitude"  id="cLongitude">

										<input type="hidden" class="form-control" name="tPassengerLatitude"  id="tPassengerLatitude" value="<?=$tPassengerLatitude;?>">
										<input type="hidden" class="form-control" name="tPassengerLongitude"  id="tPassengerLongitude" value="<?=$tPassengerLongitude;?>">
										<div class="row">
											<div class="col-lg-12">
												<label>FIFO Zone Name<span class="red"> *</span></label>
											</div>
											<div class="col-lg-6">
												<input type="text" class="form-control" name="vLocationName"  id="vLocationName" value="<?=$vLocationName;?>" placeholder="Location Name" required>
											</div>
										</div>
										<div class="row">
										 <div class="col-lg-12">
											  <label>Country <span class="red"> *</span></label>
										 </div>
										 <div class="col-lg-6">
											  <select class="form-control" name ="iCountry" id="iCountry" required="required" onChange="getGeoCounty(this.value);">
												   <option value="">Select Country</option>
												   <? for($i=0;$i<count($db_country);$i++){ ?>
												   <option value = "<?= $db_country[$i]['vCountry'] ?>" <?if($vCountry==$db_country[$i]['vCountry']){?>selected<? } ?> ><?= $db_country[$i]['vCountry'] ?></option>
												   <? } ?>
											  </select>
										 </div>
										</div>
										<div class="row" style="display: none;">
											<div class="col-lg-12">
												<label>Status</label>
											</div>
											<div class="col-lg-6">
												<div class="make-switch" data-on="success" data-off="warning" id="mySwitch">
													<input type="checkbox" name="eStatus" <?=($id != '' && $eStatus == 'Inactive')?'':'checked';?> id="eStatus"/>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12">
												<input type="submit" class=" btn btn-default" name="submit" id="submit" value="<?=$action;?> Airport FIFO Zone" onclick="return IsEmpty();">
												<!-- <input type="reset" value="Reset" class="btn btn-default"> -->
		                                        <a href="location-airport.php" class="btn btn-default">Cancel</a>
											</div>
										</div>
									</form>
								</div>
								<div class="admin-notes">
			                        <h4>Notes:</h4>
			                        <ul class="location_icon">
			                            <li>
			                            	<div class="notes-main">
			                                <div class="map-icon">
			                                	<img src="images/icon/drawing.png" class="map-icon-img" style="top: -144px;"> 
			                                </div>
			                            	</div>
			                                <div class="notes-main-text">  With use of this icon, you can edit exist polygon shape. </div>
			                            </li>
			                            <li>
			                                <div class="notes-main">
			                                <div class="map-icon">
			                                	<img src="images/icon/drawing.png" class="map-icon-img" style="top: -64px;"> 
			                                </div>
			                            	</div>
			                                <div class="notes-main-text"> With use of this icon, you can draw new polygon shape. </div>
			                            </li>
			                            <li>
			                               <div class="notes-main">
			                                <div class="map-icon">
			                                	<img src="images/icon/drawing.png" class="map-icon-img" style="top: -32px;"> 
			                                </div>
			                            	</div>
			                                <div class="notes-main-text"> With use of this icon, you can draw new polygon lines. </div>
			                            </li>
			                        </ul>
			               		</div>
							</div>
							<div class="col-lg-7">
								<label>Draw Driver FIFO Zone Here In Map :<span class="red">*</span></label>
								<p><span>Please select the area by putting the points around it. Please <a href="http://bbcsproducts.com/features-videos/geofence-locations/geofence_player.html" target="_blank" alt="Link"><b>click here</b></a> to view how to select the area and add it.</span></p>
						        <div class="panel-heading location-map" style="background:none;">
						          <div class="google-map-wrap">
						          	<input id="pac-input" type="text" placeholder="Enter Location For More Focus" style="padding:4px;width: 200px;margin-top: 5px;">
						            <div id="map-canvas" class="google-map" style="width:100%; height:500px;"></div>
						          </div>
						           <div style="text-align: center;margin-top: 5px;">
							        <button id="delete-button">Delete Selected Driver Zone</button>
							      </div>
						        </div>
							</div>
						</div>
						<div>
							<div class="col-lg-5">
							</div>
							<div class="col-lg-7">
								<label>Draw Passenger Airport Zone Here In Map :<span class="red">*</span></label>
						        <div class="panel-heading location-map" style="background:none;">
						          <div class="google-map-wrap">
						          	<input id="pac-input-pass" type="text" placeholder="Enter Location For More Focus" style="padding:4px;width: 200px;margin-top: 5px;">
						            <div id="map-canvas-pass" class="google-map" style="width:100%; height:500px;"></div>
						          </div>
						           <div style="text-align: center;margin-top: 5px;">
							        <button id="delete-button-pass">Delete Selected Passenger Zone</button>
							      </div>
						        </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--END PAGE CONTENT -->
		</div>
		<!--END MAIN WRAPPER -->


<? include_once('footer.php');?>
<script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
<script src="//maps.google.com/maps/api/js?sensor=fasle&key=<?= $GOOGLE_SEVER_API_KEY_WEB ?>&libraries=places,drawing" type="text/javascript"></script>
<script>
$(document).ready(function() {
	var referrer;
	if($("#previousLink").val() == "" ){ //alert('pre1');
		referrer =  document.referrer;
	} else { 
		referrer = $("#previousLink").val();
	}

	if(referrer == "") {
		referrer = "location-airport.php";
	} else { 
		$("#backlink").val(referrer);
	}
	$(".back_link").attr('href',referrer); 

});

function IsEmpty(){
  if((document.forms['location_form'].tDriverLatitude.value === "") || (document.forms['location_form'].tDriverLongitude.value === ""))
  {
    alert("Please select/draw the area on map shown in right hand side.");
    return false;
  }
    return true;
}

var drawingManager;
var selectedShape;

function clearSelection() {
	if (selectedShape) {
	  if (typeof selectedShape.setEditable == 'function') {
	    selectedShape.setEditable(false);
	  }
	  selectedShape = null;
	}
}

function deleteSelectedShape() {
    if (selectedShape) {
      selectedShape.setMap(null);
        $('#tDriverLatitude').val("");
		$('#tDriverLongitude').val("");
    }
}

function updateCurSelText(shape) {
 	var latt = "";
  	var longi = "";
	if (typeof selectedShape.getPath == 'function') {
	  for (var i = 0; i < selectedShape.getPath().getLength(); i++) {
	    var latlong = selectedShape.getPath().getAt(i).toUrlValue().split(",");
	    latt += (latlong[0]) + ",";
	    longi +=(latlong[1]) + ",";
	  }
	}
	$('#tDriverLatitude').val(latt);
	$('#tDriverLongitude').val(longi);
}

function setSelection(shape, isNotMarker) {
	clearSelection();
	selectedShape = shape;
	if (isNotMarker)
	  shape.setEditable(true);
	updateCurSelText(shape);
}

function getGeoCounty(Countryname) {
    var geocoder = new google.maps.Geocoder();
	var address = Countryname;
	var lat,long;
	geocoder.geocode( { 'address': address}, function(results, status) {
	  if (status == google.maps.GeocoderStatus.OK)
	  {
	      lat = results[0].geometry.location.lat();
	      $('#cLatitude').val(lat);
	      long = results[0].geometry.location.lng();
	      $('#cLongitude').val(long);
	      play();
	      playPass();
	  }
	});
}

function deleteEditShape() {
    if (myPolygon) {
      myPolygon.setMap(null);
    }
   	$('#tDriverLatitude').val("");
  	$('#tDriverLongitude').val(""); 
}

function play(){
	var pt = new google.maps.LatLng($("#cLatitude").val(),$("#cLongitude").val());
	map.setCenter(pt);
	map.setZoom(5);
}

//Display Coordinates below map
function getPolygonCoords() {
  var len = myPolygon.getPath().getLength();
  var latt = "";
  var longi = "";
  for (var i = 0; i < len; i++) {
  		var latlong = myPolygon.getPath().getAt(i).toUrlValue().split(",");
	    latt += (latlong[0]) + ",";
	    longi +=(latlong[1]) + ",";
  }
  $('#tDriverLatitude').val(latt);
  $('#tDriverLongitude').val(longi);
}

/////////////////////////////////////
var map;
var searchBox;
var placeMarkers = [];
var input;

/////////////////////////////////////
function initialize() {
	var myLatLng = new google.maps.LatLng("<?=$latitude?>","<?=$longitude?>");

    map = new google.maps.Map(document.getElementById('map-canvas'), { 
      zoom: 5,
      center: myLatLng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      disableDefaultUI: false,
      zoomControl: true
    });
    var polyOptions = {
      strokeWeight: 0,
      fillOpacity: 0.45,
      editable: true
    };
    <?php if($action == "Edit") { ?>
    	drawingModevalue = null;
    <?php } else { ?>
    	 drawingModevalue = google.maps.drawing.OverlayType.POLYGON;
    <?php } ?>
    drawingManager = new google.maps.drawing.DrawingManager({
      drawingMode: drawingModevalue,
      drawingControl: true,
      drawingControlOptions: {
        position: google.maps.ControlPosition.TOP_RIGHT,
        drawingModes: ['polygon', 'polyline']
      },
      polygonOptions: polyOptions,
      map: map
    });


    google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
        var isNotMarker = (e.type != google.maps.drawing.OverlayType.MARKER);
        drawingManager.setDrawingMode(null);
        var newShape = e.overlay;
        newShape.type = e.type;
        google.maps.event.addListener(newShape, 'click', function() {
          setSelection(newShape, isNotMarker);
        });
        google.maps.event.addListener(newShape, 'drag', function() {
          updateCurSelText(newShape);
        });
        google.maps.event.addListener(newShape, 'dragend', function() {
          updateCurSelText(newShape);
        });
        setSelection(newShape, isNotMarker);
    });
    

    google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
    google.maps.event.addListener(map, 'click', clearSelection);
    google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);
    google.maps.event.addListener(map, 'bounds_changed', function() {
      var bounds = map.getBounds();
    });

    //~ initSearch(); ============================================
    // Create the search box and link it to the UI element.
     input = /** @type {HTMLInputElement} */( //var
        document.getElementById('pac-input'));
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);

    //searchBox = new google.maps.places.SearchBox((input));

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    // Listen for the event fired when the user selects an item from the
    // pick list. Retrieve the matching places for that item.
    var marker = new google.maps.Marker({
        map: map
    });
        
    autocomplete.addListener('place_changed', function() {
    	marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }
  
        // If the place has a geometry, then present it on a map.
        placeMarkers = [];
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(14);
        }

        // Create a marker for each place.
        marker = new google.maps.Marker({
          map: map,
          title: place.name,
          position: place.geometry.location
        });


        marker.setIcon(({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(25, 25)
        }));
        marker.setVisible(true);

    });

    //~ EndSearch(); ============================================    

    // Polygon Coordinates
    var tDriverLongitude = $('#tDriverLongitude').val();
    var tDriverLatitude = $('#tDriverLatitude').val();
    var Country = $("#iCountry").val();
	if(Country != "" && (tDriverLongitude == "" || tDriverLatitude == "")) {
		getGeoCounty(Country);
  	 	myLatLng = new google.maps.LatLng($("#cLatitude").val(), $("#cLongitude").val());
  	 	map.fitBounds(myLatLng);
	} else {
	    if(tDriverLongitude != "" || tDriverLatitude != "" ) {
		    var tlat = tDriverLatitude.split(",");
		    var tlong = tDriverLongitude.split(",");
		    var triangleCoords = [];
		    var bounds = new google.maps.LatLngBounds();
			for(var i=0,len = tlat.length;i<len;i++) {
		 		if(tlat[i] != "" || tlong[i] != "") {
		 			triangleCoords.push(new google.maps.LatLng(tlat[i], tlong[i]));
					var point = new google.maps.LatLng(tlat[i], tlong[i]);
					bounds.extend(point);
		 		}
			}
			// Styling & Controls
			myPolygon = new google.maps.Polygon({
				paths: triangleCoords,
				draggable: false, // turn off if it gets annoying
				editable: true,
				strokeColor: '#FF0000',
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: '#FF0000',
				fillOpacity: 0.35
			});
			map.fitBounds(bounds);
			myPolygon.setMap(map);

			google.maps.event.addListener(myPolygon.getPath(), "insert_at", getPolygonCoords);
			google.maps.event.addListener(myPolygon.getPath(), "set_at", getPolygonCoords);
			google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteEditShape);
		}
	}
}

google.maps.event.addDomListener(window, 'load', initialize);

/*=====================////////////////////////////////////////////*/

var drawingManagerPass;
var selectedShapePass;

function clearSelectionPass() {
	if (selectedShapePass) {
	  if (typeof selectedShapePass.setEditable == 'function') {
	    selectedShapePass.setEditable(false);
	  }
	  selectedShapePass = null;
	}
}

function updateCurSelTextPass(shape) {
  	var latt = "";
  	var longi = "";
	if (typeof selectedShapePass.getPath == 'function') {
	  for (var i = 0; i < selectedShapePass.getPath().getLength(); i++) {
	    var latlong = selectedShapePass.getPath().getAt(i).toUrlValue().split(",");
	    latt += (latlong[0]) + ",";
	    longi +=(latlong[1]) + ",";
	  }
	}
	$('#tPassengerLatitude').val(latt);
	$('#tPassengerLongitude').val(longi);
}

function setSelectionPass(shape, isNotMarker) {
	clearSelectionPass();
	selectedShapePass = shape;
	if (isNotMarker)
	  shape.setEditable(true);
	updateCurSelTextPass(shape);
}

function deleteSelectedShapePass() {
    if (selectedShapePass) {
      selectedShapePass.setMap(null);
        $('#tPassengerLatitude').val("");
		$('#tPassengerLongitude').val("");
    }
}

function deleteEditShapePass() {
    if (myPolygonPass) {
      myPolygonPass.setMap(null);
    }
   	$('#tPassengerLatitude').val("");
  	$('#tPassengerLongitude').val(""); 
}

//Display Coordinates below map
function getPolygonCoordsPass() {
  var len = myPolygonPass.getPath().getLength();
  var latt = "";
  var longi = "";
  for (var i = 0; i < len; i++) {
  		var latlong = myPolygonPass.getPath().getAt(i).toUrlValue().split(",");
	    latt += (latlong[0]) + ",";
	    longi +=(latlong[1]) + ",";
  }
  $('#tPassengerLatitude').val(latt);
  $('#tPassengerLongitude').val(longi);
}

function playPass(){
	var pt = new google.maps.LatLng($("#cLatitude").val(),$("#cLongitude").val());
	mapPass.setCenter(pt);
	mapPass.setZoom(5);
}

var mapPass;
var placeMarkersPass = [];
var inputPass;

function initializePass() {
	var myLatLng = new google.maps.LatLng("<?=$latitude?>","<?=$longitude?>");
	
    mapPass = new google.maps.Map(document.getElementById('map-canvas-pass'), { 
      zoom: 5,
      center: myLatLng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      disableDefaultUI: false,
      zoomControl: true
    });
    var polyOptions = {
      strokeWeight: 0,
      fillOpacity: 0.45,
      editable: true
    };
    <?php if($action == "Edit") { ?>
    	drawingModevalue = null;
    <?php } else { ?>
    	 drawingModevalue = google.maps.drawing.OverlayType.POLYGON;
    <?php } ?>

    drawingManagerPass = new google.maps.drawing.DrawingManager({
      drawingMode: drawingModevalue,
      drawingControl: true,
      drawingControlOptions: {
        position: google.maps.ControlPosition.TOP_RIGHT,
        drawingModes: ['polygon', 'polyline']
      },
      polygonOptions: polyOptions,
      map: mapPass
    });


    google.maps.event.addListener(drawingManagerPass, 'overlaycomplete', function(e) {
        var isNotMarker = (e.type != google.maps.drawing.OverlayType.MARKER);
        drawingManagerPass.setDrawingMode(null);
        var newShape = e.overlay;
        newShape.type = e.type;
        google.maps.event.addListener(newShape, 'click', function() {
          setSelectionPass(newShape, isNotMarker);
        });
        google.maps.event.addListener(newShape, 'drag', function() {
          updateCurSelTextPass(newShape);
        });
        google.maps.event.addListener(newShape, 'dragend', function() {
          updateCurSelTextPass(newShape);
        });
        setSelectionPass(newShape, isNotMarker);
    });
    

    google.maps.event.addListener(drawingManagerPass, 'drawingmode_changed', clearSelectionPass);
    google.maps.event.addListener(mapPass, 'click', clearSelectionPass);
    google.maps.event.addDomListener(document.getElementById('delete-button-pass'), 'click', deleteSelectedShapePass);
    google.maps.event.addListener(mapPass, 'bounds_changed', function() {
      var bounds = mapPass.getBounds();
    });

    //~ initSearch(); ============================================
    // Create the search box and link it to the UI element.
     inputPass = /** @type {HTMLInputElement} */( //var
        document.getElementById('pac-input-pass'));
    mapPass.controls[google.maps.ControlPosition.TOP_RIGHT].push(inputPass);

    var autocomplete = new google.maps.places.Autocomplete(inputPass);
    autocomplete.bindTo('bounds', mapPass);

    // Listen for the event fired when the user selects an item from the
    // pick list. Retrieve the matching places for that item.
    var marker = new google.maps.Marker({
        map: mapPass
    });
        
    autocomplete.addListener('place_changed', function() {
    	marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }
  
        // If the place has a geometry, then present it on a map.
        placeMarkersPass = [];
        if (place.geometry.viewport) {
            mapPass.fitBounds(place.geometry.viewport);
        } else {
            mapPass.setCenter(place.geometry.location);
            mapPass.setZoom(14);
        }

        // Create a marker for each place.
        marker = new google.maps.Marker({
          map: mapPass,
          title: place.name,
          position: place.geometry.location
        });


        marker.setIcon(({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(25, 25)
        }));
        marker.setVisible(true);

    });

    //~ EndSearch(); ============================================    

    // Polygon Coordinates
    var tPassengerLongitude = $('#tPassengerLongitude').val();
    var tPassengerLatitude = $('#tPassengerLatitude').val();
    var Country = $("#iCountry").val();
	if(Country != "" && (tPassengerLongitude == "" || tPassengerLatitude == "")) {
		getGeoCounty(Country);
  	 	myLatLng = new google.maps.LatLng($("#cLatitude").val(), $("#cLongitude").val());
  	 	mapPass.fitBounds(myLatLng);
	} else {
	    if(tPassengerLongitude != "" || tPassengerLatitude != "" ) {
		    var tlat = tPassengerLatitude.split(",");
		    var tlong = tPassengerLongitude.split(",");
		    var triangleCoords = [];
		    var bounds = new google.maps.LatLngBounds();
			for(var i=0,len = tlat.length;i<len;i++) {
		 		if(tlat[i] != "" || tlong[i] != "") {
		 			triangleCoords.push(new google.maps.LatLng(tlat[i], tlong[i]));
					var point = new google.maps.LatLng(tlat[i], tlong[i]);
					bounds.extend(point);
		 		}
			}
			// Styling & Controls
			myPolygonPass = new google.maps.Polygon({
				paths: triangleCoords,
				draggable: false, // turn off if it gets annoying
				editable: true,
				strokeColor: '#FF0000',
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: '#FF0000',
				fillOpacity: 0.35
			});
			mapPass.fitBounds(bounds);
			myPolygonPass.setMap(mapPass);

			google.maps.event.addListener(myPolygonPass.getPath(), "insert_at", getPolygonCoordsPass);
			google.maps.event.addListener(myPolygonPass.getPath(), "set_at", getPolygonCoordsPass);
			google.maps.event.addDomListener(document.getElementById('delete-button-pass'), 'click', deleteEditShapePass);
		}
	}
}

google.maps.event.addDomListener(window, 'load', initializePass);
</script>
</body>
<!-- END BODY-->
</html>