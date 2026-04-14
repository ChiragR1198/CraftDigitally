<?php
/**
 * Case studies functions
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Get Case Studies Data
 * Reusable function to get case studies from Customizer
 *
 * @param int $limit Number of case studies to return (0 for all).
 * @return array Array of case studies
 */
function craftdigitally_get_case_studies($limit = 0) {
  require_once get_template_directory() . '/inc/default-data.php';
  
  $case_studies_json = get_theme_mod('craftdigitally_case_studies_data', '');
  $case_studies = array();

  if (!empty($case_studies_json)) {
    $decoded = json_decode($case_studies_json, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
      $case_studies = $decoded;
    }
  }

  // Fallback to default if empty
  if (empty($case_studies)) {
    $case_studies = craftdigitally_get_default_case_studies();
  }

  // Limit the number of items if specified
  if ($limit > 0 && count($case_studies) > $limit) {
    $case_studies = array_slice($case_studies, 0, $limit);
  }

  return $case_studies;
}

/**
 * Get the Case Study Landing page ID.
 *
 * @return int|false
 */
function craftdigitally_get_case_study_landing_page_id() {
  static $landing_page_id = null;

  if ($landing_page_id !== null) {
    return $landing_page_id;
  }

  $landing_pages = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'page-templates/page-case-study-landing.php',
    'number' => 1,
    'post_status' => 'publish',
  ));

  $landing_page_id = !empty($landing_pages) ? (int) $landing_pages[0]->ID : false;

  return $landing_page_id;
}

/**
 * Shared copy/settings for the case study results block.
 * Source of truth: the Case Study Landing page ACF fields.
 *
 * @return array
 */
function craftdigitally_get_shared_case_study_results_data() {
  $source_id = craftdigitally_get_case_study_landing_page_id();
  $view_all_url_default = $source_id ? get_permalink($source_id) : home_url('/case-studies/');

  return array(
    'title' => craftdigitally_get_acf('cs_landing_results_title', 'Results Our Clients Have Achieved', $source_id ?: false),
    'subtitle' => craftdigitally_get_acf('cs_landing_results_subtitle', "Our SEO strategies go beyond rankings — they deliver measurable business growth.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success.", $source_id ?: false),
    'read_more_label' => craftdigitally_get_acf('cs_landing_results_read_more_label', 'Read Full Story', $source_id ?: false),
    'view_all_label' => craftdigitally_get_acf('cs_landing_view_all_label', 'View all Case Studies', $source_id ?: false),
    'view_all_url' => craftdigitally_get_acf('cs_landing_view_all_url', $view_all_url_default, $source_id ?: false),
  );
}

/**
 * Get live case study posts for shared result sections.
 *
 * @param int   $limit       Number of case studies to return (0 for all).
 * @param array $exclude_ids Post IDs to exclude.
 * @return WP_Post[]
 */
function craftdigitally_get_case_study_posts($limit = 0, $exclude_ids = array()) {
  $posts_status = current_user_can('edit_posts')
    ? array('publish', 'draft', 'pending', 'future', 'private')
    : array('publish');

  $query = new WP_Query(array(
    'post_type' => 'case_study',
    'post_status' => $posts_status,
    'posts_per_page' => $limit > 0 ? $limit : -1,
    'post__not_in' => array_filter(array_map('absint', (array) $exclude_ids)),
    'ignore_sticky_posts' => true,
  ));

  return $query->have_posts() ? $query->posts : array();
}

/**
 * Render a single standard case study card from a post.
 *
 * @param int    $post_id     Case study post ID.
 * @param string $button_text Card CTA label.
 * @return void
 */
function craftdigitally_render_case_study_post_card($post_id, $button_text = 'Read Full Story') {
  $post_id = absint($post_id);
  if ($post_id <= 0) {
    return;
  }

  $cs_title = get_the_title($post_id);
  $cs_overview = craftdigitally_get_acf('cs_overview', get_the_excerpt($post_id), $post_id);
  $cs_company = craftdigitally_get_acf('cs_company_name', $cs_title, $post_id);
  $cs_category = craftdigitally_get_acf('cs_industry', $cs_company, $post_id);

  $thumb_url = get_the_post_thumbnail_url($post_id, 'medium');
  $cs_logo = craftdigitally_get_acf_image_url(
    'cs_hero_logo',
    $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/testeracademy.png'),
    $post_id
  );

  if (empty($cs_logo) || $cs_logo === (get_template_directory_uri() . '/assets/images/testeracademy.png')) {
    $cs_logo = $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/testeracademy.png');
  }

  $detail_url = function_exists('craftdigitally_get_case_study_detail_url')
    ? craftdigitally_get_case_study_detail_url($post_id)
    : get_permalink($post_id);
  $cs_status = get_post_status($post_id);
  if ($cs_status && $cs_status !== 'publish') {
    $detail_url = current_user_can('edit_post', $post_id) ? get_preview_post_link($post_id) : '#';
  }
  ?>
  <div class="case-study-card">
    <div class="case-study-image-wrapper">
      <img src="<?php echo esc_url($cs_logo); ?>" alt="<?php echo esc_attr($cs_company); ?> logo" class="case-study-logo" />
    </div>
    <div class="case-study-meta">
      <span class="case-study-category"><?php echo esc_html($cs_category); ?></span>
      <hr class="case-study-divider" />
    </div>
    <p class="case-study-description"><?php echo esc_html($cs_overview); ?></p>
    <a href="<?php echo esc_url($detail_url); ?>" class="btn btn-outline case-study-cta"><?php echo esc_html($button_text); ?></a>
  </div>
  <?php
}

