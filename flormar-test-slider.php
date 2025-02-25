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



// add admin menu
function flormar_add_admin_menu() {
    add_options_page(
        'Flormar Test Slider', 
        'Flormar Slider', 
        'manage_options', 
        'flormar-slider-settings', 
        'flormar_slider_settings_page'
    );
}
add_action('admin_menu', 'flormar_add_admin_menu');

// Generate the settings page
function flormar_slider_settings_page() {
    ?>
    <div class="wrap">
        <h1>Flormar Test Slider - Налаштування</h1>
        <p>Вставте шорткод <code>[flormar-test-slider]</code> на в контент сторінки, щоб додати слайдер товарів WooCommerce.</p>
        <form method="post" action="options.php">
            <?php
            settings_fields('flormar_slider_settings_group');
            do_settings_sections('flormar-slider-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
function flormar_register_settings() {
    register_setting('flormar_slider_settings_group', 'flormar_min_price');
    register_setting('flormar_slider_settings_group', 'flormar_slider_title');
    register_setting('flormar_slider_settings_group', 'flormar_max_price');
    register_setting('flormar_slider_settings_group', 'flormar_product_limit');

    add_settings_section('flormar_slider_section', 'Налаштування слайдера', null, 'flormar-slider-settings');

    add_settings_field(
        'flormar_slider_title', 
        'Заголовок слайдера', 
        'flormar_slider_title_callback', 
        'flormar-slider-settings', 
        'flormar_slider_section'
    );


    add_settings_field(
        'flormar_min_price', 
        'Мінімальна ціна', 
        'flormar_min_price_callback', 
        'flormar-slider-settings', 
        'flormar_slider_section'
    );

    add_settings_field(
        'flormar_max_price', 
        'Максимальна ціна', 
        'flormar_max_price_callback', 
        'flormar-slider-settings', 
        'flormar_slider_section'
    );

    add_settings_field(
        'flormar_product_limit', 
        'Кількість товарів у слайдері', 
        'flormar_product_limit_callback', 
        'flormar-slider-settings', 
        'flormar_slider_section'
    );
}
add_action('admin_init', 'flormar_register_settings');

// Function to display the product limit field
function flormar_product_limit_callback() {
    $limit = get_option('flormar_product_limit', '6'); // Значення за замовчуванням
    echo "<input type='number' name='flormar_product_limit' value='" . esc_attr($limit) . "' min='1' max='20' />";
}



// Function to display the slider title field
function flormar_slider_title_callback() {
    $title = get_option('flormar_slider_title', 'המוצרים הנמכרים ביותר'); // Значення за замовчуванням
    echo "<input type='text' name='flormar_slider_title' value='" . esc_attr($title) . "' style='width: 100%;' />";
}


// Field to display the min price
function flormar_min_price_callback() {
    $min_price = get_option('flormar_min_price', '');
    echo "<input type='number' name='flormar_min_price' value='" . esc_attr($min_price) . "' />";
}
// Field to display the max price
function flormar_max_price_callback() {
    $max_price = get_option('flormar_max_price', '');
    echo "<input type='number' name='flormar_max_price' value='" . esc_attr($max_price) . "' />";
}




// Register the shortcode
function flormar_register_slider_shortcode() {
    add_shortcode('flormar-test-slider', 'flormar_render_slider');
}
add_action('init', 'flormar_register_slider_shortcode');

// Render the slider with custom navigation buttons
function flormar_render_slider($atts) {

    // Get the slider settings
    $slider_title = get_option('flormar_slider_title', 'המוצרים הנמכרים ביותר');
    $min_price = get_option('flormar_min_price', '');
    $max_price = get_option('flormar_max_price', '');
    $product_limit = get_option('flormar_product_limit', '');

    $product_limit = (!empty($product_limit) && (int)$product_limit > 0) ? (int)$product_limit : -1;

    // WP_Query arguments
    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $product_limit,
        'meta_query'     => []
    ];

    // Add price filter
    if (!empty($min_price) || !empty($max_price)) {
        $price_query = ['key' => '_price', 'type' => 'NUMERIC'];

        if (!empty($min_price)) {
            $price_query['value'][] = (int) $min_price;
            $price_query['compare'] = '>=';
        }

        if (!empty($max_price)) {
            $price_query['value'][] = (int) $max_price;
            $price_query['compare'] = '<=';
        }

        $args['meta_query'][] = $price_query;
    }

    // The Query
    $query = new WP_Query($args);

    ob_start();
    ?>
    <div class="flormar-slider-wrapper">
        <div class="flormar-slider-container">
            <h2 class="flormar-slider-title"><?php echo esc_html($slider_title); ?></h2>
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




