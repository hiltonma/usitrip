<?php
/* 全局的js代码，全站适用 */

$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
$is_js_file = false;	/* 如果为false将以php的格式一行一行列到页面 */
if($base_php_self == "javascript.php"){
	$is_js_file = true;
}
if($is_js_file==false){
?>
<script type="text/javascript"><!--

<?php
}
?>

/* Points/Rewards Module V2.1rc2a bof*/
var point_submitter = null;
function submitFunctionPoint(reedmpoinvalue) {
   point_submitter = 1;
   document.checkout_payment.customer_shopping_points_spending.value = reedmpoinvalue;
}
/* Points/Rewards Module V2.1rc2a eof*/

var selected;
var submitter = null;
function submitFunction() {
   submitter = 1;
   }
function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if(object.id!=""){
  	if (selected) selected.className = 'moduleRowHand';
  	var payment_list_table = document.getElementById('payment_list_table');
	var list_div = payment_list_table.getElementsByTagName("div");
	for(i=0; i<list_div.length; i++){
		if(list_div[i].id.search(/^div_pay_list_/)!= -1){
			list_div[i].className='payment-select-off';
		}
	}
	/* object.className = 'moduleRowSelected'; */
  	
	var top_div = document.getElementById('div_pay_list_'+ object.id.replace(/tr_pay_list_/,''));
  	if(top_div!=null){
		top_div.className = 'payment-select-off ss';
	}
  
  }
  
  selected = object;

/*  one button is not an array */
	if(typeof(buttonSelect)=="undefined"){
		buttonSelect = 0;
	}
	document.checkout_payment.payment[buttonSelect].checked=true;
}

function rowOverEffect(object) {
  if (object.className == 'moduleRowNoHand') object.className = 'moduleRowHand';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowHand') object.className = 'moduleRowNoHand';
}

function popupWindow(url) {
window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=500,screenX=150,screenY=30,top=30,left=150')
}

function IsValidTimeMilitry(timeStr) {
var timePat = /^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/;
var matchArray = timeStr.match(timePat);

if(timeStr.length == 0){
return true;
}
if (matchArray == null) {
alert("Time is not in a valid format.");
return false;
}
hour = matchArray[1];
minute = matchArray[2];
second = matchArray[4];
ampm = matchArray[6];

if (second=="") { second = null; }
if (ampm=="") { ampm = null }

if (hour < 0  || hour > 23) {
alert("Hour must be between 0 and 23 for military time");
return false;
}
if (minute<0 || minute > 59) {
alert ("Minute must be between 0 and 59.");
return false;
}
if (second != null && (second < 0 || second > 59)) {
alert ("Second must be between 0 and 59.");
return false;
}
return true;
}



function check_and_get_guestname(get_email_id, guestname){
	var numtag = guestname.replace('guestname','');
	var GuestEmail = document.getElementById("GuestEmail"+numtag);
	if(GuestEmail.value=="<?php echo JS_MAY_NOT_ENTER_TEXT;?>"){ return false;}
	
	var GetEmailId = document.getElementById(get_email_id);
	var GuestName = document.getElementById(guestname);
	
	if(GetEmailId!=null && GetEmailId.value!=""){

		var url = url_ssl("ajax_checkout_payment.php?email_address=") + GetEmailId.value;
		ajax.open('GET', url, true);  
		ajax.send(null);
		
		ajax.onreadystatechange = function() { 
			if (ajax.readyState == 4 && ajax.status == 200 ) { 
				if(ajax.responseText.search(/\[SUCCESS\]/)!=-1){

					/* alert(ajax.responseText); */
					/* if(GuestName!=null && ajax.responseText.search(/\[NAME\]/)!=-1 && GuestName.value.replace(/ /,'')==""){ */
					if(GuestName!=null && ajax.responseText.search(/\[NAME\]/)!=-1){
						GuestName.value = ajax.responseText.replace(/.*\[NAME\](.+)\[\/NAME\].*/,'$1');
					}

				}else{
					alert('<?php echo db_to_html('客户账号');?>'+GetEmailId.value+'<?php echo db_to_html(' 不存在，请确认您获得正确的拼房同伴的邮箱账号！')?>');
					GetEmailId.value="";
					if(GuestName!=null){
						GuestName.value = "";
					}
				}
			}
			
		}

	}
	
}

