<?php
include ('body.php');
include ('config.php');

//Display the header
BodyHeader("Five Star Review - Features","","review features");
?> 
<p><font size="2" face="Arial, Helvetica, sans-serif"> - Can be used to rate books, 
  videos, music, images, websites....the sky's the limit!<br />
  - Fast mysql backend<br />
  - One template file controls the entire site/script layout<br />
  - Users can add html to their reviews, including images!</font></p><p><?php $filename = '/usr/home/reviewscr/domains/review-script.com/public_html/z8z2zslesdi93II/Five Star Review Script Version 5.zip';
if (file_exists($filename)) {
   echo "The Five Star Review Script was last updated on " . date ("F d, Y", filemtime($filename));
} ?></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>9/25/2008</strong> - <font color="#FF0000"><strong>Version 5.5 Released!</strong></font> Added loads of features to the administrative side. When editing reviews, items, comments and categories a new, blazing fast AJAX interface allows the admin to sort each column as well as set filters. The listing has also been AJAX paginated. Try it out in the <a href="admin">Admin Area</a> (user - webmaster / pass - 4tugboat) or take a look at a <a href="http://www.review-script.com/images/admin-tables.gif">screen shot</a>.</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>8/4/2008</strong> - <font color="#FF0000"><strong>Version 5.4 Released!</strong></font> Some enhancements to recommend.php and /search/index.php.</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>6/4/2008</strong> - <font color="#FF0000"><strong>Version 5.3 Released!</strong></font> Added AJAX tabbed navigation to the index page. Similar to the navigational tabs used at yahoo.com.</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>3/24/2008</strong> - <font color="#FF0000"><strong>Version 5.2 Released!</strong></font> Several new features were added as well as a couple bug fixes.<br />
  - Top Rated Items shown on the front page now show a hoaver image if an image for that item exists.<br />
  - New Login/UserCP Block added. On the front page or anywhere on your review site a box will display the login form if the user is not logged in. If the user logs in, there is no page refresh due to the usage of AJAX technology. If the user is already logged in, their profile image is shown as well as links to Edit Profile, Edit Photo, Manage Notes, My Reviews, Logout and Change My Password.<br />
  - Changed the way the url's are displayed to make them even more search engine friendly
  .</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>3/3/2008</strong> - <font color="#FF0000"><strong>Version 5.1 Released!</strong></font> I'm on a roll with new Five Star Review Script enhancements so here's the list of new  features:<br />
-Added links to bookmark or share a review in user accounts at delicious, digg, email, favorites, facebook, fark, furl, google, live, myweb,   myspace, newsvine, reddit, slashdot, stumbleupon, technorati, twitter and more!<br />
-New AJAX pagination system added to the review listings on index2.php. No page reloads when going to the next page of reviews. Lightning quick!<br />
-Ability to create a child category in with mutliple parent categories. For example, in the following category tree, Restaurants is used in two different areas and each will have different restaurants listed (items).<br />
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Texas: Houston: Restaurants<br />
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Texas: Austin: Restaurants<br />
 -Multiple requests for this one - Ability to add <strong>UNLIMITED</strong> features to a review item. For example, you can create fields and assign a value to appear with them:<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Color: Blue<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Size: Large<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price: $5.00<br />
