<?php
/**
 * Template Name: Login
 */
get_header();
?>

<div id="content">
    
    <!-- BEGIN FEATURED IMAGE -->
		
		<?php if(has_post_thumbnail() && $gp_settings['show_image'] == "Show") { ?>
		
			<div class="post-thumbnail<?php if($gp_settings['image_wrap'] == "Enable") { ?> wrap<?php } ?>">
				<?php $image = aq_resize(wp_get_attachment_url(get_post_thumbnail_id(get_the_id())),  $gp_settings['image_width'], $gp_settings['image_height'], $gp_settings['hard_crop'], false, true); ?>
				<?php if(get_option($dirname.'_retina') == "0") { $retina = aq_resize(wp_get_attachment_url(get_post_thumbnail_id(get_the_id())),  $gp_settings['image_width']*2, $gp_settings['image_height']*2, $gp_settings['hard_crop'], true, true); } else { $retina = ""; } ?>
				<img src="<?php echo $image[0]; ?>" data-rel="<?php echo $retina; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" alt="<?php if(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)) { echo get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); } else { the_title_attribute(); } ?>" class="wp-post-image" />
			</div><?php if($gp_settings['image_wrap'] == "Disable") { ?><div class="clear"></div><?php } ?>
			
		<?php } ?>
		
		<!-- END FEATURED IMAGE -->
		
		
		<!-- BEGIN POST CONTENT -->
		
		<div id="post-content">
		
			<?php the_content(__('Read More &raquo;', 'gp_lang')); ?>
			
		</div>
    
    <?php
    require "appsthergo/Appsthergo.php";

    use Appsthergo\Appsthergo as Appsthergo;

$apps = new Appsthergo("JHSJDJSI", "ES");
    $apps->printFormLogin();
    ?>
</div>

<?php get_footer(); ?>
