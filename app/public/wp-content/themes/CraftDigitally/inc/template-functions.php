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

