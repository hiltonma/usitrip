<?php
/**
 * @package: 	Google Map Class
 * @author: 	Mitchelle C. Pascual (mitch.pascual at gmail dot com)
 *				http://ordinarywebguy.wordpress.com
 * @date: 		March 27, 2007
 * @warning:	Use this class at your own risk. Not recommended to set more than 20 addresses at a time.
 */
class GoogleMap {

	/**
	 * @desc: 	Google Map Key
	 * @type: 	string
	 * @access: private
	 */
	var $mMapKey;

	/**
	 * @desc: 	Map Place Holder Sizes
	 * @type: 	int
	 * @access:	private
	 */
	var $mMapWidth;
	var $mMapHeight;

	/**
	 * @desc: 	Map Zoom Value
	 * @type: 	int
	 * @access:	private
	 */
	var $mMapZoom;
	
	/**
	 * @desc: 	Distance Data Array Holder
	 * @type: 	array
	 * @access: private
	 */
	var $mDistanceArr =  array();
	var $mFromAddress =  "";
	var $mFromAddressLatLnt =  "";
	
	/**
	 * @desc: 	Address Data Array Holder
	 * @type: 	array
	 * @access: private
	 */
	var $mAddressArr =  array();

	/**
	 * @desc: 	Info Window Array Holder
	 * @type: 	array
	 * @access: private
	 */
	var $mInfoWindowTextArr = array();

	/**
	 * @desc: 	Side Click Array Holder
	 * @type: 	array
	 * @access: private
	 */
	var $mSideClickArr = array();

	/**
	 * @desc: 	Var Holder of Marker Icon Color Scheme
	 * @type: 	string
	 * @access: private
	 */
	var $mDefColor;
	
	/**
	 * @desc: 	Arrays of Marker Icon Color Scheme
	 * @type: 	array
	 * @access: private
	 */
	var $mIconColor = array(
							'PACIFICA'		=>'pacifica',
							'YOSEMITE'		=>'yosemite',
							'MOAB'			=>'moab',
							'GRANITE_PINE'	=>'granitepine',
							'DESERT_SPICE'	=>'desertspice',
							'CABO_SUNSET'	=>'cabosunset',
							'TAHITI_SEA'	=>'tahitisea',
							'POPPY'			=>'poppy',
							'NAUTICA'		=>'nautica',
							'DEEP_JUNGLE'	=>'deepjungle',
							'SLATE'			=>'slate'
							);

	/**
	 * @desc: 	Var Holder of Marker Icon
	 * @type: 	string
	 * @acess: 	private
	 */
	var $mDefStyle;
	
	/**
	 * @desc: 	Arrays of Marker Icon Scheme
	 * @type: 	array
	 * @access: private
	 */
	var $mIconStyle = array(
							'FLAG'		=>array(
											'DIR'				=>'flag', 
											'ICON_W'			=>31, 
											'ICON_H'			=>35, 
											'ICON_ANCHR_W'		=>4, 
											'ICON_ANCHR_H'		=>27, 
											'INFO_WIN_ANCHR_W'	=>8, 
											'INFO_WIN_ANCHR_H'	=>3
											),
											
							'GT_FLAT'	=>array(
											'DIR'				=>'traditionalflat', 
											'ICON_W'			=>34, 
											'ICON_H'			=>35, 
											'ICON_ANCHR_W'		=>9, 
											'ICON_ANCHR_H'		=>23, 
											'INFO_WIN_ANCHR_W'	=>19, 
											'INFO_WIN_ANCHR_H'	=>0
											),
											
							'GT_PILLOW'	=>array(
											'DIR'				=>'traditionalpillow', 
											'ICON_W'			=>34, 
											'ICON_H'			=>35, 
											'ICON_ANCHR_W'		=>9, 
											'ICON_ANCHR_H'		=>23, 
											'INFO_WIN_ANCHR_W'	=>19, 
											'INFO_WIN_ANCHR_H'	=>0
											),
											
							'HOUSE'		=>array(
											'DIR'				=>'house', 
											'ICON_W'			=>24, 
											'ICON_H'			=>14, 
											'ICON_ANCHR_W'		=>9, 
											'ICON_ANCHR_H'		=>13, 
											'INFO_WIN_ANCHR_W'	=>9, 
											'INFO_WIN_ANCHR_H'	=>0
											),
											
							'PIN'		=>array(
											'DIR'				=>'pin', 
											'ICON_W'			=>31, 
											'ICON_H'			=>24, 
											'ICON_ANCHR_W'		=>17, 
											'ICON_ANCHR_H'		=>22, 
											'INFO_WIN_ANCHR_W'	=>17, 
											'INFO_WIN_ANCHR_H'	=>0
											),
											
							'PUSH_PIN'	=>array(
											'DIR'				=>'pushpin', 
											'ICON_W'			=>40, 
											'ICON_H'			=>41, 
											'ICON_ANCHR_W'		=>7, 
											'ICON_ANCHR_H'		=>38, 
											'INFO_WIN_ANCHR_W'	=>26, 
											'INFO_WIN_ANCHR_H'	=>1
											),
											
							'STAR'		=>array(
											'DIR'				=>'star', 
											'ICON_W'			=>29, 
											'ICON_H'			=>39, 
											'ICON_ANCHR_W'		=>15, 
											'ICON_ANCHR_H'		=>15, 
											'INFO_WIN_ANCHR_W'	=>19, 
											'INFO_WIN_ANCHR_H'	=>7
											)
							);

