<?php
add_action('widgets_init', create_function('', 'return register_widget("stag_widget_hero");'));

class stag_widget_hero extends WP_Widget{
    function stag_widget_hero(){
        $widget_ops = array('classname' => 'section-hero', 'description' => __('Displays content like a boss.', 'stag'));
        $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'stag_widget_hero');
        $this->WP_Widget('stag_widget_hero', __('Section: Hero', 'stag'), $widget_ops, $control_ops);
    }

    function widget($args, $instance){
        extract($args);

        // VARS FROM WIDGET SETTINGS
        $title = apply_filters('widget_title', $instance['title'] );
        $bg_image = $instance['bg_image'];
        $page = $instance['page'];
        $hero_button_text = $instance['hero_button_text'];
        $hero_button_link = $instance['hero_button_link'];

        echo $before_widget;

        ?>

        <?php if( $hero_button_text != '' ) : ?>
        <a href="<?php echo esc_url($hero_button_link); ?>" class="button"><?php echo $hero_button_text; ?></a>
        <?php endif; ?>

        <div class="hero-content-wrapper" data-background-image="<?php echo $bg_image; ?>">
            <div class="hero-content-inner">

                <?php

                if( $title ) {
                    echo $before_title . htmlspecialchars_decode($title) . $after_title;
                    echo '<span class="inner-section-divider"><i class="fa fa-bookmark-o"></i></span>';
                }

                ?>

                <div class="hero-content entry-content">
                    <?php
                        $the_page = get_page($page);

                        $query_args = array(
                            'page_id' => $page
                        );

                        $query = new WP_Query($query_args);

                        while ( $query->have_posts() ) : $query->the_post();

                            global $more;
                            $more = false;
                            the_content( __('Read More&hellip;', 'stag') );
                            wp_link_pages(array('before' => '<p><strong>'.__('Pages:', 'stag').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number'));

                        endwhile;

                        wp_reset_query();

                    ?>
                </div>
            </div>
        </div>

        <?php

        echo $after_widget;

    }

    function update($new_instance, $old_instance){
        $instance = $old_instance;

        // STRIP TAGS TO REMOVE HTML
        $instance['title'] = $new_instance['title'];
        $instance['bg_image'] = esc_url($new_instance['bg_image']);
        $instance['page'] = $new_instance['page'];
        $instance['hero_button_text'] = strip_tags($new_instance['hero_button_text']);
        $instance['hero_button_link'] = esc_url($new_instance['hero_button_link']);

        return $instance;
    }

    function form($instance){
        $defaults = array(
            /* Deafult options goes here */
            'title'            => 'Static Content',
            'bg_image'         => '',
            'page'             => 0,
            'hero_button_text' => '',
            'hero_button_link' => '',
        );

        $instance = wp_parse_args((array) $instance, $defaults);

    /* HERE GOES THE FORM */
    ?>

    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'stag'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('page'); ?>"><?php _e('Select Page:', 'stag'); ?></label>

        <select id="<?php echo $this->get_field_id( 'page' ); ?>" name="<?php echo $this->get_field_name( 'page' ); ?>" class="widefat">
        <?php

        $args = array(
            'sort_order'  => 'ASC',
            'sort_column' => 'post_title',
        );

        $pages = get_pages($args);

        foreach($pages as $paged){ ?>
            <option value="<?php echo $paged->ID; ?>" <?php if($instance['page'] == $paged->ID) echo "selected"; ?>><?php echo $paged->post_title; ?></option>
        <?php }

        ?>
        </select>
        <span class="description">This page will be used to display the content.</span>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('bg_image'); ?>"><?php _e('Background Image URL:', 'stag'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'bg_image' ); ?>" name="<?php echo $this->get_field_name( 'bg_image' ); ?>" value="<?php echo $instance['bg_image']; ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('hero_button_text'); ?>"><?php _e('Hero Button Text:', 'stag'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'hero_button_text' ); ?>" name="<?php echo $this->get_field_name( 'hero_button_text' ); ?>" value="<?php echo $instance['hero_button_text']; ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('hero_button_link'); ?>"><?php _e('Hero Button Link:', 'stag'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'hero_button_link' ); ?>" name="<?php echo $this->get_field_name( 'hero_button_link' ); ?>" value="<?php echo $instance['hero_button_link']; ?>" />
    </p>

    <?php
  }
}
