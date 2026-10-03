<?php

/**
 * Shortcode and frontend assets.
 *
 * @package Freight_Cost_Calculator
 */

// اگر فایل مستقیماً از خارج وردپرس اجرا شود، اجرای آن متوقف می‌شود.
defined('ABSPATH') || exit;

// کلاس مربوط به شورت‌کد محاسبه‌گر.
class FCC_Shortcode
{

    /**
     * Register shortcode.
     *
     * @return void
     */
    public static function register()
    {

        // شورت‌کد [freight_calculator] را در وردپرس ثبت می‌کنیم.
        add_shortcode(
            'freight_calculator',
            array(__CLASS__, 'render')
        );
    }

    /**
     * Enqueue frontend assets.
     *
     * @return void
     */
    public static function enqueue_assets()
    {

        // فایل CSS محاسبه‌گر را ثبت می‌کنیم.
        wp_register_style(
            'fcc-calculator',
            FCC_URL . 'assets/css/calculator.css',
            array(),
            FCC_VERSION
        );

        // فایل JavaScript محاسبه‌گر را ثبت می‌کنیم.
        wp_register_script(
            'fcc-calculator',
            FCC_URL . 'assets/js/calculator.js',
            array(),
            FCC_VERSION,
            true
        );
    }

    /**
     * Render calculator.
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public static function render($atts)
    {

        // CSS محاسبه‌گر را فقط زمانی که شورت‌کد استفاده شده enqueue می‌کنیم.
        wp_enqueue_style('fcc-calculator');

        // JavaScript محاسبه‌گر را فقط زمانی که شورت‌کد استفاده شده enqueue می‌کنیم.
        wp_enqueue_script('fcc-calculator');

        // نرخ حمل تنظیم‌شده در پنل مدیریت را به JavaScript می‌دهیم.
        wp_localize_script(
            'fcc-calculator',
            'fccData',
            array(
                'shippingRate' => (float) get_option('fcc_shipping_rate', 2500000),
            )
        );

        // خروجی HTML را داخل Buffer قرار می‌دهیم.
        ob_start();
?>

        <!-- کانتینر اصلی محاسبه‌گر -->
        <div class="fcc-calculator">

            <!-- عنوان محاسبه‌گر -->
            <!-- <div class="fcc-calculator__header">

                <h2 class="fcc-calculator__title">
                    <?php
                    echo esc_html__(
                        'محاسبه هزینه حمل بار',
                        'freight-cost-calculator'
                    );
                    ?>
                </h2>

                <p class="fcc-calculator__description">
                    <?php
                    echo esc_html__(
                        'هزینه تقریبی حمل بار خود را بر اساس وزن و ابعاد بسته محاسبه کنید.',
                        'freight-cost-calculator'
                    );
                    ?>
                </p>

            </div> -->

            <!-- فرم محاسبه‌گر -->
            <form class="fcc-calculator__form" id="fcc-calculator-form">

                <!-- انتخاب کشور مبدأ -->
                <div class="fcc-field">

                    <label
                        class="fcc-field__label"
                        for="fcc-origin">
                        <?php
                        echo esc_html__(
                            'خرید از',
                            'freight-cost-calculator'
                        );
                        ?>
                    </label>

                    <select
                        class="fcc-field__input"
                        id="fcc-origin"
                        name="origin"
                        required>

                        <option value="" selected disabled>
                            <?php echo esc_html__('انتخاب کنید', 'freight-cost-calculator'); ?>
                        </option>

                        <option value="china">
                            <?php echo esc_html__('چین', 'freight-cost-calculator'); ?>
                        </option>

                        <option value="russia">
                            <?php echo esc_html__('روسیه', 'freight-cost-calculator'); ?>
                        </option>

                        <option value="turkey">
                            <?php echo esc_html__('ترکیه', 'freight-cost-calculator'); ?>
                        </option>

                        <option value="uae">
                            <?php echo esc_html__('امارات', 'freight-cost-calculator'); ?>
                        </option>

                        <option value="europe">
                            <?php echo esc_html__('اروپا', 'freight-cost-calculator'); ?>
                        </option>

                        <option value="usa">
                            <?php echo esc_html__('آمریکا', 'freight-cost-calculator'); ?>
                        </option>

                    </select>

                </div>

                <!-- قیمت محصول -->
                <div class="fcc-field">

                    <label
                        class="fcc-field__label"
                        for="fcc-product-price">
                        <?php
                        echo esc_html__(
                            'قیمت محصول خریداری شده',
                            'freight-cost-calculator'
                        );
                        ?>
                    </label>

                    <!-- گروه قیمت و واحد پول -->
                    <div class="fcc-input-group">

                        <!-- مقدار قیمت -->
                        <input
                            class="fcc-field__input fcc-field__input--number"
                            type="number"
                            id="fcc-product-price"
                            name="product_price"
                            min="0"
                            step="any"
                            placeholder="مثلاً 1000" />

                        <!-- واحد پول -->
                        <select
                            class="fcc-field__input fcc-field__input--unit"
                            id="fcc-currency"
                            name="currency">

                            <option value="usd">
                                <?php echo esc_html__('دلار', 'freight-cost-calculator'); ?>
                            </option>

                            <option value="eur">
                                <?php echo esc_html__('یورو', 'freight-cost-calculator'); ?>
                            </option>

                            <option value="aed">
                                <?php echo esc_html__('درهم', 'freight-cost-calculator'); ?>
                            </option>

                            <option value="try">
                                <?php echo esc_html__('لیر', 'freight-cost-calculator'); ?>
                            </option>

                            <option value="gbp">
                                <?php echo esc_html__('پوند', 'freight-cost-calculator'); ?>
                            </option>

                        </select>

                    </div>

                </div>

                <!-- وزن محصول -->
                <div class="fcc-field">

                    <label
                        class="fcc-field__label"
                        for="fcc-weight">
                        <?php
                        echo esc_html__(
                            'وزن',
                            'freight-cost-calculator'
                        );
                        ?>
                    </label>

                    <!-- گروه وزن و واحد -->
                    <div class="fcc-input-group">

                        <!-- مقدار وزن -->
                        <input
                            class="fcc-field__input fcc-field__input--number"
                            type="number"
                            id="fcc-weight"
                            name="weight"
                            min="0"
                            step="any"
                            placeholder="مثلاً 25"
                            required />

                        <!-- واحد وزن -->
                        <select
                            class="fcc-field__input fcc-field__input--unit"
                            id="fcc-weight-unit"
                            name="weight_unit">

                            <option value="kg">
                                <?php echo esc_html__('کیلوگرم', 'freight-cost-calculator'); ?>
                            </option>

                            <option value="g">
                                <?php echo esc_html__('گرم', 'freight-cost-calculator'); ?>
                            </option>

                            <option value="lb">
                                <?php echo esc_html__('پوند', 'freight-cost-calculator'); ?>
                            </option>

                            <option value="oz">
                                <?php echo esc_html__('اونس', 'freight-cost-calculator'); ?>
                            </option>

                        </select>

                    </div>

                </div>

                <!-- ابعاد بسته -->
                <div class="fcc-field">

                    <label class="fcc-field__label">
                        <?php
                        echo esc_html__(
                            'ابعاد بسته',
                            'freight-cost-calculator'
                        );
                        ?>
                    </label>

                    <!-- سه ورودی طول، عرض و ارتفاع -->
                    <div class="fcc-dimensions">

                        <!-- طول -->
                        <div class="fcc-dimension">

                            <label
                                for="fcc-length"
                                class="fcc-dimension__label">
                                <?php
                                echo esc_html__(
                                    'طول',
                                    'freight-cost-calculator'
                                );
                                ?>
                            </label>

                            <input
                                class="fcc-field__input"
                                type="number"
                                id="fcc-length"
                                name="length"
                                min="0"
                                step="any"
                                placeholder="طول"
                                required />

                        </div>

                        <!-- عرض -->
                        <div class="fcc-dimension">

                            <label
                                for="fcc-width"
                                class="fcc-dimension__label">
                                <?php
                                echo esc_html__(
                                    'عرض',
                                    'freight-cost-calculator'
                                );
                                ?>
                            </label>

                            <input
                                class="fcc-field__input"
                                type="number"
                                id="fcc-width"
                                name="width"
                                min="0"
                                step="any"
                                placeholder="عرض"
                                required />

                        </div>

                        <!-- ارتفاع -->
                        <div class="fcc-dimension">

                            <label
                                for="fcc-height"
                                class="fcc-dimension__label">
                                <?php
                                echo esc_html__(
                                    'ارتفاع',
                                    'freight-cost-calculator'
                                );
                                ?>
                            </label>

                            <input
                                class="fcc-field__input"
                                type="number"
                                id="fcc-height"
                                name="height"
                                min="0"
                                step="any"
                                placeholder="ارتفاع"
                                required />

                        </div>

                        <!-- واحد اندازه‌گیری ابعاد (ستون چهارم در همان ردیف) -->
                        <div class="fcc-dimension fcc-dimension--unit">

                            <label
                                class="fcc-dimension__label"
                                for="fcc-dimension-unit">
                                <?php
                                echo esc_html__(
                                    'واحد اندازه‌گیری',
                                    'freight-cost-calculator'
                                );
                                ?>
                            </label>

                            <select
                                class="fcc-field__input"
                                id="fcc-dimension-unit"
                                name="dimension_unit">

                                <option value="cm">
                                    <?php
                                    echo esc_html__(
                                        'سانتی‌متر',
                                        'freight-cost-calculator'
                                    );
                                    ?>
                                </option>

                                <option value="in">
                                    <?php
                                    echo esc_html__(
                                        'اینچ',
                                        'freight-cost-calculator'
                                    );
                                    ?>
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <!-- پیام خطا -->
                <div class="fcc-error" id="fcc-error" role="alert" hidden></div>

                <!-- دکمه محاسبه -->
                <div class="fcc-actions">

                    <button
                        type="submit"
                        class="fcc-submit">
                        <?php
                        echo esc_html__(
                            'محاسبه هزینه حمل',
                            'freight-cost-calculator'
                        );
                        ?>
                    </button>

                </div>

            </form>

            <!-- محل نمایش نتیجه -->
            <div
                class="fcc-result"
                id="fcc-result"
                hidden>

                <div class="fcc-result__header">

                    <h3 class="fcc-result__title">
                        <?php
                        echo esc_html__(
                            'نتیجه محاسبه',
                            'freight-cost-calculator'
                        );
                        ?>
                    </h3>

                </div>

                <!-- وزن واقعی -->
                <div class="fcc-result__row">

                    <span class="fcc-result__label">
                        <?php
                        echo esc_html__(
                            'وزن واقعی',
                            'freight-cost-calculator'
                        );
                        ?>
                    </span>

                    <strong
                        class="fcc-result__value"
                        id="fcc-actual-weight">
                        0
                    </strong>

                    <span>کیلوگرم</span>

                </div>

                <!-- وزن حجمی -->
                <div class="fcc-result__row">

                    <span class="fcc-result__label">
                        <?php
                        echo esc_html__(
                            'وزن حجمی',
                            'freight-cost-calculator'
                        );
                        ?>
                    </span>

                    <strong
                        class="fcc-result__value"
                        id="fcc-volumetric-weight">
                        0
                    </strong>

                    <span>کیلوگرم</span>

                </div>

                <!-- وزن قابل محاسبه -->
                <div class="fcc-result__row">

                    <span class="fcc-result__label">
                        <?php
                        echo esc_html__(
                            'وزن محاسباتی',
                            'freight-cost-calculator'
                        );
                        ?>
                    </span>

                    <strong
                        class="fcc-result__value"
                        id="fcc-chargeable-weight">
                        0
                    </strong>

                    <span>کیلوگرم</span>

                </div>

                <!-- هزینه نهایی -->
                <div class="fcc-result__total">

                    <span class="fcc-result__total-label">
                        <?php
                        echo esc_html__(
                            'هزینه حمل',
                            'freight-cost-calculator'
                        );
                        ?>
                    </span>

                    <strong
                        class="fcc-result__total-value"
                        id="fcc-shipping-cost">
                        0
                    </strong>

                    <span>تومان</span>

                </div>

            </div>

        </div>

<?php

        // خروجی Buffer را به صورت string برمی‌گردانیم.
        return ob_get_clean();
    }
}
