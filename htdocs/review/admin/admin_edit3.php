<?php
//if a session does not yet exist for this user, start one
session_start();

//make sure user has been logged in.
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU=="")
	{
	// User not logged in, redirect to login page
	Header("Location: index.php");
	}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$summary = makeStringSafe($_POST["summary"]);
$review = makeStringSafe($_POST["review"]);
$source = makeStringSafe($_POST["source"]);
$date_added = makeStringSafe($_POST["date_added"]);
$sel_record = makeStringSafe($_POST["sel_record"]);

	if (!$sel_record) {
header("Location: http://$url$directory/admin/admin_menu.php?".sid);
			exit;
		}
	
	
	$sql = "UPDATE review SET 
	summary = '$summary',
	review = '$review',
	source = '$source',
	approve = 'y',
	date_added = '$date_added'
	WHERE id = '$sel_record'";
	
        $sql_result = mysql_query($sql) 
            or die( "Couldn't execute update query."); 

	if (!$sql_result) {  
   
		echo "<P>Couldn't edit record!";

	} else {
		//insert OR  update ratings
			$item_id=0;
			$flagR=0;
			$totalR=0;
			$countR=0;
			$query="SELECT a.criteriaId,a.criteriaName,c.review_item_id FROM rating_criteria a,item_rating_criteria b,review c WHERE a.criteriaId=b.criteriaId AND b.item_id=c.review_item_id AND c.id=" . $sel_record;
			$result=mysql_query($query) or die(mysql_error());

			while($row=mysql_fetch_array($result)){
				$flagR=1;
				$counter=$row["criteriaId"];
				$item_id=$row["review_item_id"]; 
				if(isset($_POST["rad$counter"]) && $_POST["rad$counter"]!=""){
					$totalR=$totalR+$_POST["rad$counter"];
					$countR++;
					$queryTemp="SELECT id FROM rating_details WHERE review_id=$sel_record AND criteriaId=$counter";
					$resultTemp=mysql_query($queryTemp) or die(mysql_error());
					if(mysql_num_rows($resultTemp)>0){
						//update
						$query="UPDATE rating_details SET ratingValue=" .$_POST["rad$counter"] ." WHERE review_id=$sel_record AND criteriaId=$counter";
						mysql_query($query) or die(mysql_error());
					}else{
						//insert
						$query="INSERT INTO rating_details(review_id,criteriaId,ratingValue) VALUES(" .$sel_record ."," .$counter ."," .$_POST["rad$counter"] .")";
						mysql_query($query) or die(mysql_error() ."hh");
					}
				}
	
			}
			if($flagR==1 && $totalR > 0 && $countR>0){
				$query="UPDATE review SET rating='" . number_format($totalR/$countR, 2, '.', '')."' WHERE id=" . $sel_record;
				mysql_query($query) or die(mysql_error());
			}elseif($flagR===1){
				$query="UPDATE review SET rating='0' WHERE id=" . $sel_record;
				mysql_query($query) or die(mysql_error());
			}	

		BodyHeader("$sitename:  Edit A Review","",""); 
        ?>

<h1>
  <center>
    <BR>
    <font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have Edited <?php echo stripslashes($source); ?>'s Review</font>
  </center>
</h1>
<P>
<table align="center">
  <tr>
    <td valign=top><strong>Summary:</strong></td>
    <td valign=top><?php echo stripslashes("$summary"); ?> </td>
  </tr>
  <tr>
    <td valign=top><strong>Review:</strong></td>
    <td valign=top><?php echo stripslashes($review); ?></td>
  </tr>
  <tr>
    <td valign=top><strong>Source:</strong></td>
    <td valign=top><?php echo stripslashes("$source"); ?></td>
  </tr>
  <tr>
    <td valign=top><strong>Date Added:</strong></td>
    <td valign=top><?php echo stripslashes($date_added); ?></td>
  </tr>
  	<tr>
		<td colspan=2>
		<table width="100%" cellspacing=1 cellpadding=1>
		<tr>
			<tr>
				<td colspan=5>Ratings Criteria<br />&nbsp;</td>
			</tr>
		</tr>
		<?php
			$query="SELECT a.criteriaId,a.criteriaName FROM rating_criteria a,item_rating_criteria b WHERE a.criteriaId=b.criteriaId AND item_id=" . $item_id;
			$result=mysql_query($query) or die(mysql_error());
			$str="";
			while($row=mysql_fetch_array($result)){
					$counter=$row["criteriaId"];
					$queryTemp="SELECT ratingValue FROM rating_details WHERE review_id=$sel_record AND criteriaId=" . $row["criteriaId"];
					$resultTemp=mysql_query($queryTemp) or die(mysql_error());
					$val=0;
					if(mysql_num_rows($resultTemp)==1){
						$rowTemp=mysql_fetch_array($resultTemp);
						$val=$rowTemp["ratingValue"];
					}
					$str.="<tr><td colspan=6 bgcolor=gray><font color=white><b>".$row["criteriaName"] ."</b></font></td></tr>";
					$str.="<tr>";
						if($val=="1"){
							$str.="<td><input type=radio name=rad$counter value=1 checked><b>1</b></td>";
							$str.="<td><input type=radio name=rad$counter value=2><b>2</b></td>";
							$str.="<td><input type=radio name=rad$counter value=3><b>3</b></td>";
							$str.="<td><input type=radio name=rad$counter value=4><b>4</b></td>";
							$str.="<td><input type=radio name=rad$counter value=5><b>5</b></td>";
							$str.="<td><input type=radio name=rad$counter value=0><b>N/A</b></td>";
						}elseif($val=="2"){
							$str.="<td><input type=radio name=rad$counter value=1><b>1</b></td>";
							$str.="<td><input type=radio name=rad$counter value=2 checked><b>2</b></td>";
							$str.="<td><input type=radio name=rad$counter value=3><b>3</b></td>";
							$str.="<td><input type=radio name=rad$counter value=4><b>4</b></td>";
							$str.="<td><input type=radio name=rad$counter value=5><b>5</b></td>";
							$str.="<td>N/A<input type=radio name=rad$counter value=0><b>N/A</b></td>";
						}elseif($val=="3"){
							$str.="<td><input type=radio name=rad$counter value=1><b>1</b></td>";
							$str.="<td><input type=radio name=rad$counter value=2><b>2</b></td>";
							$str.="<td><input type=radio name=rad$counter value=3 checked><b>3</b></td>";
							$str.="<td><input type=radio name=rad$counter value=4><b>4</b></td>";
							$str.="<td><input type=radio name=rad$counter value=5><b>5</b></td>";
							$str.="<td><input type=radio name=rad$counter value=0><b>N/A</b></td>";
						}elseif($val=="4"){
							$str.="<td><input type=radio name=rad$counter value=1><b>1</b></td>";
							$str.="<td><input type=radio name=rad$counter value=2><b>2</b></td>";
							$str.="<td><input type=radio name=rad$counter value=3><b>3</b></td>";
							$str.="<td><input type=radio name=rad$counter value=4 checked><b>4</b></td>";
							$str.="<td><input type=radio name=rad$counter value=5><b>5</b></td>";
							$str.="<td><input type=radio name=rad$counter value=0><b>N/A</b></td>";
						}elseif($val=="5"){
							$str.="<td><input type=radio name=rad$counter value=1><b>1</b></td>";
							$str.="<td><input type=radio name=rad$counter value=2><b>2</b></td>";
							$str.="<td><input type=radio name=rad$counter value=3><b>3</b></td>";
							$str.="<td><input type=radio name=rad$counter value=4><b>4</b></td>";
							$str.="<td><input type=radio name=rad$counter value=5 checked><b>5</b></td>";
							$str.="<td><input type=radio name=rad$counter value=0><b>N/A</b></td>";
						}else{
							$str.="<td><input type=radio name=rad$counter value=1><b>1</b></td>";
							$str.="<td><input type=radio name=rad$counter value=2><b>2</b></td>";
							$str.="<td><input type=radio name=rad$counter value=3><b>3</b></td>";
							$str.="<td><input type=radio name=rad$counter value=4><b>4</b></td>";
							$str.="<td><input type=radio name=rad$counter value=5><b>5</b></td>";
							$str.="<td><input type=radio name=rad$counter value=0 checked><b>N/A</b></td>";
						}
					$str.="</tr>";
			}
			echo $str;
		?>
		</table>
    	</td>
	</tr>	

</table>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br />
</div>
<?php 
				
        BodyFooter(); 
 }
 
?>
