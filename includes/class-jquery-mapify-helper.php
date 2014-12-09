<?php

/**
 * The file that helps to create map output
 *
 * A class definition that includes function that creates map
 * public-facing side of the site and the dashboard.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wen_map_maker
 * @subpackage wen_map_maker/includes
 */

class jquery_mapify_helper {

	
	public function create( $new_settings = array() ){
		$default_settings = array(
			'width' => '100%',
            'height' => '500',
			'lat' => 27.7000,
            'lng' => 85.3333,
			'zoom' => 15,
			'type' => 'ROADMAP',
			'draggable' => true,
			'zoomControl' => true,
			'scrollwheel' => true,
			'disableDoubleClickZoom' => false,
			'showMarker' => false,
			'showMarkerOnClick' =>false,
			'markerOptions' => array(
				'draggable' => false,
				'raiseOnDrag' => false
			),
			// 'afterMarkerDrag' => function() {},
			'autoLocate' => false,
			'geoLocationButton' => null,
			'searchInput' => null
		);
		
		$merged_settings = array_merge( $default_settings, $new_settings );
		$rand = rand();
		$script = '<script type="text/javascript">'."\n";
		$script .= 'jQuery(function($){'."\n";
		$script .='var _wen_map_marker_options_'.$rand.'='.json_encode($merged_settings).';'."\n";
		$script .= '$("#wen-map-marker-canvas_'.$rand.'").jMapify(_wen_map_marker_options_'.$rand.');'."\n";
		$script .= ' });'."\n";
		$script .= '</script>';

		$map = '<div id="wen-map-marker-canvas_'.$rand.'"></div>';

		return $script.$map;
	}
}
