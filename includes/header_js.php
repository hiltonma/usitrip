<script language="javascript">
<!--
function onmounseover(id){
	var td = document.getElementById("td"+id);
	var div = document.getElementById(id);
	var divTop = findPosY(td);
	var divLeft = findPosX(td);
	div.style.display = 'block';
	div.style.visibility = 'visible';
	div.style.left = divLeft;
	div.style.top = divTop + td.offsetHeight - 4;
	//bof of page select menu's hidden.
	if(id == 'westcoast'){
	<?php 	if($content == 'index_default'){?>
		document.getElementById('departurecity').style.visibility = 'hidden';
		document.getElementById('durations').style.visibility = 'hidden';
		document.getElementById('attraction').style.visibility = 'hidden';
		<?}?>
	onMouseOut('eastcoast');
	onMouseOut('Hawaii');
	onMouseOut('Florida');
	onMouseOut('ByCity');
	onMouseOut('TourPackages');
	}
	if(id == 'eastcoast'){
		<?php 		if($content == 'index_default'){?>
		document.getElementById('departurecity').style.width='126px';
	<?}?>
	onMouseOut('westcoast');
	onMouseOut('Hawaii');
	onMouseOut('Florida');
	onMouseOut('ByCity');
	onMouseOut('TourPackages');
	}
	if(id == 'Hawaii'){
		<?php 		if($content == 'advanced_search_result'){?>
		document.getElementById('advdeparturecity').style.visibility = 'hidden';
		document.getElementById('advattraction').style.visibility = 'hidden';
		<?}?>
	onMouseOut('westcoast');
	onMouseOut('eastcoast');
	onMouseOut('Florida');
	onMouseOut('ByCity');
	onMouseOut('TourPackages');
	}
	if(id == 'Florida'){
	
	onMouseOut('westcoast');
	onMouseOut('eastcoast');
	onMouseOut('Hawaii');
	onMouseOut('ByCity');
	onMouseOut('TourPackages');

	}
	if(id == 'ByCity'){
	onMouseOut('westcoast');
	onMouseOut('eastcoast');
	onMouseOut('Hawaii');
	onMouseOut('Florida');
	onMouseOut('TourPackages');
	}
	if(id == 'TourPackages'){
		<?php 		if($content == 'advanced_search_result'){?>
		document.getElementById('advdurations').style.visibility = 'hidden';
		<?}?>
	onMouseOut('westcoast');
	onMouseOut('eastcoast');
	onMouseOut('Hawaii');
	onMouseOut('Florida');
	onMouseOut('ByCity');
	}
	//eof of page select menu's hidden.
}

function onMouseOut(id){
document.getElementById(id).style.display = 'none';

//bof of page select menu's hidden.
	if(id == 'westcoast'){
	<?php 	if($content == 'index_default'){?>
		document.getElementById('departurecity').style.visibility = 'visible';
		document.getElementById('durations').style.visibility = 'visible';
		document.getElementById('attraction').style.visibility = 'visible';
		<?}?>
	}
	if(id == 'eastcoast'){
		<?php 		if($content == 'index_default'){?>
		document.getElementById('departurecity').style.width='180px'
	<?}?>
	}
	if(id == 'Hawaii'){
		<?php 		if($content == 'advanced_search_result'){?>
		document.getElementById('advdeparturecity').style.visibility = 'visible';
		document.getElementById('advattraction').style.visibility = 'visible';
		<?}?>
	}
	if(id == 'Florida'){
	}
	if(id == 'ByCity'){
	}
	if(id == 'TourPackages'){
		<?php 		if($content == 'advanced_search_result'){?>
		document.getElementById('advdurations').style.visibility = 'visible';
		<?}?>
	}
	//eof of page select menu's hidden.
	
}

function findPosX(obj)
{
    var curleft = 0;
    if (obj.offsetParent)
    {
        while (obj.offsetParent)
        {
            curleft += obj.offsetLeft
            obj = obj.offsetParent;
        }
    }
    else if (obj.x)
        curleft += obj.x;
    return curleft;
}
 
function findPosY(obj)
{
    var curtop = 0;
    if (obj.offsetParent)
    {
        while (obj.offsetParent)
        {
            curtop += obj.offsetTop
            obj = obj.offsetParent;
        }
    }
    else if (obj.y)
        curtop += obj.y;
    return curtop;
}

//-->
</script>