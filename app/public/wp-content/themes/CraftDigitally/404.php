<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package CraftDigitally
 */

get_header();
?>

<main id="main-content" class="error-404-page">
  <div class="container">
    <section class="error-404 not-found">
      <div class="error-404-content">
        <h1 class="error-404-title">404</h1>
        <h2 class="error-404-subtitle"><?php esc_html_e('Oops! Page Not Found', 'craftdigitally'); ?></h2>
        <p class="error-404-message"><?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'craftdigitally'); ?></p>

        <div class="error-404-actions">
          <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary error-404-home-btn">
            <?php esc_html_e('Go Back Home', 'craftdigitally'); ?>
          </a>
        </div>
      </div>
    </section>
  </div>
</main>

<?php
get_footer();
?>

