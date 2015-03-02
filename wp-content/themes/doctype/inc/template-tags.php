<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package StagFramework
 */


if ( ! function_exists( 'stag_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function stag_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'stag' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'stag' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<?php
			global $is_retina;
			if( $is_retina ) {
				echo get_avatar( $comment, $size = '140' );
			} else {
				echo get_avatar( $comment, $size = '70' );
			}
		?>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php printf( __( '%s', 'stag' ), sprintf( '<span><cite class="fn">%s</cite></span>', get_comment_author_link() ) ); ?>
					<span>
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php echo human_time_diff( get_comment_time('U'), current_time('timestamp') ) .' '. __('ago', 'stag'); ?>
						</time>
					</span>
					<span><?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
					<?php edit_comment_link( __( 'Edit', 'stag' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'stag' ); ?></p>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

		</article><!-- .comment-body -->

	<?php
	endif;
}
endif;  // ends check for stag_comment()


if ( ! function_exists( 'stag_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function stag_content_nav( $nav_id ) {
	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';

	?>
	<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'stag' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'stag' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'stag' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="fa fa-chevron-left"></span>', 'stag' ) ); ?></div>
		<?php endif; ?>

		<?php

		$total_pages = $wp_query->max_num_pages;
		$big = 999999999;
		if($total_pages > 1){
			if ($total_pages > 1){
				$current_page = max(1, get_query_var('paged'));
				$return = paginate_links(array(
					'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format' => '/page/%#%',
					'current' => $current_page,
					'total' => $total_pages,
					'prev_next' => false
					));
				echo "<div class='pages'>{$return}</div>";
			}
		}else{
			return false;
		}

		?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( '<span class="fa fa-chevron-right"></span>', 'stag' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
	<?php
}
endif; // stag_content_nav


if ( ! function_exists( 'stag_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function stag_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date('m/d/Y') ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	printf( __( '<span class="posted-on">Published at %1$s</span>', 'stag' ),
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			$time_string
		)
	);

	$categories_list = get_the_category_list( __( ', ', 'stag' ) );
	if ( $categories_list ) : ?>
	<span class="cat-links">
		<?php printf( __( 'In Category %1$s', 'stag' ), $categories_list ); ?>
	</span>
	<?php endif;

	$tags_list = get_the_tag_list( '', __( ', ', 'stag' ) );
	if ( $tags_list ) : ?>
	<span class="tags-links">
		<?php printf( __( 'Tags: %1$s', 'stag' ), $tags_list ); ?>
	</span>
	<?php endif;

	if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
	<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'stag' ), __( '1 Comment', 'stag' ), __( '% Comments', 'stag' ) ); ?></span>
	<?php endif;

	edit_post_link( __( 'Edit', 'stag' ), '<span class="edit-link">', '</span>' );
}
endif;  // ends check for stag_posted_on()

/**
 * Returns true if a blog has more than 1 category
 */
function stag_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so stag_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so stag_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in stag_categorized_blog
 */
function stag_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'stag_category_transient_flusher' );
add_action( 'save_post',     'stag_category_transient_flusher' );
