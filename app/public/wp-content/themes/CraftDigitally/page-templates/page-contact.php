<?php
/**
 * Template Name: Contact Page
 * Template Post Type: page
 * 
 * @package CraftDigitally
 */

get_header();

// ACF overrides (falls back to current hardcoded output to avoid any UI/text/image changes).
$cd_contact_hero_kicker = craftdigitally_get_acf('contact_hero_kicker', "Let's Start a Conversation");
$cd_contact_hero_subhead = craftdigitally_get_acf('contact_hero_subhead', "Ready to transform your digital presence? We're here to help. Reach out to discuss your project or ask any questions about our services.");

$cd_contact_form_title = craftdigitally_get_acf('contact_form_title', 'Send us a Message');
$cd_contact_form_subtitle = craftdigitally_get_acf('contact_form_subtitle', "Fill out the form below and we'll get back to you within 24 hours.");
$cd_contact_form_name_label = craftdigitally_get_acf('contact_form_name_label', 'Full Name*');
$cd_contact_form_name_ph = craftdigitally_get_acf('contact_form_name_placeholder', 'Enter your full name');
$cd_contact_form_email_label = craftdigitally_get_acf('contact_form_email_label', 'Email Address*');
$cd_contact_form_email_ph = craftdigitally_get_acf('contact_form_email_placeholder', 'you@example.com');
$cd_contact_form_phone_label = craftdigitally_get_acf('contact_form_phone_label', 'Phone Number');
$cd_contact_form_phone_ph = craftdigitally_get_acf('contact_form_phone_placeholder', '+91 XXXXX XXXXX');
$cd_contact_form_service_label = craftdigitally_get_acf('contact_form_service_label', 'Service Interest');
$cd_contact_form_service_ph = craftdigitally_get_acf('contact_form_service_placeholder', 'e.g. Local SEO');
$cd_contact_form_message_label = craftdigitally_get_acf('contact_form_message_label', 'Message*');
$cd_contact_form_message_ph = craftdigitally_get_acf('contact_form_message_placeholder', 'Tell us about your project...');
$cd_contact_form_btn = craftdigitally_get_acf('contact_form_button_label', 'Send Message');

$cd_contact_info_title = craftdigitally_get_acf('contact_info_title', 'Get in Touch');
$cd_contact_info_subtitle = craftdigitally_get_acf('contact_info_subtitle', "Have a question or ready to start your project? We'd love to hear from you.");
$cd_contact_info_email = craftdigitally_get_acf('contact_info_email', 'hello@craftdigitally.com');
$cd_contact_info_phone = craftdigitally_get_acf('contact_info_phone', '+91 979 849 6798');
$cd_contact_info_address = craftdigitally_get_acf('contact_info_address', 'North Phase, 4th Floor, D-4, Road, Motera, Ahmedabad, Gujarat 380005');
$cd_contact_info_hours = craftdigitally_get_acf('contact_info_hours', 'Monday - Friday: 9:00 AM - 6:00 PM IST');

$cd_contact_testimonials_title = craftdigitally_get_acf('contact_testimonials_title', 'What Clients Say About Working With Us');
$cd_contact_testimonials_subtitle = craftdigitally_get_acf('contact_testimonials_subtitle', 'Discover why clients trust us to handle their SEO campaigns and how our collaboration drives measurable impact.');
$cd_contact_testimonials_desc = craftdigitally_get_acf(
  'contact_testimonials_description',
  'Discover why clients trust us to handle their SEO and how our collaboration drives measurable impact.'
);
// Same data source as front-page testimonials: first 2 cards only.
$cd_home_testimonials_default = function_exists('craftdigitally_get_default_testimonials') ? craftdigitally_get_default_testimonials() : array();
$cd_home_testimonials_full      = craftdigitally_get_acf_array('home_testimonials', $cd_home_testimonials_default);
$cd_contact_testimonials_cards   = array_slice($cd_home_testimonials_full, 0, 2);

$cd_contact_results_shared = function_exists('craftdigitally_get_shared_case_study_results_data')
  ? craftdigitally_get_shared_case_study_results_data()
  : array(
    'title' => 'Results Our Clients Have Achieved',
    'subtitle' => "Our SEO strategies go beyond rankings — they deliver measurable business growth.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success.",
    'read_more_label' => 'Read Full Story',
    'view_all_label' => 'View all Case Studies',
    'view_all_url' => home_url('/case-studies/'),
  );
$cd_contact_results_title = $cd_contact_results_shared['title'];
$cd_contact_results_subtitle = $cd_contact_results_shared['subtitle'];
$cd_contact_results_count = 6;
$cd_contact_results_read_more = $cd_contact_results_shared['read_more_label'];
$cd_contact_results_view_all = $cd_contact_results_shared['view_all_label'];
$cd_contact_results_view_all_url = $cd_contact_results_shared['view_all_url'];

