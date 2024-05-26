<?php
/**
 * Services configuration for the plugin.
 *
 * @package WpPluginMold
 */

use WpPluginMold\ServiceContainer;

return array(
	'Helpers' => ServiceContainer::create_service( 'Utils\Helpers' ),
	'Activation' => ServiceContainer::create_service( 'Includes\Activation', array( 'Helpers' ) ),
	'Deactivation' => ServiceContainer::create_service( 'Includes\Deactivation', array( 'Helpers' ) ),
	'Example' => ServiceContainer::create_service( 'Example\Example', array( 'Helpers' ) ),
	'Localization' => ServiceContainer::create_service( 'Localization\Localization' ),
);
