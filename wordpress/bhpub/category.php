<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Pages Template
 *
 *
 * @file           category.php
 */
?>
<?php

//query_posts($query_string . '&meta_key=wpcf-pubdate&orderby=meta_value_num');

?>
<?php
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action('genesis_before_loop','bh_products_category_mod_query');
add_action( 'genesis_loop', 'bh_products_category_loop' );


?>

<?php //get_header(); 

function bh_products_category_mod_query(){
	//order products by pubdate
	global $query_string;
	query_posts($query_string . '&meta_key=wpcf-pubdate&orderby=meta_value_num');
} //end bh products category mod query

function bh_products_category_loop(){
//post sorting


?>

<?php
//display content
?>	

<div class="inner">
<div class="sidebar grid col-220 product-categories">
<?php //get_sidebar(); 
//should only be child category, so display parent.

if (is_category()) {
$this_category = get_category( get_query_var( 'cat' ) );
}



//if child category
if($this_category->category_parent) {
$parent_cat = get_category($this_category->category_parent);
//check for third-level cat
	if($parent_cat->category_parent){
		$parent_parent = get_category($parent_cat->category_parent);
		echo '<h2><a href="'.  get_category_link($parent_parent->cat_ID) .'">'. $parent_parent->name .'</a></h2>';
		echo '<ul>';
		$categorylist = wp_list_categories('show_count=0&title_li=&use_desc_for_title=0&child_of='.$parent_cat->category_parent."&orderby=slug&hide_empty=0&exclude=1649"); 	
		echo '</ul>';
		
	if ($parent_parent->name == "Apps"){
	echo '<p>&nbsp;</p>';
	echo '<h2>Platforms</h2>';
	echo '<ul>';
	$platforms = 	wp_list_categories('show_count=0&title_li=&use_desc_for_title=0&hide_empty=0&child_of=1649');
	echo '</ul>';
	
}
		
	}else{
		echo '<h2><a href="'.  get_category_link($parent_cat->cat_ID) .'">'. $parent_cat->name .'</a></h2>';
		echo '<ul>';
		$categorylist = wp_list_categories('show_count=0&title_li=&use_desc_for_title=0&child_of='.$this_category->category_parent."&orderby=slug&hide_empty=0&exclude=1649"); 
		echo '</ul>';
		
		if ($parent_cat->name == "Apps"){
	echo '<p>&nbsp;</p>';
	echo '<h2>Platforms</h2>';
	echo '<ul>';
	$platforms = 	wp_list_categories('show_count=0&title_li=&use_desc_for_title=0&hide_empty=0&child_of=1649');
	echo '</ul>';
	
}
	}
}else{

//if top-level category
echo '<h2>'. $this_category->name .'</h2>';
echo '<ul>';
$categorylist = wp_list_categories('show_count=0&title_li=&use_desc_for_title=0&child_of='.$this_category->cat_ID."&orderby=slug&hide_empty=0&exclude=1649");
echo '</ul>';

if ($this_category->name == "Apps"){
	echo '<p>&nbsp;</p>';
	echo '<h2>Platforms</h2>';
	echo '<ul>';
	$platforms = 	wp_list_categories('show_count=0&title_li=&use_desc_for_title=0&hide_empty=0&child_of=1649');
	echo '</ul>';
	
}
}
echo '<p>&nbsp;</p>';



 ?>
	

</div>

        <div id="content" class="grid col-700 fit">
        <!-- <div class="select-post-sorting"></div> -->
		<?php 
		$cat = get_query_var('cat');
		$category_info = get_category($cat);
		//echo 'Is first level? = '. $category_info->parent;
		
		$parent_info = get_category($category_info->parent);
		//echo 'Is second level? = '. $parent_info->parent;
		
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //pagination		
		//$ext_query = '&meta_key=wpcf-pubdate&orderby=meta_value_num&posts_per_page=25&paged='.$paged.'';
		$sort_meta_key = 0; //init
		//get sort information from query vars or defaults
		$sort_orderby = (get_query_var('orderby')) ? get_query_var('orderby') : 'pubdate';
		$sort_order = (get_query_var('order')) ? get_query_var('order') : 'DESC';
		$sort_posts_per_page = (get_query_var('ppp')) ? get_query_var('ppp') : '25';
		
		if($sort_orderby =='pubdate'){
			$sort_orderby = 'meta_value_num';
			$sort_meta_key = 'wpcf-pubdate';
		}
	
		$ext_query = array(
												'post_type'=>'products',
												'category_name'=>$category_info->slug,
												'meta_key'=>'wpcf-pubdate',
												'orderby'=>'meta_value_num',
												'order'=>'DESC',
												'posts_per_page'=>'25',
												'tag'=>'listed',
												'paged'=>$paged
												);
												
		if($sort_meta_key){
			$ext_query['meta_key'] = $sort_meta_key;	
		}
		
		$title_qual = "";
		//query_posts($query_string . $ext_query );
		global $wp_query;
		//show only main product of groups, if product-group-parent field is populated, hide the post
		//edit: needs refinement - only for Bibles, and needs to compare non-existent OR blank
		// modified 3/18/14 to use custom SQL searching indexed taxonomy instead of custom field
		
		
		$newQuery = $ext_query;
		//$newQuery = array_replace($wp_query->query_vars, $ext_query);// + $p_groups; //changed for WP 4.0 something in WP_Query changed, found 0 posts
		//var_dump($newQuery);
	
			$books_in_cat = new WP_Query($newQuery);
		
		//echo '<br/>#posts: '.$books_in_cat->found_posts; //troubleshooting
			
if ($books_in_cat->have_posts()) : ?>
<div>
<div class="product-category product-list">
<h2><?php echo $title_qual; ?><?php single_cat_title(); ?> </h2>
<ul>
		<?php while ($books_in_cat->have_posts()) : $books_in_cat->the_post(); ?>
   
            <li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
			   bh_thumbnail(get_the_ID(),'medium',true);
			   
			   $product_title =  get_the_title(); //init
			   $pg_names = array(); //init
			   
			   if (has_term('listed','post_tag')){
				   $pg_names = wp_get_post_terms(get_the_ID(),'product-group',array('fields'=>'names'));
				   if (!empty($pg_names)){
					$product_title = reset($pg_names);//change to product group title
				   }
				   $pg_names = array(); //re-initalize array
			   }
			   
			   $short_title = wp_trim_words($product_title,8);
?>
                <a href="<?php the_permalink(); ?>"><?php echo $short_title; ?></a>
 				<?php $pubdate = get_post_meta(get_the_ID(),'wpcf-pubdate',true); 
							//turn pubdate into Month Year
						
							//modify product list display
							//if title is too long, don't show subtitle/authors
							if(strlen(get_the_title())<32){
								//get current category or parent category
								//get category
								if($parent_info){
									//if child category
									$current_cat = $parent_info->slug;
								}else{
									$current_cat = $category_info->slug;
								}
							//ge	t subtitle
							$p_subtitle =  types_render_field('subtitle', array("raw"=>"true"));
								
							//if Bibles or Reference, show subtitle
							//echo $current_cat;
							if($current_cat =='bibles' || $current_cat == 'reference' || $category_info->slug == 'commentaries' ):
							?>
                            <span class="author-names"><?php echo $p_subtitle ?></span>
                            <?php	
							else:
							//else show author(s)	
							//get author name(s)
							$a_list=false;
							$a_terms = wp_get_post_terms(get_the_ID(), 'a', array("fields" => "names"));
							if($a_terms){
								$a_list = implode(', ', $a_terms);	
							}	
							if($a_list){
							?>
                            <span class="author-names"><?php echo $a_list; ?></span>
                            <?php
							}elseif ($p_subtitle){
							//no authors (?) - show subtitle if exists	
							?>
						    <span class="author-names"><?php echo $p_subtitle; ?></span>
                            <?php
							}
							endif;

							}else{
								//title too long, no room for other text
							}
                ?>
            </li><!-- end of #post-<?php the_ID(); ?> -->
            

            
           <?php endwhile; 
		   if(!$hide_navi){
		   if(function_exists('wp_pagenavi')) { 
																		wp_pagenavi( array(
																											'query' =>$books_in_cat  
																											)
																								); 
																		}
		   }
		
		?> 
        
		</ul>
        </div>
	    <?php else : ?>

       <!--  <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'minimum'); ?></h1> -->
                    
        <p><?php _e('No products found in '. $parent_info->name.' > '. single_cat_title('',false) .'.', 'minimum'); ?></p>
                    
       <!--  <h6><?php printf( __('You can return %s or search for the page you were looking for.', 'minimum'),
	            sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
		            esc_url( get_home_url() ),
		            esc_attr__('Home', 'minimum'),
		            esc_attr__('&larr; Home', 'minimum')
	                )); 
			 ?></h6>
                    
        <?php get_search_form(); ?> -->

