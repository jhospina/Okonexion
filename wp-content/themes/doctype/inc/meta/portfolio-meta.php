<?php

add_action('add_meta_boxes', 'stag_metabox_portfolio');

function stag_metabox_portfolio(){

  $meta_box = array(
    'id' => 'stag-metabox-portfolio',
    'title' => __('Portfolio Settings', 'stag'),
    'description' => __('Here you can customize your project details.', 'stag'),
    'page' => 'portfolio',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => __('Project Images', 'stag'),
            'desc' => __('Choose project images, ideal size 1170px x unlimited.', 'stag'),
            'id' => '_stag_portfolio_images',
            'type' => 'images',
            'std' => __('Upload Images', 'stag')
        ),
        array(
            'name' => __('Subtitle', 'stag'),
            'desc' => __('Enter the subtitle for this portfolio item', 'stag'),
            'id' => '_stag_portfolio_subtitle',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => __('Client Name', 'stag'),
            'desc' => __('Enter the client name of the project', 'stag'),
            'id' => '_stag_portfolio_client',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => __('Project Date', 'stag'),
            'desc' => __('Choose the project date in MM/DD/YYYY format. E.g. 12/23/2012', 'stag'),
            'id' => '_stag_portfolio_date',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => __('Project URL', 'stag'),
            'desc' => __('Enter the project URL', 'stag'),
            'id' => '_stag_portfolio_url',
            'type' => 'text',
            'std' => ''
        ),
      )
    );
  stag_add_meta_box($meta_box);
}