-Each review feature is searchable with the built in review search engine.<br />
-Search engine friendly url's. For example, http://www.trade-review.com/review/index2.php?item_id=8 now becomes http://www.trade-review.com/review/review-item/8.php. Not all of the script uses the mod_rewrite feature but the most important pages do.<br />
-Javascript slider to show the unlimited features area.<br />
-Added CSS hover display for item image<br />
-Changed useful rating to allow a rating per review instead of per item.<br />
-Cosmetic tweaks in layout and design</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>1/23/2008</strong> - <font color="#FF0000"><strong>Version 5.0 Released!</strong></font> Lots of new features!  My last update on this page was in 2004 but don't let that fool you. I've been consistently adding new features to the script between then and now. The latest changes and features added to this script are HUGE so I thought I'd update this page a bit.<br />
  -Google Base integration. Add all of your reviews to Google's new Review system. This could result in larges surges of traffic to your site. Details <a href="google_base_integration.php">here</a>.</font><font size="2" face="Arial, Helvetica, sans-serif"><br />
  -New front page layout and design using CSS.<br />
  -New template design laid out with CSS.<br />
  -AJAX Features!<br />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-</font><font size="2" face="Arial, Helvetica, sans-serif">Users login without a page refresh.</font><br />
  <font size="2" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-When users register, the username entered is instantly looked up to see if it is taken. If it is taken, the user is given a list of suggested usernames that are available. </font><br />
  <font size="2" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-CAPTCHA feature prevents spambots from attacking the registration form, email a friend, and &quot;report this&quot;. New CAPTCHA image is generated without a page refresh.<br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-NEW Comments System. Allows users to write a comment about another's review.<br />
  -Administratively Edit and Delete Comments<br />
  -Require Users to register before posting comments</font><font size="2" face="Arial, Helvetica, sans-serif"><br />
  -Code optimization.<br />
  -Nearly every file tweaked or enhanced<br />
  -Updated HTML editor used in admin area. Loads much quicker.<br />
  -Download file is nearly 1 MB smaller, even with all the added features!</font><br />
  <br />
</p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>8/13/2004</strong> - <font color="#FF0000"><strong>New Features<br />
</strong></font>-Added a Badword Filter. Prevents users from using profanity in their reviews.<br />
-Admin feature allows administrator to select what words not to allow and what words they should be replaced with. Also the ability to edit and delete the badword list.<br />
-The badword list can also contain html code.  For example, you might want to change the color of font of the goodwords to let your readers know a word substitution took place. </font></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>8/10/2004</strong> - <font color="#FF0000"><strong>Version 3.0 Released!</strong></font> Lots of new features! <br />
  -Option for users to recommend a specific product to their friends. They can click a link and enter the name and email of a friend and the script emails the recommendation.<br />
  -Admin feature allows you to view the name and email address of the sender and recipient  along with the personalized message sent. This information can be exported to your spreadsheet program with the click of a button! Great if you want to add it to your contacts database!<br />
  -Now compatible with PHP version 5.0<br />
  -Optimized to work on servers running with register_globals set to on or off.<br />
  -Admin Area now shows how many User Submitted Items for Review need to be approved. </font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>7/23/2004</strong> - <font color="#FF0000"><strong>New Features<br />
</strong></font>- Front page now displays how many reviews were made on the product<br />
- User now has the ability to suggest an item for review<br />
- Admin receives email when an item is suggested<br />
- 


 Admin is able to reject an item suggestion. There is also an area where you can optionally explain to the user why the suggestion was not implemented. <br />
 - When an item is approved or rejected the user suggesting the item is automatically notified via email.<br />
 <br />
</font><font size="2" face="Arial, Helvetica, sans-serif"><strong>7/07/2004</strong> - <font color="#FF0000"><strong>New Features<br />
  </strong></font>- Admin panel allows you to set the display order of the categories<br />
  - Admin panel allows you to set the display order of the review items within the categories (could be used to sell the top listing position to the manufacturer/owner/dealer of the product being reviewed).<br />
  - Admin panel allows you to edit items for review.<br />
  - Disabled ability for users to insert dangerous javascript code into their reviews.
</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>5/26/2004</strong> - <font color="#FF0000"><strong>New Feature</strong></font> - Added function to check and see if user has already reviewed an item. If so it denies the second review. The config file will allow you to specify whether or not you want your users to have the ability to review an item multiple times. </font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>4/15/2004</strong> - <font color="#FF0000"><strong>Version 2.0 Released!</strong></font> Lots of new features! <br />
  - Option in the config file to require registration.<br />
  - Option in the config file to allow reviews to be posted without admin approval.<br />
  -Upon registration, user and administrator receive a welcome email with registration information.<br />
  -Multiple (4)Layout Options: <a href="http://www.review-script.com/layouts.php" target="_blank">http://www.trade-review.com/review/layouts.php</a><br />
  -Option in the Admin Area to add, delete and edit categories. Allows reviews to be displayed in Yahoo style categories. <br />
  -Optional integration with phpBB Bulletin Board. The same user database is used. </font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>3/14/2004</strong> - Added a review archive that will enable google and other search engines to index every review on your site! Here's an example of the &quot;search engine friendly&quot; archive - <a href="http://www.trade-review.com/review/archive.php">http://www.trade-review.com/review/archive.php</a></font></p>
