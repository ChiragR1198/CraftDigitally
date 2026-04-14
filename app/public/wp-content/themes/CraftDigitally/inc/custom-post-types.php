<?php
/**
 * Custom Post Types Registration
 * 
 * Registers custom post types for Blog, Case Study, and Services
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Helper: Get Case Study Detail base URL (page using Case Study Detail template)
 *
 * @return string
 */
function craftdigitally_get_case_study_detail_base_url() {
  // Look for a page using the Case Study Detail page template.
  $detail_pages = get_pages(array(
    'meta_key'   => '_wp_page_template',
    'meta_value' => 'page-templates/page-case-study-detail.php',
    'number'     => 1,
    'post_status'=> 'publish',
  ));

  if (!empty($detail_pages)) {
    return get_permalink($detail_pages[0]->ID);
  }

  // Fallback: page slug /case-study-detail/
  $detail_page_by_path = get_page_by_path('case-study-detail');
  if ($detail_page_by_path && !empty($detail_page_by_path->ID)) {
    return get_permalink($detail_page_by_path->ID);
  }

  // Final fallback: direct URL
  return home_url('/case-study-detail/');
}

/**
 * Helper: Get Case Study Detail URL for a specific post.
 *
 * Routes case study cards to the page using `page-case-study-detail.php`
 * so the site uses one consistent detail layout.
 *
 * @param int $post_id Case study post ID.
 * @return string
 */
function craftdigitally_get_case_study_detail_url($post_id = 0) {
  $base_url = craftdigitally_get_case_study_detail_base_url();
  $post_id = absint($post_id);

  if ($post_id <= 0) {
    return $base_url;
  }

  return add_query_arg('post_id', $post_id, $base_url);
}

/**
 * Helper: Get Service Detail base URL (page using Service Detail template)
 *
 * @return string
 */
function craftdigitally_get_service_detail_base_url() {
  // Look for a page using the Service Detail page template.
  $detail_pages = get_pages(array(
    'meta_key'   => '_wp_page_template',
    'meta_value' => 'page-templates/page-service-detail.php',
    'number'     => 1,
    'post_status'=> 'publish',
  ));

  if (!empty($detail_pages)) {
    return get_permalink($detail_pages[0]->ID);
  }

  // Fallback: page slug /service-detail/
  $detail_page_by_path = get_page_by_path('service-detail');
  if ($detail_page_by_path && !empty($detail_page_by_path->ID)) {
    return get_permalink($detail_page_by_path->ID);
  }

  // Final fallback: direct URL
  return home_url('/service-detail/');
}

/**
 * Register Custom Post Types
 */
