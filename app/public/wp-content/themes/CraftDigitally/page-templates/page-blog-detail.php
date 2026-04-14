<?php
/**
 * Template Name: Blog Detail Page
 * Template Post Type: page
 * 
 * @package CraftDigitally
 */

get_header();

// This page renders a real WordPress Post when opened from the Blog list:
// Example: /blog-detail/?post_id=123
$cd_bdp_selected_post_id = isset($_GET['post_id']) ? absint($_GET['post_id']) : 0;
$cd_bdp_is_post_context = ($cd_bdp_selected_post_id > 0 && get_post_type($cd_bdp_selected_post_id) === 'post');
$cd_bdp_context_id = $cd_bdp_is_post_context ? $cd_bdp_selected_post_id : get_queried_object_id();

// Prevent exposing draft/private posts to public users.
if ($cd_bdp_is_post_context) {
  $cd_bdp_post_obj = get_post($cd_bdp_context_id);
  if (!$cd_bdp_post_obj) {
    $cd_bdp_is_post_context = false;
    $cd_bdp_context_id = get_queried_object_id();
  } else {
    $status = (string) $cd_bdp_post_obj->post_status;
    if ($status !== 'publish' && !current_user_can('edit_post', $cd_bdp_context_id)) {
      status_header(404);
      nocache_headers();
      include get_query_template('404');
      exit;
    }
  }
}

// Back button is automatic (NOT editable in ACF per requirement).
$cd_bdp_back_label = '< BACK TO BLOG';
$cd_bdp_landing_pages = get_pages(array(
  'meta_key' => '_wp_page_template',
  'meta_value' => 'page-templates/page-blog-landing.php',
  'number' => 1,
  'post_status' => 'publish',
));
$cd_bdp_back_url = !empty($cd_bdp_landing_pages) ? get_permalink($cd_bdp_landing_pages[0]->ID) : home_url('/blog/');

// Hero/meta values:
$cd_bdp_default_cat = 'LOCAL SEO';
if ($cd_bdp_is_post_context) {
  $wp_cats = get_the_category($cd_bdp_context_id);
  if (!empty($wp_cats) && !is_wp_error($wp_cats) && !empty($wp_cats[0]->name)) {
    $cd_bdp_default_cat = $wp_cats[0]->name;
  }
  $cd_bdp_category = craftdigitally_get_acf('blog_category', $cd_bdp_default_cat, $cd_bdp_context_id);
  $cd_bdp_title = craftdigitally_get_acf('blog_title', get_the_title($cd_bdp_context_id), $cd_bdp_context_id);
  $cd_bdp_author_default = 'By ' . get_the_author_meta('display_name', (int) get_post_field('post_author', $cd_bdp_context_id));
  $cd_bdp_author = craftdigitally_get_acf('blog_author', $cd_bdp_author_default, $cd_bdp_context_id);
  $cd_bdp_date = craftdigitally_get_acf('blog_date', get_the_date('F j, Y', $cd_bdp_context_id), $cd_bdp_context_id);
  $cd_bdp_read_time = craftdigitally_get_acf('blog_read_time', '3 min read', $cd_bdp_context_id);
} else {
  // Fallback (prefilled) when this page is viewed directly without a selected post.
  $cd_bdp_category = craftdigitally_get_acf('blog_detail_category', 'LOCAL SEO', $cd_bdp_context_id);
  $cd_bdp_title = craftdigitally_get_acf('blog_detail_title', 'Why Local SEO Matters for Small Businesses in 2025', $cd_bdp_context_id);
  $cd_bdp_author = craftdigitally_get_acf('blog_detail_author', 'By Yashasvi Zala', $cd_bdp_context_id);
  $cd_bdp_date = craftdigitally_get_acf('blog_detail_date', 'September 17,2025', $cd_bdp_context_id);
  $cd_bdp_read_time = craftdigitally_get_acf('blog_detail_read_time', '3 min read', $cd_bdp_context_id);
}

