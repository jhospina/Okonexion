<?php
/**
 * The template used for displaying portfolio content in single-portfolio.php
 *
 * @package StagFramework
 */
?>
<article id="portfolio-<?php the_ID(); ?>" <?php post_class('grid-4'); ?>>
	<figure>
		<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail('portfolio-thumbnail'); ?></a>
		<figcaption>
			<?php the_title('<h4>', '</h4>'); ?>
			<a href="<?php the_permalink(); ?>" rel="bookmark" class="button"><?php _e( 'Details', 'stag' ); ?></a>
		</figcaption>
	</figure>
</article><!-- #portfolio-<?php the_ID(); ?> -->
