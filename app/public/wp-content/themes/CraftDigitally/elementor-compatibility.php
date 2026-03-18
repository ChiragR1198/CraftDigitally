<?php
/**
 * Elementor Compatibility
 * Add this code to make theme fully compatible with Elementor
 * 
 * @package CraftDigitally
 */

// Elementor Support
add_action('after_setup_theme', 'craftdigitally_elementor_support');
function craftdigitally_elementor_support() {
  // Add Elementor support
  add_theme_support('elementor');
  
  // Add support for Elementor Pro features
  add_theme_support('elementor-pro');
  
  // Add support for Elementor's Header & Footer Builder
  add_theme_support('header-footer-elementor');
}

// Register Elementor locations
add_action('elementor/theme/register_locations', 'craftdigitally_register_elementor_locations');
function craftdigitally_register_elementor_locations($elementor_theme_manager) {
  $elementor_theme_manager->register_location('header');
  $elementor_theme_manager->register_location('footer');
}

// Add Elementor canvas template support
add_filter('template_include', 'craftdigitally_elementor_canvas_template', 11);
function craftdigitally_elementor_canvas_template($template) {
  if (!class_exists('\Elementor\Plugin')) {
    return $template;
  }
  
  $elementor = \Elementor\Plugin::$instance;
  
  // Don't override template in preview mode
  if ($elementor->preview->is_preview_mode()) {
    return $template;
  }
  
  // Get current post/page ID
  $post_id = get_the_ID();
  if (!$post_id) {
    return $template;
  }
  
  $page_template = get_post_meta($post_id, '_wp_page_template', true);
  
  // Handle Elementor Canvas template - Elementor handles this internally
  // We just need to ensure the theme doesn't interfere
  if ('elementor_canvas' === $page_template || 'elementor_header_footer' === $page_template) {
    // Let Elementor handle its own templates
    // The template will be loaded by Elementor's own system
    return $template;
  }
  
  return $template;
}

// Ensure Elementor styles and scripts load properly
add_action('wp_enqueue_scripts', 'craftdigitally_elementor_scripts', 20);
function craftdigitally_elementor_scripts() {
  if (!class_exists('\Elementor\Plugin')) {
    return;
  }
  
  // Ensure Elementor frontend styles are loaded in preview mode
  if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
    wp_enqueue_style('elementor-frontend');
    wp_enqueue_script('elementor-frontend');
  }
}

// Add support for Elementor's default colors and fonts
add_action('elementor/theme/register_locations', 'craftdigitally_elementor_theme_support', 10);
function craftdigitally_elementor_theme_support() {
  // This ensures Elementor can use theme colors and fonts
  if (class_exists('\Elementor\Plugin')) {
    \Elementor\Plugin::$instance->kits_manager->get_active_kit();
  }
}

// Allow Elementor to edit all post types
add_action('elementor/elements/categories_registered', 'craftdigitally_elementor_widget_categories');
function craftdigitally_elementor_widget_categories($elements_manager) {
  // This ensures all Elementor widgets are available
}

// Disable theme default styles on Elementor pages for better compatibility
add_action('wp', 'craftdigitally_disable_theme_styles_on_elementor');
function craftdigitally_disable_theme_styles_on_elementor() {
  if (!class_exists('\Elementor\Plugin')) {
    return;
  }
  
  $post_id = get_the_ID();
  if (!$post_id) {
    return;
  }
  
  // Check if page is built with Elementor
  if (\Elementor\Plugin::$instance->db->is_built_with_elementor($post_id)) {
    // Add body class for Elementor pages
    add_filter('body_class', function($classes) {
      $classes[] = 'elementor-built-page';
      return $classes;
    });
  }
}

