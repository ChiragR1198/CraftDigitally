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

$cd_bdp_cta_title = craftdigitally_get_acf('blog_cta_title', 'Ready to Dominate Your Competition?', $post_id);
$cd_bdp_cta_text = craftdigitally_get_acf('blog_cta_text', 'Craft Digitally transformed our online presence completely. Our website now ranks on the first page for our target keywords', $post_id);
$cd_bdp_cta_btn_label = craftdigitally_get_acf('blog_cta_button_label', 'Book a Free Consult', $post_id);

$cd_bdp_author_tag = craftdigitally_get_acf('blog_author_tag', 'LOCAL SEO EXPERT', $post_id);
$cd_bdp_author_name = craftdigitally_get_acf('blog_author_name', get_the_author(), $post_id);
$cd_bdp_author_bio = craftdigitally_get_acf('blog_author_bio', '', $post_id);
$cd_bdp_author_social_img = craftdigitally_get_acf_image_url('blog_author_social_image', get_template_directory_uri() . '/assets/images/group-3.png', $post_id);

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
              <ol class="bdp-toc-list" style="padding-left: 20px; margin: 0;">
                <?php foreach ($cd_bdp_toc as $toc): ?>
                  <li class="bdp-p"><span class="bdp-span"><?php echo esc_html(isset($toc['text']) ? $toc['text'] : ''); ?></span></li>
                <?php endforeach; ?>
              </ol>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <div class="bdp-content-2">
          <?php if (!empty($cd_bdp_intro)): ?>
          <div class="bdp-div-2">
            <p class="bdp-text-wrapper-4">
              <?php echo nl2br(esc_html($cd_bdp_intro)); ?>
            </p>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($cd_bdp_local_seo_title) || !empty($cd_bdp_local_seo_html)): ?>
          <div class="bdp-div-2">
            <div class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_local_seo_title); ?></div>
            <div class="bdp-flexcontainer-2">
              <?php echo wp_kses_post($cd_bdp_local_seo_html); ?>
            </div>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($cd_bdp_essential_title) || !empty($cd_bdp_essential_html)): ?>
          <div class="bdp-div-2">
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
          <div class="bdp-div-2">
            <p class="bdp-text-wrapper-5"><?php echo esc_html($cd_bdp_benefits_title); ?></p>
            <div class="bdp-auto">
              <?php echo wp_kses_post($cd_bdp_benefits_html); ?>
            </div>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($cd_bdp_factors_title) || !empty($cd_bdp_factors_html)): ?>
          <div class="bdp-div-2">
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
          <div class="bdp-div-2">
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
          <div class="bdp-div-3">
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
          
          <?php if (!empty($cd_bdp_author_name)): ?>
          <div class="bdp-cta-2">
            <div class="bdp-frame-4">
              <div class="bdp-frame-5"></div>
              <div class="bdp-frame-wrapper">
                <div class="bdp-frame-6">
                  <div class="bdp-div-3">
                    <?php if (!empty($cd_bdp_author_tag)): ?>
                    <div class="bdp-local-SEO-expert"><?php echo esc_html($cd_bdp_author_tag); ?></div>
                    <?php endif; ?>
                    <div class="bdp-text-wrapper-11"><?php echo esc_html($cd_bdp_author_name); ?></div>
                    <?php if (!empty($cd_bdp_author_bio)): ?>
                    <p class="bdp-text-wrapper-12">
                      <?php echo esc_html($cd_bdp_author_bio); ?>
                    </p>
                    <?php endif; ?>
                    <img class="bdp-group" src="<?php echo esc_url($cd_bdp_author_social_img); ?>" alt="Social Links" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?>
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
          <div class="bdp-share">
            <div class="bdp-text-wrapper-13">Share</div>
            <div class="bdp-frame-7">
              <div class="bdp-linkedin">
                <img class="bdp-social-icons" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/social-icons-1.svg'); ?>" alt="LinkedIn" />
              </div>
              <div class="bdp-x">
                <img class="bdp-social-icons" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/social-icons.svg'); ?>" alt="Twitter/X" />
              </div>
              <div class="bdp-facebook">
                <img class="bdp-social-icons" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/social-icons-2.svg'); ?>" alt="Facebook" />
              </div>
              <img class="bdp-facebook-2" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/facebook.svg'); ?>" alt="Facebook" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
get_footer();
?>
