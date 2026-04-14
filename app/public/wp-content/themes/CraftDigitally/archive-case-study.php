<?php
/**
 * Archive Template for Case Studies
 * 
 * Displays case studies archive using the same UI as page-case-study-landing.php
 *
 * @package CraftDigitally
 */

get_header();

// NOTE:
// Case studies should use their native permalinks:
// - Archive: /case-studies/
// - Single:  /case-studies/{post-name}/

// Pull ACF fields from the Case Study Landing PAGE (page using this template),
// so the archive shows the exact same content/UI as `page-case-study-landing.php`.
$cd_cs_landing_pages = get_pages(array(
  'meta_key' => '_wp_page_template',
  'meta_value' => 'page-templates/page-case-study-landing.php',
  'number' => 1,
  'post_status' => 'publish',
));
$cd_cs_landing_id = !empty($cd_cs_landing_pages) ? (int) $cd_cs_landing_pages[0]->ID : 0;

// ACF overrides (same as landing template).
$cd_cs_hero_title = craftdigitally_get_acf('cs_landing_hero_title', 'Driving Growth Through SEO:<br />Client Success Stories', $cd_cs_landing_id ?: false);
$cd_cs_hero_subtitle = craftdigitally_get_acf('cs_landing_hero_subtitle', "Discover how we've helped businesses improve rankings, increase traffic, and turn clicks into customers.<br />Through strategic SEO campaigns, we've guided clients from low visibility to top search positions", $cd_cs_landing_id ?: false);

// Query case_study posts
$cd_cs_posts_status = current_user_can('edit_posts')
  ? array('publish', 'draft', 'pending', 'future', 'private')
  : array('publish');

$cd_cs_posts_q = new WP_Query(array(
  'post_type' => 'case_study',
  'post_status' => $cd_cs_posts_status,
  'posts_per_page' => 7, // 1 featured + 6 grid
  'ignore_sticky_posts' => true,
));

// Featured Case Study - Default ACF values (fallback) (same as landing template).
$cd_cs_featured_logo = craftdigitally_get_acf_image_url('cs_landing_featured_logo', get_template_directory_uri() . '/assets/images/testeracademy.png', $cd_cs_landing_id ?: false);
$cd_cs_featured_title = craftdigitally_get_acf('cs_landing_featured_title', 'Top 3 Rankings for competitive keywords', $cd_cs_landing_id ?: false);
$cd_cs_featured_desc = craftdigitally_get_acf('cs_landing_featured_desc', 'A leading [industry/business type] client was struggling to rank for highly competitive keywords that their target audience searched daily. Despite quality services, they remained invisible beyond page 5 of Google.', $cd_cs_landing_id ?: false);
$cd_cs_featured_btn_label = craftdigitally_get_acf('cs_landing_featured_button_label', 'Read Case Study', $cd_cs_landing_id ?: false);
$cd_cs_featured_btn_url = craftdigitally_get_acf('cs_landing_featured_button_url', '#', $cd_cs_landing_id ?: false);

// Override featured section with first post if available
if ($cd_cs_posts_q->have_posts()) {
  $featured_cs = $cd_cs_posts_q->posts[0];
  $featured_cs_id = (int) $featured_cs->ID;
  
  $cd_cs_featured_title = craftdigitally_get_acf('cs_hero_title', get_the_title($featured_cs_id), $featured_cs_id);
  $cd_cs_featured_desc = craftdigitally_get_acf('cs_overview', get_the_excerpt($featured_cs_id), $featured_cs_id);
  $cd_cs_featured_btn_url = function_exists('craftdigitally_get_case_study_detail_url')
    ? craftdigitally_get_case_study_detail_url($featured_cs_id)
    : get_permalink($featured_cs_id);
  $featured_status = get_post_status($featured_cs_id);
  if ($featured_status && $featured_status !== 'publish') {
    $cd_cs_featured_btn_url = current_user_can('edit_post', $featured_cs_id) ? get_preview_post_link($featured_cs_id) : '#';
  }
  
  $thumb_url = get_the_post_thumbnail_url($featured_cs_id, 'large');
  $cd_cs_featured_logo = craftdigitally_get_acf_image_url(
    'cs_hero_logo',
    $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/testeracademy.png'),
    $featured_cs_id
  );

  // If ACF field is empty, use landing page ACF as fallback (matches landing template behavior).
  if (empty($cd_cs_featured_logo) || $cd_cs_featured_logo === (get_template_directory_uri() . '/assets/images/testeracademy.png')) {
    $cd_cs_featured_logo = craftdigitally_get_acf_image_url('cs_landing_featured_logo', get_template_directory_uri() . '/assets/images/testeracademy.png', $cd_cs_landing_id ?: false);
  }
}

