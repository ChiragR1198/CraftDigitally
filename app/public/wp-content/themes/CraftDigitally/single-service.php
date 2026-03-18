<?php
/**
 * Single Service Template
 * 
 * Template for displaying individual services
 *
 * @package CraftDigitally
 */

get_header();

$post_id = get_the_ID();
$services_landing_page = get_pages(array(
  'meta_key' => '_wp_page_template',
  'meta_value' => 'page-templates/page-service-landing.php',
  'number' => 1,
  'post_status' => 'publish'
));
$services_url = !empty($services_landing_page) ? get_permalink($services_landing_page[0]->ID) : home_url('/services/');

// ACF fields with fallbacks
$hero_headline = craftdigitally_get_acf('service_hero_headline', get_the_title(), $post_id);
$hero_subhead = craftdigitally_get_acf('service_hero_subhead', get_the_excerpt(), $post_id);

$problem_headline = craftdigitally_get_acf('service_problem_headline', 'Is Your Business Invisible to Nearby Customers?', $post_id);
$problem_text = craftdigitally_get_acf('service_problem_text', '', $post_id);
$problem_image = craftdigitally_get_acf_image_url('service_problem_image', get_template_directory_uri() . '/assets/images/bookimage.png', $post_id);

$cd_sd_key_highlights = craftdigitally_get_acf_array('service_key_highlights', array(
  array('bold' => 'Dominate the Map Pack:', 'text' => 'Secure prime real estate on Google Maps'),
  array('bold' => 'High-Intent Traffic:', 'text' => 'Target customers actively searching to buy now'),
  array('bold' => 'Build Trust:', 'text' => 'Turn your Google Profile into your best 24/7 salesperson.'),
), $post_id);

$cd_sd_benefits_title = craftdigitally_get_acf('service_benefits_title', 'Key Benefits of Local SEO for Your Business', $post_id);
$cd_sd_benefits_subtitle = craftdigitally_get_acf('service_benefits_subtitle', 'From higher visibility to more leads, see how local SEO helps you get found and chosen.', $post_id);
$cd_sd_benefits_cards = craftdigitally_get_acf_array('service_benefits_cards', array(
  array('icon' => get_template_directory_uri() . '/assets/images/local-seo.png', 'title' => 'Local Visibility', 'desc' => 'Show up when people nearby search for your products or services on Google Maps and local search.'),
  array('icon' => get_template_directory_uri() . '/assets/images/link-building.png', 'title' => 'Higher Rankings', 'desc' => 'Improve your position for relevant local keywords so you appear ahead of competitors.'),
  array('icon' => get_template_directory_uri() . '/assets/images/small-business-seo.png', 'title' => 'More Leads', 'desc' => 'Turn local searches into calls, directions, and website visits that convert into customers.'),
  array('icon' => get_template_directory_uri() . '/assets/images/social-media-services.png', 'title' => 'Brand Trust', 'desc' => 'Reviews, accurate info, and consistent listings build credibility and trust with local searchers.'),
  array('icon' => get_template_directory_uri() . '/assets/images/international-seo.png', 'title' => 'Competitive Edge', 'desc' => 'Outrank nearby competitors and own your local market with a clear, focused strategy.'),
  array('icon' => get_template_directory_uri() . '/assets/images/emommerce-seo.png', 'title' => 'Measurable Growth', 'desc' => 'Track rankings, traffic, and conversions so you see real business impact from local SEO.'),
), $post_id);

$cd_sd_mid_cta_title = craftdigitally_get_acf('service_mid_cta_title', 'Ready to Dominate Your Competition?', $post_id);
$cd_sd_mid_cta_text = craftdigitally_get_acf('service_mid_cta_text', 'Get a clear local SEO strategy and start turning nearby searches into real customers.', $post_id);
$cd_sd_mid_cta_btn = craftdigitally_get_acf('service_mid_cta_button_label', 'Book a Free Consult', $post_id);

$cd_sd_results_title = craftdigitally_get_acf('service_results_title', 'Results Our Clients Have Achieved', $post_id);
$cd_sd_results_subtitle = craftdigitally_get_acf('service_results_subtitle', 'From higher visibility to more traffic and leads, see how our clients turned local search into success.', $post_id);
$cd_sd_results_count = (int) craftdigitally_get_acf('service_results_count', 6, $post_id);
$cd_sd_results_read_more = craftdigitally_get_acf('service_results_read_more_label', 'Read Full Story', $post_id);
$cd_sd_results_view_all = craftdigitally_get_acf('service_results_view_all_label', 'View All Case Studies', $post_id);

