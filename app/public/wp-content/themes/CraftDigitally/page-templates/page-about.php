<?php
/**
 * Template Name: About Us Page
 * Template Post Type: page
 * 
 * @package CraftDigitally
 */

get_header();

// ACF overrides (falls back to current hardcoded output to avoid any UI/text/image changes).
$cd_about_hero_kicker = craftdigitally_get_acf('about_hero_kicker', 'Driving Local Growth Through Smart SEO Strategies');
$cd_about_hero_subhead = craftdigitally_get_acf('about_hero_subhead', 'Led by SEO experts with years of hands-on experience, Craft Digitally delivers ethical, data-driven Local SEO that produces real business results.');
$cd_about_hero_cta_label = craftdigitally_get_acf('about_hero_cta_label', 'Book a Free Consult');
$cd_about_hero_cta_url = craftdigitally_get_acf('about_hero_cta_url', home_url('/contact'));

$cd_about_growth_title = craftdigitally_get_acf('about_growth_title', 'Growth-focused digital marketing and web solutions…');
$cd_about_growth_intro_1 = craftdigitally_get_acf('about_growth_intro_1', 'CraftDigitally is a results-driven digital marketing agency based in Ahmedabad, Gujarat, helping businesses grow through strategic online visibility, strong brand presence, and measurable performance.');
$cd_about_growth_intro_2 = craftdigitally_get_acf('about_growth_intro_2', 'We build digital strategies that connect business goals to outcomes such as more qualified traffic, better leads, and higher conversions.');
$cd_about_growth_trust_label = craftdigitally_get_acf('about_growth_trust_label', 'Trust strip (small badges under hero):');
$cd_about_growth_trust_items = craftdigitally_get_acf_array('about_growth_trust_items', array(
  array('strong' => '85%+', 'text' => 'Clients Achieve ROI Within 6 Months'),
  array('strong' => '100+', 'text' => 'Keywords Ranked on First Pages'),
  array('strong' => '', 'text' => 'Client Growth Stories from Ahmedabad & Beyond'),
));
$cd_about_growth_image = craftdigitally_get_acf_image_url('about_growth_image', get_template_directory_uri() . '/assets/images/bookimage.png');
$cd_about_growth_image_alt = craftdigitally_get_acf('about_growth_image_alt', 'Notebook and glasses on a desk');

$cd_about_who_title = craftdigitally_get_acf('about_who_title', 'Who we are');
$cd_about_who_text = craftdigitally_get_acf('about_who_text', 'CraftDigitally is a full-service digital marketing partner that blends strategy, creativity, and performance. We start by understanding your business goals, then design and execute digital solutions that match your audience and your market. Our team works across SEO, content, social media, paid campaigns, web development, and analytics. The focus stays the same: clarity, consistency, and results you can measure.');

$cd_about_what_title = craftdigitally_get_acf('about_what_title', 'What we do');
$cd_about_what_subtitle = craftdigitally_get_acf('about_what_subtitle', 'Discover how strategic Local SEO helps your business attract nearby customers, boost visibility on Google, and drive real-world results.');
$cd_about_services_cards = craftdigitally_get_acf_array('about_services_cards', array(
  array('icon' => get_template_directory_uri() . '/assets/images/SEO.png', 'icon_alt' => 'Search Engine Optimisation', 'icon_w' => '17', 'icon_h' => '22', 'title' => 'Search Engine Optimisation (SEO)', 'desc' => ' Improve organic visibility, rankings, and qualified traffic.'),
  array('icon' => get_template_directory_uri() . '/assets/images/SMM.png', 'icon_alt' => 'Social media marketing', 'icon_w' => '25', 'icon_h' => '12.5', 'title' => 'Social media marketing', 'desc' => ' Build audience trust and consistent engagement.'),
  array('icon' => get_template_directory_uri() . '/assets/images/CM.png', 'icon_alt' => 'Content marketing', 'icon_w' => '27.5', 'icon_h' => '27.5', 'title' => 'Content marketing', 'desc' => 'Publish content that matters to your buyers and supports conversions.'),
  array('icon' => get_template_directory_uri() . '/assets/images/APM.png', 'icon_alt' => 'Advertising and paid media', 'icon_w' => '28', 'icon_h' => '28', 'title' => 'Advertising and paid media', 'desc' => 'Run targeted campaigns across Google and social platforms.'),
  array('icon' => get_template_directory_uri() . '/assets/images/WDD.png', 'icon_alt' => 'Web design and development', 'icon_w' => '27', 'icon_h' => '27', 'title' => 'Web design and development', 'desc' => 'Build fast, responsive, conversion-friendly websites.'),
  array('icon' => get_template_directory_uri() . '/assets/images/LTSEO.png', 'icon_alt' => 'Local and technical SEO', 'icon_w' => '26', 'icon_h' => '26', 'title' => 'Local and technical SEO', 'desc' => 'Strengthen local presence and improve crawlability and performance.'),
));

