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

$item_id = makeStringSafe($_POST["item_id"]);

$summary = makeStringSafe($_POST["summary"]);
$review = makeStringSafe($_POST["review"]);
$source = makeStringSafe($_POST["source"]);
$date_added = makeStringSafe($_POST["date_added"]);
$sel_record = makeStringSafe($_POST["sel_record"]);

	if (!$sel_record) {
header("Location: http://$url$directory/admin/admin_del1.php?".sid);
			exit;
		}
	
	
	$sql = "DELETE FROM review WHERE id = \"$sel_record\" LIMIT 1";

        $sql_result = mysql_query($sql) 
            or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	if (!$sql_result) {  
   
		echo "<P>Couldn't delete record!";

	} else {
		BodyHeader("$sitename:  Delete A Review","",""); 
        ?>
<h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have deleted 
  <?php echo stripslashes($source); ?> from the Review Database</font></h1>
<P> 
<table align="center">
  <tr> 
    <td valign=top><strong>Summary:</strong></td>
    <td valign=top> <?php echo stripslashes($summary); ?> 
    </td>
  </tr>
  <tr> 
    <td valign=top><strong>Review:</strong></td>
    <td valign=top><?php echo stripslashes($review); ?></td>
  </tr>
  <tr> 
    <td valign=top><strong>Source:</strong></td>
    <td valign=top><?php echo stripslashes($source); ?></td>
  </tr>
  <tr> 
    <td valign=top><strong>Date Added:</strong></td>
    <td valign=top><?php echo $date_added ?></td>
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
		
		<?php 
			//now delete from rating criteria details
			$query="DELETE FROM rating_details WHERE review_id=$sel_record";
			mysql_query($query) or die(mysql_error());
		?>
		</table>
    	</td>
	</tr>	


</table>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
  <?php 
				
        BodyFooter(); 
 }
 
?>

