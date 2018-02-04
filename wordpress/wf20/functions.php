<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );
@ini_set( 'upload_max_size' , '24M' );
@ini_set( 'post_max_size', '24M');


//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Winning Fotos v2.0', 'wf2' ) );
define( 'CHILD_THEME_URL', 'http://www.lineage.solutions' );
define( 'CHILD_THEME_VERSION', '1.0.7' );
define( 'CHILD_THEME_DIR', get_stylesheet_directory());



//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );


add_filter( 'genesis_pre_load_favicon', 'custom_favicon' );
function custom_favicon( $favicon_url ) {
	return ''. get_stylesheet_directory_uri() .'/img/favicon.png';
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
    return 'Winning Fotos';
}
add_filter('login_headertitle', 'wf_tooltip_logo');


add_filter('widget_text', 'do_shortcode');
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether


add_filter( 'excerpt_length', 'wf_excerpt_length', 999 );
function wf_excerpt_length( $length ) {
    return 25;
}

add_image_size('phone-screen', 220, 9999, false);



//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'best_enqueue_scripts_styles' );
function best_enqueue_scripts_styles() {
	wp_enqueue_script('jquery');
	// wp_enqueue_style( 'fontello', get_stylesheet_directory_uri() . '/fontello/css/wf-blog-icons.css' , 1.0);
    wp_enqueue_style( 'dashicons' );

    
    if(is_page('how-it-works')):
        // only include infographic script on its page
        wp_enqueue_script('GSAP', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.19.1/TweenMax.min.js' ,'jquery');
        wp_enqueue_script('scrollmagic', '//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/ScrollMagic.min.js',array('jquery','GSAP'));
        wp_enqueue_script('smgsap', '//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/animation.gsap.js', 'scrollmagic');
        wp_enqueue_script('smjq', '//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/jquery.ScrollMagic.js', 'scrollmagic');
        //wp_enqueue_script('debug', '//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/debug.addIndicators.min.js', 'scrollmagic');
        wp_enqueue_script('info', get_stylesheet_directory_uri().'/js/infographic.js', 'scrollmagic');
    endif;

    // scrolling sections for home page
    if(is_front_page()):
        //wp_enqueue_script('smjq', '//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/jquery.ScrollMagic.js', 'scrollmagic');
        wp_enqueue_script('fullpage', get_stylesheet_directory_uri().'/js/jquery.fullPage.min.js','jquery');
        wp_enqueue_style( 'fullpage_css', get_stylesheet_directory_uri() . '/jquery.fullPage.css' , 1.0);
    endif;

	}

add_action('wp_head','wf_typekit_script'); //Rucksack Font ... add AVENIR Book and AVENIR black to typekit
function wf_typekit_script(){
    echo '<script src="https://use.typekit.net/obc0xkd.js"></script>';   //new kit
    echo '<script>try{Typekit.load({ async: true });}catch(e){}</script>';
}



/* ______________________________________________________________ */






//custom sidebars

//unregister_sidebar( 'header-right' );
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );
unregister_sidebar( 'home-middle' );

add_action('genesis_before','wf_remove_sidebar');
function wf_remove_sidebar(){
    if(is_singular()){
        remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
    }
}



