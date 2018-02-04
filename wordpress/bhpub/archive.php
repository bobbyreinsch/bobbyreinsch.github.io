<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Archive Template
 *
 *
 * @file           archive.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2013 ThemeID
 * @license        license.txt
 * @version        Release: 1.1
 * @filesource     wp-content/themes/responsive/archive.php
 * @link           http://codex.wordpress.org/Theme_Development#Archive_.28archive.php.29
 * @since          available since Release 1.0
 */
?>
<?php get_header(); ?>

<div class="inner">
	<div class="grid col-220">
		<?php //category list goes here ?>
		
	</div>
	<div class="grid col-700 fit">

			<?php //$options = get_option('responsive_theme_options'); ?>
			<?php // if ($options['breadcrumb'] == 0): ?>
			<?php //echo responsive_breadcrumb_lists(); ?>
			<?php //endif; ?>

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
        <?php /*
		    <h6>
			    <?php if ( is_day() ) : ?>
				    <?php printf( __( 'Daily Archives: %s', 'minimum' ), '<span>' . get_the_date() . '</span>' ); ?>
				<?php elseif ( is_month() ) : ?>
					<?php printf( __( 'Monthly Archives: %s', 'minimum' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
				<?php elseif ( is_year() ) : ?>
					<?php printf( __( 'Yearly Archives: %s', 'minimum' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
				<?php else : ?>
					<?php _e( 'Blog Archives', 'minimum' ); ?>
				<?php endif; ?>
			</h6>
             */ ?>       
        
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__( 'Permanent Link to %s', 'minimum' ), the_title_attribute( 'echo=0' )); ?>"><?php the_title(); ?></a></h1>
                
                <div class="post-meta">
                <?php //responsive_post_meta_data(); ?>
                
				    <?php if ( comments_open() ) : ?>
                        <span class="comments-link">
                        <span class="mdash">&mdash;</span>
                    <?php comments_popup_link(__('No Comments &darr;', 'minimum'), __('1 Comment &darr;', 'minimum'), __('% Comments &darr;', 'minimum')); ?>
                        </span>
                    <?php endif; ?> 
                </div><!-- end of .post-meta -->
                
                <div class="post-entry">
                    <?php /* if ( has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                    <?php the_post_thumbnail('thumbnail', array('class' => 'alignleft')); ?>
                        </a>
                    <?php endif; */?>
                    <div class="post-thumbnail"><?php 
					//check for post-type or author
					$curr_post_type = $post->post_type;
					if($curr_post_type== "bhauthor" || $curr_post_type=="products"):
						bh_thumbnail(get_the_ID(),'medium',true);	
					else:
						if(has_post_thumbnail()){the_post_thumbnail('medium');}
					endif;
					?></div>
                    <?php the_excerpt(); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'minimum'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
                <div class="post-data">
				    <?php the_tags(__('Tagged with:', 'minimum') . ' ', ', ', '<br />'); ?> 
					<?php printf(__('Posted in %s', 'minimum'), get_the_category_list(', ')); ?>
                </div><!-- end of .post-data -->             

            <div class="post-edit"><?php edit_post_link(__('Edit', 'minimum')); ?></div>             
            </div><!-- end of #post-<?php the_ID(); ?> -->
            
            <?php comments_template( '', true ); ?>
            
        <?php endwhile; ?> 
        
        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'minimum' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'minimum' ) ); ?></div>
		</div><!-- end of .navigation -->
        <?php endif; ?>

	    <?php else : ?>

        <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'minimum'); ?></h1>
                    
        <p><?php _e('Don&#39;t panic, we&#39;ll get through this together. Let&#39;s explore our options here.', 'minimum'); ?></p>
                    
        <h6><?php printf( __('You can return %s or search for the page you were looking for.', 'minimum'),
	            sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
		            esc_url( get_home_url() ),
		            esc_attr__('Home', 'minimum'),
		            esc_attr__('&larr; Home', 'minimum')
	                )); 
			 ?></h6>
                    
        <?php get_search_form(); ?>

<?php endif; ?>  
      
        </div><!-- end of #content-archive -->
 </div>       
<?php //get_sidebar(); ?>
<?php get_footer(); ?>
