<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Pages Template
 *
 *
 * @file           single-bhauthor.php
 */
?>
<?php
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_single_author_post_loop' );
?>

<?php //get_header(); 
function bh_single_author_post_loop(){
?>

<div class="inner">

<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>

 <div class="grid col-460 author-top-left">       
              <?php 
		//replace with genesis breadcrumbs
		//$options = get_option('responsive_theme_options');
		// if ($options['breadcrumb'] == 0): 
		// echo responsive_breadcrumb_lists(); 
       //  endif;
	   ?>
        
        <div class="mobile-author-header">
        	<h1 class="post-title"><?php the_title(); ?></h1>
        </div>
</div>
<div class="grid col-460 fit author-top-right">
<?php
// tweet button, fb like button
 if ( function_exists( 'ngfb_get_social_buttons' ) )
echo ngfb_get_social_buttons( array( 'twitter','facebook' ) ); 

$authorname = get_the_slug();


?>
</div>
<div class="author-content">
<div class="grid col-300">
<div width="300">&nbsp;</div>
<?php //get_sidebar();
// main author image 
 //if(has_post_thumbnail()){
//				the_post_thumbnail("large");
//}
bh_thumbnail(get_the_ID(), 'large');
			
// additional images gallery (if exist) v2
?>  <div class="mobile-social-links"> <?php	
//mobile social media
	// links to official site, twitter, facebook
				if (types_render_field('author-blog-link', array("raw"=>"true")) || types_render_field('twitter-handle', array("raw"=>"true")) ||types_render_field('facebook-page', array("raw"=>"true"))):
				?>
                <div class="mobile-social-txt">find <?php the_title() ?> on...</div>
                <div class="author-social-links">
                	<ul>
                <?php
				if (types_render_field('official-site-link', array("raw"=>"true"))):
					echo '<li><a href="'.types_render_field('official-site-link', array("raw"=>"true")).'" target="_blank" class="site-link">site</a></li>';
				endif;
				
				if (types_render_field('twitter-handle', array("raw"=>"true"))):
					$twhandle = str_replace('@','',types_render_field('twitter-handle', array("raw"=>"true")));//remove @ if it's there
					echo '<li><a href="http://twitter.com/'.$twhandle.'" target="_blank" class="twitter-link">twitter</a></li>';
				endif;
				
				if (types_render_field('facebook-page', array("raw"=>"true"))):
					$fburl=types_render_field('facebook-page', array("raw"=>"true"));
					$fbpage = str_replace("https://facebook.com/","",$fburl);
					echo '<li><a href="'.$fburl.'" target="_blank" class="facebook-link">facebook</a></li>';
				endif;
				
				?>
         </ul>
       </div>          	       	
	<?php endif; ?>
    </div>
</div>
        <div id="content" class="grid col-620 fit">

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="post-title"><?php the_title(); ?></h1>
				
                <?php
                //get this post ID for related authors search
				$authorpostID = get_the_ID();
				
				// insert text links from custom field or most recent product
				
				// links to official site, twitter, facebook
				if (types_render_field('author-blog-link', array("raw"=>"true")) || types_render_field('twitter-handle', array("raw"=>"true")) ||types_render_field('facebook-page', array("raw"=>"true"))):
				?>
                <div class="author-social-links">
                	<ul>
                <?php
				if (types_render_field('official-site-link', array("raw"=>"true"))):
					echo '<li><a href="'.types_render_field('official-site-link', array("raw"=>"true")).'" target="_blank" class="site-link">official site</a></li>';
				endif;
				
				if (types_render_field('twitter-handle', array("raw"=>"true"))):
					$twhandle = str_replace('@','',types_render_field('twitter-handle', array("raw"=>"true")));//remove @ if it's there
					echo '<li><a href="http://twitter.com/'.$twhandle.'" target="_blank" class="twitter-link">@'.$twhandle.'</a></li>';
				endif;
				
				if (types_render_field('facebook-page', array("raw"=>"true"))):
					$fburl=types_render_field('facebook-page', array("raw"=>"true"));
					$fbpage = str_replace("https://facebook.com/","",$fburl);
					echo '<li><a href="'.$fburl.'" target="_blank" class="facebook-link">'.$fbpage.'</a></li>';
				endif;
				
				?>
                	</ul>
                 </div>
                <?php endif; ?>
				<?php /* //comments disabled
                <?php if ( comments_open() ) : ?>               
                <div class="post-meta">
                <?php responsive_post_meta_data(); ?>
				    <?php if ( comments_open() ) : ?>
                        <span class="comments-link">
                        <span class="mdash">&mdash;</span>
                    <?php comments_popup_link(__('No Comments &darr;', 'minimum'), __('1 Comment &darr;', 'minimum'), __('% Comments &darr;', 'minimum')); ?>
                        </span>
                    <?php endif; ?> 
                </div><!-- end of .post-meta -->
                <?php endif; ?> 
                */ ?>
                
                <div class="post-entry">
                    <?php the_content(__('Read more &#8250;', 'minimum')); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'minimum'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
                <?php if ( comments_open() ) : ?>
                <div class="post-data">
				    <?php the_tags(__('Tagged with:', 'minimum') . ' ', ', ', '<br />'); ?> 
                    <?php the_category(__('Posted in %s', 'minimum') . ', '); ?> 
                </div><!-- end of .post-data -->
                <?php endif; ?>             
            
            <div class="post-edit"><?php edit_post_link(__('Edit', 'minimum')); ?></div> 
            </div><!-- end of #post-<?php the_ID(); ?> -->
            
            <?php /* //comments disabled
			<?php comments_template( '', true ); ?>
             */?>
             
        <?php // section 2 - similar authors, books by author ?>
             </div><!-- end of #content -->
        </div>
        <div class="author-booklists">
            <div class="grid col-300">
            <?php //find authors in same category
	
		//exclude supplies
		$s_child_cats = (array) get_term_children('19', 'category');
		//exclude apps 
		$a_child_cats = (array) get_term_children('2','category');		
		$exclude_cats = implode(',',array_merge(array('19,2'),$s_child_cats,$a_child_cats));
		$inc_cats = get_terms('category',array('fields'=>'ids','exclude'=>$exclude_cats)); 	
			$args = array(
						'post_type' => 'products',
						'a'=>$authorname, //custom taxonomy
						//'category__not_in' => array_merge(array('19'), $child_cats), //books only, not supplies
						'category__in' => $inc_cats,
						'meta_key' => 'wpcf-pubdate',
						'orderby' => 'meta_value_num', //newest first
						'tag'=>'listed' 
						);
			$booksbyauthor = new WP_Query($args);
			$catlist=array();
			//check custom field for related authors  //later
		
			//if empty, automatically choose authors from the same category(ies)
			//get category of all books by author
			while ( $booksbyauthor->have_posts() ) :
					$booksbyauthor->the_post();
					$currentcat=get_the_category();
					foreach($currentcat as $cat){
						array_push($catlist, $cat->cat_ID);
					}
			endwhile;
			//var_dump($catlist); //testing, should return array of ids
			$catIDs=  implode(',',$catlist); // should display a comma separated list of category ids
			
			$booksbycategory = new WP_Query('post_type=products&cat='.$catIDs);
			$thisauthor = get_term_by('slug', $authorname, 'a');
			//echo $thisauthor->term_id;
			$authortags = array();
			$bookIDs = array();
			//get authors of those books (using tag or custom taxonomy)
			while ( $booksbycategory->have_posts() ) :
					$booksbycategory->the_post();
					//$currentauthor=get_terms('a', 'exclude='.$thisauthor->term_id);
					//$currentauthor = get_the_terms($post->ID, 'a');
					array_push($bookIDs, get_the_ID());
					
					//foreach($currentauthor as $atag){
					//	array_push($authortags,$atag->slug);	
				//	}	
			endwhile;
			
			$currentauthor = wp_get_object_terms($bookIDs, 'a');
					foreach($currentauthor as $atag){
						//exclude current author
						if ($atag->slug!=$authorname){
						array_push($authortags,$atag->slug);	
						}
					}
					
			$authorslugs =  implode(',',$authortags); // should display a comma separated list of slugs
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
			//find authors WITH Photos
			$aargs = array(
					'post_type'=>'bhauthor',
					'posts_per_page'=>'6',
					'post__in'=>$authorids,
					'meta_query'=> array(
															'relation'=>'OR',
															array(
																		'key'=>'wpcf-external-thumb'
																		),
															array(
																		'key'=>'_thumbnail_id'
																		)
															)
			);
			//convert author tag slugs into links to author posts
			$authorposts = new WP_query($aargs); //post_type=authors&posts_per_page=6&name='.$authorslugs
			
			if($authorposts->have_posts() ): ?>
				            <h2>Similar Authors</h2>
				<?php 
				while ( $authorposts->have_posts() ) :
					$authorposts->the_post();
					//display the author thumbnail and name
					?> <li class="author-thumb"> <?php
					 bh_thumbnail(get_the_ID(), 'thumbnail', true,'photo');
					?>
                            <a class="text-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                            <?php 
	
				endwhile;
			endif;
			wp_reset_postdata();
	
			?>
            </div>
            <div class="grid col-620 fit">
            <h2>Books <span class="booksby">by</span> <?php the_title(); ?></h2>
			<ul class="author-booklist">
            <?php //show list of books
			$booksbyauthor->rewind_posts(); //reset to beginning of wp_query
			while ( $booksbyauthor->have_posts() ) :
					$booksbyauthor->the_post();
					
					/*<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail('medium'); ?></a>
					*/ //replaced by bh_thumbnail()
					$short_title = wp_trim_words(get_the_title(),8);
					?>
				<li class="book-thumb">
                			<?php bh_thumbnail(get_the_ID(),'medium',true); ?>
                            <a href="<?php the_permalink(); ?>" class="text-link"><?php echo $short_title; ?></a></li>
                            <?php
				endwhile;
			wp_reset_postdata();
			
			?>
			</ul>
            </div>
        </div>
        
        <div class="author-generated-content">
        <?php // section 3 - tweets/blog posts
		$green_arrow = get_stylesheet_directory_uri().'/img/btn-arrow-down-green.png';
		
		
		if(types_render_field('author-blog-rss', array("raw"=>"true"))): ?>
			<div class="author-blog-feed grid col-940">
             <h2>Blog Posts</h2>
        	<h4 class="mobile-section-hdr"><a href="#">Blog Posts <img src="<?php echo $green_arrow ?>" /></a></h4>
                        <div class="mobile-expanded">
            <div class="author-blog-posts">
       
        <?php if(function_exists('fetch_feed')) {  
 					$a = true;
					include_once(ABSPATH . WPINC . '/feed.php'); // the file to rss feed generator  
					$feed = fetch_feed(types_render_field('author-blog-rss', array("raw"=>"true"))); // specify the rss feed  
				  
					$limit = $feed->get_item_quantity(7); // specify number of items  
					$items = $feed->get_items(0, $limit); // create an array of items  
				  
				}  
				if ($limit == 0) echo '<div>The feed is either empty or unavailable.</div>';  
				else foreach ($items as $item) : ?>  
				 <?php //if($a){var_dump($item);}$a=false;?>
				<h4><a href="<?php echo $item->get_permalink(); ?>" alt="<?php echo $item->get_title(); ?>" target="_author_blog"><?php echo $item->get_title(); ?></a></h4>  
				<p><?php echo $item->get_author()->get_name();?> | <?php echo $item->get_date('F j');//echo $item->get_date('j F Y @ g:i a'); ?></p>  
				<!-- <p><?php //echo substr($item->get_description(), 0, 200); ?> ...</p> -->  
				  
				<?php endforeach; ?> 
				</div></div>
        </div>
        
		
		<?php

		endif;

		/*  //BLOG POSTS ONLY - remove automatic twitter feed

		if(types_render_field('author-blog-rss', array("raw"=>"true")) && types_render_field('twitter-handle', array("raw"=>"true"))):
		//if both, two columns
		$twhandle = str_replace('@','',types_render_field('twitter-handle', array("raw"=>"true")));//remove @ if it's there
		?>
        <div class="twitter-feed grid col-460">
        <h4 class="mobile-section-hdr"><a href="#">Tweets <img src="<?php echo $green_arrow ?>" /></a></h4>
         <div class="mobile-expanded">
        <h2>&nbsp;<!-- Tweets <span class="twauthor">@<?php echo $twhandle ?></span>--></h2> 
        <?php
		//if( shortcode_exists( 'minitwitter' ) ) {  //WP3.6 coming soon	
       	// echo do_shortcode('[minitwitter username="'.$twhandle.'"]'); //API v1 retired - replace with embedded tweets until plugin update
		?>
		<a class="twitter-timeline"  href="https://twitter.com/BHpub"  data-widget-id="314470580529274880" data-screen-name="<?php echo $twhandle ?>" data-show-replies="false" data-tweet-limit="5"></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div></div>
        <div class="author-blog-feed grid col-460 fit">
        <h2>Blog Posts</h2>
         <h4 class="mobile-section-hdr"><a href="#">Blog Posts <img src="<?php echo $green_arrow ?>" /></a></h4>
                        <div class="mobile-expanded">
        <div class="author-blog-posts">
       
        <?php if(function_exists('fetch_feed')) {  
 					$a = true;
					include_once(ABSPATH . WPINC . '/feed.php'); // the file to rss feed generator  
					$feed = fetch_feed(types_render_field('author-blog-rss', array("raw"=>"true"))); // specify the rss feed  
				  
					$limit = $feed->get_item_quantity(7); // specify number of items  
					$items = $feed->get_items(0, $limit); // create an array of items  
				  
				}  
				if ($limit == 0) echo '<div>The feed is either empty or unavailable.</div>';  
				else foreach ($items as $item) : ?>  
				 <?php //if($a){var_dump($item);}$a=false;?>
				<h4><a href="<?php echo $item->get_permalink(); ?>" alt="<?php echo $item->get_title(); ?>" target="_author_blog"><?php echo $item->get_title(); ?></a></h4>  
				<p><?php echo $item->get_author()->get_name();?> | <?php echo $item->get_date('F j');//echo $item->get_date('j F Y @ g:i a'); ?></p>  
				<!-- <p><?php //echo substr($item->get_description(), 0, 200); ?> ...</p> -->  
				  
				<?php endforeach; ?> 
				</div></div>
        </div>
        
        <?php //otherwise just a single column
		elseif(types_render_field('author-blog-rss', array("raw"=>"true")) || types_render_field('twitter-handle', array("raw"=>"true"))):
			if(types_render_field('author-blog-rss', array("raw"=>"true"))): ?>
			<div class="author-blog-feed grid col-940">
             <h2>Blog Posts</h2>
        	<h4 class="mobile-section-hdr"><a href="#">Blog Posts <img src="<?php echo $green_arrow ?>" /></a></h4>
                        <div class="mobile-expanded">
            <div class="author-blog-posts">
            
		<?php
			 if(function_exists('fetch_feed')) {  
  
    include_once(ABSPATH . WPINC . '/feed.php'); // the file to rss feed generator  
    $feed = fetch_feed(types_render_field('author-blog-rss', array("raw"=>"true"))); // specify the rss feed  
  
    $limit = $feed->get_item_quantity(7); // specify number of items  
    $items = $feed->get_items(0, $limit); // create an array of items  
  
}  
if ($limit == 0) echo '<div>The feed is either empty or unavailable.</div>';  
else foreach ($items as $item) : ?>  
  
<h4><a href="<?php echo $item->get_permalink(); ?>" alt="<?php echo $item->get_title(); ?>" target="_author_blog"><?php echo $item->get_title(); ?></a></h4>  
<p><?php echo $item->get_date('f J'); ?></p>   
  
<?php endforeach;  
			endif;
			if(types_render_field('twitter-handle', array("raw"=>"true"))): ?>
			<div class="twitter-feed grid col-940">
            <h2>&nbsp; <!-- Tweets <span class="twauthor">@<?php echo $twhandle ?></span>--></h2>
            <h4 class="mobile-section-hdr"><a href="#">Tweets <img src="<?php echo $green_arrow ?>" /></a></h4>
                        <div class="mobile-expanded">
		<?php	
		//if( shortcode_exists( 'minitwitter' ) ) {
			$twhandle = str_replace('@','',types_render_field('twitter-handle', array("raw"=>"true")));//remove @ if it's there
       	 //do_shortcode('[minitwitter username="'.$twhandle.'"]');
		 ?>
		 <a class="twitter-timeline"  href="https://twitter.com/BHpub"  data-widget-id="314470580529274880" data-screen-name="<?php echo $twhandle ?>" data-show-replies="false" data-tweet-limit="5"></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	<?php
    //	}
			endif;
			
		*/ ?>
		</div>
        
        
		<?php
		// endif;


		
		?>
        
        </div>    
        <?php endwhile; ?> 
		
        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'minimum' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'minimum' ) ); ?></div>
		</div><!-- end of .navigation -->
        <?php endif; ?>

	    <?php else : ?>
       <div class="inner">
       <div id="content" class="grid col-940">
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
	
			</div>
		</div>

<?php 
} // end bh single product post loop
//get_footer(); 
function bh_author_page_append_script(){
?>
<script>
// enable jCarousel 

jQuery(document).ready(function() {
	
	//mobile expanding headers
	jQuery('.mobile-section-hdr a').not('.apps').click(function(e){
		jQuery(this).parents('.mobile-section-hdr').toggleClass("open").next('.mobile-expanded').slideToggle();
		e.preventDefault();
	});
	
//	jQuery(".mobile-expanded").hide();
	
	// initialize multiple carousels by class
	jQuery('ul.author-booklist').jcarousel();
	
	
});
</script>
<?php
} // end bh_author_page_append_script
add_action( 'genesis_after_footer', 'bh_author_page_append_script' );
genesis();
?>
