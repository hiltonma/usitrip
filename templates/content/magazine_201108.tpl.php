<?php ob_start();?>
<style type="text/css">
<!--
#FormVote input {
	margin-right:4px;
}
-->
</style>


    <div class="tourseDy">
	<form method="post" enctype="multipart/form-data" id="SubscribeForm" onsubmit="return false">
	<table width="34%" border="0" cellspacing="0" cellpadding="0" align="right"><tr><td><span id="ReturnString"></span><span>输入邮件地址：</span></td><td><div class="inputbox"><input name="emailAddressSubscribe" type="text" id="emailAddressSubscribe" /></div></td><td><button id="SubscribeButton" class="toursDyBt" title="订阅"></button></td></tr></table>
	</form>
</div>
<div class="tourseLeft">
  <div class="widget">
    <div class="titleTourse"><h3>往期回顾</h3></div>
      <ul>
        <li>
		<a href="/magazine/No-001/" target="_blank"><img src="image/tourse/tourse_last1.jpg" alt="第一期：漂洋过海迎新年" /><br />
		<span>第一期：漂洋过海迎新年</span></a>
		<p><a href="/magazine/No-001/?download=1">下载PDF</a> | <a href="/magazine/No-001/" target="_blank">阅读</a></p></li>
        <li>
		<a href="/magazine/No-002/" target="_blank"><img src="image/tourse/tourse_last2.jpg" alt="第二期：温暖旅行" /><br />
		<span>第二期：温暖旅行</span></a>
		<p><a href="/magazine/No-002/?download=1">下载PDF</a> | <a href="/magazine/No-002/" target="_blank">阅读</a></p></li>
      </ul>
  </div>
</div>
 <div class="tourseRight">
 <div class="RightTitle">
   <h3><p>最新一期电子杂志</p><a href="/magazine/No-new/?download=2" class="exe">立即下载EXE</a><a href="/magazine/No-new/?download=1" class="pdf">立即下载PDF</a></h3>
 </div>
 <div class="toursebook">
   <div class="tourseImg"><img src="image/tourse/tourse_book1.jpg" alt="最新一期电子杂志" /></div>
   <div class="tourseImg"><img src="image/tourse/tourse_book2.jpg" alt="最新一期电子杂志" /></div>
 </div>
 <div class="starreade"><a href="/magazine/" target="_blank"><img src="image/tourse/tourse_btn.jpg" alt="开始阅读" /></a></div>
 </div>

<?php
// 走四方调查{
if(tep_not_null($voteData)){
//print_r($voteData);
?>
 
<form id="FormVote" name="FormVote" method="post" action="" onsubmit="submitVote(); return false;">
<input name="v_s_id" type="hidden" value="<?= $voteData['id'];?>" />
<div class="Questionnaire">
  <h3><?= $voteData['title'];?></h3>
  <div class="AskContent">
    <table width="440" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td><b>您想告诉走四方：</b></td>
	  </tr>
	  <tr>
	    <td>
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><strong><?= $voteData['item'][0]['title']?></strong></td>
			  <td><?= $voteData['item'][0]['answers'][0]['input']?></td>
			  <td><?= $voteData['item'][0]['answers'][1]['input']?></td>
			  <td><div class="inputbox"><?= tep_draw_input_field('v_s_i_o_text[10][98]','','id="otherInput"')?></div></td>
			  <td><span>如：公司职员</span></td>
			</tr>
		    <tr>
			  <td colspan="3"><b><?= $voteData['item'][1]['title']?></b></td>
			  <td><div class="inputbox"><?= $voteData['item'][1]['answers'][0]['input']?></div></td>
			  <td><span>如：纽约</span></td>
			</tr>
		  </table>
		</td>
	  </tr>
	  <tr>
		<td style="padding-top:10px;"><strong><?= $voteData['item'][2]['title']?></strong></td>
	  </tr>
	   <tr>
		<td>
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		    <tr>
			<?php for($i=0, $n=sizeof($voteData['item'][2]['answers']); $i<$n; $i++){
				if((int)$i && $i%4==0){ echo "</tr><tr>";}
			?>
			  <td width="25%"><?= $voteData['item'][2]['answers'][$i]['input']?></td>
			<?php }?>
			</tr>
		  </table>
		</td>
	  </tr>
	  <tr>
		<td style="padding-top:10px;"><strong><?= $voteData['item'][3]['title']?></strong></td>
	  </tr>
	   <tr>
		<td>
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		    <tr>
			<?php for($i=0, $n=sizeof($voteData['item'][3]['answers']); $i<$n; $i++){
				if((int)$i && $i%4==0){ echo "</tr><tr>";}
			?>
			  <td width="25%"><?= $voteData['item'][3]['answers'][$i]['input']?></td>
			<?php }?>
			</tr>
		  </table>
		</td>
	  </tr>
	   <tr>
		<td style="padding-top:10px;"><strong>如果您也想在《走四方E游》上分享您的旅行经历，请留下您的：</strong></td>
	  </tr>
	  <tr>
		<td>
		  <table width="75%" border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><b><?= $voteData['item'][4]['title']?></b></td>
			  <td><div class="inputbox"><?= $voteData['item'][4]['answers'][0]['input']?></div></td>
			  <td><span>QQ/MSN/Email</span></td>
			</tr>
            <tr>
			  <td><b><?= $voteData['item'][5]['title']?></b></td>
			  <td><div class="inputbox"><?= $voteData['item'][5]['answers'][0]['input']?></div></td>
			  <td> </td>
			</tr>
		  </table>
		</td>
	  </tr>
	  <tr><td align="center"><a class="btn btnOrange"><button id="submitVoteButton" type="submit">提交问卷</button></a><input name="vote_code" type="hidden" value="<?= CHARSET?>" /></td></tr>
	  <tr><td id="submitVoteMsn" align="left"></td></tr>
	</table>
	</div>
</div>
</form>
<?php
}
// 走四方调查}
?>

<script type="text/javascript">
<?php //订阅功能{?>
jQuery("#SubscribeButton").click(function(){
	var email_address = jQuery("#emailAddressSubscribe").val();
	var emailPat = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	if(email_address=="" || email_address.match(emailPat)==null){
		alert("请输入有效的邮件地址");
		return false;
	}
	var form_id = 'SubscribeForm';
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('magazine.php','action=ajaxSubmitEmail')) ?>");
	ajax_post_submit(url,form_id);
	
});

function ajaxReturnString(str){
	//jQuery("#ReturnString").html(str);
	alert(str);
}

<?php //订阅功能}?>

<?php //走四方调查JS{?>
function submitVote(){
	var form_id = "FormVote";
	jQuery("#submitVoteButton").html("正在提交……");
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('magazine.php','action=ajaxSubmitVote')) ?>");
	ajax_post_submit(url,form_id);
	//jQuery("#submitVoteButton").html("提交问卷");

}
<?php //走四方调查JS}?>
</script>

<?php echo  db_to_html(ob_get_clean());?>