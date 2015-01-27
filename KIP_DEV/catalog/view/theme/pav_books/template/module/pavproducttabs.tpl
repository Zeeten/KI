<?php 
/******************************************************
 * @package Pav Product Tabs module for Opencart 1.5.x
 * @version 1.0
 * @author http://www.pavothemes.com
 * @copyright	Copyright (C) Feb 2012 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/

$span = 12/$cols; 
$active = 'latest';
$id = rand(1,9)+rand();	
$themeName = basename( dirname(dirname(dirname(__FILE__))) );
?>
<div class="box pav-product-tab">
	<?php if( !empty($module_description) ) { ?>
	<div class="module-desc">
		<?php echo $module_description;?>
	</div>
	<?php } ?>

	<div class="tab-nav">
		<ul class="nav nav-tabs" id="producttabs<?php echo $id;?>">
			<?php foreach( $tabs as $tab => $products ) { if( empty($products) ){ continue;}  ?>
			<li>
				<a href="#tab-<?php echo $tab.$id;?>" data-toggle="tab" class="tab-link"><?php echo $this->language->get('text_'.$tab)?></a>
			</li>
			<?php } ?>
		</ul>
	</div>	

	<div class="tab-content">
		<?php foreach( $tabs as $tab => $products ) { 
			if( empty($products) ){ continue;}
			?>
			<div class="tab-pane box-products tabcarousel<?php echo $id; ?> slide" id="tab-<?php echo $tab.$id;?>">				
				<?php if( count($products) > $itemsperpage ) { ?>
				<div class="carousel-controls">
					<a class="carousel-control left" href="#tab-<?php echo $tab.$id;?>"   data-slide="prev">&lsaquo;</a>
					<a class="carousel-control right" href="#tab-<?php echo $tab.$id;?>"  data-slide="next">&rsaquo;</a>
				</div>
				<?php } ?>
				
				<div class="carousel-inner">					
					<?php 
					$pages = array_chunk( $products, $itemsperpage);
					//	echo '<pre>'.print_r( $pages, 1 ); die;
					?>	
					<?php foreach ($pages as  $k => $tproducts ) {   ?>
					<div class="item <?php if($k==0) {?>active<?php } ?>">
						<div class="product-grid">
							<?php foreach( $tproducts as $i => $product ) {  $i=$i+1;?>
							<?php if( $i%$cols == 1 ) { ?>
							<div class="row-fluid box-product">
								<?php } ?>
								<div class="span<?php echo $span;?> product-block">
									<div class="product-inner">
										<?php if ($product['thumb']) { ?>
											<div class="image product-top">
												<?php if ($product['special']) { ?>
												<div class="product-label-special label">
													<span><?php echo $this->language->get( 'text_sale' ); ?></span>
												</div>
												<?php } ?>										
												<a class="product-image" href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a>
											</div>
										<?php } ?>

										<div class="product-bottom">
											<h3 class="name is-over"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h3>

											<p class="description is-over">
												<?php echo utf8_substr( strip_tags($product['description']),0,40);?>...
											</p>

											<?php if ($product['price']) { ?>
											<div class="price is-over">
												<?php if (!$product['special']) { ?>
												<?php echo $product['price']; ?>
												<?php } else { ?>
												<span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
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

								<?php if( $i%$cols == 0 || $i==count($tproducts) ) { ?>
							</div>
							<?php } ?>
							<?php } //endforeach; ?>
						</div>
					</div>
						
					<?php } ?>					
					
				</div>  
			</div>
			<?php } // endforeach of tabs ?>	
		</div>
	</div>


	<script>
	$(function () {
		$('#producttabs<?php echo $id;?> a:first').tab('show');
	})
	$('.tabcarousel<?php echo $id;?>').carousel({interval:false,auto:false,pause:'hover'});
	</script>
