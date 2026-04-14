<?php
/**
 * Template Name: Case Study Detail Page
 * Template Post Type: page
 * 
 * @package CraftDigitally
 */

get_header();

// Check if a specific case study post is being viewed via post_id parameter
$cd_csd_post_id = isset($_GET['post_id']) ? (int) $_GET['post_id'] : 0;
$cd_csd_is_post_context = false;
$cd_csd_current_post = null;

if ($cd_csd_post_id > 0) {
  $cd_csd_current_post = get_post($cd_csd_post_id);
  if ($cd_csd_current_post && $cd_csd_current_post->post_type === 'case_study') {
    $cd_csd_is_post_context = true;
  }
}

// Get Case Study Landing page URL for back button
$cd_cs_landing_pages = get_pages(array(
  'meta_key' => '_wp_page_template',
  'meta_value' => 'page-templates/page-case-study-landing.php',
  'number' => 1,
  'post_status' => 'publish',
));
$cd_cs_landing_url = !empty($cd_cs_landing_pages) ? get_permalink($cd_cs_landing_pages[0]->ID) : home_url('/case-studies/');

// NOTE:
// Case studies should use their native permalinks:
// - Archive: /case-studies/
// - Single:  /case-studies/{post-name}/

// ACF overrides (falls back to current hardcoded output to avoid any UI/text/image changes).
// If viewing a specific post, use post data; otherwise use page ACF fields
if ($cd_csd_is_post_context && $cd_csd_current_post) {
  $post_id = $cd_csd_post_id;
  $cd_csd_title = craftdigitally_get_acf('cs_hero_title', get_the_title($post_id), $post_id);
  // Get hero logo from ACF (prioritize ACF field, then featured image, then default)
  $thumb_url = get_the_post_thumbnail_url($post_id, 'large');
  $cd_csd_logo = craftdigitally_get_acf_image_url(
    'cs_hero_logo',
    $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/urban-stitch-logo 1.png'),
    $post_id
  );
  // If ACF is empty, use featured image or default
  if (empty($cd_csd_logo) || $cd_csd_logo === (get_template_directory_uri() . '/assets/images/urban-stitch-logo 1.png')) {
    $cd_csd_logo = $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/urban-stitch-logo 1.png');
  }
} else {
  $post_id = get_the_ID();
  $cd_csd_title = craftdigitally_get_acf('cs_detail_hero_title', 'Local SEO Success<br />for a Retail Store', $post_id);
  $cd_csd_logo = craftdigitally_get_acf_image_url('cs_detail_hero_logo', get_template_directory_uri() . '/assets/images/urban-stitch-logo 1.png', $post_id);
}
$cd_csd_metrics_default = array(
  array('value' => '2,117%', 'label' => 'GROWTH IN ORGANIC BLOG SESSIONS'),
  array('value' => '11X', 'label' => 'CONVERSION RATE'),
  array('value' => '39X', 'label' => 'BLOG CONVERSIONS'),
);
if ($cd_csd_is_post_context) {
  $cd_csd_metrics = craftdigitally_get_acf_array('cs_metrics', $cd_csd_metrics_default, $post_id);
  $cd_csd_btn_label = craftdigitally_get_acf('cs_hero_button_label', 'Continue Reading', $post_id);
  $cd_csd_overview = craftdigitally_get_acf('cs_overview', get_the_excerpt($post_id), $post_id);
  $cd_csd_industry = craftdigitally_get_acf('cs_industry', 'Retail', $post_id);
  $cd_csd_company = craftdigitally_get_acf('cs_company_name', get_the_title($post_id), $post_id);
  $cd_csd_services = craftdigitally_get_acf('cs_services', 'Local SEO', $post_id);
} else {
  $cd_csd_metrics = craftdigitally_get_acf_array('cs_detail_metrics', $cd_csd_metrics_default, $post_id);
  $cd_csd_btn_label = craftdigitally_get_acf('cs_detail_hero_button_label', 'Continue Reading', $post_id);
  $cd_csd_overview = craftdigitally_get_acf('cs_detail_overview', "A local home décor retailer struggled to appear in search results and attract nearby shoppers. Through a focused Local SEO strategy — including Google Business optimization, keyword targeting, and review building — we improved their online visibility and credibility. Within months, the store ranked in Google's", $post_id);
  $cd_csd_industry = craftdigitally_get_acf('cs_detail_industry', 'Retail', $post_id);
  $cd_csd_company = craftdigitally_get_acf('cs_detail_company_name', 'Tester Academy', $post_id);
  $cd_csd_services = craftdigitally_get_acf('cs_detail_services', 'Local SEO', $post_id);
}

