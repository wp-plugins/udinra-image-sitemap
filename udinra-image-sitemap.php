<?php
/*
Plugin Name: Udinra Image Sitemap for Britepic
Plugin URI: http://udinra.com/blog/britepic-image-sitemap-wordpress-plugin
Description: The plugin generates a XML Image Sitemap from the images powered by Britepic technology of Adbrite.
Author: <a href="http://udinra.com">Udinra</a>
Version: 3.2
Author URI: http://udinra.com/
*/

add_action ('admin_menu', 'image_sitemap_generate_page');

function image_sitemap_generate_page () {
	if (function_exists ('add_submenu_page'))
    	add_submenu_page ('tools.php', __('Udinra Image Sitemap'), __('Udinra Image Sitemap'),
        	'manage_options', 'image-sitemap-generate-page', 'image_sitemap_generate');
}

	/**
	 * Checks if a file is writable.
	 *
	 * @since 3.05b
	 * @access private
	 * @author  VJTD3 <http://www.VJTD3.com>
	 * @return bool true if writable
	 */
	function IsImageSitemapWritable($filename) {
		//can we write?
		if(!is_writable($filename)) {
			//no we can't.
			return false;
		}
		//we can write, return 1/true/happy dance.
		return true;
	}

function image_sitemap_generate () {

	if ($_POST ['submit']) {
		$st = image_sitemap_loop ();
		if (!$st) {
echo '<br /><div class="error"><h2>Oops!</h2><p>The XML sitemap was generated successfully but the  plugin was unable to save the xml to your WordPress root folder at <strong>' . $_SERVER["DOCUMENT_ROOT"] . '</strong>.</p><p>Please create a blank file sitemap-image.xml with write permissions 666 in the root directory of your wordpress installation';
exit();
}

?>

<div class="wrap">
<h2>Udinra Image Sitemap for Britepic</h2>
<?php $sitemapurl = get_bloginfo('url') . "/sitemap-image.xml"; ?>
<p>The XML Sitemap was generated successfully. Please open the <a target="_blank" href="<?php echo $sitemapurl; ?>">Sitemap file</a> in your favorite web browser to confirm that there are no errors.</p>
<h3>Suggestions?</h3>
<p>Please email your suggestions to Udinra at http://udinra.com/contact-me</p>
<p>Are You Making Money With Your Images then Donate Us a small share.</p>
<p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">  
 <input type="hidden" name="business" value="pitaji@udinra.com">  
 <input type="hidden" name="cmd" value="_donations">  
 <input type="hidden" name="item_name" value="udinra">  
 <input type="hidden" name="item_number" value="Udinra Image Sitemap plugin">  
 <input type="hidden" name="currency_code" value="USD">  
 <input type="image" name="submit" border="0" 
        src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif"  
        alt="PayPal - The safer, easier way to pay online">  
 <img alt="" border="0" width="1" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif" >  
</form>
</p>
<?php } else { ?>
<div class="wrap">
  <h2>Udinra Image Sitemap for Britepic</h2>
  <p>Britepic is technology from Adbrite.It helps you advertise on your images.Creating image sitemap will allow search engines find your images and make money using Britepic.</p>
  <h4>Create blank file sitemap-image.xml in the root directory of your Wordpress installation.</h4>
  <h3>Create Image Sitemap</h3>
  <form id="options_form" method="post" action="">
    <div class="submit">
      <input type="submit" name="submit" id="sb_submit" value="Generate Image Sitemap" />
    </div>
  </form>
  <p>You can click the button above to generate a Image Sitemap for your website. Once you have created your Sitemap, you should submit it to Google using Webmaster Tools. </p>
  <h3>Suggestions?</h3>
  <p>Please email your suggestions to Udinra at http://udinra.com/contact-me.</p>
  <p>Are You Making Money With Your Images then Donate Us a small share.</p>
<p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">  
 <input type="hidden" name="business" value="pitaji@udinra.com">  
 <input type="hidden" name="cmd" value="_donations">  
 <input type="hidden" name="item_name" value="udinra">  
 <input type="hidden" name="item_number" value="Udinra Image Sitemap plugin">  
 <input type="hidden" name="currency_code" value="USD">  
 <input type="image" name="submit" border="0" 
        src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif"  
        alt="PayPal - The safer, easier way to pay online">  
 <img alt="" border="0" width="1" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif" >  
</form>
</p>
</div>
<?php	}
}

function image_sitemap_loop () {
	global $wpdb;

	$posts = $wpdb->get_results ("SELECT id, post_content FROM $wpdb->posts 
							WHERE post_status = 'publish' 
							AND (post_type = 'post' OR post_type = 'page')
							ORDER BY post_date");

	if (empty ($posts)) {
		return false;

	} else {
		$xml   = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		$xml  .= '<!-- Generated-by = Udinra Image Sitemap (http://udinra.com) -->' . "\n";
		$xml  .= '<!-- Generated-on="' . date("F j, Y, g:i a") .'" -->' . "\n";		     
		$xml  .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
		
		foreach ($posts as $post) { 
			if (preg_match_all ("/britepic_src=[\'\"](http:\/\/.[^\'\"]+\.(?:jpe?g|png|gif))[\'\"]/ui", 
				$post->post_content, $matches, PREG_SET_ORDER)) {
				$i = 1;$k=1;
				$i = 1;$k=1;
				$loc = array();
				$loc1 = array();
				$loc2 = array();

					$permalink = get_permalink($post->id); 
					$xml .= "<url>\n";
					$xml .= " <loc>$permalink</loc>\n";
				$ret_code =	preg_match_all ("/britepic_keywords=[\'\"](.*?)[\'\"]/ui", 
				$post->post_content, $matches1, PREG_SET_ORDER);
				$ret_code = preg_match_all ("/britepic_caption=[\'\"](.*?)[\'\"]/ui", 
				$post->post_content, $matches2, PREG_SET_ORDER);

					foreach ($matches as $match) {
						$loc[$i] = $match[1]; $i = $i + 1;}
						$i = 1;
					foreach ($matches1 as $match1) {
						$loc1[$i] = $match1[1]; $i = $i + 1;}
						$i = 1;
					foreach ($matches2 as $match2) {
						$loc2[$i] = $match2[1]; $i = $i + 1;}
					while($k < $i) {
					 
  					 $xml .= " <image:image>\n";
					 $xml .= "  <image:loc>$loc[$k]</image:loc>\n";
					 $xml .= "  <image:caption>$loc1[$k]</image:caption>\n";
					 $xml .= "  <image:title>$loc2[$k]</image:title>\n";
					 $xml .= " </image:image>\n"; 
					 $k = $k + 1;		}			
					$xml .= "</url>\n";}
			}
	
		$xml .= "\n</urlset>";
	}

	$image_sitemap_url = $_SERVER["DOCUMENT_ROOT"] . '/sitemap-image.xml';
	if (IsImageSitemapWritable($_SERVER["DOCUMENT_ROOT"]) || IsImageSitemapWritable($image_sitemap_url)) {
		if (file_put_contents ($image_sitemap_url, $xml)) {
			return true; }
			else { return false;}
		}
	}
 }
?>
