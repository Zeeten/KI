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
	<div id="content" class="download one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
		
		<h1><?php echo $heading_title; ?></h1>
		
		<div class="inner wrapper">
			<?php foreach ($downloads as $download) { ?>
				<div class="download-list">
					<div class="download-id">
						<b><?php echo $text_order; ?></b> 
						<?php echo $download['order_id']; ?>
					</div>
					<div class="download-status">
						<b><?php echo $text_size; ?></b> 
						<?php echo $download['size']; ?>
					</div>
					<div class="download-content">				
						<div class="view-download">
							<p>
								<b><?php echo $text_name; ?></b> 
								<?php echo $download['name']; ?>
							</p>
							<p>
								<b><?php echo $text_date_added; ?></b> 
								<?php echo $download['date_added']; ?>
							</p>
						</div>
						<div class="view-download">
							<p>
								<b><?php echo $text_remaining; ?></b> 
								<?php echo $download['remaining']; ?>
							</p>
						</div>
						<div class="download-info">
							<?php if ($download['remaining'] > 0) { ?>
								<a href="<?php echo $download['href']; ?>">
									<img src="catalog/view/theme/<?php echo $themeName;?>/image/download.png" alt="<?php echo $button_download; ?>" title="<?php echo $button_download; ?>" />
								</a>
							<?php } ?>
						</div>				
					</div>
				</div>
			<?php } ?>
		</div>
  
		<div class="pagination clearfix"><?php echo $pagination; ?></div>
		
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