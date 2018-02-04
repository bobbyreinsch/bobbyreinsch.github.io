<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * IMAGE TEST
 */
?>
<?php // get_header(); ?>


<?php 
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action('genesis_sidebar','genesis_do_sidebar');
//add_action( 'genesis_loop', 'bh_home_features_loop' );
add_action('genesis_loop','bh_drafts_loop');

function bh_drafts_loop(){
	$statuses = array('draft','publish');
	foreach($statuses as $status){
	
	$drafts = array(
											'post_type'=>'bhauthor',
											'post_status' => $status,
											'posts_per_page'=>'-1',
											'meta_key' => 'wpcf-author-lastname',
											'orderby' => 'meta_value',
											'order'=>'ASC',

	);
	$plist = new WP_query($drafts);
		
?>
<div class="inner">

 <h2><?php echo $status; ?></h2>
    <div class="products-list">
		<?php if($plist->have_posts()){
				echo '<ul>';
				$pgcount = 0;
				while($plist->have_posts()): $plist->the_post(); 
							$postID = get_the_ID();
							?>
                <li id="product-<?php _e($postID) ?>" <?php post_class(); ?>>
			   <?php bh_thumbnail($postID,'thumbnail',true);	 ?> <b><?php echo get_the_title(); ?></b><br /><?php edit_post_link(__('Edit', 'responsive')); ?> <a href="<?php the_permalink(); ?>">View</a>
			   <?php the_content(); ?>
                            </li>
                <?php 
			
				endwhile;
			//	} //end foreach;
				echo '</ul>';
				echo '<hr></hr>';
				wp_reset_postdata();
		} ?>
    </div></div>
</div>
<style>
.products-list img {
	width:32px;
	min-width:none;	
	float:left;
}

li.entry>p {
	clear:left;	
}
</style>
<?php
wp_reset_postdata();
wp_reset_query();
}//end foreach
}//end function
 genesis(); 
   ?>
<?php // get_footer(); ?>