<!--*******************************签证内容********************************-->
<?php
ob_start();
?>
<script language="javascript" type="text/javascript">
// 签证首页滑动板块
jQuery(function(){
	//jQuery(".visa_list > li:not(:third)").hide();  
	//jQuery(".visa_list > li").show();
	jQuery(".visa_list li h3").click(function(){	//alert(jQuery(this).parent().next().html());
			if(jQuery(this).parent().next().css('display') == "none"){//alert('=none');
				jQuery(this).parent().parents(".visa_list")
					   .find("li").removeClass("current");
					   //.end();
					   //.find("div").stop(true,true).slideUp("slow");
				jQuery(this).parent().parent().addClass("current")
					   .slideDown("slow");
			}
			else{ //alert('not none');//alert(jQuery(this).parent().parent().parent().html());
			  jQuery(this).parent().parents(".visa_list")
					   .find("li").removeClass("current");
					   //.end()
					   //.find("div").stop(true,true).slideUp("slow");
			}
	})	
})

jQuery(function(){
	jQuery("#faq_visa_cont > div").not(":first").hide();
	jQuery("#faq_visa_tit > li").hover(function(){
		jQuery(this).addClass("cur").siblings().removeClass("cur");
		var index = jQuery("#faq_visa_tit > li").index(this);
		jQuery("#faq_visa_cont > div").eq(index).show().siblings().hide();
	})
});
</script>
<div id="visa_wrap">
	<div id="left_wrap">
    	<div class="visa_content">
            <div class="usa_visa"><!--美国签证-->
               	<dl>
                	<dt><img src="/image/visa/usa_siva_img1.gif" alt="美国签证"/></dt>
                    <dd>
                    	<ul>
                        	<li><h1>美国签证</h1></li>
                            <li><strong class="font14 color1">独家中文在线填写签证申请表DS-160 </strong>  </li>
                            <li>独家对接美国大使馆签证系统，直接打印确认信(含大使馆条形码)面签时携带此确认信！</li>
                        </ul>
                    </dd>
                </dl>
          	</div>
            <div class="visa_flow" <?php if(CHARSET=='big5') {?> style="background: url(<?= DIR_WS_TEMPLATE_IMAGES;?>visa/visa_flow_bg_big5.gif) no-repeat scroll center 0 transparent;"<?php }?>><!--签证办理流程-->
            	<ul class="arrowhead">
                	<li></li>
                	<li></li>
                	<li></li>
                	<li></li>
                </ul>
            	<ul class="imgs">
                	<li><img src="/image/visa/visa_flow_img1.gif" alt="在线中文填表"/><p>在线中文填表</p></li>
                    <li><img src="/image/visa/visa_flow_img2.gif" alt="预约面签时间"/><p>预约面签时间</p></li>
                    <li><img src="/image/visa/visa_flow_img3.gif" alt="资料审核/签证培训"/><p>资料审核/签证培训</p></li>
                    <li><img src="/image/visa/visa_flow_img4.gif" alt="陪同审核"/><p>陪同审核</p></li>
                    <li><img src="/image/visa/visa_flow_img5.gif" alt="出签配送"/><p>出签配送</p></li>
                </ul>
          </div>

            
