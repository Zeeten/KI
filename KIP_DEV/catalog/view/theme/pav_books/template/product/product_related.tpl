<?php 
	$themeConfig = $this->config->get('themecontrol');
	$listConfig = array( 
		
		'category_pzoom' => 0
	); 

	$listConfig = array_merge( $listConfig, $themeConfig );	
	$categoryPzoom = $listConfig['category_pzoom'];  
	
?>

<?php if ($products) { ?>	
	<div class="box product-related">	
		<h3 class="box-heading pull-left">
			<span><?php echo $tab_related; ?> (<?php echo count($products); ?>)</span>
		</h3>
		<section class="box-content">		
			<div class="product-grid">	
				<?php $cols = $productConfig['product_related_column']?$productConfig['product_related_column']:3;
				$ispan = floor(12/$cols); ?>
				<?php foreach ($products as $i => $product) { ?>
				<?php if( ($i+1)%$cols == 1 ) {  ?>
				<div class="row-fluid box-product">
				<?php } ?>		
					<div class="span<?php echo $ispan;?> product-block <?php if($i % $cols == 0) { echo "first";} ?>">
						<div class="product-inner">						
							<?php if ($product['thumb']) { ?>
							<div class="image product-top">
								<?php if( $special )  { ?>
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
								<h3 class="name is-over">
									<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
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
					<?php $i=$i+1; if( $i % $cols == 0 || $i == count($products) ) { ?>
				</div>
				<?php } ?>

				<?php } ?>			
			</div>
		</section>
	</div>	
<?php } ?>

