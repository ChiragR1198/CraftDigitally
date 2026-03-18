<?php
/**
 * Create Default Pages for CraftDigitally Theme
 * Run this once to create sample pages
 * 
 * @package CraftDigitally
 */

// Add admin notice to create default pages
add_action('admin_notices', 'craftdigitally_default_pages_notice');
function craftdigitally_default_pages_notice() {
  // Check if pages already created
  $pages_created = get_option('craftdigitally_default_pages_created', false);
  
  if (!$pages_created && current_user_can('manage_options')) {
    ?>
    <div class="notice notice-info is-dismissible">
      <p><strong>CraftDigitally Theme:</strong> Would you like to create default pages (Home, About, Services, Contact)?</p>
      <p>
        <a href="<?php echo admin_url('admin.php?page=create-default-pages'); ?>" class="button button-primary">Create Default Pages</a>
        <a href="<?php echo add_query_arg('craftdigitally_dismiss_pages', '1'); ?>" class="button">Not Now</a>
      </p>
    </div>
    <?php
  }
}

// Dismiss notice
add_action('admin_init', 'craftdigitally_dismiss_pages_notice');
function craftdigitally_dismiss_pages_notice() {
  if (isset($_GET['craftdigitally_dismiss_pages'])) {
    update_option('craftdigitally_default_pages_created', 'dismissed');
    wp_redirect(remove_query_arg('craftdigitally_dismiss_pages'));
    exit;
  }
}

// Add admin menu for creating default pages
add_action('admin_menu', 'craftdigitally_default_pages_menu');
function craftdigitally_default_pages_menu() {
  add_theme_page(
    'Create Default Pages',
    'Create Pages',
    'manage_options',
    'create-default-pages',
    'craftdigitally_create_default_pages_page',
    99
  );
  
  // Add cleanup pages menu
  add_theme_page(
    'Cleanup Pages',
    'Cleanup Pages',
    'manage_options',
    'cleanup-pages',
    'craftdigitally_cleanup_pages_page',
    100
  );
}

// Default pages creation page
function craftdigitally_create_default_pages_page() {
  // Handle form submission
  if (isset($_POST['create_default_pages']) && check_admin_referer('create_default_pages_nonce')) {
    craftdigitally_create_default_pages();
    echo '<div class="notice notice-success"><p>Default pages created successfully! <a href="' . admin_url('edit.php?post_type=page') . '">View Pages</a></p></div>';
  }
  
  ?>
  <div class="wrap">
    <h1>Create Default Pages</h1>
    <p>This will create the following pages for your website:</p>
    
    <ul style="list-style: disc; margin-left: 20px; margin-top: 10px;">
      <li><strong>Home</strong> - Your homepage (will be set as front page)</li>
      <li><strong>About Us</strong> - Information about your company</li>
      <li><strong>Services</strong> - Your services page</li>
      <li><strong>Contact</strong> - Contact form page</li>
      <li><strong>Blog</strong> - Your blog posts page</li>
    </ul>
    
    <form method="post" style="margin-top: 20px;">
      <?php wp_nonce_field('create_default_pages_nonce'); ?>
      <input type="submit" name="create_default_pages" class="button button-primary button-hero" value="Create Default Pages">
    </form>
    
    <hr style="margin: 30px 0;">
    
    <h2>Manual Creation</h2>
    <p>You can also create pages manually:</p>
    <ol style="margin-left: 20px;">
      <li>Go to <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>">Pages → Add New</a></li>
      <li>Enter page title and content</li>
      <li>Select a page template (Default, Full Width, Contact, etc.)</li>
      <li>Click Publish</li>
    </ol>
  </div>
  <?php
}

