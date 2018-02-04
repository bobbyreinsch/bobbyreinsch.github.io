<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );
@ini_set( 'upload_max_size' , '24M' );
@ini_set( 'post_max_size', '24M');


//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Winning Fotos', 'wf' ) );
define( 'CHILD_THEME_URL', 'http://www.lineage.solutions' );
define( 'CHILD_THEME_VERSION', '1.1.4' );
define( 'CHILD_THEME_DIR', get_stylesheet_directory());



load_theme_textdomain('kwf', get_stylesheet_directory() . '/languages' . '/');

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

// disallow comments on promotion post type
add_action( 'init', 'wf_remove_promotions_comments' );
function wf_remove_promotions_comments() {
    remove_post_type_support( 'promotion', 'comments' );
}


add_filter( 'genesis_pre_load_favicon', 'custom_favicon' );
function custom_favicon( $favicon_url ) {
	//return ''. get_stylesheet_directory_uri() .'/img/favicon.ico';
	return ''. get_stylesheet_directory_uri() .'/favicon.ico';
}

// Custom WordPress Login Page
function wf_custom_login_css() {
	wp_enqueue_style( 'wf_custom_login_css', get_stylesheet_directory_uri() . '/login.css' );
}
add_action('login_head', 'wf_custom_login_css');

function wf_login_link() {
	return get_bloginfo('url');
}
add_filter('login_headerurl','wf_login_link');

function wf_tooltip_logo() {
	return 'Kodak Winning Fotos';
}
add_filter('login_headertitle', 'wf_tooltip_logo');


add_filter('widget_text', 'do_shortcode');
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// get query vars from url
function custom_query_vars_filter($vars) {
  	$vars[] = 'c';
  	$vars[] = 't';
  	$vars[] = 'count';
  	$vars[] = 'format';
	$vars[] = 'wfcb';
	$vars[] = 'size';
  return $vars;
}
add_filter( 'query_vars', 'custom_query_vars_filter' );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'wf_enqueue_scripts_styles' );
function wf_enqueue_scripts_styles() {
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/slick/slick.min.js', array ( 'jquery' ), 1.0 , false);
	wp_enqueue_script( 'site-js', get_stylesheet_directory_uri() . '/wf.js', array ( 'jquery' ), 1.1 , false);
	wp_enqueue_style( 'slick-css', get_stylesheet_directory_uri() . '/slick/slick.css', 1.0 );
	wp_enqueue_style( 'slick-theme', get_stylesheet_directory_uri() . '/slick/slick-theme.css', 1.0 );
	wp_enqueue_style( 'fontello', get_stylesheet_directory_uri() . '/fontello/css/wf-blog-icons.css' , 1.0);
	wp_enqueue_style( 'dashicons' );
	wp_localize_script( 'site-js', 'wfAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) , 'redirurl' => get_bloginfo('url') ));
	}


//remove RUCKSACK font - replace with Graphik via @font-face
//add_action('wp_head','wf_typekit_script');
function wf_typekit_script(){
    echo '<script src="https://use.typekit.net/fdd3lim.js"></script>';
    echo '<script>try{Typekit.load({ async: true });}catch(e){}</script>';
}


// GTM tags (recommended placement)
// replaces code in Genesis Header and Footer tags in the admin
add_action('wp_head','wf_gtm_head',5);
add_action('genesis_before','wf_gtm_body');

function wf_gtm_head(){
	?><!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-M8WSGSF');</script>
	<!-- End Google Tag Manager -->
	<?php
}

function wf_gtm_body(){
	echo '<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M8WSGSF"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->';
}



/* ______________________________________________________________ */



// Custom Thumbnail sizes

add_image_size( 'header' , 1440, 9999 );
add_image_size( 'category-tile' , 445 , 225 );
add_image_size( 'post-image-lg' , 945 , 9999 );
add_image_size( 'post-image-md' , 709 , 9999 );
add_image_size( 'post-image-sm' , 473 , 9999 );
add_image_size( 'post-image-tile' , 9999 , 356);
add_image_size( 'post-thumb', 300 , 225 );







//custom sidebars
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
unregister_sidebar( 'header-right' );
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );
unregister_sidebar( 'home-middle' );



