<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Information Page 
 *
   Template Name:  Information Page
 *
 *
 * @file           information-page.php
 */
?>
<?php
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_information_page_loop' );
?>

<?php //get_header(); 
function bh_information_page_loop(){
?>

<div class="inner">

<div class="sidebar grid col-220 fit information-menu">
<?php get_sidebar('information'); ?>
	
</div>

        <div id="content" class="grid col-700 fit">
		<?php 
			
		
		
		?>
        
<?php if (have_posts()) : ?>
	
		<?php while (have_posts()) : the_post(); ?>
         <h1 class="post-title"><?php the_title(); ?></h1>
				<?php    // post thumbnail? //bh_thumbnail(get_the_ID(),'medium',true);	 ?>
 				<?php the_content(); ?>
          <!-- end of #post-<?php the_ID(); ?> -->
            

            
           <?php endwhile; 
		//wp_pagenavi();
		
		?> 
        
        <?php /*
		 if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'minimum' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'minimum' ) ); ?></div>
		</div><!-- end of .navigation --> ?>
        
        <?php endif; 		*/ ?>
	
     
	    <?php else : ?>

       <!--  <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'minimum'); ?></h1> -->
                    
        <p><?php _e('No products found in '. single_cat_title('',false) .'.', 'minimum'); ?></p>
                    
       <!--  <h6><?php printf( __('You can return %s or search for the page you were looking for.', 'minimum'),
	            sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
		            esc_url( get_home_url() ),
		            esc_attr__('Home', 'minimum'),
		            esc_attr__('&larr; Home', 'minimum')
	                )); 
			 ?></h6>
                    
        <?php get_search_form(); ?> -->

<?php endif; ?>  
         
        </div><!-- end of #content -->
</div>	
<div class="newsletter-form-footer grid col-940">
			<div class="inner">
					<div class="grid col-380">	
					<h3>Sign up for our Newsletter(s)</h3>
                  <p>&nbsp;<!-- Your inbox will thank you later. Whenever inboxes start talking.--></p>
                    </div>
                    <div class="grid col-540 fit">
                    <?php  bhpub_mailchimp_embed(); 
					 //<iframe src="http://www2.bhpublishinggroup.com/bhpub_newsletters.asp" width="100%"></iframe> ?>
                    </div>
	
			</div>
		</div>
</div>
<?php 
} // end bh information page loop
//get_footer(); 
genesis();
?>
