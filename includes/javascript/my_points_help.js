<script language="javascript"><!--
//window.onload=show;

function show(id) {
var d = document.getElementById(id);
	for (var i = 1; i<=20; i++) {
		if (document.getElementById('answer_q'+i)) {document.getElementById('answer_q'+i).style.display='none';}
	}
	
    if (d) {
	    d.style.display='block';
	    d.className='pointFaq';
    }
}
//--></script>
