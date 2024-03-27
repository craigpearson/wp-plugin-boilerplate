<?php
/**
 * Plugin Name:       My Custom Plugin
 * Plugin URI:        https://example.com/wp-plugin-mold
 * Description:       A brief description of the plugin.
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      8.0
 * Author:            Your Name
 * Author URI:        https://example.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/wp-plugin-mold-update
 * Text Domain:       wp-plugin-mold
 * Domain Path:       /languages
 * @package           WpPluginMold
 */

// exit on direct access
defined( 'ABSPATH' ) || exit;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use WpPluginMold\ServiceContainer;

$container = new ServiceContainer();

if ( file_exists( __DIR__ . '/services.php' ) ) {
    $services = require __DIR__ . '/services.php';
    foreach ($services as $name => $serviceCallable) {
        $container->register($name, $serviceCallable);
    }
}

$container->boot();
