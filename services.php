<?php
use WpPluginMold\ServiceContainer;

return [
	'Helpers' => ServiceContainer::createService('Utils\Helpers'),
	'Activation' => ServiceContainer::createService('Includes\Activation' [ 'Helpers' ]),
	'Deactivation' => ServiceContainer::createService('Includes\Deactivation' [ 'Helpers' ]),
    'Example' => ServiceContainer::createService('Example\Example', [ 'Helpers' ]),
	'Localization' => ServiceContainer::createService('Localization\Localization'),
];
