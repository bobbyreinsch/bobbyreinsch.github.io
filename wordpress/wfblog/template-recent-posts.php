<?php


/**
 * Template Name: Recent Posts in JSON
 * Description: Outputs posts by date in JSON
 * 
 */

$output = array();

$count = get_query_var('count',false);
$size = get_query_var('size','medium');
$callback = get_query_var('wfcb',false);

//default JSON
$content_type = 'Content-type: application/json';


// default behavior - display 10 most recent posts including excerpt, featured image, and category list
  $arr = array(
                'post_type'=>'post',
                'posts_per_page'=>10
              );
  if($count){
    $arr['posts_per_page'] = $count;
  }
  
 $r = new WP_Query($arr);

  if($r->have_posts()):

      while($r->have_posts()):$r->the_post();
        // add post title and permalink to new post array

            $imgargs = array(
                              'format'=>'src',
                              'size'=>$size
                              );
            
            $catlist = array();
            $categories = get_the_category();
            foreach($categories as $c){
              $catlist[] = $c->name;
            }

            $post_arr[] = array(
                                  'id'=>get_the_ID(),
                                  'title'=>get_the_title(),
                                  'url'=>get_permalink(),
                                  'date'=>get_the_date(),
                                  'categories'=>implode(', ',$catlist),
                                  'excerpt'=>get_the_excerpt(),
                                  'thumbnail'=>get_site_url(null,genesis_get_image($imgargs))
                          
                                  );

      endwhile;
      $output[] = array('blog'=>'Kodak Winning Fotos Blog','posts'=>$post_arr);
      $post_arr = array();

endif;

if(empty($output)){
  $output[] = array('error'=>'No posts found.');
}


//jsonp output
if($callback){
  echo $callback .'('. json_encode($output) .')';
}else{
  //default json output
  echo json_encode($output);
}

?>