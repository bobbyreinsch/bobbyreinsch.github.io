<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'minimum', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'minimum' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'BH Minimum Pro Theme', 'minimum' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/minimum/' );
define( 'CHILD_THEME_VERSION', '3.0' );

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue scripts
add_action( 'wp_enqueue_scripts', 'minimum_enqueue_scripts' );
function minimum_enqueue_scripts() {

	wp_enqueue_script( 'minimum-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
	//wp_enqueue_style( 'minimum-google-fonts', '//fonts.googleapis.com/css?family=Roboto:300,400|Roboto+Slab:300,400', array(), CHILD_THEME_VERSION );

}

//* Add new image sizes
//add_image_size( 'portfolio', 540, 340, TRUE );
add_image_size('category-header',715,0,false);

//* Add support for custom background
add_theme_support( 'custom-background', array( 'wp-head-callback' => '__return_false' ) );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 320,
	'height'          => 60,
	'header-selector' => '.site-title a',
	'header-text'     => false
) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'site-tagline',
	'nav',
	'subnav',
	'home-featured',
	'site-inner',
	'footer-widgets',
	'footer'
) );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar 
unregister_sidebar( 'sidebar-alt' );

//remove_action('genesis_doctype', 'genesis_do_doctype');
//add_action('genesis_doctype','bhpub_doctype_add_fbml');
function bhpub_doctype_add_fbml(){
?>
<html lang="en-US" prefix="og: http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<?php
}

//* Use logo instead of site title
remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
add_action('genesis_site_title','bh_logo_site_title');
function bh_logo_site_title(){
	echo '<a href="'.get_bloginfo('url').'" title="'. get_bloginfo('name').'"><img src="'.wp_get_attachment_url(313).'" alt="'. get_bloginfo('name') . '"/></a>';	
}


//* Remove site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

//add custom menus
remove_theme_support ( 'genesis-menus' );
add_theme_support ( 'genesis-menus' , array (
								 'primary' => 'Primary Navigation Menu' , 
								 'secondary' => 'Secondary Navigation Menu' ,
								 'top-menu' => 'Top Navigation Menu',
								  'footer-menu' => 'Footer Navigation Menu',
								  'footer-sitemap' => 'Footer Sitemap'
								 ) );

//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
//add_action( 'genesis_after_header', 'genesis_do_nav', 15 );

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
//add_action( 'genesis_footer', 'genesis_do_subnav', 7 );

//* Position other menus - not currently used, built into theme pages
function show_top_menu(){
echo '<div id="nav"><div>';  
wp_nav_menu( array( 'sort_column' => 'menu_order', 'container_id' => 'top-menu' , 'menu_class' => 'menu top-menu superfish sf-js-enabled', 'theme_location' => 'top-menu') ); 
echo '</div></div>';
}

function show_footer_menu(){
echo '<div id="nav"><div>';  
wp_nav_menu( array( 'sort_column' => 'menu_order', 'container_id' => 'footer-menu' , 'menu_class' => 'menu footer-menu superfish sf-js-enabled', 'theme_location' => 'footer-menu') ); 
echo '</div></div>';
}

function show_footer_sitemap(){
echo '<div id="nav"><div>';  
wp_nav_menu( array( 'sort_column' => 'menu_order', 'container_id' => 'secondary' , 'menu_class' => 'menu secondary-menu sub-header-menu superfish sf-js-enabled', 'theme_location' => 'secondary-menu') ); 
echo '</div></div>';
}

//* Reduce the secondary navigation menu to one level depth
//add_filter( 'wp_nav_menu_args', 'minimum_secondary_menu_args' );
function minimum_secondary_menu_args( $args ){

	if( 'secondary' != $args['theme_location'] )
	return $args;

	$args['depth'] = 1;
	return $args;

}

//* custom search form 

add_filter( 'genesis_search_form', 'bhpub_custom_search_form', 10, 4);


function bhpub_custom_search_form(){
$form = '
<form method="get" id="searchform" action="'. home_url( '/' ) .'">
		<input type="text" class="field" name="s" id="s" placeholder="search here &hellip;" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="Go"  />
	</form>	
   '; 
	return $form;
}


/*
//* Add the site tagline section
add_action( 'genesis_after_header', 'minimum_site_tagline' );
function minimum_site_tagline() {

	printf( '<div %s>', genesis_attr( 'site-tagline' ) );
	genesis_structural_wrap( 'site-tagline' );

		printf( '<div %s>', genesis_attr( 'site-tagline-left' ) );
		printf( '<p %s>%s</p>', genesis_attr( 'site-description' ), esc_html( get_bloginfo( 'description' ) ) );
		echo '</div>';
	
		printf( '<div %s>', genesis_attr( 'site-tagline-right' ) );
		genesis_widget_area( 'site-tagline-right' );
		echo '</div>';

	genesis_structural_wrap( 'site-tagline', 'close' );
	echo '</div>';

}
*/
//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'minimum_author_box_gravatar' );
function minimum_author_box_gravatar( $size ) {

	return 144;

}

//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'minimum_comments_gravatar' );
function minimum_comments_gravatar( $args ) {

	$args['avatar_size'] = 96;
	return $args;

}
/*
//* Change the number of portfolio items to be displayed (props Bill Erickson)
add_action( 'pre_get_posts', 'minimum_portfolio_items' );
function minimum_portfolio_items( $query ) {

	if ( $query->is_main_query() && !is_admin() && is_post_type_archive( 'portfolio' ) ) {
		$query->set( 'posts_per_page', '6' );
	}

}
*/
//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'site-tagline-right',
	'name'        => __( 'Site Tagline Right', 'minimum' ),
	'description' => __( 'This is the site tagline right section.', 'minimum' ),
) );

genesis_register_sidebar( array(
	'id'          => 'top-widget',
	'name'        => __( 'Top Widget', 'minimum' ),
	'description' => __( 'This is the top widget section.', 'minimum' ),
) );

genesis_register_sidebar( array(
	'id'          => 'home-featured-1',
	'name'        => __( 'Home Featured 1', 'minimum' ),
	'description' => __( 'This is the home featured 1 section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-2',
	'name'        => __( 'Home Featured 2', 'minimum' ),
	'description' => __( 'This is the home featured 2 section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-3',
	'name'        => __( 'Home Featured 3', 'minimum' ),
	'description' => __( 'This is the home featured 3 section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-4',
	'name'        => __( 'Home Featured 4', 'minimum' ),
	'description' => __( 'This is the home featured 4 section.', 'minimum' ),
) );





// Custom B&H Functions for Genesis Framework  //

//customize header and footer
remove_action('genesis_header','genesis_do_header');
add_action('genesis_header','bhpub_import_header');
function bhpub_import_header() {
	locate_template('header.php',true);
}

remove_action('genesis_footer','genesis_do_footer');
remove_action('genesis_footer','genesis_footer_markup_open',5);
remove_action('genesis_footer','genesis_footer_markup_close',15);
add_action('genesis_footer','bhpub_import_footer');


function bhpub_before_footer(){
	genesis_markup( array(
		'html5'   => '<footer class="clearfix" id="footer">',
		'xhtml'   => '<div id="footer" class="clearfix">',
		'context' => 'site-footer',
	) );
	//genesis_structural_wrap( 'footer', 'open' );
}

function bhpub_import_footer() {
	bhpub_before_footer();
	locate_template('bhpub_footer.php',true);
	bhpub_after_footer();
}

function bhpub_after_footer(){
	//genesis_structural_wrap( 'footer', 'close' );
	genesis_markup( array(
		'html5'   => '</footer>',
		'xhtml'   => '</div>',
	) );
}

// remove site description
remove_action('genesis_header','genesis_site_description');

// Favicon
add_filter( 'genesis_pre_load_favicon', 'bhpub_favicon_filter' );
function bhpub_favicon_filter( $favicon_url ) {

	return 'http://www.bhpublishinggroup.com/favicon.ico';

}

// Custom Site Settings - social media
// replaces Social settings from Responsive theme
function bhpub_social_defaults( $defaults ) {
 

$defaults['facebook_url'] = '';
$defaults['twitter_url'] = '';
$defaults['youtube_url'] = '';
$defaults['pinterest_url'] = '';
$defaults['instagram_url'] = '';
 
return $defaults;
}
add_filter( 'genesis_theme_settings_defaults', 'bhpub_social_defaults' );
//sanitize field input - html not allowed
function bh_register_social_sanitization_filters() {
	genesis_add_option_filter(
		'no_html',
		GENESIS_SETTINGS_FIELD,
			array(
			'twitter_url',
			'facebook_url',
			'youtube_url',
			'pinterest_url',
			'instagram_url'
			)
		);
}
add_action( 'genesis_settings_sanitizer_init', 'bh_register_social_sanitization_filters' );

//create meta box in admin
function bhpub_register_social_settings_box( $_genesis_theme_settings_pagehook ) {
	add_meta_box( 'bhpub-social-settings', 'Social Links', 'bhpub_social_settings_box', $_genesis_theme_settings_pagehook, 'main', 'high' );
}
add_action( 'genesis_theme_settings_metaboxes', 'bhpub_register_social_settings_box' );

function bhpub_social_settings_box() {
?>
 
<p><?php _e( 'Facebook URL:', 'minimum' );?><br />
<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[facebook_url]" value="<?php echo esc_url( genesis_get_option('facebook_url') ); ?>" size="50" /> </p>

<p><?php _e( 'Twitter URL:', 'minimum' );?><br />
<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[twitter_url]" value="<?php echo esc_url( genesis_get_option('twitter_url') ); ?>" size="50" /> </p>

<p><?php _e( 'YouTube URL:', 'minimum' );?><br />
<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[youtube_url]" value="<?php echo esc_url( genesis_get_option('youtube_url') ); ?>" size="50" /> </p>

<p><?php _e( 'Pinterest URL:', 'minimum' );?><br />
<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[pinterest_url]" value="<?php echo esc_url( genesis_get_option('pinterest_url') ); ?>" size="50" /> </p>
 
 <p><?php _e( 'Instagram URL:', 'minimum' );?><br />
<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[instagram_url]" value="<?php echo esc_url( genesis_get_option('instagram_url') ); ?>" size="50" /> </p>
 
 
<?php
}



// Custom functions for BH PUB site //

// Custom WordPress Login Logo
function bh_custom_login_css() {
	wp_enqueue_style( 'bh_custom_login_css', get_stylesheet_directory_uri() . '/login.css' );
}
add_action('login_head', 'bh_custom_login_css');

function bh_login_bhpub_link() {
	return 'http://www.bhpublishinggroup.com';
}
add_filter('login_headerurl','bh_login_bhpub_link');

function bh_tooltip_bhpub_logo() {
	return 'B&H Publishing Group: Every WORD Matters.';
}
add_filter('login_headertitle', 'bh_tooltip_bhpub_logo');

// Custom Wordpress Admin Menu icons for custom post types using dashicon font
add_action( 'admin_head', 'bhpub_custom_admin_css' );
function bhpub_custom_admin_css(){
	?>
    <style>
	#adminmenu #menu-posts-products div.wp-menu-image:before {
    	content: "\f330";
	}
	
	#adminmenu #menu-posts-bhauthor div.wp-menu-image:before {
    	content: "\f307";
	}
	
	#adminmenu #menu-posts-features div.wp-menu-image:before {
    	content: "\f488";
	}
	</style>
    <?php	
}


