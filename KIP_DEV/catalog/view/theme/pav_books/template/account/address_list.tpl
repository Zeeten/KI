<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?> 

<section class="span<?php echo $SPAN[1];?>">
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>

	<div id="content" class="address-list one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
		
		<h1><?php echo $heading_title; ?></h1>
		
		<div class="inner pav-form">
			<h3><?php echo $text_address_book; ?></h3>
			<?php foreach ($addresses as $result) { ?>
			<div class="content wrapper">
				<table class="table no-margin-bottom">
					<tr>
						<td>
							<?php echo $result['address']; ?>
						</td>
						<td style="text-align: right;">
							<a href="<?php echo $result['update']; ?>" class="button"><?php echo $button_edit; ?></a> &nbsp; 
							<a href="<?php echo $result['delete']; ?>" class="button"><?php echo $button_delete; ?></a>
						</td>
					</tr>
				</table>
			</div>
			<?php } ?>
		</div>
		
		<div class="buttons clearfix wrapper">
			<div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
			<div class="right"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_new_address; ?></a></div>
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