genesis_register_sidebar(array(
    'name'=>'Home Lower',
    'id'=>'home_lower_sidebar',
    'description' => 'This is the text below the home page content.',
    'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));


genesis_register_sidebar(array(
    'name'=>'Footer Right Column',
    'id'=>'footer_right_sidebar',
    'description' => 'This is the lower right column below the home page content.',
    'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));

genesis_register_sidebar(array(
    'name'=>'Page Nav',
    'id'=>'page_nav_sidebar',
    'description' => 'This is the page nav above the page content.',
    'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));


// header

remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );
// remove_action( 'genesis_header','genesis_do_header');
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );


add_action('genesis_site_title','wf_site_title');
function wf_site_title(){
	$blog_home_url = get_site_url();
	$wf_home_url = 'https://kodakwinningfotos.com';
	echo '<h1 class="site-title"><a href="'. $wf_home_url .'/"></a></h1>';
}

add_filter( 'genesis_search_text', 'sp_search_text' );
function sp_search_text( $text ) {
	return esc_attr( __('Search the blog','kwf'));
}

add_filter( 'genesis_search_button_text', 'wf_search_button_icon' );
function wf_search_button_icon( $text ) {
	return esc_attr( '&#59395;' );
}



// home page content sections

add_action( 'genesis_after_content', 'wf_home_addl_content' );
function wf_home_addl_content() {
  if(is_home() || is_front_page()){

      if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Home Lower' ) ) {
        }

    }
}

// nav bar for pages

add_action('genesis_header','wf_nav_bar');
function wf_nav_bar(){
        if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Page Nav' ) ) {
    }
}

add_action('genesis_after_header','wf_blog_navigation');
function wf_blog_navigation(){
	if(!is_front_page()):
		echo '<div class="blog-nav-section"><div class="blog-nav"><div class="wrap">';
		$blogmenu = array(
												'menu'=>'blog-navigation'
											);
		wp_nav_menu($blogmenu);
		echo '</div></div></div>';
	endif;
}

add_action('genesis_after_header','wf_mobile_nav_bar');
function wf_mobile_nav_bar(){
		echo '<div class="mobile-nav-section">';
			if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Page Nav' ) ) {
		}
		echo '</div>';
}

// remote authentication
add_action('genesis_footer','wf_remote_user_frame', 50);

function wf_remote_user_frame(){
	//echo '<iframe style="visibility:hidden !important;border:none !important;" width="0" height="0" src="https://kodakwinningfotos.com/webhooks/messages"></iframe>';
	echo '<iframe style="visibility:hidden !important;border:none !important;" width="0" height="0" src="https://www.kodakwinningfotos.com/webhooks/messages"></iframe>';
	echo '<script>
	function changeMenus(userObject){
		if("username" in userObject){
	    jQuery.ajax({
	            type:"post",
	            url: wfAjax.ajaxurl,
	            data: {
								action:"authenticate_user",
	              username:userObject.username,
	              email:userObject.email,
	              firstName:userObject.firstName,
	              lastName:userObject.lastName
	            },
	            success:function(data){
	              if(data){
	                // add number of tokens
									if(userObject.token){
	                  jQuery("#menu-header-nav .btn-tokens>a").html(userObject.token + " Tokens");
									}else{
										jQuery("#menu-header-nav .btn-tokens>a").html("<span class=\"plus-icon\">&#43;</span>" + " Tokens");
									}
	                // add profile pic
	                  jQuery("#menu-header-nav .btn-profile>a").css("background-image","url("+ userObject.profileImageURL +")");
									// show/hide the correct menus
									jQuery("body").addClass("logged-in").addClass("admin-bar");
								}
	            }
	      });
	    }
	  }
	//var attempts = 1;
	function receiveMessage(event) {
	    var authUser = event.data;
	    console.log(authUser);

			 //auth function
			 if(authUser){
			 	changeMenus(authUser);
		 	}else{
				//if(attempts){
				//	attempts--; //try again with www
				// }else{
					jQuery.ajax({
												type:"post",
												url: wfAjax.ajaxurl,
												data:{action:"no_user_found"},
												success:function(data){
													if(data == "Not Authorized - redirecting..."){
														window.location.href = wfAjax.redirurl;
													}
												}
					});
				//}
			}
	}
	window.addEventListener("message", receiveMessage, false);
	</script>';

}



// ajax functions

add_action("wp_ajax_authenticate_user", "wf_check_authentication");
add_action("wp_ajax_nopriv_authenticate_user", "wf_check_authentication");
add_action("wp_ajax_no_user_found", "wf_logout_user");
add_action("wp_ajax_nopriv_no_user_found", "wf_logout_user");

add_action('set_current_user', 'wf_hide_admin_bar');
//hide admin bar for subscribers (login/profile at main site only)
function wf_hide_admin_bar() {
  if (!current_user_can('edit_posts')) {
    show_admin_bar(false);
  }
}
// check postMessage in js

function wf_check_authentication(){
	 if(!current_user_can('administrator')): // don't check for admins - all other log in through wf site

		//check response
		$username = $_POST['username'];
		if($username):
			//authorized
			//parse response? maybe just send username, that should be all we need

			// if user is currently logged in
			if(is_user_logged_in()){
				// verify matching usernames
				$curr_user = wp_get_current_user();
				if($curr_user->username==$username){
					//do nothing, we're logged in
				}else{
					wp_logout(); // must log out current user, they don't match.
					$user = wf_get_user($username);
					wp_set_auth_cookie($user->ID);
					do_action('wp_login',$user->ID);
				}
			}else{
				// check for user
				$user = wf_get_user($username);
				//$user = set_current_user($user_id,$username);
	      // this will actually make the user authenticated as soon as the cookie is in the browser
	      wp_set_auth_cookie($user->ID);
	      // the wp_login action is used by a lot of plugins, just decide if you need it
	      do_action('wp_login',$user->ID);
			}
			// return true;
		else:
			//not authorized... logout
			wf_logout_user();
			//return false;

		endif;
	endif;

}

function wf_get_user($username){
	$user_id = username_exists($username);
	if($user_id>0){
		// userdata will contain all information about the user
		$userdata = get_userdata($user_id);
	}else{
		// create a new user
		$newuserdata = array(
												'user_email' 	=> $_POST['email'],
                        'user_login' 	=> $_POST['username'],
                        'first_name' 	=> $_POST['firstName'],
                        'last_name' 	=> $_POST['lastName'],
												'user_pass'		=> null,
												'role' 				=> 'subscriber',
                      );
    $new_user_id = wp_insert_user( $newuserdata );
		$userdata	= get_userdata($new_user_id);
	}
	return $userdata;
}

function wf_logout_user(){
if(!current_user_can('publish_posts')): //ignore for admins/editors/authors
	if(is_user_logged_in()){
		echo "Not Authorized - redirecting...";
	}else{
		echo "Not Authorized - log in at https://kodakwinningfotos.com";
	}
	wp_logout();
endif;
}











add_action('genesis_before_content','wf_home_page_slider');
function wf_home_page_slider(){
	if(is_home() || is_front_page()){
		remove_action('genesis_post_content','genesis_do_post_content');
		add_action('genesis_before_post_content','wf_home_content');
		add_action('genesis_after_footer','wf_home_slider_js');
	}
}

function wf_home_content(){
	echo do_shortcode('[slider]');
}


function wf_home_slider_js(){
	// http://kenwheeler.github.io/slick/
	?>
		<script>
			jQuery(document).ready(function($){
				$('.slider').slick({
					dots:true,
					arrows:false
					//	lazyLoad: 'ondemand',
					//	slidesToShow: 1,
					//	slidesToScroll: 1
				});
			});
		</script>
	<?php
}




// category page
add_filter( 'body_class' , 'wf_author_classes' );
function wf_author_classes($classes){
	if(is_author()):
		$classes[] = 'category';
		$classes[] = 'archive';
	endif;
	return $classes;
}

add_action('genesis_before','wf_category_page');
function wf_category_page(){
	if(is_category()):
		// category header
		add_action('genesis_before_content','wf_category_header');
		// category filters
		add_action('genesis_before_content','wf_show_category_filters');
		// replace loop
		//remove_action('genesis_loop','genesis_do_loop');
		//add_action('genesis_loop','wf_singlecat_loop');
    // note: standard loop is fine, just need to change ppp for pagination in pre_get_posts
		// format posts
		remove_action('genesis_post_content','genesis_do_post_content');
		add_action('genesis_before_post_title','wf_show_post_thumb');
		add_action('genesis_before_post_title','wf_catlist_postinfo');
	endif;
	if(is_author()):
		// author header
		add_action('genesis_before_content','wf_author_header');
		// category filters
		add_action('genesis_before_content','wf_show_category_filters');
		// replace loop
		//remove_action('genesis_loop','genesis_do_loop');
		//add_action('genesis_loop','wf_singlecat_loop');
    // note: standard loop is fine, just need to change ppp for pagination in pre_get_posts
		// format posts
		remove_action('genesis_post_content','genesis_do_post_content');
		add_action('genesis_before_post_title','wf_show_post_thumb');
		add_action('genesis_before_post_title','wf_catlist_postinfo');
	endif;
	if(is_page('all-categories')||is_page('all-authors')):
		// category header
		add_action('genesis_before_content','wf_all_categories_header');
		// category filters
		add_action('genesis_before_content','wf_show_category_filters');
		remove_action('genesis_loop','genesis_do_loop');
		add_action('genesis_loop','wf_page_loop');
		// format posts
		remove_action('genesis_post_content','genesis_do_post_content');
		add_action('genesis_before_post_title','wf_show_post_thumb');
		add_action('genesis_before_post_title','wf_catlist_postinfo');
	endif;
}

add_action('pre_get_posts','wf_archive_pagination');

function wf_category_header(){
	$category = get_category( get_query_var( 'cat' ) );
	$img_arr = array(
										'term_id'=>$category->term_id,
										'size'=>'header',
										//'output'=>'html',
										'alt' => $category->name,
									);
	$cat_bg = types_render_termmeta( 'category-thumbnail', $img_arr );
	echo '<div class="category-page-header">';
	echo '<div class="cat-bg">' . $cat_bg . '</div>';
	echo '<h2 class="category-title">';
	_e(single_cat_title(false),'kwf');
	echo '</h2>';
	echo '<div class="category-description">' . category_description() . '</div>';
	echo '</div>';
}

function wf_author_header(){
	$hdr_bg = genesis_get_image();
	echo '<div class="category-page-header">';
	echo	get_avatar( get_the_author_meta( 'ID' ));
	echo '<h2 class="category-title">';
	_e(get_the_author_meta('nicename'),'kwf');
	echo '</h2>';
	echo '<div class="category-description">' . nl2br(__(get_the_author_meta('description'),'kwf')) . '</div>';
	echo '</div>';
}

function wf_all_categories_header(){
	$hdr_bg = genesis_get_image();
	echo '<div class="category-page-header">';
	echo '<div class="cat-bg">' . $hdr_bg . '</div>';
	echo '<h2 class="category-title">';
	_e(get_the_title(),'kwf');
	echo '</h2>';
	//echo '<div class="category-description">' . category_description() . '</div>';
	echo '</div>';
}


function wf_show_category_filters(){
	echo '<div class="category-filters"><div class="wrap">';
	echo '<div class="select-boxes">';
	// categories drop-down
	$category_list = array(
													'show_option_all'=>__('All Categories','kwf'),
													'class'=>'category-select',

												);
	wp_dropdown_categories($category_list);

  //show only users with posts
  $all_u = get_users(array('fields'=>'id','who'=>'authors')); //output only id of author role
  foreach($all_u as $uid){
    $pcount = count_user_posts($uid);
    if($pcount > 0){
      $include_arr[] = $uid; //only those with posts
    }
  }
  $include = implode (", ", $include_arr);

  // authors drop-down
  $authors_list = array(
													'show_option_all'=>__('All Authors','kwf'),
													'class'=>'authors-select',
													'who' => 'authors',
													'include' => $include
													);
	wp_dropdown_users($authors_list);

	echo '</div>';
	// search form?
	/*
		echo '<div class="search-form">';
		get_search_form();
		echo '</div>';
	*/
	echo '</div></div>';
}

function wf_page_loop(){
  $cat = get_queried_object();
	$args = array(
									'paged'=>get_query_var('paged'),
									'posts_per_page'=>9
								);
	if(!is_page('all-categories')&&!is_page('all-authors')){
			$args['cat'] = $cat->term_id;
	}
	genesis_custom_loop($args); //pagination works here
}

// removed custom loop, replaced with pre_get_posts function
function wf_archive_pagination($query){
  if(is_author()):
    $query->query_vars['posts_per_page'] = 9;
  endif;

  if(is_category()):
      $query->query_vars['posts_per_page'] = 9;
  endif;
}

function wf_show_post_thumb() {
	global $post;
	$img_args = array(
											'format'=>'src',
											'size'=>'post-thumb',
										);
	$img = genesis_get_image($img_args);
	if($img){
		echo '<a href="'. get_permalink($post->ID) .'"><div class="post-thumb"><img src="'. $img .'"></div></a>';
	}
}

function wf_catlist_postinfo() {
	echo '<h6 class="post-category">'. get_the_category_list(', ') .'</h6>';
}






// single blog post

add_action('genesis_before_content','wf_blog_post_header_image');
function wf_blog_post_header_image(){
	if(is_singular('post')){
		$bg = genesis_get_image(array('size'=>'header'));
		if($bg){
				echo '<div class="post-bg">'. $bg .'</div>';
		}
	}
}


add_action('genesis_before','wf_post_columns');

function wf_post_columns(){
	if(is_singular('post')){
		remove_action('genesis_post_content','genesis_do_post_content');
		add_action('genesis_post_content','wf_ad_network_columns');
	}
}

function wf_ad_network_columns(){
		echo '<div class="post-flex-wrap"><div class="flex-post-content">';
			the_content();
		echo '</div><div class="flex-ad-content">';
			wf_show_ads();
		echo '</div></div>';
}

function wf_show_ads(){
// options: choose random ad from Promotions post type, based on active dates
	global $post;
	// get current date/time
	$today = date('Y-m-d',strtotime("today"));
	// get all categories
	$allcats = get_terms('category', array('fields'=>'ids'));
	//get current category
	$currcats = wp_get_post_categories($post->ID, array('fields'=>'ids'));
	// remove current category from array
	$notcats = array_diff($allcats,$currcats);

	// get dates
	$dates = array(
										'relation'=>'AND',
										array(
											'key'=>'wpcf-promotion-start-date',
											'value'=> strtotime("today"),
											'compare'=>'<=',
											// 'type'=>'DATE'
										),
										array(
											'key'=>'wpcf-promotion-end-date',
											'value'=> strtotime("today"),
											'compare'=>'>=',
											// 'type'=>'DATE'
										)
								);
	$promo = array(
										'post_type'=>'promotion',
										'orderby'=>'rand',
										'posts_per_page'=>1,
										'meta_query'=>$dates

								);

	if($notcats){
		$promo['category__not_in'] = $notcats;
		}

	$pro = new WP_Query($promo);


	if($pro->have_posts()){
		echo '<div class="flex-ad-wrap">';
		while($pro->have_posts()):$pro->the_post();

				// echo date('Y-m-d', get_post_meta(get_the_ID(),'wpcf-promotion-start-date',true));
				// echo '<br />' . date('Y-m-d', strtotime("today")) . '</br>';
				// echo date('Y-m-d', get_post_meta(get_the_ID(),'wpcf-promotion-end-date',true));
				// echo '<br /><br/>';

				the_content();

		endwhile;
		echo '</div>';
		wp_reset_postdata();
	}

	// or, load js here instead to bring in posts via ajax. v2 copy above function
}



add_action('genesis_after_content','wf_blog_post_footer_image');
function wf_blog_post_footer_image(){
	if(is_singular('post')){
		$bg = genesis_get_image(array('size'=>'header'));
		if($bg){
				echo '<div class="post-bg post-footer">'. $bg .'</div>';
		}
	}
}

add_action('genesis_before_footer','wf_blog_post_list');
function wf_blog_post_list(){
	if(is_singular('post')){
		echo '<div class="related-posts"><div class="wrap">';
		echo '<h4>'. __('You May Also Like...','kwf') . '</h4>';
			echo do_shortcode('[posts_list limit=3]');
		echo '</div></div>';
	}
}

add_filter( 'genesis_post_meta', 'wf_post_meta' );
function wf_post_meta($post_meta) {
if(is_singular('post')){
	$post_meta = '[post_tags before="Tags: "]';
	return $post_meta;
}}

add_filter( 'genesis_post_info', 'wf_post_info' );
function wf_post_info($post_info) {
	if(is_singular('post')){
		$avatar = get_avatar( get_the_author_meta( 'ID' ), 32 );
		$post_info = '[post_categories before=""] [post_date] [post_author_posts_link] <span class="post-avatar">'. $avatar .'</span>';
		return $post_info;
	}
}

add_filter( 'comment_form_defaults', 'wf_comment_form_defaults' );
function wf_comment_form_defaults( $defaults ) {

	$defaults['title_reply'] = __( 'Join the Discussion', 'kwf' );
	return $defaults;

}

add_filter( 'genesis_title_comments', 'wf_genesis_title_comments' );
function wf_genesis_title_comments() {
	$title = '<h3>'. __('Discussion','kwf') . '</h3>';
	return $title;
}




// search results

add_action('genesis_before','wf_search_results');
function wf_search_results(){
if(is_search()):
	remove_action('genesis_post_title','genesis_do_post_title');
	remove_action('genesis_post_content','genesis_do_post_content');

	add_action('genesis_before_post_title','wf_search_thumb');
	add_action('genesis_before_post_title','wf_search_wrap_start');
	add_action('genesis_after_post_title','wf_catlist_postinfo');
	add_action('genesis_post_title','wf_search_result_title');
	add_action('genesis_post_content','wf_search_result_excerpt');
	add_action('genesis_after_post_content','wf_search_wrap_end');
	add_action('genesis_before_footer','wf_search_results_related');
endif;
}

function wf_search_thumb() {
	global $post;
	$img_args = array(
											'format'=>'src',
											'size'=>'thumbnail',
										);
	$img = genesis_get_image($img_args);
	if($img){
		echo '<div class="search-post-thumb"><a href="'. get_permalink($post->ID) .'"><img src="'. $img .'"></a></div>';
	}
}

function wf_search_result_title(){
	//highlight search terms
	$s = get_query_var('s');
	$title = wf_search_highlight(get_the_title(),$s);
	echo '<h2 class="entry-title"><a href="'. get_permalink() .'">' . $title . '</a></h2>';
}

function wf_search_result_excerpt(){
	//highlight search terms
	$s = get_query_var('s');
	$excerpt = wf_search_highlight(get_the_excerpt(),$s);
	echo $excerpt;

}

function wf_search_highlight($text,$terms){
	$keys = explode(" ",$terms);
	$text = preg_replace('/('.implode('|', $keys) .')/iu', '<span class="search-highlight">\0</span>', $text);
	return $text;
}

function wf_search_results_related(){
	echo '<div class="related-posts"><div class="wrap">';
	echo '<h4>'. __('Recent Posts','kwf') .'</h4>';
		echo do_shortcode('[posts_list limit=3]');
	echo '</div></div>';
}

function wf_search_wrap_start(){
	echo '<div class="search-post-content">';
}

function wf_search_wrap_end(){
	echo '</div><!-- end post content -->';
}


add_filter( 'genesis_prev_link_text', 'gt_review_prev_link_text' );
function gt_review_prev_link_text() {
        $prevlink = '&laquo; ' . __('Previous Page','kwf');
        return $prevlink;
}
add_filter( 'genesis_next_link_text', 'gt_review_next_link_text' );
function gt_review_next_link_text() {
        $nextlink = __('Next Page','kwf') .' &raquo;';
        return $nextlink;
}



// add featured image to rss feed

function wf_add_featured_image_in_rss() {
    global $post;
    if ( has_post_thumbnail($post->ID) ) {
        $featured_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
    } elseif ( function_exists( 'genesis_get_image' ) && genesis_get_image(array('post_id'=>$post->ID,'format'=>'src','size'=>'medium')) ) {
        $featured_image = genesis_get_image(array('post_id'=>$post->ID,'format'=>'src','size'=>'medium'));
    } else {
        $featured_image = false;
    }

    if ($featured_image) {
        echo "\t" . '<enclosure url="' . get_site_url('/') . $featured_image . '" length="' . filesize(get_attached_file(get_post_thumbnail_id($post->ID))) . '" type="image/jpeg" />' . "\n";
        echo "\t" . '<image>' . get_site_url('/') . genesis_get_image(array('post_id'=>$post->ID,'format'=>'src','size'=>'medium')) . '</image>' . "\n";
        echo "\t" . '<category domain="https://kodakwinningfotos.com/">' . get_the_category_list(', ',$post->ID) . '</category>' . "\n";

    }
    
}
add_action( 'rss2_item', 'wf_add_featured_image_in_rss' );




//shortcodes

add_shortcode('category_list','wf_list_categories');
function wf_list_categories($atts,$content=null){
	extract(shortcode_atts(array(
			'limit' => 6,
			'class' => 'category-list',
			'thumb' => 'category', // thumbnail soruce, category or post
			'thumbsize' => 'category-tile', // thumbnail size
			'order' => 'id', // choose which categories to display
			// also allowed: recent; views,visits (synonyms); comments; alpha
	), $atts));
		$html = '';$cat_args = '';$post_args = ''; $post_ids = false;$cat_ids = false;
		$cat_args = array(
												'taxonomy'=>'category',
												'hide_empty'=>0,
												'exclude' => 1, // uncategorized

											);
		$allcats = get_terms($cat_args); // full list of categories
		//$html = var_dump($allcats);

		// compare to post lists
		if($order=='views'||$order=='visits'):
		// order by most popular (views)
		$popular_views = array(
												'post_type'=>'post',
												'post_status'=>'publish',
												'posts_per_page'=>30, //enough to get different categories
												'meta_key'=>'popular_posts',
												'orderby'=>'meta_value_num',
												'order'=>'DESC',
												'fields' => 'ids'
											);

		$post_ids = new WP_Query($popular_views);

		elseif($order=='comments'):
		// order by most popular (comments)
			$popular_comments = array(
													'post_type'=>'post',
													'post_status'=>'publish',
													'posts_per_page'=>30, //enough to get different categories
													'orderby'=>'comment_count',
													'order'=>'DESC',
													'fields' => 'ids'
												);

			$post_ids = new WP_Query($popular_comments);

		elseif($order=='recent'):
		// order by recently updated
			$recent_posts = array(
													'post_type'=>'post',
													'post_status'=>'publish',
													'posts_per_page'=>30, //enough to get different categories
													'fields' => 'ids'
												);

			$post_ids = new WP_Query($recent_posts);

		elseif($order=='alpha'):
			$cat_args = array(
													'taxonomy'=>'category',
													'exclude' => 1, // uncategorized
													'hide_empty' => 0,
													'orderby' => 'title',
													'fields' => 'ids',
													'number' => $limit
												);
			$cat_ids = get_terms($cat_args);


		else:
		// id order
			$cat_args = array(
													'taxonomy'=>'category',
													'hide_empty' => 0,
													'exclude' => 1, // uncategorized
													'orderby' => 'term_id',
													'fields' => 'ids',
													'number' => $limit
												);
			$cat_ids = get_terms($cat_args);

		endif;

		if($post_ids):
			$used_cats = array();$cat_posts = array();
			// loop through ids, get category
			foreach($post_ids as $id){
			// check for duplicate category
				$this_cat = get_the_category($id);
				$catID = $this_cat->term_id;
				if(!in_array($catID,$used_cats)):
						// add to duplicate array
						$used_cats[] = $catID;
						// add to post id array
						$cat_posts[] = $id;
				endif;
			}
		endif;


		if($cat_ids):
			if($thumb!='category'):
				// show most recent post thumbnail for each category
				$used_posts = array();$cat_posts = array();
				foreach($cat_ids as $catID){
					$cat_arr = array(
															'post_type'=>'post',
															'posts_per_page'=>1,
															'cat' => $catID,
															'fields'=> 'id'
														);
					$c_posts = new WP_Query($cat_arr);
					//remove possible duplicate posts
					foreach($c_posts as $id){
						if(!in_array($id,$used_posts)):
							$used_posts[] = $id;
							$cat_posts[] = $id;
						endif;
					}
				}
			endif;
		endif;


		if($thumb=='category' && $cat_ids):
			$html .= '<ul class="category-list category-list-tiles">';
			foreach($cat_ids as $catID){
				$cat = get_category($catID);
				$html .= '<li><a class="category-link" href="' . get_category_link($catID) . '">';
				$img_arr = array(
													'term_id'=>$catID,
													'size'=>'category-tile',
													//'output'=>'html',
													'alt' => $cat->name,
												);

				$cat_img = types_render_termmeta( 'category-thumbnail', $img_arr );
				if($cat_img){
					$html .= '<div class="category-thumb">' . $cat_img . '</div>';
				}
				$html .= '<h4 class="category-title">' . __($cat->name,'kwf') . '</h4>';
				$html .= '</a></li>';

			}
			$html .= '</ul>';
		elseif($cat_posts):
			$postlist = array(
													'post_type'=>'post',
													'posts_per_page'=>$limit,
													'post__in' => $cat_posts,

												);
				$loop = new WP_Query($postlist);
				if($loop->have_posts()):
					$html .= '<ul class="category-list category-list-post-thumbs">';
					while($loop->have_posts()):$loop->the_post();
						$categories = get_the_category();
						if ( ! empty( $categories ) ) {
								$html .= '<li>';
								$html .= '<a class="category-link" href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">';
								if(genesis_get_image()){
									$img_args = array(
																			'format'=>'src',
																			'size'=>$thumb,
																		);
									$img = genesis_get_image($img_args);
									$html .= '<div class="category-thumb post-thumb"><img src="'. $img .'"></div>';
										$html .= '<h4 class="category-title">' . esc_html( $categories[0]->name ) . '</h4>';
								}
						$html .= '</a></li>';
					}
					endwhile;
					$html .= '</ul>';
				endif;
		endif;

		return $html;
}
//https://developer.wordpress.org/reference/functions/get_term_meta/
//https://developer.wordpress.org/reference/functions/get_terms/



add_shortcode('posts_list','wf_list_posts');
function wf_list_posts($atts,$content=null){
	extract(shortcode_atts(array(
			'limit' => 6,
			'class' => 'post-list',
			'thumb' => 'post-thumb'
	), $atts));

	$args = array(
									'post_type'=>'post',
									'posts_per_page' => $limit,
	);
	$html = '';
	$list = new WP_Query($args);
	if($list->have_posts()):
		$html .= '<ul class="'. $class .'">';
		while($list->have_posts()):$list->the_post();
			$html .= '<li><a href="'. get_permalink() .'">';
			if($thumb){
				$img_args = array(
														'format'=>'src',
														'size'=>$thumb,
													);
				$img = genesis_get_image($img_args);
				$html .= '<div class="post-thumb"><img src="'. $img .'"></div></a>';
			}
			$html .= '<h6 class="post-category">'. get_the_category_list(', ') .'</h6>';
			$html .= '<h4 class="post-title"><a href="'. get_permalink() .'">'. get_the_title() .'</a></h4>';
			$html .= '</li>';
		endwhile;
		$html .= '</ul>';
	endif;
	wp_reset_postdata();
	return $html;

}

add_shortcode('slider','wf_posts_slider');
function wf_posts_slider($atts,$content=null){
	extract(shortcode_atts(array(
			'limit' => 4,
			'class' => 'posts-slider-default',
			'type' => 'post', // CPT allowed here
			'tags' => '',
			'category' => '',
	), $atts));

	$qarr = array(
									'post_type' => $type,
									'post_status' => 'publish',
									'posts_per_page' => $limit,
								);

	if($tags){$qarr['tag_slug__in'] = $tags;}
	if($category){$qarr['category_name']=$category;}

	$slider = new WP_Query($qarr);
	if($slider->have_posts()):
		$html .= '<div class="slider '. $class .'">';

		while($slider->have_posts()):$slider->the_post();
			$html .= '<div class="slide postid-'. get_the_ID() .'">';
			if(genesis_get_image()){
				$img_attr = array(
														'format'=>'src',
														'size'=>'header',
													);
				$html .= '<div class="slide-bg"><img src="' . genesis_get_image($img_attr) . '" /></div>';
			}
			$html .= '<div class="wrap slide-wrap">';
			$html .= '<h2 class="slide-title">'. get_the_title() .'</h2>';
			$html .= '<div class="slide-meta">';
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
			    $html .= '<a class="slide-cat" href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
			}
			$html .= '<span class="slide-date">'. get_the_date() .'</span>';
			$html .= '<span class="slide-author">' . get_the_author() . '</span>';
			$html .= '</div>';
			$html .= '<div class="slide-desc">'. get_the_excerpt() .'</div>';
			$html .= '<div class="slide-btn"><a class="button" href="'. get_permalink() .'">'. __('Read This','kwf') .'</a></div>';
			$html .= '</div>'; // slide-wrap
			$html .= '</div>'; // slide

		endwhile;
		$html .= '</div>';
	endif;
	wp_reset_postdata();
	return $html;
}






