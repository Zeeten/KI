<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?> 

<section class="span<?php echo $SPAN[1];?>">
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>

	<div id="content" class="forgot one-page">
	
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
  
		<h1><?php echo $heading_title; ?></h1>
	
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="pav-form"> 
			<div class="inner">	
				<p><?php echo $text_email; ?></p>
				<h3><?php echo $text_your_email; ?></h3>
				<div class="content wrapper">
					<table class="form table no-border">
						<tr>
							<td><?php echo $entry_email; ?></td>
							<td><input type="text" name="email" value="" /></td>
						</tr>
					</table>
				</div>
				
				<div class="buttons clearfix wrapper">
					<div class="left">
						<a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a>
					</div>
					<div class="right">
						<input type="submit" value="<?php echo $button_continue; ?>" class="button" />
					</div>
				</div>
				
			</div>		
		</form> 
		
		<?php echo $content_bottom; ?>
		
	</div>
	
</section> 

<?php if( $SPAN[2] ): ?>
<aside class="span<?php echo $SPAN[2];?>">	
	<?php echo $column_right; ?>
</aside>
<?php endif; ?>

<?php echo $footer; ?>