$cd_csd_problem_title = $cd_csd_is_post_context 
  ? craftdigitally_get_acf('cs_problem_title', 'Problem', $post_id)
  : craftdigitally_get_acf('cs_detail_problem_title', 'Problem', $post_id);
$cd_csd_problem_paras_default = array(
  array('text' => 'The client was struggling to attract local customers through online searches. Despite having a good product range, their business did not appear in the top results on Google Maps or local search queries. Competitors were outranking them, resulting in fewer calls, foot traffic, and online inquiries.'),
  array('text' => 'The client was struggling to attract local customers through online searches. Despite having a good product range, their business did not appear in the top results on Google Maps or local search queries. Competitors were outranking them, resulting in fewer calls, foot traffic, and online inquiries.'),
);
$cd_csd_problem_paras = $cd_csd_is_post_context
  ? craftdigitally_get_acf_array('cs_problem_paras', $cd_csd_problem_paras_default, $post_id)
  : craftdigitally_get_acf_array('cs_detail_problem_paras', $cd_csd_problem_paras_default, $post_id);

$cd_csd_solution_title = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_solution_title', 'Solution', $post_id)
  : craftdigitally_get_acf('cs_detail_solution_title', 'Solution', $post_id);
$cd_csd_solution_intro = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_solution_intro', 'The client was struggling to attract local customers through online searches. Despite having a good product range, their business did not appear in the top results on Google Maps or local search queries. Competitors were outranking them, resulting in fewer calls, foot traffic, and online inquiries.', $post_id)
  : craftdigitally_get_acf('cs_detail_solution_intro', 'The client was struggling to attract local customers through online searches. Despite having a good product range, their business did not appear in the top results on Google Maps or local search queries. Competitors were outranking them, resulting in fewer calls, foot traffic, and online inquiries.', $post_id);
$cd_csd_solution_lead = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_solution_lead', 'Our team implemented a tailored Local SEO strategy that include', $post_id)
  : craftdigitally_get_acf('cs_detail_solution_lead', 'Our team implemented a tailored Local SEO strategy that include', $post_id);
$cd_csd_solution_html_default = '<p class="text">
              <span class="text-wrapper-6">Optimizing their </span>
              <span class="text-wrapper-7">Google Business Profile</span>
              <span class="text-wrapper-6"> with accurate NAP (Name, Address, Phone) details, images, and posts.</span>
            </p>
            <p class="text">
              <span class="text-wrapper-6">Performing </span>
              <span class="text-wrapper-7">local keyword research</span>
              <span class="text-wrapper-6"> and optimizing service pages with location-based terms.</span>
            </p>
            <p class="text">
              <span class="text-wrapper-6">Building </span>
              <span class="text-wrapper-7">local citations</span>
              <span class="text-wrapper-6"> across trusted directories for consistency.</span>
            </p>
            <p class="text">
              <span class="text-wrapper-6">Launching a </span>
              <span class="text-wrapper-7">review generation strategy</span>
              <span class="text-wrapper-6"> to improve online reputation.</span>
            </p>
            <p class="text">
              <span class="text-wrapper-6">Creating </span>
              <span class="text-wrapper-7">locally relevant content</span>
              <span class="text-wrapper-6"> to target city-specific search queries.</span>
            </p>';
$cd_csd_solution_html = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_solution_html', $cd_csd_solution_html_default, $post_id)
  : craftdigitally_get_acf('cs_detail_solution_html', $cd_csd_solution_html_default, $post_id);

// Get main image from ACF (prioritize ACF field, then featured image, then default)
if ($cd_csd_is_post_context) {
  $thumb_url = get_the_post_thumbnail_url($post_id, 'large');
  $cd_csd_image_url = craftdigitally_get_acf_image_url(
    'cs_image',
    $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/hero.png'),
    $post_id
  );
  // If ACF is empty, use featured image or default
  if (empty($cd_csd_image_url) || $cd_csd_image_url === (get_template_directory_uri() . '/assets/images/hero.png')) {
    $cd_csd_image_url = $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/hero.png');
  }
} else {
  $cd_csd_image_url = craftdigitally_get_acf_image_url('cs_detail_image', get_template_directory_uri() . '/assets/images/hero.png', $post_id);
}
$cd_csd_image_caption = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_image_caption', 'The client was struggling to attract local customers through online searches', $post_id)
  : craftdigitally_get_acf('cs_detail_image_caption', 'The client was struggling to attract local customers through online searches', $post_id);

