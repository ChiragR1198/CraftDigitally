<?php
/**
 * Template Name: Blog Landing Page
 * Template Post Type: page
 * 
 * @package CraftDigitally
 */

get_header();

// ACF overrides (falls back to current hardcoded output to avoid any UI/text/image changes).
$cd_blog_hero_kicker = craftdigitally_get_acf('blog_landing_hero_kicker', 'Insights That Help Your Local Business Grow Online');
$cd_blog_hero_subhead = craftdigitally_get_acf('blog_landing_hero_subhead', 'Expert tips, practical strategies, and real examples to help you improve visibility, attract nearby customers, and turn searches into sales.');
$cd_blog_hero_cta_label = craftdigitally_get_acf('blog_landing_hero_cta_label', 'Book a Free Consult');
$cd_blog_hero_cta_link = craftdigitally_get_acf('blog_landing_hero_cta_link', '#contact');

$cd_blog_featured_title = craftdigitally_get_acf('blog_landing_featured_title', 'Why Local SEO Matters for Small Businesses in 2025');
$cd_blog_featured_category = craftdigitally_get_acf('blog_landing_featured_category', 'LOCAL SEO');
$cd_blog_featured_date = craftdigitally_get_acf('blog_landing_featured_date', date('d/m/Y'));
$cd_blog_featured_excerpt = craftdigitally_get_acf('blog_landing_featured_excerpt', "If you run a small or medium business today, competing locally is tougher than ever. Customers don't browse newspapers or directories anymore — they simply search on Google, check your reviews, and decide in seconds whether to trust you.");
$cd_blog_featured_btn_label = craftdigitally_get_acf('blog_landing_featured_button_label', 'Read more');
$cd_blog_featured_btn_url = craftdigitally_get_acf('blog_landing_featured_button_url', '#');
$cd_blog_featured_img = craftdigitally_get_acf_image_url('blog_landing_featured_image', get_template_directory_uri() . '/assets/images/bookimage.png');
$cd_blog_featured_img_alt = craftdigitally_get_acf('blog_landing_featured_image_alt', 'Featured Blog Post');

$cd_blog_default_cards = array(
  array(
    'image' => get_template_directory_uri() . '/assets/images/bookimage.png',
    'image_alt' => 'Blog Post 1',
    'category' => 'LOCAL SEO',
    'date' => date('d/m/Y'),
    'title' => '10 Proven Local SEO Strategies to Attract More Nearby Customers',
    'button_label' => 'Read More',
    'url' => '#', // replaced below with Blog Detail page URL (prefilled mode)
  ),
  array(
    'image' => get_template_directory_uri() . '/assets/images/blog1.png',
    'image_alt' => 'Blog Post 2',
    'category' => 'LOCAL SEO',
    'date' => date('d/m/Y'),
    'title' => '10 Proven Local SEO Strategies to Attract More Nearby Customers',
    'button_label' => 'Read More',
    'url' => '#', // replaced below with Blog Detail page URL (prefilled mode)
  ),
  array(
    'image' => get_template_directory_uri() . '/assets/images/blog2.png',
    'image_alt' => 'Blog Post 3',
    'category' => 'LOCAL SEO',
    'date' => date('d/m/Y'),
    'title' => '10 Proven Local SEO Strategies to Attract More Nearby Customers',
    'button_label' => 'Read More',
    'url' => '#', // replaced below with Blog Detail page URL (prefilled mode)
  ),
  array(
    'image' => get_template_directory_uri() . '/assets/images/blog3.png',
    'image_alt' => 'Blog Post 4',
    'category' => 'LOCAL SEO',
    'date' => date('d/m/Y'),
    'title' => '10 Proven Local SEO Strategies to Attract More Nearby Customers',
    'button_label' => 'Read More',
    'url' => '#', // replaced below with Blog Detail page URL (prefilled mode)
  ),
  array(
    'image' => get_template_directory_uri() . '/assets/images/blog4.png',
    'image_alt' => 'Blog Post 5',
    'category' => 'LOCAL SEO',
    'date' => date('d/m/Y'),
    'title' => '10 Proven Local SEO Strategies to Attract More Nearby Customers',
    'button_label' => 'Read More',
    'url' => '#', // replaced below with Blog Detail page URL (prefilled mode)
  ),
  array(
    'image' => get_template_directory_uri() . '/assets/images/blog5.png',
    'image_alt' => 'Blog Post 6',
    'category' => 'LOCAL SEO',
    'date' => date('d/m/Y'),
    'title' => '10 Proven Local SEO Strategies to Attract More Nearby Customers',
    'button_label' => 'Read More',
    'url' => '#', // replaced below with Blog Detail page URL (prefilled mode)
  ),
);
$cd_blog_cards = craftdigitally_get_acf_array('blog_landing_grid_cards', $cd_blog_default_cards);

