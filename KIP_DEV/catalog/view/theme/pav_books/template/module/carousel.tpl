<?php $this->language->load('module/themecontrol'); ?>

<div id="carousel<?php echo $module; ?>" class="jcarousel box is-highlight">
	<h3 class="box-heading"><span><?php echo $this->language->get('text_brand');?></span></h3>
	<section class="box-content">
		<ul class="jcarousel-skin-opencart">
			<?php foreach ($banners as $banner) { ?>
			<li>
				<a href="<?php echo $banner['link']; ?>">
					<img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" />
				</a>
				<span><?php echo $banner['title']; ?></span>			
			</li>
			<?php } ?>
		</ul>
	</section>
</div>

<script type="text/javascript">
<!--
$('#carousel<?php echo $module; ?> ul').jcarousel({
	vertical: false,
	visible: <?php echo $limit; ?>,
	scroll: <?php echo $scroll; ?>
});
//-->

</script>