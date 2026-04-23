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
}
$cd_bdp_hero_prefix = $cd_bdp_is_post_context ? 'blog_' : 'blog_detail_';

$cd_bdp_title_fallback = get_the_title($cd_bdp_context_id);
$cd_bdp_date_fallback = get_the_date('F j, Y', $cd_bdp_context_id);
$cd_bdp_word_count = str_word_count(wp_strip_all_tags((string) get_post_field('post_content', $cd_bdp_context_id)));
$cd_bdp_minutes = max(1, (int) ceil($cd_bdp_word_count / 200));
$cd_bdp_read_time_fallback = $cd_bdp_minutes . ' min read';

// Hero values: category/title come from content; author comes from the assigned WordPress user.
$cd_bdp_category = $cd_bdp_default_cat;
$cd_bdp_title = $cd_bdp_title_fallback;
$cd_bdp_author = 'By ' . craftdigitally_bdp_resolve_author_display_name($cd_bdp_context_id);
$cd_bdp_date = $cd_bdp_is_post_context
  ? $cd_bdp_date_fallback
  : craftdigitally_get_acf($cd_bdp_hero_prefix . 'date', $cd_bdp_date_fallback, $cd_bdp_context_id);
$cd_bdp_read_time = craftdigitally_get_acf($cd_bdp_hero_prefix . 'read_time', $cd_bdp_read_time_fallback, $cd_bdp_context_id);

// Featured image injection (for the inline image block in the 3rd section).
$cd_bdp_wp_thumb_url = get_the_post_thumbnail_url($cd_bdp_context_id, 'large');
$cd_bdp_wp_thumb_url = $cd_bdp_wp_thumb_url ? $cd_bdp_wp_thumb_url : (get_template_directory_uri() . '/assets/images/bookimage.png');
$cd_bdp_featured_img = $cd_bdp_wp_thumb_url;
$cd_bdp_featured_img_alt = $cd_bdp_title_fallback;

$cd_bdp_share_url = esc_url_raw(get_permalink($cd_bdp_context_id));
$cd_bdp_share_title = wp_strip_all_tags($cd_bdp_title);

$cd_bdp_shared_cta = craftdigitally_get_shared_cta_data();
$cd_bdp_cta_prefix = $cd_bdp_is_post_context ? 'blog_' : 'blog_detail_';
$cd_bdp_cta_title = craftdigitally_get_acf($cd_bdp_cta_prefix . 'cta_title', $cd_bdp_shared_cta['title'], $cd_bdp_context_id);
$cd_bdp_cta_text = craftdigitally_get_acf($cd_bdp_cta_prefix . 'cta_text', $cd_bdp_shared_cta['subtitle'], $cd_bdp_context_id);
$cd_bdp_cta_btn_label = craftdigitally_get_acf($cd_bdp_cta_prefix . 'cta_button_label', $cd_bdp_shared_cta['button_label'], $cd_bdp_context_id);

// Right sidebar CTA (separate fields).
$cd_bdp_sidebar_cta_title = craftdigitally_get_acf($cd_bdp_cta_prefix . 'sidebar_cta_title', $cd_bdp_cta_title, $cd_bdp_context_id);
$cd_bdp_sidebar_cta_text = craftdigitally_get_acf($cd_bdp_cta_prefix . 'sidebar_cta_text', $cd_bdp_cta_text, $cd_bdp_context_id);
$cd_bdp_sidebar_cta_btn_label = craftdigitally_get_acf($cd_bdp_cta_prefix . 'sidebar_cta_button_label', $cd_bdp_cta_btn_label, $cd_bdp_context_id);

$cd_bdp_author_block_prefix = $cd_bdp_is_post_context ? 'blog_' : 'blog_detail_';

/**
 * Build Blog Detail structured content from editor HTML.
 * - TOC is built from H2 headings (in order).
 * - Intro is everything before first H2.
 * - Sections are split by H2 (each section contains everything until next H2).
 *
 * @return array{
 *  toc: array<int, array{text:string, id:string}>,
 *  intro_html: string,
 *  sections: array<int, array{title:string, id:string, html:string}>
 * }
 */
