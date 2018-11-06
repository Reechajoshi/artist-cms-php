<?php
	$gid = false;
	$flag = 1;
	if(isset($_GET['gid']))
	{
		$gid=base64_decode($_GET['gid']);
	}
	if(isset($_GET['gname']))
	{
		$gname=base64_decode($_GET['gname']);
	}
	
	$uri = $me.'?b=artnew&gid='.base64_encode($gid);
	
	if ( isset( $_POST[ 'htmlsrc' ] ) || ( isset( $_POST[ 'aname' ] )) || (isset( $_POST[ 'ayear' ] )) || (isset( $_POST[ 'collection' ] )) || ( isset( $_POST[ 'acansize' ] )) )
	{
		$Aname = trim( $_POST['aname'] );
		$Adesc=$_POST['htmlsrc'];
		$Aid = $hlp->getunqid( $Aname );
		$d = date('Y-m-d G:i:s');
		
		$Ayear = trim($_POST['ayear']);
		$Acollection = trim($_POST['collection']);
		$Acansize = trim($_POST['acansize']);
		$subgroup = trim($_POST['subgroup']);
		
		if( ( strlen($Aname) === 0 )|| ( strlen($Ayear) === 0 )  || ( strlen($Acansize) === 0 ) || !is_numeric($Ayear) )
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
							if(($m=='image/jpeg')||($m=='image/gif')||($m=='image/png')||($m=='image/jpg')||($m=='image/tiff')||($m=='image/tif') )
							{
								$iext = explode('/',$m);	
								$unqid = $hlp->getunqid( $Aname );
								$imgname = $hlp->getunqid( $unqid );
								
								$imgDir = "data/$imgname" ;
								
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
											$cmdMain = "convert '$currentImagePath' -geometry 1200x  '$MainImagePath' ";
										else
											$cmdMain = "convert '$currentImagePath' -geometry x900  '$MainImagePath' ";
										
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
										// subgroup
										$q ="insert into artworks( Aid , gid , Aname , Adate , Adesc , AimgName ,  AimgSize , AimgWidth , AimgHeight , AimgMime , Acanvas , Ayear , Acollection , subgroup ) values ( '$Aid', '$gid' , '$Aname' , now() , '$Adesc' , '$imgname' , '$size' , ".$s[0].",".$s[1].", '$m' , '$Acansize' , '$Ayear' , $Acollection, '$subgroup' );";
										
										if( ( $res = $hlp->_db->db_query( $q ) ) !== false )
										{
											$hlp->echo_ok( "Artworks has been stored." );
											
											//$thumbId = $hlp->getunqid($unqid).".".$iext[1];
											$thumbPath = "$imgDir/thumb.250.jpeg";
											
											//$cmd = "convert $currentImagePath -geometry 150x $thumbPath";
											
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
														$cmd = "convert ${thumbPath} -gravity South -chop x${new_ht}+0+${thumbPath}";
													
														exec( $cmd, $outa, $ret );
														
														if( intval( $ret ) !== 0 )
														{
															@unlink( $thumbPath );
															$hlp->echo_err("unable to create thumbnail1.");
														}
													}
													//else
													//{
														/*$cmd = "convert ${currentImagePath}
														-resize 250x -background transparent -compose Copy -gravity center -extent 250x250 ${thumbPath}";
													
														exec( $cmd, $outa, $ret );
														
														if( intval( $ret ) !== 0 )
														{
															@unlink( $thumbPath );
															$hlp->echo_err("unable to create thumbnail.");
														}*/
													//}
												} //isset s [1]
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
				} //foreach ends
			}
			else
				$hlp->echo_err("Please upload the neccessary artwork image");	 //img empty
				
		}
		else
			$hlp->echo_err("Please enter appropriate values in all the fields"); //flag === 1?	
	} // all isset
	
	echo('<form name=frmWrt method="post" action="'.$uri.'" enctype="multipart/form-data">
	
		<div style="padding-top:10px" >
			<div style="width:80px;float:left;padding-left:10px;" >Title : </div>
			<div><input type=text name=aname maxlength="200" id=aname value="" style="width:200px;" ></div>
		</div>
		
		<div style="padding-top:10px" >
			<div style="width:80px;float:left;padding-left:10px;" >Image : </div><div><input type=file name=img style="
			width:300px;"></div>
		</div>
			
		<div style="padding-top:10px" >
			<div style="width:80px;float:left;padding-left:10px;" >Canvas size : </div>
			<div><input type=text maxlength="200" name=acansize id=acansize value="" style="width:200px;" ></div>
		</div>
		
		<div style="padding-top:10px" >
			<div style="width:80px;float:left;padding-left:10px;" >Year : </div>
			<div><input type=text name=ayear id=ayear value="" style="width:200px;" ></div>
		</div>
		
		<div style="padding-top:10px" >
			<div style="width:80px;float:left;padding-left:10px;" >Subgroup : </div>
			<div>'.$hlp->get_subgroupHTML_by_group($gid).'</div>
		</div>
		
		<div style="padding-top:10px" >
			<div style="width:80px;float:left;padding-left:10px;" >Collection : </div>
			
			<input type="radio" id=available value="0"  name="collection" checked/><label for=available >Available</label> 
			<input type="radio" id=private value="1" name="collection"  /><label for=private>Private</label>
			<input type="radio" id=institute value="2"  name="collection" /><label for=institute >Institutional</label>
		</div>
		
		
		</div>
			
		<div style="padding-top:10px" >
			
		</div>
		<div style="padding-top:10px;" ><textarea id="htmlsrc" name="htmlsrc" rows="20" cols="80" style="width: 
		95%"></textarea></div> 
		<div style="width:320px;text-align:center;padding-top:10px;" ><button type=submit class=roundbutton style="width:100px;" 
		>Save</button></div>	
		</form>');
		
	
	echo( '<body>' );
	
	echo( $szo );
	
	echo( '<div class=gencon style="padding-top:3px;">' );
	
	$toolbar_type = "BasicToolbar";
	require("ckeditor/init.php");
	$CKEditor = new CKEditor();
	$CKEditor->returnOutput = true;
	
	echo($CKEditor->replace("htmlsrc",array("toolbar"=>$toolbar_type)));
?>

</div>
</body>
