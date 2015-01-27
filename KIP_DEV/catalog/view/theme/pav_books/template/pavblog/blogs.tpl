<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?>

<section class="span<?php echo $SPAN[1];?>">
	<div id="content" class="blogs one-page">
		<?php echo $content_top; ?>
		
			<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>

			<div class="pav-header">
				<h1><?php echo $heading_title; ?></h1>
				<a class="rss-wrapper" href="<?php echo $latest_rss;?>"><span class="icon-rss-sign"></span></a>	
			</div>  
	  
			<div class="pav-filter-blogs wrapper">
				<div class="pav-blogs">
					<?php
					$cols = $config->get('cat_columns_leading_blog');
					if( count($leading_blogs) ) { ?>
						<div class="leading-blogs clearfix">
							<?php foreach( $leading_blogs as $key => $blog ) { $key = $key + 1;?>
							<div class="pavcol<?php echo $cols;?> blog-list">
							<?php require( '_item.tpl' ); ?>
							</div>
							<?php if( ( $key%$cols==0 || $cols == count($leading_blogs)) ){ ?>
								
							<?php } ?>
							<?php } ?>
						</div>
					<?php } ?>

					<?php
						$cols = $config->get('cat_columns_secondary_blogs');
						if ( count($secondary_blogs) ) { ?>
						<div class="secondary clearfix">							
							<?php foreach( $secondary_blogs as $key => $blog ) {  $key = $key+1; ?>
							<div class="pavcol<?php echo $cols;?> blog-list">
							<?php require( '_item.tpl' ); ?>
							</div>
							<?php if( ( $key%$cols==0 || $cols == count($leading_blogs)) ){ ?>
								
							<?php } ?>
							<?php } ?>
						</div>
					<?php } ?>						
					<div class="pav-pagination nomargin pagination"><?php echo $pagination;?></div>
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