genesis_register_sidebar(array(
    'name'=>'Footer Single Column',
    'id'=>'footer_sidebar',
    'description' => 'This is the footer content.',
    'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));

genesis_register_sidebar(array(
    'name'=>'Page Nav',
    'id'=>'page_nav_sidebar',
    'description' => 'This is the page nav above the page content.',
    'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));

genesis_register_sidebar(array(
    'name'=>'Page Middle',
    'id'=>'page_middle_sidebar',
    'description' => 'This is the Showcase section.',
    'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));

genesis_register_sidebar(array(
    'name'=>'Page Lower',
    'id'=>'page_lower_sidebar',
    'description' => 'This is the text just above the footer.',
    'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));




remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );
// remove_action( 'genesis_header','genesis_do_header');
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );


add_action('genesis_site_title','wf_site_title');
function wf_site_title(){
	echo '<h1 class="site-title"><a href="/"></a></h1>';
}


// saved in "header scripts" in admin
function wf_gtm_tag_head(){
    echo "<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-M8WSGSF');</script>
<!-- End Google Tag Manager -->";
}

function wf_gtm_tag_noscript(){
    echo '<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M8WSGSF"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->';
}

add_action('genesis_after_footer','wf_gtm_tag_noscript');


// home page content sections

//add_action('genesis_after_content','wf_showcase_section');
function wf_showcase_section() {
    if(is_page() && !is_page('news')){

      if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Page Middle' ) ) {
        }

    }
}

//add_action( 'genesis_before_footer', 'wf_home_addl_content' );
function wf_home_addl_content() {
  if(is_page() && !is_page('news')){

      if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Page Lower' ) ) {
        }

    }
}

// nav bar for pages (but hide on splash page)

//add_action('genesis_header','wf_nav_bar');
function wf_nav_bar(){
        if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Page Nav' ) ) {
        }
}




// home page header
add_action('genesis_before','wf_home_page_header_mod');
function wf_home_page_header_mod(){
    if(is_front_page()){
        //remove_action('genesis_header','genesis_do_header');
        add_action('genesis_before_loop','wf_home_page_header');
    }
}


function wf_home_page_header(){
    echo '<div id="fp_header" class="section page-section fp-auto-height"><div class="wrap">';    
    genesis_do_header();

    echo '<div class="mobile-nav-section">';
            if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Page Nav' ) ) {
        }

    echo '</div>';
    echo '</div></div>';

}








// mobile nav

//add_action('genesis_after_header', 'wf_mobile_nav');
add_action('genesis_header','wf_mobile_nav');
function wf_mobile_nav(){
        //if(!is_front_page()):
        echo '<div class="mobile-nav-section">';
            if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Page Nav' ) ) {
        }
        echo '</div>';
        //endif;
}

add_action('genesis_after_footer','wf_mobile_nav_script');
function wf_mobile_nav_script(){
    echo '<script>';
    echo 'jQuery(document).ready(function($){

          // mobile navigation

          $("a.menu-btn").on("click",function(){
            $(".mobile-nav-section").slideToggle().toggleClass("open");
            $(this).toggleClass("active");
            return false;
          });

            $(".features-nav a").on("click",function(e){
                e.preventDefault();
                $tabs = $(this).parents(".features-tabs");
                $tabs.find(".active").removeClass("active");
                $tabs.find(".feature").eq($(this).parent().index()).addClass("active");
                $(this).addClass("active");
            });

            $(".faq h4").on("click",function(e){
                $(this).parent().toggleClass("open");
            });

            function isMSIE() {
                var ua = window.navigator.userAgent;

                var msie = ua.indexOf("MSIE ");
                if (msie > 0) {
                    // IE 10 or older => return version number
                    return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)), 10);
                }

                var trident = ua.indexOf("Trident/");
                if (trident > 0) {
                    // IE 11 => return version number
                    var rv = ua.indexOf("rv:");
                    return parseInt(ua.substring(rv + 3, ua.indexOf(".", rv)), 10);
                }

                var edge = ua.indexOf("Edge/");
                if (edge > 0) {
                   // Edge (IE 12+) => return version number
                   return parseInt(ua.substring(edge + 5, ua.indexOf(".", edge)), 10);
                }

                // other browser
                return false;
            }

            if(isMSIE()){ 
                $(".headshot").each(function(){
                    img = $(this).find("img").attr("src");
                    if(img){
                        $(this).addClass("ie-thumb").css("background-image","url("+ img + ")").find("img").hide(); 
                    }
                    img = false;
                });
            }
            ';

        if(is_front_page()){
            // fullpage.js on home page only
            echo '
            if(isMSIE()){
                $("body").addClass("msbrowserfail");
            }else{
                $("#content").fullpage({
                    responsiveWidth: 840,
                    navigation:true,
                    navigationPosition:"right"
                });

                $(".more-btn").on("click",function(e){
                    e.preventDefault();
                    //$.fn.fullpage.moveSectionDown();
                    $.fn.fullpage.moveTo(3);
                });
            }

                    $(".for-every-business h4>a").on("click", function(e){
                        //switch image on click
                        e.preventDefault();
                        $(".for-every-business .open").removeClass("open");
                        imgSrc = $(this).parents("li").find(".thumb>img").attr("src");
                        if(imgSrc){
                            $(".for-every-business .section-img").attr("src",imgSrc).attr("srcset","");
                        }
                        $(this).parents("li").addClass("open");
                    });

            ';
        }


        echo '});'; //close jquery tag
    echo '</script>';
}



