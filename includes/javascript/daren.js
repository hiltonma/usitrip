/*!
 * global.js
 * Date: 2011-5-5
 *--------------------------*/



/* Í¶Æ± */

function votes(id){	
	jQuery.ajax({
		   beforeSend: function(){
				showLoading();	
		   },
           url:url_ssl('tours-talent-contest.php?mod=votes'),
           data:"works_id=" + id,
           type:"POST",
           cache: false,
           dataType:"html",           
           success: function(html){   
              jQuery("#PopupLoading").hide();
              var re = parseInt(html);              
              if (re > 0){		
			  	jQuery("#votes_"+id).html('<label>Æ±Êý£º</label>'+html);
			  	showSuccess();				                
			  }else{	                
			  	showError();			
			  }              
           },
           error: function (msg){
               alert(msg);
           }
     });
}
function showLoading(){
    new Popup('PopupLoading','PopupLoadingCon','PopupLoadingClose',true);
}
function showSuccess(){
    new Popup('PopupSuccess','PopupSuccessCon','PopupSuccessClose',true);
}
function showError(){
    new Popup('PopupError','PopupErrorCon','PopupErrorClose',true);
}

