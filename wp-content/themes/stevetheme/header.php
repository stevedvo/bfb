<html>

	<head>
		<?php wp_head(); ?>
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url') ?>"/>
	</head>

	<body>
		<div id="wrapper">
			<div id="header">
				<h1><?php bloginfo('name'); ?></h1>
				<p><?php var_dump(bloginfo()->name); ?></p>
			</div><!-- #header -->
			
