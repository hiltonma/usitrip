<?php
  require('includes/application_top.php');

  $breadcrumb->add(db_to_html('酒店预订'), tep_href_link('booking.php'));

  $content = 'booking';
  $off_corner_tl_tr = true;
	//seo信息
	$the_title = db_to_html('usitrip走四方旅游网-华人旅行社_出境旅游酒店预订_美国酒店预订_加拿大酒店攻略');
	$the_desc = db_to_html('Usitrip走四方旅游网身为最知名华人旅行社,为全球华人提供出境旅游酒店预订,酒店住宿攻略,酒店优惠信息,覆盖美国,加拿大,欧洲,法国,英国,意大利,德国,澳洲等出国旅游国家,包括华盛顿,纽约,费城,拉斯维加斯,洛杉矶,盐湖城,旧金山,伦敦,悉尼等热门旅行城市');
	$the_key_words = db_to_html('出境旅游酒店预订,美国酒店预订,加拿大酒店信息,酒店优惠打折');
	//seo信息 end

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');


?>