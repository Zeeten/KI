<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>
	<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
	<?php endif; ?>
	<section class="span<?php echo $SPAN[1];?>">
		<div id="content" class="home-page one-page">
			<?php echo $content_top; ?>
			<h1 style="display: none;"><?php echo $heading_title; ?></h1>
			<?php echo $content_bottom; ?>
		</div>
	</section>
	<?php if( $SPAN[2] ): ?>
	<aside class="span<?php echo $SPAN[2];?>">	
		<?php echo $column_right; ?>
	</aside>
	<?php endif; ?>
<?php echo $footer; ?>