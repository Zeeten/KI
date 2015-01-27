<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>

<!--Left-->
<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?>

<!--Content-->
<section class="span<?php echo $SPAN[1];?>">

<div id="content" class="blog-category one-page">
	<?php echo $content_top; ?>
	<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>	
  
	<div class="pav-header">
		<h1><?php echo $heading_title; ?></h1>
		<a class="rss-wrapper" href="<?php echo $latest_rss;?>"><span class="icon-rss-sign"></span></a>	
	</div>  
	
	<div class="pav-category">
		<?php if( !empty($children) ) { ?>
		<div class="pav-children clearfix">
			<h3><?php echo $this->language->get('text_children');?></h3>
			<div class="children-wrap">				
				<?php 
				$cols = (int)$config->get('children_columns');
				foreach( $children as $key => $sub )  { $key = $key + 1;?>
					<div class="column">						
						<div class="children-inner wrapper">
							<div class="row-fluid">
								<div class="span4">
									<?php if( $sub['thumb'] ) { ?>
										<div class="image blog-image">
											<img src="<?php echo $sub['thumb'];?>" alt="" />
										</div>
									<?php } ?>
								</div>
								
								<div class="span8">
									<h4>
										<a href="<?php echo $sub['link']; ?>" title="<?php echo $sub['title']; ?>"><?php echo $sub['title']; ?> (<?php echo $sub['count_blogs']; ?>)</a> 
									</h4>
									<div class="sub-description">
										<?php echo $sub['description']; ?>
									</div>
								</div>
							</div>
						</div>					
					</div>
					<?php if( ( $key%$cols==0 || $cols == count($leading_blogs)) ){ ?>
						<!-- <div class="clearfix"></div> -->
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		<div class="pav-blogs">
			<?php
			$cols = $config->get('cat_columns_leading_blog');
			if( count($leading_blogs) ) { ?>
				<div class="leading-blogs clearfix">
					<?php foreach( $leading_blogs as $key => $blog ) { $key = $key + 1;?>
					<div class="pavcol<?php echo $cols;?>">
					<?php require( '_item.tpl' ); ?>
					</div>
					<?php if( ( $key%$cols==0 || $cols == count($leading_blogs)) ){ ?>
						<div class="clearfix"></div>
					<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>

			<?php
				$cols = $config->get('cat_columns_secondary_blogs');
				if ( count($secondary_blogs) ) { ?>
				<div class="secondary clearfix">
					
					<?php foreach( $secondary_blogs as $key => $blog ) {  $key = $key+1; ?>
					<div class="pavcol<?php echo $cols;?>">
					<?php require( '_item.tpl' ); ?>
					</div>
					<?php if( ( $key%$cols==0 || $cols == count($leading_blogs)) ){ ?>
						<div class="clearfix"></div>
					<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>
			<?php if( $total ) { ?>	
			<div class="pav-pagination nomargin pagination"><?php echo $pagination;?></div>
			<?php } ?>
		</div>
  </div>

 <?php echo $content_bottom; ?></div>
 
 </section>
 
 <!--Right-->
<?php if( $SPAN[2] ): ?>
<aside class="span<?php echo $SPAN[2];?>">	
	<?php echo $column_right; ?>
</aside>
<?php endif; ?>
 
<?php echo $footer; ?>