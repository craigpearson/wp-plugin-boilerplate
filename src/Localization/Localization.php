<?php
/**
 * Handles the localization functionality of the plugin.
 *
 * @package WpPluginMold
 */

namespace WpPluginMold\Localization;

/**
 * Handles the localization functionality of the plugin.
 *
 * @package WpPluginMold
 */
class Localization {
	/**
	 * Main method for the service, called by the container.
	 */
	public function boot(): void {
		load_plugin_textdomain( 'wp-plugin-mold', false, dirname( dirname( __DIR__ ) ) . '/languages' );
	}
}
