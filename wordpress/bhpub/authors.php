<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Author Landing page Template
 *
   Template Name:  Authors
 *
 * @file           authors.php
 */
?>
<?php
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_authors_page_loop' );
?>

<?php //get_header(); 
function bh_authors_page_loop(){
?>
<div class="inner">
		<div class="grid col-220 author-categories">
        <?php get_sidebar('authors'); ?>
        </div>
        
        <div id="content-full" class="grid col-700 fit">

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
                <!-- <h1 class="post-title"><?php the_title(); ?></h1> -->
 			<?php  /*  comments were here     */ ?>
                <div class="post-entry">
                    <?php the_content(__('Read more &#8250;', 'minimum')); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'minimum'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
            <div class="post-edit"><?php edit_post_link(__('Edit', 'minimum')); ?></div> 
            </div><!-- end of #post-<?php the_ID(); ?> -->
            
           <?php /* 
           <div class="author-thumbs-list">
           <h2>Recent Popular Authors</h2>
           
           <?php //list 12 author posts tagged "popular" sorted by last name
		    			//'tag_slug__in'=>'popular'
           $pop = array(
		   							'post_type'=>'bhauthor',
									'posts_per_page'=>'12',
									'orderby'=>'meta_value',
									'meta_key'=>'wpcf-author-lastname',
									'order' => 'ASC',
									'tag_slug__in'=>'popular'
									
		   );
		   $popular_thumbs = new WP_Query($pop);
		   
		   if($popular_thumbs->have_posts()):
		   		echo '<ul>';
		   		while($popular_thumbs->have_posts()): $popular_thumbs->the_post();
		   		?>
                <li>
                		<!-- <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a> -->
                        <?php bh_thumbnail(get_the_ID(), 'thumbnail',true); ?>
                		<br/><a class="text-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                 </li>
		   			
		   		<?php
		   		endwhile;
				echo '</ul>';
				wp_reset_postdata();
		   endif;
		   */ //removed BR 12-17-2015 per EG request
           ?>
           </div>
         
            
        <?php endwhile; ?> 
        
      

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
} // end bh authors page loop
//get_footer(); 
genesis();
?>
