<?php
/**
 * Default data arrays for the theme
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Get default case studies data
 *
 * @return array
 */
function craftdigitally_get_default_case_studies() {
  return array(
    array(
      'category' => 'RETAIL',
      'description' => 'Lorem ipsum dolor sit amet consectetur. Et senectus congue turpis tellus fringilla.',
      'logo' => 'dz-logo.png',
      'link' => '#',
    ),
    array(
      'category' => 'E-COMMERCE',
      'description' => 'Lorem ipsum dolor sit amet consectetur. Et senectus congue turpis tellus fringilla.',
      'logo' => 'ecom.png',
      'link' => '#',
    ),
    array(
      'category' => 'ENERGY',
      'description' => 'Lorem ipsum dolor sit amet consectetur. Et senectus congue turpis tellus fringilla.',
      'logo' => 'energy.png',
      'link' => '#',
    ),
    array(
      'category' => 'FASHION',
      'description' => 'Lorem ipsum dolor sit amet consectetur. Et senectus congue turpis tellus fringilla.',
      'logo' => 'urban-stitch-logo 1.png',
      'link' => '#',
    ),
    array(
      'category' => 'EDUCATION',
      'description' => 'Lorem ipsum dolor sit amet consectetur. Et senectus congue turpis tellus fringilla.',
      'logo' => 'testeracademy.png',
      'link' => '#',
    ),
    array(
      'category' => 'FASHION',
      'description' => 'Lorem ipsum dolor sit amet consectetur. Et senectus congue turpis tellus fringilla.',
      'logo' => 'urban-stitch-logo 1.png',
      'link' => '#',
    ),
  );
}

/**
 * Get default testimonials data
 *
 * @return array
 */
function craftdigitally_get_default_testimonials() {
  return array(
    array(
      'title' => 'CraftDigitally turned what felt complicated into clarity I could finally act on',
      'quote' => 'Before working with CraftDigitally, SEO felt like a fog I couldn\'t navigate. Their process made everything so clear and actionable. I left with not only a visible online presence but a genuine sense of confidence in how I show up digitally',
      'name' => 'Sarah Lin',
      'title_role' => 'Brand Strategist',
      'company' => 'Lin Studio',
      'image' => 'sarah-lin.png',
    ),
    array(
      'title' => 'They helped me organize the chaos of my online world into something cohesive and professional',
      'quote' => 'I used to feel scattered across platforms, unsure of what really mattered for visibility. CraftDigitally brought structure, strategy, and calm to my digital presence. Now my website and content actually work together—and I finally feel proud to share them',
      'name' => 'Marcus Alvarez',
      'title_role' => 'Founder',
      'company' => 'Alvarez Consulting',
      'image' => 'marcus-alvarez.png',
    ),
    array(
      'title' => 'It wasn\'t just SEO—it was clarity, confidence, and direction',
      'quote' => 'CraftDigitally doesn\'t just talk keywords; they build confidence. Every step felt grounded and supportive. They helped me see how to be visible without losing authenticity. My business feels lighter, clearer, and far more aligned',
      'name' => 'Neha Patel',
      'title_role' => 'Owner',
      'company' => 'Lumina Creative',
      'image' => 'neha-patel.png',
    ),
    array(
      'title' => 'They make digital visibility feel simple and that\'s a rare gift',
      'quote' => 'I\'ve worked with a few agencies before, but CraftDigitally stands out for how approachable and clear they are. Their explanations made sense, their strategy was spot on, and the results showed up quickly. It finally feels easy to stay consistent online',
      'name' => 'Daniel Kim',
      'title_role' => 'Creative Director',
      'company' => 'DK Studio',
      'image' => 'daniel-kim.png',
    ),
    array(
      'title' => 'Every detail from tone to structure felt intentional and aligned',
      'quote' => 'What I appreciated most about CraftDigitally was their calm professionalism. They guided me through each step, never rushing, always clarifying. My brand now feels cohesive and visible in a way that truly reflects who I am',
      'name' => 'Emma Walsh',
      'title_role' => 'Founder',
      'company' => 'The Clarity Edit',
      'image' => 'emma-walsh.png',
    ),
    array(
      'title' => 'They made me feel visible again without the overwhelm',
      'quote' => 'Working with CraftDigitally was like exhaling. They simplified what always felt heavy, creating a digital presence that finally matches the quality of my work. I feel both seen and supported—and that\'s exactly what I needed',
      'name' => 'Jordan Smith',
      'title_role' => 'Consultant',
      'company' => 'Visibility & Co',
      'image' => 'jordan-smith.png',
    ),
  );
}

/**
 * Get default process steps data
 *
 * @return array
 */
function craftdigitally_get_default_process_steps() {
  return array(
    array(
      'number' => '1',
      'title' => 'SEO Audit & Strategy',
      'description' => 'We analyze your current website, research competitors, and identify the best keywords and opportunities for your Ahmedabad business.',
    ),
    array(
      'number' => '2',
      'title' => 'Expert Implementation',
      'description' => 'We analyze your current website, research competitors, and identify the best keywords and opportunities for your Ahmedabad business.',
    ),
    array(
      'number' => '3',
      'title' => 'Performance Tracking',
      'description' => 'We analyze your current website, research competitors, and identify the best keywords and opportunities for your Ahmedabad business.',
    ),
    array(
      'number' => '4',
      'title' => 'Continuous Optimization',
      'description' => 'We analyze your current website, research competitors, and identify the best keywords and opportunities for your Ahmedabad business.',
    ),
  );
}

/**
 * Get default services data
 *
 * @return array
 */
function craftdigitally_get_default_services() {
  return array(
    array(
      'title' => 'Local SEO',
      'description' => 'Turn your local presence into lasting visibility Align your listings, keywords, and on-page SEO so your business becomes the clear choice in your area',
      'icon' => 'local-seo.png',
    ),
    array(
      'title' => 'Link Building',
      'description' => 'Build trust and authority with ethical, high-quality backlinks that strengthen your rankings and position your brand as a credible voice in your industry',
      'icon' => 'link-building.png',
    ),
    array(
      'title' => 'Ecommerce SEO',
      'description' => 'Make your products easy to find and effortless to buy, optimise your store\'s structure, speed, and search visibility for better reach and higher conversions.',
      'icon' => 'emommerce-seo.png',
    ),
    array(
      'title' => 'International SEO',
      'description' => 'Expand your reach across borders with tailored multilingual and regional strategies that make your brand visible - and relevant - worldwide',
      'icon' => 'international-seo.png',
    ),
    array(
      'title' => 'Small Business SEO',
      'description' => 'Simplify growth with SEO built for your scale – clear, focused strategies that make your website a steady source of visibility and leads.',
      'icon' => 'small-business-seo.png',
    ),
    array(
      'title' => 'Social Media Services',
      'description' => 'Create connection through clarity – we craft consistent, on-brand social content that strengthens visibility, trust, and engagement across every platform',
      'icon' => 'social-media-services.png',
    ),
  );
}

/**
 * Get default why choose points
 *
 * @return array
 */
function craftdigitally_get_default_why_choose_points() {
  return array(
    __('Local Ahmedabad Expertise', 'craftdigitally'),
    __('Proven Track Record', 'craftdigitally'),
    __('Transparent Reporting', 'craftdigitally'),
    __('Affordable Pricing', 'craftdigitally'),
  );
}

