<?php
/*
Plugin Name: YouTube Chromeless
Version: 1.1
Author: CyberSEO.net
Author URI: http://www.cyberseo.net/
Plugin URI: http://www.cyberseo.net/youtube-chromeless/
Description: The YouTube Chromeless plugin allows one to play YouTube videos in the Chromless player. Use the following tag style to insert the YouTube videos into your posts: <strong>[youtube:url width]</strong>. Where <strong>url</strong> - URL of the YouTube video you want to embed; <strong>width</strong> - width of the chromeless player (height is being calculated automatically). YouTube Chromeless is based on the <a href="http://tutorialzine.com/2010/07/youtube-api-custom-player-jquery-css/">Custom YouTube Video Player</a> tutorial by TutorialZine.com.
*/

define ( 'YTCRLS_DEFAULT_WIDTH', '600' );

if (! function_exists ( "get_option" ) || ! function_exists ( "add_filter" )) {
	die ();
}

$ytcrls_id = 0;

function ytcrlsInsert($string) {
	global $ytcrls_id;
	@list ( $url, $width ) = explode ( " ", str_replace ( '  ', ' ', $string ) );
	if (! isset ( $width ) || $width == "0") {
		$width = YTCRLS_DEFAULT_WIDTH;
	}
	$ytcrls_id ++;
	return "<div id='ytcrls_player$ytcrls_id'></div><script type='text/javascript'>$('#ytcrls_player$ytcrls_id').youTubeEmbed({video:'$url',width:$width,progressBar:true});</script>";
}

function ytcrlsContent($content) {
	$content = preg_replace ( "'\[youtube:(.*?)\]'ie", "stripslashes(ytcrlsInsert('\\1'))", $content );
	return $content;
}

function ytcrlsHead() {
	$dir = get_option ( 'siteurl' ) . "/wp-content/plugins/" . dirname ( plugin_basename ( __FILE__ ) );
	echo '<link rel="stylesheet" type="text/css" href="' . $dir . '/youTubeEmbed/youTubeEmbed-jquery-1.0.css" />' . "\n";
	echo '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>' . "\n";
	echo '<script src="' . $dir . '/jquery.swfobject.1-1-1.min.js"></script>' . "\n";
	echo '<script src="' . $dir . '/youTubeEmbed/youTubeEmbed-jquery-1.0.js"></script>' . "\n";
}

if (! is_admin ()) {
	add_action ( 'wp_head', 'ytcrlsHead' );
	add_filter ( 'the_content', 'ytcrlsContent' );
	add_filter ( 'the_excerpt', 'ytcrlsContent' );
}
?>