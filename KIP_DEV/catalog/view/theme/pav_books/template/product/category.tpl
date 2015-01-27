<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>

<!--left -->
<?php if( $SPAN[0] ): ?>
<aside class="span<?php echo $SPAN[0];?>">
	<?php echo $column_left; ?>
</aside>
<?php endif; ?> 

<!--content-->
<section class="span<?php echo $SPAN[1];?>">
	<div id="content" class="category one-page">
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>

		<!--Category Info-->		
		<?php if ($thumb || $description) { ?>	
		<div class="category-info">				
			<?php if ($thumb) { ?>
			<div class="image">
				<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" />
			</div>
			<?php } ?>
			<?php if ($description) { ?>
			<div class="description-category hidden-phone wrapper">
				<?php echo $description; ?>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
	
		<?php echo $content_top; ?>

		<h1><?php echo $heading_title; ?></h1>

		<!--Re Search-->		
		<?php if ($categories) { ?>							
		<div class="category-list wrapper clearfix">
			<h3><?php echo $text_refine; ?></h3>
			<nav class="clearfix box">
				<?php if (count($categories) <= 5) { ?>						
				<ul class="re-search pull-left">
					<?php foreach ($categories as $category) { ?>
					<li>						
						<a href="<?php echo $category['href']; ?>">
							<i class="icon-angle-right">&nbsp;</i>
							<?php echo $category['name']; ?>							
						</a>
					</li>
					<?php } ?>
				</ul>					
				<?php } else { ?>
				<?php for ($i = 0; $i < count($categories);) { ?>
				<ul class="re-search pull-left">
					<?php $j = $i + ceil(count($categories) / 4); ?>
					<?php for (; $i < $j; $i++) { ?>
					<?php if (isset($categories[$i])) { ?>
					<li>						
						<a href="<?php echo $categories[$i]['href']; ?>">
							<i class="icon-angle-right">&nbsp;</i>
							<?php echo $categories[$i]['name']; ?>
						</a>
					</li>
					<?php } ?>
					<?php } ?>
				</ul>
				<?php } ?>
				<?php } ?>	
			</nav>
		</div>
		<?php } ?>  
		
		<!-- Grid / List Product -->
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/product/product_collection.tpl" ); ?>
		
		<div class="buttons m-top wrapper clearfix ">
			<div class="right">
				<a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a>
			</div>
		</div>
		
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