<?php
/**
 * The template for displaying search forms in StagFramework
 *
 * @package StagFramework
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php _ex( 'Search', 'label', 'stag' ); ?></span>
		<input required type="search" class="search-field" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php _ex( 'Search for:', 'label', 'stag' ); ?>">
	</label>
	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'stag' ); ?>">
</form>
