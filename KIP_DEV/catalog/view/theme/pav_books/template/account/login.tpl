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

	<div id="content" class="login one-page">
	
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
  
		<h1><?php echo $heading_title; ?></h1>
		
		<div class="login-content">		
			<div class="row-fluid">
				<div class="span6">
					<div class="wrapper">
						<h3><?php echo $text_new_customer; ?></h3>
						<div class="content">
							<p><b><?php echo $text_register; ?></b></p>
							<p><?php echo $text_register_account; ?></p>
							<p><a href="<?php echo $register; ?>" class="button"><?php echo $button_continue; ?></a></p>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="wrapper">
						<h3><?php echo $text_returning_customer; ?></h3>
						<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="pav-form">
							<div class="content no-border">
								<p><?php echo $text_i_am_returning_customer; ?></p>
								<p><b><?php echo $entry_email; ?></b></p>
								<p><input type="text" name="email" value="<?php echo $email; ?>" class="span8" /></p>
								<p><b><?php echo $entry_password; ?></b></p>
								<p><input type="password" name="password" value="<?php echo $password; ?>" class="span8" /></p>
								<p><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></p>
								<p>
									<input type="submit" value="<?php echo $button_login; ?>" class="button" />
									<?php if ($redirect) { ?>
									<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
									<?php } ?>
								</p>
							</div>
						</form>
					</div>
				</div>	
			</div>			
		</div>
  
		<?php echo $content_bottom; ?>
		
	</div>
  
	<script type="text/javascript">
		<!--
		$('#login input').keydown(function(e) {
			if (e.keyCode == 13) {
				$('#login').submit();
			}
		});
		//-->
	</script> 
	
</section> 

<?php if( $SPAN[2] ): ?>
<aside class="span<?php echo $SPAN[2];?>">	
	<?php echo $column_right; ?>
</aside>
<?php endif; ?>

<?php echo $footer; ?>