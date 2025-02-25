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

// Render the slider with custom navigation buttons
function flormar_render_slider($atts) {
    // Get shortcode attributes
    $atts = shortcode_atts([
        'min-price' => '',
        'max-price' => ''
    ], $atts);

    // WP_Query arguments
    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 6,
        'meta_query'     => []
    ];

    // Додаємо фільтрацію за ціною
    if (!empty($atts['min-price']) || !empty($atts['max-price'])) {
        $price_query = ['key' => '_price', 'type' => 'NUMERIC'];

        if (!empty($atts['min-price'])) {
            $price_query['value'][] = (int) $atts['min-price'];
            $price_query['compare'] = '>=';
        }

        if (!empty($atts['max-price'])) {
            $price_query['value'][] = (int) $atts['max-price'];
            $price_query['compare'] = '<=';
        }

        $args['meta_query'][] = $price_query;
    }

    // Запит товарів
    $query = new WP_Query($args);

    ob_start();
    ?>
    <div class="flormar-slider-wrapper">
        <div class="flormar-slider-container">
            <h2 class="flormar-slider-title">המוצרים הנמכרים ביותר</h2>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php if ($query->have_posts()): while ($query->have_posts()): $query->the_post();
                        global $product;
                    ?>
                        <div class="swiper-slide">
                            <div class="product-card">
                                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="product-button">
                                    <div class="product-image">
                                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" alt="<?php the_title(); ?>">
                                    </div>
                                    <h3 class="product-title"><?php the_title(); ?></h3>
                                    <p class="product-price"><?php echo wc_price($product->get_price()); ?></p>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); endif; ?>
                </div>
                <div class="swiper-button-prev">
                <svg width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="19.1297" cy="19.1297" r="19.1297" transform="rotate(-180 19.1297 19.1297)" fill="#403C3D"/>
                        <g clip-path="url(#clip0_410_126)">
                            <path d="M22.3309 31.4307L25.0169 28.6948L15.9596 19.575L25.0169 10.5177L22.3309 7.76926L13.2111 16.889L10.4627 19.575L13.2111 22.3234L22.3309 31.4432L22.3309 31.4307Z" fill="white"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_410_126">
                                <rect width="24.9857" height="24.9857" fill="white" transform="translate(32.0129 32.0129) rotate(-180)"/>
                            </clipPath>
                        </defs>
                    </svg>
                </div>
                <div class="swiper-button-next">
                <svg width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg" style="transform: rotate(180deg);">
                        <circle cx="19.1297" cy="19.1297" r="19.1297" transform="rotate(-180 19.1297 19.1297)" fill="#403C3D"/>
                        <g clip-path="url(#clip0_410_126)">
                            <path d="M22.3309 31.4307L25.0169 28.6948L15.9596 19.575L25.0169 10.5177L22.3309 7.76926L13.2111 16.889L10.4627 19.575L13.2111 22.3234L22.3309 31.4432L22.3309 31.4307Z" fill="white"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_410_126">
                                <rect width="24.9857" height="24.9857" fill="white" transform="translate(32.0129 32.0129) rotate(-180)"/>
                            </clipPath>
                        </defs>
                    </svg>
                </div>
                
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('flormar-test-slider', 'flormar_render_slider');