$cd_csd_test_quote = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_testimonial_quote', "Before working with CraftDigitally, SEO felt like a fog I couldn't navigate. Their process made everything so clear and actionable. I left with not only a visible online presence but a genuine sense of confidence in how I show up digitally", $post_id)
  : craftdigitally_get_acf('cs_detail_testimonial_quote', "Before working with CraftDigitally, SEO felt like a fog I couldn't navigate. Their process made everything so clear and actionable. I left with not only a visible online presence but a genuine sense of confidence in how I show up digitally", $post_id);
// Get testimonial avatar from ACF (prioritize ACF field, then default)
if ($cd_csd_is_post_context) {
  $cd_csd_test_avatar = craftdigitally_get_acf_image_url('cs_testimonial_avatar', get_template_directory_uri() . '/assets/images/sarah-lin.png', $post_id);
  // If ACF is empty, use default
  if (empty($cd_csd_test_avatar) || $cd_csd_test_avatar === (get_template_directory_uri() . '/assets/images/sarah-lin.png')) {
    $cd_csd_test_avatar = get_template_directory_uri() . '/assets/images/sarah-lin.png';
  }
} else {
  $cd_csd_test_avatar = craftdigitally_get_acf_image_url('cs_detail_testimonial_avatar', get_template_directory_uri() . '/assets/images/sarah-lin.png', $post_id);
}
$cd_csd_test_name = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_testimonial_name', 'Sarah Lin', $post_id)
  : craftdigitally_get_acf('cs_detail_testimonial_name', 'Sarah Lin', $post_id);
$cd_csd_test_role = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_testimonial_role', 'Brand Strategist, Lin Studio', $post_id)
  : craftdigitally_get_acf('cs_detail_testimonial_role', 'Brand Strategist, Lin Studio', $post_id);

$cd_csd_outcome_title = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_outcome_title', 'Outcome', $post_id)
  : craftdigitally_get_acf('cs_detail_outcome_title', 'Outcome', $post_id);
$cd_csd_outcome_text = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_outcome_text', 'The client was struggling to attract local customers through online searches. Despite having a good product range, their business did not appear in the top results on Google Maps or local search queries. Competitors were outranking them, resulting in fewer calls, foot traffic, and online inquiries.', $post_id)
  : craftdigitally_get_acf('cs_detail_outcome_text', 'The client was struggling to attract local customers through online searches. Despite having a good product range, their business did not appear in the top results on Google Maps or local search queries. Competitors were outranking them, resulting in fewer calls, foot traffic, and online inquiries.', $post_id);
$cd_csd_outcome_html_default = '<p class="text">
              <span class="text-wrapper-7">Top 3 rankings</span>
              <span class="text-wrapper-6"> for 12 competitive local keywords.</span>
            </p>
            <p class="text">
              <span class="text-wrapper-7">65% increase</span>
              <span class="text-wrapper-6"> in calls and inquiries from Google Maps.</span>
            </p>
            <p class="text">
              <span class="text-wrapper-7">40% growth</span>
              <span class="text-wrapper-6"> in in-store visits attributed to local search.</span>
            </p>
            <p class="text">
              <span class="text-wrapper-6">A stronger online reputation with </span>
              <span class="text-wrapper-7">50+ new 5-star review</span>
            </p>';
$cd_csd_outcome_html = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_outcome_html', $cd_csd_outcome_html_default, $post_id)
  : craftdigitally_get_acf('cs_detail_outcome_html', $cd_csd_outcome_html_default, $post_id);

$cd_csd_shared_cta = craftdigitally_get_shared_cta_data();
$cd_csd_cta_title = $cd_csd_shared_cta['title'];
$cd_csd_cta_text = $cd_csd_shared_cta['subtitle'];
$cd_csd_cta_btn = $cd_csd_shared_cta['button_label'];

$cd_csd_related_title = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_related_title', 'Related Case Studies', $post_id)
  : craftdigitally_get_acf('cs_detail_related_title', 'Related Case Studies', $post_id);
$cd_csd_related_subtitle = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_related_subtitle', "Our SEO strategies go beyond rankings — they deliver measurable business growth.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success.", $post_id)
  : craftdigitally_get_acf('cs_detail_related_subtitle', "Our SEO strategies go beyond rankings — they deliver measurable business growth.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success.", $post_id);
$cd_csd_related_count = 3;
$cd_csd_related_read_more = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_related_read_more_label', 'Read Full Story', $post_id)
  : craftdigitally_get_acf('cs_detail_related_read_more_label', 'Read Full Story', $post_id);

$cd_csd_form_title = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_form_title', 'Ready to Dominate Your Competition?', $post_id)
  : craftdigitally_get_acf('cs_detail_form_title', 'Ready to Dominate Your Competition?', $post_id);
