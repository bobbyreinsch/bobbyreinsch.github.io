<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Home Page
 *
 * Note: You can overwrite home.php as well as any other Template in Child Theme.
 * Create the same file (name) include in /responsive-child-theme/ and you're all set to go!
 * @see            http://codex.wordpress.org/Child_Themes
 *
 * @file           home.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2013 ThemeID
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/responsive/home.php
 * @link           http://codex.wordpress.org/Template_Hierarchy
 * @since          available since Release 1.0
 */
?>
<?php // get_header(); ?>


<?php //Homepage features from "Features" post type
// Homepage CUSTOM LOOP
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_home_features_loop' );
add_action('genesis_loop','bh_home_newreleases_loop');

function bh_home_features_loop(){


$features_list = array(
		   							'post_type'=>'features',
									'posts_per_page'=>'5'	,
									'post_status'=>'publish',	
									'tax_query' => array(
																	array(
																		'taxonomy' => 'location',
																		'field'    => 'slug',
																		'terms'    => 'home-page-feature',
																	),
																),								
		   );
				
			$home_features = new WP_query($features_list);
 if($home_features->have_posts()){ ?>
				<div class="homepage feature">
    	 <div class="showcase flexslider carousel" id="showcase"><ul class="slides">
				<?php while($home_features->have_posts()): $home_features->the_post(); ?>
           		<li>
                 
                <div class="showcase-content"><?php the_content(); ?></div>
                <div class="showcase-thumbnail-caption"><?php the_title(); ?></div>
                
                </li>
               
                <?php endwhile; ?>
				</ul></div></div>
 				<?php
				wp_reset_postdata();
				 }	
		}
		
	//bhpub_bulk_remove_OP();	//check remove OP posts function
		
