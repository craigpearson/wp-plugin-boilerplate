<?php
/**
 * Service Container exception for when a service is not found.
 *
 * @package WpPluginMold
 */

namespace WpPluginMold;


use Psr\Container\NotFoundExceptionInterface;

/**
 * Service Container exception for when a service is not found.
 *
 * @package WpPluginMold
 */
class ContainerNotFoundException extends Exception implements NotFoundExceptionInterface {}