<p> <font size="2" face="Arial, Helvetica, sans-serif"><strong>3/6/2004</strong> - The next version of this script will give the administrator the ability to specify whether or not users have to register before posting a review. The config file also has a setting to allow reviews to be posted without admin approval. </font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>3/2/2004</strong> - By demand, &quot;display the number of stars that a product has on the demo page i.e. a list of all the products and it's current rating next to it&quot;.</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>2/27/2004</strong> - 
  

  When approving reviews, I added the display of the item name and type. This will allow the admin the ability to make sure the review is relevant to the item. If you ask for a review of a ski resort, and the review says "great beach" you'll know not  to approve it. </font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">Also added: When a user submits a review, the administrator will receive an email notification that contains the review as well as the user's IP address. </font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>2/26/2004</strong> - <font color="#000000">Changed the review_insert.php feature so that after reviewing your product, the user gets returned to the page where they viewed the review.</font></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>2/12/2004</strong> - <font color="#000000">Enhanced
        the feature added on 11/27/2003. </font></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>1/29/2004</strong> - <font color="#000000">Long overdue...added the ability to change the admin username and password from the administration menu. </font></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>11/27/2003</strong> - <font color="#FF0000"><strong>New Feature </strong></font><font size="2" face="Arial, Helvetica, sans-serif"><font color="#000000">added in version 1.4. There is now a new file that will enable you to insert one short line of code (include &quot;review_insert.php&quot;;) into any php page and the reviews will appear exactly where you place the tag. Very convenient and customizable! <a href="http://www.trade-review.com/review/review_insert_sample.php?item_id=11">Sample</a> </font></font></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>11/16/2003</strong> - <font color="#000000">Fixed a very minor bug. The review item name was not showing on a page in which there were no user reviews. A few simple lines of code took care of the problem. </font></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>9/2/2003</strong> 
  - <font color="#FF0000"><strong>New Feature </strong><font color="#000000">added 
    in version 1.3 and a bug fix that will affect certain servers. The admin area 
    has a couple of enhanced features including the ability to edit review information.</font></font></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>3/16/2003</strong> 
  - <font color="#FF0000"><strong>New Feature</strong></font> added in version 
  1.2 - Users can sort the reviews with the following search criteria:</font></p>
<blockquote> 
  <p><font size="2" face="Arial, Helvetica, sans-serif">&#8226; Newest First <br />
    &#8226; Oldest First <br />
    &#8226; Highest Rating First <br />
    &#8226; Lowest Rating First <br />
    &#8226; Most Helpful First <br />
    &#8226; Least Helpful First <br />
    &#8226; 5-Star Reviews Only <br />
    &#8226; 4-Star Reviews Only <br />
    &#8226; 3-Star Reviews Only <br />
    &#8226; 2-Star Reviews Only <br />
    &#8226; 1-Star Reviews Only<br />
    </font></p>
</blockquote> 
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>3/15/2003</strong> 
  - <font color="#FF0000"><strong>New Feature</strong></font> added in version 
  1.1 - You can now specify how many reviews you want displayed per page! Will 
  also display Previous and Next links as well as what page the user is on.</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Very flexible! Have 
  your review page auto-generated from the database or add links to your existing 
  html pages. </strong><br />
  For example, the link to my review page of this script is <a href="http://www.trade-review.com/review/index2.php?item_id=11">http://www.trade-review.com/review/index2.php?item_id=11</a>. 
  Each product or item will have it's own item_id number.</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Admin Control Panel</strong> 
  - From the Admin Control Panel you can:</font></p>
<blockquote> 
  <p><font size="2" face="Arial, Helvetica, sans-serif">&#8226; Approve a Review 
    before it is listed<br />
    &#8226; Delete a Review <br />
    &#8226; Delete all Unapproved Reviews<br />
    &#8226; Add Item for Review<br />
    </font></p>
</blockquote>
<p><font size="2" face="Arial, Helvetica, sans-serif"><a href="/admin/index.php"><strong>Demo</strong></a><strong> 
  the Five Star Review Script - Admin Control Panel</strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Requirements</strong> 
  - PHP4, MYSQL</font></p>
