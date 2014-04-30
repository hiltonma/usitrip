<?php
include ('weather_function.php');
/*
<script type="text/javascript" src="http://us.js2.yimg.com/us.js.yimg.com/lib/common/utils/2/dragdrop_2.0.0-b1.js"></script>
*/
?>
<script type="text/javascript" src="YAHOO.js"></script>
<script type="text/javascript" src="event_2.0.0-b1.js"></script>
<script type="text/javascript" src="connection.js"></script>

<script type="text/javascript" src="dom.js"></script>
<script type="text/javascript" src="dom_ext.js"></script>
<link type="text/css" rel="stylesheet" href="weather.css">
	<div id="weather">	
		<form action="weather_function.php" method="get" >
			<label><?php echo TEXT_HEADING_ENTER_US_ZIP_CODE;?></label>
			<input name="action" value="add" type="hidden" />
			<input name="query" size="50" type="text" id="weather-query" />
			<input value="Go" type="submit" id="weather-submit" />			
		</form>
		<div class="bd">
		<ul>
		<?php
		
		echo getUserWeatherItemMarkup();
		?>
		</ul>
		</div>
	</div>
<script type="text/javascript" src="weather.js"></script>