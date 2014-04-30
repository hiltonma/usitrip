<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
$is_js_file = false;	/* 如果为false将以php的格式一行一行列到页面 */
if($base_php_self == "javascript.php"){
	$is_js_file = true;
}
if($is_js_file==false){
?>
<script type="text/javascript"><!--

<?php
}
?>

<?php
/* photo_list页面的jquery代码 start */
if($content == 'photo_list'){
?>

jQuery(document).ready(function(){
	<?php /* 结伴同游通知关闭 */?>
	jQuery("#NewVersionNoteButton").click( function(){
		alert(1);
		jQuery("#NewVersionNote").hide(200);
		return false;
	});
	<?php /* 点击编辑按钮 */?>
	jQuery("#EditPotoA").click( function(){
		jQuery("#EditPotoDiv").attr('style', function(){
			return this.style.display = "";
		});
		return false;
	});
	<?php //提交相片编辑?>
	jQuery("#EditPotoForm").submit( function(){
		var error = false;
		var error_msn = "";
		for(i=0; i<this.elements.length; i++){
			if((this.elements[i].value==this.elements[i].title || this.elements[i].value.leng<2) && this.elements[i].title!=""){
				error = true;
				error_msn += "* "+ this.elements[i].title+"\t\n";
			}
		}
		if(error == true){
			alert(error_msn);			
			return false;
		}
		var Submit_Photo_Button = document.getElementById("submit_photo_button");
		var Load_Icon = document.getElementById("load_icon");
		Submit_Photo_Button.disabled = true;
		Load_Icon.style.display = "";
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_create_photos.php','action=update')) ?>");
		var form_id = this.id;
		var success_msm = "";
		var success_go_to = "";
		ajax_post_submit(url,form_id,success_msm,success_go_to);
		return false;
	});
	<?php //删除相片?>
	jQuery("#del_a").click( function(){
		if(confirm("<?php echo db_to_html("删除这张照片时，将会同时删除游记中的这张照片，确定删除吗？");?>")){
			var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_create_photos.php','action=del')) ?>");
			url += "&photo_id=<?= (int)$photo_id?>";
			ajax_get_submit(url);
		}
		return false;
	});

});





function remove_photo_comments(comments_id){
	if(confirm("<?php echo db_to_html("您确定要删除这条评论吗？")?>")){
		/* var aid = "#comments_"+comments_id; */
		/* $(aid).fadeOut(1000); */
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_create_photos.php','action=remover_comments')) ?>");
		url += "&comments_id="+comments_id;
                url += "&photo_id=<?php echo (int)$photo_id;?>";
	        var success_msm = "";
		var success_go_to = "";
		ajax_get_submit(url,success_msm,success_go_to);
	}
}

<?php
}
/* photo_list页面的jquery代码 end */

/* 游记页面的jquery代码 start */
if($content == 'travel_notes_detail'){
?>

function delete_note_poto(vid){	/* 删除单张游记相片 */
	if(confirm("<?php echo db_to_html("删除这张照片时，将会同时删除您相册中的这张照片，确定删除吗？");?>")){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_create_photos.php','action=del')) ?>");
		url += "&form_type=travel_notes_detail&photo_id="+vid;
		ajax_get_submit(url);
	}
	return false;
}

function del_travel_notes(notte_id){	/* 删除游记 */
	if(confirm("<?php echo db_to_html("删除这个游记，将会同时删除该游记下面的所有评论，确定删除吗？");?>")){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_create_photos.php','action=del_travel_notes')) ?>");
		url += "&travel_notes_id="+notte_id;
		ajax_get_submit(url);
	}
	return false;
}

<?php
}
/* 游记页面的jquery代码 end */
?>


function MM_jumpMenu(selObj,parameter){ /* v3.0 */
  var location_val = "<?php echo preg_replace($p,$r,tep_href_link_noseo('new_travel_companion_index.php'))?>";
  if(parameter!=''){ parameter = '&'+parameter; }
  var link_symbol = '?';
  if(location_val.indexOf('?')>-1){
  	link_symbol = '&';
  }
  if(selObj.options[selObj.selectedIndex].value!=''){
  	eval("self.location='"+location_val+link_symbol+selObj.id+'='+selObj.options[selObj.selectedIndex].value+parameter+"'");
  }

}

