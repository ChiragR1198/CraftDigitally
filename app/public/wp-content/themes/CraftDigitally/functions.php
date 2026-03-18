<?php
/**
 * CraftDigitally Theme Functions
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

// Define theme version
if (!defined('CRAFTDIGITALLY_VERSION')) {
  define('CRAFTDIGITALLY_VERSION', wp_get_theme()->get('Version'));
}

// Define theme directory path
if (!defined('CRAFTDIGITALLY_DIR')) {
  define('CRAFTDIGITALLY_DIR', get_template_directory());
}

// Define theme directory URI
if (!defined('CRAFTDIGITALLY_URI')) {
  define('CRAFTDIGITALLY_URI', get_template_directory_uri());
}

/**
 * Load theme setup functions
 */
require CRAFTDIGITALLY_DIR . '/inc/theme-setup.php';

/**
 * Load custom post types (blog, case_study, service)
 */
require CRAFTDIGITALLY_DIR . '/inc/custom-post-types.php';

/**
 * Load ACF integration (field groups + getters)
 */
require CRAFTDIGITALLY_DIR . '/inc/acf.php';

/**
 * Load enqueue functions
 */
require CRAFTDIGITALLY_DIR . '/inc/enqueue.php';

/**
 * Load widget functions
 */
require CRAFTDIGITALLY_DIR . '/inc/widgets.php';

/**
 * Load default data
 */
require CRAFTDIGITALLY_DIR . '/inc/default-data.php';

/**
 * Default (demo) Posts Creator
 */
require CRAFTDIGITALLY_DIR . '/inc/default-posts.php';

/**
 * Default (demo) Services Creator
 */
require CRAFTDIGITALLY_DIR . '/inc/default-services.php';

/**
 * Load case studies functions
 */
require CRAFTDIGITALLY_DIR . '/inc/case-studies.php';

/**
 * Load contact form functions
 */
require CRAFTDIGITALLY_DIR . '/inc/contact-form.php';

/**
 * Custom template tags for this theme
 */
require CRAFTDIGITALLY_DIR . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress
 */
require CRAFTDIGITALLY_DIR . '/inc/template-functions.php';

/**
 * Customizer additions
 */
require CRAFTDIGITALLY_DIR . '/inc/customizer.php';

/**
 * Theme Compatibility Checker
 */
require CRAFTDIGITALLY_DIR . '/inc/theme-checker.php';

/**
 * Default Pages Creator
 */
require CRAFTDIGITALLY_DIR . '/inc/default-pages.php';

/**
 * Elementor Compatibility
 */
require CRAFTDIGITALLY_DIR . '/elementor-compatibility.php';

/**
 * WooCommerce Compatibility
 */
if (class_exists('WooCommerce')) {
  require CRAFTDIGITALLY_DIR . '/inc/woocommerce.php';
}

/**
 * Custom excerpt length
 */
function craftdigitally_excerpt_length($length) {
  return 30;
}
add_filter('excerpt_length', 'craftdigitally_excerpt_length');

/**
 * Custom excerpt more
 */
function craftdigitally_excerpt_more($more) {
  return '...';
}
add_filter('excerpt_more', 'craftdigitally_excerpt_more');

/**
 * Add custom body classes
 */
function craftdigitally_body_classes($classes) {
  // Adds a class of hfeed to non-singular pages
  if (!is_singular()) {
    $classes[] = 'hfeed';
  }

  // Adds a class of no-sidebar when there is no sidebar present
  if (!is_active_sidebar('sidebar-1')) {
    $classes[] = 'no-sidebar';
  }

  return $classes;
}
add_filter('body_class', 'craftdigitally_body_classes');

/**
 * Add active class to Home menu item when on home page
 */