// page heroes
remove_action('genesis_post_title','genesis_do_post_title');
add_action('genesis_post_title','wf_page_hero');

function wf_page_hero(){
    if(is_page()):
        if(genesis_get_image()):
            $hdrImg = genesis_get_image(array(
                                                'format'=>'url'
                ));
            $herotxt = get_post_meta(get_the_ID(), 'wpcf-page-header-content', true);
            // custom options here
            
            echo '<div class="hero-image" style="background-image:url(' . $hdrImg . ');"><div class="wrap">';
            if(!is_front_page()){
                echo '<h1 class="entry-title">' . get_the_title() . '</h1>';
            }else{
                $herotxt = get_the_content();
            }
            if($herotxt){
                echo '<div class="hero-text">' . apply_filters('the_content', $herotxt, true) . '</div>';
            }
            echo '</div></div>';

        else:
            genesis_do_post_title();
        endif;
    elseif(is_singular('team-member')):
        // get large image from custom field
        $hdrImg = get_post_meta(get_the_ID(), 'wpcf-team-member-header-image', true);
        $hdrJobTitle = get_post_meta(get_the_ID(),'wpcf-job-title',true);
        if($hdrImg):
            echo '<div class="hero-image" style="background-image:url(' . $hdrImg . ');"><div class="wrap">'; 
            echo '<h1 class="entry-title">' . get_the_title() . '</h1>';
            if($hdrJobTitle){
                echo '<div class="hero-text">'. $hdrJobTitle .'</div>';
            }
            echo '</div></div>';
        else:
            genesis_do_post_title();
            if($hdrJobTitle){
                echo '<div class="hero-text">'. $hdrJobTitle .'</div>';
            }
        endif;
    else:
        genesis_do_post_title();
    endif;
}

// additional page content
add_action('genesis_after_content','wf_page_addl_content');
function wf_page_addl_content(){
    if(is_page()):
        // custom options

        $addl = get_post_meta(get_the_ID(), 'wpcf-additional-content', true);
        if($addl):
            echo '<div class="addl-content"><div class="wrap">';
            echo apply_filters('the_content', $addl, true);
            echo '</div></div>';
        endif;
    endif;
}

// home page
add_action('genesis_after_loop','wf_home_page_content');
function wf_home_page_content(){
    if(is_front_page()):

        // get child pages
        $cq = new WP_Query();
        $cp = $cq->query(array('post_type' => 'page', 'posts_per_page' => -1, 'orderby'=>'menu_order', 'order'=>'ASC'));
        $c = get_page_children(get_the_ID(),$cp);
        
        // display content
        if($c):
            foreach($c as $child){
                $cpage = get_page($child->ID);
                    $p_slug = $cpage->post_name;
                    $p_title = $cpage->post_title;
                    $p_content = $cpage->post_content;
                    $p_image = genesis_get_image(array('post_id'=>$child->ID,'format'=>'html','size'=>'phone-screen','attr'=> array('class'=>'section-img','alt'=>$p_title)));
                    echo '<div class="section page-section '. $p_slug .'"><div class="wrap">';
                    if($p_image){
                        echo $p_image; //'<img src="' . $p_image . '" class="section-img"/>';
                    }
                    echo '<h2 class="section-title">' . $p_title . '</h2>';
                    echo '<div class="section-content">' . apply_filters('the_content',$p_content,true) . '</div>';
                    echo '</div></div>';

            }
        endif;
        echo '<div class="section page-section fp-auto-height" id="footer">';
        echo wf_footer();
        echo '</div>';


    endif;
}

