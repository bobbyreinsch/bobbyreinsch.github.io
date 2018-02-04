<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Press Releases Page Template
 *
   Template Name:  Press Releases
 *
 *
 * @file           press-releases.php
 */
?>

<?php //get_header(); 
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_single_press_release_loop' );
function bh_single_press_release_loop(){

?>

<div class="inner">

<div class="sidebar grid col-220 fit information-menu">
<?php get_sidebar('information'); ?>
	
</div>

        <div id="content" class="grid col-700 fit">


        
<?php if (have_posts()) : ?>
		<div class="grid col-620">
        <div class="press-release-text">
		<h2>Press Release</h2>

		<?php while (have_posts()) : the_post(); ?>
        
          
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <!-- <h1 class="post-title"><?php the_title(); ?></h1> -->
                                
                <div class="post-entry">
                    <?php the_content(__('Read more &#8250;','minimum')); ?>
                    
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:','minimum'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
                <div class="navigation">
			        <div class="previous"><?php previous_post_link( '&#8249; %link' ); ?></div>
                    <div class="next"><?php next_post_link( '%link &#8250;' ); ?></div>
		        </div><!-- end of .navigation -->
                
                <div class="post-data">
				    <?php the_tags(__('Tagged with:','minimum') . ' ', ', ', '<br />'); ?> 
					<?php printf(__('Posted in %s','minimum'), get_the_category_list(', ')); ?> 
                </div><!-- end of .post-data -->             

            <div class="post-edit"><?php edit_post_link(__('Edit','minimum')); ?></div>             
            </div><!-- end of #post-<?php the_ID(); ?> -->
            
            
        <?php endwhile; ?> 

        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts','minimum' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;','minimum' ) ); ?></div>
		</div><!-- end of .navigation -->
        <?php endif; ?>


        </div>
        </div>

	    <?php else : ?>

        <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!','minimum'); ?></h1>
                    
        <p><?php _e('Sorry, I could&#39;nt find the Press Release you requested.','minimum'); ?></p>
                    
        <h6><?php printf( __('You can return %s or search for the page you were looking for.','minimum'),
	            sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
		            esc_url( get_home_url() ),
		            esc_attr__('Home','minimum'),
		            esc_attr__('&larr; Home','minimum')
	                )); 
			 ?></h6>
                    
        <?php get_search_form(); ?>

<?php endif; ?>  

      
        
        <div class="grid col-300 fit press-releases-list">
        <h3>Press Releases <a class="rssicon" href="/category/press-release/feed/" target="_blank">rss</a></h3>
        <?php //get_sidebar(); 
	//list press releases
wp_reset_query();	
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //pagination		
$args = array(	
							'category_name' => 'press-release',
							'paged' => $paged,
							'posts_per_page' => '16'
						);

$pr = new WP_Query($args);

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

} //end single_press_release function
genesis(); ?>
