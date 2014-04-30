<?php
$use_advantages = true;
if($use_advantages == false){
	include (dirname(__FILE__).'/tours_info.php');
}else{


ob_start();
//我们的优势start{

?>
<?php $temp_href = tep_href_link('about_us.php'); $temp_href='http://208.109.123.18/web_action/2013_6youshi/';?>
    <!--  <div class="advantage_warp border_1 margin_b10 background_1"> 
          <div class="title_1">
            <h2><a href="<?php echo $temp_href?>" target="_blank">为什么选择走四方</a></h2>
          </div>
          <div class="cont">
            <ul>
              <li class="bg1"><a href="<?php echo $temp_href?>" target="_blank">美国官方认证最高评级</a></li>
              <li class="bg2"><a href="<?php echo $temp_href?>" target="_blank">透明公开 低价保证</a></li>
              <li class="bg3"><a href="<?php echo $temp_href?>" target="_blank">品质线路 华人首选</a></li>
              <li class="bg4"><a href="<?php echo $temp_href?>" target="_blank">安全保障 安心无忧</a></li>
              <li class="bg5"><a href="<?php echo $temp_href?>" target="_blank">专业客服 优质服务</a></li>
              <li class="bg6" style="border:none;"><a  href="<?php echo $temp_href?>" target="_blank">高效签证 自由出行</a></li>
            </ul>
          </div>
	  </div>-->
	  <div class="why_selus margin_b10 border_1">
  	<h4>为什么选择走四方</h4>
	<ul class="cfix">
		<li><a href="<?php echo $temp_href?>" target="_blank"><img src="/image/nav/selus_1.png" /><p>美国认证</p></a></li>
		<li><a class="n_r" href="<?php echo $temp_href?>" target="_blank"><img src="/image/nav/selus_2.png" /><p>透明公开</p></a></li>
		<li><a href="<?php echo $temp_href?>" target="_blank"><img src="/image/nav/selus_3.png" /><p>品质线路</p></a></li>
		<li><a class="n_r" href="<?php echo $temp_href?>" target="_blank"><img src="/image/nav/selus_4.png" /><p>安全保障</p></a></li>
		<li><a class="n_b" href="<?php echo $temp_href?>" target="_blank"><img src="/image/nav/selus_5.png" /><p>专业客服</p></a></li>
		<li><a class="n_r n_b" href="<?php echo $temp_href?>" target="_blank"><img src="/image/nav/selus_6.png" /><p>高效签证</p></a></li>
	</ul>
  </div>
<?php //我们的优势end }
echo db_to_html(ob_get_clean());

}
?>