// remove home page content section
add_action('genesis_before','wf_move_home_content');
function wf_move_home_content(){
    if(is_front_page()):
        remove_action('genesis_post_content','genesis_do_post_content');
        remove_action('genesis_footer','genesis_do_footer');
        // home content displayed in page hero
    endif;
}




remove_action( 'genesis_before_post_content', 'genesis_post_info' );
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

add_action('genesis_before','wf_team_member');

function wf_team_member(){
    if(is_singular('team-member')){
        remove_action('genesis_post_content','genesis_do_post_content');
        add_action('genesis_post_content','wf_team_member_page');

    }
}

function wf_team_member_page(){
        // add photo next to bio
        $bioImg = genesis_get_image(array(
                                                //'format'=>'url',
                                                'attr'=>array( 
                                                        'alt'=>get_the_title()
                                                    )
                ));
        if($bioImg){
            echo '<div class="bio-photo">'. $bioImg . '</div>';

        }
        echo '<div class="bio-text">';
        genesis_do_post_content();
        echo '</div>';
}



add_action('genesis_before_footer','wf_member_page_news');
function wf_member_page_news(){
    if(is_singular('team-member')){
        // list news items tagged with team member name
        $member_name = get_the_title();
        $tagObj = get_term_by('name', $member_name, 'post_tag');
        if(!empty($tagObj)){
            $tagID = $tagObj->term_id;
            $press = array(
                            'post_type'=>'post',
                            'posts_per_page'=>'10',
                            'category_name' => 'press',
                            'tag__in' => $tagID
                        );
            $n = new WP_Query($press);
            if($n->have_posts()):
                echo '<div class="press"><div class="wrap">';
                echo '<h2 class="section-title">Press</h2>';
                echo '<ul class="press-links">';

                while($n->have_posts()):$n->the_post();
                    //display a post
                    echo '<li>';
                    //thumbnail + link (permalink if no article link)
                    $pressthumb = genesis_get_image();
                    $presslink = get_post_meta(get_the_ID(), 'wpcf-press-link', true);
                    if(!$presslink){$presslink = get_permalink();}
                    echo '<a href="'. $presslink .'" class="press-thumb">';
                    if($pressthumb){
                        echo $pressthumb;
                    }
                    echo '</a>';
                    //title + link
                    echo '<div class="press-entry">';
                    echo '<h4><a href="'. $presslink .'">' . get_the_title() . '</a></h4>';
                    //excerpt
                    echo '<p class="press-text">' . get_the_excerpt() . '</p>';
                    //date
                    //$pressdate = get_post_meta(get_the_ID(), 'wpcf-press-date', true);
                    $pressdate = types_render_field('press-date',array('style'=>'text'));
                    if(!$pressdate){$pressdate = get_the_date();}
                    echo '<span class="press-date">' . $pressdate . '</span>';
                    echo '<span class="press-link">' . $presslink . '</span>';
                    echo '</div>';
                    echo '</li>';
                endwhile;
                echo '</ul></div></div>';
            endif;
        } 
    }
}

// team member archive redirect to about page

add_action('template_redirect','wf_safe_redir');
function wf_safe_redir(){
    if(is_post_type_archive('team-member')):
        wp_redirect(home_url() . '/about',301);
        exit();
    endif;
}


// news page 
/* 
add_action('genesis_after_post_content','wf_news_page');
function wf_news_page(){
    
    if(is_page('news')){

        $news = array(
                        'post_type'=>'post',
                        'posts_per_page'=>'3'
                    );

        $old_news = array(
                        'post_type'=>'post',
                        'posts_per_page'=>'22',
                        'offset'=>3
                    );
        $n = new WP_Query($news);
        $o = new WP_Query($old_news);

        if($n->have_posts()):
            echo '<ul class="featured-news">';
            while($n->have_posts()):$n->the_post();
                // 3 featured posts, text over hz image
                echo '<li><a class="news-feature" href="'. get_permalink() .'"';
                if(genesis_get_image()){
                    echo ' style="background-image:url('. genesis_get_image(array('format'=>'src','size'=>'large')) .')"';
                }
                echo '><div><h4 class="news-title">' . get_the_title() . '</h4>';
                echo '<p class="news-excerpt">' . get_the_excerpt()  .'</p></div>';
                echo '</a></li>'; 
            endwhile;
            echo '</ul>';
        endif;
        if($o->have_posts()):
             // more posts text in 2 columns
            echo '<h2>Previous News</h2>';
            echo '<ul class="news-archive">';

            while($o->have_posts()):$o->the_post();
                echo '<li><a href="'. get_permalink() .'"><span class="news-title">'. get_the_title() .'</span><span class="news-date">'. get_the_date() .'</span></a></li>';
            endwhile;
            echo '</ul>';

        endif;

    }
}

*/


