<div class="box pav-information">
	<h3 class="box-heading">
		<span><?php echo $heading_title; ?></span>
	</h3>
  
	<nav class="box-content">
		<ul>
			<?php foreach ($informations as $information) { ?>
			<li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
			<?php } ?>
			<li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
			<li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
		</ul>
	</nav>
</div>
