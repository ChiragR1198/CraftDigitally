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
 * Render Case Study Grid
 * Reusable function to render case study cards
 *
 * @param int    $limit        Number of case studies to display (0 for all).
 * @param string $template_type Template type: 'standard' or 'detail' (for related case studies).
 * @param string $button_text  Custom button text.
 */
function craftdigitally_render_case_study_grid($limit = 0, $template_type = 'standard', $button_text = 'Read Full Story') {
  $case_studies = craftdigitally_get_case_studies($limit);

  if (empty($case_studies)) {
    return;
  }

  if ($template_type === 'detail') {
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

