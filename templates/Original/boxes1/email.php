<?php
$email_boxes_action = true;
if($email_boxes_action==true){	//for index.php
?>


<div class="email border_1 margin_b10">
	<div class="inmail">
      <form action="" method="post" name="form_free_subscription" id="form_free_subscription" onSubmit="News_Letter_Email_Submit_Check(); return false;">
	  <h3><?php echo db_to_html('免费获得旅游折扣信息')?></h3>
	  <div id="msn_free_subscription"><!--反馈信息--></div>
	  <div class="guanjianzi2" id="submitdiv">
	 <input name="email_address" onFocus="News_Letter_Email_Check_Onfocus(this)" onBlur="News_Letter_Email_Check_Onblur(this)" type="text" class="input_search2" id="email_address" value="<?php echo YOUR_NEWS_LETTER_EMAIL ?>" size="22" />

	  <div class="button">
      <input type="submit" class="submit" value="<?php echo db_to_html('提交')?>" />
	  <?php # echo tep_template_image_submit('email_button.gif', 'Free subscription'); ?>
	  </div>	
	  </div><div class="clear"></div>
    </form>
    </div>
	  <script type="text/javascript"><!--form_free_subscription的检查
	  function News_Letter_Email_Check_Onfocus(obj){
	  	if(obj.value=="<?php echo YOUR_NEWS_LETTER_EMAIL ?>"){
			obj.value="";
		}
	  }
	  function News_Letter_Email_Check_Onblur(obj){
	  	if(obj.value=="" || obj.value=="<?php echo YOUR_NEWS_LETTER_EMAIL ?>"){
			obj.value="<?php echo YOUR_NEWS_LETTER_EMAIL ?>";
		}
	  }
	  function News_Letter_Email_Submit_Check(){
		var form = document.getElementById('form_free_subscription');
		var error = false;
		var error_message = "";
		if(form.elements['email_address'].value=="" || form.elements['email_address'].value=="<?php echo YOUR_NEWS_LETTER_EMAIL ?>"){
			error_message += '<?php echo JS_NO_NEWS_LETTER_EMAIL ?>'+"\n";
			error = 'true';
		}
		if(error == 'true'){
			alert(error_message);
			return false;
		}else{
			//form.submit();
			<?php
			$p=array('/&amp;/','/&quot;/');
			$r=array('&','"');
			?>

			document.getElementById('msn_free_subscription').innerHTML = '<?php echo PLX_WAIT ?>';
			var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('free_subscription_ajax.php')) ?>");
			var aparams=new Array(); 
			for(i=0; i<form.length; i++){
				var sparam=encodeURIComponent(form.elements[i].name);
				sparam+="=";
				sparam+=encodeURIComponent(form.elements[i].value);
				aparams.push(sparam);
			}
			var post_str=aparams.join("&");	
			ajax.open("POST", url, true); 
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
			ajax.send(post_str);
			ajax.onreadystatechange = function() { 
				if (ajax.readyState == 4 && ajax.status == 200 ) {
					document.getElementById('msn_free_subscription').style.display = ''; 
					document.getElementById('msn_free_subscription').innerHTML = ajax.responseText;
					document.getElementById('submitdiv').style.display = 'none';
					setTimeout(function(){
						document.getElementById('msn_free_subscription').style.display='none';
						document.getElementById('submitdiv').style.display = '';
					},2000);
				}
			}
		}
	  }
	  --></script>
</div>


<?php }?>