/* MAILCHIMP Newsletter embed iframe shortcode */

function bhpub_mailchimp_iframe() {
	//file location in same folder as css
	$src= get_stylesheet_directory_uri() .'/cm_popup_form.html';
	return '<iframe class="mc-iframe-form" src="'.$src.'">Sign up for the mailing list...</iframe>';	

	

}
add_shortcode('mc_signup_iframe','bhpub_mailchimp_iframe');

/* Updated MAILCHIMP Newsletter signup embed (also works with popup */
function bhpub_mailchimp_embed() {


//updated to campaign monitor 1/2016 - BR

bhpub_cmform_embed();



// <!-- old name b_2e6ebb1bba83f48b2ec8b1453_2da8e1b19c -->
// <!-- old id //bhpublishinggroup.us3.list-manage.com/subscribe/post?u=2e6ebb1bba83f48b2ec8b1453&amp;id=2da8e1b19c -->
// updated 9/23/15 BR - form ID changed ("for some reason")
/*
?>
	<!-- Begin MailChimp Signup Form -->
   
<div id="mc_embed_signup">
<form action="//bhpublishinggroup.us3.list-manage.com/subscribe/post?u=2e6ebb1bba83f48b2ec8b1453&amp;id=812cdc9738" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
    <div id="mc_embed_signup_scroll">
	
<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
<div class="mc-field-group">
	<input type="email" value="your email address" name="EMAIL" class="required email" id="mce-EMAIL"><input type="submit" value="Sign Up" name="subscribe" id="mc-embedded-subscribe" class="button" ><!-- onClick="ga('send', 'event', { eventCategory: 'bhnewsletter', eventAction: 'subscribe', eventLabel: 'bhpub_signup_page'});" -->
</div>
	<div id="mce-responses" class="clear">
		<div class="response" id="mce-error-response" style="display:none"></div>
		<div class="response" id="mce-success-response" style="display:none"></div>
	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    
    <div style="position: absolute; left: -5000px;"><input type="text" name="b_2e6ebb1bba83f48b2ec8b1453_812cdc9738" tabindex="-1" value=""></div>
    </div>
</form>
</div>
<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
<!--End mc_embed_signup-->
	
	
	<?php
	*/
}

function bhpub_cmform_embed(){

?>
<div id="mc_embed_signup">
<form action="http://lifeway.createsend.com/t/r/s/dujrol/" method="post" id="subForm" autocomplete="off">
        <!-- <label for="fieldEmail">Email</label><br />-->
        <div class="mc-field-group">
        	<input id="fieldEmail" name="cm-dujrol-dujrol" value="your email address" class="required email" type="email" /><input type="submit" class="button" value="Sign Up"/>
        </div>
        <div id="mce-responses" class="clear">
		<div class="response cm-error" id="mce-error-response" style="display:none"></div>
		<div class="response cm-success" id="mce-success-response" style="display:none"></div>
	</div>
</form>
</div>

<script type="text/javascript">
    jQuery(function ($) {
        $('#subForm').submit(function (e) {
            $thisForm = $(this);
            e.preventDefault();
            $.getJSON(
            this.action + "?callback=?",
            $(this).serialize(),
            function (data) {
                if (data.Status === 400) {
                    //alert("Error: " + data.Message);
                    // this will be loaded into the response area
                    $thisForm.find('.cm-error').html(data.Message).show().delay(10000).fadeOut('slow').delay(5000).html('');

                } else { // 200
                    //alert("Success: " + data.Message);
                    // this will be loaded into the response area
                    //message = data.Message; //default response
                    //message = "Subscription to our list confirmed." ;//custom message
                    $thisForm.find('.cm-success').html('Thank you. Your subscription to our list is confirmed.').show();

                }
            });
        });
    });
</script>

<?php

}







/* MAILCHIMP Newsletter signup embed */
function bhpub_mailchimp_embed__OLD() {
?>

<!-- Begin MailChimp Signup Form -->
<lin k href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
<style type="text/css">

/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
  We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
</style>
<div id="mc_embed_signup">
<form action="//bhpublishinggroup.us3.list-manage.com/subscribe/post?u=2e6ebb1bba83f48b2ec8b1453&amp;id=2917932253" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
<div class="mc-field-group">
<!-- <label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
</label>-->
<input type="email" value="your email address" name="EMAIL" class="required email" id="mce-EMAIL">
<input type="submit" value="Sign Up" name="subscribe" id="mc-embedded-subscribe" class="button">
</div>
<div id="mce-responses" class="clear">
<div class="response" id="mce-error-response" style="display:none"></div>
<div class="response" id="mce-success-response" style="display:none"></div>
</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;"><input type="text" name="b_2e6ebb1bba83f48b2ec8b1453_2917932253" tabindex="-1" value=""></div>
</form>
</div>
<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='MMERGE3';ftypes[3]='text';fnames[4]='MMERGE4';ftypes[4]='text';fnames[5]='MMERGE5';ftypes[5]='text';fnames[6]='MMERGE6';ftypes[6]='text';fnames[7]='MMERGE7';ftypes[7]='text';fnames[8]='MMERGE8';ftypes[8]='text';fnames[9]='MMERGE9';ftypes[9]='text';fnames[10]='MMERGE10';ftypes[10]='text';fnames[11]='MMERGE11';ftypes[11]='text';fnames[12]='MMERGE12';ftypes[12]='text';fnames[13]='MMERGE13';ftypes[13]='text';fnames[14]='MMERGE14';ftypes[14]='text';fnames[15]='MMERGE15';ftypes[15]='text';fnames[16]='MMERGE16';ftypes[16]='text';fnames[17]='MMERGE17';ftypes[17]='text';fnames[18]='MMERGE18';ftypes[18]='text';fnames[19]='MMERGE19';ftypes[19]='text';fnames[20]='MMERGE20';ftypes[20]='text';fnames[21]='MMERGE21';ftypes[21]='text';fnames[22]='MMERGE22';ftypes[22]='text';fnames[23]='MMERGE23';ftypes[23]='text';fnames[24]='MMERGE24';ftypes[24]='text';fnames[25]='MMERGE25';ftypes[25]='text';fnames[26]='MMERGE26';ftypes[26]='text';fnames[27]='MMERGE27';ftypes[27]='text';fnames[28]='MMERGE28';ftypes[28]='text';fnames[29]='MMERGE29';ftypes[29]='text';fnames[30]='MMERGE30';ftypes[30]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
<!--End mc_embed_signup-->
<?php     //end bhpub_mailchimp_embed 1.0
}


