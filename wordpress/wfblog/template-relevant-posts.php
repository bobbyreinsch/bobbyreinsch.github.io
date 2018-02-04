<?php


/**
 * Template Name: Relevant Posts Feed page
 * Description: Outputs posts by category in JSON/RSS/ATOM
 * options c(ategory),t(ag),count,format
 */

$output = array();


// defaults
$default_tags = false; // we can add tags here to the default
$categories = explode(',',get_query_var('c', 'uncategorized'));
$count = get_query_var('count',false);
$format = get_query_var('format','json');
$tags = explode(',',get_query_var('t', $default_tags));
$callback = get_query_var('wfcb',false);

//default JSON
$content_type = 'Content-type: application/json';
// switch content type/format as necessary
if($format=='rss'){
  $content_type = 'Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset');
}elseif($format=='atom'){
  $content_type = 'Content-type: application/atom+xml';
}elseif($format=='xml'){
  $content_type = 'Content-type: text/xml';
}elseif($format=='html'){
  $content_type = 'Content-type: text/html';
}

//header( $content_type );

// default behavior - show 2 posts of first category, 1 post of second
// if count, same count for all categories
$default_count = 2;
foreach($categories as $category){
  $arr = array(
                'post_type'=>'post',
                //default: sort by most recent
                'orderby'=>'rand' //change to random order
              );
  if($category && $category!='uncategorized'){
    $taxquery[0][]= array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => $category
                      );

    if($tags){
      $taxquery[0]['relation'] = 'AND';
    }
  }

  if($tags){
    $arr['post_tags'] = $tags;
    
    /* for showcase category: removed 6/15/17 BR 
    $taxquery[0][] = array(
                        'relation' => 'OR',
                        array(  

                                'taxonomy' => 'showcase-category',
                                'field'    => 'slug',
                                'terms'    => $tags
                          ),
                        array(
                                'taxonomy' => 'post_tag',
                                'field'    => 'slug',
                                'terms'    => $tags
                          )
                    );
    $arr['tax_query'] = $taxquery;
    */

  }else{
    $arr['category_name']=$category;
  }

  if($count){
    $arr['posts_per_page'] = $count;
  }
  
 $r = new WP_Query($arr);
  $n = 0;
  if(!$r->have_posts()){
    // if no tags match, just use category
    //unset($arr['tax_query']);
    unset($arr['post_tags']);
    $arr['category_name']=$category;
    $r = new WP_Query($arr);
  }

  if($r->have_posts()):
    if($count){
      while($r->have_posts()):$r->the_post();
        // add post title and permalink to new post array
          if($format=='json'){
            $post_arr[] = array('title'=>get_the_title(),'url'=>get_permalink());
          }else{
            $post_arr[] = array('title'=>get_the_title(),'url'=>get_permalink());
          }
      endwhile;
      $output[] = array('category'=>$category,'posts'=>$post_arr);
      $post_arr = array();
    }else{
      while($r->have_posts()):$r->the_post();
        // add post title and permalink to new post array
        if($n < $default_count){
          if($format=='json'){
            $post_arr[] = array('title'=>get_the_title(),'url'=>get_permalink());
          }else{
            $post_arr[] = array('title'=>get_the_title(),'url'=>get_permalink());
          }
          $n++;
        }
      endwhile;
      $output[] = array('name'=>$category,'posts'=>$post_arr);
      $post_arr = array();
      $default_count--; //reduce for second category, don't show a third.
    }
    endif;

}


if(empty($output)){
  $output[] = array('error'=>'No posts found.');
}



// format output

//rss loop
//http://www.wpbeginner.com/wp-tutorials/how-to-create-custom-rss-feeds-in-wordpress/
//atom loop
//xml loop
//html loop

//jsonp output
if($callback && $format=='jsonp'){
  echo $callback .'('. json_encode($output) .')';
}else{
  //default json output
  echo json_encode($output);
}

?>