<!--签证分类-->
<!--在需要默认展开的地方添加:1.li的class="current", 2.下级div的style="display:block;"//-->
            <div class="visa_list">			
   	 			<ul>
                	<li>
						<div class="tit">
							<?php echo get_visa_info_title($visa_product,'探亲访友签证(B2)');?>              
                		</div>
						<div class="visa_list_content">
							<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#dedede">
                				<tr>
                					<td width="167" bgcolor="#FFFFFF" class="paddingL">１、有效护照：</td>
                					<td width="490" bgcolor="#FFFFFF" class="padding15">如果您的护照将在距您预计抵美日期的六个月内过期、或已损坏、或护照上已无空白的签证签发页, 请在前来面谈之前先申请一本新护照。</td>
                				</tr>
				                <tr>
				                	<td bgcolor="#FFFFFF" class="paddingL">２、签证照片：</td>
				                	<td bgcolor="#FFFFFF" class="padding15">于6个月内拍摄的2英寸x2英寸（51毫米x51毫米）正方形白色背景的彩色正面照。详情请见照片要求。请用透明胶带将您的照片贴在护照封面上。 </td>
				                </tr>
				                <tr>
				                	<td bgcolor="#FFFFFF" class="paddingL">３、签证申请费收据原件：<br /></td>
				                	<td bgcolor="#FFFFFF" class="padding15">您可以在中信银行在中国的任何分行支付签证申请费896元人民币()。请将收据用胶水或胶条粘贴在确认页的下半页上。</td>
				                </tr>
				                <tr>
				                	<td bgcolor="#FFFFFF" class="paddingL">４、签证申请费收据原件：</td>
				                	<td bgcolor="#FFFFFF" class="padding15"> 您可以亲自或者委托我公司在中信银行在中国的任何分行支付签证申请费，并需要将收据用胶水或胶条粘贴在确认页的下半页上。</td>
				                </tr>
				                <tr>
				                	<td bgcolor="#FFFFFF" class="paddingL">５、经济证明：</td>
				                	<td bgcolor="#FFFFFF" class="padding15">证明您有能力无需工作即可支付在美停留整个期间的费用，例如能客观反映您每月收入的工资单、上有正常规律的存取记录的存折等。（注意：请不要出示银行存款证明单。存款证明单对签证申请没有帮助。）</td>
				                </tr>
				                <tr>
				                	<td bgcolor="#FFFFFF" class="paddingL">６、亲属关系证明：</td>
				                	<td bgcolor="#FFFFFF" class="padding15">与在美亲属的关系证明，如结婚证原件、子女出生证原件、来往书信和家庭合影等。</td>
				                </tr>
				                <tr>
				                	<td bgcolor="#FFFFFF" class="paddingL">７、家人在美合法身份证明：</td>
				                	<td bgcolor="#FFFFFF" class="padding15">在美亲属的合法身份证明，如护照及美国签证的复印件、留学生的I-20表、交流访问学者的DS-2019表或短期工作人员的I-797表等。</td>
				                </tr>
                			</table>
						</div>
					</li>
	                <li>
						<div class="tit">
							<?php echo get_visa_info_title($visa_product,'参团旅游签证(B2)');?> 
						</div>                
		                <div class="visa_list_content">
							<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#dedede">
				                <tr>
				                	<td width="167" bgcolor="#FFFFFF" class="paddingL"><strong>1</strong>、有效护照：</td>
				                	<td width="490" bgcolor="#FFFFFF" class="padding15">如果您的护照将在距您预计抵美日期的六个月内过期、或已损坏、或护照上已无空白的签证签发页, 请在前来签证之前先申请一本新护照。 </td>
				                </tr>
				                <tr>
				                	<td bgcolor="#FFFFFF" class="paddingL"><strong>2</strong>、DS-160表格确认页：</td>
				                	<td bgcolor="#FFFFFF" class="padding15">请在上面注明您的中文姓名、您中文姓名的电报码、中文家庭地址、公司名字及地址。请将您的表格确认页竖着打印在A4纸上。面谈时请携带打印出来的DS-160表格确认页，不要使用传真的确认页。 </td>
				                </tr>
				                <tr>
				                	<td bgcolor="#FFFFFF" class="paddingL"><strong>3</strong>、一张照片：<br /></td>
				                	<td bgcolor="#FFFFFF" class="padding15">于6个月内拍摄的2英寸x2英寸（51毫米x51毫米）正方形白色背景的彩色正面照。请用透明胶带将您的照片贴在护照封面上。</td>
				                </tr>
				                <tr>
				                	<td bgcolor="#FFFFFF" class="paddingL"><strong>4</strong>、签证申请费收据原件：</td>
				                	<td bgcolor="#FFFFFF" class="padding15"> 您可以亲自或者委托我公司在中信银行在中国的任何分行支付签证申请费，并需要将收据用胶水或胶条粘贴在确认页的下半页上。</td>
				                </tr>
				                <tr>
				                	<td bgcolor="#FFFFFF" class="paddingL"><strong>5</strong>、护照：</td>
				                	<td bgcolor="#FFFFFF" class="padding15">含有以前赴美签证的护照，包括已失效的护照</td>
				                </tr>
				                <tr>
				                	<td bgcolor="#FFFFFF" class="paddingL"><strong>6</strong>、能够说明您为何一定会返回中国的证据：</td>
				                	<td bgcolor="#FFFFFF" class="padding15">出示经济、社会、家庭或其它方面对您具有约束力的文件，以帮助您证明您在美国短暂停留后有意返回中国。由于个人情况的不同，申请人应出示的证据也各不相同。下列文件可以帮助签证官评估您是否有意返回中国：户口本、身份证、雇佣证明、能客观反映您每月收入的工资单、上有正常规律的存取记录的存折等。 </td>
				                </tr>
				                <tr>
				                	<td bgcolor="#FFFFFF" class="paddingL"><strong>7</strong>、资金证明：</td>
				                	<td bgcolor="#FFFFFF" class="padding15">证明您有能力无需工作即可支付在美停留整个期间的费用，例如能客观反映您每月收入的工资单、上有正常规律的存取记录的存折等。（注意：请不要出示银行存款证明单。存款证明单对签证申请没有帮助。）</td>
				                </tr>
			                </table>
						</div>
					</li>
                <li>
				<div class="tit">
				<?php echo get_visa_info_title($visa_product,'个人旅游签证(B2)');?>
                </div>
                <div class="visa_list_content" >
				<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#dedede">
                <tr>
                <td width="167" bgcolor="#FFFFFF" class="paddingL"><strong>1</strong>、有效护照：</td>
                <td width="490" bgcolor="#FFFFFF" class="padding15">如果您的护照将在距您预计抵美日期的六个月内过期、或已损坏、或护照上已无空白的签证签发页, 请在前来签证之前先申请一本新护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>2</strong>、DS-160表格确认页：</td>
                <td bgcolor="#FFFFFF" class="padding15">请在上面注明您的中文姓名、您中文姓名的电报码、中文家庭地址、公司名字及地址。请将您的表格确认页竖着打印在A4纸上。面谈时请携带打印出来的DS-160表格确认页，不要使用传真的确认页。范例请点击这里。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>3</strong>、一张照片：<br /></td>
                <td bgcolor="#FFFFFF" class="padding15">于6个月内拍摄的2英寸x2英寸（51毫米x51毫米）正方形白色背景的彩色正面照。详情请见照片要求。请用透明胶带将您的照片贴在护照封面上。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>4</strong>、签证申请费收据原件：</td>
                <td bgcolor="#FFFFFF" class="padding15">您可以亲自或者委托我公司在中信银行在中国的任何分行支付签证申请费，并需要将收据用胶水或胶条粘贴在确认页的下半页上。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>5</strong>、护照：</td>
                <td bgcolor="#FFFFFF" class="padding15">含有以前赴美签证的护照，包括已失效的护照。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>6</strong>、能够说明您为何一定会返回中国的证据：</td>
                <td bgcolor="#FFFFFF" class="padding15">出示经济、社会、家庭或其它方面对您具有约束力的文件，以帮助您证明您在美国短暂停留后有意返回中国。由于个人情况的不同，申请人应出示的证据也各不相同。下列文件可以帮助签证官评估您是否有意返回中国：户口本、身份证、雇佣证明、能客观反映您每月收入的工资单、上有正常规律的存取记录的存折等。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>7</strong>、邀请信：</td>
                <td bgcolor="#FFFFFF" class="padding15">如果您受邀访问美国的某位居民，那么提供下列信息将对您的申请有所帮助：邀请人信息、访美目的、事先安排的旅行时刻表。如果您只是单纯赴美旅游，那么您无需出示邀请信。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>8</strong>、资金证明：</td>
                <td bgcolor="#FFFFFF" class="padding15">证明您有能力无需工作即可支付在美停留整个期间的费用，例如能客观反映您每月收入的工资单、上有正常规律的存取记录的存折等。（注意：请不要出示银行存款证明单。存款证明单对签证申请没有帮助。）</td>
                </tr>
                </table>
				</div>
                </li>
                <li>
				<div class="tit">
				<?php echo get_visa_info_title($visa_product,'参加会议展览签证(B1/B2)');?>
                </div>
                <div class="visa_list_content" >
				<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#dedede">
                <tr>
                <td width="167" bgcolor="#FFFFFF" class="paddingL"><strong>1</strong>、有效护照：</td>
                <td width="490" bgcolor="#FFFFFF" class="padding15">如果您的护照将在距您预计抵美日期的六个月内过期、或已损坏、或护照上已无空白的签证签发页, 请在前来签证之前先申请一本新护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>2</strong>、DS-160表格确认页：</td>
                <td bgcolor="#FFFFFF" class="padding15">请在上面注明您的中文姓名、您中文姓名的电报码、中文家庭地址、公司名字及地址。请将您的表格确认页竖着打印在A4纸上。面谈时请携带打印出来的DS-160表格确认页，不要使用传真的确认页。范例请点击这里。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>3</strong>、一张照片：<br /></td>
                <td bgcolor="#FFFFFF" class="padding15">于6个月内拍摄的2英寸x2英寸（51毫米x51毫米）正方形白色背景的彩色正面照。详情请见照片要求。请用透明胶带将您的照片贴在护照封面上。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>4</strong>、签证申请费收据原件：</td>
                <td bgcolor="#FFFFFF" class="padding15">您可以亲自或者委托我公司在中信银行在中国的任何分行支付签证申请费，并需要将收据用胶水或胶条粘贴在确认页的下半页上。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>5</strong>、护照：</td>
                <td bgcolor="#FFFFFF" class="padding15">含有以前赴美签证的护照，包括已失效的护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>6</strong>、能够说明您为何一定会返回中国的证据：</td>
                <td bgcolor="#FFFFFF" class="padding15">出示经济、社会、家庭或其它方面对您具有约束力的文件，以帮助您证明您在美国短暂停留后有意返回中国。由于个人情况的不同，申请人应出示的证据也各不相同。下列文件可以帮助签证官评估您是否有意返回中国：户口本、身份证、雇佣证明、能客观反映您每月收入的工资单、上有正常规律的存取记录的存折等。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>7</strong>、邀请函：</td>
                <td bgcolor="#FFFFFF" class="padding15">如果您赴美进行商务活动，请考虑携带下列信息前来面谈：您将访问哪里、会见哪些人员、会见过程中将讨论哪些内容以及您打算在美国购买哪些产品。下列文件可能会对申请有一定帮助：美 国合作方出具的详细邀请函，说明申请人的访美目的；与美国合作方签订的合同或其它协议；将赴美检测或购买的机器、软件或其它设备的相关信息，如产品宣传手 册或产品目录等。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>8</strong>、资金证明：</td>
                <td bgcolor="#FFFFFF" class="padding15">证明您有能力无需工作即可支付在美停留整个期间的费用，例如能客观反映您每月收入的工资单、上有正常规律的存取记录的存折等。（注意：请不要出示银行存款证明单。存款证明单对签证申请没有帮助。）</td>
                </tr>
                </table>
				</div>
                </li>
                <li>
				<div class="tit">
				<?php echo get_visa_info_title($visa_product,'商务考察签证(B1/B2)');?>
                </div>
                <div class="visa_list_content">
				<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#dedede">
                <tr>
                <td width="167" bgcolor="#FFFFFF" class="paddingL"><strong>1</strong>、有效护照：</td>
                <td width="490" bgcolor="#FFFFFF" class="padding15">如果您的护照将在距您预计抵美日期的六个月内过期、或已损坏、或护照上已无空白的签证签发页, 请在前来签证之前先申请一本新护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>2</strong>、DS-160表格确认页：</td>
                <td bgcolor="#FFFFFF" class="padding15">请在上面注明您的中文姓名、您中文姓名的电报码、中文家庭地址、公司名字及地址。请将您的表格确认页竖着打印在A4纸上。面谈时请携带打印出来的DS-160表格确认页，不要使用传真的确认页。范例请点击这里。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>3</strong>、一张照片：<br /></td>
                <td bgcolor="#FFFFFF" class="padding15">于6个月内拍摄的2英寸x2英寸（51毫米x51毫米）正方形白色背景的彩色正面照。详情请见照片要求。请用透明胶带将您的照片贴在护照封面上。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>4</strong>、签证申请费收据原件：</td>
                <td bgcolor="#FFFFFF" class="padding15">您可以亲自或者委托我公司在中信银行在中国的任何分行支付签证申请费，并需要将收据用胶水或胶条粘贴在确认页的下半页上。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>5</strong>、护照：</td>
                <td bgcolor="#FFFFFF" class="padding15">含有以前赴美签证的护照，包括已失效的护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>6</strong>、能够说明您为何一定会返回中国的证据：</td>
                <td bgcolor="#FFFFFF" class="padding15">出示经济、社会、家庭或其它方面对您具有约束力的文件，以帮助您证明您在美国短暂停留后有意返回中国。由于个人情况的不同，申请人应出示的证据也各不相同。下列文件可以帮助签证官评估您是否有意返回中国：户口本、身份证、雇佣证明、能客观反映您每月收入的工资单、上有正常规律的存取记录的存折等。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>7</strong>、邀请函：</td>
                <td bgcolor="#FFFFFF" class="padding15">如果您赴美进行商务活动，请考虑携带下列信息前来面谈：您将访问哪里、会见哪些人员、会见过程中将讨论哪些内容以及您打算在美国购买哪些产品。下列文件可能会对申请有一定帮助：美 国合作方出具的详细邀请函，说明申请人的访美目的；与美国合作方签订的合同或其它协议；将赴美检测或购买的机器、软件或其它设备的相关信息，如产品宣传手 册或产品目录等。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>8</strong>、资金证明：</td>
                <td bgcolor="#FFFFFF" class="padding15">证明您有能力无需工作即可支付在美停留整个期间的费用，例如能客观反映您每月收入的工资单、上有正常规律的存取记录的存折等。（注意：请不要出示银行存款证明单。存款证明单对签证申请没有帮助。）</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>9</strong>、如果您赴美进行商务活动：</td>
                <td bgcolor="#FFFFFF" class="padding15">请考虑携带下列信息前来面谈：您将访问哪里、会见哪些人员、会见过程中将讨论哪些内容以及您打算在美国购买哪些产品。下列文件可能会对申请有一定帮助：美国合作方出具的详细邀请信，说明申请人的访美目的；与美国合作方签订的合同或其它协议；将赴美检测或购买的机器、软件或其它设备的相关信息，如产品宣传手册或产品目录等。</td>
                </tr>
                </table>
				</div>
                </li>
                <li>
				<div class="tit">
				<?php echo get_visa_info_title($visa_product,'培训签证(B1/B2)');?>
                </div>
                <div class="visa_list_content">
				<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#dedede">
                <tr>
                <td width="167" bgcolor="#FFFFFF" class="paddingL"><strong>1</strong>、有效护照：</td>
                <td width="490" bgcolor="#FFFFFF" class="padding15">如果您的护照将在距您预计抵美日期的六个月内过期、或已损坏、或护照上已无空白的签证签发页, 请在前来签证之前先申请一本新护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>2</strong>、DS-160表格确认页：</td>
                <td bgcolor="#FFFFFF" class="padding15">请在上面注明您的中文姓名、您中文姓名的电报码、中文家庭地址、公司名字及地址。请将您的表格确认页竖着打印在A4纸上。面谈时请携带打印出来的DS-160表格确认页，不要使用传真的确认页。范例请点击这里。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>3</strong>、一张照片：<br /></td>
                <td bgcolor="#FFFFFF" class="padding15">于6个月内拍摄的2英寸x2英寸（51毫米x51毫米）正方形白色背景的彩色正面照。详情请见照片要求。请用透明胶带将您的照片贴在护照封面上。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>4</strong>、签证申请费收据原件：</td>
                <td bgcolor="#FFFFFF" class="padding15">您可以亲自或者委托我公司在中信银行在中国的任何分行支付签证申请费，并需要将收据用胶水或胶条粘贴在确认页的下半页上。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>5</strong>、护照：</td>
                <td bgcolor="#FFFFFF" class="padding15">含有以前赴美签证的护照，包括已失效的护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>6</strong>、能够说明您为何一定会返回中国的证据：</td>
                <td bgcolor="#FFFFFF" class="padding15">出示经济、社会、家庭或其它方面对您具有约束力的文件，以帮助您证明您在美国短暂停留后有意返回中国。由于个人情况的不同，申请人应出示的证据也各不相同。下列文件可以帮助签证官评估您是否有意返回中国：户口本、身份证、雇佣证明、能客观反映您每月收入的工资单、上有正常规律的存取记录的存折等。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>7</strong>、邀请信：</td>
                <td bgcolor="#FFFFFF" class="padding15">如果您受邀去美国参加业务培训，那么提供下列信息将对您的申请有所帮助：邀请人信息、访美目的、事先安排的旅行时刻表等等。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>8</strong>、资金证明：</td>
                <td bgcolor="#FFFFFF" class="padding15">证明您有能力无需工作即可支付在美停留整个期间的费用，例如能客观反映您每月收入的工资单、上有正常规律的存取记录的存折等。（注意：请不要出示银行存款证明单。存款证明单对签证申请没有帮助。）</td>
                </tr>
                </table>
				</div>
                </li>
                <li>
				<div class="tit">
				<?php echo get_visa_info_title($visa_product,'体检看病签证(B2)');?>
                </div>
                <div class="visa_list_content">
				<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#dedede">
                <tr>
                <td width="167" bgcolor="#FFFFFF" class="paddingL"><strong>1</strong>、有效护照：</td>
                <td width="490" bgcolor="#FFFFFF" class="padding15">如果您的护照将在距您预计抵美日期的六个月内过期、或已损坏、或护照上已无空白的签证签发页, 请在前来签证之前先申请一本新护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>2</strong>、DS-160表格确认页：</td>
                <td bgcolor="#FFFFFF" class="padding15">请在上面注明您的中文姓名、您中文姓名的电报码、中文家庭地址、公司名字及地址。请将您的表格确认页竖着打印在A4纸上。面谈时请携带打印出来的DS-160表格确认页，不要使用传真的确认页。范例请点击这里。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>3</strong>、一张照片：<br /></td>
                <td bgcolor="#FFFFFF" class="padding15">于6个月内拍摄的2英寸x2英寸（51毫米x51毫米）正方形白色背景的彩色正面照。详情请见照片要求。请用透明胶带将您的照片贴在护照封面上。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>4</strong>、签证申请费收据原件：</td>
                <td bgcolor="#FFFFFF" class="padding15">您可以亲自或者委托我公司在中信银行在中国的任何分行支付签证申请费，并需要将收据用胶水或胶条粘贴在确认页的下半页上。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>5</strong>、护照：</td>
                <td bgcolor="#FFFFFF" class="padding15">含有以前赴美签证的护照，包括已失效的护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>6</strong>、能够说明您为何一定会返回中国的证据：</td>
                <td bgcolor="#FFFFFF" class="padding15">出示经济、社会、家庭或其它方面对您具有约束力的文件，以帮助您证明您在美国短暂停留后有意返回中国。由于个人情况的不同，申请人应出示的证据也各不相同。下列文件可以帮助签证官评估您是否有意返回中国：户口本、身份证、雇佣证明、能客观反映您每月收入的工资单、上有正常规律的存取记录的存折等。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>7</strong>、资金证明：</td>
                <td bgcolor="#FFFFFF" class="padding15">证明您有能力无需工作即可支付在美停留整个期间的费用，例如能客观反映您每月收入的工资单、上有正常规律的存取记录的存折等。（注意：请不要出示银行存款证明单。存款证明单对签证申请没有帮助。）</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>8</strong>、备注：</td>
                <td bgcolor="#FFFFFF" class="padding15">您将访问哪里、会见哪些人员、会见过程中将讨论哪些内容以及您打算在美国购买哪些产品。下列文件可能会对申请有一定帮助：美国合作方出具的详细邀请函，说明申请人的访美目的；与美国合作方签订的合同或其它协议；将赴美检测或购买的机器、软件或其它设备的相关信息，如产品宣传手册或产品目录等。</td>
                </tr>
                </table>
				</div>
                </li>
                <li>
				<div class="tit">
				<?php echo get_visa_info_title($visa_product,'留学签证(F-1)');?>
                </div>
                <div class="visa_list_content">
				<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#dedede">
                <tr>
                <td width="167" bgcolor="#FFFFFF" class="paddingL"><strong>1</strong>、有效护照：</td>
                <td width="490" bgcolor="#FFFFFF" class="padding15">如果您的护照将在距您预计抵美日期的六个月内过期、或已损坏、或护照上已无空白的签证签发页, 请在前来签证之前先申请一本新护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>2</strong>、DS-160表格确认页：</td>
                <td bgcolor="#FFFFFF" class="padding15">请在上面注明您的中文姓名、您中文姓名的电报码、中文家庭地址、公司名字及地址。请将您的表格确认页竖着打印在A4纸上。面谈时请携带打印出来的DS-160表格确认页，不要使用传真的确认页。范例请点击这里。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>3</strong>、一张照片：<br /></td>
                <td bgcolor="#FFFFFF" class="padding15">于6个月内拍摄的2英寸x2英寸（51毫米x51毫米）正方形白色背景的彩色正面照。详情请见照片要求。请用透明胶带将您的照片贴在护照封面上。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>4</strong>、签证申请费收据原件：</td>
                <td bgcolor="#FFFFFF" class="padding15">您可以亲自或者委托我公司在中信银行在中国的任何分行支付签证申请费，并需要将收据用胶水或胶条粘贴在确认页的下半页上。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>5</strong>、护照：</td>
                <td bgcolor="#FFFFFF" class="padding15">含有以前赴美签证的护照，包括已失效的护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>6</strong>、填写完整的学生和交流访问学者信息系统(SEVIS)表格：</td>
                <td bgcolor="#FFFFFF" class="padding15">填写完整的I-20A-B表（发放给F1学生）或I-20M-N表（发放给M-1学生）必须由学校指定官员（DSO）和申请人本人签字。表格上的姓名必须与您护照上的姓名完全一致，并已被美国的学术机构输入SEVIS系统。请点击查看更多有关学生和交流访问学者信息系统(SEVIS)的信息。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>7</strong>、SEVIS费收据：</td>
                <td bgcolor="#FFFFFF" class="padding15">大多数J, F 和 M 类签证的申请人现在必须支付维护学生和交流访问学者信息系统（SEVIS）的费用。请在前来面谈时携带电子版收据或I-797收据原件。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>8</strong>、在中国有牢固约束力的证明：</td>
                <td bgcolor="#FFFFFF" class="padding15">出示经济、社会、家庭或其它方面约束力的文件，以帮助您证明您在美国短暂停留后有意愿返回中国。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>9</strong>、资金证明：</td>
                <td bgcolor="#FFFFFF" class="padding15">证明您有能力无需工作即可支付在美停留整个期间的费用。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>10</strong>、 研究/学习计划：</td>
                <td bgcolor="#FFFFFF" class="padding15">在美期间计划好的学习或研究工作的详细信息，包括您所在美国大学的导师和/或系主任的名字及电子邮件地址。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>11</strong>、个人简历：</td>
                <td bgcolor="#FFFFFF" class="padding15">详细描述您过去在学术和工作方面的经历，包括一份所发表文章的清单。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>12</strong>、返校老生的学校成绩单：</td>
                <td bgcolor="#FFFFFF" class="padding15">欲返回美国学校就读的老生应该在申请签证时递交所学课程的官方成绩单。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>13</strong>、导师的个人情况介绍：</td>
                <td bgcolor="#FFFFFF" class="padding15">已经在美国大学里被分配了导师的研究生应当带来导师的个人情况介绍、简历或网页打印件。</td>
                </tr>
                </table>
				</div>
                </li>
                <li>
				<div class="tit">
				<?php echo get_visa_info_title($visa_product,'游学签证(B2)');?>
                </div>
                <div class="visa_list_content">
				<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#dedede">
                <tr>
                <td width="167" bgcolor="#FFFFFF" class="paddingL"><strong>1</strong>、有效护照：</td>
                <td width="490" bgcolor="#FFFFFF" class="padding15">如果您的护照将在距您预计抵美日期的六个月内过期、或已损坏、或护照上已无空白的签证签发页, 请在前来签证之前先申请一本新护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>2</strong>、DS-160表格确认页：</td>
                <td bgcolor="#FFFFFF" class="padding15">请在上面注明您的中文姓名、您中文姓名的电报码、中文家庭地址、公司名字及地址。请将您的表格确认页竖着打印在A4纸上。面谈时请携带打印出来的DS-160表格确认页，不要使用传真的确认页。范例请点击这里。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>3</strong>、一张照片：<br /></td>
                <td bgcolor="#FFFFFF" class="padding15">于6个月内拍摄的2英寸x2英寸（51毫米x51毫米）正方形白色背景的彩色正面照。详情请见照片要求。请用透明胶带将您的照片贴在护照封面上。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>4</strong>、签证申请费收据原件：</td>
                <td bgcolor="#FFFFFF" class="padding15">您可以亲自或者委托我公司在中信银行在中国的任何分行支付签证申请费，并需要将收据用胶水或胶条粘贴在确认页的下半页上。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>5</strong>、护照：</td>
                <td bgcolor="#FFFFFF" class="padding15">含有以前赴美签证的护照，包括已失效的护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>6</strong>、填写完整的学生和交流访问学者信息系统(SEVIS)表格：</td>
                <td bgcolor="#FFFFFF" class="padding15">填写完整的I-20A-B表（发放给F1学生）或I-20M-N表（发放给M-1学生）必须由学校指定官员（DSO）和申请人本人签字。表格上的姓名必须与您护照上的姓名完全一致，并已被美国的学术机构输入SEVIS系统。请点击查看更多有关学生和交流访问学者信息系统(SEVIS)的信息。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>7</strong>、SEVIS费收据：</td>
                <td bgcolor="#FFFFFF" class="padding15">大多数J, F 和 M 类签证的申请人现在必须支付维护学生和交流访问学者信息系统（SEVIS）的费用。请在前来面谈时携带电子版收据或I-797收据原件。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>8</strong>、在中国有牢固约束力的证明：</td>
                <td bgcolor="#FFFFFF" class="padding15">出示经济、社会、家庭或其它方面约束力的文件，以帮助您证明您在美国短暂停留后有意愿返回中国。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>9</strong>、资金证明：</td>
                <td bgcolor="#FFFFFF" class="padding15">证明您有能力无需工作即可支付在美停留整个期间的费用。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>10</strong>、 研究/学习计划：</td>
                <td bgcolor="#FFFFFF" class="padding15">在美期间计划好的学习或研究工作的详细信息，包括您所在美国大学的导师和/或系主任的名字及电子邮件地址。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>11</strong>、个人简历：</td>
                <td bgcolor="#FFFFFF" class="padding15">详细描述您过去在学术和工作方面的经历，包括一份所发表文章的清单。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>12</strong>、返校老生的学校成绩单：</td>
                <td bgcolor="#FFFFFF" class="padding15">欲返回美国学校就读的老生应该在申请签证时递交所学课程的官方成绩单。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>13</strong>、导师的个人情况介绍：</td>
                <td bgcolor="#FFFFFF" class="padding15">已经在美国大学里被分配了导师的研究生应当带来导师的个人情况介绍、简历或网页打印件。</td>
                </tr>
                </table>
				</div>
                </li>
                <li>
				<div class="tit">  
				<?php echo get_visa_info_title($visa_product,'访问学者签证(J-1)');?>
                </div>
                <div class="visa_list_content">
				<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#dedede">
                <tr>
                <td width="167" bgcolor="#FFFFFF" class="paddingL"><strong>1</strong>、有效护照：</td>
                <td width="490" bgcolor="#FFFFFF" class="padding15">如果您的护照将在距您预计抵美日期的六个月内过期、或已损坏、或护照上已无空白的签证签发页, 请在前来签证之前先申请一本新护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>2</strong>、DS-160表格确认页：</td>
                <td bgcolor="#FFFFFF" class="padding15">请在上面注明您的中文姓名、您中文姓名的电报码、中文家庭地址、公司名字及地址。请将您的表格确认页竖着打印在A4纸上。面谈时请携带打印出来的DS-160表格确认页，不要使用传真的确认页。范例请点击这里。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>3</strong>、一张照片：<br /></td>
                <td bgcolor="#FFFFFF" class="padding15">于6个月内拍摄的2英寸x2英寸（51毫米x51毫米）正方形白色背景的彩色正面照。详情请见照片要求。请用透明胶带将您的照片贴在护照封面上。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>4</strong>、签证申请费收据原件：</td>
                <td bgcolor="#FFFFFF" class="padding15">您可以亲自或者委托我公司在中信银行在中国的任何分行支付签证申请费，并需要将收据用胶水或胶条粘贴在确认页的下半页上。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>5</strong>、填写完整的DS-2019表：</td>
                <td bgcolor="#FFFFFF" class="padding15">表格上的姓名必须与您护照上的姓名完全一致，并已被美国的学术机构输入学生和交流访问学者信息系统（SEVIS）。请点击查看更多有关学生和交流访问学者信息系统(SEVIS)的信息。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>6</strong>、填写完整的学生和交流访问学者信息系统(SEVIS)表格：</td>
                <td bgcolor="#FFFFFF" class="padding15">填写完整的I-20A-B表（发放给F1学生）或I-20M-N表（发放给M-1学生）必须由学校指定官员（DSO）和申请人本人签字。表格上的姓名必须与您护照上的姓名完全一致，并已被美国的学术机构输入SEVIS系统。请点击查看更多有关学生和交流访问学者信息系统(SEVIS)的信息。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>7</strong>、SEVIS费收据：</td>
                <td bgcolor="#FFFFFF" class="padding15">大多数J, F 和 M 类签证的申请人现在必须支付维护学生和交流访问学者信息系统（SEVIS）的费用。请在前来面谈时携带电子版收据或I-797收据原件。请点击查看如何支付SEVIS费</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>8</strong>、在中国有牢固约束力的证明：</td>
                <td bgcolor="#FFFFFF" class="padding15">出示经济、社会、家庭或其它方面约束力的文件，以帮助您证明您在美国短暂停留后有意愿返回中国。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>9</strong>、资金证明：</td>
                <td bgcolor="#FFFFFF" class="padding15">证明您有能力无需工作即可支付在美停留整个期间的费用</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>10</strong>、研究/学习计划：</td>
                <td bgcolor="#FFFFFF" class="padding15">在美期间计划好的学习或研究工作的详细信息，包括您所在美国大学的导师和/或系主任的名字及电子邮件地址。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>11</strong>、个人简历：</td>
                <td bgcolor="#FFFFFF" class="padding15">详细描述您过去在学术和工作方面的经历，包括一份所发表文章的清单。</td>
                </tr>
                
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>12</strong>、导师的个人情况介绍：</td>
                <td bgcolor="#FFFFFF" class="padding15">已经在美国大学里被分配了导师的研究生应当带来导师的个人情况介绍、简历或网页打印件。</td>
                </tr>
                </table>
				</div>
                </li>
                <li>
				<div class="tit">
				<?php echo get_visa_info_title($visa_product,'实习签证(J-1)');?>
                </div>
                <div class="visa_list_content">
				<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#dedede">
                <tr>
                <td width="167" bgcolor="#FFFFFF" class="paddingL"><strong>1</strong>、有效护照：</td>
                <td width="490" bgcolor="#FFFFFF" class="padding15">如果您的护照将在距您预计抵美日期的六个月内过期、或已损坏、或护照上已无空白的签证签发页, 请在前来签证之前先申请一本新护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>2</strong>、DS-160表格确认页：</td>
                <td bgcolor="#FFFFFF" class="padding15">请在上面注明您的中文姓名、您中文姓名的电报码、中文家庭地址、公司名字及地址。请将您的表格确认页竖着打印在A4纸上。面谈时请携带打印出来的DS-160表格确认页，不要使用传真的确认页。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>3</strong>、一张照片：<br /></td>
                <td bgcolor="#FFFFFF" class="padding15">于6个月内拍摄的2英寸x2英寸（51毫米x51毫米）正方形白色背景的彩色正面照。详情请见照片要求。请用透明胶带将您的照片贴在护照封面上。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>4</strong>、签证申请费收据原件：</td>
                <td bgcolor="#FFFFFF" class="padding15">您可以亲自或者委托我公司在中信银行在中国的任何分行支付签证申请费，并需要将收据用胶水或胶条粘贴在确认页的下半页上。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>5</strong>、护照：</td>
                <td bgcolor="#FFFFFF" class="padding15">含有以前赴美签证的护照，包括已失效的护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>6</strong>、工作/研究计划：</td>
                <td bgcolor="#FFFFFF" class="padding15">有关您在美期间将要从事的工作或研究的详细描述。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>7</strong>、个人简历：</td>
                <td bgcolor="#FFFFFF" class="padding15">详细描述您过去在学术和工作方面的经历，包括一份所发表文章的清单。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>8</strong>、必备表格：</td>
                <td bgcolor="#FFFFFF" class="padding15">Blanket L-1申请人需要提供I-129S （跨国公司工作许可批准书）的原件和两份复印件以及 I-797表格复印件。</td>
                </tr>
                </table>
				</div>
                </li>
                <li>
				<div class="tit">
				<?php echo get_visa_info_title($visa_product,'工作签证(H-1)');?>
                </div>
                <div class="visa_list_content">
				<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#dedede">
                <tr>
                <td width="167" bgcolor="#FFFFFF" class="paddingL"><strong>1</strong>、有效护照：</td>
                <td width="490" bgcolor="#FFFFFF" class="padding15">如果您的护照将在距您预计抵美日期的六个月内过期、或已损坏、或护照上已无空白的签证签发页, 请在前来签证之前先申请一本新护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>2</strong>、DS-160表格确认页：</td>
                <td bgcolor="#FFFFFF" class="padding15">请在上面注明您的中文姓名、您中文姓名的电报码、中文家庭地址、公司名字及地址。请将您的表格确认页竖着打印在A4纸上。面谈时请携带打印出来的DS-160表格确认页，不要使用传真的确认页。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>3</strong>、一张照片：<br /></td>
                <td bgcolor="#FFFFFF" class="padding15">于6个月内拍摄的2英寸x2英寸（51毫米x51毫米）正方形白色背景的彩色正面照。详情请见照片要求。请用透明胶带将您的照片贴在护照封面上。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>4</strong>、签证申请费收据原件：</td>
                <td bgcolor="#FFFFFF" class="padding15">您可以亲自或者委托我公司在中信银行在中国的任何分行支付签证申请费，并需要将收据用胶水或胶条粘贴在确认页的下半页上。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>5</strong>、护照：</td>
                <td bgcolor="#FFFFFF" class="padding15">含有以前赴美签证的护照，包括已失效的护照。 </td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>6</strong>、工作/研究计划：</td>
                <td bgcolor="#FFFFFF" class="padding15">有关您在美期间将要从事的工作或研究的详细描述。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>7</strong>、个人简历：</td>
                <td bgcolor="#FFFFFF" class="padding15">详细描述您过去在学术和工作方面的经历，包括一份所发表文章的清单。</td>
                </tr>
                <tr>
                <td bgcolor="#FFFFFF" class="paddingL"><strong>8</strong>、必备表格：</td>
                <td bgcolor="#FFFFFF" class="padding15">Blanket L-1申请人需要提供I-129S （跨国公司工作许可批准书）的原件和两份复印件以及 I-797表格复印件。</td>
                </tr>
                </table>
				</div>
                </li>
                </ul>
            </div>
        </div>
        <div class="embassy"><!--使馆分区-->
        	<h3>使馆分区</h3>
            <ul>
            	<li style="height:60px;"><strong>北京使馆：</strong>适用于北京市，天津市，甘肃省，河北省，河南省，湖北省，湖南省，内蒙古自治区，江西省，宁夏回族自治区，山东省，山西省，陕西省，青海省和新疆维吾尔自治区 护照持有人</a>
