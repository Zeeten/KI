<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>

<!-- Left -->
<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?> 

<!--content-->
<section class="span<?php echo $SPAN[1];?>">
	<div id="content" class="special one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?> 

		<h1><?php echo $heading_title; ?></h1>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/product/product_collection.tpl" ); ?>

		<?php echo $content_bottom; ?>
	</div>
</section> 

<!--right-->
<?php if( $SPAN[2] ): ?>
<aside class="span<?php echo $SPAN[2];?>">	
	<?php echo $column_right; ?>
</aside>
<?php endif; ?>

<?php echo $footer; ?>