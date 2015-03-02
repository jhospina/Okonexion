<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package StagFramework
 */
?>
	</div><!-- #content -->

	<?php if ( 'on' === stag_get_option('portfolio_show_portfolio_cta') && ( is_page_template( 'template-portfolio.php' ) || is_page_template( 'template-portfolio-filterable.php' ) ) ) : ?>
	<section class="portfolio-cta">
		<div class="inside">
			<div class="grids">
				<div class="grid-7">
					<h2><?php echo stag_get_option('portfolio_cta_text'); ?></h2>
				</div>
				<div class="grid-5">
					<a href="<?php echo esc_url( stag_get_option('portfolio_cta_button_link') ); ?>" class="button"><?php echo esc_attr( stag_get_option('portfolio_cta_button_text') ); ?></a>
				</div>
			</div>
		</div>
	</section><!-- .portfolio-cta -->
	<?php endif; ?>

	<footer class="footer-widget-area">
		<div class="inside">
			<div class="grids">
				<?php dynamic_sidebar( 'sidebar-footer' ); ?>
			</div>
		</div>
	</footer>
	
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<div class="grids">
				<div class="grid-6">
					<?php echo stag_get_option('general_footer_text_left'); ?>
				</div>
				<div class="grid-6">
					<?php echo stag_get_option('general_footer_text_right'); ?>
				</div>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
