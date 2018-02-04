<?php
/**
 * Plugin Name: BHpub Product Gallery
 * Plugin URI: http://www.bhpublishinggroup.com
 * Description: Display additional product images - shortcode and automatic
 * Version: 1.1
 * Author: Bobby Reinsch
 * Author URI: http://www.bhpublishinggroup.com
 * License: GPL2
 *  Copyright 2015  Bobby Reinsch  (email : bobby.reinsch@bhpublishinggroup.com)
	

	plugin functionality
	
	- detect all images for product
		- first check server
		- then check WP Media Gallery (images attached to post)
		
	- add to image array
	
	- sort/order array(?)
	
	- display as gallery (bh-display-gallery)
		- test script gallery: jcarousel + lightbox, flexslider, lightslider, lightgallery
		- options: 	thumbnails only
							thumbnails and large image
							large image only
							
	 - implementation
	 	- auto insert for bibles (if images present)
		- v2 admin checkbox for categories? display if tagged or in category or parent category
		- shortcode for other products
	
*/

global $wp_query;

//add script options:
// jcarousel for initial list *
//			-use Flexslider + Lightbox
//			-use LightSlider
//			-use LightGallery* chosen selection

//add_action('admin_init','bh_product_gallery_admin_init');
//add_action('admin_menu','bh_product_gallery_admin_menu');


function bh_product_gallery_admin_init(){
	register_setting('bhpub-product-gallery','bh_product_gallery_options');	
}

function bh_product_gallery_admin_menu(){
	add_options_page( 'Product Gallery Options', 'BHpub Product Gallery', 'manage_options', 'bhpub-product-gallery', 'bh_product_gallery_options_page' );
}


function bh_product_gallery_options_page(){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	screen_icon();
	echo '<h2 class="dashicons-before dashicons-desktop">Product Gallery Options</h2>';
	echo '<form action="options.php" method="post">';
	settings_fields('bhpub_product_gallery_options');
	$bh_gallery_options = get_option('bh_gallery_settings');
	?>
    <p>Choose a gallery application:</p>
	
	<input type="radio" name="bh_gallery_settings[radio1]" value="jCarousel"   <?php checked('jCarousel', $bh_gallery_options['radio1']); ?> />jCarousel (default)<br />
    <input type="radio" name="bh_gallery_settings[radio1]" value="Flexslider"   <?php checked('Flexslider', $bh_gallery_options['radio1']); ?> />Flexslider<br />
  	<input type="radio" name="bh_gallery_settings[radio1]" value="LightSlider" <?php checked('LightSlider', $bh_gallery_options['radio1']); ?> />Lightslider<br />     
    <input type="radio" name="bh_gallery_settings[radio1]" value="LightGallery"   <?php checked('LightGallery', $bh_gallery_options['radio1']); ?> />Lightgallery<br />   

	<?php
	submit_button();
	echo '</form>';
	echo '</div>';
}






