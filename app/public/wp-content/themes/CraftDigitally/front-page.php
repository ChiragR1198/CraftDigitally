<?php
/**
 * Home Page Template - All sections in one file
 *
 * @package CraftDigitally
 */

get_header();

// ACF overrides (everything falls back to the current hardcoded output to avoid any UI/text/image changes).
$cd_home_hero_title = craftdigitally_get_acf('home_hero_title', 'Stand out online with clarity,<br />strategy and confidence');
$cd_home_hero_subtitle = craftdigitally_get_acf('home_hero_subtitle', "We're a leading digital marketing agency that helps businesses in Ahmedabad turn SEO, web development, and digital marketing into powerful growth channels.");
$cd_home_hero_cta_label = craftdigitally_get_acf('home_hero_cta_label', 'Book a Free Consult');
$cd_home_hero_cta_link = craftdigitally_get_acf('home_hero_cta_link', '#contact');

$cd_home_cs_title = craftdigitally_get_acf('home_case_studies_title', 'Results Our Clients Have Achieved');
$cd_home_cs_subtitle = craftdigitally_get_acf('home_case_studies_subtitle', "Our SEO strategies go beyond rankings — they deliver measurable business growth.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success.");
$cd_home_cs_count = (int) craftdigitally_get_acf('home_case_studies_count', 6);
$cd_home_cs_view_all_label = craftdigitally_get_acf('home_case_studies_view_all_label', 'View all Case Studies');
// Default URL to case studies landing PAGE (we intentionally do not use the CPT archive).
$cd_home_cs_landing_pages = get_pages(array(
  'meta_key' => '_wp_page_template',
  'meta_value' => 'page-templates/page-case-study-landing.php',
  'number' => 1,
  'post_status' => 'publish',
));
$cd_home_cs_view_all_url_default = !empty($cd_home_cs_landing_pages)
  ? get_permalink($cd_home_cs_landing_pages[0]->ID)
  : (get_page_by_path('case-studies') ? get_permalink(get_page_by_path('case-studies')->ID) : home_url('/case-studies/'));
$cd_home_cs_view_all_url = craftdigitally_get_acf('home_case_studies_view_all_url', $cd_home_cs_view_all_url_default);

// NOTE:
// Case studies should use their native permalinks:
// - Archive: /case-studies/
// - Single:  /case-studies/{post-name}/

// Query case_study posts for home page
$cd_home_cs_posts_status = current_user_can('edit_posts')
  ? array('publish', 'draft', 'pending', 'future', 'private')
  : array('publish');

$cd_home_cs_posts_q = new WP_Query(array(
  'post_type' => 'case_study',
  'post_status' => $cd_home_cs_posts_status,
  'posts_per_page' => $cd_home_cs_count ?: 6,
  'ignore_sticky_posts' => true,
));

$cd_home_testimonials_title = craftdigitally_get_acf('home_testimonials_title', 'What Clients Say About Working With Us');
$cd_home_testimonials_subtitle = craftdigitally_get_acf('home_testimonials_subtitle', 'We believe great partnerships lead to great outcomes.');
$cd_home_testimonials_desc = craftdigitally_get_acf('home_testimonials_description', 'Discover why clients trust us to handle their SEO and how our collaboration drives measurable impact.');
$cd_home_testimonials_default = function_exists('craftdigitally_get_default_testimonials') ? craftdigitally_get_default_testimonials() : array();
$cd_home_testimonials = craftdigitally_get_acf_array('home_testimonials', $cd_home_testimonials_default);

$cd_home_process_title = craftdigitally_get_acf('home_process_title', 'Our Proven Process');
$cd_home_process_subtitle = craftdigitally_get_acf('home_process_subtitle', 'A clear, results-driven approach designed to help your business grow step by step.');
$cd_home_process_default = function_exists('craftdigitally_get_default_process_steps') ? craftdigitally_get_default_process_steps() : array();
$cd_home_process_steps = craftdigitally_get_acf_array('home_process_steps', $cd_home_process_default);

