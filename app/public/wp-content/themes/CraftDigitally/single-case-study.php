<?php
/**
 * Single Case Study Template
 * 
 * Template for displaying individual case studies
 *
 * @package CraftDigitally
 */

get_header();

$post_id = get_the_ID();

// Back button should go to the native Case Studies archive.
$cd_cs_landing_url = get_post_type_archive_link('case_study');
if (empty($cd_cs_landing_url)) {
  $cd_cs_landing_url = home_url('/case-studies/');
}

// ACF fields with fallbacks
$cd_csd_logo = craftdigitally_get_acf_image_url('cs_hero_logo', get_template_directory_uri() . '/assets/images/urban-stitch-logo 1.png', $post_id);
$cd_csd_title = craftdigitally_get_acf('cs_hero_title', get_the_title(), $post_id);
$cd_csd_metrics_default = array(
  array('value' => '2,117%', 'label' => 'GROWTH IN ORGANIC BLOG SESSIONS'),
  array('value' => '11X', 'label' => 'CONVERSION RATE'),
  array('value' => '39X', 'label' => 'BLOG CONVERSIONS'),
);
$cd_csd_metrics = craftdigitally_get_acf_array('cs_metrics', $cd_csd_metrics_default, $post_id);
$cd_csd_btn_label = craftdigitally_get_acf('cs_hero_button_label', 'Continue Reading', $post_id);

$cd_csd_overview = craftdigitally_get_acf('cs_overview', get_the_excerpt(), $post_id);

$cd_csd_industry = craftdigitally_get_acf('cs_industry', '', $post_id);
$cd_csd_company = craftdigitally_get_acf('cs_company_name', get_the_title(), $post_id);
$cd_csd_services = craftdigitally_get_acf('cs_services', '', $post_id);

$cd_csd_problem_title = craftdigitally_get_acf('cs_problem_title', 'Problem', $post_id);
$cd_csd_problem_paras_default = array();
$cd_csd_problem_paras = craftdigitally_get_acf_array('cs_problem_paras', $cd_csd_problem_paras_default, $post_id);

$cd_csd_solution_title = craftdigitally_get_acf('cs_solution_title', 'Solution', $post_id);
$cd_csd_solution_intro = craftdigitally_get_acf('cs_solution_intro', '', $post_id);
$cd_csd_solution_lead = craftdigitally_get_acf('cs_solution_lead', '', $post_id);
$cd_csd_solution_html = craftdigitally_get_acf('cs_solution_html', '', $post_id);

$cd_csd_image_url = craftdigitally_get_acf_image_url('cs_image', get_template_directory_uri() . '/assets/images/hero.png', $post_id);
$cd_csd_image_caption = craftdigitally_get_acf('cs_image_caption', '', $post_id);

$cd_csd_test_quote = craftdigitally_get_acf('cs_testimonial_quote', '', $post_id);
$cd_csd_test_avatar = craftdigitally_get_acf_image_url('cs_testimonial_avatar', get_template_directory_uri() . '/assets/images/sarah-lin.png', $post_id);
$cd_csd_test_name = craftdigitally_get_acf('cs_testimonial_name', '', $post_id);
$cd_csd_test_role = craftdigitally_get_acf('cs_testimonial_role', '', $post_id);

$cd_csd_outcome_title = craftdigitally_get_acf('cs_outcome_title', 'Outcome', $post_id);
$cd_csd_outcome_text = craftdigitally_get_acf('cs_outcome_text', '', $post_id);
$cd_csd_outcome_html = craftdigitally_get_acf('cs_outcome_html', '', $post_id);

$cd_csd_shared_cta = craftdigitally_get_shared_cta_data();
$cd_csd_cta_title = $cd_csd_shared_cta['title'];
$cd_csd_cta_text = $cd_csd_shared_cta['subtitle'];
$cd_csd_cta_btn = $cd_csd_shared_cta['button_label'];

$cd_csd_related_title = craftdigitally_get_acf('cs_related_title', 'Related Case Studies', $post_id);
$cd_csd_related_subtitle = craftdigitally_get_acf('cs_related_subtitle', "Our SEO strategies go beyond rankings — they deliver measurable business growth.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success.", $post_id);
$cd_csd_related_count = (int) craftdigitally_get_acf('cs_related_count', 3, $post_id);
$cd_csd_related_read_more = craftdigitally_get_acf('cs_related_read_more_label', 'Read Full Story', $post_id);

