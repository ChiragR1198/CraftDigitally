<?php
/**
 * Template Name: Service Detail Page
 * Template Post Type: page
 *
 * Service detail page with hero, problem, benefits, results, services list,
 * process, testimonials, FAQs, and CTA. Matches Figma design.
 *
 * @package CraftDigitally
 */

get_header();

// Check if a specific service post is being viewed via post_id parameter
$cd_sd_post_id = isset($_GET['post_id']) ? (int) $_GET['post_id'] : 0;
$cd_sd_is_post_context = false;
$cd_sd_current_post = null;

if ($cd_sd_post_id > 0) {
  $cd_sd_current_post = get_post($cd_sd_post_id);
  if ($cd_sd_current_post && $cd_sd_current_post->post_type === 'service') {
    $cd_sd_is_post_context = true;
  }
}

// Get Service Landing page URL for back button
$cd_sd_landing_pages = get_pages(array(
  'meta_key' => '_wp_page_template',
  'meta_value' => 'page-templates/page-service-landing.php',
  'number' => 1,
  'post_status' => 'publish',
));
$cd_sd_landing_url = !empty($cd_sd_landing_pages) ? get_permalink($cd_sd_landing_pages[0]->ID) : home_url('/services/');

// Default content for Local SEO – can be overridden by ACF (fallbacks keep current output unchanged)
// If viewing a specific post, use post data; otherwise use page ACF fields
if ($cd_sd_is_post_context && $cd_sd_current_post) {
  $post_id = $cd_sd_post_id;
  $service_title = get_the_title($post_id);
  $hero_headline_default = 'Local SEO That Drives Real Customers to Your Business';
  $hero_subhead_default = 'Get more calls, more leads, and more foot traffic from the people already searching for what you offer in your city.';
  $problem_headline_default = 'Is Your Business Invisible to Nearby Customers?';
  $problem_text_default = '46% of all Google searches have "local intent" (people looking for services near them). If your business doesn\'t appear in the top 3 spots of the "Google Map Pack," you typically don\'t exist to those customers.

At CraftDigitally, we don\'t just "do SEO." We engineer your online presence to ensure that when local customers search for your services, you are the first brand they see and the first one they call.';
  $problem_image_default = get_template_directory_uri() . '/assets/images/bookimage.png';
} else {
  $post_id = get_the_ID();
  $service_title = get_the_title();
  $hero_headline_default = get_post_meta($post_id, 'service_hero_headline', true) ?: 'Local SEO That Drives Real Customers to Your Business';
  $hero_subhead_default = get_post_meta($post_id, 'service_hero_subhead', true) ?: 'Get more calls, more leads, and more foot traffic from the people already searching for what you offer in your city.';
  $problem_headline_default = get_post_meta($post_id, 'service_problem_headline', true) ?: 'Is Your Business Invisible to Nearby Customers?';
  $problem_text_default = get_post_meta($post_id, 'service_problem_text', true) ?: '46% of all Google searches have "local intent" (people looking for services near them). If your business doesn\'t appear in the top 3 spots of the "Google Map Pack," you typically don\'t exist to those customers.

At CraftDigitally, we don\'t just "do SEO." We engineer your online presence to ensure that when local customers search for your services, you are the first brand they see and the first one they call.';
  $problem_image_default = get_post_meta($post_id, 'service_problem_image', true) ?: get_template_directory_uri() . '/assets/images/bookimage.png';
}

$hero_headline = craftdigitally_get_acf('service_hero_headline', $hero_headline_default, $post_id);
$hero_subhead = craftdigitally_get_acf('service_hero_subhead', $hero_subhead_default, $post_id);
$problem_headline = craftdigitally_get_acf('service_problem_headline', $problem_headline_default, $post_id);
$problem_text = craftdigitally_get_acf('service_problem_text', $problem_text_default, $post_id);
$problem_image = craftdigitally_get_acf_image_url('service_problem_image', $problem_image_default, $post_id);

$cd_sd_key_highlights_default = array(
  array('bold' => 'Dominate the Map Pack:', 'text' => 'Secure prime real estate on Google Maps'),
  array('bold' => 'High-Intent Traffic:', 'text' => 'Target customers actively searching to buy now'),
  array('bold' => 'Build Trust:', 'text' => 'Turn your Google Profile into your best 24/7 salesperson.'),
);
$cd_sd_key_highlights = craftdigitally_get_acf_array('service_key_highlights', $cd_sd_key_highlights_default, $post_id);

