
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
-- Table structure for table `review_items_user_supplement_data`
--

CREATE TABLE `review_items_user_supplement_data` (
  `review_item_id` int(11) NOT NULL default '0',
  `item_supplement_id` int(11) NOT NULL default '0',
  `value` varchar(255) default NULL,
  PRIMARY KEY  (`review_item_id`,`item_supplement_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

ALTER TABLE `review_items` ADD `category_id` INT( 11 ) NULL DEFAULT '-1';

ALTER TABLE `review_category_list` ADD `parent_id` INT( 11 ) NOT NULL DEFAULT '-1';

ALTER TABLE `review_items_user` ADD `category_id` INT( 11 ) NOT NULL DEFAULT '1';