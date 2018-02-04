<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * New and Noteworthy Page Template
 *
   Template Name:  Articles
 *
 *
 * @file           single-cat-articles.php
 */
?>

<?php //get_header();
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_single_article_loop' );
function bh_single_article_loop(){
 ?>

<div class="inner">

<div class="sidebar grid fit share-article">
<?php //get_sidebar('information'); 
//sharing buttons instead
?>
<h3>Share Article</h3>
<div class="social-buttons">
<?php
// tweet button, fb like button
 //if ( function_exists( 'ngfb_get_social_buttons' ) )
//echo ngfb_get_social_buttons( array( 'pinterest','twitter','facebook' ) ); 

// Social Sharing Buttons (YOAST code)
// could also be done as template part, plugin, or in functions.php
?>
<ul class="social buttons">
	   <li>
	   <!-- <fb:like href="<?php the_permalink() ?>" send="true" showfaces="false" width="120" layout="button_count" action="like"/></fb:like> -->
        <div class="fb-like" data-href="<?php the_permalink() ?>" data-width="120"  data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="true"></div>
     </li>
     <li>
	    <a href="http://twitter.com/share" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-via="example" class="twitter-share-button">Tweet</a>
	</li>
    <li>
    	 <a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&amp;media=<?php echo urlencode($pinit_image)  ?>&amp;description=<?php urlencode(get_the_title()); ?>" class="pin-it-button" data-pin-config="beside" data-pin-do="buttonPin">Pin It</a>
    </li>
    

	  
<?php 	
//don't show these extra buttons
	/*
      <li>
	    <g:plusone size="medium" callback="plusone_vote"></g:plusone>
	  </li>
	  <li>
	    <script type="in/share" data-url="<?php the_permalink(); ?>"  data-counter="right"></script>
	  </li>
	  <li>
	    <div id="stumbleupon-button"></div>
	  </li>
       
  */   
  ?>
</ul>
<?php //end social share buttons ?>

</div>
	
</div>

        <div id="content" class="grid col-940 fit">


        
<?php if (have_posts()) : ?>
		<div class="grid col-620">
        <div class="article-text">
		

		<?php while (have_posts()) : the_post(); ?>
        
          
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="post-title"><?php the_title(); ?></h1>
                                
                <div class="post-entry">
                    <?php the_content(__('Read more &#8250;', 'responsive')); ?>
                    
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
                <div class="navigation">
			        <div class="previous"><?php previous_post_link( '&#8249; %link' ); ?></div>
                    <div class="next"><?php next_post_link( '%link &#8250;' ); ?></div>
		        </div><!-- end of .navigation -->
                
                <div class="post-data">
				    <?php the_tags(__('Tagged with:', 'responsive') . ' ', ', ', '<br />'); ?> 
					<?php //printf(__('Posted in %s', 'responsive'), get_the_category_list(', ')); ?> 
                </div><!-- end of .post-data -->             

            <div class="post-edit"><?php edit_post_link(__('Edit', 'responsive')); ?></div>             
            </div><!-- end of #post-<?php the_ID(); ?> -->
            
            
        <?php endwhile; ?> 

        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'responsive' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'responsive' ) ); ?></div>
		</div><!-- end of .navigation -->
        <?php endif; ?>


        </div>
        </div>

	    <?php else : ?>

        <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'responsive'); ?></h1>
                    
        <p><?php _e('Sorry, I could&#39;nt find the Press Release you requested.', 'responsive'); ?></p>
                    
        <h6><?php printf( __('You can return %s or search for the page you were looking for.', 'responsive'),
	            sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
		            esc_url( get_home_url() ),
		            esc_attr__('Home', 'responsive'),
		            esc_attr__('&larr; Home', 'responsive')
	                )); 
			 ?></h6>
                    
        <?php get_search_form(); ?>

<?php endif; ?>  

      
        
        <div class="grid col-300 fit articles-list">
        <h3>Related Stories</h3>
        <?php //get_sidebar(); 
	//list press releases
wp_reset_query();	
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //pagination		
$args = array(	
							'category_name' => 'articles',
							'paged' => $paged,
							'posts_per_page' => '16'
						);

$pr = new WP_Query($args);

	if($pr -> have_posts()):
	echo '<ul>';
		while ( $pr->have_posts() ):
		$pr->the_post(); 
		//$pr_link = "/press-releases/?p=".get_the_ID();
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
<?php }

//get_footer(); 
function bh_article_page_append_script(){
	if(!$bh_fb_app_id){
	$bh_fb_app_id='264893110213933';
	}
?>
<script>
jQuery(document).ready(function() {
		
	// ==  Twitter Tracking Buttons == //
	var e = document.createElement('script');
	e.type="text/javascript"; e.async = true;
	e.src = 'http://platform.twitter.com/widgets.js';
	document.getElementsByTagName('head')[0].appendChild(e);
	jQuery(e).load(function() {
	  function tweetIntentToAnalytics(intent_event) {
	    if (intent_event) {
	      var label = intent_event.data.tweet_id;
	      _gaq.push(['_trackEvent', 'twitter_web_intents',
	      intent_event.type, label]);
	    }
	  }
	  function followIntentToAnalytics(intent_event) {
	    if (intent_event) {
	      var label = intent_event.data.user_id + " (" +
	     intent_event.data.screen_name + ")";
	      _gaq.push(['_trackEvent', 'twitter_web_intents',
	     intent_event.type, label]);
	    }
	  }
	  twttr.events.bind('tweet',    tweetIntentToAnalytics);
	  twttr.events.bind('follow',   followIntentToAnalytics);
	});
	
	// load other social buttons scripts
	 // == Simple Buttons == //
	 var e = document.createElement('script');
	 e.type="text/javascript"; e.async = true;
	 e.src = 'http://assets.pinterest.com/js/pinit.js';
	 document.getElementsByTagName('head')[0].appendChild(e);
	 
	/*
	// Load Tweet Button Script
	 var e = document.createElement('script');
	 e.type="text/javascript"; e.async = true;
	 e.src = 'http://platform.twitter.com/widgets.js';
	 document.getElementsByTagName('head')[0].appendChild(e);
	 
	 // Load Plus One Button 
	var e = document.createElement('script');
	e.type="text/javascript"; e.async = true;
  	e.src = 'https://apis.google.com/js/plusone.js';
  	document.getElementsByTagName('head')[0].appendChild(e);
	*/
		
});

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $bh_fb_app_id ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

/*
// Facebook JS w/tracking
window.fbAsyncInit = function() {
	  FB.init({appId: '<?php echo $bh_fb_app_id ?>', status: true, cookie: true, xfbml: true});
	  FB.Event.subscribe("edge.create",function(response) {
	    if (response.indexOf("facebook.com") > 0) {
	      // if the returned link contains 'facebook,com'. It is a 'Like'
	      // for your Facebook page
	      _gaq.push(['_trackEvent','Facebook','Like',response]);
	    } else {
	      // else, somebody is sharing the current page on their wall
	      _gaq.push(['_trackEvent','Facebook','Share',response]);
	    }
	  });
	  FB.Event.subscribe("message.send",function(response){
	    _gaq.push(['_trackEvent','Facebook','Send',response]);
	  });
	};
*/

</script>
<?php
} // end bh_product_page_append_script

function bh_article_add_fb_root(){
	echo ('<div id="fb-root"></div>');
}
add_action('genesis_after_header','bh_article_add_fb_root');
add_action( 'genesis_after_footer', 'bh_article_page_append_script' );


genesis();
?>
