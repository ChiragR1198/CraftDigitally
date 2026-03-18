<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
  <div class="container header-container">
    <div class="header-main">
      <!-- Logo -->
      <div class="site-logo">
        <?php
        if (has_custom_logo()) {
          the_custom_logo();
        } else {
          ?>
          <a href="<?php echo esc_url(home_url('/')); ?>">
            <span class="logo-craft">craft</span>
            <span class="logo-digitally">Digitally</span>
          </a>
          <?php
        }
        ?>
      </div>

      <!-- Navigation -->
      <nav id="primary-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'craftdigitally'); ?>">
        <?php
        if (has_nav_menu('primary')) {
          wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_id' => 'primary-menu',
            'menu_class' => 'nav-menu',
            'container' => false,
            'fallback_cb' => false,
          ));
        } else {
          // Fallback menu if no menu is assigned (links to theme page templates)
          $is_home = is_front_page() || is_home();
          $is_about = is_page('about-us');
          $is_services = is_post_type_archive('service') || is_singular('service') || is_page('services') || is_page('service');
          $is_blog = is_page('blog') || is_singular('post');
          $is_contact = is_page('contact');
          echo '<ul class="nav-menu">';
          $home_class = $is_home ? ' class="current-menu-item"' : '';
          echo '<li' . $home_class . '><a href="' . esc_url(home_url('/')) . '">Home</a></li>';
          echo '<li' . ($is_about ? ' class="current-menu-item"' : '') . '><a href="' . esc_url(home_url('/about-us')) . '">About</a></li>';
          echo '<li' . ($is_services ? ' class="current-menu-item"' : '') . '><a href="' . esc_url(home_url('/services/')) . '">Services</a></li>';
          echo '<li' . ($is_blog ? ' class="current-menu-item"' : '') . '><a href="' . esc_url(home_url('/blog')) . '">Blog</a></li>';
          echo '<li' . ($is_contact ? ' class="current-menu-item"' : '') . '><a href="' . esc_url(home_url('/contact')) . '">Contact</a></li>';
          echo '</ul>';
        }
        ?>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary header-cta">Let's Talk</a>
      </nav>

      <button class="menu-toggle" type="button" aria-label="<?php esc_attr_e('Toggle navigation', 'craftdigitally'); ?>" aria-controls="primary-navigation" aria-expanded="false">
        <span class="menu-bar"></span>
        <span class="menu-bar"></span>
        <span class="menu-bar"></span>
      </button>
    </div>
  </div>
</header>

<div class="header-spacer"></div>
