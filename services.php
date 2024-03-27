<?php
use WpPluginMold\ServiceContainer;

return [
    'Helpers' => ServiceContainer::createService('Utils\Helpers'),
    'Example' => ServiceContainer::createService('Example\Example', ['Helpers']),
	'Localization' => ServiceContainer::createService('Localization\Localization'),
];
