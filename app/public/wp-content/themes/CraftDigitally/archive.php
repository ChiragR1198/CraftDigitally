<?php
/**
 * The template for displaying archive pages
 *
 * @package CraftDigitally
 */

get_header();
?>

<main id="main-content" class="site-main">
  <div class="container">
    <div class="content-wrapper">
      <?php if (have_posts()) : ?>

        <header class="page-header">
          <?php
          the_archive_title('<h1 class="page-title">', '</h1>');
          the_archive_description('<div class="archive-description">', '</div>');
          ?>
        </header>

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
                the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
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
        <div class="no-results">
          <h1><?php esc_html_e('Nothing Found', 'craftdigitally'); ?></h1>
          <p><?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'craftdigitally'); ?></p>
          <?php get_search_form(); ?>
        </div>
      <?php
      endif;
      ?>
    </div>

    <?php get_sidebar(); ?>
  </div>
</main>

<?php
get_footer();

