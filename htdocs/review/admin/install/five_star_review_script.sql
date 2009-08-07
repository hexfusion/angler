--
-- Table structure for table `review_admin`
--

CREATE TABLE `review_admin` (
  `username` char(10) NOT NULL default '',
  `passtext` char(41) NOT NULL default ''
) TYPE=MyISAM;


INSERT INTO `review_admin` (`username`, `passtext`) VALUES ('webmaster', 'c824758b742f3433c4e1e338b041e17d');

ALTER TABLE `review_admin` ADD `version` CHAR( 7 ) NOT NULL AFTER `passtext` ;
UPDATE review_admin SET version = '5.5';


-- --------------------------------------------------------

--
-- Table structure for table `item_rating_criteria`
--

CREATE TABLE `item_rating_criteria` (
  `item_id` int(11) unsigned NOT NULL default '0',
  `criteriaId` int(11) unsigned NOT NULL default '0'
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `rating_criteria`
--

CREATE TABLE `rating_criteria` (
  `criteriaId` int(11) unsigned NOT NULL auto_increment,
  `criteriaName` varchar(50) NOT NULL default '',
  `isActive` char(1) NOT NULL default 'T',
  PRIMARY KEY  (`criteriaId`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `rating_details`
--

CREATE TABLE `rating_details` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `review_id` int(11) unsigned NOT NULL default '0',
  `criteriaId` bigint(20) unsigned NOT NULL default '0',
  `ratingValue` float unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL auto_increment,
  `rating` varchar(4) NOT NULL default '',
  `summary` varchar(250) NOT NULL default '',
  `review` longtext NOT NULL,
  `source` varchar(250) NOT NULL default '',
  `location` varchar(250) NOT NULL default '',
  `review_item_id` int(11) NOT NULL default '0',
  `visitorIP` varchar(15) NOT NULL default '0',
  `date_added` date NOT NULL default '0000-00-00',
  `useful` int(11) NOT NULL default '0',
  `notuseful` int(11) NOT NULL default '0',
  `approve` enum('y','n') NOT NULL default 'n',
  `username` varchar(50) NOT NULL default '',
  `sig_show` enum('y','n') NOT NULL default 'n',
  `rating2` char(3) NOT NULL default '',
  `user_image` varchar(75) NOT NULL default '',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `summary` (`summary`,`review`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_adsense`
--

CREATE TABLE `review_adsense` (
  `ad_id` int(11) NOT NULL auto_increment,
  `ad_clientid` varchar(25) NOT NULL default '',
  `ad_channel` varchar(15) NOT NULL default '',
  `ad_share` enum('y','n') NOT NULL default 'y',
  `ad_active` enum('y','n') NOT NULL default 'y',
  `ad_percent` char(3) NOT NULL default '',
  PRIMARY KEY  (`ad_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_badwords`
--

CREATE TABLE `review_badwords` (
  `id` int(11) NOT NULL auto_increment,
  `badword` varchar(100) NOT NULL default '',
  `goodword` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_category_list`
--

CREATE TABLE `review_category_list` (
  `cat_id_cloud` int(11) NOT NULL auto_increment,
  `category` varchar(255) NOT NULL default '',
  `catorder` tinyint(4) NOT NULL default '0',
  `parent` varchar(255) NOT NULL default '',
  `parent_id` int(11) NOT NULL default '-1',
  PRIMARY KEY  (`cat_id_cloud`),
  UNIQUE KEY `category` (`category`,`parent_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_comment`
--

CREATE TABLE `review_comment` (
  `id` int(10) NOT NULL auto_increment,
  `author` varchar(30) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `comment` text NOT NULL,
  `review_id` varchar(7) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_config`
--

CREATE TABLE `review_config` (
  `license` varchar(50) NOT NULL default '',
  `admin` varchar(100) NOT NULL default '',
  `sitename` varchar(200) NOT NULL default '',
  `url` varchar(50) NOT NULL default '',
  `directory` varchar(50) NOT NULL default '',
  `registered` enum('no','yes') NOT NULL default 'yes',
  `approve` enum('n','y') NOT NULL default 'n',
  `multiple` enum('n','y') NOT NULL default 'n',
  `characters` int(11) NOT NULL default '1000',
  `num_words_req` int(3) NOT NULL default '3',
  `NumReviews` int(3) NOT NULL default '5',
  `NumPages` int(3) NOT NULL default '5',
  `use_bbcode` enum('yes','no') NOT NULL default 'yes',
  `use_spell` enum('y','n') NOT NULL default 'n',
  `user_upload` enum('y','n') NOT NULL default 'n',
  `mail_profile` enum('y','n') NOT NULL default 'y',
  `use_maillist` enum('y','n') NOT NULL default 'n',
  `groupid` int(4) NOT NULL default '1',
  `use_phpBB` enum('yes','no') NOT NULL default 'no',
  `phpbb_dir` varchar(250) NOT NULL default '',
  `use_vb` enum('yes','no') NOT NULL default 'no',
  `vb_dir` varchar(250) NOT NULL default '',
  `salt` varchar(250) NOT NULL default 'dn983jmdnd1!d',
  `domain` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`sitename`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_editor`
--

CREATE TABLE `review_editor` (
  `id` int(11) NOT NULL auto_increment,
  `rating` varchar(4) NOT NULL default '',
  `summary` varchar(250) NOT NULL default '',
  `review` longtext NOT NULL,
  `source` varchar(250) NOT NULL default '',
  `location` varchar(250) NOT NULL default '',
  `review_item_id` int(11) NOT NULL default '0',
  `visitorIP` varchar(15) NOT NULL default '0',
  `date_added` date NOT NULL default '0000-00-00',
  `useful` int(11) NOT NULL default '0',
  `notuseful` int(11) NOT NULL default '0',
  `approve` enum('y','n') NOT NULL default 'n',
  `username` varchar(50) NOT NULL default '',
  `sig_show` enum('y','n') NOT NULL default 'n',
  `rating2` char(3) NOT NULL default '',
  `user_image` varchar(75) NOT NULL default '',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `summary` (`summary`,`review`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_favorites`
--

CREATE TABLE `review_favorites` (
  `fav_id` int(11) NOT NULL auto_increment,
  `item_id_fav` int(9) NOT NULL default '0',
  `username` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`fav_id`),
  KEY `username` (`username`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_items`
--

CREATE TABLE `review_items` (
  `item_id` int(11) NOT NULL auto_increment,
  `item_name` varchar(250) NOT NULL default '',
  `item_desc` longtext NOT NULL,
  `item_type` varchar(250) NOT NULL default '',
  `category` varchar(250) NOT NULL default '',
  `category_id` int(11) default '-1',
  `sortorder` tinyint(4) NOT NULL default '0',
  `item_image` varchar(12) NOT NULL default '',
  `item_aff_url` varchar(250) NOT NULL default '',
  `item_aff_txt` varchar(250) NOT NULL default '',
  `item_aff_code` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`item_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_items_note`
--

CREATE TABLE `review_items_note` (
  `note_id` int(11) unsigned NOT NULL auto_increment,
  `note_item_id` int(11) unsigned default NULL,
  `note_user_name` varchar(50) default NULL,
  `note_notes` text,
  `note_date` date default NULL,
  PRIMARY KEY  (`note_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_items_supplement`
--

CREATE TABLE `review_items_supplement` (
  `id` int(11) NOT NULL auto_increment,
  `itemname` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_items_supplement_data`
--

CREATE TABLE `review_items_supplement_data` (
  `review_item_id` int(11) NOT NULL default '0',
  `item_supplement_id` int(11) NOT NULL default '0',
  `value` varchar(255) default NULL,
  `selected` int(11) NOT NULL default '1',
  PRIMARY KEY  (`review_item_id`,`item_supplement_id`),
  KEY `value` (`value`),
  FULLTEXT KEY `value_2` (`value`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_items_user`
--

CREATE TABLE `review_items_user` (
  `item_id` int(11) NOT NULL auto_increment,
  `item_name` varchar(250) NOT NULL default '',
  `item_desc` longtext NOT NULL,
  `item_type` varchar(250) NOT NULL default '',
  `category` varchar(250) NOT NULL default '',
  `category_id` int(11) NOT NULL default '1',
  `name` varchar(250) NOT NULL default '',
  `item_image` varchar(4) NOT NULL default '',
  `email` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`item_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_items_user_supplement_data`
--

CREATE TABLE `review_items_user_supplement_data` (
  `review_item_id` int(11) NOT NULL default '0',
  `item_supplement_id` int(11) NOT NULL default '0',
  `value` varchar(255) default NULL,
  PRIMARY KEY  (`review_item_id`,`item_supplement_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------
--
-- Table structure for table `review_recommend`
--

CREATE TABLE `review_recommend` (
  `rec_id` int(11) NOT NULL auto_increment,
  `recipient` varchar(59) NOT NULL default '',
  `rec_email` varchar(50) NOT NULL default '',
  `message` text NOT NULL,
  `username` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `send_date` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`rec_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_subscriptions`
--

CREATE TABLE `review_subscriptions` (
  `sub_id` int(11) NOT NULL auto_increment,
  `item_id_sub` int(11) NOT NULL default '0',
  `username` varchar(50) NOT NULL default '',
  `created` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`sub_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_users`
--

CREATE TABLE `review_users` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `username` varchar(50) NOT NULL default '',
  `passtext` varchar(50) NOT NULL default '',
  `email` varchar(200) NOT NULL default '',
  `created` date default NULL,
  `active` enum('y','n') NOT NULL default 'y',
  `visitorIP` varchar(15) NOT NULL default '',
  `sig` varchar(250) NOT NULL default '',
  `sig_url` varchar(250) NOT NULL default '',
  `aboutme` text NOT NULL,
  `city` varchar(250) NOT NULL default 'Anytown',
  `state` varchar(5) NOT NULL default 'AT',
  `zip` mediumint(5) unsigned NOT NULL default '55555',
  `profession` varchar(250) NOT NULL default '',
  `age` char(3) NOT NULL default '',
  `regkey` varchar(25) NOT NULL default '',
  `skype` varchar(100) NOT NULL default '',
  `maillist` enum('y','n') NOT NULL default 'n',
  `adsense_clientid` varchar(20) NOT NULL default '',
  `adsense_channelid` varchar(15) NOT NULL default '',
  `pm_count` int(5) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `review_users_reset`
--

CREATE TABLE `review_users_reset` (
  `userresetid` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(200) NOT NULL default '0',
  `expire` date NOT NULL default '0000-00-00',
  `resetid` int(25) NOT NULL default '0',
  `email` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`userresetid`),
  KEY `username` (`username`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `storage_bin`
--

CREATE TABLE `storage_bin` (
  `id` int(5) NOT NULL auto_increment,
  `varname` varchar(32) NOT NULL default '',
  `varvalue` blob NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;







INSERT INTO `storage_bin` (`id`, `varname`, `varvalue`) VALUES 
(1, 'ab896ecbc37961a655fda5a5eb2dbad4', 0x66616c7365),
(2, '508a7e27948cacfb775cd6b2a9af233d', 0x4172726179);

 ALTER TABLE `rating_details` CHANGE `ratingValue` `ratingValue` FLOAT UNSIGNED NOT NULL DEFAULT '0' ;
