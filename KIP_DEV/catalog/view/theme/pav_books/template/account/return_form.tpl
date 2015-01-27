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
	
	<div id="content" class="return-form one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
		
		<h1><?php echo $heading_title; ?></h1>
		
		<div class="inner">
			<?php echo $text_description; ?>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="pav-form">
				<h3><?php echo $text_order; ?></h3>
				<div class="content wrapper">
					<div class="row-fluid">
						<div class="span6">
							<p><span class="required">*</span> <?php echo $entry_firstname; ?></p>
							<p><input type="text" name="firstname" value="<?php echo $firstname; ?>" class="large-field span12" /></p>
							<?php if ($error_firstname) { ?>
							<p><span class="error"><?php echo $error_firstname; ?></span></p>
							<?php } ?>
							
							<p><span class="required">*</span> <?php echo $entry_lastname; ?></p>
							<p><input type="text" name="lastname" value="<?php echo $lastname; ?>" class="large-field span12" /></p>
							<?php if ($error_lastname) { ?>
							<p><span class="error"><?php echo $error_lastname; ?></span></p>
							<?php } ?>

							<p><span class="required">*</span> <?php echo $entry_email; ?></p>
							<p><input type="text" name="email" value="<?php echo $email; ?>" class="large-field span12" /></p>
							<?php if ($error_email) { ?>
							<p><span class="error"><?php echo $error_email; ?></span></p>
							<?php } ?>

							<p><span class="required">*</span> <?php echo $entry_telephone; ?></p>
							<p><input type="text" name="telephone" value="<?php echo $telephone; ?>" class="large-field span12" /></p>
							<?php if ($error_telephone) { ?>
							<p><span class="error"><?php echo $error_telephone; ?></span></p>
							<?php } ?>				
						</div>

						<div class="span6">
							<p><span class="required">*</span> <?php echo $entry_order_id; ?></p>
							<p><input type="text" name="order_id" value="<?php echo $order_id; ?>" class="large-field span12" /></p>
							<?php if ($error_order_id) { ?>
							<p><span class="error"><?php echo $error_order_id; ?></span></p>
							<?php } ?>
							<p><?php echo $entry_date_ordered; ?></p>
							<p><input type="text" name="date_ordered" value="<?php echo $date_ordered; ?>" class="large-field date span12" /></p>
						</div>
					</div>				  
				</div>
				<h3><?php echo $text_product; ?></h3>
				<div id="return-product">
					<div class="content wrapper">
						<div class="return-product">
							<div class="row-fluid">
								<div class="span4">
									<div class="return-name">
										<p><span class="required">*</span> <b><?php echo $entry_product; ?></b></p>
										<p><input type="text" name="product" value="<?php echo $product; ?>" class="span12" /></p>
										<?php if ($error_product) { ?>
										<p><span class="error"><?php echo $error_product; ?></span></p>
										<?php } ?>
									</div>
								</div>

								<div class="span4">
									<div class="return-model">
										<p><span class="required">*</span> <b><?php echo $entry_model; ?></b></p>
										<p><input type="text" name="model" value="<?php echo $model; ?>" class="span12" /></p>
										<?php if ($error_model) { ?>
										<p><span class="error"><?php echo $error_model; ?></span></p>
										<?php } ?>
									</div>
								</div>

								<div class="span4">
									<div class="return-quantity">
										<p><b><?php echo $entry_quantity; ?></b></p>
										<p><input type="text" name="quantity" value="<?php echo $quantity; ?>" class="span12" /></p>										
									</div>
								</div>
							</div>					  
						</div>
						<div class="return-detail">	
							<div class="row-fluid">
								<div class="span4">
									<div class="return-reason">
										<p><span class="required">*</span> <b><?php echo $entry_reason; ?></b></p>
										<table class="table">
											<?php foreach ($return_reasons as $return_reason) { ?>
											<?php if ($return_reason['return_reason_id'] == $return_reason_id) { ?>
											<tr>
												<td width="1">
													<input type="radio" name="return_reason_id" value="<?php echo $return_reason['return_reason_id']; ?>" id="return-reason-id<?php echo $return_reason['return_reason_id']; ?>" checked="checked" />
												</td>
												<td>
													<label for="return-reason-id<?php echo $return_reason['return_reason_id']; ?>"><?php echo $return_reason['name']; ?></label>
												</td>
											</tr>
											<?php } else { ?>
											<tr>
												<td width="1">
													<input type="radio" name="return_reason_id" value="<?php echo $return_reason['return_reason_id']; ?>" id="return-reason-id<?php echo $return_reason['return_reason_id']; ?>" />
												</td>
												<td>
													<label for="return-reason-id<?php echo $return_reason['return_reason_id']; ?>"><?php echo $return_reason['name']; ?></label>
												</td>
											</tr>
											<?php  } ?>
											<?php  } ?>
										</table>
										<?php if ($error_reason) { ?>
										<p><span class="error"><?php echo $error_reason; ?></span></p>
										<?php } ?>
									</div>
								</div>
								<div class="span4">
									<div class="return-opened">
										<p>
											<b><?php echo $entry_opened; ?></b>
										</p>
										<p>
											<?php if ($opened) { ?>
											<input type="radio" name="opened" value="1" id="opened" checked="checked" />
											<?php } else { ?>										
											<input type="radio" name="opened" value="1" id="opened" />
											<?php } ?>
											<label for="opened"><?php echo $text_yes; ?></label>
											<?php if (!$opened) { ?>
											<input type="radio" name="opened" value="0" id="unopened" checked="checked" />
											<?php } else { ?>
											<input type="radio" name="opened" value="0" id="unopened" />
											<?php } ?>
											<label for="unopened"><?php echo $text_no; ?></label>
										</p>
										<p><?php echo $entry_fault_detail; ?></p>
										<p><textarea name="comment" cols="150" rows="6" class="span12"><?php echo $comment; ?></textarea></p>										
									</div>
								</div>
								<div class="span4">
									<div class="return-captcha">
										<p>
											<b><?php echo $entry_captcha; ?></b>
										</p>
										<p>
											<input type="text" name="captcha" value="<?php echo $captcha; ?>" class="span12" />
										</p>
										<p>
											<img src="index.php?route=account/return/captcha" alt="" />
										</p>
										<p>
											<?php if ($error_captcha) { ?>
											<span class="error"><?php echo $error_captcha; ?></span>
											<?php } ?>
										</p>									
									</div>
								</div>
							</div>				
						</div>					
					</div>
				</div>
				
				<?php if ($text_agree) { ?>
				<div class="buttons clearfix wrapper">
					<div class="left">
						<a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a>
					</div>
					<div class="right">
						<?php echo $text_agree; ?>
						<?php if ($agree) { ?>
						<input type="checkbox" name="agree" value="1" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="agree" value="1" />
						<?php } ?>
						<input type="submit" value="<?php echo $button_continue; ?>" class="button" />
					</div>
				</div>
				<?php } else { ?>
				<div class="buttons clearfix wrapper">
					<div class="left">
						<a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a>
					</div>
					<div class="right">
						<input type="submit" value="<?php echo $button_continue; ?>" class="button" />
					</div>
				</div>
				<?php } ?>
			</form>
		</div>
		<?php echo $content_bottom; ?>  
	</div>

	<script type="text/javascript">
	<!--
		$(document).ready(function() {
			$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		});
	//-->
	</script> 

	<script type="text/javascript">
	<!--
		$(document).ready(function() {
			$('.colorbox').colorbox({
				width: 640,
				height: 480
			});
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