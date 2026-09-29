/**
 * Freight Cost Calculator
 *
 * Handles the front-end calculation without reloading the page.
 */

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('fcc-calculator-form');

    if (!form) {
        return;
    }

    const errorBox = document.getElementById('fcc-error');
    const resultBox =document.getElementById('fcc-result');
    const actualWeightElement = document.getElementById('fcc-actual-weight');
    const volumetricWeightElement = document.getElementById('fcc-volumetric-weight');
    const chargeableWeightElement = document.getElementById('fcc-chargeable-weight');
    const shippingCostElement = document.getElementById('fcc-shipping-cost');

    // Shipping rate comes from the plugin settings (default 2,500,000 Toman).
    const shippingRate =
        (window.fccData && parseFloat(window.fccData.shippingRate)) || 2500000;

    // Conversion factors to kilograms.
    const weightToKg = {
        kg: 1,
        g: 0.001,
        lb: 0.45359237,
        oz: 0.028349523125
    };

    // Conversion factors to centimeters.
    const lengthToCm = {
        cm: 1,
        in: 2.54
    };

    form.addEventListener('submit', function (event) {

        event.preventDefault();

        // Note: form.elements['length'] is the collection size, not the input,
        // so inputs are looked up with querySelector instead.
        const getField = function (name) {
            return form.querySelector('[name="' + name + '"]');
        };

        const getNumber = function (name) {
            const input = getField(name);
            return input ? parseFloat(input.value) || 0 : 0;
        };

        const showError = function (message) {
            errorBox.textContent = message;
            errorBox.hidden = false;
            resultBox.hidden = true;
        };

        errorBox.hidden = true;

        if (!getField('origin').value) {
            showError('لطفاً کشور مبدأ خرید را انتخاب کنید.');
            return;
        }

        const weightUnit = getField('weight_unit').value;
        const dimensionUnit = getField('dimension_unit').value;

        // Convert everything to kg / cm.
        const actualWeight = getNumber('weight') * (weightToKg[weightUnit] || 1);
        const factor = lengthToCm[dimensionUnit] || 1;
        const length = getNumber('length') * factor;
        const width = getNumber('width') * factor;
        const height = getNumber('height') * factor;

        if (actualWeight <= 0 || length <= 0 || width <= 0 || height <= 0) {
            showError('لطفاً وزن و ابعاد را به‌درستی (با اعداد انگلیسی و بزرگ‌تر از صفر) وارد کنید.');
            return;
        }

        // Volumetric weight = L × W × H / 6000.
        const volumetricWeight = (length * width * height) / 6000;

        // The larger of the two is the chargeable weight.
        const chargeableWeight = Math.max(actualWeight, volumetricWeight);

        const shippingCost = chargeableWeight * shippingRate;

        const formatWeight = function (value) {
            return value.toLocaleString('fa-IR', { maximumFractionDigits: 2 });
        };

        actualWeightElement.textContent = formatWeight(actualWeight);
        volumetricWeightElement.textContent = formatWeight(volumetricWeight);
        chargeableWeightElement.textContent = formatWeight(chargeableWeight);
        shippingCostElement.textContent =
            Math.round(shippingCost).toLocaleString('fa-IR');

        // The result box uses the `hidden` attribute.
        resultBox.hidden = false;
        resultBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
});