add_action('genesis_after_footer','bhpub_mailchimp_scripts');
function bhpub_mailchimp_scripts() {
		if (is_home() || is_singular( array('products','bhauthor' )) || is_page_template('information-page.php') ){
		echo '<script>
		jQuery(document).ready(function($){
		//mailing list signup
				
				$(".email.required").focus(function(){
					if($(this).val()=="your email address"){
						$(this).val("");
					}
				});
				$(".email.required").blur(function(){
					if($(this).val()==""){
						$(this).val("your email address");
					}
				});
		});
		</script>';	
	}
	
}



/**
* WooCommerce support


remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start() {
  //echo '<section id="main">';
  echo '<div class="inner">';
  
}

function my_theme_wrapper_end() {
 //echo '</section>';
 echo '</div>';
}



add_theme_support( 'woocommerce' );

*/

//remove WP version from header
remove_action('wp_head', 'wp_generator');

// add custom post type to category lists
add_filter('pre_get_posts', 'bhpub_query_post_type');
function bhpub_query_post_type($query) {
  if ( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
    $post_type = get_query_var('post_type');
	if($post_type)
	    $post_type = $post_type;
	else
	    $post_type = array('post','products','bhauthor','nav_menu_item','page'); // replace cpt to your custom post type //'nav_menu_item',
    $query->set('post_type',$post_type);
	return $query;
    }
}

function get_the_slug() {

global $post;

if ( is_single() || is_page() ) {
return $post->post_name;
}
else {
return "";
}

} 



//Autocomplete for tag list in related authors

// Register hooks
add_action('admin_print_scripts', 'add_script');
add_action('admin_head', 'add_script_config');
 
/**
 * Add script to admin page
 */
function add_script() {
    // Build in tag auto complete script
    wp_enqueue_script( 'suggest' );
}
 
/**
 * add script to admin page
 */
function add_script_config() {
?>
<script type="text/javascript" >
    // Function to add auto suggest
    function authorSuggest(id) {
        jQuery('#' + id).suggest("<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php?action=ajax-tag-search&tax=a", {multiple:true, multipleSep: ","});
    }
    </script>
<?php
}


// Relevanssi Search Input Filter
// Remove dashes from ISBNs for search
add_filter('relevanssi_remove_punctuation', 'bhpub_search_remove_dash', 9);
function bhpub_search_remove_dash($a) {
     $a = str_replace('&hyphen;', '', $a); //html entity
	$a = str_replace('&dash;', '', $a); //html entity
    $a = str_replace('-', '', $a); //character
	//$a = str_replace('&', 'AMPERSAND', $a); //original script at http://www.relevanssi.com/knowledge-base/words-ampersands-cant-found/
    return $a;
}

add_filter('relevanssi_results', 'rlv_exact_boost');
function rlv_exact_boost($results) {
	$query = strtolower(get_search_query());
	foreach ($results as $post_id => $weight) {
		$title = strtolower(get_the_title($post_id));
		if ($title == $query) $results[$post_id] = $weight * 1000;
	}
	return $results;
}

add_filter('relevanssi_modify_wp_query', 'rlv_meta_fix', 99);
function rlv_meta_fix($q) {
	$q->set('meta_query', '');
	return $q;
}

/*
add_filter('relevanssi_do_not_index', 'post_indexing_filter', 10, 2);
function post_indexing_filter($index, $post_id) {
    $posts_to_include = array(13581, 13582, 13583, 13584, 13585);
    if (in_array($post_id, $posts_to_include)) return true;
}
*/


// replaces for search result? - not necessary in this application
/*
add_filter('relevanssi_remove_punctuation', 'saveampersands_2', 11);
function saveampersands_2($a) {
    $a = str_replace('AMPERSAND', '&', $a);
    return $a;
}
*/





// Autocomplete for product list in related products
// look up post title, save isbn list in form


//Autocomplete for product list in Product Group Parent
// get only products with parent box checked



// sort products by custom field in admin
//not working with plugin

/*
add_filter( 'manage_edit-product_sortable_columns', 'bh_product_column_register_sortable' );

function bh_product_column_register_sortable( $post_columns ) {
    $post_columns = array(
        'cpac-column-meta-3' => 'wpcf-pubdate',
        'cpac-column-meta' => 'wpcf-isbn'
        );
        
    return $post_columns;
}
*/


//custom landing page for Top-Level categories

add_filter( 'template_include', 'bhpub_category_page_template',1);

function bhpub_category_page_template( $template ) {

if(is_category()){
				
		$cat = get_query_var('cat');
		$category_info = get_category($cat);
		if($category_info->parent==0) {
		/* No category parents. */
		$template = locate_template( array( 'category-landing.php', 'category.php' ) );
	}
	else {
		$parent_info = get_category($category_info->parent);
		if($parent_info->parent==0){
			/* second level category */
			$template = locate_template( array( 'category-landing.php', 'category.php' ) );
		}else{
		
		/* subcategory */
		$template = locate_template( array( 'category.php' ) );
		}
	}
}
return $template;
}

/* Define a constant path to our single template folder */
// define(SINGLE_PATH, TEMPLATEPATH . '/single'); //for main theme
 define('SINGLE_PATH', STYLESHEETPATH . '/single'); //for child theme

/* Filter the single_template with our custom function*/
add_filter('single_template', 'custom_single_template');

/* Single template function which will choose our template*/
function custom_single_template($single) {
    global $wp_query, $post;


/* Checks for single template by category. Check by category slug and ID */
foreach((array)get_the_category() as $cat) :

    if(file_exists(SINGLE_PATH . '/single-cat-' . $cat->slug . '.php'))
        return SINGLE_PATH . '/single-cat-' . $cat->slug . '.php';

    elseif(file_exists(SINGLE_PATH . '/single-cat-' . $cat->term_id . '.php'))
        return SINGLE_PATH . '/single-cat-' . $cat->term_id . '.php';

endforeach;

/* Checks for single template by top-level category. Check by category slug and ID */
foreach((array)get_the_category() as $cat) :
//get top-level category
					//$p_cats=get_the_category($post_id);
					$parents =get_category_parents($cat->term_id);
					//$cat_arr = split("/",$parents);
					$cat_arr = explode("/",$parents);
					$topcat = get_category_by_slug($cat_arr[0]);

    if(file_exists(SINGLE_PATH . '/single-cat-' . $topcat->slug . '.php'))
        return SINGLE_PATH . '/single-cat-' . $topcat->slug . '.php';

    elseif(file_exists(SINGLE_PATH . '/single-cat-' . $topcat->term_id . '.php'))
        return SINGLE_PATH . '/single-cat-' . $topcat->term_id . '.php';

endforeach;

/* Checks for single template by custom post type */
if ($post->post_type == "bhauthor"||"products"){
    if(file_exists(SINGLE_PATH . '/single-' . $post->post_type . '.php'))
        return SINGLE_PATH . '/single-' . $post->post_type . '.php';
}

/* Checks for single template by tag. Check by tag slug and ID */
	$wp_query->in_the_loop = true;
	if(has_tag()):
	foreach((array)get_the_tags() as $tag) :

		if(file_exists(SINGLE_PATH . '/single-tag-' . $tag->slug . '.php'))
			return SINGLE_PATH . '/single-tag-' . $tag->slug . '.php';

		elseif(file_exists(SINGLE_PATH . '/single-tag-' . $tag->term_id . '.php'))
			return SINGLE_PATH . '/single-tag-' . $tag->term_id . '.php';

	endforeach;
	endif;
	$wp_query->in_the_loop = false;


/*Checks for default single post files within the single folder */
if(file_exists(SINGLE_PATH . '/single.php'))
    return SINGLE_PATH . '/single.php';

elseif(file_exists(SINGLE_PATH . '/default.php'))
    return SINGLE_PATH . '/default.php';

return $single; 
}

//
// Product Groups - custom category filters
//
// on category pages, display only the tagged 'Primary' product
// from a group of similar products (custom tax 'product-group')

