<?php ob_start();?>
	<div id="abouts">
    	<?php
		require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/faq_left.php');
		?>
        <div class="abouts_right" id="right">
        	<div class="aboutsTit">
            	<ul>
                	<li>支付方式</li>
                </ul>
            </div>
            <div class="aboutsCont ">
            	<div class="help5">
               	  <div class="dot">
                   	<h3>美元支付</h3>
                      <ul class="paymentLinks">
					  <?php
						for ($i=0, $n=sizeof($_all_payments['USD']); $i<$n; $i++) {							
						?>
						  <li style=" <?= $_all_payments['USD'][$i]['width'];?>">
                              <p><a href="<?php echo tep_href_link('payment.php')?>#<?= $_all_payments['USD'][$i]['id']?>"><img alt="<?= $_all_payments['USD'][$i]['name']?>" src="/image/nav/<?= $_all_payments['USD'][$i]['id']?>.gif"></a></p>
                              <span><a href="<?php echo tep_href_link('payment.php')?>#<?= $_all_payments['USD'][$i]['id']?>"><?= $_all_payments['USD'][$i]['name']?></a></span>
                       	  </li>
						  <?php }?>
						  
                          <li>
                              <p><a href="<?php echo tep_href_link('payment.php')?>#western_union"><img alt="西联汇款" src="/image/nav/western_union.gif"></a></p>
                              <span><a href="<?php echo tep_href_link('payment.php')?>#western_union">西联汇款</a></span>
                       	  </li>
                          
                      </ul>
                    </div>
                  <div class="dot">
                   	<h3>人民币支付</h3>
                      <ul class="paymentLinks">
                       	<?php
						for ($i=0, $n=sizeof($_all_payments['CNY']); $i<$n; $i++) {	
						?>
						  <li>
                              <p><a href="<?php echo tep_href_link('payment.php')?>#<?= $_all_payments['CNY'][$i]['id']?>"><img alt="<?= $_all_payments['CNY'][$i]['name']?>" src="/image/nav/<?= $_all_payments['CNY'][$i]['id']?>.gif"></a></p>
                              <span><a href="<?php echo tep_href_link('payment.php')?>#<?= $_all_payments['CNY'][$i]['id']?>"><?= $_all_payments['CNY'][$i]['name']?></a></span>
                       	  </li>
						  <?php }?>
                          <li>
                              <p><a href="<?php echo tep_href_link('payment.php')?>#face_to_face"><img alt="上门付款" src="/image/nav/face_to_face.gif"></a></p>
                              <span><a href="<?php echo tep_href_link('payment.php')?>#face_to_face">上门付款</a></span>
                       	  </li>
                      </ul>
                    </div>
                   
				   <?php if(in_array('paypal_nvp_samples', $_all_payments_ids)){?>
				   <div class="content_h">
                   		<a name="paypal_nvp_samples" style="height:0;font-size:0;"></a>
                   		<h4 class="font_bold font_size14 color_blue">信用卡支付</h4>
                        <p>我们接受Visa、MasterCard、American Express和Discover。同时我们接收美国银行Debit Card（美国银行卡）。信用卡在线支付是通过安全又便捷的Paypal系统实现的，无需注册成为Paypal的会员，即可使用该支付方式。实时到账，并无任何手续费。另外，本网站已<a class="color_orange underline" target="_blank" href="https://seal.godaddy.com/verifySeal?sealID=Sw7SK8bpKlM5UcG9KesPbuhOlyKbqTQ85J99lyGiBVVfZRxR9Qcu">安装SSL证书</a>，您在本网站提交的支付信息都通过256位加密后传输。<br />
中国的Visa 或 Matercard 需符合以下两个条件：<br />
1.美金信用卡<br />
2.已开通美国网上购买业务</p>
                   </div>
				   <?php }?>
				   
                   <?php if(in_array('paypal', $_all_payments_ids)){?>
				   <div class="content_h ">
                   		<a name="paypal" style="height:0;font-size:0;"></a>
                   		<h4 class="font_bold font_size14 color_blue">PAYPAL支付 </h4>
                        <p>如果您已经是Paypal的用户，您可以直接使用您的帐户进行网上支付。实时到账，无任何手续费。</p>
                   </div>
				   <?php }?>
				   
                    <?php if(in_array('moneyorder', $_all_payments_ids)){?>
					<div class="content_h">
                    	<a name="moneyorder" style="height:0;font-size:0;"></a>
                   		<h4 class="font_bold font_size14 color_blue">支票支付 </h4>
                        <p class="paddingB">接收个人支票 （Personal Check），公司支票 （Business Check），现金支票（Money Order）、旅行支票 （Travel Check）和银行支票 （Bank Check）。</p>
						<p class="paddingB">请将支票付给：<b><?= MODULE_PAYMENT_MONEYORDER_PAYTO?></b>
                        <p class="paddingB">(A).  如果是个人支票或公司支票您无须邮寄支票，只需填写<a class="color_orange underline" href="<?= tep_href_link('CheckDraftAuthorizationForm.pdf');?>">支票支付授权书</a> ,传真或扫描后EMAIL给我们的邮箱service@usitrip.com 即可。
						<a class="color_orange underline" href="<?= tep_href_link('CheckDraftAuthorizationForm.pdf');?>">Download 支票支付授权书</a>
						
						</p>
                        <p>(B).  如果是现金支票（Money Order）、旅行支票 （Travel Check）或银行支票 （Bank Check），传真或扫描后EMAIL给我们的邮箱service@usitrip.com ，并请将支票原件通过快递邮寄给我们公司：Unitedstars International Ltd.，133B W Garvey Ave, Monterey Park, CA, USA 91754</p>
                   </div>
				   <?php } ?>
				   
				   <?php if(in_array('cashdeposit', $_all_payments_ids)){?>
                    <div class="content_h">
                    	<a name="cashdeposit" style="height:0;font-size:0;"></a>
                   		<h4 class="font_bold font_size14 color_blue">银行转账/现金存款</h4>
                        <p class="paddingB">快速（实时到账），方便（可以网上操作或去银行办理），免费。
可以通过转账（Account Transfer），或直接存款（Direct Deposit）去实现。</p>
                        <p class="paddingB">
						<?php if(0){ //取消账号显示?>
						如果你有在<?= MODULE_PAYMENT_CASEDEPOSIT_BANKNAM?>银行开户，可以直接转账或直接存现金到我们公司<?= MODULE_PAYMENT_CASEDEPOSIT_BANKNAM?>银行账户：
						
						<br />
						Account Name: <b><?= MODULE_PAYMENT_CASEDEPOSIT_ACCNAM?></b>
						<br />
						Account #: <b><?= MODULE_PAYMENT_CASEDEPOSIT_ACCNUM?></b>
						<br />
						Routing Number： <b><?= MODULE_PAYMENT_CASEDEPOSIT_ROUNUM?></b>
						</p>
                        
						<p class="paddingB">如果你有在<?= MODULE_PAYMENT_CASEDEPOSIT_BANKNAM_1?>开户，可以直接转账或直接存现金到我们<?= MODULE_PAYMENT_CASEDEPOSIT_BANKNAM_1?>银行账户：<br />
						Account Name: <b><?= MODULE_PAYMENT_CASEDEPOSIT_ACCNAM_1?></b><br />
						Account：<b><?= MODULE_PAYMENT_CASEDEPOSIT_ACCNUM_1?></b> <br />
						Routing Number:  <b><?= MODULE_PAYMENT_CASEDEPOSIT_ROUNUM_1?></b></p>
						
						<p>如果你有在Wells Fargo开户，可以直接转账或直接存现金到我们Wells Fargo银行账户：<br />
						Account Name: <b>Unitedstars International Ltd.</b><br />
						Account：<b>7296761336</b> <br />
						Routing Number:  <b>121000248</b>
						<?php }else{?>
						如果你有在<?= MODULE_PAYMENT_CASEDEPOSIT_BANKNAM.'、'.MODULE_PAYMENT_CASEDEPOSIT_BANKNAM_1?>或Wells Fargo银行开户，可以直接转账或直接存现金到我们公司银行账户，公司名称：<b>Unitedstars International Ltd.</b>
						具体银行账户信息付款时请向本公司索取，谢谢！
						<?php
						}
						?>
						</p>
						
                   </div>
                   <?php }?>
                   
                  <div class="content_h">
                  	<?php if(in_array('banktransfer', $_all_payments_ids)){?>
						<a name="banktransfer" style="height:0;font-size:0;"></a>
                   		<h4 class="font_bold font_size14 color_blue">银行电汇</h4>
                        <p class="paddingB">您可以通过银行汇款的方式进行支付或者将款项直接汇入我们的帐户，汇款时注明您的订单号，以便我们的财务部门核对。银行相关手续费由您自行承担。</p>
                        <p class="paddingB"><strong>A--美国境内帐户：</strong> <br />
						Beneficiary Name （户名）：<b><?= MODULE_PAYMENT_BANKTRANSFER_ACCNAM?></b><br />
						<?php if(0){ //取消账号显示?>
						Beneficiary Account Number （帐号）：<b><?= MODULE_PAYMENT_BANKTRANSFER_ACCNUM?></b><br />
						ank Swift Code （银行代码）: <b><?= MODULE_PAYMENT_BANKTRANSFER_SORTCODE?></b><br />
						Bank ABA Number: <b><?= MODULE_PAYMENT_BANKTRANSFER_ROUNUM?></b><br />
						Receiving  Bank Name （银行名字）：<b><?= MODULE_PAYMENT_BANKTRANSFER_BANKNAM?></b><br />
						Receiving Bank Address: （银行地址）：<b><?= MODULE_PAYMENT_BANKTRANSFER_ADDRESS?></b><br />
						<?php }else{?>
						具体银行账户信息付款时请向本公司索取，谢谢！
						<?php }?>
						</p>
                    <?php }?>
					
					<?php if(in_array('transfer', $_all_payments_ids)){?>
					<a name="transfer" style="height:0;font-size:0;"></a>
                    <p class="paddingB"><strong>B--中国境内帐户：</strong><br />中国银行转账     （可以采用您的网上银行等直接转账 或 去银行办理支付时，请记得带上本人身份证原件）
</p>
                    <table cellspacing="1" cellpadding="0" border="0" bgcolor="#f1f1f1" style="font-family:'宋体'">
                      <tbody><tr>
                        <td align="center" width="55" bgcolor="#3b70c7" class="color_fff">所在地</td>
                        <td align="center" width="125" height="22" bgcolor="#3b70c7" class="color_fff">银行</td>
                        <td align="center" width="198" bgcolor="#3b70c7" class="color_fff">开户行</td>
                        <td align="center" width="154" bgcolor="#3b70c7" class="color_fff">银行账号</td>
                        <td align="center" width="54" bgcolor="#3b70c7" class="color_fff">收款人</td>
                      </tr>
                      <?php
					  for($j=0, $n = sizeof($_transfers); $j<$n; $j++){
					  ?>
					  <tr>
                        <td align="center" height="42" bgcolor="#ffffff"><?= $_transfers[$j]['city'];?></td>
                        <td align="center" bgcolor="#ffffff"><a target="_blank" href="<?= $_transfers[$j]['href'];?>"><img border="0" src="/image/nav/<?= $_transfers[$j]['img'];?>" alt="<?= $_transfers[$j]['bank'];?>"></a></td>
                        <td align="left" bgcolor="#ffffff">&nbsp;<?= $_transfers[$j]['bank'];?></td>
                        <td align="left" bgcolor="#ffffff">&nbsp;<?= $_transfers[$j]['account'];?></td>
                        <td align="center" bgcolor="#ffffff">&nbsp;<?= $_transfers[$j]['payto'];?></td>
                      </tr>
					  <?php }?>
                    </tbody></table>
					
					<p class="paddingB"><strong>C--中国境内对公帐户：</strong><br />
						请使用以下方式汇款
</p>
					<table cellspacing="1" cellpadding="0" border="0" bgcolor="#f1f1f1" style="font-family:'宋体'" width="100%">
					<tbody>
					<tbody><tr>
                        <td align="center" bgcolor="#3b70c7" class="color_fff">公司名称</td>
						<td height="22" align="center" bgcolor="#3b70c7" class="color_fff">开户行</td>
                        <td align="center" bgcolor="#3b70c7" class="color_fff">银行账号</td>
                        
                      </tr>
					<tr>
						<td align="center" bgcolor="#ffffff"><b>深圳联星美洲商旅服务有限公司</b></td>
						<td height="42" align="center" bgcolor="#ffffff">&nbsp;<b>中国工商银行深圳分行龙华支行</b></td>
						<td align="center" bgcolor="#ffffff">&nbsp;<b>4000026639202013164</b></td>
						
					</tr>
					</tbody>
					</table>
                    <?php }?>
					<p>（*请在汇款后联系美国走四方客服人员，以便美国走四方可尽早查收该订单汇款，快速为您完成行程预订*）<br />
