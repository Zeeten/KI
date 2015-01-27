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
	<div id="content" class="return-list one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
	
		<h1><?php echo $heading_title; ?></h1>
	
		<?php if ($returns) { ?>
			<div class="wrapper">
				<?php foreach ($returns as $return) { ?>
				<div class="return-list">
					<div class="return-id">
						<b><?php echo $text_return_id; ?></b> 
						#<?php echo $return['return_id']; ?>
					</div>
					<div class="return-status">
						<b><?php echo $text_status; ?></b> 
						<?php echo $return['status']; ?>
					</div>
					<div class="return-content">
						<div class="view-content">
							<p>
								<b><?php echo $text_date_added; ?></b> 
								<?php echo $return['date_added']; ?>
							</p>
							<p>
								<b><?php echo $text_order_id; ?></b> 
								<?php echo $return['order_id']; ?>
							</p>
						</div>

						<div class="view-content">
							<p>
								<b><?php echo $text_customer; ?></b> 
								<?php echo $return['name']; ?>
							</p>
						</div>

						<div class="return-info">
							<a href="<?php echo $return['href']; ?>">
								<img src="catalog/view/theme/<?php echo $themeName;?>/image/info.png" alt="<?php echo $button_view; ?>" title="<?php echo $button_view; ?>" />
							</a>
						</div>
					</div>
				</div>
				
				<?php } ?>
				
				<div class="pagination clearfix"><?php echo $pagination; ?></div>
			</div>	
			
		<?php } else { ?>
		<div class="content wrapper"><?php echo $text_empty; ?></div>
		<?php } ?>
			
		<div class="buttons clearfix wrapper">
			<div class="right">
				<a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a>
			</div>
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