</li>
                <li><strong>广州使馆：</strong>受理福建省，广东省，广西壮族自治区和海南省 护照持有人 </li>
                <li><strong>上海使馆：</strong>只受理上海市，安徽省，江苏省和浙江省 护照持有人</li>
				<li><strong>沈阳领事馆：</strong>黑龙江省，吉林省和辽宁省</li>
				<li><strong>成都领事馆：</strong>四川，重庆，云南，贵州，西藏</li>
				<li><strong>香港领事馆：</strong>香港和澳门地区</li>				
            </ul>
			<p>
			中国公民应该到管辖其居住地的美国大使馆或领事馆递交申请，不论其居住地是否与户口一致；只有到适当的使领馆申请，得到签证的机率才会最大。在暂住地不间断居住半年以上人员，可在暂住地领馆申请，但需提供相关证明。
			</p>
        </div>
        <div class="faq_visa"><!--签证常见问题-->
        	<div class="tit">
            	<ul id="faq_visa_tit">
            	<li class="cur">注意事项</li>
                <li>温馨提示</li>
                <li>常见问答</li>
                </ul>
            </div>
            <div  id="faq_visa_cont" class="cont">
            	<div>
            		<ul>
                    	<li><strong>1</strong>、您所申请的签证是否可以成功，取决于美国使领馆签证官的直接审核结果；若最终发生拒签状况，申请人应自然接受此结果</li>
                    	<li><strong>2</strong>、" 预计工作日"为美国使馆签发签证时，正常情况下的处理时间；若 遇特殊原因如假期、使馆内部人员调整、签证打印机故障等，则有可能会产生延迟出签的情况；对申请人根据签证预计日期提示，而进行的后 续旅程安排所造成的可能经济损失，本公司不承担任何责任。</li>
                    	<li><strong>3</strong>、有关签证资料上公布的签证有效期和停留天数，仅做参考而非任何法定承诺，一切均以美国签证官签发的签证内容为唯一依据。</li>
                    	<li><strong>4</strong>、签证申请者应在签证批准后再购买机票。凡因提前购买机票而签证未被批准所造成的经济损失，我社对此不负责任。</li>
                    </ul>
                </div>
                <div style="display:none;">
            		<ul>
                    	<li><strong>1</strong>、我司不受理办理护照的服务，办理护照可业务请直接咨询当地的安局出入境管理处。</li>
                        <li><strong>2</strong>、如您持外籍护照前往中国境外，请确保您所有持有的是有再次进入中国大陆的有效签证。</li> 
                        <li><strong>3</strong>. 签证未有结果请不要出机票，否则一切损失将由申请者自行承担。 </li> 
                        <li><strong>4</strong>. 所有复印件请统一用A4纸单面打印。</li> 
                        <li><strong>5</strong>、常住地一般是一个人实际工作和生活的地方。在该地逗留超过6个月或未来打算长期逗留该地的申请人，即被认为该地的常住居民。如果常住地与户口所在地或护照签发地不相符，申请人应提交可证明其为常住居民的材料（有效暂住证，劳务合同，公安局证明等）。</li> 
                       
                    </ul>
                </div>
                <div style="display:none;">
				<dl>
					<dt>1.面谈时那些东西是禁止携带的？</dt>
					<dd>
