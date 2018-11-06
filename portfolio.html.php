<?php
	require('inc/init.php');
?>
<!DOCTYPE HTML>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta name="keywords" content=""> 
<meta name="title" content=""> 
<meta name="description" content="">

<title></title>

<link rel="shortcut icon" href="img/favicon.ico">

<link rel="stylesheet" href="css/reset.css" type="text/css">
<link rel="stylesheet" href="css/main.css" type="text/css">
<link rel="stylesheet" href="css/colorbox.css" type="text/css">

<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->

<script type="text/javascript" src="js/helper.js"></script> 

<script type="text/javascript" src="js/jquery-1.7.0.min.js"></script>
<script type="text/javascript" src="js/colorbox.js"></script>
<script type="text/javascript" src="js/jquery.lazyload.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$('.thumbnailBlockImage').colorbox({rel:'group2'});  
	//$("img").lazyload();
	/*
	try {
		for( ix=1; ix<=9; ix++)
		{
			if($("#lz"+ix))
				$("#lz"+ix).trigger("appear");
		}
	} catch(e) {}*/
<?php
	require('js/inc.swatches.js');
?>

$('.thumbAll').hover(function(){
        var thumbcap = $(this).find('.captionThumb'); //find the captionThumb class div within this div being hovered over
        var caphover = $(this).prevAll('.captionHover').eq(0); // get the first previous .captionHover div
		thumbcap.clone().hide().appendTo(caphover).fadeIn(700);
	}, function() {
        var caphover = $(this).prevAll('.captionHover').eq(0);
        caphover.empty();
    });

   });
</script>

</head>


<body>

<div id="wrapper">

<?php
	require('inc/header-main.php');
?>

<div class="clear"></div>

<div id="thumbnailBlock">
<?php
	require('inc/portfolio_thumb.php');
?>
	
<div class="clear"></div>

<!--
	<a href="" id="nextPage" >&#8594;</a> 

	<a href="" id="previousPage">&#8592;</a>
 -->
<div class="clear"></div> 

</div>

</div> <!-- closing wrapper -->

</body>
</html>
