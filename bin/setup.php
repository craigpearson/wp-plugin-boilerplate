<?php
/**
 * Upon setup, rename the plugin as needed, then self destruct.
 *
 * @package wp-plugin-mold
 */

declare( strict_types = 1 );

namespace WpPluginMold;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Plugin Setup class.
 *
 * @package wp-plugin-mold
 */
class PluginSetup {

	/**
	 * The default values for the plugin details.
	 *
	 * @var array
	 */
	private $defaults;

	/**
	 * The root directory of the project.
	 *
	 * @var string
	 */
	private $project_root;

	/**
	 * The details of the plugin.
	 *
	 * @var array
	 */
	private $details;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->project_root = dirname( __DIR__ );
		$this->load_defaults();
		$this->details = $this->prompt_for_details();
	}

	/**
	 * Run the setup process.
	 *
	 * @return void
	 */
	public static function run(): void {
		$setup = new self();
		$setup->execute_setup();
	}

	/**
	 * Load the default values for the plugin details.
	 *
	 * @return void
	 */
	private function load_defaults(): void {
		$defaults_path = $this->project_root . '/defaults.php';
		if ( file_exists( $defaults_path ) ) {
			$this->defaults = include $defaults_path;
		} else {
			$this->defaults = [];
		}
	}

	/**
	 * Prompt the user for the plugin details.
	 *
	 * @return array
	 */
	private function prompt_for_details(): array {
		echo "Let's set up your new plugin\n";
		$details = [];
		$fields = [
			'plugin_name' => 'Plugin Name',
			'plugin_author' => 'Your name / plugin author',
			'plugin_author_uri' => 'Your website / plugin URL',
			'author_email' => 'Your email address',
			'plugin_description' => 'Plugin description',
			'plugin_version' => 'Plugin version',
			'plugin_requires_wp_at_least' => 'Minimum WordPress version required',
			'plugin_requires_php' => 'Minimum PHP version required',
			'plugin_license' => 'Plugin license as per SPDX identifier: https://spdx.org/licenses/',
			'plugin_license_uri' => 'Plugin license URI',
			'plugin_update_uri' => 'Plugin update URI',
			'plugin_domain_path' => 'Plugin domain path',
			'namespace' => 'PHP namespace',
			'composer_name' => 'Composer name i.e. (vendor/name)',
		];

		foreach ( $fields as $key => $prompt ) {
			$default_value = $this->defaults[ $key ] ?? '';
			$details[ $key ] = readline( $prompt . ' [' . $default_value . ']: ' ) ?: $default_value;

			switch ( $key ) {
				case 'plugin_name':
					$this->defaults['namespace'] = $this->generate_namespace( $details[ $key ] );
					$this->defaults['plugin_text_domain'] = $this->generate_text_domain( $details[ $key ] );
					break;
				case 'plugin_author_uri':
					$this->defaults['plugin_uri'] = $details[ $key ];
					break;
			}
		}

		return $details;
	}

	/**
	 * Update the main plugin file with the plugin details.
	 *
	 * @return void
	 */
	private function update_plugin_file(): void {
		$plugin_file = $this->project_root . DIRECTORY_SEPARATOR . 'wp-plugin-mold.php';
		$plugin_content = file_get_contents( $plugin_file );
		$replacements = [
			'WP Plugin Mold' => $this->details['plugin_name'],
			'https://example.com/wp-plugin-mold' => $this->details['plugin_author_uri'],
			'A mold for creating WordPress plugins.' => $this->details['plugin_description'],
			'1.0.1-alpha' => $this->details['plugin_version'],
			'6.4.3' => $this->details['plugin_requires_wp_at_least'],
			'8.0' => $this->details['plugin_requires_php'],
			'Your Name' => $this->details['plugin_author'],
			'https://example.com' => $this->details['plugin_author_uri'],
			'GPL-3.0-or-later' => $this->details['plugin_license'],
			'https://www.gnu.org/licenses/gpl-3.0.html' => $this->details['plugin_license_uri'],
			'https://example.com/wp-plugin-mold-update' => $this->details['plugin_update_uri'],
			'wp-plugin-mold' => $this->details['plugin_domain_path'],
			'WpPluginMold' => $this->details['namespace'],
		];

		foreach ( $replacements as $old => $new ) {
			$plugin_content = str_replace( $old, $new, $plugin_content );
		}

		file_put_contents( $plugin_file, $plugin_content );
		echo "Main plugin file updated.\n";
	}

	/**
	 * Update the namespace in the given directory.
	 *
	 * @param string $directory_path The path to the directory to update.
	 * @return void
	 */
	private function update_directory_namespace( string $directory_path ): void {
		if ( ! is_dir( $directory_path ) ) {
			echo "The directory {$directory_path} does not exist. Please check your project structure.\n";
			return;
		}

		$directory = new RecursiveDirectoryIterator( $directory_path );
		$iterator = new RecursiveIteratorIterator( $directory );

		foreach ( $iterator as $file ) {
			if ( $file->getExtension() === 'php' ) {
				$content = file_get_contents( $file->getPathname() );
				$content = str_replace( 'WpPluginMold', $this->details['namespace'], $content );
				file_put_contents( $file->getPathname(), $content );
			}
		}

		echo "Namespace updated in {$directory_path} directory.\n";
	}

	/**
	 * Generate the namespace from the plugin name.
	 *
	 * The namespace is generated by converting the plugin name to PascalCase.
	 *
	 * @param string $plugin_name The plugin name.
	 * @return string
	 */
	private function generate_namespace( string $plugin_name ): string {
		$name_parts = explode( ' ', strtolower( $plugin_name ) );
		$namespace = array_map( 'ucfirst', $name_parts );
		return implode( '', $namespace );
	}

	/**
	 * Generate the text domain from the plugin name.
	 *
	 * The text domain is generated by converting the plugin name to kebab-case.
	 *
	 * @param string $plugin_name The plugin name.
	 * @return string
	 */
	private function generate_text_domain( string $plugin_name ): string {
		$name_parts = explode( ' ', strtolower( $plugin_name ) );
		return implode( '-', $name_parts );
	}

	/**
	 * Update the composer.json file with the plugin details.
	 *
	 * @return void
	 */
	private function update_composer_json(): void {
		$composer_file = $this->project_root . DIRECTORY_SEPARATOR . 'composer.json';
		if ( file_exists( $composer_file ) ) {
			$composer_content = json_decode( file_get_contents( $composer_file ), true );

			// Update relevant fields in the composer.json file
			$composer_content['name'] = $this->details['composer_name'];
			$composer_content['description'] = $this->details['plugin_description'];
			$composer_content['authors'][0]['name'] = $this->details['plugin_author'];
			$composer_content['authors'][0]['email'] = $this->details['author_email'];

			// Update the namespaces in autoload and autoload-dev
			$namespace = $this->details['namespace'] . '\\';
			$composer_content['autoload']['psr-4'] = [ $namespace => 'src/' ];
			if ( isset( $composer_content['autoload-dev']['psr-4'] ) ) {
				$composer_content['autoload-dev']['psr-4'] = [ $namespace . 'Test\\' => 'tests/' ];
			}

			file_put_contents( $composer_file, json_encode( $composer_content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
			echo "Composer.json updated.\n";
		}
	}

	/**
	 * Regenerate the Composer autoload files.
	 *
	 * @return void
	 */
	private function regenerate_autoload_files(): void {
		echo "Attempting to regenerate Composer autoload files...\n";
		exec( 'composer dump-autoload -o', $output, $return_var );
		if ( $return_var === 0 ) {
			echo "Autoload files successfully updated.\n";
		} else {
			echo "Failed to update autoload files automatically.\n";
			echo "Please run 'composer dump-autoload' manually to update autoload files.\n";
		}
	}

	/**
	 * Update the test.yml file with the plugin details.
	 *
	 * @return void
	 */
	private function update_test_yml(): void {
		$test_yml_file = $this->project_root . DIRECTORY_SEPARATOR . '.github' . DIRECTORY_SEPARATOR . 'workflows' . DIRECTORY_SEPARATOR . 'tests.yml';
		if ( file_exists( $test_yml_file ) ) {
			$test_yml_content = file_get_contents( $test_yml_file );
			$plugin_name_slug = strtolower( str_replace( ' ', '-', $this->details['plugin_name'] ) );
			$test_yml_content = str_replace( 'wp-plugin-mold', $plugin_name_slug, $test_yml_content );
			file_put_contents( $test_yml_file, $test_yml_content );
			echo "tests.yml file updated.\n";
		}
	}

	/**
	 * Execute the setup process.
	 *
	 * @return void
	 */
	public function execute_setup(): void {
		$this->update_plugin_file();
		$this->update_directory_namespace( $this->project_root . DIRECTORY_SEPARATOR . 'src' );
		$this->update_directory_namespace( $this->project_root . DIRECTORY_SEPARATOR . 'tests' );
		$this->update_composer_json();
		$this->update_test_yml();
		$this->regenerate_autoload_files();
		echo "Your plugin setup is now complete.\n";
	}
}

// To run the setup.
PluginSetup::run();