function bh_product_gallery_add_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script( "bh_lightgallery", path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/light-gallery/js/lightGallery.min.js"), array( 'jquery' ) );
	wp_enqueue_style( "bh_lightgallery_css", path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/light-gallery/css/lightGallery.css"), false, '1.0', 'all' );
	//jcarousel included in theme
	
	
	if(checked('Flexslider', $bh_gallery_options['radio1'],false)!=''){
	//lightslider - http://sachinchoolur.github.io/lightslider/
	wp_enqueue_script( "bh_flexslider", path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/flexslider/jquery.flexslider-min.js"), array( 'jquery' ) );
	wp_enqueue_style( "bh_flexslider_css", path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/flexslider/flexslider.css"), false, '1.0', 'all' );
	}
	
	if(checked('LightSlider', $bh_gallery_options['radio1'],false)!=''){
	//lightslider - http://sachinchoolur.github.io/lightslider/
	wp_enqueue_script( "bh_lightslider", path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/lightslider/js/lightslider.min.js"), array( 'jquery' ) );
	wp_enqueue_style( "bh_lightslider_css", path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/lightslider/css/lightslider.min.css"), false, '1.0', 'all' );
	}
	
	//if(checked('LightGallery', $bh_gallery_options['radio1'],false)!=''){
	//lightgallery - http://sachinchoolur.github.io/lightGallery/
	// default: lightgallery has been selected
	//wp_enqueue_script( "bh_lightgallery", path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/light-gallery/js/lightgallery.min.js"), array( 'jquery' ) );
	//wp_enqueue_style( "bh_lightgallery_css", path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/light-gallery/css/lightGallery.css"), false, '1.0', 'all' );
//	}
	
	//wp_enqueue_script( "bh_showcase", path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/jquery.aw-showcase.min.js"), array( 'jquery' ) ); //awkward slider
}   // end function add scripts 

//not sure if necessary
add_action('wp_enqueue_scripts', 'bh_product_gallery_add_scripts');




// CURRENTLY INDEPENDENT OF OPTIONS

function bh_product_images(){
	bh_product_gallery();	
}
/*
//jCarousel code
function bh_product_images__wp_Lightbox(){
// gather images from server
global $post;
$img_id = get_post_meta($post->ID,'wpcf-isbn',true);
$images_path = 'img/galleries/'. $img_id.'.*.jpg';
$img_files = glob($images_path);
//print_r($img_files);
// custom order/sort?
// if first item is "back" then move it to the end of the list
		if(strpos($img_files[0],'back') !== false){
			$img_files[] = array_shift($img_files);  //array_shift returns value it removes from the array, img_files[] appends it to the array
		}
		echo '<br />';
// display thumbs
if($img_files){
	$divclass = 'bh-image-gallery'; //default value
	$liclass= 'bh-image-gallery-item'; //default value
	$html_str = ''; //initialize
	$html_str.='<div class="'. $divclass .'"><ul>';
	foreach($img_files as $imgpath){
			
			$html_str.='<li class="'. $liclass .'"><a href="/'. $imgpath .'" rel="wp-video-lightbox" ><img src="/'. $imgpath .'" /></a></li>';
	}
	$html_str.='</ul></div>';
}
echo $html_str;
echo '<style>
.bh-image-gallery {height:150px;background:white;border-radius:.5em;box-shadow: 0 7px 9px -7px rgba(54, 58, 56, 0.25) inset;}
.bh-image-gallery img {height:100px;}
.bh-image-gallery ul {display:block;position:relative;}
.bh-image-gallery li {float:left; margin-right:.3em;list-style-type:none;width:auto;}
</style>
<script>
jQuery(document).ready(function( $ ) {
	$(".bh-image-gallery ul").jcarousel();	
});
</script>';
}

// show full size(?) on click

// scroll through gallery

*/
















//lightgallery code - http://sachinchoolur.github.io/lightGallery/
function bh_product_gallery(){
		// gather images from server
global $post;
$img_id = get_post_meta($post->ID,'wpcf-isbn',true);
$images_path = 'img/galleries/'. $img_id.'.*.jpg';
$img_files = glob($images_path);
//print_r($img_files);
// custom order/sort?
// if first item is "back" then move it to the end of the list
		if(strpos($img_files[0],'back') !== false){
			$img_files[] = array_shift($img_files);  //array_shift returns value it removes from the array, img_files[] appends it to the array
		}
		echo '<br />';
// display thumbs
if($img_files){
	$divclass = 'bh-image-gallery'; //default value
	$liclass= 'bh-image-gallery-item'; //default value
	$html_str = ''; //initialize
	$html_str.='<div class="'. $divclass .'"><ul>';
	foreach($img_files as $imgpath){
			
			$html_str.='<li data-src="/'. $imgpath .'" class="'. $liclass .'"><img src="/'. $imgpath .'" /></a></li>';
	}
	$html_str.='</ul></div>';
}
echo $html_str;
echo '<style>
.bh-image-gallery {height:150px;background:white;border-radius:.5em;box-shadow: 0 7px 9px -7px rgba(54, 58, 56, 0.25) inset;overflow-y:hidden;}
.bh-image-gallery img {height:100px;cursor:pointer;}
.bh-image-gallery ul {display:block;position:relative;}
.bh-image-gallery li {float:left; margin-right:.3em;list-style-type:none;width:auto;min-width:90px;}

#lg-outer.bh-image-gallery-popup  {background-color:white;}
.bh-image-gallery-popup #lg-gallery .thumb-cont {background-color:white;}
.bh-image-gallery-popup #lg-gallery .thumb-info {background: url("img/bg-green-top.png") repeat scroll left top #8aa14c;}
.bh-image-gallery-popup #lg-gallery .thumb-inner {margin-left:auto;margin-right:auto;}
.bh-image-gallery-popup #lg-slider {height:85%;}
.bh-image-gallery-popup #lg-action {position:static;}
.bh-image-gallery-popup #lg-action a {position:absolute;top:40%;}
.bh-image-gallery-popup #lg-action a#lg-prev {left:20px;}
.bh-image-gallery-popup #lg-action a#lg-next {right:20px;}


</style>
<script>
jQuery(document).ready(function( $ ) {
	 $(".bh-image-gallery ul").jcarousel().lightGallery({
			//thumbnails
			showThumbByDefault : true, // Whether to display thumbnails by default.
			addClass: \'bh-image-gallery-popup\',
			animateThumb : true, // Enable thumbnail animation.
			currentPagerPosition : \'middle\', // Position of selected thumbnail.
			thumbWidth : 100, // Width of each thumbnails
			thumbMargin : 5, // Spacing between each thumbnails 
			
			controls : true, // Whether to display prev/next buttons.
			//hideControlOnEnd : false, // If true, prev/next button will be hidden on first/last image.
			loop : true // Allows to go to the other end of the gallery at first/last img. 
			
			
	 }); 
});
</script>';
	
} // end function bibles gallery


function bh_product_gallery_btn(){
		// gather images from server
global $post;
$img_id = get_post_meta($post->ID,'wpcf-isbn',true);
$images_path = 'img/galleries/'. $img_id.'.*.jpg';
$img_files = glob($images_path);
//print_r($img_files);
// custom order/sort?
// if first item is "back" then move it to the end of the list
		if(strpos($img_files[0],'back') !== false){
			$img_files[] = array_shift($img_files);  //array_shift returns value it removes from the array, img_files[] appends it to the array
		}
// display thumbs
if($img_files){
	echo "<style>
	a.look-inside-btn {
		color: #819a2a;
		background: #fff;	
		display: none;
		font-size: 0.72em;
		letter-spacing: 0.075em;
		text-transform: uppercase;
		text-align:right;
		padding-right:5px;
		font-weight:bold;
		-webkit-border-bottom-right-radius: 3px;
		-webkit-border-bottom-left-radius: 3px;
		-moz-border-radius-bottomright: 3px;
		-moz-border-radius-bottomleft: 3px;
		border-bottom-right-radius: 3px;
		border-bottom-left-radius: 3px;
		border:1px solid #eaeaea;
		border-top:none;
		margin:auto;
		margin-bottom: .5em;
		min-width:190px;
	}
	
	a.look-inside-btn:hover {
		background: #f1884b;	
		border:1px solid #f1884b;
		border-top:none;
		color: #fff;
	}

	@media screen and (max-width:650px) {
		a.look-inside-btn {
			text-align:center;
		}
	}
	</style>";
	echo "<a class='look-inside-btn' href='#'>See more photos</a>";
	echo "<script>
	jQuery(document).ready(function( $ ) {
		var img_w = $('.product-page-thumbnail img').width();
		$('a.look-inside-btn')
								.css('display','block')
								.hide()
								.width(img_w-7)  // width minus padding minus border
								.delay(500)
								.slideDown('slow')
								.on('click',function(){
							//scroll to the carousel
								$('html, body').animate({scrollTop: $('.bh-image-gallery').offset().top-80}, 700);
								return false;
		});
	});
	</script>";
	
	}
}


//add_action('wp_head', 'bh_product_gallery');
//add_action('wp_admin', 'bh_product_gallery');

 
