<?php
/**
 * Template Name: Widgetized
 *
 * @package StagFramework
 * @subpackage Doctype
 * @since 1.0.6
 */

get_header(); ?>

<div class="widgetized-sections">
	<?php
	
	while( have_posts() ): the_post();
		the_content();
	endwhile;

	?>
</div>

<?php get_footer(); ?>
