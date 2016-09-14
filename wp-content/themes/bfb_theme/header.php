<html>

	<head>
		<?php //wp_head(); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" type="text/css" href="../font-awesome-4.6.3/css/font-awesome.min.css"/>
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url') ?>"/>
		<script type="text/javascript" src="wp-content/themes/bfb_theme/js/bfb_theme.js"></script>
	</head>

	<body onload = init_bfb()>
		<div id="wrapper">
			<div id="header">
				<nav id="primary-navigation" class="primary-navigation site-navigation" role="navigation">
					<i id="menu-toggle" class="fa fa-bars"></i>
					<?php wp_nav_menu(array('menu_class'=>'nav-menu', 'menu_id'=>'primary-menu')); ?>
				</nav>
				<h1 id="site-header"><?php bloginfo('name'); ?></h1>
				<!-- <h2><?php //echo esc_html(get_bloginfo('description', 'display')); ?></h2> -->
				<?php wp_nav_menu(array('menu_class'=>'nav-menu', 'menu_id'=>'hidden-menu')); ?>

			</div><!-- #header -->
			
