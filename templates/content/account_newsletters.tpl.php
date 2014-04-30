<?php 
ob_start();
echo tep_draw_form('account_newsletter', tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); 
?>
      <div class="title titleSmall"> <b></b><span></span>
        <h3>订阅走四方网信息</h3>
      </div>
      
      <div class="routeFav" style="margin-top:0px;">
        <ul class="routeFavCon">
         <div id="ErrMsg"  class="msg"   style="display:none"></div> 
        <div class="routeFavTop"><div class="row1">走四方网信息必选项</div></div>
          <li>
            <h4><input  type="checkbox"  checked="checked" disabled="disabled" />
            订单邮件</h4>
            <p>预订产品时，我们会通知您订单的进展状况，电子客票，重要通知等信息。邮件沟通是最重要的沟通渠道。</p>
          </li>
          <li>
            <h4><input type="checkbox" checked="checked" disabled="disabled" />
            短信通知</h4>
            <p>预订产品时，我们将以短信形式通知您订单的进展状况，重要通知等。</p>
          </li>
       
        <div class="routeFavTop">
          <div class="row1">走四方网信息可选项</div>
        </div>
          <li>
            <h4>
              <?php echo tep_draw_checkbox_field('newsletter_general', '1', (($newsletter['customers_newsletter'] == '1') ? true : false), ''); ?>
              电子报</h4>
            <p>实时推荐走四方网最新旅游资讯，优惠让利，大型活动预告，出行早打算，从订阅走四方电子报开始。</p>
          </li>
          <li>
            <h4>
			 <?php echo tep_draw_checkbox_field('newsletter_eusitrip', '1', (($newsletter_eusitrip == '1') ? true : false), ' '); ?>
              《走四方E游》、《走四方网出行指南》</h4>
              <p>《走四方E游》，这是一本最IN旅游玩家的长成攻略，也是一本最时尚前卫的旅游杂志。从订阅《走四方E游》开始，阅读另一个美好的世界。去美国？去欧洲？还是要玩转全球？最全面的旅游路线，最完美的旅游行程，出行早准备，尽在《走四方网出行指南》。</p>
          </li>
          <li>
          <div class="btnCenter">
          	<a class="btn btnOrange"  href="javascript:;"><button type="submit">保存</button></a>
          	</div>
		</li>
        </ul>
      </div>
      
<?php 
echo db_to_html(ob_get_clean());
if($messageStack->size('account') > 0 ){
	echo $messageStack->output_newstyle('account','ErrMsg');
	echo '<script type="text/javascript">jQuery("#ErrMsg").delay(5000).fadeOut("slow");</script>';
}

?>