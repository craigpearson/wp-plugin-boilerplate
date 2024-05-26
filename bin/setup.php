<?php
/**
 * Setup script for the plugin.
 *
 * @package wp-plugin-mold
 */

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
		$this->loadDefaults();
		$this->details = $this->promptForDetails();
	}

	/**
	 * Run the setup process.
	 */
	public static function run() {
		$setup = new self();
		$setup->executeSetup();
	}

	/**
	 * Load the default values for the plugin details.
	 */
	private function loadDefaults() {
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
	private function promptForDetails() {
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
			'plugin_license' => 'Plugin license',
			'plugin_license_uri' => 'Plugin license URI',
			'plugin_update_uri' => 'Plugin update URI',
			'plugin_domain_path' => 'Plugin domain path',
			'namespace' => 'PHP namespace',
		];

		foreach ( $fields as $key => $prompt ) {

			switch ( $key ) {
				case 'plugin_name':
					$details[ $key ] = readline( $prompt . ' [' . $this->defaults[ $key ] . ']: ' ) ?: $this->defaults[ $key ];
					// Update defaults based on the plugin name.
					$this->defaults['namespace'] = $this->generateNamespace( $details[ $key ] );
					$this->defaults['plugin_text_domain'] = $this->generateTextDomain( $details[ $key ] );
					break;
				case 'plugin_author_uri':
					$details[ $key ] = readline( $prompt . ' [' . $this->defaults[ $key ] . ']: ' ) ?: $this->defaults[ $key ];
					$this->defaults['plugin_uri'] = $details[ $key ];
					break;
				default:
					// Use the possibly updated defaults for the current key.
					$details[ $key ] = readline( $prompt . ' [' . $this->defaults[ $key ] . ']: ' ) ?: $this->defaults[ $key ];
					break;
			}
		}

		return $details;
	}

	/**
	 * Update the main plugin file with the plugin details.
	 */
	private function updatePluginFile() {
		$plugin_file = $this->project_root . DIRECTORY_SEPARATOR . 'wp-plugin-mold.php'; // Adjust to your main plugin file
		$plugin_content = file_get_contents( $plugin_file );
		$replacements = [
			'WP Plugin Mold' => $this->details['pluginName'],
			'https://example.com/wp-plugin-mold' => $this->details['pluginUri'],
			'A mold for creating WordPress plugins.' => $this->details['pluginDescription'],
			'1.0.0-alpha' => $this->details['pluginVersion'],
			'6.4.3' => $this->details['pluginRequiresAtLeast'],
			'8.0' => $this->details['pluginRequiresPhp'],
			'Your Name' => $this->details['pluginAuthor'],
			'https://example.com' => $this->details['pluginAuthorUri'],
			'GPL-3.0-or-later' => $this->details['pluginLicense'],
			'https://www.gnu.org/licenses/gpl-3.0.html' => $this->details['pluginLicenseUri'],
			'https://example.com/wp-plugin-mold-update' => $this->details['pluginUpdateUri'],
			'wp-plugin-mold' => $this->details['pluginTextDomain'],
			'/languages' => $this->details['pluginDomainPath'],
			'WpPluginMold' => $this->details['namespace'],
		];

		foreach ( $replacements as $old => $new ) {
			$plugin_content = str_replace( $old, $new, $plugin_content );
		}

		file_put_contents( $plugin_file, $plugin_content );
		echo "Main plugin file updated.\n";
	}

	/**
	 * Update the namespace in the src directory.
	 */
	private function updateSrcDirectory() {
		$src_directory = $this->project_root . DIRECTORY_SEPARATOR . 'src';
		if ( ! is_dir( $src_directory ) ) {
			echo "The src directory does not exist. Please check your project structure.\n";
			return;
		}

		$directory = new RecursiveDirectoryIterator( $src_directory );
		$iterator = new RecursiveIteratorIterator( $directory );

		foreach ( $iterator as $file ) {
			if ( $file->getExtension() === 'php' ) {
				$content = file_get_contents( $file->getPathname() );
				$content = str_replace( 'WpPluginMold', $this->details['namespace'], $content );
				file_put_contents( $file->getPathname(), $content );
			}
		}

		echo "Namespace updated in src directory.\n";
	}

	/**
	 * Generate the namespace from the plugin name.
	 *
	 * @param string $plugin_name The plugin name.
	 * @return string
	 */
	private function generateNamespace( $plugin_name ) {
		// Convert to PascalCase for the namespace
		$name_parts = explode( ' ', strtolower( $plugin_name ) );
		$namespace = array_map( 'ucfirst', $name_parts );
		return implode( '', $namespace );
	}

	/**
	 * Generate the text domain from the plugin name.
	 *
	 * @param string $plugin_name The plugin name.
	 * @return string
	 */
	private function generateTextDomain( $plugin_name ) {
		// Convert to kebab-case for the text domain
		$name_parts = explode( ' ', strtolower( $plugin_name ) );
		return implode( '-', $name_parts );
	}

	/**
	 * Update the composer.json file with the plugin details.
	 */
	private function updateComposerJson() {
		$composer_file = $this->project_root . DIRECTORY_SEPARATOR . 'composer.json';
		if ( file_exists( $composer_file ) ) {
			$composer_content = json_decode( file_get_contents( $composer_file ), true );
			// Update relevant fields in the composer.json file
			// Example: $composer_content['require']['php'] = $this->details['pluginRequiresPhp'];
			// $composer_content['name'] = strtolower(str_replace(' ', '-', $this->details['pluginName']));
			file_put_contents( $composer_file, json_encode( $composer_content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
			echo "Composer.json updated.\n";
		}
	}

	/**
	 * Regenerate the Composer autoload files.
	 */
	private function regenerateAutoloadFiles() {
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
	 * Execute the setup process.
	 */
	public function executeSetup() {
		$this->updatePluginFile();
		$this->updateSrcDirectory();
		$this->updateComposerJson();
		$this->regenerateAutoloadFiles();
		echo "Your plugin setup is now complete.\n";
	}
}

// To run the setup.
PluginSetup::run();
