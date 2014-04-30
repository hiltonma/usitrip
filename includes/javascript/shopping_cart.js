function createRequestObject(){
		var request_;
		var browser = navigator.appName;
		if(browser == "Microsoft Internet Explorer"){
		 request_ = new ActiveXObject("Microsoft.XMLHTTP");
		}else{
		 request_ = new XMLHttpRequest();
		}
		return request_;
}

		var http = createRequestObject();
		
		function getInfoProductOption(url,products_id){
			try		{
				 if(products_id == '-1')
				 { 
					http.open('get', url);
					http.onreadystatechange = handleInfo;
					http.send(null);
					
				}	
			}catch(e)
			{
				//alert(e);
			}	
		
		}
		
		function handleInfo()
		{
			
			if(http.readyState == 4)
			{
			
			var response = http.responseText;
			
			splt=response.split("||||");
			
				if(splt[0]!="")
				{
				//alert(splt[1]);	
				document.getElementById("cart_product_data_"+splt[0]).innerHTML=""+splt[1]+"";
				}
			}
			
			
}
function remove_item_confirmation(index){
	
	var fRet;
	fRet = confirm('Are you sure you want to delete this tour from Reservation List?');					
					//alert(fRet);
	if (fRet == false) { 
	return false; 	
	}
	

		for ( i= 0 ; i < window.document.cart_quantity.elements.length ; i ++)
					{
						if(window.document.cart_quantity.elements[i].id == "rem_"+index)
						{
							window.document.cart_quantity.elements[i].checked = true;
							
							//window.document.cart_quantity.edit_order.submit(); 
							return true;			
							
						}
						
					}
					
		
}
function HideContent(d) {
	var d = (typeof d == 'string') ? document.getElementById(d) : d;
	if (d.length < 1) { return; }
	d.style.display = "none";
	//}
}
function ShowContent(d) {
	if(d.length < 1) { return; }
	document.getElementById(d).style.display = "block";
}

function ReverseContentDisplay(d) {
if(d.length < 1) { return; }
if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
else { document.getElementById(d).style.display = "none"; }
}

function calcHeight(the_iframe)
{
	
	var the_height=document.getElementById(the_iframe).contentWindow.document.body.scrollHeight;//find the height of the internal page
	var the_width=document.getElementById(the_iframe).contentWindow.document.body.scrollWidth;//find the width of the internal page
	if(the_height != '0'){
	the_height = parseFloat(the_height) + 10;
	//alert(the_height);
	document.getElementById(the_iframe).style.height=the_height+'px';//change the height of the iframe
}
} 
function calcHeight_increase(the_iframe,increment_size)
{

		var browser1 = navigator.appName;
		if(browser1 == "Microsoft Internet Explorer"){
		 var the_height=document.getElementById(the_iframe).contentWindow.document.body.scrollHeight;//find the height of the internal page
		}else{
		 var the_height= '320';
		}

var the_width=document.getElementById(the_iframe).contentWindow.document.body.scrollWidth;//find the width of the internal page

if(the_height != '0'){
the_height = parseFloat(the_height) + parseFloat(increment_size) +20 ;
//alert(the_height);
document.getElementById(the_iframe).style.height=the_height+'px';//change the height of the iframe
}
}

function resize_ifrm_by_id(frmid) { 
    var ifrm = document.getElementById(frmid);
    if(window.frames[frmid] && window.frames[frmid].document) {
      window.frames[frmid].window.scroll(0,0);
      var body = window.frames[frmid].document.body;
      if(body) {
        ifrm.style.height = (body.scrollHeight || body.offsetHeight) + 'px';
      }
    }
  }