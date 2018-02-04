<?php

// Exit if accessed directly
 if ( !defined('ABSPATH')) exit;

/**
 * Redirect old product and author URLs to new
 *
 *
 * @file           page-bh-redirect.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2013 ThemeID
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/responsive/page.php
 * @link           http://codex.wordpress.org/Theme_Development#Pages_.28page.php.29
 * @since          available since Release 1.0
 */
 
$p_id = false; //init
 //get url param isbn/lin/upc
  if ( get_query_var('pid') ){
 $p_id = get_query_var('pid'); //sanitized input
  }

//parse response (remove p=)
$p_id=str_replace('?p=','',$p_id);

 if($p_id):
 
$sql = $wpdb->prepare("
SELECT $wpdb->posts.* 
    FROM $wpdb->posts, $wpdb->postmeta
    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
       AND 
		($wpdb->postmeta.meta_key = 'wpcf-isbn'
		OR $wpdb->postmeta.meta_key = 'wpcf-lin'
		OR $wpdb->postmeta.meta_key = 'wpcf-upc'
		OR $wpdb->postmeta.meta_key = 'wpcf-isbn10'
		)
    AND $wpdb->postmeta.meta_value = %s
    AND $wpdb->posts.post_type = 'products'
", $p_id);

 $product_pages  = $wpdb->get_results($sql, OBJECT);
 //the LOOP revised 
	 if($product_pages):
			$pcount = 0;
			global $post; 
			foreach ($product_pages as $post): 
			$pcount++;
				if($pcount==1):
					setup_postdata($post); 	
					//redirect to page
						$newurl = get_permalink();
						wp_redirect( $newurl, 301 ); exit();
				endif;
			endforeach;
	else:
	//no posts match - redirect to search page instead
			echo ('no results found for '.$p_id.'. redirecting to search page...');
			wp_redirect( get_bloginfo('url').'/?s='.$p_id, 302 ); exit();
	endif;

elseif(get_query_var('a')):
// check for author names, lookup and redirect
$name_arr = explode('_',get_query_var('a'));

$a_first = str_replace('+',' ', $name_arr[1]); //get query var adds a + for the space
$a_last = $name_arr[0];
$a_fullname='';
if($a_first){$a_fullname=$a_first.' ';}
if($a_last){$a_fullname=$a_fullname.$a_last;}
$a_slug=sanitize_title($a_fullname);
//lookup title - exact match
$args = array(
		   							'post_type'=>'bhauthor',
									'posts_per_page'=>'1',
									'name'=>$a_slug			
		   );
		   
//lookup title - contains both (SQL)
		   
$authorposts = new WP_Query($args);
	if($authorposts->have_posts()):
		while($authorposts->have_posts()): $authorposts->the_post();	
		//redirect to author post
		$newurl = get_permalink();
		wp_redirect( $newurl, 301 ); exit();
			endwhile;
	else:
	//if no exact match, redirect to search page
	wp_redirect( get_bloginfo('url').'/?s='.urlencode($a_fullname), 302 ); exit();
	endif;
else:
 // no product entered show error or redirect to home?
 get_header(); 

 ?>
 <div class="inner">
        <div id="content" class="grid col-940">
    	<h2>Oops...</h2>
        <p>Something went wrong... please use the search to find an author or product.</p>
<?php    	
endif;
 ?>
 </div></div>
 <?php get_footer(); 
 
 ?>