<?php
$license = 'review-0b9563dad5cb'; //valid license for product.

$admin = 'sam@westbranchresort.com'; //administrator email address

$sitename = "West Branch Angler";  //insert the name of your site.

$url = "http://www.westbranchangler.com";  //http://www.review-script.com would be an example of your url.

$directory = "/review";  //what directory is this script in.  Generally it will be /review

$domain = "westbranchangler.com";  //the domain the script is installed on.  Don't use www.  Used for security purposes.

$registered = "no";  //set to yes if you want to require users to register before posting a review

$approve = "n";  // set to "y" if you want user reviews to post without having to approve


$multiple = "y";  // set to "y" to enable users to review the same item more than once.


$characters = "1000";  //change this to the number of characters you want to allow the user to input for the review.

$num_words_req = "3";  //number of words user is required to type for their review.

//Choose how many reviews per page to display
$NumReviews = 5;

//Choose how many pages to be displayed in pagination
$NumPages=5;	

$use_bbcode = "no";  //use bbcode set to yes or no

$user_upload = "n";  //can the user upload an image with their review?  y or n.

//if user edits profile or uploads photo do you want to receive an email notification?
$mail_profile = "n"; //y or n

/////////////////////////////////////////////
//use webinsta mail_list program?  http://www.webinsta.com/downloadm.html
$use_maillist = "n";  //y or n

$groupid = "1"; //Group name ID which you get from the webinsta Groups link in your webinsta admin panel
/////////////////////////////////////////////

//////////
//Do NOT TOUCH THIS!
$salt = "dn983jmdnd1!d";
ini_set('display_errors', false); //do not change this.
//////////
?>