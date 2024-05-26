<?php
/**
 * Service Container for the plugin.
 *
 * @package WpPluginMold
 */

namespace WpPluginMold;

use WpPluginMold\Exception\ContainerException;
use WpPluginMold\Exception\ContainerNotFoundException;

/**
 * Service Container for the plugin.
 *
 * @package WpPluginMold
 */
class ServiceContainer {
	/**
	 * The services registered with the container.
	 *
	 * @var callable[]
	 */
	protected array $services = [];

	/**
	 * The instances of services that have been created.
	 *
	 * @var array<string, object>
	 */
	protected array $instances = [];

	/**
	 * Registers a service with the container.
	 *
	 * @param string   $name The name of the service.
	 * @param callable $call The callable that will create the service.
	 * @return void
	 */
	public function register( string $name, callable $call ): void {
		$this->services[ $name ] = $call;
	}

	/**
	 * Gets a service from the container.
	 *
	 * @param string $id The ID of the service.
	 * @return object The service instance.
	 * @throws ContainerNotFoundException If the service is not found.
	 * @throws ContainerException If an error occurs while creating the service.
	 */
	public function get( string $id ): object {
		if ( ! isset( $this->services[ $id ] ) ) {
			throw new ContainerNotFoundException( 'No service found for ID: ' . esc_html( $id ) );
		}

		if ( ! isset( $this->instances[ $id ] ) ) {
			$instance = call_user_func( $this->services[ $id ], $this );
			if ( ! is_object( $instance ) ) {
				throw new ContainerException( 'The service must return an object, ' . esc_html( gettype( $instance ) ) . ' returned instead.' );
			}
			$this->instances[ $id ] = $instance;
		}

		return $this->instances[ $id ];
	}

	/**
	 * Checks if a service is registered with the container.
	 *
	 * @param string $id The ID of the service.
	 * @return bool
	 */
	public function has( string $id ): bool {
		return isset( $this->services[ $id ] );
	}

	/**
	 * Deregisters a service from the container.
	 *
	 * @param string $id The ID of the service.
	 * @return void
	 */
	public function deregister( string $id ): void {
		unset( $this->services[ $id ], $this->instances[ $id ] );
	}

	/**
	 * Returns the services registered with the container.
	 *
	 * @throws ContainerException If a service is not an object.
	 * @return array<string, object>
	 */
	public function get_services(): array {
		$services = [];
		foreach ( $this->services as $id => $factory ) {
			$service = $this->get( $id );
			if ( ! is_object( $service ) ) {
				throw new ContainerException( 'Expected object, received ' . esc_html( gettype( $service ) ) );
			}
			$services[ $id ] = $service;
		}
		return $services;
	}

	/**
	 * Helper method to create a service.
	 *
	 * @param string        $class_name The name of the class to create.
	 * @param array<string> $dependencies The dependencies to inject into the class.
	 * @return callable
	 */
	public static function create_service( string $class_name, array $dependencies = [] ): callable {
		return function ( $container ) use ( $class_name, $dependencies ) {
			$full_class_name = __NAMESPACE__ . '\\' . $class_name;
			$constructor_args = array_map(
				function ( $dependency_name ) use ( $container ) {
					return $container->get( $dependency_name );
				},
				$dependencies
			);
			return new $full_class_name( ...$constructor_args );
		};
	}
}
