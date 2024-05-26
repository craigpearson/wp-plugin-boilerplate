<?php
/**
 * Plugin Name:       WP Plugin Mold
 * Plugin URI:        https://example.com/wp-plugin-mold
 * Description:       A mold for creating WordPress plugins.
 * Version:           1.0.1-alpha
 * Requires at least: 6.4.3
 * Requires PHP:      8.0
 * Author:            Your Name
 * Author URI:        https://example.com
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Update URI:        https://example.com/wp-plugin-mold-update
 * Text Domain:       wp-plugin-mold
 * Domain Path:       /languages
 *
 * @package           WpPluginMold
 */

defined( 'ABSPATH' ) || exit;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

use WpPluginMold\ServiceContainer;

if ( file_exists( __DIR__ . '/services.php' ) ) {
	$services = require __DIR__ . '/services.php';

	$mold_container = new ServiceContainer();

	foreach ( $services as $name => $service_callable ) {
		$mold_container->register( $name, $service_callable );
	}

	foreach ( $mold_container->get_services() as $service_id => $service ) {
		if ( is_object( $service ) && method_exists( $service, 'boot' ) ) {
			$service->boot();
		}
	}
}
