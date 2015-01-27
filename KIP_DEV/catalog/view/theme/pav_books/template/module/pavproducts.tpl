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
<div class="box pav-product-category clearfix no-padding">

	<?php if( !empty($module_description) ) { ?>
	<div class="module-desc">
		<?php echo $module_description;?>
	</div>
	<?php } ?>  

	<div class="box-content">
		<div class="bg-highlight clearfix">
		<div class="tab-nav tabs-category">
			<ul class="h-tabs" id="producttabs<?php echo $id;?>">
				<?php foreach( $tabs as $tab => $category ) { 
					if( empty($category) ){ continue;}
					$tab = 'cattabs';
						
					///	echo '<pre>'.print_r( $category,1 ); die; 
					$products = $category['products'];
					$icon = $this->model_tool_image->resize( $category['image'], 9, 10 ); 
				?>
					<li>
						<a href="#tab-<?php echo $tab.$id.$category['category_id'];?>" data-toggle="tab">
							<img src="<?php echo $icon;?>"/>
							<span><?php echo $category['category_name'];?></span>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>	
	
		<div class="tab-content product-tab-content">  
						<?php $it=0; foreach( $tabs as $tab => $category ) { 
				if( empty($category) ){ continue;}
				$tab = 'boxcats';

				$products = $category['products'];
				$icon = $this->model_tool_image->resize( $category['image'], 15,15 ); 
			?>
			
			<div class="tab-pane cat-products-block <?php echo $category['class'];?> <?php if( $it++ == 0 ){ ?>active<?php } ?> clearfix" id="tab-cattabs<?php echo $id.$category['category_id'];?>">	
			
				<div class="box-products  pavproducts<?php echo $id;?> slide" id="<?php echo $tab.$id.$category['category_id'];?>">
						
					<?php if( count($products) > $itemsperpage ) { ?>
					<div class="carousel-controls">
						<a class="carousel-control left" href="#<?php echo $tab.$id.$category['category_id'];?>"   data-slide="prev">&lsaquo;</a>
						<a class="carousel-control right" href="#<?php echo $tab.$id.$category['category_id'];?>"  data-slide="next">&rsaquo;</a>
					</div>
					<?php } ?>
					
					<div class="carousel-inner">		
						<?php $pages = array_chunk( $products, $itemsperpage);	 ?>	
						<?php foreach ($pages as  $k => $tproducts ) {   ?>
							<div class="item <?php if($k==0) {?>active<?php } ?> wrapper-product-category">
								<?php foreach( $tproducts as $i => $product ) { ?>
								<?php if( $i++%$cols == 0 ) { ?>
								<div class="row-fluid box-product">
								<?php } ?>
										
									<div class="pav-span<?php echo $span;?> product-block pro-content">

										<div class="product-inner">
											
											<?php if ($product['thumb']) { ?>
											<div class="image product-top">
												<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a>
											</div>
											<?php } ?>
											

											<h3 class="name is-over tab-product-name  is-hidden">
												<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
											</h3>

											
											<div class="product-bottom">
												<div class="product-wrapper-bottom">
												
													<h3 class="name is-over tab-product-name">
														<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
													</h3>
												
													<p class="description is-over">
														<?php echo utf8_substr( strip_tags($product['description']),0, 50);?>...
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
												
													<div class="pav-button is-over clearfix">
														<input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" />			
														<a class="pavicon-compare-default hidden-tablet btn-is-enable" onclick="addToCompare('<?php echo $product['product_id']; ?>');"><?php echo $this->language->get("button_compare"); ?></a>					
														<a class="pavicon-wishlist-default hidden-tablet btn-is-enable" onclick="addToWishList('<?php echo $product['product_id']; ?>');"><?php echo $this->language->get("button_wishlist"); ?></a>
													</div>	

												</div>	
											</div>	
											
										</div>
									</div>
									  
								<?php if( $i%$cols == 0 || $i==count($tproducts) ) { ?>
								</div>
								<?php } ?>
								
								<?php } //endforeach; ?>
							</div>
							
						<?php } ?>
						
					</div>  
				</div>
			</div>		
		
			<?php } // endforeach of tabs ?>	
		
		</div>
		</div>
	</div>
</div>


<script type="text/javascript">
$(function () {
	$('.pavproducts<?php echo $id;?>').carousel({interval:99999999999999,auto:false,pause:'hover'});
	$('#producttabs<?php echo $id;?> a:first').tab('show');
});
</script>
