<?php
/**
 * Plugin Name: Freight Cost Calculator
 * Plugin URI: https://example.com/
 * Description: محاسبه هزینه حمل بار بر اساس وزن واقعی و وزن حجمی.
 * Version: 0.1.0
 * Author: Saeed Amini
 * Text Domain: freight-cost-calculator
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'FCC_VERSION', '0.1.0' );
define( 'FCC_FILE', __FILE__ );
define( 'FCC_PATH', plugin_dir_path( __FILE__ ) );
define( 'FCC_URL', plugin_dir_url( __FILE__ ) );

require_once FCC_PATH . 'includes/class-fcc-plugin.php';

function fcc_run_plugin() {
    return FCC_Plugin::instance();
}

fcc_run_plugin();
