<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Press Releases Page 
 *
   T e mp la te Na m e:  Press Releases
 *
 *
 * @file           press-releases.php
 */
?>
<?php
add_action('genesis_before_loop', 'bh_press_release_page_loop');
function bh_press_release_page_loop(){
	// load most recent post
	//query_posts( 'category_name=press-release&posts_per_page=1');
}
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_press_releases_loop' );
?>


<?php //get_header();
function bh_press_releases_loop(){ 
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //pagination		
$args = array(	
							'category_name' => 'press-release',
							'paged' => $paged,
							'posts_per_page' => '16'
							//'offset'=>'1'
						);

$pr = new WP_Query($args);
?>

<div class="inner">

<div class="sidebar grid col-220 fit information-menu">
<?php get_sidebar('information'); ?>
	
</div>

        <div id="content" class="grid col-700 fit">
		<?php 
			
		
		
		?>
        
<?php if ($pr->have_posts()) : ?>
		<div class="grid col-620">
        <div class="press-release-text">
		<h2>Press Release</h2>
        
		<?php
		$pr_count = 0;
		 while ($pr->have_posts()) : $pr->the_post();
		 $pr_count++; 
		 if($pr_count==1):
		 ?>
        
				<?php    // post thumbnail? //bh_thumbnail(get_the_ID(),'medium',true);	 ?>
				<?php the_content(); ?>
          <!-- end of #post-<?php the_ID(); ?> -->
            

            
           <?php
		 endif;
		    endwhile; 
		//wp_pagenavi();
		
		?> 
        
        <?php /*
		 if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'responsive' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'responsive' ) ); ?></div>
		</div><!-- end of .navigation --> ?>
        
        <?php endif; 		*/ ?>
	
        </div>
        </div>
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
      
        
        <div class="grid col-300 fit press-releases-list">
        <h3>Press Releases <a class="rssicon" href="/category/press-release/feed/" target="_blank">rss</a></h3>
        <?php //get_sidebar(); 
	//list press releases
wp_reset_query();	
	if($pr -> have_posts()):
	echo '<ul>';
		while ( $pr->have_posts() ):
		$pr->the_post(); 
		$pr_link = "/press-releases/?p=".get_the_ID();
		?>
	<li><span class="press-release-date"><?php the_date() ?></span><br/><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>	
		<?php endwhile;
		echo '</ul>';
	
	wp_pagenavi();	
	endif;
	
wp_reset_query();


 ?>
        </div><!-- end archive list -->
        </div><!-- end of #content -->
</div>
</div>
<?php //get_footer(); 
}
genesis();
?>
