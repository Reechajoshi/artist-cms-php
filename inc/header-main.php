<p id="swatches" title="swatches: click to change">
	<span id="default"></span>
	<span id="white"></span>
	<span id="black"></span>
</p> <!-- closing swatches -->

<a href="index.html.php" id="logoBlock">
	<img src="img/sign.jpg" alt="shernavaz-signature" id="signature">
	<p id="logo">shernavaz</p>
</a> <!-- closing logo block -->


<ul id="navMenu">
<?php
	require('inc/menu_mostly_charcoals.php');
	require('inc/menu_mainly_oils.php');
?>

	<li><a href="about.html.php">about</a>
    	<ul>
			<li><a href="currently.html.php">currently</a></li>
<?php
	if(intval(file_get_contents('usr/letimotif/count.txt'))>0)
		echo('<li><a href="leitmotif.html.php">leitmotif</a></li>');
?>
        </ul>
    </li>

	<li><a href="contact.html.php">contact</a></li>
</ul> <!-- closing nav menu -->