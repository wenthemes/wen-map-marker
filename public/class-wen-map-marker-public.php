<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wen_map_marker
 * @subpackage wen_map_marker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    wen_map_marker
 * @subpackage wen_map_marker/public
 * @author     Your Name <email@example.com>
 */
class wen_map_marker_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wen_map_marker    The ID of this plugin.
	 */
	private $wen_map_marker;

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
	 * @var      string    $wen_map_marker       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $wen_map_marker, $version ) {

		$this->wen_map_marker = $wen_map_marker;
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
		 * defined in wen_map_marker_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wen_map_marker_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->wen_map_marker, plugin_dir_url( __FILE__ ) . 'css/wen-map-marker-public.css', array(), $this->version, 'all' );

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
		 * defined in wen_map_marker_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wen_map_marker_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if(is_admin())
			return;
		wp_enqueue_script( 'google-map-api', 'http://maps.google.com/maps/api/js?sensor=false', array( 'jquery' ), $this->version );
		wp_enqueue_script( 'jquery-jMapify', plugin_dir_url( __FILE__ ) . 'js/jquery.jMapify.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->wen_map_marker, plugin_dir_url( __FILE__ ) . 'js/wen-map-marker-public.js', array( 'jquery' ), $this->version, false );		

	}

	/**
	 * Creates map shortcode
	 *
	 * @since    1.0.0
	 */
	public function map_shortcode($atts=array()){
		$jquery_mapify_helper = new jquery_mapify_helper();

		$atts = shortcode_atts( array(
			'post_id' => NULL,
			'lat' => NULL,
			'lng' => NULL,
			'showMarker' => true,
			'width' => '100%',
			'height' => '500',
			'zoom' => 15,
		), $atts, 'WMM' );

		$args['showMarker'] = $atts['showMarker'];
		$args['width'] = $atts['width'];
		$args['height'] = $atts['height'];
		$args['zoom'] = (int) $atts['zoom'];

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

			if('' == $wen_map_marker_lat || '' == $wen_map_marker_lng)
				return false;
			$args['lat'] = $wen_map_marker_lat;
			$args['lng'] = $wen_map_marker_lng;
		}
		
		return $jquery_mapify_helper->create($args);
	}

	/**
	 * Append map to post content
	 *
	 * @since    1.0.0
	 */
	public function append_map($content){
		if ( !is_singular() )
			return $content;

		$wen_map_marker_post_type = get_option('wen_map_marker_post_type');
		
		global $post;
		
		if(is_array($wen_map_marker_post_type) and !in_array($post->post_type,$wen_map_marker_post_type))
			return $content;

		$wen_map_marker_content_append = get_post_meta( $post->ID, 'wen_map_marker_content_append', true );
		
		if(''==$wen_map_marker_content_append)
			return $content;
		$map_output = $this->map_shortcode();
		if( 'before_content' == $wen_map_marker_content_append)
			return $map_output.$content;
		if( 'after_content' == $wen_map_marker_content_append)
			return $content.$map_output;
	}

}
