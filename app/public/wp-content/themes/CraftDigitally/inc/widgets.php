<?php
/**
 * Register widget areas
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Register Widget Areas
 */
function craftdigitally_widgets_init() {
  register_sidebar(
    array(
      'name' => __('Sidebar', 'craftdigitally'),
      'id' => 'sidebar-1',
      'description' => __('Add widgets here to appear in your sidebar.', 'craftdigitally'),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget' => '</section>',
      'before_title' => '<h2 class="widget-title">',
      'after_title' => '</h2>',
    )
  );

  register_sidebar(
    array(
      'name' => __('Footer Widget Area 1', 'craftdigitally'),
      'id' => 'footer-1',
      'description' => __('Appears in the footer section of the site.', 'craftdigitally'),
      'before_widget' => '<section id="%1$s" class="footer-widget %2$s">',
      'after_widget' => '</section>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    )
  );

  register_sidebar(
    array(
      'name' => __('Footer Widget Area 2', 'craftdigitally'),
      'id' => 'footer-2',
      'description' => __('Appears in the footer section of the site.', 'craftdigitally'),
      'before_widget' => '<section id="%1$s" class="footer-widget %2$s">',
      'after_widget' => '</section>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    )
  );
}
add_action('widgets_init', 'craftdigitally_widgets_init');

