<?php
//$version = 'old';
/*if(USE_OLD_VERSION){
	 echo tep_get_design_body_header('<span id="BreadTop">'.IMAGE_BUTTON_LOGIN.'</span>');
	require(DIR_FS_INCLUDES . 'login_or_create_account.php');
}else{*/
	ob_start();
	$checkRememberMe = '';
	$account_input_blankvalue="请输入您的注册邮箱";
	if(!empty($_POST['email_address'])){//POST
		$account_value = $_POST['email_address'];
	}elseif(!empty($_COOKIE['user_remembered_accountname'])){//使用remember功能
		$account_value = $_COOKIE['user_remembered_accountname'];
	} else{
		$account_value  = $account_input_blankvalue;
	}	
	$input_style = $account_value == $account_input_blankvalue ? ' style="color: #ccc;" ':' style="color: #111;"';
	
?>

    <!--***********登陆内容**********-->
    
    <div class="logining"><?php /* &isDebugURL=1&debug_host=192.168.1.88%2C127.0.0.1&debug_fastfile=1&start_debug=1&debug_port=10137&no_remote=1&original_url=http%3A%2F%2Fcn.86.usitrip-1.com%2Flogin.php&send_sess_end=1&debug_stop=1&debug_start_session=1&debug_no_cache=1357609696718&debug_session_id='.$debug_session_id */ ?>
    <div class="login"><form name="logform" id="logform"  action="<?php echo tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')?>"  method="POST" enctype="application/x-www-form-urlencoded" onsubmit="return validateFormData();">
    	<div class="login_left">
        	<ul>
            <li style="z-index:1;"><span class="color_orange">*&nbsp;</span><em class="font_size14">帐号:</em><?php echo tep_draw_input_field('email_address',$account_value,' tabindex="1" class="box1 inset_shadow"  autocomplete="off" id="email" '.$input_style)?><span class="color_b3b3b3">请填写邮箱地址。</span><div class="suggest" id="suggest"></div></li>
            <li class="even">
              <label>&nbsp;</label><span id="AccountErrorMessage" style="display:none"></span>
            </li>
            <li><span class="color_orange">*&nbsp;</span><em class="font_size14">密码:</em> <?php echo tep_draw_password_field('password','','tabindex="2" id="password" onkeydown="if(jQuery(\'#PasswordErrorMessage\').css(\'display\') != \'none\') jQuery(\'#PasswordErrorMessage\').hide();" class="box1 inset_shadow"  ')?>
            <span class="color_orange"><a href="<?php echo tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL')?>">忘记密码？</a></span></li>
            <li class="even">
              <label>&nbsp;</label><span id="PasswordErrorMessage" style="display:none"></span>
            </li>
			<!--<li class="remember">
              <label>&nbsp;</label><span><input type="checkbox"  name="remember_me"  value="1"   class="checkboxLogin"  <?php echo $checkRememberMe?>/>记住我的账号</span>
            </li>-->
            <li class="paddingL55"><input type="submit" id="submit" name="submit" value="登 录"/></li>
            </ul>
        </div>
        <div class="login_right">
        	<p class="color_b3b3b3"><span class="font_size14 font_bold ">还不是usitrip用户？</span><br /><a href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT,'','SSL')?>" class="User111">注册新用户</a>立刻享受便宜又放心的旅途乐趣。<br/>
            <strong>注册走四方会员即能享受：</strong><br/>
            1、免费注册，赠送<?php echo abs(NEW_SIGNUP_POINT_AMOUNT);?>积分！<br/>
            2、预订产品得积分，积分可直接购买走四方产品。<br/>
            3、享受更加体贴的个性化服务！</p>
        </div> 
        <div class="del_float"></div>
        </form>
    </div>   
    </div>
  <script type="text/javascript">
  /* 自动完成E-Mail后缀补充 */