// Featured image (same image field should drive list + detail for posts).
$cd_bdp_featured_img = '';
$cd_bdp_featured_img_alt = '';
if ($cd_bdp_is_post_context) {
  $thumb_url = get_the_post_thumbnail_url($cd_bdp_context_id, 'large');
  $cd_bdp_featured_img = craftdigitally_get_acf_image_url(
    'blog_featured_image',
    $thumb_url ? $thumb_url : (get_template_directory_uri() . '/assets/images/bookimage.png'),
    $cd_bdp_context_id
  );
  $cd_bdp_featured_img_alt = craftdigitally_get_acf('blog_featured_image_alt', get_the_title($cd_bdp_context_id), $cd_bdp_context_id);
} else {
  $cd_bdp_featured_img = craftdigitally_get_acf_image_url(
    'blog_detail_featured_image',
    get_template_directory_uri() . '/assets/images/bookimage.png',
    $cd_bdp_context_id
  );
  $cd_bdp_featured_img_alt = craftdigitally_get_acf('blog_detail_featured_image_alt', 'Blog Image', $cd_bdp_context_id);
}

$cd_bdp_share_url = esc_url_raw(get_permalink($cd_bdp_context_id));
$cd_bdp_share_title = wp_strip_all_tags($cd_bdp_title);

$cd_bdp_toc_default = array(
  array('text' => 'What Is Local SEO?'),
  array('text' => 'Why Local SEO Is Essential in 2025'),
  array('text' => 'Key Benefits of Local SEO'),
  array('text' => 'Important Local SEO Ranking Factors'),
  array('text' => 'How to Improve Your Local SEO'),
  array('text' => 'Final Thoughts'),
);
$cd_bdp_toc = $cd_bdp_is_post_context
  ? craftdigitally_get_acf_array('blog_toc', $cd_bdp_toc_default, $cd_bdp_context_id)
  : craftdigitally_get_acf_array('blog_detail_toc', $cd_bdp_toc_default, $cd_bdp_context_id);

$cd_bdp_intro = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_intro', get_the_excerpt($cd_bdp_context_id), $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_intro', "If you run a local business in 2025, competition isn't what it used to be. Customers now rely on\n              Google to decide where to shop, which doctor to trust, or which service provider to call. They compare\n              reviews, check hours, and look for the highest-rated businesses — all within seconds.", $cd_bdp_context_id);

$cd_bdp_local_seo_title = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_local_seo_title', 'What Is Local SEO?', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_local_seo_title', 'What Is Local SEO?', $cd_bdp_context_id);
$cd_bdp_local_seo_html_default = '<p class="bdp-text">
                <span class="bdp-text-wrapper-6"
                  >Local SEO (Local Search Engine Optimization) is the process of improving your online presence so
                  your business appears in
                </span>
                <span class="bdp-text-wrapper-7">local Google searches,</span>
                <span class="bdp-text-wrapper-6"> Google Maps, and the Local 3-Pack.</span>
              </p>
              <p class="bdp-text">
                <span class="bdp-text-wrapper-6"
                  >When someone searches for "salon near me," "best bakery in Ahmedabad," or "AC repair nearby,"
                  Google shows a list of top local businesses based on relevance, reviews, and proximity. Local SEO
                  ensures
                </span>
                <span class="bdp-text-wrapper-7">your business shows up in these results</span>
                <span class="bdp-text-wrapper-6">, giving you maximum visibility at the right time.</span>
              </p>';
$cd_bdp_local_seo_html = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_local_seo_html', '', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_local_seo_html', $cd_bdp_local_seo_html_default, $cd_bdp_context_id);

