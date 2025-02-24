<?php
/**
 * Plugin Name: Flormar Test Slider
 * Description: A WordPress plugin a WooCommerce product slider.
 * Version: 1.0.0
 * Author: A.Kryvoviazov
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Register the shortcode
function flormar_register_slider_shortcode() {
    add_shortcode('flormar-test-slider', 'flormar_render_slider');
}
add_action('init', 'flormar_register_slider_shortcode');

// Render the slider placeholder
function flormar_render_slider() {
    return '<div class="flormar-slider">Slider will be here</div>';
}