$cd_sd_benefits_title = craftdigitally_get_acf('service_benefits_title', 'Key Benefits of Local SEO for Your Business', $post_id);
$cd_sd_benefits_subtitle = craftdigitally_get_acf('service_benefits_subtitle', 'From higher visibility to more leads, see how local SEO helps you get found and chosen.', $post_id);
$cd_sd_benefits_cards_default = array(
  array('icon' => get_template_directory_uri() . '/assets/images/local-seo.png', 'title' => 'Local Visibility', 'desc' => 'Show up when people nearby search for your products or services on Google Maps and local search.'),
  array('icon' => get_template_directory_uri() . '/assets/images/link-building.png', 'title' => 'Higher Rankings', 'desc' => 'Improve your position for relevant local keywords so you appear ahead of competitors.'),
  array('icon' => get_template_directory_uri() . '/assets/images/small-business-seo.png', 'title' => 'More Leads', 'desc' => 'Turn local searches into calls, directions, and website visits that convert into customers.'),
  array('icon' => get_template_directory_uri() . '/assets/images/social-media-services.png', 'title' => 'Brand Trust', 'desc' => 'Reviews, accurate info, and consistent listings build credibility and trust with local searchers.'),
  array('icon' => get_template_directory_uri() . '/assets/images/international-seo.png', 'title' => 'Competitive Edge', 'desc' => 'Outrank nearby competitors and own your local market with a clear, focused strategy.'),
  array('icon' => get_template_directory_uri() . '/assets/images/emommerce-seo.png', 'title' => 'Measurable Growth', 'desc' => 'Track rankings, traffic, and conversions so you see real business impact from local SEO.'),
);
$cd_sd_benefits_cards = craftdigitally_get_acf_array('service_benefits_cards', $cd_sd_benefits_cards_default, $post_id);

$cd_sd_mid_cta_title = craftdigitally_get_acf('service_mid_cta_title', 'Ready to Dominate Your Competition?', $post_id);
$cd_sd_mid_cta_text = craftdigitally_get_acf('service_mid_cta_text', 'Get a clear local SEO strategy and start turning nearby searches into real customers.', $post_id);
$cd_sd_mid_cta_btn = craftdigitally_get_acf('service_mid_cta_button_label', 'Book a Free Consult', $post_id);

$cd_sd_results_shared = function_exists('craftdigitally_get_shared_case_study_results_data')
  ? craftdigitally_get_shared_case_study_results_data()
  : array(
    'title' => 'Results Our Clients Have Achieved',
    'subtitle' => "Our SEO strategies go beyond rankings — they deliver measurable business growth.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success.",
    'read_more_label' => 'Read Full Story',
    'view_all_label' => 'View all Case Studies',
    'view_all_url' => home_url('/case-studies/'),
  );
$cd_sd_results_title = $cd_sd_results_shared['title'];
$cd_sd_results_subtitle = $cd_sd_results_shared['subtitle'];
$cd_sd_results_count = 6;
$cd_sd_results_read_more = $cd_sd_results_shared['read_more_label'];
$cd_sd_results_view_all = $cd_sd_results_shared['view_all_label'];
$cd_sd_results_view_all_url = $cd_sd_results_shared['view_all_url'];

// Get Case Study Detail page URL for dynamic linking
// NOTE:
// Case studies should use their native permalinks:
// - Archive: /case-studies/
// - Single:  /case-studies/{post-name}/

// Query case_study posts for service detail page
$cd_sd_cs_posts_status = current_user_can('edit_posts')
  ? array('publish', 'draft', 'pending', 'future', 'private')
  : array('publish');

$cd_sd_cs_posts_q = new WP_Query(array(
  'post_type' => 'case_study',
  'post_status' => $cd_sd_cs_posts_status,
  'posts_per_page' => $cd_sd_results_count ?: 6,
  'ignore_sticky_posts' => true,
));

