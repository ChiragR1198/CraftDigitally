<?php
/**
 * The template for displaying WooCommerce pages
 *
 * @package CraftDigitally
 */

get_header();
?>

<main id="main-content" class="site-main woocommerce-page">
  <div class="container">
    <?php woocommerce_content(); ?>
  </div>
</main>

<?php
get_footer();

