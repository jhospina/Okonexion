<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package StagFramework
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if( has_post_thumbnail() ) : ?>
	<figure class="post-thumbnail">
		<?php the_post_thumbnail(); ?>
	</figure>
	<?php endif; ?>
	
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'stag' ),
				'after'  => '</div>',
			) );
		?>
		
		<?php if( is_page_template( 'template-archive.php' ) ) : ?>
		<div class="archive-lists">
			<?php the_widget( 'WP_Widget_Recent_Posts', array( 'title' =>  __( 'Last 10 Posts', 'stag' ) ) ); ?>

		    <?php the_widget( 'WP_Widget_Archives', array( 'title' =>  __( 'Archives by Month', 'stag' ), 'count' => 1 ) ); ?>
		    
		    <?php the_widget( 'WP_Widget_Categories', array( 'title' =>  __( 'Archives by Subject', 'stag' ), 'count' => 1 ) ); ?>

		    <?php the_widget( 'WP_Widget_Search' ); ?>
		</div><!-- .archive-lists -->
		<?php endif; ?>

	</div><!-- .entry-content -->
</article><!-- #post-## -->
