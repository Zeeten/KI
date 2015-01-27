<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?> 

<section class="span<?php echo $SPAN[1];?>">
	<div id="content" class="transaction one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
		
		<h1><?php echo $heading_title; ?></h1>
		
		<div class="inner wrapper">
			<p>
				<?php echo $text_total; ?>
				<b><?php echo $total; ?></b>
			</p>		
			<table class="list table">
				<thead class="hidden-phone">
					<tr>
						<td class="left"><?php echo $column_date_added; ?></td>
						<td class="left"><?php echo $column_description; ?></td>
						<td class="right"><?php echo $column_amount; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($transactions) { ?>
					<?php foreach ($transactions  as $transaction) { ?>
					<tr>
						<td class="left">
							<span class="phone hidden-desktop hidden-tablet"><?php echo $column_date_added; ?></span>
							<span class="avg-right"><?php echo $transaction['date_added']; ?></span>
						</td>
						<td class="left">
							<span class="phone hidden-desktop hidden-tablet"><?php echo $column_description; ?></span>
							<span class="avg-right"><?php echo $transaction['description']; ?></span>
						</td>
						<td class="right">
							<span class="phone hidden-desktop hidden-tablet"><?php echo $column_amount; ?></span>
							<span class="avg-right"><?php echo $transaction['amount']; ?></span>
						</td>
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
		
		<div class="pagination clearfix">
			<?php echo $pagination; ?>
		</div>
		<div class="buttons clearfix wrapper">
			<div class="right">
				<a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a>
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