// columns shortcode for page content
add_shortcode('columns','wf_do_columns');
function wf_do_columns($atts,$content=null){
    $col_class='';$first='';$errors=[];
    extract(shortcode_atts(array(
        "number" => 1,
        "of" => 2 // up to 4
    ), $atts));

    switch ($of) {
        case 2:
            $col_class = 'one-half';
            break;
        case 3:
            $col_class = 'one-third';
            break;
        case 4:
            $col_class = 'one-fourth';
            break;
        default:
            $col_class = '';
            $errors[] = 'Invalid total number of columns. ';
    }

    if($number==1){
        $first=' first';
    }else{
        $first='';
    }

    if($number>$of){
        $errors[] = 'Number of columns is greater than total columns. ';
    }

    if($number<1){
        $errors[] = 'You cannot assign a column before 1. ';
    }

    if(empty($errors)){
        $col_content = '<div class="' . $col_class . $first . '">' . apply_filters('the_content', $content) . '</div>';

        if($number==$of){
            //last column so add clearfix
            $col_content .= '<div class="clearfix"></div>';
        }

    }else{
        //there were errors
        $col_content = $content . '<!-- There were errors setting up the columns. -->';
        foreach($errors as $key=>$value){
            // append error messages in comments
            $col_content .= '<!-- ' . $key .'. ' . $value . '-->';
        }
    }
    return $col_content;

}

