<?php /* 
Template Name: Template Name Here
*/ ?> 

<?php get_header(); ?>

	<?php do_action( 'genesis_before_content_sidebar_wrap' ); ?>
	
	<div id="content-sidebar-wrap">
		
		<?php do_action( 'genesis_before_content' ); ?>
		
		<div id="content" class="hfeed">
			
			<?php do_action( 'genesis_before_loop' ); ?>
			<?php do_action( 'genesis_loop' ); ?>
			<?php do_action( 'genesis_after_loop' ); ?>
			
		</div><!-- end #content -->
		
		<?php do_action( 'genesis_after_content' ); ?>
	
	</div><!-- end #content-sidebar-wrap -->
	
	<?php do_action( 'genesis_after_content_sidebar_wrap' ); ?>

<?php get_footer(); ?>