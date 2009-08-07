ALTER TABLE `review` ADD `rating2` CHAR(3) NOT NULL,
ADD `sig_show` enum('y','n') NOT NULL default 'n' ;

ALTER TABLE `review` ADD FULLTEXT( `summary`, `review`);

ALTER TABLE `review` ADD `user_image` VARCHAR( 75 ) NOT NULL ;

ALTER TABLE `review_admin` CHANGE `passtext` `passtext` CHAR( 40 ) NOT NULL;

DELETE FROM `review_admin`;

INSERT INTO `review_admin` ( `username` , `passtext` ) 
VALUES (
'webmaster', 'c824758b742f3433c4e1e338b041e17d'
);

ALTER TABLE `review_category_list` ADD `parent` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `review_category_list` ADD `cat_id_cloud` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;

CREATE TABLE IF NOT EXISTS `review_favorites` (
  `fav_id` int(11) NOT NULL auto_increment,
  `item_id_fav` int(9) NOT NULL default '0',
  `username` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`fav_id`),
  KEY `username` (`username`)
) TYPE=MyISAM;


ALTER TABLE `review_items` ADD `item_image` VARCHAR( 12 ) NOT NULL ,
ADD `item_aff_url` VARCHAR( 255 ) NOT NULL ,
ADD `item_aff_txt` VARCHAR( 255 ) NOT NULL ,
ADD `item_aff_code` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE `review_items` CHANGE `item_desc` `item_desc` LONGTEXT NOT NULL;

CREATE TABLE IF NOT EXISTS `review_subscriptions` (
  `sub_id` int(11) NOT NULL auto_increment,
  `item_id_sub` int(11) NOT NULL default '0',
  `username` varchar(50) NOT NULL default '',
  `created` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`sub_id`)
) TYPE=MyISAM;


ALTER TABLE `review_users` ADD `email` VARCHAR( 200 ) NOT NULL ,
ADD `regkey` VARCHAR( 25 ) NOT NULL ,
ADD `skype` VARCHAR( 100 ) NOT NULL ,
ADD `adsense_clientid` CHAR( 20 ) NOT NULL ,
ADD `adsense_channelid` CHAR( 15 ) NOT NULL ;


ALTER TABLE `review_users` CHANGE `passtext` `passtext` VARCHAR( 50 ) NOT NULL;

ALTER TABLE `review_users` CHANGE `state` `state` CHAR( 5 ) NOT NULL;


CREATE TABLE IF NOT EXISTS `review_users_reset` (
  `userresetid` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(200) NOT NULL default '0',
  `expire` date NOT NULL default '0000-00-00',
  `resetid` int(25) NOT NULL default '0',
  `email` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`userresetid`),
  KEY `username` (`username`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS `review_config` (
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
  `mail_profile` enum('y','n') NOT NULL default 'y',
  `use_maillist` enum('y','n') NOT NULL default 'n',
  `groupid` int(4) NOT NULL default '1',
    `use_spell` enum('y','n') NOT NULL default 'n',
  `user_upload` enum('y','n') NOT NULL default 'n',
  `use_phpBB` enum('yes','no') NOT NULL default 'no',
  `phpbb_dir` varchar(250) NOT NULL default '',
  `use_vb` enum('yes','no') NOT NULL default 'no',
  `vb_dir` varchar(250) NOT NULL default '',
  `salt` varchar(250) NOT NULL default 'dn983jmdnd1!d',
  `domain` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`sitename`)
) TYPE=MyISAM;

CREATE TABLE `item_rating_criteria` (
  `item_id` int(11) unsigned NOT NULL default '0',
  `criteriaId` int(11) unsigned NOT NULL default '0'
) TYPE=MyISAM;



CREATE TABLE `rating_criteria` (
  `criteriaId` int(11) unsigned NOT NULL auto_increment,
  `criteriaName` varchar(50) NOT NULL default '',
  `isActive` char(1) NOT NULL default 'T',
  PRIMARY KEY  (`criteriaId`)
) TYPE=MyISAM;



CREATE TABLE `rating_details` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `review_id` int(11) unsigned NOT NULL default '0',
  `criteriaId` bigint(20) unsigned NOT NULL default '0',
  `ratingValue` int(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `review_adsense` (
  `ad_id` int(11) NOT NULL auto_increment,
  `ad_clientid` char(20) NOT NULL default '',
  `ad_channel` char(15) NOT NULL default '',
  `ad_share` enum('y','n') NOT NULL default 'y',
  `ad_active` enum('y','n') NOT NULL default 'y',
  `ad_percent` char(3) NOT NULL default '',
  PRIMARY KEY  (`ad_id`)
) TYPE=MyISAM; 

    CREATE TABLE `storage_bin` (
      `id` int(5) NOT NULL auto_increment,
      `varname` varchar(32) NOT NULL,
      `varvalue` blob NOT NULL,
      PRIMARY KEY  (`id`)
) TYPE=MyISAM;

ALTER TABLE `review_items_user` ADD `item_image` CHAR( 4 ) NOT NULL ;

ALTER TABLE `review_config` ADD `user_upload` ENUM( 'y', 'n' ) NOT NULL DEFAULT 'n',
ADD `use_spell` ENUM( 'y', 'n' ) NOT NULL DEFAULT 'n';
