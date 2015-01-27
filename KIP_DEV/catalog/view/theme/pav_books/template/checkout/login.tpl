<div class="row-fluid">
	<div class="span6">
		<div class="content wrapper">
			<h3><?php echo $text_new_customer; ?></h3>
			<p><?php echo $text_checkout; ?></p>
			<p>
				<label for="register">
					<?php if ($account == 'register') { ?>
					<input type="radio" name="account" value="register" id="register" checked="checked" />
					<?php } else { ?>
					<input type="radio" name="account" value="register" id="register" />
					<?php } ?>
					<b><?php echo $text_register; ?></b>
				</label>
			</p>			

			<?php if ($guest_checkout) { ?>
				<p>
					<label for="guest">
						<?php if ($account == 'guest') { ?>
						<input type="radio" name="account" value="guest" id="guest" checked="checked" />
						<?php } else { ?>
						<input type="radio" name="account" value="guest" id="guest" />
						<?php } ?>
						<b><?php echo $text_guest; ?></b>
					</label>
				</p>
			<?php } ?>
			
			<p><?php echo $text_register_account; ?></p>
			
			<input type="button" value="<?php echo $button_continue; ?>" id="button-account" class="button" />
		</div>
	</div>

	<div id="login" class="span6">
		<div class="content wrapper">
			<h3><?php echo $text_returning_customer; ?></h3>
			<p><?php echo $text_i_am_returning_customer; ?></p>
			<p><b><?php echo $entry_email; ?></b></p>
			<p><input type="text" name="email" value="" class="span12" /></p>
			<p><b><?php echo $entry_password; ?></b></p>
			<p><input type="password" name="password" value="" class="span12" /></p>
			<p><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></p>
			<p><input type="button" value="<?php echo $button_login; ?>" id="button-login" class="button" /></p>
		</div>
	</div>
</div>