function bhpub_cat_posts_join( $sql ) {
	
	global $wpdb;
	//$sql =  $sql . "
		$sql = "
		INNER JOIN `wp_postmeta` ON (wp_posts.ID = wp_postmeta.post_id AND wp_postmeta.meta_key = 'wpcf-pubdate')
		
		INNER JOIN `wp_term_relationships` tr1 ON (wp_posts.ID = tr1.object_id)
		INNER JOIN `wp_term_taxonomy` ttt1 ON (tr1.term_taxonomy_id = ttt1.term_taxonomy_id)
		INNER JOIN `wp_terms` t1 ON (ttt1.term_id = t1.term_id)

		INNER JOIN `wp_term_relationships` tr2 ON (wp_posts.ID = tr2.object_id)
		INNER JOIN `wp_term_taxonomy` ttt2 ON (tr2.term_taxonomy_id = ttt2.term_taxonomy_id)
		INNER JOIN `wp_terms` t2 ON (ttt2.term_id = t2.term_id)
	";
	
	//echo $sql; //debug
	return $sql;
}

function bhpub_cat_posts_where( $sql ) {
	global $wpdb;
		$cat=get_category(get_query_var('cat'),false);
		//replace default sql, keeping some of the initial AND statements
		$sql =  " 		AND wp_posts.post_type IN ('products')
							AND (wp_posts.post_status = 'publish' OR wp_posts.post_author = 1 AND wp_posts.post_status = 'private') 
							/* AND (wp_postmeta.meta_key = 'wpcf-pubdate' )   */
							
							AND (ttt1.taxonomy = 'category' AND t1.term_id IN (" . $cat->term_id . ")
							
									 AND (wp_posts.ID NOT IN(
											SELECT p1.ID FROM `wp_posts` p1 
											INNER JOIN `wp_term_relationships` tr3 ON (p1.ID = tr3.object_id)
											INNER JOIN `wp_term_taxonomy` ttt3 ON(tr3.term_taxonomy_id = ttt3.term_taxonomy_id)
											WHERE ttt3.taxonomy = 'product-group'
											)
									)
								OR (ttt1.taxonomy = 'category' AND t1.term_id IN (" . $cat->term_id . ") AND ttt2.taxonomy = 'post_tag' AND t2.name = 'primary'))
							";
	
	//echo $sql; //debug
	return $sql;
}
/* //NOT currently in use 
function bhpub_cat_posts_fields( $sql ) {
	//echo $sql; //debug
	global $wpdb;
	return $sql; //. ", $wpdb->terms.name AS 'book_category'";
}
function bhpub_cat_posts_orderby( $sql ) {
	//echo $sql; //debug
	global $wpdb;
	return  '';// "$wpdb->terms.name ASC, $wpdb->posts.post_title ASC";
}
*/

// END PRODUCT GROUPING FILTERS

// should no longer be needed 5/8/2014 - using "Listed" tag now

/* RUN ONCE */
// Detect OP on product
// If OP, remove tag "Listed", add tag "OP"
function bhpub_bulk_remove_OP(){
	//find all out of stock products tagged "Listed"
	$meta_q = $pubdate_args = array(
												array(
															'key' => 'wpcf-print-status',
															'value' => array('OP','OR','PP','AB','WS'),
															'compare' => 'IN'						
															)
										);
	$args = array(
								'post_type'=>'products',
								'tag'=>'listed',
								'posts_per_page'=>'-1',
								'meta_query'=>$meta_q
	);
	$OP = new WP_query($args);
	
	if($OP->have_posts()):
		while($OP->have_posts()): $OP->the_post();
			global $post;
			the_title();
			echo $post->ID;
			//get all terms from post
			$p_terms = wp_get_post_terms($post->ID,'post_tag',array("fields" => "names"));
			print_r($p_terms);
			$found_listed = array_search('Listed',$p_terms); //returns key or false
			echo "Tagged Listed: " . $found_listed;
			//remove tag "listed"
			if ($found_listed>-1){
				unset($p_terms[(int) $found_listed]);
				$mod_terms = array_values($p_terms);	
				print_r($mod_terms);
				//add tag "OP"
				$mod_terms[]='OP';
				print_r($mod_terms);
				wp_set_object_terms($post->ID,$mod_terms,'post_tag');
			}
		
		endwhile;//while have posts
	endif; //if OP have posts
	
	
}



//updated dynamic body class for Genesis Framework

function bhpub_genesis_body_classes(array $classes){
	global $wp_query;
    // if there is no parent ID and it's not a single post page, category page, or 404 page, give it
    // a class of "parent-page"
   // if( $wp_query->post->post_parent < 1  && !is_single() && !is_archive() && !is_404() ) {
  //      $classes[] = 'parent-page';
	// };
	
	$qobj = get_queried_object();
	
    //if post has a category, give it a class of the category slug
	if(is_single()){
    
    if($qobj->post_parent > 0 ) {
        $parent_title = get_the_title($qobj->post_parent);
        $parent_title = preg_replace('#\s#','-', $parent_title);
        $parent_title = strtolower($parent_title);
        $classes[] = 'parent-pagename-'.$parent_title;
    	}	
	
		// if the page/post has a parent, it's a child, give it a class of its parent name
		 if($qobj->post_parent > 0 ) {
        $parent_title = get_the_title($qobj->post_parent);
        $parent_title = preg_replace('#\s#','-', $parent_title);
        $parent_title = strtolower($parent_title);
        $classes[] = 'parent-pagename-'.$parent_title;
    	}	
		
			// add a class = to the name of post or page
		  $classes[] = $qobj->name;	
		  
		
	}
	
	foreach((get_the_category(get_queried_object_id())) as $category) {
			// add category slug to the $classes array
			$classes[] = 'category-'.$category->category_nicename;
		}
		
	
	
    return array_unique($classes);
}

add_filter('body_class','bhpub_genesis_body_classes',20);

function bhpub_post_name_in_body_class( $classes ){
	if( is_singular() ) {
	     global $post;
	     $parent = get_page($post->post_parent);
		 array_push( $classes, "{$post->post_name}" );
	     array_push( $classes, "{$post->post_type}-{$post->post_name}" );
	     array_push( $classes, "{$post->post_type}-parent-{$parent->post_name}" );
     }
	return $classes;
	}
	add_filter( 'body_class', 'bhpub_post_name_in_body_class' );


// allow shortcodes in widgets
add_filter('widget_text', 'do_shortcode');

//custom query vars
add_filter('query_vars', 'parameter_queryvars' );
function parameter_queryvars( $qvars )
{
$qvars[] = 'nr';  //nr=new releases
$qvars[] = 'req_isbn';  //req_isbn = ISBN13 of requested review copy
$qvars[] = 'req_title';  //req_title = Title of requested review copy
$qvars[] = 'pid'; //pid = product ID (could be ISBN, LIN, UPC) for legacy redirect
$qvars[] = 'ppp'; //Posts Per Page, used for sorting category pages
//$qvars[] = 'p'; //legacy product pages
//$qvars[] = 'aname'; //a_name = author name last_first for legacy redirect - not needed, using a
//$qvars[] = 'a_first'; //a_first = author first name
//$qvars[] = 'a_last'; //a_last = author last name
return $qvars;
}


//custom thumbnail sizes
if( function_exists( 'add_theme_support' ) ) {
	    // This theme uses post thumbnails
	    add_theme_support( 'post-thumbnails' );
	 
	    // Generate a new thumbnail with our desired name and size		
	//	add_image_size( 'home-feature-thumb' , 125, 80, false ); //max width and height
	//	add_image_size( 'author-thumb' , 90, 90, true );
	//	add_image_size( 'author-photo', 268, , false ); //check sizes
	//	add_image_size( 'product-thumb', 125, 90, false );
	//	add_image_size( 'product-image', , 325, false );
	 
}


//fix page permalinks
//global $wp_rewrite; $wp_rewrite->flush_rules();


// populate product data from Product Service API

