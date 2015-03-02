<?php

$inc = get_template_directory() . '/inc/';

/**
 * Load Theme Options
 */

require_once( $inc . 'options/general-settings.php' );
require_once( $inc . 'options/styling-options.php' );
require_once( $inc . 'options/blog-settings.php' );
require_once( $inc . 'options/portfolio-settings.php' );

require_once( $inc . 'meta/page-meta.php' );
require_once( $inc . 'meta/portfolio-meta.php' );

require_once( $inc . 'widgets/widget-contact.php' );
require_once( $inc . 'widgets/widget-cta.php' );
require_once( $inc . 'widgets/widget-hero.php' );
require_once( $inc . 'widgets/widget-intro.php' );
require_once( $inc . 'widgets/widget-recent-projects.php' );
require_once( $inc . 'widgets/widget-static-content.php' );
