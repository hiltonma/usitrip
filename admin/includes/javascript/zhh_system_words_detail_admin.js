function submit_check(obj){
	var obj_array = new Array("dirs_id", "r_groups_id", "rw_groups_id", "rwd_groups_id"); 
	for(j=0; j<obj_array.length; j++){
	  var Class_Box = document.getElementById(obj_array[j]);
	  for(i=0; i<Class_Box.length; i++){
		  Class_Box.options[i].selected = true;
	  }
	}
	//obj.submit();
}

function add_to_class(from_id, to_id){
	var All_Class_Box = document.getElementById(from_id);
	for(j=0; j<All_Class_Box.length; j++){
	  var Class_Box = document.getElementById(to_id);
	  var s = Class_Box.length;
	  if(All_Class_Box.options[j].selected == true){
		var ready_add_value = All_Class_Box.options[j].value;
		var ready_add_text = All_Class_Box.options[j].text;
		var add_action = true;
		for(i=0; i<Class_Box.length; i++){
			Class_Box.options[i].selected = true;
			if(ready_add_value == Class_Box.options[i].value){
				add_action = false; 
			}
		}
		if(add_action==true && ready_add_value>0){
			Class_Box.options[s] = new Option(ready_add_text, ready_add_value);
			Class_Box.options[s].selected = true;
		}
	  }
	}
}

function move_form_categories(box_id){
	var Class_Box = document.getElementById(box_id);
	for(i=0; i<Class_Box.length; i++){
		if( Class_Box.options[i].selected ){
			Class_Box.remove(i);
			break;
		}
	}

}

function add_file_upload_box(target_id){
	var jq_id = "#"+ target_id;
	$(jq_id).append('<div><input name="files[]" type="file" size="30" /> <a href="JavaScript:void(0)" onClick="div_remove(this);"><img src="images/icons/u20.png" border="0" /></a></div>');
}
function div_remove(obj){
	obj.parentNode.parentNode.removeChild(obj.parentNode);
}

//处理主类别的菜单动作
function select_onchange(obj){
	$("#main_dir_id").val(obj.value);
	var select_obj = obj.parentNode.getElementsByTagName("select");
	var max_n = select_obj.length;
	for(var N=0; N<max_n; N++){
		if(typeof(select_obj[N])!= "undefined" && select_obj[N].name.indexOf(obj.name)>-1 && select_obj[N].name!=obj.name){
			var now_obj = select_obj[N];
			now_obj.parentNode.removeChild(now_obj);
			N--;
		}
	}
	var add_selects = $.get("ajax_zhh_system_words_detail_admin.php?ajax=true&dir_id="+obj.value+"&FirstDirsIds="+obj.name, '', function(data){
		$("#first_dirs_string").append(data);
	});
	//将name长于当前选择的菜单的name的菜单项全部去除。
}


jQuery(document).ready(function(){
	//继续添加新文章按钮
	$("#Continue_to_add").click(function(){
		window.location="zhh_system_words_detail_admin.php?dir_id="+this.title;
	});
});