add_action('save_post','bh_products_populate_from_api',20,2);
//populate missing info only - do not overwrite current data
function bh_products_populate_from_api($post, $postarr) {
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;
	
	$post_id=false;
	if ($postarr->post_type == 'products') {
	
		//global $post;
		$post_id = $postarr->ID;
		if(!$post_id){
		$post_id = get_the_ID();
		}
		
		   if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
		
		$isbn13=false;
		$upc=false;
		$LIN=false;
		$post=get_post($post_id);
		$isbn13 = get_post_meta($post_id,'wpcf-isbn',true);
		$upc = get_post_meta($post_id,'wpcf-upc',true);
		$LIN = get_post_meta($post_id,'wpcf-lin',true);
		$current_post_content = $post->post_content;
		$current_title = $post->post_title;
	
		//if isbn is populated and indicator field is blank
		if ($isbn13) {
			//	if(!$LIN || $LIN==0){  //remove $LIN after supplies are updated
				//HTTP request to API
				//if($isbn13){
				 $url = 'http://api.lifeway.com/v3/products/ISBN/' . $isbn13;
				//}
				 $api_user =  'BHWEB-public';
				 $api_pass = 'Md732V2w3P4o';
				 $args = array(
											'headers' => array(
																				'Authorization' => 'Basic ' . base64_encode( $api_user . ':' . $api_pass )
																			),
											'timeout' => '20'
										);
				$api_resp = wp_remote_request( $url, $args );
				//$api_resp = wp_remote_retrieve_body( $url, $args );
				
				if(!is_wp_error($api_resp)){
				//print_r($api_resp);
				$nocdata = str_replace("&lt;![CDATA[","",$api_resp["body"]); //clean cdata in products API
				//$nocdata = str_replace("&lt;![CDATA[","",$api_resp); //clean cdata in products API
				$nocdata = str_replace("]]&gt;","",$nocdata);
				//echo $nocdata; //troubleshooting xml
				$xml = simplexml_load_string($nocdata);
				//print_r($xml); //troubleshoot products API response
				}else{
					//is error, return
					echo "Error returned from the API.";
					print_r($api_resp);
					return;	
				}
				if(!$xml) {
					echo "Error converting response to XML.";
					print_r($api_resp);
					return;
				}//error handling
				
				$p_xml = $xml->xpath('//Product/ProductMetaData/MetaData'); //product data to array
				$a_xml = $xml->xpath('//Product/Contributors/Contributor'); //author data to array
				
				$arr_LIN = $xml->xpath('//Product/ItemNumber');
				$p_LIN = (string) $arr_LIN[0];
				$arr_title = $xml->xpath('//Product/Title');
				$p_title=(string) $arr_title[0];
		
	
				// loop through array to find necessary parts, set to variables
				foreach($p_xml as $key) {
						//switch($key->attributes()->id) {
							switch($key->name) {
							
							case "availability":
							$p_status = (string) $key->value;
							break;
							
							case "binding":
							$p_binding = (string) $key->value;
							break;
							
							case "iSBN10":
							$p_isbn10 = (string) $key->value;
							break;
							
							case "longDescription":
							$p_desc = (string) $key->value;
							break;
							
							case "numberofPages": 
							$p_pagecount = (string) $key->value;
							break;
							
							case "price":
							$p_msrp = (string) $key->value;
							break;
														
							case "subtitle":
							$p_subtitle = (string) $key->value;
							break;
							
							case "publicationDate":
							$p_pubdate = str_replace('-','',(string) $key->value);
							break;
							
							case "dimension-Height":
							$p_height = (string) $key->value;
							break;
							
							case "dimension-Width":
							$p_width = (string) $key->value;
							break;
							
							case "dimension-Length":
							$p_length = (string) $key->value;
							break;
							
							case "dimension-Weight":
							$p_weight = (string) $key->value;
							break;
							
							
						}
				}
				
				//get authors
								$p_authors = "";
								$authorcount = 0;
								$atags = array();
								$arr_names = array();
								foreach($a_xml as $key) {
											$a_name=false;
											$value = (string) $key->name;
											$role = (string) $key->role;
											//$val_arr = explode(",",$value);
											//$a_name=trim($val_arr[1]." ".$val_arr[0]);
											$a_name= (string) $key->displayName;
											// remove foreword, manufactured by etc. keep editors though.
											if(strpos($role,'Foreword')!==false){
												$a_name=false;
											}elseif(strpos($role,'Manufactured')!==false){
												$a_name=false;
											}
																						
										if($a_name)
											//add author to tags array 
												array_push($atags,$a_name);
												$authorcount++;
											
											//add author displayname to array
												if($a_name!=''){
												array_push($arr_names,$a_name);
												}
											
											
								}
								$arr_a = array_unique($arr_names);
								$lasta = array_pop($arr_a);
								if (count($arr_a)>0){
									$p_authors = implode($arr_a) . ' and ' .$lasta;
								}else{
									//single item in array
									$p_authors = $lasta;
								}
							
	//exceptions
				
			//	if($current_post_content!=''|| !$p_desc || $p_desc=="null"){
			//		$p_desc = $current_post_content; //prevents overwriting an existing description with "null" from Products API
			//	}
				
				$this_post = array(
													'ID' =>$post_id,
													//'post_content' => $p_desc //don't alter content either
													//'post_title' => $current_title, //$p_title no longer changing post name
													'post_type' => "products"
								
													);
				
				// unhook this function so it doesn't loop infinitely
						remove_action('save_post', 'bh_products_populate_from_api',20,2);
						
						wp_update_post($this_post);
						
						// re-hook this function
						add_action('save_post', 'bh_products_populate_from_api',20,2);
						//update only if blank
						if(!get_post_meta($post_id,'wpcf-lin',true)){
							if($p_LIN){update_post_meta($post_id,'wpcf-lin',$p_LIN);} 
						}
						if(!get_post_meta($post_id,'wpcf-isbn',true)){
							if($isbn13){update_post_meta($post_id,'wpcf-isbn',$isbn13);}
						}
						if(!get_post_meta($post_id,'wpcf-isbn',true)){
							if($p_isbn10){ update_post_meta($post_id,'wpcf-isbn10',$p_isbn10);}
						}
						if(!get_post_meta($post_id,'wpcf-pubdate',true)){
							if($p_pubdate){update_post_meta($post_id,'wpcf-pubdate',$p_pubdate);}
						}
						if(!get_post_meta($post_id,'wpcf-msrp',true)){
							if($p_msrp){update_post_meta($post_id,'wpcf-msrp',$p_msrp);}
						}
						if(!get_post_meta($post_id,'wpcf-pagecount',true)){
						if($p_pagecount){update_post_meta($post_id,'wpcf-pagecount',$p_pagecount);}
						}
						if(!get_post_meta($post_id,'wpcf-print-status',true)){
						if($p_status){update_post_meta($post_id,'wpcf-print-status',$p_status);}
						}
						if(!get_post_meta($post_id,'wpcf-binding',true)){
						if($p_binding){update_post_meta($post_id,'wpcf-binding',$p_binding);}
						}
						if(!get_post_meta($post_id,'wpcf-subtitle',true)){
						if($p_subtitle){update_post_meta($post_id,'wpcf-subtitle',$p_subtitle);}
						}
						//always edit API description field with latest data
						if($p_desc){
							update_post_meta($post_id,'wpcf-product-api-description',$p_desc);	
						}
						if(!get_post_meta($post_id,'wpcf-product-weight',true)){
						if($p_weight){update_post_meta($post_id,'wpcf-product-weight',$p_weight);}
						}
						if(!get_post_meta($post_id,'wpcf-product-height',true)){
						if($p_height){update_post_meta($post_id,'wpcf-product-height',$p_height);}
						}
						if(!get_post_meta($post_id,'wpcf-product-width',true)){
						if($p_width){update_post_meta($post_id,'wpcf-product-width',$p_width);}
						}
						if(!get_post_meta($post_id,'wpcf-product-length',true)){
						if($p_length){update_post_meta($post_id,'wpcf-product-length',$p_length);}	
						}
						if(!get_post_meta($post_id,'wpcf-book-authors',true)){						
								if($p_authors){
									update_post_meta($post_id,'wpcf-book-authors',$p_authors);
									//add author tags to post
									$post_terms = wp_set_post_terms($post_id, $atags, 'a');
									//if ( is_wp_error($post_terms) )
									//	 echo $post_terms->get_error_message();			
								}
						}

						//  end of executed code //						
				// 	}// if no LIN
					
					//get top-level category
					$p_cats=get_the_category($post_id);
					$parents =get_category_parents($p_cats[0]->term_id);
					// $cat_arr = split("/",$parents); //split() deprecated - use explode
					$cat_arr = explode("/",$parents);
					$topcat = $cat_arr[0];
						
					//check for related downloads
					//bh_find_PDF_files($post_id,$isbn13);	
									
					//add thumbnail
						
					//if supplies
					/*
						if($topcat == "Supplies"){
							bh_find_product_image($post_id,$p_LIN);		
						}else{
							//else use ISBN13			
							bh_find_product_image($post_id,$isbn13);
						}
						*/
						bh_find_product_image($post_id,$isbn13); //don't need second parameter?
						
				} //if ISBN13 present
				else {
					//no ISBN 13  - check for LIN instead (Supplies)
						bh_find_product_image($post_id,null); //don't need second parameter?
					//update thumbnail field
					/*
					if($LIN){
						bh_find_product_image($post_id,$LIN);
					} elseif ($upc) {
						bh_find_product_image($post_id,$upc);	
					} else {
						bh_find_product_image($post_id,'0');	
					}
					*/
										
				}
		} //if post type is product
		return;
	
} //end function bh products populate from api