$cd_about_trust_title = craftdigitally_get_acf('about_trust_title', 'How we build trust');
$cd_about_trust_subtitle = craftdigitally_get_acf('about_trust_subtitle', 'Trust is earned through honest work, consistent results and transparent expectations. At CraftDigitally, here’s how we build it.');
$cd_about_trust_cards = craftdigitally_get_acf_array('about_trust_cards', array(
  array('icon' => get_template_directory_uri() . '/assets/images/WDD.png', 'icon_alt' => '', 'icon_w' => '32', 'icon_h' => '32', 'title' => 'Measurable outcomes', 'desc' => 'Focusing on measurable outcomes, not vanity metrics.'),
  array('icon' => get_template_directory_uri() . '/assets/images/CKPR.png', 'icon_alt' => '', 'icon_w' => '32', 'icon_h' => '16', 'title' => 'Clear KPIs and reporting', 'desc' => 'Setting clear KPIs and sharing progress through simple reporting.'),
  array('icon' => get_template_directory_uri() . '/assets/images/EP.png', 'icon_alt' => '', 'icon_w' => '20', 'icon_h' => '27', 'title' => 'Ethical practices', 'desc' => 'Using ethical, sustainable practices that hold up long term.'),
  array('icon' => get_template_directory_uri() . '/assets/images/CC.png', 'icon_alt' => '', 'icon_w' => '26', 'icon_h' => '25', 'title' => 'Clear communication', 'desc' => 'Communicating clearly, without hidden tactics or vague promises.'),
));

$cd_about_results_title = craftdigitally_get_acf('about_results_title', 'Results Our Clients Have Achieved');
$cd_about_results_subtitle = craftdigitally_get_acf('about_results_subtitle', "Our SEO strategies are built to compound over time – they drive measurable business outcomes.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success.");
$cd_about_results_count = (int) craftdigitally_get_acf('about_results_count', 6);
$cd_about_results_read_more_label = craftdigitally_get_acf('about_results_read_more_label', 'Read Full Story');
$cd_about_results_view_all_label = craftdigitally_get_acf('about_results_view_all_label', 'View all Case Studies');

$cd_about_values_title = craftdigitally_get_acf('about_values_title', 'Our Values');
$cd_about_values_items = craftdigitally_get_acf_array('about_values_items', array(
  array('title' => 'Clarity', 'desc' => 'We simplify the plan so you know what is happening and why.'),
  array('title' => 'Integrity', 'desc' => 'Recommendations are honest and aligned to business goals.'),
  array('title' => 'Collaboration', 'desc' => 'We work closely as a partner, not a vendor.'),
  array('title' => 'Dedication', 'desc' => 'We treat performance and quality as non-negotiable.'),
));

$cd_about_choose_title = craftdigitally_get_acf('about_choose_title', 'Why choose CraftDigitally');
$cd_about_choose_points = craftdigitally_get_acf_array('about_choose_points', array(
  array('text' => 'Strategy built around your goals and your audience.'),
  array('text' => 'Strong execution across content, SEO, web, and ads.'),
  array('text' => 'Transparent reporting and accountability.'),
  array('text' => 'Long-term focus, not quick hacks.'),
));

$cd_about_mission_title = craftdigitally_get_acf('about_mission_title', 'Our mission');
$cd_about_mission_desc = craftdigitally_get_acf('about_mission_desc', 'CraftDigitally’s mission is to help businesses grow with clarity-driven marketing, measurable strategy, and consistent execution. We aim to build a digital presence that is easy to understand, trusted by audiences, and strong enough to perform over time.');
?>