	/**
	 * @desc: Var Holder of Map Control 
	 * @type: string
	 * @access: private
	 */
	var $mDefControl;

	/**
	 * @desc: 	Arrays of Map Control Scheme
	 * @type: 	array
	 * @access: private
	 */
	var $mControl = array(
							'NONE',
							'SMALL_PAN_ZOOM',
							'LARGE_PAN_ZOOM',
							'SMALL_ZOOM'
						);

	/**
	 * @desc: 	Enable/Disable Map Continuous Zooming
	 * @type: 	boolean
	 * @acess: 	public
	 */
	var $mContinuousZoom = FALSE;

	/**
	 * @desc: 	
	 * @type: 	booleanEnable/Disable Map Double Click Zooming
	 * @access: public
	 */
	var $mDoubleClickZoom = FALSE;

	/**
	 * @desc: 	Enable/Disable Map Scale (MI/KM)
	 * @type: 	boolean
	 * @access: public
	 */
	var $mScale = TRUE;

	/**
	 * @desc: 	Enable/Disable Map Inset
	 * @type: 	boolean
	 * @acess: 	public
	 */
	var $mInset = TRUE;

	/**
	 * @desc: 	Enable/Disable Map Type (Map/Satellite/Hybrid)
	 * @type: 	boolean
	 * @acess: 	public
	 */
	var $mMapType = TRUE;

	/**
	 * @desc: 	Enable/Disable Info Window Direction Option
	 * @type: 	boolean
	 * @access: public
	 */
	#var $mDirection = TRUE;

	/**
	 * @desc: 	Index Array
	 * @type: 	int
	 * @access: private
	 */
	var $mIndex;

	/**
	 * @desc:	Constructor
	 * @param: 	string (Google Map Key)
	 * @access: public
	 * @return: void
	 */
	function GoogleMap($mapKey) {
		$this->mMapKey = $mapKey;
		$this->SetMapWidth();
		$this->SetMapHeight();
		$this->SetMapZoom();
		$this->SetMarkerIconColor();
		$this->SetMarkerIconStyle();
		$this->SetMapControl();
		$this->mIndex = -1;
	} # end function

	function SetFromAddressLatLng($address) {
		$this->mFromAddress=$address;
		$this->mFromAddressLatLnt = $this->GetAddressLatLng($address);
	}
	
