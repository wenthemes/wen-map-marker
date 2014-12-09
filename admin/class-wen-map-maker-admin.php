<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wen_map_maker
 * @subpackage wen_map_maker/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    wen_map_maker
 * @subpackage wen_map_maker/admin
 * @author     Your Name <email@example.com>
 */
class wen_map_maker_Admin {

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
	 * @var      string    $wen_map_maker       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $wen_map_maker, $version ) {

		$this->wen_map_maker = $wen_map_maker;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in wen_map_maker_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wen_map_maker_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->wen_map_maker, plugin_dir_url( __FILE__ ) . 'css/wen-map-maker-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in wen_map_maker_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wen_map_maker_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		

		// wp_enqueue_script( 'jquery-jMapify', plugin_dir_url( __FILE__ ) . 'js/jquery.jMapify.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'google-map-api', 'http://maps.google.com/maps/api/js?sensor=false&libraries=places', array( 'jquery' ), $this->version );
		wp_enqueue_script( 'jquery-jMapify', plugin_dir_url(__FILE__) . '../public/js/jquery.jMapify.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->wen_map_maker, plugin_dir_url( __FILE__ ) . 'js/wen-map-maker-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add Meta Boxes for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function add_meta_boxes() {
		$wen_map_maker_post_type = get_option('wen_map_maker_post_type');
		if(!empty( $wen_map_maker_post_type)){
			foreach ($wen_map_maker_post_type as $key => $postType) {
				add_meta_box( 'wen-map-maker-meta-box', __( 'WEN Map Marker', 'wen-map-maker' ), array(&$this,'meta_box_output'), $postType, 'normal', 'high' );
			}
		}

	}

	/**
	 * Output the Metabox for the dashboard.
	 *
	 * @since    1.0.0
	 */

	function meta_box_output( $post ) {
		// create a nonce field
		wp_nonce_field( 'my_wpshed_meta_box_nonce', 'wen_map_marker_meta_box_nonce' ); ?>
		<div class="wen-map-marker-wrapper">
			<div class="wen-map-marker-search-bar">
				<a href="#" class="wen-map-marker-locate-user" title="Find current location">Locate User</a>
				<input type="text" id="wen-map-marker-search"  value="<?php echo $this->get_custom_field_value("wen_map_marker_address");?>"/>
			</div>
			<div id="wen-map-marker-canvas"></div>
			<input type="text" id="wen-map-marker-address" name="wen_map_marker_address" value="<?php echo $this->get_custom_field_value("wen_map_marker_address");?>" />
			<input type="text" id="wen-map-marker-lat" name="wen_map_marker_lat" value="<?php echo $this->get_custom_field_value("wen_map_marker_lat");?>" />
			<input type="text" id="wen-map-marker-lng" name="wen_map_marker_lng"  value="<?php echo $this->get_custom_field_value("wen_map_marker_lng");?>" />
	    </div>
		<?php
	}

	/**
	 * Returns the value for the custom field
	 *
	 * @since    1.0.0
	 */
	private function get_custom_field_value( $key ) {
		global $post;

	    $custom_field = get_post_meta( $post->ID, $key, true );
	    if ( !empty( $custom_field ) )
		    return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );

	    return false;
	}

	/**
	 * Save the Metabox values
	 *
	 * @since    1.0.0
	 */
	function meta_box_save( $post_id ) {
		// Stop the script when doing autosave
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// Verify the nonce. If insn't there, stop the script
		if( !isset( $_POST['wen_map_marker_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['wen_map_marker_meta_box_nonce'], 'my_wpshed_meta_box_nonce' ) ) return;

		// Stop the script if the user does not have edit permissions
		if( !current_user_can( 'edit_post' ) ) return;

	    // Save the textfield
		if( isset( $_POST['wen_map_marker_address'] ) )
			update_post_meta( $post_id, 'wen_map_marker_address', esc_attr( $_POST['wen_map_marker_address'] ) );

	    // Save the textarea
		if( isset( $_POST['wen_map_marker_lat'] ) )
			update_post_meta( $post_id, 'wen_map_marker_lat', esc_attr( $_POST['wen_map_marker_lat'] ) );

		// Save the textarea
		if( isset( $_POST['wen_map_marker_lng'] ) )
			update_post_meta( $post_id, 'wen_map_marker_lng', esc_attr( $_POST['wen_map_marker_lng'] ) );
	}

	/**
	 * Load Script on admin head
	 *
	 * @since    1.0.0
	 */
	function admin_head() {

		$screen = get_current_screen();
		$wen_map_maker_post_type = get_option('wen_map_maker_post_type');
		
		if(is_array($wen_map_maker_post_type) and !in_array($screen->id,$wen_map_maker_post_type))
			return;

		$script = '<script type="text/javascript">'."\n";
		$script .= 'jQuery(function($){'."\n";

		if(isset($_GET['action'])){

			global $post;

			$wen_map_marker_lat = $this->get_custom_field_value("wen_map_marker_lat");
			$wen_map_marker_lng = $this->get_custom_field_value("wen_map_marker_lng");

			if($wen_map_marker_lat != "" and $wen_map_marker_lng != "" ){
				$script .='var _wen_map_marker_options={
					lat:'.$wen_map_marker_lat.',
					lng:'.$wen_map_marker_lng.',
					showMarker:true,
					showMarkerOnClick:true,
					markerOptions:{
						draggable:true
					},
					autoLocate:false,
					geoLocationButton:".wen-map-marker-locate-user",
					searchInput:"#wen-map-marker-search",
					afterMarkerDrag:function(response){
						console.log(response);
						$("#wen-map-marker-lat").val(response.lat);
						$("#wen-map-marker-lng").val(response.lng);
						$("#wen-map-marker-address").val(response.address);
						$("#wen-map-marker-search").val(response.address);
					}
				};'."\n";
	        }
	        else{

	        	$script .='var _wen_map_marker_options={
					showMarker:false,
					showMarkerOnClick:true,
					markerOptions:{
						draggable:true
					},
					autoLocate:false,
					geoLocationButton:".wen-map-marker-locate-user",
					searchInput:"#wen-map-marker-search",
					afterMarkerDrag:function(response){
						console.log(response);
						$("#wen-map-marker-lat").val(response.lat);
						$("#wen-map-marker-lng").val(response.lng);
						$("#wen-map-marker-address").val(response.address);
						$("#wen-map-marker-search").val(response.address);
					}
				};'."\n"."\n";

	        }

		}
		else{

			$script .='var _wen_map_marker_options={
				showMarker:false,
				showMarkerOnClick:true,
				markerOptions:{
					draggable:true
				},
				autoLocate:false,
				geoLocationButton:".wen-map-marker-locate-user",
				searchInput:"#wen-map-marker-search",
				afterMarkerDrag:function(response){
					console.log(response);
					$("#wen-map-marker-lat").val(response.lat);
					$("#wen-map-marker-lng").val(response.lng);
					$("#wen-map-marker-address").val(response.address);
					$("#wen-map-marker-search").val(response.address);
				}
			};'."\n";

		}
		$script .= '$("#wen-map-marker-canvas").jMapify(_wen_map_marker_options);'."\n";
		$script .= ' });'."\n";
		$script .= '</script>';
		echo $script;
		
	}

	function setup_menu(){
	    add_menu_page( __('WEN Map Marker',"wen-map-maker"), __('WEN Options',"wen-map-maker"), 'manage_options', 'wen-map-maker', array(&$this,'option_page_init') );
	    add_action( 'admin_init', array(&$this,'register_settings' ));
	}

	function option_page_init(){
	    include(sprintf("%s/partials/wen-map-maker-admin-display.php",dirname(__FILE__)));
	}
	/*
	* register our settings
	*/
	function register_settings() {
		register_setting( 'wen-map-marker-settings-group', 'wen_map_maker_post_type' );
	}

}
