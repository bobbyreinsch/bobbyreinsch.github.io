<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Search Template
 *
 *
 * @file           search.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2013 ThemeID
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/responsive/search.php
 * @link           http://codex.wordpress.org/Theme_Development#Search_Results_.28search.php.29
 * @since          available since Release 1.0
 */
?>
<?php get_header(); ?>
<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //pagination
		$ext_query = '&posts_per_page=25&paged='.$paged.'';
		//query_posts($query_string . $ext_query );		
?>
		<div class="inner">
        <div id="content-search" class="grid col-940"> <?php //col-620 ?>

<?php if (have_posts()) : ?>

    <h6><?php printf(__('Search results for: %s', 'responsive' ), '<span>' . get_search_query() . '</span>'); ?></h6>

		<?php while (have_posts()) : the_post(); ?>
        
        
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php    
			//initialize vars
			$isbn13=false;$upc=false;$product_id=false;
			$pubdate=false;$pub_date=false;
			$authors=false;$product_authors=false;
			
			$isbn13 = get_post_meta(get_the_ID(),'wpcf-isbn',true); 
			$upc = get_post_meta(get_the_ID(),'wpcf-upc',true); 
			if($isbn13){
				$product_id=' ('.$isbn13.') ';	
			}elseif($upc){
				$product_id=' ('.$upc.') ';	
			}
						
			$pubdate = get_post_meta(get_the_ID(),'wpcf-pubdate',true); 
			$pub_date = date('F Y',strtotime($pubdate));
			
			
			$authors = get_post_meta(get_the_ID(),'wpcf-book-authors',true); 	
			if($authors){
			$product_authors = "by ".$authors;
			}
			
			//get category "breadcrumb"
			$taxonomy = 'category';
			$terms = ''; //init

// get the term IDs assigned to post.
$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
// separator between links
$separator = ', ';

if ( !empty( $post_terms ) && !is_wp_error( $post_terms ) ) {

	$term_ids = implode( ',' , $post_terms );
	$terms = wp_list_categories( 'title_li=&style=none&echo=0&taxonomy=' . $taxonomy . '&include=' . $term_ids );
	$terms = rtrim( trim( str_replace( '<br />',  $separator, $terms ) ), $separator );

	// display post categories
	//echo  $terms;
	
	//get post type and label
	$posttypeobj = get_post_type_object($post->post_type);
	$post_type_plural = $posttypeobj->labels->name;
}
			
			
			?>
            <div class="post-entry">
                    <div class="post-thumbnail"><?php 
					//check for post-type or author
					$curr_post_type = $post->post_type;
					if($curr_post_type== "bhauthor" || $curr_post_type=="products"):
						bh_thumbnail(get_the_ID(),'medium',true);	
					else:
						if(has_post_thumbnail()){the_post_thumbnail('medium');}
					endif;
					?></div>
                    <div class="search-post-content">
                    <h4 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'responsive'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h4>
					<div class="post-meta"><?php if($authors){echo $product_authors;} ?><?php if($pubdate){echo ' '.$pub_date.' ';} ?><?php if($product_id){echo $product_id;} ?></div>
					<?php the_excerpt(); ?>
                    <!-- <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>-->
                    <?php if($terms): ?>
                    <div class="post-breadcrumb">Find more <?php echo $post_type_plural ?> in <?php echo $terms?></div>
                    <?php endif; ?>
           </div>
            </div><!-- end of .post-entry -->
            
            
            
            
                <?php /*  OLD search entry 
                <h1 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'responsive'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h1>
               
               
                <div class="post-meta">
                <?php responsive_post_meta_data(); ?>
                
				    <?php if ( comments_open() ) : ?>
                        <span class="comments-link">
                        <span class="mdash">&mdash;</span>
                    <?php comments_popup_link(__('No Comments &darr;', 'responsive'), __('1 Comment &darr;', 'responsive'), __('% Comments &darr;', 'responsive')); ?>
                        </span>
                    <?php endif; ?> 
                </div><!--  end of .post-meta -->
                
                <div class="post-entry">
                    <?php the_excerpt(); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                           

            <div class="post-edit"><?php edit_post_link(__('Edit', 'responsive')); ?></div> 
			*/ ?>              
            </div><!-- end of #post-<?php the_ID(); ?> -->
            
        <?php endwhile; 
        	wp_pagenavi();?> 
       <!--  
	   <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'responsive' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'responsive' ) ); ?></div>
		</div>end of .navigation -->
        <?php endif; ?>

	    <?php else : ?>

        <h3 class="title-404"><?php printf(__('Your search for %s did not match any entries.', 'responsive' ), get_search_query() ); ?></h3>
                    
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
      
        </div><!-- end of #content-search -->

<?php //get_sidebar(); ?>
</div>
<?php get_footer(); ?>
