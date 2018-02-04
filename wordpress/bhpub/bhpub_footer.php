<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Footer Template
 *
 *
 * @file           footer.php

 */

//    </div><!-- end of #wrapper -->
//</div><!-- end of #container -->

//<div id="footer" class="clearfix">

//    <div id="footer-wrapper">
?> 

   <div id="footer-wrapper">   
        <div class="grid col-940">
        
        <div class="grid col-620">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/img/bh-logo-sm.png" width="56" height="43" class="footer-bh-logo" /> 
        
		<?php if (has_nav_menu('footer-menu')) { ?>
	        <?php wp_nav_menu(array(
				    'container'       => '',
					'fallback_cb'	  =>  false,
					'menu_class'      => 'footer-menu',
					'theme_location'  => 'footer-menu')
					); 
				?>
         <?php } ?>
         </div>
        <!-- end of col-540 -->
         
         <div class="grid col-300 fit">
         <?php //$options = genesis_get_option('responsive_theme_options');
					
            // First let's check if any of this was set
			$fb_url = genesis_get_option('facebook_url');
			$tw_url = genesis_get_option('twitter_url');
			$yt_url = genesis_get_option('youtube_url');
			$pin_url = genesis_get_option('pinterest_url');
			$ig_url = genesis_get_option('instagram_url');
		
		
                echo '<ul class="social-icons">';
					
                  if (!empty($fb_url)) echo '<li class="facebook-icon"><a href="' . $fb_url . '">'
                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/facebook-icon.png" width="35" height="34" alt="Facebook">'
                    .'</a></li>';
			 
			    if (!empty($tw_url)) echo '<li class="twitter-icon"><a href="' . $tw_url . '">'
                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/twitter-icon.png" width="35" height="34" alt="Twitter">'
                    .'</a></li>';
 
					
                if (!empty($yt_url)) echo '<li class="youtube-icon"><a href="' . $yt_url . '">'
                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/youtube-icon.png" width="35" height="34" alt="YouTube">'
                    .'</a></li>';
	
					
                if (!empty($pin_url)) echo '<li class="pinterest-icon"><a href="' . $pin_url . '">'
                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/pinterest-icon.png" width="35" height="34" alt="Pinterest">'
                    .'</a></li>';
					
				 if (!empty($ig_url)) echo '<li class="instagram-icon"><a href="' . $ig_url . '">'
                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/instagram-icon.png" width="35" height="34" alt="Instagram">'
                    .'</a></li>';
					
  
                echo '</ul><!-- end of .social-icons -->';
				
				echo '<ul class="social-icons mobile">'; //mobile buttons
					

			   
			    if (!empty($tw_url)) echo '<li class="twitter-icon"><a href="' . $tw_url . '">'
                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/tw-color.png" width="32" height="32" alt="Twitter">'
                    .'</a></li>';

                if (!empty($fb_url)) echo '<li class="facebook-icon"><a href="' . $fb_url . '">'
                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/fb-color.png" width="32" height="32" alt="Facebook">'
                    .'</a></li>';
					
                if (!empty($yt_url)) echo '<li class="youtube-icon"><a href="' . $yt_url . '">'
                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/yt-color.png" width="32" height="32" alt="YouTube">'
                    .'</a></li>';
					
				if (!empty($pin_url)) echo '<li class="pinterest-icon"><a href="' . $pin_url . '">'
                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/pin-color.png" width="32" height="32" alt="Pinterest">'
                    .'</a></li>';
					
				if (!empty($ig_url)) echo '<li class="instagram-icon"><a href="' . $ig_url . '">'
                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/ig-color.png" width="35" height="34" alt="Instagram">'
                    .'</a></li>';
					
				 echo '</ul><!-- end of .social-icons -->';	
         ?>
         </div><!-- end of col-380 fit -->
         <h3 class="mobile-site-menu-hdr"><span>Site Menu</span></h3>
          <?php if (has_nav_menu('secondary', 'minimum')) { ?>
	            <?php wp_nav_menu(array(
				    'container'       => '',
					'menu_class'      => 'secondary sub-header-menu',
					'theme_location'  => 'secondary')
					); 
				?>
            <?php } ?>
         
         </div><!-- end of col-940 -->
         
		 
		 <?php get_sidebar('colophon'); ?>
                
        <div class="grid col-940 copyright">
            <?php esc_attr_e('&copy;', 'responsive'); ?> <?php _e(date('Y')); ?><a href="<?php echo home_url('/') ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
                <?php bloginfo('name'); ?>
            </a> One LifeWay Plaza Nashville, TN 37234
        </div><!-- end of .copyright -->
 
    </div><!-- end #footer-wrapper -->
    

<?php  
 //wp_footer(); 
/*

// unused social icons

	//                if (!empty($options['linkedin_uid'])) echo '<li class="linkedin-icon"><a href="' . $options['linkedin_uid'] . '">'
//                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/linkedin-icon.png" width="35" height="34" alt="LinkedIn">'
//                    .'</a></li>';				
									
//                if (!empty($options['stumble_uid'])) echo '<li class="stumble-upon-icon"><a href="' . $options['stumble_uid'] . '">'
//					.'<img src="' . get_stylesheet_directory_uri() . '/icons/stumble-upon-icon.png" width="35" height="34" alt="StumbleUpon">'
//                    .'</a></li>';
					
 //               if (!empty($options['rss_uid'])) echo '<li class="rss-feed-icon"><a href="' . $options['rss_uid'] . '">'
//                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/rss-feed-icon.png" width="35" height="34" alt="RSS Feed">'
//                    .'</a></li>';
       
//                if (!empty($options['google_plus_uid'])) echo '<li class="google-plus-icon"><a href="' . $options['google_plus_uid'] . '">'
//                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/googleplus-icon.png" width="35" height="34" alt="Google Plus">'
//                    .'</a></li>';
					
//                if (!empty($options['instagram_uid'])) echo '<li class="instagram-icon"><a href="' . $options['instagram_uid'] . '">'
//                    .'<img src="' . get_stylesheet_directory_uri() . '/icons/instagram-icon.png" width="35" height="34" alt="Instagram">'
//                    .'</a></li>';
					
 //               if (!empty($options['yelp_uid'])) echo '<li class="yelp-icon"><a href="' . $options['yelp_uid'] . '">'
 //                   .'<img src="' . get_stylesheet_directory_uri() . '/icons/yelp-icon.png" width="35" height="34" alt="Yelp!">'
 //                   .'</a></li>';
					
 //               if (!empty($options['vimeo_uid'])) echo '<li class="vimeo-icon"><a href="' . $options['vimeo_uid'] . '">'
 //                   .'<img src="' . get_stylesheet_directory_uri() . '/icons/vimeo-icon.png" width="35" height="34" alt="Vimeo">'
 //                   .'</a></li>';
					
 //               if (!empty($options['foursquare_uid'])) echo '<li class="foursquare-icon"><a href="' . $options['foursquare_uid'] . '">'
   //                 .'<img src="' . get_stylesheet_directory_uri() . '/icons/foursquare-icon.png" width="35" height="34" alt="foursquare">'
  //                  .'</a></li>';
           


</div><!-- end #footer -->
</body>
</html>
*/
?>