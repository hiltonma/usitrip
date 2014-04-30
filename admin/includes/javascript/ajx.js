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
function getInfo(url,updatevalues,products_id){
try
{
	//alert(url);
	if(url=="duration1")
	{
		http.open('get', "input_1.php?du="+document.new_product.products_durations.value);
		http.onreadystatechange = handleInfo;
	}
	else if(url=="departureno1")
	{
	 http.open('get', "input_1.php?de="+document.new_product.products_departure_places.value);
	 http.onreadystatechange = handleInfo_de;
	}
	else if(url=="agediscount1")
	{
	 http.open('get', "input_1.php?agepr="+document.new_product.agediscount.checked);
	 http.onreadystatechange = handleInfo_ag;
	}
	else if(url=="hoteldiscount1")
	{
	 http.open('get', "input_1.php?hotpr="+document.new_product.hoteldiscount.checked);
	 http.onreadystatechange = handleInfo_hot;
	}
	else if(url=="lodgingdiscount1")
	{
	 http.open('get', "input_1.php?lodpr="+document.new_product.lodgingdiscount.checked);
	 http.onreadystatechange = handleInfo_lod;
	}
	else if(url=="otherdiscount1")
	{
	 http.open('get', "input_1.php?othpr="+document.new_product.otherdiscount.checked);
	 http.onreadystatechange = handleInfo_oth;
	}
	else if(url=="regular1")
	{
		//alert(updatevalues);
	 http.open('get', "input_1.php?regu=Regular&regularyes="+updatevalues+"&products_id="+products_id);
	 http.onreadystatechange = handleInfo_regu;
	}
	else if(url=="regulardailytype1")
	{
	 http.open('get', "input_1.php?regulardailytype=daily");
	 http.onreadystatechange = handleInfo_regu_daily;
	}
	else if(url=="regularweektype1")
	{
	 http.open('get', "input_1.php?regulardailytype=week");
	 http.onreadystatechange = handleInfo_regu_daily;
	}
	else if(url=="unregular1")
	{
			//alert(updatevalues);
	 http.open('get', "input_1.php?regu=Unregular&irregular="+updatevalues+"&products_id="+products_id);//update value gives the totalno of dates 
	 http.onreadystatechange = handleInfo_regu;
	}
	else if(url=="toalnoofdates")
	{
			//alert(updatevalues);
	 http.open('get', "input_1.php?toalnoofdates="+document.new_product.totaldates.value+"&irredular_days="+updatevalues);
	 http.onreadystatechange = handleInfo_total;
	}

	
	
	http.send(null);
}
catch(e)
{
	alert(e);
}	
}
/******************************************************/
function handleInfo(){
	
if(http.readyState == 4){

 var response = http.responseText;
try
{
	//alert(response);
	document.getElementById('div_id').innerHTML = response;
	
 }
 catch(e)
 {
 	alert(e);
 }
}
}
/******************************************************/
/****************************  de     **************************/
function handleInfo_de(){
	
if(http.readyState == 4){

 var response = http.responseText;
try
{
	//alert(response);
	document.getElementById('div_id_departure').innerHTML = response;

 }
 catch(e)
 {
 	alert(e);
 }
}
}
/******************************************************/
/*************************   ag     *****************************/
function handleInfo_ag(){
	
if(http.readyState == 4){

 var response = http.responseText;
try
{
	//alert(response);
	document.getElementById('div_id_agediscount').innerHTML = response;

 }
 catch(e)
 {
 	alert(e);
 }
}
}
/******************************************************/
/*************************   hot     *****************************/
function handleInfo_hot(){
	
if(http.readyState == 4){

 var response = http.responseText;
try
{
	//alert(response);
	document.getElementById('div_id_hoteldiscount').innerHTML = response;

 }
 catch(e)
 {
 	alert(e);
 }
}
}
/******************************************************/
/*************************   lod     *****************************/
function handleInfo_lod(){
	
if(http.readyState == 4){

 var response = http.responseText;
try
{
	//alert(response);
	document.getElementById('div_id_lodgingdiscount').innerHTML = response;

 }
 catch(e)
 {
 	alert(e);
 }
}
}
/******************************************************/
/*************************   oth     *****************************/
function handleInfo_oth(){
	
if(http.readyState == 4){

 var response = http.responseText;
try
{
	//alert(response);
	document.getElementById('div_id_otherdiscount').innerHTML = response;

 }
 catch(e)
 {
 	alert(e);
 }
}
}
/******************************************************/
/*************************   regu     *****************************/
function handleInfo_regu(){
	
if(http.readyState == 4){

 var response = http.responseText;
try
{
	//alert(response);
	document.getElementById('div_id_regular').innerHTML = response;
	//alert(document.body.innerHTML);

 }
 catch(e)
 {
 	alert(e);
 }
}
}
/******************************************************/
/*************************   regu_daily     *****************************/
function handleInfo_regu_daily(){
	
if(http.readyState == 4){

 var response = http.responseText;
try
{
	//alert(response);
	document.getElementById('div_id_regulartypedaily').innerHTML = response;

 }
 catch(e)
 {
 	alert(e);
 }
}
}
/******************************************************/
/*************************   total     *****************************/
function handleInfo_total(){
	
if(http.readyState == 4){

 var response = http.responseText;
try
{
	//alert(response);
	document.getElementById('div_id_totaldates').innerHTML = response;
	//alert(document.body.innerHTML);
 }
 catch(e)
 {
 	alert(e);
 }
}
}
/******************************************************/

