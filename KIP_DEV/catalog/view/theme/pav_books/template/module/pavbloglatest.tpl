<div class="box pav-blog-latest">
	<h3 class="box-heading">
		<span><?php echo $heading_title; ?></span>
	</h3>
	<section class="box-content">
		<?php if( !empty($blogs) ) { ?>
		<div class="pavblog-latest clearfix">				
			<?php foreach( $blogs as $key => $blog ) { $key = $key + 1;?>
				<div class="media-blog">
					<div class="media">
						<?php if( $blog['thumb']  )  { ?>
							<a href="<?php echo $blog['link'];?>" class="pull-left">
								<img src="<?php echo $blog['thumb'];?>" title="<?php echo $blog['title'];?>" alt="" />
							</a>											
						<?php } ?>					
						<div class="media-body">
							<div class="description">
								<?php echo utf8_substr( $blog['description'],0, 30 );?>...
							</div>							
							<h4 class="blog-title is-hide">
								<a href="<?php echo $blog['link'];?>" title="<?php echo $blog['title'];?>"><?php echo $blog['title'];?></a>
							</h4>
							<div class="view-more is-hide">
								<a class="button" href="<?php echo $blog['link'];?>"><?php echo $this->language->get('text_readmore');?></a>
							</div>
						</div>	
					</div>	
<?php if( ( $key%$cols==0 || $key == count($blogs)) ){  ?>
				<div class="clearfix"></div>
			<?php } ?>
				</div>				
			<?php } ?>									
		</div>
		<?php } ?>
	</section>
</div>