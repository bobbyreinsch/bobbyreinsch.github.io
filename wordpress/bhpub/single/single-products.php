<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Pages Template
 *
 *
 * @file           single-products.php
 */
?>
<?php
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action( 'genesis_loop', 'bh_single_product_post_loop' );
?>

<?php //get_header(); 
function bh_single_product_post_loop(){
?>

<div class="inner">
<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); 
		global $post;
		$thispost = $post->ID;
		
		//get top-level category
					$p_cats=get_the_category($thispost);
					$parents =get_category_parents($p_cats[0]->term_id);
					$topcat = false; //init
					if(is_string($parents)){
						$cat_arr = explode("/",$parents);
						$topcat = $cat_arr[0];
					}
					
		 //get author slugs from tags
	 $arrAuthors = array();
	 $author_tags = get_the_terms($post->ID, 'a');
	 if($author_tags){
		foreach ($author_tags as $atag){
			array_push($arrAuthors, $atag->slug);
		}
		//get author posts matching each slug
		$authorids = array();
		foreach($arrAuthors as $aslug){
			$sargs = array(
						'post_type'=>'bhauthor',
						'name'=>$aslug
				);	
				$aposts = get_posts($sargs);
				if($aposts){
					array_push($authorids,$aposts[0]->ID);
				}
			}
			
			
			
	//verify author post exists
	$authorlinks = array();
	if ($authorids){
	$aargs = array(
					'post_type'=>'bhauthor',
					'post__in'=>$authorids,
					'posts_per_page'=>'3' //max number for now, remove later
			);
			
	$author_posts = new WP_query($aargs);
	//count posts
	$countauthors = $author_posts->found_posts;
	//echo $countauthors;
	$author_names_linked = '';
	
	//loop through author posts
	
	while ( $author_posts->have_posts() ) :
					$author_posts->the_post();
					$authorlinks[$post->post_name] = get_permalink(); //assign permalink to slug							
	endwhile;
	//print_r($authorlinks);
	}
	
	//loop through author tags
	//if author page, link to that, otherwise link to tag archive page
	$tagcount=0;
	$numtags = count($author_tags);
	//sort author tags by last name
	foreach($author_tags as $tag){
		$tname = $tag->name;
		//get just last name and add it to a new property
		$tag->lastname = substr( $tname, strrpos( $tname, ' ' )+1 );
	}
	function sort_tags($a, $b){
		return strcmp($a->lastname, $b->lastname);
	}
	//sort author tag objects by lastname property
	usort($author_tags, 'sort_tags');
	foreach($author_tags as $tag){
		
		if(array_key_exists($tag->slug,$authorlinks)): //if there is an author post
			$taglink = $authorlinks[$tag->slug];
		else: //no matching post, link to tag/term archive page
			$tagid = $tag->term_id;
			$taglink = get_term_link((int)$tagid, 'a');
		endif;
		$tagcount++;
					if ($tagcount==1):
					$author_names_linked = '<a href="'.$taglink.'">'.$tag->name.'</a>';
					//add "and " to final author
					elseif ($tagcount==$numtags):
					$author_names_linked = $author_names_linked.' and <a href="'.$taglink.'">'.$tag->name.'</a>';
					else:
					// add ", " to the rest
					$author_names_linked = $author_names_linked.', <a href="'.$taglink.'">'.$tag->name.'</a>';
					endif;	
	}
	}//end if author tags
	//wp_reset_postdata();
	wp_reset_query();
	
	//$subtitle = types_render_field('subtitle', array("output"=>"html"));		
	$subtitle = stripslashes_deep(types_render_field('subtitle', array("raw"=>"true")));
	
	$productwebsite = types_render_field('wpcf-product-website',array('raw'=>'true'));
					$productfb = types_render_field('wpcf-product-facebook',array('raw'=>'true'));
					$netgalley = types_render_field('wpcf-product-netgalley',array('raw'=>'true'));
					$showLinks = true;	
					
				//	if($topcat == 'Supplies' || $topcat=='Apps'){
						if(!$productwebsite && !$productfb){
							$showLinks = false;
						}
				//	}	
							
		?>
 <div class="grid col-460 products-top-left">       
        <?php /* $options = get_option('responsive_theme_options'); 
		 if ($options['breadcrumb'] == 0): 
		 echo responsive_breadcrumb_lists(); 
         endif; */?>
		<div class="mobile-product-header">
		<h1><?php the_title() ?></h1>
                    <?php if ($subtitle!=''){ 
                    	echo '<p class="subtitle">'.$subtitle.'</p>';
					} 
                    	//author links
						if ($author_names_linked!=''){
						echo '<div class="product-author-list">'.$author_names_linked.'</div>';	
						}


		?></div>    
</div>
<div class="grid col-460 fit products-top-right">
<?php
//get thumbnail permalink for pinterest button
$pinit_image = false; //init
if(get_post_thumbnail_id($thispost)):
	$pinit_image = wp_get_attachment_image_src( get_post_thumbnail_id($thispost), 'large');
else:
	$pinit_image = get_post_meta($thispost,'wpcf-external-thumb',true);
	if(!$pinit_image){
		$pinit_image =	site_url('/img/bh-logo-login.png'); //fallback image url
	}
endif;



// ngfb tweet button, fb like button
// if ( function_exists( 'ngfb_get_social_buttons' ) )
//echo ngfb_get_social_buttons( array( 'twitter','facebook' ) ); 

// Social Sharing Buttons (YOAST code)
// could also be done as template part, plugin, or in functions.php
?>
<ul class="social buttons">
	       <li>
	    <a href="http://twitter.com/share" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-via="example" class="twitter-share-button">Tweet</a>
	</li>
    <li>
    	 <a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&amp;media=<?php echo urlencode($pinit_image)  ?>&amp;description=<?php urlencode(get_the_title()); ?>" class="pin-it-button" data-pin-config="beside" data-pin-do="buttonPin">Pin It</a>
    </li>
      <li>
	   <!-- <fb:like href="<?php the_permalink() ?>" send="true" showfaces="false" width="120" layout="button_count" action="like"/></fb:like> -->
        <div class="fb-like" data-href="<?php the_permalink() ?>" data-width="120"  data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="true"></div>
     </li>

	  
