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

/**
 * Normalize strings for reliable "legacy demo lead" matching.
 *
 * @param string $text
 * @return string
 */
function craftdigitally_bdp_normalize_text_for_legacy_match($text) {
  $text = wp_strip_all_tags((string) $text);
  $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

  // Normalize curly apostrophes and dash variants to plain equivalents.
  $text = str_replace(array('’', '&#8217;'), "'", $text);
  $text = str_replace(array('—', '–', '&ndash;', '&mdash;', '&#8211;', '&#8212;'), '-', $text);

  $text = trim(preg_replace('/\s+/u', ' ', $text));
  return $text;
}

/**
 * Legacy demo lead sentence which was historically seeded into blog posts.
 *
 * @return array<int, string> Normalized fingerprints
 */
function craftdigitally_bdp_legacy_lead_fingerprints() {
  $variants = array(
    "If you run a small or medium business today, competing locally is tougher than ever. Customers don't browse directories anymore — they search on Google, check reviews, and decide fast.",
    "If you run a small or medium business today, competing locally is tougher than ever. Customers don't browse directories anymore - they search on Google, check reviews, and decide fast.",
    "If you run a small or medium business today, competing locally is tougher than ever. Customers don’t browse directories anymore — they search on Google, check reviews, and decide fast.",
    "If you run a small or medium business today, competing locally is tougher than ever. Customers don’t browse directories anymore - they search on Google, check reviews, and decide fast.",
  );

  $out = array();
  foreach ($variants as $v) {
    $n = craftdigitally_bdp_normalize_text_for_legacy_match($v);
    if ($n !== '') {
      $out[] = $n;
    }
  }
  return array_values(array_unique($out));
}

/**
 * Remove the legacy demo lead paragraph from a string.
 * Works for both HTML (<p>...</p>) and plain text.
 *
 * @param mixed $value
 * @return string
 */
function craftdigitally_bdp_strip_legacy_demo_lead($value) {
  if (!is_string($value) || trim($value) === '') {
    return (string) $value;
  }

  $fingerprints = craftdigitally_bdp_legacy_lead_fingerprints();
  if (empty($fingerprints)) {
    return $value;
  }

  // Plain text fast path.
  if (strpos($value, '<') === false) {
    $normalized = craftdigitally_bdp_normalize_text_for_legacy_match($value);
    if (in_array($normalized, $fingerprints, true)) {
      return '';
    }

    // If it contains the sentence inside a longer string, remove the raw sentence variants.
    foreach ($fingerprints as $fp) {
      // Best-effort: remove when exact normalized sentence appears inside normalized string.
      if (strpos($normalized, $fp) !== false) {
        // Since we may not have the exact original entity/whitespace, fall back to regex removal on a normalized mirror.
        $value_norm = craftdigitally_bdp_normalize_text_for_legacy_match($value);
        if ($value_norm === $fp) {
          return '';
        }
      }
    }
    return $value;
  }

  // HTML path: remove first matching <p> whose text matches fingerprint.
  if (!class_exists('DOMDocument')) {
    return $value;
  }

  $dom = new DOMDocument();
  $prevUseErrors = libxml_use_internal_errors(true);

  $wrapped = '<!doctype html><html><head><meta charset="utf-8"></head><body><div id="cd-bdp-root">' . $value . '</div></body></html>';
  $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

  $root = $dom->getElementById('cd-bdp-root');
  if (!$root) {
    libxml_clear_errors();
    libxml_use_internal_errors($prevUseErrors);
    return $value;
  }

  foreach ($root->getElementsByTagName('p') as $p) {
    if (!($p instanceof DOMElement)) {
      continue;
    }

    $normalized = craftdigitally_bdp_normalize_text_for_legacy_match($p->textContent);
    if (in_array($normalized, $fingerprints, true)) {
      // Remove the paragraph (and nearby whitespace text nodes).
      $prev = $p->previousSibling;
      while ($prev && $prev instanceof DOMText && trim($prev->textContent) === '') {
        $to_remove = $prev;
        $prev = $prev->previousSibling;
        $root->removeChild($to_remove);
      }

      if ($p->parentNode) {
        $p->parentNode->removeChild($p);
      }
      break;
    }
  }

  $out = '';
  foreach ($root->childNodes as $child) {
    $out .= $dom->saveHTML($child);
  }

  libxml_clear_errors();
  libxml_use_internal_errors($prevUseErrors);

  return $out;
}

/**
 * Build Blog Detail structured content from editor HTML.
 * - TOC is built from H2 headings (in order).
 * - Intro is everything before first H2.
 * - Sections are split by H2 (each section contains everything until next H2).
 *
 * @param mixed $html
 * @return array{
 *  toc: array<int, array{text:string, id:string}>,
 *  intro_html: string,
 *  sections: array<int, array{title:string, id:string, html:string}>
 * }
 */
function craftdigitally_bdp_build_structured_content_from_post_html($html) {
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