// find a single file by criteria (such as ISBN or author name) in the file name
function bh_find_file_in_dir($dir,$criteria){
				$file_url = false; //initialize var
				$dir = trim($dir,'/'); //remove leading and/or trailing slash
				$filelist = scandir(ABSPATH . $dir . '/');
				foreach($filelist as $file){
					if(strpos($file,$criteria) !== false){
						//found image
						$file_url = '/'.$dir.'/'.$file;
						//now quit looking, we found it.
						return $file_url;
					} //end if
				} //end foreach
				//return $file_url;
}


// custom product list shortcode
// default [list_products category="all" search="" product_group="any" count="5" excerpt="false" meta="false" content="false" sort_by="pubdate" order="desc" class=""]
add_shortcode('extended-product-list','bhpub_list_posts_shortcode');
function bhpub_list_posts_shortcode( $atts , $content = null ){
	// Attributes
	extract( shortcode_atts(
		array(
			'search'=>'',					// search terms
			'count' => '5',					// posts_per_page
			'category' => '',				// show products in this category-slug only
			'product_group'=>'', 	// show products from this product group only (slug)
			'sort_by'=>'pubdate',	// sort by field; others include title, price
			'order'=>'DESC',  			// pubdate desc, pubdate asc, name, price desc, price asc?
			'content'=>false,   		// show post content
			'meta'=>false,				// show post meta
			'excerpt'=>false,			// show post excerpt
			'class'=>''						// custom list class
		), $atts )
	);
	
	
	 $pq = array(
   							'posts_per_page'=>$count,
							'post_type'=>'products',

   		);
	
	$orderby;$meta_key;
	// translate field names
	  switch ($sort_by) {
		  case 'pubdate' :
			 $orderby = 'meta_value_num';
			 $meta_key = 'wpcf-pubdate';
			 break;
		  case 'price' :
			 $orderby = 'meta_value_num';
			 $meta_key = 'wpcf-price';
			 break;
			case 'title' :
			 $orderby = 'title';
			 $meta_key=false;
			 break;
		  default :
		  	$orderby = $sort_by;
			 break;
   }
   
   if ($order!=''){
	   $pq['order'] = $order;
   }
    if ($orderby!=''){
	   $pq['orderby'] = $orderby;
   }
   if ($meta_key){
	   $pq['meta_key'] = $meta_key;
   }
   

	if($category){			
		$pq['category_name']=$category; //category slug
	}
	
	if($product_group){
		// change product group name to slug
		// (find custom tax term by name)
		if(strpos($product_group,'-')==FALSE){
			$thisTerm = get_term_by('name',$product_group,'product-group');
			$product_group_slug = $thisTerm->slug;
		}else{
			//should be a slug
			$product_group_slug = $product_group;
		}
	
		$pq['tax_query']=array(
													array(
																'taxonomy'=>'product-group',
																'field'=>'slug',
																'terms'=>$product_group_slug
													)
												);
	}
	
	if($search!=''){
		$pq['s']=$search;	
	}
	
	
		
	// the loop
	$products  = new WP_query($pq);
	if($products->have_posts()):
	$response = '<ul class="extended-product-list '. $class .'">';
   		while($products->have_posts()):$products->the_post();
			$response .= '<li>';
				$response .= '<a class="product-thumb" href="' . get_permalink() .'"><img src=' . bh_thumbnail_url(get_the_ID()) . ' /></a>'; //image 
				$response .= '<a class="product-title" href="' . get_permalink() .'">' . get_the_title() . '</a>'; // title and permalink
				if($excerpt){
					$response .= '<div class="product-excerpt">'. get_the_excerpt() .'</div>';// post excerpt
				}
				if($content){
					$response .= '<div class="product-description">'. get_the_content() .'</div>';// post excerpt// post content 
				}
				if($meta){
					$response .= '<div class="product-meta">posted ' . get_the_time('jS F Y')  . ' by ' .  get_the_author() .' in ' . get_the_category(', ') . '</div>';// post meta
				}
			$response .= '</li>';
		endwhile;
		$response .= '</ul>';
	endif;
	
	wp_reset_query();
	return $response;
}




//custom description handler
//check description chooser and copy selected to post content
function bh_product_description_chooser($post,$postarr){
	//check permissions & post type
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ($postarr->post_type == 'products') {
		global $post;
		//print_r($post);
		$post_id = $postarr->ID;
		if(!$post_id){
		$post_id = get_the_ID();
		}
		 if ( !current_user_can( 'edit_post', $post_id ) ) {return;}
		// get contents of custom fields
		$onix_desc = get_post_meta($post_id,'wpcf-product-onix-description',true);
		$custom_desc = get_post_meta($post_id,'wpcf-product-custom-description',true);
		$api_desc = get_post_meta($post_id,'wpcf-product-api-description',true);
		$post = get_post($post_id);
		$pc = $post->post_content;
		//default: assign post content to post description variable
		$post_desc = $pc;
		//check chooser
		$desc_chooser =  get_post_meta($post_id,'wpcf-product-choose-description',true);
		//copy selected field content to post content
		if($desc_chooser){
			//first check if custom is empty, and desc is not, copy desc to custom.
			//don't overwrite custom desc if present
			if(!$custom_desc){
				if($pc){
						update_post_meta($post_id,'wpcf-product-custom-description',$pc);	
				}
			}
			//now,  check chooser value
			//if ONIX, and if ONIX is populated, replace desc with ONIX
			if($desc_chooser=='product-onix-description'){
				if($custom_desc){
					$post_desc = $onix_desc;	
				}
			}
			//else, if CUSTOM, and custom not empty, replace desc with custom
			if($desc_chooser=='product-custom-description'){
				if($custom_desc){
					$post_desc = $custom_desc;	
				}
			}
			//else, if API, and api not empty, replace desc with api
			if($desc_chooser=='product-api-description'){
				if($api_desc){
					$post_desc = $api_desc;	
				}
			}
			
			//replace post content
					$mod_post = array(
													'ID' =>$post_id,
													'post_content' => $post_desc, 
													'post_type' => "products"
													);
						
						//unhook update posts functions 
						remove_action('save_post', 'bh_products_populate_from_api',20,2);
						remove_action('save_post', 'bh_product_description_chooser',30,2);
						
						wp_update_post($this_post);
						
						// re-hook update posts functions
						add_action('save_post', 'bh_products_populate_from_api',20,2);
						add_action('save_post', 'bh_product_description_chooser',30,2);
						
					
		
		
		}else{
			//don't rewrite content	
	
		}//end if chooser 
	}//end check post type== products
		
} //end bh product description chooser
add_action('save_post', 'bh_product_description_chooser',30,2);





//UPDATE FEB 2015
// find all possible attached images from post ID only
// check for WP Thumb
// collect ISBN, UPC, LIN from custom fields
// check local folders (img)
// check Scene7 (check ISBN, LIN, UPC - detect the "not found image" and try the next one)
// use blank or non-existent &defaultImage parameter and test for 403 (or not 200 or 304, maybe greater than 399)
// if image found, write to custom field

