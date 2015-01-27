<?php 
	/******************************************************
	 * @package Pav Megamenu module for Opencart 1.5.x
	 * @version 1.0
	 * @author http://www.pavothemes.com
	 * @copyright	Copyright (C) Feb 2013 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
	 * @license		GNU General Public License version 2
	*******************************************************/

	require_once( DIR_TEMPLATE.$this->config->get('config_template')."/template/libs/module.php" );
	$themeConfig = $this->config->get('themecontrol');
	$themeName = $this->config->get('config_template');
	$helper = ThemeControlHelper::getInstance( $this->registry, $themeName );
	$LANGUAGE_ID = $this->config->get( 'config_language_id' );  

?>
</div></section>

<?php
	/**
	 * Mass Bottom Position
	 * $ospans allow overrides width of columns base on thiers indexs. format array( 1=> 3 )[value from 1->12]
	 */
	$modules = $helper->getModulesByPosition( 'mass_bottom' ); 
	$ospans = array(1=>6,2=>6);
	$cols   = 2;
	if( count($modules) ) { ?>
	<section id="pav-mass-bottom">
		<div class="container">
			<?php $j=1;foreach ($modules as $i =>  $module) {   ?>
				<?php if( $i++%$cols == 0 || count($modules)==1 ){  $j=1;?><div class="row-fluid"><?php } ?>	
				<div class="span<?php echo floor(12/$cols);?>"><?php echo $module; ?></div>
				<?php if( $i%$cols == 0 || $i==count($modules) ){ ?></div><?php } ?>	
			<?php  $j++;  } ?>
		</div>	
	</section>	
	<?php } else { ?>
	<section id="pav-mass-bottom">
		<div class="container">
			<div class="row-fluid">
				<?php if( isset($themeConfig['about_us_data'][$LANGUAGE_ID]) ) { ?>
				<div class="span6">								
					<?php echo html_entity_decode( $themeConfig['about_us_data'][$LANGUAGE_ID], ENT_QUOTES, 'UTF-8' ); ?>
				</div>
				<?php } ?>
				
				<?php if( isset($themeConfig['twitter_data'][$LANGUAGE_ID]) ) { ?>
				<div class="span6">								
					<?php echo html_entity_decode( $themeConfig['twitter_data'][$LANGUAGE_ID], ENT_QUOTES, 'UTF-8' ); ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</section>
	<?php } ?>
	
</div></section>







<footer class="aside footer">
<aside id="footer">
	
    <?php
    /**
    * Footer Top Position
    * $ospans allow overrides width of columns base on thiers indexs. format array( 1=> 3 )[value from 1->12]
    */
    $modules = $helper->getModulesByPosition( 'footer_top' ); 
    $ospans = array();

    if( count($modules) ){
        $cols = isset($themeConfig['block_footer_top'])&& $themeConfig['block_footer_top']?(int)$themeConfig['block_footer_top']:count($modules);   
        $class = $helper->calculateSpans( $ospans, $cols );
        ?>
        <div class="footer-top">
			<div class="container">
				<div class="footer-top-wrapper">
					<div class="footer-top-inner">
						<?php $j=1;foreach ($modules as $i =>  $module) { $i=$i+1; ?>
						<?php if( $i%$cols == 1 || count($modules)==1 ){  $j=1;?><div class="row-fluid"><?php } ?>  
						<div class="<?php echo $class[$j];?>"><?php echo $module; ?></div>
						<?php if( $i%$cols == 0 || $i==count($modules) ){ ?></div><?php } ?>    
						<?php  $j++;  } ?>
					</div>
				</div>
			</div>
        </div>
    <?php } else { ?>
		<div class="footer-top">
			<div class="container">
				<div class="footer-top-wrapper">
					<div class="footer-top-inner">
						<div class="row-fluid">
							<?php if( isset($themeConfig['social_data'][$LANGUAGE_ID]) ) { ?>
							<section class="span4">								
								<?php echo html_entity_decode( $themeConfig['social_data'][$LANGUAGE_ID], ENT_QUOTES, 'UTF-8' ); ?>
							</section>
							<?php } ?>
							
							<?php if( isset($themeConfig['newsletter_data'][$LANGUAGE_ID]) ) { ?>
							<section class="span8">								
								<?php echo html_entity_decode( $themeConfig['newsletter_data'][$LANGUAGE_ID], ENT_QUOTES, 'UTF-8' ); ?>
							</section>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>


    <?php
    /**
    * Footer Center Position
    * $ospans allow overrides width of columns base on thiers indexs. format array( 1=> 3 )[value from 1->12]
    */
    $modules = $helper->getModulesByPosition( 'footer_center' ); 
    $ospans = array();

    if( count($modules) ){
        $cols = isset($themeConfig['block_footer_center'])&& $themeConfig['block_footer_center']?(int)$themeConfig['block_footer_center']:count($modules);
        $class = $helper->calculateSpans( $ospans, $cols );
        ?>
        <div class="footer-center foot-pack">
            <div class="container">
                <?php $j=1;foreach ($modules as $i =>  $module) { $i=$i+1; ?>
                <?php if( $i%$cols == 1 || count($modules)==1 ){  $j=1;?><div class="row-fluid"><?php } ?>  
                <div class="<?php echo $class[$j];?>"><?php echo $module; ?></div>
                <?php if( $i%$cols == 0 || $i==count($modules) ){ ?></div><?php } ?>    
                <?php  $j++;  } ?>
            </div>
        </div>
        <?php } elseif((isset($themeConfig['enable_footer_center'])&&$themeConfig['enable_footer_center'])) { ?>
        <div class="footer-center foot-pack">
            <div class="container">                
                <div class="row-fluid">  					
					<?php if( isset($themeConfig['shop_map_data'][$LANGUAGE_ID]) ) { ?>
					<nav class="column span3">
						<h3><span><?php echo $this->language->get('text_shop_map'); ?></span></h3>
						<?php echo html_entity_decode( $themeConfig['shop_map_data'][$LANGUAGE_ID], ENT_QUOTES, 'UTF-8' ); ?>
					</nav>
					<?php } ?>
					
                    <nav class="column span2">
                        <h3><?php echo $text_account; ?></h3>
                        <ul class="account list-link">
                            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                            <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
                            <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
                        </ul>
                    </nav>                   
            
                    <nav class="column span2">
                        <h3><?php echo $text_extra; ?></h3>
                        <ul class="extra list-link">
                            <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
                            <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
                            <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
                            <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
                        </ul>
                    </nav>            
                
                    <nav class="column span2">
                        <h3><?php echo $text_service; ?></h3>
                        <ul class="service list-link">
                            <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
                            <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
                            <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
                        </ul>
                    </nav>
					
					<?php if ($informations) { ?>
						<nav class="column span3">
							<h3><?php echo $text_information; ?></h3>
							<ul class="infor-site list-link">
								<?php foreach ($informations as $information) { ?>
								<li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
								<?php } ?>
							</ul>
						</nav>
					<?php } ?>				
                </div>
            </div>
        </div>
		
    <?php  } ?>	
	
	
	<?php
    /**
    * Footer Bottom Position
    * $ospans allow overrides width of columns base on thiers indexs. format array( 1=> 3 )[value from 1->12]
    */
    $modules = $helper->getModulesByPosition( 'footer_bottom' ); 
    $ospans = array();    

    if( count($modules) ){
        $cols = isset($themeConfig['block_footer_bottom'])&& $themeConfig['block_footer_bottom']?(int)$themeConfig['block_footer_bottom']:count($modules);  
        $class = $helper->calculateSpans( $ospans, $cols );
        ?>
        <div class="footer-bottom">
            <div class="container">
                <div class="content">
					<?php $j=1;foreach ($modules as $i =>  $module) { $i=$i+1; ?>
					<?php if( $i%$cols == 1 || count($modules)==1 ){  $j=1;?><div class="row-fluid"><?php } ?>  
					<div class="<?php echo $class[$j];?>"><?php echo $module; ?></div>
					<?php if( $i%$cols == 0 || $i==count($modules) ){ ?></div><?php } ?>    
					<?php  $j++;  } ?>
				</div>
            </div>
        </div>
		
    <?php } ?>
	
</aside>

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->

<p id="back-top"> <a href="#top"><span></span></a> </p>

<aside id="powered">
    <div class="container">
        <?php if ($informations) { ?>
        <nav>            
            <ul class="info clearfix">
                <?php foreach ($informations as $information) { ?>
                <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                <?php } ?>
            </ul>
        </nav>
        <?php } ?>

        <address>
            <?php if( isset($themeConfig['enable_custom_copyright']) && $themeConfig['enable_custom_copyright'] ) { ?>
				<?php echo $themeConfig['copyright'];?>
            <?php } else { ?>
				<?php echo $powered; ?>
            <?php } ?>
        <!--    by <a href="http://www.pavothemes.com" target="_blank" title="pavothemes - opencart themes clubs">Pavothemes.com</a> All reversed.-->
        </address>        
    </div>
</aside>
</footer>

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
<?php if( isset($themeConfig['enable_paneltool']) && $themeConfig['enable_paneltool'] ){  ?>
	<?php include( dirname(__FILE__).'/addon/panel.tpl' );?>
<?php } ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("ul li:first-child").addClass('first');
		$("ul li:last-child").addClass('last');		
		$("#image-additional a:last-child").addClass('last');
					
		$("#back-top").hide();

		$(function(){
			$(window).scroll(function(){
				if($(this).scrollTop()>100){
					$('#back-top').fadeIn();
				}else{
					$('#back-top').fadeOut();
				}
			});

			$('#back-top a').click(function(){
				$('body,html').animate({scrollTop:0},800);
				return false;
			});
		});
		
		$(function(){
			$('#header .links li').last().addClass('last');
			$('.breadcrumb a').last().addClass('last');
			$('.cart tr').eq(0).addClass('first');														  
		});		
		
	});
</script>

</div>
</body>
</html>