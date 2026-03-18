<?php
/**
 * Enqueue scripts and styles
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Enqueue styles and scripts
 */
function craftdigitally_scripts() {
  // Google Fonts
  wp_enqueue_style(
    'craftdigitally-fonts',
    'https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;1,400;1,700&family=Mona+Sans:wght@400;500;600&family=Public+Sans:wght@400&display=swap',
    array(),
    null
  );

  // Main stylesheet
  wp_enqueue_style(
    'craftdigitally-style',
    get_stylesheet_uri(),
    array(),
    wp_get_theme()->get('Version')
  );

  // Main JavaScript
  wp_enqueue_script(
    'craftdigitally-script',
    get_template_directory_uri() . '/assets/js/main.js',
    array(),
    wp_get_theme()->get('Version'),
    true
  );

  // Comment reply script for threaded comments
  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}
add_action('wp_enqueue_scripts', 'craftdigitally_scripts');

/**
 * Enqueue admin styles (for Gutenberg editor)
 */
function craftdigitally_admin_scripts() {
  wp_enqueue_style(
    'craftdigitally-editor-style',
    get_template_directory_uri() . '/assets/css/editor-style.css',
    array(),
    wp_get_theme()->get('Version')
  );
}
add_action('admin_enqueue_scripts', 'craftdigitally_admin_scripts');

