<?php get_header() ?>

<div id="main">
	<div id="content">
		<?php if(have_posts()) : while (have_posts()) : the_post(); ?>
		<h1><?php the_title(); ?></h1>
		<h4>Posted on <?php the_time('F jS, Y'); ?></h4>
		<p><?php the_content(__('(more...)')); ?></p>
		<hr/> <?php endwhile; else: ?>
		<p><?php _e('Sorry, there are no posts available at this time.'); ?></p><?php endif; ?>		
	</div><!-- #content -->
	<?php get_sidebar(); ?>
</div><!-- #main -->
<div id="delimiter">
	
</div><!-- #delimiter -->
<?php get_footer(); ?>