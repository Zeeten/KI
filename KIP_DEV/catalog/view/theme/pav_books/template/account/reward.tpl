<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?> 

<section class="span<?php echo $SPAN[1];?>">
	<div id="content" class="reward one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
			
		<h1><?php echo $heading_title; ?></h1>
		
		<div class="inner wrapper">
			<p><?php echo $text_total; ?>
				<b><?php echo $total; ?></b>
			</p>
			<table class="list table">
				<thead class="hidden-phone">
					<tr>
						<td class="left"><?php echo $column_date_added; ?></td>
						<td class="left"><?php echo $column_description; ?></td>
						<td class="right"><?php echo $column_points; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($rewards) { ?>
					<?php foreach ($rewards  as $reward) { ?>
					<tr>
						<td class="left"><?php echo $reward['date_added']; ?></td>
						<td class="left"><?php if ($reward['order_id']) { ?>
						<a href="<?php echo $reward['href']; ?>"><?php echo $reward['description']; ?></a>
						<?php } else { ?>
						<?php echo $reward['description']; ?>
						<?php } ?></td>
						<td class="right"><?php echo $reward['points']; ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="center" colspan="5"><?php echo $text_empty; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>  
		</div>
		
		<div class="pagination clearfix"><?php echo $pagination; ?></div>
		
		<div class="buttons clearfix wrapper">
			<div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
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