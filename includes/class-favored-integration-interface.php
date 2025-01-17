<?php
use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

/**
 * Class for integrating with WooCommerce Blocks
 */
class Favored_Plugin_Integration implements IntegrationInterface {
	const VERSION = '1.0.0';

	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * The name of the integration.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'favored-plugin';
	}

	/**
	 * When called invokes any initialization/setup for the integration.
	 */
	public function initialize() {
		$script_path = 'build/frontend/cart-use-credit/index.js';
		// $style_path = 'build/frontend/cart-use-credit/style-index.css';

		$script_url = plugin_dir_url( dirname( __FILE__ ) ) . $script_path;
		// $style_url = plugin_dir_url( dirname( __FILE__ ) ) . $style_path;

		$script_asset_path = plugin_dir_path( dirname( __FILE__ ) ) . '/build/frontend/cart-use-credit/index.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array(),
				'version'      => $this->get_file_version( $script_path ),
			);

		// wp_enqueue_style(
		// 	'wc-blocks-integration',
		// 	$style_url,
		// 	[],
		// 	$this->get_file_version( $style_path )
		// );

		wp_enqueue_script(
			'wc-blocks-integration',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations(
			'wc-blocks-integration',
			'favcrm-for-woocommerce',
			plugin_dir_path( dirname( __FILE__ ) ) . '/languages'
		);

	}

	/**
	 * Returns an array of script handles to enqueue in the frontend context.
	 *
	 * @return string[]
	 */
	public function get_script_handles() {
		return array( 'wc-blocks-integration' );
	}

	/**
	 * Returns an array of script handles to enqueue in the editor context.
	 *
	 * @return string[]
	 */
	public function get_editor_script_handles() {
		return array( 'wc-blocks-integration' );
	}

	/**
	 * An array of key, value pairs of data made available to the block on the client side.
	 *
	 * @return array
	 */
	public function get_script_data() {
	    // $woocommerce_example_plugin_data = some_expensive_serverside_function();
	    return [
	        // 'expensive_data_calculation' => $woocommerce_example_plugin_data
        ];
	}

	/**
	 * Get the file modified time as a cache buster if we're in dev mode.
	 *
	 * @param string $file Local path to the file.
	 * @return string The cache buster value to use for the given file.
	 */
	protected function get_file_version( $file ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && file_exists( $file ) ) {
			return filemtime( $file );
		}

		return \Favored_Plugin_Integration::VERSION;
	}
}
