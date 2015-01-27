<?php $themeName = basename( dirname(dirname(dirname(__FILE__))) ); ?>

<div class="row-fluid">	
	<div class="span6">
		<div class="content wrapper">
			<h3><?php echo $text_your_details; ?></h3>
			<p><span class="required">*</span> <?php echo $entry_firstname; ?></p>
			<p><input type="text" name="firstname" value="<?php echo $firstname; ?>" class="large-field span12" /></p>
			<p><span class="required">*</span> <?php echo $entry_lastname; ?></p>
			<p><input type="text" name="lastname" value="<?php echo $lastname; ?>" class="large-field span12" /></p>
			<p><span class="required">*</span> <?php echo $entry_email; ?></p>
			<p><input type="text" name="email" value="<?php echo $email; ?>" class="large-field span12" /></p>
			<p><span class="required">*</span> <?php echo $entry_telephone; ?></p>
			<p><input type="text" name="telephone" value="<?php echo $telephone; ?>" class="large-field span12" /></p>
			<p><?php echo $entry_fax; ?></p>
			<p><input type="text" name="fax" value="<?php echo $fax; ?>" class="large-field span12" /></p>	
		</div>
	</div>
	
	<div class="span6">
		<div class="content wrapper">
			<h3><?php echo $text_your_address; ?></h3>
			<p><?php echo $entry_company; ?></p>
			<p>
				<input type="text" name="company" value="<?php echo $company; ?>" class="large-field span12" />
			</p>

			<div style="display: <?php echo (count($customer_groups) > 1 ? 'table-row' : 'none'); ?>;"> <?php echo $entry_customer_group; ?>
				<?php foreach ($customer_groups as $customer_group) { ?>
				<?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>				
					<p>
						<input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
						<label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
					</p>			
				<?php } else { ?>					
					<p>
						<input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" />
						<label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
					</p>				
				<?php } ?>
				<?php } ?>				
			</div>


			<div id="company-id-display">
				<p><span id="company-id-required" class="required">*</span> <?php echo $entry_company_id; ?></p>
				<p><input type="text" name="company_id" value="<?php echo $company_id; ?>" class="large-field span12" /></p>
			</div>

			<div id="tax-id-display">
				<p><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?></p>
				<p><input type="text" name="tax_id" value="<?php echo $tax_id; ?>" class="large-field span12" /></p>	
			</div>

			<p><span class="required">*</span> <?php echo $entry_address_1; ?></p>

			<p><input type="text" name="address_1" value="<?php echo $address_1; ?>" class="large-field span12" /></p>

			<p><?php echo $entry_address_2; ?></p>

			<p><input type="text" name="address_2" value="<?php echo $address_2; ?>" class="large-field span12" /></p>

			<p><span class="required">*</span> <?php echo $entry_city; ?></p>

			<p><input type="text" name="city" value="<?php echo $city; ?>" class="large-field span12" /></p>
		
			<p><span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></p>

			<p><input type="text" name="postcode" value="<?php echo $postcode; ?>" class="large-field span12" /></p>
			
			<p><span class="required">*</span> <?php echo $entry_country; ?></p>
			
			<p>
				<select name="country_id" class="large-field span12">
					<option value=""><?php echo $text_select; ?></option>
					<?php foreach ($countries as $country) { ?>
					<?php if ($country['country_id'] == $country_id) { ?>
					<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
					<?php } ?>
					<?php } ?>
				</select>
			</p>
		
			<p><span class="required">*</span> <?php echo $entry_zone; ?></p>

			<p><select name="zone_id" class="large-field span12"></select></p>	
		</div>
	</div>
</div>

<?php if ($shipping_required) { ?>
<div class="clearfix">
	<div class="pull-left">
		<p>
			<?php if ($shipping_address) { ?>
			<input type="checkbox" name="shipping_address" value="1" id="shipping" checked="checked" />
			<?php } else { ?>
			<input type="checkbox" name="shipping_address" value="1" id="shipping" />
			<?php } ?>
		</p>
	</div>
	<div class="pull-left">
		<p><label for="shipping"><?php echo $entry_shipping; ?></label>	</p>
	</div>
</div>
<?php } ?>

<div class="buttons clearfix wrapper">
	<div class="right">
		<input type="button" value="<?php echo $button_continue; ?>" id="button-guest" class="button" />
	</div>
</div>

<script type="text/javascript">
	<!--
	$('#payment-address input[name=\'customer_group_id\']:checked').live('change', function() {
		var customer_group = [];
		
	<?php foreach ($customer_groups as $customer_group) { ?>
	customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_required'] = '<?php echo $customer_group['company_id_required']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_required'] = '<?php echo $customer_group['tax_id_required']; ?>';
	<?php } ?>	

		if (customer_group[this.value]) {
			if (customer_group[this.value]['company_id_display'] == '1') {
				$('#company-id-display').show();
			} else {
				$('#company-id-display').hide();
			}
			
			if (customer_group[this.value]['company_id_required'] == '1') {
				$('#company-id-required').show();
			} else {
				$('#company-id-required').hide();
			}
			
			if (customer_group[this.value]['tax_id_display'] == '1') {
				$('#tax-id-display').show();
			} else {
				$('#tax-id-display').hide();
			}
			
			if (customer_group[this.value]['tax_id_required'] == '1') {
				$('#tax-id-required').show();
			} else {
				$('#tax-id-required').hide();
			}	
		}
	});

	$('#payment-address input[name=\'customer_group_id\']:checked').trigger('change');
	//-->
</script>


<script type="text/javascript">
	<!--
	$('#payment-address select[name=\'country_id\']').bind('change', function() {
		if (this.value == '') return;
		$.ajax({
			url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('#payment-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/<?php echo $themeName;?>/image/loading.gif" alt="" /></span>');
			},
			complete: function() {
				$('.wait').remove();
			},			
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('#payment-postcode-required').show();
				} else {
					$('#payment-postcode-required').hide();
				}
				
				html = '<option value=""><?php echo $text_select; ?></option>';
				
				if (json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
	        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
		    			
						if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
		      				html += ' selected="selected"';
		    			}
		
		    			html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
				}
				
				$('#payment-address select[name=\'zone_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#payment-address select[name=\'country_id\']').trigger('change');
	//-->
</script>