function craftdigitally_home_menu_active($classes, $item, $args, $depth) {
  // Check if we're on the home page and this is the home menu item
  if ((is_front_page() || is_home()) && $item->url === home_url('/')) {
    if (!in_array('current-menu-item', $classes)) {
      $classes[] = 'current-menu-item';
    }
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'craftdigitally_home_menu_active', 10, 4);

/**
 * Add active class to Services menu item when on Service archive or Service single
 * (so the underline/bar shows on /services/ and /service/{slug}/).
 */
function craftdigitally_services_menu_active($classes, $item, $args, $depth) {
  if (is_admin()) {
    return $classes;
  }

  if (!(is_post_type_archive('service') || is_singular('service'))) {
    return $classes;
  }

  $services_url = get_post_type_archive_link('service');
  if (empty($services_url)) {
    $services_url = home_url('/services/');
  }

  $item_path = trim((string) parse_url((string) $item->url, PHP_URL_PATH), '/');
  $services_path = trim((string) parse_url((string) $services_url, PHP_URL_PATH), '/');

  // Accept either /services/ (preferred) or legacy /service/ menu links.
  if ($item_path === $services_path || $item_path === 'service') {
    if (!in_array('current-menu-item', $classes, true)) {
      $classes[] = 'current-menu-item';
    }
    if (!in_array('current_page_item', $classes, true)) {
      $classes[] = 'current_page_item';
    }
  }

  return $classes;
}
add_filter('nav_menu_css_class', 'craftdigitally_services_menu_active', 11, 4);

/**
 * Add active class to Blog menu item when on Blog landing or Blog single posts
 * (so the underline/bar shows on /blog/ and /blog/{post-name}/).
 */
function craftdigitally_blog_menu_active($classes, $item, $args, $depth) {
  if (is_admin()) {
    return $classes;
  }

  if (!(is_page_template('page-templates/page-blog-landing.php') || is_singular('post'))) {
    return $classes;
  }

  // Resolve the blog landing URL (page using blog landing template).
  $blog_pages = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'page-templates/page-blog-landing.php',
    'number' => 1,
    'post_status' => 'publish',
  ));
  $blog_url = !empty($blog_pages) ? get_permalink($blog_pages[0]->ID) : home_url('/blog/');

  $item_path = trim((string) parse_url((string) $item->url, PHP_URL_PATH), '/');
  $blog_path = trim((string) parse_url((string) $blog_url, PHP_URL_PATH), '/');

  if ($item_path === $blog_path || $item_path === 'blog') {
    if (!in_array('current-menu-item', $classes, true)) {
      $classes[] = 'current-menu-item';
    }
    if (!in_array('current_page_item', $classes, true)) {
      $classes[] = 'current_page_item';
    }
  }

  return $classes;
}
add_filter('nav_menu_css_class', 'craftdigitally_blog_menu_active', 12, 4);

/**
 * One-time rewrite rules flush after theme updates.
 *
 * This prevents sudden 404s on CPT archives (e.g. /services/) when rewrite rules
 * are stale in local/live environments.
 */
function craftdigitally_maybe_flush_rewrite_rules_on_version_change() {
  if (is_admin() || wp_doing_ajax()) {
    return;
  }

  $stored_version = get_option('craftdigitally_rewrite_flushed_version');
  if ($stored_version === CRAFTDIGITALLY_VERSION) {
    return;
  }

  // Flush once per theme version.
  flush_rewrite_rules(false);
  update_option('craftdigitally_rewrite_flushed_version', CRAFTDIGITALLY_VERSION);
}
add_action('init', 'craftdigitally_maybe_flush_rewrite_rules_on_version_change', 20);

/**
 * One-time flush for Service URL structure change.
 *
 * We changed service singles to /service/{slug}/ and archive to /services/.
 */
function craftdigitally_maybe_flush_service_rewrites_once() {
  if (is_admin() || wp_doing_ajax()) {
    return;
  }

  if (get_option('craftdigitally_service_rewrites_flushed') === '1') {
    return;
  }

  flush_rewrite_rules(false);
  update_option('craftdigitally_service_rewrites_flushed', '1');
}
add_action('init', 'craftdigitally_maybe_flush_service_rewrites_once', 21);

/**
 * One-time flush for Case Study URL structure change.
 *
 * We disabled the case_study CPT archive so /case-studies/ can be a Page.
 */
function craftdigitally_maybe_flush_case_study_rewrites_once() {
  if (is_admin() || wp_doing_ajax()) {
    return;
  }

  // v3: /case-studies/ is a PAGE; singles remain /case-studies/{slug}/
  if (get_option('craftdigitally_case_study_rewrites_flushed_v3') === '1') {
    return;
  }

  flush_rewrite_rules(false);
  update_option('craftdigitally_case_study_rewrites_flushed_v3', '1');
}
add_action('init', 'craftdigitally_maybe_flush_case_study_rewrites_once', 22);

/**
 * Ensure Service single URLs work even if a Page exists at /service/
 *
 * Example desired single: /service/local-seo/
 */
function craftdigitally_add_service_single_rewrite_rule() {
  add_rewrite_rule(
    '^service/([^/]+)/?$',
    'index.php?post_type=service&name=$matches[1]',
    'top'
  );
}
add_action('init', 'craftdigitally_add_service_single_rewrite_rule', 5);

