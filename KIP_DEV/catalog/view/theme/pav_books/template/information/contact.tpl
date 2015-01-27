<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" );
$themeConfig = $this->config->get( 'themecontrol' );

// echo '<pre>'.print_r( $themeConfig, 1);die;
?>

<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?> 

<section class="span<?php echo $SPAN[1];?>">
	<div id="content" class="contact one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
		
		<h1><?php echo $heading_title; ?></h1>
		
		<?php  if(  isset($themeConfig['contact_customhtml']) && isset($themeConfig['contact_customhtml'][$this->config->get('config_language_id')]) && trim($themeConfig['contact_customhtml'][$this->config->get('config_language_id')]) ){ ?>
			<div class="contact-customhtml content wrapper">
				<?php echo ($themeConfig['contact_customhtml'][$this->config->get('config_language_id')]); ?>
			</div>
		<?php } ?>
		
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="pav-form">
			<div class="inner">
				<h3><?php echo $text_location; ?></h3>
				<div class="contact-info wrapper content">
					<div class="content">
						<div class="row-fluid">
							<div class="span6">
								<p><b><?php echo $text_address; ?></b></p>
								<p><?php echo $store; ?></p>
								<p><?php echo $address; ?></p>							
							</div>
							
							<div class="span6">
								<?php if ($telephone) { ?>
									<p><b><?php echo $text_telephone; ?></b></p>
									<p><?php echo $telephone; ?></p>
								<?php } ?>
								<?php if ($fax) { ?>
									<p><b><?php echo $text_fax; ?></b></p>
									<p><?php echo $fax; ?></p>								
								<?php } ?>
							</div>
						</div>					
					</div>
				</div>
				
				<h3><?php echo $text_contact; ?></h3>
				<div class="content wrapper">
					<p><b><?php echo $entry_name; ?></b></p>
					<p><input type="text" name="name" value="<?php echo $name; ?>" class="span4" /></p>
					<?php if ($error_name) { ?>
					<p><span class="error"><?php echo $error_name; ?></span></p>
					<?php } ?>

					<p><b><?php echo $entry_email; ?></b></p>
					<p><input type="text" name="email" value="<?php echo $email; ?>" class="span4" /></p>
					<?php if ($error_email) { ?>
					<p><span class="error"><?php echo $error_email; ?></span></p>
					<?php } ?>
					
					<p><b><?php echo $entry_enquiry; ?></b></p>
					<p><textarea name="enquiry" cols="40" rows="10" class="span8"><?php echo $enquiry; ?></textarea></p>

					<?php if ($error_enquiry) { ?>
					<p><span class="error"><?php echo $error_enquiry; ?></span></p>
					<?php } ?>
					
					<p><b><?php echo $entry_captcha; ?></b></p>
					<p><input type="text" name="captcha" value="<?php echo $captcha; ?>" class="span4" /></p>
					<p><img src="index.php?route=information/contact/captcha" alt="" /></p>

					<?php if ($error_captcha) { ?>
					<p><span class="error"><?php echo $error_captcha; ?></span></p>
					<?php } ?>
				</div>
				<div class="buttons clearfix wrapper">
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