function MM_jumpMenu_hoistory(selObj){
  var location_val = "<?php echo preg_replace($p,$r,tep_href_link_noseo('new_travel_companion_index.php'))?>";
  var link_symbol = '?';
  if(location_val.indexOf('?')>-1){
  	link_symbol = '&';
  }
  if(selObj.options[selObj.selectedIndex].value!=''){
  	eval("self.location='"+location_val+link_symbol+'TcPath='+selObj.options[selObj.selectedIndex].value+"'");
  }
}

/* 显示并指定回帖表单 */
function show_and_hidden(id_name,parent_id,css_name){
	var id = document.getElementById(id_name);
	id.elements['parent_id'].value = parent_id;
	id.elements['parent_type'].value = '0';
	id.elements['t_companion_content'].value = "";
	var bbs_content = document.getElementById('bbs_content_' + parent_id);
	if(bbs_content==null){
		bbs_content = document.getElementById('root_tiezi_post');
	}
	VarQuoteReply = document.getElementById('QuoteReply');
	VarQuoteReply.innerHTML= '<div style="padding:10px 5px 5px 5px; background: #F5F5F5 none repeat scroll 0 0"><?php echo db_to_html('回复：')?>'+bbs_content.innerHTML+'</div>';
	if(id.style.display=='none'){
		id.style.display='';
	}else{
		/* id.style.display='none'; */
	}
	var bbs_customers_name_gender = document.getElementById('bbs_customers_name_gender_' + parent_id);
	if(parent_id>0){
		var DivReply = document.getElementById('CompanionDivReply');
		DivReply.className =css_name;
		var Close_Button = document.getElementById('close_button');
		Close_Button.style.display = "";
		showDiv('CompanionDivReply');
		var Only_Top_Can_See = document.getElementById('only_top_can_see');
		Only_Top_Can_See.checked = false;
		var Tell_Only = document.getElementById('tell_only');
		Tell_Only.style.display = "none";
		
		var table_top = "<?php echo preg_replace('/[[:space:]]+/',' ',addslashes(tep_pop_div_add_table('top')));?>";
		var table_foot = "<?php echo preg_replace('/[[:space:]]+/',' ',addslashes(tep_pop_div_add_table('foot')));?>";
		if(DivReply.className=="jb_fb_tcAddXx"){
			if(DivReply.innerHTML.indexOf("jb_xj_t1")==-1){
				DivReply.innerHTML = table_top + DivReply.innerHTML + table_foot;
			}
		}
                if(DivReply.className=="jb_fb_tc"){
			if(DivReply.innerHTML.indexOf("jb_xj_t1")==-1){
				DivReply.innerHTML = table_top + DivReply.innerHTML + table_foot;
			}
		}
		
	}
	if(typeof(id.elements['t_companion_content'])=="object" && id.elements['t_companion_content'].style.display!="none"){
		id.elements['t_companion_content'].focus();
	}
}

/* 回复的关闭按钮 */
function re_close_button(){
	var id = document.getElementById('CompanionFormReply');
	id.elements['parent_id'].value = '0';
	id.elements['parent_type'].value = '0';
	
	var obj = document.getElementById('CompanionDivReply');
	closeDiv('CompanionDivReply');
	obj.className = "jb_ft_hf";
	obj.style.display = "";
	var Close_Button = document.getElementById('close_button');
	Close_Button.style.display = "none";
	var Only_Top_Can_See = document.getElementById('only_top_can_see');
	Only_Top_Can_See.checked = false;
	var Tell_Only = document.getElementById('tell_only');
	Tell_Only.style.display = "";

	if(obj.className=="jb_ft_hf"){
		var td = obj.getElementsByTagName("td");
		for(i=0; i<td.length; i++){
			if(td[i].className=="jb_xj_content"){
				obj.innerHTML = td[i].innerHTML;
				break;
			}
		}
	}
}

