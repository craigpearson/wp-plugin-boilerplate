<?php

namespace WpPluginMold\Test\Example;

use WpPluginMold\Example\Example;
use WpPluginMold\Utils\Helpers;
use WP_Mock\Tools\TestCase;
use WP_Mock;

/**
 * Class ExampleTest
 *
 * Unit tests for the Example class.
 */
class ExampleTest extends TestCase {

    private $helpers;
    private $example;

    /**
     * Set up the test environment.
     */
    public function setUp(): void {
        WP_Mock::setUp();

        $this->helpers = \Mockery::mock(Helpers::class);
        $this->example = new Example($this->helpers);
    }

    /**
     * Tear down the test environment.
     */
    public function tearDown(): void {
        WP_Mock::tearDown();
    }

    /**
     * Test that the boot method adds the expected action.
     */
    public function testBoot() {
        WP_Mock::expectActionAdded('admin_menu', [$this->example, 'add_settings_submenu']);
        $this->example->boot();
        $this->assertHooksAdded();
    }

    /**
     * Test that the addSettingsSubmenu method calls the expected function.
     */
    public function testAddSettingsSubmenu() {
        WP_Mock::userFunction('add_submenu_page', [
            'args' => [
                'options-general.php',
                'WP Plugin Mold Settings',
                'WP Plugin Mold',
                'manage_options',
                'wp-plugin-mold-settings',
                [$this->example, 'settings_page_content']
            ],
            'times' => 1,
        ]);

        $this->example->add_settings_submenu();
        $this->assertConditionsMet();
    }

    /**
     * Test that the settingsPageContent method outputs the expected value.
     */
    public function testSettingsPageContent() {
        $this->helpers->shouldReceive('PLUGIN_VERSION')->andReturn('1.0.0');
        ob_start();
        $this->example->settings_page_content();
        $output = ob_get_clean();
        $this->assertEquals('1.0.0', $output);
    }
}
