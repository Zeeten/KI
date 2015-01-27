<?php 
/******************************************************
 * @package Pav Opencart Theme Framework for Opencart 1.5.x
 * @version 1.0
 * @author http://www.pavothemes.com
 * @copyright	Copyright (C) Feb 2013 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
	$themeConfig = $this->config->get( 'themecontrol' );
	$themeName = basename( dirname(dirname(dirname(__FILE__))) );
	require_once( DIR_TEMPLATE.$this->config->get('config_template')."/template/libs/module.php" );
	$helper = ThemeControlHelper::getInstance( $this->registry, $themeName );

	/* Add scripts files */
	$helper->addScript( 'catalog/view/javascript/jquery/jquery-1.7.1.min.js' );
	$helper->addScript( 'catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js' );
	$helper->addScript( 'catalog/view/javascript/common.js' );
	$helper->addScript( 'catalog/view/theme/'.$themeName.'/javascript/common.js' );
	$helper->addScript( 'catalog/view/javascript/jquery/bootstrap/bootstrap.min.js' );


//	echo '<pre>'.print_r( $themeConfig, 1 );die;
?>
<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
<!-- Mobile viewport optimized: h5bp.com/viewport -->
<meta name="viewport" content="width=device-width">
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/layout.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/stylesheet.css" />
<style type="text/javascript">
	<?php if( $themeConfig['theme_width'] &&  $themeConfig['theme_width'] != 'auto' ) { ?>
		#page-container .container{max-width:<?php echo $themeConfig['theme_width'];?>; width:auto;}
	<?php } ?>
	
	<?php if( isset($themeConfig['use_custombg']) && $themeConfig['use_custombg'] ) {	?>
		body{
			background:url( "image/<?php echo $themeConfig['bg_image'];?>") <?php echo $themeConfig['bg_repeat'];?>  <?php echo $themeConfig['bg_position'];?> !important;
		}
	<?php } ?>
	
	<?php 
		if( isset($themeConfig['custom_css'])  && !empty($themeConfig['custom_css']) ){
			echo trim( html_entity_decode($themeConfig['custom_css']) );
		}
	?>
