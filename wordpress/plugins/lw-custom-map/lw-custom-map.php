<?php
/*
Plugin Name: Access International Map
Description: Drag and drop trip locations on a map to display instead of Locations list on larger screens.
Version: 1.2
Author: Bobby Reinsch
Author URI: http://www.bhpublishinggroup.com


*/


//Admin

add_action('admin_init', 'maps_register_fields');
function maps_register_fields() {   
	register_setting('maps_admin_options', 'map_options', 'validate_settings');
	
}

function validate_settings($plugin_options) {  
	return $plugin_options;
}

// load css and scripts
function lw_maps_admin_scripts($hook){
	 if('toplevel_page_maps-settings'!=$hook){return;} //only load for this plugin admin page
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-droppable');
		
		// and the custom js/css
		wp_enqueue_script(	'map_admin' , plugin_dir_url( __FILE__ ) .  'map-admin.js' ,	array('jquery','jquery-ui-draggable') );
		wp_enqueue_style(	'map_plugin_css' , plugin_dir_url( __FILE__ ) . 'map-plugin.css' );
		
		wp_enqueue_media();
				
}
add_action( 'admin_enqueue_scripts', 'lw_maps_admin_scripts' );

//create admin page
add_action('admin_menu', 'lw_maps_plugin_settings');

function lw_maps_plugin_settings() {
    add_menu_page('Locations Map Settings', 'Map', 'edit_pages', 'maps-settings', 'lw_maps_display_settings','dashicons-location-alt','26.8');
}

function lw_maps_display_settings(){
//HTML for settings page
// get map background image from options
echo '<form id="map_admin" method="post" action="options.php">';
settings_fields('maps_admin_options');
$map_options =get_option('map_options');
echo '<div class="wrap"><div class="map-container"><div class="map" style="background-image:url('. $map_options['map-bg-img'] .');">';
// List draggable locations
	$q = array(
						'post_status'=>'publish,future,draft',
						'post_type'=>'location',
						'posts_per_page'=>-1		
	);
	
	  $trips = new WP_Query($q);
	   if ($trips->have_posts()) :
	  		$list = '';
      		while ($trips->have_posts()) : $trips->the_post();
				global $post;
				//initialize
				$loc_side = '';
				$loc_position='bottom:0;left:0;'; //default location, off screen
				$loc_slug=$post->post_name;
				$loc_url=get_permalink();
				$loc_title=get_the_title();
				//get top and left position from options
						$top_option = 'map-' . $loc_slug . '-top';
						$left_option = 'map-' . $loc_slug . '-left';
						$loc_position = 'top:'. $map_options[$top_option] .';left:'. $map_options[$left_option] .';';
				//get text side from options
						if(array_key_exists('map-' . $loc_slug . '-side' , $map_options)){
							$loc_side = $map_options['map-' . $loc_slug . '-side'] ? 'left':'';
						}
				
				$list .='<a id="'. $loc_slug .'" class="trip '. $loc_slug .' '.  $loc_side .' ' . $post->post_status . '"  href="'. $loc_url .'" draggable="true" style="'. $loc_position .'" target="_blank"><label>'. $loc_title .'</label><span></span></a>';
			endwhile;
		endif;
		
echo $list; 
echo '</div></div><p class="submit map-instructions"><strong>Map Administration: </strong>Drag the labels to their correct location; don&apos;t forget to <input name="Submit" type="submit" class="button-primary" value="Save Changes" /></p>';

// loop through list again for options
rewind_posts();
echo '<div class="uploader">
	<label>Map Image</label>
    <input type="text" name="map_options[map-bg-img]" id="image_url" class="regular-text" value="'.  $map_options['map-bg-img'] .'"/>
    <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image" />


</div>';//map image field
//  choose breakpoint to hide map and show list instead/only
/*$html .='<p>Choose the largest screen on which to view the list instead of the map.</p>'; 
$html .= '<select name="map_options[map-breakpoint]">
						<option value="0">Always show the map</option>
						<option value="450">Hide the map on phones</option>
						<option value="640">Hide the map on tablets</option>
						<option value="20000">Always hide the map</option>
				</select>';
				*/
echo '<h3>Additional Location Settings</h3>';
echo '<ul class="locations-list">';
while ($trips->have_posts()) : $trips->the_post();
	global $post;
	
	// list/save top and left % in fields
	echo '<li><span class="loc">'. get_the_title() .'</span> 
	<label>Top: </label><input class="field-'. $post->post_name .'-top" name="map_options[map-' . $post->post_name  .'-top]" value="' . $map_options['map-' . $post->post_name  . '-top'] .'" />
	<label>Left: </label><input class="field-'. $post->post_name .'-left" name="map_options[map-' . $post->post_name  .'-left]" value="' . $map_options['map-' . $post->post_name  . '-left'] .'" />
	<label>Label on Left: </label> <input name="map_options[map-' . $post->post_name  .'-side]" type="checkbox" value="1" ';
	if(array_key_exists('map-' . $post->post_name . '-side' , $map_options)){
							checked('1',$map_options['map-' . $post->post_name . '-side']);
						}
	echo  ' />';
	echo '<span class="status '. $post->post_status .'">'. lw_maps_pretty_post_status($post->post_status) .'</span> <a href="/wp-admin/post.php?post='. get_the_ID() .'&action=edit">[ edit ]</a>';
	echo '</li>';

endwhile;
echo '</ul>';
// save/submit button
//echo '<p class="submit"><input name="Submit" type="submit" class="button-primary" value="Save Changes" /></p>'; //moved to top
echo '</div>';//end wrap div	

echo '</form>';
wp_reset_postdata();

}