$cd_home_services_title = craftdigitally_get_acf('home_services_title', 'Our Services');
$cd_home_services_subtitle = craftdigitally_get_acf('home_services_subtitle', "Whether you're a startup in Chandkheda or an established brand in Prahladnagar, we've got your back. Your Roadmap to More Traffic, Leads & Sales");
$cd_home_services_default = function_exists('craftdigitally_get_default_services') ? craftdigitally_get_default_services() : array();
$cd_home_services = craftdigitally_get_acf_array('home_services', $cd_home_services_default);

$cd_home_why_choose_title = craftdigitally_get_acf('home_why_choose_title', 'Why Choose Craft Digitally?');
$cd_home_why_choose_text = craftdigitally_get_acf('home_why_choose_text', 'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad.');
$cd_home_why_choose_image = craftdigitally_get_acf_image_url('home_why_choose_image', get_template_directory_uri() . '/assets/images/bookimage.png');
$cd_home_why_choose_points_default = function_exists('craftdigitally_get_default_why_choose_points') ? craftdigitally_get_default_why_choose_points() : array('Local Ahmedabad Expertise', 'Proven Track Record', 'Transparent Reporting', 'Affordable Pricing');
$cd_home_why_choose_points = craftdigitally_get_acf_array('home_why_choose_points', array_map(function ($t) { return array('text' => $t); }, $cd_home_why_choose_points_default));
$cd_home_why_choose_btn_label = craftdigitally_get_acf('home_why_choose_button_label', 'Contact Us');
$cd_home_why_choose_btn_link = craftdigitally_get_acf('home_why_choose_button_link', '#contact');

$cd_home_cta_title = craftdigitally_get_acf('home_cta_title', 'Ready to Dominate Your Competition?');
$cd_home_cta_subtitle = craftdigitally_get_acf('home_cta_subtitle', 'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad');
$cd_home_cta_name_ph = craftdigitally_get_acf('home_cta_name_placeholder', 'Name');
$cd_home_cta_phone_ph = craftdigitally_get_acf('home_cta_phone_placeholder', 'Phone');
$cd_home_cta_email_ph = craftdigitally_get_acf('home_cta_email_placeholder', 'Email');
$cd_home_cta_service_ph = craftdigitally_get_acf('home_cta_service_placeholder', 'Service');
$cd_home_cta_message_ph = craftdigitally_get_acf('home_cta_message_placeholder', 'Message');
$cd_home_cta_btn_label = craftdigitally_get_acf('home_cta_button_label', "Let's Connect");
?>