<main id="main-content" class="about-page">
  <div class="main">
    <!-- Hero Section -->
    <div class="hero">
      <div class="container hero-service-container">
        <div class="headline-subhead">
          <p class="text-wrapper"><?php echo esc_html($cd_about_hero_kicker); ?></p>
          <p class="div">
            <?php echo esc_html($cd_about_hero_subhead); ?>
          </p>
        </div>
        <a href="<?php echo esc_url($cd_about_hero_cta_url); ?>" class="button"><div class="text-wrapper-2"><?php echo esc_html($cd_about_hero_cta_label); ?></div></a>
      </div>
    </div>

    <!-- Growth-focused digital marketing & web solutions -->
    <section class="about-growth-section">
      <div class="container">
        <div class="about-growth-layout">
          <div class="about-growth-copy">
            <h2 class="about-growth-title"><?php echo esc_html($cd_about_growth_title); ?></h2>
            <p class="about-growth-intro">
              <?php echo esc_html($cd_about_growth_intro_1); ?>
            </p>
            <div class="about-growth-content">
              <p class="about-growth-intro">
                <?php echo esc_html($cd_about_growth_intro_2); ?>
              </p>
              <div class="about-growth-trust-strip">
                <p class="about-growth-trust-label"><?php echo esc_html($cd_about_growth_trust_label); ?></p>
                <ul class="about-growth-list">
                  <?php foreach ($cd_about_growth_trust_items as $item): ?>
                    <li>
                      <?php if (!empty($item['strong'])): ?><strong><?php echo esc_html($item['strong']); ?></strong> <?php endif; ?>
                      <?php echo esc_html(isset($item['text']) ? $item['text'] : ''); ?>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          </div>
          <div class="about-growth-media">
            <img src="<?php echo esc_url($cd_about_growth_image); ?>" alt="<?php echo esc_attr($cd_about_growth_image_alt); ?>" />
          </div>
        </div>
      </div>
    </section>

    <!-- Who we are -->
    <section class="about-who-we-are-section">
      <div class="container">
        <div class="about-who-we-are-content">
          <h2 class="about-who-we-are-title"><?php echo esc_html($cd_about_who_title); ?></h2>
          <p class="about-who-we-are-text">
            <?php echo esc_html($cd_about_who_text); ?>
          </p>
          <!-- <p class="about-who-we-are-text">
            We partner closely with founders, in-house marketers, and small teams to clarify priorities, simplify SEO, and ship work that actually moves the needle. Our relationships are built on honest advice, clear expectations, and long-term collaboration.
          </p> -->
        </div>
      </div>
    </section>

    <!-- What we do (dark section with services) -->
    <section class="about-what-we-do-section">
      <div class="container">
        <div class="about-what-we-do-header">
          <h2 class="about-what-we-do-title"><?php echo esc_html($cd_about_what_title); ?></h2>
          <p class="about-what-we-do-subtitle">
            <?php echo esc_html($cd_about_what_subtitle); ?>
          </p>
        </div>
        <div class="about-what-we-do-grid">
          <?php foreach ($cd_about_services_cards as $card): ?>
            <?php
              $icon_url = '';
              if (isset($card['icon']) && !empty($card['icon'])) {
                if (is_array($card['icon']) && !empty($card['icon']['url'])) {
                  $icon_url = $card['icon']['url'];
                } elseif (is_numeric($card['icon'])) {
                  $icon_url = wp_get_attachment_image_url((int) $card['icon'], 'full');
                } elseif (is_string($card['icon'])) {
                  $icon_url = $card['icon'];
                }
              }
              $icon_alt = isset($card['icon_alt']) ? $card['icon_alt'] : '';
              $icon_w = isset($card['icon_w']) ? $card['icon_w'] : '';
              $icon_h = isset($card['icon_h']) ? $card['icon_h'] : '';
              $title = isset($card['title']) ? $card['title'] : '';
              $desc = isset($card['desc']) ? $card['desc'] : '';
            ?>
            <div class="about-service-card">
              <div class="about-service-icon-wrapper">
                <div class="about-service-icon-inner">
                  <img src="<?php echo esc_url($icon_url); ?>" style="width: <?php echo esc_attr($icon_w); ?>px; height: <?php echo esc_attr($icon_h); ?>px;" alt="<?php echo esc_attr($icon_alt); ?>" class="about-service-icon" />
                </div>
              </div>
              <div class="about-service-content">
                <h3 class="about-service-title"><?php echo esc_html($title); ?></h3>
                <div class="about-service-divider" role="presentation"></div>
                <p class="about-service-desc"><?php echo esc_html($desc); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- How we build trust -->
    <section class="about-trust-section">
      <div class="container">
        <div class="about-trust-layout">
          <div class="about-trust-content">
            <div class="about-trust-header">
          <h2 class="about-trust-title"><?php echo esc_html($cd_about_trust_title); ?></h2>
          <p class="about-trust-subtitle">
            <?php echo esc_html($cd_about_trust_subtitle); ?>
          </p>
        </div>
        <div class="about-trust-grid">
          <?php foreach ($cd_about_trust_cards as $card): ?>
            <?php
              $icon_url = '';
              if (isset($card['icon']) && !empty($card['icon'])) {
                if (is_array($card['icon']) && !empty($card['icon']['url'])) {
                  $icon_url = $card['icon']['url'];
                } elseif (is_numeric($card['icon'])) {
                  $icon_url = wp_get_attachment_image_url((int) $card['icon'], 'full');
                } elseif (is_string($card['icon'])) {
                  $icon_url = $card['icon'];
                }
              }
              $icon_alt = isset($card['icon_alt']) ? $card['icon_alt'] : '';
              $icon_w = isset($card['icon_w']) ? $card['icon_w'] : '';
              $icon_h = isset($card['icon_h']) ? $card['icon_h'] : '';
              $title = isset($card['title']) ? $card['title'] : '';
              $desc = isset($card['desc']) ? $card['desc'] : '';
            ?>
            <div class="about-trust-card">
              <div class="about-trust-card-icon">
                <img src="<?php echo esc_url($icon_url); ?>" style="width: <?php echo esc_attr($icon_w); ?>px; height: <?php echo esc_attr($icon_h); ?>px;" alt="<?php echo esc_attr($icon_alt); ?>" class="about-trust-icon" aria-hidden="true" />
              </div>
              <div class="about-trust-card-content">
                <h3 class="about-trust-card-title"><?php echo esc_html($title); ?></h3>
                <p class="about-trust-card-desc"><?php echo esc_html($desc); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Client Results Section (same grid as case studies) -->
    <section class="case-study-section">
      <div class="container">
        <div class="case-study-header">
          <h2 class="case-study-title"><?php echo esc_html($cd_about_results_title); ?></h2>
          <p class="case-study-subtitle">
            <?php echo wp_kses_post($cd_about_results_subtitle); ?>
          </p>  
        </div>

        <div class="case-study-grid">
          <?php craftdigitally_render_case_study_grid($cd_about_results_count ?: 6, 'standard', $cd_about_results_read_more_label); ?>
        </div>

        <div class="case-study-view-all">
          <?php
          $case_studies_page = get_page_by_path('case-studies');
          $case_studies_link = $case_studies_page ? get_permalink($case_studies_page) : home_url('/');
          ?>
          <a href="<?php echo esc_url($case_studies_link); ?>" class="btn btn-outline case-study-view-all-btn"><?php echo esc_html($cd_about_results_view_all_label); ?></a>
        </div>
      </div>
    </section>

    <!-- Values Section -->
    <section class="about-values-section">
      <div class="container">
        <div class="about-values-header">
          <h2 class="about-values-title"><?php echo esc_html($cd_about_values_title); ?></h2>
        </div>
        <div class="about-values-grid">
          <?php foreach ($cd_about_values_items as $v): ?>
            <div class="about-value-item">
              <div class="about-value-frame">
                <h3 class="about-value-title"><?php echo esc_html(isset($v['title']) ? $v['title'] : ''); ?></h3>
                <p class="about-value-desc"><?php echo esc_html(isset($v['desc']) ? $v['desc'] : ''); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Why choose CraftDigitally -->
    <section class="about-why-choose-section">
      <div class="container">
        <div class="about-why-choose-header">
          <h2 class="about-why-choose-title"><?php echo esc_html($cd_about_choose_title); ?></h2>
        </div>
        <div class="about-why-choose-points">
          <?php foreach ($cd_about_choose_points as $p): ?>
            <div class="about-why-choose-point">
              <span class="about-why-choose-icon" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 6L9 17l-5-5" stroke="#4A2C7D" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </span>
              <p class="about-why-choose-text"><?php echo esc_html(isset($p['text']) ? $p['text'] : ''); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Our mission -->
    <section class="about-mission-section">
      <div class="container">
        <div class="about-mission-content">
          <h2 class="about-mission-title"><?php echo esc_html($cd_about_mission_title); ?></h2>
          <p class="about-mission-desc">
          <?php echo esc_html($cd_about_mission_desc); ?></p>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <!-- <section class="about-cta-section">
      <div class="container">
        <div class="about-cta-content">
          <h2 class="about-cta-title">Ready to Grow Your Business?</h2>
          <p class="about-cta-subtitle">Let's work together to achieve your digital marketing goals.</p>
          <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-outline about-cta-btn">Get in Touch</a>
        </div>
      </div>
    </section> -->
  </div>
</main>

<?php
get_footer();
