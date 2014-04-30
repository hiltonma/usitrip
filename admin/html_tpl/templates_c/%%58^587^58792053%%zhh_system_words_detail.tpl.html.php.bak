<?php /* Smarty version 2.6.25-dev, created on 2013-12-27 00:54:22
         compiled from zhh_system_words_detail.tpl.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
function del_words(words_id,botton_obj){
	var obj = botton_obj;
	if(confirm('确认要删除这个文章？')){
		//alert('OK');
		var urladdress = "<?php echo $this->_tpl_vars['js_urladdress']; ?>
";
		urladdress+= "&words_id="+words_id;
		$.ajax({
			   type: "GET",
			   url: urladdress,
			   success: function(){
					$("#Lb_content").hide(500);
					var html_code = '删除成功！[<a href="javascript:history.back(-1);">返回</a>]';
					$("#Lb_content").html(html_code);
					$("#Lb_content").show(500);
				}
			   });
	}
}

</script>

<div class="main">
<div class="ItemsLbContent" id="Lb_content">
<h1 class="contentH1"><?php echo $this->_tpl_vars['WordsHeading']; ?>
</h1>
<h3 class="contentBTp">发布者：<b><?php echo $this->_tpl_vars['sent_name']; ?>
</b>&nbsp;&nbsp;
		  发布日期：<b><?php echo $this->_tpl_vars['added_time']; ?>
</b>&nbsp;&nbsp;
		  最后更新：<b><?php echo $this->_tpl_vars['last_up_per_name']; ?>
</b>&nbsp;&nbsp;
		更新日期：<b><?php echo $this->_tpl_vars['updated_time']; ?>
</b>
		&nbsp;&nbsp;<i><?php echo $this->_tpl_vars['EditAHref']; ?>
</i>&nbsp;<i><?php echo $this->_tpl_vars['DeleteAHref']; ?>
</i></h3>
<div class="wenzhangContent">
 <?php echo $this->_tpl_vars['words_content']; ?>

</div>

<?php if ($this->_tpl_vars['annexs_dir_string'] != ""): ?>		
  <div style="display:block; float:left; margin:0 0 0 10px; display:inline;">
	<h4>文章附件：</h4>
		<div id="annex_boxes">
		  <?php echo $this->_tpl_vars['annexs_dir_string']; ?>

		</div>
  </div>
 <?php endif; ?>

</div>

</div>
<?php if ($this->_tpl_vars['have_everyone']): ?>
<table>
<tr>
<td>
<div>
	<form action="" method="post">
		<input type="checkbox" name="read" value="1"/>
		<input type="hidden" name="words_id" id="words_id" value="<?php echo $this->_tpl_vars['words_id']; ?>
"/>
		<input type="submit" name="submit" value="已经阅读"/>
	</form>
</div>
</td>
</tr>
</table>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>