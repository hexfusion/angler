 ALTER TABLE `rating_details` CHANGE `ratingValue` `ratingValue` FLOAT UNSIGNED NOT NULL DEFAULT '0';
 UPDATE review_admin SET version = '5.6';