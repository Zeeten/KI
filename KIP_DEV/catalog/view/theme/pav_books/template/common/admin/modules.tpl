<?php 
$d = array(
	'widget_about_us_data'=>'	
		<div class="box pav-about-us m-height is-highlight">
			<h3 class="box-heading"><span>About Us</span></h3>
			<section class="box-content">
				<article>
					<img alt="About Us" src="image/data/demo/about_block.jpg" />
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla nulla libero, vulputate nec bibendum sed, vehicula sit amet mi. Mauris nec urna egestas. Proin mauris ipsum, egestas vel ultrices in, tempus in mauris. Aenean vel sapien sapien, mattis vulputate tellus.</p>
					<p>Mauris iaculis, nisl in volutpat mollis, ligula orci suscipit magna, sed sagittis orci tortor eget leo. Duis nisi tellus, fermentum vel tincidunt ut, bibendum nec arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut eget nunc dui.</p>
					<p>Curabitur congue rhoncus est, eget mollis massa hendrerit sed. Mauris elit purus, viverra nec gravida ut, vehicula vitae ipsum. Proin diet, nisi at vestibulum bibendum, leo ante fermentum diam, semper congue velit sapien id elit. Integer vehicula porta mattis.</p>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis, ducimus, amet porro iusto libero sed eaque suscipit eum a adipisci enim voluptas culpa qui eveniet earum voluptatem quod unde quibusdam facilis quae perferendis laudantium aperiam totam odit dicta ex ab.</p>			
				</article>
			</section>
		</div>		
	',
	
	'widget_twitter_data'=>'
		<div class="box pav-twitter-latest m-height is-highlight">
			<h3 class="box-heading"><span>Latest tweets</span></h3>
			<section class="box-content">
				<a class="twitter-timeline" data-tweet-limit="3" data-widget-id="362166380407185408" href="https://twitter.com/pavothemes">Tweets by @pavothemes</a> <script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</section>
		</div>
	',

	'widget_social_data'=>'
		<ul class="social clearfix">
			<li><a class="pavicon-facebook" href="https://facebook.com/" target="_blank" title="Facebook">Facebook</a></li>
			<li><a class="pavicon-twitter" href="https://twitter.com/" target="_blank" title="Twitter">Twitter</a></li>
			<li><a class="pavicon-google" href="https://plus.google.com/" target="_blank" title="Google Plus">Google Plus</a></li>
			<li><a class="pavicon-youtube" href="https://youtube.com/" target="_blank" title="Youtube">Youtube</a></li>
			<li><a class="pavicon-pinterest" href="http://pinterest.com/" target="_blank" title="Pinterest">Pinterest</a></li>
		</ul>
	',

	'widget_newsletter_data'=>'
		<div class="input-box">
			<a class="button button-newsletter" href="#" title="subscribe"><span>subscribe</span></a> 
			<input class="input-subscribe" name="email" placeholder="Enter email address" title="Enter email address" type="text" />
		</div>
		<h4 class="t-news hidden-tablet hidden-phone">Newsletter</h4>	
	',	


	'demo_shop_map_data'=>'
		<img src="image/data/demo/shopmap.jpg" alt="shop map">
		<p><i class="pavicon-shop">&nbsp;</i>Address: Me Tri Ha, Tu Liem, Ha Noi ,Viet Nam</p>
	',

	/* 'demo_widget_contact_data'=>'
		<p>
			Lorem ipsum dolor sit amet sectetur nisl. Ut dapibus laoreet egestas.
		</p>
		<ul class="contact-us clearfix">
			<li>
				<i class="pavicon-phone">&nbsp;</i>
				Phone: (000) 354-543-5456
			</li> 
			<li>
				<i class="pavicon-fax">&nbsp;</i>
				Fax: (000) 354-543-5457
			</li> 
		</ul>		
		<aside class="payment">
			<a href="#" title="visa"> <img src="catalog/view/theme/pav_books/image/visa.png" alt="visa"> </a>
			<a href="#" title="master card"> <img src="catalog/view/theme/pav_books/image/master_card.png" alt="master card"> </a>
			<a href="#" title="american express"> <img src="catalog/view/theme/pav_books/image/american.png" alt="american express"> </a>
			<a href="#" title="paypal"> <img src="catalog/view/theme/pav_books/image/paypal.png" alt="paypal"> </a>
		</aside> 
	', */
	

	'username_twitter_module' => 'pavothemes'
	);

