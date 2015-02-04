<?PHP
/*
 * Plugin Name: Growdev MailChimp Image Width
 * Plugin URI: http://daniel.gd
 * Description: Fix image widths for posts sent out via RSS to MailChimp
 * Version: 0.1.0
 * Author: Daniel Espinoza
 * Author URI: http://daniel.gd
 * 
 * Hat Tip to CSS Tricks http://css-tricks.com/dealing-content-images-email/
 */


function growdev_super_awesome_feed_image_magic($content) {
  // Weirdness we need to add to strip the doctype with later.
  $content = '<div>' . $content . '</div>';
  libxml_use_internal_errors(true);
  $doc = new DOMDocument('1.0', 'UTF-8');
  $doc->loadHTML('<?xml encoding="UTF-8">' . $content);

  $images = $doc->getElementsByTagName('img');
  foreach ($images as $image) {
    $image->removeAttribute('height');
    $image->setAttribute('width', '500');
  }
  // Strip weird DOCTYPE that DOMDocument() adds in
  $content = substr($doc->saveXML($doc->getElementsByTagName('div')->item(0)), 5, -6);
  return $content;
}



function get_me_the_email_feed_template() {
  add_filter('the_content_feed', 'growdev_super_awesome_feed_image_magic');
  include(ABSPATH . '/wp-includes/feed-rss2.php' );
}


function growdev_custom_feeds() {
  add_feed('email', 'get_me_the_email_feed_template');
}
add_action('init', 'growdev_custom_feeds');


