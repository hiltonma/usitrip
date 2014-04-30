<?php ob_start();
  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
	//success
	$messageStack->add('contact',TEXT_SUCCESS,'success');
	echo "<div id='success_table'>";
	echo html_to_db($messageStack->output('contact'));
	echo "</div>";
  }
?>

	<div id="abouts">
    	<?php
		require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/about_left.php');
		?>
        <div class="abouts_right">
        	<div class="aboutsTit">
            	<ul>
                	<li>联系我们</li>
                </ul>
          </div>
            <div class="aboutsCont ">
                   <div class="about3_1">
                  		<h4 class="about-title">服务热线<span>呼叫中心全年7×24小时无休贴心服务</span></h4>
                        <ul>
                        	<li class="s_1">国际总机(60热线)：1-626-898-7800</li>
                        	<li class="s_2">美加免费热线：1-888-887-2816</li>
                        	<li class="s_3">中国服务热线：4006-333-926</li>
                        	<?php /*<li class="s_3">台湾(高雄)：886-7-2515689</li>
                        	<li class="s_4">美加VIP专线：1-225-754-4328</li>
                        	<li class="s_4">美加VIP专线：1-225-754-4325</li>
                        	<li class="s_4">中国VIP专线：0755-2305-4633</li>*/?>
                        </ul>
                    </div>                    
              <div class="about3_2">
           		<h4 class="about-title">在线洽谈<span>在线客服旅游顾问时刻为您答疑解惑</span></h4>
                  <ul>
                   		<li><a href="http://webchat.b.qq.com/webchat.htm?sid=218808P8z8p8x8x8p8K8P"><img src="/image/abouts_dot10.gif" width="185" height="59" alt="企业QQ,QQ:40063333926" /></a></li>
                    	<li><a href="callto://usitrip1/"><img src="/image/abouts_dot12.gif" width="197" height="59" alt="客服Skype,Skype：usitrip1" /></a></li>
                      	<?php /*<li><a href="msnim:chat?contact=US2@usitrip.com"><img src="/image/abouts_dot11.gif" width="203" height="59" alt="官方MSN,MSN:US2@usitrip.com" /></a></li>*/?>
                      	<li><img src="/image/weixin.jpg" width="209" height="57" alt="微信帐号：usitrip1" /></li>
                  </ul>
                  <dl>
                  	<dt><img src="/image/abouts_dot13.gif" width="65" height="57" /></dt>
                    <dd>
                    	<ul>
                        	<li>客服服务：service#usitrip.com</li>
                        	<li>市场合作：marketing#usitrip.com</li>
                        	<li>技术支持：support#usitrip.com</li>
                            <li style="color:#C00;">发邮件时请把"#"替换为"@"！</li>
                        </ul>
                    </dd>
                  </dl>
                </div>
              <div class="about3_3">
           		<h4 class="about-title">公司地址<span>走四方旅游网 http://www.usitrip.com/</span></h4>
    <dl>
                	<dt class="s_1">美国</dt>
                    <dd>
                    	<ul>
                        	<li>总部地址：133B W Garvey Ave, Monterey Park, CA, USA 91754</li>
                        	<li>营业厅地址：17506 Colima Road, Suite 101, Rowland Heights, CA, 91748</li>
                        	<li>总机(60热线)：1-626-898-7800</li>
                        	<li>传真：1-626-569-0580</li>
                        </ul>
                    </dd>
                </dl>
				<dl>
                	<dt class="s_2">中国</dt>
                    <dd>
                    	<ul>
                        	<li>地址：深圳市宝安区大龙华中小企业创业总部（民治大道牛栏前大厦）B1508—1510<br />
                        	</li>
                        	<li>总机(100热线)：86-0755-2305 4633</li>
                        	<li>传真：86-0755-2303 6129</li>
                        </ul>
                    </dd>
                </dl>
              </div>
              <div class="about3_5">感谢您对走四方旅游网的支持和信赖！<br />
                无论您有任何问题，可直接填写以下表格咨询，我们将尽快给您回复！<br />
