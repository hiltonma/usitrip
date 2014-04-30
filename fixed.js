// JavaScript Document
var isie6 = window.XMLHttpRequest?false:true;

window.onload = function(){

    var a = document.getElementById('cl_table_sticky');

    //var d = document.getElementById('d');

   if(a!=null){
	   if(isie6){
	
			 a.style.position = 'absolute';
	
			
	
			/*window.onscroll = function(){
	
			d.innerHTML ='';
	
			}
			*/
	
	   }else{
	
		  a.style.position = 'fixed';
	
	   }

      a.style.right = '56px';

      a.style.bottom = '3px';
   }

}