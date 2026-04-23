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
 * Get the assigned WordPress author for the current post/page context.
 *
 * @param int $post_id Post or page ID.
 * @return WP_User|null
 */
function craftdigitally_bdp_get_author_user($post_id) {
  $uid = (int) get_post_field('post_author', $post_id);
  if ($uid < 1) {
    return null;
  }

  $user = get_userdata($uid);
  return ($user instanceof WP_User) ? $user : null;
}

/**
 * Build a readable author name from the assigned user.
 *
 * @param int $post_id Post or page ID.
 * @return string
 */
function craftdigitally_bdp_resolve_author_display_name($post_id) {
  $user = craftdigitally_bdp_get_author_user($post_id);
  if ($user instanceof WP_User) {
    $display_name = trim((string) $user->display_name);
    if ($display_name !== '' && strtolower($display_name) !== 'admin') {
      return $display_name;
    }

    $full_name = trim((string) $user->first_name . ' ' . (string) $user->last_name);
    if ($full_name !== '') {
      return $full_name;
    }

    $nickname = trim((string) $user->nickname);
    if ($nickname !== '' && strtolower($nickname) !== 'admin') {
      return $nickname;
    }

    $user_login = trim((string) $user->user_login);
    if ($user_login !== '') {
      return $user_login;
    }
  }

  return 'CraftDigitally Team';
}

/**
 * Author badge label for the assigned user.
 *
 * @param int $post_id Post or page ID.
 * @return string
 */
function craftdigitally_bdp_author_role_label($post_id) {
  $user = craftdigitally_bdp_get_author_user($post_id);
  if (!($user instanceof WP_User)) {
    return 'Author';
  }

  $custom_tag = trim((string) get_user_meta($user->ID, 'author_tag', true));
  if ($custom_tag !== '') {
    return $custom_tag;
  }

  return 'Author';
}

/**
 * Avatar URL from the assigned WordPress user.
 *
 * @param int $post_id Post or page ID.
 * @return string
 */
function craftdigitally_bdp_author_avatar_url($post_id) {
  $user = craftdigitally_bdp_get_author_user($post_id);
  if (!($user instanceof WP_User)) {
    return '';
  }

  $url = get_avatar_url($user->ID, array('size' => 256));
  return $url ? $url : '';
}

/**
 * Social URLs from the assigned user's profile/meta.
 *
 * @param int $post_id Post or page ID.
 * @return array<string, string>
 */
function craftdigitally_bdp_author_social_urls_resolved($post_id) {
  $out = array();
  $user = craftdigitally_bdp_get_author_user($post_id);
  if (!($user instanceof WP_User)) {
    return $out;
  }

  $meta_map = array(
    'linkedin' => array('linkedin', 'linkedin_url'),
    'x' => array('x', 'x_url', 'twitter', 'twitter_url'),
    'facebook' => array('facebook', 'facebook_url'),
    'whatsapp' => array('whatsapp', 'whatsapp_url'),
  );

  foreach ($meta_map as $key => $meta_keys) {
    if (isset($out[$key])) {
      continue;
    }
    foreach ($meta_keys as $meta_key) {
      $value = trim((string) get_user_meta($user->ID, $meta_key, true));
      if ($value !== '') {
        $out[$key] = esc_url_raw($value);
        break;
      }
    }
  }

  $website = trim((string) $user->user_url);
  if ($website !== '') {
    $host = strtolower((string) wp_parse_url($website, PHP_URL_HOST));
    if (!isset($out['linkedin']) && strpos($host, 'linkedin.') !== false) {
      $out['linkedin'] = esc_url_raw($website);
    } elseif (!isset($out['x']) && (strpos($host, 'x.com') !== false || strpos($host, 'twitter.com') !== false)) {
      $out['x'] = esc_url_raw($website);
    } elseif (!isset($out['facebook']) && strpos($host, 'facebook.com') !== false) {
      $out['facebook'] = esc_url_raw($website);
    } elseif (!isset($out['whatsapp']) && (strpos($host, 'whatsapp.com') !== false || strpos($host, 'wa.me') !== false)) {
      $out['whatsapp'] = esc_url_raw($website);
    }
  }

  return $out;
}

