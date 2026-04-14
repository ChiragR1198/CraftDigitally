<?php
/**
 * Blog detail table of contents: anchor targets for sidebar links.
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Stable fragment IDs for each default TOC row (0 = first H2-style section in article body).
 *
 * @param int $index Zero-based TOC item index.
 * @return string Fragment without leading #.
 */
function craftdigitally_bdp_toc_section_anchor_id($index) {
  $ids = array(
    'bdp-section-local-seo',
    'bdp-section-essential',
    'bdp-section-benefits',
    'bdp-section-factors',
    'bdp-section-improve',
    'bdp-section-final',
  );
  $index = (int) $index;
  if (isset($ids[$index])) {
    return $ids[$index];
  }
  return 'bdp-blog-content';
}
