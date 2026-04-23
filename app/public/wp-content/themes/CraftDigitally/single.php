<?php
/**
 * Single Blog Post Template
 * 
 * Template for displaying individual blog posts (default WordPress post type)
 *
 * @package CraftDigitally
 */

get_header();

// Get post data
$post_id = get_the_ID();
$blog_landing_page = get_pages(array(
  'meta_key' => '_wp_page_template',
  'meta_value' => 'page-templates/page-blog-landing.php',
  'number' => 1,
  'post_status' => 'publish'
));
$blog_back_url = !empty($blog_landing_page) ? get_permalink($blog_landing_page[0]->ID) : home_url('/blog/');

// Back button is automatic (not editable in ACF).
$cd_bdp_back_label = '< BACK TO BLOG';
$cd_bdp_back_url = $blog_back_url;

// Hero/meta values: editable via ACF (simple fields).
$cd_bdp_category = 'LOCAL SEO';
$cats = get_the_category($post_id);
if (!empty($cats) && !is_wp_error($cats) && !empty($cats[0]->name)) {
  $cd_bdp_category = (string) $cats[0]->name;
}

$cd_bdp_title = get_the_title($post_id);
$cd_bdp_author_default = 'By ' . craftdigitally_bdp_resolve_author_display_name($post_id);
$cd_bdp_date_default = get_the_date('F j, Y', $post_id);

// Basic reading time (words / 200 wpm).
$cd_bdp_word_count = str_word_count(wp_strip_all_tags((string) get_post_field('post_content', $post_id)));
$cd_bdp_minutes = max(1, (int) ceil($cd_bdp_word_count / 200));
$cd_bdp_read_time_default = $cd_bdp_minutes . ' min read';

$cd_bdp_author = $cd_bdp_author_default;
$cd_bdp_date = $cd_bdp_date_default;
$cd_bdp_read_time = craftdigitally_get_acf('blog_read_time', $cd_bdp_read_time_default, $post_id);

// Featured image (fallback to theme asset).
$cd_bdp_featured_img = get_the_post_thumbnail_url($post_id, 'large');
$cd_bdp_featured_img = $cd_bdp_featured_img ? $cd_bdp_featured_img : (get_template_directory_uri() . '/assets/images/bookimage.png');
$thumb_id = (int) get_post_thumbnail_id($post_id);
$cd_bdp_featured_img_alt = $thumb_id > 0 ? (string) get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';
$cd_bdp_featured_img_alt = trim($cd_bdp_featured_img_alt) !== '' ? $cd_bdp_featured_img_alt : $cd_bdp_title;

$cd_bdp_shared_cta = craftdigitally_get_shared_cta_data();
$cd_bdp_cta_title = craftdigitally_get_acf('blog_cta_title', $cd_bdp_shared_cta['title'], $post_id);
$cd_bdp_cta_text = craftdigitally_get_acf('blog_cta_text', $cd_bdp_shared_cta['subtitle'], $post_id);
$cd_bdp_cta_btn_label = craftdigitally_get_acf('blog_cta_button_label', $cd_bdp_shared_cta['button_label'], $post_id);

// Right sidebar CTA (separate fields).
$cd_bdp_sidebar_cta_title = craftdigitally_get_acf('blog_sidebar_cta_title', $cd_bdp_cta_title, $post_id);
$cd_bdp_sidebar_cta_text = craftdigitally_get_acf('blog_sidebar_cta_text', $cd_bdp_cta_text, $post_id);
$cd_bdp_sidebar_cta_btn_label = craftdigitally_get_acf('blog_sidebar_cta_button_label', $cd_bdp_cta_btn_label, $post_id);

$cd_bdp_share_url = esc_url_raw(get_permalink($post_id));
$cd_bdp_share_title = wp_strip_all_tags($cd_bdp_title);

$cd_bdp_raw_content = apply_filters('the_content', get_post_field('post_content', $post_id));
$cd_bdp_raw_content = craftdigitally_bdp_strip_legacy_demo_lead($cd_bdp_raw_content);
$cd_bdp_built = craftdigitally_bdp_build_structured_content_from_post_html($cd_bdp_raw_content);
$cd_bdp_toc = isset($cd_bdp_built['toc']) ? $cd_bdp_built['toc'] : array();
$cd_bdp_intro_html = isset($cd_bdp_built['intro_html']) ? $cd_bdp_built['intro_html'] : '';
$cd_bdp_sections = isset($cd_bdp_built['sections']) ? $cd_bdp_built['sections'] : array();
?>

