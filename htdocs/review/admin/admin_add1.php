<?php
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

		$sql = "SELECT * FROM review_category_list
				 ";
			
			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

BodyHeader("Add a New Item for Review","","");

?>
<script type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>


<p>&nbsp;</p>
<form action="admin_add2.php?<?php echo htmlspecialchars(SID); ?>" method="post" name="form1" id="form1">
  <table width="650" border="0" cellspacing="0" cellpadding="8" align="center">
    <tr>
      <td width="231" valign="top"><strong>Item Name:</strong></td>
      <td width="419" valign="top"><div align="center">
          <p>
            <input name="item_name" type="text" size="30" />
            <br />
            <br />
            <br />
          </p>
        </div></td>
    </tr>
    <tr>
      <td valign="top"><strong>Item Description:</strong></td>
      <td valign="top"></td>
    </tr>
    
    <tr><td colspan="2"><div align="left"><script src="nicEditor/nicEdit.js" type="text/javascript"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
new nicEditor({fullPanel : true, iconsPath : '<?php echo $directory; ?>/admin/nicEditor/nicEditorIcons.gif'}).panelInstance('item_desc');
});
</script>
   <textarea id="item_desc" name="item_desc" style="width: 580px; height: 300px;"></textarea>

          
      </div><br /><br /></td></tr>
    
    
    <tr>
      <td valign="top"><strong>Item Type:<font size="1"><br />
        <font face="Arial, Helvetica, sans-serif">(<a href="../review_form.php?item_id=1">review_form.php</a> where it says &quot;How do rate this _ &quot; What ever word you insert 
      here will be displayed at the end of that sentence.)</font></font></strong></td>
      <td valign="middle"><div align="center">
          <input name="item_type" type="text" size="30" />
        </div></td>
    </tr>
    <tr>
      <td valign="top"><br /><strong>Category (Optional):</strong></td>
      <td valign="top"><p align="center">
          <select name="category">
            <option value="-1"> -- Select a Category -- </option>
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
      </p></td>
    </tr>
    <tr>
      <td align="center" colspan="2"><strong><br />
        OPTIONAL: </strong>The fields below are to add a link to be displayed on index2.php.</td>
    </tr>
    <tr>
      <td valign="top"><strong>URL (include http://):</strong></td>
      <td valign="top"><div align="center">
          <input name="item_aff_url" type="text" id="item_aff_url" size="30" />
        </div></td>
    </tr>
    <tr>
      <td valign="top"><strong>Text for URL:</strong></td>
      <td valign="top"><div align="center">
          <input name="item_aff_txt" type="text" id="item_aff_txt" size="30" />
        </div></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><strong><em>OR</em></strong> if you would like to insert html code that contains the url and text: </td>
    </tr>
    <tr>
      <td valign="top"><strong>HTML Code :</strong></td>
      <td valign="top"><div align="center">
          <textarea name="item_aff_code" id="item_aff_code"></textarea>
        </div></td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
<?php
	$sql = "SELECT * FROM review_items_supplement order by id asc";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	while ($row = mysql_fetch_array($sql_result)) {
?>
    
    <tr>
      <td>&nbsp;<strong><?php echo $row['itemname'] ?></strong></td>
      <td align="center"><textarea name="review_supplement_item_<?php echo $row['id'] ?>" ></textarea>&nbsp;&nbsp;<input name="show_review_supplement_item_<?php echo $row['id'] ?>" type="checkbox" value="<?php echo $row['id'] ?>" checked />        
        Select</td>
    </tr>
    
<?php
	}
?>
    <td colspan="2"><br />
      <div align="center"><strong> Review Criterias</strong></div>   <br />
      <table cellspacing="0" cellpadding="0" width="580" border="1" bordercolor="black">
        <tr>
          <td><tr></td>
        </tr>
        <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
            <?php 
								$query="SELECT * FROM rating_criteria WHERE isActive='T'";
								$result=mysql_query($query) or die(mysql_error());
								$counter=0;
								if(mysql_num_rows($result) > 0){
									$counter=1;
									while($row=mysql_fetch_array($result)){
										if($counter==1){
										?>
            <tr>
              <td><?php echo stripslashes($row["criteriaName"]) ?></td>
              <td><input type="checkbox" name="chk<?php echo $row['criteriaId']; ?>" value="<?php echo $row['criteriaId']; ?>" /></td>
              <?php
										}else{
										?>
              <td><?php echo stripslashes($row["criteriaName"]) ?></td>
              <td><input type="checkbox" name="chk<?php echo $row['criteriaId']; ?>" value="<?php echo $row['criteriaId']; ?>" /></td>
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
              <td></tr></td>
            </tr>
            <?php
								}
							?>
          </table></td>
        <tr>
          <td></tr></td>
        </tr>
      </table></td>
    <tr>
      <td></tr></td>
    </tr>
    <tr>
      <td align="center" colspan="2"><br />
        <input type="submit" onclick="MM_validateForm('item_name','','R','item_type','','R');return document.MM_returnValue" value="Insert Item" />
      </td>
    </tr>
  </table>
  <div align="center"><br />
  </div>
</form>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></div>
<?php
BodyFooter();
}
exit;
?>