/* 选择付款人的单选按钮产生的结果 */

function determine_input_field(obj, id_end_tag){
	var GuestEmail = document.getElementById('GuestEmail'+ id_end_tag);
	var guestname = document.getElementById('guestname'+ id_end_tag);
	var GuestEngXing = document.getElementById('GuestEngXing'+ id_end_tag);
	var GuestEngName = document.getElementById('GuestEngName'+ id_end_tag);
	var guestbodyweight = document.getElementById('guestbodyweight'+ id_end_tag);
	if(obj.value=="0" && obj.checked==true){
		if(GuestEmail!=null && GuestEmail.value=="<?php echo JS_MAY_NOT_ENTER_TEXT;?>"){
			GuestEmail.value = "";
			GuestEmail.className = GuestEmail.className.replace(/huise/,''); 
		}
		if(guestname!=null && guestname.value==""){
			guestname.value = "<?php echo JS_MAY_NOT_ENTER_TEXT;?>";
			guestname.className = guestname.className + ' huise '; 
		}
		if(GuestEngXing!=null && GuestEngXing.value==""){
			GuestEngXing.value = "<?php echo JS_MAY_NOT_ENTER_TEXT;?>";
			GuestEngXing.className = GuestEngXing.className + ' huise '; 
		}
		if(GuestEngName!=null && GuestEngName.value==""){
			GuestEngName.value = "<?php echo JS_MAY_NOT_ENTER_TEXT;?>";
			GuestEngName.className = GuestEngName.className + ' huise '; 
		}
		if(guestbodyweight!=null && guestbodyweight.value==""){
			guestbodyweight.value = "<?php echo JS_MAY_NOT_ENTER_TEXT;?>";
			guestbodyweight.className = guestbodyweight.className + ' huise '; 
		}
	}else{
		if(GuestEmail!=null && GuestEmail.value==""){
			GuestEmail.value = "<?php echo JS_MAY_NOT_ENTER_TEXT;?>";
			GuestEmail.className = GuestEmail.className + ' huise '; 
		}
		if(guestname!=null && guestname.value=="<?php echo JS_MAY_NOT_ENTER_TEXT;?>"){
			guestname.value = "";
			guestname.className = guestname.className.replace(/huise/,''); 
		}
		if(GuestEngXing!=null && GuestEngXing.value=="<?php echo JS_MAY_NOT_ENTER_TEXT;?>"){
			GuestEngXing.value = "";
			GuestEngXing.className = GuestEngXing.className.replace(/huise/,''); 
		}
		if(GuestEngName!=null && GuestEngName.value=="<?php echo JS_MAY_NOT_ENTER_TEXT;?>"){
			GuestEngName.value = "";
			GuestEngName.className = GuestEngName.className.replace(/huise/,''); 
		}
		if(guestbodyweight!=null && guestbodyweight.value=="<?php echo JS_MAY_NOT_ENTER_TEXT;?>"){
			guestbodyweight.value = "";
			guestbodyweight.className = guestbodyweight.className.replace(/huise/,''); 
		}
	}
}

