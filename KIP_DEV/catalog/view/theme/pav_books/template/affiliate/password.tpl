<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?> 

<section class="span<?php echo $SPAN[1];?>">
	<div id="content" class="password one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
		
		<h1><?php echo $heading_title; ?></h1>		
		
		<div class="inner">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="pav-form">
				<h3><?php echo $text_password; ?></h3>
				<div class="content wrapper">
					<table class="form table">
						<tr>
							<td>
								<span class="required">*</span> <?php echo $entry_password; ?>
							</td>
							<td>
								<input type="password" name="password" value="<?php echo $password; ?>" />
								<?php if ($error_password) { ?>
								<span class="error"><?php echo $error_password; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>
								<span class="required">*</span> <?php echo $entry_confirm; ?>
							</td>
							<td>
								<input type="password" name="confirm" value="<?php echo $confirm; ?>" />
								<?php if ($error_confirm) { ?>
								<span class="error"><?php echo $error_confirm; ?></span>
								<?php } ?>
							</td>
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
			</form>	
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