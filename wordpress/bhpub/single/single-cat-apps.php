<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * App Product Page Template
 *
 *
 * @file           single-cat-apps.php
 */
?>
<?php //get_header();

remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_apps_product_page' );
function bh_apps_product_page(){
	 ?>

<div class="inner">
<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); 
		$thispost = get_the_ID();
		
		//get top-level category
					$p_cats=get_the_category($thispost);
					$parents =get_category_parents($p_cats[0]->term_id);
					$cat_arr = split("/",$parents);
					$topcat = $cat_arr[0];
	
	$subtitle = types_render_field('subtitle', array("raw"=>"true"));		
	
	//				$productwebsite = types_render_field('wpcf-product-website',array('raw'=>'true'));
	//				$productfb = types_render_field('wpcf-product-facebook',array('raw'=>'true'));
	//				$netgalley = types_render_field('wpcf-product-netgalley',array('raw'=>'true'));
	//				$showLinks = true;	
					
	//				if($topcat == 'Supplies' || $topcat=='Apps'){
						if(!$productwebsite && !$productfb){
							$showLinks = false;
						}
	//				}	
							
		?>
 <div class="grid col-460 products-top-left">       
        <?php //$options = get_option('responsive_theme_options'); ?>
		<?php // if ($options['breadcrumb'] == 0): ?>
		<?php //echo responsive_breadcrumb_lists(); ?>
        <?php //endif; ?>
	   
</div>
<div class="grid col-460 fit products-top-right">
<?php
// tweet button, fb like button
// if ( function_exists( 'ngfb_get_social_buttons' ) )
//echo ngfb_get_social_buttons( array( 'twitter','facebook' ) ); 
?>
</div>
<?php 
$hero = types_render_field('app-hero-image',array('raw'=>'true'));
if($hero):
?>
<div class="app-page-hero">
<img src="<?php echo $hero ?>" alt="<?php the_title() ?>" />
</div>
<?php
endif; //hero
?>
<div class="apps product-content">
<?php //end left sidebar ?>

<div id="content">
<div class="apps-page-thumbnail">
<?php //get_sidebar();
// main author image 

//custom thumbnail function
bh_thumbnail($thispost, 'thumbnail');
			
// additional images gallery (if exist) v2

?></div> 

                <div class="post-entry">
                	<h1><?php the_title() ?></h1>
                    <?php if ($subtitle!=''){ 
                    	echo '<p class="subtitle">'.$subtitle.'</p>';
					} 
						
					
					if($showLinks){
					 ?>
                    <div class="product-social-links">
                	<ul>
					<?php 
					if ($productwebsite!=''){
						echo '<li><a class="site-link" href="'.$productwebsite.'" target="_blank">Official Site</a></li>';
					}
					if ($productfb!=''){
						echo '<li><a class="facebook-link" href="'.$productfb.'" target="_facebook">Facebook</a></li>';
					}
					//is this netgalley or an email link?
					//link to google form - try to include ISBN and title
					?>
                    <?php if($topcat != "Supplies" && $topcat != "Apps"){ ?>
                    <li><a class="review-link" href="/request-review-copy/?req_isbn=<?php echo $isbn13; ?>&req_title=<?php echo get_the_title(); ?>">Request a Review Copy</a></li>
                     
					<?php } ?></ul>
                    </div>
                    <?php 
					}//end if showLinks
					
					the_content(__('Read more &#8250;', 'minimum')); ?>
                    <?php //wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'minimum'), 'after' => '</div>')); ?>
           			 
                   <div class="product-videos">
            		<?php //this should be a plugin eventually, just add to the end of the post content
            			  if($isbn13){
						  if(function_exists('fetch_feed')) {  
							$youtubefeed = "http://gdata.youtube.com/feeds/api/videos/-/trailer/".$isbn13."?author=bhpublishinggroup";
							
							include_once(ABSPATH . WPINC . '/feed.php'); // the file to rss feed generator  
							$feed = fetch_feed($youtubefeed); // specify the rss feed  
						  
							$limit = $feed->get_item_quantity(5); // specify number of items  
							$items = $feed->get_items(0, $limit); // create an array of items  
						  
						}  
						if ($limit == '0' || $limit == 0 || !$limit):
						 echo '<!-- No videos found for product '.$isbn13.'-->';  
						else:  ?>
						<div class="section-related-videos">
                        <h4 class="mobile-section-hdr"><a href="#">Related Media <img src="<?php echo $green_arrow ?>" /></a></h4>
                        <div class="mobile-expanded">
                        <?php
						foreach ($items as $item) : 
						$yturl = explode('/',$item->get_id());
						$ytid = end($yturl);?>  
						 <iframe width="480" height="360" src="http://www.youtube.com/embed/<?php _e($ytid); ?>?rel=0" frameborder="0" allowfullscreen></iframe>
						 <?php 						
						endforeach;  
						?></div></div><?php
						endif;
						//get product rss feed from youtube
						
						//count posts
						
						//display single video
						
						//display multiple videos with switching
						  }//if isbn
			
            		?>
                                      
                    <?php
