<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Full Content Template
 *
   Template Name:  Full Width Page (no sidebar)
 *
 * @file           full-width-page.php
 */
?>
<?php
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_full_width_page_loop' );
global $wp_query;
?>

<?php //get_header(); 
function bh_full_width_page_loop(){
?>
<div class="inner">
        <div id="content-full" class="grid col-940">
        
<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
        
        <?php 
		//replace with genesis breadcrumbs
		//$options = get_option('responsive_theme_options');
		// if ($options['breadcrumb'] == 0): 
		// echo responsive_breadcrumb_lists(); 
       //  endif;
	   ?>
        
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="post-title"><?php the_title(); ?></h1>
 
                <?php if ( comments_open() ) : ?>               
                <div class="post-meta">
                <?php responsive_post_meta_data(); ?>
                
				    <?php if ( comments_open() ) : ?>
                        <span class="comments-link">
                        <span class="mdash">&mdash;</span>
                    <?php comments_popup_link(__('No Comments &darr;', 'minimum'), __('1 Comment &darr;', 'minimum'), __('% Comments &darr;', 'minimum')); ?>
                        </span>
                    <?php endif; ?> 
                </div><!-- end of .post-meta -->
                <?php endif; ?> 
                
                <div class="post-entry">
                    <?php the_content(__('Read more &#8250;', 'minimum')); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'minimum'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
                <?php if ( comments_open() ) : ?>
                <div class="post-data">
				    <?php the_tags(__('Tagged with:', 'minimum') . ' ', ', ', '<br />'); ?> 
                    <?php the_category(__('Posted in %s', 'minimum') . ', '); ?> 
                </div><!-- end of .post-data -->
                <?php endif; ?>             
            
            <div class="post-edit"><?php edit_post_link(__('Edit', 'minimum')); ?></div> 
            </div><!-- end of #post-<?php the_ID(); ?> -->
            
            <?php comments_template( '', true ); ?>
            
        <?php endwhile; ?> 
        
        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'minimum' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'minimum' ) ); ?></div>
		</div><!-- end of .navigation -->
        <?php endif; ?>

	    <?php else : ?>

        <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'minimum'); ?></h1>
                    
        <p><?php _e('Don&#39;t panic, we&#39;ll get through this together. Let&#39;s explore our options here.', 'minimum'); ?></p>
                    
        <h6><?php printf( __('You can return %s or search for the page you were looking for.', 'minimum'),
	            sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
		            esc_url( get_home_url() ),
		            esc_attr__('Home', 'minimum'),
		            esc_attr__('&larr; Home', 'minimum')
	                )); 
			 ?></h6>
                    
        <?php get_search_form(); ?>

<?php endif; ?>  
      
        </div><!-- end of #content-full -->
</div>
<?php 
} // end bh full width page loop
//get_footer(); 
genesis();
?>
