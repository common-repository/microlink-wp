<?php

/**
 * Microlink shortcodes.
 *
 * @link       https://microlink.io/
 * @since      1.0.0
 *
 * @package    Microlink
 * @subpackage Microlink/includes
 */

/**
 * Microlink shortcodes.
 *
 * This class defines all code necessary to run the Microlink shortcodes.
 *
 * @since      1.0.0
 * @package    Microlink
 * @subpackage Microlink/includes
 * @author     Jon Torrado <jontorrado@gmail.com>
 */
class Microlink_Shortcodes {

	/**
	 * Shortcode stuff.
	 *
	 * @since    1.0.0
	 */
	public function doShortcode( $atts, $content = null) {
	    $link = isset($atts['href']) ? $atts['href'] : null;
	    if (empty($link)) {
	        return $atts;
        }

        $apiKey = get_option('microlink_api_key');
	    if (!empty($apiKey)) {
            $atts['apiKey'] = $apiKey;
        }

        $dataAttributes = '';
	    unset($atts['href']);
	    foreach ($atts as $key => $value) {
	        $dataAttributes .= ' data-'. $key .'="' . $value . '"';
        }

        return '<a href="' . $link .'" class="microlink" '. $dataAttributes .'>' . $link .'</a>';
	}

}
