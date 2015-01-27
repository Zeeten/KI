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
	
	<div id="content" class="edit one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
		
		<h1><?php echo $heading_title; ?></h1>
		
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="pav-form">
			<div class="inner">
				
				<h3><?php echo $text_your_details; ?></h3>
				
				<div class="content wrapper">
					<table class="form table">
						<tr>
							<td>
								<span class="required">*</span> <?php echo $entry_firstname; ?>
							</td>
							<td>
								<input type="text" name="firstname" value="<?php echo $firstname; ?>" />
								<?php if ($error_firstname) { ?>
								<span class="error"><?php echo $error_firstname; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>
								<span class="required">*</span> <?php echo $entry_lastname; ?>
							</td>
							<td>
								<input type="text" name="lastname" value="<?php echo $lastname; ?>" />
								<?php if ($error_lastname) { ?>
								<span class="error"><?php echo $error_lastname; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>
								<span class="required">*</span> <?php echo $entry_email; ?>
							</td>
							<td>
								<input type="text" name="email" value="<?php echo $email; ?>" />
								<?php if ($error_email) { ?>
								<span class="error"><?php echo $error_email; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>
								<span class="required">*</span> <?php echo $entry_telephone; ?>
							</td>
							<td>
								<input type="text" name="telephone" value="<?php echo $telephone; ?>" />
								<?php if ($error_telephone) { ?>
								<span class="error"><?php echo $error_telephone; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $entry_fax; ?>
							</td>
							<td>
								<input type="text" name="fax" value="<?php echo $fax; ?>" />
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