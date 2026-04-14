<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Adds custom classes to the array of body classes
 */
function craftdigitally_body_classes_extra($classes) {
  // Add class if sidebar is active
  if (is_active_sidebar('sidebar-1')) {
    $classes[] = 'has-sidebar';
  }

  // Add class for custom templates
  if (is_page_template()) {
    $classes[] = 'page-template';
  }

  return $classes;
}
add_filter('body_class', 'craftdigitally_body_classes_extra');

/**
 * Get JSON data from theme mod and decode it
 */
function craftdigitally_get_json_mod($mod_name, $default = array()) {
  $json = get_theme_mod($mod_name, '');

  if (empty($json)) {
    return $default;
  }

  $decoded = json_decode($json, true);

  if (json_last_error() !== JSON_ERROR_NONE) {
    return $default;
  }

  return $decoded;
}

/**
 * Get multiline text and convert to array
 */
function craftdigitally_get_multiline_mod($mod_name, $default = array()) {
  $text = get_theme_mod($mod_name, '');

  if (empty($text)) {
    return $default;
  }

  $lines = array_filter(array_map('trim', explode("\n", $text)));

  return !empty($lines) ? $lines : $default;
}

/**
 * Get testimonials data
 *
 * @return array
 */
function craftdigitally_get_testimonials() {
  require_once get_template_directory() . '/inc/default-data.php';
  $default = craftdigitally_get_default_testimonials();
  return craftdigitally_get_json_mod('craftdigitally_testimonials_data', $default);
}

/**
 * Get process steps data
 *
 * @return array
 */
function craftdigitally_get_process_steps() {
  require_once get_template_directory() . '/inc/default-data.php';
  $default = craftdigitally_get_default_process_steps();
  return craftdigitally_get_json_mod('craftdigitally_process_steps_data', $default);
}

/**
 * Get services data
 *
 * @return array
 */
function craftdigitally_get_services() {
  require_once get_template_directory() . '/inc/default-data.php';
  $default = craftdigitally_get_default_services();
  return craftdigitally_get_json_mod('craftdigitally_services_data', $default);
}

/**
 * Get why choose points
 *
 * @return array
 */
function craftdigitally_get_why_choose_points() {
  require_once get_template_directory() . '/inc/default-data.php';
  $default = craftdigitally_get_default_why_choose_points();
  return craftdigitally_get_multiline_mod('craftdigitally_why_choose_points', $default);
}

/**
 * Get the global CTA source page ID.
 *
 * We centralize the shared CTA on the static front page so it can be edited
 * once and reused everywhere.
 *
 * @return int|false
 */
function craftdigitally_get_shared_cta_source_id() {
  $front_page_id = (int) get_option('page_on_front');
  return $front_page_id > 0 ? $front_page_id : false;
}

/**
 * Get shared CTA content used across templates.
 *
 * @return array<string, string>
 */
function craftdigitally_get_shared_cta_data() {
  $source_id = craftdigitally_get_shared_cta_source_id();

  return array(
    'title' => craftdigitally_get_acf('home_cta_title', 'Ready to Dominate Your Competition?', $source_id),
    'subtitle' => craftdigitally_get_acf(
      'home_cta_subtitle',
      'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad',
      $source_id
    ),
    'name_placeholder' => craftdigitally_get_acf('home_cta_name_placeholder', 'Name', $source_id),
    'phone_placeholder' => craftdigitally_get_acf('home_cta_phone_placeholder', 'Phone', $source_id),
    'email_placeholder' => craftdigitally_get_acf('home_cta_email_placeholder', 'Email', $source_id),
    'service_placeholder' => craftdigitally_get_acf('home_cta_service_placeholder', 'Service', $source_id),
    'message_placeholder' => craftdigitally_get_acf('home_cta_message_placeholder', 'Message', $source_id),
    'button_label' => craftdigitally_get_acf('home_cta_button_label', "Let's Connect", $source_id),
  );
}

/**
 * Render the shared CTA section markup.
 *
 * @param array<string, mixed> $args Optional rendering args.
 * @return void
 */
function craftdigitally_render_shared_cta_section($args = array()) {
  $cta = craftdigitally_get_shared_cta_data();
  $section_id = isset($args['section_id']) ? (string) $args['section_id'] : 'contact';
  ?>
  <section class="cta-section" id="<?php echo esc_attr($section_id); ?>">
    <div class="container">
      <div class="cta-header">
        <h2 class="cta-title"><?php echo esc_html($cta['title']); ?></h2>
        <p class="cta-subtitle"><?php echo esc_html($cta['subtitle']); ?></p>
      </div>

      <div class="cta-form-wrapper">
        <form method="post" action="#" class="cta-form">
          <div class="cta-form-row">
            <input type="text" name="name" placeholder="<?php echo esc_attr($cta['name_placeholder']); ?>" required class="cta-input" />
            <input type="tel" name="phone" placeholder="<?php echo esc_attr($cta['phone_placeholder']); ?>" required class="cta-input" />
          </div>
          <input type="email" name="email" placeholder="<?php echo esc_attr($cta['email_placeholder']); ?>" required class="cta-input" />
          <input type="text" name="service" placeholder="<?php echo esc_attr($cta['service_placeholder']); ?>" class="cta-input" />
          <textarea name="message" placeholder="<?php echo esc_attr($cta['message_placeholder']); ?>" rows="5" class="cta-input cta-textarea"></textarea>
          <button type="submit" class="btn btn-outline cta-submit"><?php echo esc_html($cta['button_label']); ?></button>
        </form>
      </div>
    </div>
  </section>
  <?php
}