$cd_contact_why_work_title = craftdigitally_get_acf('contact_why_work_title', 'Why Work With Us?');
$cd_contact_why_work_subtitle = craftdigitally_get_acf('contact_why_work_subtitle', 'We combine expertise, creativity, and dedication to deliver results that matter.');
$cd_contact_why_work_cards = craftdigitally_get_acf_array('contact_why_work_cards', array(
  array('title' => 'Expert Team', 'desc' => 'Our team of experienced professionals brings years of expertise in SEO, web development, and digital marketing.'),
  array('title' => 'Proven Results', 'desc' => "We've helped numerous businesses in Ahmedabad and beyond achieve their digital marketing goals with measurable success."),
  array('title' => 'Personalized Approach', 'desc' => 'Every business is unique. We tailor our strategies to fit your specific needs and goals for maximum impact.'),
));

$cd_contact_faq_title = craftdigitally_get_acf('contact_faq_title', 'FAQs');
$cd_contact_faq_subtitle = craftdigitally_get_acf('contact_faq_subtitle', 'Get clear answers to the most common questions about our Local SEO services');
$cd_contact_faq_items = craftdigitally_get_acf_array('contact_faq_items', array(
  array('question' => 'How long does it take to see results from Local SEO?', 'answer' => 'Local SEO often shows early improvements in 2–4 months (e.g. better map visibility, more impressions). Meaningful traffic and lead growth usually build over 4–6 months and beyond, depending on competition and consistency.'),
  array('question' => 'What is the typical cost of local SEO pricing?', 'answer' => "Costs vary by scope: audits and one-off projects, ongoing monthly retainers, or full-service local SEO. We tailor packages to your goals and budget—share your objectives and we'll suggest a clear plan."),
  array('question' => 'Do you work with businesses outside Ahmedabad?', 'answer' => 'Yes. We work with local businesses across India and can support you remotely with strategy, optimisation, and reporting. Many of our clients are outside Ahmedabad.'),
  array('question' => "What's included in a local SEO audit?", 'answer' => 'We review your Google Business Profile, website pages, keywords, citations, and competitors. You get a written report with findings and prioritised next steps.'),
  array('question' => 'How do you report results?', 'answer' => "We share regular reports (e.g. monthly) with rankings, search performance, traffic, and lead metrics. You'll see dashboards and summaries that focus on what matters for your business."),
));
?>