/* 转换空格值 */
function br2nl(value) {
	return value.replace(/<br \/>|<br>|<br\/>/ig, "\n");
}

/* 引用帖子内容 */
function quote_bbs(id_name,parent_id){
	var id = document.getElementById(id_name);
	id.elements['parent_id'].value = parent_id;
	id.elements['parent_type'].value = '1';
	if(typeof(id.elements['t_companion_content'])=="object" && id.elements['t_companion_content'].style.display!="none"){
		id.elements['t_companion_content'].focus();
	}
	id.elements['t_companion_content'].value = "";
	
	var tiezi_post = document.getElementById('tiezi_post_' + parent_id)
	VarQuoteReply = document.getElementById('QuoteReply');
	VarQuoteReply.innerHTML= '<div class="yingyong"><?php echo db_to_html('引用：')?>'+tiezi_post.innerHTML+'</div>';
	if(id.style.display=='none'){
		id.style.display='';
	}else{
		/* id.style.display='none'; */
	}
}

/* 检测并提交回帖 */
function Submit_Companion_Re(From_id){

	var Companion = document.getElementById(From_id);
	var error_msn = '';
	var error = false;
	for(i=0; i<Companion.length; i++){
	
		if(Companion.elements[i]!=null){
			if((Companion.elements[i].value.length < 1 || Companion.elements[i].value == Companion.elements[i].title) && Companion.elements[i].className.search(/required/g)!= -1){
				error = true;
				error_msn +=  "* " + Companion.elements[i].title + "\n\n";
			}
		}
	}
	
	if(error==true){
		alert(error_msn);
		return false;
	}else{
		
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_travel_companion_re.php','action=process')) ?>");
		var form_id = From_id;
		var success_msm = "";
		var success_go_to = "";
		ajax_post_submit(url,form_id,success_msm,success_go_to);

	}
}

function hepl_window(url) {
	window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width=708,height=800,screenX=150,screenY=150,top=150,left=150')
}
function show_travel_companion_tips(int_num, tips_id_mun){
	var jiesong_info_tips = document.getElementById('travel_companion_tips_'+tips_id_mun);
	if(jiesong_info_tips!=null){
		if(int_num>0){
			jiesong_info_tips.style.display="";
		}else{
			jiesong_info_tips.style.display="none";
		}
	}
}

function display_filed(form_id,class_key,show_hideen){
	var form = document.getElementById(form_id);
	for(i=0; i<form.length; i++){
		if(form.elements[i].className.indexOf(class_key)>-1){
			if(show_hideen=='show'){
				form.elements[i].style.display = "";
				document.getElementById(form.elements[i].name+'_label').style.display = "none";
			}else{
				form.elements[i].style.display = "none";
				document.getElementById(form.elements[i].name+'_label').style.display = "";
			}
		}
	}
}

