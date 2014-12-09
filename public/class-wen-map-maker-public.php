<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wen_map_maker
 * @subpackage wen_map_maker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    wen_map_maker
 * @subpackage wen_map_maker/public
 * @author     Your Name <email@example.com>
 */
class wen_map_maker_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wen_map_maker    The ID of this plugin.
	 */
	private $wen_map_maker;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $wen_map_maker       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $wen_map_maker, $version ) {

		$this->wen_map_maker = $wen_map_maker;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in wen_map_maker_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wen_map_maker_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->wen_map_maker, plugin_dir_url( __FILE__ ) . 'css/wen-map-maker-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in wen_map_maker_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wen_map_maker_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if(is_admin())
			return;
		wp_enqueue_script( 'google-map-api', 'http://maps.google.com/maps/api/js?sensor=false', array( 'jquery' ), $this->version );
		wp_enqueue_script( 'jquery-jMapify', plugin_dir_url( __FILE__ ) . 'js/jquery.jMapify.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->wen_map_maker, plugin_dir_url( __FILE__ ) . 'js/wen-map-maker-public.js', array( 'jquery' ), $this->version, false );		

	}

	/**
	 * Creates map shortcode
	 *
	 * @since    1.0.0
	 */
	public function map_shortcode($atts){
		$jquery_mapify_helper = new jquery_mapify_helper();

		$atts = shortcode_atts( array(
			'post_id' => NULL,
			'lat' => NULL,
			'lng' => NULL,
			'showMarker' => true,
			'width' => '100%',
			'height' => '500',
		), $atts, 'WMM' );

		$args['showMarker'] = $atts['showMarker'];
		$args['width'] = $atts['width'];
		$args['height'] = $atts['height'];

		// In case lat and lng is passed in shortcode
		if(NULL != $atts['lat'] and NULL != $atts['lng'] ){
			$args['lat'] = $atts['lat'];
			$args['lng'] = $atts['lng'];
		}
		// In case post id is passed in shortcode
		else if( NULL != $atts['post_id']){
			$wen_map_marker_lat = get_post_meta( $post_id, "wen_map_marker_lat",true );
			$wen_map_marker_lng = get_post_meta( $post_id, "wen_map_marker_lng",true );

			$args['lat'] = $wen_map_marker_lat;
			$args['lng'] = $wen_map_marker_lng;

		}
		// In case nothing is passed shortcode
		else{
			global $post;
			$wen_map_marker_lat = get_post_meta( $post->ID, "wen_map_marker_lat",true );
			$wen_map_marker_lng = get_post_meta( $post->ID, "wen_map_marker_lng",true );

			$args['lat'] = $wen_map_marker_lat;
			$args['lng'] = $wen_map_marker_lng;
		}
		
		return $jquery_mapify_helper->create($args);
	}
}