// use shortcodes within wpcf7
add_filter( 'wpcf7_form_elements', 'mycustom_wpcf7_form_elements' );

function mycustom_wpcf7_form_elements( $form ) {
	$form = do_shortcode( $form );
	return $form;
}

// don't send contact form email
add_filter('wpcf7_skip_mail', 'wf_wpcf7_skip_mail');
function wf_wpcf7_skip_mail() {
		return true;
}

function get_attachment_id( $url ) {
	$attachment_id = 0;
	$dir = wp_upload_dir();
	if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
		$file = basename( $url );
		$query_args = array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'fields'      => 'ids',
			'meta_query'  => array(
				array(
					'value'   => $file,
					'compare' => 'LIKE',
					'key'     => '_wp_attachment_metadata',
				),
			)
		);
		$query = new WP_Query( $query_args );
		if ( $query->have_posts() ) {
			foreach ( $query->posts as $post_id ) {
				$meta = wp_get_attachment_metadata( $post_id );
				$original_file       = basename( $meta['file'] );
				$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
				if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
					$attachment_id = $post_id;
					break;
				}
			}
		}
	}
	return $attachment_id;
}


// DIY Popular Posts @ https://digwp.com/2016/03/diy-popular-posts/
// count visits in custom field
function wf_popular_posts($post_id) {
	$count_key = 'popular_posts';
	$count = get_post_meta($post_id, $count_key, true);
	if ($count == '') {
		$count = 0;
		delete_post_meta($post_id, $count_key);
		add_post_meta($post_id, $count_key, '0');
	} else {
		$count++;
		update_post_meta($post_id, $count_key, $count);
	}
}
function wf_track_posts($post_id) {
	if (!is_single()) return;
	if (empty($post_id)) {
		global $post;
		$post_id = $post->ID;
	}
	wf_popular_posts($post_id);
}
add_action('wp_head', 'wf_track_posts');





// replace footer
remove_action( 'genesis_footer','genesis_do_footer');
add_action('genesis_footer','wf_split_footer');
function wf_split_footer(){ ?>
    	<div class="footer-columns">
    		<div class="col1"><span class="copyright">&copy; <?php echo date('Y'); ?> Winning Fotos</span>
					<div class="fine-print"><?php _e('The Kodak trademark, logo and trade dress are used under license from Eastman Kodak Company.','kwf') ?></div></div>
    		<div class="col2"><?php
                if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Footer Right Column' ) ) {
                }
            ?></div>
        </div>

	<?php
}
