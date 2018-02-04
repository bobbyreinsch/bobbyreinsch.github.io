<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Author Category Page Template
 *
   Template Name:  Author Category
 *
 * @file           authors-category.php
 */
?>
<?php
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_authors_category_loop' );
?>

<?php //get_header(); 
function bh_authors_category_loop(){
?>	
<div class="inner">
		<div class="grid col-220 author-categories">
        <?php get_sidebar('authors'); ?>
        </div>
        
        <div id="content-full" class="grid col-700 fit">

<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
 <?php  /* //removed comments  */
 
			//custom class information from custom fields 								
			$headline_color = types_render_field('headline-color', array("raw"=>"true"));
			$headline_size = types_render_field('headline-size', array("raw"=>"true"));
			$text_color = types_render_field('text-color', array("raw"=>"true"));	
			$feature_link = types_render_field('feature-link', array("raw"=>"true"));	
			
			$custom_class = $headline_size.' '.$text_color.' '.$headline_color.'-headline ';			
								
								 ?>
                <div class="post-entry">
                    <div class="author-feature category-feature <?php echo $custom_class ?>">
						<?php if($feature_link){ echo'<a class="feature-link" href="'.$feature_link.'">';} ?>
						<?php if(has_post_thumbnail()): ?>
                        <div class="bg-image"><?php the_post_thumbnail('full'); ?></div>
                        <?php endif; ?>
							 <?php
							 //replace with genesis breadcrumbs
							 // $options = get_option('responsive_theme_options');
                            // if ($options['breadcrumb'] == 0):
                           // echo responsive_breadcrumb_lists(); ?
                          //  endif;  ?>
                        <?php the_content(); ?>
                        <?php if($feature_link){ echo'</a>';} ?>
                 </div>
                    
                   <?php /*
				   <?php the_content(__('Read more &#8250;', 'minimum')); ?>
				   <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'minimum'), 'after' => '</div>')); ?>-->
				   <div class="post-edit"><?php edit_post_link(__('Edit', 'minimum')); ?></div>  */ ?>
                </div><!-- end of .post-entry -->
                
            
            </div><!-- end of #post-<?php the_ID(); ?> -->
            
       
           
           <?php //list 6 author posts in this category sorted by last name
		    			//'cat_slug__in'=>$pageslug
							//tag array - needs both to appear on a landing page
			$req_tags = array('featured');
			//match tags to current category name
			$req_tags[]=sanitize_title(get_the_title());	
						
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //pagination				
	
			$authorslug = get_the_slug();
			$pageslug = str_replace('-authors', '', $authorslug);
			$bookIDs = array();			
           //find products in matching category
		   $products = array(
		   							'post_type'=>'products',
									'category_name'=>$pageslug,
									'orderby'=>'meta_value',
									'meta_key'=>'wpcf-pubdate',
									'order'=>'DESC'
									
		   );
		   
		   $booksbycategory = new WP_Query($products);
		   while ( $booksbycategory->have_posts() ) : $booksbycategory->the_post();
						array_push($bookIDs, get_the_ID());
			endwhile;
			
			$authortags = array();
			$currentauthor = wp_get_object_terms($bookIDs, 'a');
					foreach($currentauthor as $atag){
						array_push($authortags,$atag->slug);
					}		
					
			//$authorslugs =  implode(',',$authortags); // should display a comma separated list of slugs
			// get author post ids from slugs
			$authorids = array();
			
			foreach($authortags as $aslug){
				$sargs = array(
						'post_type'=>'bhauthor',
						'name'=>$aslug
				);	
				$aposts = get_posts($sargs);
				if($aposts){
					array_push($authorids,$aposts[0]->ID);
				}
			} 
			/*
			//automated - replaced by more specific tagging
			if ($authorids): */
	
		   $pop = array(
		   							'post_type'=>'bhauthor',
									'posts_per_page'=>'6',
									//'post__in'=>$authorids,
									//'tag_slug__in'  =>'featured',
									'tag_slug__and'=>$req_tags,
									'orderby'=>'meta_value',
									'meta_key'=>'wpcf-author-lastname'
									
		   );
		   
		   $popular_thumbs = new WP_Query($pop);
		   $i=0;
		   if($popular_thumbs->have_posts()):
		   		echo '<div class="author-thumbs-list">';
				echo '<div>';
		   		while($popular_thumbs->have_posts()): $popular_thumbs->the_post();
		   		$i++;
				?>
                <span class="author-thumb grid col-300 fit">
                		<!-- <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a> -->
                        <?php //replace with custom thumbnail code
						bh_thumbnail(get_the_ID(), 'thumbnail',true);
						?>
                		<br/><a class="text-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                 </span>
		   			
		   		<?php
				 if($i % 3 == 0) {echo '</div><div>';}
		   		endwhile;
				echo '</div><div class="clear">&nbsp;</div></div>';
				wp_reset_postdata();
		   endif;
		//  endif;
           ?>

           <?php 
		   //v2 list alpha links for ajax, last/first name sorter box
		   
		   if($authorids):
		   //list all author posts in this category sorted by last name
           $auth = array(
		   							'post_type'=>'bhauthor',
		   							'meta_key'=>'wpcf-author-lastname',
		   							'orderby'=>'meta_value',
		   							'order'=>'ASC',
									'post__in'=>$authorids,
									'posts_per_page'=>'10',
									'paged'=>$paged
									
		   );



		   $authorlist = new WP_Query($auth);


		   if($authorlist->have_posts()): ?>
           <div class="author-text-list"><h2>List Alphabetically</h2>
				<?php
                echo '<ul>';
		   		while($authorlist->have_posts()): $authorlist->the_post();
		   		?>
                <li class="author-name">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br/>
                        <?php the_excerpt(); ?>
                 </li>
		   			
		   		<?php
		   		endwhile;
				echo '</ul></div>';
				echo '<div class="clear"></div>';
				wp_pagenavi( array( 'query' => $authorlist ) );
				
				wp_reset_postdata();
		   endif;
		   
		   else:
		   //echo 'no authors found for this category';
		   //default content... list all alphabetically?
		     $all = array(
		   							'post_type'=>'bhauthor',
									'orderby'=>'meta_value',
									'meta_key'=>'wpcf-author-lastname',
									'posts_per_page'=>'24',
									'paged'=>$paged
									
		   );
		      $authorlist = new WP_Query($all);
			  if($authorlist->have_posts()): ?>
           <div class="author-text-list"><h2>All B&amp;H Authors</h2>
				<?php
                echo '<ul>';
		   		while($authorlist->have_posts()): $authorlist->the_post();
		   		?>
                <li class="author-name">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                 </li>
		   			
		   		<?php
		   		endwhile;
				echo '</ul></div>';
				echo '<div class="clear"></div>';
				wp_pagenavi( array( 'query' => $authorlist ) );
				
				wp_reset_postdata();
		   endif;
			  
		   endif;
           ?>
            
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

