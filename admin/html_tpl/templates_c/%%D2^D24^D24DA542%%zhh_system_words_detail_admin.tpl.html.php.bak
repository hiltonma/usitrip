<?php /* Smarty version 2.6.25-dev, created on 2013-12-27 00:55:31
         compiled from zhh_system_words_detail_admin.tpl.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script>
jQuery(document).ready(function(){
	var l = jQuery("select[name=FirstDirsIdsFirstDirsIds] option").length;
	var str = '';
	for (var i=0; i<l; i++){
		str += jQuery("select[name=FirstDirsIdsFirstDirsIds] option")[i].text;
	}
	var selected_str = jQuery("select[name=FirstDirsIdsFirstDirsIds] option:selected").text();//请选择子类别	
	if (selected_str == '每日必读'){
		jQuery("select[name=FirstDirsIds]").attr('disabled', 'disabled');
		var re = /每日必读/;
		if (re.test(str)){
			jQuery("select[name=FirstDirsIdsFirstDirsIds]").attr('disabled', 'disabled');
		}else{
			
		}
	}
});
</script>
<div class="main">
<form action="" method="post" enctype="multipart/form-data" name="WordsForm" target="_self" onSubmit="submit_check(this)">
<h1><?php echo $this->_tpl_vars['pageHeading']; ?>
</h1>
<br />
<table border="0" width="1000" cellspacing="0" cellpadding="0" class="td_main">
  <tr>
    <td colspan="2" class="even"><h2>&nbsp;基本内容</h2><div><?php echo $this->_tpl_vars['show_msn']; ?>
</div></td>
  </tr>
  <tr>
    <td width="70">&nbsp;类别：</td><td id="first_dir"><?php echo $this->_tpl_vars['first_dirs_string']; ?>
</td>
  </tr>
  <tr><td colspan="2">
<?php if ($this->_tpl_vars['words_id'] > 0): ?>
<label style="color:#999">&nbsp;发布者：<?php echo $this->_tpl_vars['sent_name']; ?>
&nbsp;&nbsp;
发布日期：<?php echo $this->_tpl_vars['added_time']; ?>
&nbsp;&nbsp;
最后更新：<?php echo $this->_tpl_vars['last_up_per_name']; ?>
&nbsp;&nbsp;
更新日期：<?php echo $this->_tpl_vars['updated_time']; ?>
</label>
<?php endif; ?>
    </td>
  </tr>
  <tr>
    <td>&nbsp;标题：</td><td><?php echo $this->_tpl_vars['input_field_words_title']; ?>
</td>
  </tr>
  <?php if ($this->_tpl_vars['have_everyone']): ?>
  <tr>
	<td>&nbsp;紧急：</td>
	<td><label><?php echo $this->_tpl_vars['radio_button_is_adjective']; ?>
</label>&nbsp;<label><?php echo $this->_tpl_vars['radio_button_no_adjective']; ?>
</label></td>
  </tr>
  <tr>
  	<td>&nbsp;Provider：</td>
	<td><?php echo $this->_tpl_vars['input_field_provider']; ?>
</td>
  </tr>
  <?php endif; ?>
  <tr>
    <td valign="top">&nbsp;文章附件：</td>
    <td>
      <div id="annex_boxes" style="float:left">
        <?php echo $this->_tpl_vars['annexs_div_string']; ?>

      </div>
      <div style="float:left; margin-left:10px; display:inline;"><a href="JavaScript:void(0)" onClick="add_file_upload_box('annex_boxes');" class="dosome">增加新附件</a></div>
      <div style="float:left; margin-left:10px; display:inline;"><b style="color:#090">提示：支持中文文件名。允许上传<?php echo $this->_tpl_vars['upload_max_filesize']; ?>
以下的文件！</b></div>
    </td>
  </tr>
  <tr>
    <td>&nbsp;内容：</td>
    <td><span style="color:#999">（请使用<b style="color:#F00">简体</b>输入）</span></td>
  </tr>
  <tr>
    <td height="310"></td>
    <td>
    <?php echo $this->_tpl_vars['textarea_field_words_content']; ?>

	<iframe id="message___Frame" src="FCKeditor/editor/fckeditor.html?InstanceName=words_content&amp;Toolbar=Default" frameborder="no" height="300" scrolling="no" width="920"></iframe></td>
  </tr>
  <tr>
    <td colspan="2"><input type="hidden" name="have_everyone" id="have_everyone" value="<?php echo $this->_tpl_vars['have_everyone']; ?>
" /><input name="submit_action" type="hidden" id="submit_action" value="1"><input name="words_id" type="hidden" id="words_id" value="<?php echo $this->_tpl_vars['words_id']; ?>
"></td>             
  </tr>
</table>

<br />

<table border="0" width="1000" cellspacing="0" cellpadding="0" class="td_main">
  <tr>
    <td colspan="2" class="even"><h2>&nbsp;&nbsp;高级设置</h2></td>
  </tr>
  <tr>
    <td style="border-right:1px dashed #d5d5d5">&nbsp;&nbsp;<b>同时添加到其它目录</b></td>
    <td>&nbsp;&nbsp;<b>权限设置</b><span style="color:#F00">提示：新增文章时若右边的权限框为空则会从文章所在目录中继承权限！</span></td>
  </tr>
  <tr>
    <td valign="top" align="center" style="border-right:1px #d5d5d5 dashed">
      <table border="0">
        <tr><td>&nbsp;所有目录</td><td style="border:0;">&nbsp;</td>
        <td>&nbsp;同时添加到以下目录</td></tr>
        <tr><td valign="top">
       <?php echo $this->_tpl_vars['mselect_menu_all_dir_box']; ?>

        </td><td valign="middle" align="center" style="border:0; width:80px; "><div style="float:left; margin-left:3px;  *margin-left:18px; _margin-left:3px; _display:inline">
        <input name="Submit" class="AllbuttonHui" type="button" title="增加" onClick="add_to_class('all_dir_box','dirs_id')" value=" 添加&gt;&gt; " /><br /><br />
        <input type="button" class="AllbuttonHui" title="撤消" name="Submit" onClick="move_form_categories('dirs_id')" value=" &lt;&lt;移除 " /></div>
          </td><td valign="top">
        <?php echo $this->_tpl_vars['mselect_menu_dirs_ids']; ?>

        </td></tr>
        </table>
    </td>
    <td  align="center">
       <table>
        <tr><td>&nbsp;所有用户组</td><td>&nbsp;</td><td>&nbsp;只允许以下用户查看</td></tr>
        <tr><td valign="top">
       
       <?php echo $this->_tpl_vars['mselect_menu_all_class_box']; ?>


        </td><td valign="top" align="center" style="border:0; width:80px;"><div style="float:left; margin-left:3px; display:inline; *margin-left:25px; _margin-left:3px;">
        <input name="Submit" class="AllbuttonHui" type="button" title="增加" onClick="add_to_class('all_class_box','r_groups_id')" value=" 添加&gt;&gt; " />
        <input type="button"  class="AllbuttonHui" title="撤消" name="Submit" onClick="move_form_categories('r_groups_id')" value=" &lt;&lt;移除 " /><br />
        
        <input name="Submit" class="AllbuttonHui" type="button" title="增加" style="margin-top:10px;" onClick="add_to_class('all_class_box','rw_groups_id')" value=" 添加&gt;&gt; " />
        <input type="button" class="AllbuttonHui" title="撤消" name="Submit" onClick="move_form_categories('rw_groups_id')" value=" &lt;&lt;移除 " />
                
        <input name="Submit" class="AllbuttonHui" type="button" title="增加" style="margin-top:12px;" onClick="add_to_class('all_class_box','rwd_groups_id')" value=" &gt;&gt;添加 " />
        <input type="button" class="AllbuttonHui" title="撤消" name="Submit" onClick="move_form_categories('rwd_groups_id')" value=" &lt;&lt;移除 " />
		<?php if ($this->_tpl_vars['have_everyone']): ?>
		<input name="Submit" class="AllbuttonHui" type="button" title="增加" style="margin-top:12px;" onClick="add_to_class('all_class_box','re_groups_id')" value=" &gt;&gt;添加 " />
        <input type="button" class="AllbuttonHui" title="撤消" name="Submit" onClick="move_form_categories('re_groups_id')" value=" &lt;&lt;移除 " />
		<?php endif; ?>
		</div>
          </td><td valign="top">
       <?php echo $this->_tpl_vars['mselect_menu_r_groups_ids']; ?>

       <?php echo $this->_tpl_vars['mselect_menu_rw_groups_ids']; ?>

       <?php echo $this->_tpl_vars['mselect_menu_rwd_groups_ids']; ?>

	   <?php if ($this->_tpl_vars['have_everyone']): ?>
		<input type="hidden" name="have_everyone" value="1"/>
		<?php echo $this->_tpl_vars['mselect_menu_erveryone_groups_ids']; ?>

	   <?php endif; ?>
        </td></tr>
        </table>
    
    </td>
  </tr>
  <tr>
    <td colspan="2" height="50">
  <div style="width:98%; text-align:center;">
  <button name="submit" class="Allbutton" type="submit">确定</button>
  <?php if ($this->_tpl_vars['words_id'] > 0): ?>
  <button name="submit" class="AllbuttonHui" type="button" id="Continue_to_add" title="<?php echo $this->_tpl_vars['dir_id']; ?>
">继续添加新文章</button>
  <?php endif; ?>
  <a href="zhh_system_words_list.php?dir_id=<?php echo $this->_tpl_vars['last_visited_dir_id']; ?>
" class="caozuo">返回上级目录</a>
  </div>
    </td>
  </tr>
 </table>
	  
</form>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>