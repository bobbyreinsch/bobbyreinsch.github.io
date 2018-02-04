# bobbyreinsch.github.io

## Wordpress code samples

* Custom Map Plugin *(wordpress/plugins/lw-custom-map/)*
drag and drop city placement in WP Admin
results stored in custom fields
in Production: http://accessthenations.com/
tech example: https://codepen.io/breinsch/pen/YXbZVK

* Product Gallery Plugin *(wordpress/plugins/bh-product-gallery/)*
detect image files in folder by product ID
load gallery to product page via AJAX
in Production: http://www.bhpublishinggroup.com/products/kjv-large-print-personal-size-reference-bible-brown-leathertouch

* Kodak Winning Fotos Blog *(wordpress/wf-blog/)*
  * responsive images + flexbox
  * background-image ajax fix for object-fit in IE
  * shared login with kodakwinningfotos.com using JS window.postMessage
  * JSON service providing recent and relevant article links for website and app
  * in Production: https://blog.kodakwinningfotos.com/
  * *files: styles.css, functions.php, template-relevant-posts.php, template-recent-posts.php*

* WinningFotos *(wordpress/wf20/)*
  * responsive animated infographic using ScrollMagic library
  * text editable and stored in custom fields
  * in Production: https://winningfotos.com/how-it-works/
  * *files: styles.css, functions.php, js/infographic.js*

* B&H Publishing Group *(wordpress/bhpub/)*
  * product catalog site using custom post types/custom taxonomy/custom fields
  * retrofitted completed site for Genesis Framework as required by IT
  * uses APIs for product images, product data upon post creation
  * product pages default to generated links, but can be customized
  * product grouping for similar titles
  * in Production: http://www.bhpublishinggroup.com/
  * *files: functions.php, front-page.php, single/single-products.php*
  
