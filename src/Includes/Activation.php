<?php
/**
 * Handles the activation functionality of the plugin.
 *
 * @package WpPluginMold
 */

namespace WpPluginMold\Includes;

use WpPluginMold\Utils\Helpers;

/**
 * Handles the activations functionality of the plugin.
 *
 * @package WpPluginMold
 */
class Activation {

	/**
	 * Helpers instance.
	 *
	 * @var Helpers The Helpers instance.
	 */
	private $helpers;

	/**
	 * Constructor.
	 *
	 * @param Helpers $helpers The Helpers instance.
	 */
	public function __construct( Helpers $helpers ) {
		$this->helpers = $helpers;
	}

	/**
	 * Main method for the service, called by the container.
	 */
	public function boot(): void {
		register_activation_hook( $this->helpers::PLUGIN_PATH . $this->helpers::PLUGIN_FILE, [ $this, 'activate' ] );
	}

	/**
	 * Activation hook callback.
	 */
	public function activate(): void {
	}
}
