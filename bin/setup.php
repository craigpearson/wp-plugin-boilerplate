<?php

echo "Let's set up your new plugin\n";

if (file_exists(__DIR__ . '/defaults.php')) {
    include __DIR__ . '/defaults.php';
}

// Prompt for plugin details
$pluginName = readline('Plugin Name [' . $plugin_name . ']: ') ?: $plugin_name;

$pluginUri = readline('Plugin URI [' . $plugin_uri . ']: ') ?: $plugin_uri;
$pluginDescription = readline('Plugin description [' . $plugin_description . ']: ') ?: $plugin_description;
$pluginVersion = readline('Plugin version [' . $plugin_version . ']: ') ?: $plugin_version;
$pluginRequiresAtLeast = readline('Required WordPress version [' . $plugin_requires_wp_at_least . ']: ') ?: $plugin_requires_wp_at_least;
$pluginRequiresPhp = readline('Required PHP version [' . $plugin_requires_php . ']: ') ?: $plugin_requires_php;
$pluginAuthor = readline('Plugin author [' . $plugin_author . ']: ') ?: $plugin_author;
$pluginAuthorUri = readline('Plugin author URI [' . $plugin_author_uri . ']: ') ?: $plugin_author_uri;
$pluginLicense = readline('Plugin license [' . $plugin_license . ']: ') ?: $plugin_license;
$pluginLicenseUri = readline('Plugin license URI [' . $plugin_license_uri . ']: ') ?: $plugin_license_uri;
$pluginUpdateUri = readline('Plugin update URI [' . $plugin_update_uri . ']: ') ?: $plugin_update_uri;
$pluginTextDomain = readline('Plugin text domain [' . $plugin_text_domain . ']: ') ?: $plugin_text_domain;
$pluginDomainPath = readline('Plugin domain path [' . $plugin_domain_path . ']: ') ?: $plugin_domain_path;
$namespace = readline('Namespace for your plugin [' . $namespace . ']: ') ?: $namespace;

// Main WordPress Plugin File Customizations
$projectRoot = dirname(__DIR__);
$pluginFile = $projectRoot . DIRECTORY_SEPARATOR . 'wp-plugin-mold.php'; // Adjust to your main plugin file
$composerFile = $projectRoot . DIRECTORY_SEPARATOR . 'composer.json';
$srcDirectory = $projectRoot . DIRECTORY_SEPARATOR . 'src';


echo "Changing to project root directory: $projectRoot\n";
chdir($projectRoot);

// Check if the src directory exists
if (!is_dir($srcDirectory)) {
    echo "The src directory does not exist. Please check your project structure.\n";
    exit;
}

// Update the main plugin file
$pluginContent = file_get_contents($pluginFile);
$replacements = [
    'WP Plugin Mold' => $pluginName,
    'https://example.com/wp-plugin-mold' => $pluginUri,
    'A mold for creating WordPress plugins.' => $pluginDescription,
    '1.0.0' => $pluginVersion,
    '6.4.3' => $pluginRequiresAtLeast,
    '8.0' => $pluginRequiresPhp,
    'Your Name' => $pluginAuthor,
    'https://example.com' => $pluginAuthorUri,
    'GPL v2 or later' => $pluginLicense,
    'https://www.gnu.org/licenses/gpl-2.0.html' => $pluginLicenseUri,
    'https://example.com/wp-plugin-mold-update' => $pluginUpdateUri,
    'wp-plugin-mold' => $pluginTextDomain,
    '/languages' => $pluginDomainPath,
    'WpPluginMold' => $namespace
];

foreach ($replacements as $old => $new) {
    $pluginContent = str_replace($old, $new, $pluginContent);
}

file_put_contents($pluginFile, $pluginContent);

echo "Main plugin file updated.\n";

// Iterate through all PHP files in the src directory
$directory = new RecursiveDirectoryIterator($srcDirectory);
$iterator = new RecursiveIteratorIterator($directory);

foreach ($iterator as $file) {
    if ($file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $content = str_replace('WpPluginMold', $namespace, $content);
        file_put_contents($file->getPathname(), $content);
    }
}

echo "Namespace updated in src directory.\n";

// Update composer.json file - We'll come back this shortly
if (file_exists($composerFile)) {
    $composerContent = json_decode(file_get_contents($composerFile), true);
    // Update relevant fields in the composer.json file
    // Example: $composerContent['require']['php'] = $pluginRequiresPhp;
	// $composerContent['name'] = strtolower(str_replace(' ', '-', $pluginName));
    file_put_contents($composerFile, json_encode($composerContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

echo "Composer.json updated.\n";

echo "Attempting to regenerate Composer autoload files...\n";

// Execute 'composer dump-autoload' and capture output and return value
exec('composer dump-autoload', $output, $returnVal);

if ($returnVal === 0) {
    // If the command executed successfully
    echo "Autoload files successfully updated.\n";
} else {
    // If the command failed
    echo "Failed to update autoload files automatically.\n";
    echo "Please run 'composer dump-autoload' manually to update autoload files.\n";
}

echo "Your plugin setup is now complete.\n";
