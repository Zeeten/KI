<?php $themeName = basename( dirname(dirname(dirname(__FILE__))) ); ?>
<?php if ($reviews) { ?>
<?php foreach ($reviews as $review) { ?>
<div class="review-list clearfix wrapper">
	<div class="author">
		<b><?php echo $review['author']; ?></b> 
		<?php echo $text_on; ?> 
		<?php echo $review['date_added']; ?>
	</div>
	<div class="rating">
		<img src="catalog/view/theme/<?php echo $themeName;?>/image/stars-<?php echo $review['rating'] . '.png'; ?>" alt="<?php echo $review['reviews']; ?>" />
	</div>
	<p class="text"><?php echo $review['text']; ?></p>
</div>
<?php } ?>

<div class="pagination"><?php echo $pagination; ?></div>
<?php } else { ?>
<div class="content wrapper"><?php echo $text_no_reviews; ?></div>
<?php } ?>
