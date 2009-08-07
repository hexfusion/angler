<?php
session_start();
include ('../functions.php');
include ('../f_secure.php');
include ('../body.php');
include ('../config.php');
$username = @$_SESSION['username_logged'];

if($username!="" && isset($_POST['note_id']) && $_POST['note_id']!="" && isset($_POST['save']) && $_POST['save']=="1"){
	$query=" UPDATE review_items_note SET note_notes='" . trim(addslashes($_POST['note_notes'])) ."', note_date=now() WHERE note_id=" . $_POST['note_id'] ." AND note_user_name='$username'";
	mysql_query($query) or die(mysql_error());
	$pg=1;
	if(isset($_POST['pg']) && $_POST['pg']!=""){
		$pg=$_POST['pg'];
	}
	header("Location: notes.php?pg=" . $pg);
}
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
	?>
	<table cellspacing=1 cellpadding=1 width="100%">
		<tr>
			<td valign=top>
				<?php
					BodyHeader("Manage Notes","","");
				?>
			</td>
		</tr>
		<tr>
			<td valign=top>
				<table cellspacing=2 cellpadding=2 align=center width="50%">
				<?php
					$note_id="";
					if(isset($_GET['note_id'])&& $_GET['note_id']!=""){
						$note_id=$_GET['note_id'];
					}
					if($note_id!="" && is_numeric($note_id)){
						$query="SELECT note_notes FROM review_items_note WHERE note_id=$note_id AND note_user_name='$username'";
						$result=mysql_query($query) or die(mysql_error());
						if(mysql_num_rows($result) > 0){
							$row=mysql_fetch_array($result);
							?>
								<script language="JavaScript">
									function submitIt(){
										frm=document.frmEdit;
										if(frm.note_notes.value==""){
											alert("Please enter notes");
											frm.note_notes.focus();
											return false;
										}else{
											return true;
										}
									}
								</script>
								<form name="frmEdit" method="post" onsubmit="javascript: return submitIt();">
									<input type="hidden" name="save" value="1">
									<input type="hidden" name="note_id" value="<?php echo "$note_id"; ?>">
									<input type="hidden" name="pg" value="<?php echo @$_GET['pg']; ?>">
									<tr>
										<td>Notes</td>
										<td><textarea name="note_notes" rows=10 cols=50><?php echo stripslashes($row['note_notes']); ?></textarea></td>
									</tr>
									<tr>
										<td></td>
										<td>&nbsp;<input type=submit value="Save">&nbsp;&nbsp;&nbsp;&nbsp;<input type=reset value="Reset">&nbsp;&nbsp;&nbsp;&nbsp;<input type=reset value="Back" onclick="javascript: history.back(-1);"></td>
									</tr>
								</form>
							<?php
						}else{
							echo "<tr><td colspan=2>Listing ID is invalid.</td></tr>";		
						}
					}else{
						echo "<tr><td colspan=2>Listing ID is missing or invalid.</td></tr>";
					}
				?>
				</table>	
			</td>
		</tr>
		<tr>
			<td colspan=5>&nbsp;</td>
		</tr>
		<tr>
			<td colspan=5>&nbsp;</td>
		</tr>

		<tr>
			<td valign=top>
				<?php
					include('user_cp_links.php');
				?>
			</td>
		</tr>
		<tr>
			<td valign=top>
				<?php
					BodyFooter();
					exit;
				?>
			</td>
		</tr>
	</table>			
			<?php
}
?>