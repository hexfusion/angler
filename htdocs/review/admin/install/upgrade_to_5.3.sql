ALTER TABLE `review_admin` ADD `version` CHAR( 7 ) NOT NULL AFTER `passtext` ;
UPDATE review_admin SET version = '5.3';