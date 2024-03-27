<?php
/**
 * Example Service for the plugin.
 *
 * @package WpPluginMold
 */

namespace WpPluginMold\Example;

use function add_action;
use function remove_action;

/**
 * Example class for the plugin
 *
 * @package WpPluginMold
 */
class Example {
	/**
	 * The initialization method for the service, called by the container.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_action( 'wp_head', [ $this, 'wp_head' ] );
		remove_action( 'wp_head', [ $this, 'wp_head'] );
	}

	/**
	 * An example method that hooks into the `wp_head` action.
	 *
	 * @return void
	 */
	public function wp_head(): void {
		echo '<!-- Example Plugin Output: ' . esc_html__( 'Hello, World!', 'wp-plugin-mold' ) . ' -->';
	}
}
