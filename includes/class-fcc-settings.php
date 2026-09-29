<?php
/**
 * Plugin settings.
 *
 * @package Freight_Cost_Calculator
 */

defined( 'ABSPATH' ) || exit;

class FCC_Settings {

    /**
     * Register admin menu.
     *
     * @return void
     */
    public static function register_menu() {
        add_menu_page(
            'محاسبه هزینه حمل',
            'محاسبه هزینه حمل',
            'manage_options',
            'freight-cost-calculator',
            array( __CLASS__, 'render_page' ),
            'dashicons-airplane',
            58
        );
    }

    /**
     * Register settings.
     *
     * @return void
     */
    public static function register_settings() {
        register_setting(
            'fcc_settings_group',
            'fcc_shipping_rate',
            array(
                'type'              => 'number',
                'sanitize_callback' => array( __CLASS__, 'sanitize_shipping_rate' ),
                'default'           => 2500000,
            )
        );

        add_settings_section(
            'fcc_main_section',
            'تنظیمات هزینه حمل',
            '__return_false',
            'freight-cost-calculator'
        );

        add_settings_field(
            'fcc_shipping_rate',
            'هزینه حمل به ازای هر کیلوگرم',
            array( __CLASS__, 'shipping_rate_field' ),
            'freight-cost-calculator',
            'fcc_main_section'
        );
    }

    /**
     * Sanitize shipping rate.
     *
     * @param mixed $value Submitted value.
     * @return float
     */
    public static function sanitize_shipping_rate( $value ) {
        $value = (float) $value;

        return $value >= 0 ? $value : 0;
    }

    /**
     * Render shipping rate field.
     *
     * @return void
     */
    public static function shipping_rate_field() {
        $value = get_option( 'fcc_shipping_rate', 2500000 );
        ?>
        <input
            type="number"
            name="fcc_shipping_rate"
            value="<?php echo esc_attr( $value ); ?>"
            min="0"
            step="0.01"
            class="regular-text"
        />
        <span>تومان / کیلوگرم</span>
        <?php
    }

    /**
     * Render settings page.
     *
     * @return void
     */
    public static function render_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1>محاسبه هزینه حمل</h1>
            <p>شورت کد نمایش محاسبه گر
                <code>
                    [freight_calculator]
                </code>
            </p>

            <form method="post" action="options.php">
                <?php
                settings_fields( 'fcc_settings_group' );
                do_settings_sections( 'freight-cost-calculator' );
                submit_button( 'ذخیره تنظیمات' );
                ?>
            </form>
        </div>
        <?php
    }
}