<?php 	//don't show these extra buttons
	/*
      <li>
	    <g:plusone size="medium" callback="plusone_vote"></g:plusone>
	  </li>
	  <li>
	    <script type="in/share" data-url="<?php the_permalink(); ?>"  data-counter="right"></script>
	  </li>
	  <li>
	    <div id="stumbleupon-button"></div>
	  </li>
       
  */   ?>
</ul>




<?php //end social share buttons ?>
</div>
<div class="product-content">
<div class="grid col-300">
<div class="product-page-thumbnail">
<?php //get_sidebar();
if($topcat == "Supplies"): // for supplies only
	//add lightbox to display full size image
	$th_url = bh_thumbnail_url($thispost); 
	echo '<a href="'.$th_url.'" rel="wp-video-lightbox">';
	//custom thumbnail function
	bh_thumbnail($thispost, 'large');
	echo '</a>';			
else:
	//custom thumbnail function - most products
	bh_thumbnail($thispost, 'large');
endif;

// additional images gallery (if exist) v2


?></div> <?php
//buy now buttons
$msrp = types_render_field('msrp', array("raw"=>"true"));
$isbn13 = types_render_field('isbn', array("raw"=>"true"));
$isbn10 = types_render_field('isbn10', array("raw"=>"true"));
$upc = types_render_field('upc',array("raw"=>"true"));
$lin = types_render_field('lin', array("raw"=>"true"));

$pubdate_raw = types_render_field('pubdate', array("raw"=>"true"));
$pubdate=strtotime(substr($pubdate_raw,0,4) . '/'. substr($pubdate_raw,4,2) . '/' . substr($pubdate_raw,6,2));

$green_arrow = get_stylesheet_directory_uri().'/img/btn-arrow-down-green.png';

//if($topcat == "Supplies" ){
$buybtntxt = "Buy Now";
if ($pubdate>time()){
$buybtntxt	= "Preorder";
}
//}else{
//$buybtntxt = "Buy Print Edition";
//if ($pubdate>time()){
//$buybtntxt	= "Preorder Print Edition";
//
//}

//edit image locations to local theme
if(is_numeric($msrp)){
	$retail_price = '$'.number_format((float)$msrp, 2, '.', ''); // force numeric prices to currency 
}else{
	$retail_price = $msrp; // if MSRP is text, for example, "FREE"
}

?>
<div class="mobile-social-links">
<?php
if($showLinks){
					 ?>
                    <div class="product-social-links">
                	<ul>
					<?php 
					if ($productwebsite!=''){
						echo '<li><a class="site-link" href="'.$productwebsite.'" target="_blank">site</a></li>';
					}
					if ($productfb!=''){
						echo '<li><a class="facebook-link" href="'.$productfb.'" target="_facebook">facebook</a></li>';
					}
					//is this netgalley or an email link?
					//link to google form - try to include ISBN and title
					/* //remove request review copy from product page
					?>
                    <?php if($topcat != "Supplies" && $topcat != "Apps"){
				   if($cat_arr[1]=="Academic"){ ?>
                   		<li><a class="review-link" href="http://www.bhacademic.com/request.asp" target="_blank">review copy</a></li>
                  <?php  }else{ ?>
                    	<li><a class="review-link" href="/request-review-copy/?req_isbn=<?php echo $isbn13; ?>">review copy</a></li>
                 <?php   }
					 } 
					 */ ?></ul>
                    </div>
                    <?php 
					}//end if showLinks
?></div>
<?php //look inside button
if(function_exists('bh_product_gallery_btn')){
	bh_product_gallery_btn();
}

?>
<div class="product-price"><strong>MSRP <?php _e($retail_price); ?> </strong><!-- | <em>prices vary per retailer</em>--></div>

