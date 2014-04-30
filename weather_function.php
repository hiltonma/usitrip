<?php


/*http://developer.yahoo.com/weather/index.html#examples*/
$weatherFeedUrl = 'http://xml.weather.yahoo.com/forecastrss?u=c&p=';
$userZipCode =  ''; //default zip codes

require('rss_parse/rss_fetch.inc');

function loadText($url)
{
	$Error = 0;
	$a = "";
	$fd = fopen ($url , "r");
	while (!feof($fd)) 
	{ 
		$Error++;
		$buffer = (fgets($fd, 4096)); 
		$a .= $buffer;
		if( $Error > 10000)
		{ 
		  break;
		};			
	} ;
	fclose($fd); 
	return trim($a);
};


$ZipCodeTable = array();
function loadZipCode($filePath,$stateName)
{
	global $ZipCodeTable;
	$ZipCode = split("\n" , loadText($filePath) );
	for($i=0;$i < count($ZipCode) ; $i++)
	{
		$t = $ZipCode[$i];
		if( !empty($t))
		{
			$t =  split('=',trim($t) );
			$ZipCodeTable['_' .  $t[1] ] = $t[0] . ', ' . strtoupper ($stateName);
		};
	};
};



function getUserWeatherItemMarkup()
{
	global $userZipCode;
	
	$zp = split(',' , $userZipCode);	
	$rs = '';
	for($i= (count($zp)-1) ;$i>=0;$i--)
	{
		
		if($zp[$i]!='')
		{
			$rs .= getWeatherItemMarkup($zp[$i],false);
		};
	};

	
	return $rs;
};

echo getUserWeatherItemMarkup();




function deleteWeatherItemMarkup($zipcode)
{
	//send delete information to backend
};


function getWeatherItemMarkup($query)
{
	global $ZipCodeTable;
	global $weatherFeedUrl;
	
	$match = 0;
	$query = strtoupper(trim($query));
	foreach($ZipCodeTable as $zipKey => $city)
	{
		$zipcode = substr($zipKey,1,10);
		if( ($query == $zipcode) || (hasPrefix($city , $query )==true) )
		{
			$match = 1;			
			break;
		};
	};
		
	if($match==0)
	{
		return '';
	};
	
	$url = $weatherFeedUrl . $zipcode;
	$rss = fetch_rss($url);
	$href = $rss->items[0]['link'];
	$title =  $rss->items[0]['title'];
	
	$description =  $rss->items[0]['description'];
	$a = split('<br />' ,  strtolower($description) );
	$hight =0;
	$low = 0;
	$currentTemp = 0;
	/*
	echo '<xmp>';
	print_r($a);
	echo '</xmp>';
	*/
	
	$currentTemp = trim($a_price[2]);
	$currentTemp = split(',',$currentTemp);
	$currentStatus =  $currentTemp[0];	$currentTemp = (int) $currentTemp[1];
	
	foreach( $a as $phrase)
	{
		$phrase = strtolower($phrase);
		if(preg_match('/high:/i',$phrase) && preg_match('/low:/i',$phrase) ) 
		{
			$high =  split('high:', $phrase );
			$low = split('low:', $phrase );
			$high =  split('low:', $high[1] );
			$high = trim($high[0]);
			$low = trim($low [1]);			
			break 1;
		};		
	};	
	
	$rs = array(
	'<li class="weather-item">',
		'<div class="header">',
			'<h5><a href="'. $href .'" class="weather-city" title="'. $title .'">', $city ,'</a></h5>',	
			'<a href="weather_function.php?action=delete&zipcode=', $zipcode , '" class="weather-delete">&#9587;</a>',
		'</div>',
		'<div class="body">',					
				'<strong><em>' .$currentTemp . 'ум</em>C</strong>',				
				'<p class="status '. $currentStatus . '"><span>'.$currentStatus.'</span></p>',
				'<div class="temp">',
					'<span class="h">hi '. $high .'</span>умC ',					
					'<span class="l">lo '. $low .'</span>умC ',					
					'<a class="weather-forecast" href="http://xml.weather.yahoo.com/forecast/'.$zipcode.'_c.html#weather_function.php?action=getForecast&zipcode=', $zipcode , '">Extended Forecast</a>',
				'</div>',
		'</div>',	
		
	'</li>'	
	);
	$rs = implode($rs,'');
	return $rs ;
};


function hasPrefix( $string,$prefix)
{
	$string = strtoupper ($string);
	$prefix= strtoupper ($prefix);
	
	if(($string=='') || ($prefix==''))
	{
		return false;
	};

	$prefix =  '_' .  strpos($string ,$prefix ) ;
	
	if( $prefix == '_0' )
	{
		return true ;
	}
	else
	{
		return false;
	};
	
};

