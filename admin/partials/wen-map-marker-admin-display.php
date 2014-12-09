<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wen_map_marker
 * @subpackage wen_map_marker/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
<?php
if( isset($_GET['settings-updated']) and 'true' == $_GET['settings-updated']){
	echo "<div id=\"message\" class=\"updated below-h2\"><p>".__("Settings has been updated.","wen-map-marker")."</p></div>";
}
?>
<h2><?php _e("WEN Map Marker Settings","wen-map-marker");?></h2>
<form method="post" action="options.php">
<?php 
settings_fields( 'wen-map-marker-settings-group' );
do_settings_sections( 'wen-map-marker-settings-group' );
submit_button(); 
?>
</form>

<h3><?php _e("Generate Custom Map Marker Shortcode","wen-map-marker");?></h3>

<div class="">
	<div class="wen-map-marker-wrapper">
		<div class="wen-map-marker-search-bar">
			<a href="#" class="wen-map-marker-locate-user" title="Find current location">Locate User</a>
			<input type="text" id="wen-map-marker-search-custom"  />
		</div>
		<div id="wen-map-marker-canvas-custom"></div>
		<input type="hidden" id="wen-map-marker-address-custom" name="wen_map_marker_address_custom"  />
		<input type="hidden" id="wen-map-marker-lat-custom" name="wen_map_marker_lat_custom"  />
		<input type="hidden" id="wen-map-marker-lng-custom" name="wen_map_marker_lng_custom"  />
		<input type="text" id="wen-map-marker-shortcode-custom" name="wen_map_marker_shortcode_custom"  />
	</div>
</div> 

</div> 