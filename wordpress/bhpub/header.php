<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

do_action( 'genesis_doctype' );
do_action( 'genesis_meta' );
do_action( 'genesis_title' );
/**
 * Header Template
 *
 *
 * @file           header.php
 */
 /*
?>


<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="application-name" content="B&H Publishing Group"/>
<title>
<?php
if ( defined( 'WPSEO_VERSION' ) ) {
    // WordPress SEO is activated
        wp_title();

} else {
	
    // WordPress SEO is not activated
	wp_title( '&#124;', true, 'right' );
}
?>
</title>
*/ ?>
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<script type="text/javascript" src="//use.typekit.net/ajy7myd.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php 
// add javascript
wp_enqueue_script('jquery');  //jQuery
wp_register_script('jcarousel', get_stylesheet_directory_uri().'/js/jquery.jcarousel.min.js', array('jquery'), '2.8',false); //infinite carousel jquery plugin
wp_enqueue_script('jcarousel');
//wp_register_script('awshowcase',get_stylesheet_directory_uri().'/js/jquery.aw-showcase.min.js', array('jquery'), '1.1', false); // home page feature content slider
//wp_enqueue_script('awshowcase');
wp_enqueue_style('responsive-style', get_stylesheet_directory_uri().'/responsive.css', false, '1.8.9');
//wp_enqueue_style('minimum-style', get_stylesheet_uri(), false, '1.8.9');
 //wp_enqueue_style('custom-fonts', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&#038;subset=latin,latin-ext, false');
//	wp_enqueue_style('custom-fonts', 'http://fonts.googleapis.com/css?family=Covered+By+Your+Grace|Open+Sans+Condensed:300,700|Open+Sans:400,400italic,700,700italic;subset=latin,latin-ext, false');
?>

<?php wp_head(); ?>
</head>

<?php
genesis_markup( array(
	'html5'   => '<body %s>',
	'xhtml'   => sprintf( '<body class="%s">', implode( ' ', get_body_class() ) ),
	'context' => 'body',
) );
do_action( 'genesis_before' );


genesis_markup( array(
	'html5'   => '<div %s>',
	'xhtml'   => '<div id="container">',
	'context' => 'container',
) );
do_action( 'genesis_before_header' );

//remove_action('genesis_header');
//do_action('genesis_header');
//new header below
?>        
    <div id="header">
    	<div class="top-bar"><div class="inner">
        <?php if (has_nav_menu('top-menu','minimum')) { ?>
	        <?php wp_nav_menu(array(
				    'container'       => '',
					'fallback_cb'	  =>  false,
					'menu_class'      => 'top-menu',
					'theme_location'  => 'top-menu')
					); 
				?>
        <?php } ?>
        </div></div>
     	<div class="inner">   
    <?php //responsive_in_header(); // header hook ?>
   
	
        <div id="logo">
            <?php echo '<a href="'.get_bloginfo('url').'" title="'. get_bloginfo('name').'"><img src="'.wp_get_attachment_url(313).'" alt="'. get_bloginfo('name') . '"/></a>'; ?>
        </div><!-- end of #logo -->
        
    
    <?php get_sidebar('top'); ?>
			    
				<?php wp_nav_menu(array(
				    'container'       => '',
					'theme_location'  => 'primary')
					); 
				?>
             <?php //sub-header menu should be near duplicate of header menu with additional categories
			 // moved to footer 
			 /*
            <?php if (has_nav_menu('sub-header-menu', 'responsive')) { ?>
	            <?php wp_nav_menu(array(
				    'container'       => '',
					'menu_class'      => 'sub-header-menu',
					'theme_location'  => 'sub-header-menu')
					); 
				?>
            <?php } ?>
			*/ ?>
 			</div>
    </div><!-- end of #header -->
    
    
    <?php
	do_action( 'genesis_after_header' );
	// responsive_header_end(); // after header hook ?>
    
	<?php //responsive_wrapper(); // before wrapper 
	
	genesis_markup( array(
	'html5'   => '<div %s>',
	'xhtml'   => '<div id="wrapper class="clearfix">',
	'context' => 'wrapper',
) );
?>
    <!-- <div id="wrapper" class="clearfix"> -->
    <?php //responsive_in_wrapper(); // wrapper hook ?>