// Function to create default pages
function craftdigitally_create_default_pages() {
  // Check if already created
  if (get_option('craftdigitally_default_pages_created')) {
    return;
  }
  
  $pages = array(
    array(
      'title' => 'Home',
      'content' => '<h2>Welcome to CraftDigitally</h2>
<p>We\'re a leading digital marketing agency that helps businesses in Ahmedabad turn SEO, web development, and digital marketing into powerful growth channels.</p>
<p>Stand out online with clarity, strategy and confidence.</p>',
      'template' => 'default',
      'is_front_page' => true
    ),
    array(
      'title' => 'About Us',
      'content' => '<h2>About CraftDigitally</h2>
<p>We are a team of passionate digital marketing professionals dedicated to helping businesses grow online.</p>

<h3>Our Mission</h3>
<p>To empower businesses with cutting-edge digital marketing strategies that deliver real results.</p>

<h3>Our Vision</h3>
<p>To be the most trusted digital marketing agency in Ahmedabad and beyond.</p>

<h3>Why Choose Us?</h3>
<ul>
<li>Expert team with years of experience</li>
<li>Proven track record of success</li>
<li>Transparent reporting and communication</li>
<li>Affordable and flexible pricing</li>
</ul>',
      'template' => 'page-templates/page-full-width.php'
    ),
    array(
      'title' => 'Services',
      'content' => '<h2>Our Services</h2>
<p>Whether you\'re a startup or an established brand, we\'ve got your back. Your Roadmap to More Traffic, Leads & Sales.</p>

<h3>Local SEO</h3>
<p>Turn your local presence into lasting visibility. Align your listings, keywords, and on-page SEO so your business becomes the clear choice in your area.</p>

<h3>Link Building</h3>
<p>Build trust and authority with ethical, high-quality backlinks that strengthen your rankings and position your brand as a credible voice in your industry.</p>

<h3>Ecommerce SEO</h3>
<p>Make your products easy to find and effortless to buy. Optimize your store\'s structure, speed, and search visibility for better reach and higher conversions.</p>

<h3>International SEO</h3>
<p>Expand your reach across borders with tailored multilingual and regional strategies that make your brand visible and relevant worldwide.</p>

<h3>Small Business SEO</h3>
<p>Simplify growth with SEO built for your scale – clear, focused strategies that make your website a steady source of visibility and leads.</p>

<h3>Social Media Services</h3>
<p>Create connection through clarity – we craft consistent, on-brand social content that strengthens visibility, trust, and engagement across every platform.</p>',
      'template' => 'page-templates/page-full-width.php'
    ),
    array(
      'title' => 'Contact',
      'content' => '<h2>Get In Touch</h2>
<p>Ready to dominate your competition? Let\'s connect and discuss how we can help your business grow.</p>

<h3>Contact Information</h3>
<p><strong>Email:</strong> info@craftdigitally.com<br>
<strong>Phone:</strong> +91 12345 67890<br>
<strong>Address:</strong> Ahmedabad, Gujarat, India</p>

<h3>Send Us a Message</h3>
<p>Fill out the contact form below and we\'ll get back to you as soon as possible.</p>',
      'template' => 'page-templates/page-contact.php'
    ),
    array(
      'title' => 'Blog',
      'content' => '<h2>Our Latest Posts</h2>
<p>Stay updated with the latest news, tips, and insights from the world of digital marketing.</p>',
      'template' => 'page-templates/page-blog-landing.php',
      'is_posts_page' => false
    ),
  );
  
  $created_pages = array();
  
  foreach ($pages as $page_data) {
    // Check if page already exists
    $existing_page = get_page_by_title($page_data['title']);
    
    if (!$existing_page) {
      $page_id = wp_insert_post(array(
        'post_title' => $page_data['title'],
        'post_content' => $page_data['content'],
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
      ));
      
      if ($page_id && !is_wp_error($page_id)) {
        // Set page template
        if ($page_data['template'] !== 'default') {
          update_post_meta($page_id, '_wp_page_template', $page_data['template']);
        }
        
        $created_pages[$page_data['title']] = $page_id;
        
        // Set as front page
        if (isset($page_data['is_front_page']) && $page_data['is_front_page']) {
          update_option('page_on_front', $page_id);
          update_option('show_on_front', 'page');
        }
        
        // Set as posts page
        if (isset($page_data['is_posts_page']) && $page_data['is_posts_page']) {
          update_option('page_for_posts', $page_id);
        }
      }
    }
  }
  
  // Mark as created
  update_option('craftdigitally_default_pages_created', true);
  update_option('craftdigitally_created_page_ids', $created_pages);
  
  return $created_pages;
}

// Add quick action link in admin bar
add_action('admin_bar_menu', 'craftdigitally_admin_bar_pages_link', 999);
function craftdigitally_admin_bar_pages_link($wp_admin_bar) {
  if (!current_user_can('edit_pages')) {
    return;
  }
  
  $args = array(
    'id' => 'craftdigitally-pages',
    'title' => 'View All Pages',
    'href' => admin_url('edit.php?post_type=page'),
    'meta' => array('class' => 'craftdigitally-pages-link')
  );
  
  $wp_admin_bar->add_node($args);
}

