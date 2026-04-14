<?php
/**
 * Create Default (Demo) Posts for CraftDigitally Theme
 *
 * Creates demo blog posts as DRAFT so the frontend does not change until user publishes.
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

add_action('admin_notices', 'craftdigitally_default_posts_notice');
function craftdigitally_default_posts_notice() {
  $posts_created = get_option('craftdigitally_default_posts_created', false);

  if (!$posts_created && current_user_can('manage_options')) {
    ?>
    <div class="notice notice-info is-dismissible">
      <p><strong>CraftDigitally Theme:</strong> Would you like to create demo Blog posts (Draft) so you can edit them in <em>Posts</em>?</p>
      <p>
        <a href="<?php echo admin_url('admin.php?page=create-default-posts'); ?>" class="button button-primary">Create Demo Posts</a>
        <a href="<?php echo add_query_arg('craftdigitally_dismiss_posts', '1'); ?>" class="button">Not Now</a>
      </p>
    </div>
    <?php
  }
}

add_action('admin_init', 'craftdigitally_dismiss_posts_notice');
function craftdigitally_dismiss_posts_notice() {
  if (isset($_GET['craftdigitally_dismiss_posts'])) {
    update_option('craftdigitally_default_posts_created', 'dismissed');
    wp_redirect(remove_query_arg('craftdigitally_dismiss_posts'));
    exit;
  }
}

add_action('admin_menu', 'craftdigitally_default_posts_menu');
function craftdigitally_default_posts_menu() {
  add_theme_page(
    'Create Demo Posts',
    'Create Posts',
    'manage_options',
    'create-default-posts',
    'craftdigitally_create_default_posts_page',
    98
  );
}

function craftdigitally_create_default_posts_page() {
  if (isset($_POST['create_default_posts']) && check_admin_referer('create_default_posts_nonce')) {
    $created = craftdigitally_create_default_posts();
    if ($created) {
      echo '<div class="notice notice-success"><p>Demo posts created as <strong>Draft</strong>. <a href="' . admin_url('edit.php') . '">View Posts</a></p></div>';
    } else {
      echo '<div class="notice notice-warning"><p>Posts already exist, so demo posts were not created.</p></div>';
    }
  }
  ?>
  <div class="wrap">
    <h1>Create Demo Blog Posts (Draft)</h1>
    <p>This will create <strong>7 draft posts</strong> so you can edit them under <em>Posts → All Posts</em>. Draft posts do not show on the frontend until you publish them.</p>

    <form method="post" style="margin-top: 20px;">
      <?php wp_nonce_field('create_default_posts_nonce'); ?>
      <input type="submit" name="create_default_posts" class="button button-primary button-hero" value="Create Demo Posts (Draft)">
    </form>
  </div>
  <?php
}

/**
 * Create demo posts (draft) once.
 *
 * @return bool True when created; false when skipped.
 */
function craftdigitally_create_default_posts() {
  // If we already created or user dismissed, don't create again.
  $flag = get_option('craftdigitally_default_posts_created', false);
  if ($flag) {
    return false;
  }

  // If site already has posts, do not seed.
  $existing = get_posts(array(
    'post_type' => 'post',
    'post_status' => array('publish', 'draft', 'pending', 'private', 'future'),
    'posts_per_page' => 1,
    'fields' => 'ids',
  ));
  if (!empty($existing)) {
    update_option('craftdigitally_default_posts_created', 'skipped_existing_posts');
    return false;
  }

  $current_user = wp_get_current_user();
  $author_id = ($current_user && !empty($current_user->ID)) ? (int) $current_user->ID : 1;

  // Ensure a category exists for nicer defaults.
  $cat_name = 'LOCAL SEO';
  $cat = term_exists($cat_name, 'category');
  if (!$cat) {
    $cat = wp_insert_term($cat_name, 'category');
  }
  $cat_id = (is_array($cat) && !empty($cat['term_id'])) ? (int) $cat['term_id'] : 0;

  $today = date('d/m/Y');
  $titles = array(
    'Why Local SEO Matters for Small Businesses in 2025',
    '10 Proven Local SEO Strategies to Attract More Nearby Customers',
    'Google Business Profile Optimization Checklist',
    'How to Rank in the Local 3-Pack: Step-by-Step',
    'Common Local SEO Mistakes (and How to Fix Them)',
    'Local Citations: What They Are & Why They Matter',
    'How to Get More Reviews Without Sounding Pushy',
  );

  $excerpt = "If you run a small or medium business today, competing locally is tougher than ever. Customers don't browse directories anymore — they search on Google, check reviews, and decide fast.";

  foreach ($titles as $t) {
    $post_id = wp_insert_post(array(
      'post_title' => $t,
      'post_content' => '<p>' . esc_html($excerpt) . '</p>',
      'post_excerpt' => $excerpt,
      'post_status' => 'draft',
      'post_type' => 'post',
      'post_author' => $author_id,
    ));

    if (!$post_id || is_wp_error($post_id)) {
      continue;
    }

    if ($cat_id) {
      wp_set_post_terms((int) $post_id, array($cat_id), 'category', false);
    }

    // Prefill ACF fields (if ACF is active). Frontend still falls back if user clears values.
    if (function_exists('update_field')) {
      update_field('blog_title', $t, $post_id);
      update_field('blog_category', $cat_name, $post_id);
      $by = ($current_user && !empty($current_user->display_name)) ? ('By ' . $current_user->display_name) : 'By Admin';
      update_field('blog_author', $by, $post_id);
      update_field('blog_date', $today, $post_id);
      update_field('blog_read_time', '3 min read', $post_id);
      update_field('blog_intro', $excerpt, $post_id);
      update_field('blog_cta_title', 'Ready to Dominate Your Competition?', $post_id);
      update_field('blog_cta_text', 'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords', $post_id);
      update_field('blog_cta_button_label', 'Book a Free Consult', $post_id);
      update_field('blog_author_tag', 'LOCAL SEO EXPERT', $post_id);
      $default_author_name = ($current_user && !empty($current_user->display_name) && strtolower($current_user->display_name) !== 'admin')
        ? $current_user->display_name
        : 'Yashasvi Zala';
      update_field('blog_author_name', $default_author_name, $post_id);
      update_field(
        'blog_author_bio',
        'Passionate about helping local businesses get discovered, Yashasvi creates simple, effective SEO strategies that increase foot traffic, calls, and online visibility.',
        $post_id
      );
    }
  }

  update_option('craftdigitally_default_posts_created', '1');
  return true;
}

