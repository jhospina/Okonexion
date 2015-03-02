<?php
/**
 * @package StagFramework
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-meta">
		<?php stag_posted_on(); ?>
	</div><!-- .entry-meta -->

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
	</div><!-- .entry-content -->
</article><!-- #post-## -->

<nav class="navigation single-page-navigation" role="navigation">
    <?php
    $prev = get_adjacent_post(false,'',false);
    $next = get_adjacent_post(false,'',true);
    ?>
    <div class="nav-links grids">
        <?php if( is_object($prev) && $prev->ID != get_the_ID()): ?>
        <div class="nav-previous grid-6">
            <a href="<?php echo get_permalink($prev->ID); ?>"><i class="fa fa-chevron-left"></i> <?php _e( 'Previous Post', 'stag' ) ?></a>
        </div>
        <?php endif; ?>

        <?php if( is_object($next) && $next->ID != get_the_ID()): ?>
        <div class="nav-next grid-6">
            <a href="<?php echo get_permalink($next->ID); ?>"><?php _e( 'Next Post', 'stag' ) ?> <i class="fa fa-chevron-right"></i></a>
        </div>
        <?php endif; ?>
    </div>
</nav>