手提包、公文包、手机、照相机和其它电子产品严禁携带进领事馆。没有签证预约的家人、朋友也不得入内。需要公民服务的美国公民可前往美国公民服务处，但美国不希望陪同家属来面谈的美国公民在此等待。 </dd>
				</dl>
				<dl>
					<dt>2.应于何时到达？</dt>
					<dd>请不要早于预约时间前1小时到达。鉴于等候室空间有限、申请人数量众多，您可能需要等候1小时以上，有时甚至在户外着装要得体。</dd>
				</dl>
				<dl>
					<dt>3.需要被采集指纹吗？</dt>
					<dd>根据美国法律所有申请人都须被采集十指指纹。此过程迅速、无痛感、不沾染墨水。如果您的指尖有破损，您须在痊愈后返回领馆完成您的面谈。如您拒绝被采集指纹，您将无法获得美国签证。</dd>
				</dl>
				<dl>
					<dt>4.面谈时需要说英语吗？</dt>
					<dd>尽管大多数美国人都说英语，但签证官员同时也通晓普通话。需要提醒的是某些特定的签证类型需要签证考核您的英语能力，在此情况下如您拒绝使用英语面谈，您可能不符合这些签证的要求。</dd>
				</dl>
				<dl>
					<dt>5.签证官是否会翻看所有的材料？</dt>
					<dd>鉴于在赴美签证的巨大需要量，签证官必须快速、高效地工作。做足准备回答各类问题是非常重要的。您可以携带任何支持您签证申请的材料，但也请谅解签证官没有充分的时间翻看所有材料。尽管如此，但这并不代表您不需要携带所有相应的材料。</dd>
				</dl>
				<dl>
					<dt>6.什么是探亲旅游签证？</dt>
					<dd>   美国旅游签证（B－2签证）：B－2签证颁发给赴美国旅游的申请人，包括：1.探亲访友；2.旅游观光；3.就医；4.处理突发事件?亲属伤亡、财产纠纷等；5.参加儿女毕业典礼或婚礼；6.参加各种性质的社团活动；7.参加各种业余性的音乐会和运动会等。</dd>
				</dl>
				<dl>
					<dt>7.什么是签证的有效期？</dt>
					<dd>