<main id="main-content">
  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container hero-container">
      <div class="hero-content fade-in">
        <h1 class="hero-title"><?php echo wp_kses_post($cd_home_hero_title); ?></h1>
        <p class="hero-subtitle">
          <?php echo esc_html($cd_home_hero_subtitle); ?>
        </p>
        <a href="<?php echo esc_url($cd_home_hero_cta_link); ?>" class="btn btn-outline"><?php echo esc_html($cd_home_hero_cta_label); ?></a>
      </div>
    </div>
  </section>

  <!-- Case Study Section -->
  <section class="case-study-section">
    <div class="container">
      <div class="case-study-header">
        <h2 class="case-study-title"><?php echo esc_html($cd_home_cs_title); ?></h2>
        <p class="case-study-subtitle">
          <?php echo wp_kses_post($cd_home_cs_subtitle); ?>
        </p>
      </div>

      <div class="case-study-grid">
        <?php
        // Display dynamic case studies from custom post type
        if ($cd_home_cs_posts_q->have_posts()) {
          while ($cd_home_cs_posts_q->have_posts()) {
            $cd_home_cs_posts_q->the_post();
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
            
            $detail_url = get_permalink($cs_id);
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
              <a href="<?php echo esc_url($detail_url); ?>" class="btn btn-outline case-study-cta">Read Full Story</a>
            </div>
            <?php
          }
          wp_reset_postdata();
        } else {
          // Fallback to static grid if no posts
          craftdigitally_render_case_study_grid($cd_home_cs_count ?: 6, 'standard', 'Read Full Story');
        }
        ?>
      </div>

      <div class="case-study-view-all">
        <a href="<?php echo esc_url($cd_home_cs_view_all_url); ?>" class="btn btn-outline case-study-view-all-btn"><?php echo esc_html($cd_home_cs_view_all_label); ?></a>
      </div>
    </div>
  </section>

  <!-- Testimonials & Process Section -->
  <section class="testimonials-process-section">
    <div class="container testimonials-container">
      <!-- Testimonials Section -->
      <div class="testimonials-header">
        <h2 class="testimonials-title"><?php echo esc_html($cd_home_testimonials_title); ?></h2>
        <p class="testimonials-subtitle">
          <?php echo esc_html($cd_home_testimonials_subtitle); ?>
        </p>
        <p class="testimonials-description">
          <?php echo esc_html($cd_home_testimonials_desc); ?>
        </p>
      </div>

      <div class="testimonials-grid">
        <?php foreach ($cd_home_testimonials as $t): ?>
          <?php
            $t_title = isset($t['title']) ? $t['title'] : '';
            $t_quote = isset($t['quote']) ? $t['quote'] : '';
            $t_name = isset($t['name']) ? $t['name'] : '';

            $t_role = isset($t['role']) ? $t['role'] : (isset($t['title_role']) ? $t['title_role'] : '');
            $t_company = isset($t['company']) ? $t['company'] : '';
            $t_role_line = $t_role;
            if (!empty($t_company)) {
              $t_role_line = rtrim($t_role_line, ', ');
              $t_role_line = $t_role_line ? ($t_role_line . ', ' . $t_company) : $t_company;
            }

            $t_avatar_url = '';
            if (isset($t['avatar']) && !empty($t['avatar'])) {
              if (is_array($t['avatar']) && !empty($t['avatar']['url'])) {
                $t_avatar_url = $t['avatar']['url'];
              } elseif (is_numeric($t['avatar'])) {
                $t_avatar_url = wp_get_attachment_image_url((int) $t['avatar'], 'full');
              } elseif (is_string($t['avatar'])) {
                $t_avatar_url = $t['avatar'];
              }
            }
            if (empty($t_avatar_url) && isset($t['image']) && !empty($t['image'])) {
              $t_avatar_url = get_template_directory_uri() . '/assets/images/' . $t['image'];
            }
          ?>
        <div class="testimonial-card">
          <div class="testimonial-content">
              <p class="testimonial-title"><?php echo esc_html($t_title); ?></p>
              <p class="testimonial-quote"><?php echo esc_html($t_quote); ?></p>
          </div>
          <div class="testimonial-author">
              <img src="<?php echo esc_url($t_avatar_url); ?>" alt="<?php echo esc_attr($t_name); ?>" class="testimonial-avatar" />
            <div class="testimonial-author-info">
                <div class="testimonial-name"><?php echo esc_html($t_name); ?></div>
                <div class="testimonial-role"><?php echo esc_html($t_role_line); ?></div>
          </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Process Section -->
      <div class="process-header">
        <h2 class="process-title"><?php echo esc_html($cd_home_process_title); ?></h2>
        <p class="process-subtitle">
          <?php echo esc_html($cd_home_process_subtitle); ?>
        </p>
      </div>

      <div class="process-steps">
        <?php foreach ($cd_home_process_steps as $s): ?>
        <div class="process-step">
            <div class="process-number"><?php echo esc_html(isset($s['number']) ? $s['number'] : ''); ?></div>
          <div class="process-content">
              <h3 class="process-step-title"><?php echo esc_html(isset($s['title']) ? $s['title'] : ''); ?></h3>
              <p class="process-step-description"><?php echo esc_html(isset($s['description']) ? $s['description'] : ''); ?></p>
        </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="services-section">
    <div class="container">
      <div class="services-header">
        <h2 class="services-title"><?php echo esc_html($cd_home_services_title); ?></h2>
        <p class="services-subtitle">
          <?php echo esc_html($cd_home_services_subtitle); ?>
        </p>
      </div>

      <div class="services-grid">
        <?php foreach ($cd_home_services as $svc): ?>
          <?php
            $svc_title = isset($svc['title']) ? $svc['title'] : '';
            $svc_desc = isset($svc['description']) ? $svc['description'] : '';
            $svc_icon_url = '';
            $svc_icon_alt = $svc_title ? ($svc_title . ' icon') : 'Service icon';

            if (isset($svc['icon']) && !empty($svc['icon'])) {
              if (is_array($svc['icon']) && !empty($svc['icon']['url'])) {
                $svc_icon_url = $svc['icon']['url'];
              } elseif (is_numeric($svc['icon'])) {
                $svc_icon_url = wp_get_attachment_image_url((int) $svc['icon'], 'full');
              } elseif (is_string($svc['icon'])) {
                // Default-data provides a filename; ACF might provide a URL.
                $svc_icon_url = (strpos($svc['icon'], 'http') === 0)
                  ? $svc['icon']
                  : (get_template_directory_uri() . '/assets/images/' . $svc['icon']);
              }
            }
          ?>
        <div class="service-card">
          <div class="service-icon-wrapper">
              <img src="<?php echo esc_url($svc_icon_url); ?>" alt="<?php echo esc_attr($svc_icon_alt); ?>" class="service-icon" />
          </div>
            <h3 class="service-title"><?php echo esc_html($svc_title); ?></h3>
          <hr class="service-divider" />
            <p class="service-description"><?php echo esc_html($svc_desc); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Why Choose Section -->
  <section class="why-choose-section">
    <div class="why-choose-inner">
      <div class="why-choose-visual">
        <img src="<?php echo esc_url($cd_home_why_choose_image); ?>" alt="Notebook with pen" loading="lazy" />
      </div>
      <div class="why-choose-content">
        <h2><?php echo esc_html($cd_home_why_choose_title); ?></h2>
        <p><?php echo esc_html($cd_home_why_choose_text); ?></p>
        <ul>
          <?php foreach ($cd_home_why_choose_points as $p): ?>
            <li><?php echo esc_html(isset($p['text']) ? $p['text'] : ''); ?></li>
          <?php endforeach; ?>
        </ul>
        <a href="<?php echo esc_url($cd_home_why_choose_btn_link); ?>" class="btn why-choose-button"><?php echo esc_html($cd_home_why_choose_btn_label); ?></a>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta-section" id="contact">
    <div class="container">
      <div class="cta-header">
        <h2 class="cta-title"><?php echo esc_html($cd_home_cta_title); ?></h2>
        <p class="cta-subtitle">
          <?php echo esc_html($cd_home_cta_subtitle); ?>
        </p>
      </div>

      <div class="cta-form-wrapper">
        <form method="post" action="#" class="cta-form">
          <div class="cta-form-row">
            <input type="text" name="name" placeholder="<?php echo esc_attr($cd_home_cta_name_ph); ?>" required class="cta-input" />
            <input type="tel" name="phone" placeholder="<?php echo esc_attr($cd_home_cta_phone_ph); ?>" required class="cta-input" />
          </div>
          <input type="email" name="email" placeholder="<?php echo esc_attr($cd_home_cta_email_ph); ?>" required class="cta-input" />
          <input type="text" name="service" placeholder="<?php echo esc_attr($cd_home_cta_service_ph); ?>" class="cta-input" />
          <textarea name="message" placeholder="<?php echo esc_attr($cd_home_cta_message_ph); ?>" rows="5" class="cta-input cta-textarea"></textarea>
          <button type="submit" class="btn btn-outline cta-submit"><?php echo esc_html($cd_home_cta_btn_label); ?></button>
        </form>
      </div>
    </div>
  </section>
</main>

<?php
get_footer();