/**
 * Render Case Study Grid
 * Reusable function to render case study cards
 *
 * @param int    $limit        Number of case studies to display (0 for all).
 * @param string $template_type Template type: 'standard' or 'detail' (for related case studies).
 * @param string $button_text  Custom button text.
 */
function craftdigitally_render_case_study_grid($limit = 0, $template_type = 'standard', $button_text = 'Read Full Story') {
  if ($template_type === 'detail') {
    $case_studies = craftdigitally_get_case_studies($limit);

    if (empty($case_studies)) {
      return;
    }

    // Related case studies format (for detail page)
    foreach ($case_studies as $index => $study) :
      $class = 'frame-' . ($index === 0 ? '4' : ($index === 1 ? '7' : '8'));
      $has_bg = ($index === 1 || $index === 2);
      $logo = isset($study['logo']) ? $study['logo'] : '';
      $category = isset($study['category']) ? $study['category'] : '';
      $description = isset($study['description']) ? $study['description'] : '';
      ?>
      <div class="<?php echo esc_attr($class); ?>">
        <?php if ($has_bg) : ?>
          <div class="thumbnail">
            <div class="<?php echo $class === 'frame-7' ? 'center-img' : 'urban-stitch-logo-wrapper'; ?>">
              <img class="<?php echo $class === 'frame-7' ? 'dz-logo-purple' : 'urban-stitch-logo-2'; ?>" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/' . rawurlencode($logo)); ?>" alt="<?php echo esc_attr($category); ?>" />
            </div>
          </div>
        <?php else : ?>
          <img class="frame-5" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/' . rawurlencode($logo)); ?>" alt="<?php echo esc_attr($category); ?>" />
        <?php endif; ?>
        <div class="div-5">
          <div class="centre">
            <div class="text-wrapper-15"><?php echo esc_html($category); ?></div>
            <div class="frame-6"></div>
          </div>
          <div class="div-5">
            <p class="text-wrapper-10"><?php echo esc_html($description); ?></p>
          </div>
        </div>
        <?php if ($class === 'frame-8') : ?>
          <button class="button-2"><div class="text-wrapper-16"><?php echo esc_html($button_text); ?></div></button>
        <?php endif; ?>
      </div>
      <?php
    endforeach;
  } else {
    $case_study_posts = craftdigitally_get_case_study_posts($limit);

    if (!empty($case_study_posts)) {
      foreach ($case_study_posts as $case_study_post) {
        craftdigitally_render_case_study_post_card($case_study_post->ID, $button_text);
      }

      return;
    }

    $case_studies = craftdigitally_get_case_studies($limit);

    if (empty($case_studies)) {
      return;
    }

    // Standard format (for landing and index pages)
    foreach ($case_studies as $study) :
      $logo = isset($study['logo']) ? $study['logo'] : '';
      $category = isset($study['category']) ? $study['category'] : '';
      $description = isset($study['description']) ? $study['description'] : '';
      $link = isset($study['link']) ? $study['link'] : '#';
      ?>
      <div class="case-study-card">
        <div class="case-study-image-wrapper">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/' . rawurlencode($logo)); ?>" alt="<?php echo esc_attr($category); ?> logo" class="case-study-logo" />
        </div>
        <div class="case-study-meta">
          <span class="case-study-category"><?php echo esc_html($category); ?></span>
          <hr class="case-study-divider" />
        </div>
        <p class="case-study-description"><?php echo esc_html($description); ?></p>
        <a href="<?php echo esc_url($link); ?>" class="btn btn-outline case-study-cta"><?php echo esc_html($button_text); ?></a>
      </div>
      <?php
    endforeach;
  }
}

