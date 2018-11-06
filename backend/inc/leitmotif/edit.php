<?php

	$lId = base64_decode( $_GET[ 'lId' ] );
	$old_oid = base64_decode( $_GET[ 'oId' ] );
	if(isset($_GET['lId']))
	{
		$lId = base64_decode( $_GET[ 'lId' ] );
		if( isset( $_GET['ac']))
		{	
			if ( $_GET['ac'] == "save" )
			{
				if (( isset( $_POST[ 'htmlsrc' ] )) || isset( $_POST[ 'corder'] ))
				{
					$lDesc=trim( $_POST['htmlsrc'] );
					$old_oid = $_POST['new_oid'];
					$new_oid=$_POST['corder'];
					if (( strlen($lDesc) === 0 ) )	
						$flag = 0 ;
					else
						$flag = 1 ;
					if( $flag === 1)
					{
						$q="update leitmotif set lDesc='$lDesc', lModifydate=now() where 
						lId='$lId';";
						if( ( $res = $hlp->_db->db_query( $q ) ) !== false )
						{
							$success = $hlp->change_ord_no($lId ,$old_oid, $new_oid,"leitmotif");
							if( $success !== false )
							{
								$hlp->echo_ok( "Leitmotif has been updated." );
							}
							else
								$hlp->echo_err( "Sorry, unable to add record. contact support." );
							
						}
						else
							$hlp->echo_err( "Sorry, unable to add record. contact 
							support." );
					}
					else
						$hlp->echo_err("Please enter appropriate values in all the fields"); //flag === 1?
				}// end of if
				else
					$hlp->echo_err("Error occured"); //flag === 1?
			}
		}
	}
	$q = "select * from leitmotif where lId='$lId';";
	if( ( $res = $hlp->_db->db_query( $q ) ) !== false )
	{
		while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
		{   
			$lDesc = trim($row[ 'lDesc' ]);
			$curr_order_id = $row['order_no'];
			
		}
	}	
	$uri = $me."?b=leitedt&lId=".base64_encode( $lId )."&ac=save";
	echo("<div>
			<form name=frmWrt method='post' action=".$uri." enctype='multipart/form-data'>
				<div style='padding-top:10px' >
					<div style='width:80px;float:left;padding-left:10px;' >Description : </div><br />
					<div style='padding-top:10px;' ><textarea id='htmlsrc' name='htmlsrc' rows='20' cols='80' style='width: 
		95%'>$lDesc</textarea></div> 
					<input type=hidden name='new_oid' value='$curr_order_id'>
				</div>
				<div style='padding-top:10px' ><div style='width:80px;float:left;padding-left:10px;' >Priority : </div><div>
				".$hlp->order_disp($curr_order_id,'TAB_LEIT',$uri,'frmWrt',"leitmotif")."
				</div>
				</div>
				
				<div style='width:320px;text-align:center;padding-top:10px;' ><button type=submit class=roundbutton style='wid
				th:100px;' >Save</button></div>	
		  </form>
		
		</div>");
		
		echo( '<body>' );

	echo( '<div class=gencon style="padding-top:3px;">' );
	$toolbar_type = "BasicToolbar";
	require("ckeditor/init.php");
	$CKEditor = new CKEditor();
	$CKEditor->returnOutput = true;
	
	echo($CKEditor->replace("htmlsrc",array("toolbar"=>$toolbar_type)));
		
?>