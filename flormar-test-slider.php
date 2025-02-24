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
// Enqueue styles and scripts
function flormar_enqueue_assets() {
    // Swiper.js library
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], '11.0.0');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '11.0.0', true);

    // Plugin custom styles and scripts
    wp_enqueue_style('flormar-slider-css', plugin_dir_url(__FILE__) . 'assets/css/style.css', [], '1.0');
    wp_enqueue_script('flormar-slider-js', plugin_dir_url(__FILE__) . 'assets/js/script.js', ['jquery'], '1.0', true);
}
add_action('wp_enqueue_scripts', 'flormar_enqueue_assets');

// Register the shortcode
function flormar_register_slider_shortcode() {
    add_shortcode('flormar-test-slider', 'flormar_render_slider');
}
add_action('init', 'flormar_register_slider_shortcode');

// Render the slider HTML structure
function flormar_render_slider() {
    ob_start();
    ?>
    <div class="flormar-slider-container">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">Slide 1</div>
                <div class="swiper-slide">Slide 2</div>
                <div class="swiper-slide">Slide 3</div>
                <div class="swiper-slide">Slide 4</div>
                <div class="swiper-slide">Slide 5</div>
                <div class="swiper-slide">Slide 6</div>
            </div>
            <!-- Navigation buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