function getForecastMarkup($zipcode)
{
	global $weatherFeedUrl;
	$url = $weatherFeedUrl . $zipcode;
	$rss = fetch_rss($url);
	$href = $rss->items[0]['link'];
	$title =  $rss->items[0]['title'];
	
	
	
	
	$description =  $rss->items[0]['description'];
	$a = split('<br />' ,  strtolower($description) );
	$hight =0;
	$low = 0;
	$currentTemp = 0;
	
	
	$currentTemp = trim($a_price[2]);
	$currentTemp = split(' ',$currentTemp);
	$currentStatus =  $currentTemp[0];
	$currentTemp = $currentTemp[1];
	
	foreach( $a as $phrase)
	{
		$phrase = strtolower($phrase);
		if(preg_match('/high:/i',$phrase) && preg_match('/low:/i',$phrase) ) 
		{
			$high =  split('high:', $phrase );
			$low = split('low:', $phrase );
			$high =  split('low:', $high[1] );
			$high = trim($high[0]);
			$low = trim($low [1]);
			
			break 1;
		};		
	};	
	
	
	
	
	
	
	global $ZipCodeTable;
	
	$time = getdate(time()); 
	$week = array('', 'Mon','Tue','Wed', 'Thu' , 'Fri' , 'Sat', 'Sun' , 'Mon','Tue','Wed', 'Thu' , 'Fri' , 'Sat');
	$item = array();
	for($i=0;$i<5;$i++)
	{


		$markup =  array(
			'<li>',
			'<p class="status ' , $currentStatus , '"><span>'.$currentStatus.'</span></p>',
			'<div class="day">' , $week[ $time['wday'] + $i ]  , '</div>',
			'<span class="hi-temp">' , $low   , ' умC</span>',
			'<span class="lo-temp">' , $high  , ' умC</span>',
			'</li>'
		);
		$item[$i] = implode($markup,'');
	};
	


	$city = $ZipCodeTable['_' . $zipcode ];
	if($city==''){return '';};
	$result = array(
	
	'<div class="fhd">',
	'<h2>5 Day Forecast of ' . $city . '</h2>',
	'<a href="?action=exitForecase" class="exit-forecast">&#9587;</a>',
	'</div>',

	'<div class="fbd">',
	'<ul>',
		
		 implode($item,''),
			
		//'<li class="more">',
		//'<a href="'.$href.'">&raquo;10 Day Forecast</a>',
		//'</li>',
	
		'</ul>',
	'</div>',
	'<div class="fft">',	
	'</div>'
	);


	return implode($result,'');
};


function getSuggestListMarkup($query)
{
	global $ZipCodeTable;
	$result = array();
	$query = strtoupper ($query);
	foreach($ZipCodeTable as $zipKey => $zipData)
	{
		$number = substr($zipKey,1,10);
		switch(true)
		{
			case ( hasPrefix( $zipKey , ('_' . $query) ) == true ):;
			$result[] = '{text:"'.$number .' ' . $zipData  . '", number:' . $number . '}' ;
			break;

			case (  hasPrefix( $zipData ,  $query ) == true ):;
			$result[] = '{text:"'.$number .' ' . $zipData  . '", number:' . $number . '}' ;
			//$result[] = '{text:"'. $zipData .' ' . $number . '", number:' . $number . '}' ;
			break;
			
			default:break;
			
		};		
	};
	$result = '{ data:[' . implode($result,",\n") . ']}';
	return $result;
};



//http://www.lcc.ctc.edu/info/charts/zipcodes.htm
loadZipCode('zipcode_ca.txt','CA, U.S.');
loadZipCode('zipcode_tx.txt','TX, U.S.');
loadZipCode('zipcode_ny.txt','NY, U.S.');

$WeathStatus = array();
$WeathStatus[] =array('mc' , 'Mostly Clear');
$WeathStatus[] =array('su' , 'Sunny');
$WeathStatus[] =array('pc' , 'Partly Cloudy');
$WeathStatus[] =array('fs' , 'Few Showers');



if(isset( $_GET['action'] ) )
{
	$action = 	 $_GET['action'] ;
	switch($action)
	{
		case 'suggest':;
		if(isset( $_GET['query'] ) )
		{
			$query = $_GET['query'];
			
			if(!empty($query))
			{
				echo getSuggestListMarkup($query);
			};
		};
		break;

		case 'getForecast':;
		if(	isset( $_GET['zipcode'] ) )
		{
			$zipcode = $_GET['zipcode'];
			
			if(!empty($zipcode))
			{
				echo getForecastMarkup($zipcode);
			};
		};
		break;

		case 'delete':;
		/*http://localhost/phpweb/weather/weather_function.php?action=delete&zipcode=94089*/
		if(isset( $_GET['zipcode'] ) )
		{
			$zipcode = trim( $_GET['zipcode'] );
			
			if(!empty($zipcode))
			{				
				echo  deleteWeatherItemMarkup( $zipcode);
			};
		};
		break;


		case 'add':;
		if(isset( $_GET['query'] ) )
		{
			$query = trim( $_GET['query'] );
			
			if(!empty($query))
			{
				echo  getWeatherItemMarkup( $query ,true );
			};
		};
		break;

		default:break;
	};
};
?>
