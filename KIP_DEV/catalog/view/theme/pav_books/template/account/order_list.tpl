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
	<div id="content" class="order-list one-page">
		<?php echo $content_top; ?>
    
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
    
		<h1><?php echo $heading_title; ?></h1>
		
		<?php if ($orders) { ?>
			<?php foreach ($orders as $order) { ?>
			<div class="order-list wrapper padding">
				<div class="order-id">
					<b><?php echo $text_order_id; ?></b> 
					#<?php echo $order['order_id']; ?>
				</div>
				<div class="order-status">
					<b><?php echo $text_status; ?></b> 
					<?php echo $order['status']; ?>
				</div>
				<div class="order-content">
					<div class="view-order">
						<p>
							<b><?php echo $text_date_added; ?></b> 
							<?php echo $order['date_added']; ?>
						</p>
						<p>
							<b><?php echo $text_products; ?></b> <?php echo $order['products']; ?>
						</p>
					</div>
					
					<div class="view-order">
						<p>
							<b><?php echo $text_customer; ?></b> 
							<?php echo $order['name']; ?>
						</p>
						<p>
							<b><?php echo $text_total; ?></b> 
							<?php echo $order['total']; ?>
						</p>          
					</div>

					<div class="order-info view-order">
						<a href="<?php echo $order['href']; ?>">
							<img src="catalog/view/theme/<?php echo $themeName;?>/image/info.png" alt="<?php echo $button_view; ?>" title="<?php echo $button_view; ?>" />
						</a>
						<a href="<?php echo $order['reorder']; ?>">
							<img src="catalog/view/theme/<?php echo $themeName;?>/image/reorder.png" alt="<?php echo $button_reorder; ?>" title="<?php echo $button_reorder; ?>" />
						</a>
					</div>
				</div>
			</div>
			<?php } ?>
		
		<div class="pagination clearfix"><?php echo $pagination; ?></div>
		<?php } else { ?>
		<div class="content wrapper"><?php echo $text_empty; ?></div>
		<?php } ?>
			
		<div class="buttons clearfix wrapper">
			<div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
		</div>
		
		<?php echo $content_bottom; ?>
		
	</div>
		
</section> 
	  
<?php if( $SPAN[2] ): ?>
	<aside class="span<?php echo $SPAN[2];?>">	
		<?php echo $column_right; ?>
	</aside>
<?php endif; ?>
   
<?php echo $footer; ?>