<?php //display different buttons for supplies
/*<a class="buybtn" href="buy.php?p=<?php echo $isbn13 ?>" target="_blank"><?php echo $buybtntxt ?></a> */ ?>
<a class="buybtn" href="http://www.lifeway.com/Product/<?php if($lin){echo $lin;}else{echo $isbn13;}?>" target="_blank"><?php echo $buybtntxt ?></a>
         <div class="retailers" style="display:block;"><ul>

         		<?php //check custom fields for links, returns false if empty

         		$link_lw = types_render_field('retailer-lw', array("raw"=>"true"));
         		$link_az = types_render_field('retailer-amazon', array("raw"=>"true"));
         		$link_bn = types_render_field('retailer-bn', array("raw"=>"true"));
         		$link_bam = types_render_field('retailer-bam', array("raw"=>"true"));
         		$link_cbd = types_render_field('retailer-cbd', array("raw"=>"true"));
         		$link_ckbr = types_render_field('retailer-ckbr', array("raw"=>"true"));
         		$link_fam = types_render_field('retailer-family', array("raw"=>"true"));
         		$link_ib = types_render_field('retailer-ib', array("raw"=>"true"));
         		$link_mar = types_render_field('retailer-mardel', array("raw"=>"true"));
         		$link_vic = types_render_field('retailer-victory', array("raw"=>"true"));
         		$link_ber = types_render_field('retailer-berean', array("raw"=>"true"));
         		$link_wl = types_render_field('retailer-worship-life', array("raw"=>"true"));

         if($topcat == "Supplies"){ 
			if($upc && $upc!=''){
				$p_id=$upc;
			}else{
				$p_id = $isbn13;	
			}
			
			// if no lin, use lw keyword link
			
			if(!$link_lw){

				if ($lin){
					$lw_product_link = 'http://www.lifeway.com/Product/'.$lin;
				}else{
					$lw_product_link = 'http://www.lifeway.com/Keyword/'.$p_id;
				}
			}


			// default generated links
				if(!$link_ber){
					$link_ber = 'http://www.berean.com/search?keywords=' . $p_id;
				}

				if(!$link_cbd){
					$link_cbd = 'http://www.christianbook.com/Christian/Books/easy_find?event=EBRN&amp;Dn=0&amp;action=Search&amp;Ntt=' . $p_id . '&amp;D= ' . $p_id . '&amp;Nu=product%2Eendeca%5Frollup&amp;Ntk=isbn';
				}

				if(!$link_ckbr){
					$link_ckbr = 'https://www.cokesbury.com/product/' . $p_id . '/';
				}

				/*  //remove Family

				if(!$link_fam){
					$link_fam = 'http://www.familychristian.com/catalogsearch/result/?q= '. $p_id;
				} 

					 <!-- <li><a href="<?php echo $link_fam ?>" target="_blank" ><img src="/img/retailers/family.png" alt="" name="family" id="family" border="0" width="150"></a></li> -->

				*/

				if(!$link_mar){
					$link_mar = 'http://www.mardel.com/search/?text=' . $p_id;
				}

				if(!$link_vic){
					$link_vic = 'http://www.victorychurchproducts.com/SetAdvancedSearch.asp?search=1&amp;what=' . $p_id;
				}
         		
			
		?>
		<li><a href="<?php echo $lw_product_link ?>" target="_blank" ><img src="/img/retailers/lifeway.png" alt="" name="lw" id="lw" border="0" width="150"></a></li>
        <li><a href="<?php echo $link_ber ?>" target="_blank" ><img src="/img/retailers/berean.png" alt="" name="berean" id="berean" border="0" width="150"></a></li>
        <li><a href="<?php echo $link_cbd ?>" target="_blank" ><img src="/img/retailers/cbd.png" alt="" name="cbd" id="cbd" border="0" width="150"></a></li>
        <li><a href="<?php echo $link_ckbr ?>" target="_blank" ><img src="/img/retailers/cokesbury.png" alt="" name="cokesbury" id="cokesbury" border="0" width="150"></a></li>
        <li><a href="<?php echo $link_mar ?>" target="_blank" ><img src="/img/retailers/mardel.png" alt="" name="mardel" id="mardel" border="0" height="32" width="150"></a></li>
        <li><a href="<?php echo $link_vic ?>" target="_blank" ><img src="/img/retailers/victory.png" alt="" name="victory" id="victory" border="0" width="150"></a></li>
        <li align="center"><a href="http://cba.know-where.com/cba/" target="_blank"><img src="/img/retailers/cba.png" name="cba" id="cba" border="0" ></a></li>
		<?php	
		}else{ 

			// all other products, default generated links
	
			if(!$link_lw){	
         		if($lin){
         			$link_lw = 'http://www.lifeway.com/Product/'. $lin;
         		}else{
         			$link_lw = 'http://www.lifeway.com/Product/'. $isbn13;
         		}
         	}

         	if(!$link_az){
	         	if($isbn10){	
	         		$link_az = 'http://www.amazon.com/gp/product/'. $isbn10 . '?ie=UTF8&amp;tag=wwwbhpublishi-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=' . $isbn10;
	         	}
	         }

         	if(!$link_bn){	
         		$link_bn = 'http://search.barnesandnoble.com/booksearch/isbninquiry.asp?ean=' . $isbn13;
         	}

         	if(!$link_bam){	
         		$link_bam = 'http://www.booksamillion.com/product/'. $isbn13;
         	}

         	if(!$link_cbd){	
         		$link_cbd = 'http://www.christianbook.com/Christian/Books/easy_find?event=EBRN&amp;Dn=0&amp;action=Search&amp;Ntt=' . $isbn13 . '&amp;D='. $isbn13 .'&amp;Nu=product%2Eendeca%5Frollup&amp;Ntk=isbn';
         	}

         	if(!$link_ckbr){
					$link_ckbr = 'https://www.cokesbury.com/product/' . $isbn13 . '/';
				}

         	/*  //remove Family

         	]if(!$link_fam){	
         		$link_fam = 'http://www.familychristian.com/catalogsearch/result/?q=' . $isbn13;
         	}

			<li><a href="<?php echo $link_fam ?>" target="_blank" ><img src="/img/retailers/family.png" alt="" name="family" id="family" border="0" width="150"></a></li>
\
         	*/

         	if(!$link_ib){	
         		$link_ib = 'http://www.indiebound.org/book/' . $isbn13;
         	}

         	if(!$link_mar){	
         		$link_mar = 'http://www.mardel.com/search/?text=' . $isbn13;
         	}


		?>
		 <li><a target="_blank" href="<?php echo $link_lw ?>"><img width="150"  border="0" id="lw" name="lw" alt="" src="/img/retailers/lifeway.png"></a></li>
        
       <?php if($link_az) { ?>
        	<li><a target="_blank" href="<?php echo $link_az ?>"><img width="150"  border="0" id="am" name="am" src="/img/retailers/amazon.png"></a>
        	<img width="1" height="1" border="0" style="border:none !important; margin:0px !important;" alt="" src="http://www.assoc-amazon.com/e/ir?t=wwwbhpublishi-20&amp;l=as2&amp;o=1&amp;a=<?php echo $isbn10 ?>"></li>
       <?php } ?>

        <li><a target="_blank" href="<?php echo $link_bn ?>"><img width="150"  border="0" id="bn" name="bn" src="/img/retailers/bn.png"></a></li>
        <li><a target="_blank" href="<?php echo $link_bam ?>"><img width="150"  border="0" id="bam" name="bam" src="/img/retailers/bam.png"></a></li>
        <li><a target="_blank" href="<?php echo $link_cbd ?>"><img width="150"  border="0" id="cbd" name="cbd" alt="" src="/img/retailers/cbd.png"></a></li> 
         <li><a href="<?php echo $link_ckbr ?>" target="_blank" ><img src="/img/retailers/cokesbury.png" alt="" name="cokesbury" id="cokesbury" border="0" width="150"></a></li>
        <li><a target="_blank" href="<?php echo $link_ib ?>"><img width="150"  border="0" id="ib" name="ib" alt="" src="/img/retailers/indiebound.png"></a></li>
		<li><a href="<?php echo $link_mar ?>" target="_blank" ><img src="/img/retailers/mardel.png" alt="" name="mardel" id="mardel" border="0" height="32" width="150"></a></li>
		<?php if($link_wl) { ?>
		<li><a target="_blank" href="<?php echo $link_wl ?>"><img width="150"  border="0" id="bn" name="bn" src="/img/retailers/wl.png"></a></li>
		 <?php } ?>
        <li align="center"><a href="http://cba.know-where.com/cba/" target="_blank"><img src="/img/retailers/cba.png" name="cba" id="cba" border="0" ></a></li>
        <?php } ?>
        </ul></div>

