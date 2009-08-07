<ul id="reviewtabs" class="shadetabs">
<li><a href="review_categories_block.php" rel="reviewcontainer" class="selected">Review Categories</a></li>
<li><a href="search_most_useful.php" rel="reviewcontainer">Most Useful</a></li>
<li><a href="reviews_latest2.php" rel="reviewcontainer">Latest Reviews</a></li>
<li><a href="reviews_top_rating.php" rel="reviewcontainer">Top Rated</a></li>

</ul>

<div id="reviewdivcontainer" style="border:1px solid gray;  margin-bottom: 1em; padding: 10px">
<p></p>
</div>

<script type="text/javascript">

var reviews=new ddajaxtabs("reviewtabs", "reviewdivcontainer")
reviews.setpersist(true)
reviews.setselectedClassTarget("link") //"link" or "linkparent"
reviews.init()

</script>