$cd_sd_services_title = craftdigitally_get_acf('service_services_title', 'Local SEO Services That Help You Get Found — and Chosen', $post_id);
$cd_sd_services_subtitle = craftdigitally_get_acf('service_services_subtitle', 'We combine strategy, optimization, and ongoing support so your business becomes the obvious choice locally.', $post_id);
$cd_sd_services_items_default = array(
  array('title' => 'Google Profile', 'desc' => 'Optimise your Google Business Profile with accurate NAP, photos, posts, and categories so you show up correctly in Maps and local search.'),
  array('title' => 'Keyword Strategy', 'desc' => 'Target the right "near me" and location-based keywords so you rank for searches that actually bring in customers.'),
  array('title' => 'On-Page SEO', 'desc' => 'Align titles, meta, and content with local intent so each page supports your visibility in your service areas.'),
  array('title' => 'Citations', 'desc' => 'Build and clean up listings across trusted directories so Google sees consistent, accurate business information.'),
  array('title' => 'Reviews', 'desc' => 'Grow and showcase genuine reviews to improve trust, click-through, and local rankings.'),
  array('title' => 'Link Building', 'desc' => 'Earn relevant local and niche backlinks to strengthen authority and visibility in your market.'),
);
$cd_sd_services_items = craftdigitally_get_acf_array('service_services_items', $cd_sd_services_items_default, $post_id);

$cd_sd_process_title = craftdigitally_get_acf('service_process_title', 'Our Proven Process', $post_id);
$cd_sd_process_subtitle = craftdigitally_get_acf('service_process_subtitle', 'From audit to optimization, we follow a clear, repeatable process that delivers results.', $post_id);
$cd_sd_process_steps_default = array(
  array('number' => '1', 'title' => 'SEO Audit & Strategy', 'description' => 'We review your current presence, competitors, and goals to build a tailored local SEO plan.'),
  array('number' => '2', 'title' => 'Expert Implementation', 'description' => 'Our team implements optimisations across your profile, website, and citations with clear ownership and timelines.'),
  array('number' => '3', 'title' => 'Performance Tracking', 'description' => 'You get transparent reporting on rankings, traffic, and leads so you always know how you\'re performing.'),
  array('number' => '4', 'title' => 'Continuous Optimization', 'description' => 'We keep refining based on data and market changes so your local visibility grows over time.'),
);
$cd_sd_process_steps = craftdigitally_get_acf_array('service_process_steps', $cd_sd_process_steps_default, $post_id);

$cd_sd_faq_title = craftdigitally_get_acf('service_faq_title', 'FAQs', $post_id);
$cd_sd_faq_subtitle = craftdigitally_get_acf('service_faq_subtitle', 'Find answers to common questions about local SEO and how we work.', $post_id);
$cd_sd_faq_items_default = array(
  array('question' => 'How long does it take to see results from SEO?', 'answer' => 'Local SEO often shows early improvements in 2–4 months (e.g. better map visibility, more impressions). Meaningful traffic and lead growth usually build over 4–6 months and beyond, depending on competition and consistency.'),
  array('question' => 'What is the typical cost of local SEO pricing?', 'answer' => 'Costs vary by scope: audits and one-off projects, ongoing monthly retainers, or full-service local SEO. We tailor packages to your goals and budget—share your objectives and we\'ll suggest a clear plan.'),
  array('question' => 'Do you work with businesses outside Ahmedabad?', 'answer' => 'Yes. We work with local businesses across India and can support you remotely with strategy, optimisation, and reporting. Many of our clients are outside Ahmedabad.'),
  array('question' => 'What\'s included in a local SEO audit?', 'answer' => 'We review your Google Business Profile, website pages, keywords, citations, and competitors. You get a written report with findings and prioritised next steps.'),
  array('question' => 'How do you report results?', 'answer' => 'We share regular reports (e.g. monthly) with rankings, search performance, traffic, and lead metrics. You\'ll see dashboards and summaries that focus on what matters for your business.'),
);
$cd_sd_faq_items = craftdigitally_get_acf_array('service_faq_items', $cd_sd_faq_items_default, $post_id);

