<?php
/**
 * ACF integration (field groups + safe getters with fallbacks)
 *
 * IMPORTANT:
 * - This file is designed so the frontend output does NOT change unless ACF fields are filled.
 * - Every template should provide the current hardcoded value as the default fallback.
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Determine if an ACF value should be treated as "empty" (so we can fallback).
 *
 * @param mixed $value
 * @return bool
 */
function craftdigitally_acf_is_empty_value($value) {
  if ($value === null || $value === false) {
    return true;
  }
  if (is_string($value)) {
    return $value === '';
  }
  if (is_array($value)) {
    return empty($value);
  }
  return false;
}

/**
 * Get an ACF field value with a default fallback.
 *
 * @param string $field_name
 * @param mixed  $default
 * @param int|string|null $post_id
 * @return mixed
 */
function craftdigitally_get_acf($field_name, $default = null, $post_id = null) {
  if (!function_exists('get_field')) {
    return $default;
  }

  $resolved_post_id = $post_id ?: get_queried_object_id();
  $value = get_field($field_name, $resolved_post_id);

  return craftdigitally_acf_is_empty_value($value) ? $default : $value;
}

/**
 * Get an ACF image field as a URL, supporting return formats: array|url|id.
 *
 * @param string $field_name
 * @param string $default_url
 * @param int|string|null $post_id
 * @return string
 */
function craftdigitally_get_acf_image_url($field_name, $default_url = '', $post_id = null) {
  $img = craftdigitally_get_acf($field_name, null, $post_id);
  if (craftdigitally_acf_is_empty_value($img)) {
    return $default_url;
  }

  // Return format: array
  if (is_array($img) && !empty($img['url'])) {
    return (string) $img['url'];
  }

  // Return format: attachment ID
  if (is_numeric($img)) {
    $url = wp_get_attachment_image_url((int) $img, 'full');
    return $url ? $url : $default_url;
  }

  // Return format: URL
  if (is_string($img)) {
    return $img;
  }

  return $default_url;
}

/**
 * Get an ACF repeater (or group array) with a default fallback.
 *
 * @param string $field_name
 * @param array $default
 * @param int|string|null $post_id
 * @return array
 */
function craftdigitally_get_acf_array($field_name, $default = array(), $post_id = null) {
  $val = craftdigitally_get_acf($field_name, null, $post_id);
  return (is_array($val) && !empty($val)) ? $val : $default;
}

/**
 * Stable ACF keys for local field groups.
 */
function craftdigitally_acf_field_key($seed) {
  return 'field_' . substr(md5('craftdigitally_' . $seed), 0, 13);
}
function craftdigitally_acf_group_key($seed) {
  return 'group_' . substr(md5('craftdigitally_' . $seed), 0, 13);
}

/**
 * Build ACF "location" rules: show field group when template matches OR when editing a page with a given path/slug.
 *
 * @param string $template Relative path like 'page-templates/page-about.php'
 * @param array $paths Array of page paths/slugs like ['about', 'about-us']
 * @return array
 */
function craftdigitally_acf_location_template_or_pages($template, $paths = array()) {
  $locations = array(
    array(
      array(
        'param' => 'page_template',
        'operator' => '==',
        'value' => $template,
      ),
    ),
  );

  foreach ($paths as $path) {
    $page = get_page_by_path($path);
    if ($page && !empty($page->ID)) {
      $locations[] = array(
        array(
          'param' => 'page',
          'operator' => '==',
          'value' => (string) $page->ID,
        ),
      );
    }
  }

  return $locations;
}

/**
 * Build ACF "location" rules for custom post types.
 *
 * @param string $post_type Post type slug like 'blog', 'case_study', 'service'
 * @param array $paths Optional array of page paths/slugs for fallback
 * @return array
 */
function craftdigitally_acf_location_post_type($post_type, $paths = array()) {
  $locations = array(
    array(
      array(
        'param' => 'post_type',
        'operator' => '==',
        'value' => $post_type,
      ),
    ),
  );

  // Also add page paths if provided (for backward compatibility)
  foreach ($paths as $path) {
    $page = get_page_by_path($path);
    if ($page && !empty($page->ID)) {
      $locations[] = array(
        array(
          'param' => 'page',
          'operator' => '==',
          'value' => (string) $page->ID,
        ),
      );
    }
  }

  return $locations;
}

/**
 * Field builders (keep definitions compact).
 */
function craftdigitally_acf_text_field($seed, $name, $label, $type = 'text') {
  return array(
    'key' => craftdigitally_acf_field_key($seed),
    'label' => $label,
    'name' => $name,
    'type' => $type,
  );
}

/**
 * Add ACF wrapper layout (width/class) so fields can appear side-by-side in admin.
 *
 * @param array $field
 * @param string|int $width Percentage like 50, 25 etc.
 * @param string $class Optional wrapper class
 * @return array
 */
function craftdigitally_acf_wrap($field, $width = '', $class = '') {
  if (!is_array($field)) {
    return $field;
  }
  if (!isset($field['wrapper']) || !is_array($field['wrapper'])) {
    $field['wrapper'] = array();
  }
  if ($width !== '' && $width !== null) {
    $field['wrapper']['width'] = (string) $width;
  }
  if (!empty($class)) {
    $field['wrapper']['class'] = (string) $class;
  }
  return $field;
}

/**
 * Set an ACF field default_value so it shows as prefilled in admin (and can be edited).
 *
 * @param array $field
 * @param mixed $default_value
 * @return array
 */
function craftdigitally_acf_default($field, $default_value) {
  if (!is_array($field)) {
    return $field;
  }
  $field['default_value'] = $default_value;
  return $field;
}

/**
 * Admin-only block heading/help text.
 */
function craftdigitally_acf_message_field($seed, $label, $message) {
  return array(
    'key' => craftdigitally_acf_field_key($seed),
    'label' => $label,
    'name' => '', // message fields don't store values
    'type' => 'message',
    'message' => $message,
    'new_lines' => 'wpautop',
    'esc_html' => 0,
  );
}

/**
 * Accordion (collapsible block) to keep ACF UI clean.
 */
function craftdigitally_acf_accordion_field($seed, $label, $open = false, $endpoint = false) {
  return array(
    'key' => craftdigitally_acf_field_key($seed),
    'label' => $label,
    'name' => '',
    'type' => 'accordion',
    'open' => $open ? 1 : 0,
    'multi_expand' => 0,
    'endpoint' => $endpoint ? 1 : 0,
  );
}

/**
 * Tab field to create a click-to-switch admin UI.
 */
function craftdigitally_acf_tab_field($seed, $label, $placement = 'top') {
  return array(
    'key' => craftdigitally_acf_field_key($seed),
    'label' => $label,
    'name' => '',
    'type' => 'tab',
    'placement' => $placement, // 'top' or 'left'
    'endpoint' => 0,
  );
}

function craftdigitally_acf_image_field($seed, $name, $label) {
  return array(
    'key' => craftdigitally_acf_field_key($seed),
    'label' => $label,
    'name' => $name,
    'type' => 'image',
    'return_format' => 'array',
    'preview_size' => 'thumbnail',
    'library' => 'all',
  );
}

function craftdigitally_acf_url_field($seed, $name, $label) {
  return array(
    'key' => craftdigitally_acf_field_key($seed),
    'label' => $label,
    'name' => $name,
    'type' => 'url',
  );
}

function craftdigitally_acf_number_field($seed, $name, $label) {
  return array(
    'key' => craftdigitally_acf_field_key($seed),
    'label' => $label,
    'name' => $name,
    'type' => 'number',
  );
}

function craftdigitally_acf_group_field($seed, $name, $label, $sub_fields) {
  return array(
    'key' => craftdigitally_acf_field_key($seed),
    'label' => $label,
    'name' => $name,
    'type' => 'group',
    'layout' => 'block',
    'sub_fields' => $sub_fields,
  );
}

function craftdigitally_acf_repeater_field($seed, $name, $label, $sub_fields, $min = 0, $max = 0) {
  $field = array(
    'key' => craftdigitally_acf_field_key($seed),
    'label' => $label,
    'name' => $name,
    'type' => 'repeater',
    // Table layout makes repeaters easier to scan/edit in admin.
    'layout' => 'table',
    'button_label' => 'Add Row',
    'sub_fields' => $sub_fields,
    'min' => $min,
  );
  if (!empty($max)) {
    $field['max'] = $max;
  }
  return $field;
}

/**
 * Register local field groups.
 *
 * Note: We keep these groups focused and named by template. Templates still fall back to current hardcoded content
 * when fields are empty — so enabling ACF does not change the UI.
 */
