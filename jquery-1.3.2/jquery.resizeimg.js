if(jQuery)(
	function(jQuery){
		jQuery.extend(jQuery.fn,{
			resizeImage:function(options) {
				settings = {
					height         : 'noresize',
					width          : 'noresize',
					waterimg       : '',/* Ë®Ó¡Í¼Æ¬ */
					waterpos       : '7',/* 0Ëæ»úÎ»ÖÃ 1¶¥²¿¾Ó×ó 2¶¥²¿¾ÓÖÐ 3¶¥²¿¾ÓÓÒ 4ÖÐÐÄÎ»ÖÃ 5µ×²¿¾Ó×ó 6µ×²¿¾ÓÖÐ 7µ×²¿¾ÓÓÒ */
					onComplete     : function() {}
				};
				if(options) {
					jQuery.extend(settings, options);
				}
				jQuery(this).each(function(){
					/*jQuery(this).live('load',function(){
						___exec_resizeImage__(jQuery(this),settings);
					});
					*/
					___exec_resizeImage__(jQuery(this),settings);
				});/*each end*/
				function ___exec_resizeImage__(ImgD,settings) {
					var data = {};
					var image=new Image();
					image.src=ImgD.attr('src');
					data.source_w = image.width;
					data.source_h = image.height;
					
					if(image.width>0 && image.height>0){
						if(settings.width!='noresize' && settings.height=='noresize'){
							if(image.width>settings.width){  
								ImgD.width(settings.width);
								ImgD.height( (image.height*settings.width)/image.width );
							}
						}else if(settings.width=='noresize' && settings.height!='noresize'){
							if(image.height>settings.height){  
								ImgD.height=settings.height;
								ImgD.width=(image.width*settings.height)/image.height;
							}
						}else if(settings.width!='noresize' && settings.height!='noresize'){
							if(image.width/image.height>= i_width/settings.height){
								if(image.width>settings.width){  
									ImgD.width=settings.width;
									ImgD.height=(image.height*settings.width)/image.width;
								}
							}else{
								if(image.height>settings.height){
									ImgD.height=settings.height;
									ImgD.width=(image.width*i_height)/image.height;
								}
							}
						}
					}
					
					if (typeof(settings.onComplete) == 'function') {
						settings.onComplete(data, ImgD);
					}
				}
			}/*resizeImage end*/
		});/*extend end*/
})(jQuery);