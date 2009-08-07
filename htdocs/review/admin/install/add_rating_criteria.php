<p>If the review directory does not have a file called f_secure.php you are running a version less than 4. You need to upgrade by downloading the latest version.</p>
<p>If the review directory does have a file called f_secure.php you are running version 4. If you wish to add unlimited rating criteria, do the following: </p>
<p>1. Open config.php and add this:</p>
<p>$NumPages=5;	//Choose how many pages to be displayed in pagination<br>
</p>
<p>2. Add the following to your database:</p>
<p>CREATE TABLE  IF NOT EXISTS `item_rating_criteria` (<br>
`item_id` int(11) unsigned NOT NULL default '0',<br>
`criteriaId` int(11) unsigned NOT NULL default '0'<br>
) TYPE=MyISAM;<br>
</p>
<p>CREATE TABLE   IF NOT EXISTS `rating_criteria` (<br>
  `criteriaId` int(11) unsigned NOT NULL auto_increment,<br>
  `criteriaName` varchar(50) NOT NULL default '',<br>
  `isActive` char(1) NOT NULL default 'T',<br>
  PRIMARY KEY  (`criteriaId`)<br>
  ) TYPE=MyISAM;</p>
<p>CREATE TABLE   IF NOT EXISTS `rating_details` (<br>
  `id` bigint(20) unsigned NOT NULL auto_increment,<br>
  `review_id` int(11) unsigned NOT NULL default '0',<br>
  `criteriaId` bigint(20) unsigned NOT NULL default '0',<br>
  `ratingValue` int(3) unsigned NOT NULL default '0',<br>
  PRIMARY KEY  (`id`)<br>
  ) TYPE=MyISAM;</p>
<p>3. Get a new zip download from review-script.com and upload the files in the admin folder. </p>
<p>4. That's it, you're done! </p>
<p>&nbsp;</p>




