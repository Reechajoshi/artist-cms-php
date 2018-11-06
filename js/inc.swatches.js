var sth = CUtil.getCookie('sth');
if(sth == 'darkGrey' || sth == 'whiteSwatch' || sth == 'lightGrey')
	$('body').attr('id',sth);
	
//swatches below

$('#black').click(function() {
	$('body').attr('id','darkGrey');
	CUtil.setCookie('sth', 'darkGrey');
});

$('#white').click(function(){
	$('body').attr('id','whiteSwatch');
	CUtil.setCookie('sth', 'whiteSwatch');
});

$('#default').click(function(){
	$('body').attr('id','lightGrey');	
	CUtil.setCookie('sth', 'lightGrey');
});
