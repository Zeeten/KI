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
	<div id="content" class="account one-page">		
		<?php echo $content_top; ?>		
		<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/breadcrumb.tpl" ); ?>
		<h1><?php echo $heading_title; ?></h1>
		<div class="inner pav-form">
			<h3><?php echo $text_my_account; ?></h3>
			<nav class="content wrapper">
				<ul class="acc-list">
					<li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
					<li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
					<li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
					<li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
				</ul>
			</nav>
			<h3><?php echo $text_my_orders; ?></h3>
			<nav class="content wrapper">
				<ul class="acc-list">
					<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
					<li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
					<?php if ($reward) { ?>
					<li><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
					<?php } ?>
					<li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
					<li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
					<li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
				</ul>
			</nav>
			<h3><?php echo $text_my_newsletter; ?></h3>
			<nav class="content wrapper">
				<ul class="acc-list">
					<li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
				</ul>
			</nav>
		</div>
	</div>  
	
	<?php echo $content_bottom; ?>
	
</section>

<?php if( $SPAN[2] ): ?>
	<aside class="span<?php echo $SPAN[2];?>">	
		<?php echo $column_right; ?>
	</aside>
<?php endif; ?>

<?php echo $footer; ?> 