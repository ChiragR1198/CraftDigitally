<?php
/**
 * WooCommerce Compatibility File
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * WooCommerce setup function
 */
function craftdigitally_woocommerce_setup() {
  add_theme_support('woocommerce');
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'craftdigitally_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets
 */
function craftdigitally_woocommerce_scripts() {
  wp_enqueue_style('craftdigitally-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce.css', array(), wp_get_theme()->get('Version'));
}
add_action('wp_enqueue_scripts', 'craftdigitally_woocommerce_scripts');

/**
 * Disable default WooCommerce stylesheet
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Add 'woocommerce-active' class to the body tag
 */
function craftdigitally_woocommerce_active_body_class($classes) {
  $classes[] = 'woocommerce-active';
  return $classes;
}
add_filter('body_class', 'craftdigitally_woocommerce_active_body_class');

/**
 * Products per page
 */
function craftdigitally_woocommerce_products_per_page() {
  return 12;
}
add_filter('loop_shop_per_page', 'craftdigitally_woocommerce_products_per_page');

/**
 * Product gallery thumbnail columns
 */
function craftdigitally_woocommerce_thumbnail_columns() {
  return 4;
}
add_filter('woocommerce_product_thumbnails_columns', 'craftdigitally_woocommerce_thumbnail_columns');

/**
 * Related Products Args
 */
function craftdigitally_woocommerce_related_products_args($args) {
  $defaults = array(
    'posts_per_page' => 3,
    'columns' => 3,
  );
  $args = wp_parse_args($defaults, $args);
  return $args;
}
add_filter('woocommerce_output_related_products_args', 'craftdigitally_woocommerce_related_products_args');

/**
 * Remove default WooCommerce wrapper
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

if (!function_exists('craftdigitally_woocommerce_wrapper_before')) {
  /**
   * Before Content
   */
  function craftdigitally_woocommerce_wrapper_before() {
    ?>
    <div id="primary" class="content-area">
      <main id="main" class="site-main" role="main">
    <?php
  }
}
add_action('woocommerce_before_main_content', 'craftdigitally_woocommerce_wrapper_before');

if (!function_exists('craftdigitally_woocommerce_wrapper_after')) {
  /**
   * After Content
   */
  function craftdigitally_woocommerce_wrapper_after() {
    ?>
      </main>
    </div>
    <?php
  }
}
add_action('woocommerce_after_main_content', 'craftdigitally_woocommerce_wrapper_after');

/**
 * Sample implementation of the WooCommerce Mini Cart
 */
if (!function_exists('craftdigitally_woocommerce_cart_link_fragment')) {
  /**
   * Cart Fragments
   */
  function craftdigitally_woocommerce_cart_link_fragment($fragments) {
    ob_start();
    craftdigitally_woocommerce_cart_link();
    $fragments['a.cart-contents'] = ob_get_clean();
    return $fragments;
  }
}
add_filter('woocommerce_add_to_cart_fragments', 'craftdigitally_woocommerce_cart_link_fragment');

if (!function_exists('craftdigitally_woocommerce_cart_link')) {
  /**
   * Cart Link
   */
  function craftdigitally_woocommerce_cart_link() {
    ?>
    <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'craftdigitally'); ?>">
      <?php
      $item_count_text = sprintf(
        /* translators: number of items in the mini cart */
        _n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'craftdigitally'),
        WC()->cart->get_cart_contents_count()
      );
      ?>
      <span class="amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span> <span class="count"><?php echo esc_html($item_count_text); ?></span>
    </a>
    <?php
  }
}

if (!function_exists('craftdigitally_woocommerce_header_cart')) {
  /**
   * Display Header Cart
   */
  function craftdigitally_woocommerce_header_cart() {
    if (is_cart()) {
      $class = 'current-menu-item';
    } else {
      $class = '';
    }
    ?>
    <ul id="site-header-cart" class="site-header-cart">
      <li class="<?php echo esc_attr($class); ?>">
        <?php craftdigitally_woocommerce_cart_link(); ?>
      </li>
      <li>
        <?php
        $instance = array(
          'title' => '',
        );
        the_widget('WC_Widget_Cart', $instance);
        ?>
      </li>
    </ul>
    <?php
  }
}

