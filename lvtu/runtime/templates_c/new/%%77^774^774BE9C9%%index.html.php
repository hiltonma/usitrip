<?php /* Smarty version 2.6.22, created on 2013-12-27 17:27:46
         compiled from index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="body">
	<div class="crumbs"></div>
	<h1><a href="javascript:void(0)" class="J-publish publish"></a><a href="<?php echo $this->_tpl_vars['nav_index']; ?>
" class="myhome myfind"></a><a href="<?php echo $this->_tpl_vars['nav_index']; ?>
" class="a_all"><span class="title"><i></i>全部游记</span></a></h1>
	<p class="place uifix"> 
	<?php unset($this->_sections['key']);
$this->_sections['key']['name'] = 'key';
$this->_sections['key']['loop'] = is_array($_loop=$this->_tpl_vars['list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['key']['show'] = true;
$this->_sections['key']['max'] = $this->_sections['key']['loop'];
$this->_sections['key']['step'] = 1;
$this->_sections['key']['start'] = $this->_sections['key']['step'] > 0 ? 0 : $this->_sections['key']['loop']-1;
if ($this->_sections['key']['show']) {
    $this->_sections['key']['total'] = $this->_sections['key']['loop'];
    if ($this->_sections['key']['total'] == 0)
        $this->_sections['key']['show'] = false;
} else
    $this->_sections['key']['total'] = 0;
if ($this->_sections['key']['show']):

            for ($this->_sections['key']['index'] = $this->_sections['key']['start'], $this->_sections['key']['iteration'] = 1;
                 $this->_sections['key']['iteration'] <= $this->_sections['key']['total'];
                 $this->_sections['key']['index'] += $this->_sections['key']['step'], $this->_sections['key']['iteration']++):
$this->_sections['key']['rownum'] = $this->_sections['key']['iteration'];
$this->_sections['key']['index_prev'] = $this->_sections['key']['index'] - $this->_sections['key']['step'];
$this->_sections['key']['index_next'] = $this->_sections['key']['index'] + $this->_sections['key']['step'];
$this->_sections['key']['first']      = ($this->_sections['key']['iteration'] == 1);
$this->_sections['key']['last']       = ($this->_sections['key']['iteration'] == $this->_sections['key']['total']);
?>
	<a href="<?php echo $this->_tpl_vars['list'][$this->_sections['key']['index']]['href']; ?>
"><?php echo $this->_tpl_vars['list'][$this->_sections['key']['index']]['name']; ?>
</a>
	<?php endfor; endif; ?></p>
	<ul class="piclist uifix">
		<?php unset($this->_sections['key']);
$this->_sections['key']['name'] = 'key';
$this->_sections['key']['loop'] = is_array($_loop=$this->_tpl_vars['travel_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['key']['show'] = true;
$this->_sections['key']['max'] = $this->_sections['key']['loop'];
$this->_sections['key']['step'] = 1;
$this->_sections['key']['start'] = $this->_sections['key']['step'] > 0 ? 0 : $this->_sections['key']['loop']-1;
if ($this->_sections['key']['show']) {
    $this->_sections['key']['total'] = $this->_sections['key']['loop'];
    if ($this->_sections['key']['total'] == 0)
        $this->_sections['key']['show'] = false;
} else
    $this->_sections['key']['total'] = 0;
if ($this->_sections['key']['show']):

            for ($this->_sections['key']['index'] = $this->_sections['key']['start'], $this->_sections['key']['iteration'] = 1;
                 $this->_sections['key']['iteration'] <= $this->_sections['key']['total'];
                 $this->_sections['key']['index'] += $this->_sections['key']['step'], $this->_sections['key']['iteration']++):
$this->_sections['key']['rownum'] = $this->_sections['key']['iteration'];
$this->_sections['key']['index_prev'] = $this->_sections['key']['index'] - $this->_sections['key']['step'];
$this->_sections['key']['index_next'] = $this->_sections['key']['index'] + $this->_sections['key']['step'];
$this->_sections['key']['first']      = ($this->_sections['key']['iteration'] == 1);
$this->_sections['key']['last']       = ($this->_sections['key']['iteration'] == $this->_sections['key']['total']);
?>
		<li class="J-npc"> <a href="<?php echo $this->_tpl_vars['travel_list'][$this->_sections['key']['index']]['href']; ?>
"> <span class="white f14 font-yahei abs npc_bgt"><?php echo $this->_tpl_vars['travel_list'][$this->_sections['key']['index']]['travel_notes_title']; ?>
</span> <span class="npc_bg hide abs">
			<p>分享人：<?php echo $this->_tpl_vars['travel_list'][$this->_sections['key']['index']]['customer_name']; ?>
</p>
			<p>分享时间：<?php echo $this->_tpl_vars['travel_list'][$this->_sections['key']['index']]['add_time']; ?>
[<?php echo $this->_tpl_vars['travel_list'][$this->_sections['key']['index']]['day_num']; ?>
天 <?php echo $this->_tpl_vars['travel_list'][$this->_sections['key']['index']]['image_number']; ?>
张]</p>
			<p>分享线路：<?php echo $this->_tpl_vars['travel_list'][$this->_sections['key']['index']]['products_name']; ?>
</p>
			<p>浏览：<?php echo $this->_tpl_vars['travel_list'][$this->_sections['key']['index']]['read_number']; ?>
 喜欢：<?php echo $this->_tpl_vars['travel_list'][$this->_sections['key']['index']]['like_number']; ?>
 </p>
			</span> </a> <a href="<?php echo $this->_tpl_vars['travel_list'][$this->_sections['key']['index']]['href']; ?>
"> <img src="<?php echo $this->_tpl_vars['travel_list'][$this->_sections['key']['index']]['cover_image']; ?>
" width="<?php echo $this->_tpl_vars['travel_list'][$this->_sections['key']['index']]['image_width']; ?>
" height="<?php echo $this->_tpl_vars['travel_list'][$this->_sections['key']['index']]['image_height']; ?>
" onerror="this.src='<?php echo @DIR_WS_IMG; ?>
erroimg.jpg'" /> </a> </li>
		<?php endfor; endif; ?>
	</ul>
	</div>
<div>
<input id="currIndex" value="1" type="hidden" />
<!-- 页面导航 -->
<input id="pageNo" value="<?php echo $this->_tpl_vars['page']; ?>
" type="hidden" />
<input id="totalAlbumNum" value="<?php echo $this->_tpl_vars['totalAlbumNum']; ?>
" type="hidden" />
<input id="pageSize" value="<?php echo $this->_tpl_vars['pagesize']; ?>
" type="hidden"/>
<input id="userId" value="" type="hidden"/>
<input id="newHandler" value="" type="hidden"/>
<div class="lvtu_lineBetween fn-bc mt20 mb20 png24"></div>
<div id="pagination" class="pagination darkgray"></div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "foot.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script src="<?php echo @DIR_WS_JS; ?>
index.js" type="text/javascript"></script> 
<script type="text/javascript">	
var pathUpload = "<?php echo $this->_tpl_vars['nav_index']; ?>
";
</script> 


<script src="<?php echo @DIR_WS_JS; ?>
index-201301041651.js"></script> 
<script type="text/javascript">
$(function(){
	/* 新手引导 */
	/*var newHandler = $("#newHandler").val();
	$(".guide_close").click(function(){
		$(".guide_bg,.guide_cot").hide();
		var userid =$("#userId").val();
		var random = new Date().getTime();
    	var params = {
    			staus : "1000",
    			newHandler:newHandler,
    			userid :userid,
				random : random
    	};
    	
		$.getJSON("web/updateHandlerStatus.htm", params, function(info) {
    		 
	      });
	});
	*/
    var pageNo = $("#pageNo").val();
    var totalAlbumNum = $("#totalAlbumNum").val();
    var pageSize = $("#pageSize").val();
    var lastItemTimeMs = $("#lastItemTimeMs").val();
    <!-- 初始化分页控件-->
	$("#pagination").paging(totalAlbumNum, {
		format : '[<nnnncnnnn>]',
		perpage : pageSize,
		page : pageNo ,
		onSelect : function(page) {
			<!-- 单击页码操作方法-->
			if(page == pageNo ){
				return;
			}else{
				var _page = lwkai.getUrlParam('page');
				var temp = window.location.href.split('page--' + _page),url='';
				if (temp.length > 1) {
					url = temp[0] + 'page--' + page + temp[1];
				} else {
					var a = temp[0].replace('.html','');
					a = a.indexOf('index/index/') != -1 ? a : a + 'index/index/';
					if (a.indexOf('--') != -1) {
						a += '--page--' + page + '.html';
					} else {
						a += 'page--' + page + '.html';
					}
					url = a;
				}
				window.location.href = url;//pathUpload + "index/index/page--" + page + '.html';
			}
			
		} 
	});
	
	/**最后显示页脚*/
	$(".qn_footer").css("visibility","visible");
});
  </script> 
</body></html>