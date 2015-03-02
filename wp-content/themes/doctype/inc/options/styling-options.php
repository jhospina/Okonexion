<?php

add_action( 'admin_init', 'stag_styling_options' );

function stag_styling_options() {
	$settings['description'] = __( 'Configure the visual appearance of your theme, or insert any custom CSS.', 'stag' );

	$settings[] = array(
		'title' => __( 'Background Color', 'stag' ),
		'desc'  => __( 'Choose the background color of your site', 'stag' ),
		'type'  => 'color',
		'id'    => 'style_background_color',
		'val'   => '#ffffff'
	);

	$settings[] = array(
		'title' => __( 'Accent Color', 'stag' ),
		'desc'  => __( 'Choose the accent color of your site', 'stag' ),
		'type'  => 'color',
		'id'    => 'style_accent_color',
		'val'   => '#8bd3c1'
	);

	$settings[] = array(
		'title' => __( 'Body Font', 'stag' ),
		'desc'  => __( 'Quickly add a custom Google Font for body from <a href="//www.google.com/webfonts/" target="_blank">Google Font Directory.</a>
				   <code>Example font name: "Source Sans Pro"</code>, for including font weights type <code>Source Sans Pro:400,700,400italic</code>.', 'stag' ),
		'type'  => 'text',
		'id'    => 'style_body_font',
		'val'   => 'Lato:300,400,700,300italic'
	);

	$settings[] = array(
		'title' => __( 'Heading Font', 'stag' ),
		'desc'  => __( 'Quickly add a custom Google Font for heading from <a href="//www.google.com/webfonts/" target="_blank">Google Font Directory</a>.', 'stag' ),
		'type'  => 'text',
		'id'    => 'style_heading_font',
		'val'   => ''
	);

	$settings[] = array(
		'title' => __( 'Google Font Script', 'stag' ),
		'desc' => __( 'Choose the character sets you want for Google Web Font', 'stag' ),
		'type' => 'select',
		'id' => 'style_font_script',
		'val' => 'latin',
		'options' => array(
			'cyrillic' => __( 'Cyrillic', 'stag' ),
			'cyrillic-ext' => __( 'Cyrillic Extended', 'stag' ),
			'greek' => __( 'Greek', 'stag' ),
			'greek-ext' => __( 'Greek Extended', 'stag' ),
			'khmer' => __( 'Khmer', 'stag' ),
			'latin' => __( 'Latin', 'stag' ),
			'latin,latin-ext' => __( 'Latin Extended', 'stag' ),
			'vietnamese' => __( 'Vietnamese', 'stag' ),
		)
	);

	$settings[] = array(
		'title' => __( 'Custom CSS', 'stag' ),
		'desc'  => __( 'Quickly add some CSS to your theme by adding it to this block.', 'stag' ),
		'type'  => 'textarea',
		'id'  => 'style_custom_css',
	);

	stag_add_framework_page( __( 'Styling Options', 'stag' ), $settings, 5 );
}

/**
 * Custom Stylsheet output
 *
 * @since 1.0
 */
function stag_custom_css( $content ){
	$stag_values = get_option( 'stag_framework_values' );

	$accent = stag_get_option('style_accent_color');
	$body_bg = stag_get_option('style_background_color');
	$body_font = explode( ':', stag_get_option('style_body_font') );
	$heading_font = explode( ':', stag_get_option('style_heading_font') );

	if( $heading_font[0] != '' ) {
		$content .= "h1, h2, h3, h4, h5, h6, .stag-intro-text { font-family: '{$heading_font[0]}'; }";
	}


	$content .= '/* Theme Dependant CSS - Depending on chosen accent color */' . "\n";
	$content .= ".page-header--portfolio span, .related-projects span { background-color: {$body_bg}; }\n";
	$content .= "body { background-color: {$body_bg}; font-family: '{$body_font[0]}', 'Helvetica Neue', Helvetica, sans-serif; }\n";
	$content .= ".blog .hentry::before, .archive .hentry::before, .search .hentry::before { background-color: {$body_bg}; }";
	$content .= "a, .paging-navigation .current, .section-intro span, .archive-lists .widgettitle:before, .archive-lists .screen-reader-text:before, .paging-navigation a:hover, .widget_categories, .widget_archive, .archive-lists a:hover { color: {$accent}; }\n";
	$content .= ".paging-navigation .current, .paging-navigation a:hover{ border-color: {$accent}; }\n";
	$content .= ".button, button, input[type=submit], .navbar a:hover, .sub-menu, .sfHover a { background-color: {$accent}; }\n";
	$content .= "\n\n";

	if( array_key_exists( 'style_custom_css', $stag_values ) && $stag_values['style_custom_css'] != '' ){
		$content .= '/* Custom CSS */' . "\n";
		$content .= stripslashes($stag_values['style_custom_css']);
		$content .= "\n\n";
	}
	return $content;
}
add_filter( 'stag_custom_styles', 'stag_custom_css' );


function stag_google_font_url() {
	$fonts_url = '';
	$font_families = array();
	$heading_font = stag_get_option('style_heading_font');

	$font_families[] = 'Rokkitt:700';
	$font_families[] = stag_get_option('style_body_font');

	if( $heading_font != '' ) {
		$font_families[] = $heading_font;
	}

	$query_args = array(
		'family' => urlencode( implode( '|', array_filter($font_families) ) ),
		'subset' => urlencode( stag_get_option('style_font_script') )
	);

	$protocol = ( is_ssl() ) ? 'https:' : 'http:';

	$fonts_url = add_query_arg( $query_args, $protocol . "//fonts.googleapis.com/css" );

	return $fonts_url;
}

if( ! function_exists( 'stag_remove_trailing_char' ) ) {
/**
 * Check if there is any space
 */
function stag_remove_trailing_char( $string, $char = ' ' ) {
  $offset = strlen( $string ) - 1;
  $trailing_char = strpos( $string, $char, $offset );
  if( $trailing_char )
    $string = substr( $string, 0, -1 );
  return $string;
}
}

function stag_get_font_face( $option ) {
  $stack = null;
  if( $option !=  '') {
    $option = explode( ':', $option );
    $name = stag_remove_trailing_char( $option[0] );
    $stack = $name;
  } else {
    $stack = '';
  }
  return $stack;
}
