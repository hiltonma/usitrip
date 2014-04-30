<?php ob_start(); ?>
<!--<div class="custom-wrap">-->
	<div class="custom-banner">
    	<img class="bannerpic" src="<?php 
    	if ($language == 'schinese'){
    		echo 'image/custom-banner.jpg';
		} else {
			echo 'image/custom-banner-t.jpg';
		}?>" alt="走四方个性化定制" />
    </div>
    <div class="custom-step">
    	<img class="stepic" src="<?php 
    	if ($language == 'schinese') {
			echo 'image/custom-banner.jpg';
		} else {
			echo 'image/custom-banner-t.jpg';
		}?>" alt="个性定制流程" />
    </div>
    <div class="custom-cnt fix" id="setp1">
    	<a target="_blank" href="http://webchat.b.qq.com/webchat.htm?sid=218808P8z8p8x8x8p8K8P" style="cursor:pointer;">
		<h2 class="<?php 
    	if ($language == 'schinese') {
			echo 'custom-cnt-header';
		} else {
			echo 'custom-cnt-header-t';
		}?>">除了拨打热线电话，您也可以联系我们的顾问VIP专线，随时进行专业服务咨询。VIP专线电话：1-626-898-7800</h2></a>
        <?php #tep_href_link('packet_group.php','action=send')
