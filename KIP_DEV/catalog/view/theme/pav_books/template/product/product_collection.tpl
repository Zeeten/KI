<?php 
	$themeConfig = $this->config->get('themecontrol');
	$listConfig = array( 
		'cateogry_display_mode' => 'grid', 
		'cateogry_product_row'  => 3,
		'category_pzoom'        => 0
	); 

	$listConfig = array_merge( $listConfig, $themeConfig );

	$DISPLAY_MODE = $listConfig['cateogry_display_mode'];
	$MAX_ITEM_ROW = $listConfig['cateogry_product_row']? $listConfig['cateogry_product_row']:3; 
	$categoryPzoom = $listConfig['category_pzoom'];  
	$text_grid = '';$text_list = '';
	 
?>

<?php if ($products) { ?>
	<div class="category-product">
		<!--Filter-->
		<div class="product-filter clearfix">
			<div class="filter pull-left">
				
				<ul class="display">
					<li><span><?php echo $text_display; ?></span></li>					
					<li><a title="<?php echo $text_grid; ?>" onclick="display('grid');" class="pavicon-grid"><?php echo $text_grid; ?></a></li>
					<li><a title="<?php echo $text_list; ?>" onclick="display('list');" class="active pavicon-list"><?php echo $text_list; ?></a></li>
				</ul>
				
				<div class="sort">
					<?php echo $text_sort; ?>
					<select onchange="location = this.value;" class="custom-select">
						<?php foreach ($sorts as $sorts) { ?>
						<?php if ($sorts['value'] == $sort . '-' . $order) { ?>
						<option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
				<div class="limit">
					<?php echo $text_limit; ?>
					<select onchange="location = this.value;" class="custom-select">
						<?php foreach ($limits as $limits) { ?>
						<?php if ($limits['value'] == $limit) { ?>
						<option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>	
			</div>
			<div class="filter pull-right">
				<div class="product-compare">
					<a href="<?php echo $compare; ?>" id="compare-total-top" class="button bg-color"><?php echo $text_compare; ?></a>
				</div>
			</div>
		</div>

		<!--Product-->
		<div class="product-list"> 				
			<?php
			$cols = $MAX_ITEM_ROW ;
			$span = floor(12/$cols);
			foreach ($products as $i => $product) { $i=$i+1;?>
			<?php if( $i%$cols == 1 ) { ?>
			<div class="row-fluid box-product">
			<?php } ?>
				<div class="span<?php echo $span;?> product-block">					
					<div class="product-inner">
					
						<?php if ($product['thumb']) { ?>
						<div class="image product-top">
							<?php if( $product['special'] ) {   ?>
							<div class="product-label-special label">
								<span><?php echo $this->language->get( 'text_sale' ); ?></span>
							</div>
							<?php } ?>
							
							<a href="<?php echo $product['href']; ?>" class="product-image"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a>
							<?php if( $categoryPzoom ) { $zimage = str_replace( "cache/","", preg_replace("#-\d+x\d+#", "",  $product['thumb'] ));  ?>
							<a href="<?php echo $zimage;?>" class="colorbox product-zoom pavicon-zoom" title="<?php echo $product['name']; ?>"></a>
							<?php } ?>
						</div>
						<?php } ?>						
						
						<div class="product-bottom">	
							<h3 class="name">
								<a class="is-visible is-over" href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
							</h3>

							<p class="description is-over">							
								<?php echo utf8_substr( $product['description'],0, 40 );?>...															
							</p>										

							<?php if ($product['price']) { ?>
							<div class="price is-over">
								<?php if (!$product['special']) { ?>
								<?php echo $product['price']; ?>
								<?php } else { ?>
								<span class="price-old"><?php echo $product['price']; ?></span> 
								<span class="price-new"><?php echo $product['special']; ?></span>
								<?php } ?>
								<?php if ($product['tax']) { ?>																
								<span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
								<?php } ?>
							</div>
							<?php } ?>

							<?php if ($product['rating']) { ?>
							<div class="rating is-over">
								<img src="catalog/view/theme/<?php echo $themeName;?>/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" />
							</div>
							<?php } ?>

							<div class="pav-button clearfix is-over">
								<input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" />								
								<a class="pavicon-compare-default hidden-tablet" onclick="addToCompare('<?php echo $product['product_id']; ?>');"><?php echo $this->language->get("button_compare"); ?></a>					
								<a class="pavicon-wishlist-default hidden-tablet" onclick="addToWishList('<?php echo $product['product_id']; ?>');"><?php echo $this->language->get("button_wishlist"); ?></a>
							</div>
							<div class="img-overlay hiden"></div>
						</div>	
					</div>					
				</div>

			<?php if( $i%$cols == 0 || $i==count($products) ) { ?>
			</div>
			<?php } ?>	

			<?php } ?>
			
		</div>
		
		<!--Pagination-->
		<div class="pagination clearfix">			
			<aside>
				<div class="product-compare pull-right">
					<a href="<?php echo $compare; ?>" id="compare-total-bottom" class="button bg-color"><?php echo $text_compare; ?></a>
				</div>
				<?php echo $pagination; ?>
			</aside>
		</div>			
	</div>	
		
	<?php } else { ?>			
	<div class="content wrapper">
		<?php echo $text_empty; ?>
	</div>	
	<?php } ?>

	
<script type="text/javascript">
<!--
function display(view) {
	if (view == 'list') {
		$('.product-grid').attr('class', 'product-list');
		
		$('.product-list .item').each(function(index, element) {
			html  = '<div class="right">';
			html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
			html += '  <div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
			html += '  <div class="compare">' + $(element).find('.compare').html() + '</div>';
			html += '</div>';			
			
			html += '<div class="left">';
			
			var image = $(element).find('.image').html();
			
			if (image != null) { 
				html += '<div class="image">' + image + '</div>';
			}
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
					
			html += '  <div class="name">' + $(element).find('.name').html() + '</div>';
			html += '  <div class="description">' + $(element).find('.description').html() + '</div>';
			
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
				
			html += '</div>';
						
			$(element).html(html);
		});		
		
		$('.display').html('<li><span><?php echo $text_display; ?></span></li><li><a title="<?php echo $text_grid; ?>" onclick="display(\'grid\');" class="pavicon-grid"><?php echo $text_grid; ?></a></li><li><a title="<?php echo $text_list; ?>" onclick="display(\'list\');" class="active pavicon-list"><?php echo $text_list; ?></a></li>');
		
		$.totalStorage('display', 'list'); 
		
	} else {
	
		$('.product-list').attr('class', 'product-grid');
		
		$('.product-grid .item').each(function(index, element) {
		
			html = '';
			
			var image = $(element).find('.image').html();
			
			if (image != null) {
				html += '<div class="image">' + image + '</div>';
			}
			
			html += '<div class="name">' + $(element).find('.name').html() + '</div>';
			html += '<div class="description">' + $(element).find('.description').html() + '</div>';
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
			
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
						
			html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';
			html += '<div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
			html += '<div class="compare">' + $(element).find('.compare').html() + '</div>';
			
			$(element).html(html);
			
		});	
					
		
		$('.display').html('<li><span><?php echo $text_display; ?></span></li><li><a title="<?php echo $text_grid; ?>" onclick="display(\'grid\');" class="active pavicon-grid"><?php echo $text_grid; ?></a></li><li><a title="<?php echo $text_list; ?>" onclick="display(\'list\');" class="pavicon-list"><?php echo $text_list; ?></a></li>');		
		
		
		$.totalStorage('display', 'grid');
	}
}

view = $.totalStorage('display');

if (view) {
	display(view);
} else {
	display('<?php echo $DISPLAY_MODE;?>');
}

//-->
</script> 


<?php if( $categoryPzoom ) {  ?>
<script type="text/javascript">
<!--
	$(document).ready(function() {
		$('.colorbox').colorbox({
			overlayClose: true,
			opacity: 0.5,
			rel: false,
			onLoad:function(){
				$("#cboxNext").remove(0);
				$("#cboxPrevious").remove(0);
				$("#cboxCurrent").remove(0);
			}
		});
		 
	});
//-->
</script>
<?php } ?>