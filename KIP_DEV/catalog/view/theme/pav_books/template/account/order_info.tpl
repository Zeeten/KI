<?php 
	require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); 
	$themeName = basename( dirname(dirname(dirname(__FILE__))) );
?>
<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?> 

<section class="span<?php echo $SPAN[1];?>">
	<div id="content" class="order-info one-page">
	<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
		
		<h1><?php echo $heading_title; ?></h1>
		
		<div class="wrapper pav-form">
			<table class="list table">
				<thead class="hidden-phone">
					<tr>
						<td class="left" colspan="2"><?php echo $text_order_detail; ?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="left" style="width: 50%;"><?php if ($invoice_no) { ?>
							<p>
								<b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?>
							</p>
							<?php } ?>
							<p>
								<b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?>
							</p>
							<p>
								<b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?>
							</p>
						</td>
						<td class="left" style="width: 50%;">
							<?php if ($payment_method) { ?>
							<p>
								<b><?php echo $text_payment_method; ?></b> 
								<?php echo $payment_method; ?>
							</p>
							<?php } ?>
							<?php if ($shipping_method) { ?>
							<p>
								<b><?php echo $text_shipping_method; ?></b> 
								<?php echo $shipping_method; ?>
							</p>
							<?php } ?>
						</td>
					</tr>
				</tbody>
			</table>
	  
			<table class="list table">
				<thead class="hidden-phone">
					<tr>
						<td class="left">
							<?php echo $text_payment_address; ?>
						</td>
						<?php if ($shipping_address) { ?>
						<td class="left">
							<?php echo $text_shipping_address; ?>
						</td>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="left">
							<?php echo $payment_address; ?>
						</td>
						<?php if ($shipping_address) { ?>
						<td class="left">
							<?php echo $shipping_address; ?>
						</td>
						<?php } ?>
					</tr>
				</tbody>
			</table>
	  
			<table class="list table">
				<thead class="hidden-phone">
					<tr>
						<td class="left"><?php echo $column_name; ?></td>
						<td class="left"><?php echo $column_model; ?></td>
						<td class="right"><?php echo $column_quantity; ?></td>
						<td class="right"><?php echo $column_price; ?></td>
						<td class="right"><?php echo $column_total; ?></td>
						<?php if ($products) { ?>
						<td style="width: 1px;"></td>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
				  <?php foreach ($products as $product) { ?>
					<tr>
						<td class="left">
							<?php echo $product['name']; ?>
							<?php foreach ($product['option'] as $option) { ?>
								<p><small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small></p>
					  	  <?php } ?>
						</td>
						
						<td class="left"><?php echo $product['model']; ?></td>
						<td class="right"><?php echo $product['quantity']; ?></td>
						<td class="right"><?php echo $product['price']; ?></td>
						<td class="right"><?php echo $product['total']; ?></td>
						
						<td class="right">
							<a href="<?php echo $product['return']; ?>">
								<img src="catalog/view/theme/<?php echo $themeName;?>/image/return.png" alt="<?php echo $button_return; ?>" title="<?php echo $button_return; ?>" />
							</a>
						</td>
					</tr>
					<?php } ?>
					<?php foreach ($vouchers as $voucher) { ?>
					<tr>
						<td class="left"><?php echo $voucher['description']; ?></td>
						<td class="left"></td>
						<td class="right">1</td>
						<td class="right"><?php echo $voucher['amount']; ?></td>
						<td class="right"><?php echo $voucher['amount']; ?></td>
						<?php if ($products) { ?>
						<td></td>
						<?php } ?>
					</tr>
					<?php } ?>
				</tbody>			
				<tfoot>
					<?php foreach ($totals as $total) { ?>
					<tr>
						<td colspan="3"></td>
						<td class="right"><b><?php echo $total['title']; ?>:</b></td>
						<td class="right"><?php echo $total['text']; ?></td>
						<?php if ($products) { ?>
						<td></td>
						<?php } ?>
					</tr>
					<?php } ?>
				</tfoot>
			</table>  
	  
		    <?php if ($comment) { ?>
			<table class="list table">
				<thead class="hidden-phone">
					<tr>
						<td class="left"><?php echo $text_comment; ?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="left"><?php echo $comment; ?></td>
					</tr>
				</tbody>
			</table>
			<?php } ?>
			
			<?php if ($histories) { ?>
			<h3><?php echo $text_history; ?></h3>
			<table class="list table">
				<thead class="hidden-phone">
					<tr>
						<td class="left"><?php echo $column_date_added; ?></td>
						<td class="left"><?php echo $column_status; ?></td>
						<td class="left"><?php echo $column_comment; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($histories as $history) { ?>
					<tr>
						<td class="left"><?php echo $history['date_added']; ?></td>
						<td class="left"><?php echo $history['status']; ?></td>
						<td class="left"><?php echo $history['comment']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php } ?>
    	</div>
		
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