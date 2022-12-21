<?php
/**
 * Plugin Name: Set.or.th API
 * Version 1.0.0
 * Description: a WordPress plugin that retrieves and displays data about a particular company's stock information from the SET (Stock Exchange of Thailand) API.
 */

add_action( 'admin_menu', 'set_api_plugin_create_menu' );

function set_api_plugin_create_menu(): void {
	add_menu_page(
		'Set.or.th API',
		'Set.or.th API',
		'manage_options',
		__DIR__ . '/admin/settings.php'
	);

	add_action( 'admin_init', 'set_api_register_settings' );
}


function set_api_register_settings(): void {
	register_setting( 'set-api', 'set_api_company_name' );
	register_setting( 'set-api', 'set_api_update_date' );
	register_setting( 'set-api', 'set_api_last' );
	register_setting( 'set-api', 'set_api_update_time' );
	register_setting( 'set-api', 'set_api_prior' );
	register_setting( 'set-api', 'set_api_change' );
	register_setting( 'set-api', 'set_api_change_percent' );
	register_setting( 'set-api', 'set_api_volume' );
	register_setting( 'set-api', 'set_api_value' );
	register_setting( 'set-api', 'set_api_up_down' );
}

add_shortcode( 'set_api_last_update', 'set_api_last_update' );
function set_api_last_update(): string {
	return '<div class="set-api-last-update"></div>';
}

add_shortcode( 'set_api_last_update_time', 'set_api_last_update_time' );
function set_api_last_update_time(): string {
	return '<div class="set-api-last-update-time"></div>';
}

add_shortcode( 'set_api_up_or_down', 'set_api_up_or_down' );
function set_api_up_or_down(): string {
	return 'set-api-up';
}

add_shortcode( 'set_api_last', 'set_api_last' );
function set_api_last(): string {
	return '<div class="set-api-last"></div>';
}


add_shortcode( 'set_api_prior', 'set_api_prior' );
function set_api_prior(): string {
	return '<div class="set-api-prior"></div>';
}

add_shortcode( 'set_api_change', 'set_api_change' );
function set_api_change(): string {
	return '<div class="set-api-change"></div>';
}

add_shortcode( 'set_api_change_percent', 'set_api_change_percent' );
function set_api_change_percent(): string {
	return '<div class="set-api-change-percent"></div>';
}

add_shortcode( 'set_api_volume', 'set_api_volume' );
function set_api_volume(): string {
	return '<div class="set-api-volume"></div>';
}

add_shortcode( 'set_api_value', 'set_api_value' );
function set_api_value(): string {
	return '<div class="set-api-value"></div>';
}


add_action( 'set_api_retrieve_data', 'set_api_retrieve_data', 10 );
function set_api_retrieve_data(): bool {
	$company_name = '';
	if ( get_option( 'set_api_company_name' ) ) {
		$company_name = get_option( 'set_api_company_name' );
	}
	if ( $company_name == '' ) {
		_e( 'Error: No company name' );

		return false;
	}
	$url = 'https://www.set.or.th/api/set/stock/' . $company_name . '/related-product/o?lang=en';

	$response = wp_remote_get( $url );
	try {
		$data = json_decode( $response['body'], true );
	} catch ( Exception $ex ) {
		echo $ex->getMessage();

		return false;
	}

	$result = array();
	$valid  = true;

	$result['last_update']      = date( get_option( 'date_format' ) );
	$result['last_update_time'] = date( get_option( 'time_format' ) );
	$result['last']             = '';
	if ( isset( $data['relatedProducts'][0]['last'] ) && $data['relatedProducts'][0]['last'] != null ) {
		$result['last'] = $data['relatedProducts'][0]['last'];
	} else {
		$valid = false;
	}
	$result['prior'] = '';
	if ( isset( $data['relatedProducts'][0]['prior'] ) && $data['relatedProducts'][0]['prior'] != null ) {
		$result['prior'] = $data['relatedProducts'][0]['prior'];
	} else {
		$valid = false;
	}

	if ( $result['prior'] > $result['last'] ) {
		$result['up_or_down'] = 'down';
	} elseif ( $result['last'] > $result['prior'] ) {
		$result['up_or_down'] = 'up';
	} else {
		$result['up_or_down'] = 'equal';
	}
	$result['change'] = '';
	if ( isset( $data['relatedProducts'][0]['change'] ) && $data['relatedProducts'][0]['change'] != null ) {
		$result['change'] = $data['relatedProducts'][0]['change'];
	} else {
		$valid = false;
	}
	$result['change_percent'] = '';
	if ( isset( $data['relatedProducts'][0]['percentChange'] ) && $data['relatedProducts'][0]['percentChange'] != null ) {
		$result['change_percent'] = round( $data['relatedProducts'][0]['percentChange'], 2 );
	} else {
		$valid = false;
	}
	$result['volume'] = '';
	if ( isset( $data['relatedProducts'][0]['totalVolume'] ) && $data['relatedProducts'][0]['totalVolume'] != null ) {
		$result['volume'] = $data['relatedProducts'][0]['totalVolume'];
	} else {
		$valid = false;
	}
	$result['value'] = '';
	if ( isset( $data['relatedProducts'][0]['totalValue'] ) && $data['relatedProducts'][0]['totalValue'] != null ) {
		$result['value'] = $data['relatedProducts'][0]['totalValue'];
	} else {
		$valid = false;
	}

	if ( $valid ) {
		set_api_update_option( 'set_api_update_date', $result['last_update'] );
		set_api_update_option( 'set_api_update_time', $result['last_update_time'] );
		set_api_update_option( 'set_api_last', $result['last'] );
		set_api_update_option( 'set_api_prior', $result['prior'] );
		set_api_update_option( 'set_api_change', $result['change'] );
		set_api_update_option( 'set_api_change_percent', $result['change_percent'] );
		set_api_update_option( 'set_api_volume', $result['volume'] );
		set_api_update_option( 'set_api_value', $result['value'] );
		set_api_update_option( 'set_api_up_down', $result['up_or_down'] );
	} else {
		echo 'Error: No API Data Retrieved';
	}
	echo json_encode(set_api_get_data());

	return true;
}

