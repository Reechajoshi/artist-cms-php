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
<link rel="stylesheet" href="css/flexslider.css" type="text/css">
<link rel="stylesheet" href="css/colorbox.css" type="text/css">


<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->

<script type="text/javascript" src="js/helper.js"></script>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.flexslider.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		 $('.flex-container').flexslider({
			 animation:"fade",
			 controlsContainer: ".flex-container",
			 start: function(slider) {
				var $currentSlide = slider.find('ul').children('li').not('.clone').eq(0);
				var txt = $currentSlide.find('p').html();
				$('#captionExhibition').html(txt); 
			 },
			 after: function(slider) {
				var $currentSlide = slider.find('ul').children('li').not('.clone').eq(slider.currentSlide);
				var txt = $currentSlide.find('p').html();
				$('#captionExhibition').html(txt);						
			 }
		});
		
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

<div id="captionExhibition">
	
</div>

<div class="flex-container" id="flexExhibition">
	<div class="flexslider">
	 	<ul class="slides">
<?php

	$q="select * from leitmotif order by order_no;";
				$res = $hlp->_db->db_query( $q );	
			
			
				while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
				{
					$lDesc =trim($row[ 'lDesc' ]);
					$limgName = $row[ 'lImgId' ];
					$imgPath = "backend/user/leitmotif/$limgName/original.jpeg";
					echo("<li><img src='$imgPath'><p class='flex-caption'>");
					echo("$lDesc");
					echo("</p></li>");
				}
	
?>
	  	</ul>
	</div> <!-- closing flexslider -->
</div> <!-- closing flex exhibition -->

<div class="clear"></div>

</div> <!-- closing wrapper -->

</body>
</html>
