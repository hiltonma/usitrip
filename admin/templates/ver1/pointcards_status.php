<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link rel="stylesheet" type="text/css" href="css2/global.css" />
<link rel="stylesheet" type="text/css" href="css2/expert.css" />
<link rel="stylesheet" type="text/css" href="css2/calendar.css" />
<script type="text/javascript" src="js2/jquery.js"></script>
<script type="text/javascript" src="js2/global.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<title>index</title>
</head>
<body >
<div class="main">
    <div class="searchBox">
        <h2 class="conSwitch" id="ConSwitchH_1"><?php echo db_to_html('会员积分卡激活情况-搜索条件')?></h2>
        <div class="searchCon" id="ConSwitchCon_1">
        <form action="<?php echo tep_href_link(FILENAME_POINTCARDS_ORDER)?>" method="get">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="65"><?php echo db_to_html('积分卡卡号:')?></td>
                    <td><input type="text" class="textAll"  name="pointcards_id_start" value="<?php echo $_GET['pointcards_id_start']?>"/> - <input type="text" class="textAll"  name="pointcards_id_end" value="<?php echo $_GET['pointcards_id_end']?>"/></td>       
               </tr>
                <tr>
                    <td colspan="2" class="btnLeft">
						<input type="hidden" name="action" value="status" /> 
                        <button class="btn btnOrange" type="submit"><?php echo db_to_html('搜 索')?></button>
                    </td>
                </tr>
            </table>
            </form>
        </div>
    </div>
 
 <?php if(!empty($VIN_ERROR)){?>
 <div class="error"><?php echo $VIN_ERROR?></div>
 <?php }elseif (!empty($VIN_NOTICE)){?>
  <div class="notice"><?php echo $VIN_NOTICE?></div>
 <?php }
 
 $param = array('page','order','sort','timeStart','timeEnd','pointcards_id_start','pointcards_id_end','configuration_value');
 
 if(count($records) > 0 ){ ?>
    <div>
        <h2 class="conSwitch" id="ConSwitchH_2"><?php echo db_to_html('搜索结果'); ?></h2>

        <div class="minCon" id="ConSwitchCon_2">

            <div>
<table class="expertTable" id="ExpertTable" border="0" cellpadding="0" cellspacing="0">
<tbody>
    <tr>
		<th><?php echo db_to_html('积分卡号'); ?></th>
		<th><?php echo db_to_html('激活时间'); ?></th>
		<th><?php echo db_to_html('激活状态'); ?></th>
		<th><?php echo db_to_html('过期时间'); ?></th>
    </tr>
	<?php foreach($records as $record) { ?>
    <tr>
		<td><?php echo str_pad($record['pointcards_id'],6,'0',STR_PAD_LEFT )?></td>		
		<td><?php echo $record['created']?></td>
		<td><?php echo $record['customer_info']?></td>
		<td><?php echo $record['expire_date']?></td>
    </tr>
	<?php } //endforeach records display?>
	<tfoot>
<tr>
    <td bgcolor="#f5f5f5" colspan="4">
        <div class="pageNum"><?php echo $split_left_content?></div>
        <div class="page"><?php echo $split_right_content?></div>
    </td>
</tr>
</tfoot>
</tbody>
</table>
          </div>
 <?php }// endif  records check ?>



<script type="text/javascript">
$(function(){
     $("#ExpertTable>tbody>tr").each(function(index) {
        if((index+1)%2 == 0 ){
            $(this).addClass("trEven");
        }
    });
});
 


</script>

        </div>
    </div>
</div>
</body>
</html>
