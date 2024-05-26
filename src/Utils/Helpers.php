<?php
/**
 * Handles the localization functionality of the plugin.
 *
 * @package WpPluginMold
 */

namespace WpPluginMold\Utils;

/**
 * Handles the localization functionality of the plugin.
 *
 * @package WpPluginMold
 */
class Helpers {

	/** The version for the plugin */
	const PLUGIN_VERSION = '1.0.0';

	/** The Text domain for the plugin */
	const TEXT_DOMAIN = 'wp-plugin-mold';

	/** The file for the plugin */
	const PLUGIN_FILE = 'wp-plugin-mold.php';

	/** The path for the plugin */
	const PLUGIN_PATH = __DIR__ . '/../../';

	/**
	 * Main method for the service, called by the container.
	 */
	public function boot(): void {
	}
}
