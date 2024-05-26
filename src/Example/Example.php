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

	/**
	 * Helpers instance
	 *
	 * @var Helpers The Helpers instance
	 */
	private $helpers;

	/**
	 * The Helpers instance.
	 *
	 * @param Helpers $helpers The Helpers instance.
	 */
	public function __construct( Helpers $helpers ) {
		$this->helpers = $helpers;
	}

	/**
	 * The initialization method for the service, called by the container.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_action( 'admin_menu', array( $this, 'add_settings_submenu' ) );
	}

	/**
	 * Add Settings Submenu
	 *
	 * @return void
	 */
	public function add_settings_submenu(): void {
		add_submenu_page(
			'options-general.php',
			'WP Plugin Mold Settings',
			'WP Plugin Mold',
			'manage_options',
			'wp-plugin-mold-settings',
			array( $this, 'settings_page_content' )
		);
	}

	/**
	 * Render Settings Submenu Content
	 *
	 * @return void
	 */
	public function settings_page_content(): void {
		echo esc_html( $this->helpers::PLUGIN_VERSION );
	}
}