$cd_csd_form_title = craftdigitally_get_acf('cs_form_title', 'Ready to Dominate Your Competition?', $post_id);
$cd_csd_form_subtitle = craftdigitally_get_acf('cs_form_subtitle', 'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad', $post_id);
$cd_csd_form_name_ph = craftdigitally_get_acf('cs_form_name_placeholder', 'Name', $post_id);
$cd_csd_form_phone_ph = craftdigitally_get_acf('cs_form_phone_placeholder', 'Phone', $post_id);
$cd_csd_form_email_ph = craftdigitally_get_acf('cs_form_email_placeholder', 'Email', $post_id);
$cd_csd_form_service_ph = craftdigitally_get_acf('cs_form_service_placeholder', 'Service', $post_id);
$cd_csd_form_message_ph = craftdigitally_get_acf('cs_form_message_placeholder', 'Message', $post_id);
$cd_csd_form_submit = craftdigitally_get_acf('cs_form_submit_label', "Let's Connect", $post_id);

// If ACF content is empty, use post content
if (empty($cd_csd_overview) && empty($cd_csd_problem_paras) && empty($cd_csd_solution_html)) {
  $cd_csd_overview = apply_filters('the_content', get_the_content());
}
?>

<main id="main-content" class="case-study-detail">
  <div class="main">
    <!-- Hero Section -->
    <div class="hero">
      <div class="container">
        <img class="urban-stitch-logo" src="<?php echo esc_url($cd_csd_logo); ?>" alt="Case Study Logo" />
        <p class="local-SEO-success"><?php echo wp_kses_post($cd_csd_title); ?></p>
        <div class="metrics">
          <?php
            $m0 = isset($cd_csd_metrics[0]) ? $cd_csd_metrics[0] : $cd_csd_metrics_default[0];
            $m1 = isset($cd_csd_metrics[1]) ? $cd_csd_metrics[1] : $cd_csd_metrics_default[1];
            $m2 = isset($cd_csd_metrics[2]) ? $cd_csd_metrics[2] : $cd_csd_metrics_default[2];
          ?>
          <div class="metric">
            <div class="text-wrapper"><?php echo esc_html(isset($m0['value']) ? $m0['value'] : ''); ?></div>
            <p class="div"><?php echo esc_html(isset($m0['label']) ? $m0['label'] : ''); ?></p>
          </div>
          <div class="metric-2">
            <div class="text-wrapper"><?php echo esc_html(isset($m1['value']) ? $m1['value'] : ''); ?></div>
            <div class="div"><?php echo esc_html(isset($m1['label']) ? $m1['label'] : ''); ?></div>
          </div>
          <div class="metric-3">
            <div class="text-wrapper"><?php echo esc_html(isset($m2['value']) ? $m2['value'] : ''); ?></div>
            <div class="div"><?php echo esc_html(isset($m2['label']) ? $m2['label'] : ''); ?></div>
          </div>
        </div>
        <button class="button"><div class="text-wrapper-2"><?php echo esc_html($cd_csd_btn_label); ?></div></button>
      </div>
    </div>

    <!-- Content Section -->
    <div class="content" id="content">
      <div class="container-2 content-section-container">
        <?php if (!empty($cd_csd_overview)): ?>
        <!-- Overview -->
        <div class="overview">
          <p class="p">
            <?php echo esc_html($cd_csd_overview); ?>
          </p>
        </div>

        <!-- Meta Data -->
        <div class="meta-data">
          <div class="div-2">
            <div class="text-wrapper-3">INDUSTRY</div>
            <div class="text-wrapper-4"><?php echo esc_html($cd_csd_industry); ?></div>
          </div>
          <div class="divider"></div>
          <div class="div-2">
            <div class="text-wrapper-3">COMPANY NAME</div>
            <div class="text-wrapper-4"><?php echo esc_html($cd_csd_company); ?></div>
          </div>
          <div class="divider"></div>
          <div class="div-2">
            <div class="text-wrapper-3">SERVICES</div>
            <div class="text-wrapper-4"><?php echo esc_html($cd_csd_services); ?></div>
          </div>
        </div>

        <!-- Problem Section -->
        <div class="div-3">
          <div class="text-wrapper-5"><?php echo esc_html($cd_csd_problem_title); ?></div>
          <div class="flexcontainer1">
            <?php foreach ($cd_csd_problem_paras as $pp): ?>
              <p class="div-4"><span class="span"><?php echo esc_html(isset($pp['text']) ? $pp['text'] : ''); ?></span></p>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Solution Section -->
        <div class="div-3">
          <div class="text-wrapper-5"><?php echo esc_html($cd_csd_solution_title); ?></div>
          <p class="div-4">
            <?php echo esc_html($cd_csd_solution_intro); ?>
          </p>
          <p class="text">
            <span class="text-wrapper-6"><?php echo esc_html($cd_csd_solution_lead); ?></span>
          </p>
          <div class="flexcontainer">
            <?php echo wp_kses_post($cd_csd_solution_html); ?>
          </div>
        </div>

        <!-- Image Section -->
        <div class="image">
          <div class="img" style="background-image: url('<?php echo esc_url($cd_csd_image_url); ?>');"></div>
          <p class="text-wrapper-8"><?php echo esc_html($cd_csd_image_caption); ?></p>
        </div>

        <!-- Testimonial Section -->
        <div class="testimonial-card">
          <p class="text-wrapper-9">
            <?php echo esc_html($cd_csd_test_quote); ?>
          </p>
          <div class="avatar-company">
            <img class="avatar" src="<?php echo esc_url($cd_csd_test_avatar); ?>" alt="<?php echo esc_attr($cd_csd_test_name); ?>" />
            <div class="frame">
              <div class="text-wrapper-10"><?php echo esc_html($cd_csd_test_name); ?></div>
              <div class="text-wrapper-11"><?php echo esc_html($cd_csd_test_role); ?></div>
            </div>
          </div>
        </div>

        <!-- Outcome Section -->
        <div class="outcome">
          <div class="text-wrapper-5"><?php echo esc_html($cd_csd_outcome_title); ?></div>
          <p class="div-4">
            <?php echo esc_html($cd_csd_outcome_text); ?>
          </p>
          <div class="flexcontainer">
            <?php echo wp_kses_post($cd_csd_outcome_html); ?>
          </div>
        </div>

        <!-- CTA Section -->
        <div class="cta">
          <div class="container-2">
            <div class="frame-2">
              <div class="content-2">
                <p class="text-wrapper-12"><?php echo esc_html($cd_csd_cta_title); ?></p>
                <p class="div-4">
                  <?php echo esc_html($cd_csd_cta_text); ?>
                </p>
              </div>
            </div>
          </div>
          <button class="div-wrapper"><div class="text-wrapper-13"><?php echo esc_html($cd_csd_cta_btn); ?></div></button>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Related Case Studies Section -->
    <div class="case-study">
      <div class="frame-wrapper">
        <div class="frame-3">
          <div class="frame-2">
            <div class="text-wrapper-14"><?php echo esc_html($cd_csd_related_title); ?></div>
            <p class="our-SEO-strategies">
              <?php echo wp_kses_post($cd_csd_related_subtitle); ?>
            </p>
          </div>
          <div class="case-study-grid">
            <?php
            // Get related case studies (excluding current)
            $related_args = array(
              'post_type' => 'case_study',
              'posts_per_page' => $cd_csd_related_count ?: 3,
              'post__not_in' => array($post_id),
              'orderby' => 'rand',
            );
            $related_query = new WP_Query($related_args);
            if ($related_query->have_posts()) {
              while ($related_query->have_posts()) {
                $related_query->the_post();
                $related_id = get_the_ID();
                $related_title = get_the_title();
                $related_overview = craftdigitally_get_acf('cs_overview', get_the_excerpt(), $related_id);
                $related_company = craftdigitally_get_acf('cs_company_name', $related_title, $related_id);
                $related_category = craftdigitally_get_acf('cs_industry', $related_company, $related_id);
                
                $thumb_url = get_the_post_thumbnail_url($related_id, 'medium');
                $related_logo = craftdigitally_get_acf_image_url(
                  'cs_hero_logo',
                  $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/testeracademy.png'),
                  $related_id
                );
                
                $related_detail_url = function_exists('craftdigitally_get_case_study_detail_url')
                  ? craftdigitally_get_case_study_detail_url($related_id)
                  : get_permalink($related_id);
                $related_status = get_post_status($related_id);
                if ($related_status && $related_status !== 'publish') {
                  $related_detail_url = current_user_can('edit_post', $related_id) ? get_preview_post_link($related_id) : '#';
                }
                ?>
                <div class="case-study-card">
                  <div class="case-study-image-wrapper">
                    <img src="<?php echo esc_url($related_logo); ?>" alt="<?php echo esc_attr($related_company); ?> logo" class="case-study-logo" />
                  </div>
                  <div class="case-study-meta">
                    <span class="case-study-category"><?php echo esc_html($related_category); ?></span>
                    <hr class="case-study-divider" />
                  </div>
                  <p class="case-study-description"><?php echo esc_html($related_overview); ?></p>
                  <a href="<?php echo esc_url($related_detail_url); ?>" class="btn btn-outline case-study-cta"><?php echo esc_html($cd_csd_related_read_more); ?></a>
                </div>
                <?php
              }
              wp_reset_postdata();
            } else {
              // Fallback to static grid if no related posts
              craftdigitally_render_case_study_grid($cd_csd_related_count ?: 3, 'standard', $cd_csd_related_read_more);
            }
            ?>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA Form Section -->
    <?php craftdigitally_render_shared_cta_section(); ?>
  </div>
</main>

<?php
get_footer();
?>
