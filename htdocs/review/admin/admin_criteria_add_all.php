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

		
		BodyHeader("Add Review Criteria to ALL Items!");
?>


			<div align="center"><strong>Choose Review Criterias to Add to ALL review items </strong></div>
			<FORM method="POST" action="admin_criteria_add_all2.php?<?php echo htmlspecialchars(SID); ?>"><table cellspacing="0" cellpadding="0" width="100%" border="1" bordercolor="black">
				<tr>
			    <td><tr></td></tr>
					<td valign="top">
						<table width="100%" cellspacing="2" cellpadding="2">
						<tr>
						  <td><?php //show the categories
		$link_count = "SELECT DISTINCT category FROM review_category_list WHERE category != '' ORDER BY category ASC";
		$result = mysql_query($link_count)
or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));
		$row=mysql_fetch_array($result);
		?>
  <div align="center">Apply TO:  
    <select name="category" class="select">
      <option value="">-- ALL CATEGORIES --</option>
<?php
	function displayCat($catId, $dashes = 0){
		$st = "";
		$query = 'select * from review_category_list where parent_id = ' .$catId. ' order by catorder';
		for($i=0; $i<$dashes; $i++){
			$st .= "--";
		}
		
		$dashes += 2;
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)){
			echo "<option value='" .$row["cat_id_cloud"]."'>" .$st . $row["category"] . "</option>";
			displayCat($row["cat_id_cloud"], $dashes);
		}
	}
	displayCat(-1);
?>
    </select>
   
  </div></td></tr>	
						<?php 
								 
								$query="SELECT * FROM rating_criteria WHERE isActive='T'";
								$result=mysql_query($query) or die(mysql_error());
								$counter=0;
								if(mysql_num_rows($result) > 0){
									$counter=1;
									while($row=mysql_fetch_array($result)){
										$chk="";
										for($i=0;$i<sizeOf($cArray);$i++){
											if($row["criteriaId"]==$cArray[$i]){
												$chk="checked";
												break;
											}
										}
											
										if($counter==1){
										?>
											<tr><td><?php echo stripslashes($row["criteriaName"]); ?></td><td><input type="checkbox" name="chk<?php echo $row['criteriaId']; ?>" value="<?php echo $row['criteriaId']; ?>" <?php echo $chk; ?> /></td>
										<?php
										}else{
										?>
											<td><?php echo stripslashes($row["criteriaName"]); ?></td><td><input type="checkbox" name="chk<?php echo $row['criteriaId']; ?>" value="<?php echo $row['criteriaId']; ?>" <?php echo $chk; ?> /></td>
										<?php
										}
									
										if($counter==2){
											?>
						  </tr>
											<?php
											$counter=1;
										}else{
											$counter++;
										}
									}
								}
								if($counter==1){
									?>
						  <tr>
					      <td></tr></td></tr>
									<?php
								}
							?>
					  </table>
			  </td>
				<tr>
			    <td></tr></td></tr>	
</table>
		</td>
	</tr>
    
	<tr>
      <td align="center" colspan="2">
        <input name="submit" type="submit" value="Update All Items" />
      </td>
    </tr>
  </table></FORM>
  <p>&nbsp;</p>

<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br />
</div>
<?php
 BodyFooter(); 

?>