// Dynamic posts (default WP "post" type) — these should appear automatically when you publish posts.
// Fallback to the ACF prefilled cards above when no posts exist.
// NOTE:
// Blog posts should use their native permalinks:
// - Landing: /blog/
// - Single:  /blog/{post-name}/

// Prefilled mode URL attachment:
// If user didn't set URLs in ACF (or default is '#'), keep them pointing to /blog/ (safe fallback).
if (empty($cd_blog_featured_btn_url) || $cd_blog_featured_btn_url === '#') {
  $cd_blog_featured_btn_url = home_url('/blog/');
}
if (is_array($cd_blog_cards) && !empty($cd_blog_cards)) {
  foreach ($cd_blog_cards as $i => $card) {
    if (!is_array($card)) {
      continue;
    }
    if (empty($card['url']) || $card['url'] === '#') {
      $cd_blog_cards[$i]['url'] = home_url('/blog/');
    }
    if (empty($card['button_label'])) {
      $cd_blog_cards[$i]['button_label'] = 'Read More';
    }
    if (empty($card['image_alt']) && !empty($card['title'])) {
      $cd_blog_cards[$i]['image_alt'] = $card['title'];
    }
  }
}

$cd_blog_posts_status = current_user_can('edit_posts')
  ? array('publish', 'draft', 'pending', 'future', 'private')
  : array('publish');

$cd_blog_posts_q = new WP_Query(array(
  'post_type' => 'post',
  'post_status' => $cd_blog_posts_status,
  'posts_per_page' => 7, // 1 featured + 6 cards
  'ignore_sticky_posts' => true,
));

if ($cd_blog_posts_q->have_posts()) {
  $featured_post = $cd_blog_posts_q->posts[0];
  $featured_id = (int) $featured_post->ID;

  $cd_blog_featured_title = craftdigitally_get_acf('blog_title', get_the_title($featured_id), $featured_id);

  $default_cat = 'LOCAL SEO';
  $wp_cats = get_the_category($featured_id);
  if (!empty($wp_cats) && !is_wp_error($wp_cats) && !empty($wp_cats[0]->name)) {
    $default_cat = $wp_cats[0]->name;
  }
  $cd_blog_featured_category = craftdigitally_get_acf('blog_category', $default_cat, $featured_id);
  $cd_blog_featured_date = get_the_date('d/m/Y', $featured_id);
  $cd_blog_featured_excerpt = craftdigitally_get_acf('blog_intro', get_the_excerpt($featured_id), $featured_id);
  $cd_blog_featured_btn_label = craftdigitally_get_acf('blog_landing_featured_button_label', 'Read more');
  $cd_blog_featured_btn_url = get_permalink($featured_id);
  $featured_status = get_post_status($featured_id);
  if ($featured_status && $featured_status !== 'publish') {
    $cd_blog_featured_btn_url = current_user_can('edit_post', $featured_id) ? get_preview_post_link($featured_id) : home_url('/blog/');
  }

  $thumb_url = get_the_post_thumbnail_url($featured_id, 'large');
  $cd_blog_featured_img = craftdigitally_get_acf_image_url(
    'blog_featured_image',
    $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/bookimage.png'),
    $featured_id
  );
  $cd_blog_featured_img_alt = craftdigitally_get_acf('blog_featured_image_alt', get_the_title($featured_id), $featured_id);
}

