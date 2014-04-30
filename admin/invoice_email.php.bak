<table cellpadding="0" cellspacing="0" border="0" width="715" align="center">
	<tbody>
    	<tr height="95" style="padding-top:10px;">
        	<td width="380" style="vertical-align:top;"><img src="<?php echo HTTP_SERVER?>/image/logo.jpg" alt="走四方旅游网" width="180" height="70" style="border:0 none;" /></td>
            <td colspan="2" style="vertical-align:top;">
            	<div style="margin:0;padding:0;text-align:right;">
                	<h1 style="margin:0;padding:0;line-height:50px;font-family:Arial;font-size:36px;color:#222;">Invoice:<?php echo ORDER_EMAIL_PRIFIX_NAME . $oID; ?></h1>
                    <p style="margin:0;padding:0;font-family:Arial;font-size:20px;font-weight:bold;color:#222;">DATE: <?php echo date("m/d/Y", strtotime($order->info['date_purchased']) );?></p>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="715" align="center" style="font-size:14px;line-height:26px;padding-bottom:10px;font-family:tahoma;">
	<tbody>
    	<tr>
        	<td width="60%">
            	<p style="margin:0;padding:0;padding-left:10px;">
                	<b>Unitedstars International Ltd.</b><br />133B W Garvey Ave, Monterey Park,<br />CA, USA 91754<br />TEL:1-626-898-7800<br />FAX:1-626-569-0580
                </p>
            </td>
            <td>
            	<table cellpadding="0" cellspacing="0" border="0">
                	<tbody>
                    	<tr>
                        	<td style="text-align:right;"><b>Bill to：</b></td>
                            <td style="text-align:left;">&nbsp;</td>
                        </tr>
                        <tr>
                        	<td style="text-align:right;">Customer Name：</td>
                            <td style="text-align:left;"><?php echo $order->customer["name"];?>&nbsp;</td>
                        </tr>
                        <tr>
                        	<td style="text-align:right;">TelNumber：</td>
                            <td style="text-align:left;"><?php echo $order->customer["telephone"];?>&nbsp;</td>
                        </tr>
                        <tr>
                        	<td style="text-align:right;">Reservation Number：</td>
                            <td style="text-align:left;"><?php echo ORDER_EMAIL_PRIFIX_NAME . $oID; ?>&nbsp;</td>
                        </tr>
                        <tr>
                        	<td style="text-align:right;">Payment Method：</td>
                            <td style="text-align:left;"><?php echo $order->info['payment_method']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="715" align="center">
	<thead>
    	<tr style="background:#C6D9F1;font-size:14px;font-family:Arial;line-height:40px;">
        	<th width="350" style="border:1px solid #000000;">Itinerary</th>
            <th width="135" style="border:1px solid #000000;border-left:0 none;">Tour Code</th>
            <th width="50" style="border:1px solid #000000;border-left:0 none;">Tax</th>
            <th width="90" style="border:1px solid #000000;border-left:0 none;">Price (ex)</th>
            <th style="border:1px solid #000000;border-left:0 none;">Total (inc)</th>
        </tr>
    </thead>
    <tbody>
    	<?php
		for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
			if($order->products[$i]['parent_orders_products_id']<1){	
		?>
		<tr style="font-size:14px;font-family:Arial;line-height:22px;">
        	<td width="300" style="border:1px solid #000000;border-top:0 none;border-bottom-style:dotted;"><p style="margin:0;padding:0;padding:5px;">
			<?php echo $order->products[$i]['name'];?>
			</p>
			<?php 
			if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
			  for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
				echo '<span style="color:#3f3f3f"><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i><br></small></nobr></span>';
			  }
			}
			?>
			</td>
            <td width="135" style="text-align:center;border:1px solid #000000;border-left:0 none;border-top:0 none;border-bottom-style:dotted;"><p style="margin:0;padding:0;"><?php echo $order->products[$i]['model'];?></p></td>
            <td width="50" style="text-align:center;border:1px solid #000000;border-left:0 none;border-top:0 none;border-bottom-style:dotted;"><p style="margin:0;padding:0;"><?php echo tep_display_tax_value($order->products[$i]['tax']);?></p></td>
            <td width="90" style="text-align:center;border:1px solid #000000;border-left:0 none;border-top:0 none;border-bottom-style:dotted;"><p style="margin:0;padding:0;"><?php echo $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value'])?></p></td>
            <td style="text-align:center;border:1px solid #000000;border-left:0 none;border-top:0 none;border-bottom-style:dotted;font-weight:bold;"><p style="margin:0;padding:0;">
			<?php echo $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']);?>
			</p></td>
        </tr>
        <?php
			}
		}
		?>
		
        <tr style="font-size:14px;font-family:Arial;">
        	<td colspan="5" style="text-align:right;margim:0;padding-right:10px;border:1px solid #000000;border-top:0 none;">
			<?php
			  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
				echo '<p style="margim:0;padding:0;_line-height:30px;">' . $order->totals[$i]['title'] . '<span style="color:#FF0000;font-size:16px;font-weight:bold;">' . $order->totals[$i]['text'] . '</span></p>';
			  }
			?>
			</td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="715" align="center" style="background:#C6D9F1;border:1px solid #000000;border-top:0 none;font-size:12px;line-height:22px;font-family:tahoma;color:#333333;">
	<tbody>
    	<tr>
        	<td><p style="margin:10px;padding:0;">Usitrip wish you a pleasant trip！<br />
Welcome logging in our website to share your photos and reviews. You will get points for each photo and review which you can use for your next tour. Please check "my account" for detailed information.<br />
尊敬的各位旅客，走四方网祝您旅途愉快！<br />
<br />
-----------------<br />
Your trip ,Our care!<br />
您的旅行，我的关注！</p>
			</td> 
        </tr>
        <tr>
        	<td style="text-align:right;padding-bottom:10px;">
            	<div style="margin:0;padding:0;line-height:22px;">
                	<p style="margin:0;padding:0;padding-right:10px;text-decoration:underline;font-weight:bold;">www.usitrip.com|走四方旅游网</p>
                    <div style="margin:0;padding:0;position:relative;">
                    	<img src="<?php echo HTTP_SERVER?>/image/plus.jpg" height="33" width="32" style="position:absolute;right:10px;top:3px;" />
                        <p style="margin:0;padding:0;padding-right:50px;">全球华人首选出国旅游网站<br />美国BBB认证最高商誉评级</p>
                    </div>
                    <p style="margin:0;padding:0;padding-right:10px;">客服Email：service@usitrip.com<br />美加（热线）：888-887-2816<br />中国（热线）：4006-333-926</p>
                </div>
            </td>
        </tr>
    </tbody>
</table>
