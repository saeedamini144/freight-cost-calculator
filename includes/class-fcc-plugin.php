<?php
/**
 * Main plugin class.
 *
 * @package Freight_Cost_Calculator
 */

defined( 'ABSPATH' ) || exit;

class FCC_Plugin {

    /**
     * Plugin instance.
     *
     * @var FCC_Plugin|null
     */
    private static $instance = null;

    /**
     * Get plugin instance.
     *
     * @return FCC_Plugin
     */
    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Load plugin classes.
     *
     * @return void
     */
    private function load_dependencies() {
        require_once FCC_PATH . 'includes/class-fcc-calculator.php';
        require_once FCC_PATH . 'includes/class-fcc-settings.php';
        require_once FCC_PATH . 'includes/class-fcc-shortcode.php';
    }

    /**
     * Register plugin hooks.
     *
     * @return void
     */
    private function init_hooks() {
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        add_action( 'init', array( 'FCC_Shortcode', 'register' ) );
        add_action( 'admin_menu', array( 'FCC_Settings', 'register_menu' ) );
        add_action( 'admin_init', array( 'FCC_Settings', 'register_settings' ) );
        add_action( 'wp_enqueue_scripts', array( 'FCC_Shortcode', 'enqueue_assets' ) );
        add_filter( 'plugin_action_links_' . plugin_basename( FCC_FILE ), array( $this, 'add_settings_link' ) );
    }

    /**
     * Add a settings link on the plugins list page.
     *
     * @param array $links Existing links.
     * @return array
     */
    public function add_settings_link( $links ) {
        $url = admin_url( 'admin.php?page=freight-cost-calculator' );
        array_unshift( $links, '<a href="' . esc_url( $url ) . '">تنظیمات</a>' );

        return $links;
    }

    /**
     * Load plugin translations.
     *
     * @return void
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'freight-cost-calculator',
            false,
            dirname( plugin_basename( FCC_FILE ) ) . '/languages'
        );
    }
}
