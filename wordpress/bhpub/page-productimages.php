<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * IMAGE TEST
 */
?>
<?php // get_header(); ?>


<?php 
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action('genesis_sidebar','genesis_do_sidebar');
//add_action( 'genesis_loop', 'bh_home_features_loop' );
add_action('genesis_loop','bh_drafts_loop');

function bh_drafts_loop(){
	$statuses = array('draft');
	foreach($statuses as $status){
	
	$drafts = array(
											'post_type'=>'products',
											'post_status' => $status,
											'posts_per_page'=>'-1',
											'meta_key' => 'wpcf-pubdate',
											'orderby' => 'meta_value_num',
											'order'=>'DESC',

	);
	$plist = new WP_query($drafts);
		
?>
<div class="inner">
 
 <h2><?php echo $status; ?></h2>
    <div class="products-list">
		<?php if($plist->have_posts()){
				echo '<ul>';
				$pgcount = 0;
				while($plist->have_posts()): $plist->the_post(); 
							$postID = get_the_ID();
							?>
                <li id="product-<?php _e($postID) ?>" <?php post_class(); ?>>
                <?php $status = types_render_field('print-status', array("raw"=>"true")); 
				$isbn = types_render_field('isbn',array('format'=>'raw')); 
				$lin = types_render_field('lin',array('format'=>'raw')); 
                $upc = types_render_field('upc',array('format'=>'raw'));
				$pubdate = types_render_field('pubdate',array('format'=>'raw')); ?>
                <div class="product-images">
                <span>THUMB<br /><?php bh_thumbnail($postID,'thumbnail',true);	 ?> </span> <?php
				 	if($isbn){
					//search webcovers images folder for ISBN
					foreach(glob('img/webcovers/*'.$isbn.'*.jpg') as $webcover){
						//display image alt filename
						echo '<span>BH ISBN<br /><img src="/'.$webcover.'" alt="'.$webcover.'" height="90" /></span>';
					}}
				 
				 if($upc): ?>
               <span>S7 UPC<br /><img src="https://s7d9.scene7.com/is/image/LifeWayChristianResources/<?php echo $upc; ?>?hei=125" height="90" /></span>  
				<?php
				// search supplies images folder for UPC
				foreach(glob('img/supplies/*/*'.$upc.'*.jpg') as $upcimg){
					//display image alt filename
						echo '<span>BH UPC<br /><img src="/'.$upcimg.'" alt="'.$upcimg.'" height="90" /></span>';
				}
				
				endif;
				if($lin){
					echo '<span>S7 LIN<br /><img src="https://s7d9.scene7.com/is/image/LifeWayChristianResources/'.$lin.'" height="90" alt="LIN: '.$lin.'" /></span>';	
				}
				if($isbn){
					echo '<span>S7 ISBN<br /><img src="https://s7d9.scene7.com/is/image/LifeWayChristianResources/'.$isbn.'" height="90" alt="ISBN: '.$isbn.'" /></span>';

					//search supplies images folder for ISBN
					foreach(glob('img/supplies/*/*'.$isbn.'*.jpg') as $isbnimg){
						//display image alt filename
						echo '<span>SUP ISBN<br /><img src="/'.$isbnimg.'" alt="'.$isbnimg.'" height="90" /></span>';
					}
					
					
				}
				?></div>
				<?php echo '<strong>'. get_the_title(). '</strong>'; echo ' || ' . $status;  ?>
				<?php if($isbn){echo '<br/>ISBN: '.$isbn.' ';} if($upc){echo '<br/>UPC: '.$upc. ' ';} if($lin){echo '<br/>LIN: '.$lin. ' ';} if($pubdate){echo '<br/>Pub: '.$pubdate. ' ';} echo '<br/><br/>'; ?>
                <?php edit_post_link(__('Edit', 'responsive')); ?> <a href="<?php the_permalink(); ?>">View Page</a>
               	<?php 
                //modify product list display
				
				/*  //author/subtitle info. don't need for this page
				//initialize vars
				$p_subtitle='';
				$a_terms='';
				$a_list='';
				$a_terms='';
							//if title is too long, don't show subtitle/authors
							if(strlen(get_the_title())<32){
								//get current category or parent category
									$this_category = get_the_category($postID);
									$current_cat = $this_category[0]->slug;

							//ge	t subtitle
							$p_subtitle =  types_render_field('subtitle', array("raw"=>"true"));
								
							//if Bibles or Reference, show subtitle
							//echo $current_cat;
							if($current_cat =='biblias' || $current_cat == 'referencia' ):
							?>
                            <span class="author-names"><?php echo $p_subtitle ?></span>
                            <?php	
							else:
							//else show author(s)	
							//get author name(s)
							$a_terms = wp_get_post_terms($postID, 'a', array("fields" => "names"));
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
							*/ ?>
                            </li>
                <?php 
			
				endwhile;
			//	} //end foreach;
				echo '</ul>';
				echo '<hr></hr>';
				wp_reset_postdata();
		} ?>
        
        <?php 
		// paginated published pages by category
		//choose category
		
		//list published posts w/pagination
			
		?>
        
        
    </div></div>
</div>
<style>
.products-list img {
	width:50px;
	min-width:none;	
}

.products-list li {
	position:relative;
	clear:both;	
	display:block;
	margin-bottom:.5em;
	padding-bottom:.5em;
	border-bottom:1px solid #dedede;
}

.products-list .product-images {
	display:block;
	float:left;
	margin:.1em;	
}

.products-list .product-images span {
	font-size:.6em;
	color:black;
	display:block;
	float:left;		
	padding:.2em;
	margin:.1em;
	margin-bottom:1em;
	padding-bottom:1em;
	background:#FFC;
}


</style>
<?php
wp_reset_postdata();
wp_reset_query();
}//end foreach
}//end function
 genesis(); 
   ?>
<?php // get_footer(); ?>