<?php
/**
 * Template Name: Archives
 */

$page_subtitle = get_post_meta( get_the_ID(), '_stag_page_subtitle', true );

get_header() ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<header class="page-header">
				<h1 class="blog-title"><?php the_title(); ?></h1>
				<?php if( ! empty( $page_subtitle ) ) echo "<p class='blog-subtitle'>{$page_subtitle}</p>"; ?>
			</header>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