echo tep_draw_form('packet_group', '#', 'post', 'onsubmit="return false;" id="packet_group"'); ?>
		<ul class="custom-form">
        	<li>
            	<table>
                	<tbody>
                    	<tr>
                        	<td width="215">&nbsp;</td>
                            <td><label for="destination">出游目的地：</label><?php echo tep_draw_input_field('to_city','',' class="iptext required" id="to_city" title="出游目的地必须填写！"');?><span class="iptips">(可填写多个目的地，用"，"号割开。例如：英国，法国)</span></td>
                        </tr>
                        <tr>
                        	<td>&nbsp;</td>
                            <td>
                            	<label>出发日期：</label>
                                <select name="departureYear" id="startYear" title="请选择年份！" onchange="resetMonth(this.value)">
                                	<option value="">选择年份</option>
                                	<?php
                                	$startYear = (int)date('Y');
                                	$endYear = $startYear + 5;
                                	for ($i = $startYear; $i < $endYear; $i++) {
                                		echo '<option value="' . $i . '">' . $i . '</option>';
                                	}
                                	?>
                                </select>年
                                <select name="departureMonth" id="startMonth">
                                	<option value="default">选择月份</option>
                                	<?php $startMonth = (int)date('m');
                                	for ($i = $startMonth; $i <= 12; $i++) {
                                		echo '<option value="' . $i . '">' . $i . '</option>';
                                	}
                                	?>
                                </select>月
                                <script type="text/javascript">
                                function resetMonth(year) {
                                	var currentYear = <?php echo $startYear;?>;
                                	var currentMonth = <?php echo $startMonth?>;
                                	var startM = 1,html='';
                                	if (year == currentYear) {
                                		startM = currentMonth;
                                	}
                                	for (var i = startM; i <= 12; i++ ) {
                                		html += '<option vlaue="' + i + '">' + i + '</option>';
                                	}
                                	jQuery('#startMonth').html(html);
                                }
                                </script>
                            </td>
                        </tr>
                        <tr>
                        	<td>&nbsp;</td>
                            <td>
                            	<label>行程天数：</label>
                                <label for="dater1" class="iptcheck"><?php echo tep_draw_radio_field("d", "1-4天", true, ' id="dater1"')?>1-4天</label>
                                <label for="dater2" class="iptcheck"><?php echo tep_draw_radio_field("d", "5-8天", false, ' id="dater2"')?>5-8天</label>
                                <label for="dater3" class="iptcheck"><?php echo tep_draw_radio_field("d", "9-12天", false, ' id="dater3"')?>9-12天</label>
                                <label for="dater4" class="iptcheck"><?php echo tep_draw_radio_field("d", "12天以上", false, ' id="dater4"')?>12天以上</label>
                                <?php /*<label for="more" class="iptcheck"><?php echo tep_draw_radio_field("d", "其他", false, ' id="more"')?>其他</label>*/?>
                            </td>
                        </tr>
                        <tr>
                        	<td>&nbsp;</td>
                            <td>
                            	<label>出游人数：</label>
                                <span class="checkage">成人<?php echo tep_draw_input_num_en_field('homan','',' class="iptext required" onblur="if(this.value>1){jQuery(\'#visa_part\').css(\'display\',\'\');}else{jQuery(\'#visa_part\').css(\'display\',\'none\');}" style="width:50px;" id="homan" title="请填写成人数！"');?></span>
                                <span class="checkage">儿童(0-12岁)<?php echo tep_draw_input_num_en_field('child','',' class="iptext required" style="width:50px;" id="child" title="请填写儿童数！"');?></span>
                            </td>
                        </tr>
                        <tr>
                        	<td>&nbsp;</td>
                            <td>
                            	<label>已有签证：</label>
                                <span class="checkage"><label for="has" class="iptcheck">是</label><?php echo tep_draw_radio_field("visa", "是", false, ' id="has"')?></span>
                                <span class="checkage"><label for="nonhas" class="iptcheck">否</label><?php echo tep_draw_radio_field("visa", "否", true, ' id="nonhas"')?></span>
								<span class="checkage" id="visa_part" style="display:none"><label for="part" class="iptcheck">部分</label><?php echo tep_draw_radio_field("visa", "部分", false, ' id="part"')?></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </li>
            <li>
            	<table>
                	<tbody>
                    	<tr>
                        	<td width="215">
                            	<h3>我的联系方式：</h3>
                            </td>
                            <td><label for="contacter">联系人：</label><?php
                            $people = tep_customers_name($customer_id);
                            echo tep_draw_input_field('people',$people,' class="iptext required" id="people" title="联系人必须填写！"');?><em class="equired">*</em></td>
                        </tr>
                        <tr>
                        	<td>&nbsp;</td>
                            <td>
                            <label>&nbsp;</label>
                            <?php
                            $gender = tep_customer_gender($customer_id);
							$gender = tep_not_null($gender) ? $gender : 'm';
                            /*
                            Gender: Male
							Gender: Female
                            */
                            ?>
                            <span class="checkage"><label for="man" class="iptcheck">先生</label><?php echo tep_draw_radio_field("gender", "先生", ($gender == 'm'), ' id="man"')?></span>
                            <span class="checkage"><label for="woman" class="iptcheck">女士</label><?php echo tep_draw_radio_field("gender", "女士", ($gender == 'f'), ' id="woman"')?></span>
                            </td>
                        </tr>
                        <tr>
                        	<td>&nbsp;</td>
                            <td><label for="contactel">联系电话：</label><?php
                            $telephone_temp = tep_customers_cellphone($customer_id);
                            unset($telephone);
                            echo tep_draw_input_num_en_field('telephone',$telephone_temp,' class="iptext required validate-number" id="telephone" title="请输入正确的电话号码！"');?>
                            <em class="equired">*</em></td>
                        </tr>
                        <tr>
                        	<td width="215">&nbsp;</td>
                            <td><label for="contactemail">常用邮箱：</label><?php
							$email = tep_get_customers_email($customer_id);
							echo tep_draw_input_num_en_field('email','','  class="iptext required validate-email" id="email" title="邮箱地址未填写或者填写不正确！"');?><em class="equired">*</em></td>
                        </tr>
                    </tbody>
                </table>
            </li>
            <li>
            	<table>
                	<tbody>
                    	<tr>
                        	<td width="215" class="explain-title">
                            	<h3>其他需求/特别说明：</h3>
                            </td>
                            <td>
                            <?php 
                            echo tep_draw_textarea_field("explain", 5, 30, 30, '', 'class="explain"  id="explain"');
                            ?>	
                            
                            </td>
                        </tr>
                        <tr>
                        	<td>&nbsp;</td>
                            <td><label for="validatenub" class="iptcheck">验证码：<?php echo tep_draw_input_field('visual_verify_code' ,'','tabindex="6" id="vvcInput" class="iptvalidate" onfocus="jQuery(\'#vvcInput\').addClass(\'on\');jQuery(\'#verify_vvc\').fadeOut(\'slow\')" onblur="jQuery(\'#vvcInput\').removeClass(\'on\');verifyVVC()" title="验证码未填写！"' )?></label><span class="validate-code"><img  onclick="updateVVC()" src="<?php echo $RandomImg?>" id="vvc" alt="看不清?点击换一张图。" title="看不清?点击换一张图。" /></span><span class="check-validate-code">(看不清？<a href="javascript:void(0)"onclick="updateVVC()">换一个！</a>)</span><span id="verify_vvc"></span>
							</td>
                        </tr>
                    </tbody>
                </table>
            </li>
            <li class="nobd">
            	<table>
                	<tbody>
                    	<tr>
                        	<td width="215">&nbsp;</td>
                            <td>
                            	<input type="submit" class="custom-submit" alt="确认提交" value="确认提交" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </li>
        </ul>
        </form><script type="text/javascript">
							function updateVVC(){
								var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('contact_us.php')) ?>");     
								jQuery.get(url,{"action":"updateVVC",'random':Math.random()},function(data){
									jQuery("#vvc").attr('src', data);
           						});
    						}
							
							function verifyVVC(){
								verified_vvc = false;
								var vvc = jQuery("#vvcInput").val();       
								var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('packet_group.php')) ?>");   
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
							
							
							function formCallback(result, form) {
								//alert("valiation callback for form '" + form.id + "': result = " + result);
								window.status = "valiation callback for form '" + form.id + "': result = " + result;
								
								if (result == true) {
									if(jQuery('#startYear').val() == ''){
										alert('请选择年份！');
										return false;
									}
									if(jQuery('#vvcInput').val() == ''){
										alert('请填写验证码！');
										return false;
									}
									jQuery('input[type="submit"]').attr('disabled',true);
									var data = {
										'to_city' : jQuery('#to_city').val(),
										'year-month' : jQuery('#startYear').val() + '-' + jQuery('#startMonth').val(),
										'to_day' : jQuery('input[id^="dater"]:checked').val(),
										'homan_child' : '成人' + jQuery('#homan').val() + '儿童' + jQuery('#child').val(),
										'visa' : jQuery('input[name="visa"]:checked').val(),
										'user-name' : jQuery('#people').val(),
										'gender' : jQuery('input[name="gender"]:checked').val(),
										'tel' : jQuery('#telephone').val(),
										'mail' : jQuery('#email').val(),
										'content' : jQuery('#explain').val(),
										'visual_verify_code' : jQuery('#vvcInput').val()
									};
									jQuery.post(url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('packet_group.php','action=send')) ?>"),data,function(rt){// 赋值并显示成功信息
									if(parseInt(rt,10) > 0) {
										alert(rt);
										jQuery('input[type="submit"]').attr('disabled',false);
										return;
									}
									jQuery('#user-name').html(data['user-name']);
									jQuery('#show_to_city').html(data['to_city']);
									jQuery('#to_day').html(data['to_day']);
									jQuery('#year-month').html(data['year-month']);
									jQuery('#homan_child').html(data['homan_child']);
									jQuery('#show_visa').html(data['visa']);
									jQuery('#show_gender').html(data['gender']);
									jQuery('#show_tel').html(data['tel']);
									jQuery('#show_mail').html(data['mail']);
									jQuery('#show_msg').html(data['content']);
									
									
										jQuery('#setp1').slideUp();
										jQuery('#setp2').slideDown();},'text');
									
									
									
								}
							}
							
							var valid = new Validation('packet_group', {immediate : true,useTitles:true, onFormValidate : formCallback});	
							</script>
        <div class="custom-thk fix">
        	<h3>感谢您填写个性定制计划！</h3>
            <p>若您在出行中有更多具体需求，可致电定制专线：001-888-887-2186(美) 0086-4006-333-926(中)</p>        
        </div>
    </div>
