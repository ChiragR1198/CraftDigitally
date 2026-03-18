<?php
/**
 * Archive Template for Service Post Type
 * 
 * Displays services archive page
 *
 * @package CraftDigitally
 */

get_header();

// Pull ACF fields from the Services landing page (if a Page is using that template).
$service_landing_page = get_pages(array(
  'meta_key' => '_wp_page_template',
  'meta_value' => 'page-templates/page-service-landing.php',
  'number' => 1,
  'post_status' => 'publish',
));
$landing_id = !empty($service_landing_page) ? $service_landing_page[0]->ID : false;

// Match `page-templates/page-service-landing.php` output so /services/ never looks "half".
$cd_sl_hero_kicker = craftdigitally_get_acf('service_landing_hero_kicker', 'SEO Services That Help Your Business Get Found - and Chosen', $landing_id);
$cd_sl_hero_subhead = craftdigitally_get_acf('service_landing_hero_subhead', 'With tailored SEO, we make it easier for customers to discover your business and choose you over competitors. Clear strategy, transparent reporting, and measurable results-every step of the way.', $landing_id);

$cd_sl_featured_title = craftdigitally_get_acf('service_landing_featured_title', 'Local SEO That Drives Real Foot Traffic to Your Business', $landing_id);
$cd_sl_featured_desc = craftdigitally_get_acf('service_landing_featured_desc', 'Our Local SEO strategies make it easier for people nearby to discover, trust, and choose your business. From GBP optimization to local ranking boosts, we help you turn searches into walk-ins.', $landing_id);
$cd_sl_featured_btn_label = craftdigitally_get_acf('service_landing_featured_button_label', 'Read Our Services', $landing_id);
$cd_sl_featured_btn_link = craftdigitally_get_acf('service_landing_featured_button_link', '#services', $landing_id);
$cd_sl_featured_img = craftdigitally_get_acf_image_url('service_landing_featured_image', get_template_directory_uri() . '/assets/images/service-book.png', $landing_id);
$cd_sl_featured_img_alt = craftdigitally_get_acf('service_landing_featured_image_alt', 'Local SEO Service', $landing_id);

$cd_sl_services_title = craftdigitally_get_acf('service_landing_services_title', 'Our Services', $landing_id);
$cd_sl_services_subtitle = craftdigitally_get_acf('service_landing_services_subtitle', "Whether you're a startup in Chandkheda or an established brand in Prahladnagar, we've got your back. Your Roadmap to More Traffic, Leads & Sales", $landing_id);
$cd_sl_services_cards = craftdigitally_get_acf_array('service_landing_services_cards', array(
  array('icon' => get_template_directory_uri() . '/assets/images/local-seo.png', 'icon_alt' => 'Local SEO icon', 'title' => 'Local SEO', 'desc' => 'Turn your local presence into lasting visibility. Align your listings, keywords, and on-page SEO so your business becomes the clear choice in your area.'),
  array('icon' => get_template_directory_uri() . '/assets/images/link-building.png', 'icon_alt' => 'Link Building icon', 'title' => 'Link Building', 'desc' => 'Build trust and authority with ethical, high-quality backlinks that strengthen your rankings and position your brand as a credible voice in your industry.'),
  array('icon' => get_template_directory_uri() . '/assets/images/emommerce-seo.png', 'icon_alt' => 'Ecommerce SEO icon', 'title' => 'Ecommerce SEO', 'desc' => "Make your products easy to find and effortless to buy. Optimise your store's structure, speed, and search visibility for better reach and higher conversions."),
  array('icon' => get_template_directory_uri() . '/assets/images/international-seo.png', 'icon_alt' => 'International SEO icon', 'title' => 'International SEO', 'desc' => 'Expand your reach across borders with tailored multilingual and regional strategies that make your brand visible - and relevant - worldwide.'),
  array('icon' => get_template_directory_uri() . '/assets/images/small-business-seo.png', 'icon_alt' => 'Small Business SEO icon', 'title' => 'Small Business SEO', 'desc' => 'Simplify growth with SEO built for your scale – clear, focused strategies that make your website a steady source of visibility and leads.'),
  array('icon' => get_template_directory_uri() . '/assets/images/social-media-services.png', 'icon_alt' => 'Social Media Services icon', 'title' => 'Social Media Services', 'desc' => 'Create connection through clarity – we craft consistent, on-brand social content that strengthens visibility, trust, and engagement across every platform.'),
), $landing_id);