/* 自动填充顾客资料 */
function AutoPopGuest(i){
	if(i<1){
		alert('error!');
		return false;
	}
	var input_obj = document.getElementsByTagName("input");
	var select_obj=document.getElementsByTagName("select");
	for(x=0;x<select_obj.length;x++){
		var regexp=new RegExp("guestgender\\d+\\\["+i+"\]","g");
		if(select_obj[x].id.search(regexp)>-1){
		var GuestSexId=select_obj[x].id;
		var b_GuestSex = document.getElementById(GuestSexId.replace(/\[\d\]+/,'['+(i-1)+']'));
		if(b_GuestSex!=null&&b_GuestSex.value!=''){
			select_obj[x].selectedIndex=document.getElementById(GuestSexId.replace(/\[\d\]+/,'['+(i-1)+']')).selectedIndex;
		}
		}
	}
	for(j=0; j<input_obj.length; j++){
	
		var regexp = new RegExp("guestname\\d+\\_" +i ,"g");
		if(input_obj[j].id.search(regexp)>-1){
			/* var b_id = input_obj[j].id.substr(0,(input_obj[j].id.length - 1) ) + (i-1); */
			var b_id = input_obj[j].id.replace(/\d+$/,(i-1));
			var b_guestname = document.getElementById(b_id);
			if(b_guestname!=null){
				input_obj[j].value = b_guestname.value;	/* 中文名 */
			}
			//var GuestSexId=select_obj[j].id.replace(/^[^\d]+/,'guestgender');
			//var GuestSex = document.getElementById(GuestSexId);
			//var b_GuestSex = document.getElementById(GuestSexId.replace(/\d+$/,(i-1)));
			//if(b_GuestSex!=null && GuestSex!=null){
			//	GuestSex.selectedIndex=GuestSex.selectIndex;
			//}
			/* 英文姓 start */
			var GuestEngXingId = input_obj[j].id.replace(/^[^\d]+/,'GuestEngXing');
			var GuestEngXing = document.getElementById(GuestEngXingId);
			var b_GuestEngXing = document.getElementById(GuestEngXingId.replace(/\d+$/,(i-1)));
			if(b_GuestEngXing!=null && GuestEngXing!=null){
				GuestEngXing.value = b_GuestEngXing.value;
			}
			/* 英文姓 end */
			/* 英文名 start */
			var GuestEngNameId = input_obj[j].id.replace(/^[^\d]+/,'GuestEngName');
			var GuestEngName = document.getElementById(GuestEngNameId);
			var b_GuestEngName = document.getElementById(GuestEngNameId.replace(/\d+$/,(i-1)));
			if(b_GuestEngName!=null && GuestEngName!=null){
				GuestEngName.value = b_GuestEngName.value;
			}
			/* 英文名 end */
			/* 小孩出生日期 start */
			var guestchildageId = input_obj[j].id.replace(/^[^\d]+/,'guestchildage');
			var guestchildage = document.getElementById(guestchildageId);
			var b_guestchildage = document.getElementById(guestchildageId.replace(/\d+$/,(i-1)));
			if(b_guestchildage!=null && guestchildage!=null){
				guestchildage.value = b_guestchildage.value;
			}
			/* 小孩出生日期 end */
			/* 体重 start */
			var guestbodyweightId = input_obj[j].id.replace(/^[^\d]+/,'guestbodyweight');
			var guestbodyweight = document.getElementById(guestbodyweightId);
			var b_guestbodyweight = document.getElementById(guestbodyweightId.replace(/\d+$/,(i-1)));
			if(b_guestbodyweight!=null && guestbodyweight!=null){
				guestbodyweight.value = b_guestbodyweight.value;
			}
			/* 体重 end */
			/* height start */
			var guestbodyheightId = input_obj[j].id.replace(/^[^\d]+/,'guestbodyheight');
			var guestbodyweight = document.getElementById(guestbodyheightId);
			var b_guestbodyheight = document.getElementById(guestbodyheightId.replace(/\d+$/,(i-1)));
			if(b_guestbodyheight!=null && guestbodyheight!=null){
				guestbodyheight.value = b_guestbodyheight.value;
			}
			/* height end */
			/* 电子邮件 start */
			var GuestEmailId = input_obj[j].id.replace(/^[^\d]+/,'GuestEmail');
			var GuestEmail = document.getElementById(GuestEmailId);
			var b_GuestEmail = document.getElementById(GuestEmailId.replace(/\d+$/,(i-1)));
			if(b_GuestEmail!=null && GuestEmail!=null){
				GuestEmail.value = b_GuestEmail.value;
			}
			/* 电子邮件 end */
			/* amit dded for extrafields start */
			var guestgenderId = input_obj[j].id.replace(/^[^\d]+/,'guestgender');
			var guestgender = document.getElementById(guestgenderId);
			var b_guestgender = document.getElementById(guestgenderId.replace(/\d+$/,(i-1)));
			if(b_guestgender!=null && guestgender!=null){
				guestgender.value = b_guestgender.value;
			}
			
			var guestdobId = input_obj[j].id.replace(/^[^\d]+/,'guestdob');
			var guestdob = document.getElementById(guestdobId);
			var b_guestdob = document.getElementById(guestdobId.replace(/\d+$/,(i-1)));
			if(b_guestdob!=null && guestdob!=null){
				guestdob.value = b_guestdob.value;
			}
			/* amit added for extra fields end */
			
			
		}
	}
	/* var ='guestname0_'+i; */
	/* document.getElementById('guestname'+ id_end_tag); */
}

