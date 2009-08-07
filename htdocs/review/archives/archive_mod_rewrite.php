<?php
include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$sql = "SELECT * FROM 
			review_items WHERE item_name != '' ORDER by item_id
";
$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

//Display the header
BodyHeader("Reviews - $sitename");
?>
<hr noshade size=1> 
<table width="99%" border="0" align="center" cellpadding="3" cellspacing="0"> 
  <tr> 
    <td width="88">&nbsp;</td> 
    <td width="267"><strong>Name</strong></td> 
    <td width="369"><strong>Description</strong></td> 
  </tr> 
  <?php 
while ($row = mysql_fetch_array($sql_result)) { 
	$item_name = stripslashes($row["item_name"]);
	$item_id = $row["item_id"];
	$item_desc = stripslashes($row["item_desc"]);

$sql_avg = "select avg(rating) as average from review WHERE  rating !='' AND review.review_item_id = $item_id AND approve = 'y'";

			$sql_result_avg = mysql_query($sql_avg)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while ($row2 = mysql_fetch_array($sql_result_avg)) { 
	$average = $row2["average"];
}
?> 
  <tr> 
    <td width="88"><a href=<?php echo "$directory"; ?>/review-item/<?php echo "$item_id"; ?>.php><img src="<?php echo "$directory"; ?>/images/review.gif" hspace="1" vspace="1" border="0"></a></td> 
    <td><?php echo "$item_name"; ?><br /> 
      <font face=verdana,arial,helvetica size=-2><b>Avg. Customer Review<br /> 
      </b> 
      <?php $average_for = sprintf ("%01.1f", $average); echo "($average_for Stars):"; ?> 
      </font> 
      <?php 		 $display = ($rating/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div></td> 
    <td><?php echo "$item_desc<BR><BR>"; ?></td> 
  </tr> 
  <?php } ?> 
</table> 
<?php
BodyFooter();
exit;
?> 
