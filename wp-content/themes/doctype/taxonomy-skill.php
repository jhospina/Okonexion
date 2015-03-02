<?php get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<header class="page-header page-header--portfolio">
			<h1 class="blog-title"><span><?php echo single_cat_title('', false); ?></span></h1>
		</header>

		<section class="grids portfolio-items">
			
			<?php if( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						if( ! has_post_thumbnail() ) continue;

						get_template_part( 'content', 'portfolio' );
					?>

				<?php endwhile; ?>

			<?php else: ?>

				<?php get_template_part( 'no-results', 'archive' ); ?>

			<?php endif; ?>

		</section>
	
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