function submit_travel_companion_app(t_companion_id){
	var form = document.getElementById('travel_companion_app_form');
	/* 检查 */
	var error = false;
	var error_sms = '';
	if(form.elements['tca_cn_name'].value.length<1){
		error = true;
		error_sms += "* " + '<?php echo db_to_html('姓名 不能为空！');?>'+"\n\n";
	}
	if(form.elements['tca_en_name'].value.length<1){
		error = true;
		error_sms += "* " + '<?php echo db_to_html('英文名 不能为空！');?>'+"\n\n";
	}
	if(form.elements['email_address'].value.length<1){
		error = true;
		error_sms += "* " + '<?php echo db_to_html('邮箱 不能为空！');?>'+"\n\n";
	}
	if(form.elements['tca_people_num'].value<1){
		error = true;
		error_sms += "* " + '<?php echo db_to_html('人数 不能为空！');?>'+"\n\n";
	}
	if(form.elements['tca_content'].value<1 || form.elements['tca_content'].value==form.elements['tca_content'].title){
		error = true;
		error_sms += "* " + '<?php echo db_to_html('留言 ');?>'+ form.elements['tca_content'].title +"\n\n";
	}
	
	if(error == true){
		alert(error_sms);
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_travel_companion_app.php','action=process&t_companion_id='.(int)$t_companion_id)) ?>");
	var form_id = 'travel_companion_app_form';
	var success_msm = "";
	var success_go_to = "";
	ajax_post_submit(url,form_id,success_msm,success_go_to);
}

/* 设置同意、取消、拒绝申请 */
function t_verify_action(tca_id, agre_or_close, skip_check, action_exc){
	/* agre_or_close有值是是取消，skip_check为true时是跳过检查同意人数，当action_exc为refusal_verify是拒绝申请 */
	if(tca_id<1){
		alert('<?= db_to_html('不存在的tca_id')?>'+tca_id);
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_my_travel_companion.php','action=agre_verify')) ?>");
	if(typeof(agre_or_close)!='undefined' && agre_or_close =='close'){		/* 取消同意 */
		url += "&close_agre=1";
	}else if(typeof(agre_or_close)!='undefined' && action_exc =='refusal_verify'){	/* 拒绝申请 */
		url += "&action_exc=refusal_verify";
	}else if(typeof(skip_check)=='undefined' || skip_check !='true'){	/* 人数检查 */
		url += "&check_per=true";
	}
        var sms_obj = document.getElementById("close_agre_refusal_pop")
        var sms_content = sms_obj.elements['text_content'].value
	
	url += "&tca_id="+tca_id;
        url +="&sms_content="+sms_content;
	var success_msm = "";
	var success_go_to = "";
	ajax_get_submit(url,success_msm,success_go_to);
}

/* 设置同意或取消申请结伴时的弹出层信息 */
function agre_verify(tca_id, agre_or_close, skip_check, action_exc){
	  var General_Notes_Content = document.getElementById("GeneralNotesContent");
	  var H3 = "<?php echo db_to_html("给他留言");?>";
	  var ActionReason = "<?php echo db_to_html("请输入留言消息：");?>";
	  var agre_or_close_val = "";
	  var skip_check_val = "";
		if(typeof(skip_check)!='undefined' && skip_check =='true'){
			skip_check_val = skip_check;
		}
	  var action_exc_val = "";
	  if(typeof(agre_or_close)!='undefined' && agre_or_close =='close'){	
	  	var H3 = "<?php echo db_to_html("取消理由");?>";
		ActionReason = "<?php echo db_to_html("请输入取消理由：");?>";
		agre_or_close_val = agre_or_close;
	  }
	  if(typeof(action_exc)!='undefined' && action_exc =='refusal_verify'){	
	  	var H3 = "<?php echo db_to_html("拒绝理由");?>";
		ActionReason = "<?php echo db_to_html("请输入拒绝理由：");?>";
		action_exc_val = action_exc;
	  }
	  var TextareaField = '<?php echo tep_draw_textarea_field('text_content','',50,5,'',' onmouseout="check_input_string_num(this, 100, &quot;tpl_carp_sms_info&quot;)" onkeyup="check_input_string_num(this, 100, &quot;tpl_carp_sms_info&quot;)" onkeydown="check_input_string_num(this, 100, &quot;tpl_carp_sms_info&quot;)" ');?>';
	  <?php 
		$tpl_content = file_get_contents(DIR_FS_CONTENT . 'html_tpl/'.'close_agre_refusal_pop.tpl.html');
		$tpl_content = preg_replace('/'.preg_quote('<!--','/').'(.+)'.preg_quote('-->','/').'/','',$tpl_content);
		$tpl_content = db_to_html($tpl_content);
		
		echo 'var tpl_code ="'.preg_replace('/[[:space:]]+/',' ',addslashes($tpl_content)).'"; ';
		echo 'tpl_code = tpl_code.replace("{H3}",H3); ';
		echo 'tpl_code = tpl_code.replace("{ActionReason}",ActionReason); ';
		echo 'tpl_code = tpl_code.replace("{TextareaField}",TextareaField); ';
		echo 'General_Notes_Content.innerHTML = tpl_code;'
	  ?>
	  var form_ = document.getElementById("close_agre_refusal_pop");
	  form_.elements['tca_id'].value = tca_id;
	  form_.elements['skip_check'].value = skip_check_val;
	  form_.elements['agre_or_close'].value = agre_or_close_val;
	  form_.elements['action_exc'].value = action_exc_val;
	  
	  showDiv("GeneralNotes");
	  /* t_verify_action(tca_id, agre_or_close, skip_check); */
}

/* 设置拒绝申请结伴时的弹出层信息 */
function RefusalVerify(tca_id){
	agre_verify(tca_id, "", "", "refusal_verify");
	/* t_verify_action(tca_id, "", "", "refusal_verify"); */
}

/* 提交拒绝、同意和取消弹出层信息 */
function Submit_close_agre_refusal_pop(obj){
	if(obj.elements['tca_id'].value=="" || obj.elements['tca_id'].value<1){ alert("no tca_id"); return false;}
	if(obj.elements['text_content'].value=="" || obj.elements['text_content'].value.replace(/ /,'').length<1){ alert("<?= db_to_html("请输入留言消息！")?>"); return false;}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_my_travel_companion.php','action=write_pop_sms')) ?>");
	var replace_id = "";
	var form_id = obj.id;
	var success_msm = "";
	var success_go_to = "";
	ajax_post_submit(url,form_id,success_msm,success_go_to, replace_id);
}

/* 设置过期贴,将不能再申请结伴。 */
function set_has_expired(t_companion_id,num){
	if(t_companion_id<1){
		alert('<?= db_to_html('不存在的t_companion_id')?>'+t_companion_id);
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_my_travel_companion.php','action=set_has_expired')) ?>");
	url += "&t_companion_id="+t_companion_id;
	url += "&has_expired="+num;
	var success_msm = "";
	var success_go_to = "";
	ajax_get_submit(url,success_msm,success_go_to);
}
/* 去订购 */
function go_to_booking(p_id){
	if(p_id<1){
		alert('<?= db_to_html('不存在的团')?>'+p_id);
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo(FILENAME_PRODUCT_INFO, 'products_id=')) ?>")+p_id;
	location = url;
}

/* 只显示我发送或我收到的信息 */
function only_show(key,obj){
	var mo_d = jQuery('#right_button');
	
	for(j=0; j<mo_d.children().size(); j++){
		mo_d.children()[j].innerHTML = mo_d.children()[j].innerHTML.replace(/(\<b\>)|(\<\/b\>)/,'');
	}
	obj.innerHTML = "<b>"+obj.innerHTML+"</b>";
	var ul = document.getElementById('ul_sms');	
	var li = ul.getElementsByTagName('li');
	for(i=0; i<li.length; i++ ){
		if((key=="sent" || key=="receive") && li[i].title!=""){
			li[i].style.display = 'none';
			/* 隐藏其下面的checkbox */
			var chbox = li[i].getElementsByTagName('input');
			for(j=0; j<chbox.length; j++){
				if(chbox[j].type="checkbox"){ chbox[j].checked = false; chbox[j].style.display='none'; }
			}
		}
		if(li[i].title == key || key ==""){
			li[i].style.display = '';
			/* 显示其下面的checkbox */
			var chbox = li[i].getElementsByTagName('input');
			for(j=0; j<chbox.length; j++){
				if(chbox[j].type="checkbox"){ chbox[j].checked = false; chbox[j].style.display=''; }
			}
		}
	}
}
/* 选择全部复选框 */
function select_all_checkboxs(from_id,boxname, header){
	var from = document.getElementById(from_id);
	for(i=0; i<from.length; i++){
		/* alert(from.elements[i].type); */
		if(from.elements[i].name==boxname && from.elements[i].type =="checkbox" && from.elements[i].style.display!="none"){
			from.elements[i].checked = header.checked;
		}
	}
}
/* 删除短信 */
function remove_sms(){
	var from = document.getElementById('my_msm_form');
	var have_sel = false;
	for(i=0; i<from.length; i++){
		if(from.elements[i].name=="sis_ids[]" && from.elements[i].checked == true){
			have_sel = true; break;
		}
	}
	if(have_sel==false){ alert('<?= db_to_html('请选择要删除的信息！');?>'); return false;}
	if(confirm('<?= db_to_html('您真的要删除这些信息吗？')?>')){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_my_travel_companion.php','action=remove_sms')) ?>");
		var form_id = from.id;
		var success_msm = "";
		var success_go_to = "";
		ajax_post_submit(url,form_id,success_msm,success_go_to);
	}
}
/* 标记为已读 */
function set_has_been_read(){
	var from = document.getElementById('my_msm_form');
	var have_sel = false;
	for(i=0; i<from.length; i++){
		if(from.elements[i].name=="sis_ids[]" && from.elements[i].checked == true){
			have_sel = true; break;
		}
	}
	if(have_sel==false){ alert('<?= db_to_html('请选择要标记的信息！');?>'); return false;}

	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_my_travel_companion.php','action=set_has_been_read')) ?>");
	var form_id = from.id;
	var success_msm = "";
	var success_go_to = "";
	ajax_post_submit(url,form_id,success_msm,success_go_to);
}

/* 显示所有内容 */
function show_title(obj,sis_id){
	var tmp_var = obj.innerHTML;
	obj.innerHTML = obj.title;
	obj.title = tmp_var;
	obj.style.fontWeight = "normal";
	var icons = document.getElementById('icons_'+sis_id);
	if(icons.src.indexOf('News_read.gif') == -1 && icons.src.indexOf('News_send.gif') == -1 ){
		submit_action = true;
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_my_travel_companion.php','action=show_title')) ?>")+"&sis_id="+sis_id;
		var success_msm = "";
		var success_go_to = "";
		ajax_get_submit(url,success_msm,success_go_to);
	}
}

/* 显示ul下的所有li */
function show_all_li(links_obj, ul_id){
	var ul = document.getElementById(ul_id);
	var li = ul.getElementsByTagName('li');
	for(i=0; i<li.length; i++){
		li[i].style.display = "";
	}
	links_obj.style.display = "none";
}

/* 取消、删除申请 */
function remove_app(app_id){
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_my_travel_companion.php','action=remove_app')) ?>")+"&tca_id="+app_id;
	var success_msm = "";
	var success_go_to = "";
	ajax_get_submit(url,success_msm,success_go_to);
}

/* 显示或编辑账户信息 */
function edit_or_show_account(){
	var show = document.getElementById('show_info');
	var edit = document.getElementById('edit_info');
	if(edit.style.display==""){
		show.style.display = "";
		edit.style.display = "none";
	}else{
		show.style.display = "none";
		edit.style.display = "";
	}
}
/* 提交修改账户信息 */
function submit_account_edit(from){
	var from = from;
	var error = false;
	var error_sms = '';
	if(from.elements['firstname'].value.length < <?= ENTRY_FIRST_NAME_MIN_LENGTH?>){
		error = true;
		error_sms += "<?= ENTRY_FIRST_NAME_ERROR?>"+"\t\n";
	}
        /*
	if(from.elements['lastname'].value.length < <?= ENTRY_LAST_NAME_MIN_LENGTH?>){
		error = true;
		error_sms += "<?= ENTRY_LAST_NAME_ERROR?>"+"\t\n";
	}*/
	
	if(error==true){ alert(error_sms); return false; }
	
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_my_travel_companion.php','action=submit_account_edit')) ?>");
	var form_id = from.id;
	var success_msm = "";
	var success_go_to = "";
	ajax_post_submit(url,form_id,success_msm,success_go_to);
}

	

function check_login(travel_companion_tips_id,isLogin){
    if(isLogin==false){
        showDiv(travel_companion_tips_id);
    }
}

function travel_companion_search(){
    var search_key = document.getElementById('tc_keyword').value;
    if(search_key=='<?php echo db_to_html('请输入关键字，进一步搜索');?>'){
        search_key='';
    }
    var form_obj = document.getElementById('form_search');
    form_obj.elements['tc_keyword'].value = search_key;
    form_obj.submit();

}

/* 格式化日期 */
function format_date(obj, type){
	if(obj.value.length<1 || type.length<1){ return false;}
	var old_val = obj.value;
	switch(type){
		case 'Y':
			if(old_val.length==2){
				if(old_val>="00" && old_val<"10"){ obj.value = "20"+old_val;}
				else{ obj.value = "19"+old_val;}
			}
		break;
		case 'M':
			if(old_val>12){ obj.value = "";}
			if(obj.value.length==1){
				 obj.value = "0"+obj.value;
			}
		break;
		case 'D':
			if(old_val>31){ obj.value = "";}
			if(obj.value.length==1){
				 obj.value = "0"+obj.value;
			}
		break;
	}
	if(obj.value.match(/^\d+$/)==null){
		obj.value = "";
	}
}

function remove_travel_comments(comments_id){
     if(confirm("<?php echo db_to_html("您确定要删除这条评论吗？")?>")){
		/* var aid = "#comments_"+comments_id; */
		/* $(aid).fadeOut(1000); */
		var url =url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_travel_notes_detail.php','action=remove_comments')) ?>");
		url += "&comments_id="+comments_id;
                url += "&travel_notes_id=<?php echo (int)$_GET['travel_notes_id'];?>";
		var success_msm = "";
		var success_go_to = "";
		ajax_get_submit(url,success_msm,success_go_to);
     }
}
function edite_travel_notes(){
        var form_id = document.getElementById("EditPotoForm");
	var error_msn = '';
	var error = false;
        
	for(i=0; i<form_id.elements.length; i++){
			if((form_id.elements[i].value==form_id.elements[i].title || form_id.elements[i].value.leng<2) && form_id.elements[i].title!=""){
				error = true;
				error_msn += "* "+ form_id.elements[i].title+"\t\n";
			}
        }
	if(error==true){
		alert(error_msn);
		return false;
        }
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_travel_notes_detail.php','action=update')) ?>");
                var form_id = "EditPotoForm";
		var success_msm = "";
		var success_go_to = "";
		ajax_post_submit(url,form_id,success_msm,success_go_to);
                return false;
                
}
function edite_travel_notes_title(){
        var form_id = document.getElementById("EditTravelForm");
	var error_msn = '';
	var error = false;

	for(i=0; i<form_id.elements.length; i++){
			if((form_id.elements[i].value==form_id.elements[i].title || form_id.elements[i].value.leng<2) && form_id.elements[i].title!=""){
				error = true;
				error_msn += "* "+ form_id.elements[i].title+"\t\n";
			}
        }
	if(error==true){
		alert(error_msn);
		return false;
        }
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_travel_notes_detail.php','action=edite_travel_title')) ?>");
                 var form_id = "EditTravelForm";
		var success_msm = "";
		var success_go_to = "";
		ajax_post_submit(url,form_id,success_msm,success_go_to);
                return false;

}

function show_edit_travle(form_id,img_src){
    var fForm = document.getElementById(form_id);
    var tForm = document.getElementById("EditPotoForm");
    document.getElementById("photo_box_0").setAttribute('src',img_src);
    tForm.elements['photo_name'].value =fForm.elements['photo_name'].value;
    tForm.elements['photo_title'].value =fForm.elements['photo_title'].value;
    tForm.elements['photo_content'].value =fForm.elements['photo_content'].value;
    tForm.elements['update_photo_action'].value =fForm.elements['update_photo_action'].value;
    tForm.elements['update_photo_action'].value =fForm.elements['update_photo_action'].value;
    tForm.elements['photo_id'].value =fForm.elements['photo_id'].value;

    /* tForm.elements['photo_box'].setAttribute('src',img_src); */
    
    showDiv("EditPotoDiv");

}
function show_Div(img_src){
    document.getElementById("img_customers_face").setAttribute('src',img_src);
    showDiv("travel_companion_tips_2064");

}


jQuery(document).ready(function(){
	<?php //结伴同游通知关闭?>
	jQuery("#NewVersionNoteButton").click( function(){
		jQuery("#NewVersionNote").slideUp(500);
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_my_travel_companion.php','action=HiddenNewVersionNote')) ?>");
		var success_msm = "";
		var success_go_to = "";
		ajax_get_submit(url,success_msm,success_go_to);
		return false;
	});
});

<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>

