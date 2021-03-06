<?php
/**
 * Template Name: Portfolio - Filterable
 * Description: Template to display filterable portfolio items
 */

get_header();

$portfolio_title    = stag_get_option( 'portfolio_title' );
$portfolio_subtitle = stag_get_option( 'portfolio_subtitle' );

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<header class="page-header page-header--portfolio">
				<?php if( ! empty( $portfolio_title ) ) echo "<h1 class='blog-title'><span>{$portfolio_title}</span></h1>"; ?>
				<?php if( ! empty( $portfolio_subtitle ) ) echo "<p class='blog-subtitle'>{$portfolio_subtitle}</p>"; ?>
			</header>

			<ul class="portfolio-filter">
				<li class="button filter" data-filter="all"><?php _e('All', 'stag'); ?></li>
				<?php

				$terms = get_terms('skill');
				$count = count($terms);
				$i = 0;

				if($count > 0){
				    foreach($terms as $term){
				        echo "<li class='button filter' data-filter='{$term->slug}'>{$term->name}</li>";
				    }
				}

				?>
			</ul>

			<section id="portfolio-filter" class="grids portfolio-items">

				<?php

				$args = array(
				  'post_type' => 'portfolio',
				  'posts_per_page' => -1,
				);

				$the_query = new WP_Query( $args );

				if( $the_query->have_posts() ) :
				while( $the_query->have_posts() ): $the_query->the_post();

				if( ! has_post_thumbnail() ) continue;

				get_template_part( 'content', 'portfolio' );
				
				endwhile;
				endif;

				wp_reset_postdata();

				?>
				
			</section>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