$cd_cs_results_title = craftdigitally_get_acf('cs_landing_results_title', 'Results Our Clients Have Achieved', $cd_cs_landing_id ?: false);
$cd_cs_results_subtitle = craftdigitally_get_acf('cs_landing_results_subtitle', "Our SEO strategies go beyond rankings — they deliver measurable business growth.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success.", $cd_cs_landing_id ?: false);
$cd_cs_results_count = (int) craftdigitally_get_acf('cs_landing_results_count', 6, $cd_cs_landing_id ?: false);
$cd_cs_results_read_more = craftdigitally_get_acf('cs_landing_results_read_more_label', 'Read Full Story', $cd_cs_landing_id ?: false);
$cd_cs_view_all_label = craftdigitally_get_acf('cs_landing_view_all_label', 'View all Case Studies', $cd_cs_landing_id ?: false);
$cd_cs_view_all_url = craftdigitally_get_acf('cs_landing_view_all_url', '#', $cd_cs_landing_id ?: false);
if (empty($cd_cs_view_all_url) || $cd_cs_view_all_url === '#') {
  $cd_cs_view_all_url = get_post_type_archive_link('case_study') ?: home_url('/case-studies/');
}

$cd_cs_test_heading = craftdigitally_get_acf('cs_landing_testimonial_heading', 'What Clients Say About Working With Us', $cd_cs_landing_id ?: false);
$cd_cs_test_quote = craftdigitally_get_acf('cs_landing_testimonial_quote', "Before working with CraftDigitally, SEO felt like a fog I couldn't navigate. Their process made everything so clear and actionable. I left with not only a visible online presence but a genuine sense of confidence", $cd_cs_landing_id ?: false);
$cd_cs_test_avatar = craftdigitally_get_acf_image_url('cs_landing_testimonial_avatar', get_template_directory_uri() . '/assets/images/sarah-lin.png', $cd_cs_landing_id ?: false);
$cd_cs_test_name = craftdigitally_get_acf('cs_landing_testimonial_name', 'Sarah Lin', $cd_cs_landing_id ?: false);
$cd_cs_test_role = craftdigitally_get_acf('cs_landing_testimonial_role', 'Brand Strategist, Lin Studio', $cd_cs_landing_id ?: false);

$cd_cs_cta_title = craftdigitally_get_acf('cs_landing_cta_title', 'Ready to Dominate Your Competition?', $cd_cs_landing_id ?: false);
$cd_cs_cta_subtitle = craftdigitally_get_acf('cs_landing_cta_subtitle', 'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad', $cd_cs_landing_id ?: false);
$cd_cs_cta_name_ph = craftdigitally_get_acf('cs_landing_cta_name_placeholder', 'Name', $cd_cs_landing_id ?: false);
$cd_cs_cta_phone_ph = craftdigitally_get_acf('cs_landing_cta_phone_placeholder', 'Phone', $cd_cs_landing_id ?: false);
$cd_cs_cta_email_ph = craftdigitally_get_acf('cs_landing_cta_email_placeholder', 'Email', $cd_cs_landing_id ?: false);
$cd_cs_cta_service_ph = craftdigitally_get_acf('cs_landing_cta_service_placeholder', 'Service', $cd_cs_landing_id ?: false);
$cd_cs_cta_message_ph = craftdigitally_get_acf('cs_landing_cta_message_placeholder', 'Message', $cd_cs_landing_id ?: false);
$cd_cs_cta_submit = craftdigitally_get_acf('cs_landing_cta_submit_label', "Let's Connect", $cd_cs_landing_id ?: false);
?>

