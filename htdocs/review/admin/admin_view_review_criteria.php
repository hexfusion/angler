<?php
session_start();

//make sure user has been logged in.
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU==""){
	// User not logged in, redirect to login page
	Header("Location: index.php");
}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

BodyHeader("Add a New Category","","");

?>
<p align="center"><strong><font size="4">View Review Criterias</font> </strong></p>
<table cellspacing=0 cellpadding=0 width=500 align=center border=1 bordercolor=black>
	<tr>
		<td>
			<table cellspacing=3 cellpadding=3 width="99%" align=center border=0>
				<tr>
				  <td colspan=4 align=right><a href="admin_addedit_review_criteria.php">Add New Criteria</a><br />
                      <a href="admin_criteria_add_all.php">Add Criteria to ALL Items</a></td>
				</tr>
				<tr>
					<td colspan=4>&nbsp;
						<font color=red><b>
						<?php 
							if(isset($_SESSION['msg']) && $_SESSION['msg']!=""){
								echo $_SESSION['msg'];
								$_SESSION['msg']="";
							}
						?>
						</b></font>
					</td>
				</tr>
				<tr>
					<td><b>ID</td>
					<td><b>Criteria Name</td>
					<td><b>Status</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=4>&nbsp;</td>
				</tr>

				<?php 
					$query="SELECT * FROM rating_criteria";
					$result=mysql_query($query) or die(mysql_error());
					if(mysql_num_rows($result) > 0){
						while($row=mysql_fetch_array($result)){
							$status=$row["isActive"];
							$st="";
							$bgcolor="";
							if($status=="T"){
								$st="Active";
							}else{
								$st="In Active";
								$bgcolor=" bgcolor=red";	
							}
							?>
								<tr>
									<td><?php echo $row["criteriaId"]; ?></td>
									<td><?php echo $row["criteriaName"]; ?></td>
									<td <?php echo "$bgcolor"; ?>><?php echo $st; ?></td>
									<td>
										<a href="admin_addedit_review_criteria.php?id=<?php echo $row['criteriaId']; ?>">Edit</a>
										&nbsp;|&nbsp;<a href="admin_status_review_criteria.php?id=<?php echo $row['criteriaId']; ?>&status=<?php echo "$status"; ?>">Invert Status</a>
										&nbsp;|&nbsp;<a href="admin_delete_review_criteria.php?id=<?php echo $row['criteriaId']; ?>">Delete</a>
									</td>
								</tr>
							<?php	
						}
					}else{
					?>
							<tr><td colspan=4><b><font color=red>No record found.</font></b></td></tr>
					<?php
					}
					?>
			</table>
		</td>
	</tr>
</table>			 

<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></div>
<?php
BodyFooter();
exit;
?>