$cd_blog_cards_resolved = array();
if ($cd_blog_posts_q->have_posts()) {
  // Build cards from posts (skip first featured)
  $posts = $cd_blog_posts_q->posts;
  for ($i = 1; $i < count($posts) && count($cd_blog_cards_resolved) < 6; $i++) {
    $pid = (int) $posts[$i]->ID;
    $default_cat = 'LOCAL SEO';
    $wp_cats = get_the_category($pid);
    if (!empty($wp_cats) && !is_wp_error($wp_cats) && !empty($wp_cats[0]->name)) {
      $default_cat = $wp_cats[0]->name;
    }
    $post_url = get_permalink($pid);
    $pstatus = get_post_status($pid);
    if ($pstatus && $pstatus !== 'publish') {
      $post_url = current_user_can('edit_post', $pid) ? get_preview_post_link($pid) : home_url('/blog/');
    }
    $thumb_url = get_the_post_thumbnail_url($pid, 'medium');
    $cd_blog_cards_resolved[] = array(
      'image_url' => craftdigitally_get_acf_image_url(
        'blog_featured_image',
        $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/bookimage.png'),
        $pid
      ),
      'image_alt' => craftdigitally_get_acf('blog_featured_image_alt', get_the_title($pid), $pid),
      'category' => craftdigitally_get_acf('blog_category', $default_cat, $pid),
      'date' => get_the_date('d/m/Y', $pid),
      'title' => craftdigitally_get_acf('blog_title', get_the_title($pid), $pid),
      'button_label' => 'Read More',
      'url' => $post_url,
    );
  }
}

// If not enough posts, fill remaining cards from ACF fallback (prefilled grid cards).
while (count($cd_blog_cards_resolved) < 6) {
  $idx = count($cd_blog_cards_resolved);
  $fallback = isset($cd_blog_cards[$idx]) ? $cd_blog_cards[$idx] : (isset($cd_blog_default_cards[$idx]) ? $cd_blog_default_cards[$idx] : array());
  $img = isset($fallback['image']) ? $fallback['image'] : '';
  if (is_array($img) && !empty($img['url'])) {
    $img = $img['url'];
  } elseif (is_numeric($img)) {
    $img = wp_get_attachment_image_url((int) $img, 'full');
  }
  $cd_blog_cards_resolved[] = array(
    'image_url' => $img,
    'image_alt' => isset($fallback['image_alt']) ? $fallback['image_alt'] : '',
    'category' => isset($fallback['category']) ? $fallback['category'] : 'LOCAL SEO',
    'date' => isset($fallback['date']) ? $fallback['date'] : date('d/m/Y'),
    'title' => isset($fallback['title']) ? $fallback['title'] : '',
    'button_label' => isset($fallback['button_label']) ? $fallback['button_label'] : 'Read More',
    'url' => isset($fallback['url']) ? $fallback['url'] : '#',
  );
}

$cd_blog_cta_title = craftdigitally_get_acf('blog_landing_cta_title', 'Ready to Dominate Your Competition?');
$cd_blog_cta_subtitle = craftdigitally_get_acf('blog_landing_cta_subtitle', 'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords, and our business has grown significantly. Their SEO expertise is unmatched in Ahmedabad');
$cd_blog_cta_name_ph = craftdigitally_get_acf('blog_landing_cta_name_placeholder', 'Name');
$cd_blog_cta_phone_ph = craftdigitally_get_acf('blog_landing_cta_phone_placeholder', 'Phone');
$cd_blog_cta_email_ph = craftdigitally_get_acf('blog_landing_cta_email_placeholder', 'Email');
$cd_blog_cta_service_ph = craftdigitally_get_acf('blog_landing_cta_service_placeholder', 'Service');
$cd_blog_cta_message_ph = craftdigitally_get_acf('blog_landing_cta_message_placeholder', 'Message');
$cd_blog_cta_btn_label = craftdigitally_get_acf('blog_landing_cta_button_label', "Let's Connect");
?>