// Function to cleanup pages - keep only specified pages
function craftdigitally_cleanup_pages_page() {
  // Handle form submission
  if (isset($_POST['cleanup_pages']) && check_admin_referer('cleanup_pages_nonce')) {
    $result = craftdigitally_delete_extra_pages();
    
    if ($result['success']) {
      echo '<div class="notice notice-success"><p><strong>Success!</strong> ' . $result['message'] . '</p></div>';
      echo '<p><a href="' . admin_url('edit.php?post_type=page') . '" class="button">View All Pages</a></p>';
    } else {
      echo '<div class="notice notice-error"><p><strong>Error:</strong> ' . $result['message'] . '</p></div>';
    }
  }
  
  // Get current pages count
  $all_pages = get_pages(array('post_status' => 'any'));
  $pages_to_keep = craftdigitally_get_pages_to_keep();
  $pages_to_delete = array();
  
  foreach ($all_pages as $page) {
    $should_keep = false;
    foreach ($pages_to_keep as $keep_title) {
      // Check if page title matches (case insensitive, partial match for variations)
      if (stripos($page->post_title, $keep_title) !== false || stripos($keep_title, $page->post_title) !== false) {
        $should_keep = true;
        break;
      }
    }
    if (!$should_keep) {
      $pages_to_delete[] = $page;
    }
  }
  
  ?>
  <div class="wrap">
    <h1>Cleanup Pages</h1>
    <p>This will delete all pages except the following 5 pages:</p>
    
    <ul style="list-style: disc; margin-left: 20px; margin-top: 10px; background: #fff; padding: 15px; border-left: 4px solid #2271b1;">
      <li><strong>Home — Front Page</strong></li>
      <li><strong>Case Studies — Elementor</strong></li>
      <li><strong>Case Study Detail</strong></li>
      <li><strong>Blog — Elementor</strong></li>
      <li><strong>Blog Detail</strong></li>
    </ul>
    
    <?php if (!empty($pages_to_delete)): ?>
      <div style="margin: 20px 0; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107;">
        <h3 style="margin-top: 0;">⚠️ Warning: The following <?php echo count($pages_to_delete); ?> page(s) will be permanently deleted:</h3>
        <ul style="list-style: disc; margin-left: 20px;">
          <?php foreach ($pages_to_delete as $page): ?>
            <li><strong><?php echo esc_html($page->post_title); ?></strong> (ID: <?php echo $page->ID; ?>, Status: <?php echo $page->post_status; ?>)</li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php else: ?>
      <div style="margin: 20px 0; padding: 15px; background: #d1e7dd; border-left: 4px solid #198754;">
        <p><strong>✓ No pages to delete.</strong> All pages match the pages to keep.</p>
      </div>
    <?php endif; ?>
    
    <form method="post" style="margin-top: 20px;" onsubmit="return confirm('Are you sure you want to delete these pages? This action cannot be undone!');">
      <?php wp_nonce_field('cleanup_pages_nonce'); ?>
      <input type="submit" name="cleanup_pages" class="button button-primary button-hero" value="Delete Extra Pages" <?php echo empty($pages_to_delete) ? 'disabled' : ''; ?>>
      <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="button">Cancel</a>
    </form>
  </div>
  <?php
}

// Get list of pages to keep
function craftdigitally_get_pages_to_keep() {
  return array(
    'Home',
    'Case Studies',
    'Case Study Detail',
    'Blog',
    'Blog Detail'
  );
}

// Function to delete extra pages
function craftdigitally_delete_extra_pages() {
  if (!current_user_can('delete_pages')) {
    return array('success' => false, 'message' => 'You do not have permission to delete pages.');
  }
  
  $pages_to_keep = craftdigitally_get_pages_to_keep();
  $all_pages = get_pages(array('post_status' => 'any'));
  $deleted_count = 0;
  $kept_count = 0;
  $errors = array();
  
  foreach ($all_pages as $page) {
    $should_keep = false;
    
    // Check if this page should be kept
    foreach ($pages_to_keep as $keep_title) {
      // Flexible matching - check if titles match (case insensitive, partial match)
      if (stripos($page->post_title, $keep_title) !== false || stripos($keep_title, $page->post_title) !== false) {
        $should_keep = true;
        break;
      }
    }
    
    if ($should_keep) {
      $kept_count++;
    } else {
      // Delete the page
      $result = wp_delete_post($page->ID, true); // true = force delete (bypass trash)
      if ($result) {
        $deleted_count++;
      } else {
        $errors[] = $page->post_title;
      }
    }
  }
  
  $message = "Deleted {$deleted_count} page(s) and kept {$kept_count} page(s).";
  if (!empty($errors)) {
    $message .= " Failed to delete: " . implode(', ', $errors);
  }
  
  return array(
    'success' => true,
    'message' => $message,
    'deleted' => $deleted_count,
    'kept' => $kept_count
  );
}