$cd_bdp_essential_title = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_essential_title', 'Why Local SEO Is Essential in 2025', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_essential_title', 'Why Local SEO Is Essential in 2025', $cd_bdp_context_id);
$cd_bdp_essential_intro = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_essential_intro', '', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_essential_intro', "Search behaviour continues to shift, and local businesses must keep up. Here's why Local SEO matters\n              more than ever:", $cd_bdp_context_id);
$cd_bdp_essential_html_default = '<div class="bdp-flexcontainer-3">
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-7">People Trust Search Engines Over Traditional Advertising</span>
                </p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >Customers rely on online results, reviews, and ratings to choose businesses. If you\'re not
                    visible, you\'re losing opportunities.</span
                  >
                </p>
              </div>
              <div class="bdp-flexcontainer-4">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">"Near Me" Searches Are Growing Rapidly</span></p>
                <p class="bdp-text"><span class="bdp-text-wrapper-6">More people use mobile searches like:</span></p>
                <p class="bdp-text"><span class="bdp-text-wrapper-6">"best dentist near me"</span></p>
                <p class="bdp-text"><span class="bdp-text-wrapper-6">"urgent care open now"</span></p>
                <p class="bdp-text"><span class="bdp-text-wrapper-6">"cafe near me with wifi"</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6">Google prioritises local results that are optimized.</span>
                </p>
              </div>
              <div class="bdp-flexcontainer-3">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">Google\'s Local Algorithms Are Smarter</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >Google uses AI to understand user intent better, showing hyper-relevant businesses. Only
                    optimized businesses rank well.</span
                  >
                </p>
              </div>
              <div class="bdp-flexcontainer-3">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">Competition Is Increasing</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >More businesses are investing in online visibility — if you aren\'t, your competitors will
                    dominate the results.</span
                  >
                </p>
              </div>';
$cd_bdp_essential_html = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_essential_html', '', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_essential_html', $cd_bdp_essential_html_default, $cd_bdp_context_id);

$cd_bdp_benefits_title = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_benefits_title', 'Key Benefits of Local SEO', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_benefits_title', 'Key Benefits of Local SEO', $cd_bdp_context_id);
$cd_bdp_benefits_html_default = '<div class="bdp-flexcontainer-3">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">1. Increased Local Visibility</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >Appear in Google\'s Local Pack and Maps results, where most local clicks happen.</span
                  >
                </p>
              </div>
              <div class="bdp-flexcontainer-5">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">2. More Qualified Leads</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >People searching locally are ready to take action — call, visit, or book.</span
                  >
                </p>
              </div>
              <div class="bdp-flexcontainer-3">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">3. Stronger Trust &amp; Reputation</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >Consistent reviews, accurate information, and updated profiles make you look more
                    reliable.</span
                  >
                </p>
              </div>
              <div class="bdp-flexcontainer-5">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">4. Boost in Store Visits &amp; Calls</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >Local search directly influences in-person visits and enquiries.</span
                  >
                </p>
              </div>
              <div class="bdp-flexcontainer-5">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">5. Cost-Effective Long-Term Growth</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >Unlike paid ads, Local SEO delivers results for months and years.</span
                  >
                </p>
              </div>';
$cd_bdp_benefits_html = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_benefits_html', '', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_benefits_html', $cd_bdp_benefits_html_default, $cd_bdp_context_id);

$cd_bdp_factors_title = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_factors_title', 'Important Local SEO Ranking Factors', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_factors_title', 'Important Local SEO Ranking Factors', $cd_bdp_context_id);
$cd_bdp_factors_intro = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_factors_intro', 'Google considers several factors before ranking a local business:', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_factors_intro', 'Google considers several factors before ranking a local business:', $cd_bdp_context_id);
$cd_bdp_factors_html_default = '<div class="bdp-flexcontainer-5">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">Google Business Profile Optimization</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >Accurate information, photos, updates, and category selection matter.</span
                  >
                </p>
              </div>
              <div class="bdp-flexcontainer-5">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">NAP Consistency (Name, Address, Phone)</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6">Your business information must match across all platforms.</span>
                </p>
              </div>
              <div class="bdp-flexcontainer-5">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">Reviews &amp; Ratings</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >High ratings and recent reviews help you rank higher and convert better.</span
                  >
                </p>
              </div>
              <div class="bdp-flexcontainer-5">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">Local Citations</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6">Listings on trusted directories increase authority.</span>
                </p>
              </div>
              <div class="bdp-flexcontainer-3">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">On-Page Local SEO</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >Optimized pages with local keywords help Google understand your relevance.</span
                  >
                </p>
              </div>
              <div class="bdp-flexcontainer-5">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">Backlinks from Local Websites</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6">Local mentions build trust and ranking power.</span>
                </p>
              </div>';
$cd_bdp_factors_html = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_factors_html', '', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_factors_html', $cd_bdp_factors_html_default, $cd_bdp_context_id);

