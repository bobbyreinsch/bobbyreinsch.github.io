<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Review Request Page 
 *
   Template Name:  Review Request Page
 *
 *
 * @file           review-request-page.php
 */
?>
<?php
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bhpub_review_request_page' );
?>

<?php //get_header(); 
function bhpub_review_request_page(){
?>
<div class="inner">
        <div id="content" class="grid col-940">
       
<?php if (have_posts()) : ?>
	
		<?php while (have_posts()) : the_post(); ?>
         <h1 class="post-title"><?php the_title(); ?></h1>
				<?php    // post thumbnail? //bh_thumbnail(get_the_ID(),'medium',true);	 ?>
                <div class="request-form" >
 				<?php the_content(); ?>
          <!-- end of #post-<?php the_ID(); ?> -->
            	</div>

            
           <?php endwhile; 
		//wp_pagenavi();
		
		?> 
        
        <?php /*
		 if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'responsive' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'responsive' ) ); ?></div>
		</div><!-- end of .navigation --> ?>
        
        <?php endif; 		*/ ?>
	
     
	    <?php else : ?>

       <!--  <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'responsive'); ?></h1> -->
                    
        <p><?php _e('No products found in '. single_cat_title('',false) .'.', 'responsive'); ?></p>
                    
       <!--  <h6><?php printf( __('You can return %s or search for the page you were looking for.', 'responsive'),
	            sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
		            esc_url( get_home_url() ),
		            esc_attr__('Home', 'responsive'),
		            esc_attr__('&larr; Home', 'responsive')
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
                    <p>Your inbox will thank you later. Whenever inboxes start talking.</p>
                    </div>
                    <div class="grid col-540 fit">
                    <iframe src="http://www2.bhpublishinggroup.com/bhpub_newsletters.asp" width="100%"></iframe>
                    </div>
	
			</div>
		</div>
</div>

<?php //get_footer(); 
} //end bhpub review request page
function bh_review_request_script(){
			//gather isbn from url
			global $wp_query;
			$requested_title = '';
			if (isset($wp_query->query_vars['req_isbn'])){
				$requested_isbn = $wp_query->query_vars['req_isbn'];
			}
			//look up title
			$findtitle = array( 	
													'post_type'=>'products',
													'meta_key'=>'wpcf-isbn',
													'meta_value' => $requested_isbn
												);
			
		$requested_product = new WP_query($findtitle);
		if($requested_product->have_posts()){
			while ( $requested_product->have_posts() ) :	$requested_product->the_post();
			$requested_title = get_the_title();
			endwhile;
		}
		 wp_reset_postdata();
?>
<script>
// load ISBN and Title into form
jQuery(function(){
	jQuery("#entry_1035357426").val("<?php echo $requested_title ?>");
	jQuery("#entry_2016885093").val("<?php echo $requested_isbn ?>");
});

</script>
<?php } //end bh review request script 
add_action('genesis_after_footer','bh_review_request_script');
genesis();
?>