//shortcodes

// faq
add_shortcode('faq','wf_faq_page');
function wf_faq_page($atts,$content){
    $faq_page = get_page_by_path('how-it-works/faq');
    $faq_id = $faq_page->ID;
    $faq = $faq_page->post_content;
    $faq_html = '<div class="faq"><h2 class="section-title">' . get_the_title($faq_id) . '</h2>' . $faq . '</div>';
    return $faq_html;
}

// team members
add_shortcode('team','wf_list_team');
function wf_list_team($atts,$content){
    $list = '';
    // get all the features
    $team = array(
                    'post_type'=>'team-member',
                    'posts_per_page'=>-1,
                    'orderby'=>'menu_order',
                    'order'=>'ASC'
                    );

    $t = new WP_Query($team);
    if($t->have_posts()):
        $list .= '<ul class="team-members">';
        while($t->have_posts()):$t->the_post();
            $fb = get_post_meta(get_the_ID(),'wpcf-facebook',true);
            $tw = get_post_meta(get_the_ID(),'wpcf-twitter',true);
            $web = get_post_meta(get_the_ID(),'wpcf-website',true);
            $title = get_post_meta(get_the_ID(),'wpcf-job-title',true);
            $thumb = genesis_get_image(array('format'=>'url'));
            $list .= '<li>';
            //if($thumb):
                $list .= '<div class="headshot"><img src="'. $thumb .' " alt="'. get_the_title() .'" /></div>';
            //endif;
            $list .= '<div class="bio"><h2>' .  get_the_title() . '<span class="social">';
            if($fb):
                $list .= '<a class="fb-btn" href="'. $fb .'"></a>';
            endif;
            if($tw):
                $list .= '<a class="tw-btn" href="'. $tw .'"></a>'; 
            endif;
            if($web):
                $list .= '<a class="web-btn" href="'. $web .'"></a>';
            endif;
            $list .= '</span></h2>';
            if($title):
                $list .= '<h5>'. $title .'</h5>';
            endif;
            $list .= get_the_content();
            $list .= '</li>';
        endwhile;  
        $list .= '</ul>';
    endif;

    return $list;
    
}


