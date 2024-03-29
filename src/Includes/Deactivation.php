<?php
/**
 * Handles the deactivation functionality of the plugin.
 *
 * @package WpPluginMold
 */

namespace WpPluginMold\Includes;

use WpPluginMold\Utils\Helpers;

/**
 * Handles the deactivation functionality of the plugin.
 *
 * @package WpPluginMold
 */
class Deactivation {

	/** @var Helpers The Helpers instance */
    private $helpers;

    /**
     * Constructor.
     *
     * @param Helpers $helpers The Helpers instance.
     */
    public function __construct(Helpers $helpers) {
		$this->helpers = $helpers;
	}

	/**
	 * Main method for the service, called by the container.
	 */
	public function boot(): void {
		register_deactivation_hook( $this->helpers::PLUGIN_PATH . $this->helpers::PLUGIN_FILE, [ $this, 'deactivate' ] );
	}

	/**
	 * Deactivation hook callback.
	 */
	public function deactivate(): void {
		// Things to do when the plugin is deactivated.
	}
}