</div>                                        
                    <div class="about3_6">    <?php echo tep_draw_form('contact_us', tep_href_link(FILENAME_CONTACT_US, 'action=send'), 'post', ' id="contact_us"'); ?>
                    	<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#c1dbf1">
  <tr>
    <td height="50" colspan="2" align="center" bgcolor="#f4faff"><strong class="font_size14 color_blue">联系我们</strong></td>
    </tr>
      <tr>
  <td align="right" bgcolor="#f4faff" class="color_blue paddingTable">姓&nbsp;&nbsp;&nbsp;&nbsp;名：</td>
  <td bgcolor="#f4faff" class="paddingTable"><?php
																								$name = ucfirst($customer_first_name) . ' ' . ucfirst($customer_last_name);
																								$name = db_to_html($name);
																								echo tep_draw_input_field('name', '',' class="box1 required" title="姓名必须填写！"  style="width:35%;"'); 
																								?><span class="sp1">* </span></td>
  </tr>
  <tr>
  <td align="right" bgcolor="#f4faff" class="color_blue paddingTable">电子邮箱：</td>
  <td bgcolor="#f4faff" class="paddingTable"><?php 
																								$email = tep_get_customers_email($customer_id);
																								echo tep_draw_input_field('email','','  class="box1 required validate-email" id="email" title="'. html_to_db(ENTRY_EMAIL_ADDRESS_CHECK_ERROR).'" style="width:35%;"');?>
																								    <span class="sp1">* (<?php echo '必须填写但是不会显示在页面'; ?>)</span></td>
  </tr>
  <tr>
    <td width="22%" align="right" bgcolor="#f4faff" class="color_blue paddingTable">旅行团名称： </td>
    <td bgcolor="#f4faff" class="paddingTable"><?php echo tep_draw_input_field('tourname','','  class="box1"  style="width:35%;"'); ?></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#f4faff" class="color_blue paddingTable">旅行团代码：</td>
    <td bgcolor="#f4faff" class="paddingTable"><?php echo tep_draw_input_field('tourcode','',' class="box1"  style="width:35%;"'); ?></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#f4faff" class="color_blue paddingTable">订单编号： </td>
    <td bgcolor="#f4faff" class="paddingTable"><?php echo tep_draw_input_field('reservationnumber','',' class="box1"  style="width:35%;"'); ?></td>
  </tr>

  <tr>
    <td align="right" bgcolor="#f4faff" class="color_blue paddingTable">您的留言： <br /></td>
    <td bgcolor="#f4faff" class="paddingTable"><?php echo tep_draw_textarea_field('enquiry', '', 50, 15,'',' class="required validation-failed" cols="60" rows="7"  style="width: 95%; wrap:soft" title="请输入您的建议或疑问"'); ?><span class="sp1">*</span></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#f4faff" class="color_blue paddingTable">验证码：</td>
    <td bgcolor="#f4faff" class="paddingTable"><?php echo tep_draw_input_field('visual_verify_code' ,'','tabindex="6" id="vvcInput" class="box2"  onfocus="jQuery(\'#vvcInput\').addClass(\'on\');jQuery(\'#verify_vvc\').fadeOut(\'slow\')" onblur="jQuery(\'#vvcInput\').removeClass(\'on\');verifyVVC()"' )?> 
        <img src="<?php echo $RandomImg?>"  onclick="updateVVC()" align="absmiddle" id="vvc" width="75" height="25"  alt="看不清?点击换一张图。" title="看不清?点击换一张图。"/> <a href="javascript:;" onclick="updateVVC()">看不清?点击换一张图。</a><div><dl class="even" id="vvc_warp"  >
            <dt>&nbsp;</dt>
            <dd><span id="verify_vvc"></span></dd>
    </dl></div></td>
    </tr>
</table>
<div class="submit_div"><input type="submit" class="refer" value="提 交"/></div>
</form>
              </div>                    
       	  </div>
        </div>
    </div>
<script type="text/javascript">
function validateResult(msg){
        this.code = -1;
        this.message = "null" ;
        if(typeof(msg) == "string"){
          var sepPos = msg.search(/,/);
          this.code = parseInt(msg.substring(0,sepPos),10);
          this.message = msg.substring(sepPos+1,msg.length);
        }
       this.isSuccess = function(){return this.code == 0 ;}     
       this.toString=function(){return "CODE:"+this.code+" MESSAGE:"+this.message ; }      
    }
    function verifyVVC(){
    	verified_vvc = false;
        var vvc = jQuery("#vvcInput").val();       
    	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('contact_us.php')) ?>");   
    	var doSubmit = arguments.length>0;  
    	if(vvc == ""){
    		jQuery('#verify_vvc').html('<span class="errorTip">请输入图片中显示的字符，不区分大小写。</span>');
			jQuery('#verify_vvc').fadeIn("slow");
			return ;
    	}
    	jQuery.get(url,{"action":"validate","data":vvc,"validator":"vvc"},function(data){
                     result = new validateResult(data);                   
                     if(result.isSuccess()){
                    	 verified_vvc = true;             			
                    	jQuery('#verify_vvc').html('<span class="rightTip">验证码输入正确。</span>');
                    	jQuery('#verify_vvc').fadeIn("slow");
                    	 if(doSubmit)dosubmit();
         			}else{
         				jQuery('#verify_vvc').html('<span class="errorTip">'+result.message+'</span>');
         				jQuery('#verify_vvc').fadeIn("slow");
         			} 
          });
    }
function updateVVC(){
    	 var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('contact_us.php')) ?>");     
     	jQuery.get(url,{"action":"updateVVC",'random':Math.random()},function(data){
                    jQuery("#vvc").attr('src', data); 
           });
    }
	
function formCallback(result, form) {
	//alert("valiation callback for form '" + form.id + "': result = " + result);
	window.status = "valiation callback for form '" + form.id + "': result = " + result;
}

var valid = new Validation('contact_us', {immediate : true,useTitles:true, onFormValidate : formCallback});						
</script>	
<?php echo  db_to_html(ob_get_clean());?>