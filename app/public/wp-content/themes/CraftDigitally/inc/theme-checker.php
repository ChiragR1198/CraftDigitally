<?php
/**
 * Theme Compatibility Checker
 * Add this to WordPress admin to check theme compatibility
 * 
 * @package CraftDigitally
 */

// Add admin menu for theme checker
add_action('admin_menu', 'craftdigitally_theme_checker_menu');
function craftdigitally_theme_checker_menu() {
  add_theme_page(
    'Theme Compatibility Checker',
    'Theme Checker',
    'manage_options',
    'theme-checker',
    'craftdigitally_theme_checker_page'
  );
}

// Theme checker page
function craftdigitally_theme_checker_page() {
  ?>
  <div class="wrap">
    <h1>CraftDigitally Theme Compatibility Checker</h1>
    <p>This tool checks if your theme is properly configured and compatible with popular plugins.</p>
    
    <div class="theme-checker-results">
      <?php craftdigitally_run_theme_checks(); ?>
    </div>
  </div>
  
  <style>
    .theme-checker-results {
      margin-top: 20px;
    }
    .check-item {
      background: #fff;
      border-left: 4px solid #ddd;
      padding: 15px;
      margin-bottom: 15px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .check-item.success {
      border-left-color: #46b450;
    }
    .check-item.warning {
      border-left-color: #ffb900;
    }
    .check-item.error {
      border-left-color: #dc3232;
    }
    .check-item h3 {
      margin: 0 0 10px 0;
      font-size: 16px;
    }
    .check-item p {
      margin: 5px 0;
      color: #666;
    }
    .status-icon {
      font-size: 20px;
      margin-right: 10px;
    }
  </style>
  <?php
}

// Run theme compatibility checks
function craftdigitally_run_theme_checks() {
  $checks = array();
  
  // Check 1: Theme Support Features
  $theme_supports = array(
    'title-tag' => current_theme_supports('title-tag'),
    'post-thumbnails' => current_theme_supports('post-thumbnails'),
    'html5' => current_theme_supports('html5'),
    'custom-logo' => current_theme_supports('custom-logo'),
    'automatic-feed-links' => current_theme_supports('automatic-feed-links'),
  );
  
  $all_support = true;
  foreach ($theme_supports as $feature => $supported) {
    if (!$supported) {
      $all_support = false;
      break;
    }
  }
  
  $checks[] = array(
    'title' => 'WordPress Theme Support',
    'status' => $all_support ? 'success' : 'error',
    'message' => $all_support ? 'All required theme supports are enabled.' : 'Some theme supports are missing.',
    'details' => $theme_supports
  );
  
  // Check 2: Navigation Menus
  $nav_menus = get_registered_nav_menus();
  $checks[] = array(
    'title' => 'Navigation Menus',
    'status' => count($nav_menus) > 0 ? 'success' : 'error',
    'message' => count($nav_menus) > 0 ? count($nav_menus) . ' navigation menu(s) registered.' : 'No navigation menus registered.',
    'details' => $nav_menus
  );
  
  // Check 3: Widget Areas
  global $wp_registered_sidebars;
  $widget_count = count($wp_registered_sidebars);
  $checks[] = array(
    'title' => 'Widget Areas',
    'status' => $widget_count > 0 ? 'success' : 'warning',
    'message' => $widget_count . ' widget area(s) registered.',
    'details' => array_keys($wp_registered_sidebars)
  );
  
  // Check 4: Page Templates
  $page_templates = wp_get_theme()->get_page_templates();
  $checks[] = array(
    'title' => 'Page Templates',
    'status' => count($page_templates) > 0 ? 'success' : 'warning',
    'message' => count($page_templates) . ' custom page template(s) available.',
    'details' => $page_templates
  );
  
  // Check 5: Elementor Compatibility
  $elementor_active = class_exists('\Elementor\Plugin');
  $elementor_support = current_theme_supports('elementor');
  $checks[] = array(
    'title' => 'Elementor Page Builder',
    'status' => $elementor_active ? ($elementor_support ? 'success' : 'warning') : 'warning',
    'message' => $elementor_active ? 
      ($elementor_support ? 'Elementor is active and theme has Elementor support.' : 'Elementor is active but theme support is recommended.') : 
      'Elementor is not installed.',
    'details' => array(
      'Elementor Active' => $elementor_active ? 'Yes' : 'No',
      'Theme Support' => $elementor_support ? 'Yes' : 'No'
    )
  );
  
  // Check 6: WooCommerce Compatibility (Optional)
  $woo_active = class_exists('WooCommerce');
  $woo_support = current_theme_supports('woocommerce');
  
  if ($woo_active) {
    $woo_status = $woo_support ? 'success' : 'warning';
    $woo_message = $woo_support ? 
      'WooCommerce is active and fully supported.' : 
      'WooCommerce is active. Theme support available if needed.';
  } else {
    $woo_status = 'success';
    $woo_message = 'WooCommerce not installed (Optional - only needed for e-commerce sites)';
  }
  
  $checks[] = array(
    'title' => 'WooCommerce (Optional)',
    'status' => $woo_status,
    'message' => $woo_message,
    'details' => array(
      'WooCommerce Active' => $woo_active ? 'Yes' : 'No',
      'Theme Support' => $woo_support ? 'Yes (Ready)' : 'Available if needed',
      'Required' => 'No - Only for e-commerce sites'
    )
  );
  
  // Check 7: Required Template Files
  $required_templates = array(
    'index.php' => 'Main template file',
    'style.css' => 'Main stylesheet',
    'header.php' => 'Header template',
    'footer.php' => 'Footer template',
    'functions.php' => 'Theme functions',
  );
  
  $template_dir = get_template_directory();
  $missing_templates = array();
  
  foreach ($required_templates as $file => $desc) {
    if (!file_exists($template_dir . '/' . $file)) {
      $missing_templates[] = $file;
    }
  }
  
  $checks[] = array(
    'title' => 'Required Template Files',
    'status' => count($missing_templates) === 0 ? 'success' : 'error',
    'message' => count($missing_templates) === 0 ? 
      'All required template files exist.' : 
      'Missing files: ' . implode(', ', $missing_templates),
    'details' => $required_templates
  );
  
  // Check 8: WordPress Version
  global $wp_version;
  $version_ok = version_compare($wp_version, '5.9', '>=');
  $checks[] = array(
    'title' => 'WordPress Version',
    'status' => $version_ok ? 'success' : 'warning',
    'message' => 'WordPress ' . $wp_version . ' ' . ($version_ok ? '(Compatible)' : '(Update Recommended)'),
    'details' => array('Version' => $wp_version)
  );
  
  // Check 9: PHP Version
  $php_version = phpversion();
  $php_ok = version_compare($php_version, '7.4', '>=');
  $checks[] = array(
    'title' => 'PHP Version',
    'status' => $php_ok ? 'success' : 'error',
    'message' => 'PHP ' . $php_version . ' ' . ($php_ok ? '(Compatible)' : '(Upgrade Required)'),
    'details' => array('Version' => $php_version)
  );
  
  // Check 10: SEO Plugins (Recommended for SEO/Marketing sites)
  $seo_plugins = array(
    'Yoast SEO' => class_exists('WPSEO_Options'),
    'Rank Math' => class_exists('RankMath'),
    'All in One SEO' => class_exists('All_in_One_SEO_Pack'),
  );
  
  $seo_active = array_filter($seo_plugins);
  $seo_status = count($seo_active) > 0 ? 'success' : 'warning';
  $seo_message = count($seo_active) > 0 ? 
    'SEO plugin active: ' . implode(', ', array_keys($seo_active)) : 
    'No SEO plugin detected. Recommended for SEO/Marketing sites.';
  
  $checks[] = array(
    'title' => 'SEO Plugin (Recommended)',
    'status' => $seo_status,
    'message' => $seo_message,
    'details' => array(
      'Yoast SEO' => $seo_plugins['Yoast SEO'] ? 'Installed' : 'Not installed',
      'Rank Math' => $seo_plugins['Rank Math'] ? 'Installed' : 'Not installed',
      'All in One SEO' => $seo_plugins['All in One SEO'] ? 'Installed' : 'Not installed',
      'Recommended' => 'Yoast SEO or Rank Math'
    )
  );
  
  // Check 11: Active Plugins
  $active_plugins = get_option('active_plugins');
  $checks[] = array(
    'title' => 'Active Plugins',
    'status' => 'success',
    'message' => count($active_plugins) . ' plugin(s) active.',
    'details' => array('Count' => count($active_plugins))
  );
  
  // Check 12: Contact Form Plugin (Recommended)
  $contact_plugins = array(
    'Contact Form 7' => class_exists('WPCF7'),
    'WPForms' => class_exists('WPForms'),
    'Ninja Forms' => class_exists('Ninja_Forms'),
  );
  
  $contact_active = array_filter($contact_plugins);
  $contact_status = count($contact_active) > 0 ? 'success' : 'warning';
  $contact_message = count($contact_active) > 0 ? 
    'Contact form plugin active: ' . implode(', ', array_keys($contact_active)) : 
    'No contact form plugin. Theme has built-in contact form.';
  
  $checks[] = array(
    'title' => 'Contact Form Plugin',
    'status' => $contact_status,
    'message' => $contact_message,
    'details' => array(
      'Built-in Form' => 'Yes (Contact Page template)',
      'CF7' => $contact_plugins['Contact Form 7'] ? 'Installed' : 'Not installed',
      'WPForms' => $contact_plugins['WPForms'] ? 'Installed' : 'Not installed',
      'Note' => 'Theme already has contact form on Contact Page template'
    )
  );
  
  // Display results
  echo '<h2 style="margin-top: 30px;">✅ Essential Checks</h2>';
  
  $essential_checks = array_slice($checks, 0, 9); // First 9 checks
  foreach ($essential_checks as $check) {
    $icon = $check['status'] === 'success' ? '✅' : ($check['status'] === 'warning' ? '⚠️' : '❌');
    ?>
    <div class="check-item <?php echo esc_attr($check['status']); ?>">
      <h3><span class="status-icon"><?php echo $icon; ?></span><?php echo esc_html($check['title']); ?></h3>
      <p><?php echo esc_html($check['message']); ?></p>
      <?php if (!empty($check['details']) && is_array($check['details'])) : ?>
        <details style="margin-top: 10px;">
          <summary style="cursor: pointer; color: #0073aa;">View Details</summary>
          <ul style="margin: 10px 0; padding-left: 20px;">
            <?php foreach ($check['details'] as $key => $value) : ?>
              <li><?php echo esc_html($key); ?>: <?php echo is_bool($value) ? ($value ? 'Yes' : 'No') : esc_html($value); ?></li>
            <?php endforeach; ?>
          </ul>
        </details>
      <?php endif; ?>
    </div>
    <?php
  }
  
  // Display recommended plugins/features for SEO sites
  echo '<h2 style="margin-top: 40px;">🎯 Recommended for SEO/Marketing Sites</h2>';
  
  $recommended_checks = array_slice($checks, 9); // Remaining checks
  foreach ($recommended_checks as $check) {
    $icon = $check['status'] === 'success' ? '✅' : ($check['status'] === 'warning' ? '⚠️' : '❌');
    ?>
    <div class="check-item <?php echo esc_attr($check['status']); ?>">
      <h3><span class="status-icon"><?php echo $icon; ?></span><?php echo esc_html($check['title']); ?></h3>
      <p><?php echo esc_html($check['message']); ?></p>
      <?php if (!empty($check['details']) && is_array($check['details'])) : ?>
        <details style="margin-top: 10px;">
          <summary style="cursor: pointer; color: #0073aa;">View Details</summary>
          <ul style="margin: 10px 0; padding-left: 20px;">
            <?php foreach ($check['details'] as $key => $value) : ?>
              <li><?php echo esc_html($key); ?>: <?php echo is_bool($value) ? ($value ? 'Yes' : 'No') : esc_html($value); ?></li>
            <?php endforeach; ?>
          </ul>
        </details>
      <?php endif; ?>
    </div>
    <?php
  }
  
  // Add recommendations box
  ?>
  <div style="background: #e7f5fe; border-left: 4px solid #0073aa; padding: 20px; margin-top: 30px;">
    <h3 style="margin-top: 0;">📌 Recommended Plugins for Your SEO/Marketing Site:</h3>
    <ol style="margin: 15px 0; padding-left: 25px;">
      <li><strong>Yoast SEO</strong> or <strong>Rank Math</strong> - For SEO optimization</li>
      <li><strong>WP Rocket</strong> or <strong>W3 Total Cache</strong> - For performance & caching</li>
      <li><strong>Elementor</strong> or <strong>Beaver Builder</strong> - For page building (optional)</li>
      <li><strong>Contact Form 7</strong> or <strong>WPForms</strong> - For advanced forms (theme has built-in form)</li>
      <li><strong>Wordfence</strong> or <strong>iThemes Security</strong> - For security</li>
      <li><strong>UpdraftPlus</strong> - For backups</li>
    </ol>
    <p style="margin: 10px 0 0 0;"><strong>Note:</strong> WooCommerce is NOT needed for SEO/Marketing sites. Only install if you need e-commerce functionality.</p>
  </div>
  <?php
}