	function GetAddressLatLng($address) {
		$resp_lat_lng = "";
		$resp_lat_lng = @file_get_contents("http://maps.google.com/maps/geo?&q=".urlencode($address)."&output=csv&key=".$this->mMapKey);
		$exp_resp_lat_lng = explode(",", $resp_lat_lng);
		$lat_lang = trim($exp_resp_lat_lng[2]).", ".trim($exp_resp_lat_lng[3]);
		return $lat_lang;
	}
	/**
	 * @desc: 	Set Distance(es)
	 * @param: 	string 
	 * @access: public
	 * @return: void
	 */
	function SetDistance($toAddress, $unit='M') {
		$exp_fromLatLng = explode(",", $this->mFromAddressLatLnt);
		$lat1 = trim($exp_fromLatLng[0]);
		$lon1 = trim($exp_fromLatLng[1]);
		
		$toLatLng = $this->GetAddressLatLng($toAddress);
		$exp_toLatLng = explode(", ", $toLatLng);
		$lat2 = trim($exp_toLatLng[0]);
		$lon2 = trim($exp_toLatLng[1]);
		
		$M =  69.09 * rad2deg(acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lon1 - $lon2))));
		
		switch(strtoupper($unit))
		{
			case 'K':
				// kilometers
				$ret_m = number_format($M * 1.609344, 2, ".", "")."km";
				break;
			case 'N':
				// nautical miles
				$ret_m = number_format($M * 0.868976242, 2, ".", "")."mi";
				break;
			case 'F':
				// feet
				$ret_m = number_format($M * 5280, 2, ".", "")."f";
				break;            
			case 'I':
				// inches
				$ret_m = number_format($M * 63360, 2, ".", "")."in";
				break;            
			case 'M':
			default:
				// miles
				$ret_m = number_format($M, 2, ".", "")."mi";
				break;
		}
		
		$this->mDistanceArr[$toAddress] = $ret_m;
	} # end function
	
	/**
	 * @desc: 	Set Address(es)
	 * @param: 	string 
	 * @access: public
	 * @return: void
	 */
	function SetAddress($address) {
		$this->mIndex++;
		$this->mAddressArr[$this->mIndex] = $address;
		$this->mInfoWindowTextArr[$this->mIndex] = $address;
		$this->mSideClickArr[$this->mIndex] = $address;
	} # end function

	/**
	 * @desc: 	Set Info Window Text
	 * @param: 	string
	 * @access:	public
	 * @return: void
	 */
	function SetInfoWindowText($info) {
		$this->mInfoWindowTextArr[$this->mIndex] = $info;
	} # end function

	/**
	 * @desc: 	Set Side Click for Multiple Addresses
	 * @param: 	string
	 * @access:	public
	 * @return: void
	 */
	function SetSideClick($str) {
		$this->mSideClickArr[$this->mIndex] = $str;
	} # end function

	/**
	 * @desc: 	Set Map Width
	 * @param: 	int 
	 * @access:	public
	 * @return: void
	 */
	function SetMapWidth($width=300) {
		$this->mMapWidth = $width;
	} # end function

	/**
	 * @desc: 	Set Map Zoom
	 * @param: 	int
	 * @access:	public
	 * @return:	void
	 */
	function SetMapZoom($zoom=13) {
		$this->mMapZoom = $zoom;
	} # end function

	/**
	 * @desc: 	Set Map Height
	 * @param: 	int
	 * @access:	public
	 * @return:	void
	 */
	function SetMapHeight($height=300) {
		$this->mMapHeight = $height;
	} # end function

	/**
	 * @desc: 	Set Marker Icon Color Scheme
	 * @param: 	string [options('PACIFICA','YOSEMITE','MOAB','GRANITE_PINE','DESERT_SPICE','CABO_SUNSET','TAHITI_SEA','POPPY','NAUTICA','SLATE')]
	 * @access:	public
	 * @return: void
	 */
	function SetMarkerIconColor($colorScheme="PACIFICA") {
		$this->mDefColor = $colorScheme;
	} # end function

	/**
	 * @desc: 	Set Marker Icon Style Scheme
	 * @param: 	string [options('FLAG','GT_FLAT','GT_PILLOW','HOUSE','PIN','PUSH_PIN','STAR')]
	 * @access:	public
	 * @return: void
	 */
	function SetMarkerIconStyle($style="GT_FLAT") {
		$this->mDefStyle = $style;
	} # end function

	/**
	 * @desc: 	Set Map Control
	 * @param: 	string [options('NONE','SMALL_PAN_ZOOM','LARGE_PAN_ZOOM','SMALL_ZOOM')]
	 * @access:	public
	 * @return: void
	 */
	function SetMapControl($control="SMALL_PAN_ZOOM") {
		$this->mDefControl = $control;
	} # end function

	/**
	 * @desc: 	Generate JS Code 
	 * @param: 	string 
	 * @access: public
	 * @return: string
	 */
	function InitJs() {
        $ret = "";
		# show error if misconfigured
		$is_error = $this->CheckConf();
		if ($is_error) { 
			$ret = $is_error; 
		} else {		
			$cnt_add = count($this->mAddressArr);
			
			$color = $this->mIconColor[$this->mDefColor];
			$dir = $this->mIconStyle[$this->mDefStyle]['DIR'];
	
			$icon_w  = $this->mIconStyle[$this->mDefStyle]['ICON_W'];
			$icon_h  = $this->mIconStyle[$this->mDefStyle]['ICON_H'];
	
			$icon_anchr_w  = $this->mIconStyle[$this->mDefStyle]['ICON_ANCHR_W'];
			$icon_anchr_h  = $this->mIconStyle[$this->mDefStyle]['ICON_ANCHR_H'];
	
			$info_win_anchr_w  = $this->mIconStyle[$this->mDefStyle]['INFO_WIN_ANCHR_W'];
			$info_win_anchr_h  = $this->mIconStyle[$this->mDefStyle]['INFO_WIN_ANCHR_H'];
			
			# start of JS SCRIPT		
            $ret .= "<script type=\"text/javascript\">\n";
			$ret .= "var gmarkers = [];\n";
			$ret .= "var address = [];\n";
			$ret .= "var points = [];\n";
			$ret .= "var gdir = [];\n";
			$ret .= "var distanceArr = [];\n";
				
			$ret .= "if(GBrowserIsCompatible()) { \n";
			$ret .= "	var map = new GMap2(document.getElementById('map')); \n";
	
			# handle map continuous zooming
			$ret .= ($this->mContinuousZoom==TRUE)?"	map.enableContinuousZoom(); \n":"";
	
			# handle map double click zooming
			$ret .= ($this->mDoubleClickZoom==TRUE)?"	map.enableDoubleClickZoom(); \n":"";
	
			# handle map controls
			$mapCtrl = "";
			switch ($this->mDefControl) {
				case 'NONE':
					$mapCtrl = "";
					break;
					
				case 'SMALL_PAN_ZOOM':
					$mapCtrl = "map.addControl(new GSmallMapControl()); \n";
					break;
					
				case 'LARGE_PAN_ZOOM':
					$mapCtrl = "map.addControl(new GLargeMapControl()); \n";
					break;
	
				case 'SMALL_ZOOM':
					$mapCtrl = "map.addControl(new GSmallZoomControl()); \n";
					break;
				
				default;
					break;
			
			} # end switch
			$ret .= "	$mapCtrl";
			
			# handle map scale (mi/km)
			$ret .= ($this->mScale==TRUE)?"	map.addControl(new GScaleControl()); \n":"";
	
			# handle map type (map/satellite/hybrid)
			$ret .= ($this->mMapType==TRUE)?"	map.addControl(new GMapTypeControl()); \n":"";
	
			# handle map inset
			$ret .= ($this->mInset==TRUE)?"	map.addControl(new GOverviewMapControl()); \n":"";
	
			$ret .= "	var geocoder = new GClientGeocoder(); \n";
			$ret .= "	var icon = new GIcon(); \n";
			$ret .= "	icon.image = 'http://google.webassist.com/google/markers/$dir/$color.png'; \n";
			$ret .= "	icon.shadow = 'http://google.webassist.com/google/markers/$dir/shadow.png'; \n";
			$ret .= "	icon.iconSize = new GSize($icon_w,$icon_h); \n";
			$ret .= "	icon.shadowSize = new GSize($icon_w,$icon_h); \n";
			$ret .= "	icon.iconAnchor = new GPoint($icon_anchr_w,$icon_anchr_h); \n";
			$ret .= "	icon.infoWindowAnchor = new GPoint($info_win_anchr_w,$info_win_anchr_h); \n";
			$ret .= "	icon.printImage = 'http://google.webassist.com/google/markers/$dir/$color.gif'; \n";
			$ret .= "	icon.mozPrintImage = 'http://google.webassist.com/google/markers/$dir/{$color}_mozprint.png'; \n";
			$ret .= "	icon.printShadow = 'http://google.webassist.com/google/markers/$dir/shadow.gif'; \n";
			$ret .= "	icon.transparent = 'http://google.webassist.com/google/markers/$dir/{$color}_transparent.png'; \n\n";

			# loop set address(es)
			$total_addr_cntr_ext=$cnt_add-1;
			if($this->mFromAddress!=""){
				$total_addr_cntr_ext=$cnt_add;
			}
			for ($i=$total_addr_cntr_ext; $i>=0; $i--) {
			
				/*********START - distance*******/
				//if($distance_from!="")*/{
				$ret .= "	gdir[$i] = new GDirections(map, document.getElementById(\"directions_$i\")); \n";//Show directions
//				$ret .= "	gdir[$i] = new GDirections(); \n";
				$ret .= "	GEvent.addListener(gdir[$i], \"load\", function(){
											document.getElementById(\"distance_$i\").innerHTML=this.getDistance().html;
										}
									); \n";
				//$ret .= "	GEvent.addListener(gdir[$i], \"error\", handleErrors);";
				/*********END - distance*******/
				
				$ret .= "	var address_$i = {\n";
				$ret .= "	  infowindowtext: '".addslashes($this->mInfoWindowTextArr[$i])."',\n";
				$ret .= "	  full: '".addslashes($this->mAddressArr[$i])."'\n";
				$ret .= "	};\n\n";

				$ret .= "	address[$i] = address_$i.infowindowtext;\n\n";
				
				$ret .= "	geocoder.getLatLng (\n";
				$ret .= "	  address_$i.full,\n";
				$ret .= "	  function(point) {\n";
				$ret .= "		if(point) {\n";
				$ret .= "		  points[$i] = point; \n";	
				$ret .= "		  map.setCenter(point, {$this->mMapZoom});\n";
				$ret .= "		  var marker = new GMarker(point, icon);\n";
				$ret .= "		  GEvent.addListener(marker, 'click', function() {\n";
				$ret .= "			marker.openInfoWindowHtml(address_$i.infowindowtext);\n";
				$ret .= "		  });\n";

				$ret .= "		  map.addOverlay(marker);\n";
				
				if($_GET['selected_addr']==addslashes($this->mAddressArr[$i])){
					$ret .= "		  map.setCenter(points[$i],{$this->mMapZoom});";
					$ret .= "		  marker.openInfoWindowHtml(address_$i.infowindowtext);\n";
				}

				# show only info window to the first set address
				/*if ($i=="0"){
					$ret .= "		  marker.openInfoWindowHtml(address_$i.infowindowtext);\n";
				}*/
				$ret .= "		  gmarkers[$i] = marker;\n";
				
				$ret .= "		}\n";
				$ret .= "		else {\n";
				
												// Start - check again if not found first time
												$ret .= "	geocoder.getLatLng (\n";
												$ret .= "	  address_$i.full,\n";
												$ret .= "	  function(point) {\n";
												$ret .= "		if(point) {\n";
												$ret .= "		  points[$i] = point; \n";	
												$ret .= "		  map.setCenter(point, {$this->mMapZoom});\n";
												$ret .= "		  var marker = new GMarker(point, icon);\n";
												$ret .= "		  GEvent.addListener(marker, 'click', function() {\n";
												$ret .= "			marker.openInfoWindowHtml(address_$i.infowindowtext);\n";
												$ret .= "		  });\n";
								
												$ret .= "		  map.addOverlay(marker);\n";
												
												if($_GET['selected_addr']==addslashes($this->mAddressArr[$i])){
													$ret .= "		  map.setCenter(points[$i],{$this->mMapZoom});";
													$ret .= "		  marker.openInfoWindowHtml(address_$i.infowindowtext);\n";
												}
								
												# show only info window to the first set address
												/*if ($i=="0"){
													$ret .= "		  marker.openInfoWindowHtml(address_$i.infowindowtext);\n";
												}*/
												$ret .= "		  gmarkers[$i] = marker;\n";
								
												$ret .= "		}\n";
												$ret .= "		else {\n";
												$ret .= "		  map.setCenter(new GLatLng(37.4419, -122.1419), {$this->mMapZoom});\n";
												$ret .= "		}\n";
												$ret .= "	  }\n";
												$ret .= "	); // end geocoder.getLatLng\n\n";
												// End - check again if not found first time
				
				$ret .= "		  map.setCenter(new GLatLng(37.4419, -122.1419), {$this->mMapZoom});\n";
				$ret .= "		}\n";
				$ret .= "	  }\n";
				$ret .= "	); // end geocoder.getLatLng\n\n";
				
			} # end for
			$ret .= "} // end if\n\n";
			
			/*Start - Set search address as default address*/
			if($this->mFromAddress!=""){
				/*Start - pointer for searched location*/
				$ret .= "	var icon_find = new GIcon(); \n";
				$color="moab";
				$ret .= "	icon_find.image = 'http://google.webassist.com/google/markers/$dir/$color.png'; \n";
				$ret .= "	icon_find.shadow = 'http://google.webassist.com/google/markers/$dir/shadow.png'; \n";
				$ret .= "	icon_find.iconSize = new GSize($icon_w,$icon_h); \n";
				$ret .= "	icon_find.shadowSize = new GSize($icon_w,$icon_h); \n";
				$ret .= "	icon_find.iconAnchor = new GPoint($icon_anchr_w,$icon_anchr_h); \n";
				$ret .= "	icon_find.infoWindowAnchor = new GPoint($info_win_anchr_w,$info_win_anchr_h); \n";
				$ret .= "	icon_find.printImage = 'http://google.webassist.com/google/markers/$dir/$color.gif'; \n";
				$ret .= "	icon_find.mozPrintImage = 'http://google.webassist.com/google/markers/$dir/{$color}_mozprint.png'; \n";
				$ret .= "	icon_find.printShadow = 'http://google.webassist.com/google/markers/$dir/shadow.gif'; \n";
				$ret .= "	icon_find.transparent = 'http://google.webassist.com/google/markers/$dir/{$color}_transparent.png'; \n\n";
				/*End - pointer for searched location*/
				
				$ret .= "	var from_address = {\n";
				$ret .= "	  infowindowtext: '".addslashes($this->mFromAddress)."',\n";
				$ret .= "	  full: '".addslashes($this->mFromAddress)."'\n";
				$ret .= "	};\n\n";
				
				$ret .= "	geocoder.getLatLng (\n";
				$ret .= "	  from_address.full,\n";
				$ret .= "	  function(point) {\n";
				$ret .= "		if(point) {\n";
				$ret .= "		  map.setCenter(point, {$this->mMapZoom});\n";
				$ret .= "		  var marker = new GMarker(point, icon_find);\n";
				$ret .= "		  GEvent.addListener(marker, 'click', function() {\n";
				$ret .= "			marker.openInfoWindowHtml(from_address.infowindowtext);\n";
				$ret .= "		  });\n";
				$ret .= "		  map.addOverlay(marker);\n";
				$ret .= "		  marker.openInfoWindowHtml(from_address.infowindowtext);\n";
				$ret .= "		}\n";
				$ret .= "		else {\n";
				$ret .= "		 // alert('Location not found: ' + from_address.infowindowtext);\n
											//map.setCenter(new GLatLng(37.4419, -122.1419), {$this->mMapZoom});\n";
											$ret .= "	geocoder.getLatLng (\n";
											$ret .= "	  from_address.full,\n";
											$ret .= "	  function(point) {\n";
											$ret .= "		if(point) {\n";
											$ret .= "		  map.setCenter(point, {$this->mMapZoom});\n";
											$ret .= "		  var marker = new GMarker(point, icon_find);\n";
											$ret .= "		  GEvent.addListener(marker, 'click', function() {\n";
											$ret .= "			marker.openInfoWindowHtml(from_address.infowindowtext);\n";
											$ret .= "		  });\n";
											$ret .= "		  map.addOverlay(marker);\n";
											$ret .= "		  marker.openInfoWindowHtml(from_address.infowindowtext);\n";
											$ret .= "		}\n";
											$ret .= "		else {\n";
											$ret .= "		 // alert('Location not found: ' + from_address.infowindowtext);\n
																		//map.setCenter(new GLatLng(37.4419, -122.1419), {$this->mMapZoom});\n";
											$ret .= "		}\n";
											$ret .= "	  }\n";
											$ret .= "	); // end geocoder.getLatLng\n\n";
				$ret .= "		}\n";
				$ret .= "	  }\n";
				$ret .= "	); // end geocoder.getLatLng\n\n";
			}
			/*End - Set search address as default address*/
			
			/**********Start - Direction************/
			$ret .= "
			function setDirections(fromAddress, toAddress, locale) {
				if(locale=='undefined'){
					locale='en_US';
				}
				for(var cntr_toaddr=0; cntr_toaddr < toAddress.length; cntr_toaddr++){
					gdir[cntr_toaddr].load(\"from: \" + fromAddress + \" to: \" + toAddress[cntr_toaddr],
									{ \"locale\": locale });
				}
			}";
			/**********End - Direction************/
			
			$ret .= "function sideClick(i) {\n";
			$ret .= "   if (gmarkers[i]) {\n";
			$ret .= "	  gmarkers[i].openInfoWindowHtml(address[i]);\n";
			$ret .= "	  map.setCenter(points[i],{$this->mMapZoom});\n";
			$ret .= "   } else {\n";
			$ret .= "	  var htstring = address[i];\n";
			$ret .= "	  var stripped = htstring.replace(/(<([^>]+)>)/ig,'');\n";
			$ret .= "	  alert('Location not found: ' +  stripped);\n";
			$ret .= "   } /*endif*/\n";
			$ret .= "} /*end function */\n";

			$ret .= "</script>\n";
		} # end if

		return $ret;
	} # end function

	/**
	 * @desc: 	Generate JS for Map Key (static)
	 * @access: public
	 * @return: string
	 */
	function GmapsKey() {
		if($language == 'spanish' ){
			$map_url="http://maps.google.es/";
		}else{
			$map_url="http://maps.google.com/";
		}
		return "<script type=\"text/javascript\" src=\"".$map_url."maps?file=api&v=2&dt_t4f=".date("his")."&key={$this->mMapKey}\"></script>\n";	
	} # end function

	/**
	 * @desc: 	Generate Links for Multiple Addresses (static)
	 * @access: public
	 * @return: string
	 */
	function GetSideClick() {
		$ret = "";
		$loop = count($this->mAddressArr);
		for ($i=0; $i<$loop; $i++) {
			$ret .=	"<a href=\"javascript:void($i);\" onclick=\"javascript:sideClick($i);\">{$this->mSideClickArr[$i]}</a><br />\n";
		} # end for

		return $ret;
	} # end function

	/**
	 * @desc: 	Generate Map Holder/Container (static)
	 * @access: public
	 * @return: string
	 */
	function MapHolder() {
		return "<div id=\"map\" style=\"width: ".$this->mMapWidth."px; height: ".$this->mMapHeight."px;\"></div>";
	} # end function

	/**
	 * @desc: 	Generate Unloading Script for Google Map (static)
	 * @access: public
	 * @return: string
	 */
	function UnloadMap() {
		return '<script type="text/javascript">window.onunload = function() { GUnload(); }</script>';
	} # end function

	/**
	 * @desc: 	Check Passed Method Parameters
	 * @access: private
	 * @return: string
	 */
	function CheckConf() {
		$ret = "";
		# map height and width
		if (!is_numeric($this->mMapWidth) || !is_numeric($this->mMapHeight)) 
			$ret .= "<h1>INVALID SetMapWidth() OR SetMapHeight() PARAMETER</h1><br />\n";		
		
		# map control
		if (!in_array($this->mDefControl, $this->mControl)) {
			$ret .= "<h1>INVALID setMapControl() PARAMETER:  $this->mDefControl</h1><br />\n";
			$ret .= "<b>POSSIBLE PARAMETER VALUES: </b><br />\n";
			foreach ($this->mControl as $option=>$value) {
				$ret .= "=>'$option' <br />\n";
			} # end foreach
		} # end if

		# color
		if (!array_key_exists($this->mDefColor, $this->mIconColor)) {
			$ret .= "<h1>INVALID setMarkerIconColor() PARAMETER:  $this->mDefColor</h1><br />\n";
			$ret .= "<b>POSSIBLE PARAMETER VALUES: </b><br />\n";
			foreach ($this->mIconColor as $option=>$value) {
				$ret .= "=>'$option' <br />\n";
			} # end foreach
		} # end if
			
		# style
		if (!array_key_exists($this->mDefStyle, $this->mIconStyle)) {
			$ret .= "<h1>INVALID setMarkerIconStyle() PARAMETER: $this->mDefStyle</h1><br />\n";
			$ret .= "<b>POSSIBLE PARAMETER VALUES: </b><br />\n";
			foreach ($this->mIconStyle as $option=>$value) {
				$ret .= "=>'$option' <br />\n";
			} # end foreach
		} # end if
	
		return $ret;
	} # end function
} # end class
?>