<?php
    $js_idx=0;
	$js_content='
	<script type="text/javascript"><!--
    var cats1 = new Array();'."\n";

	
	//取级别1
    $js_sql = tep_db_query('SELECT *  FROM `categories` c, `categories_description` cd WHERE c.categories_id=cd.categories_id Group By c.categories_id ORDER BY c.categories_id ASC');	
    $js_row = tep_db_fetch_array($js_sql);
	
    $js_list = array();
    //$js_idx=0;
    
    do{
		$js_content.= "    cats1['".$js_idx++."'] = new Array('".$js_row['categories_id']."','".$js_row['parent_id']."','".ereg_replace("'", "\'", $js_row['categories_name'])."');\n";
    }while( $js_row = tep_db_fetch_array($js_sql));

$js_content.= 'var grp_name1 = "categories_id0,categories_id1,categories_id2".split(",");';
$js_content.= '
function getObject1(name) {
	obj = document.getElementById(name);
	return obj;
}

function SelChange1(obj) {
    var name = obj.name;
    var value = obj.value;
    var begi = false;
    var pid = obj.value;

    for(var u=0;u < cats1.length;u++)
    {
        if(pid == cats1[u][2])
        {
             pid = cats1[u][0];
             break;
        }
    }

    for (var i=0; i < grp_name1.length; i++)
    {
        if (name == grp_name1[i])
        {
            begi = true;
            continue;
        }
        if (begi == true)
        {
            var obj1 = getObject1(grp_name1[i])
            for (var j = obj1.length - 1; j >= 0; --j) 
		obj1.remove(j);
            obj1.value = "";
            
            var s=0;
            for(var m=0; m < cats1.length; m++)
            {
                if(m==0 && cats1[m][0]>0){ obj1.options[s++] = new Option("请选择子类别", ""); }
				if(cats1[m][1] == pid && pid > 0 ) 
                {
                    
					//obj1.options[s++] = new Option(cats1[m][2], cats1[m][2]);
                    obj1.options[s++] = new Option(cats1[m][2], cats1[m][0]);
                    if(s==1)
                    {
                        obj1.options[s-1].selected = true;
                    }
                    else
                    {
                        obj1.options[s-1].selected = false;
                    }
                }
            }
            //SelChange1(obj1);
            for (var q=i+1; q < grp_name1.length; q++)
            {
            	var obj1 = getObject1(grp_name1[q])
            	for (var w = obj1.length - 1; w >= 0; --w) 
				obj1.remove(w);
            	obj1.value = "";
            }
			if(getObject1(grp_name1[i]).length < 2){
				getObject1(grp_name1[i]).style.display="none";
			}else{
				getObject1(grp_name1[i]).style.display="";
			}
		return;
        }
    }
}
//--></script>
';

echo $js_content;
?> 