<?php $themeName = basename( dirname(dirname(dirname(__FILE__))) ); ?>
<div class="breadcrumb no-box">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	<?php } ?>
 
	<script type="text/javascript">
		$(function(){			
			$('.breadcrumb a').last().addClass('last');			
		});	
	</script>
</div>