<?php


//old mardel
//http://www.mardel.com/search/default.aspx?keywords=9781433688249

//digital buy now links
$kindle = types_render_field('link-kindle', array("raw"=>"true"));
$itunes = types_render_field('link-itunes', array("raw"=>"true"));
$nook = types_render_field('link-nook', array("raw"=>"true"));
$kobo = types_render_field('link-kobo', array("raw"=>"true"));
$msb = types_render_field('link-msb', array("raw"=>"true"));


if ($kindle!='' || $itunes!='' || $nook!='' || $kobo!='' || $msb!='' ):
?>
<a class="buybtn digital" href="buy.php?p=<?php echo $isbn13 ?>" target="_blank">Digital Editions</a>
         <div class="retailers">
         <ul>
  <?php if ($kindle!=''){ ?>
         	<li><a target="_blank" href="<?php _e($kindle) ?>"><img src="/img/retailers/kindle.png" alt="kindle" /></a></li>
   <?php }
   					if ($itunes!='') { ?>         
      		<li><a target="_blank" href="<?php _e($itunes) ?>"><img src="/img/retailers/ibooks.png" alt="ibooks" /></a></li>
      <?php }
  					 if ($nook!='') { ?>        
            <li><a target="_blank" href="<?php _e($nook) ?>"><img src="/img/retailers/nook.png" alt="nook" /></a></li>
    <?php }
  					 if ($kobo!='') { ?>  
   			<li><a target="_blank" href="<?php _e($kobo) ?>"><img src="/img/retailers/kobo.png" alt="kobo" /></a></li>
   <?php }
  					 if ($msb!='') { ?>  
   			<li><a target="_blank" href="<?php _e($msb) ?>">MyStudyBible</a></li>
   
   <?php } ?>        
         </ul>
         </div>

<?php
endif;

//end if supplies


//goodreads
	// link for now, tie to API next update
	// show ONLY for books category
	
 if($topcat == "Books"){ 	?>
<div class="goodreads">
<a href="http://www.goodreads.com/book/isbn/<?php _e($isbn13) ?>" target="_goodreads"><img src="<?php _e(get_stylesheet_directory_uri()) ?>/img/goodreads-badge-read-reviews.png" border="0"/></a>
</div>
<?php }//end if books ?>