//buy now buttons
$msrp = types_render_field('msrp', array("raw"=>"true"));
//$isbn13 = types_render_field('isbn', array("raw"=>"true"));
//$isbn10 = types_render_field('isbn10', array("raw"=>"true"));
//$lin = types_render_field('lin', array("raw"=>"true"));

$pubdate_raw = types_render_field('pubdate', array("raw"=>"true"));
$pubdate=strtotime(substr($pubdate_raw,0,4) . '/'. substr($pubdate_raw,4,2) . '/' . substr($pubdate_raw,6,2));

$green_arrow = get_stylesheet_directory_uri().'/img/btn-arrow-down-green.png';


//edit image locations to local theme
if(is_numeric($msrp)){
	$retail_price = '$'.number_format((float)$msrp, 2, '.', ''); // force numeric prices to currency 
}else{
	$retail_price = $msrp; // if MSRP is text, for example, "FREE"
}

?>

                </div><!-- end of .post-entry -->
                <?php $more_content = types_render_field('book-add-content',array('output'=>'html'));
					if ($more_content!=''){ ?>
                    <div class="more-content">
                    	<?php _e($more_content) ?>
                    </div>
                    <?php } ?>    
                     
                    </div>
        </div><!-- end of #content -->


                <div class="apps-buy-button">
<?php //display different buttons for app stores 

	$ios_url = types_render_field('app-link-ios', array("raw"=>"true"));
	$droid_url = types_render_field('app-link-android', array("raw"=>"true"));
	$winp_url = types_render_field('app-link-windows', array("raw"=>"true"));
	$pc_url = types_render_field('app-link-pc', array("raw"=>"true"));
	$xbox_url = types_render_field('app-link-xbox', array("raw"=>"true"));
	$other_urls = types_child_posts('app-link-other');
				//foreach ($other_urls as $other_link):
						//do something
						
				//end foreach;
	
	//display buttons for each platform
	echo '<div class="apps-download">';
	
	if($droid_url):
		echo '<a href="' . $droid_url . '" target="_blank" /><img src="/img/retailers/android-btn.png" alt="Download at Google Play" height="40" /></a>';
	endif;
	if($ios_url):
		echo '<a href="' . $ios_url . '" target="_blank" /><img src="/img/retailers/ios-btn.png" alt="Download at the App Store" height="40" /></a>';
	endif;
	if($winp_url):
		echo '<a href="' . $winp_url . '" target="_blank" /><img src="/img/retailers/windowsphone-btn.png" alt="Download at the Windows Phone Store" height="32" /></a>';
	endif;
	if($pc_url):
		echo '<a href="' . $pc_url . '" target="_blank" />Download for PC</a>';
	endif;
	if($xbox_url):
		echo '<a href="' . $xbox_url . '" target="_blank" />Buy on XBOX Live</a>';
	endif;
	
	//loop for other retailers
		//foreach ($other_urls as $other_link):
						//do something
						
				//end foreach;
	
	//get platform
	/* //old way - detect category 
	$buybtn = '<img src="/img/retailers/ios-btn.png" alt="Download at the App Store" height="40" />';//iOS buy button
	
	$post_cat_list = wp_get_post_categories($thispost);
	$clist = array();
	foreach($post_cat_list as $c){
		$cat_info = get_category($c);
		$clist[]=$cat_info->slug;
		}
		
		$allc = implode(',',$clist);
		if(strpos($allc,'xbox') !== false){
			$buybtn = 'Buy on XBOX LIVE';//xbox
		}
			if(strpos($allc,'pc') !== false){
			$buybtn = 'Download for PC';//PC
		}
		
		if(strpos($allc,'windows-phone') !== false){
			$buybtn = '<img src="/img/retailers/windowsphone-btn.png" alt="Download at the Windows Phone Store" height="40" />';//win
		}
		
		if(strpos($allc,'android') !== false){
			$buybtn = '<img src="/img/retailers/android-btn.png" alt="Download at Google Play" height="40" />';//android
		}		
	
	//display correct button for platform
	echo '<div class="apps-download"><a href="' . $app_url . '" target="_blank" />' . $buybtn . '</a></div>';
	*/