// features
add_shortcode('features','wf_features_shortcode');
function wf_features_shortcode($atts,$content){
    extract(shortcode_atts(array(
            "tabs" => 0,
        ), $atts));
    $features = '';
    // get all the features
    $feat = array(
                    'post_type'=>'feature',
                    'posts_per_page'=>-1,
                    'orderby'=>'menu_order',
                    'order'=>'ASC'
                    );

    $f = new WP_Query($feat);
    $curr_page = get_the_title();
    if($f->have_posts()):

 
        if($curr_page == 'Home Page'):

           $features = '<div class="features-tabs"><ul class="features-nav">';
        // tabs nav
            $active = 'active';
            $n = 1;
            while($f->have_posts()):$f->the_post();
                $tab_text = get_post_meta(get_the_ID(),'wpcf-feature-tab-text',true);
                if(!$tab_text){$tab_text = get_the_title();}
                $features .= '<li><a href="#feature-'. $n .'" class="'. $active .'">' . $tab_text . '</a></li>';
                $active = '';
                $n++;
            endwhile;
            $features .= '</ul>';
            

            rewind_posts();
            
        // show featured image and excerpt
            $active = 'active';
            while($f->have_posts()):$f->the_post();
                $tab_desc = get_post_meta(get_the_ID(),'wpcf-feature-tab-content',true);
                $tab_img_url = get_post_meta(get_the_ID(), 'wpcf-feature-tab-image', true);
                if(!$tab_desc){$tab_desc = get_the_excerpt();}
                $features .= '<div class="feature ' . $active .'">';
                if($tab_img_url){
                    $img_id = get_attachment_id(get_site_url() . $tab_img_url);
                    $tab_img = wp_get_attachment_image($img_id,'phone-screen','', array('alt'=>get_the_title()));
                    //$features .= '<div class="feature-image"><img src="'. $tab_img  .'" /></div>';
                    $features .= '<div class="feature-image">'. $tab_img . '</div>';
                }
                $features .= '<div class="feature-info"><h3 class="feature-title">' . get_the_title() . '</h3>';
                $features .= '<div class="feature-desc">' . $tab_desc . '</div></div>';
                $features .= '</div>';

            $active = '';
            endwhile;
        
            $features .= '</div>';
        else:
   
        // basic content
        // show all feature content
        $features = '<div class="features">';
            while($f->have_posts()):$f->the_post();
                $f_sub = get_post_meta(get_the_ID(),'wpcf-feature-subheading',true);
                $f_img = genesis_get_image(array('format'=>'html', 'size'=>'medium_large','attr'=>array('alt'=>get_the_title())));
                $features .= '<div class="feature">';
                if($f_img){  
                    //$features .= '<div class="feature-image"><img src="'.  $f_img .'" /></div>';
                    $features .= '<div class="feature-image">'.  $f_img .'</div>';
                }
                $features .= '<div class="feature-info"><h2 class="feature-title">' . get_the_title() . '</h2>';
                if($f_sub){
                    $features .= '<h3 class="feature-subhead">' . $f_sub . '</h3>';
                }
                $features .= '<div class="feature-desc">' . get_the_content() . '</div></div>';
                $features .= '</div>';
            endwhile;
        $features .= '</div>';

        endif;    
  
    endif;
    wp_reset_postdata();
    return $features;

}