$cd_bdp_improve_title = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_improve_title', 'How to Improve Your Local SEO', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_improve_title', 'How to Improve Your Local SEO', $cd_bdp_context_id);
$cd_bdp_improve_html_default = '<div class="bdp-flexcontainer-5">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">1. Optimize Your Google Business Profile</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >Add photos, services, categories, posts, and keep it updated regularly.</span
                  >
                </p>
              </div>
              <div class="bdp-flexcontainer-6">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">2. Use Local Keywords</span></p>
                <p class="bdp-text"><span class="bdp-text-wrapper-6">Examples:</span></p>
                <p class="bdp-text"><span class="bdp-text-wrapper-6">"best coaching classes in Surat"</span></p>
                <p class="bdp-text"><span class="bdp-text-wrapper-6">"plumber in South Delhi"</span></p>
                <p class="bdp-text"><span class="bdp-text-wrapper-6">"wedding photographer in Chandigarh"</span></p>
              </div>
              <div class="bdp-flexcontainer-3">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">3. Ask for Reviews Regularly</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >Happy customers are the best marketing asset. Respond to each review professionally.</span
                  >
                </p>
              </div>
              <div class="bdp-flexcontainer-7">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">4. Build Local Citations</span></p>
                <p class="bdp-text"><span class="bdp-text-wrapper-6">Get listed on:</span></p>
                <p class="bdp-text"><span class="bdp-text-wrapper-6">JustDial</span></p>
                <p class="bdp-text"><span class="bdp-text-wrapper-6">Sulekha</span></p>
                <p class="bdp-text"><span class="bdp-text-wrapper-6">IndiaMart</span></p>
                <p class="bdp-text"><span class="bdp-text-wrapper-6">Niche directories</span></p>
              </div>
              <div class="bdp-flexcontainer-5">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">5. Improve Your Website\'s On-Page SEO</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6"
                    >Local schema, internal links, meta tags, and fast loading speeds matter.</span
                  >
                </p>
              </div>
              <div class="bdp-flexcontainer-5">
                <p class="bdp-text"><span class="bdp-text-wrapper-7">6. Publish Local-Focused Content</span></p>
                <p class="bdp-text">
                  <span class="bdp-text-wrapper-6">Create blogs and guides that answer your customers\' questions.</span>
                </p>
              </div>';
$cd_bdp_improve_html = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_improve_html', '', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_improve_html', $cd_bdp_improve_html_default, $cd_bdp_context_id);

$cd_bdp_testimonial_quote = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_testimonial_quote', '', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_testimonial_quote', "Before working with CraftDigitally, SEO felt like a fog I couldn't navigate. Their process made\n              everything so clear and actionable. I left with not only a visible online presence but a genuine sense\n              of confidence in how I show up digitally", $cd_bdp_context_id);

$cd_bdp_final_title = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_final_title', 'Final Thoughts', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_final_title', 'Final Thoughts', $cd_bdp_context_id);
$cd_bdp_final_html_default = '<p class="bdp-p">
                <span class="bdp-span"
                  >Local SEO is no longer optional — it\'s the foundation of online visibility for small businesses.
                  With more people relying on Google to find services nearby, staying optimized gives you a
                  competitive edge.</span
                >
              </p>
              <p class="bdp-p">
                <span class="bdp-span"
                  >If you want consistent leads, stronger visibility, and long-term growth, Local SEO should be a
                  top priority for your business in 2025.</span
                >
              </p>';
$cd_bdp_final_html = $cd_bdp_is_post_context
  ? craftdigitally_get_acf('blog_final_html', '', $cd_bdp_context_id)
  : craftdigitally_get_acf('blog_detail_final_html', $cd_bdp_final_html_default, $cd_bdp_context_id);

$cd_bdp_shared_cta = craftdigitally_get_shared_cta_data();
$cd_bdp_cta_title = $cd_bdp_shared_cta['title'];
$cd_bdp_cta_text = $cd_bdp_shared_cta['subtitle'];
$cd_bdp_cta_btn_label = $cd_bdp_shared_cta['button_label'];

$cd_bdp_author_block_prefix = $cd_bdp_is_post_context ? 'blog_' : 'blog_detail_';