// Dynamic services (CPT) for the grid.
$cd_sl_service_posts_status = current_user_can('edit_posts')
  ? array('publish', 'draft', 'pending', 'future', 'private')
  : array('publish');
$cd_sl_service_posts_q = new WP_Query(array(
  'post_type' => 'service',
  'post_status' => $cd_sl_service_posts_status,
  'posts_per_page' => 6,
  'ignore_sticky_posts' => true,
  'orderby' => 'date',
  'order' => 'DESC',
));

// NOTE:
// Services should use their native permalinks:
// - Archive: /services/
// - Single:  /service/{post-name}/

$cd_sl_cta_title = craftdigitally_get_acf('service_landing_cta_title', 'Ready to Dominate Your Competition?', $landing_id);
$cd_sl_cta_subtitle = craftdigitally_get_acf('service_landing_cta_subtitle', 'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad', $landing_id);
$cd_sl_cta_name_ph = craftdigitally_get_acf('service_landing_cta_name_placeholder', 'Name', $landing_id);
$cd_sl_cta_phone_ph = craftdigitally_get_acf('service_landing_cta_phone_placeholder', 'Phone', $landing_id);
$cd_sl_cta_email_ph = craftdigitally_get_acf('service_landing_cta_email_placeholder', 'Email', $landing_id);
$cd_sl_cta_service_ph = craftdigitally_get_acf('service_landing_cta_service_placeholder', 'Service', $landing_id);
$cd_sl_cta_message_ph = craftdigitally_get_acf('service_landing_cta_message_placeholder', 'Message', $landing_id);
$cd_sl_cta_submit = craftdigitally_get_acf('service_landing_cta_submit_label', "Let's Connect", $landing_id);
?>

