<?php
/**
 * Create Default (Demo) Service Posts for CraftDigitally Theme
 *
 * Creates demo service posts as DRAFT so the frontend does not change until user publishes.
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

add_action('admin_notices', 'craftdigitally_default_services_notice');
function craftdigitally_default_services_notice() {
  $services_created = get_option('craftdigitally_default_services_created', false);

  if (!$services_created && current_user_can('manage_options')) {
    ?>
    <div class="notice notice-info is-dismissible">
      <p><strong>CraftDigitally Theme:</strong> Would you like to create demo Service posts (Draft) so you can edit them in <em>Services</em>?</p>
      <p>
        <a href="<?php echo admin_url('admin.php?page=create-default-services'); ?>" class="button button-primary">Create Demo Services</a>
        <a href="<?php echo add_query_arg('craftdigitally_dismiss_services', '1'); ?>" class="button">Not Now</a>
      </p>
    </div>
    <?php
  }
}

add_action('admin_init', 'craftdigitally_dismiss_services_notice');
function craftdigitally_dismiss_services_notice() {
  if (isset($_GET['craftdigitally_dismiss_services'])) {
    update_option('craftdigitally_default_services_created', 'dismissed');
    wp_redirect(remove_query_arg('craftdigitally_dismiss_services'));
    exit;
  }
}

add_action('admin_menu', 'craftdigitally_default_services_menu');
function craftdigitally_default_services_menu() {
  add_theme_page(
    'Create Demo Services',
    'Create Services',
    'manage_options',
    'create-default-services',
    'craftdigitally_create_default_services_page',
    99
  );
}

function craftdigitally_create_default_services_page() {
  if (isset($_POST['create_default_services']) && check_admin_referer('create_default_services_nonce')) {
    $created = craftdigitally_create_default_services();
    if ($created) {
      echo '<div class="notice notice-success"><p>Demo services created as <strong>Draft</strong>. <a href="' . admin_url('edit.php?post_type=service') . '">View Services</a></p></div>';
    } else {
      echo '<div class="notice notice-warning"><p>Services already exist, so demo services were not created.</p></div>';
    }
  }
  ?>
  <div class="wrap">
    <h1>Create Demo Service Posts (Draft)</h1>
    <p>This will create <strong>6 draft service posts</strong> so you can edit them under <em>Services → All Services</em>. Draft posts do not show on the frontend until you publish them.</p>

    <form method="post" style="margin-top: 20px;">
      <?php wp_nonce_field('create_default_services_nonce'); ?>
      <input type="submit" name="create_default_services" class="button button-primary button-hero" value="Create Demo Services (Draft)">
    </form>
  </div>
  <?php
}

/**
 * Create demo service posts (draft) once.
 *
 * @return bool True when created; false when skipped.
 */