签证有效期是签证的一项十分重要的内容。世界上所有主权国家签发的签证基本上都标 明有效期。也就是说， 没有有效期的签证是不存在的。签证有效期，就是说签证在某一段时间内有效，超过了 这段时间，签证也就 无效了。所以，对于申请某国（地区）签证的申请人来讲，必须在获得签证后，牢记住 该签证的有效期，并 在该有效期内抵达目的地。</dd>
				</dl>
				<dl>
					<dt>8.什么是签证的停留期？</dt>
					<dd>签证的停留期，是指准许签证获得者在前往国家（地区）停留的期限。其在签证页上的 表达方式有： <br/>
（1）每次停留XX天英文为“DURATION OF EACH STAY……DAYS”或“DURATION OF STAY ……DAYS”。 <br/>
（2）停留期与有效期是一致的英文为“FROM……TO……”。 <br/>
（3）准许停留XX天英文为“WITH A STAY OF …… DAYS ”。“……” 处填写阿拉伯 数字。 </dd>
				</dl>
				<dl>
					<dt>9. 常见的拒签理由有哪些？</dt>
					<dd>签证结果无非两种：获签或拒签。拒签后一般会在护照上加盖使馆的印章，俗称拒签章 ，以表明此本护照使馆受理后没有发给签证。使馆拒签的原因多种多样。但大致不外乎下列几种：<br/> 