<main id="main-content" class="service-landing-page">
  <div class="main">
    <!-- Hero Section -->
    <div class="hero">
      <div class="container hero-service-container">
        <div class="headline-subhead">
          <p class="text-wrapper"><?php echo esc_html($cd_sl_hero_kicker); ?></p>
          <p class="div">
            <?php echo esc_html($cd_sl_hero_subhead); ?>
          </p>
        </div>
      </div>
    </div>

    <!-- Featured Service Section -->
    <div class="featured-case-study">
      <div class="container-2">
        <div class="right">
          <div class="div-2">
            <p class="p"><?php echo esc_html($cd_sl_featured_title); ?></p>
            <p class="text-wrapper-4">
              <?php echo esc_html($cd_sl_featured_desc); ?>
            </p>
          </div>
          <a href="<?php echo esc_url($cd_sl_featured_btn_link); ?>" class="button"><div class="btn btn-outline"><?php echo esc_html($cd_sl_featured_btn_label); ?></div></a>
        </div>
        <div class="img-wrapper" style="width: 645px; height: 400px; background: url(image.png); filter: drop-shadow(24px 32px 8px rgba(0, 0, 0, 0.15));">
          <img class="img" src="<?php echo esc_url($cd_sl_featured_img); ?>" alt="<?php echo esc_attr($cd_sl_featured_img_alt); ?>" />
        </div>
      </div>
    </div>

    <!-- Services Grid Section -->
    <section class="services-section" id="services">
      <div class="container">
        <div class="services-header">
          <h2 class="services-title"><?php echo esc_html($cd_sl_services_title); ?></h2>
          <p class="services-subtitle">
            <?php echo esc_html($cd_sl_services_subtitle); ?>
          </p>
        </div>

        <div class="services-grid">
          <?php
          if ($cd_sl_service_posts_q->have_posts()) {
            while ($cd_sl_service_posts_q->have_posts()) {
              $cd_sl_service_posts_q->the_post();
              $svc_id = get_the_ID();
              $svc_title = get_the_title();
              $svc_desc = craftdigitally_get_acf('service_short_desc', get_the_excerpt(), $svc_id);

              // Icon priority: ACF icon -> featured image -> default icon.
              $thumb_url = get_the_post_thumbnail_url($svc_id, 'medium');
              $svc_icon = craftdigitally_get_acf_image_url(
                'service_icon',
                $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/local-seo.png'),
                $svc_id
              );
              if (empty($svc_icon) || $svc_icon === (get_template_directory_uri() . '/assets/images/local-seo.png')) {
                $svc_icon = $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/local-seo.png');
              }

              $svc_icon_alt = craftdigitally_get_acf('service_icon_alt', $svc_title . ' icon', $svc_id);
              // For published services: pretty permalink like /service/local-seo/
              // For drafts/private (visible to admins in the grid): use preview link.
              $detail_url = get_permalink($svc_id);
              $svc_status = get_post_status($svc_id);
              if ($svc_status && $svc_status !== 'publish') {
                $detail_url = current_user_can('edit_post', $svc_id)
                  ? get_preview_post_link($svc_id)
                  : '#';
              }
              ?>
              <div class="service-card">
                <div class="service-icon-wrapper">
                  <img src="<?php echo esc_url($svc_icon); ?>" alt="<?php echo esc_attr($svc_icon_alt); ?>" class="service-icon" />
                </div>
                <h3 class="service-title"><?php echo esc_html($svc_title); ?></h3>
                <hr class="service-divider" />
                <p class="service-description"><?php echo esc_html($svc_desc); ?></p>
                <a href="<?php echo esc_url($detail_url); ?>" class="service-link">Learn More →</a>
              </div>
              <?php
            }
            wp_reset_postdata();
          } else {
            // Fallback to static cards if no posts exist (or none are publishable for the viewer).
            foreach ($cd_sl_services_cards as $card) :
              $icon = isset($card['icon']) ? $card['icon'] : '';
              if (is_array($icon) && !empty($icon['url'])) { $icon = $icon['url']; }
              elseif (is_numeric($icon)) { $icon = wp_get_attachment_image_url((int) $icon, 'full'); }
              $icon_alt = isset($card['icon_alt']) ? $card['icon_alt'] : '';
              ?>
              <div class="service-card">
                <div class="service-icon-wrapper">
                  <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($icon_alt); ?>" class="service-icon" />
                </div>
                <h3 class="service-title"><?php echo esc_html(isset($card['title']) ? $card['title'] : ''); ?></h3>
                <hr class="service-divider" />
                <p class="service-description"><?php echo esc_html(isset($card['desc']) ? $card['desc'] : ''); ?></p>
              </div>
            <?php endforeach;
          }
          ?>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <?php get_template_part('template-parts/sections/testimonial-slider-section'); ?>

    <!-- CTA Section -->
    <section class="cta-section" id="contact">
      <div class="container"> 
        <div class="cta-header">
          <h2 class="cta-title"><?php echo esc_html($cd_sl_cta_title); ?></h2>
          <p class="cta-subtitle">
            <?php echo esc_html($cd_sl_cta_subtitle); ?>
          </p>
        </div>

        <div class="cta-form-wrapper">
          <form method="post" action="#" class="cta-form">
            <div class="cta-form-row">
              <input type="text" name="name" placeholder="<?php echo esc_attr($cd_sl_cta_name_ph); ?>" required class="cta-input" />
              <input type="tel" name="phone" placeholder="<?php echo esc_attr($cd_sl_cta_phone_ph); ?>" required class="cta-input" />
            </div>
            <input type="email" name="email" placeholder="<?php echo esc_attr($cd_sl_cta_email_ph); ?>" required class="cta-input" />
            <input type="text" name="service" placeholder="<?php echo esc_attr($cd_sl_cta_service_ph); ?>" class="cta-input" />
            <textarea name="message" placeholder="<?php echo esc_attr($cd_sl_cta_message_ph); ?>" rows="5" class="cta-input cta-textarea"></textarea>
            <button type="submit" class="btn btn-outline cta-submit"><?php echo esc_html($cd_sl_cta_submit); ?></button>
          </form>
        </div>
      </div>
    </section>
  </div>
</main>

<?php
get_footer();
?>
