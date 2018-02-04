<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Pages Template
 *
 *
 * @file           category-landing.php
 */
?>

<?php
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_category_landing_page_loop' );

add_action('genesis_before','bh_pr_redirect');

function bh_pr_redirect(){
	if(is_category(47)){ //Press Release category
	wp_redirect(get_permalink(147)); //Press Releases Page ID
	} //end if is category
} // end bh pr redirect

function bh_category_landing_page_loop(){

?>

<div class="inner">

<div class="sidebar grid col-220 product-categories">

<?php //get_sidebar(); 

$title_qual='';	

if (is_category()) {
$this_category = get_category( get_query_var( 'cat' ) );
}

//if child category
if($this_category->category_parent) {
$parent_cat = get_category($this_category->category_parent);
echo '<h2><a href="'.  get_category_link($parent_cat->cat_ID) .'">'. $parent_cat->name .'</a></h2>';
echo '<ul>';
$categorylist = wp_list_categories('show_count=0&title_li=&use_desc_for_title=0&child_of='.$this_category->category_parent.'&orderby=slug&hide_empty=0&exclude=1649');  //exclude books>other cat ID 886
$ischildcat = true;

echo '</ul>';

if ($parent_cat->name == "Apps"){
	echo '<p>&nbsp;</p>';
	echo '<h2>Platforms</h2>';
	echo '<ul>';
	$platforms = 	wp_list_categories('show_count=0&title_li=&use_desc_for_title=0&hide_empty=0&child_of=1649');
	echo '</ul>';
	
}
echo '<p>&nbsp;</p>';
}else{

//if top-level category
echo '<h2><a href="'.  get_category_link($this_category->cat_ID) .'">'. $this_category->name .'</a></h2>';
echo '<ul>';

$categorylist = wp_list_categories('show_count=0&title_li=&use_desc_for_title=0&child_of='.$this_category->cat_ID.'&orderby=slug&hide_empty=0&exclude=1649');

echo '</ul>';
echo '<p>&nbsp;</p>';

if ( $this_category->name == "Apps"){
	echo '<h2>Platforms</h2>';
	echo '<ul>';
	$platforms = 	wp_list_categories('show_count=0&title_li=&use_desc_for_title=0&hide_empty=0&child_of=1649');
	echo '</ul>';
}

$ischildcat=false;
}
echo '<p>&nbsp;</p>';
	
?>
</div>
        <div id="content" class="grid col-700 fit">
		<?php 
		$cat = get_query_var('cat');
		$category_info = get_category($cat);
		//echo 'Is first level? = '. $category_info->parent;
			//print_r($category_info);
		if ($category_info->parent):
		$parent_info = get_category($category_info->parent);
		//echo 'Is second level? = '. $parent_info->parent;
			//print_r($parent_info);
		endif;
		
		?>
             <!-- no breadcrumb on category page
        <?php //$options = get_option('responsive_theme_options'); 
					//if ($options['breadcrumb'] == 0): 
					//echo responsive_breadcrumb_lists(); 
        			//endif; ?>
        -->
        <div>
        <?php //insert page with matching slug
		$cat=get_category(get_query_var('cat'),false);
		$content_page = $cat->slug.'-content';
		
		//if sub-page, include parent slug
		if ($parent_info){
				$content_page = $parent_info->slug.'-content'.'/'.$content_page;
		}
		//echo $content_page;  //testing, display slug
		$args = array(
							'pagename' => $content_page,
							'post_type' => 'page'
						);
		$category_page = new WP_Query($args);
		if ( $category_page->have_posts() ):
		
			while ( $category_page->have_posts() ) :
			$category_page->the_post(); 
			
				
		//custom class information from custom fields 								
			$headline_color = types_render_field('headline-color', array("raw"=>"true"));
			$headline_size = types_render_field('headline-size', array("raw"=>"true"));
			$text_color = types_render_field('text-color', array("raw"=>"true"));	
			$feature_link = types_render_field('feature-link', array("raw"=>"true"));	
			
			$custom_class = $headline_size.' '.$text_color.' '.$headline_color.'-headline ';		
			
			//custom or standard layout
			//if post has tag "custom"
			if (has_tag('custom')):
			?>
            <div class="custom-category-feature"><?php the_content(); ?> </div>
            <?php
			else:
			//standard feature layout
			?>
            <div class="category-feature <?php echo $custom_class ?>">
            <?php if(has_post_thumbnail()): ?>
            <div class="bg-image clearfix"><?php the_post_thumbnail('category-header'); ?></div>
			
            <?php endif; ?>
			</div>
			<div style="margin-top:-48px; padding:0px 20px;"><?php the_content(); ?></div>
            <?php
			endif;
		endwhile;
	
		wp_reset_postdata();
        ?>
        </div>
        
<?php 
			//echo "filename: category-landing.php";
			$hide_navi = false; //init
			if(!$ischildcat):
			//if main landing page
			//$ext_query = '&meta_key=wpcf-pubdate&orderby=meta_value_num&tag=best-selling&posts_per_page=10';
			
			$priority_query = array(
										
										'relation'=>'OR',	
										array(
												'key' => 'wpcf-item-priority-order',
												'compare' => 'NOT EXISTS'
			
											),
										array(
												'key' => 'wpcf-pubdate'

											)
				);

			$ext_query = array(
												//'meta_key'=>'wpcf-pubdate',
												'orderby'=>'meta_value_num',
												'meta_query' => $priority_query,
												//'tag'=>'top-10-'.$this_category->slug,
												'tag'=>'best-selling',
												'posts_per_page'=>'20'
			);
			
			$title_qual = "Best-Selling ";
			$hide_navi = true;
				//get Spanish category id and subs
				$terms = get_terms('category','child_of=16');
				
					if ($terms) {
					  foreach( $terms as $term ) {
						//$espcats .= '-' . $term->term_id . ','; //query string
						$espcats .=  $term->term_id . ','; //query array
					
					  }
					}
					//$espcats .= '-16,-17,-18'; //with Spanish top category and additional Spanish subcategories of other top categories
					$espcats .= '16,17,18';
					//$espcats = array($esplist);
					
				if($cat->slug=='spanish'):
				//show Spanish books only in Spanish category
				$ext_query = $ext_query;	
				elseif($cat->slug=='books'):
				//if books category, don't show featured Kids books (or Spanish books)
					//$ext_query = $ext_query.'&cat=-9,'.$espcats;
					$espcats .=',9';
					//$ext_query = array_merge($ext_query,array('category_not__in'=>array($espcats)));
					$ext_query = array_replace($ext_query, array('category__not_in'=>explode(',',$espcats)));
				else:
				//don't show Spanish books
				//$ext_query = $ext_query.'&cat='.$espcats;
				//$ext_query = array_replace($ext_query,array('category_not__in'=>array($espcats)));
				$ext_query = array_replace($ext_query,array('category__not_in'=>explode(',',$espcats)));
				endif;
			else:
			//otherwise (no landing page) <-- wrong, there is a landing page for these. Looks like this is second-level page.
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //pagination		
			//$ext_query = '&meta_key=wpcf-pubdate&orderby=meta_value_num&posts_per_page=25&paged='.$paged.'';
			$ext_query = array(
												'meta_key'=>'wpcf-pubdate',
												'orderby'=>'meta_value_num',
												'order'=>'DESC',
												'posts_per_page'=>'25',
												'tag'=>'listed',
												'paged'=>$paged
												);
			$title_qual = "";
				
			endif;	//is child category
		else:		
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //pagination		
		//$ext_query = '&meta_key=wpcf-pubdate&orderby=meta_value_num&posts_per_page=25&paged='.$paged.'';
		$ext_query = array(
												'meta_key'=>'wpcf-pubdate',
												'orderby'=>'meta_value_num',
												'order'=>'DESC',
												'posts_per_page'=>'25',
												'tag'=>'listed',
												'paged'=>$paged
												);
		$title_qual = "";
		endif; // has category content page
		//query_posts($query_string . $ext_query );
		global $wp_query;
		//show only main product of groups, if product-group-parent field is populated, hide the post
		// modified 3/18/14 to use indexed taxonomy instead of custom field
		$term_args = array('exclude'=>'57','fields'=>'ids'); //exclude 'featured'
	
		//$newQuery = array_replace($wp_query->query_vars,$ext_query);// + $p_groups;
		
		$ext_query['category_name'] = $category_info->slug;
		$ext_query['product_type']='products';
		
		
		
		$newQuery = $ext_query;
		//$books_in_cat = new WP_Query($newQuery);
		$books_in_cat = new WP_Query($newQuery);
		
		
	
if ($books_in_cat->have_posts()) : ?>
<div class="products-loading"> </div>
<div>
<div class="product-category product-list">
<h2><?php echo $title_qual; ?><?php single_cat_title(); ?> </h2>
<ul>
		<?php while ($books_in_cat->have_posts()) : $books_in_cat->the_post(); 
		$post_id = get_the_ID();
		?>
   
            <li id="post-<?php _e($post_id) ?>" <?php post_class(); ?>>
		<?php
			   bh_thumbnail($post_id,'medium',true);
			   
			   $product_title =  get_the_title(); //init
			   $pg_names = array(); //init
			   
			   if (has_term('listed','post_tag')){
				   $pg_names = wp_get_post_terms($post_id,'product-group',array('fields'=>'names'));
				   if (!empty($pg_names)){
					$product_title = reset($pg_names);//change to product group title
				   }
				   $pg_names = array(); //re-initalize array
			   }
			   
			   $short_title = wp_trim_words($product_title,8);
?>
                <a href="<?php the_permalink(); ?>"><?php echo $short_title; ?></a>
 				<?php $pubdate = get_post_meta($post_id,'wpcf-pubdate',true); 
							//turn pubdate into Month Year
							
					//modify product list display
							//if title is too long, don't show subtitle/authors
							if(strlen($product_title)<32){
								//get current category or parent category
								//get category
								if(isset($parent_info)){
									//if child category
									$current_cat = $parent_info->slug;
									$product_cat = $category_info->slug;
								}else{
									$current_cat = $category_info->slug;
									
								}
								
								
							//ge	t subtitle
							$p_subtitle =  types_render_field('subtitle', array("raw"=>"true"));
							//if Bibles or Reference, show subtitle
							if($current_cat =='bibles' || $current_cat == 'reference'):
							?>
                            <span class="author-names"><?php echo $p_subtitle ?></span>
                            <?php	
							else:
							$a_list = false; //init
							//else show author(s)	
							//get author name(s)
							$a_terms = wp_get_post_terms($post_id, 'a', array("fields" => "names"));
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
            
        <?php endwhile; ?> 
        <div class="clear">&nbsp;</div>
        </ul>
        <?php /*
		 if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'minimum' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'minimum' ) ); ?></div>
		</div><!-- end of .navigation --> ?>
        
        <?php endif; 	*/ 	
		
		   if(!$hide_navi){
		   if(function_exists('wp_pagenavi')) { 
																		wp_pagenavi( array(
																											'query' =>$books_in_cat  
																											)
																								); 
																		}
		   }
		 ?>
		
     
        </div>
        </div></div>
	    <?php else : ?>

        <!-- <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'minimum'); ?></h1> -->

          <p><?php _e('No products have been assigned to '. single_cat_title('',false) .'.', 'minimum'); ?></p>
        <!--             
        <h6><?php printf( __('You can return %s or search for the page you were looking for.', 'minimum'),
	            sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
		            esc_url( get_home_url() ),
		            esc_attr__('Home', 'minimum'),
		            esc_attr__('&larr; Home', 'minimum')
	                )); 
			 ?></h6>
                    
        <?php get_search_form(); ?>
			-->
            </div>