function lw_maps_pretty_post_status($status){
	if($status=='publish'){
		$pretty = 'Published';
	}elseif($status=='future'){
		$pretty = 'Scheduled';
	}else {
		$pretty = $status;
	}
	return $pretty;
}



//Display Map

// load CSS and scripts - enqueue jQuery
function lw_maps_display_scripts(){
	wp_enqueue_script('jquery');
		// and the custom js
	wp_enqueue_script(	'maps_display',
										plugins_url( '/map.js', __FILE__ ),
										array('jquery')
										);
	wp_enqueue_style(	'map_plugin_css',
										plugins_url( '/map-plugin.css', __FILE__ )
										);
}
add_action('wp_enqueue_scripts','lw_maps_display_scripts');
// CSS animations - pulse onload, hover?


// shortcode? or function?
add_action('genesis_after_header','lw_maps_display_map');
function lw_maps_display_map(){
	if(is_front_page()):
	$map_options =get_option('map_options');
	$map_bg = ($map_options['map-bg-img']) ? 'background-image:url('. $map_options['map-bg-img'] .');' : ''; //default map image in css
	$html = '<div id="custom-map" class="map-container"><div class="map" style="'. $map_bg. '">';
	// List draggable locations
	$m = array(
						'post_status'=>'publish',
						'post_type'=>'location',
						'posts_per_page'=>-1		
	);
	
	  $loc = new WP_Query($m);
	   if ($loc->have_posts()) :
	  		$maplist = '';
      		while ($loc->have_posts()) : $loc->the_post();
				global $post;
				//initialize
				$loc_side = '';
				$loc_position='bottom:0;left:0;'; //default location, off screen
				$loc_slug=$post->post_name;
				$loc_url=get_permalink();
				$loc_title=get_the_title();
				//get top and left position from options
						$top_option = 'map-' . $loc_slug . '-top';
						$left_option = 'map-' . $loc_slug . '-left';
						$loc_position = 'top:'. $map_options[$top_option] .';left:'. $map_options[$left_option]  .';';
				//get text side from options
						if(array_key_exists('map-' . $loc_slug . '-side' , $map_options)){
							$loc_side = $map_options['map-' . $loc_slug . '-side'] ? 'left':'';
						}
				
				if (function_exists('CFS')){
					//show each date
					$maplist .='<a class="trip '. $loc_slug .' '.  $loc_side .'"  href="'. $loc_url .'" style="'. $loc_position .'"><label>'. $loc_title .'</label><span></span>';
						// hover to display dates?				
										
					$maplist .='</a>';
				}else{				
					// otherwise just show the location
					$maplist .='<a class="trip '. $loc_slug .' '.  $loc_side .'"  href="'. $loc_url .'" style="'. $loc_position .'"><label>'. $loc_title .'</label><span></span></a>';
				}
				
			
			endwhile;
		endif;
		
$html .= $maplist; 
$html .= '</div></div><!-- end map-container -->';
echo $html;

// INTERNATIONAL TRIPS text logo
//echo '<div class="accessint-large-blue-text"><div class="wrap"><a href="/trips/"><img src="/wp-content/uploads/2015/05/callout-international-students.png"></a></div></div>';
endif;
}













// uninstall function - delete data from options/database - uninstall.php








/*  // DISPLAY LOCATIONS WP_QUERY

	// query array for WP_Query
	$q = array(
						'post_status'=>'publish',
						'post_type'=>'post',
						'cat'=>3, //Trips category
						'tag'=>$tag->slug,
						'posts_per_page'=>-1		
	);
	
		if($show_title){
			$return_string .= '<h3 class="trip-location-name">'.$tag->name.'</h3>';
		}
   		$return_string .= '<ul>';
  $trips = new WP_Query($q);
   if ($trips->have_posts()) :
      while ($trips->have_posts()) : $trips->the_post();
         $return_string .= '<li class="trip-list-item"><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
      endwhile;
   endif;
   $return_string .= '</ul>';

   wp_reset_query();
   return $return_string;



*/



?>