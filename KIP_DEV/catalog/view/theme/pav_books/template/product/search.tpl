<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?>

<section class="span<?php echo $SPAN[1];?>">
	<div id="content" class="search one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?> 
		
		<h1><?php echo $heading_title; ?></h1>

		<h3><?php echo $text_critea; ?></h3>
		
		<div class="content wrapper">
			<div class="row-fluid">
				<div class="top-search">			
					<div class="span6">
						<div class="row-fluid">
							<div class="span2">
								<label for="entry_search"><?php echo $entry_search; ?></label>
							</div>
							<div class="span10">
								<?php if ($search) { ?>
								<input type="text" name="search" value="<?php echo $search; ?>" />
								<?php } else { ?>
								<input type="text" name="search" value="<?php echo $search; ?>" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" />
								<?php } ?>
							</div>
						</div>
					</div>
				
					<div class="span6">
						<select name="category_id">
							<option value="0"><?php echo $text_category; ?></option>
							<?php foreach ($categories as $category_1) { ?>
							<?php if ($category_1['category_id'] == $category_id) { ?>
							<option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
							<?php } ?>
							<?php foreach ($category_1['children'] as $category_2) { ?>
							<?php if ($category_2['category_id'] == $category_id) { ?>
							<option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
							<?php } ?>
							<?php foreach ($category_2['children'] as $category_3) { ?>
							<?php if ($category_3['category_id'] == $category_id) { ?>
							<option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
							<?php } ?>
							<?php } ?>
							<?php } ?>
							<?php } ?>
						</select>
					</div>
				</div>				
			</div>			
			
			<div class="row-fluid">
				<div class="bottom-search">
					<div class="span6">
						<?php if ($sub_category) { ?>
						<input type="checkbox" name="sub_category" value="1" id="sub_category" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="sub_category" value="1" id="sub_category" />
						<?php } ?>
						<label for="sub_category"><?php echo $text_sub_category; ?></label>
					</div>
					<div class="span6">
						<?php if ($description) { ?>
						<input type="checkbox" name="description" value="1" id="description" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="description" value="1" id="description" />
						<?php } ?>
						<label for="description"><?php echo $entry_description; ?></label>	
					</div>
				</div>
			</div>
		</div>

		<div class="buttons clearfix wrapper">
			<div class="right">
				<input type="button" value="<?php echo $button_search; ?>" id="button-search" class="button" />
			</div>
		</div>
		
		<h3><?php echo $text_search; ?></h3>
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/product/product_collection.tpl" ); ?>
		
		<?php echo $content_bottom; ?>
		
	</div>
	
<script type="text/javascript">
<!--
	$('#content input[name=\'search\']').keydown(function(e) {
		if (e.keyCode == 13) {
			$('#button-search').trigger('click');
		}
	});

	$('select[name=\'category_id\']').bind('change', function() {
		if (this.value == '0') {
			$('input[name=\'sub_category\']').attr('disabled', 'disabled');
			$('input[name=\'sub_category\']').removeAttr('checked');
		} else {
			$('input[name=\'sub_category\']').removeAttr('disabled');
		}
	});

	$('select[name=\'category_id\']').trigger('change');

	$('#button-search').bind('click', function() {
		url = 'index.php?route=product/search';
		
		var search = $('#content input[name=\'search\']').attr('value');
		
		if (search) {
			url += '&search=' + encodeURIComponent(search);
		}

		var category_id = $('#content select[name=\'category_id\']').attr('value');
		
		if (category_id > 0) {
			url += '&category_id=' + encodeURIComponent(category_id);
		}
		
		var sub_category = $('#content input[name=\'sub_category\']:checked').attr('value');
		
		if (sub_category) {
			url += '&sub_category=true';
		}
			
		var filter_description = $('#content input[name=\'description\']:checked').attr('value');
		
		if (filter_description) {
			url += '&description=true';
		}

		location = url;
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