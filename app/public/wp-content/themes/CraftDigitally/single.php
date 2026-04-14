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
$cd_bdp_category = craftdigitally_get_acf('blog_category', 'LOCAL SEO', $post_id);
$cd_bdp_title = craftdigitally_get_acf('blog_title', get_the_title(), $post_id);
$cd_bdp_author = craftdigitally_get_acf('blog_author', 'By ' . get_the_author(), $post_id);
$cd_bdp_date = craftdigitally_get_acf('blog_date', get_the_date('F j, Y'), $post_id);
$cd_bdp_read_time = craftdigitally_get_acf('blog_read_time', '3 min read', $post_id);

$cd_bdp_toc_default = array(
  array('text' => 'What Is Local SEO?'),
  array('text' => 'Why Local SEO Is Essential in 2025'),
  array('text' => 'Key Benefits of Local SEO'),
  array('text' => 'Important Local SEO Ranking Factors'),
  array('text' => 'How to Improve Your Local SEO'),
  array('text' => 'Final Thoughts'),
);
$cd_bdp_toc = craftdigitally_get_acf_array('blog_toc', $cd_bdp_toc_default, $post_id);

$cd_bdp_intro = craftdigitally_get_acf('blog_intro', get_the_excerpt(), $post_id);

$cd_bdp_local_seo_title = craftdigitally_get_acf('blog_local_seo_title', 'What Is Local SEO?', $post_id);
$cd_bdp_local_seo_html = craftdigitally_get_acf('blog_local_seo_html', '', $post_id);

$cd_bdp_essential_title = craftdigitally_get_acf('blog_essential_title', 'Why Local SEO Is Essential in 2025', $post_id);
$cd_bdp_essential_intro = craftdigitally_get_acf('blog_essential_intro', '', $post_id);
$cd_bdp_essential_html = craftdigitally_get_acf('blog_essential_html', '', $post_id);

$cd_bdp_benefits_title = craftdigitally_get_acf('blog_benefits_title', 'Key Benefits of Local SEO', $post_id);
$cd_bdp_benefits_html = craftdigitally_get_acf('blog_benefits_html', '', $post_id);

$cd_bdp_factors_title = craftdigitally_get_acf('blog_factors_title', 'Important Local SEO Ranking Factors', $post_id);
$cd_bdp_factors_intro = craftdigitally_get_acf('blog_factors_intro', 'Google considers several factors before ranking a local business:', $post_id);
$cd_bdp_factors_html = craftdigitally_get_acf('blog_factors_html', '', $post_id);

$cd_bdp_improve_title = craftdigitally_get_acf('blog_improve_title', 'How to Improve Your Local SEO', $post_id);
$cd_bdp_improve_html = craftdigitally_get_acf('blog_improve_html', '', $post_id);

$cd_bdp_testimonial_quote = craftdigitally_get_acf('blog_testimonial_quote', '', $post_id);

$cd_bdp_final_title = craftdigitally_get_acf('blog_final_title', 'Final Thoughts', $post_id);
$cd_bdp_final_html = craftdigitally_get_acf('blog_final_html', '', $post_id);

$cd_bdp_shared_cta = craftdigitally_get_shared_cta_data();
$cd_bdp_cta_title = $cd_bdp_shared_cta['title'];
$cd_bdp_cta_text = $cd_bdp_shared_cta['subtitle'];
$cd_bdp_cta_btn_label = $cd_bdp_shared_cta['button_label'];

$cd_bdp_share_url = esc_url_raw(get_permalink($post_id));
$cd_bdp_share_title = wp_strip_all_tags($cd_bdp_title);

// If ACF content is empty, use post content
if (empty($cd_bdp_local_seo_html) && empty($cd_bdp_essential_html) && empty($cd_bdp_benefits_html)) {
  $cd_bdp_local_seo_html = apply_filters('the_content', get_the_content());
}
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
          <?php if (!empty($cd_bdp_toc)): ?>
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
          <?php endif; ?>
        </div>
        <div class="bdp-content-2" id="bdp-blog-content">
          <?php if (!empty($cd_bdp_intro)): ?>
          <div class="bdp-div-2">
            <p class="bdp-text-wrapper-4">
              <?php echo nl2br(esc_html($cd_bdp_intro)); ?>
            </p>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($cd_bdp_local_seo_title) || !empty($cd_bdp_local_seo_html)): ?>
          <div class="bdp-div-2" id="bdp-section-local-seo">
            <div class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_local_seo_title); ?></div>
            <div class="bdp-flexcontainer-2">
              <?php echo wp_kses_post($cd_bdp_local_seo_html); ?>
            </div>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($cd_bdp_essential_title) || !empty($cd_bdp_essential_html)): ?>
          <div class="bdp-div-2" id="bdp-section-essential">
            <p class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_essential_title); ?></p>
            <?php if (!empty($cd_bdp_essential_intro)): ?>
            <p class="bdp-p">
              <?php echo esc_html($cd_bdp_essential_intro); ?>
            </p>
            <?php endif; ?>
            <div class="bdp-auto-flex">
              <?php echo wp_kses_post($cd_bdp_essential_html); ?>
            </div>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($cd_bdp_benefits_title) || !empty($cd_bdp_benefits_html)): ?>
          <div class="bdp-div-2" id="bdp-section-benefits">
            <p class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_benefits_title); ?></p>
            <div class="bdp-auto">
              <?php echo wp_kses_post($cd_bdp_benefits_html); ?>
            </div>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($cd_bdp_factors_title) || !empty($cd_bdp_factors_html)): ?>
          <div class="bdp-div-2" id="bdp-section-factors">
            <p class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_factors_title); ?></p>
            <?php if (!empty($cd_bdp_factors_intro)): ?>
            <p class="bdp-p"><?php echo esc_html($cd_bdp_factors_intro); ?></p>
            <?php endif; ?>
            <div class="bdp-auto-2">
              <?php echo wp_kses_post($cd_bdp_factors_html); ?>
            </div>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($cd_bdp_improve_title) || !empty($cd_bdp_improve_html)): ?>
          <div class="bdp-div-2" id="bdp-section-improve">
            <p class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_improve_title); ?></p>
            <div class="bdp-auto-3">
              <?php echo wp_kses_post($cd_bdp_improve_html); ?>
            </div>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($cd_bdp_testimonial_quote)): ?>
          <div class="bdp-testimonial-card">
            <p class="bdp-text-wrapper-9">
              <?php echo nl2br(esc_html($cd_bdp_testimonial_quote)); ?>
            </p>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($cd_bdp_final_title) || !empty($cd_bdp_final_html)): ?>
          <div class="bdp-div-3" id="bdp-section-final">
            <div class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_final_title); ?></div>
            <div class="bdp-flexcontainer-8">
              <?php echo wp_kses_post($cd_bdp_final_html); ?>
            </div>
          </div>
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
          <?php if (!empty($cd_bdp_cta_title)): ?>
          <div class="bdp-cta-3">
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
