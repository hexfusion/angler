<?php
session_start();

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

BodyHeader("Google Base Integration with Five Star Review Script","Google Base Integration with Five Star Review Script","google, google base, google reviews");
?>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0"><tr>
    <td><p>&nbsp;</p>
      <p>The Five Star Review Script now integrates with <a href="http://base.google.com/base" target="_blank">Google Base Reviews</a>. Add the power of PHP, MYSQL, XML, and RSS 2.0 together and you have a powerful feature that allows your entire review database to be added to Google's Review system. Not only do your review appear in Google Reviews, but they can appear in Google Product Search and Google Web Search. Here is some info from the google website:</p>
      <p>&quot;Google Base is a place where you can easily submit all types of online and   offline content, which we'll make searchable on Google (if your content isn't   online yet, we'll put it there). You can describe any item you post with   <em>attributes</em>, which will help people find it when they do related   searches. In fact, based on your items' relevance, users may find them in their   results for searches on <a href="http://www.google.com/products">Google Product   Search</a> and even our main <a href="http://www.google.com">Google web   search</a>.&quot;</p>
      <p>Why add your reviews to Google Base Reviews when they are already in Google's web search? Again, Google explains it best:</p>
      <p>&quot;Google Base enables you to add <em>attributes</em> describing your content, so   that searchers can easily find it. The more popular individual attributes   become, the more often we'll suggest them when others post the same items.   Similarly, items that become more popular will show up as suggested item types   in the <strong>Choose an existing item type</strong> drop-down menu.&quot;</p>
      <p>Using the Google Base Review integration feature is extremely simple. After signing up with google, simply view /review/rss/rss_google.xml (example <a href="<?php echo "$directory"; ?>/rss/rss_google.xml">here</a>) on your website and upload the page to google. That's it. Simple!</p>
      <p>Here is some additional information about <a href="http://base.google.com/support/bin/answer.py?answer=59260&amp;hl=en_US" target="_blank">Google Base</a>.</p>
    <p>&nbsp;</p></td>
  </tr></table>



<?php
BodyFooter();
exit;
?>
