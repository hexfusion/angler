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

if(isset($_POST['saveDb']) && $_POST['saveDb']!=""){
	$strId=0;
	$strId=addslashes(stripslashes($_POST['cId']));
	$strName=addslashes(stripslashes(trim($_POST['txtName'])));
	$strStatus=@$_POST['chkStatus'];
	
	if($strStatus!="T") $strStatus="F";
	
	$strNameT=str_replace(" ","",$strName);
	
	if($strNameT==""){
		$_SESSION['msg']="Enter criteria name.";
	}else{
		if($strId==0){
			$query="INSERT INTO rating_criteria(criteriaName,isActive) VALUES('" .$strName ."','" . $strStatus."')";
			mysql_query($query) or die(mysql_error());
			$_SESSION['msg']="Record Inserted.";
			header("Location: admin_view_review_criteria.php");
			exit;		
		}else{
			$query="UPDATE rating_criteria SET criteriaName='" .$strName ."',isActive='" . $strStatus ."' WHERE criteriaId=" .$strId;
			mysql_query($query) or die(mysql_error());
			$_SESSION['msg']="Record Updated";
			header("Location: admin_view_review_criteria.php");
			exit;		
		}		
	}
}

BodyHeader("Add a New Category","","");

?>
<p align="center"><strong><font size="4">Review Criteria</font> </strong></p>
<table cellspacing=0 cellpadding=0 width=400 align=center border=1 bordercolor=black>
	<tr>
		<td>
			<table cellspacing=3 cellpadding=3 width="99%" align=center border=0>
				<tr>
					<td colspan=2 align=right><a href="admin_view_review_criteria.php">Back To View Criteria</a></td>
				</tr>
				<tr>
					<td colspan=2>&nbsp;
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
				<?php 
					$cId=0;
					$val="";
					$st="F";
					if(isset($_GET["id"]) && $_GET['id']!=""){
						$cId=$_GET["id"];
						$query="SELECT * FROM rating_criteria WHERE criteriaId=" . $cId;
						$result=mysql_query($query) or die(mysql_error());
						if(mysql_num_rows($result) > 0){
							$row=mysql_fetch_array($result);
							$val=stripslashes($row["criteriaName"]);
							$st=$row["isActive"];
						}
					}
				?>
				<script language="Javascript">
					function submitIt(){
						var fr=document.frm;
						if(fr.txtName.value==""){
							alert("Please enter criteria name");
							fr.txtName.focus();
							return false;
						}else{
							return true;
						}
					}
				</script>
				<form name="frm" method="Post" onsubmit="javascript: return submitIt();">
				<input type=hidden name="saveDb" value="1">
				<input type=hidden name="cId" value="<?php echo "$cId"; ?>">
				<tr>
					<td colspan>Review Criteria Name</td>				
					<td><input type="text" name="txtName" value="<?php echo "$val"; ?>"></td>
				</tr>
				<tr>
					<td>Is Active</td>
					<td>
						<?php if ($st=="T"){?>
							<input type=checkbox name="chkStatus" value="T" checked>
						<?php }else{?>
							<input type=checkbox name="chkStatus" value="T">
						<?php }?>
					</td>
				</tr>
				<tr>
					<td colspan=2 align=right><input type=submit value="Save">&nbsp;&nbsp;</td>
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