function bh_find_product_image($post_id,$DEPRECATED=null){
		$thumb_url = false;  // initialize
		//check for WP thumbnail
		if ( strlen( $img = get_the_post_thumbnail( $post_id, array( 150, 150 ) ) ) ) {
			$thumbnailexists = true;
		} else {
    		$thumbnailexists = false;
		}
		// if no WP thumbnail, check local folder(s)
		if(!$thumbnailexists){
				// check custom field local image
				$product_image = get_post_meta($post_id,'wpcf-product-image',true);
				if($product_image){$thumb_url=$product_image;}
				if(!$thumb_url) {
					// get possible image IDs
					$th_lin = get_post_meta($post_id,'wpcf-lin',true);
					$th_isbn = get_post_meta($post_id,'wpcf-isbn',true);
					$th_upc = get_post_meta($post_id,'wpcf-upc',true);
					// store (multiple) image urls in array
					//	echo $post_id.'<br/>'; //dev
					//echo $th_lin.' '.$th_isbn.' '.$th_upc.'<br/>'; //dev
					$thumb_list = array();
					$ex=false; //check internal first
					//books
					if($th_isbn){
						$bh_found = bh_find_file_in_dir('img/webcovers/',$th_isbn);	
					//	echo $bh_found;
						if($bh_found){$thumb_list[]=$bh_found;}
					}
					//supplies
					if($th_lin){
						echo 'searching by LIN<br/>';
						foreach(glob('../img/supplies/*/*'.$th_lin.'{*.jpg,*.jpeg}', GLOB_BRACE) as $l_img){
						//		echo $l_img;
								$thumb_list[] = str_replace('../img/','/img/',$l_img);
						}	//end for each
					}
					if($th_upc){
						echo 'searching by UPC<br/>';
						foreach(glob('../img/supplies/*/*'.$th_upc.'{*.jpg,*.jpeg}', GLOB_BRACE) as $u_img){
						//	echo $u_img;
								$thumb_list[] = str_replace('../img/','/img/',$u_img);
						}	//end for each
					}
					if($th_isbn){
						echo 'searching by ISBN<br/>';
						foreach(glob('../img/supplies/*/*'.$th_isbn.'{*.jpg,*.jpeg}', GLOB_BRACE) as $i_img){
						//	echo $i_img;
								$thumb_list[] = str_replace('../img/','/img/',$i_img);
						}	//end for each
					}
					//print_r($thumb_list); //dev
					
					//if thumb list is empty, check scene7
					if(empty($thumb_list)){
						$ex=true;
					//	echo "No local thumbnail, check scene7"; //dev
					$s7_url = 'https://s7d9.scene7.com/is/image/LifeWayChristianResources/';
							
							if($th_isbn){
								$s7_img = $s7_url.$th_isbn; //remove default 'not found' image response
								$s7_head= wp_remote_head($s7_img.'?defaultImage=null'); //get headers
									//print_r($s7_head); //dev
								if($s7_head['response']['code']<399){
									//if not error (forbidden or not found), add url to thumb list
									$thumb_list[] = $s7_img;
								} 
							}
							if($th_lin){
								$s7_img = $s7_url.$th_lin; //remove default 'not found' image response
								$s7_head= wp_remote_head($s7_img.'?defaultImage=null'); //get headers
									//print_r($s7_head); //dev
								if($s7_head['response']['code']<399){
									//if not error (forbidden or not found), add url to thumb list
									$thumb_list[] = $s7_img;
								}
							}
							if($th_upc){
								$s7_img = $s7_url.$th_upc; //remove default 'not found' image response
								$s7_head= wp_remote_head($s7_img.'?defaultImage=null'); //get headers
								//	print_r($s7_head); //dev
								if($s7_head['response']['code']<399){
									//if not error (forbidden or not found), add url to thumb list
									$thumb_list[] = $s7_img;
								}
							}
					//	print_r($thumb_list); //dev
					
					} //end if thumb_list is empty
					if($thumb_list){
						//make first item in thumb_list the thumb_url
						$thumb_url = $thumb_list[0];
						if(!$ex){
							update_post_meta($post_id,'wpcf-product-image',$thumb_url); // visible url in wp-admin custom field
						}
						//make available to bh_thumbnail function
						update_post_meta($post_id,'wpcf-external-thumb',$thumb_url);
					}else{
					//couldn't find any images, show the s7 Not Found image
						update_post_meta($post_id,'wpcf-external-thumb',$s7_img);
					}
					
				} //end if thumb_url is false
				else {
						update_post_meta($post_id,'wpcf-external-thumb',$thumb_url);
				}
				//exit(); //dev prevent submit
		} //end if WP thumbnail does not exist
		
		
} //end function find product image

// get product image
// 1. Check custom field for image url
// 2. Modified: Check php directory for image file containing ID //Check xml directory for images folder
// 3. Check Scene7

function bh_find_product_image__OLD($post_id,$product_id) {
	//echo 'find product image begin';
	$product_isbn=false;
	if($product_id){$product_isbn = $product_id;}
	$thumb_url = false;  // initialize
		if ( strlen( $img = get_the_post_thumbnail( $post_id, array( 150, 150 ) ) ) ) {
			$thumbnailexists = true;
		} else {
    		$thumbnailexists = false;
		}
		//echo 'thumbnail: '. $thumbnailexists;
		
		if(!$thumbnailexists){
			//check custom field
			$product_image = get_post_meta($post_id,'wpcf-product-image',true);
			//if url, validate file
			if($product_image){
				//attempt to load file
				//if(is_array(getimagesize('http://'.$_SERVER['SERVER_NAME'].$product_image))){
					$thumb_url = $product_image;
				//}

			}
			// check xml file dir
			/* //asp
			if(!$thumb_url) {
				$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
				$imagelist = simplexml_load_file($protocol.$_SERVER['HTTP_HOST'].'/img/webcovers/dir.asp');
				if($imagelist){
				$thisIMG = $imagelist->xpath('//image[contains(filename,'.$product_id.')]/fileurl');
				$thumb_url = (string) $thisIMG[0];
				}
			
			}
			*/
			//check file dir //php
			//echo $thumb_url;
			if(!$thumb_url) {
				if($product_isbn){
					$thumb_url = bh_find_file_in_dir('img/webcovers/',$product_isbn);
				
					//Feb 2015: check supplies images folder
					if(!$thumb_url){
						foreach(glob('img/supplies/*/*'.$product_isbn.'*') as $s_img){
								$thumb_url = '/'.$s_img;
						}	//end for each
					} //end if !thumb
				}
				if($thumb_url){
						//found the file - save url in custom field
						update_post_meta($post_id,'wpcf-product-image',$thumb_url);	
				}
			}
			//scene 7
			if(!$thumb_url){
				if($product_id!='0'){
				//if no url or invalid, attempt load with ISBN from scene7: https://s7d9.scene7.com/is/image/LifeWayChristianResources/9781433680083
				$s7_url = 'https://s7d9.scene7.com/is/image/LifeWayChristianResources/'.$product_id;
		
				if(is_array(getimagesize($s7_url))){
					$thumb_url = $s7_url . '?$Product$';	
					}
				}
			}
			if($thumb_url){
					update_post_meta($post_id,'wpcf-external-thumb',$thumb_url);
				
				}//if valid thumb url
			}//if no thumb
	//	}//end if isbn
//	} //end if product

	//return;
} //end function

//add_action('save_post','bh_find_product_image',30,2);

// get author image from server
function bh_find_author_image($post, $postarr) {
	
	if ($postarr->post_type == 'bhauthor') {
	
	//initialize
	$thumb_url = false;
	$author_image = false;
	
		global $post;
		$post_id = $postarr->ID;
		if(!$post_id){
		$post_id = get_the_ID();
		}
		
	if($post->post_status == 'trash' or $post->post_status == 'auto-draft'){
                return $post_id; //don't do anything if we're trashing the post
        }	
		
	$thumbnailexists = has_post_thumbnail($post_id);
		//if no thumbnail, check custom field for url
		if(!$thumbnailexists){
			$author_image = get_post_meta($post_id,'wpcf-author-photo',true);
			
			/* //( ASP/XML version)
			if(!$author_image){
				// check xml file dir
						$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
						$authorname = $post->post_title; //maybe change to first/last name
						$imagelist = simplexml_load_file($protocol.$_SERVER['HTTP_HOST'].'/img/authors/dir.asp');
						if($imagelist){
						$thisIMG = $imagelist->xpath('//image[contains(filename,'.$authorname.')]/fileurl');
						$thumb_url = (string) $thisIMG[0];
						}
					}
			*/
			if(!$author_image) {
				$author_image = bh_find_file_in_dir('img/authors',$authorname);
				/*$aimgdir = 'img/authors';
				$aimglist = scandir(ABSPATH . $aimgdir);
				foreach($imglist as $aimg){
					if(strripos($aimg,$authorname === false)){
						//not found, keep looking
					}else{
						//found image
						$author_image = '/'.$aimgdir.'/'.$aimg;
						//now quit looking, we found it.
						exit();
					}
				} */
			}		
						
	
		//skip validation, was performed on server to generate url
			$thumb_url = $author_image;
		
		if($thumb_url){
				update_post_meta($post_id,'wpcf-external-thumb',$thumb_url);
				//echo 'updated external thumb url';
				
				}
		} //end if no thumbnail
	} //end if author
	return;
}

add_action('save_post','bh_find_author_image',35,2);