$module = array_merge( $d, $module );

//	echo '<pre>'.print_r( $languages, 1 );die;

?>

<div class="inner-modules-wrap">
	<div class="vtabs mytabs" id="my-tab-innermodules">
		<a onclick="return false;" href="#tab-imodule-footer" class="selected"><?php echo $this->language->get('Footer');?></a>
	</div>

	<div class="page-tabs-wrap">
		<div id="tab-imodule-footer">
		
			<h4><?php echo $this->language->get( 'Mass Bottom' ) ; ?></h4>

			<div id="language-about_us_data" class="htabs mytabstyle">
				<?php foreach ($languages as $language) { ?>
				<a href="#tab-language-about_us_data-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
				<?php } ?>
			</div>
			
			<?php foreach ($languages as $language) {   ?>
			<div id="tab-language-about_us_data-<?php echo $language['language_id']; ?>">
				<table class="form">
		            <?php $text =  isset($module['about_us_data'][$language['language_id']]) ? $module['about_us_data'][$language['language_id']] : $module['widget_about_us_data'];  ?>
					<tr>
						<td><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $this->language->get('About Us Module');?>: </td>
						<td><textarea name="themecontrol[about_us_data][<?php echo $language['language_id']; ?>]" id="about_us_data-<?php echo $language['language_id']; ?>" rows="5" cols="60"><?php echo $text; ?></textarea></td>
					</tr>
				</table>
			</div>
			<?php } ?>			
			
			<div id="language-twitter_data" class="htabs mytabstyle">
				<?php foreach ($languages as $language) { ?>
				<a href="#tab-language-twitter_data-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
				<?php } ?>
			</div>
			
			<?php foreach ($languages as $language) {   ?>
			<div id="tab-language-twitter_data-<?php echo $language['language_id']; ?>">
				<table class="form">
		            <?php $text =  isset($module['twitter_data'][$language['language_id']]) ? $module['twitter_data'][$language['language_id']] : $module['widget_twitter_data'];  ?>
					<tr>
						<td><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $this->language->get('Twitter Module');?>: </td>
						<td><textarea name="themecontrol[twitter_data][<?php echo $language['language_id']; ?>]" id="twitter_data-<?php echo $language['language_id']; ?>" rows="5" cols="60"><?php echo $text; ?></textarea></td>
					</tr>
				</table>
			</div>
			<?php } ?>


			
			<h4><?php echo $this->language->get( 'Footer Top' ) ; ?></h4>

			<div id="language-social_data" class="htabs mytabstyle">
				<?php foreach ($languages as $language) { ?>
				<a href="#tab-language-social_data-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
				<?php } ?>
			</div>
			
			<?php foreach ($languages as $language) {   ?>
			<div id="tab-language-social_data-<?php echo $language['language_id']; ?>">
				<table class="form">
		            <?php $text =  isset($module['social_data'][$language['language_id']]) ? $module['social_data'][$language['language_id']] : $module['widget_social_data'];  ?>
					<tr>
						<td><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $this->language->get('Social Module');?>: </td>
						<td><textarea name="themecontrol[social_data][<?php echo $language['language_id']; ?>]" id="social_data-<?php echo $language['language_id']; ?>" rows="5" cols="60"><?php echo $text; ?></textarea></td>
					</tr>
				</table>
			</div>
			<?php } ?>	

			<div id="language-newsletter_data" class="htabs mytabstyle">
				<?php foreach ($languages as $language) { ?>
				<a href="#tab-language-newsletter_data-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
				<?php } ?>
			</div>
			
			<?php foreach ($languages as $language) {   ?>
			<div id="tab-language-newsletter_data-<?php echo $language['language_id']; ?>">
				<table class="form">
		            <?php $text =  isset($module['newsletter_data'][$language['language_id']]) ? $module['newsletter_data'][$language['language_id']] : $module['widget_newsletter_data'];  ?>
					<tr>
						<td><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $this->language->get('Newsletter Module');?>: </td>
						<td><textarea name="themecontrol[newsletter_data][<?php echo $language['language_id']; ?>]" id="newsletter_data-<?php echo $language['language_id']; ?>" rows="5" cols="60"><?php echo $text; ?></textarea></td>
					</tr>
				</table>
			</div>
			<?php } ?>	
			
		
			<h4><?php echo $this->language->get( 'Footer Center' ) ; ?></h4>

			<div id="language-shop_map_data" class="htabs mytabstyle">
				<?php foreach ($languages as $language) { ?>
				<a href="#tab-language-shop_map_data-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
				<?php } ?>
			</div>
			
			<?php foreach ($languages as $language) {   ?>
			<div id="tab-language-shop_map_data-<?php echo $language['language_id']; ?>">
				<table class="form">
		            <?php $text =  isset($module['shop_map_data'][$language['language_id']]) ? $module['shop_map_data'][$language['language_id']] : $module['demo_shop_map_data'];  ?>
					<tr>
						<td><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $this->language->get('Contact Us Module');?>: </td>
						<td><textarea name="themecontrol[shop_map_data][<?php echo $language['language_id']; ?>]" id="shop_map_data-<?php echo $language['language_id']; ?>" rows="5" cols="60"><?php echo $text; ?></textarea></td>
					</tr>
				</table>
			</div>
			<?php } ?>
			
			
			<?php /*
			<div id="language-widget_contact_data" class="htabs mytabstyle">
				<?php foreach ($languages as $language) { ?>
				<a href="#tab-language-widget_contact_data-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
				<?php } ?>
			</div>

			<?php foreach ($languages as $language) {   ?>
			<div id="tab-language-widget_contact_data-<?php echo $language['language_id']; ?>">
				<table class="form">
	            	<?php $text =  isset($module['widget_contact_data'][$language['language_id']]) ? $module['widget_contact_data'][$language['language_id']] : $module['demo_widget_contact_data'];  ?>
					<tr>
						<td><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $this->language->get('Contact Us Module');?>: </td>
						<td><textarea name="themecontrol[widget_contact_data][<?php echo $language['language_id']; ?>]" id="widget_contact_data-<?php echo $language['language_id']; ?>" rows="5" cols="60"><?php echo $text; ?></textarea></td>
					</tr>
				</table>
			</div>
			<?php } ?>
			*/ ?>
			
			

			<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 			
			<script type="text/javascript">	
			<!--
			$("#language-about_us_data a").tabs();
			<?php foreach( $languages as $language )  { ?>
			CKEDITOR.replace('about_us_data-<?php echo $language['language_id']; ?>', {
				filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
			});
			<?php } ?>
			//-->

			
			<!--
			$("#language-twitter_data a").tabs();
			<?php foreach( $languages as $language )  { ?>
			CKEDITOR.replace('twitter_data-<?php echo $language['language_id']; ?>', {
				filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
			});
			<?php } ?>
			//-->	

			
			<!--
			$("#language-social_data a").tabs();
			<?php foreach( $languages as $language )  { ?>
			CKEDITOR.replace('social_data-<?php echo $language['language_id']; ?>', {
				filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
			});
			<?php } ?>
			//-->	

			<!--
			$("#language-newsletter_data a").tabs();
			<?php foreach( $languages as $language )  { ?>
			CKEDITOR.replace('newsletter_data-<?php echo $language['language_id']; ?>', {
				filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
			});
			<?php } ?>
			//-->		
			
			
			<!--
			$("#language-shop_map_data a").tabs();
			<?php foreach( $languages as $language )  { ?>
			CKEDITOR.replace('shop_map_data-<?php echo $language['language_id']; ?>', {
				filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
			});
			<?php } ?>
			//-->
			
			/*
			<!--
			$("#language-widget_contact_data a").tabs();
			<?php foreach( $languages as $language )  { ?>
			CKEDITOR.replace('widget_contact_data-<?php echo $language['language_id']; ?>', {
				filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
			});
			<?php } ?>
			//-->
			*/
			
			</script> 
		</div>
	</div>
	<div class="clearfix clear"></div>	
</div>