（1）材料不齐备。使馆为了尽量减少人为因素对签证的影响，明文规定了对申请材料 的要求。如果申请材料不齐，所造成的影响是：要求补材料或拒签。<br/>
（2）材料不真实。使馆在审查时，要确认所提供的材料真实与否。如果认为材料不真 实，百分之百会被拒签。 <br/>
（3）出国目的不明确，出国后可能会从事与所申请签证内容不一致的活动，比如持短 期商务签证去探亲，到境外后滞留等。签证官作出这样的判断，可能是根据以下原因：申请人在国内 的职位和收入太低，会被国外的工作机会所吸引；申请人太年轻，无牵无挂，滞留境外的可能性较大 ；申请人家庭经济状况不好，境外的生活条件会对他有强大的吸引力等等。 </dd>
				</dl>
				<dl>
					<dt>10. 在国外逗留期间签证过期了，该怎么办？</dt>
					<dd>
申请人在正常情况下，应该在签证有效期和停留期之内到达和离开前往国。否则，则属 于非法居留。严重者，有可能被罚款、拘留或驱逐出境。申请人如确因特殊原因，要求延长停留期限 ，应在停留期之内提前向前往国的移民机关提出延期申请。如果理由充分，则有可能获准延期。如果 申请人未在停留期之内提出延期申请，且签证有效期和停留期均己过期， 而本人又未离开该国，则必 须持相应的有效证明材料，申请办理延期手续 。</dd>
				</dl>
				<dl>
					<dt>11.什么是留学签证？</dt>
					<dd>美国移民法为赴美留学人员提供了两种非移民签证类别。"F" 签证发放给赴美进行学术学习的留学生，而"M" 签证则发放给进行非学术或职业学习的留学生。要获得学生签证，申请人必须首先申请一所美国学校并被其接收。当您被接收后，学校会为您签发一份I-20或 I-20M表格。<br/>
      申请赴美留学签证的申请人必须首先证明自己有能力在美国做全日制学生。申请人还必须证明其进入美国并短暂停留的唯一目的是在已建立的学术机构完成学业。另外，申请人还必须证明自己有能力支付学费和上学期间所需的生活费用。</dd>
	  			</dl>
				<dl>
					<dt>12.什么时候需要续签？</dt>
					<dd>如果您计划进入美国的时间早于您签证上的到期日，则不需要申请新的签证。即使您预期停留在美国的时间超过您签证上的到期日，也不需要新签证。例如，您计划在2005年10月5日进入美国而您的签证到期日为2005年10月11日，则您不需要新的签证。因为您在到期日之间到达美国，您的签证依然有效。</dd>
				</dl>
				<dl>
					<dt>13.什么是独家中文在线填写系统？</dt>
					<dd>全国独家首创中文在线DS表格填写系统，直接对接美国大使馆官网，可快速独立完成签证表格填写，直接打印DS确认页，面签时可携带！</dd>
				</dl>
				<dl>
					<dt>14.你们的邀请函对签证有什么帮助？</dt>
					<dd>我们是一家在美国注册具备开邀请函资格的公司，我们拥有合法的美国加州旅行社特许执照(注册号Registration #: 2110393-40可在美国加州检查院官方网站进行核实),并拥有合法的美国旅行社营业执照，一直被美国联邦商业监督局(better business bureau)权威鉴定认证A+级最高商誉信用评级，我们可以为您开具美国大使馆认可的邀请函，大大提高您的过签率。</dd>
				</dl>
				<dl>
					<dt>15.签证服务流程具体是怎样的？</dt>
					<dd>凭借多年的行业经验，我们的签证团队会密切贴近客户的行业特点、商业背景等为您量身定制专业、快捷的签证方案。从为客户填写或审核DS表格、代买签证费、预约面谈时间、面谈培训、面谈陪同、签证出签配送等等都是贴心高效的服务流程，让客户安心快捷的获得签证。</dd>
				</dl>
				<dl>
					<dt>16.拒签了退费吗？</dt>
					<dd>我们拥有完美的签证通过率，但是不排除个别情况特殊签证难签的客户，为了排除这样的后顾之忧，针对在我公司定制行程并首次办理签证的客户（已有拒签记录的客户除外），我们承诺可以免费为您再次办理签证业务，以便您获得您的签证，但是首次产生的签证费用不予退还。</dd>
				</dl>
				
                </div>
            </div>
        </div>
    </div>
    <div id="right_wrap">
    	<div class="visa100"><!--安心签证100%-->
       		<img src="/image/visa/<?php if(CHARSET=='big5'){ echo 'visa_100_big5.jpg';}else{echo 'visa_100.jpg';} ?>" alt="安心签证100%" />
        </div>
        <div class="module prime"><!--独家中文在线填表-->
        	<div class="tit_visa">独家中文在线填表DS-160 </div>
            <div class="cont">
           	  <p class="aerobus">7X24小时客服在线帮助</p>
                <dl>
                	<dt><img src="/image/visa/prime_dot1.gif" alt="签证申请"/></dt>
                    <dd>独家直接对接美国大使馆系统快速<br />完成签证申请，无需等待</dd>
                </dl>
                <dl>
                	<dt><img src="/image/visa/prime_dot2.gif" alt="方便快捷的办理流程"/></dt>
                    <dd>方便快捷的办理流程<br />在线操作在线支付简便迅捷</dd>
                </dl>
                <dl>
                	<dt><img src="/image/visa/prime_dot3.gif" alt="专业服务100%"/></dt>
                    <dd>专业服务100%<br />专业团队保障您的出签率</dd>
                </dl>
                <dl style="border-bottom:none;">
                	<dt><img src="/image/visa/prime_dot4.gif"  alt="隐私保密100%"/></dt>
                    <dd>隐私保密100%<br />走四方品牌保证您的资料隐私</dd>
                </dl>
                
            </div>
        </div>
        <div class="module contact_visa"><!--联系我们-->
        	<div class="tit_visa">联系我们</div>
            <div class="cont">
            	<dl>
                	<dt class="s_1">总部:美国</dt>
                    <dd>
                    	<ul>
                        	<li class="s1">国际总机(60热线)： 1-626-898-7800</li>
                        	<?php /*<li class="s1">Tel：225-754-4326(热线)</li>*/?>
                        	<li class="s1">美加免费：1-888-887-2816</li>
                        	<li class="s2">Email：service@usitrip.com</li>
                        </ul>
                    </dd>
                </dl>
                <dl>
                	<dt class="s_2">中国免费客服电话</dt>
                    <dd>
                    	<ul>
                        	<li>4006-333-926</li>
                        	<li>总机(100热线)：86-0755-2305-4633</li>
                        </ul>
                    </dd>
                </dl>
                <?php /*<dl style="border-bottom:none;">
                	<dt class="s_3">台湾免费客服电话</dt>
                    <dd>
                    	<ul>
                        	<li>(02)4050-2999 转 8991271816</li>
                        </ul>
                    </dd>*/ ?>
                </dl>
            </div>
        </div>
    </div>
</div>
<?php
echo db_to_html(ob_get_clean());
?>
   
  
	