如需开具中国发票则需支付发票金额的3%的税费和快递费用。</p>
					
                  </div>
                    
					<div class="content_h ">
                    	<a name="face_to_face" style="height:0;font-size:0;"></a>
                   		<h4 class="font_bold font_size14 color_blue">上门付款</h4>
                        <p>中国客服中心<br />
                          地址：深圳市宝安区大龙华中小企业创业总部（民治大道牛栏前大厦）B1508—1510室 518000<br />
                        中国免费电话：400-6333-926</p>
                        <p>美国：<br />
                          美国加州Monterey Park营业厅<br />
                          地址：133B W Garvey Ave, Monterey Park, CA 91754<br />
                          <br />
                          美国加州Rowland Heights营业厅<br />
                        地址：17506 Colima Road, Suite 101, Rowland Heights, CA 91748 </p>
					</div>
				   
				   <?php if(in_array('alipay_direct_pay', $_all_payments_ids)){?>
                    <div class="content_h">
                    	<a name="alipay_direct_pay" style="height:0;font-size:0;"></a>
                   		<h4 class="font_bold font_size14 color_blue">支付宝账号：<b><?= MODULE_PAYMENT_ALIPAY_DIRECT_PAY_EMAIL?></b></h4>
                        <p>1. 可以使用支付宝账号直接支付。 <br />2. 也可以使用国内主流银行发行的各类借记卡和信用卡</p>
                   </div>
				   <?php }?>
				   
                   <?php if(in_array('netpay', $_all_payments_ids)){?>
					<div class="content_h">
                    	<a name="netpay" style="height:0;font-size:0;"></a>
                   		<h4 class="font_bold font_size14 color_blue">银联在线支付：</h4>
                        <p>支持国内主流银行发行的各类借记卡和信用卡</p>
                   </div>
				   <?php }?>
				   
                   <div class="content_h">
                   		<a name="western_union" style="height:0;font-size:0;"></a>
						<h4 class="font_bold font_size14 color_blue">西联汇款 <span class="font_size12 noBold">（该支付方式适用于美国境外的汇款）：</span></h4>
                        <p class="paddingB">收款人姓名：名(Gang), 姓(Yu)<br /> 