function craftdigitally_register_custom_post_types() {
  
  /**
   * Case Study Post Type
   */
  $case_study_labels = array(
    'name'                  => _x('Case Studies', 'Post Type General Name', 'craftdigitally'),
    'singular_name'         => _x('Case Study', 'Post Type Singular Name', 'craftdigitally'),
    'menu_name'             => __('Case Studies', 'craftdigitally'),
    'name_admin_bar'        => __('Case Study', 'craftdigitally'),
    'archives'              => __('Case Study Archives', 'craftdigitally'),
    'attributes'            => __('Case Study Attributes', 'craftdigitally'),
    'parent_item_colon'     => __('Parent Case Study:', 'craftdigitally'),
    'all_items'             => __('All Case Studies', 'craftdigitally'),
    'add_new_item'          => __('Add New Case Study', 'craftdigitally'),
    'add_new'               => __('Add New', 'craftdigitally'),
    'new_item'              => __('New Case Study', 'craftdigitally'),
    'edit_item'             => __('Edit Case Study', 'craftdigitally'),
    'update_item'           => __('Update Case Study', 'craftdigitally'),
    'view_item'             => __('View Case Study', 'craftdigitally'),
    'view_items'            => __('View Case Studies', 'craftdigitally'),
    'search_items'          => __('Search Case Study', 'craftdigitally'),
    'not_found'             => __('Not found', 'craftdigitally'),
    'not_found_in_trash'    => __('Not found in Trash', 'craftdigitally'),
    'featured_image'        => __('Featured Image', 'craftdigitally'),
    'set_featured_image'    => __('Set featured image', 'craftdigitally'),
    'remove_featured_image' => __('Remove featured image', 'craftdigitally'),
    'use_featured_image'    => __('Use as featured image', 'craftdigitally'),
    'insert_into_item'      => __('Insert into case study', 'craftdigitally'),
    'uploaded_to_this_item'  => __('Uploaded to this case study', 'craftdigitally'),
    'items_list'            => __('Case studies list', 'craftdigitally'),
    'items_list_navigation' => __('Case studies list navigation', 'craftdigitally'),
    'filter_items_list'     => __('Filter case studies list', 'craftdigitally'),
  );
  
  $case_study_args = array(
    'label'                 => __('Case Study', 'craftdigitally'),
    'description'           => __('Client case studies and success stories', 'craftdigitally'),
    'labels'                => $case_study_labels,
    'supports'              => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions', 'page-attributes'),
    'taxonomies'            => array(),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'        => 6,
    'menu_icon'             => 'dashicons-portfolio',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    // IMPORTANT:
    // We want /case-studies/ to be a normal WordPress PAGE using the
    // `page-templates/page-case-study-landing.php` template.
    // So we DISABLE the CPT archive to avoid route conflicts.
    //
    // Singles will still be: /case-studies/{post-name}/
    'has_archive'           => false,
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'capability_type'       => 'post',
    'show_in_rest'          => true,
    'rewrite'               => array(
      'slug'                  => 'case-studies',
      'with_front'            => false,
      'pages'                 => true,
      'feeds'                 => true,
    ),
  );
  
  register_post_type('case_study', $case_study_args);
  
  /**
   * Service Post Type
   */
  $service_labels = array(
    'name'                  => _x('Services', 'Post Type General Name', 'craftdigitally'),
    'singular_name'         => _x('Service', 'Post Type Singular Name', 'craftdigitally'),
    'menu_name'             => __('Services', 'craftdigitally'),
    'name_admin_bar'        => __('Service', 'craftdigitally'),
    'archives'              => __('Service Archives', 'craftdigitally'),
    'attributes'            => __('Service Attributes', 'craftdigitally'),
    'parent_item_colon'     => __('Parent Service:', 'craftdigitally'),
    'all_items'             => __('All Services', 'craftdigitally'),
    'add_new_item'          => __('Add New Service', 'craftdigitally'),
    'add_new'               => __('Add New', 'craftdigitally'),
    'new_item'              => __('New Service', 'craftdigitally'),
    'edit_item'             => __('Edit Service', 'craftdigitally'),
    'update_item'           => __('Update Service', 'craftdigitally'),
    'view_item'             => __('View Service', 'craftdigitally'),
    'view_items'            => __('View Services', 'craftdigitally'),
    'search_items'          => __('Search Service', 'craftdigitally'),
    'not_found'             => __('Not found', 'craftdigitally'),
    'not_found_in_trash'    => __('Not found in Trash', 'craftdigitally'),
    'featured_image'        => __('Featured Image', 'craftdigitally'),
    'set_featured_image'    => __('Set featured image', 'craftdigitally'),
    'remove_featured_image' => __('Remove featured image', 'craftdigitally'),
    'use_featured_image'    => __('Use as featured image', 'craftdigitally'),
    'insert_into_item'      => __('Insert into service', 'craftdigitally'),
    'uploaded_to_this_item'  => __('Uploaded to this service', 'craftdigitally'),
    'items_list'            => __('Services list', 'craftdigitally'),
    'items_list_navigation' => __('Services list navigation', 'craftdigitally'),
    'filter_items_list'     => __('Filter services list', 'craftdigitally'),
  );
  
  $service_args = array(
    'label'                 => __('Service', 'craftdigitally'),
    'description'           => __('Service offerings and packages', 'craftdigitally'),
    'labels'                => $service_labels,
    'supports'              => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions', 'page-attributes'),
    'taxonomies'            => array(),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 7,
    'menu_icon'             => 'dashicons-admin-tools',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    // Explicit archive slug so the archive is always /services/
    'has_archive'           => 'services',
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'capability_type'       => 'post',
    'show_in_rest'          => true,
    'rewrite'               => array(
      // Singles should be /service/{post-name}/
      'slug'                  => 'service',
      'with_front'            => false,
      'pages'                 => true,
      'feeds'                 => true,
    ),
  );
  
  register_post_type('service', $service_args);
}
add_action('init', 'craftdigitally_register_custom_post_types', 0);

// NOTE:
// We intentionally do NOT override `post_type_link` / `preview_post_link` for the
// `case_study` CPT anymore. Case studies should use their native permalinks:
// - Archive: /case-studies/
// - Single:  /case-studies/{post-name}/

// NOTE:
// We intentionally do NOT override `post_type_link` / `preview_post_link` for the
// `service` CPT anymore. Services should use their native permalinks:
// - Archive: /services/
// - Single:  /service/{post-name}/

/**
 * Flush rewrite rules on theme activation
 */
function craftdigitally_flush_rewrite_rules_on_activation() {
  craftdigitally_register_custom_post_types();
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'craftdigitally_flush_rewrite_rules_on_activation');
