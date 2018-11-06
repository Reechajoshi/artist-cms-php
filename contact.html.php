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

<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->

<script type="text/javascript" src="js/helper.js"></script> 
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/colorbox.js"></script> 

<script type="text/javascript">
$(document).ready(function(){		
	$('.thumbnailBlockImage').colorbox({rel:'group2'});
<?php
	require('js/inc.swatches.js');
?>
} );
</script>

</head>

<body>

<div id="wrapper">

<?php
	require('inc/header-main.php');
?>

<div class="clear"></div>

<div id="aboutUs" class="contentInnerBlock">

<a href="mailto:s@shernavaz.com" target=_blank id="contactEmail">s@shernavaz.com</p>

<a href="https://www.facebook.com/shernavaz.colahzt" target="_blank"><img src="img/facebook-bw.png" width="32" height="32" alt="facebook-icon"></a>

<div class="clear"></div>

</div> <!-- closing contentInnerBlock -->

</div> <!-- closing wrapper -->

</body>
</html>
