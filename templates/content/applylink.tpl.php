        <div class="links">
            <h1><?php echo db_to_html('申请友情链接'); ?></h1>
            <div class="linksIntro">
                <h2><?php echo db_to_html('欢迎各大旅游网站、户外运动网站、时尚网站及其它各类相关网站与走四方网交换链接。');?></h2>
                <p><b><?php echo db_to_html('基本要求');?></b><br /><?php echo db_to_html('网站不得包含反动/黄色等违法信息；');?><br /><?php echo db_to_html('PR及Alexa排名不限，我们将根据贵站的情况，提供相应的链接。');?></p>
            </div>

            <div class="linksMethod">
                <div class="linksMethod1">
                    <div class="head"><span class="left"><?php echo db_to_html('方式一');?></span><span class="mid"><?php echo db_to_html('在线联系管理员申请友情链接');?></span><span class="right"></span></div>

                    <div class="con">
                        <div class="qq" onclick="javascript:window.open('http://sighttp.qq.com/msgrd?v=3&uin=2216364379&site=qq&menu=yes');">
                            <div class="icon"><?php echo db_to_html('QQ联系管理员');?></div>
                            <div class="tip"><?php echo db_to_html('(请注明交换链接)');?></div>
                            <p><?php echo db_to_html('QQ:2216364379');?></p>
                        </div>
                        <div class="qq msn" onclick="javascript:window.open('msnim:chat?contact=usi4trip_charles@hotmail.com');">
                            <div class="icon"><?php echo db_to_html('MSN联系管理员');?></div>
                            <div class="tip"><?php echo db_to_html('(请把#改为@)');?></div>
                            <p>MSN: usi4trip_charles#hotmail.com</p>
                        </div>
                    </div>

                    <div class="bottom">
                        <div class="left"></div>
                        <div class="right"></div>
                    </div>
                </div>                
                <div class="linksMethod2">
                    <div class="head">
                        <span class="left"><?php echo db_to_html('方式二');?></span><span class="mid"><?php echo db_to_html('在线填表提交链接申请
');?></span>
                        <span class="right"></span>
                    </div>
                    <?php echo tep_draw_form('linkform', 'applylink.php', 'post', "id='linkform'"); ?>
                    <div class="con">
                        <h2><?php echo db_to_html('网站信息');?></h2>
                        <ul>
                            <li>
                                <label><?php echo db_to_html('网站名称:');?></label>                            
                                <input <?php if (CHARSET == 'big5'){ ?> onblur="this.value=traditionalized(this.value);" <? }else{
                                    ?> onblur="this.value=simplized(this.value);" <?php } ?> type="text" class="text" id="link_name" name="link_name"/>
                            </li>
                            
                            <li><label><?php echo db_to_html('网站首页:');?></label><input <?php if (CHARSET == 'big5'){ ?> onblur="this.value=traditionalized(this.value);" <? }else{
                                    ?> onblur="this.value=simplized(this.value);" <?php } ?> type="text" class="text url" id="link_url" name="link_url" value="http://" /></li>
                            <li><label><?php echo db_to_html('互惠页URL:');?></label><input <?php if (CHARSET == 'big5'){ ?> onblur="this.value=traditionalized(this.value);" <? }else{
                                    ?> onblur="this.value=simplized(this.value);" <?php } ?> type="text" class="text url" id="link_reciprocal_url" name="link_reciprocal_url" value="http://"/><span title="<?php echo db_to_html('请填写您网站上包含走四方网链接的页面地址'); ?>"></span></li>
                            <li><label><?php echo db_to_html('网站描述:');?></label><textarea <?php if (CHARSET == 'big5'){ ?> onblur="this.value=traditionalized(this.value);" <? }else{
                                    ?> onblur="this.value=simplized(this.value);" <?php } ?> class="textarea" id="link_desc" name="link_desc" onkeydown="this.style.color='#111'" onblur="if(this.value==''){this.value='<?php echo db_to_html('请用10个字符以上的文字，对网站做个简单的介绍。');?>';this.style.color='#ccc'}" onfocus="if(this.value!='<?php echo db_to_html('请用10个字符以上的文字，对网站做个简单的介绍。');?>'){this.style.color='#111'}else{this.value='';this.style.color='#111'}" style="color:#ccc;"><?php echo db_to_html('请用10个字符以上的文字，对网站做个简单的介绍。');?></textarea></li>
                        </ul>
                        <h2><?php echo db_to_html('站长信息');?></h2>
                        <ul>
                            <li><label><?php echo db_to_html('站长姓名:')?></label><input <?php if (CHARSET == 'big5'){ ?> onblur="this.value=traditionalized(this.value);" <? }else{
                                    ?> onblur="this.value=simplized(this.value);" <?php } ?> type="text" class="text" id="link_contact_name" name="link_contact_name" /></li>
                            <li><label><?php echo db_to_html('站长邮箱:')?></label><input <?php if (CHARSET == 'big5'){ ?> onblur="this.value=traditionalized(this.value);" <? }else{
                                    ?> onblur="this.value=simplized(this.value);" <?php } ?> type="text" class="text url" id="link_contact_email" name="link_contact_email" onkeydown="this.style.color='#111'" onblur="if(this.value==''){this.value='yourname@exampale.com';this.style.color='#ccc'}" onfocus="if(this.value!='yourname@exampale.com'){this.style.color='#111'}else{this.value='';this.style.color='#111'}" value="yourname@exampale.com" style="color: #ccc;"/></li>
                        </ul>
                        <div class="submit">
                            <a class="btn btnOrange" href="javascript:;" onclick="apply_link('linkform')"><button type="button" id="applylink"><?php echo db_to_html('申请链接')?></button></a>
                        </div>
                    </div>
                    </form>
                    <div class="bottom">
                        <div class="left"></div>
                        <div class="right"></div>
                    </div>
                </div>               

            </div>

            <div class="linksInfo">
                <h2><?php echo db_to_html('走四方网链接信息')?></h2>
                <dl>
                    <dt><?php echo db_to_html('文字链接')?></dt>
                    <dd><span><?php echo db_to_html('链接文字:')?></span><?php echo db_to_html('走四方网')?></dd>
                    <dd><span><?php echo db_to_html('链接地址:')?></span>
                    <?php 
                    if (CHARSET == 'big5'){
                    ?>
                        http://www.usitrip.com/
                    <?php 
                    }else{
                    ?>
                        http://www.usitrip.com/
                    <?php 
                    }
                    ?>
                    </dd>
                    <dd><span><?php echo db_to_html('网站描述:')?></span><?php echo db_to_html('走四方网是北美旅游行业首屈一指的先锋旅游公司，提供在线预订以北美地区为核心的全球景点旅游行程的服务。')?></dd>
                </dl>
             
            </div>

        </div>
        
<div class="popup" id="popupTip">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con">

  <div class="popupCon" id="popupConTip" style="width:350px;">
  
    <div class="success">
        <div class="img"><img src="<?= DIR_WS_TEMPLATE_IMAGES;?>success.jpg" /></div>
        <div class="right">
            <p><?php echo db_to_html('您的申请已经提交，管理员会及时处理！'); ?></p>            
            <p><a class="btn btnGrey" href="javascript:;"><button type="button" id="passButton" onclick="closePopup('popupTip');"><?php echo db_to_html('关 闭');?></button></a></p>
        </div>
    </div>
  
    
  </div>
  
</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
</table>
</div>
<div id="popupBg" class="popupBg"></div>
<script type="text/javascript">
    function apply_link(form_id){
        
        var link_name = jQuery.trim(jQuery("#link_name").val());
        var link_url = jQuery.trim(jQuery("#link_url").val());
        var link_reciprocal_url = jQuery.trim(jQuery("#link_reciprocal_url").val());
        var link_desc = jQuery.trim(jQuery("#link_desc").val());
        var link_contact_name = jQuery.trim(jQuery("#link_contact_name").val());
        var link_contact_email = jQuery.trim(jQuery("#link_contact_email").val());        
        var from = document.getElementById(form_id);
        if(link_name == ''){
            alert("<?php echo db_to_html('请输入您的网站名称！')?>");            
            return false;
        }
        if(link_url == ''){
            alert("<?php echo db_to_html('请输入您的网站链接地址！')?>");
            return false;
        }else if (!CheckUrl(link_url)){            
            alert("<?php echo db_to_html('请输入有效的网站地址！')?>");
            return false;
        }
        if(link_reciprocal_url == ''){
            alert("<?php echo db_to_html('请输入您的互惠页URL！')?>");
            return false;
        }else if (!CheckUrl(link_reciprocal_url)){            
            alert("<?php echo db_to_html('请输入有效的互惠页URL!')?>");
            return false;
        }
        
        if (link_desc == '' || link_desc.length < 10 || link_desc == '<?php echo db_to_html('请用10个字符以上的文字，对网站做个简单的介绍。') ?>'){
            alert("<?php echo db_to_html('请输入你的网站描述，不少于10个字!')?>");
            return false;
        }
        
        if(link_contact_name == '' || link_contact_name.length < 2){
            alert("<?php echo db_to_html('请输入您的姓名，不少于2个字符！')?>");
            return false;
        }        
        if(link_contact_email == '' || link_contact_email == 'yourname@exampale.com'){            
            alert("<?php echo db_to_html('请输入您的Email地址！')?>");
            return false;
        }else if (!email(link_contact_email)){
            alert("<?php echo db_to_html('请输入有效的Email地址！')?>");
            return false;
        }
        jQuery("#applylink").attr("disabled", true);        
        var url = url_ssl("<?php echo tep_href_link('applylink.php', 'action=applylink') ?>");
        var success_msm="";
        var success_go_to="";
        var replace_id="";        
        ajax_post_submit(url,form_id,success_msm,success_go_to, replace_id);
            
    }   
    
    // email 
    function email(value){
        var reg = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i;
        return reg.test(value)    
    }
    // url
    function CheckUrl(str) {
    var RegUrl = new RegExp();
    RegUrl.compile("^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$");
    if (!RegUrl.test(str)) {
        return false;
    }
    return true;
}


    
</script>