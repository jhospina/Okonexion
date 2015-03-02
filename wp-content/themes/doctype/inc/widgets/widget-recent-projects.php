<?php
add_action('widgets_init', create_function('', 'return register_widget("stag_widget_recent_projects");'));

class stag_widget_recent_projects extends WP_Widget{
    function stag_widget_recent_projects(){
        $widget_ops = array('classname' => 'section-recent-projects', 'description' => __('Displays recent portfolio items.', 'stag'));
        $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'stag_widget_recent_projects');
        $this->WP_Widget('stag_widget_recent_projects', __('Section: Recent Projects', 'stag'), $widget_ops, $control_ops);
    }

    function widget($args, $instance){
        extract($args);

        // VARS FROM WIDGET SETTINGS
        $title = apply_filters('widget_title', $instance['title'] );
        $subtitle = $instance['subtitle'];
        $button_text = $instance['button_text'];
        $button_link = $instance['button_link'];
        $post_count = $instance['post_count'];

        echo $before_widget;

        ?>

        <?php if( $title ) : ?>
        <header class="page-header page-header--portfolio">
            <h2 class="blog-title"><span><?php echo $title; ?></span></h2>
            <p class="blog-subtitle"><?php echo $subtitle; ?></p>
        </header><!-- .entry-header -->
        <?php endif; ?>

        <div class="grids portfolio-items">
            <?php

            $args = array(
              'post_type'      => 'portfolio',
              'posts_per_page' => $post_count,
              'orderby'        => 'date'
            );

            $the_query = new WP_Query( $args );

            if( $the_query->have_posts() ) :
            while( $the_query->have_posts() ): $the_query->the_post();

            if( ! has_post_thumbnail() ) continue;

            get_template_part( 'content', 'portfolio' );
            
            endwhile;
            endif;

            wp_reset_postdata();

            ?>
        </div>

        <?php

        if($button_link != ''){
          ?>
          <a href="<?php echo esc_url( $button_link ); ?>" class="button portfolio-button"><?php echo esc_attr( $button_text ); ?></a>
          <?php
        }

        echo $after_widget;

    }

    function update($new_instance, $old_instance){
        $instance = $old_instance;

        // STRIP TAGS TO REMOVE HTML
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['subtitle'] = strip_tags($new_instance['subtitle']);
        $instance['button_text'] = strip_tags($new_instance['button_text']);
        $instance['button_link'] = strip_tags($new_instance['button_link']);
        $instance['post_count'] = strip_tags($new_instance['post_count']);

        return $instance;
    }

    function form($instance){
        $defaults = array(
            /* Deafult options goes here */
            'title' => 'Recent Projects',
            'subtitle' => 'here is some recent work I have done for my clients',
            'button_text' => 'I like them. Show me more!',
            'button_link' => '',
            'post_count' => 3,
        );

        $instance = wp_parse_args((array) $instance, $defaults);

    /* HERE GOES THE FORM */
    ?>

    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'stag'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Subtitle:', 'stag'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'subtitle' ); ?>" name="<?php echo $this->get_field_name( 'subtitle' ); ?>" value="<?php echo $instance['subtitle']; ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('button_link'); ?>"><?php _e('Portfolio Link:', 'stag'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'button_link' ); ?>" name="<?php echo $this->get_field_name( 'button_link' ); ?>" value="<?php echo $instance['button_link']; ?>" />
        <span class="description"><?php _e('Enter the portfolio page URL.', 'stag'); ?></span>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Button Text:', 'stag'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo $instance['button_text']; ?>" />
        <span class="description"><?php _e('Enter text for the portfolio button.', 'stag'); ?></span>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('Post Count:', 'stag'); ?></label>
        <input type="number" step="3" class="widefat" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" value="<?php echo $instance['post_count']; ?>" />
        <span class="description"><?php _e('Enter the number of recent portfolio items to display at homepage.', 'stag'); ?></span>
    </p>

    <?php
  }
}

?>
