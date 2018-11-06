<?php
	require('backend/conf/vars.php');
	require('backend/helper/class.helper.php');
	
	$hlp = new chlp(true);
	
	$GROUP_CHARCOALS_I = 'b125a616536dda7fcf0eecc4040fceda'; //TODO: SET FROM DB NOT HARD CODE
	$GROUP_OILS_I = '88de4f77ba6b3c8b15a3003268646097'; //TODO: SET FROM DB NOT HARD CODE
	
	if( isset($_GET['g'] ) )
	{
		$GROUP_I = $_GET['g'];
	}
	else
	{
		$GROUP_I = $GROUP_CHARCOALS_I;
	}
?>