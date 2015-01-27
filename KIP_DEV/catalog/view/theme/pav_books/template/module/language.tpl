<?php if (count($languages) > 1) { ?>
<?php 
	$tmp = array();
	foreach( $languages as $language ){
		if( $language['code'] == $language_code ){
			$tmp = $language;
			break;
		}
	}
	// echo '<pre>'.print_r( $tmp, 1 );die;
 ?>
 
 <div class="language-wrapper pull-right">
	<?php /* <span><?php echo $text_language; ?> : </span> */ ?>
	<div class="btn-group">
		<a class="btn dropdown-toggle no-bg" data-toggle="dropdown" href="#">
			<?php if( !empty($tmp) ) { ?>
			<span>			
				<img src="image/flags/<?php echo $tmp['image']; ?>" alt="<?php echo $tmp['name']; ?>" title="<?php echo $tmp['name']; ?>" />
			</span>		
			<?php } ?>
			<span class="caret icon-angle-down"></span>
		</a>
		<div class="dropdown-menu dropdown">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
			  <div id="language">
				<?php foreach ($languages as $language) { ?>
					<span class="list-item" onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>'); $(this).parent().parent().submit();">
						<span class="item-name"><?php echo $language['name']; ?></span>
						<img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>'); $(this).parent().parent().submit();" class="item-symbol" />
					</span>				
				<?php } ?>
				<input type="hidden" name="language_code" value="" />
				<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
			  </div>
			</form>	
		</div>
	</div>
 </div> 

<?php } ?>