/**
 * Ensure Case Study archive/single URLs work even if a Page exists at /case-studies/
 *
 * Desired:
 * - Archive: /case-studies/
 * - Single:  /case-studies/{slug}/
 */
function craftdigitally_add_case_study_rewrite_rules() {
  // Do NOT add an archive rule for ^case-studies/$ because /case-studies/ must be a PAGE.
  add_rewrite_rule(
    '^case-studies/([^/]+)/?$',
    'index.php?post_type=case_study&name=$matches[1]',
    'top'
  );
}
add_action('init', 'craftdigitally_add_case_study_rewrite_rules', 6);

/**
 * Clear page template cache to refresh template list
 * This helps WordPress detect new page templates
 */
function craftdigitally_clear_template_cache() {
  // Clear theme cache when in admin
  if (is_admin() && isset($_GET['clear_template_cache'])) {
    $theme = wp_get_theme();
    delete_transient('_wp_get_post_templates');
    delete_transient('_wp_get_post_templates_' . $theme->get_stylesheet());
    wp_cache_flush();
    wp_redirect(admin_url('edit.php?post_type=page&template_cache_cleared=1'));
    exit;
  }
}
add_action('admin_init', 'craftdigitally_clear_template_cache');

/**
 * Force refresh page templates on admin pages
 * This ensures new templates are detected immediately
 */
function craftdigitally_refresh_page_templates() {
  if (is_admin()) {
    $theme = wp_get_theme();
    delete_transient('_wp_get_post_templates');
    delete_transient('_wp_get_post_templates_' . $theme->get_stylesheet());
  }
}
add_action('admin_init', 'craftdigitally_refresh_page_templates', 1);

/**
 * Redirect post archives to blog landing page
 * This ensures all blog/post archives use the custom blog landing page template
 */
function craftdigitally_redirect_post_archives_to_blog_landing() {
  // Only redirect on frontend, not in admin
  if (is_admin()) {
    return;
  }

  // Find the blog landing page
  $blog_landing_page = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'page-templates/page-blog-landing.php',
    'number' => 1,
    'post_status' => 'publish'
  ));
  
  // If no blog landing page exists, don't redirect
  if (empty($blog_landing_page)) {
    return;
  }
  
  $blog_landing_url = get_permalink($blog_landing_page[0]->ID);
  
  // Check if this is a post-related archive or the posts page
  $should_redirect = false;
  
  // Check if current page is the posts page set in WordPress settings
  $posts_page_id = get_option('page_for_posts');
  if ($posts_page_id && is_page($posts_page_id)) {
    // If the posts page is not the blog landing page, redirect
    if ($posts_page_id != $blog_landing_page[0]->ID) {
      $should_redirect = true;
    }
  }
  // Check for post archives
  elseif (is_post_type_archive('post') || 
          (is_archive() && get_post_type() === 'post') || 
          is_category() || 
          is_tag() || 
          (is_home() && !is_front_page())) {
    $should_redirect = true;
  }
  
  // Redirect if needed
  if ($should_redirect) {
    wp_redirect($blog_landing_url, 301);
    exit;
  }
}
add_action('template_redirect', 'craftdigitally_redirect_post_archives_to_blog_landing', 1);

/**
 * Redirect /service/ (singular) to /services/ (plural)
 *
 * We want only one public route for the services landing.
 */
function craftdigitally_redirect_service_page_to_services_archive() {
  // Only redirect on frontend, not in admin/ajax/rest.
  if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
    return;
  }

  $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
  $path = trim((string) parse_url($request_uri, PHP_URL_PATH), '/');

  // Redirect only the exact /service/ path (optionally with query string).
  if ($path === 'service') {
    wp_redirect(home_url('/services/'), 301);
    exit;
  }
}
add_action('template_redirect', 'craftdigitally_redirect_service_page_to_services_archive', 0);

/**
 * Redirect legacy blog detail page URLs to the native /blog/{post-name}/ URL.
 *
 * Legacy example: /blog-detail/?post_id=123
 * New canonical:  /blog/post-name/
 */