</div> <?php //end left sidebar ?>

        <div id="content" class="grid col-620 fit">
        
    <?php 
	
	/*
                <?php if ( comments_open() ) : ?>               
                <div class="post-meta">
                <?php responsive_post_meta_data(); ?>
                
				    <?php if ( comments_open() ) : ?>
                        <span class="comments-link">
                        <span class="mdash">&mdash;</span>
                    <?php comments_popup_link(__('No Comments &darr;', 'responsive'), __('1 Comment &darr;', 'responsive'), __('% Comments &darr;', 'responsive')); ?>
                        </span>
                    <?php endif; ?> 
                </div><!-- end of .post-meta -->
                <?php endif; ?>
                
			*/ ?>
                <div class="post-entry">
                	<h1><?php the_title() ?></h1>
                    <?php if ($subtitle!=''){ 
                    	echo '<p class="subtitle">'.$subtitle.'</p>';
					} 
                    	//author links
						if ($author_names_linked!=''){
						echo '<div class="product-author-list">'.$author_names_linked.'</div>';	
						}
						
					
					if($showLinks){
					 ?>
                    <div class="product-social-links">
                	<ul>
					<?php 
					if ($productwebsite!=''){
						echo '<li><a class="site-link" href="'.$productwebsite.'" target="_blank">Official Site</a></li>';
					}
					if ($productfb!=''){
						echo '<li><a class="facebook-link" href="'.$productfb.'" target="_facebook">Facebook</a></li>';
					}
					//is this netgalley or an email link?
					//link to google form - try to include ISBN and title
					?>
                    <?php /* if($topcat != "Supplies" && $topcat != "Apps"){ ?>
                   <?php  if($cat_arr[1]=="Academic"){ ?>
                    <li><a class="review-link" href="http://www.bhacademic.com/request.asp" target="_blank">Request a Review Copy</a></li>
                    <?php }else{ ?>
                    <li><a class="review-link" href="/request-review-copy/?req_isbn=<?php echo $isbn13; ?>">Request a Review Copy</a></li>
					<?php }
							}  */ ?></ul>
                    </div>
                    <?php 
					}//end if showLinks
					the_content();
					//the_content(__('Read more &#8250;', 'responsive')); ?>
                    <?php //wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
           
                   <div class="product-videos">
            		<?php //this should be a plugin eventually, just add to the end of the post content
            			  
						if(function_exists('bhpub_youtube_videos')){
							bhpub_youtube_videos();	
						}else{
	
						  // ORIGINAL YouTube API v2 
						  if($isbn13){
						  if(function_exists('fetch_feed')) {  
							$youtubefeed = "http://gdata.youtube.com/feeds/api/videos/-/trailer/".$isbn13."?author=bhpublishinggroup";
							//echo '<p>'.$youtubefeed.'</p>';
							include_once(ABSPATH . WPINC . '/feed.php'); // the file to rss feed generator  
							//echo ABSPATH . WPINC . '/feed.php';
							$feed = fetch_feed($youtubefeed); // specify the rss feed  
						  
							$limit = $feed->get_item_quantity(5); // specify number of items  
							$items = $feed->get_items(0, $limit); // create an array of items  
						  
						}  
						if ($limit == '0' || $limit == 0 || !$limit):
						 echo '<!-- No videos found for product '.$isbn13.'-->';  
						else:  ?>
						<div class="section-related-videos">
                        <h4 class="mobile-section-hdr"><a href="#">Related Media <img src="<?php echo $green_arrow ?>" /></a></h4>
                        <div class="mobile-expanded">
                        <?php
						foreach ($items as $item) : 
						$yturl = explode('/',$item->get_id());
						$ytid = end($yturl);?>  
						 <iframe width="480" height="360" src="http://www.youtube.com/embed/<?php _e($ytid); ?>?rel=0" frameborder="0" allowfullscreen></iframe>
						 <?php 						
						endforeach;  
						?></div></div><?php
						endif;
						//get product rss feed from youtube
						
						//count posts
						
						//display single video
						
						//display multiple videos with switching
						  }//if isbn
				 //end YouTube API v2
						} //end if v3 plugin function
            		?>
                     
                    </div>
                    <?php $more_content = types_render_field('book-add-content',array('output'=>'html'));
					if ($more_content!=''){ ?>
                    <div class="more-content">
                    	<?php _e($more_content) ?>
                    </div>
                    <?php } 
					//automated image gallery
					if (function_exists('bh_product_images')){
						// if(strpos(site_url(),'.staging.') !== false){
						 	bh_product_images(); 
						// } //show on STAGING ONLY, remove for launch
					}
					?>
                    
                    

            
              <!-- product details 3 columns-->
              <?php
              		$pagecount = types_render_field('pagecount', array("raw"=>"true"));
					$binding = types_render_field('binding', array("raw"=>"true"));
					$status = types_render_field('print-status', array("raw"=>"true"));
	
					$weight = types_render_field('product-weight', array("raw"=>"true"));
					$height = types_render_field('product-height', array("raw"=>"true"));
					$width = types_render_field('product-width', array("raw"=>"true"));
					$length = types_render_field('product-length', array("raw"=>"true"));
					$trimsize = '';
					if ($height!='' && $width!='' && $length!=''){
					$trimsize = $length.' x '.$width.' x '.$height;
					}
					
					//downloads
					$sample_chapter = types_render_field('sample-chapter-pdf', array("raw"=>"true"));
					$tip_sheet = types_render_field('tip-sheet-pdf', array("raw"=>"true"));
					$toc = types_render_field('table-of-contents-pdf', array("raw"=>"true"));
					$foreword = types_render_field('foreword-pdf', array("raw"=>"true"));
					
					//supplies fields
					
					$scripture = types_render_field('scripture-verse',array("raw"=>"true"));
					$covercolor = types_render_field('cover-color',array("raw"=>"true"));
					$productcolor = types_render_field('product-color',array("raw"=>"true"));
					$qty = types_render_field('package-qty',array("raw"=>"true"));
					$packaging = types_render_field('packaging',array("raw"=>"true"));
					
					//apps fields
					
					
              ?>
                    <div class="product-details">
                    <h4 class="mobile-section-hdr"><a href="#">Technical Details <img src="<?php echo $green_arrow ?>" /></a></h4>
                    <div class="mobile-expanded">
                       <div class="grid col-300">
                       		<ul>
                            <?php //check if supplies, show other fields
  							 if($topcat == "Supplies"){
								 if ($isbn13!=''){
								 echo '<li><strong>ISBN: </strong>'.$isbn13.'</li>';
								 }
								if ($upc!=''){
                              		echo '<li><strong>UPC: </strong>'.$upc.'</li>';
								}
								if ($lin!=''){
									echo  '<li><strong>LIN: </strong>'.$lin.'</li>';
								}
								if ($weight!=''){
									echo '<li><strong>Weight: </strong>'.$weight.'</li>';
								}
							 }elseif($topcat == "Apps"){
								 
							 }else{
                                echo '<li><strong>ISBN: </strong>'.$isbn13.'</li>';
                                
								if ($trimsize!=''){
                              		echo '<li><strong>Trim Size: </strong>'.$trimsize.'</li>';
								}
								if ($pagecount!=''){
									echo  '<li><strong>Page Count: </strong>'.$pagecount.'</li>';
								}
								if ($weight!=''){
									echo '<li><strong>Weight: </strong>'.$weight.'</li>';
								}
							 }
								?>
                            </ul>
                       </div>
                       <div class="grid col-300">
                            <ul>
                            	<?php 
								//echo '<li>Category: ' . $topcat . '</li>';
								 if($topcat == "Supplies"){
								 if ($scripture!=''){
								 echo '<li><strong>Scripture Verse: </strong>'.$scripture.'</li>';
								 }
								if ($covercolor!=''){
                              		echo '<li><strong>Cover Color: </strong>'.$covercolor.'</li>';
								}
								if ($productcolor!=''){
									echo  '<li><strong>Product Color: </strong>'.$productcolor.'</li>';
								}
								if ($qty!=''){
									echo '<li><strong>Package Quantity: </strong>'.$qty.'</li>';
								}
								if ($packaging!=''){
									echo '<li><strong>Packaging: </strong>'.$packaging.'</li>';
								}
								 }else{
								//not supplies
								if ($binding!=''){
									echo '<li><strong>Binding: </strong>'.$binding.'</li>';
								}
								if ($status!=''){
									echo '<li><strong>Status: </strong>'.$status.'</li>';
								}
								 }
								 //always show pubdate
								if($pubdate!=''){
									echo '<li><strong>Publication Date: </strong>'.date('F Y',$pubdate).'</li>';
								} 
							
								?>
                            </ul>
                       </div>
                       <div class="grid col-300 fit">
                       		<ul>
                            <?php
							
								if ($tip_sheet!=''){
									echo '<li><a href="'.$tip_sheet.'" target="_pdf">Tip Sheet for Retailers (PDF)</a></li>';
								}
								if ($sample_chapter!=''){
									echo '<li><a href="'.$sample_chapter.'" target="_pdf">Sample Chapter (PDF)</a></li>';
								}
								if ($toc!=''){
									echo '<li><a href="'.$toc.'" target=_pdf">Table of Contents (PDF)</a></li>';
								}
								if ($foreword!=''){
									echo '<li><a href="'.$foreword.'" target="_pdf">Foreword (PDF)</a></li>';
								}
							//if Supplies, link to templates page 
 								if($topcat == "Supplies") { ?>
									<li><a href="/supplies/templates/">Download Templates</a></li>
						<?php } ?>
                            </ul>
                       </div>
                    </div>
               </div>
               
               <?php  
			   // Show Related Products - Revised 3/14/14
			   // uses custom taxonomy search instead of custom fields
				$pg=false;
				$pg = get_the_terms($post->ID,'product-group'); //check if current post is 'tagged' with a product group
			   if($pg){
			   		$pgslug = false; $pgname=false;//init
					//choose one tag other than 'featured'
						foreach($pg as $pgtag){
									//get custom tag data
									$pgslug = $pgtag->slug;
									$pgname = $pgtag->name;
									$pgterm_id = $pgtag->term_id;
									$pgtaxid = $pgtag->term_taxonomy_id;
									//echo 'slug: ' . $pgslug . '; tag: '. $pgname;
									//more here if needed
						}
					
					if($pgslug){
					// lookup products
					 $findpg=array(
			   				'post_type'=>'products',
							'product-group'=>$pgslug
			  				 );
					
					$grouped_products = new WP_query($findpg);
	
					//the loop!		
					if($grouped_products->have_posts() /*&& $grouped_products->post_count>1*/){
						   ?>
					   <div class="product-group">
                       <?php   if($topcat=='Bibles'){
						   			 		$product_group_title = "Additional Bindings";
					   				 }else{
											$product_group_title = "More in " . $pgname;
									  } ?>
                       <h3><?php echo $product_group_title; ?></h3>
                       <ul>
					   <?php
					   while ( $grouped_products->have_posts() ) :	$grouped_products->the_post();
					   // display unless isbn matches current page isbn
					 //  $p_isbn13 = get_post_meta( $post->ID , 'wpcf-isbn', true );
					 //  $p_binding = get_post_meta( $post->ID , 'wpcf-binding', true );
					 //  $p_subtitle = get_post_meta($post->ID ,'wpcf-subtitle',true);
					   
					   $p_isbn13 = types_render_field('isbn',array("raw"=>"true"));
					   $p_binding = types_render_field('binding',array("raw"=>"true"));
					   $p_subtitle = types_render_field('subtitle',array("raw"=>"true"));
					   
					   if($p_isbn13 != $isbn13):
					   //display product in list
					   //$short_title = wp_trim_words(get_the_title(),8);
					   ?>
                       			<li><?php bh_thumbnail($post->ID,'thumbnail',true) ?><a class="product-title" href="<?php the_permalink() ?>"><?php the_title(); ?> <?php echo ' - ' .$p_subtitle ?> <?php echo $p_binding ?></a> <span class="productid">(<?php echo $p_isbn13 ?>)</span></li>
                       <?php 
					   endif;
					   endwhile; ?>
					   </ul></div>
					  <?php }
					  //reset post query
					  wp_reset_postdata();

					} //end if $pgslug
			   } //end if $pg
			  
					   ?>
                </div><!-- end of .post-entry -->
                </div><!-- end of #content -->