$cd_sd_cta_title = craftdigitally_get_acf('service_cta_title', 'Ready to Dominate Your Competition?', $post_id);
$cd_sd_cta_subtitle = craftdigitally_get_acf('service_cta_subtitle', 'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad.', $post_id);
$cd_sd_cta_name_ph = craftdigitally_get_acf('service_cta_name_placeholder', 'Name', $post_id);
$cd_sd_cta_phone_ph = craftdigitally_get_acf('service_cta_phone_placeholder', 'Phone', $post_id);
$cd_sd_cta_email_ph = craftdigitally_get_acf('service_cta_email_placeholder', 'Email', $post_id);
$cd_sd_cta_service_ph = craftdigitally_get_acf('service_cta_service_placeholder', 'Service', $post_id);
$cd_sd_cta_message_ph = craftdigitally_get_acf('service_cta_message_placeholder', 'Message', $post_id);
$cd_sd_cta_submit = craftdigitally_get_acf('service_cta_submit_label', "Let's Connect", $post_id);
?>

<main id="main-content" class="service-detail-page">
  <div class="main">
    <!-- Hero Section -->
    <div class="hero">
      <div class="container hero-service-container">
        <div class="headline-subhead">
          <p class="text-wrapper"><?php echo esc_html($hero_headline); ?></p>
          <p class="div"><?php echo esc_html($hero_subhead); ?></p>
        </div>
        <a href="#contact" class="button"><div class="text-wrapper-2">Book a Free Consult</div></a>
      </div>
    </div>

    <!-- Problem / Introduction Section -->
    <section class="sd-problem">
      <div class="sd-container">
        <div class="sd-problem-grid">
          <div class="sd-problem-content">
            <h2 class="sd-problem-title"><?php echo esc_html($problem_headline); ?></h2>
            <div class="sd-problem-text">
              <?php echo wp_kses_post(wpautop($problem_text)); ?>
            </div>
            <div class="sd-key-highlights">
              <p class="sd-highlights-label">Key highlights</p>
              <ul class="sd-highlights-list">
                <?php foreach ($cd_sd_key_highlights as $h): ?>
                  <li><strong><?php echo esc_html(isset($h['bold']) ? $h['bold'] : ''); ?></strong> <?php echo esc_html(isset($h['text']) ? $h['text'] : ''); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
          <div class="sd-problem-image-wrap">
            <img class="sd-problem-image" src="<?php echo esc_url($problem_image); ?>" alt="<?php echo esc_attr($problem_headline); ?>" />
          </div>
        </div>
      </div>
    </section>

    <!-- Key Benefits Section -->
    <section class="services-section">
      <div class="container">
        <div class="services-header">
          <h2 class="services-title"><?php echo esc_html($cd_sd_benefits_title); ?></h2>
          <p class="services-subtitle">
            <?php echo esc_html($cd_sd_benefits_subtitle); ?>
          </p>
        </div>

        <div class="services-grid">
          <?php foreach ($cd_sd_benefits_cards as $b): ?>
            <?php
              $icon = isset($b['icon']) ? $b['icon'] : '';
              if (is_array($icon) && !empty($icon['url'])) { $icon = $icon['url']; }
              elseif (is_numeric($icon)) { $icon = wp_get_attachment_image_url((int) $icon, 'full'); }
              elseif (is_string($icon) && strpos($icon, 'http') !== 0) { $icon = get_template_directory_uri() . '/assets/images/' . ltrim($icon, '/'); }
              $icon_alt = isset($b['title']) ? $b['title'] . ' icon' : '';
            ?>
          <div class="service-card">
            <div class="service-icon-wrapper">
                <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($icon_alt); ?>" class="service-icon" />
          </div>
              <h3 class="service-title"><?php echo esc_html(isset($b['title']) ? $b['title'] : ''); ?></h3>
            <hr class="service-divider" />
              <p class="service-description"><?php echo esc_html(isset($b['desc']) ? $b['desc'] : ''); ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Mid CTA Section -->
    <section class="sd-mid-cta">
      <div class="sd-container sd-mid-cta-inner">
        <div class="sd-mid-cta-content">
          <h2 class="sd-mid-cta-title"><?php echo esc_html($cd_sd_mid_cta_title); ?></h2>
          <p class="sd-mid-cta-text"><?php echo esc_html($cd_sd_mid_cta_text); ?></p>
        </div>
        <a href="#contact" class="btn btn-outline sd-mid-cta-btn"><?php echo esc_html($cd_sd_mid_cta_btn); ?></a>
      </div>
    </section>

    <!-- Client Results Section -->
    <section class="sd-results">
      <div class="sd-container">
        <div class="sd-section-header sd-section-header--center">
          <h2 class="sd-section-title"><?php echo esc_html($cd_sd_results_title); ?></h2>
          <p class="sd-section-subtitle"><?php echo wp_kses_post($cd_sd_results_subtitle); ?></p>
        </div>
        <div class="sd-results-grid">
          <?php
          // Display dynamic case studies from custom post type
          if ($cd_sd_cs_posts_q->have_posts()) {
            while ($cd_sd_cs_posts_q->have_posts()) {
              $cd_sd_cs_posts_q->the_post();
              $cs_id = get_the_ID();
              $cs_title = get_the_title();
              $cs_overview = craftdigitally_get_acf('cs_overview', get_the_excerpt(), $cs_id);
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
                <a href="<?php echo esc_url($detail_url); ?>" class="btn btn-outline case-study-cta"><?php echo esc_html($cd_sd_results_read_more); ?></a>
              </div>
              <?php
            }
            wp_reset_postdata();
          } else {
            // Fallback to static grid if no posts
            craftdigitally_render_case_study_grid($cd_sd_results_count, 'standard', $cd_sd_results_read_more);
          }
          ?>
        </div>
        <div class="sd-results-cta-wrap">
          <a href="<?php echo esc_url($cd_sd_results_view_all_url); ?>" class="btn btn-outline sd-results-view-all"><?php echo esc_html($cd_sd_results_view_all); ?></a>
        </div>
      </div>
    </section>

    <!-- Detailed Services Section -->
    <section class="sd-services-detail">
      <div class="sd-container">
        <div class="sd-section-header">
          <h2 class="sd-section-title"><?php echo esc_html($cd_sd_services_title); ?></h2>
          <p class="sd-section-subtitle"><?php echo esc_html($cd_sd_services_subtitle); ?></p>
        </div>
        <div class="sd-services-detail-grid">
          <?php foreach ($cd_sd_services_items as $it): ?>
          <div class="sd-service-item">
              <h3 class="sd-service-item-title"><?php echo esc_html(isset($it['title']) ? $it['title'] : ''); ?></h3>
              <hr class="service-divider" />
              <p class="sd-service-item-desc"><?php echo esc_html(isset($it['desc']) ? $it['desc'] : ''); ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Our Proven Process (same step UI as home; no gradient bg image on this page template) -->
    <section class="service-detail-process">
      <div class="container testimonials-container">
        <div class="process-header">
          <h2 class="process-title"><?php echo esc_html($cd_sd_process_title); ?></h2>
          <p class="process-subtitle">
            <?php echo esc_html($cd_sd_process_subtitle); ?>
          </p>
        </div>

        <div class="process-steps">
          <?php foreach ($cd_sd_process_steps as $s): ?>
            <?php
              $step_desc = isset($s['description']) ? $s['description'] : (isset($s['desc']) ? $s['desc'] : '');
            ?>
          <div class="process-step">
            <div class="process-number"><?php echo esc_html(isset($s['number']) ? $s['number'] : ''); ?></div>
            <div class="process-content">
              <h3 class="process-step-title"><?php echo esc_html(isset($s['title']) ? $s['title'] : ''); ?></h3>
              <p class="process-step-description"><?php echo esc_html($step_desc); ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <?php get_template_part('template-parts/sections/testimonial-slider-section'); ?>

    <!-- FAQs Section -->
    <section class="sd-faq">
      <div class="sd-container">
        <div class="sd-section-header sd-section-header--center">
          <h2 class="sd-section-title"><?php echo esc_html($cd_sd_faq_title); ?></h2>
          <p class="sd-section-subtitle"><?php echo esc_html($cd_sd_faq_subtitle); ?></p>
        </div>
        <div class="sd-accordion">
          <?php foreach ($cd_sd_faq_items as $idx => $faq): ?>
            <details class="sd-accordion-item" <?php echo $idx === 0 ? 'open' : ''; ?>>
              <summary class="sd-accordion-trigger"><?php echo esc_html(isset($faq['question']) ? $faq['question'] : ''); ?></summary>
            <div class="sd-accordion-content">
                <p><?php echo esc_html(isset($faq['answer']) ? $faq['answer'] : ''); ?></p>
            </div>
          </details>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <?php craftdigitally_render_shared_cta_section(); ?>
  </div>
</main>

<?php
get_footer();
