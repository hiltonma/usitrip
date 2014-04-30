<!-- content main body start -->
<?php 
ob_start();	
?>
<!-- content main body end -->
<script type="text/javascript">
var suffixArr= ["@hotmail.com" , "@gmail.com" , "@163.com" , "@qq.com" , "@126.com" , "@yahoo.com.cn" , "@yahoo.com" , "@sina.com" , "@yahoo.cn"];
var suffixArr2= ["@hotmail.com" , "@gmail.com","@163.com","@qq.com ","@126.com","@yahoo.com.cn","@yahoo.com",'@sina.com','@yahoo.cn','@yahoo.com.tw','@sohu.com','@msn.com','@live.cn','@yahoo.com.hk','@tom.com','@yeah.net'];
(function(){
	jQuery.fn.autoComplete=function(options){
        var defaults={
            subBox:"#suggest",
            subOp:"li",
            id:"#email_address",
            suffixArr:suffixArr,
			suffixArr2:suffixArr2,
            hoverClass:"on",
            _cur:-1
        };
        var option = jQuery.extend({}, defaults, options || {});

        jQuery(option.id).keyup(function(e){
        	jQuery('#AccountErrorMessage').hide();
            var _that=jQuery(this);
            if(_that.val()!=""){
                if(e.keyCode!=38 && e.keyCode!=40 && e.keyCode!=13 && e.keyCode!=27 && e.keyCode!=9){
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
        		jQuery(this).val('<?php echo $account_input_blankvalue ?>');
        	}
        });

        jQuery(option.id).focus(function(){
        	if(jQuery(this).val()=="<?php echo $account_input_blankvalue ?>"){
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
                        jQuery(option.subBox).hide();
                        e.stopPropagation();
                        jQuery(option.id).focus();
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
                case 40:
                    option._cur++;
                    jQuery.fn.autoComplete.itemFun()
                    break;
                case 38:
                    option._cur--;					
                    jQuery.fn.autoComplete.itemFun()
                    break;
				case 9:
					jQuery("#suggest").hide();
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
    verified_email = false;  
    verified_name = false;    
    verified_password = false;
    verified_confirmation = false;
    verified_agreement = false ;
    verified_vvc = false;
    function validateResult(msg){
        this.code = -1;
        this.message = "null" ;
        if(typeof(msg) == "string"){
          var sepPos = msg.search(/,/);
          this.code = parseInt(msg.substring(0,sepPos),10);
          this.message = msg.substring(sepPos+1,msg.length);
        }
       this.isSuccess = function(){return this.code == 0 ;}     
       this.toString=function(){return "CODE:"+this.code+" MESSAGE:"+this.message ; }      
    }
    function updateVVC(){
    	 var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo(FILENAME_CREATE_ACCOUNT)) ?>");     
     	jQuery.get(url,{"action":"updateVVC",'random':Math.random()},function(data){
                    jQuery("#vvc").attr('src', data); 
           });
    }
    function verifyVVC(){
    	verified_vvc = false;
        var vvc = jQuery("#vvcInput").val();       
    	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo(FILENAME_CREATE_ACCOUNT)) ?>");   
    	var doSubmit = arguments.length>0;  
    	if(vvc == ""){
    		jQuery('#verify_vvc').html('<span class="errorTip">请输入图片中显示的字符，不区分大小写。</span>');
			jQuery('#verify_vvc').fadeIn("slow");
			return ;
    	}
    	jQuery.get(url,{"action":"validate","data":vvc,"validator":"vvc"},function(data){
                     result = new validateResult(data);                   
                     if(result.isSuccess()){
                    	 verified_vvc = true;             			
                    	jQuery('#verify_vvc').html('<span class="rightTip">验证码输入正确。</span>');
                    	jQuery('#verify_vvc').fadeIn("slow");
                    	 if(doSubmit)dosubmit();
         			}else{
         				jQuery('#verify_vvc').html('<span class="errorTip">'+result.message+'</span>');
         				jQuery('#verify_vvc').fadeIn("slow");
         			} 
          });
    }
    function verifyName(){    	
    	 verified_name = false;
         var name = jQuery.trim(document.regform.firstname.value);
         var error = '';
         if(name == ''){
             error = "请填写您的中文姓名或拼音。";
             verified_name = false;
         }else if(name.length < 2){
        	 error = "请填写您的全名。";
        	 verified_name = false;
         }else{
        	 var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo(FILENAME_CREATE_ACCOUNT)) ?>");  
        	 var doSubmit = arguments.length>0;     
        	jQuery.get(url,{"action":"validate","data":name,"validator":"name"},function(data){
                         result = new validateResult(data);
                         var verify = jQuery('#verify_firstname');
                         if(result.isSuccess()){        			
                        	jQuery('#verify_firstname').html('<span class="rightTip">您的姓名可以注册。</span>');
                        	jQuery('#verify_firstname').fadeIn("slow");
                        	 if(doSubmit)dosubmit();
             			}else{
             				
             				jQuery('#verify_firstname').html('<span class="alertTip">'+result.message+'</span>');
             				jQuery('#verify_firstname').fadeIn("slow");
             			} 
              });
        	verified_name = true;
         }
		if(error != ""){			
			jQuery('#verify_firstname').html('<span class="errorTip">'+error+'</span>');
			jQuery('#verify_firstname').fadeIn("slow");
		}
    }
    
    function verifyEmail(){
       if(jQuery("#suggest").css("display") !="none") {return false;}    
    	verified_email = false;
        var email_address = jQuery.trim(document.regform.email_address.value);        
        var emailPat = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
        if(email_address!="" && email_address.match(emailPat)!=null){
            var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo(FILENAME_CREATE_ACCOUNT)) ?>");      
            var doSubmit = arguments.length>0;     
            jQuery.get(url,{"action":"validate","data":email_address,"validator":"email"}
                    ,function(data){
                        result = new validateResult(data);
                        var verify = jQuery('#verify_email');
                        if(result.isSuccess()){
            				verified_email = true;
            				verify.fadeOut("slow");
            				verify.html('<span class="rightTip">您的邮箱地址可以注册。</span>');
            	        	verify.fadeIn("slow");            	        	
            	        	if(doSubmit)dosubmit();            	        	 
            			}else{
            				verify.html('<span class="errorTip">'+result.message+'</span>');
            				verify.fadeIn("slow");
            			} 
                        });
        } else{
        	var verify = jQuery('#verify_email');
        	verify.html('<span class="errorTip">请输入有效的邮箱地址。</span>');
        	verify.fadeIn("slow");
        }
        return verified_email;
    }

    function verifyPassword(fullcheck){          
    	verified_password = false;
    	var verify = jQuery('#verify_password') ;
    	var password = document.regform.password.value;
    	
    	if(typeof(fullcheck)!= 'undefined'){
    		if(password.length < 5){
    			verify.html('<span class="errorTip">请输入5-12个字符的密码。</span>');
            	verify.fadeIn("slow");
            	return false;
    		}
        }   
		if(password.length < 5) return ;
    	if(password.length < 5 || password.length > 12){
    		verify.html('<span class="errorTip">请输入5-12个字符的密码。</span>');
        	verify.fadeIn("slow");
        	return false;
    	}
    	if(password.match(/^[0-9]{5,12}$/)){
    		verify.html('<span class="normalTip">密码安全度：<b class="on">弱</b><b>中</b><b>强</b></span>');
    		verify.show();
    	}else if(password.match(/^[a-zA-Z0-9]{5,12}$/)){
    		verify.html('<span class="normalTip">密码安全度：<b>弱</b><b  class="on">中</b><b>强</b></span>');
    		verify.show();
    	}else if(password.match(/^[a-zA-Z0-9@\?-_\$%&=\*\)\(]{5,12}$/)){
    		verify.html('<span class="normalTip">密码安全度：<b>弱</b><b >中</b><b  class="on">强</b></span>');
    		verify.show();
    	}else{
    		verify.html('<span class="normalTip">密码安全度：<b>弱</b><b  class="on">中</b><b >强</b></span>');
    		verify.show();
    	}
    	verified_password = true;
    }

    function verifyConfirmation(){ 
    	verified_confirmation = false    ;	
        if(document.regform.password.value == "") {
        	var verify = jQuery('#verify_password') ;
        	verify.html('<span class="errorTip">请输入5-12个字符的密码。</span>');
        	verify.fadeIn("slow");
        	document.regform.password.focus();
        	return ;
        }            	
    	var verify = jQuery('#verify_password2') ;
        if(document.regform.password.value == document.regform.confirmation.value){        	      	
        	verified_confirmation = true;  
        	jQuery("#verify_password2").fadeOut("slow");      	
        }else {
        	verify.html('<span class="errorTip">两次密码输入不一致。</span>');
        	jQuery("#verify_password2").fadeIn("slow");
        }
        
    }

    function dosubmit(){
		console.log('verified_email=' + verified_email);
		console.log('verified_name=' + verified_name);
		console.log('verified_password=' + verified_password);
		console.log('verified_confirmation=' + verified_confirmation);
		console.log('verified_vvc=' + verified_vvc);
		console.log('================');
    	if(verified_email == true && verified_name == true&& verified_vvc == true && verified_password==true &&verified_confirmation == true ){         		
            //return true;
			jQuery('form[name="regform"]').submit();
    	} else {
			return false;	
		}
		
    }
    
    function validateFormData(){
    	if(verified_email == false) verifyEmail(1);
    	if(verified_name == false) verifyName(1);
    	if(verified_password == false) verifyPassword();
    	if(verified_confirmation == false) verifyConfirmation();
    	if(verified_vvc == false) verifyVVC(1);
    	dosubmit();    	

		return false;
    }
    </script>
<link rel="stylesheet" href="/templates/Original/page_css/login_reg.css"/>
<div class="pathLinks borderB_e6e6e6">您现在的位置：<a href="<?php echo HTTP_SERVER?>">首页</a> &gt; 注册新用户</div>
<?php if ($messageStack->size('create_account') > 0) {
	 echo '<div style="margin:8px;">' . $messageStack->output('create_account') . "</div>"; 
}?>
<div class="loginContent">
<?php #$isPointCardUser = $pointcards_id_display = 'S-58454511545674';?>

  <div class="tip"><?php if($isPointCardUser){?>
  提示：您已拥有$2，请修改个人信息再获得$8！<a class="font_bold font_size14" href="<?php echo tep_href_link(FILENAME_LOGIN)?>">已有帐号直接登录</a>
  <?php }else{?>
  提示：如果你已经注册<strong>“USITRIP”会员</strong>，请直接<a href="<?php echo tep_href_link(FILENAME_LOGIN)?>" class="font_bold font_size14">登录</a>
  <?php }?></div>
    <form name="regform" action="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT,'','SSL')?>"  method="POST"   enctype="application/x-www-form-urlencoded" onsubmit="">
  <div class="regtext">
            <?php if($pointcards_id_display){?>
          <dl>
            <dt><em class="color_orange">*</em>卡号:</dt>
            <dd><?php echo $pointcards_id_display;?><?php echo tep_draw_hidden_field('pointcards_code',$pointcards_code)?> </dd>
           </dl>
          <dl class="even">
            <dt>&nbsp;</dt>
            <dd><span ></span></dd> </dl>
          <?php }?>
    <dl style="z-index:1;position:relative">
      <dt><em class="color_orange">*</em> <?php if ((int)$Admin->login_id > 0) {?>客户<?php }else{?>常用<?php }?>邮件地址:</dt>
      <dd>
        <?php echo tep_draw_input_field('email_address',$_POST['email_address'],'tabindex="2" id="email_address" class="box1" autocomplete="off" onfocus="jQuery(\'#email_address\').addClass(\'on\');jQuery(\'#verify_email\').fadeOut(\'slow\')" onblur="jQuery(\'#email_address\').removeClass(\'on\');verifyEmail()"');?>
        <span class="color_b3b3b3">请输入<?php if ((int)$Admin->login_id > 0) {?>客户<?php }else{?>您常用<?php }?>的电子邮箱，将用于登录、接受订单通知等。 </span>
        <div class="suggest" id="suggest"></div>
        </dd>
    </dl>
    <dl class="even">
          <dt>&nbsp;</dt>
          <dd><span id="verify_email" style="display:none;"></span></dd>
    </dl>
    <dl>
      <dt><em class="color_orange">*</em> <?php if ((int)$Admin->login_id > 0) {?>客户<?php }else{?>常用<?php }?>姓名:</dt>
      <dd>
        <?php echo tep_draw_input_field('firstname' , $_POST['firstname'],' tabindex="3" id="firstname"  class="box1"  onblur=" jQuery(\'#firstname\').removeClass(\'on\'); verifyName();"  onfocus="jQuery(\'#firstname\').addClass(\'on\');jQuery(\'#verify_firstname\').fadeOut(\'slow\')"')?>
        <span class="color_b3b3b3">不少于2个字符，可以是汉字或拼音。</span></dd>
    </dl>
    <dl class="even">
            <dt>&nbsp;</dt>
            <dd><span id="verify_firstname" style="display:none;"></span></dd>
    </dl>
    <?php 
    // 如果是客服帮客人注册,则不出现密码输入框和验证码
    if ((int)$Admin->login_id == 0) {?>
    
    <dl>
      <dt><em class="color_orange">*</em> 设置密码:</dt>
      <dd>
        <?php echo tep_draw_password_field('password' ,'','tabindex="4" id="pass" class="box1"  onkeyup="verifyPassword();"  onfocus="jQuery(\'#pass\').addClass(\'on\');jQuery(\'#verify_password\').fadeOut(\'slow\');" onblur="jQuery(\'#pass\').removeClass(\'on\');verifyPassword(0);"')?>
        <span class="color_b3b3b3">5-12个字符，可以由数字、英文、符号组成。 </span></dd>
    </dl>
    <dl class="even">
            <dt>&nbsp;</dt>
            <dd><span id="verify_password" style="display:none;"></span></dd> 
    </dl>
    <dl>
      <dt><em class="color_orange">*</em> 确认密码:</dt>
      <dd>
        <?php echo tep_draw_password_field('confirmation' ,'','tabindex="5" class="box1" id="pass2"  onfocus="jQuery(\'#pass2\').addClass(\'on\');jQuery(\'#verify_password2\').fadeOut(\'slow\')" onblur="jQuery(\'#pass2\').removeClass(\'on\');verifyConfirmation()"')?>
        <span class="color_b3b3b3">请再输入一次密码。 </span></dd>
    </dl>
    <dl class="even" id="verify_password2_warp"  >
            <dt>&nbsp;</dt>
            <dd><span id="verify_password2" ></span></dd> 
    </dl>
    <dl>
      <dt><em class="color_orange">*</em> 验 证 码:</dt>
      <dd class="verification" ><?php echo tep_draw_input_field('visual_verify_code' ,'','tabindex="6" id="vvcInput" class="box2"  onfocus="jQuery(\'#vvcInput\').addClass(\'on\');jQuery(\'#verify_vvc\').fadeOut(\'slow\')" onblur="jQuery(\'#vvcInput\').removeClass(\'on\');verifyVVC()"' )?> 
        <img src="<?php echo $RandomImg?>"  onclick="updateVVC()" align="absmiddle" id="vvc" width="75" height="25"  alt="看不清?点击换一张图。" title="看不清?点击换一张图。"/> <a href="javascript:;" onclick="updateVVC()">看不清?点击换一张图。</a></dd>
    </dl>
    <dl class="even" id="vvc_warp"  >
            <dt>&nbsp;</dt>
            <dd><span id="verify_vvc" ></span></dd>
    </dl>
    <?php }?>
    <dl class="dlbtn">
      <dt></dt>
      <dd>
        <input type="submit" class="submit" value="同意我们的协议并注册" onclick="return validateFormData()"/>
      </dd>
    </dl>
	<div>
          <input type="hidden" name="action" value="process">
          <!--<a href="javascript:;"   class="btn btnOrange btnReg"  onclick="validateFormData();">
          <button type="button" tabindex="7">同意以下协议并注册</button>
          </a>--> </div>
        <div class="agreement">
          <h3>点击上面的“同意我们的协议并注册”，即表示您同意接受我们的<a target="_blank" href="<?php echo tep_href_link('order_agreement.php')?>">订购协议</a>。</h3>
          <?php /*<div class="agreementCon" >
            <p>请在预订和购买走四方网（usitrip.com）网站上的旅行团以及旅行团相关的产品前仔细阅读此顾客须知的各项条款，以便您能全面了解双方的权利和责任。作为走四方网的顾客，您必须认同您所阅读的内容，理解并同意以下各项条款，并赞成其所有内容, 包括其它的一些更新， 走四方网保持最终解释权。 </p>
            <br>
            <p style="padding-bottm:10px;">走四方网注册商标<img src="image/logo_s.gif" alt="此商标归走四方网所有。" title="此商标归走四方网所有。" style="vertical-align:middle;" />。</p>
            <p> <strong>旅行权益 </strong><br>
              导游在旅行途中会为客人推荐自费项目，客人自主决定是否参加。<br />
              行程中导游若有违反合同之处，旅客有权抵制。如有争议，请旅客和导游先行友好协商，并请及时联络我们反馈，我们将协调解决。为了有效的维护旅客的利益，请旅客在争端开始前联络我们调解。行程结束之后的投诉，如无证据证明，旅行社保留不予受理的权力。<br/>
              线路产品组成要素，均为经过走四方网严格考评甄选出的具备相关资质的地接社提供，走四方网只对其硬件设施等标准的描述和承诺承担责任，不对其在您消费过程中可能涉及的人员软性服务或者不可抗力造成的不便承担责任。 </p>
            <br>
            <br>
            <p> <strong>您的资格</strong><br>
              您必须是18岁或18岁以上的个体。 如果您未满18岁, 您可以在父母或法律监护人的带领下使用走四方网。 </p>
            <br />
            <p> <strong>您的责任</strong> <br>
              在您预订前, 您有责任阅读所有和您想要购买团的相关信息，包括∶价格与通知、路线介绍、 团费包含哪些，不包含哪些、取消和退款政策、各项条款，以及特别提示等。一旦您阅读并充分理解其所有内容后，在购买后就不得有任何异议。 </p>
            <br />
            <p> <strong>订票程序及电子版团票</strong> <br>
              1.在您提交预订之后，您立马会通过E-Mail收到一个预订收据。<br>
              2.在您提交预订的一两个工作日内，您会收到我们发给您的确认邮件。<br>
              3.电子版团票会在您出发前的二至三天，或者更快的时间内，通过邮件发送给您，在电子版团票里我们会提供您出团的所有详细信息。为了您的方便与再次确认，当地旅行团供应商的信息我们将一并发送给您。<br>
              4.您只需要打印出您的电子版团票，并在出团当天附上您带有照片的有效身份证，出示给导游便可以了。 请记住，电子版团票是您的购买凭证。</p>
            <p>您可以在出发前联系您的航班和当地旅行团供应商以再次确定您的抵达接机事宜 </p>
            <br />
            <p> <strong>隐私及数据收集 </strong><br>
              走四方网 是网站收集信息的唯一所有者， 我们不会把信息出卖、分享或是租赁给任何团体。走四方网 从不同的站点收集用户信息，用以处理订单和更好的用相关信息为您服务。信息包括姓名、运送地址、帐户地址、电话号码、电子邮件地址以及付款信息，比如信用卡。走四方网 还需要您提交您的用户名及密码以便访问您的信息。您得保证您的用户名和密码是机密的并不能与其它任何人分享。 </p>
            <p> 走四方网的信息安全使用将会在<a class="sp3" href="privacy-policy.php">隐私和安全政策</a>里详细介绍. 走四方网的隐私和安全政策是此协议的一部分，并且您同意给予我们对其所提及数据的使用权，不视之为侵犯您的隐私和公众权利。<br>
              <br>
            <p> <strong>注册及登录 </strong><br>
              为了让您更方便及时地参与走四方网推出的一些优惠活动，我们可能会设置：当您登录您在走四方网注册会员使用的邮箱并点击走四方网注册或者活动的邮件信息链接到达走四方网时，走四方网会默认您已经登录了走四方网网站。走四方网确保不会泄漏您的任何个人信息，但请务必确保您邮箱的安全。</p>
            <br>
            <br>
            <p> <strong>旅游保险</strong><br>
              走四方网 强烈推荐您购买医疗、行程取消以及行李等保险项目。走四方网 现暂不提供任何行程安排，网站上列出的旅游产品都是由独立的旅行团供应商来实施操作的。我们是旅行团供应商、运送、观光和旅馆住宿的代理商。走四方网 不对意外事故承担任何责任，包括丢失损坏物品，救护伤员，途中死亡，以及一切的推迟、不规则操作。解决事宜会遵循航空公司、酒店和公共汽车公司等指定的规则。走四方网不对任何由于您和旅行团供应商或第三方争论而引起的开销或损害负责，包括一切涉及到本网站或使用网站内信息的事宜，您不得发表任何针对走四方网以及它的所有成员及附属机构的言论。 </p>
            <br />
            <br>
            <p> <strong>订团修改和取消</strong> <br>
              旅行团供应商会尽量保证行程安排和预计的保持一致。但为了保证行程顺利, 旅行团供货商保留对由于天气、交通、旅行游览车临时发生故障和其它一些无法控制的原因所导致对行程更改、推迟和取消的权利。<br>
              旅行团供应商保留在出发前如果参团人数不足以成团的情况下取消行程的权利，走四方网会提前通知。<br>
              旅行团供应商保留因为参团人数不足而调换游览车的权力。<br>
              旅行团供应商所有临时采取的行为, 均会从整个团体利益出发而考虑。此行为与走四方网完全无关，但是走四方网会全力协助走四方贵宾争取权宜。<br>
            </p>
            <br />
            <p> <strong>护照和签证</strong> <br>
              您有责任携带一切旅游证件和/或进入和/或经由您选择路线中国家所必须的一切必备证件。不同国籍的人会有不同的入境法律。走四方网 不会保留顾客的私人旅行证件或是承担通知每个国家现行所需证件的责任。您要承担由于缺少旅行证件而造成的推迟或行程更改的所有费用。 </p>
            <p> 您应当严格遵守法律以及游览国家政府所发布的法规，包括移民和海关法律条例等。走四方网 不对您由于违背游览国家政府法规而产生的罚款负责。</p>
            <br />
            <p> <strong>价格、取消以及退款政策</strong> <br>
              所有的旅游产品价格都是以美元计算。 不同时期的价格会有所不同。 比如，价格在节假日会有所增长。 所有价格都需要加以确认。 具体细则我们会在<a class="sp3" href="cancellation-and-refund-policy.php">取消和退款政策</a>.走四方网 的取消和退款政策是此协议的一部分，在您预订前您要确保已经阅读并同意其所有内容。 </p>
            <br />
            <p> <strong>关于买二送二和买二送一的团</strong><br />
              只在适用的条件下生效。如在行程确认后进行修改或取消，需要交纳一定的费用。提前于出团日7天及以上取消免费参团人行程，将额外收取$30.00的取消费用。提前于出团日7天以内取消免费参团人行程，将按取消和退款条例进行收费。如免费参团人于出团当天缺席，将根据每个团设定的标准对其收取一定数量的罚金（每人）。罚金将由导游以现金的方式强制性从其他参团人处收取。<br />
              更多关于走四方网取消和退款条例的信息请参考取消和退款条例。走四方网客户协议、取消和退款条例均属于协议内容。同意协议表示您在预定前已经阅读并同意协议内容。 </p>
            <br />
            <p> <strong>自行退款</strong><br>
              您不得对走四方网 使用Discover, MasterCard, VISA, American Express 或任何银行信用卡的电子记录退款而产生异议。同意走四方网 通过电子邮件操作工作, 所以我们提供的服务是以邮件形式传送的。您不能要求团票亲自领取或是邮寄，接受电子邮件作为传送方式并通知Discover, MasterCard, VISA, American Express 和发行信用卡的银行我们会通过电子邮件传送来为您您预订服务。如果走四方网 可以提供电子版团票已经通过电子邮件传送出去（提供电脑系统记录数据和时间证明）；或是通过传真（提供显示已传送到指定传真号码成功的复印件证明）；或是通过邮寄（提供特定某人已在美国邮政服务处已投递团票的签名陈述），您就不得对已获取基本服务而收费用进行争论。 <br>
              您不得不正当的对收费进行争论。例如在走四方网不认同的情况下自行退款。 如果您不适当的自行退款(只要 走四方网 判定)，您得退还所有费用。如果您不正当的自行退款，您必须提供一个手写给信用卡公司，确定费用是合理正当的声明，并传真一份此信件的复本到走四方网，
              对于从网站自行退款，走四方网将采取法律行动将其取回，并收取自行退款费用。 如果走四方网诉讼成功，您还将承担诉讼费用。 </p>
            <br />
            <p><strong>责任划分</strong><br>
              走四方网将尽力给顾客提供正确、完善和最新的信息，但也不排除会有一些技术和排版上的错误存在。顾客在使用前有义务进行核实。我们有权在不提前通知的情况下作任何修改和更新。顾客自行承担访问使用网站信息的所有风险。我们会提供一些其它网站的链接以方便您访问。这些网站与走四方网无任何关系，我们不对其提供内容负责。您在选择时要提高警惕，以免在使用时感染病毒或对您造成任何损害。<br>
              <br>
              <strong>法律声明</strong><br>
              该协议遵循并依照美国加利福尼亚州法律之内容，不与其法律条款抵触。一旦顾客对走四方网的进行使用并享用其提供之服务，即视为同意遵守洛杉矶和加利福尼亚法院所有的个人专属管辖权，并因此同意无条件遵守洛杉矶县内法院规定之所有法律条款。一旦该协议中的条款出现无效或非强制性的规定，该规定必须最大限度的得到强制执行。强制过程中包含的其他规定仍然发挥充分法律效力。走四方网不支持或强制执行该协定的任何规定的行为不视为对任何权限和规定的弃权。该协定不能用作走四方网与产品供应商或任何其他公司团体合作、任命、合资和代理等关系之凭证和依据。产品供应商和任何其他公司团体均无权以走四方网的名义行使任何义务和职责。该协议是顾客和走四方网间针对顾客对走四方网的使用而制定的。</p>
          </div> */ ?>
        </div>

    <div class="del_float"></div>
  </div>
  </form>
</div>
<script type="text/javascript">
jQuery("#email_address").autoComplete();
</script>
<?php 
	echo db_to_html(ob_get_clean());
	if($messageStack->size('create_account') > 0 ) echo $messageStack->output_newstyle('create_account','verify_email');
 ?>
