<?php
$uri = $me.'?b=contnew';
$flag = 1;


if ( isset( $_POST[ 'ctitle' ] ) || (isset( $_POST[ 'htmlsrc' ] )) || (isset( $_POST[ 'cimgtitle' ] )))
{
	$cTitle=trim( $_POST['ctitle'] );
	$cTitleLink=trim( $_POST['ctitlelink'] );
	$cDesc=addslashes(trim( $_POST['htmlsrc'] ));
	$cImgTitle=trim( $_POST['cimgtitle'] );
	$cId=$hlp->getunqid( $cTitle );
	if( ( strlen($cTitle) === 0 )|| ( strlen($cDesc) === 0 ) || strlen($cImgTitle) === 0 )	
		$flag = 0 ;
	if( $flag === 1)
	{
		if(!$_FILES['img']['name']=="")
		{			
			foreach($_FILES as $f)
			{
				if($f['error']==0)
				{
					$iname = $f['name'];
					$s = @getimagesize($f['tmp_name']);
					if(isset($s['mime']))
					{
						$m = $s['mime'];
						if(($m=='image/jpeg')||($m=='image/gif')||($m=='image/png')||($m=='image/jpg')||($m==						'image/tiff')||($m=='image/tif') )
						{
							$iext = explode('/',$m);	
							$unqid = $hlp->getunqid( $cTitle );
							$imgname = $hlp->getunqid( $unqid );
							
							$imgDir = "user/currently/$imgname" ;
							
							$cmd1="mkdir $imgDir";
							
							exec ( $cmd1, $outa, $ret );
							
							$tmpPath = "$imgDir/temp.".$iext[1];							
							$currentImagePath = "$imgDir/original.jpeg";
							
							$MainImagePath = "$imgDir/main.jpeg";
							
							$continue = true;
							if( move_uploaded_file( $f['tmp_name'], $tmpPath ))
							{
								$size = filesize( $tmpPath );
								$cmd = "convert '$tmpPath' '$currentImagePath' ";
								exec ( $cmd, $outa, $ret );
								if( intval( $ret ) === 0 )
								{
									$s = @getimagesize( $currentImagePath );
									if($s[0] >= $s[1]) // width greater then height
										$cmdMain = "convert '$currentImagePath' -geometry 1200x  '$MainImagePath
										' ";
									else
										$cmdMain = "convert '$currentImagePath' -geometry x900  '$MainImagePath'";
									
									exec ( $cmdMain, $outa, $ret );
									if($ret !== 0)
									$continue = false;
									@unlink( $tmpPath );
									$size = filesize( $currentImagePath );
									$m = "image/jpeg";
									$iext[1] = "jpg";
								}
								else
								{
									@unlink( $tmpPath );
									rmdir( "$imgDir" );// rmdir as command if failed
									$continue = false;
								}
								
								if( $continue )
								{
									$q1="select count(*)  cnt, max(order_no) max_count from currently;";
									if( ( $res = $hlp->_db->db_query( $q1 ) ) !== false )
									{
										while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
										{		
											$cntx=  $row['cnt'];
											$max_ord= $row['max_count'];
											
										}
									}
									$cnt = intval( $cntx );
									
									if( $cnt > 0 )
									{ 
										$max = intval( $max_ord ) +1;
									}
									else
									{
										$max=1;
									}
									$q ="insert into currently (cId, cImgId, cTitle, cTitleLink, cDesc,		
									cImgTitle, cCreatedate, cModifydate, order_no) values ('$cId', '$imgname', '$cTitle', '
									$cTitleLink', '$cDesc', '$cImgTitle', now(), now(), $max);";
									if( ( $res = $hlp->_db->db_query( $q ) ) !== false )
									{
										$hlp->echo_ok( "Event has been stored." );
										$thumbPath = "$imgDir/thumb.250.jpeg";
										$cmd = "convert $currentImagePath -geometry 250x $thumbPath";
										exec( $cmd, $op, $ret );
										if( intval( $ret ) === 0 && file_exists( $thumbPath ) )
										{	
											$s = @getimagesize( $thumbPath );
											if( isset( $s[ 1 ] ) )
											{
												$ht = $s[ 1 ];
												if( $ht > 250 )
												{									
													$new_ht=$ht-250;
													$cmd = "convert ${thumbPath} -gravity South -chop x${new_ht}+0+0 ${thumbPath}";
													exec( $cmd, $outa, $ret );
													if( intval( $ret ) !== 0 )
													{
														@unlink( $thumbPath );
														
														$hlp->echo_err("unable to create thumbnail1.");
													}
												}
											}
										}
										else
										{
											@unlink( $thumbPath );
											$hlp->echo_err( "Unable to create Thumbnail." );
										}	
									}
									else
										$hlp->echo_err( "Sorry, unable to add record. contact support." );
								}
								else
									$hlp->echo_err( "Sorry, unable to process request. contact support." );	
							}
							else
								$hlp->echo_err("Sorry, unable to upload image.");
						}
						else
							$hlp->echo_err("Sorry, uploaded file type is not supported.");
					}
					else
						$hlp->echo_err("Sorry, uploaded file type is not supported.");
				}
				else
					$hlp->echo_err("Saving failed");
				
			}//end of foreach
		}
		else
			$hlp->echo_err("Please upload the neccessary artwork image");	 //img empty
	}// end of if
	else
		$hlp->echo_err("Please enter appropriate values in all the fields"); //flag === 1?
}

echo("<div>
		<form name=frmWrt method='post' action=".$uri." enctype='multipart/form-data'>
			<div style='padding-top:10px' >
				<div style='width:80px;float:left;padding-left:10px;' >Title : </div>
				<div><input type=text maxlength='200' name=ctitle style='width:200px;' ></div>
			</div>
			<div style='padding-top:10px' >
				<div style='width:80px;float:left;padding-left:10px;' >Title Link: </div>
				<div><input type=text maxlength='200' name=ctitlelink style='width:200px;' ></div>
			</div>
			<div style='padding-top:10px' >
				<div style='width:80px;float:left;padding-left:10px;' >Description: </div><br />
				<div style='padding-top:10px;' ><textarea id='htmlsrc' name='htmlsrc' rows='20' cols='80' style='width: 
		95%'></textarea></div> 
			</div>
			<div style='padding-top:10px' >
				<div style='width:80px;float:left;padding-left:10px;' >Image : </div>
				<div><input type=file name=img style='width:300px;' ></div>
			</div>
			<div style='padding-top:10px' >
				<div style='width:80px;float:left;padding-left:10px;' >Image Title : </div>
				<div><input type=text maxlength='200' name=cimgtitle style='width:200px;' ></div>
			</div>
			
			<div style='width:320px;text-align:center;padding-top:10px;' ><button type=submit class=roundbutton style='width:100px;' >Save</button></div>	
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