<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package CraftDigitally
 */

// Redirect post archives to blog landing page
if (is_home() || (is_archive() && get_post_type() === 'post') || is_category() || is_tag()) {
  $blog_landing_page = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'page-templates/page-blog-landing.php',
    'number' => 1,
    'post_status' => 'publish'
  ));
  
  if (!empty($blog_landing_page)) {
    wp_redirect(get_permalink($blog_landing_page[0]->ID), 301);
    exit;
  }
}

get_header();
?>

<main id="main-content" class="site-main">
  <div class="container">
    <div class="content-wrapper">
      <?php
      if (have_posts()) :

        if (is_home() && !is_front_page()) :
          ?>
          <header class="page-header">
            <h1 class="page-title"><?php single_post_title(); ?></h1>
          </header>
        <?php
        endif;
        ?>

        <div class="posts-grid">
          <?php
          // Start the Loop
          while (have_posts()) :
            the_post();
          ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
              <?php craftdigitally_post_thumbnail(); ?>

              <header class="entry-header">
                <?php
                if (is_singular()) :
                  the_title('<h1 class="entry-title">', '</h1>');
                else :
                  the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                endif;
                ?>

                <?php if ('post' === get_post_type()) : ?>
                  <div class="entry-meta">
                    <?php
                    craftdigitally_posted_on();
                    craftdigitally_posted_by();
                    ?>
                  </div>
                <?php endif; ?>
              </header>

              <div class="entry-summary">
                <?php the_excerpt(); ?>
              </div>

              <footer class="entry-footer">
                <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-outline">
                  <?php esc_html_e('Read More', 'craftdigitally'); ?>
                </a>
              </footer>
            </article>
          <?php
          endwhile;
          ?>
        </div>

        <?php
        // Pagination
        the_posts_pagination(
          array(
            'mid_size' => 2,
            'prev_text' => __('&laquo; Previous', 'craftdigitally'),
            'next_text' => __('Next &raquo;', 'craftdigitally'),
          )
        );

      else :
        ?>
        <section class="no-results not-found">
          <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Nothing Found', 'craftdigitally'); ?></h1>
          </header>

          <div class="page-content">
            <?php
            if (is_home() && current_user_can('publish_posts')) :
              printf(
                '<p>' . wp_kses(
                  /* translators: 1: link to WP admin new post page */
                  __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'craftdigitally'),
                  array(
                    'a' => array(
                      'href' => array(),
                    ),
                  )
                ) . '</p>',
                esc_url(admin_url('post-new.php'))
              );
            elseif (is_search()) :
              ?>
              <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'craftdigitally'); ?></p>
              <?php
              get_search_form();
            else :
              ?>
              <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'craftdigitally'); ?></p>
              <?php
              get_search_form();
            endif;
            ?>
          </div>
        </section>
      <?php
      endif;
      ?>
    </div>

    <?php get_sidebar(); ?>
  </div>
</main>


<?php
get_footer();
