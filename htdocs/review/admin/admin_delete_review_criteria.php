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

if(isset($_POST['delDb']) && $_POST['delDb']!=""){
	$strId=0;
	$strId=addslashes(stripslashes($_POST['cId']));
	if($strId==0 || $strId=="" ||!is_numeric($strId)){
		$_SESSION['msg']="Invalid Data.";
		header("Location: admin_view_review_criteria.php");
		exit;		
	}else{
		$query="DELETE FROM rating_details  WHERE criteriaId=" .$strId;
		mysql_query($query) or die(mysql_error());

		$query="DELETE FROM item_rating_criteria WHERE criteriaId=" . $strId;
		mysql_query($query) or die(mysql_error());
		
		$query="DELETE FROM rating_criteria  WHERE criteriaId=" .$strId;
		mysql_query($query) or die(mysql_error());
		
		$_SESSION['msg']="Record Deleted.";
		header("Location: admin_view_review_criteria.php");
		exit;		
	}		
}

BodyHeader("Add a New Category");

?>
<p align="center"><strong><font size="4">Review Criteria</font> </strong></p>
<table cellspacing=0 cellpadding=0 width=400 align=center border=1 bordercolor=black>
	<tr>
		<td>
			<table cellspacing=3 cellpadding=3 width="99%" align=center border=0>
				<tr>
					<td colspan=2 align=right><a href="admin_view_review_criteria.php">Back To View Criteria</a></td>
				</tr>
				<?php 
					$cId=0;
					if(isset($_GET['id']) && $_GET['id']){
						$cId=$_GET['id'];
					}
				?>
				<form name="frm" method="Post">
				<input type=hidden name="delDb" value="1">
				<input type=hidden name="cId" value="<?php echo "$cId"; ?>">
				<tr>
					<td colspan=2>Delete Review Criteria</td>				
				</tr>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr>
					<td coslspan=2><strong>Are you sure you want to delete this criteria.</strong></td>
				</tr>
				<tr>
					<td colspan=2 align=right><input type=submit value="Delete">&nbsp;&nbsp;</td>
				</tr>
				</form>

			</table>
		</td>
	</tr>
</table>			 

<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></div>
<?php
BodyFooter();
exit;
?>