function craftdigitally_redirect_blog_detail_page_to_post_permalink() {
  if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
    return;
  }

  if (!is_page_template('page-templates/page-blog-detail.php')) {
    return;
  }

  $post_id = isset($_GET['post_id']) ? absint($_GET['post_id']) : 0;
  if ($post_id <= 0 || get_post_type($post_id) !== 'post') {
    return;
  }

  // Only redirect published posts for public users.
  $status = (string) get_post_status($post_id);
  if ($status !== 'publish' && !current_user_can('edit_post', $post_id)) {
    return;
  }

  $target = get_permalink($post_id);
  if (!empty($target)) {
    wp_redirect($target, 301);
    exit;
  }
}
add_action('template_redirect', 'craftdigitally_redirect_blog_detail_page_to_post_permalink', 2);

/**
 * Redirect legacy case study detail page URLs to the native /case-studies/{post-name}/ URL.
 *
 * Legacy example: /case-study-detail/?post_id=123
 * New canonical:  /case-studies/post-name/
 */
function craftdigitally_redirect_case_study_detail_page_to_single() {
  if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
    return;
  }

  if (!is_page_template('page-templates/page-case-study-detail.php')) {
    return;
  }

  $post_id = isset($_GET['post_id']) ? absint($_GET['post_id']) : 0;
  if ($post_id <= 0 || get_post_type($post_id) !== 'case_study') {
    return;
  }

  $status = (string) get_post_status($post_id);
  if ($status !== 'publish' && !current_user_can('edit_post', $post_id)) {
    return;
  }

  $target = get_permalink($post_id);
  if (!empty($target)) {
    wp_redirect($target, 301);
    exit;
  }
}
add_action('template_redirect', 'craftdigitally_redirect_case_study_detail_page_to_single', 3);

/**
 * Add /blog/ prefix to post permalinks
 * This ensures all blog posts have URLs like: /blog/post-name
 */
function craftdigitally_add_blog_prefix_to_posts($permalink, $post) {
    // Only modify post type 'post'
    if ($post->post_type !== 'post' || empty($post->post_name)) {
        return $permalink;
    }
    
    // Get the blog landing page slug
    $blog_landing_page = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-templates/page-blog-landing.php',
        'number' => 1,
        'post_status' => 'publish'
    ));
    
    if (!empty($blog_landing_page)) {
        $blog_page = get_post($blog_landing_page[0]->ID);
        $blog_slug = $blog_page->post_name;
        
        // Build the new permalink with /blog/ prefix
        $home_url = trailingslashit(home_url());
        $new_permalink = $home_url . $blog_slug . '/' . $post->post_name . '/';
        
        // Check if permalink already has the blog prefix
        if (strpos($permalink, '/' . $blog_slug . '/') === false) {
            return $new_permalink;
        }
    }
    
    return $permalink;
}
add_filter('post_link', 'craftdigitally_add_blog_prefix_to_posts', 10, 2);
add_filter('post_type_link', 'craftdigitally_add_blog_prefix_to_posts', 10, 2);

/**
 * Add rewrite rules for /blog/ prefix
 */
function craftdigitally_add_blog_rewrite_rules() {
    // Get the blog landing page slug
    $blog_landing_page = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-templates/page-blog-landing.php',
        'number' => 1,
        'post_status' => 'publish'
    ));
    
    if (!empty($blog_landing_page)) {
        $blog_page = get_post($blog_landing_page[0]->ID);
        $blog_slug = $blog_page->post_name;
        
        // Add rewrite rule for posts with /blog/ prefix
        add_rewrite_rule(
            '^' . $blog_slug . '/([^/]+)/?$',
            'index.php?name=$matches[1]&post_type=post',
            'top'
        );
        
        // Also handle pagination and feeds
        add_rewrite_rule(
            '^' . $blog_slug . '/([^/]+)/page/?([0-9]{1,})/?$',
            'index.php?name=$matches[1]&post_type=post&paged=$matches[2]',
            'top'
        );
    }
}
add_action('init', 'craftdigitally_add_blog_rewrite_rules');

/**
 * Flush rewrite rules when theme is activated or blog page is updated
 */
function craftdigitally_flush_rewrite_rules() {
    craftdigitally_add_blog_rewrite_rules();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'craftdigitally_flush_rewrite_rules');

/**
 * Flush rewrite rules when a page is saved (in case blog page slug changes)
 */
function craftdigitally_flush_rewrite_on_page_save($post_id) {
    // Check if this is the blog landing page
    $template = get_post_meta($post_id, '_wp_page_template', true);
    if ($template === 'page-templates/page-blog-landing.php') {
        craftdigitally_flush_rewrite_rules();
    }
}
add_action('save_post_page', 'craftdigitally_flush_rewrite_on_page_save');
