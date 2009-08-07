<?php
session_start();

include ("./body.php");
include ("./functions.php");
include ("./config.php");
include ("./f_secure.php");


		$sql = "SELECT * FROM review_category_list
				 ";
			
			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

BodyHeader("Add a New Item for Review","Add a new item","");

?>
<script language="JavaScript" type="text/javascript">
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

<p>If there is an item you would like to review but it's not listed on our site, please fill out the form below. If your suggestion is approved you will receive an email notification so you can come back and complete the review on your new item.</p>
<fieldset>
<p class="titles">
  <legend>Add New Item</legend>
</p>
<form action="user_add_item2.php?<?php echo htmlspecialchars(SID); ?>" method="post" name="form1" id="form1">
  <p>
    <label for="name">Item Name:</label>
    <input type="text" name="item_name" size="40" maxlength="85"  value="<?php if (isset($_SESSION['item_name'])) { echo $_SESSION['item_name']; } ?>" />
  </p>
  <p>
    <label for="name">Item Description:</label>
    <script src="<?php echo $directory; ?>/admin/nicEditor/nicEdit.js" type="text/javascript"></script>
    <script type="text/javascript">
bkLib.onDomLoaded(function() {
new nicEditor({fullPanel : true, iconsPath : '<?php echo $directory; ?>/admin/nicEditor/nicEditorIcons.gif'}).panelInstance('item_desc');
});
</script>
  </p>
  <div align="left">
    <textarea id="item_desc" name="item_desc" style="width: 600px; height: 250px;" />
    <?php if (isset($_SESSION['item_desc'])) { echo $_SESSION['item_desc']; } ?>
</textarea>
  </div>
  </p>
  <p>
    <label for="name">Item Type:</label>
    <input type="text" name="item_type" size="40" maxlength="85" value="<?php if (isset($_SESSION['item_type'])) { echo $_SESSION['item_type']; } ?>" />
  </p>
  <p>
    <label for="name">Category:</label>
    <select name="category">
      <option value=""> -- Select a Category -- </option>
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
		
		
		/*
		function displayCat($catId, $dashes = 0){
		$st = "";
		$query = 'select * from review_category_list where parent_id = ' .$catId. '';
		for($i=0; $i<$dashes; $i++){
			$st .= "--";
		}
		
		$dashes += 2;
		$result = mysql_query($query);
		while($row=mysql_fetch_array($result)){
	echo "<option value='" .urlencode($row["category"])."'>" .$st . $row["category"] . "</option>";
	if($_SESSION['category'] == $row["category"]){
					echo "<option value='" .urlencode($row["category"])."' selected>" .$st . $row["category"] . "</option>";
			}else{
	echo "<option value='" .urlencode($row["category"])."'>" .$st . $row["category"] . "</option>";
			}
	displayCat($row["category"],$dashes);
}
	}
	displayCat(-1);
	*/
		
?>
    </select>
  </p>
  <br />
  <p>
    <label for="name">Item Type:<br />
    <span class="small">(<a href="<?php echo "$directory"; ?>/review_form.php?item_id=1">review_form.php</a> where it says &quot;How do rate this _ &quot; What ever word you insert 
    here will be displayed at the end of that sentence.)</span></label>
    <input type="text" name="item_type" size="40" maxlength="85" value="<?php if (isset($_SESSION['item_type'])) { echo $_SESSION['item_type']; } ?>" />
  </p>
  <br /><br />
<br />

  <p>
  <label for="name">Enter Code to Right: <span class="small">(Case SeNsiTiVe)</span>
  <div align="right">
  <input name="button" type="button" onclick="document.getElementById('randImage2').setAttribute('src', '<?php echo "$directory"; ?>/login/randomImage3.php?r='+ Math.floor(Math.random() * 5000));" value="New Code!" />&nbsp;&nbsp;&nbsp;
  </div>
  </label>
  <input name="txtNumber" type="text" id="txtNumber" value="" />
  <img src="<?php echo "$directory"; ?>/login/randomImage3.php" id='randImage2' alt="Verification Number" />
  </input>
  </p>
  <br />
  <br />
  <p>
    <label for="name">Your Name:</label>
    <input type="text" name="name" size="40" maxlength="85"  value="<?php if (isset($_SESSION['name'])) { echo $_SESSION['name']; } ?>" />
  </p>
  <p>
    <label for="name">Your Email: <span class="small">(for private notification purposes)</span></label>
    <input type="text" name="email" value="<?php if (isset($_SESSION['email'])) { echo $_SESSION['email']; } ?>" size="40" maxlength="85" />
  </p>
  <br />
  <br />
  <br />
  If any of the features below are applicable for the item you are creating, enter the information and click the Select box to make it active.<br />
  <br />
  <?php
	$sql = "SELECT * FROM review_items_supplement order by id asc";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	while ($row = mysql_fetch_array($sql_result)) {
?>
  <!--   <tr>
      <td><strong><?php echo $row['itemname'] ?></strong></td>
      <td align="left"><textarea cols="27" style="width:195px" rows="3" name="review_supplement_item_<?php echo $row['id'] ?>" ></textarea></td>
    </tr> -->
  <p>
    <label for="name"><strong><?php echo $row['itemname'] ?></strong></label>
    <textarea name="review_supplement_item_<?php echo $row['id'] ?>" ></textarea>
    &nbsp;&nbsp;
    <input name="show_review_supplement_item_<?php echo $row['id'] ?>" type="checkbox" value="<?php echo $row['id'] ?>" />
    Select</p>
  <?php
	}
?>
  <p align="center"><br />
    <input type="submit" onclick="MM_validateForm('item_name','','R','item_type','','R','name','','R','email','','RisEmail');return document.MM_returnValue" value="Submit Item" />
  </p>
</form>
</fieldset>
<?php

BodyFooter();
}
exit;

?>
