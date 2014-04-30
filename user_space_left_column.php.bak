<script type="text/javascript"><!--
//添加好友
function AddFriends(cus_id){
	var url = url_ssl("<?php echo tep_href_link_noseo('add_friends_ajax.php','customers_id=')?>") + cus_id;
	ajax.open("GET", url, true);
	ajax.send(null); 
	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			document.getElementById("AddFriendsLink").style.display = 'none';
			alert(ajax.responseText);
		}
	}
}
-->
</script>			



<a href="zhenghua_html/geren_kongjian_liulan.html"><img src="image/photo_1.jpg" border="0" class="border_bian" /></a>
<div class="join_friend">
  <p><span class="huise ">昵称：</span>钢铁侠不会飞<br />
    <span class="huise">性别：</span>保密<br />
    <span class="huise">旅游爱好：</span>探险自然<br />
    <p class="huise">描述：</p>
         <p>我是一个小小鸟，小鸟是谁不知道啊。哈哈哈哈。我是一个小小鸟，小鸟是谁不知道啊。哈哈哈哈。我是一个小小鸟，小鸟是谁不知道啊。哈哈哈哈。我是一个小小鸟，小鸟是谁不知道啊。哈哈哈哈。</p>
          <p><a href="<?php echo tep_href_link('my-space.php') ?>"><?php echo db_to_html('管理我的空间')?></a><br />
            

			<?php
			// add friend button
			if($cser!=$customer_id && !check_friend($customer_id,$cser)){
				echo db_to_html('<a id="AddFriendsLink" href="JavaScript:AddFriends(\''. (int)$cser.'\')">加他为好友</a>');
			}
			?>
			</p>
        </div>
  <div class="join_friend"><p class="cu">寻找好友</p><p>已经有朋友在走四方网，立即找
    到TA</p>
    <div class="tiaozheng">
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="117" valign="top">
                 
            <form id="form3" name="form3" method="post" action="">
              <label>
                <input name="textfield3" type="text" class="input_search4" id="textfield3" size="17" />
              </label>
            </form>            </td>
            <td width="37" valign="bottom">
            <img src="image/buttons/tchinese/sousou.gif" width="22" height="19" />            </td>
           </tr>
         </table>
    </div>
        </div>
  <div class="join_friend"><p class="cu">产品搜索</p>
    <div class="tiaozheng">
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="117" valign="top">
                 
            <form id="form3" name="form3" method="post" action="">
              <label>
                <input name="textfield3" type="text" class="input_search4" id="textfield3" value="产品关键字" size="17" />
                </label>
              </form>            </td>
          <td width="37" valign="bottom">
           <img src="image/buttons/tchinese/button_search2.gif"  />            </td>
         </tr>
        </table>
    </div>
        </div>
