<?php
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

function showPic (whichpic) { 	
	if (document.getElementById) { 		
		document.getElementById('placeholder').src = whichpic.href; 
		document.getElementById('ShowLightBox').href = whichpic.href;
		document.getElementById('ShowLightBox').title = whichpic.title;

		if (whichpic.title) { 			
			if(document.getElementById('desc')!=null){
				/* document.getElementById('desc').childNodes[0].nodeValue = whichpic.title;  */
			}
		} else { 			
			if(document.getElementById('desc')!=null){
				/* document.getElementById('desc').childNodes[0].nodeValue = whichpic.childNodes[0].nodeValue; */
			} 
		}
		return false; 	
	} else { 		
		return true; 	
	}
}

/* 通过发送酒店信息给朋友 */
function Check_Onfocus(obj,msn){
	if(obj.value==msn){
		obj.value="";
	}
}
function Check_Onblur(obj,msn){
	if(obj.value==""){
		obj.value=msn;
	}
}

function Check_Submit(form_id,msn_id){
	var FormObj = document.getElementById(form_id);
	var MsnHmtl = document.getElementById(msn_id);
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('hotel_ajax.php')); ?>");
	var error = false;
	var error_message = "";
	
	if(error == 'true'){
		/* alert(error_message); */
		MsnHmtl.innerHTML = '<div class="messageStackError">'+ error_message +'</div>';
		return false;
	}else{
		MsnHmtl.innerHTML = '<div style="color:#FF6600;"><img src="image/ajax-loading.gif" align="absmiddle" style="margin-right:5px;" /><?php echo db_to_html('正在发送...');?></div>';
		var aparams=new Array(); 
		for(i=0; i<FormObj.length; i++){
			var sparam=encodeURIComponent(FormObj.elements[i].name);
			sparam+="=";
			sparam+=encodeURIComponent(FormObj.elements[i].value);
			aparams.push(sparam);
		}
		var post_str=aparams.join("&");	
		ajax.open("POST", url, true); 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		ajax.send(post_str);
		ajax.onreadystatechange = function() { 
			if (ajax.readyState == 4 && ajax.status == 200 ) { 
				MsnHmtl.innerHTML = ajax.responseText;
			}
		}
	}
}

/* 复制内容到粘贴板 */
function copy_input(id, success_msn){
	var txt = document.getElementById(id).value;
	var success_msn = success_msn;
	copyToClipboard(txt, success_msn);
}
function  copyToClipboard(txt, success_msn){   
       if(window.clipboardData) {   
               window.clipboardData.clearData();   
               window.clipboardData.setData("Text", txt);
             alert(success_msn);   
       } else if(navigator.userAgent.indexOf("Opera") != -1) {   
            window.location = txt;   
       } else if (window.netscape) {   
            try {   
                 netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");   
            } catch (e) {   
                 alert("<?php echo db_to_html('如果您正在使用FireFox！\n请在流览器位址栏输入\'about:config\'并回车\n然后将\'signed.applets.codebase_principal_support\'设置为\'true\'')?>");   
            }   
            var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);   
            if (!clip)   
                 return;   
            var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);   
            if (!trans)   
                 return;   
            trans.addDataFlavor('text/unicode');   
            var str = new Object();   
            var len = new Object();   
            var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);   
            var copytext = txt;   
            str.data = copytext;   
            trans.setTransferData("text/unicode",str,copytext.length*2);   
            var clipid = Components.interfaces.nsIClipboard;   
            if (!clip)   
                 return false;   
            clip.setData(trans,null,clipid.kGlobalClipboard);   
            alert(success_msn)   
       }   
}


<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>