/**
 * Build args for template-parts/bdp-author-block.php.
 *
 * @param int    $post_id Context post/page ID.
 * @param string $prefix  Unused. Kept for backward compatibility with existing template calls.
 * @return array<string, mixed>
 */
function craftdigitally_bdp_get_author_block_args($post_id, $prefix) {
  $name = craftdigitally_bdp_resolve_author_display_name($post_id);

  $user = craftdigitally_bdp_get_author_user($post_id);
  $bio  = '';
  if ($user instanceof WP_User) {
    $bio = trim((string) get_the_author_meta('description', $user->ID));
  }
  if ($bio === '') {
    $bio = craftdigitally_bdp_default_author_bio_text();
  }

  $tag = craftdigitally_bdp_author_role_label($post_id);

  $avatar_url = craftdigitally_bdp_author_avatar_url($post_id);
  $social     = craftdigitally_bdp_author_social_urls_resolved($post_id);

  return array(
    'author_tag'   => $tag,
    'author_name'  => $name,
    'author_bio'   => $bio,
    'avatar_url'   => $avatar_url,
    'social'       => $social,
  );
}

/**
 * Render author social link fields on the WordPress user profile screen.
 *
 * @param WP_User $user User being edited.
 * @return void
 */
function craftdigitally_author_social_profile_fields($user) {
  if (!($user instanceof WP_User)) {
    return;
  }
  ?>
  <h2><?php esc_html_e('Author Social Links', 'craftdigitally'); ?></h2>
  <table class="form-table" role="presentation">
    <tr>
      <th><label for="craftdigitally_linkedin_url"><?php esc_html_e('LinkedIn URL', 'craftdigitally'); ?></label></th>
      <td><input type="url" name="craftdigitally_linkedin_url" id="craftdigitally_linkedin_url" value="<?php echo esc_attr((string) get_user_meta($user->ID, 'linkedin_url', true)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="craftdigitally_x_url"><?php esc_html_e('X (Twitter) URL', 'craftdigitally'); ?></label></th>
      <td><input type="url" name="craftdigitally_x_url" id="craftdigitally_x_url" value="<?php echo esc_attr((string) get_user_meta($user->ID, 'x_url', true)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="craftdigitally_facebook_url"><?php esc_html_e('Facebook URL', 'craftdigitally'); ?></label></th>
      <td><input type="url" name="craftdigitally_facebook_url" id="craftdigitally_facebook_url" value="<?php echo esc_attr((string) get_user_meta($user->ID, 'facebook_url', true)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="craftdigitally_whatsapp_url"><?php esc_html_e('WhatsApp URL', 'craftdigitally'); ?></label></th>
      <td><input type="url" name="craftdigitally_whatsapp_url" id="craftdigitally_whatsapp_url" value="<?php echo esc_attr((string) get_user_meta($user->ID, 'whatsapp_url', true)); ?>" class="regular-text" /></td>
    </tr>
  </table>
  <?php
}
add_action('show_user_profile', 'craftdigitally_author_social_profile_fields');
add_action('edit_user_profile', 'craftdigitally_author_social_profile_fields');

/**
 * Save author social link fields from the WordPress user profile screen.
 *
 * @param int $user_id User ID being saved.
 * @return void
 */
function craftdigitally_save_author_social_profile_fields($user_id) {
  if (!current_user_can('edit_user', $user_id)) {
    return;
  }

  $map = array(
    'craftdigitally_linkedin_url' => 'linkedin_url',
    'craftdigitally_x_url' => 'x_url',
    'craftdigitally_facebook_url' => 'facebook_url',
    'craftdigitally_whatsapp_url' => 'whatsapp_url',
  );

  foreach ($map as $posted_key => $meta_key) {
    $value = isset($_POST[$posted_key]) ? trim((string) wp_unslash($_POST[$posted_key])) : '';
    if ($value === '') {
      delete_user_meta($user_id, $meta_key);
      continue;
    }
    update_user_meta($user_id, $meta_key, esc_url_raw($value));
  }
}
add_action('personal_options_update', 'craftdigitally_save_author_social_profile_fields');
add_action('edit_user_profile_update', 'craftdigitally_save_author_social_profile_fields');
