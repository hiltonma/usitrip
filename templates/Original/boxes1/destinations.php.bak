<div class="title titleBig">
        <b></b><span></span>
        <h3>
		<?php
		if($isHotels){
			echo db_to_html("酒店所在城市");
		}elseif($isCruises){
			echo db_to_html("邮轮全航线行程");
		}else{
			echo db_to_html(preg_replace('/ .+/','',tep_get_category_name($cPathOnly))); 
		}
		?>
		</h3>
    </div>
<?php
if($cPathOnly=='24' || $cPathOnly=='25' || $cPathOnly=='54' || $cPathOnly=='157' || $cPathOnly=='33' || $cPathOnly=='267' ){	//只有顶级目录为西海岸、东海岸、加拿大、欧洲或邮轮267才有列表
	$parent_categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$cPathOnly . "' AND categories_feature_status = '0' AND categories_status='1' Order By sort_order ASC, categories_id DESC ");
	$sub_categories_array =array();
	while($row_parent_category = tep_db_fetch_array($parent_categories_query)){
		$sub_categories_array[] = $row_parent_category['categories_id'];
	}
	
	$palceTitle = '按旅游景点查看';
	if($isCruises){
		$palceTitle = '按目的地查看';
	}
	
	if(count($sub_categories_array) ){
		$_mnu = $cat_mnu_sel;
		if($cat_mnu_sel!="tours" && $cat_mnu_sel!="vcpackages"){
			$_mnu = 'vcpackages';
		}
?>
	<dl class="place">
	 <dt><?php echo db_to_html($palceTitle);?></dt>
			<?php
			foreach($sub_categories_array as $val){
				$li_class = $val == $current_category_id?'selected':'';
				$href = tep_href_link(FILENAME_DEFAULT,'cPath='.$val.'&mnu='.$_mnu);
				$name = db_to_html(tep_get_category_name($val));
				echo '<dd><a href="'.$href.'" class="'.$li_class.'" val="'.$val.'">'.$name.'</a></dd>';
			}	
			?>
	</dl>
<?php
	}
}
?>