</div>        
                
                <?php if ( comments_open() ) : ?>
                <div class="post-data">
				    <?php the_tags(__('Tagged with:', 'responsive') . ' ', ', ', '<br />'); ?> 
                    <?php the_category(__('Posted in %s', 'responsive') . ', '); ?> 
                </div><!-- end of .post-data -->
                <?php endif; ?>             
            
            <div class="post-edit"><?php edit_post_link(__('Edit', 'responsive')); ?></div> 
</div> <!-- .inner -->
</div> <!-- #wrapper -->
            <?php 
			$product_quote = types_render_field('product-quote', array("output"=>"html"));
			if ($product_quote!=''){ ?>
            <div class="product-quote">
            	<div class="inner">
                		<?php _e($product_quote); ?>
                </div>
            </div>
            <?php } ?>
          <div class="product-links">  
          	<div class="inner">
<?php
            //reset author posts loop
	if(isset($author_posts)):
			echo '<div class="product-authors"><ul>';
			$author_posts->rewind_posts();
			$acount=0;
            while($author_posts->have_posts()): $author_posts->the_post();
          $acount+=1;
		  //format results
		   if ($countauthors==1):
			//single author 
			$gridclass="col-940";  
	
		   elseif ($countauthors==2):
		  //two authors
			$gridclass="col-460";  
		  
		  //elseif ($countauthors==3):
		  else:
		  //three authors
		  	$gridclass="col-300";  	
		
		  
		  //else:
		  //more authors, use carousel to browse through posts (later, for now, limit authors to three)

		  endif;
		  	$fitclass="";
			if($countauthors==$acount){
				$fitclass=" fit";	
			}
		   ?> 
           <li class="grid <?php _e($gridclass.$fitclass) ?>">
           <?php
		   //show author thumbnail
		   
		   //custom bh thumbnails
		   ?>
		   <div class="author-thumb">
           <a class="author-thumb-link" href="<?php the_permalink(); ?>">
           <?php bh_thumbnail(get_the_ID(),'thumbnail',false, 'author-thumb-img'); ?>
           </a></div>
           <div class="author-bio">
           <h4><span class="mobile-author">author<br/></span><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
		   <p><?php the_excerpt(); ?></p>
           <p><a href="<?php the_permalink() ?>">full biography &#8594;</a></p>
           </div>
           </li>
            <?php
			
			endwhile; 
			echo '</ul></div>';
			wp_reset_query();
			//wp_reset_postdata();
			
	endif; //if author posts ?>
           
                <?php 
				//update - check related books custom field for isbns, list those posts
				$catslugs=array();$catids=array();
				$category = get_the_category();
				foreach($category as $cat){
					array_push($catslugs,$cat->slug);	
					array_push($catids,$cat->term_id);
				}
	
				//get list of books in the same category and/or by the same author
				
				//August 1 2014: split into two lookups and then combined as a single array of ids
				//same author(s)
				$abooks = array(
												'post_type'=>'products',
												'post_status'=>'publish',
												'posts_per_page'=>'40',
												'tag'=>'listed',
												'orderby'=>'meta_value_num',
												'meta_key'=>'wpcf-pubdate',
												'fields'=>'ids',
												'tax_query'=>array(
																					array(
																						'taxonomy'=>'a',
																						'field'=>'slug',
																						'terms'=>$arrAuthors //must be array	- from line 136
																					)
																				)
											);	
											
				//same category(s)
				$cbooks = array(
												'post_type'=>'products',
												'post_status'=>'publish',
												'posts_per_page'=>'40',
												'tag'=>'listed',
												'orderby'=>'meta_value_num',
												'meta_key'=>'wpcf-pubdate',
												'fields'=>'ids',
												'category__in'=>$catids
											);											
				
				$a_ids = get_posts($abooks);
				$c_ids = get_posts($cbooks);
				
				$post_ids = array_merge( $a_ids, $c_ids);
				
				//final array - show only the most recent 25 either by author or in category
				 $rbooks = array(
				 								'post_type'=>'products',
												'post__in'  => $post_ids, 
												'orderby'=>'meta_value_num',
												'meta_key'=>'wpcf-pubdate',
												'posts_per_page'=>'25'
				 );
		
				
				$relatedbooks = new WP_query($rbooks);	
				if($relatedbooks->have_posts() && $relatedbooks->post_count>1): 
				?>
				 <div class="product-related">
                <h4>You may also be interested in...</h4>
                <ul class="booklist">
				
				<?php
				$loop_count = 0;
				while($relatedbooks->have_posts()): $relatedbooks->the_post();
					if ($post->ID!=$thispost){ //hide the current product from results	
						$product_title =  get_the_title(); //init
					    $pg_names = array(); //init	   
			  			 $short_title = wp_trim_words($product_title,8);
						?>
						<li>
							<?php 
							bh_thumbnail($post->ID,'medium',true);
							 ?>
							<br/><a class="booktitle" href="<?php the_permalink(); ?>"><?php echo $short_title; ?></a>
						</li>
						<?php
	
				} // if not current product
					endwhile;
				?>
				</ul>
            	</div>
				<?php
				endif;
				wp_reset_query();
				wp_reset_postdata();
				?>
                
            </div><!-- .inner -->
            </div><!-- .product-links -->
            <!-- end of #post-<?php the_ID(); ?> -->
            
            <?php comments_template( '', true ); ?>
            
        <?php endwhile; ?> 
        
	    <?php else : ?>

      <!--   <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'responsive'); ?></h1> -->
                    
        <p><?php _e('Product not found.', 'responsive'); ?></p>
                    
        <h6><?php printf( __('You can return %s or search for the page you were looking for.', 'responsive'),
	            sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
		            esc_url( get_home_url() ),
		            esc_attr__('Home', 'responsive'),
		            esc_attr__('&larr; Home', 'responsive')
	                )); 
			 ?></h6>
                    
        <?php get_search_form(); ?>


      

