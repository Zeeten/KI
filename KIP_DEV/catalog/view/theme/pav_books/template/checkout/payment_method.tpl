<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<?php if ($payment_methods) { ?>
	<p><?php echo $text_payment_method; ?></p>
	<table class="radio table">
		<?php foreach ($payment_methods as $payment_method) { ?>
		<tr class="highlight">
			<td>
				<?php if ($payment_method['code'] == $code || !$code) { ?>
				<?php $code = $payment_method['code']; ?>
				<input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
				<?php } else { ?>
				<input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
				<?php } ?>
			</td>
			<td>
				<label for="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></label>
			</td>
		</tr>
		<?php } ?>
	</table>	
<?php } ?>

<p><b><?php echo $text_comments; ?></b></p>

<textarea name="comment" rows="8" class="span12"><?php echo $comment; ?></textarea>

<?php if ($text_agree) { ?>
<div class="buttons clearfix wrapper">
    <div class="right">
        <?php echo $text_agree; ?>
        <?php if ($agree) { ?>
        <input type="checkbox" name="agree" value="1" checked="checked" />
        <?php } else { ?>
        <input type="checkbox" name="agree" value="1" />
        <?php } ?>
        <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" class="button" />
    </div>
</div>

<?php } else { ?>
<div class="buttons clearfix wrapper">
    <div class="right">
        <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" class="button" />
    </div>
</div>
<?php } ?>

<script type="text/javascript">
    <!--
    $('.colorbox').colorbox({
    	width: 640,
    	height: 480
    });
    //-->
</script> 