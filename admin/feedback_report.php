<?php
/*
  $Id: admin_members.php,v 1.2 2004/03/12 18:33:12 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
  header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
  header( "Cache-Control: no-cache, must-revalidate" );
  header( "Pragma: no-cache" );

require('includes/application_top.php');
 $sql = tep_db_query('select count(*) as total from feedback ');
 $rows_total = tep_db_result($sql,"0","total");
if($rows_total=='0'){
	die('婃拸諦誧脤戙毀嚏暮翹ㄐ');
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>覃脤賦彆ㄐ</title>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#B1C3D9">
  <tr>
    <td valign="top" bgcolor="#FFFFFF">
	
	<?php
	$age_sql = tep_db_query('select tours_age from feedback ');
	$age_18 = 0;
	$age_18_30 = 0;
	$age_31_45 = 0;
	$age_45_60 = 0;
	$age_60 = 0;
	while($age_rows = tep_db_fetch_array($age_sql)){
		if($age_rows['tours_age']=='18'){$age_18++;}
		if($age_rows['tours_age']=='18-30'){$age_18_30++;}
		if($age_rows['tours_age']=='31-45'){$age_31_45++;}
		if($age_rows['tours_age']=='45-60'){$age_45_60++;}
		if($age_rows['tours_age']=='60'){$age_60++;}
	}
	
	$max_val_age = max($age_18,$age_18_30,$age_31_45,$age_45_60,$age_60);
	
	$age_18_bfb = ($age_18/$rows_total);
	$age_18_h = intval(200 * $age_18_bfb);
	$age_img_18_name = 'report_b.gif';
	if($age_18==$max_val_age){
		$age_img_18_name = 'report_r.gif';
	}
	
	$age_18_30_bfb = ($age_18_30/$rows_total);
	$age_18_30_h = intval(200 * $age_18_30_bfb);
	$age_img_18_30_name = 'report_b.gif';
	if($age_18_30==$max_val_age){
		$age_img_18_30_name = 'report_r.gif';
	}
	
	$age_31_45_bfb = ($age_31_45/$rows_total);
	$age_31_45_h = intval(200 * $age_31_45_bfb);
	$age_img_31_45_name = 'report_b.gif';
	if($age_31_45==$max_val_age){
		$age_img_31_45_name = 'report_r.gif';
	}
	
	$age_45_60_bfb = ($age_45_60/$rows_total);
	$age_45_60_h = intval(200 * $age_45_60_bfb);
	$age_img_45_60_name = 'report_b.gif';
	if($age_45_60==$max_val_age){
		$age_img_45_60_name = 'report_r.gif';
	}
	
	$age_60_bfb = ($age_60/$rows_total);
	$age_60_h = intval(200 * $age_60_bfb);
	$age_img_60_name = 'report_b.gif';
	if($age_60==$max_val_age){
		$age_img_60_name = 'report_r.gif';
	}
	?>
	<table border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td height="25" colspan="5"><strong>1﹜蠟腔爛鍵</strong></td>
        </tr>
      <tr>
        <td height="200" align="left" valign="bottom">
		<?php echo $age_18 ?><br>
          <img src="images/icons/<?php echo $age_img_18_name ?>" width="20" height="<?php echo $age_18_h?>"></td>
        <td align="left" valign="bottom">
		<?php echo $age_18_30 ?><br>
		<img src="images/icons/<?php echo $age_img_18_30_name ?>" width="20" height="<?php echo $age_18_30_h?>"></td>
        <td align="left" valign="bottom">
		<?php echo $age_31_45 ?><br>
		<img src="images/icons/<?php echo $age_img_31_45_name ?>" width="20" height="<?php echo $age_31_45_h?>"></td>
        <td align="left" valign="bottom">
		<?php echo $age_45_60 ?><br>
		<img src="images/icons/<?php echo $age_img_45_60_name ?>" width="20" height="<?php echo $age_45_60_h?>"></td>
        <td align="left" valign="bottom">
		<?php echo $age_60 ?><br>
		<img src="images/icons/<?php echo $age_img_60_name ?>" width="20" height="<?php echo $age_60_h?>"></td>
      </tr>
      <tr>
        <td align="left">18呡眕狟</td>
        <td align="left">18-30</td>
        <td align="left">31-45</td>
        <td align="left">45-60</td>
        <td align="left">60眕奻</td>
      </tr>
    </table></td>
    <td valign="top" bgcolor="#FFFFFF">
	
	<?php
	$gender_sql = tep_db_query('select tours_gender from feedback ');
	$gender_f = 0;
	$gender_m = 0;
	while($gender_rows = tep_db_fetch_array($gender_sql)){
		if($gender_rows['tours_gender']=='f'){$gender_f++;}
		if($gender_rows['tours_gender']=='m'){$gender_m++;}
	}
	
	$max_val_gender = max($gender_f,$gender_m);
	
	$gender_f_bfb = ($gender_f/$rows_total);
	$gender_f_h = intval(200 * $gender_f_bfb);
	$gender_img_f_name = 'report_b.gif';
	if($gender_f==$max_val_gender){
		$gender_img_f_name = 'report_r.gif';
	}
	
	$gender_m_bfb = ($gender_m/$rows_total);
	$gender_m_h = intval(200 * $gender_m_bfb);
	$gender_img_m_name = 'report_b.gif';
	if($gender_m==$max_val_gender){
		$gender_img_m_name = 'report_r.gif';
	}
	
	?>
	<table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td height="25" colspan="2"><strong>2﹜蠟腔俶梗</strong></td>
        </tr>
      <tr>
        <td height="200" align="left" valign="bottom">
		<?php echo $gender_m ?><br>
          <img src="images/icons/<?php echo $gender_img_m_name ?>" width="20" height="<?php echo $gender_m_h?>"></td>
        <td align="left" valign="bottom">
		<?php echo $gender_f ?><br>
		<img src="images/icons/<?php echo $gender_img_f_name ?>" width="20" height="<?php echo $gender_f_h?>"></td>
        </tr>
      <tr>
        <td align="left">鹹</td>
        <td align="left">躓</td>
      </tr>
    </table></td>
    <td valign="top" bgcolor="#FFFFFF">
	<?php
	$job_sql = tep_db_query('select tours_job from feedback ');
	
	$job_array = array(0=>'赻蚕眥珛',1=>'誑薊厙',2=>'IT',3=>'奪燴',4=>'悝汜',5=>'儂迮',6=>'趙馱',7=>'夔埭',8=>'瓟谿',9=>'諒郤堤唳',10=>'む坻');
	foreach($job_array as $key => $val){
		$job_[$key] = 0;	//var
	}
	
	while($job_rows = tep_db_fetch_array($job_sql)){
		foreach($job_array as $key => $val ){
			if($job_rows['tours_job']==$key){$job_[$key]++;}
		}
	}
	
	$max_val_job = max($job_[0],$job_[1],$job_[2],$job_[3],$job_[4],$job_[5],$job_[6],$job_[7],$job_[8],$job_[9],$job_[10]);
	
	foreach($job_array as $key => $val ){
		$job_bfb_[$key] = ($job_[$key]/$rows_total);
		$job_h_[$key] = intval(200 * $job_bfb_[$key]);
		$job_img_name_[$key] = 'report_b.gif';
		if($job_[$key]==$max_val_job){
			$job_img_name_[$key] = 'report_r.gif';
			
		}
	}
	
	?>	
	
	<table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td height="25" colspan="2"><strong>3﹜蠟腔眥珛</strong></td>
        </tr>
      <tr>
    
	<?php foreach($job_array as $key => $val ){?>   
		<td height="200" align="left" valign="bottom">
		<?php echo $job_[$key] ?><br>
          <img src="images/icons/<?php echo $job_img_name_[$key] ?>" width="20" height="<?php echo $job_h_[$key]?>"></td>
	<?php }?>
        </tr>
      <tr>
	
	<?php foreach($job_array as $key => $val ){?>   
	    <td align="left"><?php echo $val?></td>
	<?php }?>
      </tr>
    </table>	</td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#FFFFFF">
	<?php
	$like_sql = tep_db_query('select tours_like from feedback ');
	
	$like_array = array(0=>'赻蚕俴',1=>'赻毅蚔',2=>'躲芶',3=>'眻汔儂蚔',4=>'滄儂蚔',5=>'蚔謫',6=>'傑庈蚔',7=>'赻�遝偎�',8=>'む坻');
	foreach($like_array as $key => $val){
		$like_[$key] = 0;	//var
	}
	
	while($like_rows = tep_db_fetch_array($like_sql)){
		foreach($like_array as $key => $val ){
			if($like_rows['tours_like']==$key){$like_[$key]++;}
		}
	}
	
	$max_val_like = max($like_[0],$like_[1],$like_[2],$like_[3],$like_[4],$like_[5],$like_[6],$like_[7],$like_[8]);
	
	foreach($like_array as $key => $val ){
		$like_bfb_[$key] = ($like_[$key]/$rows_total);
		$like_h_[$key] = intval(200 * $like_bfb_[$key]);
		$like_img_name_[$key] = 'report_b.gif';
		if($like_[$key]==$max_val_like){
			$like_img_name_[$key] = 'report_r.gif';
			
		}
	}
	
	?>	
	
	<table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td height="25" colspan="2"><strong>4﹜蠟腔藏蚔乾疑</strong></td>
        </tr>
      <tr>
    
	<?php foreach($like_array as $key => $val ){?>   
		<td height="200" align="left" valign="bottom">
		<?php echo $like_[$key] ?><br>
          <img src="images/icons/<?php echo $like_img_name_[$key] ?>" width="20" height="<?php echo $like_h_[$key]?>"></td>
	<?php }?>
        </tr>
      <tr>
	
	<?php foreach($like_array as $key => $val ){?>   
	    <td align="left"><?php echo $val?></td>
	<?php }?>
      </tr>
    </table>	</td>
    <td valign="top" bgcolor="#FFFFFF">
	<?php
	$from_sql = tep_db_query('select tours_from from feedback ');
	
	$from_array = array(0=>'google',1=>'baidu',2=>'yahoo',3=>'bbs',4=>'friend',5=>'other');
	foreach($from_array as $key => $val){
		$from_[$key] = 0;	//var
	}
	
	while($from_rows = tep_db_fetch_array($from_sql)){
		foreach($from_array as $key => $val ){
			if($from_rows['tours_from']==$val){$from_[$key]++;}
		}
	}
	
	$max_val_from = max($from_[0],$from_[1],$from_[2],$from_[3],$from_[4],$from_[5]);
	
	foreach($from_array as $key => $val ){
		$from_bfb_[$key] = ($from_[$key]/$rows_total);
		$from_h_[$key] = intval(200 * $from_bfb_[$key]);
		$from_img_name_[$key] = 'report_b.gif';
		if($from_[$key]==$max_val_from){
			$from_img_name_[$key] = 'report_r.gif';
			
		}
	}
	
	?>	
	
	<table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td height="25" colspan="2"><strong>5﹜蠟岆籵徹睡笱源宒眭耋掛厙桴腔</strong></td>
        </tr>
      <tr>
    
	<?php foreach($from_array as $key => $val ){?>   
		<td height="200" align="left" valign="bottom">
		<?php echo $from_[$key] ?><br>
          <img src="images/icons/<?php echo $from_img_name_[$key] ?>" width="20" height="<?php echo $from_h_[$key]?>"></td>
	<?php }?>
        </tr>
      <tr>
	
	<?php foreach($from_array as $key => $val ){?>   
	    <td align="left"><?php echo $val?></td>
	<?php }?>
      </tr>
    </table>	</td>
    <td valign="top" bgcolor="#FFFFFF">
	<?php
	$ServicesEval_sql = tep_db_query('select ServicesEval from feedback ');
	
	$ServicesEval_array = array(0=>'蚥凅',1=>'謎疑',2=>'笢脹',3=>'摯跡',4=>'船',5=>'竭船');
	foreach($ServicesEval_array as $key => $val){
		$ServicesEval_[$key] = 0;	//var
	}
	
	while($ServicesEval_rows = tep_db_fetch_array($ServicesEval_sql)){
		foreach($ServicesEval_array as $key => $val ){
			if($ServicesEval_rows['ServicesEval']==$key){$ServicesEval_[$key]++;}
		}
	}
	
	$max_val_ServicesEval = max($ServicesEval_[0],$ServicesEval_[1],$ServicesEval_[2],$ServicesEval_[3],$ServicesEval_[4],$ServicesEval_[5]);
	
	foreach($ServicesEval_array as $key => $val ){
		$ServicesEval_bfb_[$key] = ($ServicesEval_[$key]/$rows_total);
		$ServicesEval_h_[$key] = intval(200 * $ServicesEval_bfb_[$key]);
		$ServicesEval_img_name_[$key] = 'report_b.gif';
		if($ServicesEval_[$key]==$max_val_ServicesEval){
			$ServicesEval_img_name_[$key] = 'report_r.gif';
			
		}
	}
	
	?>	
	
	<table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td height="25" colspan="2"><strong>6﹜蠟勤掛桴督昢腔ぜ歎</strong></td>
        </tr>
      <tr>
    
	<?php foreach($ServicesEval_array as $key => $val ){?>   
		<td height="200" align="left" valign="bottom">
		<?php echo $ServicesEval_[$key] ?><br>
          <img src="images/icons/<?php echo $ServicesEval_img_name_[$key] ?>" width="20" height="<?php echo $ServicesEval_h_[$key]?>"></td>
	<?php }?>
        </tr>
      <tr>
	
	<?php foreach($ServicesEval_array as $key => $val ){?>   
	    <td align="left"><?php echo $val?></td>
	<?php }?>
      </tr>
    </table>	</td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#FFFFFF">
	<?php
	$number_sql = tep_db_query('select tours_number from feedback ');
	
	$number_array = array(0=>'0',1=>'0-1',2=>'1',3=>'2',4=>'3',5=>'4',6=>'5');
	foreach($number_array as $key => $val){
		$number_[$key] = 0;	//var
	}
	
	while($number_rows = tep_db_fetch_array($number_sql)){
		foreach($number_array as $key => $val ){
			if($number_rows['tours_number']==$val){$number_[$key]++;}
		}
	}
	
	$max_val_number = max($number_[0],$number_[1],$number_[2],$number_[3],$number_[4],$number_[5],$number_[6]);
	
	foreach($number_array as $key => $val ){
		$number_bfb_[$key] = ($number_[$key]/$rows_total);
		$number_h_[$key] = intval(200 * $number_bfb_[$key]);
		$number_img_name_[$key] = 'report_b.gif';
		if($number_[$key]==$max_val_number){
			$number_img_name_[$key] = 'report_r.gif';
			
		}
	}
	
	?>	
	
	<table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td height="25" colspan="2"><strong>7﹜蠟郔輪撓爛す歙藩爛堤俴撓棒ˋ</strong></td>
        </tr>
      <tr>
    
	<?php foreach($number_array as $key => $val ){?>   
		<td height="200" align="left" valign="bottom">
		<?php echo $number_[$key] ?><br>
          <img src="images/icons/<?php echo $number_img_name_[$key] ?>" width="20" height="<?php echo $number_h_[$key]?>"></td>
	<?php }?>
        </tr>
      <tr>
	
	<?php foreach($number_array as $key => $val ){?>   
	    <td align="left"><?php echo $val?></td>
	<?php }?>
      </tr>
    </table>	</td>
    <td valign="top" bgcolor="#FFFFFF">
	<?php
	$add_server_sql = tep_db_query('select tours_add_server from feedback ');
	
	$add_server_array = array(0=>'儂き',1=>'ワ痐',2=>'逤陬',3=>'む坻');
	foreach($add_server_array as $key => $val){
		$add_server_[$key] = 0;	//var
	}
	
	while($add_server_rows = tep_db_fetch_array($add_server_sql)){
		foreach($add_server_array as $key => $val ){
			if($add_server_rows['tours_add_server']==$key){$add_server_[$key]++;}
		}
	}
	
	$max_val_add_server = max($add_server_[0],$add_server_[1],$add_server_[2],$add_server_[3]);
	
	foreach($add_server_array as $key => $val ){
		$add_server_bfb_[$key] = ($add_server_[$key]/$rows_total);
		$add_server_h_[$key] = intval(200 * $add_server_bfb_[$key]);
		$add_server_img_name_[$key] = 'report_b.gif';
		if($add_server_[$key]==$max_val_add_server){
			$add_server_img_name_[$key] = 'report_r.gif';
			
		}
	}
	
	?>	
	
	<table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td height="25" colspan="2"><strong>8﹜蠟橇腕扂蠅剒猁崝樓闡虳源醱腔珛昢/督昢ˋ</strong></td>
        </tr>
      <tr>
    
	<?php foreach($add_server_array as $key => $val ){?>   
		<td height="200" align="left" valign="bottom">
		<?php echo $add_server_[$key] ?><br>
          <img src="images/icons/<?php echo $add_server_img_name_[$key] ?>" width="20" height="<?php echo $add_server_h_[$key]?>"></td>
	<?php }?>
        </tr>
      <tr>
	
	<?php foreach($add_server_array as $key => $val ){?>   
	    <td align="left"><?php echo $val?></td>
	<?php }?>
      </tr>
    </table>	</td>
    <td valign="top" bgcolor="#FFFFFF">
	<?php
	$i_rec_sql = tep_db_query('select tours_i_rec from feedback ');
	
	$i_rec_array = array(0=>'羶衄',1=>'袧掘芢熱',2=>'芢熱賸1弇',3=>'芢熱賸2弇',4=>'芢熱賸3弇摯眕奻');
	foreach($i_rec_array as $key => $val){
		$i_rec_[$key] = 0;	//var
	}
	
	while($i_rec_rows = tep_db_fetch_array($i_rec_sql)){
		foreach($i_rec_array as $key => $val ){
			if($i_rec_rows['tours_i_rec']==$key){$i_rec_[$key]++;}
		}
	}
	
	$max_val_i_rec = max($i_rec_[0],$i_rec_[1],$i_rec_[2],$i_rec_[3],$i_rec_[4]);
	
	foreach($i_rec_array as $key => $val ){
		$i_rec_bfb_[$key] = ($i_rec_[$key]/$rows_total);
		$i_rec_h_[$key] = intval(200 * $i_rec_bfb_[$key]);
		$i_rec_img_name_[$key] = 'report_b.gif';
		if($i_rec_[$key]==$max_val_i_rec){
			$i_rec_img_name_[$key] = 'report_r.gif';
			
		}
	}
	
	?>	
	
	<table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td height="25" colspan="2"><strong>9﹜蠟芢熱掛桴莉こ跤む坻攬衭羶ˋ</strong></td>
        </tr>
      <tr>
    
	<?php foreach($i_rec_array as $key => $val ){?>   
		<td height="200" align="left" valign="bottom">
		<?php echo $i_rec_[$key] ?><br>
          <img src="images/icons/<?php echo $i_rec_img_name_[$key] ?>" width="20" height="<?php echo $i_rec_h_[$key]?>"></td>
	<?php }?>
        </tr>
      <tr>
	
	<?php foreach($i_rec_array as $key => $val ){?>   
	    <td align="left"><?php echo $val?></td>
	<?php }?>
      </tr>
    </table>	</td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#FFFFFF">
	<?php
	$consumer_sql = tep_db_query('select tours_consumer from feedback ');
	
	$consumer_array = array(0=>'$100眕狟',1=>'$100-$500',2=>'$500-$1000',3=>'$1000眕奻');
	foreach($consumer_array as $key => $val){
		$consumer_[$key] = 0;	//var
	}
	
	while($consumer_rows = tep_db_fetch_array($consumer_sql)){
		foreach($consumer_array as $key => $val ){
			if($consumer_rows['tours_consumer']==$key){$consumer_[$key]++;}
		}
	}
	
	$max_val_consumer = max($consumer_[0],$consumer_[1],$consumer_[2],$consumer_[3]);
	
	foreach($consumer_array as $key => $val ){
		$consumer_bfb_[$key] = ($consumer_[$key]/$rows_total);
		$consumer_h_[$key] = intval(200 * $consumer_bfb_[$key]);
		$consumer_img_name_[$key] = 'report_b.gif';
		if($consumer_[$key]==$max_val_consumer){
			$consumer_img_name_[$key] = 'report_r.gif';
			
		}
	}
	
	?>	
	
	<table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td height="25" colspan="2"><strong>10﹜蠟珨啜豪煤嗣屾婓珨棒藏蚔俴最奻儸ˋ</strong></td>
        </tr>
      <tr>
    
	<?php foreach($consumer_array as $key => $val ){?>   
		<td height="200" align="left" valign="bottom">
		<?php echo $consumer_[$key] ?><br>
          <img src="images/icons/<?php echo $consumer_img_name_[$key] ?>" width="20" height="<?php echo $consumer_h_[$key]?>"></td>
	<?php }?>
	
        </tr>
      <tr>
	
	<?php foreach($consumer_array as $key => $val ){?>   
	    <td align="left"><?php echo $val?></td>
	<?php }?>

      </tr>
    </table>
	
	</td>
    <td valign="top" bgcolor="#FFFFFF">
	<?php
	$focus_sql = tep_db_query('select tours_focus from feedback ');
	
	$focus_array = array(0=>'嬴虛',1=>'俴最',2=>'歎跡',3=>'督昢',4=>'儂き');
	foreach($focus_array as $key => $val){
		$focus_[$key] = 0;	//var
	}
	
	while($focus_rows = tep_db_fetch_array($focus_sql)){
		foreach($focus_array as $key => $val ){
			if($focus_rows['tours_focus']==$key){$focus_[$key]++;}
		}
	}
	
	$max_val_focus = max($focus_[0],$focus_[1],$focus_[2],$focus_[3],$focus_[4]);
	
	foreach($focus_array as $key => $val ){
		$focus_bfb_[$key] = ($focus_[$key]/$rows_total);
		$focus_h_[$key] = intval(200 * $focus_bfb_[$key]);
		$focus_img_name_[$key] = 'report_b.gif';
		if($focus_[$key]==$max_val_focus){
			$focus_img_name_[$key] = 'report_r.gif';
			
		}
	}
	
	?>	
	
	<table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td height="25" colspan="2"><strong>11﹜蠟婓藏蚔徹最笢ㄛ載婓綱狟醱笢腔闡珨欴儸ˋ</strong></td>
        </tr>
      <tr>
    
	<?php foreach($focus_array as $key => $val ){?>   
		<td height="200" align="left" valign="bottom">
		<?php echo $focus_[$key] ?><br>
          <img src="images/icons/<?php echo $focus_img_name_[$key] ?>" width="20" height="<?php echo $focus_h_[$key]?>"></td>
	<?php }?>
	
        </tr>
      <tr>
	
	<?php foreach($focus_array as $key => $val ){?>   
	    <td align="left"><?php echo $val?></td>
	<?php }?>

      </tr>
    </table>
	
	</td>
    <td valign="top" bgcolor="#FFFFFF">
	<?php
	$tours_add_prod_sql = tep_db_query('select tours_add_prod from feedback_add_prod ');
	
	$tours_add_prod_array = array(0=>'ワ痐都妎',1=>'藏蚔都妎',2=>'藏蚔陓洘',3=>'肮俴疑衭',4=>'載嗣芞え',5=>'載嗣弝け',6=>'羲溫ぜ蹦',7=>'赻蚕俴',8=>'む坻');
	foreach($tours_add_prod_array as $key => $val){
		$tours_add_prod_[$key] = 0;	//var
	}
	
	while($tours_add_prod_rows = tep_db_fetch_array($tours_add_prod_sql)){
		foreach($tours_add_prod_array as $key => $val ){
			if(($tours_add_prod_rows['tours_add_prod']-1)==$key){$tours_add_prod_[$key]++;}
		}
	}
	
	$max_val_tours_add_prod = max($tours_add_prod_[0],$tours_add_prod_[1],$tours_add_prod_[2],$tours_add_prod_[3],$tours_add_prod_[4],$tours_add_prod_[5],$tours_add_prod_[6],$tours_add_prod_[7],$tours_add_prod_[8]);
	
	foreach($tours_add_prod_array as $key => $val ){
		$tours_add_prod_bfb_[$key] = ($tours_add_prod_[$key]/$rows_total);
		$tours_add_prod_h_[$key] = intval(200 * $tours_add_prod_bfb_[$key]);
		$tours_add_prod_img_name_[$key] = 'report_b.gif';
		if($tours_add_prod_[$key]==$max_val_tours_add_prod){
			$tours_add_prod_img_name_[$key] = 'report_r.gif';
			
		}
	}
	
	?>	
	
	<table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td height="25" colspan="2"><strong>12﹜斕洷咡掛桴壺賸蚳珛腔藏蚔莉こ俋ㄛ遜褫眕氝樓闡虳源醱囀�暊堧�</strong></td>
        </tr>
      <tr>
    
	<?php foreach($tours_add_prod_array as $key => $val ){?>   
		<td height="200" align="left" valign="bottom">
		<?php echo $tours_add_prod_[$key] ?><br>
          <img src="images/icons/<?php echo $tours_add_prod_img_name_[$key] ?>" width="20" height="<?php echo $tours_add_prod_h_[$key]?>"></td>
	<?php }?>
	
        </tr>
      <tr>
	
	<?php foreach($tours_add_prod_array as $key => $val ){?>   
	    <td align="left"><?php echo $val?></td>
	<?php }?>

      </tr>
    </table>
	
	</td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#FFFFFF">
	<?php
	$site_improve_sql = tep_db_query('select tours_site_improve from feedback ');
	
	$site_improve_array = array(0=>'珜醱票擁',1=>'絳瑤扢數',2=>'刲坰竘э',3=>'諦督窐講',4=>'む坻');
	foreach($site_improve_array as $key => $val){
		$site_improve_[$key] = 0;	//var
	}
	
	while($site_improve_rows = tep_db_fetch_array($site_improve_sql)){
		foreach($site_improve_array as $key => $val ){
			if($site_improve_rows['tours_site_improve']==$key){$site_improve_[$key]++;}
		}
	}
	
	$max_val_site_improve = max($site_improve_[0],$site_improve_[1],$site_improve_[2],$site_improve_[3],$site_improve_[4]);
	
	foreach($site_improve_array as $key => $val ){
		$site_improve_bfb_[$key] = ($site_improve_[$key]/$rows_total);
		$site_improve_h_[$key] = intval(200 * $site_improve_bfb_[$key]);
		$site_improve_img_name_[$key] = 'report_b.gif';
		if($site_improve_[$key]==$max_val_site_improve){
			$site_improve_img_name_[$key] = 'report_r.gif';
			
		}
	}
	
	?>	
	
	<table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td height="25" colspan="2"><strong>13﹜蠟橇腕掛桴剒猁婓闡虳源醱輛俴蜊輛ˋ</strong></td>
        </tr>
      <tr>
    
	<?php foreach($site_improve_array as $key => $val ){?>   
		<td height="200" align="left" valign="bottom">
		<?php echo $site_improve_[$key] ?><br>
          <img src="images/icons/<?php echo $site_improve_img_name_[$key] ?>" width="20" height="<?php echo $site_improve_h_[$key]?>"></td>
	<?php }?>
	
        </tr>
      <tr>
	
	<?php foreach($site_improve_array as $key => $val ){?>   
	    <td align="left"><?php echo $val?></td>
	<?php }?>

      </tr>
    </table>
	
	</td>
    <td valign="middle" bgcolor="#FFFFFF">
<?php
$sql = tep_db_query('select count(*) as total from customers ');
$rows_cus_total = tep_db_result($sql,"0","total");
$rep_l = (intval(($rows_total/$rows_cus_total) * 10000) / 10000 ) * 100;
?>

		<p><strong><?php echo "善醴ヶ峈砦ㄛ僕彶善橾諦誧覃脤毀嚏恀橙 ".$rows_total." 爺ㄐ";?></strong></p>
		<p><strong><?php echo "僕楷堤諦誧蚘璃 ".$rows_cus_total." 爺ㄐ";?></strong></p>
		<p><strong><?php echo "毀嚏薹峈 ".$rep_l."%﹝";?></strong></p>
	</td>
    <td valign="top" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
</table>
</body>
</html>
