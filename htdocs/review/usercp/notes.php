<?php
session_start();
include ('../functions.php');
include ('../f_secure.php');
include ('../body.php');
include ('../config.php');
$username = makeStringSafe($_SESSION['username_logged']);
if (!$username) {
	BodyHeader("User Not Found!","","");
?>
<link href="../review.css" rel="stylesheet" type="text/css">
<P align="center">
<P align="center"><span class="box1">Click to <a href=../login/register.php?item_id=$item_id&back=$back>Register</a> or if you've already registered, <a href=../login/signin.php?item_id=$item_id&back=$back>Login In</a>. </span>
<P align="center"><br />
  <?php
   BodyFooter();  
	exit;  
}else{
					BodyHeader("Manage Notes","","");
	?>
<table cellspacing=1 cellpadding=1 width="100%">
  <tr>
    <td valign=top><table cellspacing=2 cellpadding=2 align=center width="90%">
        <?php 
						$query=" SELECT count(*) as ct FROM review_items_note  WHERE note_user_name='" . $username ."'";
						$result=mysql_query($query) or die(mysql_error());
						$total=0;
						if(mysql_num_rows($result) > 0){
							$row=mysql_fetch_array($result);
							$total=$row["ct"];
						}
						

						if($total > 0){
							?>
        <tr>
          <td colspan=5>Total <b>
            <?php echo "$total"; ?>
            </b> records found. </td>
        </tr>
        <?php 
							if($NumReviews > 0){
								$NumReviews=5;
							}
						
							if($NumPages > 0){
								$NumPages=5;
							}
												
							$pg = 1;
							
							if(isset($_GET['pg']) && $_GET['pg']!=""){
								$pg=$_GET['pg'];
							}
							
							$allPages = ceil($total/$NumReviews);
							if($pg > $allPages) $pg=$allPages;

							if($pg > 1) {
								$start = ($pg-1)*$NumReviews;
							}else{
								$start = 0;
							}

							$PHP_SELF = $_SERVER['PHP_SELF'];
							
							$stop = $NumReviews;
							if($stop >= $total) { 
								$stop = $total;
							}
						
							$nextPage=cPaging($allPages,$pg,$NumReviews,$NumPages);										
						
							if ($total != 0) {
								$nextPage .= " |";
							}
							?>
        <tr>
          <td colspan=5 align=right><?php echo "$nextPage"; ?></td>
        </tr>
        <tr bgcolor="#cecece">
          <td width="15%"><b>Note Date</b></td>
          <td><b>Notes</b></td>
          <td width="10%"></td>
          <td width="10%"></td>
          <td width="10%"></td>
        </tr>
        <script language="JavaScript">
								function confirm_del(id,pg){
									if(confirm("Are you sure to delete these notes")){
										window.location="notes_delete.php?note_id=" + id + "&pg=" + pg;
									}
								}
							</script>
        <?php
							$query=" SELECT note_id,note_item_id,note_user_name,note_notes, DATE_FORMAT(note_date,'%d-%b-%Y') as note_date FROM review_items_note WHERE note_user_name='$username' LIMIT $start,$stop";
							$result=mysql_query($query) or die(mysql_error());
							while($row=mysql_fetch_array($result)){
								?>
        <tr>
          <td><?php echo $row["note_date"]; ?></td>
          <td><?php
												echo (stripslashes(substr($row["note_notes"],0,75)));
												
												if(strlen($row["note_notes"])> 75){
													echo "&nbsp; ..." .strlen($row["note_notes"]) ;
												}
											?>
          </td>
          <td><a href="notes_edit.php?note_id=<?php echo $row['note_id']; ?>&pg=<?php echo $pg; ?>">Edit</a></td>
          <td><a href="javascript: confirm_del(<?php echo $row['note_id']; ?>,<?php echo $pg; ?>);">Delete</a></td>
          <td><a href="../index2.php?item_id=<?php echo $row['note_item_id']; ?>">View Listing</a></td>
        </tr>
        <?php						
							}								
			
						}else{
							?>
        <tr>
          <td>&nbsp;&nbsp; No records found<br />
            <br />
          </td>
        </tr>
        <?php
						}
						
					?>
      </table></td>
  </tr>
  <tr>
    <td valign=top><br />
      <br />
      <br />
      <br />
      <?php
					include('user_cp_links.php');
				?>
    </td>
  </tr>
</table>
<?php
			BodyFooter();
					//exit;
			
}

function cPaging($count,$start,$pageSize,$displayPages){
	global  $PHP_SELF;
	$tempStr="";
	$flag=1;
	$center=$start+($displayPages>>1);
	if($center < 0) $center=0;
	for($i=1;$i<=$count;$i++){
		
		if($i<=($displayPages>>1) && $i>=$start-($displayPages>>1) ){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."'>$i</a> ";		
				}
				$flag++;		
			
		}elseif($flag<=$displayPages && ($i>=$start-($displayPages>>1) && $i<=$start+($displayPages>>1))){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."'>$i</a> ";		
				}
				$flag++;		
		}elseif($flag<=$displayPages && $i>=$start-($displayPages>>1)){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."'>$i</a> ";		
				}
				$flag++;		
		
		}elseif($i==1){
			if($i==$start){
				$tempStr.="  $i";			
			}else{
				$tempStr .= "  <a href='$PHP_SELF?pg=" . $i ."'>[$i ....]</a>&nbsp;&nbsp;";			
			}
			
		}	elseif($i==$count){
			if($i==$start){
				$tempStr.=" | $i";			
			}else{
				$tempStr .= " | &nbsp;&nbsp;<a href='$PHP_SELF?pg=" . $i ."'>[$i ....]</a> ";			
			}
		}		
	}			
	//calculate previous next
	if($start < $count){
		$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?pg=" . ($start+1) ."'><b>Next</b></a> ";
	}
	
	if($start > 1){
		$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?pg=" . ($start-1) ."'><b>Prev</b></a> ";	
	}
	
	return $tempStr;
}


?>
