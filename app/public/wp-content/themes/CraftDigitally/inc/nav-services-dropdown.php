<?php
/**
 * Primary nav: auto “Services” dropdown listing all published Service posts.
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Tag menu items that already have children defined in Appearance → Menus (skip auto dropdown).
 *
 * @param WP_Post[] $items Sorted menu items.
 * @param object    $args  Menu args.
 * @return WP_Post[]
 */
function craftdigitally_nav_mark_parents_with_wp_children($items, $args) {
  if (empty($args->theme_location) || $args->theme_location !== 'primary' || empty($items)) {
    return $items;
  }
  $parent_ids = array();
  foreach ($items as $item) {
    $pid = isset($item->menu_item_parent) ? (int) $item->menu_item_parent : 0;
    if ($pid > 0) {
      $parent_ids[$pid] = true;
    }
  }
  foreach ($items as $item) {
    $id = isset($item->ID) ? (int) $item->ID : 0;
    if ($id && !empty($parent_ids[$id])) {
      $item->craftdigitally_has_wp_submenu = true;
    }
  }
  return $items;
}
add_filter('wp_nav_menu_objects', 'craftdigitally_nav_mark_parents_with_wp_children', 5, 2);

/**
 * URL of the Service Landing page (template), else /services/.
 *
 * @return string
 */
function craftdigitally_get_services_landing_url() {
  static $url = null;
  if ($url !== null) {
    return $url;
  }
  $pages = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'page-templates/page-service-landing.php',
    'number' => 1,
    'post_status' => 'publish',
  ));
  $url = !empty($pages) ? get_permalink($pages[0]->ID) : home_url('/services/');
  return $url;
}

/**
 * Whether a nav menu item is the top-level “Services” parent we attach the dropdown to.
 *
 * Skips items that already have child items defined in the menu (manual submenu).
 *
 * @param object $item Nav menu item.
 * @return bool
 */
function craftdigitally_nav_item_is_services_parent($item) {
  if (empty($item) || !is_object($item)) {
    return false;
  }
  if (isset($item->menu_item_parent) && (int) $item->menu_item_parent !== 0) {
    return false;
  }
  if (!empty($item->craftdigitally_has_wp_submenu)) {
    return false;
  }
  $landing = craftdigitally_get_services_landing_url();
  if (!empty($item->url)) {
    if (untrailingslashit((string) $item->url) === untrailingslashit($landing)) {
      return true;
    }
  }
  if (!empty($item->title) && strcasecmp(trim(wp_strip_all_tags($item->title)), 'Services') === 0) {
    return true;
  }
  return false;
}

/**
 * Query service posts for the nav dropdown (same visibility idea as service landing grid).
 *
 * @return WP_Post[]
 */
function craftdigitally_get_service_posts_for_nav() {
  $status = current_user_can('edit_posts')
    ? array('publish', 'draft', 'pending', 'future', 'private')
    : array('publish');
  return get_posts(array(
    'post_type' => 'service',
    'post_status' => $status,
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
    'no_found_rows' => true,
  ));
}

/**
 * Markup for the services submenu `<ul>`.
 *
 * @return string
 */
function craftdigitally_get_services_submenu_html() {
  static $cached = null;
  if ($cached !== null) {
    return $cached;
  }
  $posts = craftdigitally_get_service_posts_for_nav();
  if (empty($posts)) {
    $cached = '';
    return $cached;
  }
  $html = '<ul class="sub-menu" role="menu" aria-label="' . esc_attr__('Services', 'craftdigitally') . '">';
  foreach ($posts as $post) {
    if (!isset($post->ID)) {
      continue;
    }
    $permalink = get_permalink($post);
    if (!$permalink) {
      continue;
    }
    $title = get_the_title($post);
    $html .= '<li class="menu-item menu-item-type-service" role="none">';
    $html .= '<a role="menuitem" href="' . esc_url($permalink) . '">' . esc_html($title) . '</a>';
    $html .= '</li>';
  }
  $html .= '</ul>';
  $cached = $html;
  return $cached;
}

/**
 * Append submenu after the Services top-level link (inside the same `<li>`).
 *
 * @param string $item_output Anchor HTML from the walker.
 * @param object $item        Menu item.
 * @param int    $depth       Depth.
 * @param object $args        Menu args.
 * @return string
 */
function craftdigitally_append_services_submenu_html($item_output, $item, $depth, $args) {
  if ((int) $depth !== 0 || empty($args->theme_location) || $args->theme_location !== 'primary') {
    return $item_output;
  }
  if (!craftdigitally_nav_item_is_services_parent($item)) {
    return $item_output;
  }
  $sub = craftdigitally_get_services_submenu_html();
  if ($sub === '') {
    return $item_output;
  }
  return $item_output . $sub;
}
add_filter('walker_nav_menu_start_el', 'craftdigitally_append_services_submenu_html', 10, 4);

/**
 * @param array    $classes Menu item classes.
 * @param WP_Post  $item    Menu item.
 * @param stdClass $args    Menu args.
 * @param int      $depth   Depth.
 * @return array
 */
function craftdigitally_services_nav_menu_css_class($classes, $item, $args, $depth) {
  if ((int) $depth !== 0 || empty($args->theme_location) || $args->theme_location !== 'primary') {
    return $classes;
  }
  if (!craftdigitally_nav_item_is_services_parent($item)) {
    return $classes;
  }
  if (craftdigitally_get_services_submenu_html() === '') {
    return $classes;
  }
  if (!in_array('menu-item-has-children', $classes, true)) {
    $classes[] = 'menu-item-has-children';
  }
  $classes[] = 'nav-item-services-dropdown';
  return $classes;
}
add_filter('nav_menu_css_class', 'craftdigitally_services_nav_menu_css_class', 15, 4);

/**
 * Highlight Services when viewing a service or the services landing URL.
 *
 * @param array    $classes Menu item classes.
 * @param WP_Post  $item    Menu item.
 * @param stdClass $args    Menu args.
 * @param int      $depth   Depth.
 * @return array
 */
function craftdigitally_services_nav_current_ancestor_class($classes, $item, $args, $depth) {
  if ((int) $depth !== 0 || empty($args->theme_location) || $args->theme_location !== 'primary') {
    return $classes;
  }
  if (!craftdigitally_nav_item_is_services_parent($item)) {
    return $classes;
  }
  $on_services_landing = is_page() && get_page_template_slug() === 'page-templates/page-service-landing.php';
  if (is_singular('service') || is_post_type_archive('service') || $on_services_landing) {
    $classes[] = 'current-menu-ancestor';
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'craftdigitally_services_nav_current_ancestor_class', 25, 4);

/**
 * Accessibility: indicate expandable submenu on Services link.
 *
 * @param array    $atts    Link attributes.
 * @param WP_Post  $item    Menu item.
 * @param stdClass $args    Menu args.
 * @param int      $depth   Depth.
 * @return array
 */
function craftdigitally_services_nav_link_attributes($atts, $item, $args, $depth) {
  if ((int) $depth !== 0 || empty($args->theme_location) || $args->theme_location !== 'primary') {
    return $atts;
  }
  if (!craftdigitally_nav_item_is_services_parent($item) || craftdigitally_get_services_submenu_html() === '') {
    return $atts;
  }
  $atts['aria-haspopup'] = 'true';
  $atts['aria-expanded'] = 'false';
  $existing = isset($atts['class']) ? $atts['class'] : '';
  $atts['class'] = trim($existing . ' nav-services-trigger');
  return $atts;
}
add_filter('nav_menu_link_attributes', 'craftdigitally_services_nav_link_attributes', 10, 4);
