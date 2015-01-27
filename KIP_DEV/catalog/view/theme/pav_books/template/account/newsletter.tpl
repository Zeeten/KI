<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<aside class="span<?php echo $SPAN[0];?>">
		<?php echo $column_left; ?>
	</aside>
<?php endif; ?> 

<section class="span<?php echo $SPAN[1];?>">
	<div id="content" class="newsletter one-page">
		<?php echo $content_top; ?>
		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
		
		<h1><?php echo $heading_title; ?></h1>
		
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="pav-form">
			<div class="inner">
				<div class="content wrapper">
				  <table class="form table">
					<tr>
					    <td>
							<?php echo $entry_newsletter; ?>
						</td>
						<td>
							<?php if ($newsletter) { ?>
							<input type="radio" name="newsletter" value="1" checked="checked" />
							<?php echo $text_yes; ?>&nbsp;
							<input type="radio" name="newsletter" value="0" />
							<?php echo $text_no; ?>
							<?php } else { ?>
							<input type="radio" name="newsletter" value="1" />
							<?php echo $text_yes; ?>&nbsp;
							<input type="radio" name="newsletter" value="0" checked="checked" />
							<?php echo $text_no; ?>
							<?php } ?>
						</td>
					</tr>
				  </table>
				</div>				
			</div>
			<div class="buttons clearfix wrapper">
				<div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
				<div class="right"><input type="submit" value="<?php echo $button_continue; ?>" class="button" /></div>
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