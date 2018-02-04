<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Single Posts Template
 *
 *
 * @file           single.php
 */
?>
<?php //get_header(); 
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_single_post_loop' );
function bh_single_post_loop(){

?>
<div class="inner">
        <div id="content" class="grid col-620">
        
<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
        
        <?php //breadcrumb here? // ?> 
          
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="post-title"><?php the_title(); ?></h1>

                <div class="post-meta">
                <?php //responsive_post_meta_data(); ?>
                
				    <?php if ( comments_open() ) : ?>
                        <span class="comments-link">
                        <span class="mdash">&mdash;</span>
                    <?php comments_popup_link(__('No Comments &darr;', 'minimum'), __('1 Comment &darr;', 'minimum'), __('% Comments &darr;', 'minimum')); ?>
                        </span>
                    <?php endif; ?> 
                </div><!-- end of .post-meta -->
                                
                <div class="post-entry">
                    <?php the_content(__('Read more &#8250;', 'minimum')); ?>
                    
                    <?php if ( get_the_author_meta('description') != '' ) : ?>
                    
                    <div id="author-meta">
                    <?php if (function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), '80' ); }?>
                        <div class="about-author"><?php _e('About','responsive'); ?> <?php the_author_posts_link(); ?></div>
                        <p><?php the_author_meta('description') ?></p>
                    </div><!-- end of #author-meta -->
                    
                    <?php endif; // no description, no author's meta ?>
                    
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'minimum'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
                <div class="navigation">
			        <div class="previous"><?php previous_post_link( '&#8249; %link' ); ?></div>
                    <div class="next"><?php next_post_link( '%link &#8250;' ); ?></div>
		        </div><!-- end of .navigation -->
                
                <div class="post-data">
				    <?php the_tags(__('Tagged with:', 'minimum') . ' ', ', ', '<br />'); ?> 
					<?php printf(__('Posted in %s', 'minimum'), get_the_category_list(', ')); ?> 
                </div><!-- end of .post-data -->             

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
      
        </div><!-- end of #content -->
<?php //get_sidebar(); ?>
</div> <!-- end of .inner -->
<?php 
}

function bh_newsletter_signup_footer(){
?>
<div class="newsletter-form-footer grid col-940">
			<div class="inner">
					<div class="grid col-380">	
					<h3>Sign up for our Newsletter</h3>
            		<p class="desktop-newsletter-text"></p>
                    </div>
                    <div class="grid col-540 fit">
                    <?php  bhpub_mailchimp_embed(); 
					 //<iframe src="http://www2.bhpublishinggroup.com/bhpub_newsletters.asp" width="100%"></iframe> ?>
                    </div>
	
			</div>
		</div>
<?php	
}

add_action('genesis_before_footer','bh_newsletter_signup_footer');
//get_footer(); 
genesis();
?>