add_action('acf/init', function () {
  if (!function_exists('acf_add_local_field_group')) {
    return;
  }

  // Shared: Template part - testimonial slider section (single testimonial block).
  acf_add_local_field_group(array(
    'key' => craftdigitally_acf_group_key('shared_testimonial_block'),
    'title' => 'CraftDigitally: Testimonial Block (Template Part)',
    'fields' => array(
      craftdigitally_acf_text_field('shared_testimonial_heading', 'shared_testimonial_heading', 'Heading', 'text'),
      craftdigitally_acf_text_field('shared_testimonial_quote', 'shared_testimonial_quote', 'Quote', 'textarea'),
      craftdigitally_acf_image_field('shared_testimonial_avatar', 'shared_testimonial_avatar', 'Avatar'),
      craftdigitally_acf_text_field('shared_testimonial_name', 'shared_testimonial_name', 'Name', 'text'),
      craftdigitally_acf_text_field('shared_testimonial_role', 'shared_testimonial_role', 'Role/Company', 'text'),
    ),
    'location' => array(
      array(
        array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'page',
        ),
      ),
    ),
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
  ));

  /**
   * BLOG LANDING PAGE (`page-templates/page-blog-landing.php`)
   */
  acf_add_local_field_group(array(
    'key' => craftdigitally_acf_group_key('blog_landing_page'),
    'title' => 'CraftDigitally: Blog Landing Page',
    'fields' => array(
      craftdigitally_acf_message_field(
        'blog_landing_note_posts',
        'How this page works',
        '<strong>Blog list is automatic from Posts.</strong> When you publish a new post in <em>Posts → Add New</em>, it will appear on this page. The fields below are only used as <strong>fallback (prefilled)</strong> when there are no posts (or if you want placeholder content).'
      ),

      craftdigitally_acf_accordion_field('blog_landing_acc_hero', 'Hero', 1),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_hero_kicker', 'blog_landing_hero_kicker', 'Hero Kicker', 'text'), 50),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_hero_cta_label', 'blog_landing_hero_cta_label', 'Hero CTA Label', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_hero_cta_link', 'blog_landing_hero_cta_link', 'Hero CTA Link (e.g. #contact)', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_hero_subhead', 'blog_landing_hero_subhead', 'Hero Subhead', 'textarea'), 100),

      craftdigitally_acf_accordion_field('blog_landing_acc_featured', 'Featured (fallback only)', 0),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_featured_title', 'blog_landing_featured_title', 'Featured Post Title', 'text'), 50),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_featured_category', 'blog_landing_featured_category', 'Featured Category', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_featured_date', 'blog_landing_featured_date', 'Featured Date (text)', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_featured_excerpt', 'blog_landing_featured_excerpt', 'Featured Excerpt', 'textarea'), 100),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_featured_button_label', 'blog_landing_featured_button_label', 'Featured Button Label', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_url_field('blog_landing_featured_button_url', 'blog_landing_featured_button_url', 'Featured Button URL'), 75),
      craftdigitally_acf_wrap(craftdigitally_acf_image_field('blog_landing_featured_image', 'blog_landing_featured_image', 'Featured Image'), 50),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_featured_image_alt', 'blog_landing_featured_image_alt', 'Featured Image Alt', 'text'), 50),

      craftdigitally_acf_accordion_field('blog_landing_acc_grid', 'Grid cards (fallback only)', 0),
      craftdigitally_acf_repeater_field(
        'blog_landing_grid_cards',
        'blog_landing_grid_cards',
        'Blog Grid Cards (max 6)',
        array(
          craftdigitally_acf_wrap(craftdigitally_acf_image_field('blog_landing_card_image', 'image', 'Image'), 30),
          craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_card_title', 'title', 'Title', 'text'), 70),
          craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_card_category', 'category', 'Category', 'text'), 25),
          craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_card_date', 'date', 'Date (text)', 'text'), 25),
          craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_card_button_label', 'button_label', 'Button Label', 'text'), 25),
          craftdigitally_acf_wrap(craftdigitally_acf_url_field('blog_landing_card_url', 'url', 'URL'), 75),
          craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_card_image_alt', 'image_alt', 'Image Alt', 'text'), 100),
        ),
        0,
        6
      ),

      craftdigitally_acf_accordion_field('blog_landing_acc_cta', 'CTA form', 0, true),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_cta_title', 'blog_landing_cta_title', 'CTA Title', 'text'), 50),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_cta_button_label', 'blog_landing_cta_button_label', 'CTA Form: Button Label', 'text'), 50),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_cta_subtitle', 'blog_landing_cta_subtitle', 'CTA Subtitle', 'textarea'), 100),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_cta_name_placeholder', 'blog_landing_cta_name_placeholder', 'CTA Form: Name Placeholder', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_cta_phone_placeholder', 'blog_landing_cta_phone_placeholder', 'CTA Form: Phone Placeholder', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_cta_email_placeholder', 'blog_landing_cta_email_placeholder', 'CTA Form: Email Placeholder', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_cta_service_placeholder', 'blog_landing_cta_service_placeholder', 'CTA Form: Service Placeholder', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_landing_cta_message_placeholder', 'blog_landing_cta_message_placeholder', 'CTA Form: Message Placeholder', 'text'), 100),
    ),
    'location' => craftdigitally_acf_location_template_or_pages(
      'page-templates/page-blog-landing.php',
      array('blog')
    ),
    'active' => true,
  ));

  /**
   * BLOG DETAIL PAGE (`page-templates/page-blog-detail.php`) - Content is editable via structured fields.
   */
  acf_add_local_field_group(array(
    'key' => craftdigitally_acf_group_key('blog_detail_page'),
    'title' => 'CraftDigitally: Blog Detail Page',
    'fields' => array(
      craftdigitally_acf_message_field(
        'blog_detail_note',
        'How this page works',
        '<strong>This page is used to display a selected blog post</strong> (coming from the Blog list). The back button (<code>&lt; BACK TO BLOG</code>) is automatic and not editable here.'
      ),

      craftdigitally_acf_accordion_field('blog_detail_acc_hero', 'Hero (fallback only)', 1),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_detail_category', 'blog_detail_category', 'Category', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_detail_date', 'blog_detail_date', 'Date', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_detail_read_time', 'blog_detail_read_time', 'Read Time', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_detail_author', 'blog_detail_author', 'Author', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_detail_title', 'blog_detail_title', 'Post Title', 'text'), 100),

      craftdigitally_acf_accordion_field('blog_detail_acc_image', 'Featured image (fallback only)', 0),
      craftdigitally_acf_wrap(craftdigitally_acf_image_field('blog_detail_featured_image', 'blog_detail_featured_image', 'Featured Image'), 50),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_detail_featured_image_alt', 'blog_detail_featured_image_alt', 'Featured Image Alt', 'text'), 50),

      craftdigitally_acf_repeater_field(
        'blog_detail_toc',
        'blog_detail_toc',
        'Table of Contents Items',
        array(
          craftdigitally_acf_text_field('blog_detail_toc_item', 'text', 'Item', 'text'),
        ),
        0
      ),

      // Content sections (HTML allowed to preserve existing styled spans/classes).
      craftdigitally_acf_accordion_field('blog_detail_acc_content', 'Content blocks (fallback only)', 0),
      craftdigitally_acf_text_field('blog_detail_intro', 'blog_detail_intro', 'Intro Paragraph', 'textarea'),

      craftdigitally_acf_text_field('blog_detail_local_seo_title', 'blog_detail_local_seo_title', 'Section: What Is Local SEO? (Title)', 'text'),
      craftdigitally_acf_text_field('blog_detail_local_seo_html', 'blog_detail_local_seo_html', 'Section: What Is Local SEO? (Inner HTML)', 'textarea'),

      craftdigitally_acf_text_field('blog_detail_essential_title', 'blog_detail_essential_title', 'Section: Why Local SEO Is Essential (Title)', 'text'),
      craftdigitally_acf_text_field('blog_detail_essential_intro', 'blog_detail_essential_intro', 'Section: Why Local SEO Is Essential (Intro)', 'textarea'),
      craftdigitally_acf_text_field('blog_detail_essential_html', 'blog_detail_essential_html', 'Section: Why Local SEO Is Essential (Inner HTML)', 'textarea'),

      craftdigitally_acf_text_field('blog_detail_benefits_title', 'blog_detail_benefits_title', 'Section: Key Benefits (Title)', 'text'),
      craftdigitally_acf_text_field('blog_detail_benefits_html', 'blog_detail_benefits_html', 'Section: Key Benefits (Inner HTML)', 'textarea'),

      craftdigitally_acf_text_field('blog_detail_factors_title', 'blog_detail_factors_title', 'Section: Ranking Factors (Title)', 'text'),
      craftdigitally_acf_text_field('blog_detail_factors_intro', 'blog_detail_factors_intro', 'Section: Ranking Factors (Intro)', 'textarea'),
      craftdigitally_acf_text_field('blog_detail_factors_html', 'blog_detail_factors_html', 'Section: Ranking Factors (Inner HTML)', 'textarea'),

      craftdigitally_acf_text_field('blog_detail_improve_title', 'blog_detail_improve_title', 'Section: How to Improve (Title)', 'text'),
      craftdigitally_acf_text_field('blog_detail_improve_html', 'blog_detail_improve_html', 'Section: How to Improve (Inner HTML)', 'textarea'),

      craftdigitally_acf_text_field('blog_detail_testimonial_quote', 'blog_detail_testimonial_quote', 'Testimonial Quote', 'textarea'),

      craftdigitally_acf_text_field('blog_detail_final_title', 'blog_detail_final_title', 'Final Thoughts Title', 'text'),
      craftdigitally_acf_text_field('blog_detail_final_html', 'blog_detail_final_html', 'Final Thoughts (Inner HTML)', 'textarea'),

      // CTA blocks
      craftdigitally_acf_text_field('blog_detail_cta_title', 'blog_detail_cta_title', 'CTA Title', 'text'),
      craftdigitally_acf_text_field('blog_detail_cta_text', 'blog_detail_cta_text', 'CTA Text', 'textarea'),
      craftdigitally_acf_text_field('blog_detail_cta_button_label', 'blog_detail_cta_button_label', 'CTA Button Label', 'text'),

      craftdigitally_acf_accordion_field('blog_detail_acc_author', 'Author block (fallback only)', 0, true),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_detail_author_tag', 'blog_detail_author_tag', 'Author Tag (e.g. LOCAL SEO EXPERT)', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_detail_author_name', 'blog_detail_author_name', 'Author Name', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_image_field('blog_detail_author_social_image', 'blog_detail_author_social_image', 'Author Social Links Image'), 50),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_detail_author_bio', 'blog_detail_author_bio', 'Author Bio', 'textarea'), 100),
    ),
    'location' => craftdigitally_acf_location_template_or_pages(
      'page-templates/page-blog-detail.php',
      array('blog-detail', 'blog-details')
    ),
    'active' => true,
  ));

  /**
   * CASE STUDY LANDING PAGE (`page-templates/page-case-study-landing.php`)
   */
  acf_add_local_field_group(array(
    'key' => craftdigitally_acf_group_key('case_study_landing_page'),
    'title' => 'CraftDigitally: Case Study Landing Page',
    'fields' => array(
      craftdigitally_acf_text_field('cs_landing_hero_title', 'cs_landing_hero_title', 'Hero Title (HTML allowed for <br />)', 'textarea'),
      craftdigitally_acf_text_field('cs_landing_hero_subtitle', 'cs_landing_hero_subtitle', 'Hero Subtitle (HTML allowed for <br />)', 'textarea'),

      craftdigitally_acf_image_field('cs_landing_featured_logo', 'cs_landing_featured_logo', 'Featured Logo'),
      craftdigitally_acf_text_field('cs_landing_featured_title', 'cs_landing_featured_title', 'Featured Title', 'text'),
      craftdigitally_acf_text_field('cs_landing_featured_desc', 'cs_landing_featured_desc', 'Featured Description', 'textarea'),
      craftdigitally_acf_text_field('cs_landing_featured_button_label', 'cs_landing_featured_button_label', 'Featured Button Label', 'text'),
      craftdigitally_acf_url_field('cs_landing_featured_button_url', 'cs_landing_featured_button_url', 'Featured Button URL'),

      craftdigitally_acf_text_field('cs_landing_results_title', 'cs_landing_results_title', 'Results Section Title', 'text'),
      craftdigitally_acf_text_field('cs_landing_results_subtitle', 'cs_landing_results_subtitle', 'Results Section Subtitle (HTML allowed for <br />)', 'textarea'),
      craftdigitally_acf_number_field('cs_landing_results_count', 'cs_landing_results_count', 'Results Grid Count'),
      craftdigitally_acf_text_field('cs_landing_results_read_more_label', 'cs_landing_results_read_more_label', 'Results Card Button Label', 'text'),
      craftdigitally_acf_text_field('cs_landing_view_all_label', 'cs_landing_view_all_label', 'View All Button Label', 'text'),
      craftdigitally_acf_url_field('cs_landing_view_all_url', 'cs_landing_view_all_url', 'View All URL'),

      craftdigitally_acf_text_field('cs_landing_testimonial_heading', 'cs_landing_testimonial_heading', 'Testimonial Heading', 'text'),
      craftdigitally_acf_text_field('cs_landing_testimonial_quote', 'cs_landing_testimonial_quote', 'Testimonial Quote', 'textarea'),
      craftdigitally_acf_image_field('cs_landing_testimonial_avatar', 'cs_landing_testimonial_avatar', 'Testimonial Avatar'),
      craftdigitally_acf_text_field('cs_landing_testimonial_name', 'cs_landing_testimonial_name', 'Testimonial Name', 'text'),
      craftdigitally_acf_text_field('cs_landing_testimonial_role', 'cs_landing_testimonial_role', 'Testimonial Role', 'text'),

      craftdigitally_acf_text_field('cs_landing_cta_title', 'cs_landing_cta_title', 'CTA Title', 'text'),
      craftdigitally_acf_text_field('cs_landing_cta_subtitle', 'cs_landing_cta_subtitle', 'CTA Subtitle', 'textarea'),
      craftdigitally_acf_text_field('cs_landing_cta_button_label', 'cs_landing_cta_button_label', 'CTA Button Label', 'text'),

      // CTA form placeholders (so the whole section is editable via ACF).
      craftdigitally_acf_text_field('cs_landing_cta_name_placeholder', 'cs_landing_cta_name_placeholder', 'CTA Form: Name Placeholder', 'text'),
      craftdigitally_acf_text_field('cs_landing_cta_phone_placeholder', 'cs_landing_cta_phone_placeholder', 'CTA Form: Phone Placeholder', 'text'),
      craftdigitally_acf_text_field('cs_landing_cta_email_placeholder', 'cs_landing_cta_email_placeholder', 'CTA Form: Email Placeholder', 'text'),
      craftdigitally_acf_text_field('cs_landing_cta_service_placeholder', 'cs_landing_cta_service_placeholder', 'CTA Form: Service Placeholder', 'text'),
      craftdigitally_acf_text_field('cs_landing_cta_message_placeholder', 'cs_landing_cta_message_placeholder', 'CTA Form: Message Placeholder', 'text'),
      craftdigitally_acf_text_field('cs_landing_cta_submit_label', 'cs_landing_cta_submit_label', 'CTA Form: Submit Button Label', 'text'),
    ),
    'location' => craftdigitally_acf_location_template_or_pages(
      'page-templates/page-case-study-landing.php',
      array('case-studies', 'case-study', 'case-study-landing')
    ),
    'active' => true,
  ));

  /**
   * CASE STUDY DETAIL PAGE (`page-templates/page-case-study-detail.php`)
   */
  acf_add_local_field_group(array(
    'key' => craftdigitally_acf_group_key('case_study_detail_page'),
    'title' => 'CraftDigitally: Case Study Detail Page',
    'fields' => array(
      craftdigitally_acf_image_field('cs_detail_hero_logo', 'cs_detail_hero_logo', 'Hero Logo'),
      craftdigitally_acf_text_field('cs_detail_hero_title', 'cs_detail_hero_title', 'Hero Title (HTML allowed for <br />)', 'textarea'),
      craftdigitally_acf_repeater_field(
        'cs_detail_metrics',
        'cs_detail_metrics',
        'Hero Metrics (3 items)',
        array(
          craftdigitally_acf_text_field('cs_detail_metric_value', 'value', 'Value (e.g. 2,117%)', 'text'),
          craftdigitally_acf_text_field('cs_detail_metric_label', 'label', 'Label', 'text'),
        ),
        0
      ),
      craftdigitally_acf_text_field('cs_detail_hero_button_label', 'cs_detail_hero_button_label', 'Hero Button Label', 'text'),

      craftdigitally_acf_text_field('cs_detail_overview', 'cs_detail_overview', 'Overview Text', 'textarea'),

      craftdigitally_acf_text_field('cs_detail_industry', 'cs_detail_industry', 'Industry', 'text'),
      craftdigitally_acf_text_field('cs_detail_company_name', 'cs_detail_company_name', 'Company Name', 'text'),
      craftdigitally_acf_text_field('cs_detail_services', 'cs_detail_services', 'Services', 'text'),

      craftdigitally_acf_text_field('cs_detail_problem_title', 'cs_detail_problem_title', 'Problem Title', 'text'),
      craftdigitally_acf_repeater_field(
        'cs_detail_problem_paras',
        'cs_detail_problem_paras',
        'Problem Paragraphs',
        array(
          craftdigitally_acf_text_field('cs_detail_problem_para', 'text', 'Paragraph', 'textarea'),
        ),
        0
      ),

      craftdigitally_acf_text_field('cs_detail_solution_title', 'cs_detail_solution_title', 'Solution Title', 'text'),
      craftdigitally_acf_text_field('cs_detail_solution_intro', 'cs_detail_solution_intro', 'Solution Intro', 'textarea'),
      craftdigitally_acf_text_field('cs_detail_solution_lead', 'cs_detail_solution_lead', 'Solution Lead-in (small line)', 'textarea'),
      craftdigitally_acf_text_field('cs_detail_solution_html', 'cs_detail_solution_html', 'Solution List (Inner HTML)', 'textarea'),

      craftdigitally_acf_image_field('cs_detail_image', 'cs_detail_image', 'Main Image'),
      craftdigitally_acf_text_field('cs_detail_image_caption', 'cs_detail_image_caption', 'Image Caption', 'text'),

      craftdigitally_acf_text_field('cs_detail_testimonial_quote', 'cs_detail_testimonial_quote', 'Testimonial Quote', 'textarea'),
      craftdigitally_acf_image_field('cs_detail_testimonial_avatar', 'cs_detail_testimonial_avatar', 'Testimonial Avatar'),
      craftdigitally_acf_text_field('cs_detail_testimonial_name', 'cs_detail_testimonial_name', 'Testimonial Name', 'text'),
      craftdigitally_acf_text_field('cs_detail_testimonial_role', 'cs_detail_testimonial_role', 'Testimonial Role', 'text'),

      craftdigitally_acf_text_field('cs_detail_outcome_title', 'cs_detail_outcome_title', 'Outcome Title', 'text'),
      craftdigitally_acf_text_field('cs_detail_outcome_text', 'cs_detail_outcome_text', 'Outcome Text', 'textarea'),
      craftdigitally_acf_text_field('cs_detail_outcome_html', 'cs_detail_outcome_html', 'Outcome Bullets (Inner HTML)', 'textarea'),

      craftdigitally_acf_text_field('cs_detail_cta_title', 'cs_detail_cta_title', 'CTA Title', 'text'),
      craftdigitally_acf_text_field('cs_detail_cta_text', 'cs_detail_cta_text', 'CTA Text', 'textarea'),
      craftdigitally_acf_text_field('cs_detail_cta_button_label', 'cs_detail_cta_button_label', 'CTA Button Label', 'text'),

      craftdigitally_acf_text_field('cs_detail_related_title', 'cs_detail_related_title', 'Related Case Studies Title', 'text'),
      craftdigitally_acf_text_field('cs_detail_related_subtitle', 'cs_detail_related_subtitle', 'Related Case Studies Subtitle (HTML allowed for <br />)', 'textarea'),
      craftdigitally_acf_number_field('cs_detail_related_count', 'cs_detail_related_count', 'Related Grid Count'),
      craftdigitally_acf_text_field('cs_detail_related_read_more_label', 'cs_detail_related_read_more_label', 'Related Card Button Label', 'text'),

      // CTA form placeholders (bottom form section).
      craftdigitally_acf_text_field('cs_detail_form_title', 'cs_detail_form_title', 'Bottom Form CTA Title', 'text'),
      craftdigitally_acf_text_field('cs_detail_form_subtitle', 'cs_detail_form_subtitle', 'Bottom Form CTA Subtitle', 'textarea'),
      craftdigitally_acf_text_field('cs_detail_form_name_placeholder', 'cs_detail_form_name_placeholder', 'Bottom Form: Name Placeholder', 'text'),
      craftdigitally_acf_text_field('cs_detail_form_phone_placeholder', 'cs_detail_form_phone_placeholder', 'Bottom Form: Phone Placeholder', 'text'),
      craftdigitally_acf_text_field('cs_detail_form_email_placeholder', 'cs_detail_form_email_placeholder', 'Bottom Form: Email Placeholder', 'text'),
      craftdigitally_acf_text_field('cs_detail_form_service_placeholder', 'cs_detail_form_service_placeholder', 'Bottom Form: Service Placeholder', 'text'),
      craftdigitally_acf_text_field('cs_detail_form_message_placeholder', 'cs_detail_form_message_placeholder', 'Bottom Form: Message Placeholder', 'text'),
      craftdigitally_acf_text_field('cs_detail_form_submit_label', 'cs_detail_form_submit_label', 'Bottom Form: Submit Button Label', 'text'),
    ),
    'location' => craftdigitally_acf_location_template_or_pages(
      'page-templates/page-case-study-detail.php',
      array('case-study-detail', 'case-studies-detail')
    ),
    'active' => true,
  ));

  /**
   * CONTACT PAGE (`page-templates/page-contact.php`)
   */
  acf_add_local_field_group(array(
    'key' => craftdigitally_acf_group_key('contact_page'),
    'title' => 'CraftDigitally: Contact Page',
    'fields' => array(
      craftdigitally_acf_message_field(
        'contact_page_note',
        'How this works',
        '<strong>These fields control the Contact Page layout.</strong> If you leave fields empty, the theme will use the existing hardcoded design as fallback.'
      ),

      craftdigitally_acf_tab_field('contact_tab_hero', 'Hero', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_hero_kicker', 'contact_hero_kicker', 'Hero Kicker', 'text'),
        "Let's Start a Conversation"
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_hero_subhead', 'contact_hero_subhead', 'Hero Subhead', 'textarea'),
        "Ready to transform your digital presence? We're here to help. Reach out to discuss your project or ask any questions about our services."
      ),

      craftdigitally_acf_tab_field('contact_tab_form', 'Form', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_form_title', 'contact_form_title', 'Form Title', 'text'),
        'Send us a Message'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_form_subtitle', 'contact_form_subtitle', 'Form Subtitle', 'textarea'),
        "Fill out the form below and we'll get back to you within 24 hours."
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_form_name_label', 'contact_form_name_label', 'Form: Name Label', 'text'),
        'Full Name*'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_form_name_placeholder', 'contact_form_name_placeholder', 'Form: Name Placeholder', 'text'),
        'Enter your full name'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_form_email_label', 'contact_form_email_label', 'Form: Email Label', 'text'),
        'Email Address*'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_form_email_placeholder', 'contact_form_email_placeholder', 'Form: Email Placeholder', 'text'),
        'you@example.com'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_form_phone_label', 'contact_form_phone_label', 'Form: Phone Label', 'text'),
        'Phone Number'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_form_phone_placeholder', 'contact_form_phone_placeholder', 'Form: Phone Placeholder', 'text'),
        '+91 XXXXX XXXXX'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_form_service_label', 'contact_form_service_label', 'Form: Service Label', 'text'),
        'Service Interest'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_form_service_placeholder', 'contact_form_service_placeholder', 'Form: Service Placeholder', 'text'),
        'e.g. Local SEO'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_form_message_label', 'contact_form_message_label', 'Form: Message Label', 'text'),
        'Message*'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_form_message_placeholder', 'contact_form_message_placeholder', 'Form: Message Placeholder', 'text'),
        'Tell us about your project...'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_form_button_label', 'contact_form_button_label', 'Form: Button Label', 'text'),
        'Send Message'
      ),

      craftdigitally_acf_tab_field('contact_tab_info', 'Info', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_info_title', 'contact_info_title', 'Info Title', 'text'),
        'Get in Touch'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_info_subtitle', 'contact_info_subtitle', 'Info Subtitle', 'textarea'),
        "Have a question or ready to start your project? We'd love to hear from you."
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_info_email', 'contact_info_email', 'Email', 'text'),
        'hello@craftdigitally.com'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_info_phone', 'contact_info_phone', 'Phone', 'text'),
        '+91 979 849 6798'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_info_address', 'contact_info_address', 'Address', 'textarea'),
        'North Phase, 4th Floor, D-4, Road, Motera, Ahmedabad, Gujarat 380005'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_info_hours', 'contact_info_hours', 'Business Hours', 'text'),
        'Monday - Friday: 9:00 AM - 6:00 PM IST'
      ),

      craftdigitally_acf_tab_field('contact_tab_testimonials', 'Testimonials', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_testimonials_title', 'contact_testimonials_title', 'Testimonials Title', 'text'),
        'What Clients Say About Working With Us'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_testimonials_subtitle', 'contact_testimonials_subtitle', 'Testimonials Subtitle', 'textarea'),
        'Discover why clients trust us to handle their SEO campaigns and how our collaboration drives measurable impact.'
      ),
      craftdigitally_acf_repeater_field(
        'contact_testimonials_cards',
        'contact_testimonials_cards',
        'Testimonials Cards',
        array(
          craftdigitally_acf_text_field('contact_testimonial_card_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('contact_testimonial_card_quote', 'quote', 'Quote', 'textarea'),
          craftdigitally_acf_image_field('contact_testimonial_card_avatar', 'avatar', 'Avatar'),
          craftdigitally_acf_text_field('contact_testimonial_card_name', 'name', 'Name', 'text'),
          craftdigitally_acf_text_field('contact_testimonial_card_role', 'role', 'Role', 'text'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('contact_tab_results', 'Results', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_results_title', 'contact_results_title', 'Results Section Title', 'text'),
        'Results Our Clients Have Achieved'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_results_subtitle', 'contact_results_subtitle', 'Results Section Subtitle (HTML allowed for <br />)', 'textarea'),
        "Our SEO strategies go beyond rankings — they deliver measurable business growth.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success."
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_number_field('contact_results_count', 'contact_results_count', 'Results Grid Count'),
        6
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_results_read_more_label', 'contact_results_read_more_label', 'Results Card Button Label', 'text'),
        'Read Full Story'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_results_view_all_label', 'contact_results_view_all_label', 'View All Button Label', 'text'),
        'View all Case Studies'
      ),

      craftdigitally_acf_tab_field('contact_tab_why_work', 'Why Work', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_why_work_title', 'contact_why_work_title', 'Why Work With Us Title', 'text'),
        'Why Work With Us?'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_why_work_subtitle', 'contact_why_work_subtitle', 'Why Work With Us Subtitle', 'textarea'),
        'We combine expertise, creativity, and dedication to deliver results that matter.'
      ),
      craftdigitally_acf_repeater_field(
        'contact_why_work_cards',
        'contact_why_work_cards',
        'Why Work Cards',
        array(
          craftdigitally_acf_text_field('contact_why_work_card_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('contact_why_work_card_desc', 'desc', 'Description', 'textarea'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('contact_tab_faq', 'FAQs', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_faq_title', 'contact_faq_title', 'FAQ Title', 'text'),
        'FAQs'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('contact_faq_subtitle', 'contact_faq_subtitle', 'FAQ Subtitle', 'textarea'),
        'Get clear answers to the most common questions about our Local SEO services'
      ),
      craftdigitally_acf_repeater_field(
        'contact_faq_items',
        'contact_faq_items',
        'FAQ Items',
        array(
          craftdigitally_acf_text_field('contact_faq_q', 'question', 'Question', 'text'),
          craftdigitally_acf_text_field('contact_faq_a', 'answer', 'Answer', 'textarea'),
        ),
        0
      ),
    ),
    'location' => craftdigitally_acf_location_template_or_pages(
      'page-templates/page-contact.php',
      array('contact')
    ),
    'active' => true,
  ));

  /**
   * SERVICE LANDING PAGE (`page-templates/page-service-landing.php`)
   */
  acf_add_local_field_group(array(
    'key' => craftdigitally_acf_group_key('service_landing_page'),
    'title' => 'CraftDigitally: Service Landing Page',
    'fields' => array(
      craftdigitally_acf_message_field(
        'service_landing_note',
        'How this works',
        '<strong>These fields control the Service Landing Page layout.</strong> If you leave fields empty, the theme will use the existing hardcoded design as fallback. The Services Grid section will automatically display service posts from the Service custom post type archive.'
      ),

      craftdigitally_acf_tab_field('service_landing_tab_hero', 'Hero', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_hero_kicker', 'service_landing_hero_kicker', 'Hero Kicker', 'text'),
        'SEO Services That Help Your Business Get Found - and Chosen'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_hero_subhead', 'service_landing_hero_subhead', 'Hero Subhead', 'textarea'),
        'With tailored SEO, we make it easier for customers to discover your business and choose you over competitors. Clear strategy, transparent reporting, and measurable results-every step of the way.'
      ),

      craftdigitally_acf_tab_field('service_landing_tab_featured', 'Featured', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_featured_title', 'service_landing_featured_title', 'Featured Title', 'text'),
        'Local SEO That Drives Real Foot Traffic to Your Business'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_featured_desc', 'service_landing_featured_desc', 'Featured Description', 'textarea'),
        'Our Local SEO strategies make it easier for people nearby to discover, trust, and choose your business. From GBP optimization to local ranking boosts, we help you turn searches into walk-ins.'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_featured_button_label', 'service_landing_featured_button_label', 'Featured Button Label', 'text'),
        'Read Our Services'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_featured_button_link', 'service_landing_featured_button_link', 'Featured Button Link (e.g. #services)', 'text'),
        '#services'
      ),
      craftdigitally_acf_image_field('service_landing_featured_image', 'service_landing_featured_image', 'Featured Image'),
      craftdigitally_acf_text_field('service_landing_featured_image_alt', 'service_landing_featured_image_alt', 'Featured Image Alt', 'text'),

      craftdigitally_acf_tab_field('service_landing_tab_services', 'Services Grid', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_services_title', 'service_landing_services_title', 'Services Title', 'text'),
        'Our Services'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_services_subtitle', 'service_landing_services_subtitle', 'Services Subtitle', 'textarea'),
        "Whether you're a startup in Chandkheda or an established brand in Prahladnagar, we've got your back. Your Roadmap to More Traffic, Leads & Sales"
      ),
      craftdigitally_acf_message_field(
        'service_landing_services_note',
        'Note',
        'The Services Grid will automatically display service posts from the Service custom post type. If no service posts exist, it will fall back to the static cards below.'
      ),
      craftdigitally_acf_repeater_field(
        'service_landing_services_cards',
        'service_landing_services_cards',
        'Services Cards (Fallback)',
        array(
          craftdigitally_acf_image_field('service_landing_service_icon', 'icon', 'Icon'),
          craftdigitally_acf_text_field('service_landing_service_icon_alt', 'icon_alt', 'Icon Alt', 'text'),
          craftdigitally_acf_text_field('service_landing_service_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('service_landing_service_desc', 'desc', 'Description', 'textarea'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('service_landing_tab_cta', 'CTA', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_cta_title', 'service_landing_cta_title', 'CTA Title', 'text'),
        'Ready to Dominate Your Competition?'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_cta_subtitle', 'service_landing_cta_subtitle', 'CTA Subtitle', 'textarea'),
        'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_cta_button_label', 'service_landing_cta_button_label', 'CTA Button Label', 'text'),
        "Let's Connect"
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_cta_name_placeholder', 'service_landing_cta_name_placeholder', 'CTA Form: Name Placeholder', 'text'),
        'Name'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_cta_phone_placeholder', 'service_landing_cta_phone_placeholder', 'CTA Form: Phone Placeholder', 'text'),
        'Phone'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_cta_email_placeholder', 'service_landing_cta_email_placeholder', 'CTA Form: Email Placeholder', 'text'),
        'Email'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_cta_service_placeholder', 'service_landing_cta_service_placeholder', 'CTA Form: Service Placeholder', 'text'),
        'Service'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_cta_message_placeholder', 'service_landing_cta_message_placeholder', 'CTA Form: Message Placeholder', 'text'),
        'Message'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_landing_cta_submit_label', 'service_landing_cta_submit_label', 'CTA Form: Submit Button Label', 'text'),
        "Let's Connect"
      ),
    ),
    'location' => craftdigitally_acf_location_template_or_pages(
      'page-templates/page-service-landing.php',
      array('services', 'service')
    ),
    'active' => true,
  ));

  /**
   * SERVICE DETAIL PAGE (`page-templates/page-service-detail.php`)
   */
  acf_add_local_field_group(array(
    'key' => craftdigitally_acf_group_key('service_detail_page'),
    'title' => 'CraftDigitally: Service Detail Page',
    'fields' => array(
      craftdigitally_acf_message_field(
        'service_detail_note',
        'How this works',
        '<strong>These fields control the Service Detail Page layout.</strong> If you leave fields empty, the theme will use the existing hardcoded design as fallback.'
      ),

      craftdigitally_acf_tab_field('service_detail_tab_hero', 'Hero', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_hero_headline', 'service_hero_headline', 'Hero Headline', 'text'),
        'Local SEO That Drives Real Customers to Your Business'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_hero_subhead', 'service_hero_subhead', 'Hero Subhead', 'textarea'),
        'Get more calls, more leads, and more foot traffic from the people already searching for what you offer in your city.'
      ),

      craftdigitally_acf_tab_field('service_detail_tab_problem', 'Problem', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_problem_headline', 'service_problem_headline', 'Problem Headline', 'text'),
        'Is Your Business Invisible to Nearby Customers?'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_problem_text', 'service_problem_text', 'Problem Text', 'textarea'),
        '46% of all Google searches have "local intent" (people looking for services near them). If your business doesn\'t appear in the top 3 spots of the "Google Map Pack," you typically don\'t exist to those customers.

At CraftDigitally, we don\'t just "do SEO." We engineer your online presence to ensure that when local customers search for your services, you are the first brand they see and the first one they call.'
      ),
      craftdigitally_acf_image_field('service_problem_image', 'service_problem_image', 'Problem Image'),
      craftdigitally_acf_repeater_field(
        'service_key_highlights',
        'service_key_highlights',
        'Key Highlights',
        array(
          craftdigitally_acf_text_field('service_highlight_bold', 'bold', 'Bold Label', 'text'),
          craftdigitally_acf_text_field('service_highlight_text', 'text', 'Text', 'text'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('service_detail_tab_benefits', 'Benefits', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_benefits_title', 'service_benefits_title', 'Benefits Title', 'text'),
        'Key Benefits of Local SEO for Your Business'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_benefits_subtitle', 'service_benefits_subtitle', 'Benefits Subtitle', 'textarea'),
        'From higher visibility to more leads, see how local SEO helps you get found and chosen.'
      ),
      craftdigitally_acf_repeater_field(
        'service_benefits_cards',
        'service_benefits_cards',
        'Benefits Cards',
        array(
          craftdigitally_acf_image_field('service_benefit_icon', 'icon', 'Icon'),
          craftdigitally_acf_text_field('service_benefit_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('service_benefit_desc', 'desc', 'Description', 'textarea'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('service_detail_tab_mid_cta', 'Mid CTA', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_mid_cta_title', 'service_mid_cta_title', 'Mid CTA Title', 'text'),
        'Ready to Dominate Your Competition?'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_mid_cta_text', 'service_mid_cta_text', 'Mid CTA Text', 'textarea'),
        'Get a clear local SEO strategy and start turning nearby searches into real customers.'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_mid_cta_button_label', 'service_mid_cta_button_label', 'Mid CTA Button Label', 'text'),
        'Book a Free Consult'
      ),

      craftdigitally_acf_tab_field('service_detail_tab_results', 'Results', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_results_title', 'service_results_title', 'Results Title', 'text'),
        'Results Our Clients Have Achieved'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_results_subtitle', 'service_results_subtitle', 'Results Subtitle', 'textarea'),
        'From higher visibility to more traffic and leads, see how our clients turned local search into success.'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_number_field('service_results_count', 'service_results_count', 'Results Grid Count'),
        6
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_results_read_more_label', 'service_results_read_more_label', 'Results Card Button Label', 'text'),
        'Read Full Story'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_results_view_all_label', 'service_results_view_all_label', 'Results View All Label', 'text'),
        'View All Case Studies'
      ),

      craftdigitally_acf_tab_field('service_detail_tab_services', 'Detailed Services', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_services_title', 'service_services_title', 'Detailed Services Title', 'text'),
        'Local SEO Services That Help You Get Found — and Chosen'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_services_subtitle', 'service_services_subtitle', 'Detailed Services Subtitle', 'textarea'),
        'We combine strategy, optimization, and ongoing support so your business becomes the obvious choice locally.'
      ),
      craftdigitally_acf_repeater_field(
        'service_services_items',
        'service_services_items',
        'Detailed Services Items',
        array(
          craftdigitally_acf_text_field('service_service_item_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('service_service_item_desc', 'desc', 'Description', 'textarea'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('service_detail_tab_process', 'Process', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_process_title', 'service_process_title', 'Process Title', 'text'),
        'Our Proven Process'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_process_subtitle', 'service_process_subtitle', 'Process Subtitle', 'textarea'),
        'From audit to optimization, we follow a clear, repeatable process that delivers results.'
      ),
      craftdigitally_acf_repeater_field(
        'service_process_steps',
        'service_process_steps',
        'Process Steps',
        array(
          craftdigitally_acf_text_field('service_process_step_number', 'number', 'Number', 'text'),
          craftdigitally_acf_text_field('service_process_step_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('service_process_step_desc', 'desc', 'Description', 'textarea'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('service_detail_tab_faq', 'FAQs', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_faq_title', 'service_faq_title', 'FAQ Title', 'text'),
        'FAQs'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_faq_subtitle', 'service_faq_subtitle', 'FAQ Subtitle', 'textarea'),
        'Find answers to common questions about local SEO and how we work.'
      ),
      craftdigitally_acf_repeater_field(
        'service_faq_items',
        'service_faq_items',
        'FAQ Items',
        array(
          craftdigitally_acf_text_field('service_faq_q', 'question', 'Question', 'text'),
          craftdigitally_acf_text_field('service_faq_a', 'answer', 'Answer', 'textarea'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('service_detail_tab_cta', 'CTA', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_title', 'service_cta_title', 'CTA Title', 'text'),
        'Ready to Dominate Your Competition?'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_subtitle', 'service_cta_subtitle', 'CTA Subtitle', 'textarea'),
        'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad.'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_button_label', 'service_cta_button_label', 'CTA Button Label', 'text'),
        "Let's Connect"
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_name_placeholder', 'service_cta_name_placeholder', 'CTA Form: Name Placeholder', 'text'),
        'Name'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_phone_placeholder', 'service_cta_phone_placeholder', 'CTA Form: Phone Placeholder', 'text'),
        'Phone'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_email_placeholder', 'service_cta_email_placeholder', 'CTA Form: Email Placeholder', 'text'),
        'Email'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_service_placeholder', 'service_cta_service_placeholder', 'CTA Form: Service Placeholder', 'text'),
        'Service'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_message_placeholder', 'service_cta_message_placeholder', 'CTA Form: Message Placeholder', 'text'),
        'Message'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_submit_label', 'service_cta_submit_label', 'CTA Form: Submit Button Label', 'text'),
        "Let's Connect"
      ),
    ),
    'location' => craftdigitally_acf_location_template_or_pages(
      'page-templates/page-service-detail.php',
      array('service-detail')
    ),
    'active' => true,
  ));
  /**
   * FRONT PAGE (`front-page.php`)
   */
  acf_add_local_field_group(array(
    'key' => craftdigitally_acf_group_key('home_front_page'),
    'title' => 'CraftDigitally: Home Page (Front Page)',
    'fields' => array(
      craftdigitally_acf_message_field(
        'home_page_note',
        'How this works',
        '<strong>These fields control the Home Page layout.</strong> If you leave fields empty, the theme will use the existing hardcoded design as fallback.'
      ),

      craftdigitally_acf_tab_field('home_tab_hero', 'Hero', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_hero_title', 'home_hero_title', 'Hero Title (HTML allowed for <br />)', 'textarea'),
        'Stand out online with clarity,<br />strategy and confidence'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_hero_subtitle', 'home_hero_subtitle', 'Hero Subtitle', 'textarea'),
        "We're a leading digital marketing agency that helps businesses in Ahmedabad turn SEO, web development, and digital marketing into powerful growth channels."
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_hero_cta_label', 'home_hero_cta_label', 'Hero CTA Label', 'text'),
        'Book a Free Consult'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_hero_cta_link', 'home_hero_cta_link', 'Hero CTA Link (e.g. #contact)', 'text'),
        '#contact'
      ),

      craftdigitally_acf_tab_field('home_tab_case_studies', 'Case Studies', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_case_studies_title', 'home_case_studies_title', 'Case Studies Section Title', 'text'),
        'Results Our Clients Have Achieved'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_case_studies_subtitle', 'home_case_studies_subtitle', 'Case Studies Section Subtitle (HTML allowed for <br />)', 'textarea'),
        "Our SEO strategies go beyond rankings — they deliver measurable business growth.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success."
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_number_field('home_case_studies_count', 'home_case_studies_count', 'Case Studies Grid Count'),
        6
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_case_studies_view_all_label', 'home_case_studies_view_all_label', 'Case Studies View All Button Label', 'text'),
        'View all Case Studies'
      ),
      craftdigitally_acf_url_field('home_case_studies_view_all_url', 'home_case_studies_view_all_url', 'Case Studies View All URL'),

      craftdigitally_acf_tab_field('home_tab_testimonials', 'Testimonials', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_testimonials_title', 'home_testimonials_title', 'Testimonials Title', 'text'),
        'What Clients Say About Working With Us'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_testimonials_subtitle', 'home_testimonials_subtitle', 'Testimonials Subtitle', 'text'),
        'We believe great partnerships lead to great outcomes.'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_testimonials_description', 'home_testimonials_description', 'Testimonials Description', 'textarea'),
        'Discover why clients trust us to handle their SEO and how our collaboration drives measurable impact.'
      ),
      craftdigitally_acf_repeater_field(
        'home_testimonials_repeater',
        'home_testimonials',
        'Testimonials (cards)',
        array(
          craftdigitally_acf_text_field('home_testimonials_item_title', 'title', 'Card Title', 'text'),
          craftdigitally_acf_text_field('home_testimonials_item_quote', 'quote', 'Quote', 'textarea'),
          craftdigitally_acf_image_field('home_testimonials_item_avatar', 'avatar', 'Avatar'),
          craftdigitally_acf_text_field('home_testimonials_item_name', 'name', 'Name', 'text'),
          craftdigitally_acf_text_field('home_testimonials_item_role', 'role', 'Role', 'text'),
          craftdigitally_acf_text_field('home_testimonials_item_company', 'company', 'Company', 'text'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('home_tab_process', 'Process', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_process_title', 'home_process_title', 'Process Title', 'text'),
        'Our Proven Process'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_process_subtitle', 'home_process_subtitle', 'Process Subtitle', 'textarea'),
        'A clear, results-driven approach designed to help your business grow step by step.'
      ),
      craftdigitally_acf_repeater_field(
        'home_process_steps_repeater',
        'home_process_steps',
        'Process Steps',
        array(
          craftdigitally_acf_text_field('home_process_step_number', 'number', 'Number', 'text'),
          craftdigitally_acf_text_field('home_process_step_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('home_process_step_desc', 'description', 'Description', 'textarea'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('home_tab_services', 'Services', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_services_title', 'home_services_title', 'Services Title', 'text'),
        'Our Services'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_services_subtitle', 'home_services_subtitle', 'Services Subtitle', 'textarea'),
        "Whether you're a startup in Chandkheda or an established brand in Prahladnagar, we've got your back. Your Roadmap to More Traffic, Leads & Sales"
      ),
      craftdigitally_acf_repeater_field(
        'home_services_repeater',
        'home_services',
        'Services (cards)',
        array(
          craftdigitally_acf_image_field('home_services_item_icon', 'icon', 'Icon'),
          craftdigitally_acf_text_field('home_services_item_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('home_services_item_desc', 'description', 'Description', 'textarea'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('home_tab_why_choose', 'Why Choose', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_why_choose_title', 'home_why_choose_title', 'Why Choose Title', 'text'),
        'Why Choose Craft Digitally?'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_why_choose_text', 'home_why_choose_text', 'Why Choose Text', 'textarea'),
        'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad.'
      ),
      craftdigitally_acf_image_field('home_why_choose_image', 'home_why_choose_image', 'Why Choose Image'),
      craftdigitally_acf_repeater_field(
        'home_why_choose_points_repeater',
        'home_why_choose_points',
        'Why Choose Points',
        array(
          craftdigitally_acf_text_field('home_why_choose_point_text', 'text', 'Point', 'text'),
        ),
        0
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_why_choose_button_label', 'home_why_choose_button_label', 'Why Choose Button Label', 'text'),
        'Contact Us'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_why_choose_button_link', 'home_why_choose_button_link', 'Why Choose Button Link', 'text'),
        '#contact'
      ),

      craftdigitally_acf_tab_field('home_tab_cta', 'CTA', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_cta_title', 'home_cta_title', 'CTA Title', 'text'),
        'Ready to Dominate Your Competition?'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_cta_subtitle', 'home_cta_subtitle', 'CTA Subtitle', 'textarea'),
        'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_cta_name_placeholder', 'home_cta_name_placeholder', 'CTA Form: Name Placeholder', 'text'),
        'Name'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_cta_phone_placeholder', 'home_cta_phone_placeholder', 'CTA Form: Phone Placeholder', 'text'),
        'Phone'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_cta_email_placeholder', 'home_cta_email_placeholder', 'CTA Form: Email Placeholder', 'text'),
        'Email'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_cta_service_placeholder', 'home_cta_service_placeholder', 'CTA Form: Service Placeholder', 'text'),
        'Service'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_cta_message_placeholder', 'home_cta_message_placeholder', 'CTA Form: Message Placeholder', 'text'),
        'Message'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('home_cta_button_label', 'home_cta_button_label', 'CTA Form: Button Label', 'text'),
        "Let's Connect"
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'page_type',
          'operator' => '==',
          'value' => 'front_page',
        ),
      ),
    ),
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
  ));

  /**
   * ABOUT PAGE (`page-templates/page-about.php`)
   */
  acf_add_local_field_group(array(
    'key' => craftdigitally_acf_group_key('about_page'),
    'title' => 'CraftDigitally: About Page',
    'fields' => array(
      craftdigitally_acf_message_field(
        'about_page_note',
        'How this works',
        '<strong>These fields control the About Page layout.</strong> If you leave fields empty, the theme will use the existing hardcoded design as fallback.'
      ),

      craftdigitally_acf_tab_field('about_tab_hero', 'Hero', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_hero_kicker', 'about_hero_kicker', 'Hero Kicker', 'text'),
        'Driving Local Growth Through Smart SEO Strategies'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_hero_subhead', 'about_hero_subhead', 'Hero Subhead', 'textarea'),
        'Led by SEO experts with years of hands-on experience, Craft Digitally delivers ethical, data-driven Local SEO that produces real business results.'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_hero_cta_label', 'about_hero_cta_label', 'Hero CTA Label', 'text'),
        'Book a Free Consult'
      ),
      craftdigitally_acf_url_field('about_hero_cta_url', 'about_hero_cta_url', 'Hero CTA URL'),

      craftdigitally_acf_tab_field('about_tab_growth', 'Growth', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_growth_title', 'about_growth_title', 'Growth Section Title', 'text'),
        'Growth-focused digital marketing and web solutions…'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_growth_intro_1', 'about_growth_intro_1', 'Growth Intro (Paragraph 1)', 'textarea'),
        'CraftDigitally is a results-driven digital marketing agency based in Ahmedabad, Gujarat, helping businesses grow through strategic online visibility, strong brand presence, and measurable performance.'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_growth_intro_2', 'about_growth_intro_2', 'Growth Intro (Paragraph 2)', 'textarea'),
        'We build digital strategies that connect business goals to outcomes such as more qualified traffic, better leads, and higher conversions.'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_growth_trust_label', 'about_growth_trust_label', 'Trust Strip Label', 'text'),
        'Trust strip (small badges under hero):'
      ),
      craftdigitally_acf_repeater_field(
        'about_growth_trust_items',
        'about_growth_trust_items',
        'Trust Strip Items',
        array(
          craftdigitally_acf_text_field('about_growth_trust_item_strong', 'strong', 'Bold Part (e.g. 85%+)', 'text'),
          craftdigitally_acf_text_field('about_growth_trust_item_text', 'text', 'Text (after bold)', 'text'),
        ),
        0
      ),
      craftdigitally_acf_image_field('about_growth_image', 'about_growth_image', 'Growth Section Image'),
      craftdigitally_acf_text_field('about_growth_image_alt', 'about_growth_image_alt', 'Growth Image Alt Text', 'text'),

      craftdigitally_acf_tab_field('about_tab_who', 'Who We Are', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_who_title', 'about_who_title', 'Who We Are Title', 'text'),
        'Who we are'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_who_text', 'about_who_text', 'Who We Are Text', 'textarea'),
        'CraftDigitally is a full-service digital marketing partner that blends strategy, creativity, and performance. We start by understanding your business goals, then design and execute digital solutions that match your audience and your market. Our team works across SEO, content, social media, paid campaigns, web development, and analytics. The focus stays the same: clarity, consistency, and results you can measure.'
      ),

      craftdigitally_acf_tab_field('about_tab_what', 'What We Do', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_what_title', 'about_what_title', 'What We Do Title', 'text'),
        'What we do'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_what_subtitle', 'about_what_subtitle', 'What We Do Subtitle', 'textarea'),
        'Discover how strategic Local SEO helps your business attract nearby customers, boost visibility on Google, and drive real-world results.'
      ),
      craftdigitally_acf_repeater_field(
        'about_services_cards',
        'about_services_cards',
        'Services Cards',
        array(
          craftdigitally_acf_image_field('about_service_icon', 'icon', 'Icon'),
          craftdigitally_acf_text_field('about_service_icon_alt', 'icon_alt', 'Icon Alt Text', 'text'),
          craftdigitally_acf_text_field('about_service_icon_w', 'icon_w', 'Icon Width (px)', 'text'),
          craftdigitally_acf_text_field('about_service_icon_h', 'icon_h', 'Icon Height (px)', 'text'),
          craftdigitally_acf_text_field('about_service_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('about_service_desc', 'desc', 'Description', 'text'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('about_tab_trust', 'Trust', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_trust_title', 'about_trust_title', 'Trust Section Title', 'text'),
        'How we build trust'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_trust_subtitle', 'about_trust_subtitle', 'Trust Section Subtitle', 'textarea'),
        'Trust is earned through honest work, consistent results and transparent expectations. At CraftDigitally, here\'s how we build it.'
      ),
      craftdigitally_acf_repeater_field(
        'about_trust_cards',
        'about_trust_cards',
        'Trust Cards',
        array(
          craftdigitally_acf_image_field('about_trust_icon', 'icon', 'Icon'),
          craftdigitally_acf_text_field('about_trust_icon_alt', 'icon_alt', 'Icon Alt (optional)', 'text'),
          craftdigitally_acf_text_field('about_trust_icon_w', 'icon_w', 'Icon Width (px)', 'text'),
          craftdigitally_acf_text_field('about_trust_icon_h', 'icon_h', 'Icon Height (px)', 'text'),
          craftdigitally_acf_text_field('about_trust_card_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('about_trust_card_desc', 'desc', 'Description', 'text'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('about_tab_results', 'Results', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_results_title', 'about_results_title', 'Results Section Title', 'text'),
        'Results Our Clients Have Achieved'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_results_subtitle', 'about_results_subtitle', 'Results Section Subtitle (HTML allowed for <br />)', 'textarea'),
        "Our SEO strategies are built to compound over time – they drive measurable business outcomes.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success."
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_number_field('about_results_count', 'about_results_count', 'Results Grid Count'),
        6
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_results_read_more_label', 'about_results_read_more_label', 'Results Card Button Label', 'text'),
        'Read Full Story'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_results_view_all_label', 'about_results_view_all_label', 'View All Button Label', 'text'),
        'View all Case Studies'
      ),

      craftdigitally_acf_tab_field('about_tab_values', 'Values', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_values_title', 'about_values_title', 'Values Title', 'text'),
        'Our Values'
      ),
      craftdigitally_acf_repeater_field(
        'about_values_items',
        'about_values_items',
        'Values Items',
        array(
          craftdigitally_acf_text_field('about_value_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('about_value_desc', 'desc', 'Description', 'text'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('about_tab_choose', 'Why Choose', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_choose_title', 'about_choose_title', 'Why Choose Title', 'text'),
        'Why choose CraftDigitally'
      ),
      craftdigitally_acf_repeater_field(
        'about_choose_points',
        'about_choose_points',
        'Why Choose Points',
        array(
          craftdigitally_acf_text_field('about_choose_point_text', 'text', 'Point', 'text'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('about_tab_mission', 'Mission', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_mission_title', 'about_mission_title', 'Mission Title', 'text'),
        'Our mission'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('about_mission_desc', 'about_mission_desc', 'Mission Description', 'textarea'),
        'CraftDigitally\'s mission is to help businesses grow with clarity-driven marketing, measurable strategy, and consistent execution. We aim to build a digital presence that is easy to understand, trusted by audiences, and strong enough to perform over time.'
      ),
    ),
    'location' => craftdigitally_acf_location_template_or_pages(
      'page-templates/page-about.php',
      array('about', 'about-us', 'about-us-page', 'aboutus')
    ),
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
  ));

  /**
   * BLOG POST TYPE (default WordPress `post` post type)
   */
  acf_add_local_field_group(array(
    'key' => craftdigitally_acf_group_key('blog_post_type'),
    'title' => 'CraftDigitally: Blog Post Content',
    'fields' => array(
      craftdigitally_acf_message_field(
        'blog_post_note',
        'How this works',
        '<strong>These fields control the Blog list + Blog detail layout for this post.</strong> If you leave fields empty, the theme will use the existing hardcoded design as fallback.'
      ),

      craftdigitally_acf_tab_field('blog_post_tab_meta', 'Meta', 'top'),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_title', 'blog_title', 'Title (optional override)', 'text'), 100),
      craftdigitally_acf_wrap(craftdigitally_acf_image_field('blog_featured_image', 'blog_featured_image', 'Blog Image (used in list + detail)'), 50),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_featured_image_alt', 'blog_featured_image_alt', 'Blog Image Alt', 'text'), 50),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_category', 'blog_category', 'Category', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_date', 'blog_date', 'Date (text)', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_read_time', 'blog_read_time', 'Read Time', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_author', 'blog_author', 'Author (e.g. By Admin)', 'text'), 25),

      craftdigitally_acf_tab_field('blog_post_tab_toc', 'TOC', 'top'),
      craftdigitally_acf_repeater_field(
        'blog_toc',
        'blog_toc',
        'Table of Contents Items',
        array(
          craftdigitally_acf_text_field('blog_toc_item', 'text', 'Item', 'text'),
        ),
        0
      ),

      craftdigitally_acf_tab_field('blog_post_tab_intro', 'Intro', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_intro', 'blog_intro', 'Introduction Text', 'textarea'),
        "If you run a local business in 2025, competition isn't what it used to be. Customers now rely on Google to decide where to shop, which doctor to trust, or which service provider to call. They compare reviews, check hours, and look for the highest-rated businesses — all within seconds."
      ),

      craftdigitally_acf_tab_field('blog_post_tab_s1', 'Section 1', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_local_seo_title', 'blog_local_seo_title', 'Title', 'text'),
        'What Is Local SEO?'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_local_seo_html', 'blog_local_seo_html', 'Content', 'wysiwyg'),
        '<p class="bdp-text"><span class="bdp-text-wrapper-6">Local SEO (Local Search Engine Optimization) is the process of improving your online presence so your business appears in </span><span class="bdp-text-wrapper-7">local Google searches,</span><span class="bdp-text-wrapper-6"> Google Maps, and the Local 3-Pack.</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">When someone searches for &quot;salon near me,&quot; &quot;best bakery in Ahmedabad,&quot; or &quot;AC repair nearby,&quot; Google shows a list of top local businesses based on relevance, reviews, and proximity. Local SEO ensures </span><span class="bdp-text-wrapper-7">your business shows up in these results</span><span class="bdp-text-wrapper-6">, giving you maximum visibility at the right time.</span></p>'
      ),

      craftdigitally_acf_tab_field('blog_post_tab_s2', 'Section 2', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_essential_title', 'blog_essential_title', 'Title', 'text'),
        'Why Local SEO Is Essential in 2025'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_essential_intro', 'blog_essential_intro', 'Intro', 'textarea'),
        "Search behaviour continues to shift, and local businesses must keep up. Here's why Local SEO matters more than ever:"
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_essential_html', 'blog_essential_html', 'Content', 'wysiwyg'),
        '<div class="bdp-flexcontainer-3"><p class="bdp-text"><span class="bdp-text-wrapper-7">People Trust Search Engines Over Traditional Advertising</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Customers rely on online results, reviews, and ratings to choose businesses. If you&#039;re not visible, you&#039;re losing opportunities.</span></p></div><div class="bdp-flexcontainer-4"><p class="bdp-text"><span class="bdp-text-wrapper-7">&quot;Near Me&quot; Searches Are Growing Rapidly</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">More people use mobile searches like:</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">&quot;best dentist near me&quot;</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">&quot;urgent care open now&quot;</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">&quot;cafe near me with wifi&quot;</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Google prioritises local results that are optimized.</span></p></div><div class="bdp-flexcontainer-3"><p class="bdp-text"><span class="bdp-text-wrapper-7">Google&#039;s Local Algorithms Are Smarter</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Google uses AI to understand user intent better, showing hyper-relevant businesses. Only optimized businesses rank well.</span></p></div><div class="bdp-flexcontainer-3"><p class="bdp-text"><span class="bdp-text-wrapper-7">Competition Is Increasing</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">More businesses are investing in online visibility — if you aren&#039;t, your competitors will dominate the results.</span></p></div>'
      ),

      craftdigitally_acf_tab_field('blog_post_tab_s3', 'Section 3', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_benefits_title', 'blog_benefits_title', 'Title', 'text'),
        'Key Benefits of Local SEO'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_benefits_html', 'blog_benefits_html', 'Content', 'wysiwyg'),
        '<div class="bdp-flexcontainer-3"><p class="bdp-text"><span class="bdp-text-wrapper-7">1. Increased Local Visibility</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Appear in Google&#039;s Local Pack and Maps results, where most local clicks happen.</span></p></div><div class="bdp-flexcontainer-5"><p class="bdp-text"><span class="bdp-text-wrapper-7">2. More Qualified Leads</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">People searching locally are ready to take action — call, visit, or book.</span></p></div><div class="bdp-flexcontainer-3"><p class="bdp-text"><span class="bdp-text-wrapper-7">3. Stronger Trust &amp; Reputation</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Consistent reviews, accurate information, and updated profiles make you look more reliable.</span></p></div><div class="bdp-flexcontainer-5"><p class="bdp-text"><span class="bdp-text-wrapper-7">4. Boost in Store Visits &amp; Calls</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Local search directly influences in-person visits and enquiries.</span></p></div><div class="bdp-flexcontainer-5"><p class="bdp-text"><span class="bdp-text-wrapper-7">5. Cost-Effective Long-Term Growth</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Unlike paid ads, Local SEO delivers results for months and years.</span></p></div>'
      ),

      craftdigitally_acf_tab_field('blog_post_tab_s4', 'Section 4', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_factors_title', 'blog_factors_title', 'Title', 'text'),
        'Important Local SEO Ranking Factors'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_factors_intro', 'blog_factors_intro', 'Intro', 'textarea'),
        'Google considers several factors before ranking a local business:'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_factors_html', 'blog_factors_html', 'Content', 'wysiwyg'),
        '<div class="bdp-flexcontainer-5"><p class="bdp-text"><span class="bdp-text-wrapper-7">Google Business Profile Optimization</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Accurate information, photos, updates, and category selection matter.</span></p></div><div class="bdp-flexcontainer-5"><p class="bdp-text"><span class="bdp-text-wrapper-7">NAP Consistency (Name, Address, Phone)</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Your business information must match across all platforms.</span></p></div><div class="bdp-flexcontainer-5"><p class="bdp-text"><span class="bdp-text-wrapper-7">Reviews &amp; Ratings</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">High ratings and recent reviews help you rank higher and convert better.</span></p></div><div class="bdp-flexcontainer-5"><p class="bdp-text"><span class="bdp-text-wrapper-7">Local Citations</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Listings on trusted directories increase authority.</span></p></div><div class="bdp-flexcontainer-3"><p class="bdp-text"><span class="bdp-text-wrapper-7">On-Page Local SEO</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Optimized pages with local keywords help Google understand your relevance.</span></p></div><div class="bdp-flexcontainer-5"><p class="bdp-text"><span class="bdp-text-wrapper-7">Backlinks from Local Websites</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Local mentions build trust and ranking power.</span></p></div>'
      ),

      craftdigitally_acf_tab_field('blog_post_tab_s5', 'Section 5', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_improve_title', 'blog_improve_title', 'Title', 'text'),
        'How to Improve Your Local SEO'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_improve_html', 'blog_improve_html', 'Content', 'wysiwyg'),
        '<div class="bdp-flexcontainer-5"><p class="bdp-text"><span class="bdp-text-wrapper-7">1. Optimize Your Google Business Profile</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Add photos, services, categories, posts, and keep it updated regularly.</span></p></div><div class="bdp-flexcontainer-6"><p class="bdp-text"><span class="bdp-text-wrapper-7">2. Use Local Keywords</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Examples:</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">&quot;best coaching classes in Surat&quot;</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">&quot;plumber in South Delhi&quot;</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">&quot;wedding photographer in Chandigarh&quot;</span></p></div><div class="bdp-flexcontainer-3"><p class="bdp-text"><span class="bdp-text-wrapper-7">3. Ask for Reviews Regularly</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Happy customers are the best marketing asset. Respond to each review professionally.</span></p></div><div class="bdp-flexcontainer-7"><p class="bdp-text"><span class="bdp-text-wrapper-7">4. Build Local Citations</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Get listed on:</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">JustDial</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Sulekha</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">IndiaMart</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Niche directories</span></p></div><div class="bdp-flexcontainer-5"><p class="bdp-text"><span class="bdp-text-wrapper-7">5. Improve Your Website&#039;s On-Page SEO</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Local schema, internal links, meta tags, and fast loading speeds matter.</span></p></div><div class="bdp-flexcontainer-5"><p class="bdp-text"><span class="bdp-text-wrapper-7">6. Publish Local-Focused Content</span></p><p class="bdp-text"><span class="bdp-text-wrapper-6">Create blogs and guides that answer your customers&#039; questions.</span></p></div>'
      ),

      craftdigitally_acf_tab_field('blog_post_tab_testimonial', 'Testimonial', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_testimonial_quote', 'blog_testimonial_quote', 'Testimonial Quote', 'textarea'),
        "Before working with CraftDigitally, SEO felt like a fog I couldn't navigate. Their process made everything so clear and actionable. I left with not only a visible online presence but a genuine sense of confidence in how I show up digitally"
      ),

      craftdigitally_acf_tab_field('blog_post_tab_final', 'Final', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_final_title', 'blog_final_title', 'Final Thoughts Title', 'text'),
        'Final Thoughts'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('blog_final_html', 'blog_final_html', 'Final Thoughts Content', 'wysiwyg'),
        '<p class="bdp-p"><span class="bdp-span">Local SEO is no longer optional — it&#039;s the foundation of online visibility for small businesses. With more people relying on Google to find services nearby, staying optimized gives you a competitive edge.</span></p><p class="bdp-p"><span class="bdp-span">If you want consistent leads, stronger visibility, and long-term growth, Local SEO should be a top priority for your business in 2025.</span></p>'
      ),

      craftdigitally_acf_tab_field('blog_post_tab_cta', 'CTA + Author', 'top'),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_cta_title', 'blog_cta_title', 'CTA Title', 'text'), 50),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_cta_button_label', 'blog_cta_button_label', 'CTA Button Label', 'text'), 50),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_cta_text', 'blog_cta_text', 'CTA Text', 'textarea'), 100),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_author_tag', 'blog_author_tag', 'Author Tag', 'text'), 33),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_author_name', 'blog_author_name', 'Author Name', 'text'), 33),
      craftdigitally_acf_wrap(craftdigitally_acf_image_field('blog_author_social_image', 'blog_author_social_image', 'Author Social Image'), 34),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('blog_author_bio', 'blog_author_bio', 'Author Bio', 'textarea'), 100),
    ),
    'location' => craftdigitally_acf_location_post_type('post', array('blog')),
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    // Disable this group so default WordPress post editor is used without ACF tabs.
    'active' => false,
  ));

  /**
   * CASE STUDY POST TYPE (`case_study` custom post type)
   * - Tabbed, side-by-side UI (similar to Blog Post tabs)
   */
  acf_add_local_field_group(array(
    'key' => craftdigitally_acf_group_key('case_study_post_type'),
    'title' => 'CraftDigitally: Case Study Content',
    'fields' => array(
      craftdigitally_acf_message_field(
        'cs_note',
        'How this works',
        '<strong>These fields control the Case Study detail + listings layout for this case study.</strong> If you leave fields empty, the theme will fall back to the default design/content.'
      ),

      // HERO TAB
      craftdigitally_acf_tab_field('cs_tab_hero', 'Hero', 'top'),
      craftdigitally_acf_wrap(craftdigitally_acf_image_field('cs_hero_logo', 'cs_hero_logo', 'Hero Logo'), 40),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('cs_hero_title', 'cs_hero_title', 'Hero Title (HTML allowed)', 'textarea'), 60),
      craftdigitally_acf_repeater_field(
        'cs_metrics',
        'cs_metrics',
        'Hero Metrics',
        array(
          craftdigitally_acf_text_field('cs_metric_value', 'value', 'Value', 'text'),
          craftdigitally_acf_text_field('cs_metric_label', 'label', 'Label', 'text'),
        ),
        0,
        10
      ),
      craftdigitally_acf_text_field('cs_hero_button_label', 'cs_hero_button_label', 'Hero Button Label', 'text'),

      // OVERVIEW + META TAB
      craftdigitally_acf_tab_field('cs_tab_overview', 'Overview & Meta', 'top'),
      craftdigitally_acf_text_field('cs_overview', 'cs_overview', 'Overview', 'textarea'),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('cs_industry', 'cs_industry', 'Industry', 'text'), 33),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('cs_company_name', 'cs_company_name', 'Company Name', 'text'), 33),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('cs_services', 'cs_services', 'Services', 'text'), 34),

      // PROBLEM TAB
      craftdigitally_acf_tab_field('cs_tab_problem', 'Problem', 'top'),
      craftdigitally_acf_text_field('cs_problem_title', 'cs_problem_title', 'Problem Title', 'text'),
      craftdigitally_acf_repeater_field(
        'cs_problem_paras',
        'cs_problem_paras',
        'Problem Paragraphs',
        array(
          craftdigitally_acf_text_field('cs_problem_text', 'text', 'Paragraph Text', 'textarea'),
        ),
        0
      ),

      // SOLUTION TAB
      craftdigitally_acf_tab_field('cs_tab_solution', 'Solution', 'top'),
      craftdigitally_acf_text_field('cs_solution_title', 'cs_solution_title', 'Solution Title', 'text'),
      craftdigitally_acf_text_field('cs_solution_intro', 'cs_solution_intro', 'Solution Intro', 'textarea'),
      craftdigitally_acf_text_field('cs_solution_lead', 'cs_solution_lead', 'Solution Lead Text', 'text'),
      craftdigitally_acf_text_field('cs_solution_html', 'cs_solution_html', 'Solution Content (HTML)', 'wysiwyg'),

      // IMAGE TAB
      craftdigitally_acf_tab_field('cs_tab_image', 'Image', 'top'),
      craftdigitally_acf_wrap(craftdigitally_acf_image_field('cs_image', 'cs_image', 'Case Study Image'), 60),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('cs_image_caption', 'cs_image_caption', 'Image Caption', 'text'), 40),

      // TESTIMONIAL TAB
      craftdigitally_acf_tab_field('cs_tab_testimonial', 'Testimonial', 'top'),
      craftdigitally_acf_text_field('cs_testimonial_quote', 'cs_testimonial_quote', 'Testimonial Quote', 'textarea'),
      craftdigitally_acf_wrap(craftdigitally_acf_image_field('cs_testimonial_avatar', 'cs_testimonial_avatar', 'Testimonial Avatar'), 40),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('cs_testimonial_name', 'cs_testimonial_name', 'Testimonial Name', 'text'), 30),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('cs_testimonial_role', 'cs_testimonial_role', 'Testimonial Role', 'text'), 30),

      // OUTCOME TAB
      craftdigitally_acf_tab_field('cs_tab_outcome', 'Outcome', 'top'),
      craftdigitally_acf_text_field('cs_outcome_title', 'cs_outcome_title', 'Outcome Title', 'text'),
      craftdigitally_acf_text_field('cs_outcome_text', 'cs_outcome_text', 'Outcome Text', 'textarea'),
      craftdigitally_acf_text_field('cs_outcome_html', 'cs_outcome_html', 'Outcome Content (HTML)', 'wysiwyg'),

      // CTA TAB
      craftdigitally_acf_tab_field('cs_tab_cta', 'CTA', 'top'),
      craftdigitally_acf_text_field('cs_cta_title', 'cs_cta_title', 'CTA Title', 'text'),
      craftdigitally_acf_text_field('cs_cta_text', 'cs_cta_text', 'CTA Text', 'textarea'),
      craftdigitally_acf_text_field('cs_cta_button_label', 'cs_cta_button_label', 'CTA Button Label', 'text'),

      // RELATED TAB
      craftdigitally_acf_tab_field('cs_tab_related', 'Related', 'top'),
      craftdigitally_acf_text_field('cs_related_title', 'cs_related_title', 'Related Case Studies Title', 'text'),
      craftdigitally_acf_text_field('cs_related_subtitle', 'cs_related_subtitle', 'Related Case Studies Subtitle (HTML allowed)', 'textarea'),
      craftdigitally_acf_number_field('cs_related_count', 'cs_related_count', 'Related Case Studies Count'),
      craftdigitally_acf_text_field('cs_related_read_more_label', 'cs_related_read_more_label', 'Related Read More Label', 'text'),

      // FORM TAB
      craftdigitally_acf_tab_field('cs_tab_form', 'Form', 'top'),
      craftdigitally_acf_text_field('cs_form_title', 'cs_form_title', 'Form Title', 'text'),
      craftdigitally_acf_text_field('cs_form_subtitle', 'cs_form_subtitle', 'Form Subtitle', 'textarea'),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('cs_form_name_placeholder', 'cs_form_name_placeholder', 'Form: Name Placeholder', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('cs_form_phone_placeholder', 'cs_form_phone_placeholder', 'Form: Phone Placeholder', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('cs_form_email_placeholder', 'cs_form_email_placeholder', 'Form: Email Placeholder', 'text'), 25),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('cs_form_service_placeholder', 'cs_form_service_placeholder', 'Form: Service Placeholder', 'text'), 25),
      craftdigitally_acf_text_field('cs_form_message_placeholder', 'cs_form_message_placeholder', 'Form: Message Placeholder', 'text'),
      craftdigitally_acf_text_field('cs_form_submit_label', 'cs_form_submit_label', 'Form: Submit Label', 'text'),
    ),
    'location' => craftdigitally_acf_location_post_type('case_study', array('case-studies', 'case-study')),
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
  ));

  /**
   * SERVICE POST TYPE (`service` custom post type)
   * - Tabbed, side-by-side UI (similar to Case Study tabs)
   * - Used for service landing page grid and service detail page
   */
  acf_add_local_field_group(array(
    'key' => craftdigitally_acf_group_key('service_post_type'),
    'title' => 'CraftDigitally: Service Content',
    'fields' => array(
      craftdigitally_acf_message_field(
        'service_post_note',
        'How this works',
        '<strong>These fields control the Service landing grid + Service detail layout for this service.</strong> If you leave fields empty, the theme will fall back to the default design/content.'
      ),

      // GRID/LISTING TAB (for service landing page)
      craftdigitally_acf_tab_field('service_tab_listing', 'Listing (Grid)', 'top'),
      craftdigitally_acf_wrap(craftdigitally_acf_image_field('service_icon', 'service_icon', 'Service Icon (for grid)'), 50),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('service_icon_alt', 'service_icon_alt', 'Icon Alt Text', 'text'), 50),
      craftdigitally_acf_wrap(craftdigitally_acf_text_field('service_short_desc', 'service_short_desc', 'Short Description (for grid)', 'textarea'), 100),
      craftdigitally_acf_message_field(
        'service_listing_note',
        'Note',
        'These fields are used when this service appears in the Services Grid on the Service Landing page.'
      ),

      // HERO TAB
      craftdigitally_acf_tab_field('service_tab_hero', 'Hero', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_hero_headline', 'service_hero_headline', 'Hero Headline', 'text'),
        'Local SEO That Drives Real Customers to Your Business'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_hero_subhead', 'service_hero_subhead', 'Hero Subhead', 'textarea'),
        'Get more calls, more leads, and more foot traffic from the people already searching for what you offer in your city.'
      ),

      // PROBLEM TAB
      craftdigitally_acf_tab_field('service_tab_problem', 'Problem', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_problem_headline', 'service_problem_headline', 'Problem Headline', 'text'),
        'Is Your Business Invisible to Nearby Customers?'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_problem_text', 'service_problem_text', 'Problem Text', 'textarea'),
        '46% of all Google searches have "local intent" (people looking for services near them). If your business doesn\'t appear in the top 3 spots of the "Google Map Pack," you typically don\'t exist to those customers.

At CraftDigitally, we don\'t just "do SEO." We engineer your online presence to ensure that when local customers search for your services, you are the first brand they see and the first one they call.'
      ),
      craftdigitally_acf_image_field('service_problem_image', 'service_problem_image', 'Problem Image'),
      craftdigitally_acf_repeater_field(
        'service_key_highlights',
        'service_key_highlights',
        'Key Highlights',
        array(
          craftdigitally_acf_text_field('service_highlight_bold', 'bold', 'Bold Text', 'text'),
          craftdigitally_acf_text_field('service_highlight_text', 'text', 'Text', 'text'),
        ),
        0
      ),

      // BENEFITS TAB
      craftdigitally_acf_tab_field('service_tab_benefits', 'Benefits', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_benefits_title', 'service_benefits_title', 'Benefits Title', 'text'),
        'Key Benefits of Local SEO for Your Business'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_benefits_subtitle', 'service_benefits_subtitle', 'Benefits Subtitle', 'textarea'),
        'From higher visibility to more leads, see how local SEO helps you get found and chosen.'
      ),
      craftdigitally_acf_repeater_field(
        'service_benefits_cards',
        'service_benefits_cards',
        'Benefits Cards',
        array(
          craftdigitally_acf_image_field('service_benefit_icon', 'icon', 'Icon'),
          craftdigitally_acf_text_field('service_benefit_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('service_benefit_desc', 'desc', 'Description', 'textarea'),
        ),
        0
      ),

      // MID CTA TAB
      craftdigitally_acf_tab_field('service_tab_mid_cta', 'Mid CTA', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_mid_cta_title', 'service_mid_cta_title', 'Mid CTA Title', 'text'),
        'Ready to Dominate Your Competition?'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_mid_cta_text', 'service_mid_cta_text', 'Mid CTA Text', 'textarea'),
        'Get a clear local SEO strategy and start turning nearby searches into real customers.'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_mid_cta_button_label', 'service_mid_cta_button_label', 'Mid CTA Button Label', 'text'),
        'Book a Free Consult'
      ),

      // RESULTS TAB
      craftdigitally_acf_tab_field('service_tab_results', 'Results', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_results_title', 'service_results_title', 'Results Title', 'text'),
        'Results Our Clients Have Achieved'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_results_subtitle', 'service_results_subtitle', 'Results Subtitle', 'textarea'),
        'From higher visibility to more traffic and leads, see how our clients turned local search into success.'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_number_field('service_results_count', 'service_results_count', 'Results Count'),
        6
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_results_read_more_label', 'service_results_read_more_label', 'Results Read More Label', 'text'),
        'Read Full Story'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_results_view_all_label', 'service_results_view_all_label', 'Results View All Label', 'text'),
        'View All Case Studies'
      ),

      // SERVICES TAB
      craftdigitally_acf_tab_field('service_tab_services', 'Detailed Services', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_services_title', 'service_services_title', 'Services Title', 'text'),
        'Local SEO Services That Help You Get Found — and Chosen'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_services_subtitle', 'service_services_subtitle', 'Services Subtitle', 'textarea'),
        'We combine strategy, optimization, and ongoing support so your business becomes the obvious choice locally.'
      ),
      craftdigitally_acf_repeater_field(
        'service_services_items',
        'service_services_items',
        'Services Items',
        array(
          craftdigitally_acf_text_field('service_item_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('service_item_desc', 'desc', 'Description', 'textarea'),
        ),
        0
      ),

      // PROCESS TAB
      craftdigitally_acf_tab_field('service_tab_process', 'Process', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_process_title', 'service_process_title', 'Process Title', 'text'),
        'Our Proven Process'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_process_subtitle', 'service_process_subtitle', 'Process Subtitle', 'textarea'),
        'From audit to optimization, we follow a clear, repeatable process that delivers results.'
      ),
      craftdigitally_acf_repeater_field(
        'service_process_steps',
        'service_process_steps',
        'Process Steps',
        array(
          craftdigitally_acf_text_field('service_step_number', 'number', 'Step Number', 'text'),
          craftdigitally_acf_text_field('service_step_title', 'title', 'Title', 'text'),
          craftdigitally_acf_text_field('service_step_desc', 'description', 'Description', 'textarea'),
        ),
        0
      ),

      // FAQ TAB
      craftdigitally_acf_tab_field('service_tab_faq', 'FAQs', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_faq_title', 'service_faq_title', 'FAQ Title', 'text'),
        'FAQs'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_faq_subtitle', 'service_faq_subtitle', 'FAQ Subtitle', 'textarea'),
        'Find answers to common questions about local SEO and how we work.'
      ),
      craftdigitally_acf_repeater_field(
        'service_faq_items',
        'service_faq_items',
        'FAQ Items',
        array(
          craftdigitally_acf_text_field('service_faq_question', 'question', 'Question', 'text'),
          craftdigitally_acf_text_field('service_faq_answer', 'answer', 'Answer', 'textarea'),
        ),
        0
      ),

      // CTA TAB
      craftdigitally_acf_tab_field('service_tab_cta', 'CTA', 'top'),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_title', 'service_cta_title', 'CTA Title', 'text'),
        'Ready to Dominate Your Competition?'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_subtitle', 'service_cta_subtitle', 'CTA Subtitle', 'textarea'),
        'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad.'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_name_placeholder', 'service_cta_name_placeholder', 'CTA Form: Name Placeholder', 'text'),
        'Name'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_phone_placeholder', 'service_cta_phone_placeholder', 'CTA Form: Phone Placeholder', 'text'),
        'Phone'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_email_placeholder', 'service_cta_email_placeholder', 'CTA Form: Email Placeholder', 'text'),
        'Email'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_service_placeholder', 'service_cta_service_placeholder', 'CTA Form: Service Placeholder', 'text'),
        'Service'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_message_placeholder', 'service_cta_message_placeholder', 'CTA Form: Message Placeholder', 'text'),
        'Message'
      ),
      craftdigitally_acf_default(
        craftdigitally_acf_text_field('service_cta_submit_label', 'service_cta_submit_label', 'CTA Form: Submit Label', 'text'),
        "Let's Connect"
      ),
    ),
    'location' => craftdigitally_acf_location_post_type('service', array('services', 'service')),
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
  ));
});