// feature template below - modify settings at bottom of page 
/*
<div class="homepage feature">
    <div class="showcase" id="showcase">
        <div class="showcase-slide">
        <div class="showcase-content"><img alt="about_slider" src="/wp-content/uploads/2013/04/about_slider.png" height="320" />
        <div style="position: absolute; top: 0px; left: 187px; height: 300px; width: 520px; background: black;">[embed]http://www.youtube.com/watch?v=azvGYuGX7h0[/embed]</div>
        </div>
        <div class="showcase-thumbnail"><img alt="videos" src="/wp-content/uploads/2013/04/thumb-video.jpg" />
        <div class="showcase-thumbnail-caption">videos</div>
        <div class="showcase-thumbnail-cover"></div>
        </div>
        </div>
       <!-- more slides here -->
    </div>
</div>
*/
function bh_home_newreleases_loop(){
// new releases by month
// set default month = this month
$this_month= mktime(0,0,0,date("m"),1,date("Y"));

//calculate previous month, and next two months
$last_month = strtotime(date('d M Y' , $this_month) . ' -1 month');
$next_month = strtotime(date('d M Y' , $this_month) . ' +1 month');
$next_2months = strtotime(date('d M Y' , $this_month) . ' + 2 months');


//convert to comparable pubdate
$pubdate_after = date('Y',$this_month).date('m',$this_month).'00';
$pubdate_before = date('Y',$next_month).date('m',$next_month).'00';

$this_month_class = ' class="current"';
	$last_month_class = '';
	$next_month_class='';
	$next_2months_class='';
// check for other month in query vars
global $post;
global $wp_query;
if (isset($wp_query->query_vars['nr']))
	{
		$sort_date=  $wp_query->query_vars['nr'];
		$new_month = strtotime($sort_date);
		//$new_next = mktime(0,0,0, date("m",$new_month)+1, 1, date("Y",$new_month));
		$new_next = strtotime(date('d M Y' , $new_month) . ' + 1 month');

		$pubdate_after = date('Y',$new_month).date('m',$new_month).'00';
		$pubdate_before = date('Y',$new_next).date('m',$new_next).'00';
		
		//change nav classes to active date range
		if($sort_date==date('MY',$last_month)){
			$this_month_class = '';
			$last_month_class = ' class="current"';
		}elseif($sort_date==date('MY',$next_month)){
			$this_month_class = '';
			$next_month_class = ' class="current"';
		}elseif($sort_date==date('MY',$next_2months)){
			$this_month_class = '';
			$next_2months_class = ' class="current"';
		}
	}
	
// add criteria to WP_query to match pubdate range
		$pubdate_args = array(
												array(
															'key' => 'wpcf-pubdate',
															'value' => array( (int) $pubdate_after, (int) $pubdate_before ),
															'type' => 'numeric',
															'compare' => 'BETWEEN'						
															)
										);
	//	$pgterms = get_terms('product-group',array('fields'=>'ids')); 
	/*
		$tax_args = array(
											'relation'=>'OR',
											array (
														'taxonomy'=>'product-group',
														//'field'=>'term_id',
														'terms'=>$pgterms,
														'operator'=>'NOT IN'
											),
												array(
														'taxonomy'=>'post_tag',
														'field'=>'slug',
													'terms'=>array('primary')
											)
		);
		*/
		
		//exclude supplies
		$s_child_cats = (array) get_term_children('19', 'category');
		//exclude apps 
		$a_child_cats = (array) get_term_children('2','category');	
		//exclude spanish // 17,18,16 +children of 16
		$sp_child_cats = (array) get_term_children('16','category');
		
		$exclude_cats = implode(',',array_merge(array('19,2,16,17,18'),$s_child_cats,$a_child_cats,$sp_child_cats));
		$inc_cats = implode(',',get_terms('category',array('fields'=>'ids','exclude'=>$exclude_cats))); 
		 // list new releases or featured new releases (look for tag)
		$new_products = array(
											'post_type'=>'products',
											'post_status'=>'publish',
											'posts_per_page'=>'25',
											'category__not_in' => array_merge(array('19,2,16,17,18'),$s_child_cats,$a_child_cats,$sp_child_cats),
											'meta_key' => 'wpcf-pubdate',
											// new orderby multiple
											//'orderby' => 'meta_value_num',
											//'order'=>'DESC',
											'tag'=>'listed',
											'meta_query' => $pubdate_args,	
											'orderby'=>array('meta_value_num'=>'DESC','date'=>'ASC')
											//'tax_query'=> $tax_args		
											//tax query also too slow. Filtered below							
				   );
		
				global $wpdb;
				$wpdb->show_errors();
				
				$plist = new WP_query($new_products);
		
		
// December usually has no releases, so it's prevented from appearing in the list
// hidden using CSS - styles.css line 572

		
?>

<div class="newsletter-mailchimp-form home-page-newsletter">
	<div class="box">
    	<div class="grid col-460"><h2><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/bh-logo-newsletter.png" /> Newsletter</h2><span>for FREE books, exclusive content, and author interviews</span></div>
        <div class="grid col-460 fit"><?php  bhpub_cmform_embed(); ?></div>
        <div class="clear"></div>
    </div>
</div>

<div class="inner">
<a name="new"></a>
<div class="new-releases-container">
<div class="loading"></div>
<ul class="new-release-nav" id="nrnav">
	<li class="<?php echo strtolower(date('M',$last_month)); ?>"><a href="?nr=<?php echo date('MY',$last_month); ?>#new" <?php echo $last_month_class ?>><?php echo date('F',$last_month); ?></a></li>
    <li class="<?php echo strtolower(date('M',$this_month)); ?>"><a href="?nr=<?php echo date('MY',$this_month); ?>#new" <?php echo $this_month_class ?>><?php echo date('F',$this_month); ?></a></li>
    <li class="<?php echo strtolower(date('M',$next_month)); ?>"><a href="?nr=<?php echo date('MY',$next_month); ?>#new" <?php echo $next_month_class ?>><?php echo date('F',$next_month); ?></a></li>
    <li class="<?php echo strtolower(date('M',$next_2months)); ?>"><a href="?nr=<?php echo date('MY',$next_2months); ?>#new" <?php echo $next_2months_class ?>><?php echo date('F',$next_2months); ?></a></li>
    <!-- <li><a href="#new" class="closetoggle">close list...</a></li>-->
</ul>
<a href="#nrnav" class="nrnav-toggle"></a>
    <h2>New Releases</h2>
    <div class="new-releases-list">
		<?php if($plist->have_posts()){
			//if($plist){
				echo '<ul>';
				$pgcount = 0;
				while($plist->have_posts()): $plist->the_post(); 
				//foreach($plist as $post){
			//	setup_postdata($post);
							?>
                <li id="product-<?php _e($post->ID) ?>" <?php post_class(); ?>>
			   <?php    bh_thumbnail($post->ID,'thumbnail',true);	 ?><br/>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                
                <?php
				//$pgcount++;
                //modify product list display
							//if title is too long, don't show subtitle/authors
							if(strlen(get_the_title())<32){
								//get current category or parent category
									$this_category = get_the_category($post->ID);
									$current_cat = $this_category[0]->slug;

							//ge	t subtitle
							$p_subtitle =  types_render_field('subtitle', array("raw"=>"true"));
								
							//if Bibles or Reference, show subtitle
							//echo $current_cat;
							if($current_cat =='bibles' || $current_cat == 'reference' || $current_cat == 'commentaries' ):
							?>
                            <span class="author-names"><?php echo $p_subtitle ?></span>
                            <?php	
							else:
							//else show author(s)	
							//get author name(s)
							$a_terms = wp_get_post_terms($post->ID, 'a', array("fields" => "names"));
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
							} ?>
                            </li>
                <?php 
			
				endwhile;
			//	} //end foreach;
				echo '</ul>';
				wp_reset_postdata();
		} ?>
    </div></div>
</div>


<?php //home sidebar widgets 2 and 3 here ?>
<div class="featured-stories">
	<div class="inner">
    
			<?php get_sidebar('home'); ?>
             
	</div>
</div>

<?php 
			//feed from Posts cat: New and Noteworthy with excerpt and thumbs
			$n_posts = array(
		   							'post_type'=>'post',
									'posts_per_page'=>'4',
									'category_name'=>'new-noteworthy'						
		   );
				
			$news = new WP_query($n_posts);
			if($news->have_posts()){
				$i=0;$fit='';
				echo '<div class="small-stories"><div class="inner"><h2>New and Noteworthy</h2>';
				while($news->have_posts()): $news->the_post(); 
					$i++;if($i==4){$fit='fit';}
					?>
					<div class="grid col-220 <?php echo $fit?>">
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('full'); ?></a>
                    <br /><a class="text-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <?php the_excerpt(); ?>
					</div>
				<?php endwhile;
				echo '</div><div class="clearfix"></div></div>';
				wp_reset_postdata();
			}
?>

<?php //parse blog and post feeds?>
<div class="home-feeds">
	<div class="inner">
    	<div class="grid col-460">
        <h2>From the Blog <a href="http://blog.bhpublishinggroup.com" class="home-hdr-link"></a></h2>
        <div class="home-blog-feed">
        	<ul>
        <?php if(function_exists('fetch_feed')) {  
 					$a = true;
					include_once(ABSPATH . WPINC . '/feed.php'); // the file to rss feed generator  
					$feed = fetch_feed('http://blog.bhpublishinggroup.com/feed'); // specify the rss feed  
				  
					$limit = $feed->get_item_quantity(10); // specify number of items  
					$items = $feed->get_items(0, $limit); // create an array of items  
				  
				}  
				if ($limit == 0) echo '<div>The feed is either empty or unavailable.</div>';  
				else foreach ($items as $item) :
                	$fixedTitle = str_replace('Blog Post: ','',$item->get_title()); 
					?>  
				 <?php //if($a){var_dump($item);}$a=false;?>
                 <li>
				<h4><a href="<?php echo $item->get_permalink(); ?>" alt="<?php echo $fixedTitle ?>" target="_bhblog"><?php echo $fixedTitle ?></a></h4>  
				 <span class="blog-date"><?php echo $item->get_author()->get_name();?> | <?php echo $item->get_date('F j');//echo $item->get_date('j F Y @ g:i a'); ?></span>  
				<!-- <p><?php //echo substr($item->get_description(), 0, 200); ?> ...</p> -->  
				  </li>
				<?php endforeach; ?> 
        
        	</ul>
        </div></div>
        <div class="grid col-460 fit">
       <h2>Press Releases <a href="/press-releases" class="home-hdr-link"></a></h2> 
       <div class="home-press-releases">
       <?php
       	$pr_posts = array(
		   							'post_type'=>'post',
									'posts_per_page'=>'8',
									'category_name'=>'press-release'						
		   );
				
			$pr = new WP_query($pr_posts);
			if($pr->have_posts()){
				echo '<ul>';
				while($pr->have_posts()): $pr->the_post(); ?>
       				<li id="pr-<?php the_ID(); ?>">
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <span class="pr-date"><?php echo get_the_date('F j', $post->ID); ?></span>
                    </li>
	       			<?php endwhile;
				echo '</ul>';
				wp_reset_postdata();
			}
			
       ?>
        </div>
	</div>
</div> <!-- .inner -->
<div class="clearfix"></div>
</div>


<?php //newsletter signup ?>	
<div class="newsletter-form-footer home-newsletter">
<?php //old Yesmail signup 
/*
<h3>Sign up for our Newsletter</h3>
 <p>&nbsp;<!--We don't want to brag, but it's great stuff.--></p> 
<iframe src="http://www2.bhpublishinggroup.com/bhpub_newsletters.asp?l=home" height="10em" width="50%" scrolling="no" frameborder="0"></iframe>
*/
// Mission Statement Below
genesis_widget_area( 'home-featured-3', array(
																			'before' => '<div class="mission-statement inner">',
																			'after' => '</div>',
																			));
	?>
</div>


  <?php //remove default home page	
  
} //end custom loop function
 genesis(); 
  
  /*
  	<div class="inner">
        <div id="featured" class="grid col-940">
  
        <div class="grid col-460">

            <?php $options = get_option('responsive_theme_options');
			// First let's check if headline was set
			    if ($options['home_headline']) {
                    echo '<h1 class="featured-title">'; 
				    echo $options['home_headline'];
				    echo '</h1>'; 
			// If not display dummy headline for preview purposes
			      } else { 
			        echo '<h1 class="featured-title">';
				    echo __('Hello, World!','responsive');
				    echo '</h1>';
				  }
			?>
                    
            <?php $options = get_option('responsive_theme_options');
			// First let's check if headline was set
			    if ($options['home_subheadline']) {
                    echo '<h2 class="featured-subtitle">'; 
				    echo $options['home_subheadline'];
				    echo '</h2>'; 
			// If not display dummy headline for preview purposes
			      } else { 
			        echo '<h2 class="featured-subtitle">';
				    echo __('Your H2 subheadline here','responsive');
				    echo '</h2>';
				  }
			?>
            
            <?php $options = get_option('responsive_theme_options');
			// First let's check if content is in place
			    if (!empty($options['home_content_area'])) {
                    echo '<p>'; 
					echo do_shortcode($options['home_content_area']);
				    echo '</p>'; 
			// If not let's show dummy content for demo purposes
			      } else { 
			        echo '<p>';
				    echo __('Your title, subtitle and this very content is editable from Theme Option. Call to Action button and its destination link as well. Image on your right can be an image or even YouTube video if you like.','responsive');
				    echo '</p>';
				  }
			?>
            
            <?php $options = get_option('responsive_theme_options'); ?>
		    <?php if ($options['cta_button'] == 0): ?>     
            <div class="call-to-action">

            <?php $options = get_option('responsive_theme_options');
			// First let's check if headline was set
			    if (!empty($options['cta_url']) && $options['cta_text']) {
					echo '<a href="'.$options['cta_url'].'" class="blue button">'; 
					echo $options['cta_text'];
				    echo '</a>';
			// If not display dummy headline for preview purposes
			      } else { 
					echo '<a href="#nogo" class="blue button">'; 
					echo __('Call to Action','responsive');
				    echo '</a>';
				  }
			?>  
            
            </div><!-- end of .call-to-action -->
            <?php endif; ?>         
            
        </div><!-- end of .col-460 -->

        <div id="featured-image" class="grid col-460 fit"> 
                           
            <?php $options = get_option('responsive_theme_options');
			// First let's check if image was set
			    if (!empty($options['featured_content'])) {
					echo do_shortcode($options['featured_content']);
		    // If not display dummy image for preview purposes
			      } else {             
                    echo '<img class="aligncenter" src="'.get_stylesheet_directory_uri().'/images/featured-image.png" width="440" height="300" alt="" />'; 
 				  }
			?> 
                                   
        </div><!-- end of #featured-image --> 
        </div><!-- end of .inner -->
       
        </div><!-- end of #featured -->
		
<div class="inner">               
<?php get_sidebar('home'); ?>
</div>

	//$plist = new WP_query($new_products);
		//INNER JOIN `wp_term_relationships` tr1 ON (wp_posts.ID = tr1.object_id)
		//		INNER JOIN `wp_term_taxonomy` ttt1 ON (tr1.term_taxonomy_id = ttt1.term_taxonomy_id)
		//		INNER JOIN `wp_terms` t1 ON (ttt1.term_id = t1.term_id)
		$mod_tax_query = '
				SELECT  ID,post_title FROM `wp_posts`
				
				INNER JOIN `wp_postmeta` ON (wp_posts.ID = wp_postmeta.post_id AND wp_postmeta.meta_key = "wpcf-pubdate") 
												
				INNER JOIN `wp_term_relationships` tr2 ON (wp_posts.ID = tr2.object_id)
				INNER JOIN `wp_term_taxonomy` ttt2 ON (tr2.term_taxonomy_id = ttt2.term_taxonomy_id)
				INNER JOIN `wp_terms` t2 ON (ttt2.term_id = t2.term_id)
				
				WHERE post_type = "products"
				AND post_status = "publish" 	
				
				AND (wp_posts.ID IN (SELECT p5.ID FROM `wp_posts` p5
																INNER JOIN `wp_term_relationships` tr1 ON (p5.ID = tr1.object_id)
																INNER JOIN `wp_term_taxonomy` ttt1 ON (tr1.term_taxonomy_id = ttt1.term_taxonomy_id AND ttt1.taxonomy="category")
																INNER JOIN `wp_terms` t1 ON (ttt1.term_id = t1.term_id)
																AND t1.term_id IN ('. $inc_cats .')
																)
							)
			 			
				AND ((wp_posts.ID NOT IN(
											SELECT p1.ID FROM `wp_posts` p1 
											INNER JOIN `wp_term_relationships` tr3 ON (p1.ID = tr3.object_id)
											INNER JOIN `wp_term_taxonomy` ttt3 ON(tr3.term_taxonomy_id = ttt3.term_taxonomy_id AND ttt3.taxonomy = "product-group")
											)
									)
								OR (ttt2.taxonomy = "post_tag" AND t2.name = "primary"))
				 
				AND wp_postmeta.meta_value BETWEEN '. (int) $pubdate_after .' AND '. (int) $pubdate_before .'
				ORDER BY wp_postmeta.meta_value DESC
				LIMIT 20
				';
				//echo $mod_tax_query; //debug
//$plist = $wpdb->get_results($mod_tax_query, OBJECT_K);


*/ ?>
<?php // get_footer(); ?>