<?php
/**
 * CraftDigitally Theme Customizer
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer
 */
function craftdigitally_customize_register($wp_customize) {
  $wp_customize->get_setting('blogname')->transport = 'postMessage';
  $wp_customize->get_setting('blogdescription')->transport = 'postMessage';

  if (isset($wp_customize->selective_refresh)) {
    $wp_customize->selective_refresh->add_partial(
      'blogname',
      array(
        'selector' => '.site-title a',
        'render_callback' => 'craftdigitally_customize_partial_blogname',
      )
    );
    $wp_customize->selective_refresh->add_partial(
      'blogdescription',
      array(
        'selector' => '.site-description',
        'render_callback' => 'craftdigitally_customize_partial_blogdescription',
      )
    );
  }

  // ============================================
  // HERO SECTION
  // ============================================
  $wp_customize->add_section('craftdigitally_hero', array(
    'title' => __('Hero Section', 'craftdigitally'),
    'priority' => 30,
  ));

  // Show/Hide Hero Section
  $wp_customize->add_setting('craftdigitally_show_hero', array(
    'default' => true,
    'sanitize_callback' => 'craftdigitally_sanitize_checkbox',
  ));

  $wp_customize->add_control('craftdigitally_show_hero', array(
    'label' => __('Show Hero Section', 'craftdigitally'),
    'section' => 'craftdigitally_hero',
    'type' => 'checkbox',
  ));

  // Hero Title
  $wp_customize->add_setting('craftdigitally_hero_title', array(
    'default' => __('Stand out online with clarity,<br />strategy and confidence', 'craftdigitally'),
    'sanitize_callback' => 'wp_kses_post',
  ));

  $wp_customize->add_control('craftdigitally_hero_title', array(
    'label' => __('Hero Title', 'craftdigitally'),
    'section' => 'craftdigitally_hero',
    'type' => 'textarea',
    'description' => __('You can use HTML tags like &lt;br /&gt; for line breaks', 'craftdigitally'),
  ));

  // Hero Subtitle
  $wp_customize->add_setting('craftdigitally_hero_subtitle', array(
    'default' => __('We\'re a leading digital marketing agency that helps businesses in Ahmedabad turn SEO, web development, and digital marketing into powerful growth channels.', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_hero_subtitle', array(
    'label' => __('Hero Subtitle', 'craftdigitally'),
    'section' => 'craftdigitally_hero',
    'type' => 'textarea',
  ));

  // Hero Button Text
  $wp_customize->add_setting('craftdigitally_hero_button_text', array(
    'default' => __('Book a Free Consult', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_hero_button_text', array(
    'label' => __('Hero Button Text', 'craftdigitally'),
    'section' => 'craftdigitally_hero',
    'type' => 'text',
  ));

  // ============================================
  // CASE STUDIES SECTION
  // ============================================
  $wp_customize->add_section('craftdigitally_case_studies', array(
    'title' => __('Case Studies Section', 'craftdigitally'),
    'priority' => 31,
  ));

  // Case Studies Title
  $wp_customize->add_setting('craftdigitally_case_studies_title', array(
    'default' => __('Results Our Clients Have Achieved', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_studies_title', array(
    'label' => __('Section Title', 'craftdigitally'),
    'section' => 'craftdigitally_case_studies',
    'type' => 'text',
  ));

  // Case Studies Subtitle
  $wp_customize->add_setting('craftdigitally_case_studies_subtitle', array(
    'default' => __('Our SEO strategies go beyond rankings — they deliver measurable business growth.', 'craftdigitally') . "\n" . __('From higher visibility to increased traffic and leads, see how our clients turned searches into success.', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_case_studies_subtitle', array(
    'label' => __('Section Subtitle', 'craftdigitally'),
    'section' => 'craftdigitally_case_studies',
    'type' => 'textarea',
    'description' => __('Each line will be displayed separately', 'craftdigitally'),
  ));

  // Case Studies Data (JSON)
  require_once get_template_directory() . '/inc/default-data.php';
  $default_case_studies = json_encode(craftdigitally_get_default_case_studies(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

  $wp_customize->add_setting('craftdigitally_case_studies_data', array(
    'default' => $default_case_studies,
    'sanitize_callback' => 'craftdigitally_sanitize_json',
  ));

  $wp_customize->add_control('craftdigitally_case_studies_data', array(
    'label' => __('Case Studies Data (JSON)', 'craftdigitally'),
    'section' => 'craftdigitally_case_studies',
    'type' => 'textarea',
    'description' => __('Enter case studies in JSON format. Each item should have: category, description, logo (filename), and link (optional).', 'craftdigitally'),
  ));

  // View All Button Text
  $wp_customize->add_setting('craftdigitally_case_studies_button_text', array(
    'default' => __('View all Case Studies', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_studies_button_text', array(
    'label' => __('View All Button Text', 'craftdigitally'),
    'section' => 'craftdigitally_case_studies',
    'type' => 'text',
  ));

  // ============================================
  // TESTIMONIALS SECTION
  // ============================================
  $wp_customize->add_section('craftdigitally_testimonials', array(
    'title' => __('Testimonials Section', 'craftdigitally'),
    'priority' => 32,
  ));

  // Testimonials Title
  $wp_customize->add_setting('craftdigitally_testimonials_title', array(
    'default' => __('What Clients Say About Working With Us', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_testimonials_title', array(
    'label' => __('Section Title', 'craftdigitally'),
    'section' => 'craftdigitally_testimonials',
    'type' => 'text',
  ));

  // Testimonials Subtitle
  $wp_customize->add_setting('craftdigitally_testimonials_subtitle', array(
    'default' => __('We believe great partnerships lead to great outcomes.', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_testimonials_subtitle', array(
    'label' => __('Section Subtitle', 'craftdigitally'),
    'section' => 'craftdigitally_testimonials',
    'type' => 'textarea',
  ));

  // Testimonials Description
  $wp_customize->add_setting('craftdigitally_testimonials_description', array(
    'default' => __('Discover why clients trust us to handle their SEO and how our collaboration drives measurable impact.', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_testimonials_description', array(
    'label' => __('Section Description', 'craftdigitally'),
    'section' => 'craftdigitally_testimonials',
    'type' => 'textarea',
  ));

  // Testimonials Data (JSON)
  $default_testimonials = json_encode(craftdigitally_get_default_testimonials(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

  $wp_customize->add_setting('craftdigitally_testimonials_data', array(
    'default' => $default_testimonials,
    'sanitize_callback' => 'craftdigitally_sanitize_json',
  ));

  $wp_customize->add_control('craftdigitally_testimonials_data', array(
    'label' => __('Testimonials Data (JSON)', 'craftdigitally'),
    'section' => 'craftdigitally_testimonials',
    'type' => 'textarea',
    'description' => __('Enter testimonials in JSON format. Each item should have: title, quote, name, title_role, company, and image (filename).', 'craftdigitally'),
  ));

  // ============================================
  // PROCESS SECTION
  // ============================================
  $wp_customize->add_section('craftdigitally_process', array(
    'title' => __('Process Section', 'craftdigitally'),
    'priority' => 33,
  ));

  // Process Title
  $wp_customize->add_setting('craftdigitally_process_title', array(
    'default' => __('Our Proven Process', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_process_title', array(
    'label' => __('Section Title', 'craftdigitally'),
    'section' => 'craftdigitally_process',
    'type' => 'text',
  ));

  // Process Subtitle
  $wp_customize->add_setting('craftdigitally_process_subtitle', array(
    'default' => __('A clear, results-driven approach designed to help your business grow step by step.', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_process_subtitle', array(
    'label' => __('Section Subtitle', 'craftdigitally'),
    'section' => 'craftdigitally_process',
    'type' => 'textarea',
  ));

  // Process Steps Data (JSON)
  $default_process_steps = json_encode(craftdigitally_get_default_process_steps(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

  $wp_customize->add_setting('craftdigitally_process_steps_data', array(
    'default' => $default_process_steps,
    'sanitize_callback' => 'craftdigitally_sanitize_json',
  ));

  $wp_customize->add_control('craftdigitally_process_steps_data', array(
    'label' => __('Process Steps Data (JSON)', 'craftdigitally'),
    'section' => 'craftdigitally_process',
    'type' => 'textarea',
    'description' => __('Enter process steps in JSON format. Each item should have: number, title, and description.', 'craftdigitally'),
  ));

  // ============================================
  // SERVICES SECTION
  // ============================================
  $wp_customize->add_section('craftdigitally_services', array(
    'title' => __('Services Section', 'craftdigitally'),
    'priority' => 34,
  ));

  // Services Title
  $wp_customize->add_setting('craftdigitally_services_title', array(
    'default' => __('Our Services', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_services_title', array(
    'label' => __('Section Title', 'craftdigitally'),
    'section' => 'craftdigitally_services',
    'type' => 'text',
  ));

  // Services Subtitle
  $wp_customize->add_setting('craftdigitally_services_subtitle', array(
    'default' => __('Whether you\'re a startup in Chandkheda or an established brand in Prahladnagar, we\'ve got your back. Your Roadmap to More Traffic, Leads & Sales', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_services_subtitle', array(
    'label' => __('Section Subtitle', 'craftdigitally'),
    'section' => 'craftdigitally_services',
    'type' => 'textarea',
  ));

  // Services Data (JSON)
  $default_services = json_encode(craftdigitally_get_default_services(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

  $wp_customize->add_setting('craftdigitally_services_data', array(
    'default' => $default_services,
    'sanitize_callback' => 'craftdigitally_sanitize_json',
  ));

  $wp_customize->add_control('craftdigitally_services_data', array(
    'label' => __('Services Data (JSON)', 'craftdigitally'),
    'section' => 'craftdigitally_services',
    'type' => 'textarea',
    'description' => __('Enter services in JSON format. Each item should have: title, description, and icon (filename).', 'craftdigitally'),
  ));

  // ============================================
  // WHY CHOOSE SECTION
  // ============================================
  $wp_customize->add_section('craftdigitally_why_choose', array(
    'title' => __('Why Choose Section', 'craftdigitally'),
    'priority' => 35,
  ));

  // Why Choose Title
  $wp_customize->add_setting('craftdigitally_why_choose_title', array(
    'default' => __('Why Choose Craft Digitally?', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_why_choose_title', array(
    'label' => __('Section Title', 'craftdigitally'),
    'section' => 'craftdigitally_why_choose',
    'type' => 'text',
  ));

  // Why Choose Description
  $wp_customize->add_setting('craftdigitally_why_choose_description', array(
    'default' => __('Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad.', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_why_choose_description', array(
    'label' => __('Section Description', 'craftdigitally'),
    'section' => 'craftdigitally_why_choose',
    'type' => 'textarea',
  ));

  // Why Choose Points (one per line)
  $wp_customize->add_setting('craftdigitally_why_choose_points', array(
    'default' => __('Local Ahmedabad Expertise', 'craftdigitally') . "\n" . __('Proven Track Record', 'craftdigitally') . "\n" . __('Transparent Reporting', 'craftdigitally') . "\n" . __('Affordable Pricing', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_why_choose_points', array(
    'label' => __('Key Points (one per line)', 'craftdigitally'),
    'section' => 'craftdigitally_why_choose',
    'type' => 'textarea',
    'description' => __('Enter each point on a new line', 'craftdigitally'),
  ));

  // Why Choose Button Text
  $wp_customize->add_setting('craftdigitally_why_choose_button_text', array(
    'default' => __('Contact Us', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_why_choose_button_text', array(
    'label' => __('Button Text', 'craftdigitally'),
    'section' => 'craftdigitally_why_choose',
    'type' => 'text',
  ));

  // ============================================
  // CTA SECTION
  // ============================================
  $wp_customize->add_section('craftdigitally_cta', array(
    'title' => __('CTA Section', 'craftdigitally'),
    'priority' => 36,
  ));

  // CTA Title
  $wp_customize->add_setting('craftdigitally_cta_title', array(
    'default' => __('Ready to Dominate Your Competition?', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_cta_title', array(
    'label' => __('Section Title', 'craftdigitally'),
    'section' => 'craftdigitally_cta',
    'type' => 'text',
  ));

  // CTA Subtitle
  $wp_customize->add_setting('craftdigitally_cta_subtitle', array(
    'default' => __('Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_cta_subtitle', array(
    'label' => __('Section Subtitle', 'craftdigitally'),
    'section' => 'craftdigitally_cta',
    'type' => 'textarea',
  ));

  // CTA Button Text
  $wp_customize->add_setting('craftdigitally_cta_button_text', array(
    'default' => __('Let\'s Connect', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_cta_button_text', array(
    'label' => __('Submit Button Text', 'craftdigitally'),
    'section' => 'craftdigitally_cta',
    'type' => 'text',
  ));

  // ============================================
  // CASE STUDY LANDING PAGE
  // ============================================
  $wp_customize->add_section('craftdigitally_case_study_landing', array(
    'title' => __('Case Study Landing Page', 'craftdigitally'),
    'priority' => 37,
  ));

  // Hero Title
  $wp_customize->add_setting('craftdigitally_case_study_hero_title', array(
    'default' => __('Driving Growth Through SEO:<br />Client Success Stories', 'craftdigitally'),
    'sanitize_callback' => 'wp_kses_post',
  ));

  $wp_customize->add_control('craftdigitally_case_study_hero_title', array(
    'label' => __('Hero Title', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_landing',
    'type' => 'textarea',
    'description' => __('You can use HTML tags like &lt;br /&gt; for line breaks', 'craftdigitally'),
  ));

  // Hero Subtitle
  $wp_customize->add_setting('craftdigitally_case_study_hero_subtitle', array(
    'default' => __('Discover how we\'ve helped businesses improve rankings, increase traffic, and turn clicks into customers.<br />Through strategic SEO campaigns, we\'ve guided clients from low visibility to top search positions', 'craftdigitally'),
    'sanitize_callback' => 'wp_kses_post',
  ));

  $wp_customize->add_control('craftdigitally_case_study_hero_subtitle', array(
    'label' => __('Hero Subtitle', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_landing',
    'type' => 'textarea',
    'description' => __('You can use HTML tags like &lt;br /&gt; for line breaks', 'craftdigitally'),
  ));

  // Featured Case Study Title
  $wp_customize->add_setting('craftdigitally_featured_case_study_title', array(
    'default' => __('Top 3 Rankings for competitive keywords', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_featured_case_study_title', array(
    'label' => __('Featured Case Study Title', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_landing',
    'type' => 'text',
  ));

  // Featured Case Study Description
  $wp_customize->add_setting('craftdigitally_featured_case_study_description', array(
    'default' => __('A leading [industry/business type] client was struggling to rank for highly competitive keywords that their target audience searched daily. Despite quality services, they remained invisible beyond page 5 of Google.', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_featured_case_study_description', array(
    'label' => __('Featured Case Study Description', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_landing',
    'type' => 'textarea',
  ));

  // Featured Case Study Logo
  $wp_customize->add_setting('craftdigitally_featured_case_study_logo', array(
    'default' => 'testeracademy.png',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_featured_case_study_logo', array(
    'label' => __('Featured Case Study Logo (filename)', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_landing',
    'type' => 'text',
    'description' => __('Enter the logo filename from assets/images folder', 'craftdigitally'),
  ));

  // Featured Case Study Button Text
  $wp_customize->add_setting('craftdigitally_featured_case_study_button_text', array(
    'default' => __('Read Case Study', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_featured_case_study_button_text', array(
    'label' => __('Featured Case Study Button Text', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_landing',
    'type' => 'text',
  ));

  // Testimonials Title
  $wp_customize->add_setting('craftdigitally_case_study_testimonials_title', array(
    'default' => __('What Clients Say About Working With Us', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_study_testimonials_title', array(
    'label' => __('Testimonials Section Title', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_landing',
    'type' => 'text',
  ));

  // Testimonial Quote
  $wp_customize->add_setting('craftdigitally_case_study_testimonial_quote', array(
    'default' => __('Before working with CraftDigitally, SEO felt like a fog I couldn\'t navigate. Their process made everything so clear and actionable. I left with not only a visible online presence but a genuine sense of confidence', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_case_study_testimonial_quote', array(
    'label' => __('Testimonial Quote', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_landing',
    'type' => 'textarea',
  ));

  // Testimonial Name
  $wp_customize->add_setting('craftdigitally_case_study_testimonial_name', array(
    'default' => __('Sarah Lin', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_study_testimonial_name', array(
    'label' => __('Testimonial Author Name', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_landing',
    'type' => 'text',
  ));

  // Testimonial Role
  $wp_customize->add_setting('craftdigitally_case_study_testimonial_role', array(
    'default' => __('Brand Strategist, Lin Studio', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_study_testimonial_role', array(
    'label' => __('Testimonial Author Role', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_landing',
    'type' => 'text',
  ));

  // Testimonial Avatar
  $wp_customize->add_setting('craftdigitally_case_study_testimonial_avatar', array(
    'default' => 'sarah-lin.png',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_study_testimonial_avatar', array(
    'label' => __('Testimonial Avatar (filename)', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_landing',
    'type' => 'text',
    'description' => __('Enter the avatar filename from assets/images folder', 'craftdigitally'),
  ));

  // ============================================
  // CASE STUDY DETAIL PAGE
  // ============================================
  $wp_customize->add_section('craftdigitally_case_study_detail', array(
    'title' => __('Case Study Detail Page', 'craftdigitally'),
    'priority' => 38,
  ));

  // Hero Section
  $wp_customize->add_setting('craftdigitally_case_detail_logo', array(
    'default' => 'urban-stitch-logo 1.png',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_logo', array(
    'label' => __('Hero Logo (filename)', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
    'description' => __('Enter the logo filename from assets/images folder', 'craftdigitally'),
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_hero_title', array(
    'default' => __('Local SEO Success<br />for a Retail Store', 'craftdigitally'),
    'sanitize_callback' => 'wp_kses_post',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_hero_title', array(
    'label' => __('Hero Title', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'textarea',
    'description' => __('You can use HTML tags like &lt;br /&gt; for line breaks', 'craftdigitally'),
  ));

  // Metrics
  $wp_customize->add_setting('craftdigitally_case_detail_metric_1_value', array(
    'default' => '2,117%',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_metric_1_value', array(
    'label' => __('Metric 1 Value', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_metric_1_label', array(
    'default' => 'GROWTH IN ORGANIC BLOG SESSIONS',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_metric_1_label', array(
    'label' => __('Metric 1 Label', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_metric_2_value', array(
    'default' => '11X',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_metric_2_value', array(
    'label' => __('Metric 2 Value', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_metric_2_label', array(
    'default' => 'CONVERSION RATE',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_metric_2_label', array(
    'label' => __('Metric 2 Label', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_metric_3_value', array(
    'default' => '39X',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_metric_3_value', array(
    'label' => __('Metric 3 Value', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_metric_3_label', array(
    'default' => 'BLOG CONVERSIONS',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_metric_3_label', array(
    'label' => __('Metric 3 Label', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_hero_button', array(
    'default' => 'Continue Reading',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_hero_button', array(
    'label' => __('Hero Button Text', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  // Overview
  $wp_customize->add_setting('craftdigitally_case_detail_overview', array(
    'default' => __('A local home décor retailer struggled to appear in search results and attract nearby shoppers. Through a focused Local SEO strategy — including Google Business optimization, keyword targeting, and review building — we improved their online visibility and credibility. Within months, the store ranked in Google\'s', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_overview', array(
    'label' => __('Overview', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'textarea',
  ));

  // Meta Data
  $wp_customize->add_setting('craftdigitally_case_detail_industry', array(
    'default' => 'Retail',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_industry', array(
    'label' => __('Industry', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_company', array(
    'default' => 'Tester Academy',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_company', array(
    'label' => __('Company Name', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_services', array(
    'default' => 'Local SEO',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_services', array(
    'label' => __('Services', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  // Problem
  $wp_customize->add_setting('craftdigitally_case_detail_problem_1', array(
    'default' => __('The client was struggling to attract local customers through online searches. Despite having a good product range, their business did not appear in the top results on Google Maps or local search queries. Competitors were outranking them, resulting in fewer calls, foot traffic, and online inquiries.', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_problem_1', array(
    'label' => __('Problem Paragraph 1', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'textarea',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_problem_2', array(
    'default' => __('The client was struggling to attract local customers through online searches. Despite having a good product range, their business did not appear in the top results on Google Maps or local search queries. Competitors were outranking them, resulting in fewer calls, foot traffic, and online inquiries.', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_problem_2', array(
    'label' => __('Problem Paragraph 2', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'textarea',
  ));

  // Solution
  $wp_customize->add_setting('craftdigitally_case_detail_solution_intro', array(
    'default' => __('The client was struggling to attract local customers through online searches. Despite having a good product range, their business did not appear in the top results on Google Maps or local search queries. Competitors were outranking them, resulting in fewer calls, foot traffic, and online inquiries.', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_solution_intro', array(
    'label' => __('Solution Introduction', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'textarea',
  ));

  // Solution points (simplified - can be expanded)
  $wp_customize->add_setting('craftdigitally_case_detail_solution_1', array(
    'default' => 'Our team implemented a tailored Local SEO strategy that include',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_solution_1', array(
    'label' => __('Solution Point 1', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  // Image
  $wp_customize->add_setting('craftdigitally_case_detail_image', array(
    'default' => 'hero.png',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_image', array(
    'label' => __('Content Image (filename)', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
    'description' => __('Enter the image filename from assets/images folder', 'craftdigitally'),
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_image_caption', array(
    'default' => 'The client was struggling to attract local customers through online searches',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_image_caption', array(
    'label' => __('Image Caption', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  // Testimonial
  $wp_customize->add_setting('craftdigitally_case_detail_testimonial_quote', array(
    'default' => __('Before working with CraftDigitally, SEO felt like a fog I couldn\'t navigate. Their process made everything so clear and actionable. I left with not only a visible online presence but a genuine sense of confidence in how I show up digitally', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_testimonial_quote', array(
    'label' => __('Testimonial Quote', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'textarea',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_testimonial_name', array(
    'default' => 'Sarah Lin',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_testimonial_name', array(
    'label' => __('Testimonial Author Name', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_testimonial_role', array(
    'default' => 'Brand Strategist, Lin Studio',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_testimonial_role', array(
    'label' => __('Testimonial Author Role', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_testimonial_avatar', array(
    'default' => 'sarah-lin.png',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_testimonial_avatar', array(
    'label' => __('Testimonial Avatar (filename)', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
    'description' => __('Enter the avatar filename from assets/images folder', 'craftdigitally'),
  ));

  // Outcome
  $wp_customize->add_setting('craftdigitally_case_detail_outcome_intro', array(
    'default' => __('The client was struggling to attract local customers through online searches. Despite having a good product range, their business did not appear in the top results on Google Maps or local search queries. Competitors were outranking them, resulting in fewer calls, foot traffic, and online inquiries.', 'craftdigitally'),
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_outcome_intro', array(
    'label' => __('Outcome Introduction', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'textarea',
  ));

  // CTA
  $wp_customize->add_setting('craftdigitally_case_detail_cta_title', array(
    'default' => 'Ready to Dominate Your Competition?',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_cta_title', array(
    'label' => __('CTA Title', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_cta_subtitle', array(
    'default' => 'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords',
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_cta_subtitle', array(
    'label' => __('CTA Subtitle', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'textarea',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_cta_button', array(
    'default' => 'Book a Free Consult',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_cta_button', array(
    'label' => __('CTA Button Text', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  // Related Case Studies
  $wp_customize->add_setting('craftdigitally_case_detail_related_title', array(
    'default' => 'Related Case Studies',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_related_title', array(
    'label' => __('Related Case Studies Title', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'text',
  ));

  $wp_customize->add_setting('craftdigitally_case_detail_related_subtitle', array(
    'default' => __('Our SEO strategies go beyond rankings — they deliver measurable business growth.<br />From higher visibility to increased traffic and leads, see how our clients turned searches into success.', 'craftdigitally'),
    'sanitize_callback' => 'wp_kses_post',
  ));

  $wp_customize->add_control('craftdigitally_case_detail_related_subtitle', array(
    'label' => __('Related Case Studies Subtitle', 'craftdigitally'),
    'section' => 'craftdigitally_case_study_detail',
    'type' => 'textarea',
    'description' => __('You can use HTML tags like &lt;br /&gt; for line breaks', 'craftdigitally'),
  ));
}
add_action('customize_register', 'craftdigitally_customize_register');

/**
 * Render the site title for the selective refresh partial
 */
function craftdigitally_customize_partial_blogname() {
  bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial
 */
function craftdigitally_customize_partial_blogdescription() {
  bloginfo('description');
}

/**
 * Sanitize checkbox
 */
function craftdigitally_sanitize_checkbox($checked) {
  return ((isset($checked) && true === $checked) ? true : false);
}

/**
 * Sanitize JSON data
 */
function craftdigitally_sanitize_json($input) {
  if (empty($input)) {
    return '';
  }
  
  // Try to decode JSON to validate it
  $decoded = json_decode($input, true);
  
  // If JSON is invalid, return empty string
  if (json_last_error() !== JSON_ERROR_NONE) {
    return '';
  }
  
  // Return the original input if valid
  return $input;
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously
 */
function craftdigitally_customize_preview_js() {
  wp_enqueue_script('craftdigitally-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), wp_get_theme()->get('Version'), true);
}
add_action('customize_preview_init', 'craftdigitally_customize_preview_js');

