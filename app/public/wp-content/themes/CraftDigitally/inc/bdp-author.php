<?php
/**
 * Blog detail author block helpers.
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Default author bio when ACF is empty.
 *
 * @return string
 */
function craftdigitally_bdp_default_author_bio_text() {
  return 'Passionate about helping local businesses get discovered, Yashasvi creates simple, effective SEO strategies that increase foot traffic, calls, and online visibility.';
}

/**
 * Default social profile URLs when no per-post / per-page URLs are set.
 *
 * @return array<string, string>
 */
function craftdigitally_bdp_default_author_social_urls() {
  return array(
    'linkedin'  => 'https://www.linkedin.com/',
    'x'         => 'https://twitter.com/',
    'facebook'  => 'https://www.facebook.com/',
    'instagram' => 'https://www.instagram.com/',
  );
}

/**
 * Replace generic WP "admin" labels with a readable name.
 *
 * @param int    $post_id Post ID for author lookup.
 * @param string $name    Candidate display name (may be from ACF).
 * @return string
 */
function craftdigitally_bdp_resolve_author_display_name($post_id, $name) {
  $n = trim((string) $name);
  if ($n !== '' && strtolower($n) !== 'admin') {
    return $n;
  }
  $uid = (int) get_post_field('post_author', $post_id);
  $dn  = trim((string) get_the_author_meta('display_name', $uid));
  if ($dn !== '' && strtolower($dn) !== 'admin') {
    return $dn;
  }
  return 'Yashasvi Zala';
}

/**
 * Avatar URL: ACF image if set, otherwise WordPress/Gravatar for the post author.
 *
 * @param int         $post_id     Post or page ID.
 * @param string      $acf_field   e.g. blog_author_avatar or blog_detail_author_avatar.
 * @return string
 */
function craftdigitally_bdp_author_avatar_url($post_id, $acf_field) {
  $acf = craftdigitally_get_acf_image_url($acf_field, '', $post_id);
  if ($acf !== '') {
    return $acf;
  }
  $uid = (int) get_post_field('post_author', $post_id);
  if ($uid < 1) {
    return '';
  }
  $url = get_avatar_url($uid, array('size' => 256));
  return $url ? $url : '';
}

/**
 * Social URLs from ACF; if none set, use theme defaults (same destinations as footer placeholders).
 *
 * @param int    $post_id Post or page ID.
 * @param string $prefix  `blog_` or `blog_detail_`.
 * @return array<string, string> Keys: linkedin, x, facebook, instagram.
 */
function craftdigitally_bdp_author_social_urls_resolved($post_id, $prefix) {
  $map = array(
    'linkedin'  => $prefix . 'author_linkedin_url',
    'x'         => $prefix . 'author_x_url',
    'facebook'  => $prefix . 'author_facebook_url',
    'instagram' => $prefix . 'author_instagram_url',
  );
  $out = array();
  $any = false;
  foreach ($map as $key => $field_name) {
    $u = trim((string) craftdigitally_get_acf($field_name, '', $post_id));
    if ($u !== '') {
      $out[$key] = esc_url_raw($u);
      $any       = true;
    }
  }
  if (!$any) {
    foreach (craftdigitally_bdp_default_author_social_urls() as $key => $u) {
      $out[$key] = esc_url_raw($u);
    }
  }
  return $out;
}

/**
 * Build args for template-parts/bdp-author-block.php.
 *
 * @param int    $post_id Context post/page ID.
 * @param string $prefix  `blog_` (single post) or `blog_detail_` (blog detail page fallback).
 * @return array<string, mixed>
 */
function craftdigitally_bdp_get_author_block_args($post_id, $prefix) {
  $raw_name = craftdigitally_get_acf($prefix . 'author_name', '', $post_id);
  if ($raw_name === '') {
    $uid      = (int) get_post_field('post_author', $post_id);
    $raw_name = get_the_author_meta('display_name', $uid);
  }
  $name = craftdigitally_bdp_resolve_author_display_name($post_id, (string) $raw_name);

  $bio = craftdigitally_get_acf($prefix . 'author_bio', '', $post_id);
  if ($bio === '') {
    $bio = craftdigitally_bdp_default_author_bio_text();
  }

  $tag = craftdigitally_get_acf($prefix . 'author_tag', 'LOCAL SEO EXPERT', $post_id);

  $avatar_url = craftdigitally_bdp_author_avatar_url($post_id, $prefix . 'author_avatar');
  $social     = craftdigitally_bdp_author_social_urls_resolved($post_id, $prefix);

  return array(
    'author_tag'   => $tag,
    'author_name'  => $name,
    'author_bio'   => $bio,
    'avatar_url'   => $avatar_url,
    'social'       => $social,
  );
}
