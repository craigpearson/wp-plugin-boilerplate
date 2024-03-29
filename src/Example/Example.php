<?php
/**
 * Example Service for the plugin.
 *
 * @package WpPluginMold
 */

namespace WpPluginMold\Example;

use WpPluginMold\Utils\Helpers;

/**
 * Example class for the plugin
 *
 * @package WpPluginMold
 */
class Example {

	/** @var Helpers The Helpers instance */
    private $helpers;

    /**
     * The Helpers instance.
     *
     * @param Helpers $helpers The Helpers instance.
     */
    public function __construct(Helpers $helpers) {
        $this->helpers = $helpers;
    }

	/**
	 * The initialization method for the service, called by the container.
	 *
	 * @return void
	 */
	public function boot(): void {
        add_action('admin_menu', [$this, 'addSettingsSubmenu']);
    }

	/**
	 * Add Settings Submenu
	 *
	 * @return void
	 */
    public function addSettingsSubmenu(): void {
        add_submenu_page(
            'options-general.php',
            'WP Plugin Mold Settings',
            'WP Plugin Mold',
            'manage_options',
            'wp-plugin-mold-settings',
            [$this, 'settingsPageContent']
        );
    }

	/**
	 * Render Settings Submenu Content
	 *
	 * @return void
	 */
    public function settingsPageContent(): void {
        echo $this->helpers::PLUGIN_VERSION;
    }
}
