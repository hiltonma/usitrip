<?php /* Smarty version 2.6.25-dev, created on 2013-12-29 00:07:25
         compiled from zhh_system_dir_admin.tpl.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th height="25"><b>类别管理</b></th>
</tr>
<tr>
	<td style="padding-bottom:10px;border-bottom:1px #d5d5d5 solid">
		<!--search form start-->
			<form action="" method="get" id="form_search" name="form_search" >
			<table width="95%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td >&nbsp;目录名：<?php echo $this->_tpl_vars['input_field_search_class_name']; ?>

						<button type="submit" class="Allbutton">搜索</button>
						<input name="action" type="hidden" id="action" value="search"></td>
				</tr>
				<tr>
					<td >
					&nbsp;<?php echo $this->_tpl_vars['search_result']; ?>

					</td>
				</tr>
			</table>
			</form>
		<!--search form end-->
	</td>
</tr>
<tr>
	<td>
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td valign="top" >
						<strong>&nbsp;当前目录树：</strong>
					</td>
					<?php echo $this->_tpl_vars['now_class_str']; ?>

					<td valign="top" >
					<?php echo $this->_tpl_vars['class_tree_string']; ?>

				</td>
				</tr>
				<tr>
					<td align="right" valign="top" >
					<strong>当前目录：</strong><br><br>
					<strong>目录说明：</strong><br><br><br><br><br>
					<strong>权限设置：</strong>
					</td>
					<td valign="top" >
						<form action="" method="post" id="ClassForm" name="ClassForm"  onSubmit="submit_check(this)">
						<?php echo $this->_tpl_vars['input_field_dir_name']; ?>

						排列序号
						<?php echo $this->_tpl_vars['input_field_sort_num']; ?>

						<?php echo $this->_tpl_vars['hidden_field_dir_id']; ?>
    
						  <div id="update_more">
							
						<?php echo $this->_tpl_vars['textarea_field_dir_description']; ?>

						</div>
						  
						  <div>
				  <table>
				  <tr>
				  	<td style="height:30px;" >所有用户组</td><td>&nbsp;</td>
				  	<td >只允许以下用户查看</td></tr>
				  <tr><td valign="top" >
				  <?php echo $this->_tpl_vars['mselect_menu_all_class_box']; ?>


				  </td><td valign="top" >
				  <input name="Submit" type="button" title="增加" onClick="add_to_class('all_class_box','r_groups_id')" value=" 增加&gt;&gt; " /><br />
				  <input type="button" title="撤消" name="Submit" onClick="move_form_categories('r_groups_id')" value=" &lt;&lt;删除 " /><br /><br /><br />
				  <input name="Submit" type="button" title="增加" onClick="add_to_class('all_class_box','rw_groups_id')" value=" 增加&gt;&gt; " /><br />
				  <input type="button" title="撤消" name="Submit" onClick="move_form_categories('rw_groups_id')" value=" &lt;&lt;删除 " /><br /><br /><br />
				  <input name="Submit" type="button" title="增加" onClick="add_to_class('all_class_box','rwd_groups_id')" value=" 增加&gt;&gt; " /><br />
				  <input type="button" title="撤消" name="Submit" onClick="move_form_categories('rwd_groups_id')" value=" &lt;&lt;删除 " />
					</td><td valign="top" >
				 <?php echo $this->_tpl_vars['mselect_menu_r_groups_ids']; ?>

				 <?php echo $this->_tpl_vars['mselect_menu_rw_groups_ids']; ?>

				 <?php echo $this->_tpl_vars['mselect_menu_rwd_groups_ids']; ?>

				  </td></tr>
				  <tr>
					<td colspan="3" valign="top" >
					<label>
					  <input name="permissions_all_child" type="checkbox" id="permissions_all_child" value="1">权限应用于当前目录的所有子级目录(包括文章)
					</label>
					</td>
					</tr>
				  </table>
						  </div>
                          

							<button name="submit" class="Allbutton" type="submit" onclick="document.getElementById('ClassForm').elements['submit_action'].value='Update'">更新</button> Or
							<button name="submit" class="AllbuttonHui" type="submit" onClick="delclass(); return false">删除</button>
                            
                            <br><br>
                            
                            <input name="submit_action" type="hidden" value="Update" >

						</form>
						<script type="text/javascript">
							function delclass(){
								var tmpvar = window.confirm('删除目录会连其所有的子目录都会删除，您真的要删除吗？');
								if(tmpvar==true){
									document.getElementById('ClassForm').elements['submit_action'].value='Delete';
									submit();
								}
							}
						</script> </td>
				</tr>
				
				<tr>
					<td align="right" valign="top" >&nbsp;</td>
					<td valign="top" >
						<form action="" method="post" name="ClassForm_2" onsubmit="check_add_subdir(this); return false;">
								<?php echo $this->_tpl_vars['input_field_add_class_name']; ?>

								<?php echo $this->_tpl_vars['hidden_field_add_parent_id']; ?>

                                <button name="submit" class="Allbutton" type="submit">增加子目录</button>
                                <input name="submit_action" type="hidden" value="Add" >
							</form> </td>
					</tr>
					<tr>
						<td align="right" valign="top" >&nbsp;</td>
						<td valign="top"  style="color:#FF0000"><?php echo $this->_tpl_vars['error_msn']; ?>
</td>
					</tr>
					<tr>
						<td align="right" valign="top" >&nbsp;</td>
						<td valign="top"  style="color:#00CC00">
						<?php echo $this->_tpl_vars['done_msn']; ?>

						<?php if ($this->_tpl_vars['done_msn'] != ""): ?>
						<!--如果操作成功则更新左框架的菜单信息-->
<script type="text/javascript">
var I2_iframe = parent.parent.document.getElementById("I2");
if(I2_iframe!=null){
	//alert(1);
	I2_iframe.src="zhh_system_left_menu.php?open_dir_id=<?php echo $this->_tpl_vars['dir_id']; ?>
";
}
</script> 
						<?php endif; ?>
						</td>
					</tr>
				</table>
		</td>
	</tr>
</table>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>