$cd_csd_form_subtitle = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_form_subtitle', 'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad', $post_id)
  : craftdigitally_get_acf('cs_detail_form_subtitle', 'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad', $post_id);
$cd_csd_form_name_ph = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_form_name_placeholder', 'Name', $post_id)
  : craftdigitally_get_acf('cs_detail_form_name_placeholder', 'Name', $post_id);
$cd_csd_form_phone_ph = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_form_phone_placeholder', 'Phone', $post_id)
  : craftdigitally_get_acf('cs_detail_form_phone_placeholder', 'Phone', $post_id);
$cd_csd_form_email_ph = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_form_email_placeholder', 'Email', $post_id)
  : craftdigitally_get_acf('cs_detail_form_email_placeholder', 'Email', $post_id);
$cd_csd_form_service_ph = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_form_service_placeholder', 'Service', $post_id)
  : craftdigitally_get_acf('cs_detail_form_service_placeholder', 'Service', $post_id);
$cd_csd_form_message_ph = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_form_message_placeholder', 'Message', $post_id)
  : craftdigitally_get_acf('cs_detail_form_message_placeholder', 'Message', $post_id);
$cd_csd_form_submit = $cd_csd_is_post_context
  ? craftdigitally_get_acf('cs_form_submit_label', "Let's Connect", $post_id)
  : craftdigitally_get_acf('cs_detail_form_submit_label', "Let's Connect", $post_id);
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
    <div class="content">
      <div class="container-2 content-section-container">
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
        <div class="testimonial-card case-detail">
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
              'posts_per_page' => $cd_csd_related_count,
              'post__not_in' => $cd_csd_is_post_context ? array($cd_csd_post_id) : array(),
              'orderby' => 'rand',
            );
            $related_query = new WP_Query($related_args);
            $related_displayed = 0;
            if ($related_query->have_posts()) {
              while ($related_query->have_posts()) {
                $related_query->the_post();
                $related_id = get_the_ID();
                $related_title = get_the_title();
                $related_overview = craftdigitally_get_acf('cs_overview', get_the_excerpt(), $related_id);
                $related_company = craftdigitally_get_acf('cs_company_name', $related_title, $related_id);
                $related_category = craftdigitally_get_acf('cs_industry', $related_company, $related_id);
                
                // Get related case study logo from ACF (prioritize ACF field, then featured image, then default)
                $thumb_url = get_the_post_thumbnail_url($related_id, 'medium');
                $related_logo = craftdigitally_get_acf_image_url(
                  'cs_hero_logo',
                  $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/testeracademy.png'),
                  $related_id
                );
                // Ensure we have a valid image URL
                if (empty($related_logo) || $related_logo === (get_template_directory_uri() . '/assets/images/testeracademy.png')) {
                  $related_logo = $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/testeracademy.png');
                }
                
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
                $related_displayed++;
              }
              wp_reset_postdata();
            }

            // If there are fewer than 3 live related posts, fill the remaining slots
            // with the default case study cards so the section always shows 3 boxes.
            if ($related_displayed < $cd_csd_related_count) {
              $fallback_case_studies = function_exists('craftdigitally_get_case_studies')
                ? craftdigitally_get_case_studies($cd_csd_related_count)
                : array();

              if (!empty($fallback_case_studies)) {
                $fallback_needed = $cd_csd_related_count - $related_displayed;
                $fallback_case_studies = array_slice($fallback_case_studies, 0, $fallback_needed);

                foreach ($fallback_case_studies as $fallback_study) :
                  $fallback_logo = isset($fallback_study['logo']) ? $fallback_study['logo'] : '';
                  $fallback_category = isset($fallback_study['category']) ? $fallback_study['category'] : '';
                  $fallback_description = isset($fallback_study['description']) ? $fallback_study['description'] : '';
                  $fallback_link = isset($fallback_study['link']) ? $fallback_study['link'] : '#';
                  ?>
                  <div class="case-study-card">
                    <div class="case-study-image-wrapper">
                      <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/' . rawurlencode($fallback_logo)); ?>" alt="<?php echo esc_attr($fallback_category); ?> logo" class="case-study-logo" />
                    </div>
                    <div class="case-study-meta">
                      <span class="case-study-category"><?php echo esc_html($fallback_category); ?></span>
                      <hr class="case-study-divider" />
                    </div>
                    <p class="case-study-description"><?php echo esc_html($fallback_description); ?></p>
                    <a href="<?php echo esc_url($fallback_link); ?>" class="btn btn-outline case-study-cta"><?php echo esc_html($cd_csd_related_read_more); ?></a>
                  </div>
                  <?php
                endforeach;
              }
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