<main id="main-content" class="blog-landing-page">
  <div class="main">
    <!-- Hero Section -->
    <div class="hero">
      <div class="container hero-blog-container">
        <div class="headline-subhead">
          <p class="text-wrapper"><?php echo esc_html($cd_blog_hero_kicker); ?></p>
          <p class="div">
            <?php echo esc_html($cd_blog_hero_subhead); ?>
          </p>
        </div>
        <a href="<?php echo esc_url($cd_blog_hero_cta_link); ?>" class="button"><div class="text-wrapper-2"><?php echo esc_html($cd_blog_hero_cta_label); ?></div></a>
      </div>
    </div>

    <!-- Featured Blog Post Section -->
    <div class="featured-case-study">
      <div class="container-2">
        <div class="right">
          <div class="div-2">
            <p class="p"><?php echo esc_html($cd_blog_featured_title); ?></p>
            <div class="div-2">
              <div class="frame">
                <div class="local-SEO"><?php echo esc_html($cd_blog_featured_category); ?></div>
                <div class="text-wrapper-3"><?php echo esc_html($cd_blog_featured_date); ?></div>
              </div>
              <div class="frame-2"></div>
            </div>
            <p class="text-wrapper-4">
              <?php echo esc_html($cd_blog_featured_excerpt); ?>
            </p>
          </div>
          <a href="<?php echo esc_url($cd_blog_featured_btn_url); ?>" class="div-wrapper"><div class="text-wrapper-5"><?php echo esc_html($cd_blog_featured_btn_label); ?></div></a>
        </div>
        <div class="img-wrapper">
          <img class="img" src="<?php echo esc_url($cd_blog_featured_img); ?>" alt="<?php echo esc_attr($cd_blog_featured_img_alt); ?>" />
        </div>
      </div>
    </div>

    <!-- Blog Grid Section -->
    <div class="blog">
      <div class="frame-wrapper">
        <div class="frame-3">
          <div class="frame-4">
            <div class="thumbnail">
              <?php $card0 = isset($cd_blog_cards_resolved[0]) ? $cd_blog_cards_resolved[0] : array(); ?>
              <img src="<?php echo esc_url(isset($card0['image_url']) ? $card0['image_url'] : ''); ?>" alt="<?php echo esc_attr(isset($card0['image_alt']) ? $card0['image_alt'] : ''); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
            </div>
            <div class="div-3">
              <div class="div-2">
                <div class="frame">
                  <div class="local-SEO"><?php echo esc_html(isset($card0['category']) ? $card0['category'] : 'LOCAL SEO'); ?></div>
                  <div class="text-wrapper-3"><?php echo esc_html(isset($card0['date']) ? $card0['date'] : date('d/m/Y')); ?></div>
                </div>
                <div class="frame-2"></div>
              </div>
              <div class="div-3">
                <p class="text-wrapper-6"><?php echo esc_html(isset($card0['title']) ? $card0['title'] : ''); ?></p>
                <a href="<?php echo esc_url(isset($card0['url']) ? $card0['url'] : '#'); ?>" class="button-2"><div class="text-wrapper-7"><?php echo esc_html(isset($card0['button_label']) ? $card0['button_label'] : 'Read More'); ?></div></a>
              </div>
            </div>
          </div>
          <div class="frame-5">
            <div class="thumbnail-2">
              <?php $card1 = isset($cd_blog_cards_resolved[1]) ? $cd_blog_cards_resolved[1] : array(); ?>
              <img src="<?php echo esc_url(isset($card1['image_url']) ? $card1['image_url'] : ''); ?>" alt="<?php echo esc_attr(isset($card1['image_alt']) ? $card1['image_alt'] : ''); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
            </div>
            <div class="div-3">
              <div class="div-2">
                <div class="frame">
                  <div class="local-SEO"><?php echo esc_html(isset($card1['category']) ? $card1['category'] : 'LOCAL SEO'); ?></div>
                  <div class="text-wrapper-3"><?php echo esc_html(isset($card1['date']) ? $card1['date'] : date('d/m/Y')); ?></div>
                </div>
                <div class="frame-2"></div>
              </div>
              <div class="div-3">
                <p class="text-wrapper-6"><?php echo esc_html(isset($card1['title']) ? $card1['title'] : ''); ?></p>
                <a href="<?php echo esc_url(isset($card1['url']) ? $card1['url'] : '#'); ?>" class="button-2"><div class="text-wrapper-7"><?php echo esc_html(isset($card1['button_label']) ? $card1['button_label'] : 'Read More'); ?></div></a>
              </div>
            </div>
          </div>
          <div class="frame-6">
            <div class="thumbnail-3">
              <?php $card2 = isset($cd_blog_cards_resolved[2]) ? $cd_blog_cards_resolved[2] : array(); ?>
              <img src="<?php echo esc_url(isset($card2['image_url']) ? $card2['image_url'] : ''); ?>" alt="<?php echo esc_attr(isset($card2['image_alt']) ? $card2['image_alt'] : ''); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
            </div>
            <div class="div-3">
              <div class="div-2">
                <div class="frame">
                  <div class="local-SEO"><?php echo esc_html(isset($card2['category']) ? $card2['category'] : 'LOCAL SEO'); ?></div>
                  <div class="text-wrapper-3"><?php echo esc_html(isset($card2['date']) ? $card2['date'] : date('d/m/Y')); ?></div>
                </div>
                <div class="frame-2"></div>
              </div>
              <div class="div-3">
                <p class="text-wrapper-6"><?php echo esc_html(isset($card2['title']) ? $card2['title'] : ''); ?></p>
                <a href="<?php echo esc_url(isset($card2['url']) ? $card2['url'] : '#'); ?>" class="button-2"><div class="text-wrapper-7"><?php echo esc_html(isset($card2['button_label']) ? $card2['button_label'] : 'Read More'); ?></div></a>
              </div>
            </div>
          </div>
          <div class="frame-7">
            <div class="thumbnail-4">
              <?php $card3 = isset($cd_blog_cards_resolved[3]) ? $cd_blog_cards_resolved[3] : array(); ?>
              <img src="<?php echo esc_url(isset($card3['image_url']) ? $card3['image_url'] : ''); ?>" alt="<?php echo esc_attr(isset($card3['image_alt']) ? $card3['image_alt'] : ''); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
            </div>
            <div class="div-3">
              <div class="div-2">
                <div class="frame">
                  <div class="local-SEO"><?php echo esc_html(isset($card3['category']) ? $card3['category'] : 'LOCAL SEO'); ?></div>
                  <div class="text-wrapper-3"><?php echo esc_html(isset($card3['date']) ? $card3['date'] : date('d/m/Y')); ?></div>
                </div>
                <div class="frame-2"></div>
              </div>
              <div class="div-3">
                <p class="text-wrapper-6"><?php echo esc_html(isset($card3['title']) ? $card3['title'] : ''); ?></p>
                <a href="<?php echo esc_url(isset($card3['url']) ? $card3['url'] : '#'); ?>" class="button-2"><div class="text-wrapper-7"><?php echo esc_html(isset($card3['button_label']) ? $card3['button_label'] : 'Read More'); ?></div></a>
              </div>
            </div>
          </div>
          <div class="frame-8">
            <div class="thumbnail-5">
              <?php $card4 = isset($cd_blog_cards_resolved[4]) ? $cd_blog_cards_resolved[4] : array(); ?>
              <img src="<?php echo esc_url(isset($card4['image_url']) ? $card4['image_url'] : ''); ?>" alt="<?php echo esc_attr(isset($card4['image_alt']) ? $card4['image_alt'] : ''); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
            </div>
            <div class="div-3">
              <div class="div-2">
                <div class="frame">
                  <div class="local-SEO"><?php echo esc_html(isset($card4['category']) ? $card4['category'] : 'LOCAL SEO'); ?></div>
                  <div class="text-wrapper-3"><?php echo esc_html(isset($card4['date']) ? $card4['date'] : date('d/m/Y')); ?></div>
                </div>
                <div class="frame-2"></div>
              </div>
              <div class="div-3">
                <p class="text-wrapper-6"><?php echo esc_html(isset($card4['title']) ? $card4['title'] : ''); ?></p>
                <a href="<?php echo esc_url(isset($card4['url']) ? $card4['url'] : '#'); ?>" class="button-2"><div class="text-wrapper-7"><?php echo esc_html(isset($card4['button_label']) ? $card4['button_label'] : 'Read More'); ?></div></a>
              </div>
            </div>
          </div>
          <div class="frame-9">
            <div class="thumbnail-6">
              <?php $card5 = isset($cd_blog_cards_resolved[5]) ? $cd_blog_cards_resolved[5] : array(); ?>
              <img src="<?php echo esc_url(isset($card5['image_url']) ? $card5['image_url'] : ''); ?>" alt="<?php echo esc_attr(isset($card5['image_alt']) ? $card5['image_alt'] : ''); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
            </div>
            <div class="div-3">
              <div class="div-2">
                <div class="frame">
                  <div class="local-SEO"><?php echo esc_html(isset($card5['category']) ? $card5['category'] : 'LOCAL SEO'); ?></div>
                  <div class="text-wrapper-3"><?php echo esc_html(isset($card5['date']) ? $card5['date'] : date('d/m/Y')); ?></div>
                </div>
                <div class="frame-2"></div>
              </div>
              <div class="div-3">
                <p class="text-wrapper-6"><?php echo esc_html(isset($card5['title']) ? $card5['title'] : ''); ?></p>
                <a href="<?php echo esc_url(isset($card5['url']) ? $card5['url'] : '#'); ?>" class="button-2"><div class="text-wrapper-7"><?php echo esc_html(isset($card5['button_label']) ? $card5['button_label'] : 'Read More'); ?></div></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php get_template_part('template-parts/sections/testimonial-slider-section'); ?>

    <!-- CTA Section -->
    <?php craftdigitally_render_shared_cta_section(); ?>
  </div>
</main>

<?php
get_footer();
