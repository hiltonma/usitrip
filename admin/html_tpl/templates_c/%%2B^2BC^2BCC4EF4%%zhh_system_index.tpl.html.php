<?php /* Smarty version 2.6.25-dev, created on 2013-12-27 00:54:27
         compiled from zhh_system_index.tpl.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
function openframe(){
	
}
</script>
<body onLoad="">
<table width="100%"  border="0" height="100%" cellpadding="0" cellspacing="0" class="centerTab">
  <tr id="TopShow_content">
    <td colspan="3" height="55px">
      <div class="centerTopFrame">
        <div class="head">
          <p class="logo"><a href="<?php echo @HTTP_SERVER; ?>
" target="_blank"><img src="images/logo.20121014.jpg" class="logoimg" /></a><span><?php echo $this->_tpl_vars['title']; ?>
</span></p>
          <div class="changeXt"  id="changeXt" onMouseOver="document.getElementById('child').style.display='block';document.getElementById('changeXtTop').className='changeXtTopSelect'" onMouseOut="document.getElementById('child').style.display='none';document.getElementById('changeXtTop').className='changeXtTop'">
            <div id="changeXtTop" class="changeXtTop">切换系统</div>
            <div class="TopShowBg" id="child" style="display:none" >
              <div class="changeOther">
                <ul>
                  <li><a href="domestic_orders.php">转帐支付管理系统</a></li>
                  <li><a href="orders.php">后台订单</a></li>
                  <li><a href="categories.php">产品管理</a></li>
                  <li><a href="banner_manager.php">广告管理</a></li>
                  <li><a href="question_answers.php">QA管理</a></li>
				  <li><a href="daren_system.php">走四方旅游达人管理</a></li>
                </ul>
              </div>
            </div>
          </div>
       </div>
       <div class="Welcome">
	   <?php echo $this->_tpl_vars['login_name']; ?>
 欢迎您登录，<a href="logoff.php" class="DChu">退出</a>
	   <?php if ($this->_tpl_vars['login_groups_id'] == '1'): ?>
	   <a href="configuration.php" class="SZhi">系统设置</a>
	   <?php endif; ?>
	   </div>
</div>
</td></tr>
  <tr><td colspan="3" height="7" valign="top"><div class="TopShow" id="TopShowtit" onClick="showHideLyer(this,'TopShow_content','TopShowselect')"></div></td></tr>

  <tr>
    <td class="centerLeftFrame " width="200px;" id="LeftShow_content"><iframe name="I2" id="I2" height="100%"  width="200px" src="<?php echo $this->_tpl_vars['lift_frame_src']; ?>
" border="0" frameborder="0" scrolling="auto"  > 浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe></td>
    <td class="LeftShowBg "><div class="LeftShow" id="LeftShowtit"></div></td>
    <td class="IndexWidth"><div style= "height: 100% "> <iframe  width="100%" height="100%" name="MzMain" id="MzMain" src="<?php echo $this->_tpl_vars['right_frame_src']; ?>
" border="0" frameborder="0" scrolling="" > 浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe></div></td>
  </tr>
  <tr><td colspan="3" class="Centerbottom" ><p class="bottomTxt">版权 &copy;2006-<?php echo date('Y'); ?> usitrip.com</p></td></tr>
  </table>
</body>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>