<main id="main-content" class="contact-page">
  <div class="main">
    <!-- Hero Section -->
    <section class="hero contact-hero">
      <div class="container hero-service-container">
        <div class="headline-subhead">
          <p class="text-wrapper"><?php echo esc_html($cd_contact_hero_kicker); ?></p>
          <p class="div">
            <?php echo esc_html($cd_contact_hero_subhead); ?>
          </p>
        </div>
      </div>
    </section>

    <!-- Contact Split Section -->
    <section class="contact-split-section">
      <div class="container">
        <div class="contact-split">
          <div class="contact-card contact-card--form">
            <div class="contact-card-header">
              <h2 class="contact-card-title"><?php echo esc_html($cd_contact_form_title); ?></h2>
              <p class="contact-card-subtitle"><?php echo esc_html($cd_contact_form_subtitle); ?></p>
            </div>

            <form method="post" action="#" class="contact-form">
              <div class="form-group">
                <label for="contact-name"><?php echo esc_html($cd_contact_form_name_label); ?></label>
                <input type="text" id="contact-name" name="name" placeholder="<?php echo esc_attr($cd_contact_form_name_ph); ?>" required class="form-input" />
              </div>

              <div class="form-group">
                <label for="contact-email"><?php echo esc_html($cd_contact_form_email_label); ?></label>
                <input type="email" id="contact-email" name="email" placeholder="<?php echo esc_attr($cd_contact_form_email_ph); ?>" required class="form-input" />
              </div>

              <div class="form-group">
                <label for="contact-phone"><?php echo esc_html($cd_contact_form_phone_label); ?></label>
                <input type="tel" id="contact-phone" name="phone" placeholder="<?php echo esc_attr($cd_contact_form_phone_ph); ?>" class="form-input" />
              </div>

              <div class="form-group">
                <label for="contact-service"><?php echo esc_html($cd_contact_form_service_label); ?></label>
                <input type="text" id="contact-service" name="service" placeholder="<?php echo esc_attr($cd_contact_form_service_ph); ?>" class="form-input" />
              </div>

              <div class="form-group">
                <label for="contact-message"><?php echo esc_html($cd_contact_form_message_label); ?></label>
                <textarea id="contact-message" name="message" placeholder="<?php echo esc_attr($cd_contact_form_message_ph); ?>" rows="6" required class="form-input form-textarea"></textarea>
              </div>

              <button type="submit" class="btn btn-outline form-submit"><?php echo esc_html($cd_contact_form_btn); ?></button>
            </form>
          </div>

          <aside class="contact-card--info">
            <div class="contact-card-header">
              <h2 class="contact-card-title"><?php echo esc_html($cd_contact_info_title); ?></h2>
              <p class="contact-card-subtitle"><?php echo esc_html($cd_contact_info_subtitle); ?></p>
            </div>

            <ul class="contact-info-list">
              <li class="contact-info-item">
                <span class="contact-info-icon" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" focusable="false">
                    <path stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                  </svg>
                </span>
                <div class="contact-info-content">
                  <div class="contact-info-label">Email</div>
                  <a class="contact-info-value" href="mailto:<?php echo esc_attr($cd_contact_info_email); ?>"><?php echo esc_html($cd_contact_info_email); ?></a>
                </div>
              </li>
              <li class="contact-info-item">
                <span class="contact-info-icon" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" focusable="false">
                    <path stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                  </svg>
                </span>
                <div class="contact-info-content">
                  <div class="contact-info-label">Phone</div>
                  <a class="contact-info-value" href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $cd_contact_info_phone)); ?>"><?php echo esc_html($cd_contact_info_phone); ?></a>
                </div>
              </li>
              <li class="contact-info-item">
                <span class="contact-info-icon" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" focusable="false">
                    <path stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                  </svg>
                </span>
                <div class="contact-info-content">
                  <div class="contact-info-label">Office</div>
                  <div class="contact-info-value"><?php echo esc_html($cd_contact_info_address); ?></div>
                </div>
              </li>
              <li class="contact-info-item">
                <span class="contact-info-icon" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" focusable="false">
                    <path stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5-1.5a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
                <div class="contact-info-content">
                  <div class="contact-info-label">Business Hours</div>
                  <div class="contact-info-value"><?php echo esc_html($cd_contact_info_hours); ?></div>
                </div>
              </li>
            </ul>
          </aside>
        </div>
      </div>
    </section>

    <!-- Testimonials: same markup + classes as front-page (home) -->
    <section class="testimonials-process-section">
      <div class="container testimonials-container">
        <div class="testimonials-header">
          <h2 class="testimonials-title"><?php echo esc_html($cd_contact_testimonials_title); ?></h2>
          <p class="testimonials-subtitle"><?php echo esc_html($cd_contact_testimonials_subtitle); ?></p>
          <p class="testimonials-description"><?php echo esc_html($cd_contact_testimonials_desc); ?></p>
        </div>
        <div class="testimonials-grid">
          <?php foreach ($cd_contact_testimonials_cards as $t) : ?>
            <?php
              $t_title = isset($t['title']) ? $t['title'] : '';
              $t_quote = isset($t['quote']) ? $t['quote'] : '';
              $t_name  = isset($t['name']) ? $t['name'] : '';

              $t_role    = isset($t['role']) ? $t['role'] : (isset($t['title_role']) ? $t['title_role'] : '');
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
      </div>
    </section>

    <!-- Client Results Section -->
    <section class="case-study-section">
      <div class="container">
        <div class="case-study-header">
          <h2 class="case-study-title"><?php echo esc_html($cd_contact_results_title); ?></h2>
          <p class="case-study-subtitle">
            <?php echo wp_kses_post($cd_contact_results_subtitle); ?>
          </p>  
        </div>

        <div class="case-study-grid">
          <?php craftdigitally_render_case_study_grid($cd_contact_results_count, 'standard', $cd_contact_results_read_more); ?>
        </div>

        <div class="case-study-view-all">
          <a href="<?php echo esc_url($cd_contact_results_view_all_url); ?>" class="btn btn-outline case-study-view-all-btn"><?php echo esc_html($cd_contact_results_view_all); ?></a>
        </div>
      </div>
    </section>

    <!-- Why Work With Us Section -->
    <section class="contact-why-work-section">
      <div class="container">
        <div class="contact-why-work-header">
          <div class="contact-why-work-title-copy">
            <h2 class="contact-why-work-title"><?php echo esc_html($cd_contact_why_work_title); ?></h2>
            <p class="contact-why-work-subtitle"><?php echo esc_html($cd_contact_why_work_subtitle); ?></p>
          </div>
          <div class="contact-why-work-grid">
            <?php foreach ($cd_contact_why_work_cards as $c): ?>
            <div class="contact-why-work-card">
              <div class="contact-why-work-card-content">
                  <h3 class="contact-why-work-card-title"><?php echo esc_html(isset($c['title']) ? $c['title'] : ''); ?></h3>
                  <p class="contact-why-work-card-desc"><?php echo esc_html(isset($c['desc']) ? $c['desc'] : ''); ?></p>
            </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQs Section -->
    <section class="contact-faq-section">
      <div class="container">
        <div class="contact-faq-header">
          <h2 class="contact-faq-title"><?php echo esc_html($cd_contact_faq_title); ?></h2>
          <p class="contact-faq-subtitle"><?php echo esc_html($cd_contact_faq_subtitle); ?></p>
        </div>
        <div class="contact-faq-accordion">
          <?php foreach ($cd_contact_faq_items as $idx => $faq): ?>
            <details class="contact-faq-item" <?php echo $idx === 0 ? 'open' : ''; ?>>
              <summary class="contact-faq-trigger"><?php echo esc_html(isset($faq['question']) ? $faq['question'] : ''); ?></summary>
            <div class="contact-faq-content">
                <p><?php echo esc_html(isset($faq['answer']) ? $faq['answer'] : ''); ?></p>
            </div>
          </details>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </div>
</main>

<?php
get_footer();
