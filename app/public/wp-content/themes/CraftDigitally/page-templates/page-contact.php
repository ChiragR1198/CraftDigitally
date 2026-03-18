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
$cd_contact_testimonials_cards = craftdigitally_get_acf_array('contact_testimonials_cards', array(
  array(
    'title' => 'CraftDigitally turned what felt complicated into clarity I could finally act on',
    'quote' => "Before working with CraftDigitally, SEO felt like a fog I couldn't navigate. Their process made everything so clear and actionable. I left with not only a visible online presence but a genuine sense of confidence in how I show up digitally.",
    'avatar' => get_template_directory_uri() . '/assets/images/sarah-lin.png',
    'name' => 'Sarah Lin',
    'role' => 'Brand Strategist, Lin Studio',
  ),
  array(
    'title' => 'They helped me organize the chaos of my online world into something cohesive and professional',
    'quote' => 'I used to feel scattered across platforms, unsure of what really mattered for visibility. CraftDigitally brought structure, strategy, and calm to my digital presence. Now my website and content actually work together—and I finally feel proud to share them.',
    'avatar' => get_template_directory_uri() . '/assets/images/marcus-alvarez.png',
    'name' => 'Marcus Alvarez',
    'role' => 'Founder, Alvarez Consulting',
  ),
));

$cd_contact_results_title = craftdigitally_get_acf('contact_results_title', 'Results Our Clients Have Achieved');
$cd_contact_results_subtitle = craftdigitally_get_acf('contact_results_subtitle', "Our SEO strategies go beyond rankings — they deliver measurable business growth.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success.");
$cd_contact_results_count = (int) craftdigitally_get_acf('contact_results_count', 6);
$cd_contact_results_read_more = craftdigitally_get_acf('contact_results_read_more_label', 'Read Full Story');
$cd_contact_results_view_all = craftdigitally_get_acf('contact_results_view_all_label', 'View all Case Studies');

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
                <span class="contact-info-icon" aria-hidden="true">✉</span>
                <div class="contact-info-content">
                  <div class="contact-info-label">Email</div>
                  <a class="contact-info-value" href="mailto:<?php echo esc_attr($cd_contact_info_email); ?>"><?php echo esc_html($cd_contact_info_email); ?></a>
                </div>
              </li>
              <li class="contact-info-item">
                <span class="contact-info-icon" aria-hidden="true">☎</span>
                <div class="contact-info-content">
                  <div class="contact-info-label">Phone</div>
                  <a class="contact-info-value" href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $cd_contact_info_phone)); ?>"><?php echo esc_html($cd_contact_info_phone); ?></a>
                </div>
              </li>
              <li class="contact-info-item">
                <span class="contact-info-icon" aria-hidden="true">⌂</span>
                <div class="contact-info-content">
                  <div class="contact-info-label">Office</div>
                  <div class="contact-info-value"><?php echo esc_html($cd_contact_info_address); ?></div>
                </div>
              </li>
              <li class="contact-info-item">
                <span class="contact-info-icon" aria-hidden="true">⏱</span>
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

    <!-- Testimonials Section (Dark Background) -->
    <section class="contact-testimonials-section">
      <div class="container">
        <div class="contact-testimonials-header">
          <h2 class="contact-testimonials-title"><?php echo esc_html($cd_contact_testimonials_title); ?></h2>
          <p class="contact-testimonials-subtitle"><?php echo esc_html($cd_contact_testimonials_subtitle); ?></p>
        </div>
        <div class="contact-testimonials-grid">
          <?php foreach ($cd_contact_testimonials_cards as $t): ?>
            <?php
              $t_avatar = isset($t['avatar']) ? $t['avatar'] : '';
              if (is_array($t_avatar) && !empty($t_avatar['url'])) { $t_avatar = $t_avatar['url']; }
              elseif (is_numeric($t_avatar)) { $t_avatar = wp_get_attachment_image_url((int) $t_avatar, 'full'); }
            ?>
          <div class="contact-testimonial-card">
            <div class="contact-testimonial-content">
                <p class="contact-testimonial-title"><?php echo esc_html(isset($t['title']) ? $t['title'] : ''); ?></p>
                <p class="contact-testimonial-quote"><?php echo esc_html(isset($t['quote']) ? $t['quote'] : ''); ?></p>
            </div>
            <div class="contact-testimonial-author">
                <img src="<?php echo esc_url($t_avatar); ?>" alt="<?php echo esc_attr(isset($t['name']) ? $t['name'] : ''); ?>" class="contact-testimonial-avatar" />
              <div class="contact-testimonial-author-info">
                  <div class="contact-testimonial-name"><?php echo esc_html(isset($t['name']) ? $t['name'] : ''); ?></div>
                  <div class="contact-testimonial-role"><?php echo esc_html(isset($t['role']) ? $t['role'] : ''); ?></div>
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
          <?php craftdigitally_render_case_study_grid($cd_contact_results_count ?: 6, 'standard', $cd_contact_results_read_more); ?>
        </div>

        <div class="case-study-view-all">
          <?php
          $case_studies_page = get_page_by_path('case-studies');
          $case_studies_link = $case_studies_page ? get_permalink($case_studies_page) : home_url('/');
          ?>
          <a href="<?php echo esc_url($case_studies_link); ?>" class="btn btn-outline case-study-view-all-btn"><?php echo esc_html($cd_contact_results_view_all); ?></a>
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