$cd_sd_services_title = craftdigitally_get_acf('service_services_title', 'Local SEO Services That Help You Get Found — and Chosen', $post_id);
$cd_sd_services_subtitle = craftdigitally_get_acf('service_services_subtitle', 'We combine strategy, optimization, and ongoing support so your business becomes the obvious choice locally.', $post_id);
$cd_sd_services_items = craftdigitally_get_acf_array('service_services_items', array(
  array('title' => 'Google Profile', 'desc' => 'Optimise your Google Business Profile with accurate NAP, photos, posts, and categories so you show up correctly in Maps and local search.'),
  array('title' => 'Keyword Strategy', 'desc' => 'Target the right "near me" and location-based keywords so you rank for searches that actually bring in customers.'),
  array('title' => 'On-Page SEO', 'desc' => 'Align titles, meta, and content with local intent so each page supports your visibility in your service areas.'),
  array('title' => 'Citations', 'desc' => 'Build and clean up listings across trusted directories so Google sees consistent, accurate business information.'),
  array('title' => 'Reviews', 'desc' => 'Grow and showcase genuine reviews to improve trust, click-through, and local rankings.'),
  array('title' => 'Link Building', 'desc' => 'Earn relevant local and niche backlinks to strengthen authority and visibility in your market.'),
), $post_id);

$cd_sd_process_title = craftdigitally_get_acf('service_process_title', 'Our Proven Process', $post_id);
$cd_sd_process_subtitle = craftdigitally_get_acf('service_process_subtitle', 'From audit to optimization, we follow a clear, repeatable process that delivers results.', $post_id);
$cd_sd_process_steps = craftdigitally_get_acf_array('service_process_steps', array(
  array('number' => '1', 'title' => 'SEO Audit & Strategy', 'desc' => 'We review your current presence, competitors, and goals to build a tailored local SEO plan.'),
  array('number' => '2', 'title' => 'Expert Implementation', 'desc' => 'Our team implements optimisations across your profile, website, and citations with clear ownership and timelines.'),
  array('number' => '3', 'title' => 'Performance Tracking', 'desc' => 'You get transparent reporting on rankings, traffic, and leads so you always know how you\'re performing.'),
  array('number' => '4', 'title' => 'Continuous Optimization', 'desc' => 'We keep refining based on data and market changes so your local visibility grows over time.'),
), $post_id);

$cd_sd_faq_title = craftdigitally_get_acf('service_faq_title', 'FAQs', $post_id);
$cd_sd_faq_subtitle = craftdigitally_get_acf('service_faq_subtitle', 'Find answers to common questions about local SEO and how we work.', $post_id);
$cd_sd_faq_items = craftdigitally_get_acf_array('service_faq_items', array(
  array('question' => 'How long does it take to see results from SEO?', 'answer' => 'Local SEO often shows early improvements in 2–4 months (e.g. better map visibility, more impressions). Meaningful traffic and lead growth usually build over 4–6 months and beyond, depending on competition and consistency.'),
  array('question' => 'What is the typical cost of local SEO pricing?', 'answer' => 'Costs vary by scope: audits and one-off projects, ongoing monthly retainers, or full-service local SEO. We tailor packages to your goals and budget—share your objectives and we\'ll suggest a clear plan.'),
  array('question' => 'Do you work with businesses outside Ahmedabad?', 'answer' => 'Yes. We work with local businesses across India and can support you remotely with strategy, optimisation, and reporting. Many of our clients are outside Ahmedabad.'),
  array('question' => 'What\'s included in a local SEO audit?', 'answer' => 'We review your Google Business Profile, website pages, keywords, citations, and competitors. You get a written report with findings and prioritised next steps.'),
  array('question' => 'How do you report results?', 'answer' => 'We share regular reports (e.g. monthly) with rankings, search performance, traffic, and lead metrics. You\'ll see dashboards and summaries that focus on what matters for your business.'),
), $post_id);

