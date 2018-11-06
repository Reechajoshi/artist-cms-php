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
			//if($hlp->is_backend_login_ok())
			//	require('backend/aloha/inc.php');
		?>

		<script type="text/javascript">
			$(document).ready(function(){			

			$('.thumbnailBlockImage').colorbox({rel:'group2'}); 
			
			<?php
				require('js/inc.swatches.js');

				//if($hlp->is_backend_login_ok())
				//{
				/*	Aloha.ready( function() {
						var	$ = Aloha.jQuery,
							$body = $('body');
							
						$('#part1L').aloha();
						$(this).hide();
					});*/
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

		<div class="contentInnerBlock">
			<?php
			
				$q="select * from currently order by order_no desc;";
				$res = $hlp->_db->db_query( $q );	
			
			
				while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
				{
					
					$cTitle=$row[ 'cTitle' ];
					$cTitleLink = trim($row[ 'cTitleLink' ]);  
					$cDesc = nl2br($row[ 'cDesc' ]);
					$cImgTitle = $row[ 'cImgTitle' ];
					$cimgName = $row[ 'cImgId' ];
					$imgPath = "backend/user/currently/$cimgName/original.jpeg";
					
					echo("<div class='linkCurrently'>

							<div class='linkCurrentlyText'>");
							if($cTitleLink=="")
								echo("<p><strong>$cTitle</strong></p>");
							else
								echo("
								<a href='$cTitleLink' target='_blank'>
									<strong>$cTitle</strong>
								</a>");
							echo("
								<p>
									$cDesc
								</p>

							</div>
							<div class='linkCurrentlyImg'>
								<img src='$imgPath' alt='$cImgTitle' title='$cImgTitle'>
							</div>
						</div>
					");
				}
			?>

		</div> <!-- closing contentInnerBlock -->

</div> <!-- closing wrapper -->


</body>
</html>