<?php endif; 

 ?> 

        </div><!-- end of #content -->
</div> 
<div class="newsletter-form-footer grid col-940">
			<div class="inner">
					<div class="grid col-380">	
					<h3>Sign up for our Newsletter</h3>
                    <p class="desktop-newsletter-text"></p>
                    </div>
                    <div class="grid col-540 fit">
                   <?php  bhpub_mailchimp_embed(); ?>               
                    </div>
					<div class="mobile-newsletter-text"><p>*We'll never share your information with others.</p></div>
			</div>
		<!-- </div> I think this was the old #wrapper-->
<?php 
} // end bh single product post loop
//<iframe src="http://www2.bhpublishinggroup.com/bhpub_newsletters.asp" width="100%"></iframe> // old yesmail 
//get_footer(); 
function bh_product_page_append_script(){
	if(!$bh_fb_app_id){
	$bh_fb_app_id='264893110213933';
	}
?>
<script>
// enable jCarousel 

jQuery(document).ready(function() {
	//buy now button
	jQuery('a.buybtn').not('.apps').click(function(e){
	//jQuery(this).next('.retailers').slideToggle();
	e.preventDefault();
	});
	
	//mobile expanding headers
	jQuery('.mobile-section-hdr a').not('.apps').click(function(e){
		jQuery(this).parents('.mobile-section-hdr').toggleClass("open").next('.mobile-expanded').slideToggle();
		e.preventDefault();
	});
	
	//jQuery(".mobile-expanded").hide();
	
	// initialize multiple carousels by class
	jQuery('ul.booklist').jcarousel();
	
	
	// ==  Twitter Tracking Buttons == //
	var e = document.createElement('script');
	e.type="text/javascript"; e.async = true;
	e.src = 'http://platform.twitter.com/widgets.js';
	document.getElementsByTagName('head')[0].appendChild(e);
	jQuery(e).load(function() {
	  function tweetIntentToAnalytics(intent_event) {
	    if (intent_event) {
	      var label = intent_event.data.tweet_id;
	      _gaq.push(['_trackEvent', 'twitter_web_intents',
	      intent_event.type, label]);
	    }
	  }
	  function followIntentToAnalytics(intent_event) {
	    if (intent_event) {
	      var label = intent_event.data.user_id + " (" +
	     intent_event.data.screen_name + ")";
	      _gaq.push(['_trackEvent', 'twitter_web_intents',
	     intent_event.type, label]);
	    }
	  }
	  twttr.events.bind('tweet',    tweetIntentToAnalytics);
	  twttr.events.bind('follow',   followIntentToAnalytics);
	});
	
	// load other social buttons scripts
	 // == Simple Buttons == //
	 var e = document.createElement('script');
	 e.type="text/javascript"; e.async = true;
	 e.src = 'http://assets.pinterest.com/js/pinit.js';
	 document.getElementsByTagName('head')[0].appendChild(e);
	 
	/*
	// Load Tweet Button Script
	 var e = document.createElement('script');
	 e.type="text/javascript"; e.async = true;
	 e.src = 'http://platform.twitter.com/widgets.js';
	 document.getElementsByTagName('head')[0].appendChild(e);
	 
	 // Load Plus One Button 
	var e = document.createElement('script');
	e.type="text/javascript"; e.async = true;
  	e.src = 'https://apis.google.com/js/plusone.js';
  	document.getElementsByTagName('head')[0].appendChild(e);
	*/
		
});

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $bh_fb_app_id ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

/*
// Facebook JS w/tracking
window.fbAsyncInit = function() {
	  FB.init({appId: '<?php echo $bh_fb_app_id ?>', status: true, cookie: true, xfbml: true});
	  FB.Event.subscribe("edge.create",function(response) {
	    if (response.indexOf("facebook.com") > 0) {
	      // if the returned link contains 'facebook,com'. It is a 'Like'
	      // for your Facebook page
	      _gaq.push(['_trackEvent','Facebook','Like',response]);
	    } else {
	      // else, somebody is sharing the current page on their wall
	      _gaq.push(['_trackEvent','Facebook','Share',response]);
	    }
	  });
	  FB.Event.subscribe("message.send",function(response){
	    _gaq.push(['_trackEvent','Facebook','Send',response]);
	  });
	};
*/

</script>
<?php
} // end bh_product_page_append_script

