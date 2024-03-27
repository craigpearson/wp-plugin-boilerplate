<?php

namespace WpPluginMold\Test\Example;

use WpPluginMold\Example\Example;
use WP_Mock\Tools\TestCase;
use WP_Mock;

/**
 * Class ExampleTest
 *
 * This class contains the unit tests for the Example class.
 */
class ExampleTest extends TestCase {

    /**
     * Set up the test environment before each test.
     *
     * Configures WP_Mock and sets up mocked WordPress functions used in the tests.
     */
    public function setUp(): void {
        WP_Mock::setUp();

        WP_Mock::userFunction('remove_action', [
            'times' => '0+', // Indicates the function can be called zero or more times.
            'args'  => [WP_Mock\Functions::type('string'), WP_Mock\Functions::type('callable')],
            'return' => true, // Mock the function to return true.
        ]);
    }

    /**
     * Tear down the test environment after each test.
     *
     * Cleans up the WP_Mock environment to ensure isolation between tests.
     */
    public function tearDown(): void {
        WP_Mock::tearDown();
    }

    /**
     * Test the boot method of the Example class.
     *
     * Ensures that the wp_head action is correctly added by the boot method.
     */
    public function test_boot(): void {
        $example = new Example();
        WP_Mock::expectActionAdded('wp_head', [$example, 'wp_head']);
        $example->boot();

        $this->assertHooksAdded();
    }

    /**
     * Test the wp_head method of the Example class.
     *
     * Ensures that the wp_head method outputs the correct string.
     */
    public function test_wp_head(): void {
        ob_start();
        (new Example())->wp_head();
        $output = ob_get_clean();

        $expected_output = '<!-- Example Plugin Output: ' . esc_html__('Hello, World!', 'wp-plugin-mold') . ' -->';
        $this->assertEquals($expected_output, $output);
    }
}
