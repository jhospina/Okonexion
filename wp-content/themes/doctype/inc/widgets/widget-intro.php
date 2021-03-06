<?php
add_action('widgets_init', create_function('', 'return register_widget("stag_widget_intro");'));

class stag_widget_intro extends WP_Widget{
    function stag_widget_intro(){
        $widget_ops = array('classname' => 'section-intro', 'description' => __('Displays a basic intro.', 'stag'));
        $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'stag_widget_intro');
        $this->WP_Widget('stag_widget_intro', __('Section: Intro', 'stag'), $widget_ops, $control_ops);
    }

    function widget($args, $instance){
        extract($args);

        // VARS FROM WIDGET SETTINGS
        $content = $instance['content'];

        echo $before_widget;
        ?>

        <h1><?php echo $content; ?></h1>

        <?php

        echo $after_widget;

    }

    function update($new_instance, $old_instance){
        $instance = $old_instance;

        // STRIP TAGS TO REMOVE HTML
        $instance['content'] = $new_instance['content'];

        return $instance;
    }

    function form($instance){
        $defaults = array(
            /* Deafult options goes here */
            'content' => __( 'I am a <span>dedicated</span> designer who enjoys <span>awesome</span> projects.', 'stag' ),
        );

        $instance = wp_parse_args((array) $instance, $defaults);

    /* HERE GOES THE FORM */
    ?>

    <p>
      <textarea rows="16" cols="20" name="<?php echo $this->get_field_name( 'content' ); ?>" id="<?php echo $this->get_field_id( 'content' ); ?>" class="widefat"><?php echo @$instance['content']; ?></textarea>
      <span class="description">
          <?php _e( 'Use &lt;span&gt; tag to highlight text.', 'stag' ); ?>
      </span>
    </p>

    <?php
  }
}