<div class="bdp-page">
  <div class="bdp-main">
    <div class="bdp-hero">
      <div class="bdp-container">
        <div class="bdp-headline-subhead">
          <div class="bdp-frame">
            <a href="<?php echo esc_url($cd_bdp_back_url); ?>" class="bdp-text-wrapper"><?php echo esc_html($cd_bdp_back_label); ?></a>
            <div class="bdp-text-wrapper"><?php echo esc_html($cd_bdp_category); ?></div>
          </div>
          <hr class="bdp-hero-title-divider" aria-hidden="true" />
          <h1 class="bdp-div"><?php echo esc_html($cd_bdp_title); ?></h1>
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
          <?php if (!empty($cd_bdp_toc)): ?>
          <div class="bdp-frame-2">
            <div class="bdp-text-wrapper-3">Table of Contents</div>
            <div class="bdp-frame-3"></div>
            <div class="bdp-flexcontainer">
              <ol class="bdp-toc-list">
                <?php foreach ($cd_bdp_toc as $toc_item) : ?>
                  <li class="bdp-toc-item">
                    <a class="bdp-toc-link" href="#<?php echo esc_attr($toc_item['id']); ?>"><?php echo esc_html($toc_item['text']); ?></a>
                  </li>
                <?php endforeach; ?>
              </ol>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <div class="bdp-content-2" id="bdp-blog-content">
          <?php if (!empty(trim(wp_strip_all_tags($cd_bdp_intro_html)))) : ?>
          <div class="bdp-div-2">
            <div class="bdp-text-wrapper-4">
              <?php echo wp_kses_post($cd_bdp_intro_html); ?>
            </div>
          </div>
          <?php endif; ?>

          <?php if (!empty($cd_bdp_sections)) : ?>
            <?php foreach ($cd_bdp_sections as $i => $section) : ?>
              <div class="bdp-div-2" id="<?php echo esc_attr($section['id']); ?>">
                <h2 class="bdp-text-wrapper-5"><?php echo esc_html($section['title']); ?></h2>
                <div class="bdp-flexcontainer-2">
                  <?php echo wp_kses_post($section['html']); ?>
                </div>
              </div>

              <?php if (($i + 1) === 3) : ?>
                <div class="bdp-img">
                  <?php if (!empty($cd_bdp_featured_img)) : ?>
                    <img src="<?php echo esc_url($cd_bdp_featured_img); ?>" alt="<?php echo esc_attr($cd_bdp_featured_img_alt); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if (!empty($cd_bdp_cta_title)): ?>
          <div class="bdp-cta">
            <div class="bdp-frame-wrapper">
              <div class="bdp-content-wrapper">
                <div class="bdp-div-3">
                  <p class="bdp-text-wrapper-4"><?php echo esc_html($cd_bdp_cta_title); ?></p>
                  <?php if (!empty($cd_bdp_cta_text)): ?>
                  <p class="bdp-p">
                    <?php echo esc_html($cd_bdp_cta_text); ?>
                  </p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <a href="#contact" class="bdp-button"><div class="bdp-text-wrapper-10"><?php echo esc_html($cd_bdp_cta_btn_label); ?></div></a>
          </div>
          <?php endif; ?>
          
          <?php
          get_template_part(
            'template-parts/bdp-author-block',
            null,
            craftdigitally_bdp_get_author_block_args($post_id, 'blog_')
          );
          ?>
        </div>
        <div class="bdp-right">
          <?php if (!empty($cd_bdp_sidebar_cta_title)): ?>
          <div class="bdp-cta-3">
            <div class="bdp-frame-wrapper">
              <div class="bdp-content-wrapper">
                <div class="bdp-div-3">
                  <p class="bdp-text-wrapper-4"><?php echo esc_html($cd_bdp_sidebar_cta_title); ?></p>
                  <?php if (!empty($cd_bdp_sidebar_cta_text)): ?>
                  <p class="bdp-p">
                    <?php echo esc_html($cd_bdp_sidebar_cta_text); ?>
                  </p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <a href="#contact" class="bdp-button"><div class="bdp-text-wrapper-10"><?php echo esc_html($cd_bdp_sidebar_cta_btn_label); ?></div></a>
          </div>
          <?php endif; ?>
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
  </div>
</div>

<?php
get_footer();
?>
