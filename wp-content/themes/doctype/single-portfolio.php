<?php
/**
 * The Template for displaying all single portfolio posts.
 *
 * @package StagFramework
 */

get_header();

$subtitle    = esc_attr( get_post_meta( get_the_ID(), '_stag_portfolio_subtitle', true ) );
$client      = esc_attr( get_post_meta( get_the_ID(), '_stag_portfolio_client', true ) );
$date        = esc_attr( get_post_meta( get_the_ID(), '_stag_portfolio_date', true ) );
$project_url = esc_url( get_post_meta( get_the_ID(), '_stag_portfolio_url', true ) );
$image_ids   = explode(',', get_post_meta( get_the_ID(), '_stag_image_ids', true) );


?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="page-header page-header--portfolio">
					<h1 class="blog-title"><span><?php the_title(); ?></span></h1>
					<p class="blog-subtitle"><?php echo $subtitle; ?></p>
				</header><!-- .entry-header -->
				
				<?php if( $image_ids[0] !== "" ) : ?>
				<section class="project-images">
					<ul id="portfolio-slider" class="portfolio-slider">
						<?php

						foreach ( $image_ids as $image ) {
							if(!$image) continue;
							$src = wp_get_attachment_image_src( $image, 'full' );
							echo "<li><img src='{$src[0]}' width='{$src[1]}' height='{$src[2]}'></li>";
						}

						?>
					</ul>	
				</section>
				<?php endif; ?>

				<section class="entry-content-wrapper">
					<div class="entry-content">
						<div class="main-content">
							<?php the_content(); ?>
							<?php
								wp_link_pages( array(
									'before' => '<div class="page-links">' . __( 'Pages:', 'stag' ),
									'after'  => '</div>',
								) );
							?>
						</div><!-- .entry-content -->

						<aside class="project-meta">
							<li>
								<?php
								if( $project_url != '' ) :
									echo "<a class='button project-url' target='_blank' href='{$project_url}'>". __( 'Project URL', 'stag' ) ."</a>";
								endif;
								?>
							</li>

							<?php

							if( $client != '' ) :
								echo "<li><span>". __( 'Client:', 'stag' ) ."</span><p>{$client}</p></li>";
							endif;

							$categories_list = get_the_term_list( get_the_ID(), 'skill', '', ', ', '' );

							if( $categories_list ) :
								echo "<li><span>". __( 'Skills:', 'stag' ) ."</span><p>{$categories_list}</p></li>";
							endif;

							if( $date != '' ) :
								echo "<li><span>". __( 'Project Date:', 'stag' ) ."</span><p>{$date}</p></li>";
							endif;

							?>
						</aside>
					</div>
				</section>
			</article>

			<?php endwhile; // end of the loop. ?>

			<?php if( stag_get_option('portfolio_show_related_projects') == 'on' )  get_template_part( 'content', 'related-portfolio' ); ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