$cd_sd_cta_title = craftdigitally_get_acf('service_cta_title', 'Ready to Dominate Your Competition?', $post_id);
$cd_sd_cta_subtitle = craftdigitally_get_acf('service_cta_subtitle', 'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad.', $post_id);
$cd_sd_cta_name_ph = craftdigitally_get_acf('service_cta_name_placeholder', 'Name', $post_id);
$cd_sd_cta_phone_ph = craftdigitally_get_acf('service_cta_phone_placeholder', 'Phone', $post_id);
$cd_sd_cta_email_ph = craftdigitally_get_acf('service_cta_email_placeholder', 'Email', $post_id);
$cd_sd_cta_service_ph = craftdigitally_get_acf('service_cta_service_placeholder', 'Service', $post_id);
$cd_sd_cta_message_ph = craftdigitally_get_acf('service_cta_message_placeholder', 'Message', $post_id);
$cd_sd_cta_submit = craftdigitally_get_acf('service_cta_submit_label', "Let's Connect", $post_id);

// If ACF content is empty, use post content
if (empty($problem_text) && empty($cd_sd_services_items) && empty($cd_sd_process_steps)) {
  $problem_text = apply_filters('the_content', get_the_content());
}
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
    <?php if (!empty($problem_headline) || !empty($problem_text)): ?>
    <section class="sd-problem">
      <div class="sd-container">
        <div class="sd-problem-grid">
          <div class="sd-problem-content">
            <?php if (!empty($problem_headline)): ?>
            <h2 class="sd-problem-title"><?php echo esc_html($problem_headline); ?></h2>
            <?php endif; ?>
            <?php if (!empty($problem_text)): ?>
            <div class="sd-problem-text">
              <?php echo wp_kses_post(wpautop($problem_text)); ?>
            </div>
            <?php endif; ?>
            <?php if (!empty($cd_sd_key_highlights)): ?>
            <div class="sd-key-highlights">
              <p class="sd-highlights-label">Key highlights</p>
              <ul class="sd-highlights-list">
                <?php foreach ($cd_sd_key_highlights as $h): ?>
                  <li><strong><?php echo esc_html(isset($h['bold']) ? $h['bold'] : ''); ?></strong> <?php echo esc_html(isset($h['text']) ? $h['text'] : ''); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
            <?php endif; ?>
          </div>
          <?php if (!empty($problem_image)): ?>
          <div class="sd-problem-image-wrap">
            <img class="sd-problem-image" src="<?php echo esc_url($problem_image); ?>" alt="<?php echo esc_attr($problem_headline); ?>" />
          </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- Key Benefits Section -->
    <?php if (!empty($cd_sd_benefits_title) || !empty($cd_sd_benefits_cards)): ?>
    <section class="sd-benefits">
      <div class="sd-container">
        <div class="sd-section-header sd-section-header--center">
          <h2 class="sd-section-title"><?php echo esc_html($cd_sd_benefits_title); ?></h2>
          <p class="sd-section-subtitle"><?php echo esc_html($cd_sd_benefits_subtitle); ?></p>
        </div>
        <div class="sd-benefits-grid">
          <?php foreach ($cd_sd_benefits_cards as $b): ?>
            <?php
              $icon = isset($b['icon']) ? $b['icon'] : '';
              if (is_array($icon) && !empty($icon['url'])) { $icon = $icon['url']; }
              elseif (is_numeric($icon)) { $icon = wp_get_attachment_image_url((int) $icon, 'full'); }
              elseif (is_string($icon) && strpos($icon, 'http') !== 0) { $icon = get_template_directory_uri() . '/assets/images/' . ltrim($icon, '/'); }
            ?>
            <div class="sd-benefit-card">
              <div class="sd-benefit-icon"><img src="<?php echo esc_url($icon); ?>" alt="" /></div>
              <h3 class="sd-benefit-title"><?php echo esc_html(isset($b['title']) ? $b['title'] : ''); ?></h3>
              <p class="sd-benefit-desc"><?php echo esc_html(isset($b['desc']) ? $b['desc'] : ''); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- Mid CTA Section -->
    <?php if (!empty($cd_sd_mid_cta_title)): ?>
    <section class="sd-mid-cta">
      <div class="sd-container sd-mid-cta-inner">
        <div class="sd-mid-cta-content">
          <h2 class="sd-mid-cta-title"><?php echo esc_html($cd_sd_mid_cta_title); ?></h2>
          <p class="sd-mid-cta-text"><?php echo esc_html($cd_sd_mid_cta_text); ?></p>
        </div>
        <a href="#contact" class="btn btn-outline sd-mid-cta-btn"><?php echo esc_html($cd_sd_mid_cta_btn); ?></a>
      </div>
    </section>
    <?php endif; ?>

    <!-- Client Results Section -->
    <?php if (!empty($cd_sd_results_title)): ?>
    <section class="sd-results">
      <div class="sd-container">
        <div class="sd-section-header sd-section-header--center">
          <h2 class="sd-section-title"><?php echo esc_html($cd_sd_results_title); ?></h2>
          <p class="sd-section-subtitle"><?php echo esc_html($cd_sd_results_subtitle); ?></p>
        </div>
        <div class="sd-results-grid">
          <?php craftdigitally_render_case_study_grid($cd_sd_results_count ?: 6, 'standard', $cd_sd_results_read_more); ?>
        </div>
        <div class="sd-results-cta-wrap">
          <?php
          $case_studies_page = get_page_by_path('case-studies');
          $case_studies_link = $case_studies_page ? get_permalink($case_studies_page) : home_url('/case-studies/');
          ?>
          <a href="<?php echo esc_url($case_studies_link); ?>" class="btn btn-outline sd-results-view-all"><?php echo esc_html($cd_sd_results_view_all); ?></a>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- Detailed Services Section -->
    <?php if (!empty($cd_sd_services_title) || !empty($cd_sd_services_items)): ?>
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
              <p class="sd-service-item-desc"><?php echo esc_html(isset($it['desc']) ? $it['desc'] : ''); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- Our Proven Process -->
    <?php if (!empty($cd_sd_process_title) || !empty($cd_sd_process_steps)): ?>
    <section class="sd-process">
      <div class="sd-container">
        <div class="sd-section-header sd-section-header--center">
          <h2 class="sd-section-title"><?php echo esc_html($cd_sd_process_title); ?></h2>
          <p class="sd-section-subtitle"><?php echo esc_html($cd_sd_process_subtitle); ?></p>
        </div>
        <div class="sd-process-list">
          <?php foreach ($cd_sd_process_steps as $s): ?>
            <div class="sd-process-step">
              <span class="sd-process-num"><?php echo esc_html(isset($s['number']) ? $s['number'] : ''); ?></span>
              <div class="sd-process-content">
                <h3 class="sd-process-title"><?php echo esc_html(isset($s['title']) ? $s['title'] : ''); ?></h3>
                <p class="sd-process-desc"><?php echo esc_html(isset($s['desc']) ? $s['desc'] : ''); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- Testimonials Section -->
    <?php get_template_part('template-parts/sections/testimonial-slider-section'); ?>

    <!-- FAQs Section -->
    <?php if (!empty($cd_sd_faq_title) || !empty($cd_sd_faq_items)): ?>
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
    <?php endif; ?>

    <!-- CTA Section -->
    <?php if (!empty($cd_sd_cta_title)): ?>
    <section class="cta-section" id="contact">
      <div class="container">
        <div class="cta-header">
          <h2 class="cta-title"><?php echo esc_html($cd_sd_cta_title); ?></h2>
          <p class="cta-subtitle">
            <?php echo esc_html($cd_sd_cta_subtitle); ?>
          </p>
        </div>
        <div class="cta-form-wrapper">
          <form method="post" action="#" class="cta-form">
            <div class="cta-form-row">
              <input type="text" name="name" placeholder="<?php echo esc_attr($cd_sd_cta_name_ph); ?>" required class="cta-input" />
              <input type="tel" name="phone" placeholder="<?php echo esc_attr($cd_sd_cta_phone_ph); ?>" required class="cta-input" />
            </div>
            <input type="email" name="email" placeholder="<?php echo esc_attr($cd_sd_cta_email_ph); ?>" required class="cta-input" />
            <input type="text" name="service" placeholder="<?php echo esc_attr($cd_sd_cta_service_ph); ?>" class="cta-input" />
            <textarea name="message" placeholder="<?php echo esc_attr($cd_sd_cta_message_ph); ?>" rows="5" class="cta-input cta-textarea"></textarea>
            <button type="submit" class="btn btn-outline cta-submit"><?php echo esc_html($cd_sd_cta_submit); ?></button>
          </form>
        </div>
      </div>
    </section>
    <?php endif; ?>
  </div>
</main>

<?php
get_footer();
?>