function bh_thumbnail($post_id, $size, $link = NULL, $class=NULL) {
	$t_height=false;
	//get custom sizes
		if ($size=='thumbnail'):
			$t_height = 125;
		
		elseif ($size=='medium'):
			$t_height = 160;
		
		elseif ($size=='large'):
			$t_height = 300;
			//$thumb = $thumb.'?param='.$t_height			
		endif;
		if($t_height){
				$att_h = 'height="'.$t_height.'" ';	
				$s7_h = 'hei='.$t_height;
			}else{
				$att_h='';	
			}
			
	//check WP Thumbnail
	if(get_post_thumbnail_id($post_id)):
			$attr = array('class'=>$class);
			$thumb = get_the_post_thumbnail($post_id, $size,$attr);
			if ($link):
				echo '<a href="'.get_permalink($post_id).'" >'.$thumb.'</a>';	
			else:
				echo $thumb;
			endif;
	else:
		//local or scene 7 image
		$thumb = get_post_meta($post_id,'wpcf-external-thumb',true);
		if($thumb):
		$s7 = strpos($thumb,'scene7');
		if($s7){$thumb=$thumb.'?$Product$';} //if($s7){$thumb=$thumb.'?'.$s7_h;}
			if ($link):
				echo '<a href="'.get_permalink($post_id).'" ><img class="bh-thumb '.$size. ' '.$class.'" src="'.$thumb.'" '.$att_h.'/></a>';
			else:
				echo '<img class="bh_thumb '.$size.' '.$class.'" src="'.$thumb.'" '.$att_h.'/>';
			endif;
		endif;
	endif;
	
}//end bh thumbnail

function bh_thumbnail_url($post_id){
			if(get_post_thumbnail_id($post_id)):
				$thumb_url = wp_get_attachment_url( get_post_thumbnail_id($post_id) );
			else:
				$thumb_url = get_post_meta($post_id,'wpcf-external-thumb',true);
			endif;	
			if($thumb_url){return $thumb_url;}	
} //end bh thumbnail url

// insert chartbeat/omniture analytics code at bottom of page
function bh_add_more_analytics(){
	?>
    <!-- Chartbeat (header) -->
<script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>

<!-- Omniture -->
<script language="javascript">

var host = window.location.hostname;
var account = '';
var environment = '';
var arl = 'http://';
var sarl = 'https://';

switch (host.substr(host.lastIndexOf(".") + 1)){
	case 'test': //DEV
	case 'org':
		account = 'bhpublishingdev';
		environment = 'test';
		arl = "http://";
		sarl = arl;		
		break;
	case 'office': //MODEL
		account = 'bhpublishingdev';	
		environment = 'office';
		arl = "http://";
		sarl = arl;		
		break;		
	case 'com': //PROD
		account = 'bhpublishingprod';	
		environment = 'com';
		//arl = "http://a729.g.akamai.net/f/729/16507/7d/";
		//sarl = "https://a248.e.akamai.net/f/248/16507/7d/";
		arl = "http://";
		sarl = arl;		
		break;
	default: //ERROR
		account = 'bhpublishingdev';
		environment = 'test';
		break;
};
document.write('<sc'+'rip'+'t lan'+'guage="java'+'scr'+'ipt" type="te'+'xt/ja'+'vascri'+'pt" src="'+((location.protocol == 'http:')?arl:sarl)+'stats.lifeway.'+environment+'/header/?'+account+'"></scr'+'ipt>');
document.write('<sc'+'rip'+'t lan'+'guage="java'+'scr'+'ipt" type="te'+'xt/ja'+'vascri'+'pt" src="/propsgen.js"></scr'+'ipt>');
document.write('<sc'+'rip'+'t lan'+'guage="java'+'scr'+'ipt" type="te'+'xt/ja'+'vascri'+'pt" src="'+((location.protocol == 'http:')?arl:sarl)+'stats.lifeway.'+environment+'/footer/"></scr'+'ipt>');
</script>
<!-- end Omniture -->

<!-- Chartbeat (footer) -->
<script type="text/javascript">
var _sf_async_config={uid:30528,domain:"bhpublishinggroup.com"};
(function(){
  function loadChartbeat() {
    window._sf_endpt=(new Date()).getTime();
    var e = document.createElement('script');
    e.setAttribute('language', 'javascript');
    e.setAttribute('type', 'text/javascript');
    e.setAttribute('src',
       (("https:" == document.location.protocol) ? "https://a248.e.akamai.net/chartbeat.download.akamai.com/102508/" : "http://static.chartbeat.com/") +
       "js/chartbeat.js");
    document.body.appendChild(e);
  }
  var oldonload = window.onload;
  window.onload = (typeof window.onload != 'function') ?
     loadChartbeat : function() { oldonload(); loadChartbeat(); };
})();

</script>
    <?php
}
add_action('genesis_after','bh_add_more_analytics');

// remove empty custom field - parent product
// empty custom field prevents product from appearing in some lists
function bh_remove_empty_pgparent($post_id) {
	// verify this is not an auto save routine. 
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    //authentication checks
    if (!current_user_can('edit_post', $post_id)) return;
	 //obtain custom field meta for this post
	if(get_post_type($post_id)=='products'):
	 $key = 'wpcf-product-group-parent';
     $pgparent = get_post_meta($post_id,$key,true);
	 	 if(empty($pgparent)){
		  delete_post_meta($post_id,$key); //Remove post's custom field
		 }
		 endif;
}

add_action('save_post','bh_remove_empty_pgparent',50,1);



// find additional supplies product images and save for gallery display




//find product downloads from xml file, add to custom field
function bh_find_PDF_files($post_id,$isbn13) {
//xml file list

//find all files for given isbn
$pdflist = scandir(ABSPATH . 'PDF/');
if($pdflist){
//iterate through all files
foreach($pdflist as $attachment){
	//find files with containing ISBN
	if(strripos($attachment,$isbn13)!==false):
	
//sample chapters
	if(strripos($attachment,'sampCh.')!==false){
		//add to custom field
		update_post_meta($post_id,'wpcf-sample-chapter-pdf','/PDF/'.$attachment);
	}
//tip sheets
	if(strripos($attachment,'tips.')!==false){
		//add to custom field
		update_post_meta($post_id,'wpcf-tip-sheet-pdf','/PDF/'.$attachment);
	}
//table of contents
if(strripos($attachment,'toc.')!==false){
		//add to custom field
		update_post_meta($post_id,'wpcf-table-of-contents-pdf','/PDF/'.$attachment);
	}

//foreword
if ($attachment->type=='Foreword'){
		//add to custom field
		update_post_meta($post_id,'wpcf-foreword-pdf','/PDF/'.$attachment);
	}

//book club questions
/*
if ($attachment->type=='Book Club Questions'){
		//add to custom field
		update_post_meta($post_id,'wpcf-table-of-contents-pdf',$attachment->fileurl);
	}
*/
		endif;
		} //end for each
	} // end if pdf list

} //end function



//find product downloads from xml file, add to custom field
function bh_XML_find_PDF_files($post_id,$isbn13) {
//xml file list
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$pdf_xml = simplexml_load_file($protocol.$_SERVER['HTTP_HOST'].'/PDF/dir.asp');
//find all files for given isbn
$pdflist = $pdf_xml->xpath('//file[contains(isbn,'.$isbn13.')]');
if($pdflist){
//iterate through all files
foreach($pdflist as $attachment){
//sample chapters
	if ($attachment->type=='Sample Chapter'){
		//add to custom field
		update_post_meta($post_id,'wpcf-sample-chapter-pdf',$attachment->fileurl);
	}
//tip sheets
	if ($attachment->type=='Tip Sheet'){
		//add to custom field
		update_post_meta($post_id,'wpcf-tip-sheet-pdf',$attachment->fileurl);
	}
//table of contents
if ($attachment->type=='Table of Contents'){
		//add to custom field
		update_post_meta($post_id,'wpcf-table-of-contents-pdf',$attachment->fileurl);
	}

//foreword
if ($attachment->type=='Foreword'){
		//add to custom field
		update_post_meta($post_id,'wpcf-foreword-pdf',$attachment->fileurl);
	}

//book club questions
/*
if ($attachment->type=='Book Club Questions'){
		//add to custom field
		update_post_meta($post_id,'wpcf-table-of-contents-pdf',$attachment->fileurl);
	}
*/

		} //end if pdflist exists/is valid
	} // end for each

} //end function





//not working yet
function bh_sideload_post_thumbnail($thumb_url) {
	global $post;
	//if valid, upload and assign to post thumbnail
					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/media.php');
					set_time_limit(300);
					// Download file to temp location
					$tmp = download_url( $thumb_url );
		
					// Set variables for storage
					// fix file filename for query strings
					preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $thumb_url, $matches);
					$file_array['name'] = basename($matches[0]);
					$file_array['tmp_name'] = $tmp;
		
					// If error storing temporarily, unlink
					if ( is_wp_error( $tmp ) ) {
						@unlink($file_array['tmp_name']);
						$file_array['tmp_name'] = '';
					}
		
					// do the validation and storage stuff
					$thumbid = media_handle_sideload( $file_array, $post->ID);
					// If error storing permanently, unlink
					if ( is_wp_error($thumbid) ) {
						@unlink($file_array['tmp_name']);
						return $thumbid;
					}
					
					set_post_thumbnail( $post, $thumbid );
}

/*

// ADDITIONAL IMAGES GALLERY
// (moved to plugin)



*/


?>