var dontSubmit = false;
var suffixArr= ["@hotmail.com" , "@gmail.com","@163.com","@qq.com ","@126.com","@yahoo.com.cn","@yahoo.com",'@sina.com','@yahoo.cn'];
var suffixArr2= ["@hotmail.com" , "@gmail.com","@163.com","@qq.com ","@126.com","@yahoo.com.cn","@yahoo.com",'@sina.com','@yahoo.cn','@yahoo.com.tw','@sohu.com','@msn.com','@live.cn','@yahoo.com.hk','@tom.com','@yeah.net'];
(function(){
	jQuery.fn.autoComplete=function(options){
        var defaults={
            subBox:"#suggest",
            subOp:"li",
            id:"#email",
            suffixArr:suffixArr,
            suffixArr2:suffixArr2,
            hoverClass:"on",
            _cur:-1,
            blankValue : '请输入邮箱帐号'
        };
        var option = jQuery.extend({}, defaults, options || {});

        jQuery(option.id).keyup(function(e){
        	jQuery('#AccountErrorMessage').hide();
            var _that=jQuery(this);
            if(_that.val()!=""){
                if(e.keyCode!=38 && e.keyCode!=40 && e.keyCode!=13 && e.keyCode!=27&& e.keyCode!=9){
                    var _inputVal=_that.val();
                    jQuery.fn.autoComplete.tipFun(_inputVal,_that);
                }
                jQuery(this).removeClass("textMid"); 
                jQuery(this).removeClass("textSmall"); 
                if(jQuery(this).val().length>25){ 
                    jQuery(this).removeClass("textSmall");
                    jQuery(this).addClass("textMid");  
                    if(jQuery(this).val().length>30){
                        jQuery(this).addClass("textSmall");
                    }
                };
            }else{
                jQuery(option.subBox).hide();
            }
        });

        jQuery(option.id).blur(function(){
        	if(jQuery.trim(jQuery(this).val())==''){
        		jQuery(this).removeClass("textMid"); 
                jQuery(this).removeClass("textSmall");
        		jQuery(this).val(option.blankValue);
        		jQuery(this).css({'color':'#ccc'});
        	}
        });

        jQuery(option.id).focus(function(){        	
        	jQuery(this).css({'color':'#111'});
        	if(jQuery(this).val() == option.blankValue){
        		jQuery(this).val("");
        	}
        });
	   
        jQuery.fn.autoComplete.tipFun=function(_v,o){
             option._cur=-1;
            var _that=o;
            jQuery(option.subBox).show();
            var str="<ul>";
            var e=_v.indexOf("@");
            if(e==-1){
                jQuery.each(option.suffixArr,function(s,m){
                    str+='<li><a href="javascript:void(0)"  >' + _v + m + "</a></li>";							
                });
            }else{
                var _sh=_v.substring(0,e)
                var _se=_v.substring(e);
                var ind = 0;
                jQuery.each(option.suffixArr2, function (s,m) {
                    if(m.indexOf(_se)!=-1){
                        str += '<li><a href="javascript:void(0)" >' + _sh + m + "</a></li>";
                        ind = 1;
                    }
                });
                if(ind==0){
                    str += '<li><a class="cur_val" href="javascript:void(0)" >' + _v + "</a></li>";
                }
            }
            str+="</ul>";
            jQuery(option.subBox).html(str); 

            jQuery(option.subBox).find(option.subOp).hover(function(){
                var _that=jQuery(this);
                _that.addClass(option.hoverClass);					   
            },function(){
                var _that=jQuery(this);
                _that.removeClass(option.hoverClass)			
            });

            jQuery(option.subBox).find(option.subOp).each(function(){
                jQuery(this).click(function(e){
                        jQuery(option.id).val(jQuery(e.target).html());
                        dontSubmit = true;
                        jQuery(option.subBox).hide();
                        e.stopPropagation();
                });											  
            })
        };

		jQuery(document).bind("click",function(e){
            jQuery(option.subBox).hide();
		});

		jQuery.fn.autoComplete.itemFun=function(){
		    
			var _tempArr=jQuery(option.subBox).find(option.subOp);
			var _size=_tempArr.size();
			for(var i=0;i<_size;i++){
				_tempArr.eq(i).removeClass(option.hoverClass);
			}
			
			if(_size>0){
				if(option._cur>_size-1){
					option._cur=0;	
				}
				if(option._cur<0){
					option._cur=_size-1;	
				}
				_tempArr.eq(option._cur).addClass(option.hoverClass);
			}else{
				option._cur=-1;	
			}
		};

		jQuery(document).keydown(function(e){
            switch(e.keyCode){
                case 40: //方向键 向下
                    option._cur++;
                    jQuery.fn.autoComplete.itemFun()
                    break;
                case 38: //方向键 向上
                    option._cur--;
                    jQuery.fn.autoComplete.itemFun()
                    break;		
				case 9:
					jQuery(option.subBox).hide();
					break;
                default:
                   break;
            }
		})
	    jQuery(option.id).keydown(function(e){
		    var _temp=jQuery(option.subBox).find(option.subOp);
		    if(e.keyCode==13){
		        if(option._cur!=-1){
		            jQuery(this).val(_temp.eq(option._cur).text());
			        option._cur=-1;
		        }
		        jQuery(option.subBox).hide();
			    e.stopPropagation();
		    }							  
	    });
	    return this;	
    }	  
})(jQuery);
  </script>
<script type="text/javascript">
jQuery(document).keydown(function(e){
	if(e.keyCode==13){
		jQuery("#submit").click();
	}
});

jQuery("#Email").autoComplete({'blankValue':'<?php echo $account_input_blankvalue ?>'});


function showErrorMessage(msg,target){
	if(typeof(msg) != 'undefined' && typeof(target) != 'undefined' ){
		jQuery('#'+target).html('<span class="errorTip">'+msg+"</span>");
		jQuery('#'+target).show();
	}
}



function validateFormData(){	
	$error = false ;
	var accountText = jQuery.trim(document.logform.email_address.value) ;
	if(accountText == "" || accountText=='<?php echo $account_input_blankvalue ?>'){
		showErrorMessage("请输入您的登录账号。",'AccountErrorMessage');
		$error = true;
	}else if(jQuery.trim(document.logform.password.value) == ""){
		showErrorMessage("请输入您的登录密码。",'PasswordErrorMessage');
		$error=true;
	}
	if($error == true) 	{
		return false;
	}
}
//input选中边缘黄色
jQuery("document").ready(function(){
	jQuery(".box1").focus(function(){jQuery(this).addClass("on")});
	jQuery(".box1").blur(function(){jQuery(this).removeClass("on")});
});

//验证码有内容
jQuery(".textVerify").focus(function(){jQuery(".btnVerify").html("确定");}); 
</script>
  
<?php echo db_to_html(ob_get_clean());?>
<style>
<!--
#content {padding-bottom:18px;} /*for autocomplate pop*/
-->
</style>
<?php if ($messageStack->size('login') > 0) { echo $messageStack->output_newstyle('login','AccountErrorMessage' ,true);}?>
<?php /*}echo tep_get_design_body_footer();*/?>
