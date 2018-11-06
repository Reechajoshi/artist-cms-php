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

<script type="text/javascript" src="js/helper.js"></script> 

<link rel="shortcut icon" href="img/favicon.ico">

<link rel="stylesheet" href="css/reset.css" type="text/css">
<link rel="stylesheet" href="css/main.css" type="text/css">

<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 

<script type="text/javascript" src="js/colorbox.js"></script> 

<?php
	if($hlp->is_backend_login_ok())
		require('backend/aloha/inc.php');
?>

<script type="text/javascript">
$(document).ready(function() {
<?php
	if($hlp->is_backend_login_ok())
	{
?>
		Aloha.ready( function( ) {
			var	$ = Aloha.jQuery,
				$body = $('body');
				
			$('#aboutUs').aloha();
			$(this).hide();
		});
<?php
	}
?>
	$('.thumbnailBlockImage').colorbox({rel:'group2'});
<?php
	require('js/inc.swatches.js');
?>

   });
</script>


</head>

<body>

<div id="wrapper">

<?php
	require('inc/header-main.php');
?>

<div class="clear"></div>

<div id="aboutUs" class="contentInnerBlock">
<?php
	require('usr/about.txt');
?>
</div> <!-- closing about us -->

</div> <!-- closing wrapper -->

</body>
</html>
