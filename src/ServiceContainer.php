<?php
/**
 * Service Container for the plugin.
 *
 * @package WpPluginMold
 */

namespace WpPluginMold;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Service Container for the plugin.
 *
 * @package WpPluginMold
 */
class ServiceContainer implements ContainerInterface {
	/**
	 * The services registered with the container.
	 *
	 * @var callable[]
	 */
	protected array $services = [];

	/**
	 * The instances of services that have been created.
	 *
	 * @var array<string, mixed>
	 */
	protected array $instances = [];

	/**
	 * Registers a service with the container.
	 *
	 * @param string   $name The name of the service.
	 * @param callable $callable The callable that will create the service.
	 * @return void
	 */
	public function register( string $name, callable $callable ): void {
		$this->services[ $name ] = $callable;
	}

	/**
	 * Boots the container, creating instances of all services.
	 *
	 * @param string $id The ID of the service.
	 * @return mixed The service instance.
	 *
	 * @throws ContainerNotFoundException If the service is not found.
	 */
	public function get( $id ) {
		if ( ! $this->has( $id ) ) {
			throw new ContainerNotFoundException( esc_html__( 'No service found for ID:', 'wp-plugin-mold' ) . ' ' . esc_html( $id ) );
		}

		if ( ! isset( $this->instances[ $id ] ) ) {
			$this->instances[ $id ] = call_user_func( $this->services[ $id ], $this );
		}

		return $this->instances[ $id ];
	}

	/**
	 * Checks if a service is registered with the container.
	 *
	 * @param string $id The ID of the service.
	 * @return bool
	 */
	public function has( $id ): bool {
		return isset( $this->services[ $id ] );
	}

	/**
	 * Helper method to create a service.
	 *
	 * @param string        $class_name The name of the class to create.
	 * @param array<string> $dependencies The dependencies to inject into the class.
	 * @return callable
	 */
	public static function createService( string $class_name, array $dependencies = [] ): callable {
		return function ( $container ) use ( $class_name, $dependencies ) {
			$full_class_name = __NAMESPACE__ . "\\$class_name";
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
