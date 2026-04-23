<?php
/**
 * Theme setup functions
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Sets up theme defaults and registers support for various WordPress features
 */
function craftdigitally_setup() {
  // Add default posts and comments RSS feed links to head
  add_theme_support('automatic-feed-links');

  // Let WordPress manage the document title
  add_theme_support('title-tag');

  // Enable support for Post Thumbnails on posts and pages
  add_theme_support('post-thumbnails');
  set_post_thumbnail_size(1200, 9999);

  // Register navigation menus
  register_nav_menus(
    array(
      'primary' => __('Primary Menu', 'craftdigitally'),
      'footer' => __('Footer Menu', 'craftdigitally'),
      'footer-social' => __('Footer Social Menu', 'craftdigitally'),
    )
  );

  // Switch default core markup to output valid HTML5
  add_theme_support(
    'html5',
    array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
      'script',
      'style',
    )
  );

  // Add theme support for selective refresh for widgets
  add_theme_support('customize-selective-refresh-widgets');

  // Add theme support for custom logo
  add_theme_support(
    'custom-logo',
    array(
      'height' => 100,
      'width' => 400,
      'flex-height' => true,
      'flex-width' => true,
    )
  );

  // Add theme support for custom background
  add_theme_support(
    'custom-background',
    array(
      'default-color' => 'f2efed',
    )
  );

  // Add support for Block Styles
  add_theme_support('wp-block-styles');

  // Add support for full and wide align images
  add_theme_support('align-wide');

  // Add support for editor styles
  add_theme_support('editor-styles');

  // Enqueue editor styles
  add_editor_style('assets/css/editor-style.css');

  // Add support for responsive embedded content
  add_theme_support('responsive-embeds');

  // Add support for custom line height controls
  add_theme_support('custom-line-height');

  // Add support for experimental link color control
  add_theme_support('experimental-link-color');

  // Add support for custom units
  add_theme_support('custom-units');

  // Add support for custom spacing
  add_theme_support('custom-spacing');

  // Content width
  $GLOBALS['content_width'] = 1200;
}
add_action('after_setup_theme', 'craftdigitally_setup');

/**
 * Disable manual excerpts for standard Posts (blog).
 * Blog content is authored in the main editor; excerpt UI is not used.
 */
function craftdigitally_disable_post_excerpt_support() {
  remove_post_type_support('post', 'excerpt');
}
add_action('init', 'craftdigitally_disable_post_excerpt_support', 11);

/**
 * Admin UI: hide Tags on blog (default `post`) screens.
 *
 * Applies to:
 * - Add/Edit post screen (metabox)
 */
function craftdigitally_admin_hide_post_tags() {
  if (!is_admin()) {
    return;
  }

  // Keep Categories enabled; only hide Tags.
  remove_meta_box('tagsdiv-post_tag', 'post', 'side');
}
add_action('admin_menu', 'craftdigitally_admin_hide_post_tags', 999);

/**
 * Hide taxonomy dropdown filters in the Posts list table.
 */
function craftdigitally_admin_hide_post_list_tax_filters() {
  if (!is_admin() || !function_exists('get_current_screen')) {
    return;
  }

  $screen = get_current_screen();
  if (!$screen || empty($screen->id) || $screen->id !== 'edit-post') {
    return;
  }

  echo '<style>
    /* Hide "All Tags" dropdown in Posts list table (keep Categories visible) */
    select#post_tag, select[name="post_tag"],
    label[for="post_tag"] {
      display: none !important;
    }
  </style>';
}
add_action('admin_head', 'craftdigitally_admin_hide_post_list_tax_filters', 20);

