//------------------------------------------
// Payment Fill-in Fields v1.0
// Global JS File
//------------------------------------------

//==========================================
// Set up
//==========================================

// Sniffer based on http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html

var uagent    = navigator.userAgent.toLowerCase();
var is_safari = ( (uagent.indexOf('safari') != -1) || (navigator.vendor == "Apple Computer, Inc.") );
var is_ie     = ( (uagent.indexOf('msie') != -1) && (!is_opera) && (!is_safari) && (!is_webtv) );
var is_ie4    = ( (is_ie) && (uagent.indexOf("msie 4.") != -1) );
var is_moz    = (navigator.product == 'Gecko');
var is_ns     = ( (uagent.indexOf('compatible') == -1) && (uagent.indexOf('mozilla') != -1) && (!is_opera) && (!is_webtv) && (!is_safari) );
var is_ns4    = ( (is_ns) && (parseInt(navigator.appVersion) == 4) );
var is_opera  = (uagent.indexOf('opera') != -1);
var is_kon    = (uagent.indexOf('konqueror') != -1);
var is_webtv  = (uagent.indexOf('webtv') != -1);

var is_win    =  ( (uagent.indexOf("win") != -1) || (uagent.indexOf("16bit") !=- 1) );
var is_mac    = ( (uagent.indexOf("mac") != -1) || (navigator.vendor == "Apple Computer, Inc.") );
var ua_vers   = parseInt(navigator.appVersion);


//==========================================
// Hide / Unhide page elements
//==========================================

function ShowHide(id1, id2)
{
	if (id1 != '') toggleview(id1);
	if (id2 != '') toggleview(id2);
}
	
//==========================================
// Get element by id
//==========================================

function my_getbyid(id)
{
	itm = null;
	
	if (document.getElementById)
	{
		itm = document.getElementById(id);
	}
	else if (document.all)
	{
		itm = document.all[id];
	}
	else if (document.layers)
	{
		itm = document.layers[id];
	}
	
	return itm;
}

//==========================================
// Show/hide toggle
//==========================================

function toggleview(id)
{
	if ( ! id ) return;
	
	if ( itm = my_getbyid(id) )
	{
		if (itm.style.display == "none")
		{
			my_show_div(itm);
		}
		else
		{
			my_hide_div(itm);
		}
	}
}

//==========================================
// Set DIV ID to hide
//==========================================

function my_hide_div(itm)
{
	if ( ! itm ) return;
	
	itm.style.display = "none";
}

//==========================================
// Set DIV ID to show
//==========================================

function my_show_div(itm)
{
	if ( ! itm ) return;
	
	itm.style.display = "";
}