<table border="1" align="center">
  <tr>
    <td><BR><?php include "/usr/local/psa/home/vhosts/review-script.com/httpdocs/poll/poll.php"; ?></td>
  </tr>
</table>
<p>
<!------- Start of HTML Code ------->
<form action="http://www.hotscripts.com/cgi-bin/rate.cgi" method="POST"><input type="hidden" name="ID" value="20999"><table BORDER="0" align="center" CELLSPACING="0" bgcolor="#800000"><tr><td><table border="0" cellspacing="0" width="100%" bgcolor="#FFCC33" cellpadding="3"><tr><td align="center"><font face="arial, verdana" size="2"><b>Rate Our Script @
<a href="http://www.hotscripts.com">HotScripts.com</a></b></font></td><td align="center"><select name="ex_rate" size="1"><option selected>Select</option><option value="5">Excellent!</option><option value="4">Very Good</option><option value="3">Good</option><option value="2">Fair</option><option value="1">Poor</option></select></td><td align="center"><input type="submit" value="Go!"></td></tr></table></td></tr></table></form><!------- End of HTML Code ------->

<br /><table width="190" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#000000">
<tr>
<form action="http://www.devscripts.com/vote.php" target="_blank" method="post">
<input type="hidden" name="strScriptId" value="1963">
<td align="center" bgcolor="#FFEFCE" height="70">
<font face="Verdana" size="1" color="#000000">
Rate our script at <a href="http://www.devscripts.com" target="_blank"><font face="Verdana" size="1" color="#0000FF"><b><u>DevScripts</u></b></font></a>  <br />
<select name="strRValue">
<option value="0">-- Choose Your Rating --</option>
<option value="10">5 stars</option>
<option value="8">4 stars</option>
<option value="6">3 stars</option>
<option value="4">2 stars</option>
<option value="2">1 star</option>
</select><br /><input type="submit" value="Rate Our Script">
</font>	
</td>
</form>
</tr>
</table>
</p><p>&nbsp;</p>
<!-- NeedScripts.com Rating Code Below -->
<form action="http://www.needscripts.com/cgi/rate.cgi" method="POST">
<input type=hidden name="ID" value="32657">
<table border=1 align="center" cellpadding=0 cellspacing=0 bordercolor="#003399" bgcolor="#F0F0F0" style="border-collapse: collapse">
<tr><td align=center> <font face="Verdana" color="#000080" style="font-size: 9pt"> <b>
  <a href="http://www.needscripts.com/Resource/32657.html" style="text-decoration: none">
  <font color="#000080">Rate us</font></a> at<br />
<a href="http://www.needscripts.com/" style="text-decoration: none">
<font color="#000080">NeedScripts.com</font></a></b></font></td>
</tr><tr><td>
<table Border=0 Cellpadding=4 Cellspacing=0 bgcolor="#FFFFFF" style="border-collapse: collapse" bordercolor="#111111">
<tr><td> <font face="Verdana"> <input type="radio" value="5" name=rate> </font> </td>
<td> <font face="Verdana" size="2">Excellent! </font> </td> </tr>
<tr><td> <font face="Verdana"> <input type="radio" value="4" name=rate> </font> </td>
<td> <font face="Verdana" size="2">Very Good </font> </td>
</tr><tr><td> <font face="Verdana"> <input type="radio" value="3" name=rate> </font> </td>
<td> <font face="Verdana" size="2">Good </font> </td>
</tr><tr><td> <font face="Verdana"> <input type="radio" value="2" name=rate> </font> </td>
<td> <font face="Verdana" size="2">Fair </font> </td>
</tr><tr><td> <font face="Verdana"> <input type="radio" value="1" name=rate> </font> </td>
<td> <font face="Verdana" size="2">Poor </font> </td>
</tr><tr><td align=center colspan="2"> <font face="Verdana"> <input type=submit value="Cast My Vote!"  style="font-family: Verdana; font-weight: bold; color: #FFFFFF; letter-spacing: -1pt; background-color: #003366"> </font> </td>
</tr></table></td></tr></table></form>
<!-- NeedScripts.com Rating Code Above -->
<?php
BodyFooter();
exit;
?>