收款人电话：(001)225-7544328<br />
收款人地址：133B W Garvey Ave, Monterey Park, CA, USA 91754<br /><br />
（*请在汇款后联系美国走四方客服人员，以便美国走四方可尽早查收汇款，快速为您完成行程预订*）</p><p>
汇款办法：<br />
1. 寻找就近代理网点－汇款人需前往“西联汇款”代理网点（如：中国邮政、中国邮政储蓄银行、农业银行、光大银行等）办理汇款业务<br />
2. 填写汇款单－正确填写汇款人信息及收款人信息（如上所示）<br />
3. 提供监控号码－汇款完毕，联系美国走四方客服人员并告知单据上的“监控号码”（MTCN#），此号码为汇款取款号码，及时生效，请妥善保管汇款单据。
</p>
                   </div>
                   <div class="content_h ">
                   <strong class="color_orange font_size14">公司资质</strong><br />
  美国政府注册的营业执照（UNITED STATES OF AMERICA LICENSE） （如需查验，请直接联系我公司）
                   </div>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("ul.paymentLinks li a").click(function(){
		var urlstr = this.href;
		var anchorstr = urlstr.split("#")[1];
		jQuery("a[name=" + anchorstr + "]").parent().siblings("div").removeClass("bg");
		jQuery("a[name=" + anchorstr + "]").parent().addClass("bg");
	});
});
</script>                 
                   
                </div>
            </div>
        </div>
    </div>

<?php echo  db_to_html(ob_get_clean());?>