function craftdigitally_bdp_build_structured_content($html) {
  $result = array('toc' => array(), 'intro_html' => '', 'sections' => array());

  if (!is_string($html) || trim($html) === '') {
    return $result;
  }

  if (!class_exists('DOMDocument')) {
    return $result;
  }

  $dom = new DOMDocument();
  $prevUseErrors = libxml_use_internal_errors(true);

  // Wrap in a container so we can extract innerHTML reliably.
  $wrapped = '<!doctype html><html><head><meta charset="utf-8"></head><body><div id="cd-bdp-root">' . $html . '</div></body></html>';
  $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

  $root = $dom->getElementById('cd-bdp-root');
  if (!$root) {
    libxml_clear_errors();
    libxml_use_internal_errors($prevUseErrors);
    return $result;
  }

  // TinyMCE can leave behind empty heading tags like <h2></h2> / <h3></h3>.
  // Remove them so blank headings do not render as fake "Section" rows.
  foreach (iterator_to_array($root->childNodes) as $child) {
    if (!($child instanceof DOMElement)) {
      continue;
    }

    $tag = strtolower($child->tagName);
    if ($tag !== 'h2' && $tag !== 'h3') {
      continue;
    }

    $text = trim(preg_replace('/\s+/', ' ', $child->textContent));
    if ($text === '' && $child->parentNode) {
      $child->parentNode->removeChild($child);
    }
  }

  $usedIds = array();
  foreach ($dom->getElementsByTagName('*') as $node) {
    if ($node instanceof DOMElement && $node->hasAttribute('id')) {
      $usedIds[$node->getAttribute('id')] = true;
    }
  }

  $make_unique_id = function ($text) use (&$usedIds) {
    $base = sanitize_title($text);
    if ($base === '') {
      $base = 'section';
    }
    $candidate = $base;
    $i = 2;
    while (isset($usedIds[$candidate])) {
      $candidate = $base . '-' . $i;
      $i++;
    }
    $usedIds[$candidate] = true;
    return $candidate;
  };

  $intro_html = '';
  $sections = array();
  $current = null;

  foreach (iterator_to_array($root->childNodes) as $child) {
    if (!($child instanceof DOMElement)) {
      // Keep text nodes (whitespace/newlines) with current bucket.
      if ($current === null) {
        $intro_html .= $dom->saveHTML($child);
      } else {
        $current['html'] .= $dom->saveHTML($child);
      }
      continue;
    }

    $tag = strtolower($child->tagName);
    if ($tag === 'h2') {
      if ($current !== null) {
        $sections[] = $current;
      }

      $title = trim(preg_replace('/\s+/', ' ', $child->textContent));
      if ($title === '') {
        $title = 'Section';
      }

      $id = $child->getAttribute('id');
      if ($id === '') {
        $id = $make_unique_id($title);
        $child->setAttribute('id', $id);
      }

      $current = array(
        'title' => $title,
        'id' => $id,
        'html' => '',
      );
      continue;
    }

    if ($current === null) {
      $intro_html .= $dom->saveHTML($child);
    } else {
      $current['html'] .= $dom->saveHTML($child);
    }
  }

  if ($current !== null) {
    $sections[] = $current;
  }

  libxml_clear_errors();
  libxml_use_internal_errors($prevUseErrors);

  $toc = array();
  foreach ($sections as $s) {
    if (!empty($s['title']) && !empty($s['id'])) {
      $toc[] = array('text' => $s['title'], 'id' => $s['id']);
    }
  }

  $result['toc'] = $toc;
  $result['intro_html'] = $intro_html;
  $result['sections'] = $sections;
  return $result;
}

$cd_bdp_raw_content = apply_filters('the_content', get_post_field('post_content', $cd_bdp_context_id));
$cd_bdp_raw_content = craftdigitally_bdp_strip_legacy_demo_lead($cd_bdp_raw_content);
$cd_bdp_built = craftdigitally_bdp_build_structured_content($cd_bdp_raw_content);
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
            <a class="bdp-text-wrapper" href="<?php echo esc_url($cd_bdp_back_url); ?>"><?php echo esc_html($cd_bdp_back_label); ?></a>
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
          <div class="bdp-frame-2">
            <div class="bdp-text-wrapper-3">Table of Contents</div>
            <div class="bdp-frame-3"></div>
            <div class="bdp-flexcontainer">
              <?php if (!empty($cd_bdp_toc)) : ?>
                <ol class="bdp-toc-list">
                  <?php foreach ($cd_bdp_toc as $toc_item) : ?>
                    <li class="bdp-toc-item">
                      <a class="bdp-toc-link" href="#<?php echo esc_attr($toc_item['id']); ?>"><?php echo esc_html($toc_item['text']); ?></a>
                    </li>
                  <?php endforeach; ?>
                </ol>
              <?php endif; ?>
            </div>
          </div>
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
                  <p class="bdp-text-wrapper-4"><?php echo esc_html($cd_bdp_sidebar_cta_title); ?></p>
                  <p class="bdp-p">
                    <?php echo esc_html($cd_bdp_sidebar_cta_text); ?>
                  </p>
                </div>
              </div>
            </div>
            <button class="bdp-button"><div class="bdp-text-wrapper-10"><?php echo esc_html($cd_bdp_sidebar_cta_btn_label); ?></div></button>
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
            <p class="bdp-text-wrapper-14"><?php echo esc_html($cd_bdp_cta_title); ?></p>
            <p class="bdp-text-wrapper-15"><?php echo esc_html($cd_bdp_cta_text); ?></p>
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
          <button class="bdp-button-2"><div class="bdp-text-wrapper-17"><?php echo esc_html($cd_bdp_cta_btn_label); ?></div></button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
get_footer();             
?>
