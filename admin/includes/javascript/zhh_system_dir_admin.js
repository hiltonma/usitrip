function submit_check(obj){
	var obj_array = new Array("r_groups_id", "rw_groups_id", "rwd_groups_id"); 
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

function check_add_subdir(Form){
	if(Form.elements['add_class_name'].value.length<2){
		alert("请先输入目录名称再提交！");
	}else{
		Form.submit();
	}
}