<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Information Sidebar
 *
 *
 * @file           sidebar-information.php
 */
?>
<h2>Information</h2>
<?php
$info = array(
							'menu' => 'Information Links',
							'menu_class' => 'info'
);

//wp_nav_menu($info);
// not returning current page

// Trade FAQ = 7653
// Consumer FAQ = 7652
// Privacy Policy = 9942
// Terms = 9943

$infopages = array(
									'include'=>'147,22,24,7653,7652,11528,9942,9943',
									'sort_column'=>'menu_order',
									'title_li'=>__('')
									
									);

wp_list_pages($infopages);




?>