// infographic
add_shortcode('info','wf_infographic_shortcode');
function wf_infographic_shortcode($atts,$content){
    //extract(shortcode_atts(array(
    //         
     //   ), $atts));

    // get infographic page
    /*
    $info_page = get_page_by_path( 'infographic' );
    $iid = $info_page->ID;

    // get custom text fields from infographic page
    $s1 = get_post_meta( $iid, 'wpcf-info-step-1', true );
    $s2 = get_post_meta( $iid, 'wpcf-info-step-2', true );
    $s3 = get_post_meta( $iid, 'wpcf-info-step-3', true );
    $s4 = get_post_meta( $iid, 'wpcf-info-step-4', true );
    $s5 = get_post_meta( $iid, 'wpcf-info-step-5', true );
    */

    // get images directory
    $imgdir = get_stylesheet_directory_uri();


    // check current post for text fields
    global $post;

    if($post){
        if(get_post_meta($post->ID, 'wpcf-info-step-1')){
            $s1 = get_post_meta($post->ID, 'wpcf-info-step-1', true );
        }
        if(get_post_meta($post->ID, 'wpcf-info-step-2')){
            $s2 = get_post_meta($post->ID, 'wpcf-info-step-2', true );
        }
        if(get_post_meta($post->ID, 'wpcf-info-step-3')){
            $s3 = get_post_meta($post->ID, 'wpcf-info-step-3', true );
        }
        if(get_post_meta($post->ID, 'wpcf-info-step-4')){
            $s4 = get_post_meta($post->ID, 'wpcf-info-step-4', true );
        }
        if(get_post_meta($post->ID, 'wpcf-info-step-5')){
            $s5 = get_post_meta($post->ID, 'wpcf-info-step-5', true );
        }
    }

    $steps_list = '<ol>';
    $steps_list .= '<li>' . $s1 . '</li>';
    $steps_list .= '<li>' . $s2 . '</li>';
    $steps_list .= '<li>' . $s3 . '</li>';
    $steps_list .= '<li>' . $s4 . '</li>';
    $steps_list .= '<li>' . $s5 . '</li>';
    $steps_list .= '</ol>';
    // add to template

    $info = '<div id="showcase-image"><img src="'. $imgdir .'/img/howitworks.svg" /><div class="steps">'.  $steps_list .'</div></div><div id="showcase-infographic">

    <div class="row-1">
        <!-- camera, images, glow, step 1 -->
        <div class="glow glow-1"></div><div class="glow glow-2"></div>
        <img class="camera" src="'. $imgdir .'/img/camera.png" alt="Step 1"/>
        <div class="imageflow"></div>
        <div class="step step1"><h4>1</h4><span class="desc">'. $s1 .'</span></div>
    </div>

    <div class="row-2">
        <!-- tube, step 2 -->
        <img class="tube1" src="'. $imgdir .'/img/tubes1.png" alt="Step 2" />
        <div class="step step2"><h4>2</h4><span class="desc">'. $s2 .'</span></div>

    </div>

    <div class="row-3">
        <!-- step 3, computer, select-box -->
        <div class="glow glow-4"></div>
        <div class="computer"><div class="graph"><img src="'. $imgdir .'/img/graph.gif" alt="Ranking Images..."/></div><div class="lights"><img src="'. $imgdir .'/img/anim-screen.gif" alt="Analyzing Images..."/></div></div>
        <div class="selectbox"><img src="'. $imgdir .'/img/image-select-1.png" alt="Collect Winning Fotos"/></div>
        <img class="tube2" src="'. $imgdir .'/img/tubes2.png" alt="Step 3"/>
        <div class="step step3"><h4>3</h4><span class="desc">'. $s3 .'</span></div>
        <img class="tube3" src="'. $imgdir .'/img/tubes3.png" alt="Step 4"/>

    </div>

    <div class="row-4">
        <!-- pictures -->
        <div class="pictures">
            <div class="glow glow-5"></div>
            <img src="'. $imgdir .'/img/pictures-mail.png" alt="Send highest-ranked images" />
        </div>
        
    </div>

    <div class="row-5">
        <!-- envelope, imac, step 4, step 5 -->
        <div class="glow glow-6"></div>
        <img class="mail" src="'. $imgdir .'/img/mail.png" alt="Winning Fotos image collection"/>
        <img class="tube4" src="'. $imgdir .'/img/tubes4.png" alt="Step 5"/>
        <div class="imac"><img class="screen" src="'. $imgdir .'/img/imac-screen.png" alt="Use the best photos"/></div>
        <div class="step step4"><h4>4</h4><span class="desc">'. $s4 .'</span></div>
        <div class="step step5"><h4>5</h4><span class="desc">'. $s5 .'</span></div>
        
    </div>


</div>';


    $info .=  '<script>var v_img = ["'. $imgdir .'/img/image-select-1.png","'. $imgdir .'/img/image-select-2.png","'. $imgdir .'/img/image-select-3.png"];</script>';

    // $info .= '<!-- using step text from page '. $post->ID .' -->'; //troubleshooting
    return $info;
}


// button
add_shortcode('button','wf_button_shortcode');
function wf_button_shortcode($atts,$content){
	extract(shortcode_atts(array(
			"url" => null,
			"class"=> null
	), $atts));

	$btn = '<div class="btn '. $class  .'"><a href="' . $url . '">'. $content .'</a></div>';
	return $btn;
}


// columns shortcode for page content
add_shortcode('columns','bha_do_columns');
function bha_do_columns($atts,$content=null){
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
function wf_wpcf7_skip_mail()
{
return true;
}



// replace footer
remove_action( 'genesis_footer','genesis_do_footer');
add_action('genesis_footer','wf_footer');
function wf_footer(){ 
    ?>
    		<?php
                if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Footer Single Column' ) ) {
                }
            ?>
            <div class="copyright">&copy; Copyright WINNING fotos <?php echo date('Y'); ?></div>

	<?php
    
}

/**
 * Get an attachment ID given a URL.
 * 
 * @param string $url
 *
 * @return int Attachment ID on success, 0 on failure
 */
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


