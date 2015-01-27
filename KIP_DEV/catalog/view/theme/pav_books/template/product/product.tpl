<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" );

  $themeConfig = $this->config->get('themecontrol');
  
  $productConfig = array(
      'product_enablezoom'=>1,
      'product_zoommode'  => 'basic',
      'product_zoomeasing' => 1,
      'product_zoomlensshape' => "round",
      'product_zoomlenssize' => "150",
      'product_zoomgallery'  => 0,
      'extra_images_visiable' => 3,
      'product_related_column'=>'', 
  );
  $productConfig = array_merge( $productConfig, $themeConfig );
  
?>

<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?> 

<section class="span<?php echo $SPAN[1];?>">
	<div id="content" class="product-detail one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
		
		<div class="product-info">
			<div class="row-fluid">
				<?php if ($thumb || $images) { ?>
				<div class="span5 image-container">
					
			        <?php if ($thumb) { ?>
			        <div class="image">					
			        	<?php if( $special )  { ?>
			          		<span class="product-label-special label"><?php echo $this->language->get( 'text_sale' ); ?></span>
			        	<?php } ?>
						
						<a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="colorbox">
							<img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image"  data-zoom-image="<?php echo $popup; ?>" class="product-image-zoom"/>
						</a>
					</div>
					<?php } ?>

					  <?php if ($images) { ?>
				        <div class="image-additional slide carousel" id="image-additional">
							<div class="carousel-inner">
								<?php 
								
								if( $productConfig['product_zoomgallery'] == 'slider' && $thumb ) {  
									$eimages = array( 0=> array( 'popup'=>$popup,'thumb'=> $thumb )  ); 
									$images = array_merge( $eimages, $images );
								}
								
								$icols = 3; $i= 0;
								foreach ($images as  $image) { ?>
									<?php if( (++$i)%$icols == 1 ) { ?>
									<div class="item clearfix">
									<?php } ?>
									<a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="colorbox hidden-tablet" data-zoom-image="<?php echo $image['popup']; ?>" data-image="<?php echo $image['popup']; ?>">
										<img src="<?php echo $image['thumb']; ?>" style="max-width:<?php echo $this->config->get('config_image_additional_width');?>px"  title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" data-zoom-image="<?php echo $image['popup']; ?>" class="product-image-zoom" />
									</a>
									<?php if( $i%$icols == 0 || $i==count($images) ) { ?>
									</div>
									<?php } ?>								  
								<?php } ?>
							</div>
				         	<div class="carousel-control pavicon-prev left hidden-tablet" href="#image-additional" data-slide="prev">&lsaquo;</div>
							<div class="carousel-control pavicon-next right hidden-tablet" href="#image-additional" data-slide="next">&rsaquo;</div>
				        </div>
						<script type="text/javascript">
						$('#image-additional .item:first').addClass('active');
						$('#image-additional').carousel({interval:false})
						</script>
				        <?php } ?>
				</div>
				<?php } ?>

				<div class="span7 content-info">
				
					<h1 class="product-name normal"><?php echo $heading_title; ?></h1>
					
					<div class="product-tool">
					<?php if ($review_status) { ?>
					<div class="review clearfix">
						<div class="rating">
							<img src="catalog/view/theme/<?php echo $themeName;?>/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $reviews; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $text_write; ?></a>
						</div>
						
					</div>
					<div class="share clearfix">
							<!-- AddThis Button BEGIN -->
							<div class="addthis_default_style">
								<a class="addthis_button_compact"><?php echo $text_share; ?></a> <a class="addthis_button_email"></a><a class="addthis_button_print"></a> <a class="addthis_button_facebook"></a> <a class="addthis_button_twitter"></a>
							</div>
							<script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js"></script> 
							<!-- AddThis Button END --> 
						</div>
					<?php } ?>					
					</div>
					<ul class="description">
						<li>
							<?php if ($manufacturer) { ?>
							<span><?php echo $text_manufacturer; ?></span>
							<a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a>
							<?php } ?>
						</li>
						<li>
							<span><?php echo $text_model; ?></span> <?php echo $model; ?>
						</li>
						<li>
							<?php if ($reward) { ?>
							<span><?php echo $text_reward; ?></span> <?php echo $reward; ?>
							<?php } ?>
						</li>
						<li>
							<span><?php echo $text_stock; ?></span> <?php echo $stock; ?>
						</li>
					</ul>

					<?php if ($options) { ?>
					<div class="options">
						<h2><?php echo $text_option; ?></h2>						
						<?php foreach ($options as $option) { ?>
						<?php if ($option['type'] == 'select') { ?>
						<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							<p>
								<?php if ($option['required']) { ?>
								<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b>
							</p>
							<p>
								<select name="option[<?php echo $option['product_option_id']; ?>]" class="span8">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach ($option['option_value'] as $option_value) { ?>
									<option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
										<?php if ($option_value['price']) { ?>
										(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
										<?php } ?>
									</option>
									<?php } ?>
								</select>
							</p>
						</div>						
						<?php } ?>


						<?php if ($option['type'] == 'radio') { ?>
						<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							<p>
								<?php if ($option['required']) { ?>
								<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b>
							</p>
							
							<?php foreach ($option['option_value'] as $option_value) { ?>
							<p>
								<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
								<label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
									<?php if ($option_value['price']) { ?>
									(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
									<?php } ?>
								</label>	
							</p>
							<?php } ?>
							
						</div>						
						<?php } ?>


						<?php if ($option['type'] == 'checkbox') { ?>
						<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							<p>
								<?php if ($option['required']) { ?>
								<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b>
							</p>

							
							<?php foreach ($option['option_value'] as $option_value) { ?>
							<p>
								<input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
								<label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
									<?php if ($option_value['price']) { ?>
									(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
									<?php } ?>
								</label>
							</p>
							<?php } ?>
						</div>						
						<?php } ?>


						<?php if ($option['type'] == 'image') { ?>
						<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							<p>
								<?php if ($option['required']) { ?>
								<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b>
							</p>
							<table class="option-image table">
								<?php foreach ($option['option_value'] as $option_value) { ?>
								<tr>
									<td style="width: 1px;">
										<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
									</td>
									<td>
										<label for="option-value-<?php echo $option_value['product_option_value_id']; ?>">
											<img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" />
										</label>
									</td>
									<td>
										<label for="option-value-<?php echo $option_value['product_option_value_id']; ?>">
											<?php echo $option_value['name']; ?>
											<?php if ($option_value['price']) { ?>
											(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
											<?php } ?>
										</label>
									</td>
								</tr>
								<?php } ?>
							</table>
						</div>
						<?php } ?>
						<?php if ($option['type'] == 'text') { ?>
						<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							<p>
								<?php if ($option['required']) { ?>
								<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b>
							</p>
							<p>
								<input type="text" class="span12" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
							</p>							
						</div>						
						<?php } ?>

						<?php if ($option['type'] == 'textarea') { ?>
						<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							<p>
								<?php if ($option['required']) { ?>
								<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b>
							</p>
							<p>
								<textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5" class="span12"><?php echo $option['option_value']; ?></textarea>
							</p>							
						</div>						
						<?php } ?>


						<?php if ($option['type'] == 'file') { ?>
						<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							<p>
								<?php if ($option['required']) { ?>
								<span class="required">*</span>
								<?php } ?>							
								<b><?php echo $option['name']; ?>:</b>
							</p>
							
							<p>
								<input type="button" value="<?php echo $button_upload; ?>" id="button-option-<?php echo $option['product_option_id']; ?>" class="button">
								<input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
							</p>
						</div>						
						<?php } ?>

						<?php if ($option['type'] == 'date') { ?>
						<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							<p>
								<?php if ($option['required']) { ?>
								<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b>
							</p>
							<p>
								<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date span7" />
							</p>							
						</div>						
						<?php } ?>

						<?php if ($option['type'] == 'datetime') { ?>
						<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							<p>
								<?php if ($option['required']) { ?>
								<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b>
							</p>
							<p>
								<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime span12" />
							</p>	
						</div>
						<?php } ?>

						<?php if ($option['type'] == 'time') { ?>
						<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							<p>
								<?php if ($option['required']) { ?>
								<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b>
							</p>
							<p>
								<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time span12" />
							</p>	
						</div>
						<?php } ?>
						<?php } ?>
					</div>
					<?php } ?>
					
					
					<div class="wrap_top clearfix">
						<a onclick="addToWishList('<?php echo $product_id; ?>');" class="pavicon-wishlist-default hidden-tablet is-visible"><?php echo $button_wishlist; ?></a>
						<a onclick="addToCompare('<?php echo $product_id; ?>');" class="pavicon-compare-default hidden-tablet is-visible"><?php echo $button_compare; ?></a>
					</div>
					

					<div class="wrap_bottom clearfix">
						<?php if ($price) { ?>
						<div class="price pull-left">
							<ul class="pav-price">
								<li>
									<span class="price-label is-hiden"><?php echo $text_price; ?></span>	
									<?php if (!$special) { ?>
									<?php echo $price; ?>
									<?php } else { ?>										
									<span class="price-new"><?php echo $special; ?></span>
									<span class="price-old"><?php echo $price; ?></span>
									<?php } ?>
								</li>
								<li>
									<?php if ($tax) { ?>									
									<span class="price-tax"><?php echo $text_tax; ?> <?php echo $tax; ?></span>																
									<?php } ?>
								</li>
								<li>
									<?php if ($points) { ?>
									<span class="reward"><small><?php echo $text_points; ?> <?php echo $points; ?></small></span>
									<?php } ?>
								</li>
								<li>
									<?php if ($discounts) { ?>							
									<span class="discount">
										<?php foreach ($discounts as $discount) { ?>
										<?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?>																
										<?php } ?>
									</span>
									<?php } ?>
								</li>
							</ul>
						</div>
						<?php } ?>




						<?php if ($profiles): ?>
						<div class="option">
							<h3><span class="required">*</span><?php echo $text_payment_profile ?></h3>
							<select name="profile_id">
								<option value=""><?php echo $text_select; ?></option>
								<?php foreach ($profiles as $profile): ?>
								<option value="<?php echo $profile['profile_id'] ?>"><?php echo $profile['name'] ?></option>
								<?php endforeach; ?>
							</select>												
							<span id="profile-description"></span>
						</div>
						<?php endif; ?>



						
						<div class="cart pull-right">
							<div class="quantity-adder pull-left no-border">			
								<div class="input-quantity pull-left">
									<span><?php echo $text_qty; ?></span>						
									<input class="quantity" type="text" name="quantity" size="1" value="<?php echo $minimum; ?>" />	
								</div>							
								<div class="buttons-adjust-qty pull-left">
									<div class="add-up add-action">
										<span class="icon-plus"></span>
									</div>
									<div class="add-down add-action">
										<span class="icon-minus"></span>
									</div>	
								</div>
							</div>
							<input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
							<input type="button" value="<?php echo $button_cart; ?>" id="button-cart" class="button" />
						</div>
					</div>
					<?php if ($minimum > 1) { ?>
					<p class="minimum"><small><?php echo $text_minimum; ?></small></p>
					<?php } ?>
				</div>
			</div>
		</div>
		
		<div class="view-tabs box">
			<div id="tabs" class="htabs clearfix">
				<ul id="tab" class="nav-tabs">
					<li>
						<a class="tab-link" href="#tab-description"><?php echo $tab_description; ?></a>
					</li>
					
					<?php if ($attribute_groups) { ?>
					<li>	
						<a class="tab-link" href="#tab-attribute"><?php echo $tab_attribute; ?></a>
					</li>	
					<?php } ?>
					
					<?php if ($review_status) { ?>
					<li>
						<a class="tab-link" href="#tab-review"><?php echo $tab_review; ?></a>
					</li>
					<?php } ?>	
				</ul>				
			</div>
			
			<div id="tab-description" class="wrapper">
				<?php echo $description; ?>
			</div>
			
			<?php if ($attribute_groups) { ?>
			<div id="tab-attribute" class="wrapper">
				<table class="attribute table">
					<?php foreach ($attribute_groups as $attribute_group) { ?>
					<thead class="hidden-phone">
						<tr>
							<td colspan="2"><?php echo $attribute_group['name']; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
						<tr>
							<td><?php echo $attribute['name']; ?></td>
							<td><?php echo $attribute['text']; ?></td>
						</tr>
						<?php } ?>
					</tbody>
					<?php } ?>
				</table>
			</div>
			<?php } ?>
			
			<?php if ($review_status) { ?>
			<div id="tab-review" class="wrapper">
				<div id="review"></div>
				<h3 id="review-title"><?php echo $text_write; ?></h3>
				<p>
					<b><?php echo $entry_name; ?></b>
				</p>
				<p>
					<input type="text" name="name" value="" class="span5" />
				</p>
				<p>
					<b><?php echo $entry_review; ?></b>
				</p>
				<p>
					<textarea name="text" cols="40" rows="8" class="span12"></textarea>
				</p>
				<p>
					<span style="font-size: 11px;"><?php echo $text_note; ?></span>
				</p>
				<p>
					<b><?php echo $entry_rating; ?></b> 
					<span><?php echo $entry_bad; ?></span>					
					<input type="radio" name="rating" value="1" />					
					<input type="radio" name="rating" value="2" />					
					<input type="radio" name="rating" value="3" />					
					<input type="radio" name="rating" value="4" />					
					<input type="radio" name="rating" value="5" />					
					<span><?php echo $entry_good; ?></span>
				</p>
				<p><b><?php echo $entry_captcha; ?></b></p>
				<p><input type="text" name="captcha" value="" class="span5" /></p>
				<p><img src="index.php?route=product/product/captcha" alt="" id="captcha" /></p>
				<div class="buttons clearfix wrapper">
					<div class="right"><a id="button-review" class="button"><?php echo $button_continue; ?></a></div>
				</div>	
			</div>		
			<?php } ?>	
			
		</div>
	</div>	    

	<?php if ($tags) { ?>
	<div class="tags">
		<b><?php echo $text_tags; ?></b>
		<?php for ($i = 0; $i < count($tags); $i++) { ?>
		<?php if ($i < (count($tags) - 1)) { ?>
		<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
		<?php } else { ?>
		<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
		<?php } ?>
		<?php } ?>
	</div>
	<?php } ?>
	
	
	<!-- Product Related -->
	
	<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/product/product_related.tpl" ); ?>	

	<!-- End -->

	<?php echo $content_bottom; ?>		
	
	<?php if( $productConfig['product_enablezoom'] ) { ?>
	<script type="text/javascript" src=" catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/javascript/elevatezoom/elevatezoom-min.js"></script>
	<script type="text/javascript">
		<?php if( $productConfig['product_zoomgallery'] == 'slider' ) {  ?>
		$("#image").elevateZoom({gallery:'image-additional', cursor: 'pointer', galleryActiveClass: 'active'}); 
		<?php } else { ?>
		var zoomCollection = '<?php echo $productConfig["product_zoomgallery"]=="basic"?".product-image-zoom":"#image";?>';
		$( zoomCollection ).elevateZoom({
		<?php if( $productConfig['product_zoommode'] != 'basic' ) { ?>
		zoomType        : "<?php echo $productConfig['product_zoommode'];?>",
		<?php } ?>
		lensShape : "<?php echo $productConfig['product_zoomlensshape'];?>",
		lensSize    : <?php echo (int)$productConfig['product_zoomlenssize'];?>,

		});
		<?php } ?> 
	</script>
	<?php } ?>


	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('.colorbox').colorbox({
			overlayClose: true,
			opacity: 0.5,
			rel: "colorbox"
		});
	});
	//-->
	</script> 

	<script type="text/javascript">
		<!--

		$('select[name="profile_id"], input[name="quantity"]').change(function(){
			$.ajax({
				url: 'index.php?route=product/product/getRecurringDescription',
				type: 'post',
				data: $('input[name="product_id"], input[name="quantity"], select[name="profile_id"]'),
				dataType: 'json',
			    beforeSend: function() {
			        $('#profile-description').html('');
			    },
				success: function(json) {
					$('.success, .warning, .attention, information, .error').remove();
			        
					if (json['success']) {
			            $('#profile-description').html(json['success']);
					}	
				}
			});
		});



		$('#button-cart').bind('click', function() {
			$.ajax({
				url: 'index.php?route=checkout/cart/add',
				type: 'post',
				data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
				dataType: 'json',
				success: function(json) {
					$('.success, .warning, .attention, information, .error').remove();
					
					if (json['error']) {
						if (json['error']['option']) {
							for (i in json['error']['option']) {
								$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
							}
						}
		                
		                if (json['error']['profile']) {
		                    $('select[name="profile_id"]').after('<span class="error">' + json['error']['profile'] + '</span>');
		                }
					} 
					
					if (json['success']) {
						$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/<?php echo $themeName;?>/image/close.png" alt="" class="close" /></div>');
							
						$('.success').fadeIn('slow');
							
						$('#cart-total').html(json['total']);
						
						$('html, body').animate({ scrollTop: 0 }, 'slow'); 
					}	
				}
			});
		});

		//-->
	</script>

	<?php if ($options) { ?>

	<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>

		<?php foreach ($options as $option) { ?>
		<?php if ($option['type'] == 'file') { ?>

		<script type="text/javascript">
		<!--
		new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {
			action: 'index.php?route=product/product/upload',
			name: 'file',
			autoSubmit: true,
			responseType: 'json',
			onSubmit: function(file, extension) {
				$('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/<?php echo $themeName;?>/image/loading.gif" class="loading" style="padding-left: 5px;" />');
				$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', true);
			},
			onComplete: function(file, json) {
				$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', false);

				$('.error').remove();

				if (json['success']) {
					alert(json['success']);

					$('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').attr('value', json['file']);
				}

				if (json['error']) {
					$('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json['error'] + '</span>');
				}

				$('.loading').remove();	
			}
		});
	//-->
	</script>
	<?php } ?>
	<?php } ?>
	<?php } ?>

	<script type="text/javascript">
	<!--
	$('#review .pagination a').live('click', function() {
		$('#review').fadeOut('slow');

		$('#review').load(this.href);
		
		$('#review').fadeIn('slow');
		
		return false;
	});			

	$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

	$('#button-review').bind('click', function() {
		$.ajax({
			url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
			type: 'post',
			dataType: 'json',
			data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
			beforeSend: function() {
				$('.success, .warning').remove();
				$('#button-review').attr('disabled', true);
				$('#review-title').after('<div class="attention"><img src="catalog/view/theme/<?php echo $themeName;?>/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
			complete: function() {
				$('#button-review').attr('disabled', false);
				$('.attention').remove();
			},
			success: function(data) {
				if (data['error']) {
					$('#review-title').after('<div class="warning">' + data['error'] + '</div>');
				}
				
				if (data['success']) {
					$('#review-title').after('<div class="success">' + data['success'] + '</div>');

					$('input[name=\'name\']').val('');
					$('textarea[name=\'text\']').val('');
					$('input[name=\'rating\']:checked').attr('checked', '');
					$('input[name=\'captcha\']').val('');
				}
			}
		});
});
	//-->
	</script> 


	<script type="text/javascript">
	<!--
	$('#tabs a').tabs();
	//-->
	</script> 

	<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 

	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		if ($.browser.msie && $.browser.version == 6) {
			$('.date, .datetime, .time').bgIframe();
		}

		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		$('.datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:m'
		});
		$('.time').timepicker({timeFormat: 'h:m'});
	});
	//-->
	</script> 

</section> 

<?php if( $SPAN[2] ): ?>
	<aside class="span<?php echo $SPAN[2];?>">	
		<?php echo $column_right; ?>
	</aside>
<?php endif; ?>

<?php echo $footer; ?>