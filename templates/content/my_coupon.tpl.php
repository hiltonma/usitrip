<div style="border:1px solid #AED5FF;padding:8px;">
    <div id="active_range" class="activeRange">
        <form action="" id="active_coupon" name="active_coupon" method="post">
            <span><b><?php echo  TITLE_GET_COUPON;?>&nbsp;&nbsp;</b></span> 
            <span><?= db_to_html("优惠券编号：");?></span>
            <span><?= tep_draw_input_num_en_field('coupon_code','',' autocomplete="off" ');?></span>
            <span><button type="submit"></button></span>
            <span id="active_coupon_msn"></span>
        </form>
    </div>
    <div id="coupon_list" class="couponList">
        <table width="100%" id="coupon_list_table" style="float:none;" border="0" cellspacing="1" bgcolor="#dcdcdc" cellpadding="0" class="couponListTable">
            <tr>
                <th background="/image/nav/user_bg11.gif"><?= db_to_html("优惠券编号");?></th>
                <th><?= db_to_html("金额");?></th>
                <th><?= db_to_html("生效日期");?></th>
                <th><?= db_to_html("使用范围");?></th>
                <th><?= db_to_html("有效期");?></th>
                <th><?= db_to_html("状态");?></th>
            </tr>
            <?php
			if($HtmlMyCoupons != NULL){
				for($i=0; $i<count($HtmlMyCoupons); $i++){
			?>
            <tr onmouseover="this.className='over'" onmouseout="this.className=''">
                <td><?= $HtmlMyCoupons[$i]['code'];?></td>
                <td><?= $HtmlMyCoupons[$i]['amount'];?></td>
                <td><?= $HtmlMyCoupons[$i]['start_date'];?></td>
                <td><?= $HtmlMyCoupons[$i]['use_range'];?></td>
                <td><?= $HtmlMyCoupons[$i]['expire_date'];?></td>
                <td><?= $HtmlMyCoupons[$i]['status'];?></td>
            </tr>
            <?php
				}
			}
			?>
        </table>
    </div>
</div>