?>
<div class="product-price"><strong><?php _e($retail_price); ?> </strong></div>
 </div>  
                    
                    
                    
                    
                    
                    

</div>        
                
                <?php if ( comments_open() ) : ?>
                <div class="post-data">
				    <?php the_tags(__('Tagged with:', 'minimum') . ' ', ', ', '<br />'); ?> 
                    <?php the_category(__('Posted in %s', 'minimum') . ', '); ?> 
                </div><!-- end of .post-data -->
                <?php endif; ?>             
        
  			<div class="app-details">
  		      <?php 
			  	$app_details_1 = types_render_field('app-details-1',array('output'=>'html'));
				$app_details_2 = types_render_field('app-details-2',array('output'=>'html'));	
				$app_details_3 = types_render_field('app-details-3',array('output'=>'html'));
				$app_detail_icon_1 = types_render_field('app-detail-icon-1',array('output'=>'html'));
				$app_detail_icon_2 = types_render_field('app-detail-icon-2',array('output'=>'html'));
				$app_detail_icon_3 = types_render_field('app-detail-icon-3',array('output'=>'html'));
				
				if (!$app_details_1 OR !$app_details_2 OR  !$app_details_3): //if only 2 are populated
				
				 if($app_details_1): ?>
                   <div class="grid col-460">
                    	<h4><?php if($app_detail_icon_1){echo $app_detail_icon_1;} ?></h4>
                    	<?php echo $app_details_1; ?>
                    </div>
                   <?php endif;
				   if($app_details_2):  ?>
                    <div class="grid col-460 fit">
                    	<h4><?php if($app_detail_icon_2){echo $app_detail_icon_2;} ?></h4>
                    	<?php echo $app_details_2; ?>
                    </div>
                    <?php endif;
                 	 if($app_details_3): ?>
                    <div class="grid col-460 fit">
                    	<h4><?php if($app_detail_icon_3){echo $app_detail_icon_3;} ?></h4>
                    	<?php echo $app_details_3; ?>
                    </div>
                    <?php endif; 
					else:
					//all three populated, the normal case
					?>
                    <div class="grid col-300">
                    	<h4><?php if($app_detail_icon_1){echo $app_detail_icon_1;} ?></h4>
                    	<?php if($app_details_1){echo $app_details_1;} ?>
                    </div>
                    <div class="grid col-300">
                    	<h4><?php if($app_detail_icon_2){echo $app_detail_icon_2;} ?></h4>
                    	<?php if($app_details_2){echo $app_details_2;} ?>
                    </div>
                    <div class="grid col-300 fit">
                    	<h4><?php if($app_detail_icon_3){echo $app_detail_icon_3;} ?></h4>
                    	<?php if($app_details_3){echo $app_details_3;} ?>
                    </div>
                    
                    <?php 
					endif;
                	?>
                
                </div>	
	 </div>
  
            
            <div class="post-edit"><?php edit_post_link(__('Edit', 'minimum')); ?></div> 

            <?php 
			$product_quote = types_render_field('product-quote', array("output"=>"html"));
			if ($product_quote!=''){ ?>
            <div class="product-quote">
            	
                		<?php _e($product_quote); ?>
                
            </div>
            <?php } ?>
           
          <div class="apps product-links">  
          	

           
                <?php 
				//update - check related books custom field for isbns, list those posts
				$catslugs=array();
				$category = get_the_category();
				foreach($category as $cat){
					array_push($catslugs,$cat->term_id);	
				}
				//get list of books in the same category and/or by the same author
                $rbooks = array(
					'post_type'=>'products',
					'category__in'=>$catslugs
					//'tax_query'=>array(
					//							array(
					//								'taxonomy'=>'category',
					//								'field'=>'slug',
					//								'terms'=>$catslugs //must be array - from above		
					//							)
					//						)
										);
				$relatedbooks = new WP_query($rbooks);
				if($relatedbooks->have_posts()): // && count($relatedbooks)>1
				global $wp_query;
				global $post;
				?>
				 <div class="product-related">
                <h4>Related Apps</h4>
                <ul class="booklist">
				
				<?php
				while($relatedbooks->have_posts()): $relatedbooks->the_post();
				if ($post->ID!=$thispost){ //hide the current product from results
				?>
                <li>
                	<?php 
					bh_thumbnail($post->ID,'thumbnail',true);
					/*  if(has_post_thumbnail()){ ?>
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                    <?php }
					else {
					 ?>
					<a href="<?php the_permalink(); ?>"><?php get_the_image(array(
												'meta_key' => 'wpcf-external-thumb',
												'attachment' => 'false',
												'the_post_thumbnail' => 'true',
												'size' => 'medium',
												'link_to_post'=>'false',
												'height'=>'160'
									
		)); ?></a>
                    <?php } */
					 ?>
                    <br/><a class="booktitle" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</li>
				<?php
				}
				endwhile;
				?>
				</ul>
            	</div>
				<?php
				endif;
				wp_reset_query();
				//wp_reset_postdata();
				?>
                
            </div><!-- .product-links -->
        </div> <!-- .inner -->