<!--</div>-->
<div class="custom-wrap" id="setp2" style="display:none">
	<div class="custom-cnt fix"><br />
        <h2 class="custom-cnt-header">除了拨打热线电话，您也可以联系我们的顾问VIP专线，随时进行专业服务咨询。VIP专线电话：1-626-898-7800 1-225-754-4325</h2>
        <div class="custom-success-wrap">
            <div class="custom-success-title">
                <h3>尊敬的 <strong id="user-name" class="user-name">XXX</strong> <span id="show_gender">先生/女士</span>：</h3>
                <p>您的个性旅游需求已经成功提交！如下：</p>
            </div>
            <ul class="custom-success-list">
                <li>出游目的地：<span id="show_to_city">美国</span></li>
                <li>行程天数：<span id="to_day">1-4天</span></li>
                <li>出发日期：<span id="year-month">2012-7</span></li>
                <li>出游人数：<span id="homan_child">1位成人、2位2-12岁之间的儿童、2个婴儿</span></li>
                <li>已有签证：<span id="show_visa">否</span></li>
                <li>联系电话：<span id="show_tel">xxxxxxxx</span></li>
                <li>常用邮箱：<span id="show_mail">1@1.com</span></li>
                
                <li>我还有些特殊需求：<span id="show_msg"></span></li>
            </ul>
            <h3 class="custom-success-tips">我们会在24小时内与您联系，请您耐心等候，谢谢！ <a href="<?php echo tep_href_link('index.php');?>">&gt;&gt; 返回首页</a> </h3>
			
        </div>
    </div>
</div>
<?php echo  db_to_html(ob_get_clean());?>