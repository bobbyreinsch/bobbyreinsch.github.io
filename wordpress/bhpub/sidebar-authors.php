<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Authors Sidebar (widgets below content)
 *
 *
 * @file           sidebar-authors.php
 */
?>
<?php
//maybe javascript... get page slug, and check if it matches one of the links, then addClass current to parent li

?>
<h1>Authors</h1>
<?php //wp_list_pages should do this dynamically 
//http://codex.wordpress.org/Function_Reference/wp_list_pages
// exclude pages, include pages for alphabetical listings
 ?>
        
        <?php wp_list_pages('exclude=366,364&child_of=328&title_li=<h2>' . __('By Category') . '</h2>'); ?>
        
        <?php wp_list_pages('include=366,364&child_of=328&title_li=<h2>' . __('Alphabetical') . '</h2>'); ?>
        
        
               <?php //list up to 16 author posts tagged "popular" sorted by last name
			   //'tag_slug__in'=>'popular'
           $poptag = array(
		   							'post_type'=>'bhauthor',
									'posts_per_page'=>'25',
									'order_by'=>'meta_value',
									'meta_key'=>'wpcf-author-lastname',
									'tag_slug__in'=>'popular'
									
		   );
		   $popular = new WP_Query($poptag);
		   if($popular->have_posts()):
		   		echo'<h2>Popular Authors</h2>';
				echo '<ul>';
		   		while($popular->have_posts()): $popular->the_post();
		   		?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		   			
		   		<?php
		   		endwhile;
				echo '</ul>';
				wp_reset_postdata();
			else: ?>
				<ul>
        <li>No Authors tagged "Popular"</li>
        </ul>
		 <?php  endif;
           ?>
        
        <div id="widgets">
        <?php //responsive_widgets(); // above widgets hook ?>
           <?php /* 
            <?php if (!dynamic_sidebar('authors')) : ?>
            <div class="widget-wrapper">
            
                <div class="widget-title"><?php _e('In Archive', 'responsive'); ?></div>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul> 

            </div><!-- end of .widget-wrapper -->
            <?php endif; //end of right-left ?>
				*/ ?>		
        <?php //responsive_widgets_end(); // after widgets hook ?>
        </div><!-- end of #widgets -->