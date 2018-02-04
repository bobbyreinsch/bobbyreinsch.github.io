<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Author List All Sorted Template
 *
   Template Name:  Author List
 *
 * @file           authors-list.php
 */
?>
<?php
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_authors_list_loop' );
?>

<?php //get_header(); 
function bh_authors_list_loop(){
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
	   
	   //hide post content
		/*
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <!-- <h1 class="post-title"><?php the_title(); ?></h1> -->
                <div class="post-entry">
                    <?php the_content(__('Read more &#8250;', 'responsive')); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
            <div class="post-edit"><?php edit_post_link(__('Edit', 'responsive')); ?></div> 
            </div><!-- end of #post-<?php the_ID(); ?> -->
            */ ?>
            
           
           <?php //list author posts sorted by last name
		    			//'cat_slug__in'=>$pageslug
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //pagination		
			$pageslug=get_the_slug();
			//echo $pageslug;
			if($pageslug == 'sort-first-name'):
				//$sortby = 'wpcf-author-firstname';
				 $all = array(
		   							'post_type'=>'bhauthor',
									'meta_key'=>'wpcf-author-firstname',
									'orderby'=>'meta_value',
									'order'=>'ASC',
									'posts_per_page'=>'16',
									'paged'=>$paged
							);
			else:
				//default content... list all alphabetically
				//$sortby = 'wpcf-author-lastname';
				$all = array(
		   							'post_type'=>'bhauthor',
									'meta_key'=>'wpcf-author-lastname',
									'orderby'=>'meta_value',
									'order'=>'ASC',
									'posts_per_page'=>'16',
									'paged'=>$paged
							);
			endif;			
			
		   

		      $authorlist = new WP_Query($all);
			  if($authorlist->have_posts()): ?>
           <div class="author-text-list sorted"><h2>All B&amp;H Authors</h2>
				<?php
                echo '<ul>';
		   		while($authorlist->have_posts()): $authorlist->the_post();
		   		?>
                <li class="author-name">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <?php the_excerpt(); ?>
                 </li>
		   			
		   		<?php
		   		endwhile;
				echo '</ul></div>';
				echo '<div class="clear"></div>';
				wp_pagenavi( array( 'query' => $authorlist ) );
				
				wp_reset_postdata();
		   endif;
			  
           ?>
            
        <?php endwhile; ?> 
        
	    <?php else : ?>

        <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'responsive'); ?></h1>
                    
        <p><?php _e('Don&#39;t panic, we&#39;ll get through this together. Let&#39;s explore our options here.', 'responsive'); ?></p>
                    
        <h6><?php printf( __('You can return %s or search for the page you were looking for.', 'responsive'),
	            sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
		            esc_url( get_home_url() ),
		            esc_attr__('Home', 'responsive'),
		            esc_attr__('&larr; Home', 'responsive')
	                )); 
			 ?></h6>
                    
        <?php get_search_form(); ?>

<?php endif; ?>  
      
        </div><!-- end of #content-full -->
</div>
<?php 
} // end bh authors list loop
//get_footer(); 
genesis();
?>
