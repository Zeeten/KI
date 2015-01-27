<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?> 

<section class="span<?php echo $SPAN[1];?>">
	<div id="content" class="tracking one-page">
	
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
				
		<h1><?php echo $heading_title; ?></h1>
		
		<div class="inner">
			<p><?php echo $text_description; ?></p>
			<p><?php echo $text_code; ?></p>
			<div class="wrapper">
				<p>				
					<textarea cols="40" rows="5"><?php echo $code; ?></textarea>
				</p>
				<p>
					<?php echo $text_generator; ?>
				</p>
				<p>				
					<input type="text" name="product" value="" />
				</p>
				<p>
					<?php echo $text_link; ?>
				</p>
				<p>				
					<textarea name="link" cols="40" rows="5"></textarea>
				</p>
			</div>
		</div>	
		
		<div class="buttons clearfix wrapper">
			<div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
		</div>
		<?php echo $content_bottom; ?>
	</div>	

	<script type="text/javascript">
	<!--
		$('input[name=\'product\']').autocomplete({
			delay: 500,
			source: function(request, response) {
				$.ajax({
					url: 'index.php?route=affiliate/tracking/autocomplete&filter_name=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {		
						response($.map(json, function(item) {
							return {
								label: item.name,
								value: item.link
							}
						}));
					}
				});
			},
			select: function(event, ui) {
				$('input[name=\'product\']').attr('value', ui.item.label);
				$('textarea[name=\'link\']').attr('value', ui.item.value);
								
				return false;
			},
			focus: function(event, ui) {
			  return false;
		   }
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