</div> <!-- #wrapper -->  
            <!-- end of #post-<?php the_ID(); ?> -->
  
            <?php comments_template( '', true ); ?>
            
        <?php endwhile; ?> 
        
        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'minimum' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'minimum' ) ); ?></div>
		</div><!-- end of .navigation -->
        <?php endif; ?>

	    <?php else : ?>

      <!--   <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'minimum'); ?></h1> -->
                    
        <p><?php _e('Product not found.', 'minimum'); ?></p>
                    
        <h6><?php printf( __('You can return %s or search for the page you were looking for.', 'minimum'),
	            sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
		            esc_url( get_home_url() ),
		            esc_attr__('Home', 'minimum'),
		            esc_attr__('&larr; Home', 'minimum')
	                )); 
			 ?></h6>
                    
        <?php get_search_form(); ?>


      

<?php endif; ?> 
        </div><!-- end of #content -->
</div> 
<div class="newsletter-form-footer grid col-940">
			<div class="inner">
					<div class="grid col-380">	
					<h3>Sign up for our Newsletter</h3>
                    <p class="desktop-newsletter-text"></p>
                    </div>
                    <div class="grid col-540 fit">
                    <?php  bhpub_mailchimp_embed(); 
					 //<iframe src="http://www2.bhpublishinggroup.com/bhpub_newsletters.asp" width="100%"></iframe> ?>
                    </div>
					<div class="mobile-newsletter-text"><p>*We'll never share your information with others.</p></div>
			</div>
		<!-- </div> I think this was the old #wrapper-->
<?php //get_footer(); 
} // end app product page
// add jquery script to footer
function bh_app_product_page_scripts(){
?>
<script>
// enable jCarousel 

jQuery(document).ready(function() {
	//buy now button
	jQuery('a.buybtn').not('.apps').click(function(e){
	jQuery(this).next('.retailers').slideToggle();
	e.preventDefault();
	});
	
	//mobile expanding headers
	jQuery('.mobile-section-hdr a').not('.apps').click(function(e){
		jQuery(this).parents('.mobile-section-hdr').toggleClass("open").next('.mobile-expanded').slideToggle();
		e.preventDefault();
	});
	
	//jQuery(".mobile-expanded").hide();
	
	// initialize multiple carousels by class
	jQuery('ul.booklist').jcarousel();
		
	
});
</script>
<?php 
}
add_action('genesis_after_footer','bh_app_product_page_scripts');


genesis(); ?>