function bh_add_fb_root(){
	echo ('<div id="fb-root"></div>');
}
add_action('genesis_after_header','bh_add_fb_root');
add_action( 'genesis_after_footer', 'bh_product_page_append_script' );

function archived_product_loop_queries(){
				$exclude_cats = array();$cat_not_in = false;
				//if not bibles, exclude bibles
				$bible_child_cats = (array) get_term_children('3','category');
				$spanish_child_cats = (array) get_term_children('16','category');
					
				if(count(array_intersect($catids,$bible_child_cats))===0){
					$exclude_cats = array_merge($exclude_cats,array('3'),$bible_child_cats);	
					$cat_not_in = true;
				}
				//if not spanish, exclude spanish						
				if(count(array_intersect($catids,$spanish_child_cats))===0){
					$exclude_cats = array_merge($exclude_cats, array('15','16','17','18'),$spanish_child_cats);	
					$cat_not_in = true;
				}
				if($cat_not_in){	
					//$rbooks['category__not_in']=$exclude_cats;
				}
	
				/*
				print_r($catslugs);
				echo '<hr/>';
				print_r($catids);
				echo '<hr/>';
				print_r($arrAuthors);
				echo '<hr/>';
				*/
	
	// replaced by $wpdb query below
				
				// May 8 2014: Replaced $wpdb query with new system. 
				// Only items tagged "Listed" are displayed. Product Groups are only for display, not query.
				/*
                $rbooks = array(
					'post_type'=>'products',
					'posts_per_page'=>'40',
					'tag'=>'listed',
					'orderby'=>'meta_value_num',
					'meta_key'=>'wpcf-pubdate',
	
					'tax_query'=>array(
												'relation'=>'OR',
												array(
													'taxonomy'=>'a',
													'field'=>'slug',
													'terms'=>$arrAuthors //must be array	- from line 136
												),
												array(
													'taxonomy'=>'category',
													'field'=>'slug',
													'terms'=>end($catslugs) //must be array - from above		
												)
											)
										);
					
				*//*
				print_r($a_ids);
				echo '<hr/>';
				print_r($c_ids);
				echo '<hr/>';
				*/
				/*
							//insert backup queries here
				//echo '#posts: ' . $relatedbooks->post_count;		
				//if($dbposts):
							//$max_count = ($relatedbooks->post_count < 20 ? $relatedbooks->post_count : 20);

				//foreach($dbposts as $post){
							
				//setup_postdata($post);
								//check count
				//	if($loop_count<=$max_count){
					//check for 'featured' or null product-group taxonomy
			//		$product_terms = wp_get_object_terms($post->ID, 'product-group');
			//		$showpost = true;
			//		if(!empty($product_terms)){
			//			$showpost = false;
				//		foreach($product_terms as $term) {
				//			if($term->slug == 'featured'){
				//				$showpost = true;
				//			}
				//		}
				//	}
				//	if ($showpost == true){
							   
		//	   if (has_term('primary','post_tag')){
		//		   $pg_names = wp_get_post_terms($post->ID,'product-group',array('fields'=>'names'));
		//		   if (!empty($pg_names)){
		//			$product_title = reset($pg_names);//change to product group title
		//		   }
			//	   $pg_names = array(); //re-initalize array
		//	   }
									//$loop_count ++;
			//			} // if showpost = true
			//		} //if loop count <=20
			//			}
				
				*/
				//navigation
				/*
		global $wp_query;
		if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'responsive' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'responsive' ) ); ?></div>
		</div><!-- end of .navigation -->
        <?php endif; 
		*/
}
genesis();
?>