<?php endif; ?>  
      
        </div><!-- end of #content -->
</div>
</div>
<?php
} // end bh products category loop


// add sorting scripts to footer
//add_action('genesis_after_footer','bh_ajax_sort_posts');
function bh_ajax_sort_posts(){
	?>
    <script>
	//add drop-downs to js pages
	$jq('.select-post-sorting').html(
						
								'<form action="#">Sort by:<select name="posts_sort" class="posts_sort"><option value="newest">Release Date, Newest First</option><option value="oldest">Release Date, OIdest First</option><option value="az">Product Title, A-Z</option><option value="za">Product Title, Z-A</option></select> Quantity: <select name="posts_number" class="posts_number"><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25" selected>25</option><option value="50">50</option></select></form>'
	);
	
	//collect dropdown data
	$jq('.select-post-sorting select').on('change',function(){
		//get value from each box
		$sortby = $jq('.posts_sort').val();
		$number = $jq('.posts_number').val();
		var loc = String(window.location);
		$link = loc.substring(0,(loc.lastIndexOf('/')+1));
		switch($sortby){
			case 'oldest':
			o = '?orderby=pubdate&order=ASC';
			break;
			case 'az':
			o = '?orderby=title&order=ASC';
			break;
			case 'za':
			o = '?orderby=title&order=DESC';
			break;
			default: //newest
			o = '?orderby=pubdate&order=DESC';
		}	
		
		if($number){
			n='&ppp='+$number;	
			}else{
			n='&ppp=25';
			//default 25	
		}
		$link = $link + o + n;
		//v1 - load new page in div
		$plist = $jq('.product-category.product-list');
		$plist.fadeOut(300,function(){
			$jq(this).load($link + ' .product-category.product-list',function(){
						$plist.fadeIn(500);
									// update page url/hash  
									if($link!=window.location){
												//window.history.pushState({path:$link},'',$link);
									}  // if new url doesn't match current location
				}); //end load
		}); //end fade
		
	}); //end jq

	</script>
	<?php
	//v2 - use admin-ajax
}

//get_footer(); 
genesis();
?>