function craftdigitally_create_default_services() {
  // If we already created or user dismissed, don't create again.
  $flag = get_option('craftdigitally_default_services_created', false);
  if ($flag) {
    return false;
  }

  // If site already has service posts, do not seed.
  $existing = get_posts(array(
    'post_type' => 'service',
    'post_status' => array('publish', 'draft', 'pending', 'private', 'future'),
    'posts_per_page' => 1,
    'fields' => 'ids',
  ));
  if (!empty($existing)) {
    update_option('craftdigitally_default_services_created', 'skipped_existing_services');
    return false;
  }

  $current_user = wp_get_current_user();
  $author_id = ($current_user && !empty($current_user->ID)) ? (int) $current_user->ID : 1;

  $services = array(
    array(
      'title' => 'Local SEO',
      'icon' => 'local-seo.png',
      'icon_alt' => 'Local SEO icon',
      'short_desc' => 'Turn your local presence into lasting visibility. Align your listings, keywords, and on-page SEO so your business becomes the clear choice in your area.',
      'hero_headline' => 'Local SEO That Drives Real Customers to Your Business',
      'hero_subhead' => 'Get more calls, more leads, and more foot traffic from the people already searching for what you offer in your city.',
      'problem_headline' => 'Is Your Business Invisible to Nearby Customers?',
      'problem_text' => '46% of all Google searches have "local intent" (people looking for services near them). If your business doesn\'t appear in the top 3 spots of the "Google Map Pack," you typically don\'t exist to those customers.

At CraftDigitally, we don\'t just "do SEO." We engineer your online presence to ensure that when local customers search for your services, you are the first brand they see and the first one they call.',
    ),
    array(
      'title' => 'Link Building',
      'icon' => 'link-building.png',
      'icon_alt' => 'Link Building icon',
      'short_desc' => 'Build trust and authority with ethical, high-quality backlinks that strengthen your rankings and position your brand as a credible voice in your industry.',
      'hero_headline' => 'Link Building That Builds Authority',
      'hero_subhead' => 'Earn high-quality backlinks that boost your rankings and establish your brand as a trusted industry leader.',
      'problem_headline' => 'Struggling to Build Quality Backlinks?',
      'problem_text' => 'Quality backlinks are essential for SEO success, but building them ethically and effectively requires expertise and strategy.',
    ),
    array(
      'title' => 'Ecommerce SEO',
      'icon' => 'emommerce-seo.png',
      'icon_alt' => 'Ecommerce SEO icon',
      'short_desc' => 'Make your products easy to find and effortless to buy. Optimise your store\'s structure, speed, and search visibility for better reach and higher conversions.',
      'hero_headline' => 'Ecommerce SEO That Drives Sales',
      'hero_subhead' => 'Optimize your online store to rank higher, attract more customers, and convert visitors into buyers.',
      'problem_headline' => 'Is Your Online Store Getting Lost in Search?',
      'problem_text' => 'With millions of products online, standing out requires strategic SEO that makes your store discoverable and trustworthy.',
    ),
    array(
      'title' => 'International SEO',
      'icon' => 'international-seo.png',
      'icon_alt' => 'International SEO icon',
      'short_desc' => 'Expand your reach across borders with tailored multilingual and regional strategies that make your brand visible - and relevant - worldwide.',
      'hero_headline' => 'International SEO for Global Reach',
      'hero_subhead' => 'Expand your business across borders with SEO strategies tailored to different markets and languages.',
      'problem_headline' => 'Ready to Go Global But Not Sure How?',
      'problem_text' => 'International expansion requires more than translation—it needs strategic SEO that understands local markets and search behaviors.',
    ),
    array(
      'title' => 'Small Business SEO',
      'icon' => 'small-business-seo.png',
      'icon_alt' => 'Small Business SEO icon',
      'short_desc' => 'Simplify growth with SEO built for your scale – clear, focused strategies that make your website a steady source of visibility and leads.',
      'hero_headline' => 'Small Business SEO Made Simple',
      'hero_subhead' => 'Get clear, focused SEO strategies designed for small businesses that deliver real results without overwhelming complexity.',
      'problem_headline' => 'Feeling Overwhelmed by SEO?',
      'problem_text' => 'Small businesses need SEO that fits their budget and resources, not enterprise-level complexity.',
    ),
    array(
      'title' => 'Social Media Services',
      'icon' => 'social-media-services.png',
      'icon_alt' => 'Social Media Services icon',
      'short_desc' => 'Create connection through clarity – we craft consistent, on-brand social content that strengthens visibility, trust, and engagement across every platform.',
      'hero_headline' => 'Social Media That Builds Your Brand',
      'hero_subhead' => 'Create consistent, engaging social content that strengthens your brand and drives real business results.',
      'problem_headline' => 'Is Your Social Media Strategy Working?',
      'problem_text' => 'Effective social media requires more than posting—it needs strategy, consistency, and content that resonates with your audience.',
    ),
  );

  foreach ($services as $svc) {
    $post_id = wp_insert_post(array(
      'post_title' => $svc['title'],
      'post_content' => '<p>' . esc_html($svc['short_desc']) . '</p>',
      'post_excerpt' => $svc['short_desc'],
      'post_status' => 'draft',
      'post_type' => 'service',
      'post_author' => $author_id,
    ));

    if (!$post_id || is_wp_error($post_id)) {
      continue;
    }

    // Prefill ACF fields (if ACF is active). Frontend still falls back if user clears values.
    if (function_exists('update_field')) {
      update_field('service_icon', get_template_directory_uri() . '/assets/images/' . $svc['icon'], $post_id);
      update_field('service_icon_alt', $svc['icon_alt'], $post_id);
      update_field('service_short_desc', $svc['short_desc'], $post_id);
      update_field('service_hero_headline', $svc['hero_headline'], $post_id);
      update_field('service_hero_subhead', $svc['hero_subhead'], $post_id);
      update_field('service_problem_headline', $svc['problem_headline'], $post_id);
      update_field('service_problem_text', $svc['problem_text'], $post_id);
      
      // Set featured image if icon exists
      $icon_path = get_template_directory() . '/assets/images/' . $svc['icon'];
      if (file_exists($icon_path)) {
        $attachment_id = craftdigitally_upload_image_from_path($icon_path, $post_id, $svc['title'] . ' Icon');
        if ($attachment_id) {
          set_post_thumbnail($post_id, $attachment_id);
        }
      }
    }
  }

  update_option('craftdigitally_default_services_created', '1');
  return true;
}

/**
 * Helper function to upload image from file path
 */
function craftdigitally_upload_image_from_path($file_path, $post_id, $title = '') {
  if (!file_exists($file_path)) {
    return false;
  }

  $filename = basename($file_path);
  $upload_file = wp_upload_bits($filename, null, file_get_contents($file_path));

  if (!$upload_file['error']) {
    $wp_filetype = wp_check_filetype($filename, null);
    $attachment = array(
      'post_mime_type' => $wp_filetype['type'],
      'post_parent' => $post_id,
      'post_title' => $title ? $title : preg_replace('/\.[^.]+$/', '', $filename),
      'post_content' => '',
      'post_status' => 'inherit'
    );

    $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $post_id);
    if (!is_wp_error($attachment_id)) {
      require_once(ABSPATH . 'wp-admin/includes/image.php');
      $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
      wp_update_attachment_metadata($attachment_id, $attachment_data);
      return $attachment_id;
    }
  }

  return false;
}
