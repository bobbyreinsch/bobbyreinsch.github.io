<?php

// Exit if accessed directly
 if ( !defined('ABSPATH')) exit;

/**
 * Redirect real 404s to www2.bhpublishinggroup.com (old site)
 *
 *
 * @file           404.php
 * @author     Bobby Reinsch - BH Publishing Group
 * @version    Release: 1.0

 */
$URL = $_SERVER['REQUEST_URI'];
//echo $URL;
//echo "<br/>http://www2.bhpublishinggroup.com" . $URL;
header("HTTP/1.1 301 Moved Permanently");
header("Location: http://www2.bhpublishinggroup.com".$URL);
exit();
?>