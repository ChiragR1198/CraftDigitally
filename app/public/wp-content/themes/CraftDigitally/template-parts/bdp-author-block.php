<?php
/**
 * Blog detail author card: avatar, tag, name, bio, social links (inline SVGs).
 *
 * @package CraftDigitally
 *
 * @param array $args {
 *   @type string              $author_tag
 *   @type string              $author_name
 *   @type string              $author_bio
 *   @type string              $avatar_url
 *   @type array<string,string> $social Keys: linkedin, x, facebook, instagram — absolute URLs.
 * }
 */

if (!defined('ABSPATH')) {
  exit;
}

$author_tag  = isset($args['author_tag']) ? (string) $args['author_tag'] : '';
$author_name = isset($args['author_name']) ? (string) $args['author_name'] : '';
$author_bio  = isset($args['author_bio']) ? (string) $args['author_bio'] : '';
$avatar_url  = isset($args['avatar_url']) ? (string) $args['avatar_url'] : '';
$social      = (isset($args['social']) && is_array($args['social'])) ? $args['social'] : array();

$avatar_alt = $author_name !== '' ? $author_name : __('Author', 'craftdigitally');
?>
<div class="bdp-cta-2">
  <div class="bdp-frame-4">
    <div class="bdp-frame-5">
      <?php if ($avatar_url !== '') : ?>
        <img
          class="bdp-author-avatar"
          src="<?php echo esc_url($avatar_url); ?>"
          alt="<?php echo esc_attr($avatar_alt); ?>"
          width="64"
          height="64"
          loading="lazy"
          decoding="async"
        />
      <?php endif; ?>
    </div>
    <div class="bdp-frame-wrapper">
      <div class="bdp-frame-6">
        <div class="bdp-div-3">
          <?php if ($author_tag !== '') : ?>
          <div class="bdp-local-SEO-expert"><?php echo esc_html($author_tag); ?></div>
          <?php endif; ?>
          <?php if ($author_name !== '') : ?>
          <div class="bdp-text-wrapper-11"><?php echo esc_html($author_name); ?></div>
          <?php endif; ?>
          <?php if ($author_bio !== '') : ?>
          <p class="bdp-text-wrapper-12"><?php echo esc_html($author_bio); ?></p>
          <?php endif; ?>

          <?php if (!empty($social)) : ?>
          <div class="bdp-author-social" role="list">
            <?php if (!empty($social['linkedin'])) : ?>
            <a class="bdp-author-social__link" role="listitem" href="<?php echo esc_url($social['linkedin']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('LinkedIn', 'craftdigitally'); ?>">
              <span class="bdp-author-social__icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" focusable="false"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
              </span>
            </a>
            <?php endif; ?>

            <?php if (!empty($social['x'])) : ?>
            <a class="bdp-author-social__link" role="listitem" href="<?php echo esc_url($social['x']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('X', 'craftdigitally'); ?>">
              <span class="bdp-author-social__icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" focusable="false"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
              </span>
            </a>
            <?php endif; ?>

            <?php if (!empty($social['facebook'])) : ?>
            <a class="bdp-author-social__link" role="listitem" href="<?php echo esc_url($social['facebook']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Facebook', 'craftdigitally'); ?>">
              <span class="bdp-author-social__icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" focusable="false"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              </span>
            </a>
            <?php endif; ?>

            <?php if (!empty($social['instagram'])) : ?>
            <a class="bdp-author-social__link" role="listitem" href="<?php echo esc_url($social['instagram']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Instagram', 'craftdigitally'); ?>">
              <span class="bdp-author-social__icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" focusable="false"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
              </span>
            </a>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