</style>
<?php 
	if( isset($themeConfig['enable_customfont']) && $themeConfig['enable_customfont'] ){
	$css=array();
	$link = array();
	for( $i=1; $i<=3; $i++ ){
		if( trim($themeConfig['google_url'.$i]) && $themeConfig['type_fonts'.$i] == 'google' ){
			$link[] = '<link rel="stylesheet" type="text/css" href="'.trim($themeConfig['google_url'.$i]) .'"/>';
			$themeConfig['normal_fonts'.$i] = $themeConfig['google_family'.$i];
		}
		if( trim($themeConfig['body_selector'.$i]) && trim($themeConfig['normal_fonts'.$i]) ){
			$css[]= trim($themeConfig['body_selector'.$i])." {font-family:".str_replace("'",'"',htmlspecialchars_decode(trim($themeConfig['normal_fonts'.$i])))."}\r\n"	;
		}
	}
	echo implode( "\r\n",$link );
?>
<style type="text/css">
	<?php echo implode("\r\n",$css);?>
</style>
<?php } else { ?>
<link href='http://fonts.googleapis.com/css?family=Arvo:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<?php } ?>
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<?php if( $helper->getParam('skin') &&  $helper->getParam('skin') != 'default' ){ ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/skins/<?php echo  $helper->getParam('skin');?>/stylesheet/stylesheet.css" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/font-awesome.min.css" />
<?php if( isset($themeConfig['responsive']) && $themeConfig['responsive'] ){ ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/theme-responsive.css" />
<?php } ?>
<?php if( $direction == 'rtl' ) { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/theme-rtl.css" />
<?php } ?>

<?php foreach( $helper->getScriptFiles() as $script )  { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>

<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>

<?php if( isset($themeConfig['custom_javascript'])  && !empty($themeConfig['custom_javascript']) ){ ?>
	<script type="text/javascript">
	<!--
		$(document).ready(function() {
			<?php echo html_entity_decode(trim( $themeConfig['custom_javascript']) ); ?>
		});
	//-->
	</script>
<?php }	?>

<!--[if IE 8]>         
	<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/ie8.css" />
<![endif]-->


<!--[if lt IE 9]>
<?php if( isset($themeConfig['load_live_html5'])  && $themeConfig['load_live_html5'] ) { ?>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<?php } else { ?>
<script src="catalog/view/javascript/html5.js"></script>
<?php } ?>
<![endif]-->

<?php if ( isset($stores) && $stores ) { ?>
<script type="text/javascript">
<!--
	$(document).ready(function() {
		<?php foreach ($stores as $store) { ?>
			$('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
		<?php } ?>
	});
//-->
</script>
<?php } ?>


<?php echo $google_analytics; ?>

</head>
<body class="fs<?php echo $themeConfig['fontsize'];?> <?php echo $helper->getPageClass();?> <?php echo $helper->getParam('body_pattern','');?>">
	<div id="page-container">	
		<header id="header" class="pav-header">
			<?php if( isset($themeConfig['enable_advertising_top']) && $themeConfig['enable_advertising_top'] ) { ?>
			<div id="topbar" class="hidden-phone topbar">
				<div class="container"><?php echo html_entity_decode($themeConfig['advertising_top'], ENT_QUOTES, 'UTF-8'); ?></div>
			</div>	
			<?php } ?>
			<div id="header-top" class="pav-header-top">
				<div class="container">
					<div class="row-fluid">
						<div class="span8">							
							<nav id="links" class="pull-left">
								<ul class="clearfix links">
									<?php /* <li class="first"><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a></li> */ ?>
									<li>										
										<a href="<?php echo $account; ?>" class="account">
											<i class="pavicon-account"></i>
											<?php echo $text_account; ?>
										</a>
									</li>
									<li>											
										<a href="<?php echo $wishlist; ?>" id="wishlist-total" class="wishlist">
											<i class="pavicon-wishlist-top"></i>	
											<?php echo $text_wishlist; ?>
										</a>
									</li>	
									<li>										
										<a href="<?php echo $checkout; ?>" class="checkout">
											<i class="pavicon-checkout"></i>	
											<?php echo $text_checkout; ?>
										</a>
									</li>								
									<li>										
										<a href="<?php echo $shopping_cart; ?>" class="shopping">
											<i class="icon-shopping-cart"></i>	
											<?php echo $text_shopping_cart; ?>
										</a>
									</li>									
								</ul>
							</nav>
							<p id="welcome" class="pull-left">
								<?php if (!$logged) { ?>
									<?php echo $text_welcome; ?>
								<?php } else { ?>
									<?php echo $text_logged; ?>
								<?php } ?>
							</p>
						</div>
						<div class="span4">
							<?php echo $language; ?>
							<!-- <span class="pull-right">|</span> -->							
							<?php echo $currency; ?>
						</div>
					</div>
				</div>
			</div>

			<div id="header-center" class="pav-header-center">
				<div id="mainnav">
					<div class="container">
						<?php
						/**
						 * Main Menu modules
						 */
						$modules = $helper->getModulesByPosition( 'mainmenu' );
						if( count($modules) ){
							?>

							<?php foreach ($modules as $module) { ?>
							<?php echo $module; ?>
							<?php } ?>

							<?php } elseif ($categories) { ?>
							<nav id="mainmenu">
								<div class="navbar">
									<div class="hidden-desktop wrapper-nav-title">
										<a class="btn btn-navbar bg-color" data-toggle="collapse" data-target=".nav-collapse">
											<span class="icon-bar is-white"></span>
											<span class="icon-bar is-white"></span>
											<span class="icon-bar is-white"></span>											
										</a>
										<span class="hidden-desktop pull-left h1">Navigation</span>
									</div>

									<div class="navbar-inner">
										<div class="nav-collapse collapse">
											<ul class="nav megamenu">
												<li class="home">
													<a class="dropdown-toggle" href="<?php echo $home; ?>"><span><?php echo $text_home; ?></span></a>
												</li>
												<?php foreach ($categories as $category) { ?>
												<?php if ($category['children']) { ?>
												<li class="parent dropdown deeper ">
													<a href="<?php echo $category['href'];?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?>
														<b class="caret"></b>
													</a>
												<?php } else { ?>
												<li>
													<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
													<?php } ?>
													<?php if ($category['children']) { ?>
													<ul class="dropdown-menu">
														<li>													
															<?php for ($i = 0; $i < count($category['children']);) { ?>
															<ul class="dropdown-menu-child">
																<?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
																<?php for (; $i < $j; $i++) { ?>
																<?php if (isset($category['children'][$i])) { ?>
																<li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
																<?php } ?>
																<?php } ?>
															</ul>
															<?php } ?>
														</li>		
													</ul>
													<?php } ?>
												</li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
							</nav>
						<?php } ?>
					</div>
				</div>				
			</div>

			<div id="header-bottom" class="pav-header-bottom">
				<div class="wrapper-header-bottom">
					<div class="container">
						<div class="row-fluid">
							<div class="span5">
								<?php if ($logo) { ?>
									<div id="logo" class="pull-left"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
								<?php } ?>
							</div>
							<div class="span7">
								<div class="header-right clearfix">
									<?php echo $cart; ?>		
									<div id="search" class="pull-right">
										<input type="text" name="search" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>" />
										<div class="button-search pavicon-search">&nbsp;</div>
									</div>	
								</div>							
							</div>
						</div>
					</div>
				</div>
			</div>	
		</header>
				
		
		<?php
		/**
		 * Slideshow modules
		 */
		$modules = $helper->getModulesByPosition( 'slideshow' );
		if( $modules ){
			?>
			<section id="slideshow" class="pav-slideshow">
				<?php foreach ($modules as $module) { ?>
				<?php echo $module; ?>
				<?php } ?>
			</section>
		<?php } ?>

		
		<section class="container">
			<div id="main" class="aside">
			<?php
			/**
			* Showcase modules
			* $ospans allow overrides width of columns base on thiers indexs. format array( 1=> 3 )[value from 1->12]
			*/
			$modules = $helper->getModulesByPosition( 'showcase' );
			$ospans = array();

			if( count($modules) ){
				$cols = isset($config['block_showcase'])&& $config['block_showcase']?(int)$config['block_showcase']:count($modules);
				$class = $helper->calculateSpans( $ospans, $cols );
			?>
			<section class="pav-showcase" id="pavo-showcase">
				<?php $j=1;foreach ($modules as $i =>  $module) { $i=$i+1; ?>
				<?php if(  $cols > 1  && $i%$cols == 1 ){  $j=1;?><div class="row-fluid"><?php } ?>
				<div class="<?php echo $class[$j];?>"><?php echo $module; ?></div>
				<?php if(  $cols > 1  && ($i%$cols == 0 || $i==count($modules)) ){ ?></div><?php } ?>
				<?php  $j++;  } ?>
			</section>
			<?php } ?>
						

			<?php
			/**
			 * Promotion modules
			 * $ospans allow overrides width of columns base on thiers indexs. format array( 1=> 3 )[value from 1->12]
			 */
			$modules = $helper->getModulesByPosition( 'promotion' );
			$ospans = array();

			if( count($modules) ){
				$cols = isset($config['block_promotion'])&& $config['block_promotion']?(int)$config['block_promotion']:count($modules);	
				$class = $helper->calculateSpans( $ospans, $cols );
			?>
			<section class="pav-promotion" id="pav-promotion">
				<div class="promotion">
				
					<?php /*
					
					<?php $j=1;foreach ($modules as $i =>  $module) { $i=$i+1; ?>
					<?php if( $cols > 1  && $i%$cols == 1 ){  $j=1;?><div class="row-fluid"><?php } ?>
					<div class="<?php echo $class[$j];?>"><?php echo $module; ?></div>
					<?php if( $cols > 1 &&( $i%$cols == 0 || $i==count($modules)) ){ ?></div><?php } ?>
					<?php  $j++;  } ?>					
					
					*/ ?>
					
					<?php $j=1;foreach ($modules as $i =>  $module) {   ?>
					<?php if( $i++%$cols == 0 || count($modules)==1 ){  $j=1;?><div class="row-fluid"><?php } ?>	
					<div class="span<?php echo floor(12/$cols);?>"><?php echo $module; ?></div>
					<?php if( $i%$cols == 0 || $i==count($modules) ){ ?></div><?php } ?>	
					<?php  $j++;  } ?>
					
				</div>
			</section>
			<?php } ?>




			<section id="sys-notification">
							
				<?php if ($error) { ?>    
				    <div class="warning"><?php echo $error ?><img src="catalog/view/theme/<?php echo $themeName;?>/image/close.png" alt="" class="close" /></div>				    
				<?php } ?>

				<div id="notification"></div>
			</section>
			<section id="columns"><div class="row-fluid">