/* 根据列出填写的客户资料供选择单人的客户名 */
function SelSingleName(num){
	if(num==''){
		alert('no num');
		return false;
	}
	var LayerSingleName = document.getElementById('LayerSingleName'+num);
	/* guestname0_0 */
	var input_obj = document.getElementsByTagName("input");
	 
	var html_code = '';
	for(j=0; j<input_obj.length; j++){
		var regexp = new RegExp("guestname\\d+\\_" +num ,"g");
		if(input_obj[j].id.search(regexp)>-1 && input_obj[j].value !=''){
			html_code += '<div class ="meun_sel" ';
			html_code +=' onMouseOut="this.className=&quot;meun_sel&quot; "';
			html_code +=' onMouseMove="this.className=&quot; meun_sel1&quot;"';
			html_code +=' onclick="GetSingleName(\''+num+'\',this,\'' + input_obj[j].id + '\')" >'+input_obj[j].value+'</div>';
		}
	}
	if(html_code!=''){
		LayerSingleName.innerHTML = html_code;
		LayerSingleName.style.display ='';
	}

}

function GetSingleName(num, obj1,id){
	var obj = document.getElementById('SingleName_'+num);
	var LayerSingleName = document.getElementById('LayerSingleName'+num);
	if(obj!=null){
		obj.value = obj1.innerHTML;
		var browser = navigator.appName;
		if(browser=='Netscape'){
			obj.focus();
			obj.blur();
			obj.select(); 
		}
		if(browser == 'Microsoft Internet Explorer'){
			/* obj.value = obj.value; */
			/* obj.focus(); */
			obj.select(); 
		}
	}
	var i = id.replace('guestname','');
	i = parseInt(i,10);
	var sex = jQuery('select[id=\'guestgender' + i + '[' + num + ']\']').val()
	if (sex == '<?php echo db_to_html('男')?>') {
		jQuery('input[name=\'SingleGender[' + num + ']\'][value=\'m\']').attr('checked',true);
	} else if (sex == '<?php echo db_to_html('女')?>') {
		jQuery('input[name=\'SingleGender[' + num + ']\'][value=\'f\']').attr('checked',true);
	}
	
	LayerSingleName.innerHTML ='';
	LayerSingleName.style.display ='none';
}

/* 团购免填顾客资料按钮 */
function can_un_fill_guest(parent_id , button_obj){
	var parent_obj = document.getElementById(parent_id);
	var input_obj = parent_obj.getElementsByTagName('input');
	var guestname_default = "<?php echo db_to_html('走四方网填写');?>";
	var xin_default = "Xing";
	var ming_default = "Ming";
	var age_default = "<?php echo date('m/d/Y');?>";
	var weight_default = "1";
	if(button_obj.checked==true){
		for(i=0; i<input_obj.length; i++){
			if(input_obj[i].type=="text"){
				if(input_obj[i].id.indexOf('guestname')>-1){
					input_obj[i].value = guestname_default;
				}
				if(input_obj[i].id.indexOf('GuestEngXing')>-1){
					input_obj[i].value = xin_default;
				}
				if(input_obj[i].id.indexOf('GuestEngName')>-1){
					input_obj[i].value = ming_default;
				}
				if(input_obj[i].id.indexOf('guestchildage')>-1){
					input_obj[i].value = age_default;
				}
				if(input_obj[i].id.indexOf('guestbodyweight')>-1){
					input_obj[i].value = weight_default;
				}
			}
		}
	}else{
		for(i=0; i<input_obj.length; i++){
			if(input_obj[i].type=="text"){
				if(input_obj[i].id.indexOf('guestname')>-1 && input_obj[i].value == guestname_default){
					input_obj[i].value = "";
				}
				if(input_obj[i].id.indexOf('GuestEngXing')>-1 && input_obj[i].value == xin_default){
					input_obj[i].value = "";
				}
				if(input_obj[i].id.indexOf('GuestEngName')>-1 && input_obj[i].value == ming_default){
					input_obj[i].value = "";
				}
				if(input_obj[i].id.indexOf('guestchildage')>-1 && input_obj[i].value == age_default){
					input_obj[i].value = "";
				}
				if(input_obj[i].id.indexOf('guestbodyweight')>-1 && input_obj[i].value == weight_default){
					input_obj[i].value = "";
				}
			}
		}
	}
}
	

<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>

<?php include(dirname(__FILE__).'/get_country_tel_code.js.php');?>