function set_api_update_option( $option_name, $option_value ) {

	if ( ! get_option( $option_name ) ) {
		add_option( $option_name, $option_value );
	} else {
		update_option( $option_name, $option_value );
	}
}

function set_api_get_data(): array {
	$result                = array();
	$result['last_update'] = '';
	if ( get_option( 'set_api_update_date' ) ) {
		$result['last_update'] = get_option( 'set_api_update_date' );
	}
	$result['last_update_time'] = '';
	if ( get_option( 'set_api_update_time' ) ) {
		$result['last_update_time'] = get_option( 'set_api_update_time' );
	}
	$result['last'] = '';
	if ( get_option( 'set_api_last' ) ) {
		$result['last'] = get_option( 'set_api_last' );
	}
	$result['prior'] = '';
	if ( get_option( 'set_api_prior' ) ) {
		$result['prior'] = get_option( 'set_api_prior' );
	}
	$result['up_or_down'] = '';
	if ( get_option( 'set_api_up_down' ) ) {
		$result['up_or_down'] = get_option( 'set_api_up_down' );
	}
	$result['change'] = '';
	if ( get_option( 'set_api_change' ) ) {
		$result['change'] = get_option( 'set_api_change' );
	}
	$result['change_percent'] = '';
	if ( get_option( 'set_api_change_percent' ) ) {
		$result['change_percent'] = get_option( 'set_api_change_percent' );
	}
	$result['volume'] = '';
	if ( get_option( 'set_api_volume' ) ) {
		$result['volume'] = get_option( 'set_api_volume' );
	}
	$result['value'] = '';
	if ( get_option( 'set_api_value' ) ) {
		$result['value'] = get_option( 'set_api_value' );
	}

	return $result;

}

register_activation_hook( __FILE__, 'set_api_activation' );
function set_api_activation(): void {
	if ( ! wp_next_scheduled( 'set_api_retrieve_data' ) ) {
		wp_schedule_event( time(), 'hourly', 'set_api_retrieve_data' );
	}
}

register_deactivation_hook( __FILE__, 'set_api_deactivation' );
function set_api_deactivation(): void {
	wp_clear_scheduled_hook( 'set_api_retrieve_data' );
}

add_action( "wp_ajax_set_api_get_data", "ajax_set_api_get_data" );
add_action( "wp_ajax_nopriv_set_api_get_data", "ajax_set_api_get_data" );

function ajax_set_api_get_data(): void {
	echo json_encode( set_api_get_data() );
	exit();
}

add_action( 'init', function () {
	wp_register_script( "set-api-script", plugin_dir_url( __FILE__ ) . '/assets/set-api-script.js', array( 'jquery' ) );
	wp_localize_script( 'set-api-script', 'wordpressObject', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	wp_enqueue_script( 'set-api-script' );

} );