<main id="main-content" class="case-study-landing">
  <div class="main">
    <!-- Hero Section -->
    <div class="hero">
      <div class="container">
        <div class="div">
          <p class="text-wrapper"><?php echo wp_kses_post($cd_cs_hero_title); ?></p>
          <p class="discover-how-we-ve"><?php echo wp_kses_post($cd_cs_hero_subtitle); ?></p>
        </div>
      </div>
    </div>

    <!-- Featured Case Study Section -->
    <div class="featured-case-study">
      <div class="container-2">
        <div class="frame">
          <img src="<?php echo esc_url($cd_cs_featured_logo); ?>" alt="Featured Case Study Logo" class="case-study-logo" />
        </div>
        <div class="right">
          <div class="div-2">
            <p class="p"><?php echo esc_html($cd_cs_featured_title); ?></p>
            <p class="text-wrapper-2">
              <?php echo esc_html($cd_cs_featured_desc); ?>
            </p>
          </div>
          <a href="<?php echo esc_url($cd_cs_featured_btn_url); ?>" class="button"><div class="text-wrapper-3"><?php echo esc_html($cd_cs_featured_btn_label); ?></div></a>
        </div>
      </div>
    </div>

    <!-- Case Study Grid Section -->
    <section class="case-study-section">
      <div class="container">
        <div class="case-study-header">
          <h2 class="case-study-title"><?php echo esc_html($cd_cs_results_title); ?></h2>
          <p class="case-study-subtitle">
            <?php echo wp_kses_post($cd_cs_results_subtitle); ?>
          </p>  
        </div>

        <div class="case-study-grid">
          <?php
          // Display dynamic case studies from custom post type
          if ($cd_cs_posts_q->have_posts()) {
            $posts = $cd_cs_posts_q->posts;
            $display_count = 0;
            $max_count = $cd_cs_results_count ?: 6;
            
            // Skip first post (already shown in featured section)
            for ($i = 1; $i < count($posts) && $display_count < $max_count; $i++) {
              $cs_id = (int) $posts[$i]->ID;
              $cs_title = get_the_title($cs_id);
              $cs_overview = craftdigitally_get_acf('cs_overview', get_the_excerpt($cs_id), $cs_id);
              $cs_company = craftdigitally_get_acf('cs_company_name', $cs_title, $cs_id);
              $cs_category = craftdigitally_get_acf('cs_industry', $cs_company, $cs_id);
              
              // Get logo from ACF field (prioritize ACF, then featured image, then default)
              $thumb_url = get_the_post_thumbnail_url($cs_id, 'medium');
              $cs_logo = craftdigitally_get_acf_image_url(
                'cs_hero_logo',
                $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/testeracademy.png'),
                $cs_id
              );
              // Ensure we have a valid image URL
              if (empty($cs_logo) || $cs_logo === (get_template_directory_uri() . '/assets/images/testeracademy.png')) {
                $cs_logo = $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/testeracademy.png');
              }
              
              $detail_url = function_exists('craftdigitally_get_case_study_detail_url')
                ? craftdigitally_get_case_study_detail_url($cs_id)
                : get_permalink($cs_id);
              $cs_status = get_post_status($cs_id);
              if ($cs_status && $cs_status !== 'publish') {
                $detail_url = current_user_can('edit_post', $cs_id) ? get_preview_post_link($cs_id) : '#';
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
                <a href="<?php echo esc_url($detail_url); ?>" class="btn btn-outline case-study-cta"><?php echo esc_html($cd_cs_results_read_more); ?></a>
              </div>
              <?php
              $display_count++;
            }
          }
          
          // Fallback: If no posts or not enough posts, use default static grid
          if (!$cd_cs_posts_q->have_posts() || $display_count < ($cd_cs_results_count ?: 6)) {
            craftdigitally_render_case_study_grid($cd_cs_results_count ?: 6, 'standard', $cd_cs_results_read_more);
          }
          ?>
        </div>

        <div class="case-study-view-all">
          <a href="<?php echo esc_url($cd_cs_view_all_url); ?>" class="btn btn-outline case-study-view-all-btn"><?php echo esc_html($cd_cs_view_all_label); ?></a>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <div class="clients-say">
      <div class="title-copy"><p class="text-wrapper-8"><?php echo esc_html($cd_cs_test_heading); ?></p></div>
      <div class="testimonial-card">
        <button type="button" class="img-2 testimonial-arrow-left" aria-label="Previous testimonial">‹</button>
        <div class="frame-11">
          <p class="text-wrapper-9">
            <?php echo esc_html($cd_cs_test_quote); ?>
          </p>
          <div class="avatar-company">
            <img class="avatar" src="<?php echo esc_url($cd_cs_test_avatar); ?>" alt="<?php echo esc_attr($cd_cs_test_name); ?>" />
            <div class="frame-12">
              <div class="text-wrapper-10"><?php echo esc_html($cd_cs_test_name); ?></div>
              <div class="text-wrapper-11"><?php echo esc_html($cd_cs_test_role); ?></div>
            </div>
          </div>
        </div>
        <button type="button" class="img-2 testimonial-arrow-right" aria-label="Next testimonial">›</button>
      </div>
    </div>

    <!-- CTA Section -->
    <?php craftdigitally_render_shared_cta_section(); ?>
  </div>
</main>

<?php
get_footer();