// If post content blocks are empty, use the post content as a fallback (keeps UI while making Posts usable immediately).
if ($cd_bdp_is_post_context) {
  if (empty($cd_bdp_local_seo_html) && empty($cd_bdp_essential_html) && empty($cd_bdp_benefits_html) && empty($cd_bdp_factors_html) && empty($cd_bdp_improve_html) && empty($cd_bdp_final_html)) {
    $cd_bdp_local_seo_html = apply_filters('the_content', get_post_field('post_content', $cd_bdp_context_id));
  }
}
?>

<div class="bdp-page">
  <div class="bdp-main">
    <div class="bdp-hero">
      <div class="bdp-container">
        <div class="bdp-headline-subhead">
          <div class="bdp-frame">
            <a class="bdp-text-wrapper" href="<?php echo esc_url($cd_bdp_back_url); ?>"><?php echo esc_html($cd_bdp_back_label); ?></a>
            <div class="bdp-text-wrapper"><?php echo esc_html($cd_bdp_category); ?></div>
          </div>
          <hr class="bdp-hero-title-divider" aria-hidden="true" />
          <p class="bdp-div"><?php echo esc_html($cd_bdp_title); ?></p>
        </div>
        <div class="bdp-meta-data">
          <div class="bdp-div-wrapper"><div class="bdp-text-wrapper-2"><?php echo esc_html($cd_bdp_author); ?></div></div>
          <div class="bdp-divider"></div>
          <div class="bdp-div-wrapper"><div class="bdp-text-wrapper-2"><?php echo esc_html($cd_bdp_date); ?></div></div>
          <div class="bdp-divider"></div>
          <div class="bdp-div-wrapper"><div class="bdp-text-wrapper-2"><?php echo esc_html($cd_bdp_read_time); ?></div></div>
        </div>
      </div>
    </div>
    <div class="bdp-content">
      <div class="bdp-container-2">
        <div class="bdp-left">
          <div class="bdp-frame-2">
            <div class="bdp-text-wrapper-3">Table of Contents</div>
            <div class="bdp-frame-3"></div>
            <div class="bdp-flexcontainer">
              <ol class="bdp-toc-list">
                <?php foreach ($cd_bdp_toc as $idx => $toc) : ?>
                  <li class="bdp-toc-item">
                    <a class="bdp-toc-link" href="#<?php echo esc_attr(craftdigitally_bdp_toc_section_anchor_id((int) $idx)); ?>"><?php echo esc_html(isset($toc['text']) ? $toc['text'] : ''); ?></a>
                  </li>
                <?php endforeach; ?>
              </ol>
            </div>
          </div>
        </div>
        <div class="bdp-content-2" id="bdp-blog-content">
          <div class="bdp-div-2">
            <p class="bdp-text-wrapper-4">
              <?php echo nl2br(esc_html($cd_bdp_intro)); ?>
            </p>
          </div>
          <div class="bdp-div-2" id="bdp-section-local-seo">
            <div class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_local_seo_title); ?></div>
            <div class="bdp-flexcontainer-2">
              <?php echo wp_kses_post($cd_bdp_local_seo_html); ?>
            </div>
          </div>
          <div class="bdp-div-2" id="bdp-section-essential">
            <p class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_essential_title); ?></p>
            <p class="bdp-p">
              <?php echo esc_html($cd_bdp_essential_intro); ?>
            </p>
            <div class="bdp-auto-flex">
              <?php echo wp_kses_post($cd_bdp_essential_html); ?>
            </div>
          </div>
          <div class="bdp-div-2" id="bdp-section-benefits">
            <p class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_benefits_title); ?></p>
            <div class="bdp-auto">
              <?php echo wp_kses_post($cd_bdp_benefits_html); ?>
            </div>
          </div>
          <div class="bdp-img">
            <?php if (!empty($cd_bdp_featured_img)): ?>
              <img src="<?php echo esc_url($cd_bdp_featured_img); ?>" alt="<?php echo esc_attr($cd_bdp_featured_img_alt); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
            <?php endif; ?>
          </div>
          <p class="bdp-text-wrapper-8">The client was struggling to attract local customers through online searches</p>
          <div class="bdp-div-2" id="bdp-section-factors">
            <p class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_factors_title); ?></p>
            <p class="bdp-p"><?php echo esc_html($cd_bdp_factors_intro); ?></p>
            <div class="bdp-auto-2">
              <?php echo wp_kses_post($cd_bdp_factors_html); ?>
            </div>
          </div>
          <div class="bdp-div-2" id="bdp-section-improve">
            <p class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_improve_title); ?></p>
            <div class="bdp-auto-3">
              <?php echo wp_kses_post($cd_bdp_improve_html); ?>
            </div>
          </div>
          <div class="bdp-testimonial-card">
            <p class="bdp-text-wrapper-9">
              <?php echo nl2br(esc_html($cd_bdp_testimonial_quote)); ?>
            </p>
          </div>
          <div class="bdp-div-3" id="bdp-section-final">
            <div class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_final_title); ?></div>
            <div class="bdp-flexcontainer-8">
              <?php echo wp_kses_post($cd_bdp_final_html); ?>
            </div>
          </div>
          <div class="bdp-cta">
            <div class="bdp-frame-wrapper">
              <div class="bdp-content-wrapper">
                <div class="bdp-div-3">
                  <p class="bdp-text-wrapper-4"><?php echo esc_html($cd_bdp_cta_title); ?></p>
                  <p class="bdp-p">
                    <?php echo esc_html($cd_bdp_cta_text); ?>
                  </p>
                </div>
              </div>
            </div>
            <button class="bdp-button"><div class="bdp-text-wrapper-10"><?php echo esc_html($cd_bdp_cta_btn_label); ?></div></button>
          </div>
          <?php
          get_template_part(
            'template-parts/bdp-author-block',
            null,
            craftdigitally_bdp_get_author_block_args($cd_bdp_context_id, $cd_bdp_author_block_prefix)
          );
          ?>
        </div>
        <div class="bdp-right">
          <div class="bdp-cta-3">
            <div class="bdp-frame-wrapper">
              <div class="bdp-content-wrapper">
                <div class="bdp-div-3">
                  <p class="bdp-text-wrapper-4"><?php echo esc_html($cd_bdp_cta_title); ?></p>
                  <p class="bdp-p">
                    <?php echo esc_html($cd_bdp_cta_text); ?>
                  </p>
                </div>
              </div>
            </div>
            <button class="bdp-button"><div class="bdp-text-wrapper-10"><?php echo esc_html($cd_bdp_cta_btn_label); ?></div></button>
          </div>
          <?php
          get_template_part(
            'template-parts/bdp-share-social',
            null,
            array(
              'share_url'   => $cd_bdp_share_url,
              'share_title' => $cd_bdp_share_title,
            )
          );
          ?>
        </div>
      </div>
    </div>
    <div class="bdp-container-wrapper">
      <div class="bdp-container-3">
        <div class="bdp-frame-6">
          <div class="bdp-content-3">
            <p class="bdp-text-wrapper-14"><?php echo esc_html($cd_bdp_shared_cta['title']); ?></p>
            <p class="bdp-text-wrapper-15"><?php echo esc_html($cd_bdp_shared_cta['subtitle']); ?></p>
          </div>
        </div>
        <div class="bdp-form">
          <div class="bdp-form-2">
            <div class="bdp-frame-8">
              <input class="bdp-name" placeholder="<?php echo esc_attr($cd_bdp_shared_cta['name_placeholder']); ?>" type="text" id="bdp-input-1" />
              <div class="bdp-phone"><label class="bdp-label" for="bdp-input-1"><?php echo esc_html($cd_bdp_shared_cta['phone_placeholder']); ?></label></div>
            </div>
            <input class="bdp-email" placeholder="<?php echo esc_attr($cd_bdp_shared_cta['email_placeholder']); ?>" type="email" />
            <div class="bdp-service"><div class="bdp-text-wrapper-16"><?php echo esc_html($cd_bdp_shared_cta['service_placeholder']); ?></div></div>
            <div class="bdp-message"><div class="bdp-text-wrapper-16"><?php echo esc_html($cd_bdp_shared_cta['message_placeholder']); ?></div></div>
          </div>
          <button class="bdp-button-2"><div class="bdp-text-wrapper-17"><?php echo esc_html($cd_bdp_shared_cta['button_label']); ?></div></button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
get_footer();             
?>