<?php endif; ?>  

        </div><!-- end of #content -->
</div>

<?php
} // end bh category landing page loop
//get_footer(); 
genesis();
/*
deprecated - direct DB SQL lookup filters
//	$pgterms = get_terms('product-group', $term_args); 
		//print_r($pgterms);
		//the tax_query cannot combine OR with AND, so a custom SQL query was required (below)
		$p_groups = array(
											'tax_query'=>array(
																			//'relation'=>'OR',
																				array(
																							'taxonomy'=>'category',
																							'field'=>'slug',
																							'terms'=>array($cat->slug)
																							),
																			//	array(
																			//				'taxonomy'=>'product-group',
																			//				'field'=>'slug',
																			//				'terms'=>array('featured')
																			//				),
																				array(
																							'taxonomy'=>'product-group',
																			//				'terms'=>$pgterms,
																							'operator'=>'NOT IN'
																							)
																			
																		//		array(
																		//					'taxonomy'=>'product-group',
																		//					'terms'=>$pgterms,
																		//					'operator'=>'NOT IN'
																		//					)
											
																			)	
																			
											//'meta_query'=> array(
											//		array(
											//					'key'=>'wpcf-product-group-parent',
											//					'value'=>null,
											//					'compare'=>'NOT EXISTS'
											//				)
											//		)
											);
	
		//$newQuery = array_merge($wp_query->query_vars, $ext_query, $p_groups);


//add_filter( 'posts_fields', 'bhpub_cat_posts_fields' );
//add_filter( 'posts_join', 'bhpub_cat_posts_join' );
//add_filter( 'posts_where', 'bhpub_cat_posts_where' );
//add_filter( 'posts_orderby', 'bhpub_cat_posts_orderby' );

//$newQuery = $newQuery ;// + $p_groups;
		// modify page query to remove titles in a group that are not the feature title
	
//var_dump($newQuery); //debug		
	//query_posts($newQuery);		 //SQL filters only work with WP_query
if($parent_cat->name == "Bibles"){
		
	$books_in_cat = new WP_Query($newQuery);


		}else{
			$books_in_cat = new WP_Query($newQuery);
		}


// Make sure these filters don't affect any other queries
//remove_filter( 'posts_fields', 'bhpub_cat_posts_fields' );
//remove_filter( 'posts_join', 'bhpub_cat_posts_join' );
//remove_filter( 'posts_where', 'bhpub_cat_posts_where' );
//remove_filter( 'posts_orderby', 'bhpub_cat_posts_orderby' );
*/
?>
