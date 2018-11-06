<?php

		$cId = base64_decode( $_GET[ 'cId' ] );
		$old_oid = base64_decode( $_GET[ 'oId' ] );
		if( isset( $_GET['ac']))
		{	
			if ( $_GET['ac'] == "save" )
			{
				if ( isset( $_POST[ 'ctitle' ] ) || (isset( $_POST[ 'htmlsrc' ] )) || (isset( $_POST[ 'cimgtitle' ] ) ||isset( $_POST[ 'corder'] )))
				{
					$new_oid=$_POST['corder'];
					$cTitle=trim( $_POST['ctitle'] );
					$cTitleLink=trim( $_POST['ctitlelink'] );
					$cDesc=stripslashes(trim( $_POST['htmlsrc'] ));
					$cImgTitle=trim( $_POST['cimgtitle'] );
					$old_oid = $_POST['new_oid'];
					if( ( strlen($cTitle) === 0 ) || ( strlen($cDesc) === 0 ) || strlen(
					$cImgTitle) === 0 )	
						$flag = 0 ;
					else
						$flag = 1 ;
					if( $flag === 1)
					{
						$q="update currently set cTitle='$cTitle',cTitleLink='$cTitleLink',cDesc='$cDesc',cModifydate=now() where cId='$cId';";
						if( ( $res = $hlp->_db->db_query( $q ) ) !== false )
						{
							$success = $hlp->change_ord_no($cId ,$old_oid, $new_oid,"currently");
							if( $success !== false )
							{
								$hlp->echo_ok( "Events has been updated." );
							}
							else
								$hlp->echo_err( "Sorry, unable to add record. contact support." );
						}
					}
					else
						$hlp->echo_err("Please enter appropriate values in all the fields"); //flag === 1?
				}// end of if
				else
					$hlp->echo_err("Error occured"); //flag === 1?
			}
		}
	
	$q = "select * from currently where cId='$cId';";
	if( ( $res = $hlp->_db->db_query( $q ) ) !== false )
	{
		while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
		{
			$cTitle=$row[ 'cTitle' ];
			$cTitleLink = trim($row[ 'cTitleLink' ]);         
			$cDesc = trim($row[ 'cDesc' ]);
			$cImgTitle = $row[ 'cImgTitle' ];
			$curr_order_id = $row['order_no'];
		}
	}	
	$uri = $me."?b=contedt&cId=".base64_encode( $cId )."&ac=save";
	echo("<div>
			<form name=frmWrt method='post' action=".$uri." enctype='multipart/form-data'>
				<div style='padding-top:10px' >
					<div style='width:80px;float:left;padding-left:10px;' >Title : </div>
					<div><input type=text maxlength='200' name=ctitle value='$cTitle' style='width:200px;' ></div>
					<input type=hidden name='new_oid' value='$curr_order_id'>
				</div>
				<div style='padding-top:10px' >
					<div style='width:80px;float:left;padding-left:10px;' >Title Link: </div>
					<div><input type=text maxlength='200' name=ctitlelink value='$cTitleLink' style='width:200px;' ></div>
				</div>
				<div style='padding-top:10px' >
					<div style='width:80px;float:left;padding-left:10px;' >Description : </div><br />
					<div style='padding-top:10px;' ><textarea id='htmlsrc' name='htmlsrc' rows='20' cols='80' style='width: 
		95%'>$cDesc</textarea></div> 
				</div>
				<div style='padding-top:10px' >
					<div style='width:80px;float:left;padding-left:10px;' >Image Title : </div>
					<div><input type=text maxlength='200' value='$cImgTitle' name=cimgtitle style='width:200px;' ></div>
				</div>
				<div style='padding-top:10px' >
				
				<div style='padding-top:10px' ><div style='width:80px;float:left;padding-left:10px;' >Priority : </div><div>
				".$hlp->order_disp($curr_order_id,'TAB_CURRENT',$uri,'frmWrt',"currently")."
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