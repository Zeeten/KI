<?php 
	require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); 
	$themeName = basename( dirname(dirname(dirname(__FILE__))) );
?>
<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?> 


<section class="span<?php echo $SPAN[1];?>">
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?><img src="catalog/view/theme/<?php echo $themeName;?>/image/close.png" alt="" class="close" /></div>
	<?php } ?>
	
	<div id="content" class="wishlist one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
		
		<h1><?php echo $heading_title; ?></h1>
		
		<?php if ($products) { ?>
		<div class="wishlist-info">
			<div class="margin">
				<table class="table">
					<thead class="hidden-phone">
						<tr>
							<td class="image"><?php echo $column_image; ?></td>
							<td class="name"><?php echo $column_name; ?></td>
							<td class="model"><?php echo $column_model; ?></td>
							<td class="stock"><?php echo $column_stock; ?></td>
							<td class="price"><?php echo $column_price; ?></td>
							<td class="action"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<?php foreach ($products as $product) { ?>
					<tbody id="wishlist-row<?php echo $product['product_id']; ?>">
						<tr>
							<td class="image">
								<?php if ($product['thumb']) { ?>
								<a href="<?php echo $product['href']; ?>">
									<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
								</a>
								<?php } ?>					  
							</td>
							<td class="name">
								<span class="phone hidden-desktop hidden-tablet"><?php echo $column_name; ?></span>
								<a href="<?php echo $product['href']; ?>" class="avg-right"><?php echo $product['name']; ?></a>
							</td>
							<td class="model">
								<span class="phone hidden-desktop hidden-tablet"><?php echo $column_model; ?></span>
								<span class="avg-right"><?php echo $product['model']; ?></span>
							</td>
							<td class="stock">
								<span class="phone hidden-desktop hidden-tablet"><?php echo $column_stock; ?></span>
								<span class="avg-right"><?php echo $product['stock']; ?></span>
							</td>
							<td class="price">
								<span class="phone hidden-desktop hidden-tablet"><?php echo $column_price; ?></span>
								<?php if ($product['price']) { ?>
								<div class="price avg-right">
									<?php if (!$product['special']) { ?>
									<?php echo $product['price']; ?>
									<?php } else { ?>
									<s><?php echo $product['price']; ?></s> <b><?php echo $product['special']; ?></b>
									<?php } ?>
								</div>
								<?php } ?>
							</td>
							<td class="action">
								<span class="phone hidden-desktop hidden-tablet"><?php echo $column_action; ?></span>
								<div class="avg-right">
									<img src="catalog/view/theme/<?php echo $themeName;?>/image/cart-add.png" alt="<?php echo $button_cart; ?>" title="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" />
									&nbsp;&nbsp;
									<a href="<?php echo $product['remove']; ?>">
										<img src="catalog/view/theme/<?php echo $themeName;?>/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" />
									</a>
								</div>	
							</td>
						</tr>
					</tbody>
					<?php } ?>
				</table>
			</div>
		</div>
		
		<div class="buttons clearfix wrapper">
			<div class="right">
				<a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a>
			</div>
		</div>
		<?php } else { ?>
		
		<div class="content wrapper"><?php echo $text_empty; ?></div>
		
		<div class="buttons clearfix wrapper">
			<div class="right">
				<a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a>
			</div>
		</div>
		
		<?php } ?>
		
		<?php echo $content_bottom; ?>
	</div>
</section>

<?php if( $SPAN[2] ): ?>
	<aside class="span<?php echo $SPAN[2];?>">	
		<?php echo $column_right; ?>
	</aside>
<?php endif; ?>

<?php echo $footer; ?>