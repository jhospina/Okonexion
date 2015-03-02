<?php

add_action( 'admin_init', 'stag_portfolio_settings' );

function stag_portfolio_settings(){
	$settings['description'] = __( 'Customize your portfolio settings.', 'stag' );

	$settings[] = array(
	    'title' => __( 'Portfolio Title', 'stag' ),
	    'desc'  => __( 'Enter the default title for the portfolio page template header section.', 'stag' ),
	    'type'  => 'text',
	    'id'    => 'portfolio_title',
	    'val'   => 'My Portfolio'
	);

	$settings[] = array(
	    'title' => __( 'Portfolio Subtitle', 'stag' ),
	    'desc'  => __( 'Enter the default subtitle for the portfolio page template header section.', 'stag' ),
	    'type'  => 'text',
	    'id'    => 'portfolio_subtitle',
	    'val'   => 'Here is some recent work I have done for my clients'
	);

	$settings[] = array(
	    'title' => __( 'Related Project Text', 'stag' ),
	    'desc'  => __( 'Enter the text for related projects.', 'stag' ),
	    'type'  => 'text',
	    'id'    => 'portfolio_related_project_text',
	    'val'   => 'Other Projects'
	);

	$settings[] = array(
	    'title' => __( 'Show Related Projects?', 'stag' ),
	    'desc'  => __( 'Enable to show related projects on portfolio single page.', 'stag' ),
	    'type'  => 'checkbox',
	    'id'    => 'portfolio_show_related_projects',
	    'val'   => 'on',
	);

	$settings[] = array(
	    'title' => __( 'Show Portfolio CTA?', 'stag' ),
	    'desc'  => __( 'Enable to show call to action on portfolio page.', 'stag' ),
	    'type'  => 'checkbox',
	    'id'    => 'portfolio_show_portfolio_cta',
	    'val'   => 'on',
	);

	$settings[] = array(
	    'title' => __( 'CTA Text', 'stag' ),
	    'desc'  => __( 'Enter the text for call to action on portfolio page.', 'stag' ),
	    'type'  => 'text',
	    'id'    => 'portfolio_cta_text'
	);

	$settings[] = array(
	    'title' => __( 'CTA Button Text', 'stag' ),
	    'desc'  => __( 'Enter the text for call to action button on portfolio page.', 'stag' ),
	    'type'  => 'text',
	    'id'    => 'portfolio_cta_button_text'
	);

	$settings[] = array(
	    'title' => __( 'CTA Button Link', 'stag' ),
	    'desc'  => __( 'Enter the url for call to action button link on portfolio page.', 'stag' ),
	    'type'  => 'text',
	    'id'    => 'portfolio_cta_button_link'
	);

	stag_add_framework_page( __( 'Portfolio Settings', 'stag' ), $settings, 20 );
}
